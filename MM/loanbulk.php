<?php {
  require 'dbconfig.php';
  $dueamount = $_POST['dueamount'];
  $totalamount = $_POST['totalamount'];
  $occurence = $_POST['occurence'];
  $occurBy = $_POST['occurBy'];
  $actualDate = $_POST['actualDate'];
  $issueDate = $_POST['issueDate'];
  $memberStr = $_POST['members'];
  $serviceCharge=$_POST['serviceCharge'];

  $members = explode(',',$memberStr);

  for($y=0;$y<count($members);$y++){
    $idmember = $members[$y];
    $lastLoanCountStr = "";
    $totalamountTemp = $totalamount;
    $actualDateTemp = $actualDate;
    $issueDateTemp = $issueDate;


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
        $totalamountTemp = 0;
        $serviceCharge=0;
        if ($occurBy == 0) {
          $actualDateTemp = date('Y-m-d', strtotime($actualDateTemp . '+ 1 day'));
        }
        if ($occurBy == 1) {
          $actualDateTemp = date('Y-m-d', strtotime($actualDateTemp . '+ 1 week'));
        }
        if ($occurBy == 2) {
          $actualDateTemp = date('Y-m-d', strtotime($actualDateTemp . '+ 1 month'));
        }
      }
      $sql = "INSERT INTO loan (idmember,idloanmember,totalamount,dueamount,actualdate,issueDate,servicecharge) VALUES ('$idmember',$lastLoanCount,'$totalamountTemp','$dueamount','$actualDateTemp','$issueDateTemp','$serviceCharge')";
      $rs = mysqli_query($con, $sql);
      if ($rs) {
        // echo "inserted";
      } else {
        // echo "not inserted";
      }
    }
  }
}
