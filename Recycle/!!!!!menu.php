<?php //questions.php Загрузка вопросов для тестирования
require 'head.php';
echo $checkLog; //Проверяем вход Оператора
?>
<br>
<div id='content'>
<div id='menu'>
<ul>
<li><a href=questions.php class='m_lnk'>Вопросы</a></li>
<li><a href=questions.php class='m_lnk'>Создать тестирование</a></li>
<li><a href=index.php class='m_lnk'>Результаты</a></li>
</ul>
 </div>
</div>
<br>
<?php
require 'footer.php';
?>