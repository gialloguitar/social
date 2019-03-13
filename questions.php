<?php //questions.php Все вопросы в базе
require 'head.php';
echo $checkLog; //Проверяем вход Оператора
$td_vop = $flush = "";

//проверяем буфер
$result  = queryMysql("SELECT * FROM buffer;");
$rows    = mysqli_num_rows($result);
$credits = "Вопросов в буфере: ".$rows;
if ($rows > 0) $flush = "<input type='button' value='очистить буфер' onClick='flushBuffer();'/>";
	
?>
<br>
<div id='content'>
<h1><a href='<?=$_SERVER['PHP_SELF'] ?>'>Все вопросы</a></h1>
<div id='menu'>
<div class='make'><a href='<?=$site ?>'>Меню</a></div>
<div class='make'><a href='results.php'>Результаты</a></div>
<div id='makeVopros' class='make'><a href='makequestion.php'>Добавить вопрос</a></div>
<div id='delAll' class='make'><a href=# onClick='delAllVopros();' >Удалить все вопросы</a></div>
<form id='search' name="search" method="post" action="search.php">
    <input type="search" name="query" placeholder="Поиск">
    <button type="submit">Найти вопрос</button> 
</form>

</div>
<div id='countTest' class='make'></div>
<div id='makeTest' class='make'><a href='maketesting.php' >Создать тестирование</a></div>
<div id='credits'><div id='buffer'><?=$credits ?></div>
<a href='maketesting.php' ><b><input type='button' value='управление тестированием' /></b></a>
<div id='flush'><?=$flush ?></div>
</div>
<div id='wrap_question' style="height: auto;">
<table>
<thead>
<tr>
<th>№</th>
<th>ID</th>
<th>Название</th>
<th>Текст вопроса</th>
<th>Изображение</th>
<th>Вариант<br>№1</th>
<th>Вариант<br>№2</th>
<th>Дата создания</th>
<th>Выберите вопросы<br>для теста</th>
<th></th>
<th></th>
</tr>
</thead>
<tbody>
<?php //Выводим вопросы по 10 шт.
$result = queryMysql("SELECT * FROM questions;");
$rows = mysqli_num_rows($result);
$per_page = 10; 
$next = $prev = $start = $end = '';

if($rows > $per_page) { //Вывод на несколько страниц
    $pages = floor($rows/$per_page) + 1;
	if(!isset($_GET['p'])) echo "<script>location.replace(site + location.pathname + '?p=1')</script>";
	$p = @$_GET['p'];
	$next = "<a href='".$_SERVER['PHP_SELF']."?p=".($p+1)."' >&rarr;</a>";
	$end  = "<a class='nav' href='".$_SERVER['PHP_SELF']."?p=".$pages."' >в конец</a>";
	if($p > 1) {
		$prev = "<a href='".$_SERVER['PHP_SELF']."?p=".($p-1)."' >&larr;</a>";
	    $start  = "<a class='nav' href='".$_SERVER['PHP_SELF']."?p=".(1)."' >в начало</a>";
	}
	
	if($p == $pages) $next = $end = '';
	    
	$seek = ($p-1) * $per_page;
for( $i = 0; $i < $per_page ; ++$i) {
	$j = $seek + $i;
    $q = $j+1;	
	mysqli_data_seek($result, $j);  
	if($j == $rows) break;
    $row = mysqli_fetch_assoc($result);  
    $td_vop += $row['idVopros']."_";
	$id_name = $row['idVopros']."+++".$row['nameVopros'];
if($row['imgVopros']) {
    $img = "<img src='".$row['imgVopros']."' id='Show_img' />";
                  }
else $img = "";

	echo "<tr class='tr_vop'>
	          <td><b style=\"display: inline-block; width: 40px\">".$q.".</b></td>
			  <td class='td_vop'>".$row['idVopros']."</td>
	          <td>".$row['nameVopros']."</td>
			  <td>".$row['textVopros']."</td>
			  <td>".$img."</td>
			  <td>".$row['variant1']."</td>
			  <td>".$row['variant2']."</td>
			  <td>".$row['Qdate']."</td>
			  <td><input id=".$row['idVopros']." class='buffer' type='button' value='Добавить' onClick=\"toBuffer('".$id_name."')\" ></td>
			  <td><a href='editquestion.php?id=".$row['idVopros']."' ><input type='button' value='Изменить' ></a></td>
			  <td><input type='button' value='Удалить' onClick=\"RemoveVopros(".$row['idVopros'].",'".$row['imgVopros']."',".$p.");\" ></td></tr>";
}
}
else {  //Вывод на одну страницу    
  for( $i = 0; $i < $rows  ; ++$i) {
       mysqli_data_seek($result, $i);  
       $row = mysqli_fetch_assoc($result);  
       //$td_vop += $row['idVopros']."_";
	   $id_name = $row['idVopros']."+++".$row['nameVopros'];
if($row['imgVopros']) {
    $img = "<img src='".$row['imgVopros']."' id='Show_img' />";
                  }
else $img = "";
$q = $i+1;
	echo "<tr class='tr_vop'>
	          <td><b style=\"display: inline-block; width: 40px\">".$q.".</b></td>
	          <td>".$row['idVopros']."</td>
	          <td>".$row['nameVopros']."</td>
			  <td>".$row['textVopros']."</td>
			  <td>".$img."</td>
			  <td>".$row['variant1']."</td>
			  <td>".$row['variant2']."</td>
			  <td>".$row['Qdate']."</td>
			  <td><input id=".$row['idVopros']." class='buffer' type='button' value='Добавить' onClick=\"toBuffer('".$id_name."')\" ></td>
			  <td><a href='editquestion.php?id=".$row['idVopros']."' ><input type='button' value='Изменить' ></a></td>
			  <td><input type='button' value='Удалить' onClick=\"RemoveVopros(".$row['idVopros'].",'".$row['imgVopros']."', 1);\" ></td></tr>";
  }
}     //else
?>
  </tbody>
</table>
<hr>
<!-- div id='tempTest' class='make'><=$td_vop ></div -->
  </div> <!-- WRAP -->
  <div id='pagin'>
<?=$start."  ".$prev."  Всего вопросов: ".$rows."  ".$next."  ".$end ?>
</div>
</div> <!--  CONTENT -->
<br>
<?php
require 'footer.php';
?>