<?php //questions.php Загрузка вопросов для тестирования
require 'head.php';
echo $checkLog; //Проверяем вход Оператора

$err_msg = $err_file = $remove_img = $tmp_id = "";
$Vopros = $tmpVopros = array();
if(isset($err_common) && 
         $err_common['type'] == 2) $err_file = "<span id='err_msg'>Неприемлемый формат изображения. Файл должен быть: JPG, PNG, GIF, TIFF и не больше 5 МБ.</span>";
if(isset($_GET['id'])) {  //redirect to EDITOR VOPROS
echo "<script>location.replace('editquestion.php?id=".$_GET['id']."');</script>";
}
//CREATE VOPROS
if(!empty($_POST)) {
	foreach ($_POST as $i) {
	   $i = fixString($i);
	   $Vopros []= $i;
               }
if($_POST['nameVopros'] == '' || 
   $_POST['textVopros'] == '' || 
   $_POST['variant1'] == '' || 
   $_POST['variant2'] == '') $err_msg = "<span id='err_msg'>Обязательно для заполнения!</span>";
else {
queryMysql("INSERT INTO questions(nameVopros,textVopros,variant1,variant2) VALUES('$Vopros[0]','$Vopros[1]','$Vopros[2]','$Vopros[3]');");
$result = queryMysql("SELECT idVopros FROM questions;");
$rows = mysqli_num_rows($result);
mysqli_data_seek($result, $rows-1);
$row = mysqli_fetch_assoc($result);
$idVopros = $row['idVopros'];
echo "В базу успешно добавлен вопрос с ID: ".$idVopros;

/*$idVopros = mysqli_insert_id($result);
mysqli_stmt_close($result); */

//UPLOAD IMG

if ($_FILES && 
    $_FILES['imgVopros']['size'] > 5) {
	$fsize = $_FILES['imgVopros']['size'];
	switch($_FILES['imgVopros']['type'])
	{
		case 'image/jpeg' : $t = "jpg"; break;
		case 'image/gif'  : $t = "gif"; break;
		case 'image/png'  : $t = "png"; break;
		case 'image/tiff' : $t = "tif"; break;
		default           : $t = '';    break; 
	}	
	   if($t && $fsize < 5000000) {
	   $filename = "img/".$idVopros."_".$_FILES['imgVopros']['name'];
	   move_uploaded_file($_FILES['imgVopros']['tmp_name'], $filename);
	   $Vopros []= $filename;
	   queryMysql("UPDATE questions  SET imgVopros = '$Vopros[4]' WHERE idVopros = '$idVopros';");
	         }
       else {
		queryMysql("DELETE FROM questions WHERE idVopros = '$idVopros';");
		//$err_file = "<span id='err_msg'>Неприемлемый формат изображения. Файл должен быть: JPG, PNG, GIF, TIFF и не больше 5 МБ.</span>";			 
	     }
     }	  
  }
}

?>
<br>
<div id='content'>
  <h1>Создайте новый вопрос</h1>
  <div id='menu'>
<div class='make'><a href='<?=$site ?>'>Меню</a></div>
<div class='make'><a href='questions.php'>Вопросы</a></div>
<div class='make'><a href='results.php'>Результаты</a></div>
<form id='search' name="search" method="post" action="search.php">
    <input type="search" name="query" placeholder="Поиск">
    <button type="submit">Найти вопрос</button> 
</form>
</div>
  <div id='wrap_question'>
  <form method='POST' action='' enctype='multipart/form-data' >
 Введите название вопроса:<?=$err_msg ?><br>
 <input type='text' name='nameVopros' size=70><br>
 Вопрос:<?=$err_msg ?><br>
 <textarea rows=10 cols=50 name='textVopros' ></textarea><br>
 Картинка к вопросу:<br><?=$err_file ?>	
					<div class="upload-file-container">
				    <img id="img_min" src='' alt="IMG" /><input type="file" name="imgVopros" id="imgInput" ><br>
					<?=$remove_img ?>
					</div>				
					<script>$("#imgInput").change(function(){readURL(this);});</script>
 <br>
 Варианты ответов на вопрос:<?=$err_msg ?><br>
<ol>
<li><input type='text' name='variant1' size=20><br></li>
<li><input type='text' name='variant2' size=20><br></li>
</ol>
<input type='submit' class='make' value='Создать вопрос'>
</form>
<a href='questions.php'><input type='button' class='make' value='Отмена'></a>
  </div>
</div>
<br>

<?php
require 'footer.php';
?>