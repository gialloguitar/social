<?php //logout.php Выход из админки оператора
require 'head.php';
$msg = '';
if(isset($_SESSION['logedin'])) {
	echo "<script>O('logout').innerHTML = '';</script>";
	destroySession();
	$msg = "Вы вышли из системы!";
	$replace = "<script>location.replace(site + \"/logout.php\")</script>";
	}
else {
	
	$msg = "Вы вне системы! ";
}
?>
<div id='content' >
<div class='login'><p><?=$msg ?></p></div>
</div>
<?php
sleep(2);
echo $replace;
require 'footer.php';
?>