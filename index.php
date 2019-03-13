<?php //OPERATOR INDEX
require 'head.php';
$error = $user = $pass = $menu = '';
	
if(isset($_POST['user'])) {
$user = fixString($_POST['user']);   
$pass = fixString($_POST['pass']);

if($user == '' || $pass == '') {
$error = "<span id='err_msg'>Введите Ваш логин и пароль!</span>";
}
else {
$result = queryMysql("SELECT * FROM users WHERE fname = '$user' AND pass = '$pass';"); 
$rows = mysqli_num_rows($result);
    if($rows == 0) $error = "<span id='err_msg'>Неверный логин и пароль!</span>";
	else {
        mysqli_data_seek($result, 0);  
        $row = mysqli_fetch_assoc($result);		
	    if ($row['isAdmin'] == 1) {
/*		$SID = session_id(); //Need for work from same Desktop
		if($row['ip'] == '' && $row['SID'] == '') {
			queryMysql("UPDATE users  SET ip = '$_SERVER[REMOTE_ADDR]' WHERE isAdmin = 1;");
			queryMysql("UPDATE users  SET SID = '$SID' WHERE isAdmin = 1;");
		    $_SESSION['logedin'] = TRUE;
            $display = "none";			
		    //$replace = "<script>location.replace(site + \"/menu.php\")</script>";
			$replace = "<script>location.replace(site)</script>";
		}
	    elseif($row['ip'] !== $_SERVER['REMOTE_ADDR'] || $row['SID'] !== $SID) $error = "<span id='err_msg'>Закройте все админские сессии!</span>"; */
		    queryMysql("UPDATE users SET ip = '$_SERVER[REMOTE_ADDR]' WHERE isAdmin = 1;");
		    $_SESSION['logedin'] = TRUE;  //админская переменная сессии
            $display = "none";			
			$replace = "<script>location.replace(site)</script>";
	    } // Only isAdmin
        else $error = "<span id='err_msg'>Вам запрещен доступ к панели администратора!</span>";
	 } // Query NOT NULL
   } // user AND pass NOT NULL	 
} //POST user     


if($logedin) $menu = "<br>
                     <div id='main'><ul>
                     <li><a href=questions.php class='m_lnk'>Вопросы</a></li>
                     <li><a href=maketesting.php class='m_lnk'>Управление тестированием</a></li>
                     <li><a href=results.php class='m_lnk'>Результаты</a></li>
                     </ul>
                     </div>
                     <br>";
	
?>
<div id='content' >
<?=$menu ?>
<div id='login' style="display: <?=$display ?>;">
<form action='' method='POST'>
<?=$error ?><br>
<span class='field'>Войдите в систему</span><br>
<span class='field'>Login:<br></span><input maxlength=10 type='text' name='user' value='<?=$user ?>'/><br>
<span class='field'>Password:<br></span><input maxlength=10 type='password' name='pass' value='<?=$pass ?>' /><br>
<span class='field'><input type='submit' value='Войти' /></span>
</form>
</div>
</div>
<?php
echo $replace;
require 'footer.php';
?>
