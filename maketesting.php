<?php //maketesting.php Создаем тестирование
require 'head.php';
echo $checkLog; //Проверяем вход Оператора
$err_msg = $start_timer = $act_test = $testing_stat = '';
 	
//CREATE TESTING
if(!empty($_POST)) {
			   
if($_POST['IDs'] == '' ||
   $_POST['time'] == '' ||
   $_POST['type'] == '' ||
   $_POST['num_users'] < 1 ) $err_msg = "Выберите вопросы и заполните необходимые поля!";
else {   		
             
			 $IDs       = $_POST['IDs'];
             $type      = $_POST['type'];			 
             $time      = fixString($_POST['time']);           
             $num_users = fixString($_POST['num_users']);
			 $result = queryMysql("SELECT * FROM testing WHERE isActive = 'ACTIVE';");
             $rows = mysqli_num_rows($result);
			 mysqli_data_seek($result, 0);
			 $row = mysqli_fetch_assoc($result);
             if($rows !== 0) $act_test = "<b>Имеется активное тестирование от ".$row['Tdate']."<br>Сперва деактивируйте его.</b><input type='button' class='make' onClick='unsetTest();' value='Disactive тестирование' />";
             else {
		     $act_test = '';
						
			 
			     //создаем строку ID вопросов с учетом их веса в формате IDwW_ ...
			    if($type == 'FICTION') {
			 	   $IDs = explode("_", $IDs);
				   foreach($IDs as $i) {
				      $temp []= $i."w".$_POST[$i];
				        }
				      $IDs = implode("_", $temp);
				     }				
			
			
			 queryMysql("INSERT INTO testing (ID_questions, num_users, time_per, type, isActive) VALUES('$IDs','$num_users','$time','$type','ACTIVE');");
			 $process = new Process('php '.$dir.'/ws/server.php');			
              
			   if ($process->status()) {				
				   $wsStatus = "WS сервер запущен PID: ".$process->getPid()." <input class='make' type='button' onClick='stopServerWS(".$process->getPid().");' value='Выключить сервер' />";
				   }
               else $wsStatus = "WS сервер не удалось запустить";
               unset($_POST);      
		     //exec('/usr/bin/php /home/social/www/chat/server.php > /dev/null &', $outStart, $retStart);
			     }	 

	}
}

//Выводим служебную информацию
exec('ps aux | grep server.php', $out);
	if(count($out) < 3)	$wsStatus = "WS сервер не запущен";
	else {
		$out = explode(" ",$out[0]);
		//echo "echo: ".$out[1];
		if($out[0] == 'www-data') $wsStatus = "WS сервер запущен PID:".$out[1]." <input class='make' type='button' onClick='stopServerWS(".$out[1].");' value='Выключить сервер' />";
		else $wsStatus = "Имеется открытый процесс WS-сервера от имени: ".$out[0]."<br>Обратитесь к системному администратору.";
	}

$result = queryMysql("SELECT * FROM testing");
$rows = mysqli_num_rows($result);
  if($rows !== 0) $del_test = "<b>Тесты в базе: ".$rows." шт.</b><br>";
  else $del_test = '';
$result = queryMysql("SELECT * FROM testing WHERE isActive = 'ACTIVE'");
$rows = mysqli_num_rows($result);
  if($rows == 1) {
	  mysqli_data_seek($result, 0);
	  $row = mysqli_fetch_assoc($result);
	  $info_test = "<b>АКТИВНО ТЕСТИРОВАНИЕ! </b><input type='button' class='make' onClick='unsetTest();' value='Disactive тестирование' />";
	  $type = typeTest($row['type']);
		 
	  $q = explode("_", $row['ID_questions']);
      $num_q = count($q);	//кол-во вопросов  
      $testing_stat = "Статисика активного тестирования:<br>Дата создания: ".$row['Tdate']."<br>Кол-во вопросов: ".$num_q."<br>Тип: ".$type."<br>Кол-во участников: ".$row['num_users']."<div id='testing_stat'></div><script>trapStatTest(".$row['num_users'].",".$row['test_ID'].",".$num_q.");</script>";
  }
  elseif($rows > 1) $info_test = "<b>Обнаружено несколько активных тестирований, удалите!</b>";
  else {
	  $info_test = 'Нет активного тестирования';
	  $testing_stat = '';
  }	  

?>
<br>
<div id='content'>
<div id='menu'>
<div class='make'><a href='<?=$site ?>'>Меню</a></div>
<div class='make'><a href='questions.php'>Вопросы</a></div>
<div class='make'><a href='results.php'>Результаты</a></div>
</div>
<div id='status'>
<div id='statusWS'><?=$wsStatus ?></div>
<div id='statusTest'><?=$info_test ?></div>
</div>
<div id='wrap_question'>
<span id='err_msg'><?=$err_msg ?></span><br>
Выбраны следующие вопросы:<br>
<form action='' method='POST' onSubmit="validTestingData(this);" >
<div id='set'>
<?php  //выводим вопросы
$result = queryMysql("SELECT * FROM buffer;");
$rows = mysqli_num_rows($result);
if($rows == 0) echo "<b>Вы не выбрали ни одного вопроса</b>";
else {
   $q=1;
   $IDs = '';
   for ($i=0; $i < $rows; ++$i) {
       mysqli_data_seek($result, $i);
	   $row = mysqli_fetch_array($result);
	   $IDs [] = $row[0];
	   echo "<b style=\"display: inline-block; width: 40px\">".$q.".</b>название:  <b>".$row[1]."</b>   (id: ".$row[0].")<br><div class=weight >Укажите вес в процентах :<br>
				 <select name=".$row[0]." required >
				 <option value=0>100% / 0%</option>
				 <option value=1>90% / 10%</option>
		         <option value=2>80% / 20%</option>
		         <option value=3>70% / 30%</option>
		         <option value=4>60% / 40%</option>
			     <option value=5>50% / 50%</option>
			     <option value=6>40% / 60%</option>
			     <option value=7>30% / 70%</option>
			     <option value=8>20% / 80%</option>
				 <option value=9>10% / 90%</option>
				 <option value=10>0% /100%</option>
                 </select> , вероятность для Варианта 1 и Варианта 2 соответственно.</div><br>";
       ++$q;	   
   }
   $IDs = implode("_", $IDs);
   echo "<input style=\"display:none;\" type=text name='IDs' value='".$IDs."' />";
}
 ?>
</div>
<hr align="left" width="550">
Укажите количество экзаменуемых (По умолчанию: 1 участник):  <input type='text' name='num_users' value='1' size=5 /><br>
Установите время ответа на вопрос (По умолчанию: 30 секунд):  <input type='text' name='time' value='30' size=5 /><br>
Укажите тип тестирования (По умолчанию: Слепое):<br>
<input type='radio' name='type' value='BLIND' onClick='setWeight(this.value);' checked />Слепое (индикатор ответов остальных участников отключен)<br> 
<input type='radio' name='type' value='TRUE' onClick='setWeight(this.value);' />Правдивое<br>
<input type='radio' name='type' value='FALSE' onClick='setWeight(this.value);' />Инверсия ответов<br>
<input type='radio' name='type' value='FICTION' onClick='setWeight(this.value);' />Фиктивное (нужно указать вес для каждого варианта во всех вопросах)<br>
                       
<input type='submit' class='make' value='Запустить тестирование!' />                             
</form>
<br>
<div class='ok' id='ok1'>
<?=$act_test ?>
</div>
<div class='ok' id='ok2'>
<?=$del_test ?>
</div>
<div class='ok' id='ok3'>
<a href='results.php' ><input type='button' class='make' value='Перейти к результатам' /></a>
</div>
<div id='wrap_testing_stat'><?=$testing_stat ?></div>
</div> <!--WRAP -->
</div> <!--CONTENT -->
<br>
<?php
require 'footer.php';
?>