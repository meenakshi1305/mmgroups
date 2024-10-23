<?php
require 'dbconfig.php'; {
  $idmemberin = $_POST['idmember'];
  $idloanmemberin = $_POST['idloanmember'];

  //This below line is a code to Send form entries to database
  $sql = "update loan set deleted = 1 where idmember = '$idmemberin' and idloanmember = '$idloanmemberin'";
  try {
    $result = $con->query($sql);
  } catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
  }
  //connection closed.
  mysqli_close($con);
}