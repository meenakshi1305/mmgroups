<?php {

require 'dbconfig.php';

  //This below line is a code to Send form entries to database
  $sql = "SELECT idmember,name,idno FROM member where groupleader = 1";

  try {
    $result = $con->query($sql);
    if ($result !== false && $result->num_rows > 0) {
      // output data of each row
      while ($row = $result->fetch_assoc()) {
        echo  $row["idmember"], ",-," . $row["name"], ",-," . $row["idno"], ",_,";
      }
    } else {
      echo "0 results";
    }
  } catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
  }
  //connection closed.
  mysqli_close($con);
}