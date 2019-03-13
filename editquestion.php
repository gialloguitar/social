<?php //questions.php Загрузка вопросов для тестирования
require 'head.php';
echo $checkLog; //Проверяем вход Оператора
$err_msg = $err_file = $remove_img = $tmp_id = "";
$H1 = "Создайте новый вопрос";
$button_vopros = "Создать вопрос";
$Vopros = $tmpVopros = array();

if(isset($err_common) && 
         $err_common['type'] == 2) $err_file = "<span id='err_msg'>Неприемлемый формат изображения. Файл должен быть: JPG, PNG, GIF, TIFF и не больше 5 МБ.</span>";


if(isset($_GET['id'])) {  //EDITOR VOPROS
$tmp_id = $_GET['id']; 
$H1 = "Вопрос № ".$tmp_id;
$button_vopros = "Сохранить";

$result = queryMysql("SELECT nameVopros,textVopros,imgVopros,variant1,variant2 FROM questions WHERE idVopros = '$tmp_id';");	
mysqli_data_seek($result,0);
$row = mysqli_fetch_assoc($result);

$tmpVopros['name'] = $row['nameVopros'];
$tmpVopros['text'] = $row['textVopros'];
$tmpVopros['v1'] = $row['variant1'];
$tmpVopros['v2'] = $row['variant2'];
$tmpVopros['img'] = $row['imgVopros'];

if($tmpVopros['img'] == '') $remove_img = "";
else $remove_img = "<input type='button' value='Удалить изображение' onClick=\"RemoveImg(".$tmp_id.",'".$tmpVopros['img']."');\" >";


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
queryMysql("UPDATE questions SET nameVopros = '$Vopros[0]',
          textVopros = '$Vopros[1]',
		  variant1 = '$Vopros[2]',
		  variant2 = '$Vopros[3]' WHERE idVopros = '$tmp_id';");

$tmpVopros['name'] = $Vopros['0'];
$tmpVopros['text'] = $Vopros['1'];
$tmpVopros['v1'] = $Vopros['2'];
$tmpVopros['v2'] = $Vopros['3'];	  

//UPLOAD IMG
if ($_FILES && $_FILES['imgVopros']['size'] > 5) {
	$fsize = $_FILES['imgVopros']['size'];
	switch($_FILES['imgVopros']['type'])
	{
		case 'image/jpeg' : $t = "jpg"; break;
		case 'image/gif'  : $t = "gif"; break;
		case 'image/png'  : $t = "png"; break;
		case 'image/tiff' : $t = "tif"; break;
		default           : $t = '';    break; 
	}	
	   if($t !== '' && $fsize < 5000000) {
	   $filename = "img/".$tmp_id."_".$_FILES['imgVopros']['name'];   
	   move_uploaded_file($_FILES['imgVopros']['tmp_name'], $filename);
	   queryMysql("UPDATE questions  SET imgVopros = '$filename' WHERE idVopros = '$tmp_id';");
	   $tmpVopros['img'] = $filename;
	   echo "Изменения сохранены!";
	         }
       else {
		$err_file = "<span id='err_msg'>Неприемлемый формат изображения. Файл должен быть: JPG, PNG, GIF, TIFF и не больше 5 МБ.</span>";			 
	     }
     }
//else print_r($_FILES['imgVopros']['error']);	  
}
} //!empty POST
} //isset GET id
else echo "<script>location.replace('makequestion.php');</script>"; //redirect to MAKE VOPROS

unset($Vopros);
?>
<br>
<div id='content'>
  <h1><?=$H1 ?></h1>
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
  <form method='POST' action='<?=$_SERVER['REQUEST_URI'] ?>' enctype='multipart/form-data' >
 Название вопроса:<?=$err_msg ?><br>
 <input type='text' name='nameVopros' value='<?=$tmpVopros['name'] ?>' size=70><br>
 Текст вопроса:<?=$err_msg ?><br>
 <textarea rows=10 cols=50 name='textVopros' ><?=$tmpVopros['text'] ?></textarea><br>
 Картинка к вопросу:<br><?=$err_file ?>	
					<div class="upload-file-container">
				    <img id="img_min" src='<?=$tmpVopros['img'] ?>' alt="IMG" />				<input type="file" name="imgVopros" id="imgInput" ><br>
					<?=$remove_img ?>
					</div>				
					<script>$("#imgInput").change(function(){readURL(this);});</script>
 <br>
 Варианты ответов на вопрос:<?=$err_msg ?><br>
<ol>
<li><input type='text' name='variant1' value='<?=$tmpVopros['v1'] ?>' size=20><br></li>
<li><input type='text' name='variant2' value='<?=$tmpVopros['v2'] ?>' size=20><br></li>
</ol>
 <input type='submit' class='make' value='<?=$button_vopros ?>'>
</form>
<br>
<a href='questions.php'><input type='button' class='make' value='Отмена'></a>
  </div>
</div>
<br>

<?php
require 'footer.php';
?>