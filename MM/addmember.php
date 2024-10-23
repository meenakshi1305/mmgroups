<?php 

  require 'dbconfig.php';

{
    $name = $_POST['name'];
    $aadharno = $_POST['aadharno'];
    $nomineename = $_POST['nomineename'];
    $nomineeaadharno = $_POST['nomineeaadharno'];
    $mobileno = $_POST['mobilenumber'];
    $passwordInput = $_POST['passwordInput'];
    $address = $_POST['address'];
    $bankdetails = $_POST['bankdetails'];
    $groupleader = $_POST['groupleader'];
    $groupleaderid = $_POST['groupleaderid'];
    $showAlert = $_POST['showAlert'];
    $doj = $_POST['doj'];
    if($groupleader==1){
        $groupleaderid = null;
    }

    $checkaano = "select * from member where aadharno = '$aadharno'";
    $result = $con->query($checkaano);

    if ($result->num_rows > 0 && $showAlert=='undefined') {
        http_response_code(408); 
        echo "Aadhar number already exists";
        exit;
    }
    else{
    $lastidno = 0 ;
    $idno = "";
    
    $sqlIdno = "select idno from member order by createdon desc limit 1";
    
    try  {
      $result = $con->query($sqlIdno);
      if ($result !== false && $result->num_rows > 0) {
      // output data of each row
      while ($row = $result->fetch_assoc()) {
        $lastidno =   $row["idmember"];
      }
    }
    $idno = $lastidno +1;
  } catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
  }
        }
      } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
      }

    //This below line is a code to Send form entries to database

    $sql = "INSERT INTO member (name,idno,aadharno,nomineename,nomineeaadharno,mobilenumber,password,address,bankdetails,groupleader,groupleaderid,doj) VALUES ('$name','$idno','$aadharno','$nomineename','$nomineeaadharno','$mobileno','$passwordInput','$address','$bankdetails','$groupleader','$groupleaderid','$doj')";

    //fire query to save entries and check it with if statement
    $rs = mysqli_query($con, $sql);
    if ($rs) {
        $insertedId =  mysqli_insert_id($con);
        $zero = 0;
        echo $insertedId;
        $sql1 = "INSERT INTO user (username,password,isadmin,idmember) VALUES ('$idno','$passwordInput','$zero','$insertedId')";
        $rs = mysqli_query($con, $sql1);
        http_response_code(200);
    } else {
        http_response_code(404);
    }

    //connection closed.
    mysqli_close($con);
?>