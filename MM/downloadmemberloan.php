<?php
require('./fpdf/fpdf.php');
require 'dbconfig.php';

$memberid = $_GET['idmember'];
class PDF extends FPDF
{
    function Header()
    {
        // $this->setX(10);
        // $this->SetFont('Arial', '', 10);
        // $this->Cell(10, 5, 'GroupLeader :');
    }
    // private $row = 1; // Initialize row counter
    private $totalRows = 0;
    function displayMemberLoanDetails($pdf, $data, $header,$row)
    {
        foreach($data as $itr) {
            $pdf->SetFont('Arial', '', 7);
            $pdf->Cell(10, 9, $row, 1); 
            $pdf->Cell(15, 9, $itr['dojReturn'], 1);
            $pdf->Cell(10, 9, $itr['idnoReturn'], 1);
            $pdf->Cell(50, 9, $itr['nameReturn'], 1);
            $pdf->Cell(19, 9, $itr['mobileReturn'], 1);
            $pdf->Cell(19, 9, $itr['totalAmountReturn'], 1);
            $pdf->Cell(19, 9, $itr['balanceReturn'], 1);
            $pdf->Cell(19, 9, $itr['actualdateReturn'], 1);
            // $totalBalance += $data[6];
            $pdf->Ln();
            $row++;// Increment row number
            $this->totalRows++; // Increment total rows
        }        
    }
    private $outlistRow = 1;
    function displayOutlistMembers($pdf, $data, $header)
    {
        $pdf->SetFont('Arial', '', 7);
        $pdf->Cell(10, 9, $this->outlistRow, 1); 
        $pdf->Cell(11, 9, $data[0], 1);
        $pdf->Cell(50, 9, $data[1], 1);
        $pdf->Cell(19, 9, $data[2], 1);;
        for ($i = 3; $i < count($data); $i++) {
            $pdf->Cell(19, 7, $data[$i], 1);
        }
        $pdf->Ln();
        $this->outlistRow++;// Increment row number
        $this->totalRows++; // Increment total rows
    }
    function getTotalRows()
    {
        return $this->totalRows;
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Courier', '', 5);
$pdf->Ln(10);

$sql = "SELECT m.idno AS idno,m.idmember as memberid, m.name AS name, m.mobilenumber AS mobilenumber, l.issuedate AS issuedate, l.totalamount AS totalamount, (SELECT (l1.totalamount - SUM(dueamount)) FROM loan l1 WHERE l1.idmember = l.idmember AND l1.paymentdate IS NOT NULL AND l1.completed = 0 AND l1.deleted = 0) AS balance, (SELECT MAX(l2.actualdate) FROM loan l2 WHERE l2.idmember = l.idmember) AS closedate FROM member m JOIN loan l ON m.idmember = l.idmember WHERE m.groupleaderid = '$memberid' AND l.completed = 0 AND l.deleted = 0 GROUP BY m.idmember ORDER BY closedate ";


$sqlRe = "SELECT l.totalamount AS totalamount FROM member m JOIN loan l ON m.idmember = l.idmember WHERE m.groupleaderid = '$memberid' AND l.completed = 0 AND l.deleted = 0 GROUP BY m.idmember ";
$totalBalance = 0;
try {
    $resultTotalAmount = $con->query($sqlRe);
    $result = $con->query($sql);
    if ($result !== false && $result->num_rows > 0) {
        $processedIDs = array();
        // $headerDisplayed = false;
        $totalAmountArr = array();
        while($first_arr = $resultTotalAmount->fetch_assoc()){
            $totalAmountArr[] = $first_arr["totalamount"];
        }
        $totalAmountUniqueArr = array_unique($totalAmountArr);
        $arrRow = array();
        while($row= $result->fetch_assoc()){
            $arrRow[] = array(
                'dojReturn' =>$row["issuedate"],
                'idnoReturn' => $row["idno"],
                'nameReturn' =>$row["name"],
                'mobileReturn' => $row["mobilenumber"],
                'totalAmountReturn' => $row["totalamount"],
                'balanceReturn' => $row["balance"],
                'actualdateReturn' => $row["closedate"],
            );
            $totalBalance += $row["balance"];
        }
            for ($itr = 0; $itr < sizeof($totalAmountUniqueArr); $itr++) {
                $valueItr = $totalAmountUniqueArr[$itr];
                $filtered_array = array_filter($arrRow, function ($obj) use ($valueItr) {
                    return $obj['totalAmountReturn'] == $valueItr;
                }); 
                    $header = array('S.no', 'Date', 'ID No', 'Name', 'Mobile No', 'Amount', 'Balance', 'Closing Date');
                    $pdf->SetFont('Arial', 'B', 7);
                    $pdf->Cell(10, 9, $header[0], 1);
                    $pdf->Cell(15, 9, $header[1], 1);
                    $pdf->Cell(10, 9, $header[2], 1);
                    $pdf->Cell(50, 9, $header[3], 1);
                    $pdf->Cell(19, 9, $header[4], 1);
                    $pdf->Cell(19, 9, $header[5], 1);
                    $pdf->Cell(19, 9, $header[6], 1);
                    $pdf->Cell(19, 9, $header[7], 1);
                    $pdf->Ln();
                    // $headerDisplayed = true; 
                $pdf->SetX(10);
                $pdf->displayMemberLoanDetails($pdf, $filtered_array, $header,1);
                $pdf->Ln();
            }
        
    } else {
        echo "0 results";
    }
} catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
}

$sql1 = "SELECT m.idmember AS idmember, m.idno AS idno,m.name AS name, m.mobilenumber as mobilenumber from member m where m.groupleaderid='$memberid' AND m.idmember NOT IN (SELECT m.idmember as memberid FROM member m JOIN loan l ON m.idmember = l.idmember WHERE m.groupleaderid = '$memberid' AND l.completed = 0 AND l.deleted = 0 GROUP BY m.idmember)";
try {
    $result = $con->query($sql1);
    if ($result !== false && $result->num_rows > 0) {
        $processedIDs = array();
        $headerDisplayed = false;
        while ($row = $result->fetch_assoc()) {
            $idnoReturn = $row["idno"];
            $nameReturn = $row["name"];
            $mobileReturn = $row["mobilenumber"];

            if (in_array($idnoReturn, $processedIDs)) {
                continue;
            }

            if (!$headerDisplayed) {
                $header = array('S.no', 'ID No', 'Name', 'Mobile No');
                $pdf->SetFont('Arial', 'B', 7);
                $pdf->Cell(10, 9, $header[0], 1);
                $pdf->Cell(11, 9, $header[1], 1);
                $pdf->Cell(50, 9, $header[2], 1);
                $pdf->Cell(19, 9, $header[3], 1);
                $pdf->Ln();
                $headerDisplayed = true;
              
            }

            $data = array(
                $idnoReturn,
                $nameReturn,
                $mobileReturn,
            );

            $pdf->SetX(10);
            $pdf->displayOutlistMembers($pdf, $data, $header);

            $processedIDs[] = $idnoReturn;
        }        
    }
    $pdf->Cell(0, 30, '    TOTAL BALANCE  :   ' . $totalBalance);
} catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
}

$pdf->Output();
?>