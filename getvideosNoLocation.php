<?php
include "config.php";

$args = json_decode($_GET["args"], true);

$qu = $connection->prepare(
    "SELECT DISTINCT filePath FROM videos v
    WHERE v.date >= :datelo AND v.date <= :datehi"
);

$qu->bindParam(":datelo", $args["datetime"][0]);
$qu->bindParam(":datehi", $args["datetime"][1]);

$qu->execute();
header('HTTP/1.1 200 OK');
echo json_encode($qu->fetchAll(PDO::FETCH_ASSOC));
?>