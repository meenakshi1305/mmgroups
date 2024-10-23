<?php {
  require 'dbconfig.php';
  //This below line is a code to Send form entries to database
  $sql = "SELECT m1.idmember,m1.name,m1.idno,m1.mobilenumber,concat(m2.name,' (',m2.idno,')') as groupleader FROM member m1 left join member m2 on m1.groupleaderid = m2.idmember;";
  try {
    $result = $con->query($sql);
    if ($result !== false && $result->num_rows > 0) {
      // output data of each row
      while ($row = $result->fetch_assoc()) {


        echo $row["idmember"], ",-," . $row["name"], ",-," . $row["idno"], ",-," . $row["mobilenumber"], ",-," . $row["groupleader"], ",_,";
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
