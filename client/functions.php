<?php //CLIENT functions.php - Settings for DB & some Functions 

$host = $_SERVER['HTTP_HOST'];
$site = "http://".$host;
//////////////MySQL
$dbhost = 'localhost';
$dbname = 'social_st';
$dbuser = 'social';
$dbpass = '123456';
$connection = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($connection->connect_error) die ($connection->connect_error);
function queryMysql($query){
	global $connection;
	$result = $connection->query($query);
	if(!$result) die ($connection->error);
	return $result;
}
queryMysql("SET NAMES 'utf8' COLLATE 'utf8_general_ci';");
$result = queryMysql("SELECT * FROM testing WHERE isActive = 'ACTIVE';");
$rows = mysqli_num_rows($result);
  if($rows == 0) {
	  destroySession();
	  $toForm = "<script>location.replace(client)</script>"; 
  }
/////////////////

function fixDate($date) {
    $darr  = explode("-", $date);
	$d = $darr[2].".".$darr[1].".".$darr[0]." г. ";
    return $d;
	}
	
function fixString($var) {
	global $connection;
	$var = stripcslashes(htmlentities(strip_tags($var)));
	return $connection->real_escape_string($var);
}
function destroySession() {
	if(session_id()) {
    queryMysql("TRUNCATE temp;");    
	$_SESSION = Array();
	if(session_id() != "" || isset($_COOCKIE[session_name()])) setcookie(session_name(), session_id(), time()-2592000 );
	session_destroy(); }
    else exit;	
}
////////////////
 if(isset($_POST['fname']) &&
    isset($_POST['mname']) &&
	isset($_POST['sname']) &&
    isset($_POST['sex'])   &&
    isset($_POST['dbirth'])) {
		$result   = queryMysql("SELECT * FROM testing WHERE isActive = 'ACTIVE';");
		mysqli_data_seek($result, 0);
		$row    = mysqli_fetch_assoc($result);
		$num_t    = $row['num_users']; // установленное оператором кол-во испытуемых 
		$time_per = $row['time_per'];  // установленное оператором время на один вопрос
		$test_ID  = $row['test_ID'];   //ID проводимого тестирования
		$temp     = queryMysql("SELECT * FROM temp;");
		$temp     = mysqli_num_rows($temp);  //текущее количество вошедших пользователей, должно быть не больше устновленного опреатором
		        if($temp < $num_t){
		              $SID = session_id();
					  $ip  = $_SERVER['REMOTE_ADDR'];
		              queryMysql("INSERT INTO temp (sign, ip) VALUES ('$SID', '$ip');");
					  //важный момент - установка окружения сессии и переводим испытуемого в режим ожидания
		              $_SESSION['i'] = 0;  
		              $_SESSION['fname']     = fixString($_POST['fname']);
		              $_SESSION['mname']     = fixString($_POST['mname']);
		              $_SESSION['sname']     = fixString($_POST['sname']);
		              $_SESSION['sex']       = fixString($_POST['sex']);
		              $_SESSION['dbirth']    = fixString($_POST['dbirth']);
                      $_SESSION['num_users'] = $num_t;	
                      $_SESSION['time_per']	 = $time_per;
                      $_SESSION['test_ID']	 = $test_ID;		  
		              queryMysql("INSERT INTO users (fname, mname, sname, sex, dbirth, ip, SID) VALUES ('$_SESSION[fname]','$_SESSION[mname]','$_SESSION[sname]','$_SESSION[sex]','$_SESSION[dbirth]','$_SERVER[REMOTE_ADDR]','$SID');");
		 
		              echo "<script>location.replace('run.php')</script>";
		                          }
		     
	}

	
////////AJAX
if(isset($_POST['whatT'])) {
	$result = queryMysql("SELECT type FROM testing WHERE isActive = 'ACTIVE';");
	mysqli_data_seek($result, 0);
	$row = mysqli_fetch_assoc($result);
	echo $row['type'];
}
if(isset($_POST['wait'])) {
	    $result  = queryMysql("SELECT num_users,time_per FROM testing WHERE isActive = 'ACTIVE';");
		mysqli_data_seek($result, 0);
		$row  = mysqli_fetch_assoc($result);
		$num_users = $row['num_users'];
		$time_per  = $row['time_per'];
		$temp    = queryMysql("SELECT * FROM temp;");
		$temp    = mysqli_num_rows($temp);
		        if($temp == $num_users) echo $time_per;
}
if(isset($_POST['howNum'])) {
	session_start();
	
	echo $_SESSION['num_users']."_".$_SESSION['time_per'];
}	
if(isset($_POST['check'])) {	
$result = queryMysql("SELECT * FROM testing WHERE isActive = 'ACTIVE';");
$rows = mysqli_num_rows($result);
  if($rows !== 0){
	  mysqli_data_seek($result, 0);
	  $row = mysqli_fetch_assoc($result);
	  $temp    = queryMysql("SELECT * FROM temp;");
	  $temp    = mysqli_num_rows($temp);
	     if($temp >= $row['num_users']) echo "Превышен лимит участников тестирования из ".$row['num_users']." чел.<br>Дождитесь завершения.";
		 else {
			 $years = '';
			 $y = 1950;
					  while ($y < 2011) {
					  $years .= "<option>".$y."</option>";
					  ++$y;
					  }
					  $years .= "<option selected>2011</option>";
			 echo    "<div id='login_cl'>
                      <span id='err_msg'></span>			  
			          <form action='' method='POST' onSubmit=\"return validUserData(this);\"><br>
                      Введите ваши данные<br>
					  <span class='field'>Фамилия:<br></span><input maxlength=20 type='text' name='sname' /><br>
                      <span class='field'>Имя:<br></span><input maxlength=20 type='text' name='fname' /><br>
					  <span class='field'>Отчество:<br></span><input maxlength=20 type='text' name='mname' /><br>                     
					  <span class='field'>Ваш пол:<br>
					  Муж.
					  <input type='radio' name='sex' value='M'/>
					  Жен.
					  <input type='radio' name='sex' value='J'/></span><br>
					  <span class='field'>Год рождения: <!-- input type='date' name='dbirth' / -->
					  <select name='dbirth' required>".$years."</select>
					  </span><br>
					  <span class='field'><input type='submit' value='Начать тест' /></span>
                      </form></div>";
		 }
  }
  }
if(isset($_POST['otvet'])) {
	     session_start();
		 $i         = $_SESSION['i'];
		 $UID       = $_SESSION['UID'];
	     $type      = $_SESSION['type_t'];
		 $test_ID   = $_SESSION['test_ID'];
         $time_per  = $_SESSION['time_per'];
         $num_users = $_SESSION['num_users'];		 
         $questions = $_SESSION['questions'];
		 $indicator = $_SESSION['indicator'];
		 $idVopros  = $questions[$i]['idVopros'];
         $IDs       = count($questions);
		 
		 $result = queryMysql("SELECT * FROM questions WHERE idVopros = '$idVopros';");
		 mysqli_data_seek($result, 0);
		 $row = mysqli_fetch_assoc($result);
		 
		 if ($type !== 'FICTION') queryMysql("INSERT INTO results (UID, test_ID, idVopros, Type, Otvet, nameVopros, textVopros, imgVopros, variant1, variant2) VALUES ('$UID','$test_ID','$idVopros','$type','$_POST[otvet]','$row[nameVopros]','$row[textVopros]','$row[imgVopros]','$row[variant1]','$row[variant2]');");
		 else {
			 $weight    = $questions[$i]['weight'];
			 queryMysql("INSERT INTO results (UID, test_ID, idVopros, Type, Weight, Otvet, nameVopros, textVopros, imgVopros, variant1, variant2) VALUES ('$UID','$test_ID','$idVopros','$type','$weight','$_POST[otvet]','$row[nameVopros]','$row[textVopros]','$row[imgVopros]','$row[variant1]','$row[variant2]');");
		 }
		 
		 ++$i; $_SESSION['i'] = $i;
            
			if($i > $IDs - 1) {
			   echo "<b>Тестирование окончено. Спасибо!</b><br>
			   <div class='ws_resp' id='count_t' style=\"position: absolute; left: 10000px;\"></div>
			   <div id='timer' style=\"position: absolute; left: 10000px;\" ></div>
			   <input id=\"weight\" style=\"position: absolute; left: 10000px;\" type=\"text\" name=\"weight\" value=\"5\" />
			   <input id=\"num_users\" style=\"position: absolute; left: 10000px;\" type=\"text\" name=\"num_users\" value=\"1\" />
               <div class='ws_resp' id='v1'></div>
               <div class='ws_resp' id='v2'></div>
			   <div id='progress'  style=\"position: absolute; left: 10000px;\" >
               <div id='prog_v1' class='otvet'></div>
               <div id='prog_v2' class='otvet'></div>
               </div>";			   
			   destroySession();
		               }
		    else {  //echo IMG, Invert Variants, Weight
 			        if($questions[$i]['imgVopros'] == '') $img = '';
                    else $img =  "<div class=img_test><img id=img_min src=\"".$site."/".$questions[$i]['imgVopros']."\" /></div>";
					
			        if($type == 'TRUE' || $type == 'FICTION') { $v1 = 'v1'; $v2 = 'v2';}
			        else { $v1 = 'v2'; $v2 = 'v1';}					
				    
					if($type == 'FICTION') $weight = "<input id=\"weight\" style=\"position: absolute; left: 10000px;\" type=\"text\" name=\"weight\" value=".$questions[$i]['weight']." />";
					else $weight = '';
					$q = $i+1;
					
					
  echo "<form method='POST' action='' >
  <div id='timer'></div>
  Вопрос № ".$q."<br>
  <div id='vopros_text'>".$questions[$i]['textVopros']."</div>".$img.$weight."
  <input id=\"num_users\" style=\"position: absolute; left: 10000px;;\" type=\"text\" name=\"num_users\" value=".$num_users." />
  Выберите вариант ответа:<br>
  <div id='variants' class='wrap_variants'>
  <label class='radio'>
  <input class='variant_radio radio' type='radio' name='otvet' value=".$v1.">
  <div class='variant radio__text'>".$questions[$i]['variant1']."<br></div>
  </label>
  <label class='radio'>
  <input class='variant_radio radio' type='radio' name='otvet' value=".$v2.">
  <div class='variant radio__text'>".$questions[$i]['variant2']."<br></div>
  </label>
  </div>
  <div id='indicator_block' ".$indicator." >
  <div class='wrap_variants'>
  Как отвечают остальные участники:<br>
  <div class='variant' id='za_v1'>За вариант 1</div>
  <div class='ws_resp' id='v1'></div>
  <div class='variant' id='za_v2'>За вариант 2</div>
  <div class='ws_resp' id='v2'></div>
  </div>

  <div id='progress'>
     <div id='prog_v1' class='otvet'></div>
     <div id='prog_v2' class='otvet'></div>
  </div>
  <div class='variant'>Всего дано ответов <div class='ws_resp' id='count_t'></div></div></div><br>
  <input type='submit' value='Ответить'>
  </form>";  
		        }
		}
?>
