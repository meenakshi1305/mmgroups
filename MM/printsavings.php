<?php
require('./fpdf/fpdf.php');
require 'dbconfig.php';

$idsavings = $_GET['idsavings'];
$idmember = $_GET['idmember'];

$sqlIdno = "select count(*) as countup from printsavings";
$countup = 0;
$result = $con->query($sqlIdno);
while ($row = $result->fetch_assoc()) {
  $countup =   $row["countup"];
}

$sqlinsert = "INSERT INTO printsavings (idsavings,idmember) VALUES ('$idsavings',$idmember)";
$rs = mysqli_query($con, $sqlinsert);

// Fetch the savings and member details from the database
$sql = "SELECT s.amount as amt, s.savingdate as date,m.aadharno as aadhar,m.idno as id,m.address as address,(select sum(amount) from savings where idmember='$idmember') as totalSaved FROM savings s join member m on s.idmember=m.idmember where s.idsavings = '$idsavings'";
try {
  $result = $con->query($sql);
  if ($result !== false && $result->num_rows > 0) {
    // Create a new PDF instance
    $pdf = new FPDF();

    // Add a new page to the PDF
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // Header
    $pdf->SetY(10);
    $pdf->Cell(10, 8, 'Billno: ' . $countup + 1, 0, 0, 'C');
    $pdf->Cell(140, 8, 'MM Groups', 0, 0, 'C');
    $pdf->Cell(40, 8, 'Office: 73394 89378', 0, 1, 'C');
    $pdf->Cell(355, 0, '88382 54544', 0, 1, 'C');
    $pdf->Cell(160, 8, '21/1,PTAN steert,senjai,karaikudi.', 0, 1, 'C');
    $pdf->Cell(160,8, 'sivagangai dist - 630 001,Tamil Nadu', 0, 1, 'C');
    $pdf->Cell(310, 0, 'Date:', 0, 1, 'C');
    $pdf->Cell(10, 8, '===========================================================================',0,1);

     while ($row = $result->fetch_assoc()) {
      $pdf->Cell(10, 8, 'Customer ID number: ' . $row['id'], 0, 1);
      $pdf->Cell(10, 8, 'Aadhar number: ' . $row['aadhar'], 0, 1);
      $pdf->MultiCell(150, 8, 'Address: ' . $row['address'], 0, 0);      
      $pdf->SetY(42);
      $pdf->SetX(145);
      $pdf->Cell(10, 8, 'Amount: ' . $row['amt'], 0, 1, 'C');
      $pdf->SetX(155);
      $pdf->Cell(10, 8,'Saving Date: ' . $row['date'], 0, 1, 'C');
      $pdf->SetX(152);
      $pdf->Cell(10, 8,'Total Savings: ' . $row['totalSaved'], 0, 1, 'C');
      // $pdf->Cell(150, 10, 'Amount: ' . $row['amt'], 0, 0);
      // $pdf->Cell(150, 10, 'Saving Date: ' . $row['date'], 0, 1);
      $pdf->SetY(70);
      $pdf->SetFont('Arial', 'I', 11);
      $pdf->Cell(70, 30, 'GroupLeader signature', 0, 0);
      $pdf->Cell(60, 30, 'Customer signature', 0, 0);
      $pdf->Cell(100, 30, 'Authorised signature', 0, 1);
    }

    // Output the PDF
    $pdf->Output();
  } else {
    echo "0 results";
  }

    // Footer
    $pdf->SetY(-15);
    $pdf->SetFont('Arial', 'I', 8);
    $pdf->Cell(0, 10, 'Your Footer Text', 0, 0, 'C');

   
   
} catch (Exception $e) {
  echo 'Message: ' . $e->getMessage();
}
?>