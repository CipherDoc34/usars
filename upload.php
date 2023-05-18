<?php
    include "config.php";
    //////////// TEMP //////////
    $loc = "params->location";

    header("Content-Type: application/json; charset=UTF-8");
    function warning_handler($errno, $errstr) { 
        header('HTTP/1.1 500 FILE NOT FOUND');
        echo $errstr;
        die();
    }
    function getMeta($path){
        $output = null;
        $retval = null;
        $command = "exif.exe -j -ee " . $path;
        exec($command, $output, $retval);
    
        $json = "";
        for($x = 0; $x < count($output);$x++){
            $json = $json . $output[$x];
        }
        return $json;
    }

    $params = json_decode($_GET['metadata']);
    $temporaryPath = __DIR__ . DIRECTORY_SEPARATOR . "temp" . DIRECTORY_SEPARATOR . $params->name;
    $path = pathinfo($temporaryPath);
    $jsonMeta = getMeta($temporaryPath);
    //echo $temporaryPath;
    //print_r($path);
    //print_r($jsonMeta);
    $jsonParseMeta = json_decode($jsonMeta, true)[0];
    $jsonParseMetaKeys = array_keys($jsonParseMeta);
    if(array_key_exists("FileCreateDate", $jsonParseMeta)){
        $date = $jsonParseMeta["FileCreateDate"];
    } else{
        $date = null;
    }
    
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
    $q = "INSERT INTO videos(id, date, location, filePath, jsonMeta, name, ext) VALUES(:id,:date,:loc,:path, :json, :name, :ext)";
    $qu = $connection->prepare($q);

    $qu->bindParam(':id', $sha);
    $qu->bindParam(':ext', $path['extension']);
    $qu->bindParam(':name', $params->name);
    $qu->bindParam(':date', $date);
    $qu->bindParam(':loc', $loc);
    $qu->bindParam(':path', $relativeVideoPath);
    $qu->bindParam(':json', $jsonMeta);
    $qu->execute(); 
    header('HTTP/1.1 200 OK');
    echo "Added";
}
catch(PDOException $e){
    header('HTTP/1.1 500 INTERNAL SERVER ERROR');
    echo "Something went wrong " . $e->getMessage() . " " . $jsonMeta;
    die();
}
?>