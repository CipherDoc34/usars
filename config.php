<?php
$environment = 'RAILWAY';

if(true){
    $host = "containers-us-west-158.railway.app"; /* Host name */
    $user = "root"; /* User */
    $password = getenv('USARSRootPassRailway'); /* Password */
    $dbname = "railway"; /* Database name */
    $port = "6906";
    $mysqlurl = 'mysql:host='.$host.';dbname='.$dbname.';port='.$port;
} else {
    $host = "localhost"; /* Host name */
    $user = "root"; /* User */
    $password = getenv('USARRootPass'); /* Password */
    $dbname = "usar"; /* Database name */
    $port = "3306";
    $mysqlurl = 'mysql:host='.$host.';dbname='.$dbname.';port='.$port;
}
try {
    $connection = new PDO($mysqlurl, $user, $password);
    //echo getenv('USARRootPass');
    //echo $connection->getAttribute(PDO::ATTR_CONNECTION_STATUS);
} catch (PDOException $e) {
    header('HTTP/1.1 500 INTERNAL SERVER ERROR');
    echo $e->getMessage();
    die();
}
?>