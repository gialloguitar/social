<?php  //SERCH.PHP - results of search for Operator
require 'head.php';
echo $checkLog; //Проверяем вход Оператора
$td_vop = $flush = $thead = $tfoot = "";

$result  = queryMysql("SELECT * FROM buffer;");
$rows    = mysqli_num_rows($result);
$credits = "Вопросов в буфере: ".$rows;
if ($rows > 0) $flush = "<input type='button' value='очистить буфер' onClick='flushBuffer();'/>";

//query SEARCH
if (!empty($_POST['query'])) { 
    $result = search($_POST['query']);
    $rows = mysqli_num_rows($result);
	  if($rows == 0) $search_result = "ничего не найдено, измените запрос";
	  else {
      $search_result = "Найдено элементов : ".$rows;
      $thead = "<table><thead><tr>
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
				<th></th></tr></thead><tbody>";
      $tfoot = "</tbody></table><hr>";
	  }	  
}
else $search_result = "ничего не найдено, измените запрос";
?>
<br>
<div id='content'>
<h1>Результаты поиска</h1>
<div id='menu'>
<div class='make'><a href='<?=$site ?>'>Меню</a></div>
<div class='make'><a href='questions.php'>Вопросы</a></div>
<div class='make'><a href='results.php'>Результаты</a></div>
<div id='makeVopros' class='make'><a href='makequestion.php'>Добавить вопрос</a></div>
<div id='delAll' class='make'><a href=# onClick='delAllVopros();' >Удалить все вопросы</a></div>
<form id='search' name="search" method="post" action="search.php">
    <input type="search" name="query" placeholder="Поиск">
    <button type="submit">Найти вопрос</button> 
</form>
</div>
<div id='credits'><div id='buffer'><?=$credits ?></div>
<a href='maketesting.php' ><b><input type='button' value='управление тестированием' /></b></a>
<div id='flush'><?=$flush ?></div>
</div>
<div id='wrap_question'>
<?php 
echo $search_result."<br>\n"; 
echo $thead;
//Выводим результаты поиска   
for( $i = 0; $i < $rows  ; ++$i) {
       mysqli_data_seek($result, $i);  
       $row = mysqli_fetch_assoc($result);  
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
echo $tfoot;
?>
</div>
</div> <!--  CONTENT -->
<br>
<?php
require 'footer.php';
?>
