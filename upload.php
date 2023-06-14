<?php
    include "config.php";
    //////////// TEMP //////////
    //$loc = "params->location";

    header("Content-Type: application/json; charset=UTF-8");
    function warning_handler($errno, $errstr) { 
        header('HTTP/1.1 500 FILE NOT FOUND');
        echo $errstr;
        die();
    }
    function getMeta($path){
        $output = null;
        $retval = null;
        $command = "exif.exe -j -ee -n " . $path;
        exec($command, $output, $retval);
    
        $json = "";
        for($x = 0; $x < count($output);$x++){
            $json = $json . $output[$x];
        }
        return $json;
    }
    function getGeoMeta($path){
        $output = null;
        $retval = null;
        $command = "exif.exe -p json.fmt -ee " . $path;
        exec($command, $output, $retval);
    
        $json = "";
        for($x = 0; $x < count($output);$x++){
            $json = $json . $output[$x];
        }
        return $json;
    }
    function FileError($path, $error){
        header('HTTP/1.1 500 INTERNAL SERVER ERROR');
        echo $error;
        if($path) unlink($path);
        die();
    }

    function insertGeoJson($json, $vidID, $connection){
        $q = "INSERT into `geo_location` (videoID, timestamp, longitude, latitude) 
            values (:vidid, :time, :long, :lat)";
        $qu = $connection->prepare($q);
        for($i = 1; $i < count($json); $i++){
            $qu->bindParam(":vidid", $vidID);
            $qu->bindParam(":lat", $json[$i]['Latitude']);
            $qu->bindParam(":long", $json[$i]['Longitude']);
            $qu->bindParam(":time", $json[$i]['TimeStamp']);
            $qu->execute();
        }
        if ($i == 1) return 0; 
        
        echo " Geo Added";
        return 1;
    }

    $paramsString = $_GET['metadata'];
    $params = json_decode($paramsString);
    $temporaryPath = __DIR__ . DIRECTORY_SEPARATOR . "temp" . DIRECTORY_SEPARATOR . $params->name;
    $path = pathinfo($temporaryPath);
    $jsonMeta = getMeta($temporaryPath);
    $jsonGeoMeta = getGeoMeta($temporaryPath);

    $jsonParseMeta = json_decode($jsonMeta, true)[0];
    $jsonParseMetaKeys = array_keys($jsonParseMeta);
    if(array_key_exists("GPSLatitude", $jsonParseMeta) && array_key_exists("GPSLongitude", $jsonParseMeta)){
        $loclo = $jsonParseMeta["GPSLongitude"];
        $locla = $jsonParseMeta["GPSLatitude"];
    } else{
        $loclo = null;
        $locla = null;
    }
    $creator = "exif";

    set_error_handler("warning_handler", E_WARNING);
    $sha = hash_file("sha256", $temporaryPath);
    restore_error_handler();
    
    $q = $connection->prepare('Select id from videos where :hash = videos.id');
    $q->bindParam(":hash", $sha);
    $q->execute();
    if(sizeof($q->fetchAll())){
        header('HTTP/1.1 500 INTERNAL SERVER ERROR');
        unlink($temporaryPath);
        echo "Video Already Exists";
        die();
    }
    $folderPath = __DIR__ . DIRECTORY_SEPARATOR . "videos";
    if (!file_exists($folderPath)) { if (!mkdir($folderPath, 0777, true)) {
        header('HTTP/1.1 500 INTERNAL SERVER ERROR');
        echo "ERROR";
        die();
    }}
    $VideoPath = $folderPath . DIRECTORY_SEPARATOR . $params->name;
    rename($temporaryPath, $VideoPath);
    $relativeVideoPath = "videos". DIRECTORY_SEPARATOR . $params->name;
try{
    if(!insertGeoJson(json_decode($jsonGeoMeta, true), $sha, $connection)) FileError($relativeVideoPath, "No GEO Data, File Removed");
    $q = "INSERT INTO videos
    (id, date, longitude, latitude, filePath, jsonMeta, name, ext, videoMeta, creator) 
    VALUES(:id, :date, :long, :lat, :path, :json, :name, :ext, :videoMeta, :creator)";

    $qu = $connection->prepare($q);

    $qu->bindParam(':id', $sha);
    $qu->bindParam(':ext', $path['extension']);
    $qu->bindParam(':name', $params->name);
    $qu->bindParam(':date', $params->lastModified);
    $qu->bindParam(':long', $loclo);
    $qu->bindParam(':lat', $locla);
    $qu->bindParam(':path', $relativeVideoPath);
    $qu->bindParam(':json', $jsonMeta);
    $qu->bindParam(':videoMeta', $paramsString);
    $qu->bindParam(':creator', $creator);
    $qu->execute(); 
    header('HTTP/1.1 200 OK');
    echo "Added";
}
catch(PDOException $e){
    header('HTTP/1.1 500 INTERNAL SERVER ERROR');
    //echo "Something went wrong " . $e->getMessage();
    FileError($relativeVideoPath, "Something went wrong " . $e->getMessage());
    die();
}
?>