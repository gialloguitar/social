<?php //STATQUESTION.PHP  - статистика по одному вопросу
require 'head.php';
echo $checkLog; //Проверяем вход Оператора

if(empty($_GET)) echo "<script>location.replace('results.php')</script>";

$test   = $_GET['test']; //ID теста
$id     = $_GET['id'];   //ID вопроса
$v1     = $_GET['v1'];
$v2     = $_GET['v2'];
$v3     = $_GET['v3'];
$v4     = $_GET['v4'];

$edit_quest = "<div class='make'><a href='editquestion.php?id=".$id."'>Редактировать вопрос</a></div>";
$st = $weight = '';
$w = -1;

///////Информация о тесте
$result = queryMysql("SELECT * FROM testing WHERE test_ID = '$test';");
mysqli_data_seek($result, 0);
$row       = mysqli_fetch_assoc($result);
$date_t    = fixBigDate($row['Tdate']);
$type      = typeTest($row['type']);
$num_users = $row['num_users'];
$time      = $row['time_per'];
$weight    = $type." тестирование. Установленное время ответа на один вопрос: ".$time." сек. Кол-во участников: ".$num_users."<br>";

///////////Сводная информация
////По половому признаку в текущем тесте
///////кол-во женщин/////////////
///v1
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE test_ID = '$test' AND idVopros = '$id' AND Otvet = 'v1' AND sex = 'J';");
$v1_w = mysqli_num_rows($result);
///v2
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE test_ID = '$test' AND idVopros = '$id' AND Otvet = 'v2' AND sex = 'J';");
$v2_w = mysqli_num_rows($result);
///v3
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE test_ID = '$test' AND idVopros = '$id' AND Otvet = 'Воздержался' AND sex = 'J';");
$v3_w = mysqli_num_rows($result);
///v4
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE test_ID = '$test' AND idVopros = '$id' AND Otvet = 'timeout' AND sex = 'J';");
$v4_w = mysqli_num_rows($result);
///////кол-во мужчин/////////////
///v1
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE test_ID = '$test' AND idVopros = '$id' AND Otvet = 'v1' AND sex = 'M';");
$v1_m = mysqli_num_rows($result);
///v2
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE test_ID = '$test' AND idVopros = '$id' AND Otvet = 'v2' AND sex = 'M';");
$v2_m = mysqli_num_rows($result);
///v3
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE test_ID = '$test' AND idVopros = '$id' AND Otvet = 'Воздержался' AND sex = 'M';");
$v3_m = mysqli_num_rows($result);
///v4
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE test_ID = '$test' AND idVopros = '$id' AND Otvet = 'timeout' AND sex = 'M';");
$v4_m = mysqli_num_rows($result);

if ($row['type'] == 'FICTION') { 
    $temp = explode("_", $row['ID_questions']);
	foreach($temp as $item) {
	    $ID_W = explode("w", $item);
		if($ID_W[0] == $id) $w = $ID_W[1];
			}
		 switch ($w) {
                                     case '0':
                                     $w = "100% / 0%";
                                     break;
									 case '1':
                                     $w = "90% / 10%";
                                     break;
									 case '2':
                                     $w = "80% / 20%";
                                     break;
									 case '3':
                                     $w = "70% / 30%";
                                     break;
									 case '4':
                                     $w = "60% / 40%";
                                     break;
									 case '5':
                                     $w = "50% / 50%";
                                     break;
									 case '6':
                                     $w = "40% / 60%";
                                     break;
									 case '7':
                                     $w = "30% / 70%";
                                     break;
									 case '8':
                                     $w = "20% / 80%";
                                     break;
									 case '9':
                                     $w = "10% / 90%";
                                     break;
									 case '10':
                                     $w = "0% / 100%";
                                     break;
									 default:
									 $w = '-';
									 
                                    	}
		$weight = $type." тестирование. Для этого вопроса была установлена вероятность  ".$w." показа Варианта № 1 и Варианта № 2 соответственно.<br>
		Установленное время ответа на один вопрос: ".$time." сек. Кол-во участников: ".$num_users."<br>";								
	
	}
///Собираем сводную инфу за всё время
//////////////////////TRUE
//v1
$result = queryMysql("SELECT * FROM results WHERE type = 'TRUE' AND otvet = 'v1' AND idVopros = '$id';");
$com_v1_true = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE type = 'TRUE' AND idVopros = '$id' AND Otvet = 'v1' AND sex = 'J';");
$com_v1_true_w = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE type = 'TRUE' AND idVopros = '$id' AND Otvet = 'v1' AND sex = 'M';");
$com_v1_true_m = mysqli_num_rows($result);
//v2
$result = queryMysql("SELECT * FROM results WHERE type = 'TRUE' AND otvet = 'v2' AND idVopros = '$id';");
$com_v2_true = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'TRUE' AND Otvet = 'v2' AND sex = 'J';");
$com_v2_true_w = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'TRUE' AND Otvet = 'v2' AND sex = 'M';");
$com_v2_true_m = mysqli_num_rows($result);
//v3 - Воздержался
$result = queryMysql("SELECT * FROM results WHERE type = 'TRUE' AND otvet = 'Воздержался' AND idVopros = '$id';");
$com_v3_true = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'TRUE' AND Otvet = 'Воздержался' AND sex = 'J';");
$com_v3_true_w = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'TRUE' AND Otvet = 'Воздержался' AND sex = 'M';");
$com_v3_true_m = mysqli_num_rows($result);
//v4 - timeout
$result = queryMysql("SELECT * FROM results WHERE type = 'TRUE' AND otvet = 'timeout' AND idVopros = '$id';");
$com_v4_true = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'TRUE' AND Otvet = 'timeout' AND sex = 'J';");
$com_v4_true_w = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'TRUE' AND Otvet = 'timeout' AND sex = 'M';");
$com_v4_true_m = mysqli_num_rows($result);
/////////////////////FALSE (инверсия)
//v1
$result = queryMysql("SELECT * FROM results WHERE type = 'FALSE' AND otvet = 'v1' AND idVopros = '$id';");
$com_v1_false = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'FALSE' AND Otvet = 'v1' AND sex = 'J';");
$com_v1_false_w = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'FALSE' AND Otvet = 'v1' AND sex = 'M';");
$com_v1_false_m = mysqli_num_rows($result);
//v2
$result = queryMysql("SELECT * FROM results WHERE type = 'FALSE' AND otvet = 'v2' AND idVopros = '$id';");
$com_v2_false = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'FALSE' AND Otvet = 'v2' AND sex = 'J';");
$com_v2_false_w = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'FALSE' AND Otvet = 'v2' AND sex = 'M';");
$com_v2_false_m = mysqli_num_rows($result);
//v3 - Воздержался
$result = queryMysql("SELECT * FROM results WHERE type = 'FALSE' AND otvet = 'Воздержался' AND idVopros = '$id';");
$com_v3_false = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'FALSE' AND Otvet = 'Воздержался' AND sex = 'J';");
$com_v3_false_w = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'FALSE' AND Otvet = 'Воздержался' AND sex = 'M';");
$com_v3_false_m = mysqli_num_rows($result);
//v4 - timeout
$result = queryMysql("SELECT * FROM results WHERE type = 'FALSE' AND otvet = 'timeout' AND idVopros = '$id';");
$com_v4_false = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'FALSE' AND Otvet = 'timeout' AND sex = 'J';");
$com_v4_false_w = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'FALSE' AND Otvet = 'timeout' AND sex = 'M';");
$com_v4_false_m = mysqli_num_rows($result);
/////////////////////BLIND
//v1
$result = queryMysql("SELECT * FROM results WHERE type = 'BLIND' AND otvet = 'v1' AND idVopros = '$id';");
$com_v1_bl = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'BLIND' AND Otvet = 'v1' AND sex = 'J';");
$com_v1_bl_w = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'BLIND' AND Otvet = 'v1' AND sex = 'M';");
$com_v1_bl_m = mysqli_num_rows($result);
//v2
$result = queryMysql("SELECT * FROM results WHERE type = 'BLIND' AND otvet = 'v2' AND idVopros = '$id';");
$com_v2_bl = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'BLIND' AND Otvet = 'v2' AND sex = 'J';");
$com_v2_bl_w = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'BLIND' AND Otvet = 'v2' AND sex = 'M';");
$com_v2_bl_m = mysqli_num_rows($result);
//v3 - Воздержался
$result = queryMysql("SELECT * FROM results WHERE type = 'BLIND' AND otvet = 'Воздержался' AND idVopros = '$id';");
$com_v3_bl = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'BLIND' AND Otvet = 'Воздержался' AND sex = 'J';");
$com_v3_bl_w = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'BLIND' AND Otvet = 'Воздержался' AND sex = 'M';");
$com_v3_bl_m = mysqli_num_rows($result);
//v4 - timeout
$result = queryMysql("SELECT * FROM results WHERE type = 'BLIND' AND otvet = 'timeout' AND idVopros = '$id';");
$com_v4_bl = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'BLIND' AND Otvet = 'v1' AND sex = 'J';");
$com_v4_bl_w = mysqli_num_rows($result);
$result = queryMysql("SELECT Otvet,sex FROM results JOIN users ON results.UID = users.UID WHERE idVopros = '$id' AND type = 'BLIND' AND Otvet = 'v1' AND sex = 'M';");
$com_v4_bl_m = mysqli_num_rows($result);

////////////////Информация о вопросе
$result = queryMysql("SELECT nameVopros,textVopros,imgVopros,variant1,variant2 FROM results WHERE idVopros = '$id';");	
mysqli_data_seek($result,0);
$row  = mysqli_fetch_assoc($result);
unset($result); //на всяк случай очистим выборку, она будет большой со временем
$tmpVopros['name'] = $row['nameVopros'];
$tmpVopros['text'] = $row['textVopros'];
$tmpVopros['v1'] = $row['variant1'];
$tmpVopros['v2'] = $row['variant2'];
$tmpVopros['img'] = $row['imgVopros']; 



if($tmpVopros['img'] == '') $img = "";
else $img = "<b>Картинка к вопросу:</b><br>
					<div class=\"upload-file-container\">
				    <img id=\"img_min\" src='".$tmpVopros['img'] ."' alt=\"IMG\" /><br>
					</div>";
					
$result = queryMysql("SELECT * FROM questions WHERE idVopros = '$id';");
$rows   = mysqli_num_rows($result);
if($rows == 0 && $tmpVopros['img'] !== '') {
	$img = "<b>Картинка к вопросу:</b><br>
					<div id='err_msg'>Изображение отсутствует, так как вопрос был удален из базы
					</div>";
	$edit_quest = '';
    $st = "<div id='status' style=\"background-color: white; color: red; margin: 2px;\">Данный вопрос удален из базы</div>";	
}
elseif($rows == 0 && $tmpVopros['img'] == '') {
    $img = '';
	$edit_quest = '';
	$st = "<div id='status' style=\"background-color: white; color: red; margin: 2px;\">Данный вопрос удален из базы</div>";
}

?>
<!-- Диаграммы -->
 
<script>

      // Load the Visualization API and the corechart package.
     google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Вариант № 1', <?=$v1 ?>],
          ['Вариант № 2', <?=$v2 ?>],
          ["Воздержались", <?=$v3 ?>],
          ['Таймаут', <?=$v4 ?>]
        ]);

        // Set chart options
        var options = {'title':'Ответы в этом тестировании\nот <?=$date_t ?>, (<?=$type ?>)',
                       'width':550,
                       'height':350};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(O('chart_div1'));
        chart.draw(data, options);
      }
    </script>
<script>


      // Load the Visualization API and the corechart package.
     google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Вариант № 1', <?=$com_v1_true ?>],
          ['Вариант № 2', <?=$com_v2_true ?>],
          ['Воздержались', <?=$com_v3_true ?>],
          ['Таймаут', <?=$com_v4_true ?>]
        ]);

        // Set chart options
        var options = {'title':'Ответы за всё время при Правдивом тестировании',
                       'width':550,
                       'height':350};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(O('chart_div2'));
        chart.draw(data, options);
      }
    </script>
<script>


      // Load the Visualization API and the corechart package.
     google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Вариант № 1', <?=$com_v1_false ?>],
          ['Вариант № 2', <?=$com_v2_false ?>],
          ['Воздержались', <?=$com_v3_false ?>],
          ['Таймаут', <?=$com_v4_false ?>]
        ]);

        // Set chart options
        var options = {'title':'Ответы за всё время при Инвертированном тестировании',
                       'width':550,
                       'height':350};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(O('chart_div3'));
        chart.draw(data, options);
      }
    </script>
<script>


      // Load the Visualization API and the corechart package.
     google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
          ['Вариант № 1', <?=$com_v1_bl ?>],
          ['Вариант № 2', <?=$com_v2_bl ?>],
          ['Воздержались', <?=$com_v3_bl ?>],
          ['Таймаут', <?=$com_v4_bl ?>]
        ]);

        // Set chart options
        var options = {'title':'Ответы за всё время при Слепом тестировании',
                       'width':550,
                       'height':350};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(O('chart_div4'));
        chart.draw(data, options);
      }
    </script>	

<!-- Гистограммы  -->
 
<script>
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Вариант", "Кол-во", { role: "style" } ],
        ["№1", <?=$v1 ?>, "blue"],
        ["№2", <?=$v2 ?>, "green"],
        ["Воздержались", <?=$v3 ?>, "gold"],
        ["Таймаут", <?=$v4 ?>, "red"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Ответы в этом тестировании\nот <?=$date_t ?>, (<?=$type ?>)",
        width: 650,
        height: 400,
        bar: {groupWidth: "65%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("chart_div5"));
      chart.draw(view, options);
  }
  </script>
<script>
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Вариант", "Кол-во", { role: "style" } ],
        ["№1", <?=$com_v1_true ?>, "blue"],
        ["№2", <?=$com_v2_true ?>, "green"],
        ["Воздержались", <?=$com_v3_true ?>, "gold"],
        ["Таймаут", <?=$com_v4_true ?>, "red"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Ответы за всё время при Правдивом тестировании",
        width: 650,
        height: 400,
        bar: {groupWidth: "65%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("chart_div6"));
      chart.draw(view, options);
  }
  </script>
<script>
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Вариант", "Кол-во", { role: "style" } ],
        ["№1", <?=$com_v1_false ?>, "blue"],
        ["№2", <?=$com_v2_false ?>, "green"],
        ["Воздержались", <?=$com_v3_false ?>, "gold"],
        ["Таймаут", <?=$com_v4_false ?>, "red"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Ответы за всё время при Инвертированном тестировании",
        width: 650,
        height: 400,
        bar: {groupWidth: "65%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("chart_div7"));
      chart.draw(view, options);
  }
  </script>
<script>
    google.charts.load("current", {packages:['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Вариант", "Кол-во", { role: "style" } ],
        ["№1", <?=$com_v1_bl ?>, "blue"],
        ["№2", <?=$com_v2_bl ?>, "green"],
        ["Воздержались", <?=$com_v3_bl ?>, "gold"],
        ["Таймаут", <?=$com_v4_bl ?>, "red"]
      ]);

      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);

      var options = {
        title: "Ответы за всё время при Слепом тестировании",
        width: 650,
        height: 400,
        bar: {groupWidth: "65%"},
        legend: { position: "none" },
      };
      var chart = new google.visualization.ColumnChart(document.getElementById("chart_div8"));
      chart.draw(view, options);
  }
  </script>  

  <div id='content'>
  <h1>Сводная статистика для вопроса ID: <?=$id ?>  (<?=$tmpVopros['name'] ?>)</h1>
  <div id ='menu'>
  <div class='make'><a href='<?=$site ?>'>Меню</a></div>
  <div class='make'><a href='questions.php'>Вопросы</a></div>
  <div class='make'><a href='results.php'>Результаты</a></div>
  <div class='make'><a href='result.php?test=<?=$test ?>'>Назад к результату тестирования</a></div>
  <?=$edit_quest ?>
  </div>
  <div id='wrap_question'>
  <?=$weight ?>
  <?=$st ?>
<div id='wrap_stat_left'>  
    <b>Название вопроса:</b><br>
    <div class='field' style="width: 300px; margin-left: 15px;" ><?=$tmpVopros['name'] ?></div>
    <b>Текст вопроса:</b><br>
    <div class='field' style="width: 300px; margin-left: 15px;"><?=$tmpVopros['text'] ?></div>
    <?=$img ?>				
    <b>Варианты ответов на вопрос:</b><br>
    <ol>
    <li><div class='field' ><?=$tmpVopros['v1'] ?></div></li>
    <li><div class='field' ><?=$tmpVopros['v2'] ?></div></li>
    </ol>
</div> <!-- stat LEFT --> 
<div id='wrap_stat_right' > 
<b>Отчет по вопросу в данном тесте: </b>
<br>
<div class='field' style='display: inline-block; width: 330px;'>
<ul>
<li>1-й вариант: <?=$v1 ?> (из них муж: <?=$v1_m ?> , жен: <?=$v1_w ?>)</li> 
<li>2-й вариант: <?=$v2 ?> (из них муж: <?=$v2_m ?> , жен: <?=$v2_w ?>)</li>  
<li>Воздержались: <?=$v3 ?> (из них муж: <?=$v3_m ?> , жен: <?=$v3_w ?>)</li> 
<li>Таймаут: <?=$v4 ?> (из них муж: <?=$v4_m ?> , жен: <?=$v4_w ?>)</li> 
</ul></div>
<br> 
<b>За всё время:</b>
<br>
<div class='field' style='display: inline-block; width: 330px;'>
Правдивое
<ul>
<li>1-й вариант: <?=$com_v1_true ?> (из них муж: <?=$com_v1_true_m ?> , жен: <?=$com_v1_true_w ?>)</li> 
<li>2-й вариант: <?=$com_v2_true ?> (из них муж: <?=$com_v2_true_m ?> , жен: <?=$com_v2_true_w ?>)</li>  
<li>Воздержались: <?=$com_v3_true ?> (из них муж: <?=$com_v3_true_m ?> , жен: <?=$com_v3_true_w ?>)</li> 
<li>Таймаут: <?=$com_v4_true ?> (из них муж: <?=$com_v4_true_m ?> , жен: <?=$com_v4_true_w ?>)</li> 
</ul>
</div>

<div class='field' style='display: inline-block; width: 330px;'>
Инвертированное
<ul>
<li>1-й вариант: <?=$com_v1_false ?> (из них муж: <?=$com_v1_false_m ?> , жен: <?=$com_v1_false_w ?>)</li> 
<li>2-й вариант: <?=$com_v2_false ?> (из них муж: <?=$com_v2_false_m ?> , жен: <?=$com_v2_false_w ?>)</li>  
<li>Воздержались: <?=$com_v3_false ?> (из них муж: <?=$com_v3_false_m ?> , жен: <?=$com_v3_false_w ?>)</li> 
<li>Таймаут: <?=$com_v4_false ?> (из них муж: <?=$com_v4_false_m ?> , жен: <?=$com_v4_false_w ?>)</li> 
</ul>
</div>

<div class='field' style='display: inline-block; width: 330px;'>
Слепое
<ul>
<li>1-й вариант: <?=$com_v1_bl ?> (из них муж: <?=$com_v1_bl_m ?> , жен: <?=$com_v1_bl_w ?>)</li> 
<li>2-й вариант: <?=$com_v2_bl ?> (из них муж: <?=$com_v2_bl_m ?> , жен: <?=$com_v2_bl_w ?>)</li>  
<li>Воздержались: <?=$com_v3_bl ?> (из них муж: <?=$com_v3_bl_m ?> , жен: <?=$com_v3_bl_w ?>)</li> 
<li>Таймаут: <?=$com_v4_bl ?> (из них муж: <?=$com_v4_bl_m ?> , жен: <?=$com_v4_bl_w ?>)</li> 
</ul>
</div>
</div> <!--  stat RIGHT-->
</div> <!--  wrap QUESTION -->
<hr>
<div id='wrap_common_stat' style='display: block;' >
  <h3>Графическое представление:</h3>

	<div id='wrap_chart_diag' style='display: block;'>
      <div id='chart_div1' class='inl_block'></div>
      <div id='chart_div2' class='inl_block'></div>
      <div id='chart_div3' class='inl_block'></div>
	  <div id='chart_div4' class='inl_block'></div>
    </div>
	<hr>
	<div id='wrap_chart_gist' style='display: block;'>
      <div id='chart_div5' class='inl_block'></div>
      <div id='chart_div6' class='inl_block'></div>
      <div id='chart_div7' class='inl_block'></div>
	  <div id='chart_div8' class='inl_block'></div>
    </div>
	</div>
	<hr>
   
   <script>
   var arr = {
   "id"             : <?=$id ?>,
   "name"           : "<?=$tmpVopros['name'] ?>",
   "text"           : "<?=$tmpVopros['text'] ?>",
   "var1"           : "<?=$tmpVopros['v1'] ?>",
   "var2"           : "<?=$tmpVopros['v2'] ?>",
   "img"            : "<?=$tmpVopros['img'] ?>",
   "type"           : "<?=$type ?>",
   "time"           : <?=$time ?>,
   "num"            : <?=$num_users ?>,
   "date"           : "<?=$date_t ?>",   
   "w"              : "<?=$w ?>",	   
   "v1"             : <?=$v1 ?>,
   "v2"             : <?=$v2 ?>,
   "v3"             : <?=$v3 ?>,
   "v4"             : <?=$v4 ?>,
   "v1_m"           : <?=$v1_m ?>,
   "v2_m"           : <?=$v2_m ?>,
   "v3_m"           : <?=$v3_m ?>,
   "v4_m"           : <?=$v4_m ?>,
   "v1_w"           : <?=$v1_w ?>,
   "v2_w"           : <?=$v2_w ?>,
   "v3_w"           : <?=$v3_w ?>,
   "v4_w"           : <?=$v4_w ?>,
   "com_v1_true"    : <?=$com_v1_true ?>,
   "com_v1_true_m"  : <?=$com_v1_true_m ?>,
   "com_v1_true_w"  : <?=$com_v1_true_w ?>,
   "com_v1_false"   : <?=$com_v1_false ?>,
   "com_v1_false_m" : <?=$com_v1_false_m ?>,
   "com_v1_false_w" : <?=$com_v1_false_w ?>,
   "com_v1_bl"      : <?=$com_v1_bl ?>,
   "com_v1_bl_m"    : <?=$com_v1_bl_m ?>,
   "com_v1_bl_w"    : <?=$com_v1_bl_w ?>,
   "com_v2_true"    : <?=$com_v2_true ?>,
   "com_v2_true_m"  : <?=$com_v2_true_m ?>,
   "com_v2_true_w"  : <?=$com_v2_true_w ?>,
   "com_v2_false"   : <?=$com_v2_false ?>,
   "com_v2_false_m" : <?=$com_v2_false_m ?>,
   "com_v2_false_w" : <?=$com_v2_false_w ?>,
   "com_v2_bl"      : <?=$com_v2_bl ?>,
   "com_v2_bl_m"    : <?=$com_v2_bl_m ?>,
   "com_v2_bl_w"    : <?=$com_v2_bl_w ?>,
   "com_v3_true"    : <?=$com_v3_true ?>,
   "com_v3_true_m"  : <?=$com_v3_true_m ?>,
   "com_v3_true_w"  : <?=$com_v3_true_w ?>,
   "com_v3_false"   : <?=$com_v3_false ?>,
   "com_v3_false_m" : <?=$com_v3_false_m ?>,
   "com_v3_false_w" : <?=$com_v3_false_w ?>,
   "com_v3_bl"      : <?=$com_v3_bl ?>,
   "com_v3_bl_m"    : <?=$com_v3_bl_m ?>,
   "com_v3_bl_w"    : <?=$com_v3_bl_w ?>,
   "com_v4_true"    : <?=$com_v4_true ?>,
   "com_v4_true_m"  : <?=$com_v4_true_m ?>,
   "com_v4_true_w"  : <?=$com_v4_true_w ?>,
   "com_v4_false"   : <?=$com_v4_false ?>,
   "com_v4_false_m" : <?=$com_v4_false_m ?>,
   "com_v4_false_w" : <?=$com_v4_false_w ?>,
   "com_v4_bl"      : <?=$com_v4_bl ?>,
   "com_v4_bl_m"    : <?=$com_v4_bl_m ?>,
   "com_v4_bl_w"    : <?=$com_v4_bl_w ?>   
   };
   </script>
   <input type='button' class='make' value='создать PDF' onClick='makePDF(arr);'/>
  
</div>  <!-- CONTENT -->

<?php
require 'footer.php';
?>