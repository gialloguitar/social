<?php  //CLIENT head.php - шаблон шапки
    header('Content-type: text/html; charset=utf-8');	
    session_start();
    require 'functions.php';

    $toRun = $toForm = $fname = $mname = $sname = $sex = $dbirth = $ip = $SID  = '';	
    
	
	if(isset($_SESSION['fname'])) {  //странная проверка, но в плане критерия вполне годится
		 $ip     = $_SERVER['REMOTE_ADDR']; //определеяем текущего пользователя по IP и сессии
		 $SID    = session_id();
         //извлекаем пользователя		 
		 $userInfo = queryMysql("SELECT * FROM users WHERE SID = '$SID' AND ip = '$ip' AND fname = '$_SESSION[fname]' AND sname = '$_SESSION[sname]';");
         mysqli_data_seek($userInfo, 0);
		 $userInfo = mysqli_fetch_assoc($userInfo);                 
				 $fname  = $userInfo['fname'];
		         $mname  = $userInfo['mname'];
		         $sname  = $userInfo['sname'];
				 $dbirth = $userInfo['dbirth'];
		         $sex    = $userInfo['sex'];
		                 if($userInfo['sex'] == 'M') $sex = "муж.";
                         else $sex = "жен.";
				 $UID    = $_SESSION['UID'] = $userInfo['UID'];  //Уникальный идентификатор пользователя		 
		 
		 
		 
		 //извлекаем тест
		 $testing   = queryMysql("SELECT * FROM testing WHERE isActive = 'ACTIVE';");
         mysqli_data_seek($testing, 0);
         $testing = mysqli_fetch_assoc($testing);  
		         $type = $testing['type'];           // тип тестирования
                 $time_per = $testing['time_per'];   // на 1 вопрос
                 $test_ID  = $testing['test_ID'];
				 $num_users = $testing['num_users'];
				 
				 if($testing['type'] == 'BLIND') $indicator = "style=\"display: none;\"";
				 else $indicator = '';
				 
				 $ID_questions = explode("_", $testing['ID_questions']);  //  массив с ID вопросов в текущем тестировании
                 $IDs = count($ID_questions);  //их кол-во
                    $c = 0;    
					foreach ($ID_questions as $IDw) {
						if($type == 'FICTION') {
							$ID = strstr($IDw, 'w', true);
							$w  = substr(strstr($IDw, 'w'), 1);
							$sql_questions = queryMysql("SELECT * FROM questions WHERE idVopros = '$ID';");
							mysqli_data_seek($sql_questions, 0);
							$questions []= mysqli_fetch_assoc($sql_questions); ///ИЗВЛЕКЛИ ВОПРОСЫ
							$questions [$c]['weight'] = $w; //ДОБАВЛЯЕМ ВЕС ВОПРОСА
							++$c;
						}
                        else {
						$ID = $IDw;						
                        $sql_questions = queryMysql("SELECT * FROM questions WHERE idVopros = '$ID';");
						mysqli_data_seek($sql_questions, 0);
						$questions []= mysqli_fetch_assoc($sql_questions); //ИЗВЛЕКЛИ ВОПРОСЫ
						}
                        }
		 //на всякий случай пересоздаем переменные сессии			
         $_SESSION['questions'] = $questions;  //грузим в сессию полученый выше массив с вопросами текущего теста и т.д.
		 $_SESSION['indicator'] = $indicator;
		 $_SESSION['type_t']    = $type;
		 $_SESSION['test_ID']   = $test_ID;
		 $_SESSION['num_users'] = $num_users;
         $toRun  = "<script>location.replace('run.php')</script>";	//если сессия есть, то принудительно со всех страниц бросаем в тестирование 	 
	}
	else $toForm = "<script>location.replace(client)</script>";     // ...иначе пусть ждет или вводит свои данные
	
?>	
<!DOCTYPE HTML>
<html>
 <head>
  <meta charset="utf-8">
  <title>Клиент</title>
  <link rel='stylesheet' type='text/css' href='<?=$site ?>/css/style.css' />
  <script src='<?=$site ?>/js/jquery-3.3.1.min.js'></script>
  <script src='<?=$site ?>/js/functions.js'></script>
</head>
<body>
   
    <div id='banner_cl'>
    <p><a href='/client' >Client of Social Testing</a></p>
   </div>
   <hr>
<?php 	//вывод PHP error
    $err_common = error_get_last();
	print_r($err_common);
	//print_r($_SESSION);
?>

