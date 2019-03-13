//Общая библиотека JS для всех интерфейсов
var host = location.hostname;
var site = "http://" + host;
var client = site + "/client";
var setTest = check = new Array();
var count_t = 0; //глобальный идентификатор кол-ва ответивших на текущий вопрос
var count_u = 0; //глобальный идентификатор кол-ва отвечающих
var typeT = ''; //глобальный идентификатор типа тестирования

//Упрощаем жизь
function O(elem) {
	return typeof elem == 'object' ? elem : document.getElementById(elem)
}
function S(elem) {
	return O(elem).style
}
//Читаем изображение
function readURL(input) {
     if (input.files) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#img_min').attr('src', e.target.result);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
//Удаляем ненужное в текущем вызове (параметр необходим для удаления всего вопроса по ID
function RemoveVopros(id, img, p) {             
			   var pathScript = location.pathname;  
			   var promt = confirm('Вы действительно хотите удалить этот вопрос ?')
               if (promt) {
				   $.post('functions.php', {remove : 'VOPROS', id: id, image: img}, function() { 
				   location.replace(site + pathScript + "?p=" + p); })
			   }
}
function RemoveImg(id, img) {              
			   var pathScript = location.pathname;  
			   var promt = confirm('Вы действительно хотите удалить это изображение ?')
               if (promt) {
				   $.post(location.pathname, {remove : 'IMAGE', id: id, image: img}, function() { 
				   location.replace(site + pathScript + "?id=" +id) })
			   }			
}
//Удаляем все вопросы
function delAllVopros() {
	var promt = confirm('Вы действительно хотите удалить все вопросы?')
    if (promt) $.post('functions.php', {erase: 'vopros'}, function(data){
		location.reload();
		O('delAll').innerHTML = data;
	});
}
//Делаем последнее тестирование не активным, удаляем все
function unsetTest() {
	$.post('functions.php', {isActive: 'FALSE'}, function(data) {
	O('statusTest').innerHTML = '';
    S('wrap_testing_stat').zIndex = '-1';
	$('#ok1').text(data);
	clearInterval(timeOutStat);
    O('set').innerHTML = '<b>Буфер очищен, выберите вопросы для нового тестирования</b>';
    	})
	}
function delTests() {
	var promt = confirm('Вы действительно хотите удалить результаты всех тестов?')
	if(promt) {
	$.post('functions.php', {delTests: 'TRUE'}, function(data) {
	$('#ok2').text(data);
	O('set_testing').innerHTML = '';
    	})
	}	
}
//удаляем тест с результатом
function delTest(id) {
	var promt = confirm('Вы действительно хотите удалить результаты теста?')
	if(promt) {
	$.post('functions.php', {delTest: id}, function() {
	location.replace(location.pathname);
    	})
	}	
}
//собираем инфу о тесте
function trapStatTest(num, tid, q) {
	S('wrap_testing_stat').zIndex = 1;
    timeOutStat = setInterval( function(){
		   $.post('functions.php', { isActive : num, testid: tid, numq: q }, function(data) {
		       if(data == '1') {
				   clearInterval(timeOutStat);
				   O('testing_stat').innerHTML = "Тестирование окончено. Деактивируйте<br><input type='button' class='make' onClick='unsetTest();' value='Disactive тестирование' />";   
			   }
			   else if(data == '0') O('testing_stat').innerHTML = "В данный момент участники отвечают на вопросы";		   
			   
			   else O('testing_stat').innerHTML = data;
			 
		      })
			},3000);
}
//Выключаем WS
function stopServerWS(pid){
	console.log(pid);
	$.post('functions.php',{ wspid: pid}, function(data) {
	if(data) $('#statusWS').html(data);
	})
}
//Интерфейс работы с буфером вопросов
function toBuffer(idName) {
	   O('flush').innerHTML = "<input type='button' value='очистить буфер' onClick='flushBuffer();'/>";
       var buff = idName.split('+++');
       var v = O(buff[0]).value;   
	   if (v === 'Добавить') O(buff[0]).value = 'В буфере';
	   else O(buff[0]).value = 'Добавить';

       $.post('functions.php', { idBuff: buff[0], nameBuff: buff[1]}, function(data) {
	   O('buffer').innerHTML = "Вопросов в буфере: " + data;
	   if(data == 0) S('flush').zIndex = '-1';
	   })

}
//Очистка буфера
function flushBuffer() {
	   
       $.post('functions.php', { buffer: 'FLUSH'}, function() {
	   location.replace(location.href);
	   });
	
	   }
//Чекаем начало экзамена
function checkStartTesting() {
	var timeOut = setInterval(function(){		
	    $.post('functions.php', { check: 'check'}, function(data) {
		if(data){
        O('content_cl').innerHTML = data;		
		clearInterval(timeOut);
	            }
	    else O('content_cl').innerHTML = '<h1>Ожидайте...</h1>';			
	})	
	}, 2000);
}
//ждем всех перед выводом первого вопроса
function waitAll() {
	S('wrap_question_cl').zIndex = '-1'; 
	var timeOut = setInterval(function(){
		O('wait').innerHTML = '<h1>Ждем остальных...</h1>';
	    $.post('functions.php', { wait: 'wait'}, function(data) {
		if(data){
		O('wait').innerHTML = '';
		O('wait_script').display = 'none';
		S('wrap_question_cl').zIndex = '1';
		timerTest(data);
		clearInterval(timeOut);
	            }
	})	
	}, 2000);
}
//Проверяем ввденные пользовательские данные
function validUserData(form) {
	fail  = validFname(form.fname.value);
	fail += validSname(form.sname.value);
	fail += validSex(form.sex.value);
	fail += validDbirth(form.dbirth.value);
	
	if(fail == "") return true
    else {
		O('err_msg').innerHTML = fail;
		return false;
	}	
    
	function validFname(field) {
		if (field == "") return "Укажите ваше имя.\n";
		else if (/[^А-Я-Ё]/gi.test(field)) return "Разрешены только русские буквы.\n";
	    return "";
	}
	
	function validSname(field) {
		if (field == "") return "Укажите вашу фамилию.\n";
		else if (/[^А-Я-Ё]/gi.test(field)) return "Разрешены только русские буквы.\n";
	    return "";
	}	
	function validSex(field) {
		return (field == "") ? "Укажите ваш пол.\n" : "";
	}
	function validDbirth(field) {
		return (field == "") ? "Укажите дату вашего рождения.\n" : "";
	}
}

function validTestingData(form) {
	fail  = validData(form.num_users.value);
	fail += validData(form.time.value);	
	if(fail == "") return true
    else {
		O('err_msg').innerHTML = fail;
		return false;
	}	
    
	function validData(field) {
		if (field == "") return "Заполните поля.\n";
		else if (/[^0-9]/g.test(field)) return "Разрешены только цифры.\n";
	    return "";
	}	
}
//Приглашаем к тестированию и подключаемся к WS(beta, просто объявление, не используется)
function Hello(ids, time) {
	     var endWord = 'вопросов';		
	     if( ids == 1 || ids == 21 || ids == 31 ) endWord = 'вопрос';
		 else if (ids == 2 || ids == 3 || ids == 4 || ids == 22 || ids == 23 || ids == 24 || ids == 32 || ids == 33 || ids == 34) endWord = 'вопроса';
         var hello = "Ответьте на " + ids + " " + endWord + ".\n Прочтите вопрос, затем нажмите Ответить.\n На каждый ответ отведено " + time + " секунд. ";
		 alert(hello);		 
}
//Генератор сч 0...9
function mathRand(){
        return Math.floor(Math.random()*11);
}
//считаем проценты
function mathPercent(a,b){
   
   var c = Math.floor(100*a*(1/(a+b)));
   var p1 = c + '%';
   S('progress').backgroundColor = 'none';
   S('prog_v1').width = p1;
   O('prog_v1').innerHTML = '<b>' + p1 + '</b>';
   var d = 100-c;
   var p2 = d + '%';
   O('prog_v2').innerHTML = '<b>' + p2 + '</b>';  
}
/////Подключаемся к WS
function wsConnect() {
window.onload = function () {
         socket = new WebSocket("ws://" + host + ":8889");
         var count1 = count2 = 0;
		 var status = O("credits");
         //чекнем тип тестирования         
         $.post('functions.php', { whatT: 'TRUE'}, function (data) {
		 typeT = data;
		 });
		 
              socket.onopen    = function (event) {
				  status.innerHTML += "<br>Соединение c WS установлено!";   
                         }
			  socket.onclose   = function (event)  {
                         if (event.wasClean) {
                               status.innerHTML += "Соединение c WS закрыто";  
                                 }     
                         else {
                               status.innerHTML += "Cоединение c WS закрыто плохо";
                             }
                               status.innerHTML += "<br>Код: " +event.code + " причина:" + event.reason;
                         }
              socket.onerror   = function (event)   {
                      status.innerHTML = "ошибка: " + event.message;
                      //location.reload();					  
                         }
              socket.onmessage = function (event) {               				   
                   ++count_t;				   
				   O('count_t').innerHTML = count_t;
				   var message = JSON.parse(event.data);
                   if(typeT == 'FICTION') {
				   var weight = O('weight').value;
				   var rand = mathRand();			  
                   //console.log("Random: " + rand);
                   //console.log("Weight: " + weight);				   
				   if (weight == rand){ //Extremum
				        if (rand <= 4) { 
						++count1; 
						//O('v1').innerHTML = count1;
						//ZDES
						var v1 = count1;
						var v2 = count2;
						mathPercent(v1,v2);
						
						}
                        else if (rand >= 5) { 
						++count2; 
						//O('v2').innerHTML = count2;
						//ZDES
						var v1 = count1;
						var v2 = count2;
						mathPercent(v1,v2);
						}
				   }
				   else if (weight < rand) { 
				   ++count1; 
				   //O('v1').innerHTML = count1;
				   //ZDES
				   var v1 = count1;
				   var v2 = count2;
				   mathPercent(v1,v2);
				   }
				   else if (weight > rand) { 
				   ++count2; 
				   //O('v2').innerHTML = count2;
				   //ZDES
				   var v1 = count1;
				   var v2 = count2;
				   mathPercent(v1,v2);
				   }
				   
				   } //if FICTION				   
				   else {
				   if (message == 'v1') { 
				   ++count1; 
				   //O('v1').innerHTML = count1;
				   //ZDES
				   var v1 = count1;
				   var v2 = count2;
				   mathPercent(v1,v2);
				   }
                   else if (message == 'v2') { 
				   ++count2; 
				   //O('v2').innerHTML = count2;
				   //ZDES
				   var v1 = count1;
				   var v2 = count2;
				   mathPercent(v1,v2);
				   }
				   }				   
                  // O('wait').innerHTML = "Подождите, пока остальные ответят на предыдущий вопрос. Дано ответов: " + count_t;
                   
				   $.post('functions.php', {howNum: 'how_many'}, function(data) {
				       
					   data = data.split("_");					  
					   if(count_t == data[0]) {
						   count1 = count2 = count_t = 0;
						   timerTest(data[1]); //запускаем заново счетчик при открытии нового вопроса
						   S('wrap_question_cl').zIndex = '1';
						   O('wait').innerHTML = '';
						   O('v1').innerHTML = '';
						   O('v2').innerHTML = '';
						   
						   S('progress').backgroundColor = 'white';
                           S('prog_v1').width = '0';
                           O('prog_v1').innerHTML = '';
						   O('prog_v2').innerHTML = '';
						   O('count_t').innerHTML = '';
						   
					   }
					   if(!data[0]) {
						   clearInterval(timeOut);
						   S('wrap_question_cl').zIndex = '1';
						   S('credits').display = 'none';
						   O('wait').innerHTML = '';
						   S('v1').display = 'none';
						   S('v2').display = 'none';
						   O('count_t').innerHTML = '';
						   
					   }
					   })				   
                         }
              document.forms[0].onsubmit = function () {
				         
				         var message = this.otvet.value;
						 if(message == 0) var promt = confirm("Вы действительно хотите воздержаться от ответа?");
						 else {
						 clearInterval(timeOut);						 
					     S('wrap_question_cl').zIndex = '-1';
						 O('wait').innerHTML = "Подождите, пока остальные ответят на предыдущий вопрос";
                         socket.send(JSON.stringify(message));
						 $.post('functions.php', { otvet: message}, function(data) {
		                 if(data){						  
                         O('form').innerHTML = data;		
						 } } )
						      } //else
						 if(promt) {
						 clearInterval(timeOut);	 
						 message = "Воздержался";		
				         S('wrap_question_cl').zIndex = '-1';
						 O('wait').innerHTML = "Подождите, пока остальные ответят на предыдущий вопрос";
                         socket.send(JSON.stringify(message));
						 $.post('functions.php', { otvet: message}, function(data) {
		                 if(data){
                         O('form').innerHTML = data;		
						 } } ) 
						 }
						 						 
                         return false;
                         }						 
		 } //onload
}
//Отображаем таймер и не только
function timerTest(t) {
       var timer = O('timer');
	   timer.style.zIndex = '1'; 
	   timeOut = setInterval( function(){
		   timer.innerHTML = --t + " сек. осталось";
		    console.log("Tick: " + t);
		    if (t == 0) {
			clearInterval(timeOut);	
     		socket.send(JSON.stringify("v3"));
			
			$.post('functions.php', { otvet: 'timeout'}, function(data) {
		                 if(data){						 	 
                         O('form').innerHTML = data;		
						 } } )
			}			
	   },1000);
}
//покзываем вес оператору для дальнейшей его установки
function setWeight(type) {
	 var set = document.getElementsByClassName('weight');
	 if(type == 'FICTION') {
		 typeT = type;
         for ( var i=0; i < set.length; ++i) {
	           set[i].style.zIndex = '1';
	 }
	 }
	 else {
		 typeT = type;
	     for ( var i=0; i < set.length; ++i) {
	           set[i].style.zIndex = '-1';
	 }	 
	 }
}
//AJAX to PDF
function makePDF(arr) {
	var message = JSON.stringify(arr);
	if (location.pathname == '/statquestion.php') {
	$.post('print/print_stat.php', {JSON: message}, function(data) {
		//O('pdf').innerHTML = data; //for debug 
		window.location.href = data;
	})
	}
	else if (location.pathname == '/result.php') {
	$.post('print/print_table.php', {JSON: message}, function(data) {
	////O('pdf').innerHTML = data; //for debug 
		window.location.href = data;
	})
	//console.log(arr);
	}
	
}

//Действия по умолчанию
$(document).ready(function() {
 
   //Проверяем вопросы в буфере
   if (location.pathname == '/questions.php' || location.pathname == '/search.php') {
             $.post( 'functions.php', { buffer: 'GET'}, function(data) {			  
			  
			  if(data !== 'EMPTY') {
				 var buffer = document.getElementsByClassName('buffer');
			     set = data.split('_')
		         for( var i=0; i < set.length; ++i) {
					 for( var j=0; j < buffer.length; ++j) {
			          if( buffer[j].id == set[i]) O(set[i]).value = 'В буфере';  
					                          }
			                             }
			                        }									
			                    })
   
   }

}) 
