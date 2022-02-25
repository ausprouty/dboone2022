<?php

myRequireOnce ('writeLog.php');
myRequireOnce ('dirListFiles.php');

function testPdf(){
	$title = 'pdf/multiply103.pdf';
	require_once __DIR__ . '/../vendor/autoload.php';
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
	$mpdf->text_input_as_HTML = true;
	$mpdf->showImageErrors = true.
	$stylesheet = file_get_contents( __DIR__ . '/../styles/pdf.css');
    //$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
    $html = file_get_contents( __DIR__ . '/../pdf/folder/nojs/M2/eng/multiply1/multiply103.html');
	$mpdf->WriteHTML($html);
    $mpdf->Output($title,  \Mpdf\Output\Destination::FILE);
	return 'check Somewhere';
}
