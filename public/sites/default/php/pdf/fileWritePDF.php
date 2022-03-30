<?php
myRequireOnce ('writeLog.php');
myRequireOnce ('modifyForPDF.php', 'pdf');


function fileWritePDF($filename, $html){
    $html=modifyForPDF($html);
	$filename = str_ireplace('.html', '.pdf', $filename);
	require_once __DIR__ . '/../vendor/autoload.php';
	$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
	$mpdf->text_input_as_HTML = true;
	$mpdf->showImageErrors = true.
	$stylesheet = file_get_contents( __DIR__ . '/../styles/pdf.css');
	$mpdf->WriteHTML($html);
	$mpdf->Output($filename ,  \Mpdf\Output\Destination::FILE);
	$mpdf = NULL;
	return TRUE;
}
