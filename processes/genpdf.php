<?php 
require('../fpdflib/fpdf.php');
require_once('../classes/censusclass.php');
require_once('../classes/reportingclass.php');

$censusModel = new CensusClass();
$reportingModel = new reportingclass(); // Fixed: Added instantiation

$membersList = $censusModel->getAllMembers(0, 9999); 
$reportsList = $reportingModel->getAllReports();

$pdf = new FPDF();
$pdf->AddPage();

// Census Header
$logoY = 20;
$pdf->Image('../style/imgs/pdfheaderlogo.png', 60, $logoY, 90);

$pdf->SetY($logoY);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0,0,102);
$pdf->Cell(0, 10, '======================', 0, 0);
$pdf->Cell(0, 10, '======================', 0, 1, 'R');

$pdf->SetY($logoY + 10);
$pdf->Cell(0, 10, 'Made by theackkthree', 0, 1, 'C');

// --- TITLE ---
$pdf->SetY($logoY + 20);
$pdf->SetFont('Arial','B',14);
$pdf->Cell(0, 10, 'Census Data:', 0, 1, 'L');

// --- DYNAMIC TABLE ---
$pdf->SetY(50);
$pdf->SetFont('Arial','B',10);
$pdf->SetFillColor(230, 230, 230); 

// column width
$w = [40, 55, 45, 25, 25]; 

// Header Row
$pdf->Cell($w[0], 10, 'Name', 1, 0, 'C', true);
$pdf->Cell($w[1], 10, 'Address', 1, 0, 'C', true);
$pdf->Cell($w[2], 10, 'Email', 1, 0, 'C', true);
$pdf->Cell($w[3], 10, 'Date of Birth', 1, 0, 'C', true);
$pdf->Cell($w[4], 10, 'Status', 1, 1, 'C', true);

// Data Rows Census
$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);

if (!empty($membersList)) {
    foreach ($membersList as $row) {
        $fullName = $row['First_Name'] . ' ' . $row['Last_Name'];
        
        $pdf->Cell($w[0], 8, iconv('UTF-8', 'windows-1252', $fullName), 1, 0, 'C');
        $pdf->Cell($w[1], 8, iconv('UTF-8', 'windows-1252', $row['Address']), 1, 0, 'C');
        $pdf->Cell($w[2], 8, $row['Email'], 1, 0, 'C');
        $pdf->Cell($w[3], 8, $row['Date_of_Birth'], 1, 0, 'C');
        $pdf->Cell($w[4], 8, $row['User_Status'], 1, 1, 'C'); 
        
        if($pdf->GetY() > 250) {
            $pdf->AddPage();
            $pdf->SetY(20);
        }
    }
} else {
    $pdf->Cell(array_sum($w), 10, 'No records found.', 1, 1, 'C');
}

// --- TOTAL ROWS CENSUS---
$pdf->SetY(40);
$pdf->SetFont('Arial','I',10);
$pdf->SetTextColor(0,0,102);
$pdf->Cell(0, 10, 'Total Population: ' . count($membersList), 0, 1, 'R');


// --- ADD PAGE REPORTS TABLE ---
$pdf->AddPage();

// Reports Header
$logoY = 20;
$pdf->Image('../style/imgs/pdfheaderlogo.png', 60, $logoY, 90);

$pdf->SetY($logoY);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0,0,102);
$pdf->Cell(0, 10, '======================', 0, 0);
$pdf->Cell(0, 10, '======================', 0, 1, 'R');

$pdf->SetY($logoY + 10);
$pdf->Cell(0, 10, 'Made by theackkthree', 0, 1, 'C');

$pdf->SetFont('Arial','B',14);
$pdf->Cell(0, 10, 'Reports:', 0, 1, 'L');

// reports table
$pdf->SetY(50);
$pdf->SetFont('Arial','B',9);
$pdf->SetFillColor(230, 230, 230); 

$rw = [25, 35, 60, 45, 25]; 

// Header Row
$pdf->Cell($rw[0], 10, 'Date', 1, 0, 'C', true);
$pdf->Cell($rw[1], 10, 'Type', 1, 0, 'C', true);
$pdf->Cell($rw[2], 10, 'Message', 1, 0, 'C', true);
$pdf->Cell($rw[3], 10, 'Location', 1, 0, 'C', true);
$pdf->Cell($rw[4], 10, 'Status', 1, 1, 'C', true);

// Data Rows
$pdf->SetFont('Arial', '', 8);
$pdf->SetTextColor(0, 0, 0);

if (!empty($reportsList)) {
    foreach ($reportsList as $report) {
        $pdf->Cell($rw[0], 8, $report['Date_Sent'], 1, 0, 'C');
        $pdf->Cell($rw[1], 8, iconv('UTF-8', 'windows-1252', $report['Type_of_Problem']), 1, 0, 'C');
        
        $pdf->Cell($rw[2], 8, iconv('UTF-8', 'windows-1252', substr($report['Report_Message'], 0, 40)), 1, 0, 'L');
        
        $pdf->Cell($rw[3], 8, iconv('UTF-8', 'windows-1252', $report['located']), 1, 0, 'C');
        $pdf->Cell($rw[4], 8, strtoupper($report['report_status']), 1, 1, 'C');

        if($pdf->GetY() > 260) {
            $pdf->AddPage();
            $pdf->SetY(20);
        }
    }
} else {
    $pdf->Cell(array_sum($rw), 10, 'No reports found.', 1, 1, 'C');
}

// --- TOTAL ROWS REPORTS---
$pdf->SetY(40);
$pdf->SetFont('Arial','I',10);
$pdf->SetTextColor(0,0,102);
$pdf->Cell(0, 10, 'Total Reports: ' . count($reportsList), 0, 1, 'R');

// --- FOOTER ---
$pdf->SetY(260);
$pdf->SetFont('Arial','B',10);
$pdf->SetTextColor(0,0,102);
$pdf->Cell(0, 10, '=========================================================================', 0, 1, 'C');

$pdf->Output('I', 'Census_Reports_Export.pdf'); 
?>