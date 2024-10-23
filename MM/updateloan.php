<?php {
    require 'dbconfig.php';

    $idloan = $_POST['idloan'];
    $dueamount = $_POST['dueamount'];
    $paymentdate = $_POST['paymentdate'];
    $penality = $_POST['penality'];
    $idmember = $_POST['idmember'];
    $idloanmember = $_POST['idloanmember'];
    if(empty($paymentdate)){
        $paymentdate = null;
    }

    $sql = "update loan set  dueamount = '$dueamount' , paymentdate = '$paymentdate'  , penality='$penality', updatedon = now() where idloan = '$idloan'";

    //fire query to save entries and check it with if statement
    $rs = mysqli_query($con, $sql);
    if ($rs) {
        echo mysqli_insert_id($con);
        http_response_code(200);
    } else {
        http_response_code(404);
    }

    //connection closed.
    mysqli_close($con);
}
