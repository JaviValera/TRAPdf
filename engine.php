<?php
// if you are using composer, just use this
include 'vendor/autoload.php';
// CONFIGURACION DEL CONVERTIDOR A HTML
use Gufy\PdfToHtml\Config;
Config::set('pdftohtml.bin', 'C:/poppler-0.51/bin/pdftohtml.exe');
Config::set('pdfinfo.bin', 'C:/poppler-0.51/bin/pdfinfo.exe');
move_uploaded_file($_FILES['src-pdf']['tmp_name'], 'document.pdf');
$pdf = new Gufy\PdfToHtml\Pdf('document.pdf');
$total_pages = $pdf->getPages();
// HASTA AQUÍ
// CONFIGURACION CONVERTIDOR A PDF
use mikehaertl\wkhtmlto\Pdf;
$pdf2 = new Pdf(array(
    'binary' => 'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe',
    'ignoreWarnings' => true,
    'commandOptions' => array(
        'useExec' => true,      // Can help if generation fails without a useful error message
        'procEnv' => array(
            // Check the output of 'locale' on your system to find supported languages
            'LANG' => 'en_US.utf-8',
        ),
    ),
));
// HASTA AQUÍ
use Stichoza\GoogleTranslate\TranslateClient;
$page='1';
do
{
$html = $pdf->html($page);
unset($pdf);
$html = str_replace('<br>', ' AAAA ', $html);
$dom = new DOMDocument;
$dom->loadHTML('<?xml version="1.0" encoding="UTF-8"?>' . $html);
unset($html);
$b = $dom->getElementsByTagName('p');
foreach ($b as $p)
{
    $tr = new TranslateClient(null, $_POST["lang"]);
    $p->nodeValue = $tr->translate($p->nodeValue);
}
$html2 = $dom->saveHTML();
$html2 = str_replace('AAAA', ' <br> ', $html2);
file_put_contents('translated' . $page . '.html', $html2);
$pdf2->addPage('localhost/TRAPdf/translated' . $page . '.html');
$page++;
} while ($page <= $total_pages);
if (!$pdf2->send('translated-' . $_FILES['src-pdf']['name'])) {
    echo $pdf2->getError();
}
$clean='1';
while ($clean <= $total_pages)
{
unlink('translated' . $clean . '.html');
$clean++;
}
unlink('document.pdf');
exec('rd C:\xampp\htdocs\TRAPdf\output\ /S /Q');
exec('md C:\xampp\htdocs\TRAPdf\output\ ');
?>
