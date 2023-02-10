/*
 *  Swiftly Admin Panel v1.12 alpha
 *  (c) 2019-2020 INSOweb  <http://insoweb.ru>
 *  All rights reserved.
 */


var status_console_full     = false,
    height_console_full     = 0,
    width_console_full      = 0,
    top_console_full        = 0,
    open_consolevar         = false,
    left_console_full       = 0,
    makeITname,
    n                       = 0,
    nMAX                    = 29,
    console_main_boolean    = true;


var Console = {
  lastWidth: 0,
  lastHeight: 0,
  lastPosX: 0,
  lastPosY: 0,
  isMoving: false,
  log: function(a){
    textFunc = a;
    console.log(a);

    if(typeof(textFunc) == 'string'){
      textFunc = '<span style="color: #ffe0a7;">"' + textFunc + '"</span>';
    }
    if(typeof(textFunc) == 'number'){
      textFunc = '<span style="color: #676cff;">' + textFunc + '</span>';
    }
    if(typeof(textFunc) == 'bigint'){
      textFunc = '<span style="color: #676cff;">' + textFunc + '</span>';
    }
    if(typeof(textFunc) == 'boolean'){
      textFunc = '<span style="color: #fd71ff;">' + textFunc + '</span>';
    }
    if(typeof(textFunc) == 'undefined'){
      textFunc = '<span style="color: #ffe635;">' + textFunc + '</span>';
    }
    //   if(typeof(textFunc) == 'object'){
    //     var tmpCount = textFunc.length;
    //     for(let i = 0; i < tmpCount; i++){
    //
    //     }
    //   }

    $('.console-main-textarea').append(textFunc + '</br>');
    // return null;
  }
};

function open_console(){

  var width = window.innerWidth;

  if(!open_consolevar){
    notification_add('line','','Консоль открыта.  Help - помощь.',5)
    open_consolevar = true;
    if(width < 830){
      $('console').css('width','95vw')
      $('console').css('left','50vw')
      $('console').css('transform','translate(-50%, -50%)')
      $('console').css('top','50vh')
      $('console').css('height','80vh')
      $('.console-head').css('border-radius','15px 15px 0px 0px')
      $('.console-main').css('border-radius','0px 0px 15px 15px')
      $('console').css('border-radius','15px')
    } else {
      $('console').css('width','50vw')
      $('console').css('left','70px')
      $('console').css('top','70px')
      $('console').css('height','350px')
    }
    $('console').css('display','block')
    setTimeout(function(){
      $('console').css('opacity','1')
    }, 10)
  } else {
    open_consolevar = false;
    console_close();
  }
}

function console_close(){

  open_consolevar = false;
  $('.menu-console').css('opacity','0')
  $('console').css('transition','0.095s cubic-bezier(0, 1.14, 1, 1) all')
  setTimeout(function(){
    $('console').css('opacity','0')
    setTimeout(function(){
      $('.menu-console').css('display','none')
      $('console').css('display','none')
      $('.console-head-btn-full').attr('onclick','console_collapse()')
      $('console').css('min-height','200px')
      $('console').css('height', height_console_full + 'px')
      $('console').css('width', width_console_full + 'px')
      $('console').css('top', top_console_full)
      $('console').css('left', left_console_full)

      $('.console-head-btn-full-line-2').css('opacity','0')
      $('.console-head-btn-full-line').css('transform','rotate(0deg) translate(0%, 0px)')
      $('.console-head-btn-full-line').css('margin-top','9px')
      $('.console-head-btn-full-line-2').css('margin-top','0px')
      $('.console-head-btn-full-line-2').css('width','50%')
      $('.console-head-btn-full-line').css('width','50%')
      $('.console-head-btn-full-line-2').css('transform','rotate(0deg) translate(0%, 0px)')
      $('.console-head-btn-full').attr('title','Свернуть')
      $('console').css('transition','0.25s cubic-bezier(0, 1.14, 1, 1) all')
      $('.console-main-textarea').html('')
    }, 250)
  }, 10)
}

function console_expand(){
  var width = window.innerWidth;

  if(width < 830){
    $('console').css('width','95vw')
    $('console').css('left','50vw')
    $('console').css('transform','translate(-50%, -50%)')
    $('console').css('top','50vh')
    $('console').css('height','80vh')
    $('.console-head').css('border-radius','15px 15px 0px 0px')
    $('.console-main').css('border-radius','0px 0px 15px 15px')
    $('console').css('border-radius','15px')
  } else{
    $('console').css('height', height_console_full + 'px')
    $('console').css('width', width_console_full + 'px')
    $('console').css('top', top_console_full)
    $('console').css('left', left_console_full)
  }
  $('.console-head-btn-full').attr('onclick','console_collapse()')
  $('console').css('min-height','200px')

  $('console').css('overflow','visible')
  $('.console-head-btn-full-line-2').css('opacity','0')
  $('.console-head-btn-full-line').css('transform','rotate(0deg) translate(0%, 0px)')
  $('.console-head-btn-full-line').css('margin-top','9px')
  $('.console-head-btn-full-line-2').css('margin-top','0px')
  $('.console-head-btn-full-line-2').css('width','50%')
  $('.console-head-btn-full-line').css('width','50%')
  $('.console-head-btn-full-line-2').css('transform','rotate(0deg) translate(0%, 0px)')
  $('.console-head-btn-full').attr('title','Свернуть')
}

function console_collapse(a){

  var width = window.innerWidth;

  height_console_full = $('console').height(),
  width_console_full  = $('console').width(),
  top_console_full    = $('console').css('top'),
  left_console_full   = $('console').css('left');

  if(width < 830){
    $('console').css('left','calc(50vw)')
  } else{
    $('console').css('left','calc(50vw - 100px)')
  }

  $('console').css('min-height','0px')
  $('console').css('height','30px')
  $('console').css('width','200px')
  $('console').css('overflow','hidden')
  $('console').css('top','calc(100vh - 30px)')
  $('.console-head-btn-full-line-2').css('opacity','1')
  $('.console-head-btn-full-line').css('transform','rotate(-45deg) translate(-45%, 0px)')
  $('.console-head-btn-full-line').css('margin-top','7.5px')
  $('.console-head-btn-full-line-2').css('margin-top','7.5px')
  $('.console-head-btn-full-line-2').css('width','30%')
  $('.console-head-btn-full-line').css('width','30%')
  $('.console-head-btn-full').attr('title','Развернуть')
  $('.console-head-btn-full').attr('onclick','console_expand()')
  $('.console-head-btn-full-line-2').css('transform','rotate(45deg) translate(45%, 0px)')
}

$(document).ready(function(){
  $(".console-head").on("contextmenu", false);

  $('html').keydown(function(eventObject){
		if (event.altKey && event.keyCode == 67) { //если нажали Alt + C
			open_console()
	  }
	});

  $(document).mouseup(function (e) {
    var container = $(".menu-console");
    if (container.has(e.target).length === 0){
      if(event.button != 2){
        $(".menu-console").css('opacity','0')
        setTimeout(function(){
          $(".menu-console").css('display','none')
        },150)
      }
    }
  });

  $('#console-border-top').mousedown(function(){ consoleResize('top'); }).mouseup(function() { consoleResize('stop'); });
  $('#console-border-left').mousedown(function(){ consoleResize('left'); }).mouseup(function() { consoleResize('stop'); });
  $('#console-border-right').mousedown(function(){ consoleResize('right'); }).mouseup(function() { consoleResize('stop'); });
  $('#console-border-bottom').mousedown(function(){ consoleResize('bottom'); }).mouseup(function() { consoleResize('stop'); });
  $('#console-border-top-left').mousedown(function(){ consoleResize('top-left'); }).mouseup(function() { consoleResize('stop'); });
  $('#console-border-top-right').mousedown(function(){ consoleResize('top-right'); }).mouseup(function() { consoleResize('stop'); });
  $('#console-border-bottom-left').mousedown(function(){ consoleResize('bottom-left'); }).mouseup(function() { consoleResize('stop'); });
  $('#console-border-bottom-right').mousedown(function(){ consoleResize('bottom-right'); }).mouseup(function() { consoleResize('stop'); });

});

$(document).ready(function(){

  $(document).mousedown(function (e){
    var div = $("console");
    if($("console").css('display') != 'none'){
      if (!div.is(e.target) && div.has(e.target).length === 0) {
        $('console').css('box-shadow','0px 0px 12px -7px rgba(0,0,0,0.44)')
        $('.console-main').css('backdrop-filter','saturate(180%) blur(490px)')
      } else{
        $('console').css('box-shadow','0px 0px 12px -4px rgba(0,0,0,0.44)')
        $('.console-main').css('backdrop-filter','saturate(180%) blur(6px)')
      }
    }
  });

  $(document).keydown(function(eventObject){
    if($('console').css('display') == 'block'){
      if (event.keyCode == 38) { //если нажали вверх
        if(arrayHistoryConsole.length != 0){
          if(n > 0){
            n--;
            $('.console-main-input-block').val(arrayHistoryConsole[n])
          } else{
            n = arrayHistoryConsole.length - 1;
            $('.console-main-input-block').val(arrayHistoryConsole[n])
          }
          $('.console-main-input-block').blur()
          $('.console-main-input-block').focus()
        }
  	  }
      if (event.keyCode == 40) { //если нажали вниз
        if(arrayHistoryConsole.length != 0){
          if(n < arrayHistoryConsole.length - 1 && arrayHistoryConsole.length >= 0){
            $('.console-main-input-block').val(arrayHistoryConsole[n])
            n++;
          } else{
            $('.console-main-input-block').val(arrayHistoryConsole[n])
            n = 0;
          }
          $('.console-main-input-block').blur()
          $('.console-main-input-block').focus()
        }
  	  }
      if (event.keyCode != 40 && event.keyCode != 38) { //если нажали вниз
        n = 0;
      }
    }
	});
  $('.console-main-input-block').keydown(function(eventObject){
		if (event.keyCode == 13) { //если нажали Enter
			console_send('.console-main-input-block');
	  }
	});

  var console_head = document.getElementById('console');;
  var console_main = document.getElementById('console-main');

  var console_head = document.getElementById('console');;
  var console_main = document.getElementById('console-main');

  console_head.onmousedown = function(e) {

    if(!status_console_full){
      var coords = getCoords(console_main);
      var shiftX = e.pageX - coords.left;
      var shiftY = e.pageY - coords.top;

      console_main.style.position = 'fixed';
      moveAt(e);

      console_main.style.zIndex = 999999999999999999999999999999999999999999999; // над другими элементами
      $(console_main).css('transition','0s all')

      function moveAt(e) {
        console_main.style.left = e.clientX - shiftX + 'px';
        console_main.style.top = e.clientY - shiftY + 'px';
      }

      document.onmousemove = function(e) {
        moveAt(e);
      };

      document.onmouseup = function() {
        var topBlock = $(console_main.style.top.split('px'))[0];
        if(topBlock < 0){
          console_main.style.top = '0px';
        }


        document.onmousemove = null;
        console_head.onmouseup = null;
        $(console_main).css('transition','0.25s cubic-bezier(0, 1.14, 1, 1) all')
      };
    }

  }

  console_head.ondragstart = function() {
    return false;
  };

  function getCoords(elem) {   // кроме IE8-
    var box = elem.getBoundingClientRect();
    return {
      top: box.top + pageYOffset,
      left: box.left + pageXOffset
    };
  }

  function getCoords(elem) {   // кроме IE8-
    var box = elem.getBoundingClientRect();
    return {
      top: box.top + pageYOffset,
      left: box.left + pageXOffset
    };
  }

});

function consoleResize(arg) {

  // stop
  if(arg == 'stop') {
	$('console').css('transition','all 0.25s cubic-bezier(0, 1.14, 1, 1) 0s');
	Console.isMoving = false;
	return;
  }

  // run
  Console.lastWidth = $('console').outerWidth();
  Console.lastHeight = $('console').outerHeight();
  Console.lastPosX = $('console').offset().left;
  Console.lastPosY = $('console').offset().top;
  Console.isMoving = true;
  $('console').css('transition','0.01s all');

  // moving very прямо
  if(arg == 'top') {
      document.onmousemove = function(e) {
		if(Console.isMoving) {
		  var mx = e.clientX;
		  var my = e.clientY;
		  if(my <= (Console.lastPosY + Console.lastHeight - 200)) {
		    $('console').css('top', my);
		    $('console').css('height', (Console.lastHeight + (Console.lastPosY - my)));
		  }
		}
      };
  }
  if(arg == 'bottom') {
      document.onmousemove = function(e) {
		if(Console.isMoving) {
		  var mx = e.clientX;
		  var my = e.clientY;
		  if(my >= (Console.lastPosY + 200)) {
		    $('console').css('height', (my - Console.lastPosY));
		  }
		}
      };
  }
  if(arg == 'left') {
      document.onmousemove = function(e) {
		if(Console.isMoving) {
		  var mx = e.clientX;
		  var my = e.clientY;
		  if(mx <= (Console.lastPosX + Console.lastWidth - 150)) {
		    $('console').css('left', mx);
		    $('console').css('width', (Console.lastWidth + (Console.lastPosX - mx)));
		  }
		}
      };
  }
  if(arg == 'right') {
      document.onmousemove = function(e) {
		if(Console.isMoving) {
		  var mx = e.clientX;
		  var my = e.clientY;
		  if(mx >= (Console.lastPosX + 150)) {
		    $('console').css('width', (mx - Console.lastPosX));
		  }
		}
      };
  }
  // moving very криво
  if(arg == 'top-left') {
      document.onmousemove = function(e) {
		if(Console.isMoving) {
		  var mx = e.clientX;
		  var my = e.clientY;
		  if((my <= (Console.lastPosY + Console.lastHeight - 200)) && (mx <= (Console.lastPosX + Console.lastWidth - 150))) {
		    $('console').css('top', my);
		    $('console').css('height', (Console.lastHeight + (Console.lastPosY - my)));
		    $('console').css('left', mx);
		    $('console').css('width', (Console.lastWidth + (Console.lastPosX - mx)));
		  }
		}
      };
  }
  if(arg == 'top-right') {
      document.onmousemove = function(e) {
		if(Console.isMoving) {
		  var mx = e.clientX;
		  var my = e.clientY;
		  if((my <= (Console.lastPosY + Console.lastHeight - 200)) && (mx >= (Console.lastPosX + 150))) {
		    $('console').css('top', my);
		    $('console').css('height', (Console.lastHeight + (Console.lastPosY - my)));
			$('console').css('width', (mx - Console.lastPosX));
		  }
		}
      };
  }
  if(arg == 'bottom-left') {
      document.onmousemove = function(e) {
		if(Console.isMoving) {
		  var mx = e.clientX;
		  var my = e.clientY;
		  if((my >= (Console.lastPosY + 200)) && (mx <= (Console.lastPosX + Console.lastWidth - 150))) {
		    $('console').css('height', (my - Console.lastPosY));
		    $('console').css('left', mx);
		    $('console').css('width', (Console.lastWidth + (Console.lastPosX - mx)));
		  }
		}
      };
  }
  if(arg == 'bottom-right') {
      document.onmousemove = function(e) {
		if(Console.isMoving) {
		  var mx = e.clientX;
		  var my = e.clientY;
		  if((my >= (Console.lastPosY + 200)) && (mx >= (Console.lastPosX + 150))) {
		    $('console').css('height', (my - Console.lastPosY));
			$('console').css('width', (mx - Console.lastPosX));
		  }
		}
      };
  }
}

function console_inclinator(number, decl) {
  var strnum = String(number);
  var number1 = Number(strnum.substring(strnum.length - 1, strnum.length));
  var number2 = Number(strnum.substring(strnum.length - 2, strnum.length));
  if((number2 >= 10 && number2 <= 20) || (number1 >= 5 && number1 <= 9) || number1 == 0) {
    // записей действий
    if(decl == 'records'){
      return 'записей действий';
    }
  }
  else if(number1 == 1) {
    // запись действия
    if(decl == 'records'){
      return 'запись действия';
    }
  }
  else if(number1 >= 2 && number1 <= 4) {
    // записи действия
    if(decl == 'records'){
      return 'записи действия';
    }
  }
  else {
    console.log(number1);
    console.log(number2);
    return undefined;
  }
}

function console_send(block){

  // ========================- Variables (START) -=========================== //

  var tmpString = $(block).val(); // Value in line
  var сlear_command = /^(cls|clear|del)$/ui;  // Clear commands
  var help_command = /^(help|\?|info)/ui;  // Help commands
    var help_0_command = /^(help|\?|info)$/ui;;  // Help commands
  var serial_key_command = /^(serial_key)$/ui;  // serial_key commands
  var list_dir_command = /^(dir list)$/ui;  // list dir commands
  var hash_key_command = /^(hash_key)$/ui;  // hash_key commands
  var close_command = /^(exit|close)$/ui;  // Clear commands
  var history_command = /^(history)/ui; // History commands
    var history_0_command = /^(history)$/ui;
    var history_max_command = /^(history)\s*(max)\s*([0-9]*)$/ui;
    var history_clear_command = /^(history)\s*(clear|cls|del)$/ui;

  var lines_command = /^(lines|line)$/ui;  // Clear commands

  var program_command = /^(program)/ui;
    var program_theme_command = /^(program)\s*(theme)\s*(white|black|light|dark)/ui;
    var program_update_command = /^(program)\s*(update)/ui;

  var cookies_command = /^(cookie)/ui; // Cookies commands
    var cookies_0_command = /^(cookie)$/ui;

  var session_command = /^(session)/ui; // Session commands
    var session_0_command = /^(session)$/ui;

  var function_command = /(^([a-zA-Z._$]{1})([0-9a-zA-Z_$])+)((\({1})([A-zА-яЁё0-9_,./\\?=+'"!@#$%^&*№;:()\s-]*)(\)$))/ui; // Function commands
    var function_0_command = /((\({1})([A-zА-яЁё0-9_,./\\?=+'"!@#$%^&*№;:()\s-]*)(\)$))/ui;

  var server_command = /^(server)/ui; // Server commands
  var console_command = /^(Console)/ui; // Server commands
  var system_command = /^(system)/ui; // System commands
    var system_dump_command = /^(system)\s*(dump)$/ui;

  // =========================- Variables (END) -============================ //

  // ==========================- CODE (START) -============================== //

  // Find out the number of characters per line
  if(tmpString.replace(/\s+/g,'').length > 0){

    // We write our actions to the array
    if(tmpString != arrayHistoryConsole[arrayHistoryConsole.length - 1]){
      if(arrayHistoryConsole.length > nMAX){

        var tmpArrayHistoryConsole = '';
        for(let i = 0; i < arrayHistoryConsole.length; i++){
          tmpArrayHistoryConsole += arrayHistoryConsole[i] + '%8Delimiter8%';
        }
        tmpArrayHistoryConsole += tmpString;
        $.cookie('HistoryConsole', tmpArrayHistoryConsole, {expires: 99999});

        arrayHistoryConsole.shift()
        arrayHistoryConsole.push(tmpString)

      } else{
        var tmpArrayHistoryConsole = '';
        for(let i = 0; i < arrayHistoryConsole.length; i++){
          tmpArrayHistoryConsole += arrayHistoryConsole[i] + '%8Delimiter8%';
        }
        tmpArrayHistoryConsole += tmpString;
        $.cookie('HistoryConsole', tmpArrayHistoryConsole, {expires: 99999});
        arrayHistoryConsole.push(tmpString)
      }
    }

    // clear console
    if(tmpString.match(сlear_command)){
      $('.console-main-textarea').html('')
    }

    // close
    else if(tmpString.match(close_command)){
      $('.console-main-textarea').append('<c-good>До свидания! ;-)</c-good>')
      setTimeout(function(){
        console_close();
      }, 250)
    }

    // history
    else if(tmpString.match(history_command)){

      // history
      if(tmpString.match(history_0_command)){
        var tmpArrayExample = 'Array (' + arrayHistoryConsole.length + ')\n(';
        for(let i = 0; i < arrayHistoryConsole.length; i++){
          tmpArrayExample += '\n [' + i + '] => ' + arrayHistoryConsole[i];
        }
        tmpArrayExample += '\n)';

        $('.console-main-textarea').append('<c-good>Ваши данные из истории действий:</c-good></br>')
        $('.console-main-textarea').append('<c-default>' + tmpArrayExample + '</c-default></br>')
      }

      // history max 99
      if(tmpString.match(history_max_command)){
        var tmpCount = Number(tmpString.replace(/^(history)\s*(max)\s*/ui,''));

        arrayHistoryConsole = [];
        nMAX = tmpCount - 1;
        $.cookie('HistoryConsole', 'null', {expires: 99999});

        $('.console-main-textarea').append('<c-good>Установлено: ' + tmpCount + ' ' + console_inclinator(tmpCount, 'records') + '</c-good></br>')

      }

      // history (clear|cls)
      if(tmpString.match(history_clear_command)){
        arrayHistoryConsole = [];
        $.cookie('HistoryConsole', 'null', {expires: 99999});
        $('.console-main-textarea').append('<c-good>История консоли успешно очищена!</c-good></br>')

      }

    }

    // cookies
    else if(tmpString.match(cookies_command)){

      // print array cookies
      if(tmpString.match(cookies_0_command)){

        $('.console-main-textarea').append(tmpString + '</br>');
        // ajax request
        $.ajax({
          type: "POST",
          url: "php/console.php",
          data: {
            send: tmpString,
            type: 'cookie'
          },
          cache: false,
          error: function (response) {
            $('.console-main-textarea').append('<c-error>' + response + '</c-error></br>')
            $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
          },
          beforeSend: function(){
            loader('show');
          },
          complete: function(){
            loader('hidden');
          },
          success: function(response) {
            $('.console-main-textarea').append(response + '</br>')
            $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
          },
        }).done();

      }

    }

    // serial_key
    else if(tmpString.match(serial_key_command)){
      $.ajax({
        type: "POST",
        url: "php/console.php",
        data: {
          send: tmpString,
          type: 'serial_key'
        },
        cache: false,
        error: function (response) {
          $('.console-main-textarea').append('<c-error>' + response + '</c-error></br>')
          $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
        },
        beforeSend: function(){
          loader('show');
        },
        complete: function(){
          loader('hidden');
        },
        success: function(response) {
          $('.console-main-textarea').append(response + '</br>')
          $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
        },
      }).done();
    }

    // list_dir
    else if(tmpString.match(list_dir_command)){
      $.ajax({
        type: "POST",
        url: "php/console.php",
        data: {
          send: tmpString,
          type: 'list_dir'
        },
        cache: false,
        error: function (response) {
          $('.console-main-textarea').append('<c-error>' + response + '</c-error></br>')
          $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
        },
        beforeSend: function(){
          loader('show');
        },
        complete: function(){
          loader('hidden');
        },
        success: function(response) {
          $('.console-main-textarea').append(response + '</br>')
          $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
        },
      }).done();
    }

    // hash_key
    else if(tmpString.match(hash_key_command)){

      $.ajax({
        type: "POST",
        url: "php/console.php",
        data: {
          send: tmpString,
          type: 'hash_key'
        },
        cache: false,
        error: function (response) {
          $('.console-main-textarea').append('<c-error>' + response + '</c-error></br>')
          $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
        },
        beforeSend: function(){
          loader('show');
        },
        complete: function(){
          loader('hidden');
        },
        success: function(response) {
          $('.console-main-textarea').append(response + '</br>')
          $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
        },
      }).done();
    }

    // help
    else if(tmpString.match(help_command)){

      // help
      if(tmpString.match(help_0_command)){

        $('.console-main-textarea').append(tmpString + '</br>');
        $('.console-main-textarea').append('<c-help>Для получения сведений об определенной команде наберите HELP (имя команды)</c-help></br>');
        // ajax request
        $.ajax({
          type: "POST",
          url: "php/console.php",
          data: {
            send: tmpString,
            type: 'help'
          },
          cache: false,
          error: function (response) {
            $('.console-main-textarea').append('<c-error>' + response + '</c-error></br>')
            $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
          },
          beforeSend: function(){
            loader('show');
          },
          complete: function(){
            loader('hidden');
          },
          success: function(response) {
            $('.console-main-textarea').append(response + '</br>')
            $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
          },
        }).done();

      }

    }

    // program
    else if(tmpString.match(program_command)){

      // program theme black || white
      if(tmpString.match(program_theme_command)){
        var tmpStringTheme = tmpString.replace(/^(program)\s*(theme)\s*/ui,'');

        if(tmpStringTheme.match(/^(light|white|0)$/ui)){
          GlobalTheme = 'white';
          theme_chart = "light";
          updateChartsNew(theme_chart);
          $('html').get(0).style.setProperty('--color','#303036')
          $('html').get(0).style.setProperty('--colorI','#fff')
          $('html').get(0).style.setProperty('--dark','#101018')
          $('html').get(0).style.setProperty('--menu','#1e1e2d')
          $('html').get(0).style.setProperty('--menu-profile','#29293c')
          $('html').get(0).style.setProperty('--main-bg-search','#f2f3f8')
          $('html').get(0).style.setProperty('--main-bg','#f2f3f8')
          $('html').get(0).style.setProperty('--white','#fff')
          $('html').get(0).style.setProperty('--border-color','#d9e3e9')
          $('html').get(0).style.setProperty('--main-bg-2','#4f4f67')
          $('html').get(0).style.setProperty('--border-bg','#464646')
          $('html').get(0).style.setProperty('--shadow-name','rgba(41, 41, 60, 1)')
          $('html').get(0).style.setProperty('--menu-status','#41415b')
          $('html').get(0).style.setProperty('--bg-color-btn','#29293c')
          $('html').get(0).style.setProperty('--color-btn-hover','#303036')
          $('html').get(0).style.setProperty('--bg-color-scrollbar','#dadada96')
          $('html').get(0).style.setProperty('--noise-bg',"url('../media/img/white1noise.png')")
          opacity_save_settings();
          $('.console-main-textarea').append('<c-good>Установлена светлая тема</c-good></br>')
        }
        if(tmpStringTheme.match(/^(dark|black|1)$/ui)){
          GlobalTheme = 'black';
          theme_chart = "dark";
          updateChartsNew('dark');
          $('html').get(0).style.setProperty('--color','#fff')
          $('html').get(0).style.setProperty('--colorI','#fff')
          $('html').get(0).style.setProperty('--dark','#121212')
          $('html').get(0).style.setProperty('--menu','#121212')
          $('html').get(0).style.setProperty('--menu-profile','#2b2b2b')
          $('html').get(0).style.setProperty('--main-bg-search','#2b2b2b')
          $('html').get(0).style.setProperty('--main-bg','#1a1a1a')
          $('html').get(0).style.setProperty('--white','#222')
          $('html').get(0).style.setProperty('--border-color','#353535')
          $('html').get(0).style.setProperty('--main-bg-2','#1e1e2d')
          $('html').get(0).style.setProperty('--border-bg','#121212')
          $('html').get(0).style.setProperty('--shadow-name','rgba(43, 43, 43, 1)')
          $('html').get(0).style.setProperty('--menu-status','#434343')
          $('html').get(0).style.setProperty('--bg-color-btn','#333')
          $('html').get(0).style.setProperty('--color-btn-hover','#fff')
          $('html').get(0).style.setProperty('--bg-color-scrollbar','#2b2b2b')
          $('html').get(0).style.setProperty('--noise-bg',"url('../media/img/whitenoise.png')")
          opacity_save_settings();
          $('.console-main-textarea').append('<c-good>Установлена темная тема</c-good></br>')
        }
        $.cookie('theme', GlobalTheme, {expires: 99999});
      }

      // program theme auto
      if(tmpString.match(/^(program)\s*(theme)\s*$/ui)){
        if(GlobalTheme == 'black'){
          GlobalTheme = 'white';
          theme_chart = "light";
          updateChartsNew(theme_chart);
          $('html').get(0).style.setProperty('--color','#303036')
          $('html').get(0).style.setProperty('--colorI','#fff')
          $('html').get(0).style.setProperty('--dark','#101018')
          $('html').get(0).style.setProperty('--menu','#1e1e2d')
          $('html').get(0).style.setProperty('--menu-profile','#29293c')
          $('html').get(0).style.setProperty('--main-bg-search','#f2f3f8')
          $('html').get(0).style.setProperty('--main-bg','#f2f3f8')
          $('html').get(0).style.setProperty('--white','#fff')
          $('html').get(0).style.setProperty('--border-color','#d9e3e9')
          $('html').get(0).style.setProperty('--main-bg-2','#4f4f67')
          $('html').get(0).style.setProperty('--border-bg','#464646')
          $('html').get(0).style.setProperty('--shadow-name','rgba(41, 41, 60, 1)')
          $('html').get(0).style.setProperty('--menu-status','#41415b')
          $('html').get(0).style.setProperty('--bg-color-btn','#29293c')
          $('html').get(0).style.setProperty('--color-btn-hover','#303036')
          $('html').get(0).style.setProperty('--bg-color-scrollbar','#dadada96')
          $('html').get(0).style.setProperty('--noise-bg',"url('../media/img/white1noise.png')")
          opacity_save_settings();
          $('.console-main-textarea').append('<c-good>Установлена светлая тема</c-good></br>')
        }
        else if(GlobalTheme == 'white'){
          GlobalTheme = 'black';
          theme_chart = "dark";
          updateChartsNew('dark');
          $('html').get(0).style.setProperty('--color','#fff')
          $('html').get(0).style.setProperty('--colorI','#fff')
          $('html').get(0).style.setProperty('--dark','#121212')
          $('html').get(0).style.setProperty('--menu','#121212')
          $('html').get(0).style.setProperty('--menu-profile','#2b2b2b')
          $('html').get(0).style.setProperty('--main-bg-search','#2b2b2b')
          $('html').get(0).style.setProperty('--main-bg','#1a1a1a')
          $('html').get(0).style.setProperty('--white','#222')
          $('html').get(0).style.setProperty('--border-color','#353535')
          $('html').get(0).style.setProperty('--main-bg-2','#1e1e2d')
          $('html').get(0).style.setProperty('--border-bg','#121212')
          $('html').get(0).style.setProperty('--shadow-name','rgba(43, 43, 43, 1)')
          $('html').get(0).style.setProperty('--menu-status','#434343')
          $('html').get(0).style.setProperty('--bg-color-btn','#333')
          $('html').get(0).style.setProperty('--color-btn-hover','#fff')
          $('html').get(0).style.setProperty('--bg-color-scrollbar','#2b2b2b')
          $('html').get(0).style.setProperty('--noise-bg',"url('../media/img/whitenoise.png')")
          opacity_save_settings();
          $('.console-main-textarea').append('<c-good>Установлена темная тема</c-good></br>')
        }
        $.cookie('theme', GlobalTheme, {expires: 99999});
      }

      // program update
      if(tmpString.match(program_update_command)){
        $('.console-main-textarea').append('<c-info>Загрузка...</c-info></br>')
        updateAP();
      }

    }

    // session
    else if(tmpString.match(session_command)){

      // print array session
      if(tmpString.match(session_0_command)){

        $('.console-main-textarea').append(tmpString + '</br>');
        // ajax request
        $.ajax({
          type: "POST",
          url: "php/console.php",
          data: {
            send: tmpString,
            type: 'session'
          },
          cache: false,
          beforeSend: function(){
            loader('show');
          },
          complete: function(){
            loader('hidden');
          },
          error: function (response) {
            $('.console-main-textarea').append('<c-error>' + response + '</c-error></br>')
            $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
          },
          success: function(response) {
            $('.console-main-textarea').append(response + '</br>')
            $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
          },
        }).done();

      }

    }

    // function
    else if(tmpString.match(function_command)){

      if(tmpString.match(console_command)){
        var tmpConsole = tmpString.replace(function_0_command, '');
        $('.console-main-textarea').append(tmpString + '</br>');
        setTimeout(function(){
          Console.log(tmpConsole);
        }, 1)
      } else{
        if(isFunction(window[tmpString.replace(function_0_command, '')])){
          var textFunc = eval(tmpString);

          if(typeof(textFunc) == 'string'){
            textFunc = '<span style="color: #ffe0a7;">"' + textFunc + '"</span>';
          }
          if(typeof(textFunc) == 'number'){
            textFunc = '<span style="color: #676cff;">' + textFunc + '</span>';
          }
          if(typeof(textFunc) == 'bigint'){
            textFunc = '<span style="color: #676cff;">' + textFunc + '</span>';
          }
          if(typeof(textFunc) == 'boolean'){
            textFunc = '<span style="color: #fd71ff;">' + textFunc + '</span>';
          }
          if(typeof(textFunc) == 'undefined'){
            textFunc = '<span style="color: #ffe635;">' + textFunc + '</span>';
          }

          $('.console-main-textarea').append('<c-func>function</c-func> ' + tmpString + ';</br>');
          $('.console-main-textarea').append(textFunc + '</br>');
        } else{
          $('.console-main-textarea').append('<c-error>Данной функции не существует!</c-error></br>')
        }
      }

    }

    // server
    else if(tmpString.match(server_command)){
      $('.console-main-textarea').append(tmpString + '</br>');
      tmpString = tmpString.replace(/^(server)\s*/ui, '');

      $.ajax({
        type: "POST",
        url: "php/console.php",
        data: {
          send: tmpString,
          type: 'func'
        },
        beforeSend: function(){
          loader('show');
        },
        complete: function(){
          loader('hidden');
        },
        cache: false,
        error: function (response) {
          $('.console-main-textarea').append('<c-error>' + response + '</c-error></br>')
          $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
        },
        success: function(response) {
          $('.console-main-textarea').append(response + '</br>')
          $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
        },
      }).done();
    }

    // system
    else if(tmpString.match(system_command)){

      // system dump
      if(tmpString.match(system_dump_command)){


        var height_window_DUMP = window.innerHeight,
          width_window_DUMP = window.innerWidth,
          height_screen_DUMP = screen.height,
          width_screen_DUMP = screen.width,
          browser_name_DUMP = navigator.appName,
          browser_ver_DUMP = navigator.appVersion,
          platform_name_DUMP = navigator.platform,
          lang_DUMP = navigator.language,
          cookie_DUMP = navigator.cookieEnabled;

          $('.console-main-textarea').append(tmpString + '</br>');

          takeScreenShot();

          setTimeout(function(){
            $.ajax({
              type: "POST",
              url: "php/console.php",
              data: {
                send: tmpString,
                type: 'dump',
                screenshot: makeITname
              },
              beforeSend: function(){
                loader('show');
              },
              complete: function(){
                loader('hidden');
              },
              cache: false,
              error: function (response) {
                $('.console-main-textarea').append('<c-error>' + response + '</c-error></br>')
                $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
              },
              success: function(response) {
                $('.console-main-textarea').append(response + '</br>')
                $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
              },
            }).done();
          }, 500)
      }

    }

    // lines
    else if(tmpString.match(lines_command)){

      $.ajax({
        type: "POST",
        url: "php/console.php",
        data: {
          type: 'lines'
        },
        beforeSend: function(){
          loader('show');
        },
        complete: function(){
          loader('hidden');
        },
        cache: false,
        error: function (response) {
          $('.console-main-textarea').append('<c-error>' + response + '</c-error></br>')
          $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
        },
        success: function(response) {
          $('.console-main-textarea').append("В текущем проекте <c-good>" + response + '</c-good> строк</br>')
          $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
        },
      }).done();

    }

    // other
    else{
      var textFunc = eval(tmpString);

      if(typeof(textFunc) == 'string'){
        textFunc = '<span style="color: #ffe0a7;">"' + textFunc + '"</span>';
      }
      if(typeof(textFunc) == 'number'){
        textFunc = '<span style="color: #676cff;">' + textFunc + '</span>';
      }
      if(typeof(textFunc) == 'bigint'){
        textFunc = '<span style="color: #676cff;">' + textFunc + '</span>';
      }
      if(typeof(textFunc) == 'boolean'){
        textFunc = '<span style="color: #fd71ff;">' + textFunc + '</span>';
      }
      if(typeof(textFunc) == 'undefined'){
        textFunc = '<span style="color: #ffe635;">' + textFunc + '</span>';
      }

      $('.console-main-textarea').append(tmpString + '</br>');
      $('.console-main-textarea').append(textFunc + '</br>');
    }



    // clears input and focus
    $(block).val('');
    $(block).focus();
  } else{
    // Add new empty line
    $('.console-main-textarea').append('<br>')
  }

  // scroll down the console
  $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));

  // ===========================- CODE (END) -=============================== //
}

function takeScreenShot() {

  var height_window_DUMP = window.innerHeight,
      width_window_DUMP = window.innerWidth,
      height_screen_DUMP = screen.height,
      width_screen_DUMP = screen.width,
      browser_name_DUMP = navigator.appName,
      browser_ver_DUMP = navigator.appVersion,
      platform_name_DUMP = navigator.platform,
      lang_DUMP = navigator.language,
      cookie_DUMP = navigator.cookieEnabled,
      tim=new Date();

    $('console').css('opacity','0')
    $('dev').css('display','block')
    $('dev').html('<dev-b>Дата и время: </dev-b>' + tim.getDate() + '.' + (tim.getMonth() + 1) + '.' + tim.getFullYear() + ' ' + tim.getHours() + ':' + tim.getMinutes() + ':' + tim.getSeconds() + '<br><dev-b>Высота окна: </dev-b>' + height_window_DUMP + 'px<br><dev-b>Ширина окна: </dev-b>' + width_window_DUMP + 'px<br><dev-b>Высота экрана: </dev-b>' + height_screen_DUMP + 'px<br><dev-b>Ширина экрана: </dev-b>' + width_screen_DUMP + 'px<br><dev-b>Имя браузера: </dev-b>' + browser_name_DUMP + '<br><dev-b>Версия браузера: </dev-b>' + browser_ver_DUMP + '<br><dev-b>Тип платформы: </dev-b>' + platform_name_DUMP + '<br><dev-b>Язык системы: </dev-b>' + lang_DUMP + '<br><dev-b>Поддержка cookies: </dev-b>' + cookie_DUMP)
    $('console').css('z-index','-9999999999999999999999999999999999999999999999999999999999999999')
    const img = new Image();

      html2canvas(jQuery("body")[0]).then(function(canvas) {
      	const url = canvas.toDataURL('image/png');
      	img.src = url;
      	document.body.appendChild(img);
        makeIT(url);
        return;
      });
}

function console_head_menu(a){
  var mouse_menu_console_x = (a.value = event.offsetX==undefined?event.layerX:event.offsetX),
      mouse_menu_console_y = (a.value = event.offsetY==undefined?event.layerY:event.offsetY);

  $('.menu-console').css('opacity','0')
  setTimeout(function(){
    $('.menu-console').css('display','none')
    setTimeout(function(){
      $('.menu-console').css('display','block')
      $('.menu-console').css('left',mouse_menu_console_x + 'px')
      $('.menu-console').css('top',mouse_menu_console_y + 'px')
      setTimeout(function(){
        $('.menu-console').css('opacity','1')
      }, 150)
    }, 10)
  }, 150)
}

function console_full(){
  if(status_console_full){
    status_console_full = false;
    $('console').css('height', height_console_full + 'px')
    $('console').css('width', width_console_full + 'px')
    $('console').css('left',left_console_full)
    // $('console').css('left', left_console_full)
    // $('console').css('top', top_console_full)
    $('.console-head').css('border-radius','4.5px 4.5px 0px 0px')
    $('.console-main').css('border-radius','0px 0px 4.5px 4.5px')
    $('.console-head').css('filter','invert(0)')
  } else{
    status_console_full = true;
    setTimeout(function(){
      height_console_full = $('console').height(),
      width_console_full  = $('console').width(),
      top_console_full    = $('console').css('top'),
      left_console_full   = $('console').css('left');
      $('console').css('height','100vh')
      $('console').css('width','100vw')
      $('console').css('left','0')
      $('console').css('top','0')
      $('.console-head').css('border-radius','0px')
      $('.console-main').css('border-radius','0px')
      $('.console-head').css('filter','invert(1)')
    }, 00)

  }
}

function makeIT(a){
    //получаем картинку в base64
    var data = a.replace(/data:image\/png;base64,/, '');

    //все возникшие проблемы решились удалением canvas
    $('img').remove();

    //засылаем картинку на сервер
    $.post('php/screenshot.php',{data:data, login: userData['login']}, function(rep){
      Console.log('Имя папки: ' + rep)
         makeITname = rep;
         $('console').css('opacity','1')
         $('dev').css('display','none')
         $('console').css('z-index','9999999999999999999999999999999999999999999999999999999999999999')
    });



}
