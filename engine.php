<?php
 
// Include Composer autoloader if not already done.
include 'vendor/autoload.php';
require ('FPDF/fpdf.php');

// Parse pdf file and build necessary objects.
$parser = new \Smalot\PdfParser\Parser();
$pdf    = $parser->parseFile($_FILES["src-pdf"]["tmp_name"]);
 
$text = $pdf->getText();

//$text = mb_convert_encoding($text, "auto");
//$text = utf8_decode($text);



//$text = iconv('UTF-8', 'ISO-8859-1', $text);


 //echo $text ;

use Stichoza\GoogleTranslate\TranslateClient;

$tr = new TranslateClient(null, 'es'); // Detect language and translate to Spanish
$text = $tr->translate($text); // Returns raw array of translated data

$text = utf8_decode($text);

//echo $text;

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->Write(5,$text);
//$pdf->Cell(40,10,$text);
$pdf->Output(); 
?>