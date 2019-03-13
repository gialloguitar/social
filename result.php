<?php //RESULT.PHP for Operator детальный обзор одного теста
require 'head.php';   
echo $checkLog; //Проверяем вход Оператора
if(empty($_GET)) echo "<script>location.replace('results.php')</script>";

$test = @$_GET['test'];
$th = $makePdf = $voprosi_weight = "";

//Извлекаем нужный ТЕСТ
$result      = queryMysql("SELECT * FROM testing WHERE test_ID = $test;");
               mysqli_data_seek($result, 0);
$testing     = mysqli_fetch_assoc($result);  //инфа о тесте
$type = typeTest($testing['type']);

if($testing['type'] !== 'FICTION') $voprosi = explode("_", $testing['ID_questions']);
else {
$th = "<th>Вероятность<br>Вар. 1/Вар. 2:</th>";    									
$temp = explode("_", $testing['ID_questions']);
foreach($temp as $item) {
    $ID = strstr($item, 'w', true);
	$ID_W = explode("w", $item);
    $voprosi_weight_temp []= $ID_W;
	$voprosi []= $ID;	
             }
			 
	for($i=0; $i<count($voprosi_weight_temp); ++$i) {
    // массив вида ID_vopr : Weight
	$voprosi_weight [$voprosi_weight_temp[$i][0]] = $voprosi_weight_temp[$i][1];      
	}		
		 
   } //else
   
$num_vopr    = count($voprosi);

//Селект вопросы в тесте
$results = queryMysql("SELECT UID,Date,test_ID,nameVopros,results.idVopros,Otvet FROM results WHERE test_ID = '$test';");
$rows = mysqli_num_rows($results);
$s = $rows / $num_vopr;  //кол-во отвечающих
if($s == 0) {
	$num_users = "Это тестирование никто не проходил. Удалите его !";
    $makePdf = "display: none;";
	}
else $num_users = "Кол-во участников: ".$s." чел.";	

for ( $i = 0; $i < $num_vopr; ++$i ) {
    $resvopr = queryMysql("SELECT idVopros,nameVopros FROM results WHERE idVopros = $voprosi[$i];");  
    mysqli_data_seek($resvopr, 0);
    $arr_voprosi []= mysqli_fetch_assoc($resvopr);    
    }
	
?>
<div id='content' >
<h1>Отчет по тесту № <?=$test ?></h1>
<div id='menu'>
<div class='make'><a href='<?=$site ?>'>Меню</a></div>
<div class='make'><a href='questions.php'>Вопросы</a></div>
<div class='make'><a href='results.php'>Результаты</a></div>
</div>
Дата проведения теста <?=fixBigDate($testing['Tdate']) ?>, <?=$type ?> тестирование. <?=$num_users ?>
<div id='wrap_question'>
<div id='status' style="background-color: white; margin: 2px;">для просмотра детальной статистики выберите вопрос.</div>
<table>
<thead>
<tr>
<th>Название вопроса:</th>
<?=$th ?>
<th>Вариант №1<br>кол-во</th>
<th>Вариант №2<br>кол-во</th>
<th>Воздержался<br>кол-во</th>
<th>Таймаут<br>кол-во</th>
</tr>
</thead>
<tbody>
<?php
for ( $i = 0; $i < $num_vopr; ++$i ) {
	     if($s == 0) break;
	      $v1 = $v2 = $v3 = $v4 = 0;
		 $q = $i+1; 
		 for( $j = 0; $j < $rows; ++$j) {
		 mysqli_data_seek($results, $j);
		 $row = mysqli_fetch_assoc($results);
         		 if($arr_voprosi[$i]['idVopros'] == $row['idVopros']) {
					 $temp_id = $row['idVopros'];
					 $td = '';
				     switch ($row['Otvet']) {
                                     case 'v1':
                                     ++$v1;
                                     break;
                                     case 'v2':
                                     ++$v2;
                                     break;
                                     case 'Воздержался':
                                     ++$v3;
                                     break;
									 case 'timeout':
                                     ++$v4;
                                     break;
                                            }
											
	if($testing['type'] == 'FICTION'){
                    $w = $voprosi_weight[$temp_id];
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
		             $td = "<td>".$w."</td>";   										
	                 }  //if FICTION
	        }      
		 
	 }
   echo "<tr>
        <td><a href=\"statquestion.php?test=".$test."&amp;id=".$arr_voprosi[$i]['idVopros']."&amp;v1=".$v1."&amp;v2=".$v2."&amp;v3=".$v3."&amp;v4=".$v4."\" >".$q.". ".$arr_voprosi[$i]['nameVopros']."</a></td>".$td."
        <td>".$v1."</td>	
        <td>".$v2."</td>
        <td>".$v3."</td>	
        <td>".$v4."</td>	  
		</tr>";
        $arr_voprosi[$i]['v1'] = $v1;
		$arr_voprosi[$i]['v2'] = $v2;
		$arr_voprosi[$i]['v3'] = $v3;
		$arr_voprosi[$i]['v4'] = $v4;
		}
?>
</tbody>
</table>
<hr>
</div>
 <script>
   var arr = {
   "test_id"        : <?=$test ?>,
   "arr_vop"        : <?=$arr_voprosi=json_encode($arr_voprosi); ?>,
   "w"              : <?=$voprosi_weight=json_encode($voprosi_weight); ?>,	      
   "date"           : "<?=fixBigDate($testing['Tdate']); ?>",   
   "num_usr"        : <?=$testing['num_users'] ?>,
   "time"           : <?=$testing['time_per'] ?>,
   "type"           : "<?=$testing['type'] ?>",
   "num_vopr"       : <?=$num_vopr ?>
   };
   </script>
   <input type='button' class='make' style='<?=$makePdf ?>' value='создать PDF' onClick='makePDF(arr);'/>
   <input type='button' class='make' style="float: right;" onClick='delTest(<?=$test ?>)' value='Удалить результаты теста' />

</div>  <!-- CONTENT -->
<?php
echo $replace;
require 'footer.php';
?>