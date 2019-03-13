<?php //PRINT_TABLE.PHP выгрузка сводной таблицы	
require('tfpdf/tfpdf.php');
require('../functions.php');


$arr = json_decode($_POST['JSON'], true);
$date = $arr['date'];
//$filename = "test_table.pdf";
$filename = "TMP.pdf";

$pdf = new tFPDF();
$pdf->AddPage();


//Pie chart
// Add a Unicode font (uses UTF-8)
$pdf->AddFont('TimesNR','','times.ttf', true);
$pdf->AddFont('TahomaB','','tahomabd.ttf', true);
$pdf->AddFont('Tahoma','','tahoma.ttf', true);

$pdf->SetFont('TahomaB','',14);
$txt = "Отчет по тесту №: ".$arr['test_id']." от ".$date;
$pdf->MultiCell(0, 5, $txt, 0, 1);	
$pdf->Ln(5);

$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Line($valX, $valY, $valX+190, $valY);
$pdf->Ln(2);
$pdf->SetFont('Tahoma', '', 10);
$txt = typeTest($arr['type'])." тестирование. Установленное время для ответа на один вопрос: ".$arr['time']." сек. Кол-во участников: ".$arr['num_usr'];
$pdf->MultiCell(0, 5, $txt, 0, 1);
$pdf->Ln(2);

$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Line($valX, $valY, $valX+190, $valY);
$pdf->Ln(5);

$pdf->SetFont('TahomaB', '', 8);

$txt = "Название";
$pdf->Cell(40,5,$txt,0,0,'C');

if($arr['type'] == 'FICTION') $txt = "Вероятность";
else $txt = '';
$pdf->Cell(35,5,$txt,0,0,'C');
$txt = "Вариант №1";
$pdf->Cell(30,5,$txt,0,0,'C');
$txt = "Вариант №2";
$pdf->Cell(30,5,$txt,0,0,'C');
$txt = "Воздержался";
$pdf->Cell(30,5,$txt,0,0,'C');
$txt = "Таймаут";
$pdf->Cell(30,5,$txt,0,1,'C');
$txt = "вопроса";
$pdf->Cell(40,5,$txt,0,0,'C');

if($arr['type'] == 'FICTION') $txt = "Вар. 1/Вар. 2";
else $txt = '';
$pdf->Cell(35,5,$txt,0,0,'C');
$txt = "кол-во";
$pdf->Cell(30,5,$txt,0,0,'C');
$txt = "кол-во";
$pdf->Cell(30,5,$txt,0,0,'C');
$txt = "кол-во";
$pdf->Cell(30,5,$txt,0,0,'C');
$txt = "кол-во";
$pdf->Cell(30,5,$txt,0,1,'C');

$pdf->Ln(2);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Line($valX, $valY, $valX+190, $valY);
$pdf->Ln(2);

$pdf->SetFont('Tahoma', '', 8);
for( $i=0; $i<$arr['num_vopr']; ++$i) {
	      $q = $i+1;
          $txt = $q.". (id ".$arr['arr_vop'][$i]['idVopros'].") ".$arr['arr_vop'][$i]['nameVopros'];
          $pdf->Cell(40,5,$txt,0,0,'L');
		  if ($arr['type'] == 'FICTION') {
			     
			     foreach ( $arr['w'] as $id => $wtmp) {
			            if ($id == $arr['arr_vop'][$i]['idVopros']) {
			            $w = $wtmp;
						   }
				        }
		         switch ($w) {
                                     case '0':
                                     $w = "100% / 0%";
                                     break;
									 case '1':
                                     $w = "90% / 10%";
                                     break;
									 case '2':
                                     $w = "80% / 20%";
                                     break;
									 case '3':
                                     $w = "70% / 30%";
                                     break;
									 case '4':
                                     $w = "60% / 40%";
                                     break;
									 case '5':
                                     $w = "50% / 50%";
                                     break;
									 case '6':
                                     $w = "40% / 60%";
                                     break;
									 case '7':
                                     $w = "30% / 70%";
                                     break;
									 case '8':
                                     $w = "20% / 80%";
                                     break;
									 case '9':
                                     $w = "10% / 90%";
                                     break;
									 case '10':
                                     $w = "0% / 100%";
                                     break;
									 default:
									 $w = '-//-';
                                    	}
		  }
		  else $w = '';
		  $pdf->Cell(35,5,$w,0,0,'C');
		  
		  $txt = $arr['arr_vop'][$i]['v1'];
          $pdf->Cell(30,5,$txt,0,0,'C');
		  $txt = $arr['arr_vop'][$i]['v2'];
          $pdf->Cell(30,5,$txt,0,0,'C');
		  $txt = $arr['arr_vop'][$i]['v3'];
          $pdf->Cell(30,5,$txt,0,0,'C');
		  $txt = $arr['arr_vop'][$i]['v4'];
          $pdf->Cell(30,5,$txt,0,1,'C');

}

$pdf->Ln(2);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Line($valX, $valY, $valX+190, $valY);
$pdf->Ln(2);

$txt = "check: 1234567890";
$pdf->Cell(40,5,$txt,1,1,'C');



$dir = __DIR__;
$file = $dir."/tmp/".$filename;
$pdf->Output($file, 'F');


echo "print/tmp/".$filename;
//var_dump(iconv_get_encoding('all'));
?>