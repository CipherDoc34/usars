<?php
include("config.php");
include("getGoogleMapsAPIKey.php");
?>
<!doctype html>
<html>
  <body>
    <div>
     <?php
     $fetchVideos = $connection->prepare("SELECT * FROM videos ORDER BY id DESC");
     $fetchVideos->execute();
     $googleAPIKey = json_decode(getGoogleMapsAPIKEY(), true);
     while($row = $fetchVideos->fetch()){
       $path = $row['filePath'];
       $name = $row['name'];
       $location = "'http://localhost/usars/map.html?video=". $row['id'] ."&key=".$googleAPIKey[0]['key']."', '_blank'";
       echo "<div style='float: left; margin-right: 5px;'>
          <video src='".$path."' controls width='320px' height='180px' ></video>     
          <br>
          <span>".$name."</span>
          <button type='button' onclick=\"window.open(".$location.")\">Map</button>
       </div>";
     }
     ?>
  </body>
</html>