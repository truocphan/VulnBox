<?php
require(USCES_PLUGIN_DIR.'/classes/fpdf/mbfpdf.php');

$pdf=new MBFPDF();
$pdf->AddMBFont(BIG5,'BIG5');
$pdf->Open();
$pdf->AddPage();
$pdf->SetFont(BIG5,'',20);
$pdf->Write(10,'?ڡ????ﾅ 18 C ﾀ・? 83 %');
$pdf->Output();
?>
