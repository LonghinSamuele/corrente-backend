<?php
require '../../support/Db.php';
require '../../support/Response.php';
DB::init();
$request = $_REQUEST['q'];
$url_params = array_values(array_filter(explode('/', $request)));
$get_params = $_GET;
unset($get_params['q']);
$params = [];

$sql = "SELECT DATE(`date`) as 'label', `value`  FROM `dati` WHERE DATE(`date`) = DATE(";
if (sizeof($url_params) > 2) {
    $params[] = $url_params[2];
    $sql .= "?";
} else
    $sql .= " NOW() ";
$sql .= ") ORDER BY `date` ";
//var_dump($params);die();
Response::json(getArray(DB::createQuery($sql, $params)));

function getArray($queryResult)
{
    $result = $queryResult->get_result();
    $data = ['items' => []];
    while ($row = $result->fetch_assoc()) {
        $data['items'][] = $row;
    }
    return $data;
}



