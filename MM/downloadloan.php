<?php

require('./fpdf/fpdf.php');
require 'dbconfig.php';

$memberid = $_GET['idmember'];

class PDF extends FPDF
{
    function loadTable($header, $data)
    {
        // Column widths
        $w = array(10, 20, 20, 25,25,25, 30,30);
        // Header
        $this->SetFont('Arial', 'B', 10);
        for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1);
        $this->Ln();
        $this->SetFont('Arial', '', 10);
        // Data
        $itr = 1;
        $totalDueAmount = 0;
        $totalPenalityAmount = 0;
        foreach ($data as $row) {
        if ($itr == 20) {
            // Add a new page
            $this->AddPage();
        }
        $this->Cell($w[0],8,$itr,1,0,'C');
        $this->Cell($w[1],8,$row['actualdate'],1,0,'C');
        if($row['paymentdate'] != '0000-00-00'){
            $this->Cell($w[2],8,$row['paymentdate'],1,0,'C');
        }
        else{
            $this->Cell($w[2],8,'',1,0,'C');
        }
        $this->Cell($w[3],8,$row['dueamount']==0?'':$row['dueamount'],1,0,'C');
        $totalDueAmount += $row['dueamount'];
        $this->Cell($w[4],8,$row['penality'],1,0,'C');
        $totalPenalityAmount += $row['penality'];
        if( $row['penality'] == null || $row['penality'] == 0){
        $this->Cell($w[3], 8,'', 1, 0, 'C');
        }
        else{
        $this->Cell($w[3], 8, $row['dueamount'] + $row['penality'], 1, 0, 'C');
        }
        $this->Cell($w[6],8,'',1,0,'C');
        $this->Cell($w[6],8,'',1,0,'C');
        $this->Ln();
        $itr++;
        }
        $this->Cell($w[0] + $w[1] + $w[2], 8, 'Total: ', 1, 0, 'R');
        $this->Cell($w[3], 8, $totalDueAmount, 1, 0, 'C');
        $this->Cell($w[4], 8, $totalPenalityAmount, 1, 0, 'C');
        $this->Ln(); 
        $this->Cell($w[0] + $w[1] + $w[2], 8, 'Grand Total: ', 1, 0, 'R');
        $this->Cell($w[5] + $w[3], 8, $totalDueAmount + $totalPenalityAmount, 1, 0, 'C');
        
        // Closing line
        $this->Cell(array_sum($w),0,'','T');
    }

    function printMemberDetails($row)
    {
        $this->SetFont('Arial', '', 12);
        $this->setX(10);
        $this->setY(50);
        $this->Cell(5, 10, 'Name : ' . $row['name'], 0, 1);
        $this->Cell(5, 10, 'ID : ' . $row['idno'], 0, 1);
        $this->Cell(5, 10, 'Aadhar No : ' . $row['aadhar'], 0, 1);
        $this->Cell(5, 10, 'Mobile No : ' . $row['mbno'], 0, 1);
        $this->Cell(5, 10, 'Amount : ' . $row['totalamount'], 0, 1);
        $this->SetY(45);
        $this->SetX(170);
        $this->Cell(0, 6, 'Date : ' . $row['issuedate'], 0, 1);
        $this->SetY(50);
        $this->SetX(115);
        $this->Cell(5, 10, 'Paid Amount :'. $row['paid'], 0, 1);
        $this->SetY(60);
        $this->SetX(115);
        $this->Cell(5, 10, 'Due Amount :' . $row['dueamount'], 0, 1);
        $this->SetY(70);
        $this->SetX(115);
        $this->Cell(5, 10, 'Service charge :' . $row['servicecharge'], 0, 1);
        $this->SetY(80);
        $this->SetX(115);
        $this->Cell(5, 10, 'Penality :' . $row['penality'],0, 1);
        $this->SetY(90);
        $this->SetX(115);
        $this->Cell(5, 10, 'Balance Amount :' . $row['balance'],0, 1);
    }

    function LoadData()
    {
        require 'dbconfig.php';
        $idmember = $_GET['idmember'];
        $idloanmember = $_GET['idloanmember'];
        $sql = "select actualdate,paymentdate,dueamount,penality from loan where idmember = '$idmember' and idloanmember = '$idloanmember'";
        $array = array();
        $result = $con->query($sql);
        // look through query
        while($row = $result->fetch_assoc()){
            // add each row returned into an array
            $array[] = $row;
        }
        return $array;
    }
}

$pdf = new PDF();
$pdf->AddPage();
// Column headings
$header = array('S.No', 'Date', 'Delay Date', 'Due Amount','Penality','Total Due', 'Leader Signature','Authorised sign');

// Data loading
$data = $pdf->LoadData();
$pdf->setX(10);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(10, 10, 'Off :');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(10, 10, ' 73394 89378');
$pdf->setX(150);
$pdf->SetFont('Arial', '', 14);
$pdf->Cell(10, 10, 'Cell :');
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(10, 10, ' 88382 54544');
$pdf->setY(20);
$pdf->setX(70);
$pdf->SetFont('Times', 'B', 30);
$pdf->Cell(10, 10, ' MM GROUPS');

$pdf->SetFont('Arial', '', 15);
$pdf->Cell(50);
$pdf->Ln(5);
$pdf->Cell(53);
$pdf->setY(27);
$pdf->setX(55);
$pdf->Cell(10, 10, '21/1, PTAV Street, Senjai, KARAIKUDI - 630 001', 'C');
$pdf->Ln(5);
$pdf->Cell(65);
$pdf->Cell(10, 10, 'Sivagangai District, Tamilnadu.', 'C');
$pdf->setY(40);
$pdf->setX(5);
$pdf->Cell(10, 10, '================================================================');
$idmember = $_GET['idmember'];
$idloanmember = $_GET['idloanmember'];

$sql = "select DISTINCT m1.name as name,m1.idno as idno, m1.aadharno as aadhar, m1.mobilenumber as mbno,l1.issuedate as issuedate,l1.totalamount as totalamount,l1.dueamount as dueamount,l1.servicecharge as servicecharge,(SELECT ((l1.totalamount + l1.servicecharge) - SUM(dueamount)) FROM loan l2 WHERE l2.idmember = l1.idmember AND l2.paymentdate IS NOT NULL AND l2.completed = 0 AND l2.deleted = 0) AS balance,(SELECT (SUM(dueamount)+sum(penality)) FROM loan l2 WHERE l2.idmember = l1.idmember AND l2.paymentdate IS NOT NULL AND l2.completed = 0 AND l2.deleted = 0) AS paid,(SELECT sum(penality) FROM loan l2 WHERE l2.idmember = l1.idmember AND l2.paymentdate IS NOT NULL AND l2.completed = 0 AND l2.deleted = 0) AS penality  from member m1 left join loan l1 on m1.idmember = l1.idmember where l1.idloanmember = '$idloanmember' and m1.idmember = '$idmember' limit 1";
$result = $con->query($sql);

while ($row = $result->fetch_assoc()) {
    $pdf->printMemberDetails($row);
}
$pdf->SetFont('Arial', '', 10);
$pdf->SetY(100);
$pdf->SetX(10);
$pdf->loadTable($header, $data);
$pdf->SetFont('Arial', 'I', 12);
$pdf->SetY(240);
$pdf->SetX(15);
$pdf->Cell(0, 30, 'Authorised Signatory');
$pdf->SetY(235);
$pdf->SetX(145);
$pdf->Cell(0, 40, 'Group Leader Signature');
require 'dbconfig.php';

$pdf->Output();
?>