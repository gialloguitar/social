<?php // RUN.PHP - Интерфейс прохождения теста клиента
require 'head.php';
echo $toForm;

echo "<script>wsConnect();</script>";
?>
<div id="status_ws"></div>
<div id='credits'><?="Участник тестирования: "
.$fname." ".$mname." ".$sname."<br>(".$sex.", год рожд. ".$dbirth."г.)"; ?>
</div>
<div id='content_cl'>
<div id='wait'></div>
<center><div id='wrap_question_cl'>  
  <form method='POST' id='form' action='' >
  <div id='timer'></div>
  Вопрос № 1<br>
  <div id='vopros_text'><?=$questions[0]['textVopros']?></div>
  
	<?php  //echo IMG, Invert Variants, Weight				
	        if($questions[0]['imgVopros'] == '') $img = '';
            else $img =  "<div class=img_test ><img id=img_min src=\"".$site."/".$questions[0]['imgVopros']."\" /></div>";
            echo $img;					
			
			if($type == 'TRUE' || $type == 'FICTION') { $v1 = 'v1'; $v2 = 'v2';}
			else { $v1 = 'v2'; $v2 = 'v1';} 
			
			if($type == 'FICTION') echo "<input id=\"weight\" style=\"position: absolute; left: 10000px;;\" type=\"text\" name=\"weight\" value=".$questions[0]['weight']." />";
			
			
			echo "<input id=\"num_users\" style=\"position: absolute; left: 10000px;;\" type=\"text\" name=\"num_users\" value=".$num_users." />"
			?>
  Выберите вариант ответа:<br>
  <div id='variants' class='wrap_variants'>
  <label class='radio'> 
  <input class='variant_radio radio' type='radio' name='otvet' value='<?=$v1 ?>'>
  <div class='variant radio__text'><?=$questions[0]['variant1'] ?><br></div>
  </label>
  <label class='radio'> 
  <input class='variant_radio radio' type='radio' name='otvet' value='<?=$v2 ?>'>
  <div class='variant radio__text'><?=$questions[0]['variant2'] ?><br></div>
  </label>
  </div>
  <div id='indicator_block' <?=$indicator ?> >
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
  <div class='variant'>Всего дано ответов <div class='ws_resp' id='count_t'></div></div>
  </div>
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