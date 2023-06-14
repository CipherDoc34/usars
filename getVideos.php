<?php
include "config.php";

function GetBounds($coordArray){
    $numOfCoords = count($coordArray);
    $x1 = $coordArray[0][0];
    $x2 = $coordArray[0][0];
    $y1 = $coordArray[0][1];
    $y2 = $coordArray[0][1];
    for($coord = 0; $coord < $numOfCoords; $coord++){
        $x = $coordArray[$coord][0];
        $y = $coordArray[$coord][1];
        if($x < $x1) $x1 = $x;
        if($x > $x2) $x2 = $x;
        if($y < $y1) $y1 = $y;
        if($y > $y2) $y2 = $y;
    }
    return array($x1, $x2, $y1, $y2);
}

$args = json_decode($_GET["args"], true);
$numvid = null;
$returnType = null;

if(array_key_exists("numvid", $args))
    $numvid = $args["numvid"];

if(array_key_exists("return", $args))
    $returnType = $args["return"];

$locationBounds = GetBounds($args["location"]);


// print_r("DateLow: " . $args["datetime"][0] . "<br></br>");
// print_r("DateHigh: " . $args["datetime"][1]. "<br></br>");

// for($numOfCoords = 0; $numOfCoords < count($locationBounds); $numOfCoords++){
//     print_r($numOfCoords.": ");
//     print_r($locationBounds[$numOfCoords]);
//     print_r("<br></br>");
// }

// print_r("Number of Videos: " . $numvid . "<br></br>");
// print_r("Return Type: " . $returnType);

$qu = $connection->prepare(
    "SELECT DISTINCT filePath FROM videos v RIGHT JOIN geo_location g
    ON v.id = g.videoID
    WHERE v.date >= :datelo AND v.date <= :datehi AND
    g.latitude >= :x1 AND
    g.latitude <= :x2 AND 
    g.longitude >= :y1 AND
    g.longitude <= :y2"
);

$qu->bindParam(":datelo", $args["datetime"][0]);
$qu->bindParam(":datehi", $args["datetime"][1]);
$qu->bindParam(":x1", $locationBounds[0]);
$qu->bindParam(":x2", $locationBounds[1]);
$qu->bindParam(":y1", $locationBounds[2]);
$qu->bindParam(":y2", $locationBounds[3]);

$qu->execute();
header('HTTP/1.1 200 OK');
echo json_encode($qu->fetchAll(PDO::FETCH_ASSOC));

?>