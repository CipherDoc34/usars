<?php
$host = "localhost"; /* Host name */
$user = "root"; /* User */
$password = getenv('USARRootPass'); /* Password */
$dbname = "usar"; /* Database name */
try {
    $connection = new PDO('mysql:host='.$host.';dbname='.$dbname, $user, $password);
    //echo $connection->getAttribute(PDO::ATTR_CONNECTION_STATUS);
} catch (PDOException $e) {
    header('HTTP/1.1 500 INTERNAL SERVER ERROR');
    echo $e->getMessage();
    die();
}
?>