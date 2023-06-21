<?php
function getGoogleMapsAPIKEY(){
    include "config.php";
    $q = $connection->prepare("SELECT `key` from api_keys where service = 'googleMaps'");
    $q->execute();
    $googleJson = json_encode($q->fetchAll(PDO::FETCH_ASSOC));
    return $googleJson;
}
?>
<?php
if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) echo getGoogleMapsAPIKEY();  
?>