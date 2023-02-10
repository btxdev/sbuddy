var time_status = false;

function time_close(a){
  time_status = false
  $(a).css('opacity','0')
  setTimeout(function(){
    $(a).css('display','none')
  }, 150)
}
function open_time(a){
  if(time_status){
    time_close('#time-main');
  } else{
    time_status = true;
    $(a).css('display','block')
    setTimeout(function(){
      $(a).css('opacity','1')
    }, 1)
  }
}

function time_timer_add(a ,b){
  var tmpParentBlock  =  $(b).parent().parent(),
      tmpFirst        =  $(tmpParentBlock.find('.window-time-main-timer-input-elem-block-elem')[0]),
      tmpSecond       =  $(tmpParentBlock.find('.window-time-main-timer-input-elem-block-elem')[1]);

  if(a == 0){
    if(Number(tmpFirst.text()) < 9){
      tmpFirst.text(Number(tmpFirst.text()) + 1)
    }
    if(Number(tmpFirst.text()) != 0){
      $(tmpParentBlock.find('.window-time-main-timer-input-elem-change-elem')[2]).css({'opacity':'0.5','visibility':'visible'})
    }
    if(Number(tmpFirst.text()) == 9){
      $(b).css({'opacity':'0','visibility':'hidden'})
    } else{
      $(b).css({'opacity':'0.5','visibility':'visible'})
    }
  }
  if(a == 1){
    if(Number(tmpSecond.text()) < 9){
      tmpSecond.text(Number(tmpSecond.text()) + 1)
    }
    if(Number(tmpSecond.text()) != 0){
      $(tmpParentBlock.find('.window-time-main-timer-input-elem-change-elem')[3]).css({'opacity':'0.5','visibility':'visible'})
    }
    if(Number(tmpSecond.text()) == 9){
      $(b).css({'opacity':'0','visibility':'hidden'})
    } else{
      $(b).css({'opacity':'0.5','visibility':'visible'})
    }
  }
  if(a == 2){
    if(Number(tmpFirst.text()) >= 1){
      tmpFirst.text(Number(tmpFirst.text()) - 1)
    }
    if(Number(tmpFirst.text()) != 9){
      $(tmpParentBlock.find('.window-time-main-timer-input-elem-change-elem')[0]).css({'opacity':'0.5','visibility':'visible'})
    }
    if(Number(tmpFirst.text()) == 0){
      $(b).css({'opacity':'0','visibility':'hidden'})
    } else{
      $(b).css({'opacity':'0.5','visibility':'visible'})
    }
  }
  if(a == 3){
    if(Number(tmpSecond.text()) >= 1){
      tmpSecond.text(Number(tmpSecond.text()) - 1)
    }
    if(Number(tmpSecond.text()) != 9){
      $(tmpParentBlock.find('.window-time-main-timer-input-elem-change-elem')[1]).css({'opacity':'0.5','visibility':'visible'})
    }
    if(Number(tmpSecond.text()) == 0){
      $(b).css({'opacity':'0','visibility':'hidden'})
    } else{
      $(b).css({'opacity':'0.5','visibility':'visible'})
    }
  }
  if(a == 4){
    if(Number(tmpFirst.text()) < 9){
      tmpFirst.text(Number(tmpFirst.text()) + 1)
    }
    if(Number(tmpFirst.text()) != 0){
      $(tmpParentBlock.find('.window-time-main-timer-input-elem-change-elem')[2]).css({'opacity':'0.5','visibility':'visible'})
    }
    if(Number(tmpFirst.text()) == 9){
      $(b).css({'opacity':'0','visibility':'hidden'})
    } else{
      $(b).css({'opacity':'0.5','visibility':'visible'})
    }
  }
  if(a == 5){
    if(Number(tmpSecond.text()) < 9){
      tmpSecond.text(Number(tmpSecond.text()) + 1)
    }
    if(Number(tmpSecond.text()) != 0){
      $(tmpParentBlock.find('.window-time-main-timer-input-elem-change-elem')[3]).css({'opacity':'0.5','visibility':'visible'})
    }
    if(Number(tmpSecond.text()) == 9){
      $(b).css({'opacity':'0','visibility':'hidden'})
    } else{
      $(b).css({'opacity':'0.5','visibility':'visible'})
    }
  }
  if(a == 6){
    if(Number(tmpFirst.text()) >= 1){
      tmpFirst.text(Number(tmpFirst.text()) - 1)
    }
    if(Number(tmpFirst.text()) != 9){
      $(tmpParentBlock.find('.window-time-main-timer-input-elem-change-elem')[0]).css({'opacity':'0.5','visibility':'visible'})
    }
    if(Number(tmpFirst.text()) == 0){
      $(b).css({'opacity':'0','visibility':'hidden'})
    } else{
      $(b).css({'opacity':'0.5','visibility':'visible'})
    }
  }
  if(a == 7){
    if(Number(tmpSecond.text()) >= 1){
      tmpSecond.text(Number(tmpSecond.text()) - 1)
    }
    if(Number(tmpSecond.text()) != 9){
      $(tmpParentBlock.find('.window-time-main-timer-input-elem-change-elem')[1]).css({'opacity':'0.5','visibility':'visible'})
    }
    if(Number(tmpSecond.text()) == 0){
      $(b).css({'opacity':'0','visibility':'hidden'})
    } else{
      $(b).css({'opacity':'0.5','visibility':'visible'})
    }
  }
  if(a == 8){
    if(Number(tmpFirst.text()) < 9){
      tmpFirst.text(Number(tmpFirst.text()) + 1)
    }
    if(Number(tmpFirst.text()) != 0){
      $(tmpParentBlock.find('.window-time-main-timer-input-elem-change-elem')[2]).css({'opacity':'0.5','visibility':'visible'})
    }
    if(Number(tmpFirst.text()) == 9){
      $(b).css({'opacity':'0','visibility':'hidden'})
    } else{
      $(b).css({'opacity':'0.5','visibility':'visible'})
    }
  }
  if(a == 9){
    if(Number(tmpSecond.text()) < 9){
      tmpSecond.text(Number(tmpSecond.text()) + 1)
    }
    if(Number(tmpSecond.text()) != 0){
      $(tmpParentBlock.find('.window-time-main-timer-input-elem-change-elem')[3]).css({'opacity':'0.5','visibility':'visible'})
    }
    if(Number(tmpSecond.text()) == 9){
      $(b).css({'opacity':'0','visibility':'hidden'})
    } else{
      $(b).css({'opacity':'0.5','visibility':'visible'})
    }
  }
  if(a == 10){
    if(Number(tmpFirst.text()) >= 1){
      tmpFirst.text(Number(tmpFirst.text()) - 1)
    }
    if(Number(tmpFirst.text()) != 9){
      $(tmpParentBlock.find('.window-time-main-timer-input-elem-change-elem')[0]).css({'opacity':'0.5','visibility':'visible'})
    }
    if(Number(tmpFirst.text()) == 0){
      $(b).css({'opacity':'0','visibility':'hidden'})
    } else{
      $(b).css({'opacity':'0.5','visibility':'visible'})
    }
  }
  if(a == 11){
    if(Number(tmpSecond.text()) >= 1){
      tmpSecond.text(Number(tmpSecond.text()) - 1)
    }
    if(Number(tmpSecond.text()) != 9){
      $(tmpParentBlock.find('.window-time-main-timer-input-elem-change-elem')[1]).css({'opacity':'0.5','visibility':'visible'})
    }
    if(Number(tmpSecond.text()) == 0){
      $(b).css({'opacity':'0','visibility':'hidden'})
    } else{
      $(b).css({'opacity':'0.5','visibility':'visible'})
    }
  }
}

var tmpArrayInputs = [];

function time_timer_save(){
  var tmpInputs = $('.window-time-main-timer').find('.window-time-main-timer-input-elem-block-elem');

  var tmpString = '';

  for(let i = 0; i < tmpInputs.length; i += 2){
    tmpString = $(tmpInputs[i]).text() + $(tmpInputs[i + 1]).text()
    tmpArrayInputs.push(tmpString)
  }
  return tmpArrayInputs;
}
var timerTime;
var timer;
var timerM;
var timerH;


function time_timer_play(b){
  var tmpSave = time_timer_save();

  $(b).parent().find('.window-time-main-stopwatch-btn-elem').css('display','none')
  setTimeout(function(){
    $($(b).parent().find('.window-time-main-stopwatch-btn-elem')[0]).css('display','inline-block')
  },1)

  if(tmpSave[0] >= 0 && tmpSave[0] <= 99 && tmpSave[1] >= 0 && tmpSave[1] <= 99 && tmpSave[2] >= 0 && tmpSave[2] <= 99){
    $('.window-time-main-timer-input-elem-change-elem').css({'opacity':'0','visibility':'hidden'})

    timerS = tmpArrayInputs[2];
    timerM = tmpArrayInputs[1];
    timerH = tmpArrayInputs[0];

    timerTime = setInterval(time_timer, 1000);
  }


}

function time_timer(){
  if(timerS == 0){
    if(timerM == 0){
      if(timerH == 0){
        time_timer_stop(true);
        return;
      } else{
        timerH--;
        timerM = 59;
        timerS = 59;
      }
    } else{
      timerM--;
      timerS = 59;
    }
  }else{
    timerS--;
  }

  time_timer_output(Number(timerH), Number(timerM), Number(timerS));

}

function time_timer_output(timerH, timerM, timerS){
  var thisTimerH = String(timerH)
  var thisTimerM = String(timerM)
  var thisTimerS = String(timerS)

  if(thisTimerH < 10){
    thisTimerH = '0' + thisTimerH;
  }
  if(thisTimerM < 10){
    thisTimerM = '0' + thisTimerM;
  }
  if(thisTimerS < 10){
    thisTimerS = '0' + thisTimerS;
  }

  $($('.window-time-main-timer-input-elem-block-elem')[0]).text(thisTimerH[0])
  $($('.window-time-main-timer-input-elem-block-elem')[1]).text(thisTimerH[1])
  $($('.window-time-main-timer-input-elem-block-elem')[2]).text(thisTimerM[0])
  $($('.window-time-main-timer-input-elem-block-elem')[3]).text(thisTimerM[1])
  $($('.window-time-main-timer-input-elem-block-elem')[4]).text(thisTimerS[0])
  $($('.window-time-main-timer-input-elem-block-elem')[5]).text(thisTimerS[1])
}

function time_timer_stop(a){
  if(a){
    var timeAudio = new Audio();
    timeAudio.src = 'media/audio/09260.mp3';
    timeAudio.autoplay = true;
  }
  $($('.window-time-main-timer-input-elem-change-elem')[0]).css({'opacity':'0.5','visibility':'visible'});
  $($('.window-time-main-timer-input-elem-change-elem')[1]).css({'opacity':'0.5','visibility':'visible'});
  $($('.window-time-main-timer-input-elem-change-elem')[4]).css({'opacity':'0.5','visibility':'visible'});
  $($('.window-time-main-timer-input-elem-change-elem')[5]).css({'opacity':'0.5','visibility':'visible'});
  $($('.window-time-main-timer-input-elem-change-elem')[7]).css({'opacity':'0.5','visibility':'visible'});
  $($('.window-time-main-timer-input-elem-change-elem')[8]).css({'opacity':'0.5','visibility':'visible'});
  $($('.window-time-main-timer-input-elem-change-elem')[9]).css({'opacity':'0.5','visibility':'visible'});
  $($('.window-time-main-timer-input-elem-block-elem')[0]).text('0');
  $($('.window-time-main-timer-input-elem-block-elem')[1]).text('0');
  $($('.window-time-main-timer-input-elem-block-elem')[2]).text('0');
  $($('.window-time-main-timer-input-elem-block-elem')[3]).text('1');
  $($('.window-time-main-timer-input-elem-block-elem')[4]).text('0');
  $($('.window-time-main-timer-input-elem-block-elem')[5]).text('0');
  tmpArrayInputs = [];
  clearInterval(timerTime)
  $('#timerBTN').find('.window-time-main-stopwatch-btn-elem').css('display','none')
  setTimeout(function(){
    $($('#timerBTN').find('.window-time-main-stopwatch-btn-elem')[1]).css('display','inline-block')
  },1)
}

var time_status_full = false;

function time_elem(a,b,c){
  var tmpCountBlock = $(a).find('.window-time-main-nav-elem')

  for(let i = 0; i < tmpCountBlock.length; i++){
    $(tmpCountBlock[i]).css('color','var(--color)')
  }

  $(c).css('color','#5d78ff')

  if(b == 'time'){
    $(a).find('.window-time-main-text').css('margin-left','0%')
  }
  if(b == 'stopwatch'){
    $(a).find('.window-time-main-text').css('margin-left','-100%')
  }
  if(b == 'timer'){
    $(a).find('.window-time-main-text').css('margin-left','-200%')
  }
}

function time_full(a){
  var tmpBlockTime = $($(a).find(".window-time-main-nav-elem")[0])

  if(time_status_full){
    // no full
    time_status_full = false;
    time_elem('#time-main','time',tmpBlockTime)

    $(a).find('.window-time-main-nav').css({'height':'0px','opacity':'0','overflow':'hidden'})
    $(a).find('.console-head-btn-full-full2').css({'height':'30%','width':'30%'})
    $(a).find('.console-head-btn-full-full').css({'left':'-4px','right':'0','top':'4px'})
    $(a).css({'height':'120px'})
    $(a).find('.window-time-main-text-analog').css({'height':'100%','width':'89px','display':'inline-block','margin-top':'0px'})
    $(a).find('.window-time-main-text-analog-time').css({'transform':'scale(1)'})
    $(a).find('.window-time-main-text-text').css({'height':'100%','width':'calc(100% - 89px)','margin-top':'0px','text-align':'left'})
    $(a).find('.window-time-main-text-analog-stopwatch').css({'visibility':'hidden','opacity':'0','transform':'scale(1)'})


  } else{
    // full
    time_status_full = true;
    time_elem('#time-main','time',tmpBlockTime)

    $(a).find('.window-time-main-nav').css({'height':'40px','opacity':'1','overflow':'visible'})
    $(a).find('.console-head-btn-full-full2').css({'height':'0px','width':'0px'})
    $(a).find('.console-head-btn-full-full').css({'left':'0px','right':'0px','top':'0px'})
    $(a).css({'height':'450px'})
    $(a).find('.window-time-main-text-analog').css({'height':'195px','width':'100%','display':'block','margin-top':'14px'})
    $(a).find('.window-time-main-text-analog-time').css({'transform':'scale(2.3)'})
    $(a).find('.window-time-main-text-text').css({'height':'120px','width':'calc(100% - 0px)','margin-top':'14px','text-align':'center'})
    $(a).find('.window-time-main-text-analog-stopwatch').css({'visibility':'visible','opacity':'1','transform':'scale(2.3)'})


  }

}

var timerId;
var time_stopwatch_play_i = 0;
var stopwatch1 = 0;
var stopwatch2 = 0;
var stopStatus = 0;

function time_stopwatch(action, a, b){
  var tmpBlock = $(a);
  var tmpElemBtnBlock = $(b).parent().find('.window-time-main-stopwatch-btn-elem');
  for(let i = 0; i < tmpElemBtnBlock.length; i++){
    $(tmpElemBtnBlock[i]).css('display','none')
  }
  if(action == 'play'){

    setTimeout(function(){
      $(tmpElemBtnBlock[0]).css('display','inline-block')
      $(tmpElemBtnBlock[2]).css('display','inline-block')
    }, 1)
    StartStop();
  }
  if(action == 'stop'){
    if(stopStatus == 0){

      setTimeout(function(){
        $(tmpElemBtnBlock[0]).css('display','inline-block')
      }, 1)
      StartStop();
      stopStatus = 1;
    }
    else if(stopStatus == 1){

      setTimeout(function(){
        $(tmpElemBtnBlock[1]).css('display','inline-block')
      }, 1)
      ClearСlock();
      stopStatus = 0;
    }
  }
  if(action == 'cutoff'){
    $(tmpElemBtnBlock[0]).css('display','inline-block')
    $(tmpElemBtnBlock[2]).css('display','inline-block')
    time_stopwatch_flag();
  }
}
//объявляем переменные
var base = 60;
var clocktimer, dateObj, dh, dm, ds, ms;
var readout = '';
var h = 1,
  m = 1,
  tm = 1,
  s = 0,
  ts = 0,
  ms = 0,
  init = 0;

function time_stopwatch_flag(){
  var tmpIdBlock = stringGenerator(15,5);
  var tmpCountElem = $('.window-time-main-stopwatch-point').find('.window-time-main-stopwatch-point-elem').length + 1;

  m1 = m;
  s1 = s;
  ms1 = ms;

  if(m1 < 10){
    m1 = Number('0' + m1) - 1;
  } else{
    m1 = m1 - 1;
  }
  if(s1 >= 60){
    s1 = s1%60;
  }
  if(s1 < 10){
    s1 = '0' + s1;
  }

  $('.window-time-main-stopwatch-point').append("<div class='window-time-main-stopwatch-point-elem' id='" + tmpIdBlock + "'><div class='window-time-main-stopwatch-point-elem-count'>" + tmpCountElem + "</div><div class='window-time-main-stopwatch-point-elem-time'>" + m1 + ":" + s1 + "." + ms1 + "</div></div>")
  setTimeout(function(){
    $('.window-time-main-stopwatch-point').scrollTop($('.window-time-main-stopwatch-point').prop('scrollHeight'));
    $('#' + tmpIdBlock).css('opacity','1')
  }, 10)
}
//функция для очистки поля
function ClearСlock() {
  clearTimeout(clocktimer);
  $('.window-time-main-stopwatch-point').text(' ')
  var tmpBlockElem = $('.window-time-main-text-analog-stopwatch-points-elem')
  h = 1;
  m = 1;
  tm = 1;
  s = 0;
  ts = 0;
  ms = 0;
  init = 0;
  readout = '00:00.00';
  $('.window-time-main-text-analog-stopwatch-analog-line').css('transform','translate(-50%, 0px) rotate(0deg)')
  $('.window-time-main-text-analog-stopwatch-text').html("<span class='window-time-main-text-analog-stopwatch-text-0' style='margin-left: -3px; width: 22px;'>00</span><span>:</span><span class='window-time-main-text-analog-stopwatch-text-0' style='width: 22px;'>00</span><span>.</span><span class='window-time-main-text-analog-stopwatch-text-0' style='width: 22px;'>00</span>");
  for(let iij = 0; iij <= tmpBlockElem.length; iij++){
    $($('.window-time-main-text-analog-stopwatch-points-elem')[iij]).css({'background-color':'var(--color)','opacity':'0.5'})
  }
}

//функция для старта секундомера
function StartTIME() {
  var cdateObj = new Date();
  var t = (cdateObj.getTime() - dateObj.getTime()) - (s * 1000);
  if (t > 999) {
    s++;
  }
  if (s >= (m * base)) {
    ts = 0;
    m++;
  } else {
    ts = parseInt((ms / 100) + s);
    if (ts >= base) {
      ts = ts - ((m - 1) * base);
    }
  }
  if (m > (h * base)) {
    tm = 1;
    h++;
  } else {
    tm = parseInt((ms / 100) + m);
    if (tm >= base) {
      tm = tm - ((h - 1) * base);
    }
  }
  ms = Math.round(t / 10);
  if (ms > 99) {
    ms = 0;
  }
  if (ms == 0) {
    ms = '00';
  }
  if (ms > 0 && ms <= 9) {
    ms = '0' + ms;
  }
  if (ts > 0) {
    ds = ts;
    if (ts < 10) {
      ds = '0' + ts;
    }
  } else {
    ds = '00';
  }
  dm = tm - 1;
  if (dm > 0) {
    statusStopwatch = true;
    if (dm < 10) {
      dm = '0' + dm;
    }
  } else {
    statusStopwatch = true;
    dm = '00';
  }
  dh = h - 1;
  if (dh > 0) {
    if (dh < 10) {
      dh = '0' + dh;
    }
  } else {
    dh = '00';
  }
  if(dm == 59 && ds == 59 && ms == 99){
    StartStop();
    stopStatus = 1;
    return;
  }
  if(statusStopwatch){
    statusStopwatch = false;
    $('.window-time-main-text-analog-stopwatch-points-elem').css({'background-color':'var(--color)','opacity':'0.5'})
  }
  for(let iij = 0; iij <= ds; iij++){
    $($('.window-time-main-text-analog-stopwatch-points-elem')[iij]).css({'background-color':'#5d78ff','opacity':'1'})
  }
  $('.window-time-main-text-analog-stopwatch-analog-line').css('transform','translate(-50%, 0px) rotate(' + 3.6 * Number(ms) + 'deg)')
  $('.window-time-main-text-analog-stopwatch-text').html("<span class='window-time-main-text-analog-stopwatch-text-0' style='margin-left: -3px;'>" + dm + "</span><span>:</span><span class='window-time-main-text-analog-stopwatch-text-0'>" + ds + "</span><span>.</span><span class='window-time-main-text-analog-stopwatch-text-0'>" + ms + "</span>  ");
  clocktimer = setTimeout("StartTIME()", 1);
}
var statusStopwatch = false;
//Функция запуска и остановки
function StartStop() {
  if (init == 0) {
    ClearСlock();
    dateObj = new Date();
    StartTIME();
    init = 1;
  } else {
    clearTimeout(clocktimer);
    init = 0;
  }
}

$(document).ready(function(){

  var blockStopwatchCountPoints = $('.window-time-main-text-analog-stopwatch-points');
  for(let i = 0; i < blockStopwatchCountPoints.length; i++){
    for(let j = 0; j < 360; j += 6){
      blockStopwatchCountPoints.append("<div class='window-time-main-text-analog-stopwatch-points-elem' style='transform: rotate(" + j + "deg);'></div>")
    }
  }

  movingWindow('time-header','time-main');

  $('html').keydown(function(eventObject){
    if (event.altKey && event.keyCode == 84) { //если нажали Alt + C
      open_time('#time-main')
    }
  });

});
