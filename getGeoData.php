<?php
include "config.php";

$video = $_GET["args"];

$q = "SELECT latitude lat, longitude lng from geo_location where videoID = :vidid";
$qu = $connection->prepare($q);
$qu->bindParam(":vidid", $video);
$qu->execute();
//$results = $qu->fetchAll(PDO::FETCH_ASSOC);
$results = $qu->fetchAll(PDO::FETCH_OBJ);

echo json_encode($results);

?>