<?php

myRequireOnce('writeLog.php');
myRequireOnce('dirListFiles.php');
/*
	require_once __DIR__ . '/vendor/autoload.php';

	$mpdf = new \Mpdf\Mpdf();
	$mpdf->WriteHTML('<h1>Hello world!</h1>');
	$mpdf->Output();
*/
function testPdf(){
	$tite = 'test1';
	require_once __DIR__ . '/../vendor/autoload.php';
	$mpdf = new \Mpdf\Mpdf();
	$mpdf->text_input_as_HTML = true;
	$mpdf->WriteHTML('<h1>Hello world!</h1>');
    $mpdf->Output($title .'.pdf', 'D');
}


function testPdf2(){

//writeLogError('testPdf-8', "I am here");
//============================================================+
// File name   : example_001.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 001 for TCPDF class
//               Default Header and Footer
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Default Header and Footer
 * @author Nicola Asuni
 * @since 2008-03-04
 */
 //   $dir='./pdf/';
define('K_PATH_IMAGES', '/home/globa544/edit.mc2.online/sites/default/pdf/folder/nojs/M2/eng/multiply1/');

//return $_SERVER['DOCUMENT_ROOT'];
// Include the main TCPDF library (search for installation path).
require_once('./tcpdf/tcpdf.php');

$pageLayout = array(140, 280); //  or array($height, $width)
//$pdf = new TCPDF('p', 'mm', $pageLayout, true, 'UTF-8', false);
$unit= 'mm';
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, $unit, $pageLayout, true, 'UTF-8', false);
//writeLogError('testPdf-39', "I am here");
// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Bob Prouty');
$pdf->setTitle('MC2');
$pdf->setSubject('MC2');
$pdf->setKeywords('');
//writeLogError('testPdf-46', "I am here");
// set default header data
//$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
//$pdf->setFooterData(array(0,64,0), array(0,64,128));

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//writeLogError('testPdf-57', "I am here");
// set margins
$pdf->setMargins(10,10,10);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
//writeLogError('testPdf-65', "I am here");
// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set default font subsetting mode
$pdf->setFontSubsetting(true);
//writeLogError('testPdf-79', "I am here");
// Set font
// dejavusans is a UTF-8 Unicode font, if you only need to
// print standard ASCII chars, you can use core fonts like
// helvetica or times to reduce file size.
$pdf->setFont('dejavusans', '', 14, '', true);

// Add a page
// This method has several options, check the source code documentation for more information.
$pdf->AddPage();

// set text shadow effect
$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

$html = '<style>'. file_get_contents( __DIR__ . '/../styles/pdf.css').'</style>';
//define("ROOT_PUBLISH", '/home/globa544/app.mc2.online/');

$html .= file_get_contents( __DIR__ . '/../pdf/folder/nojs/M2/eng/multiply1/multiply101.html');

// Print text using writeHTML()
//writeHTML(html, ln = true, fill = 0, reseth = false, cell = false, align = '')
// see https://www.rubydoc.info/gems/rfpdf/1.17.1/TCPDF:writeHTML
$pdf->writeHTML($html, true, false, true, false, '');

//writeLogError('testPdf-105', "I am here");
// ---------------------------------------------------------

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output(__DIR__ . '/../pdf/multiply101.pdf', 'F');
//writeLogError('testPdf-111', "I am here");
return (K_PATH_IMAGES);

/*============================================================+
// END OF FILE
I : send the file inline to the browser (default). The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
D : send to the browser and force a file download with the name given by name.
F : save to a local server file with the name given by name.
S : return the document as a string (name is ignored).
FI : equivalent to F + I option
FD : equivalent to F + D option
E : return the document as base64 mime multi-part email attachment (RFC 2045)
//============================================================+
*/

}