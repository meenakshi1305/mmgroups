<?php {
  require 'dbconfig.php';
  $idmember = $_POST['idmember'];
  $dueamount = $_POST['dueamount'];
  $totalamount = $_POST['totalamount'];
  $occurence = $_POST['occurence'];
  $occurBy = $_POST['occurBy'];
  $actualDate = $_POST['actualDate'];
  $issueDate = $_POST['issueDate'];
  $servicecharge = $_POST['servicecharge'];
  $lastLoanCountStr = "";


  $sqlCountIdLoanMember = "select count(distinct(idloanmember)) as loanCount from loan where idmember = '$idmember'";
  $result = $con->query($sqlCountIdLoanMember);
  if ($result !== false && $result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
      $lastLoanCountStr =   $row["loanCount"];
    }
  }
  $one = 1;
  $lastLoanCount = (int)$lastLoanCountStr + $one;
  echo $lastLoanCount;

  for ($x = 1; $x <= $occurence; $x++) {
    if ($x != 1) {
      $totalamount = 0;
      $servicecharge = 0;
      if ($occurBy == 0) {
        $actualDate = date('Y-m-d', strtotime($actualDate . '+ 1 day'));
      }
      if ($occurBy == 1) {
        $actualDate = date('Y-m-d', strtotime($actualDate . '+ 1 week'));
      }
      if ($occurBy == 2) {
        $actualDate = date('Y-m-d', strtotime($actualDate . '+ 1 month'));
      }
    }
    $sql = "INSERT INTO loan (idmember,idloanmember,totalamount,dueamount,actualdate,issueDate,servicecharge) VALUES ('$idmember',$lastLoanCount,'$totalamount','$dueamount','$actualDate','$issueDate','$servicecharge')";
    $rs = mysqli_query($con, $sql);
    if ($rs) {
      // echo "inserted";
    } else {
      // echo "not inserted";
    }
  }
}
