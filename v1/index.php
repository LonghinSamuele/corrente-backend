<?php
require '../support/Db.php';
require '../support/Response.php';
DB::init();
$request = $_REQUEST['q'];
$url_params = array_values(array_filter(explode('/', $request)));
$get_params = $_GET;
unset($get_params['q']);
$params = [];
if (sizeof($url_params) > 1) {
    switch ($url_params[1]) {
        case 'today':
            $sql = "SELECT DATE(`date`) as 'label', `value` FROM `dati`";
            $sql .= " WHERE DATE(`date`) = DATE(NOW()) ";
            if (sizeof($url_params) > 2)
                $sql .= "AND id >" . $url_params[2];
            $sql .= " ORDER BY `date` ";
            break;
        case 'day':
            $sql = "SELECT DATE(`date`) as 'label', `value`  FROM `dati` WHERE DATE(`date`) = DATE(";
            if (sizeof($url_params) > 2) {
                $params[] = $url_params[2];
                $sql .= "?";
            } else
                $sql .= " NOW() ";
            $sql .= ") ORDER BY `date` ";
            var_dump($sql);
            die();
            break;
        case 'day-span':
            $sub_query = "SELECT `date`, `value` FROM `dati` where ( DATE (`date`) >= DATE(?) and DATE(`date`) <= DATE(?)) ORDER BY `date` ";
//            $sub_query = "SELECT `date`, `value` FROM `dati` where ( DATE (`date`) >= DATE('2021-4-1') and DATE(`date`) <= DATE('2021-4-10')) ORDER BY `date` ";
            $sql = "SELECT AVG(`value`) AS 'value', concat( DATE(`date`) , ' : ',    HOUR(`date`)) as `label` FROM (" . $sub_query . ") AS sq GROUP BY concat( DATE(`date`) , ' : ',    HOUR(`date`))";
            if (isset($get_params['sd']) && isset($get_params['ed'])) {
                $params[] = $get_params['sd'];
                $params[] = $get_params['ed'];
            }
//            echo($sql);
//            die();
            break;
        case 'month':
            $sql = "SELECT DATE(`date`) AS 'label', AVG(`value`) AS 'value' FROM dati WHERE month(`date`) = month(";
            if (sizeof($url_params) > 2) {
                $params[] = $url_params[2];
                $sql .= "?";
            } else
                $sql .= "NOW()";
            $sql .= ") GROUP BY DATE(`date`) ORDER BY DATE(`date`) ";
            break;
        case 'week':
            $sql = "SELECT DAY(`date`) AS 'label', AVG(`value`) AS 'value' FROM dati WHERE weekofyear(`date`) = weekofyear(";
            if (sizeof($url_params) > 2)
                $sql .= "'" . $url_params[2] . "'";
            else
                $sql .= "NOW()";
            $sql .= ") GROUP BY DAY(`date`) ORDER BY DAY(`date`) ";
            break;
        case 'all':
            $sql = "SELECT DATE(`date`) as 'label', `value` as 'value' FROM `dati` ORDER BY `date` ";
            break;
        default:
            $sql = "SELECT DATE(`date`) as 'label', `value` as 'value' FROM dati LIMIT 10 ORDER BY `date` ";
            break;
    }
    Response::json(DB::getArray(DB::createQuery($sql, $params)));
}



