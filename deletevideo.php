<?php
include("config.php");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
  <body>
    <div>
     <?php
     if(isset($_POST['delete'])){
        echo "deleting ". $_POST['id'];
        $fetchPath = $connection->prepare("Select filePath from videos where id='". $_POST['id']."'");
        $fetchPath->execute();
        $path = $fetchPath->fetch();
        $q = "delete from videos where id='".$_POST['id']."'";
        echo "<script>console.log(".$path['filePath']." ".$q.")</script>";
        $delete = $connection->prepare($q);
        $delete->execute();
        unlink($path['filePath']);
        header('location: deletevideo.php');
        exit;
     }
     $fetchVideos = $connection->prepare("SELECT * FROM videos ORDER BY id DESC");
     $fetchVideos->execute();
     while($row = $fetchVideos->fetch()){
       $path = $row['filePath'];
       $name = $row['name'];
       $pathtoFile = '"'.'"';
       $toDelete = '"'.$row['id'].'"';
       echo "<div style='float: left; margin-right: 5px;'>
          <video src='".$path."' controls width='320px' height='320px' ></video>     
          <br>
          <span>".$name."</span>
          <button type='button' onclick='openModal(".$toDelete.",".json_encode($path).")'>Delete</button>
       </div>";
     }
     ?>
 
    </div>
    <div class="container">
  <div class="modal fade" id="ConfirmDelete" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Are you sure you want to delete?</h4>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          
        </div>
      </div>
      
    </div>
  </div>
  <script>
    function openModal(name, path){
      //console.log(name);
      //console.log(path);
      let modalbody = "<form method='post' action='' enctype='multipart/form-data' id='FormDelete'><input type='hidden' value='"
                        + name +"' name='id'></form>" + 
                        "<video src='"+path+"' controls width='320px' height='320px' ></video>"
      $("#ConfirmDelete .modal-body").html(modalbody);
      $("#ConfirmDelete .modal-footer").html("<input type='submit' style='background-color:red;color:white;' value='Delete' id="+name+" name='delete' "+
                                              "form='FormDelete' class='btn btn-default'><button type='button' class='btn btn-default' data-dismiss='modal'>Cancel</button>");
      $("#ConfirmDelete").modal()
    }
  </script>
</div>
  </body>
</html>