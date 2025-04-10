<?php
require('fpdf/fpdf.php');
require_once 'services/User.php';
require_once 'services/Cart.php';

$cart = new Cart();
$user = new User();
$items = $cart->get_cart();

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Header
$pdf->Cell(0, 10, 'INVOICE', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);

// Company Info
$pdf->Cell(100, 10, 'Your Company Name', 0, 1);
$pdf->Cell(100, 6, '123 Street Name', 0, 1);
$pdf->Cell(100, 6, 'City, Country', 0, 1);
$pdf->Cell(100, 6, 'Phone: +1 234 567 890', 0, 1);
$pdf->Ln(10);

// Customer Info
$pdf->Cell(100, 6, 'Bill To:', 0, 1);
$pdf->Cell(100, 6, $user->get_full_name(), 0, 1);
$pdf->Ln(10);

// Table Header
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(60, 10, 'Product Name', 1);
$pdf->Cell(30, 10, 'Quantity', 1);
$pdf->Cell(40, 10, 'Unit Price', 1);
$pdf->Cell(40, 10, 'Total', 1);
$pdf->Ln();

// Table Content
$pdf->SetFont('Arial', '', 12);
$totalAmount = 0;

foreach ($items as $item) {
    $name = $item['name'];
    $qty = $item['quantity'];
    $price = $item['price'];
    $lineTotal = $qty * $price;
    $totalAmount += $lineTotal;

    $pdf->Cell(60, 10, $name, 1);
    $pdf->Cell(30, 10, $qty, 1);
    $pdf->Cell(40, 10, '$' . number_format($price, 2), 1);
    $pdf->Cell(40, 10, '$' . number_format($lineTotal, 2), 1);
    $pdf->Ln();
}

// Total
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(130, 10, 'Total Amount', 1);
$pdf->Cell(40, 10, '$' . number_format($totalAmount, 2), 1);
$pdf->Ln(20);

// Footer
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Thank you for your purchase!', 0, 1, 'C');

// Output the PDF
$pdf->Output('D', 'invoice.pdf'); // 'D' means force download
exit;
