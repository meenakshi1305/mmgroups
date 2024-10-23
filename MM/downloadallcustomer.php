<?php
require('./fpdf/fpdf.php');
require 'dbconfig.php';

class PDF extends FPDF
{
    
    private $row = 1; // Initialize row counter
    
    function DisplayMemberDetails($pdf, $data)
    {
        global $totalBalance;
        $pdf->SetFont('Arial', '', 8);
         // Display row number
        $pdf->Cell(5, 9, $this->row, 1); 
        $pdf->Cell(16, 9, $data[0], 1);
        $pdf->Cell(11, 9, $data[1], 1);
        $pdf->Cell(50, 9, $data[2], 1);
        $pdf->Cell(18, 9, $data[3], 1);
        $pdf->Cell(11, 9, $data[4], 1);
        $pdf->Cell(11, 9, $data[5], 1);
        $pdf->Cell(16, 9, $data[6], 1);
        $pdf->Cell(50, 9, $data[7], 1);
        
        $totalBalance += $data[5];
        $pdf->Ln();
        $this->row++; // Increment row number
    }
}
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial', '', 10);
$pdf->Ln(20);
$sql = "select m .name as name,m .idno as idno,m .mobilenumber as mobilenumber,(select name from member m2 where m2.idmember=m.groupleaderid) as groupleader,l .issuedate as issuedate,l .totalamount as totalamount, ( select (l1 .totalamount - sum(dueamount)) from loan l1 where l1 .idmember =l .idmember and l1 .paymentdate is not null and l1 .completed =0) as balance, ( select max(l2 .actualdate ) from loan l2 where l2 .idmember =l .idmember ) as closedate from member m join loan l on m .idmember = l .idmember where l .completed = 0  group by m .idmember";
try {
    $result = $con->query($sql);
    if ($result !== false && $result->num_rows > 0) {
        $processedIDs = array(); 
        $headerDisplayed = false; 
        while ($row = $result->fetch_assoc()) {
            $dojReturn = $row["issuedate"];
            $idnoReturn = $row["idno"];
            $nameReturn = $row["name"];
            $mobileReturn = $row["mobilenumber"];
            $totalAmountReturn = $row["totalamount"];            
            $balanceReturn = $row["balance"];
            $actualdateReturn = $row["closedate"];
            $groupleaderReturn = $row["groupleader"];
            

            if (in_array($idnoReturn, $processedIDs)) {
                continue;
            }

            if (!$headerDisplayed) {
                $header = array('No','Date', 'ID No', 'Name','Mobile No','Amount','Balance','End Date','groupleader');
                $pdf->SetFont('Arial', 'B', 7); 
                $pdf->Cell(5, 9, $header[0], 1,0);
                $pdf->Cell(16, 9, $header[1], 1,0);
                $pdf->Cell(11, 9, $header[2], 1,0);
                $pdf->Cell(50, 9, $header[3], 1,0);
                $pdf->Cell(18, 9, $header[4], 1,0);
                $pdf->Cell(11, 9, $header[5], 1,0);
                $pdf->Cell(11, 9, $header[6], 1,0);
                $pdf->Cell(16, 9, $header[7], 1,0);
                $pdf->Cell(50, 9, $header[8], 1,0);
                
                $pdf->Ln();
                $headerDisplayed = true;
            }

            $nameAndMobile = $nameReturn ."\n". $mobileReturn;

            $data = array(
                $dojReturn,                
                $idnoReturn,
                $nameReturn,
                $mobileReturn,
                $totalAmountReturn,
                $balanceReturn,
                $actualdateReturn,
                $groupleaderReturn,
            );

            $pdf->SetX(10);
            $pdf->DisplayMemberDetails($pdf, $data);

            $processedIDs[] = $idnoReturn;
            
        }
        $pdf->SetFont('Arial', 'B', 10);
        // $pdf->SetY(7);
        // $pdf->SetX(10);
        $pdf->Cell(0, 30, ' TOTAL BALANCE  :   ');
        $pdf->SetX(120);
        $pdf->Cell(0,30,$totalBalance);
    } 
}catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
}

$pdf->Output();
?>