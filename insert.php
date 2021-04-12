<?php


$link = mysqli_connect("127.0.0.1", "pilottow_api", "w$=t&rTxFKH$", "pilottow_longhin_corrente");
$link->set_charset("utf8mb4");

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}

if (($data = $_GET['value']) && ($tag = $_GET['tag'])) {

    $stmt = $link->prepare("INSERT INTO `dati` (`id`, `value`, `date`, `tag`) VALUES (NULL, ?, CURRENT_TIMESTAMP, ?);    ");
    $stmt->bind_param('ss', $data, $tag);
    $stmt->execute();

    header('Content-Type: application/json');

    echo json_encode(['status' => 'ok']);
}
