<?php

class DB
{
    public static $link;

    public static function init()
    {
        $params = require __DIR__ . '/config_db.php';
        self::$link = mysqli_connect($params['host'], $params['username'], $params['password'], $params['database']);
        self::$link->set_charset("utf8mb4");
        if (!self::$link) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
    }

    public static function createQuery($sql, $params = [])
    {
        $preparedStatement = self::$link->prepare($sql);
        switch (sizeof($params)) {
            case 1:
                $preparedStatement->bind_param('s', $params[0]);
                break;
            case 2:
                $preparedStatement->bind_param('ss', $params[0], $params[1]);
                break;
            case 3:
                $preparedStatement->bind_param('sss', $params[0], $params[1], $params[2]);
                break;
        }
        $preparedStatement->execute();

        return $preparedStatement;
    }

    public static function getArray($queryResult)
    {
        $result = $queryResult->get_result();
        $data = ['items' => []];
        while ($row = $result->fetch_assoc()) {
            $data['items'][] = $row;
        }
        return $data;
    }

}
