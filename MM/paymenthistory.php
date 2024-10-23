<?php {

require 'dbconfig.php';

    $payamount = $_POST['payamount'];
    $idmember = $_POST['memberid'];
    $actualdate = $_POST['actualdate'];

    //This below line is a code to Send form entries to database

    $sql = "INSERT INTO paymenthistory (idmember,amount,actualdate) VALUES ('$idmember','$payamount','$actualdate')";

    //fire query to save entries and check it with if statement
    try {
        $rs = mysqli_query($con, $sql);
        if ($rs) {
            http_response_code(200);
        } else {
            http_response_code(404);
        }
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }

    //connection closed.
    mysqli_close($con);
}
