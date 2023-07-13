<?php
function GetBounds($coordArray)
{
    $numOfCoords = count($coordArray);
    $x1 = $coordArray[0][0];
    $x2 = $coordArray[0][0];
    $y1 = $coordArray[0][1];
    $y2 = $coordArray[0][1];
    for($coord = 0; $coord < $numOfCoords; $coord++)
    {
        $x = $coordArray[$coord][0];
        $y = $coordArray[$coord][1];
        if($x < $x1) $x1 = $x;
        if($x > $x2) $x2 = $x;
        if($y < $y1) $y1 = $y;
        if($y > $y2) $y2 = $y;
    }
    return array($x1, $x2, $y1, $y2);
}

function GetFileVideo($time, $location, &$ret)
{
    include "config.php";
    try
    {
        $qu = $connection->prepare(
            "SELECT DISTINCT v.id, filePath FROM videos v RIGHT JOIN geo_location g
            ON v.id = g.videoID
            WHERE v.date >= :datelo AND v.date <= :datehi AND
            g.latitude >= :x1 AND
            g.latitude <= :x2 AND 
            g.longitude >= :y1 AND
            g.longitude <= :y2;"
        );
        
        $timelo = $time[0];
        $timehi = $time[1];
        if ($timelo/1000000000000 < 1)
            $timelo *= 1000;
        if ($timehi/1000000000000 < 1)
            $timehi *= 1000;

        $qu->bindParam(":datelo", $timelo);
        $qu->bindParam(":datehi", $timehi);
        $qu->bindParam(":x1", $location[0]);
        $qu->bindParam(":x2", $location[1]);
        $qu->bindParam(":y1", $location[2]);
        $qu->bindParam(":y2", $location[3]);
        $qu->execute();
        $ret = $qu->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $e)
    {
        return $e;
    }
    return false;
}

function GetGeoCoords($videos, &$ret)
{
    include "config.php";
    $ret = $videos;
    try
    {
        $qu = $connection->prepare(
            "SELECT `timestamp`, `longitude`, `latitude` from geo_location where videoID= :vid;"
        );
        for($i = 0; $i < count($videos); $i++)
        {
            $qu->bindParam(":vid", $videos[$i]['id']);
            $qu->execute();
            $ret[$i]["geo"] = $qu->fetchAll(PDO::FETCH_OBJ);
        }
    }
    catch(PDOException $e) 
    {
        return $e;
    }
    return false;
}

function GetVideoData($videos)
{
    $ret = $videos;
    for($i = 0; $i < count($videos); $i++)
    {
        $ret[$i]["data"] = readfile($videos[$i]["filePath"]);
    }
    return $ret;
}

$args = json_decode($_GET["args"], true);

$numvid = null;
$returnType = null;
$GeoCoords = null;
if(array_key_exists("numvid", $args)) $numvid = $args["numvid"];
if(array_key_exists("return", $args)) $returnType = $args["return"];
if(array_key_exists("geocoords", $args)) $GeoCoords = $args["geocoords"];

$locationBounds = GetBounds($args["location"]);

if($e = GetFileVideo($args["datetime"], $locationBounds, $videos)) 
{
    header('HTTP/1.1 500 SERVER ERROR');
    print_r(json_encode($e));
    die();
}

if($GeoCoords == null || $GeoCoords == "True")
{
    if ($e = GetGeoCoords($videos, $ret))
    {
        header('HTTP/1.1 500 SERVER ERROR');
        print_r(json_encode($e));
        die();
    }
}
else $ret = $videos;

if(!$returnType)
{
    header('HTTP/1.1 200 OK');
    print_r(json_encode($ret));
    die();
}
else if ($returnType == 1)
{
    header('HTTP/1.1 200 OK');
    print_r(json_encode(GetVideoData($ret)));
    die();
}
else if ($returnType == 2)
{
    header('HTTP/1.1 404 NOT AVAILABLE');
    echo "2";
    die();
}
?>