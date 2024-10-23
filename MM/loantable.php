<?php
require 'dbconfig.php'; {
  $idmemberin = $_GET['idmember'];
  $idloanmemberin = $_GET['idloanmember'];
  $completedin = $_GET['completed'];

  //This below line is a code to Send form entries to database
  $sql = "SELECT idloan,totalamount,actualdate,dueamount,paymentdate,issuedate,penality,(select sum(dueamount) from loan where paymentdate is null and idmember = '$idmemberin' and idloanmember = '$idloanmemberin' and completed = '$completedin' and deleted = 0) as balanceAmount FROM loan where idmember = '$idmemberin' and idloanmember = '$idloanmemberin' and completed = '$completedin' and deleted = 0";
  try {
    $result = $con->query($sql);
    if ($result !== false && $result->num_rows > 0) {
      // output data of each row
      while ($row = $result->fetch_assoc()) {
        echo $row["idloan"], ",-," . $row["totalamount"], ",-," . $row["actualdate"], ",-," . $row["dueamount"], ",-," . $row["paymentdate"], ",-," . $row["issuedate"], ",-," . $row["penality"], ",-," . $row["balanceAmount"], ",_,";
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
