<?php 
  require 'dbconfig.php';
  {
  $idmemberin = $_GET['idmember'];
  $completedin = $_GET['completed'];


  //This below line is a code to Send form entries to database
  $sql = "select DISTINCT idloanmember as idloanmember from loan where idmember = '$idmemberin' and completed = '$completedin' and deleted = 0";

  try {
    $result = $con->query($sql);
    if ($result !== false && $result->num_rows > 0) {
      // output data of each row
      while ($row = $result->fetch_assoc()) {
        echo $row["idloanmember"], ",";
      }
    } else {
      echo "0,";
    }
  } catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
  }
  //connection closed.
  mysqli_close($con);
}