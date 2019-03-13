<?php //PRINT_STAT.PHP выгрузка статистики	
require('tfpdf/diag.php');

$arr = json_decode($_POST['JSON'], true);
$nameVopros = str_replace(" ",'_', $arr['name']);
//$filename = $nameVopros.".pdf";
$filename = "TMP.pdf";

$pdf = new PDF_Diag();
$pdf->AddPage();


//Pie chart
// Add a Unicode font (uses UTF-8)
$pdf->AddFont('TimesNR','','times.ttf', true);
$pdf->AddFont('TahomaB','','tahomabd.ttf', true);
$pdf->AddFont('Tahoma','','tahoma.ttf', true);

$pdf->SetFont('TahomaB','',14);
$txt = "Сводная статистика для вопроса ID: ".$arr['id']." (".$arr['name'].")";
$pdf->MultiCell(0, 5, $txt, 0, 1);	
$pdf->Ln(5);

$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Line($valX, $valY, $valX+190, $valY);

$pdf->SetFont('Tahoma', '', 10);
if($arr['w'] < 0) $txt = $arr['type']." тестирование. Установленно время ответа на один вопрос: ".$arr['time']." сек. Кол-во участников: ".$arr['num'];
else $txt = $arr['type']." тестирование. Для этого вопроса была установлена вероятность: ".$arr['w']."\nпоказа Варианта № 1 и Варианта № 2 соответственно.\nУстановленно время ответа на один вопрос: ".$arr['time']." сек. Кол-во участников: ".$arr['num'];

$pdf->MultiCell(0, 5, $txt, 0, 1);
$pdf->Ln(2);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Line($valX, $valY, $valX+190, $valY);
$pdf->Ln(2);

$pdf->SetFont('TahomaB', '', 10);
$pdf->Cell(0, 5, 'Название вопроса:',0,1);
$pdf->SetFont('Tahoma', '', 10);
$pdf->MultiCell(80, 5, $arr['name'], 0, 1);

$pdf->SetFont('TahomaB', '', 10);
$pdf->Cell(0, 5, 'Текст вопроса:',0,1);
$pdf->SetFont('Tahoma', '', 10);
$pdf->MultiCell(80, 5, $arr['text'], 0, 1);

if($arr['img'] !== '') {
$pdf->SetFont('TahomaB', '', 10);
$pdf->Cell(0, 5, 'Картинка к вопросу:',0,1);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Image("../".$arr['img'],$valX,$valY, 40, 40);
$pdf->Ln(40);
}

$pdf->SetFont('TahomaB', '', 10);
$pdf->Cell(0, 5, 'Варианты ответов на вопрос:',0,1);
$pdf->SetFont('Tahoma', '', 10);
$pdf->MultiCell(60, 5, "1. ".$arr['var1'], 0, 1);
$pdf->MultiCell(60, 5, "2. ".$arr['var2'], 0, 1);

$pdf->Ln(2);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Line($valX, $valY, $valX+190, $valY);
$pdf->Ln(2);

$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->SetFont('TahomaB', '', 10);
$pdf->Cell(0, 5, 'Отчет по вопросу:',0,1);
$pdf->SetFont('Tahoma', '', 10);
$pdf->Cell(80, 5, '1-й вариант: '.$arr['v1'].' (из них Муж. '.$arr['v1_m'].', Жен.'.$arr['v1_w'].')', 0, 1);
$pdf->Cell(80, 5, '2-й вариант: '.$arr['v2'].' (из них Муж. '.$arr['v2_m'].', Жен.'.$arr['v2_w'].')' , 0, 1);
$pdf->Cell(80, 5, 'Воздержались: '.$arr['v3'].' (из них Муж. '.$arr['v3_m'].', Жен.'.$arr['v3_w'].')' , 0, 1);
$pdf->Cell(80, 5, 'Таймаут: '.$arr['v4'].' (из них Муж. '.$arr['v4_m'].', Жен.'.$arr['v4_w'].')' , 0, 1);
$pdf->Ln(2);

$pdf->SetXY($valX+100,$valY);
$pdf->SetFont('TahomaB', '', 10);
$pdf->Cell(0, 5, 'За всё время:',0,1);
$pdf->SetFont('Tahoma', '', 10);
$pdf->SetX($valX+100);
$pdf->Cell(0, 5, 'Правдивое',0,1);
$pdf->SetX($valX+100);
$pdf->Cell(80, 5, '1-й вариант: '.$arr['com_v1_true'].' (из них Муж. '.$arr['com_v1_true_m'].', Жен.'.$arr['com_v1_true_w'].')', 0, 1);
$pdf->SetX($valX+100);
$pdf->Cell(80, 5, '2-й вариант: '.$arr['com_v2_true'].' (из них Муж. '.$arr['com_v2_true_m'].', Жен.'.$arr['com_v2_true_w'].')' , 0, 1);
$pdf->SetX($valX+100);
$pdf->Cell(80, 5, 'Воздержались: '.$arr['com_v3_true'].' (из них Муж. '.$arr['com_v3_true_m'].', Жен.'.$arr['com_v3_true_w'].')' , 0, 1);
$pdf->SetX($valX+100);
$pdf->Cell(80, 5, 'Таймаут: '.$arr['com_v4_true'].' (из них Муж. '.$arr['com_v4_true_m'].', Жен.'.$arr['com_v4_true_w'].')' , 0, 1);
$pdf->Ln(2);

$pdf->SetX($valX+100);
$pdf->Cell(0, 5, 'Инвертированное',0,1);
$pdf->SetX($valX+100);
$pdf->Cell(80, 5, '1-й вариант: '.$arr['com_v1_false'].' (из них Муж. '.$arr['com_v1_false_m'].', Жен.'.$arr['com_v1_false_w'].')', 0, 1);
$pdf->SetX($valX+100);
$pdf->Cell(80, 5, '2-й вариант: '.$arr['com_v2_false'].' (из них Муж. '.$arr['com_v2_false_m'].', Жен.'.$arr['com_v2_false_w'].')' , 0, 1);
$pdf->SetX($valX+100);
$pdf->Cell(80, 5, 'Воздержались: '.$arr['com_v3_false'].' (из них Муж. '.$arr['com_v3_false_m'].', Жен.'.$arr['com_v3_false_w'].')' , 0, 1);
$pdf->SetX($valX+100);
$pdf->Cell(80, 5, 'Таймаут: '.$arr['com_v4_false'].' (из них Муж. '.$arr['com_v4_false_m'].', Жен.'.$arr['com_v4_false_w'].')' , 0, 1);
$pdf->Ln(2);

$pdf->SetX($valX+100);
$pdf->Cell(0, 5, 'Слепое',0,1);
$pdf->SetX($valX+100);
$pdf->Cell(80, 5, '1-й вариант: '.$arr['com_v1_bl'].' (из них Муж. '.$arr['com_v1_bl_m'].', Жен.'.$arr['com_v1_bl_w'].')', 0, 1);
$pdf->SetX($valX+100);
$pdf->Cell(80, 5, '2-й вариант: '.$arr['com_v2_bl'].' (из них Муж. '.$arr['com_v2_bl_m'].', Жен.'.$arr['com_v2_bl_w'].')' , 0, 1);
$pdf->SetX($valX+100);
$pdf->Cell(80, 5, 'Воздержались: '.$arr['com_v3_bl'].' (из них Муж. '.$arr['com_v3_bl_m'].', Жен.'.$arr['com_v3_bl_w'].')' , 0, 1);
$pdf->SetX($valX+100);
$pdf->Cell(80, 5, 'Таймаут: '.$arr['com_v4_bl'].' (из них Муж. '.$arr['com_v4_bl_m'].', Жен.'.$arr['com_v4_bl_w'].')' , 0, 1);
$pdf->AddPage();

////Графическое представление
/////текущее
$pdf->SetFont('TahomaB','',14);
$txt = "Графическая статистика для вопроса ID: ".$arr['id']." (".$arr['name'].")\nв текущем тестировании.";
$pdf->MultiCell(0, 5, $txt, 0, 1);	
$pdf->Ln(5);

$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Line($valX, $valY, $valX+190, $valY);



$data = array('1-й вариант' => $arr['v1'], '2-й вариант' => $arr['v2'], 'Воздержались' => $arr['v3'], 'Таймаут' => $arr['v4']);

$pdf->SetFont('TahomaB','',10);
$pdf->Cell(0, 5, 'Диаграмма', 0, 1);
$pdf->SetFont('Tahoma','',10);
$pdf->Cell(30, 5, '1-й вариант: ');
$pdf->Cell(15, 5, $arr['v1'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, '2-йвариант:');
$pdf->Cell(15, 5, $arr['v2'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Воздержались: ');
$pdf->Cell(15, 5, $arr['v3'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Таймаут:');
$pdf->Cell(15, 5, $arr['v4'], 0, 0, 'R');
$pdf->Ln();
$pdf->Ln(8);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->SetXY(90, $valY);
$col1=array(100,100,255);
$col2=array(255,100,100);
$col3=array(255,255,100);
$col4=array(100,255,255);
$pdf->PieChart(130, 50, $data, '%l (%p)', array($col1,$col2,$col3,$col4));
$pdf->SetXY($valX, $valY + 40);

//Bar diagram
$pdf->SetFont('TahomaB','',10);
$pdf->Cell(0, 5, 'Гистограмма', 0, 1);
$pdf->Ln(8);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->SetFont('Tahoma','',10);
$pdf->BarDiagram(150, 50, $data, '%l : %v (%p)', array($arr['v1'],$arr['v2'],$arr['v3'],$arr['v4']));
$pdf->SetXY($valX, $valY + 80);
$pdf->AddPage();


/////за всё время правдивое
$pdf->SetFont('TahomaB','',14);
$txt = "Графическая статистика для вопроса ID: ".$arr['id']." (".$arr['name'].")\nЗа всё время при правдивом тестировании.";
$pdf->MultiCell(0, 5, $txt, 0, 1);	
$pdf->Ln(5);

$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Line($valX, $valY, $valX+190, $valY);



$data = array('1-й вариант' => $arr['com_v1_true'], '2-й вариант' => $arr['com_v2_true'], 'Воздержались' => $arr['com_v3_true'], 'Таймаут' => $arr['com_v4_true']);

$pdf->SetFont('TahomaB','',10);
$pdf->Cell(0, 5, 'Диаграмма', 0, 1);
$pdf->SetFont('Tahoma','',10);
$pdf->Cell(30, 5, '1-й вариант: ');
$pdf->Cell(15, 5, $arr['com_v1_true'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, '2-йвариант:');
$pdf->Cell(15, 5, $arr['com_v2_true'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Воздержались: ');
$pdf->Cell(15, 5, $arr['com_v3_true'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Таймаут:');
$pdf->Cell(15, 5, $arr['com_v4_true'], 0, 0, 'R');
$pdf->Ln();
$pdf->Ln(8);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->SetXY(90, $valY);
$col1=array(100,100,255);
$col2=array(255,100,100);
$col3=array(255,255,100);
$col4=array(100,255,255);
$pdf->PieChart(130, 50, $data, '%l (%p)', array($col1,$col2,$col3,$col4));
$pdf->SetXY($valX, $valY + 40);

//Bar diagram
$pdf->SetFont('TahomaB','',10);
$pdf->Cell(0, 5, 'Гистограмма', 0, 1);
$pdf->Ln(8);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->SetFont('Tahoma','',10);
$pdf->BarDiagram(150, 50, $data, '%l : %v (%p)', array($arr['com_v1_true'],$arr['com_v2_true'],$arr['com_v3_true'],$arr['com_v4_true']));
$pdf->SetXY($valX, $valY + 80);
$pdf->AddPage();

/////за всё время инверсия
$pdf->SetFont('TahomaB','',14);
$txt = "Графическая статистика для вопроса ID: ".$arr['id']." (".$arr['name'].")\nЗа всё время при инвертированном тестировании.";
$pdf->MultiCell(0, 5, $txt, 0, 1);	
$pdf->Ln(5);

$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Line($valX, $valY, $valX+190, $valY);



$data = array('1-й вариант' => $arr['com_v1_false'], '2-й вариант' => $arr['com_v2_false'], 'Воздержались' => $arr['com_v3_false'], 'Таймаут' => $arr['com_v4_false']);

$pdf->SetFont('TahomaB','',10);
$pdf->Cell(0, 5, 'Диаграмма', 0, 1);
$pdf->SetFont('Tahoma','',10);
$pdf->Cell(30, 5, '1-й вариант: ');
$pdf->Cell(15, 5, $arr['com_v1_false'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, '2-йвариант:');
$pdf->Cell(15, 5, $arr['com_v2_false'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Воздержались: ');
$pdf->Cell(15, 5, $arr['com_v3_false'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Таймаут:');
$pdf->Cell(15, 5, $arr['com_v4_false'], 0, 0, 'R');
$pdf->Ln();
$pdf->Ln(8);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->SetXY(90, $valY);
$col1=array(100,100,255);
$col2=array(255,100,100);
$col3=array(255,255,100);
$col4=array(100,255,255);
$pdf->PieChart(130, 50, $data, '%l (%p)', array($col1,$col2,$col3,$col4));
$pdf->SetXY($valX, $valY + 40);

//Bar diagram
$pdf->SetFont('TahomaB','',10);
$pdf->Cell(0, 5, 'Гистограмма', 0, 1);
$pdf->Ln(8);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->SetFont('Tahoma','',10);
$pdf->BarDiagram(150, 50, $data, '%l : %v (%p)', array($arr['com_v1_false'],$arr['com_v2_false'],$arr['com_v3_false'],$arr['com_v4_false']));
$pdf->SetXY($valX, $valY + 80);
$pdf->AddPage();


/////за всё время слепое
$pdf->SetFont('TahomaB','',14);
$txt = "Графическая статистика для вопроса ID: ".$arr['id']." (".$arr['name'].")\nЗа всё время в слепом тестировании.";
$pdf->MultiCell(0, 5, $txt, 0, 1);	
$pdf->Ln(5);

$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->Line($valX, $valY, $valX+190, $valY);



$data = array('1-й вариант' => $arr['com_v1_bl'], '2-й вариант' => $arr['com_v2_bl'], 'Воздержались' => $arr['com_v3_bl'], 'Таймаут' => $arr['com_v4_bl']);

$pdf->SetFont('TahomaB','',10);
$pdf->Cell(0, 5, 'Диаграмма', 0, 1);
$pdf->SetFont('Tahoma','',10);
$pdf->Cell(30, 5, '1-й вариант: ');
$pdf->Cell(15, 5, $arr['com_v1_bl'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, '2-йвариант:');
$pdf->Cell(15, 5, $arr['com_v2_bl'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Воздержались: ');
$pdf->Cell(15, 5, $arr['com_v3_bl'], 0, 0, 'R');
$pdf->Ln();
$pdf->Cell(30, 5, 'Таймаут:');
$pdf->Cell(15, 5, $arr['com_v4_bl'], 0, 0, 'R');
$pdf->Ln();
$pdf->Ln(8);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->SetXY(90, $valY);
$col1=array(100,100,255);
$col2=array(255,100,100);
$col3=array(255,255,100);
$col4=array(100,255,255);
$pdf->PieChart(130, 50, $data, '%l (%p)', array($col1,$col2,$col3,$col4));
$pdf->SetXY($valX, $valY + 40);

//Bar diagram
$pdf->SetFont('TahomaB','',10);
$pdf->Cell(0, 5, 'Гистограмма', 0, 1);
$pdf->Ln(8);
$valX = $pdf->GetX();
$valY = $pdf->GetY();
$pdf->SetFont('Tahoma','',10);
$pdf->BarDiagram(150, 50, $data, '%l : %v (%p)', array($arr['com_v1_bl'],$arr['com_v2_bl'],$arr['com_v3_bl'],$arr['com_v4_bl']));
$pdf->SetXY($valX, $valY + 80);


$dir = __DIR__;
$file = $dir."/tmp/".$filename;
$pdf->Output($file, 'F');


echo "print/tmp/".$filename;
//var_dump(iconv_get_encoding('all'));
?>