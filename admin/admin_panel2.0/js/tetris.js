var tetris_status = false;
var infoStat = 7;
var infoStat2 = 1;


function tetris_close(a){
  tetris_status = false;
  infoStat2 = 0;
  tetrisSound.pause();
  $(a).css('opacity','0')
  setTimeout(function(){
    $(a).css('display','none')
  }, 150)
}
function open_tetris(a){
  if(tetris_status){
    tetris_close('#time-main');
  } else{
    tetris_status = true;
    infoStat2 = 1;
    $(a).css('display','block')
    setTimeout(function(){
      $(a).css('opacity','1')
    }, 1)
  }
}

var tetrisSound = new Audio();

function tetris_sound(){
  if(infoStat2 == 1){
    var rnd = randomInteger(1,4);

    tetrisSound.src = 'media/audio/tetris_sound/' + rnd + '.mp3';
    tetrisSound.volume = 0.2;
    tetrisSound.autoplay = true;
    tetrisSound.poster = "media/img/poster.png";
    tetrisSound.preload = true;
  }
}

var gameOver = false;

function tetris_restart(){
  gameOver = false;
  infoStat2 = 1;

  var tmpStack = $('#stack').find('.on');
  for(let i = 0; i < tmpStack.length; i++){
    $(tmpStack[i]).removeAttr('class');
    $(tmpStack[i]).attr('class','brick');
  }

  $('.window-time-main-finish-text').css('opacity','0')
  $('.window-time-main-finish-text').css('transform','translate(0px, 15px)')
  $('.window-time-main-finish').css('opacity','0');
  setTimeout(function(){
    $('.window-time-main-finish').css('display','none');
    $('.window-time-main-finish-text').css('display','none')
  }, 250)

  now = '';
  f = '';
  $('#result').text('0')

  tetris();

}

function tetris(){
  let fs = "1111:01|01|01|01*011|110:010|011|001*110|011:001|011|010*111|010:01|11|01:010|111:10|11|10*11|11*010|010|011:111|100:11|01|01:001|111*01|01|11:100|111:11|10|10:111|001", now = [3,0], pos = [4,0];

  let gP = function(x,y) { return document.querySelector('[data-y="'+y+'"] [data-x="'+x+'"]'); };

  try {var draw = function(ch, cls) {

        try {var f = fs.split('*')[now[0]].split(':')[now[1]].split('|').map(function(a){return a.split('')});} catch (err) {}
        try {for(let y=0; y<f.length; y++){
          for(let x=0; x<f[y].length; x++){
            if(f[y][x]=='1') {
                if(x+pos[0]+ch[0]>9||x+pos[0]+ch[0]<0||y+pos[1]+ch[1]>19||gP(x+pos[0]+ch[0],y+pos[1]+ch[1]).classList.contains('on')) return false;
                gP(x+pos[0]+ch[0], y+pos[1]+ch[1]).classList.add(cls!==undefined?cls:'now');
            }
          }
        }} catch (err) {}

        pos = [pos[0]+ch[0], pos[1]+ch[1]];
    }} catch (err) {}

  if($.cookie('sound_noti') == 'true'){
    tetris_sound();
    setInterval(tetris_sound, 180000)
  }

  let deDraw = function(){ if(document.querySelectorAll('.now').length>0) deDraw(document.querySelector('.now').classList.remove('now')); }
  let countCheck = Number($('#result').text());
  let check = function(){
    for(let i=0; i<20; i++){
      if(document.querySelectorAll('[data-y="'+i+'"] .brick.on').length == 10){
        if($.cookie('sound_noti') == 'true'){
          let tetrisDel = new Audio();
          tetrisDel.volume = 0.25;
          tetrisDel.src = 'media/audio/tetrisDel.mp3';
          tetrisDel.autoplay = true;
          tetrisDel.poster = "media/img/poster.png";
        }
        return check(roll(i), document.querySelector('#result').innerHTML=Math.floor(document.querySelector('#result').innerHTML) + 10);
      }
    }
    if(Number(document.querySelector('#result').innerHTML) - countCheck == 40){
      document.querySelector('#result').innerHTML = Math.floor(document.querySelector('#result').innerHTML) + 20;
      if($.cookie('sound_noti') == 'true'){
        let tetrisDel = new Audio();
        tetrisDel.volume = 0.25;
        tetrisDel.src = 'media/audio/tetrisDelX4.mp3';
        tetrisDel.autoplay = true;
        tetrisDel.poster = "media/img/poster.png";
      }
    }
  };
  let roll = function(ln){ if(false !== (document.querySelector('[data-y="'+ln+'"]').innerHTML = document.querySelector('[data-y="'+(ln-1)+'"]').innerHTML) && ln>1) roll(ln-1); };
  window.addEventListener('keydown', kdf = function(e){

    try {if(e.keyCode==38&&false!==(now[1]=((prv=now[1])+1)%fs.split('*')[now[0]].split(':').length) && false===draw([0,0], undefined, deDraw())) draw([0,0],undefined, deDraw(), now=[now[0],prv]);} catch (err) { }
      if((e.keyCode==39||e.keyCode==37)&&false===draw([e.keyCode==39?1:-1,0],undefined,deDraw())) draw([0,0],undefined,deDraw());
      if(e.keyCode == 40)
          if(false === draw([0,1], undefined, deDraw())) {
              if(draw([0,0], 'on', deDraw())||true) check();
              if(false === draw([0,0], undefined, now = [Math.floor(Math.random()*fs.split('*').length),0], pos = [4,0])) {
          toV=-1;
          let result = document.querySelector('#result').innerHTML;


          let topResult = $.cookie('tetris');
          tetrisSound.pause();
          if(Number(topResult) < Number(result)){
            $.cookie('tetris', result, {expires: 99999});
            topResult = result;
            $('.window-time-main-finish-text-recordCount').text(' ')
            $('.window-time-main-finish-text-record').text('Новый рекорд!')
            if($.cookie('sound_noti') == 'true' && infoStat2 == 1 && !gameOver){
              let tetrisWin = new Audio();
              tetrisWin.volume = 0.25;
              tetrisWin.src = 'media/audio/tetrisWin.mp3';
              tetrisWin.autoplay = true;
              tetrisWin.poster = "media/img/poster.png";
            }
          } else{
            $('.window-time-main-finish-text-recordCount').text(topResult)
            $('.window-time-main-finish-text-record').text('Рекорд')
            if($.cookie('sound_noti') == 'true' && infoStat2 == 1 && !gameOver){
              let tetrisWin = new Audio();
              tetrisWin.volume = 0.25;
              tetrisWin.src = 'media/audio/tetrisGameOver.mp3';
              tetrisWin.autoplay = true;
              tetrisWin.poster = "media/img/poster.png";
            }
          }
          $('#result2').text(result)

          $('.window-time-main-finish').css('display','block');
          if(!gameOver){
            gameOver = true;
          }
          setTimeout(function(){
            $('.window-time-main-finish').css('opacity','1');
            setTimeout(function(){
              let canvas = document.getElementById('window-time-main-finish');
              canvas.confetti = canvas.confetti || confetti.create(canvas, { resize: true });

              if(e.keyCode != 40 || infoStat2 == 1 && (topResult == result) && result != '0'){
                for(let i = 0; i < infoStat; i++){
                    canvas.confetti({
                      patricleCount: 650,
                      spread: 40,
                      origin: { y: 1.2 },
                      decay: 0.92
                    });
                  if(infoStat == i){
                    infoStat = 1;
                  }
                }
              }
              infoStat2 = 0;
            }, 550)
            setTimeout(function(){
              $('.window-time-main-finish-text').css('display','block')
              setTimeout(function(){
                $('.window-time-main-finish-text').css('opacity','1')
                $('.window-time-main-finish-text').css('transform','translate(0px, 0px)')
                now = 0;
              }, 1)
            }, 300)
          },250)

        }
          }
  });
  toF = function() {
      kdf({keyCode:40});
      setTimeout(function(){if(toV>=0)toF();}, toV=toV>0?toV-0.5:toV);
  }
  toF(toV = 500);
}

$(document).ready(function(){


  movingWindow('tetris-header','tetris-main');

  $('html').keydown(function(eventObject){
    if (event.altKey && event.keyCode == 49) { //если нажали Alt + 1

      if(!tetris_status){
        open_tetris('#tetris-main');
        tetris();
      } else{
        tetris_close('#tetris-main');
      }

    }
  });


});
