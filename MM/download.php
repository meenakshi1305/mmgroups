<?php
require('./fpdf/fpdf.php');
require 'dbconfig.php';

$memberid = $_GET['idmember'];


$nameReturn = '';
$idnoReturn = '';
$aadharReturn = '';
$mobileReturn = '';
$addressReturn = '';
$bankdetailsReturn = '';
$groupLeaderReturn = '';
$nomineenameReturn = '';
$nomineeaadharnoReturn = '';
$dojReturn = '';
$totalAmountReturn = '';
$dueAmountReturn = '';
$serviceChargeReturn='';
$zero = 0;


$sql = "SELECT m1.name as name, m1.idno as idno, m1.aadharno as aadharno, m1.mobilenumber as mobilenumber, m1.address as address, m1.bankdetails as bankdetails, m1.doj as doj, m2.name as groupleader, m1.nomineename as nomineename, m1.nomineeaadharno as nomineeaadharno , l.totalamount AS totalamount,
l.dueamount AS dueamount,l.issuedate AS issuedate,l.servicecharge as servicecharge
FROM member AS m1
LEFT JOIN member AS m2 ON m1.groupleaderid = m2.idmember
LEFT JOIN loan AS l ON m1.idmember = l.idmember
WHERE m1.idmember = '$memberid' and l.completed = '$zero' ";

try {
  $result = $con->query($sql);
  if ($result !== false && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $nameReturn = $row["name"];
      $idnoReturn = $row["idno"];
      $aadharReturn = $row["aadharno"];
      $mobileReturn = $row["mobilenumber"];
      $addressReturn = $row["address"];
      $bankdetailsReturn = $row["bankdetails"];
      $groupLeaderReturn = $row["groupleader"]; // Corrected field name
      $nomineenameReturn = $row["nomineename"];
      $nomineeaadharnoReturn = $row["nomineeaadharno"];
      $dojReturn = $row["issuedate"];
      if($row["totalamount"] != $zero){
        $totalAmountReturn = $row["totalamount"];
      }
      if($row["servicecharge"] != $zero){
        $serviceChargeReturn = $row["servicecharge"];
      }
      $dueAmountReturn =  $row["dueamount"];
      
    }
  } else {
    echo "0 results";
  }
} catch (Exception $e) {
  echo 'Message: ' . $e->getMessage();
}


class PDF extends FPDF
{
  function Header()
  {
    $this->setX(10);
    $this->SetFont('Arial', '', 14);
    $this->Cell(10, 10, 'Off :');
    $this->SetFont('Arial', 'B', 14);
    $this->Cell(10, 10, ' 73394 89378');
    $this->setX(150);
    $this->SetFont('Arial', '', 14);
    $this->Cell(10, 10, 'Cell :');
    $this->SetFont('Arial', 'B', 14);
    $this->Cell(10, 10, ' 88382 54544');
    $this->setY(20);
    $this->setX(70);
    $this->SetFont('Times', 'B', 30);
    $this->Cell(10, 10, ' MM GROUPS');

    $this->SetFont('Arial', '', 15);
    $this->Cell(50);
    $this->Ln(5);
    $this->Cell(53);
    $this->setY(27);
    $this->setX(55);
    $this->Cell(10, 10, '21/1, PTAV Street, Senjai, KARAIKUDI - 630 001', 'C');
    $this->Ln(5);
    $this->Cell(65);
    $this->Cell(10, 10, 'Sivagangai District, Tamilnadu.', 'C');
    $this->setY(40);
    $this->setX(5);
    $this->Cell(10, 10, '================================================================');
  }

  function Footer()
  {
    $this->SetY(-15);
    $this->SetFont('Arial', 'I', 8);
  }
}

$pdf = new PDF();

$pdf->AddPage();
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(0, 20, '', 0, 1);
$pdf->Cell(0, 10, 'Name                    :  ' . $nameReturn, 0, 1);
$pdf->Cell(0, 10, 'ID No                    :  ' . $idnoReturn, 0, 1);
$pdf->Cell(0, 10, 'Aadhar No            :  ' . $aadharReturn, 0, 1);
$pdf->Cell(0, 10, 'Mobile No             :  ' . $mobileReturn, 0, 1);
$pdf->MultiCell(0, 10, 'Address                :  ' . $addressReturn, 0, 1);
$pdf->MultiCell(0, 10, 'Bank Details        :  ' . $bankdetailsReturn, 0, 1);
$pdf->Cell(0, 10, 'Nominee name            :  ' . $nomineenameReturn, 0, 1);
if($nomineeaadharnoReturn !=0){
  $pdf->Cell(0, 10, 'Nominee Aadhar             :  ' . $nomineeaadharnoReturn, 0, 1);
}
else{
  $pdf->Cell(0, 10, 'Nominee Aadhar             :  ', 0, 1);
}
$pdf->Cell(0, 10, 'Group Leader       :  ' . $groupLeaderReturn, 0, 1);
$pdf->Cell(0, 10, 'Total Amount       :  '.$totalAmountReturn  , 0, 1);
$pdf->Cell(0, 10, 'Due Amount       :  '.$dueAmountReturn  , 0, 1);
$pdf->SetY(225);
$pdf->SetFont('Arial', 'I', 14);
$pdf->Cell(0, 30, 'Signature of customer');
$pdf->SetY(220);
$pdf->SetX(145);
$pdf->Cell(0, 40, 'Authorised Signatory');
$pdf->SetY(35);
$pdf->SetX(145);
$pdf->Cell(0, 40, 'Date : ' . $dojReturn);

$pdf->Output();
