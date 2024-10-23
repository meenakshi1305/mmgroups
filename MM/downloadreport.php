<?php
require "./fpdf/fpdf.php";
require "dbconfig.php";

$startDate = $_GET["startDate"];
$endDate = $_GET["endDate"];

$sql = "select DISTINCT idno,name,aadharno,mobilenumber,address,bankdetails from member m join loan l on m.idmember = l.idmember where l.actualdate >= '$startDate' and l.actualdate <='$endDate' and l.paymentdate is null;";

try {
    $result = $con->query($sql);
    if ($result !== false && $result->num_rows > 0) {
        class PDF extends FPDF
        {
        }

        $pdf = new PDF();
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont("Arial", "", 12);

        $recordCount = 0; // Variable to keep track of the number of records printed

        while ($row = $result->fetch_assoc()) {
            $pdf->SetDrawColor(255,255,255); // Set outline color to white (same as the background color)
            $pdf->SetFillColor(255,255,255);
            $pdf->SetFont("Arial", "", 6); // Set font size to 10
            $pdf->SetTextColor(0, 0, 0);
            $value = $row["name"] . " - " . $row["idno"] . " - " . $row["aadharno"] . " - " . $row["mobilenumber"];
            $pdf->MultiCell(200, 5, $value,1, 0, 1, "C");
            // $pdf->Cell(5, 10, $row['idno'] . "-", 0, 0, 'C');
            // $pdf->Cell(25, 10, $row['aadharno'], 0, 0, 'C');

            $recordCount++;

           
        }
        $pdf->Output();
    }
} catch (Exception $e) {
    echo "Message: " . $e->getMessage();
}
