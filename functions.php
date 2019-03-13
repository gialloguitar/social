<?php //OPERATOR functions.php - Settings for DB & some Functions 

$host = $_SERVER['HTTP_HOST'];
$site = "http://".$host;
$dir  = __DIR__;
//////MySQL
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
queryMysql("SET NAMES 'utf8' COLLATE 'utf8_general_ci';"); //приводим клиента базы к правильной кодировке
queryMysql("ALTER TABLE questions ORDER BY idVopros;"); //сортируем таблицы
queryMysql("ALTER TABLE testing ORDER BY Tdate;");
//////
/* An easy way to keep in track of external processes.
* Ever wanted to execute a process in php, but you still wanted to have somewhat controll of the process ? Well.. This is a way of doing it.
* @compability: Linux only. (Windows does not work).
* @author: Peec
*/
class Process{
    private $pid;
    private $command;

    public function __construct($cl=false){
        if ($cl != false){
            $this->command = $cl;
            $this->runCom();
        }
    }
    private function runCom(){
        $command = 'nohup '.$this->command.' > /dev/null 2>&1 & echo $!';
        exec($command ,$op);
        $this->pid = (int)$op[0];
    }

    public function setPid($pid){
        $this->pid = $pid;
    }

    public function getPid(){
        return $this->pid;
    }

    public function status(){
        $command = 'ps -p '.$this->pid;
        exec($command,$op);
        if (!isset($op[1]))return false;
        else return true;
    }

    public function start(){
        if ($this->command != '')$this->runCom();
        else return true;
    }

    public function stop(){
        $command = 'kill '.$this->pid;
        exec($command);
        if ($this->status() == false)return true;
        else return false;
    }
}

function fixString($var) {
	global $connection;
	$var = stripcslashes(htmlentities(strip_tags($var)));
	return $connection->real_escape_string($var);
}

function fixDate($date) {
    $darr  = explode("-", $date);
	$d = $darr[2].".".$darr[1].".".$darr[0]."г. ";
    return $d;
	}

function fixBigDate($dbdate) {
	return fixDate(substr($dbdate, 0, 10));
    
}	
	
function typeTest($type) {
           switch ($type) {
                                     case 'TRUE':
                                     return "Правдивое";
                                     break;
                                     case 'FALSE':
                                     return "Инвертированное";
                                     break;
                                     case 'FICTION':
                                     return "Фиктивное";
                                     break;	
                                     case 'BLIND':
                                     return "Слепое";
                                     break;										 
                                            }
}
function destroySession() {
    queryMysql("UPDATE users  SET ip = '' WHERE isAdmin = 1;");
	queryMysql("UPDATE users  SET SID = '' WHERE isAdmin = 1;");
	queryMysql("UPDATE testing SET isActive = '0' WHERE isActive = 'ACTIVE';");
	queryMysql("DELETE FROM results WHERE UID = 0;"); //удаляем грехи отцов
	
	$_SESSION = Array();
	if(session_id() != "" || isset($_COOCKIE[session_name()])) setcookie(session_name(), session_id(), time()-2592000 );
	session_destroy(); 
}

function search($query) {
          $query  = fixString($query);
		  $result = queryMysql("SELECT * FROM questions WHERE nameVopros like '%$query%' OR textVopros like '%$query%' OR idVopros like '%$query%' OR variant1 like '%$query%' OR variant2 like '%$query%';");
		  return $result;
}
////AJAX
    
	if(isset($_POST['remove'])) {
        if($_POST['remove'] == 'VOPROS') {		//удаляем вопрос
	        queryMysql("DELETE FROM questions WHERE idVopros = '".$_POST['id']."';");
	        exec("/bin/rm ".$_POST['image']);
		}
		if($_POST['remove'] == 'IMAGE') {     //удяляем изображение
		    queryMysql("UPDATE questions SET imgVopros = '' WHERE idVopros = '".$_POST['id']."';");
	        exec("/bin/rm ".$_POST['image']); 
		}
    }
    if(isset($_POST['isActive'])) {
	      if($_POST['isActive'] == 'FALSE') {	
	       queryMysql("UPDATE testing SET isActive = '0' WHERE isActive = 'ACTIVE';");
		   queryMysql("TRUNCATE buffer;");
		   queryMysql("TRUNCATE temp;");
           echo "Нет активного тестирования";
		  }
          else { //обрабатываем панель управления тестированием
			  $tid    = $_POST['testid'];   //id теста
			  $numu   = $_POST['isActive']; //кол-во участников
			  $numq   = $_POST['numq'];  //кол-во вопросов
			  $numqqq = $numu * $numq;   //кол-во ответов, которое должно оказаться в этом тесте
			  $result = queryMysql("SELECT * FROM temp;");
			  $rows   = mysqli_num_rows($result);
			  $result = queryMysql("SELECT * FROM results WHERE test_ID = '$tid';");
			  $num_ot = mysqli_num_rows($result);  //кол-во поступивших ответов
			  if($num_ot == $numqqq) echo 1;
			  elseif($rows == $numu) echo 0;
			  elseif($rows < $numu) echo "Кол-во подключившихся участников: ".$rows;
		  	  //else
					  
		  }
}
    
	if(isset($_POST['delTest']) && $_POST['delTest'] !== 0){
    $id = $_POST['delTest'];
    unset($_POST);	
	$result = queryMysql("SELECT * FROM results WHERE test_ID = '$id';");
	$rows   = mysqli_num_rows($result);
	if ($rows > 0) { //если тест проходили
	queryMysql("DELETE FROM testing WHERE test_ID = '$id';");
	queryMysql("DELETE FROM results WHERE test_ID = '$id';");
	}
	else queryMysql("DELETE FROM testing WHERE test_ID = '$id';");  //если тест никто не проходил
	
	
	}
    
	if(isset($_POST['delTests'])) {
	    $results = queryMysql("SELECT * FROM testing;");
		$rows = mysqli_num_rows($results);
		if($rows !== 0){	
	            queryMysql("TRUNCATE testing;");
	            queryMysql("TRUNCATE results;");
                echo "все результаты тестов удалены";
		}
        else echo "В базе нет пройденных тестов";		
}
    if(isset($_POST['wspid'])) {
	             session_start();
	             exec('ps -p '.$_POST['wspid'], $out);
				 if(isset($out[1])) {
					 exec('kill '.$_POST['wspid']); echo "WS сервер не запущен";
				   // unset($_SESSION['wspid']);
					 }
				 else {
                     echo "<b>Процесс сервера не обнаружен<b><br>";
				      }
				 }
    if(isset($_POST['erase'])) {
	     if($_POST['erase'] == 'vopros')	{
		$results = queryMysql("SELECT * FROM questions;");
		$rows = mysqli_num_rows($results);
		if($rows !== 0){
		echo "Удалено вопросов: ".$rows;
		queryMysql("TRUNCATE questions;");
		exec('rm -r '.$dir.'/img/*');
		}
		else echo "В базе нет вопросов";
	}
	    elseif($_POST['erase'] == 'results') queryMysql("TRUNCATE results;");
	}
	
	if(isset($_POST['idBuff']) && 
	   isset($_POST['nameBuff'])) {
	
	   $id   = $_POST['idBuff'];
	   $name = $_POST['nameBuff'];
	   unset($_POST);
	   $result = queryMysql("SELECT idVopros FROM buffer WHERE idVopros = '$id';");
	   $buffer = queryMysql("SELECT * FROM buffer;");
	   $rowsRes = mysqli_num_rows($result);
	   $rowsBuf = mysqli_num_rows($buffer);
	   
	   if($rowsRes == 0) {
	   queryMysql("INSERT INTO buffer (idVopros, nameVopros) VALUES ('$id', '$name');");
	   $r = $rowsBuf+1;
	   echo $r;
	   }
	   else {
	   queryMysql("DELETE FROM buffer WHERE idVopros = '$id';");
	   $r = $rowsBuf-1;
       echo $r;	   
	   }
	}
	
	if(isset($_POST['buffer'])) {
	         if($_POST['buffer'] == 'GET') {
			 $result = queryMysql("SELECT idVopros FROM buffer;");
			 $rows = mysqli_num_rows($result);
			     if($rows > 0) {
					 
					 for ( $i=0; $i<$rows; ++$i) {
						 mysqli_data_seek($result, $i);
					     $temp []= mysqli_fetch_row($result)[0];
					                             }
		                    echo implode("_", $temp);										 
				               }
                 else echo 'EMPTY';							   
			 } //post GET
			 if($_POST['buffer'] == 'FLUSH') {
				 queryMysql("TRUNCATE buffer;");
			 } //post FLUSH
	}
?>
