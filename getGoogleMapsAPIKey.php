<?php
function getGoogleMapsAPIKEY(){
    include "config.php";
    $q = $connection->prepare("SELECT `key` from API_KEYS where service = 'googleMaps'");
    $q->execute();
    $googleJson = json_encode($q->fetchAll(PDO::FETCH_ASSOC));
    return $googleJson;
}
?>