<?php  //OPERATOR head.php - шаблон шапки
    header('Content-type: text/html; charset=utf-8');
	session_start();
	require 'functions.php';
    

	$panel = $info = $replace = $logout = $checkLog = '';
	$display = 'block';
    
	if(!isset($_SESSION['logedin'])) {
	$logedin = FALSE;
	}
    else {
	$logedin = TRUE;
	$panel = "<div id ='panel' >Панель оператора тестирования</div>";
	$logout = "<div id='logout'><a href='logout.php'>Выйти</a></div>";
    $info = "<div class='tech_info'>Ваш IP: ".$_SERVER['REMOTE_ADDR']."</div>";
	$display = "none";
	}
	
	if(!$logedin) $checkLog = "<script>location.replace(site);</script>"; //Авторизация!
	
?>	
<!DOCTYPE HTML>
<html>
 <head>
  <meta charset="utf-8">
  <title>Тестирование</title>
  <link rel='stylesheet' type='text/css' href='<?=$site ?>/css/style.css' />
  <script src='<?=$site ?>/js/jquery-3.3.1.min.js'></script>
  <script src='<?=$site ?>/js/functions.js'></script>
  <!-- script src='<?=$site ?>/js/loader.js'></script -->
  <script src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
   
    <div id='banner'>
    <p><a href='<?=$site ?>' >Social Testing</a></p>
	<?=$panel ?>
	<?=$logout ?>
	<?=$info ?>
   </div>
   <hr>
<?php 	//вывод PHP error
    $err_common = error_get_last();
	print_r($err_common);
	
?>

