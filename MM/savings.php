<?php

require 'dbconfig.php';

$idmember = $_POST['idmember'];
$amount = $_POST['amount'];
$savingdate = $_POST['savingdate'];

//This below line is a code to Send form entries to database

$sql1 = "INSERT INTO savings (idmember,amount,savingdate) VALUES ('$idmember','$amount','$savingdate')";

//fire query to save entries and check it with if statement
$rs = mysqli_query($con, $sql1);

$sql = "SELECT amount,savingdate,idsavings FROM savings where idmember= '$idmember' order by savingdate desc";

try {
  $result = $con->query($sql);
  if ($result !== false && $result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
      echo  $row["amount"], ",-," . $row["savingdate"], ",-," . $row["idsavings"], ",_,";
    }
  } else {
    echo "0 results";
  }
} catch (Exception $e) {
  echo 'Message: ' . $e->getMessage();
}

//connection closed.
mysqli_close($con);
