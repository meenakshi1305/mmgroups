<?php {
  require 'dbconfig.php';
  $updateDue = $_POST['updateDue'];
  $updateUpdateDate = $_POST['updateUpdateDate'];
  $updateActualDate = $_POST['updateActualDate'];
  $memberStr = $_POST['members'];

  $members = explode(',',$memberStr);

  for($y=0;$y<count($members);$y++){
    $idmember = $members[$y];
    $sql = "update loan set paymentdate = '$updateUpdateDate' where idmember = '$idmember' and actualdate = '$updateActualDate' and dueamount = '$updateDue' and completed = 0;";
    $rs = mysqli_query($con, $sql);
    if ($rs) {
        echo mysqli_insert_id($con);
        http_response_code(200);
    } else {
        http_response_code(404);
    }

    
    
  }
    //connection closed.
    mysqli_close($con);
}
