<?php {
    require 'dbconfig.php';

    $idloanmember = $_GET['idloanmember'];
    $idmember = $_GET['idmember'];
    $one = 1;
        $sql = "update loan set completed = '$one' where idloanmember= '$idloanmember'AND idmember='$idmember' ";
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
