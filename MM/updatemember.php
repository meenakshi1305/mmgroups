<?php {
    require 'dbconfig.php';

    $idmember = $_POST['idmember'];
    $name = $_POST['name'];
    $aadharno = $_POST['aadharno'];
    $mobileno = $_POST['mobilenumber'];
    $address = $_POST['address'];
    $bankdetails = $_POST['bankdetails'];
    $groupleader = $_POST['groupleader'];
    $groupleaderid = $_POST['groupleaderid'];
    $nomineename = $_POST['nomineename'];
    $nomineeaadharno = $_POST['nomineeaadharno'];
    $doj = $_POST['doj'];

    $sql = "update member set name = '$name' , aadharno = '$aadharno' , mobilenumber = '$mobileno' , address = '$address' , bankdetails = '$bankdetails' , groupleader = '$groupleader',updatedon = now(),groupleaderid= '$groupleaderid', doj = '$doj', nomineename = '$nomineename',nomineeaadharno = '$nomineeaadharno' where idmember = '$idmember'";

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
