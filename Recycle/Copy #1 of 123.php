<?php // RUN.PHP - testing
require 'head.php';
//echo $_SERVER['HTTP_REFERER'].", ".$site.$_SERVER['REQUEST_URI'];  
//if ($_SERVER['HTTP_REFERER'] == $site."/client/") echo "<script>Hello(".$IDs.",".$time_per.");</script>";
echo "<script>wsConnect();</script>";
?>
<div id="status_ws"></div>
<div id='credits'><?="Участник тестирования: "
.$fname." ".$mname." ".$sname."<br>(".$sex.", дата рожд. ".$dbirth."), ".$ip; ?>
</div>
<div id='content_cl'>
<div id='wait'></div>
<center><div id='wrap_question_cl'>
  
  <form method='POST' id='form' action='' >
  <div id='timer'></div>
  <!-- textarea name='get'></textarea -->
  Вопрос № <?=$questions[0]['idVopros'] ?> :<br>
  <div id='vopros_text'><?=$questions[0]['textVopros']?></div>
	<?php  //echo IMG and Invert Variants				
	        if($questions[0]['imgVopros'] == '') $img = '';
            else $img =  "<div class=img_test ><img id=img_min src=".$site."/".$questions[0]['imgVopros']." /></div>";
            echo $img;					
			if($type == 'TRUE') { $v1 = 'v1'; $v2 = 'v2';}
			else { $v1 = 'v2'; $v2 = 'v1';} ?>
  Выберите вариант ответа:<br>
  <div id='variants'>
  <?=$questions[0]['variant1'] ?>: <input type='radio' name='otvet' value='<?=$v1 ?>'>
  <?=$questions[0]['variant2'] ?>: <input type='radio' name='otvet' value='<?=$v2 ?>'>
  </div>
  Как ответили остальные участники:<br>
  Вариант 1: <div class='ws_resp' id='v1'></div>
  Вариант 2:<div class='ws_resp' id='v2'></div>
  <br>
  <input type='submit' value='Ответить'>
  </form>
  </div></center>
<div id='wait_script'>
<script>waitAll();</script>
<script>window.onbeforeunload = function() {
  return "Активно тестирование";
};
</script>
</div>
</div>	 
<?php
require 'footer.php';
?> 