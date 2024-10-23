<?php
{
  require 'dbconfig.php';
  $groupleader = $_GET['groupleader'];
  // This below line is a code to send form entries to the database
  $sql = "SELECT idmember, name, idno, mobilenumber, aadharno from  member where groupleaderid = '$groupleader'";
  
  try {
    $result = $con->query($sql);
    if ($result !== false && $result->num_rows > 0) {
      // Output data of each row
      while ($row = $result->fetch_assoc()) {
        echo $row["idmember"], ",-," . $row["name"], ",-," . $row["idno"], ",-," . $row["mobilenumber"], ",-," . $row["aadharno"], ",_,";
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