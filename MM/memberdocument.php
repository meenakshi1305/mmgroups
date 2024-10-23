<?php

require 'dbconfig.php';

if (count($_FILES) > 0) {
    if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
        $imgData = file_get_contents($_FILES['userImage']['tmp_name']);
        $imgType = $_FILES['userImage']['type'];
        $idmember = $_POST['idmember'];
        $filename = $_POST['filename'];
        $avatar = $_POST['isavatar'];
        $sql = "INSERT INTO memberdocument(idmember ,document,filename,isavatar) VALUES(?,?, ?,?)";
        $statement = $con->prepare($sql);
        $statement->bind_param('sssi',$idmember, $imgData, $filename,$avatar);
        $current_id = $statement->execute() or die("<b>Error:</b> Problem on Image Insert<br/>" . mysqli_connect_error());
    }
}
?>