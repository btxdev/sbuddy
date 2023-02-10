/*
 *  Study Buddy
 *  (c) 2020 Study Buddy
 *  All rights reserved.
 *
 *  Developed by INSOweb
 *  <http://insoweb.ru>
 *
 */

// ========================== Variables (start)============================== //

var timeoutId, idLogoHat, idLogoStudy, idLogoBuddy, idLogoOwl, idLogoHelp,
    arraySmallElem = [],
    arrayBigElem = [];

// =========================== Variables (end)=============================== //


$(document).ready(function(){

  // =============================== LOGO (start) ============================//

  idLogoHat    =  $('#URjd5-RF2K-RdBS');
  idLogoStudy  =  $('#aYmkz-DAML-Vzkr');
  idLogoBuddy  =  $('#iyNna-OcPS-KtHf');
  idLogoOwl    =  $('#AnrGT-PEYV-ZOn5');
  idLogoHelp   =  $('#Z6AtY-thec-Qsia > rect');


  $('.nav-logo').mouseover(function(){
    if($(window).scrollTop() > 200){
      idLogoHat.css('transform','translate(-87px, -255px) scale(1.6) rotate(-3deg)')
    }
  });

  $('.nav-logo').mouseleave(function(){
    if($(window).scrollTop() > 200){
      idLogoHat.css('transform','translate(-58px, -253px) scale(1.6) rotate(0deg)')
    }
  });

  // =============================== LOGO (end) ==============================//



  if(currentFileName.match(/^(news|news.php)$/ui)){
    $(window).scroll(function(){
      if(device == 'pc'){
        let y = $(window).scrollTop() - $(".filter-news").parent().offset().top;
        if(y > -100){
          $(".filter-news").css({'margin-top': y + 100 + 'px'})
        } else{
          $(".filter-news").css({'margin-top': '0px'})
        }
      } else{
        $(".filter-news").css({'margin-top': '0px'})
      }
    })
  }

  if(currentFileName.match(/^(register|register.php|recovery|recovery.php)$/ui)){

    registerDesign();

    $(window).resize(registerDesign)

    function registerDesign(){
      if(device == 'phone'){
        $('.register-block').css({
          'width':'100vw',
          'border':'none',
          'box-shadow':'none',
        })
        $('.register').css({
          'background-color':'#fff',
          'height':'auto'
        });
      } else{
        $('.register-block').css({
          'width':'350px',
          'border':'1px solid #e8e8e8',
          'box-shadow':'0px 0px 40px 0 rgba(189, 189, 189, 0.16)',
        })
        $('.register').css({
          'background-color':'#e6e8fc6b',
          'height':'100vh'
        });
      }
    }

  }

  if(!currentFileName.match(/^(index|index.php)$/ui)){
    generateSmallElem();
    generateBigElem();

    $(window).resize(function(){
      if(device == 'pc'){
        generateBigElem();
      } else{
        for(let i = 0; i < arrayBigElem.length; i++){
          let tmpId = arrayBigElem[i][3];
          $('#' + tmpId).remove();
        }
        arrayBigElem = [];
      }
    })

    $(window).scroll(function(){

      for(let i = 0; i < arraySmallElem.length; i++){
        if(arraySmallElem[i][0].match(/^(back-triangle.svg|back-triangle2.svg)$/ui)){
          $('#' + arraySmallElem[i][3]).css({top: ($(window).scrollTop()*arraySmallElem[i][5]) + arraySmallElem[i][2], transform: 'rotate('+$(window).scrollTop() / arraySmallElem[i][4]+'deg)'})
        } else{
          $('#' + arraySmallElem[i][3]).css({top: ($(window).scrollTop()*arraySmallElem[i][5]) + arraySmallElem[i][2]})
        }
      }

      for(let i = 0; i < arrayBigElem.length; i++){
        if(arrayBigElem[i][0].match(/^(back-triangle.svg|back-triangle2.svg)$/ui)){
          $('#' + arrayBigElem[i][3]).css({top: (-$(window).scrollTop()*arrayBigElem[i][5]) + arrayBigElem[i][2], transform: 'rotate('+$(window).scrollTop() / arrayBigElem[i][4]+'deg)'})
        } else{
          $('#' + arrayBigElem[i][3]).css({top: (-$(window).scrollTop()*arrayBigElem[i][5]) + arrayBigElem[i][2]})
        }
      }

    });

  }

  if(currentFileName.match(/^(index|index.php)$/ui)){
    $(window).resize(function(){
      var clientWidth = document.documentElement.clientWidth;
      // if(clientWidth > 1250){
      //   $('.back-h1').css({
      //     'font-size':'60px'
      //   })
      // }
      // if(clientWidth <= 1250 && clientWidth >= 1200){
      //   $('.back-h1').css({
      //     'font-size':'52px'
      //   })
      // }
      // if(clientWidth <= 1199){
      //   $('.back').css({
      //     'transform':'scale(0.8)'
      //   })
      //   $('.back-h1').css({
      //     'font-size':'39px'
      //   })
      //   $('.back-h1').parent().css({
      //     'margin-top':'-161px',
      //     'text-align':'center'
      //   })
      //   $('.Acquaintance-title-text').css({
      //     'font-size':'39px'
      //   })
      //   $('.row').css({
      //     'margin-right':'0px',
      //     'margin-left':'0px'
      //   })
      // }
    });
  }

  if(currentFileName.match(/^(chat|chat.php|drive|drive.php)$/ui)){
    $($('.background-main').children()[0]).css('display','none')
  }

  $(window).scroll(function(){

    if(currentFileName.match(/^(index|index.php)$/ui)){

      if(document.documentElement.clientWidth >= 1200){
        if(timeoutId ){
          clearTimeout(timeoutId );
        }

        timeoutId = setTimeout(function(){
         $('.btn2-main').css({'transition':'0.25s all ease-in-out'})
        }, 100);

        $('.back1').css({top: -$(window).scrollTop()*0.24})
        $('.back2').css({top: -$(window).scrollTop()*0.2 + 130})
        $('.back3').css({top: -$(window).scrollTop()*0.5 + 210})
        $('.back-plus').css({top: -$(window).scrollTop()*0.11 + 180})
        $('.back-h1').css({top: -$(window).scrollTop()*0.19})
        $('.back-plus1').css({bottom: $(window).scrollTop()*1.02 - 163})
        $('.back-circle').css({top: -$(window).scrollTop()*1.04 + 33})
        $('.back-triangle').css({top: -$(window).scrollTop()* - 0.35 - 200, transform: 'rotate('+$(window).scrollTop()*0.11+'deg)'})
        $('.back-text').css({'margin-top': -$(window).scrollTop()*0.5 + 80 + 'px'})
        $('.back-circle-msg').css({top: -$(window).scrollTop()* - 0.35 - 1200, transform: 'rotate('+$(window).scrollTop()*0.11+'deg)'})
        $('.back-triangle222').css({top: -$(window).scrollTop()* - 0.35 - 950, transform: 'rotate('+$(window).scrollTop()*0.11+'deg)'})
        $('.back-circle222').css({top: -$(window).scrollTop()*  0.35 + 950})
        $('.back-plus3').css({top: -$(window).scrollTop()*  0.3 + 550})
        $('.back-triangle2').css({top: -$(window).scrollTop()*  - 0.35 - 700, transform: 'rotate('+$(window).scrollTop()*0.11+'deg) scale(0.7)'})
        $('.back-triangle22').css({top: -$(window).scrollTop()*  0.3 + 300})
        $('.back-circle22').css({top: -$(window).scrollTop()*  -0.3 - 300})
        $('.back-plus-msg').css({top: -$(window).scrollTop()*  0.3 + 1000})
        $('.btn2-main').css({'transition':'0s all ease-in-out', 'margin-top': -$(window).scrollTop()*0.25 + 10 + 'px'})

      }
      else{
        $('.back1').css({top: 0 * 0.24})
        $('.back2').css({top: 0 * 0.2 + 130})
        $('.back3').css({top: 0 * 0.5 + 280})
        $('.back-plus').css({top: 0 * 0.11 - 105})
        $('.back-h1').css({top: 0 * 0.19})
        $('.back-plus1').css({bottom:0 * 1.02 - 163})
        $('.back-circle').css({top: 0 * 1.04 + 33})
        $('.back-triangle').css({top: 0 *  - 0.35 - 200, transform: 'rotate('+ 0*0.11+'deg)'})
        $('.back-text').css({'margin-top': 0 * 0.5 + 80 + 'px'})
        $('.back-circle-msg').css({top: 0 *  - 0.35 - 1200, transform: 'rotate('+ 0*0.11+'deg)'})
        $('.back-triangle222').css({top: 0 *  - 0.35 - 950, transform: 'rotate('+ 0*0.11+'deg)'})
        $('.back-circle222').css({top: 0 *   0.35 + 950})
        $('.back-plus3').css({top: 0 *   0.3 + 550})
        $('.back-triangle2').css({top: 0 *   - 0.35 - 700, transform: 'rotate('+ 0*0.11+'deg) scale(0.7)'})
        $('.back-triangle22').css({top: 0 *   0.3 + 300})
        $('.back-circle22').css({top: 0 *   -0.3 - 300})
        $('.back-plus-msg').css({top: 0 *   0.3 + 1000})
        $('.btn2-main').css({'transition':'0s all ease-in-out', 'margin-top': + 0*0.25 + 10 + 'px'})

      }

    }

    if($(this).scrollTop() <= 350){
      var sumScroll = 100 / 350 * $(this).scrollTop();
      var sizeNav = 75 + ((55 - 75) * (sumScroll / 100));
      var sizeBtn = 17 + ((8 - 17) * (sumScroll / 100));
      var sizeLogo = 60 + ((41 - 60) * (sumScroll / 100));
      $('.nav-menu').css('margin-top', sizeBtn + 'px')
      $('nav').css('height', sizeNav + 'px')
      $('.nav-logo').css('height', sizeLogo + 'px')
    } else{
      $('.nav-menu').css('margin-top', 8 + 'px')
      $('nav').css('height', 55 + 'px')
      $('.nav-logo').css('height', 41 + 'px')
    }

    if($(this).scrollTop() <= 25){
      $('.scrollElem').css({'opacity':'1','visibility':'visible'})
    } else{
      $('.scrollElem').css({'opacity':'0','visibility':'hidden'})
    }

    if($(this).scrollTop() <= 200){
      if(device == 'pc'){
        idLogoHat.css({'opacity':'1','visibility':'visible','transform':'translate(0px, 0px)'})
        idLogoBuddy.css({'opacity':'1','visibility':'visible','transform':'translate(0px, 0px)'})
        idLogoStudy.css({'opacity':'1','visibility':'visible','transform':'translate(0px, 0px)'})
        idLogoOwl.css({'transform':'matrix(0.674697, 0.14505, -0.14505, 0.674697, 298.176, 11.1178)'})
        idLogoHelp.css('opacity','0')
      }
    } else{
      if(device == 'pc'){
        idLogoOwl.css({'transform':'rotate(0deg) translate(-451px, 113px)'})
        idLogoHat.css({'opacity':'1','visibility':'visible','transform':'translate(-58px, -253px) scale(1.6)'})
        idLogoStudy.css({'opacity':'0','visibility':'hidden','transform':'translate(-100px, 0px)'})
        idLogoBuddy.css({'opacity':'0','visibility':'hidden','transform':'translate(100px, 0px)'})
        setTimeout(function(){
          idLogoHelp.css('opacity','1')
        }, 120)
      }
    }

  });

  // ============================ WINDOWS (start) ============================//

  $(window).resize(windowDesign);

  windowDesign(device);

  function windowDesign(a){
    if(device == 'phone'){

      $('html').get(0).style.setProperty('--border-rWindow','15px');
      $('html').get(0).style.setProperty('--border-r','10px');
      $('html').get(0).style.setProperty('--border-rSmall','5px');
      $('#captcha-v2-div > div').css({
        'width':'100%'
      })
      $('footer2').css({
        'text-align':'center'
      })
      $($('footer2 > div > div')[0]).css({
        'text-align':'center'
      })
      $('.footer-description').css({
        'text-align':'center'
      })
      $($('.footer-menu-title').parent()[0]).css({
        'text-align':'left',
        'margin-top':'10px'
      })
      $('.background-main').children().first().css({
        'margin-top':'0px',
        'margin-bottom':'78px'
      });
      $('.background-main').children().first().find('.Acquaintance-h1').css({
        'font-size':'40px',
      });
      $('.background-main').children().first().find('.Acquaintance-h2').css({
        'font-size':'30px',
        'line-height':'30px',
        'font-weight':'700'
      });
      $('.news-filter-elem-photo').css({
        'display':'block',
        'width':'100%'
      });
      $('.news-filter-elem-text').css({
        'width':'calc(100% - 20px)'
      });
      $('.news-filter-elem-text-title').css({
        'max-height':'200px'
      });
      $('.news-filter-elem-text-btn').css({
        'position':'relative',
        'bottom':'initial',
        'text-align':'left',
      });
      $('.news-elem').css({
        'width':'100%'
      });
      $('.filter-news').css({
        'width':'100%'
      });
      $('.news-filter-elem').css({
        'width':'100%'
      });
      $('.pages-news').css({
        'width':'100%'
      });
      $('.window').css({
        'bottom':'25px',
        'transform':'translate(-50%, 0%) scale(1)',
        'width':'90vw',
        'position':'fixed'
      });
      $('.background-main').addClass('row')
    } else{
      $('html').get(0).style.setProperty('--border-rWindow','0px')
      $('html').get(0).style.setProperty('--border-r','0px')
      $('html').get(0).style.setProperty('--border-rSmall','0px')
      $('#captcha-v2-div > div').css({
        'width':'304px'
      })
      $('.footer-description').css({
        'text-align':'center'
      });
      $('footer2').css({
        'text-align':'right'
      })
      $($('footer2 > div > div')[0]).css({
        'text-align':'left'
      })
      $($('.footer-menu-title').parent()[0]).css({
        'text-align':'left',
        'margin-top':'0px'
      })
      if(!currentFileName.match(/^(index|index.php)$/ui)){
        $('.background-main').children().first().css({
        'margin-top':'100px',
        'margin-bottom':'100px'
      });
      }
      $('.background-main').children().first().find('.Acquaintance-h1').css({
        'font-size':'60px',
      })
      $('.background-main').children().first().find('.Acquaintance-h2').css({
        'font-size':'40px',
        'line-height':'65px',
        'font-weight':'500'
      });
      $('.news-filter-elem-photo').css({
        'display':'inline-block',
        'width':'180px'
      });
      $('.news-filter-elem-text').css({
        'width':'calc(100% - 200px)'
      });
      $('.news-filter-elem-text-title').css({
        'max-height':'56px'
      });
      $('.news-filter-elem-text-btn').css({
        'position':'absolute',
        'bottom':'15px',
        'text-align':'right',
      });

      $('.news-elem').css({
        'width':'90%'
      });
      $('.filter-news').css({
        'width':'90%'
      });
      $('.news-filter-elem').css({
        'width':'95.5%'
      });
      $('.pages-news').css({
        'width':'90%'
      });
      $('.window').css({
        'bottom':'initial',
        'transform':'translate(-50%, -50%) scale(1)',
        'width':'400px',
        'position':'absolute'
      });
      $('.background-main').removeClass('row')
    }
  }

  // ============================= WINDOWS (end) =============================//

});


function openBurgerMenu(block){
  if($(block).find('.burger-menu-block-line2').css('opacity') == '0'){
    // открываем

    $('#menu-mobile').css({
      // 'transform':'scale(1.2)',
      'opacity':'0',
      'position':'fixed'
    });
    $('#menu-mobile > .menu-mobile-block').css({
      'bottom':'25px',
      'transform':'scale(1.2)'
    })
    setTimeout(function(){
      $('#menu-mobile').css({
        'display':'none'
      });
    }, 350)
    $(block).find('.burger-menu-block-line2').css('opacity','0.75')
    $(block).find('.burger-menu-block-line1').css({
      'transform':'translate(0px, -5px) rotate(0deg)'
    })
    $(block).find('.burger-menu-block-line3').css({
      'transform':'translate(0px, 5px) rotate(0deg)'
    })

    $('nav').find('.menu-mobile-block-close-block-line2').css('opacity','0.75')
    $('nav').find('.menu-mobile-block-close-block-line1').css({
      'transform':'translate(0px, -5px) rotate(0deg)'
    })
    $('nav').find('.menu-mobile-block-close-block-line3').css({
      'transform':'translate(0px, 5px) rotate(0deg)'
    })
  } else{
    // закрываем
    $('#menu-mobile').css({
      'display':'block',
      'position':'fixed',
    });
    $('#menu-mobile > .menu-mobile-block').css({
      'bottom':'25px',
      'transform':'scale(1)'
    });
    setTimeout(function(){
      $('#menu-mobile').css({
        // 'transform':'scale(1)',
        'opacity':'1',
      });
    }, 1)
    $(block).find('.burger-menu-block-line2').css('opacity','0')
    $(block).find('.burger-menu-block-line1').css({
      'transform':'translate(0px, 0px) rotate(45deg)'
    })
    $(block).find('.burger-menu-block-line3').css({
      'transform':'translate(0px, 0px) rotate(-45deg)'
    })

    $('nav').find('.menu-mobile-block-close-block-line2').css('opacity','0')
    $('nav').find('.menu-mobile-block-close-block-line1').css({
      'transform':'translate(0px, 0px) rotate(45deg)'
    })
    $('nav').find('.menu-mobile-block-close-block-line3').css({
      'transform':'translate(0px, 0px) rotate(-45deg)'
    })
  }
}

function generateBigElem(){
  $('.container-BigElem-2').text('')
  var tmpHeightMainBlock = $('.background-main').height();
  var tmpWidthMainBlock = $('.background-main').width();
  var tmpWidthScreen = document.documentElement.clientWidth;
  var tmpArraySmallElem = ['back-triangle.svg', 'back-triangle2.svg', 'back-circle.svg', 'back-plus.svg']

  if(device != 'phone'){
    for(let i = 0; i < (tmpHeightMainBlock / 500); i++){
      let y = randomInteger(50, tmpHeightMainBlock - 50 + tmpHeightMainBlock, true);
      let x = randomInteger(55, (tmpWidthScreen - tmpWidthMainBlock) / 2 - 35, true);
      let id = idGenerator();
      let k = randomInteger(5, 8);
      let speed = randomInteger(0.9, 1.4, false);
      let type = tmpArraySmallElem[randomInteger(0, tmpArraySmallElem.length - 1, true)];
      let leftOrRight = Math.floor(randomInteger(0,1.5));
      let blur = 0;
      arrayBigElem.push([type, x, y, id, k, speed, blur]);

      if(leftOrRight == 1){
        leftOrRight = 'right:' + x + 'px;';
      } else{
        leftOrRight = 'left:' + x + 'px;';
      }

      $('.container-BigElem-2').append("<div id='" + id + "' class='background-BigElem' style='filter: blur(" + blur + "px); background-image: url(media/svg/" + type + "); top: " + y + "px; " + leftOrRight + "'></div>")

    }
  }

}

function generateSmallElem(){
  var tmpHeightMainBlock = $('.background-main').height();
  var tmpWidthMainBlock = $('.background-main').width();


  for(let i = 0; i < (tmpHeightMainBlock / 300); i++){
    var tmpArraySmallElem = ['back-triangle.svg', 'back-triangle2.svg', 'back-circle.svg', 'back-plus.svg']
    let x = randomInteger(20, tmpWidthMainBlock - 20, true);
    let y = randomInteger(50, tmpHeightMainBlock - 60, true);
    let id = idGenerator();
    let k = randomInteger(5, 8);
    let speed = randomInteger(0.3, 0.4, false);
    let type = tmpArraySmallElem[randomInteger(0, tmpArraySmallElem.length - 1, true)];

    arraySmallElem.push([type, x, y, id, k, speed])

    $('.background-main').append("<div id='" + id + "' class='background-smallElem' style='background-image: url(media/svg/" + type + "); top: " + y + "px; left: " + x + "px;'></div>")
  }
  console.log('Таблица сгенерированных small элементов')
  console.table(arraySmallElem)
}

function randomInteger(min, max, floor) {
  let rand = Math.random() * (max - min) + min;
  if(floor === undefined || floor == false){
    return rand;
  } else if(floor == true){
    return Math.floor(rand);
  }

}
