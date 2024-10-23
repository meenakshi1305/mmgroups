<?php
{
  require 'dbconfig.php';
  // This below line is a code to send form entries to the database
  $sql = "SELECT idmember, name, idno, mobilenumber, groupleader from  member where groupleader = 1";
  
  try {
    $result = $con->query($sql);
    if ($result !== false && $result->num_rows > 0) {
      // Output data of each row
      while ($row = $result->fetch_assoc()) {
        echo $row["idmember"], ",-," . $row["name"], ",-," . $row["idno"], ",-," . $row["mobilenumber"], ",-," . $row["groupleader"], ",_,";
      }
    } else {
      echo "0 results";
    }
  } catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
  }
  
  // Connection closed.
  mysqli_close($con);
}
?>