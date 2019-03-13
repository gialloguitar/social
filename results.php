<?php //RESULTS.PHP for Operator
require 'head.php';   
echo $checkLog; //Проверяем вход Оператора
$date = $ty = $err = $set_date = $gets = '';

if((isset($_GET['Tdate']) && $_GET['Tdate'] !== '') || 
   (isset($_GET['Type']) && $_GET['Type'] !== '')) {

    $date = $_GET['Tdate']; $ty = $_GET['Type'];
    $result = queryMysql("SELECT * FROM testing WHERE type like '%$ty%' AND Tdate like '%$date%';");	
    $set_date = "Результаты по запросу";
	//<br>дата ".fixDate($date).", тип ".typeTest($ty);

}	
else {
	$result = queryMysql("SELECT * FROM testing;");
    $set_date = "Результаты за всё время";
	}

?>
<div id='content' >
<h1>Результаты всех пройденных тестов</h1>
<div id='menu'>
<div class='make'><a href='<?=$site ?>'>Меню</a></div>
<div class='make'><a href='questions.php'>Вопросы</a></div>
<div id='delAll' class='make'><a href=# onClick='delTests();' >Удалить все тесты</a></div>
</div>
<div id='ok2'></div>
<br>
<div id='wrap_question'>
<form class='inl_block' action='' method='GET'>
<div>
Для просмотра результатов выберите дату проведения тестирования:<br>
<input type='date' name='Tdate'  value=<?=$date ?> />
<?=$err ?>
</div>
<div>
Отсортируйте по типу:<br>
<select name='Type' >
                 <option value='' >-</option>
				 <option value='TRUE' >Правдивое</option>
				 <option value='FALSE' >Инверсия</option>
				 <option value='FICTION' >Фикция</option>
				 <option value='BLIND' >Слепое</option>
</select>
</div>
<input type='submit' value='Запрос' />				 
</form><br>
<?=$set_date ?>
<div id='set_testing'>
<?php 
$per_page = 10; 
$next = $prev = $start = $end = '';


$rows = mysqli_num_rows($result);
if($rows == 0) echo "ничего не найдено, измените запрос";
if($rows > $per_page) { //Вывод на несколько страниц
    $pages = floor($rows/$per_page) + 1;
	
	if($date !== '' || $ty !== '' ) {
		$gets = "&Tdate=".$date."&Type=".$ty;
	}
	else $gets = '';
	
	
	if(!isset($_GET['p'])) echo "<script>location.replace(site + location.pathname + '?p=1".$gets."')</script>";
	$p = @$_GET['p'];
	
	$next = "<a href='".$_SERVER['PHP_SELF']."?p=".($p+1).$gets."' >&rarr;</a>";
	$end  = "<a class='nav' href='".$_SERVER['PHP_SELF']."?p=".$pages.$gets."' >в конец</a>";
	if($p > 1) {
		$prev  = "<a href='".$_SERVER['PHP_SELF']."?p=".($p-1).$gets."' >&larr;</a>";
	    $start = "<a class='nav' href='".$_SERVER['PHP_SELF']."?p=".(1).$gets."' >в начало</a>";
	}
	
	if($p == $pages) $next = $end  = '';
	
	$seek = ($p-1) * $per_page;
	
 for( $i = 0; $i < $per_page  ; ++$i) {
       $j = $seek + $i;	
	   mysqli_data_seek($result, $j);  
	   if($j == $rows) break; 
       $test = mysqli_fetch_assoc($result);
	   $num_vopros = count(explode("_", $test['ID_questions']));
	   $type = typeTest($test['type']);
	   $date = fixBigDate($test['Tdate']);
	   $q = $j+1;
       echo "<b style=\"display: inline-block; width: 40px\">".$q.".</b>
	   <a href='result.php?test=".$test['test_ID']."' style=\"display: inline-block; width: 77%\">Дата: ".$date.", кол-во участников: ".$test['num_users'].", кол-во вопросов: ".$num_vopros.", время вопроса:  ".$test['time_per']." сек., тип: ".$type."</a>
	   <input type='button' class='make' style=\"margin-left: 10px;\" onClick='delTest(".$test['test_ID'].");' value='Удалить' /><br>";	   
    }
 }
else { //вывод на одну
	 for( $i = 0; $i < $rows  ; ++$i) {
       mysqli_data_seek($result, $i);  
       $test = mysqli_fetch_assoc($result);
	   $num_vopros = count(explode("_", $test['ID_questions']));
	   $type = typeTest($test['type']);
	   $date = fixBigDate($test['Tdate']);
	   $q = $i+1;
       echo "<b style=\"display: inline-block; width: 40px\">".$q.".</b>
	   <a href='result.php?test=".$test['test_ID']."' style=\"display: inline-block; width: 77%\">Дата: ".$date.", кол-во участников: ".$test['num_users'].", кол-во вопросов: ".$num_vopros.", время вопроса:  ".$test['time_per']." сек., тип: ".$type."</a>
	   <input type='button' class='make' style=\"margin-left: 10px;\" onClick='delTest(".$test['test_ID'].");' value='Удалить' /><br>";	   
    }
	 
 }
?> 
</div>
</div>
  <div id='pagin'>
<?=$start."  ".$prev."  Всего результатов: ".$rows."  ".$next."  ".$end ?>
</div>
</div>
<?php
echo $replace;
require 'footer.php';
?>