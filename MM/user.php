<?php

require 'dbconfig.php'; {
  $username = $_POST['username'];
  $passwordInput = $_POST['passwordInput'];
  $sql = "SELECT iduser,isadmin FROM user where username= '$username' and password='$passwordInput'";
  $result = $con->query($sql);
  if ($result !== false && $result->num_rows > 0) {
    $randomStr = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'), 0, 50);
    $token = '';
    $isadmin = 0;
    $iduser = 0;
    while ($row = $result->fetch_assoc()) {
      $isadmin =  $row["isadmin"];
      $iduser = $row["iduser"];
    }
    if ($isadmin == 1) {
      $token = 'ad' . $randomStr;
    } else if ($isadmin == 2) {
      $token = 'mg' . $randomStr;
    } else {
      $token = 'us' . $randomStr;
    }
    $sql1 = "INSERT INTO logintrack (iduser,token) VALUES ('$iduser','$token')";
    $rs = mysqli_query($con, $sql1);
    echo $token;
  } else {
    echo 0;
  }
  mysqli_close($con);
}
