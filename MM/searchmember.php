<?php {


  require 'dbconfig.php';
  $inputSearch = $_POST['inputSearch'];

  //This below line is a code to Send form entries to database
  $sql = "SELECT idmember,name,idno,aadharno,mobilenumber,password,address,bankdetails,groupleader,doj,groupleaderid,nomineename,nomineeaadharno FROM member where idno= '$inputSearch' or aadharno='$inputSearch' or mobilenumber='$inputSearch'";

  try {
    $result = $con->query($sql);
    if ($result !== false && $result->num_rows > 0) {
      // output data of each row
      while ($row = $result->fetch_assoc()) {
        $groupleaderId = $row['groupleaderid'];
        $sql1 = "select idno,name from member where idmember='$groupleaderId'";
        $rs = $con->query($sql1);
        $groupleadername = '';
        while ($r = $rs->fetch_assoc()) {
          $groupleadername = $r['idno'] . ',' . $r['name'];
        }
        echo  $row["idmember"], ",-," . $row["nomineename"], ",-," . $row["nomineeaadharno"], ",-," . $row["name"], ",-," . $row["idno"], ",-," . $row["aadharno"], ",-," . $row["mobilenumber"], ",-," . $row["password"], ",-," . $row["address"], ",-," . $row["bankdetails"], ",-,"  . $row["groupleader"], ",-,"  . $row["doj"], ",-,"  . $groupleadername;
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
