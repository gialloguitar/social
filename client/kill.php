<?php  //тул для убивания текущей сессии
require 'head.php';
destroySession();
if(!session_id()) echo "<span id='err_msg'>Вы успешно уничтожили сессию!</span>";
else echo "<span id='err_msg'>Что-то пошло не так...</span>";
//echo $toForm;
?>