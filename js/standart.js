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

var loginRegex = /^([A-z0-9]){4,32}$/;
var passwordRegex = /^([a-zA-Z0-9-.,_!$#а-яА-ЯёЁ]){8,64}$/u;
var nameRegex = /^([A-Za-zА-ЯЁа-яё]){2,32}$/u;
var emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var phoneRegex = /^([0-9]){11}$/g;

// =========================== Variables (end)=============================== //

$(document).ready(function(){
  deviceDefinition();
  $(window).resize(function(){
    deviceDefinition();
  });
  $(window).scroll(function(){
    deviceDefinition();
  });
  setTimeout(function() {
    Login.login.keypress(function(event) {
      if(event.keyCode == 13) {
        if(Login.password.val().length == 0){
          Login.password.focus();
        } else{
          login();
        }
      }
    });
    Login.password.keypress(function(event) {
      if(event.keyCode == 13) {
        if(Login.login.val().length == 0){
          Login.login.focus();
        } else{
          login();
        }
      }
    });
  }, 1000);
});

function login() {
  // check fields
  var errc = 0;
  // login
  if(!Login.login.val().match(loginRegex)){
    Login.login.siblings('.input-line').css({
      'background-color':'var(--red)'
    });
    setTimeout(function(){
      Login.login.siblings('.input-line').css({
        'background-color':'#d4d4d4'
      });
    }, 3000);
    notification_add('Error', 'Ошибка', 'Логин указан некорректно!');
    errc++;
  }
  // password
  if(!Login.password.val().match(passwordRegex)){
    Login.password.siblings('.input-line').css({
      'background-color':'var(--red)'
    });
    setTimeout(function(){
      Login.password.siblings('.input-line').css({
        'background-color':'#d4d4d4'
      });
    }, 3000);
    notification_add('Error', 'Ошибка', 'Пароль указан некорректно!');
    errc++;
  }
  if(errc > 0) return;
  // request
  loaderLogin('show');
  // login function
  function sendLoginForm() {
    $.ajax({
      type: 'POST',
      url: 'php/db_auth.php',
      data: {
        login_form: true,
        captcha3_token: theCaptchaToken,
        login: Login.login.val(),
        password: Login.password.val()
      },
      beforeSend: function() {
        loaderLogin('show');
      },
      complete: function() {
        theCaptchaToken = undefined;
        setTimeout(function() {
          loaderLogin('hidden');
        }, 1000);
      },
      success: function(response) {
        function checkResponseCode(code, rsp) {
          if(typeof(rsp) == 'undefined') rsp = response;
          return (response.substring(0, code.length) == code);
        }
        // good
        if(checkResponseCode('OK.')) {
          if(response.substring(3, 4) == 'REF.') {
            var ref = response.substring(7, response.length);
            if(ref == 'none') ref = 'index';
            location.reload(ref);
          }
          else {
            location.reload(true);
          }
        }
        else if(checkResponseCode('WRONG.')) {
          notification_add('error', 'Ошибка', 'Проверьте правильность заполенных полей');
        }
        else if(checkResponseCode('ERROR.')) {
          notification_add('error', 'Ошибка', 'Неизвестная ошибка');
        }
        else if(checkResponseCode('NOT_FOUND.')) {
          notification_add('error', 'Ошибка', 'Пользователь с указанным именем не найден');
        }
        else if(checkResponseCode('WRONG_PASSWORD.')) {
          notification_add('error', 'Ошибка', 'Неверный пароль');
        }
        else if(checkResponseCode('BANNED.')) {
          notification_add('error', 'Нет доступа', 'Обнаружена подозрительная активность');
        }
        else if(checkResponseCode('LOGIN.')) {
          notification_add('error', 'Ошибка', 'Логин указан некорректно!');
        }
        else if(checkResponseCode('PASSWORD.')) {
          notification_add('error', 'Ошибка', 'Пароль указан некорректно!');
        }
        else if(checkResponseCode('CAPTCHA.')) {
          location.reload(true);
        }
        else {
          notification_add('error', 'Ошибка', 'Неизвестная ошибка');
          console.error('error: ' + response);
        }
      },
      error: function(jqXHR, status) {
        notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
        console.error('error: ' + status + ', ' + jqXHR);
      }
    });
  }
  // get token
  if(typeof(theCaptchaToken) == 'undefined') {
    if(typeof(grecaptcha) == 'undefined') {
      loaderLogin('hidden');
      console.error('grecaptcha undefined');
      return;
    }
    grecaptcha.execute('6LdKcfQUAAAAAMtb7qMKV1fs2rAIzLEeJp5UdFX9', {action: 'login'}).then(function(token) {
       theCaptchaToken = token;
       sendLoginForm();
    });
  }
  else {
    sendLoginForm();
  }
}

function logout(token) {
  $.ajax({
    type: 'POST',
    url: 'php/db_auth.php',
    data: {
      log_out: token
    },
    success: function(response) {
      location.reload(true);
      /*if(response == 'OK.' || response == 'RELOAD.') {

      }
      else {
        notification_add('error', 'Ошибка', 'Неизвестная ошибка');
        console.error('error: ' + response);
      }*/
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка', 'Нет соединения с сервером');
      console.error('error: ' + status + ', ' + jqXHR);
    }
  });
}

function loaderRegister(action){
  if(action === undefined){
    if($('.register-block').find('.window-container-preloader').css('visibility') == 'visible'){
      return true;
    } else{
      return false;
    }
  } else{
    if(action.match(/^(show|visible|visual)$/ui)){
      $('.register-block').find('.window-container-preloader').css({
        'height':'100%',
        'width':'100%',
        'top':'0px',
        'margin':'auto',
        'visibility':'visible',
        'background-color':'#3696db2b'
      });
      $('.register-block').find('.window-btn').css({
        'opacity':'0'
      })
      $('.register-block').find('.register-preloader').css({
        'opacity':'1',
        'visibility':'visible',
        'bottom':'0',
        'transform':'translate(0px, 0%)'
      })
      $('.register-block').find('.register-preloader > div').css({
        'display':'block'
      })
      $('.register-block').find('.login-preloader-elem4').css({
        'visibility':'visible',
        'width':'100%'
      })
    }

    if(action.match(/^(hidden|invisible|stealthy)$/ui)){
      $('.register-block').find('.window-container-preloader').css({
        'height':'41px',
        'width':'268px',
        'top':'506px',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto',
        'visibility':'hidden',
        'background-color':'#2a9fd0'
      })
      setTimeout(function(){
        $('.register-block').find('.window-btn').css({
          'opacity':'1'
        })
      }, 250)

      $('.register-block').find('.register-preloader').css({
        'opacity':'1',
        'visibility':'visible',
        'bottom':'0',
        'transform':'translate(100%, 0px)'
      })
      setTimeout(function(){
        $('.register-block').find('.register-preloader').css({
          'transform':'translate(-100%, 0px)'
        })
        $('.register-block').find('.register-preloader > div').css({
          'display':'none'
        })
        $('.register-block').find('.login-preloader-elem4').css({
          'visibility':'hidden',
          'width':'0%'
        })
      }, 250)

    }
  }
}

function loaderLogin(action){
  if(action === undefined){
    if($('#login').find('.window-container-preloader2').css('visibility') == 'visible'){
      return true;
    } else{
      return false;
    }
  } else{
    if(action.match(/^(show|visible|visual)$/ui)){
      $('#login').find('.window-container-preloader2').css({
        'height':'100%',
        'width':'100%',
        'top':'0px',
        'margin':'auto',
        'visibility':'visible',
        'background-color':'#3696db2b'
      })
      $('#login').find('.window-btn').css({
        'opacity':'0'
      })
      $('#login').find('.login-preloader').css({
        'opacity':'1',
        'visibility':'visible',
        'bottom':'0',
        'transform':'translate(0px, 0%)'
      })
      $('#login').find('.login-preloader > div').css({
        'display':'block'
      })
      $('#login').find('.login-preloader-elem4').css({
        'visibility':'visible',
        'width':'100%'
      })
    }

    if(action.match(/^(hidden|invisible|stealthy)$/ui)){
      $('#login').find('.window-container-preloader2').css({
        'height':'41px',
        'width':'160px',
        'top':'300px',
        'left':'0',
        'right':'0',
        'bottom':'0',
        'margin':'auto',
        'visibility':'hidden',
        'background-color':'#2a9fd0'
      })
      setTimeout(function(){
        $('#login').find('.window-btn').css({
          'opacity':'1'
        })
      }, 250)
      $('#login').find('.login-preloader').css({
        'opacity':'1',
        'visibility':'visible',
        'bottom':'0',
        'transform':'translate(100%, 0px)'
      })
      setTimeout(function(){
        $('#login').find('.login-preloader').css({
          'transform':'translate(-100%, 0px)'
        })
        $('#login').find('.login-preloader > div').css({
          'display':'none'
        })
        $('#login').find('.login-preloader-elem4').css({
          'visibility':'hidden',
          'width':'0%'
        })
      }, 250)

    }
  }
}

function loaderTest(action){
  if(action === undefined){
    if($('#control-test').find('.window-container-preloader2').css('visibility') == 'visible'){
      return true;
    } else{
      return false;
    }
  } else{
    if(action.match(/^(show|visible|visual)$/ui)){
      $('#control-test').find('.window-container-preloader2').css({
        'height':'100%',
        'width':'100%',
        // 'top':'0px',
        'left':'0px',
        'bottom':'0px',
        'margin':'auto',
        'visibility':'visible',
        'background-color':'#3696db2b'
      })
      $('#control-test').find('.window-btn').css({
        'opacity':'0'
      })
      $('#control-test').find('.test-preloader').css({
        'opacity':'1',
        'visibility':'visible',
        'bottom':'0',
        'transform':'translate(0px, 0%)'
      })
      $('#control-test').find('.test-preloader > div').css({
        'display':'block'
      })
      $('#control-test').find('.test-preloader-elem4').css({
        'visibility':'visible',
        'width':'100%'
      })
    }

    if(action.match(/^(hidden|invisible|stealthy)$/ui)){
      $('#control-test').find('.window-container-preloader2').css({
        'height': $('#control-test').find('.window-btn').outerHeight() + 'px',
        'width':$('#control-test').find('.window-btn').outerWidth() + 'px',
        'top':'initial',
        'left':'25px',
        'right':'initial',
        'bottom':'25px',
        'margin':'auto',
        'visibility':'hidden',
        'background-color':'#2a9fd0'
      })
      setTimeout(function(){
        $('#control-test').find('.window-btn').css({
          'opacity':'1'
        })
      }, 250)
      $('#control-test').find('.test-preloader').css({
        'opacity':'1',
        'visibility':'visible',
        'bottom':'0',
        'transform':'translate(100%, 0px)'
      })
      setTimeout(function(){
        $('#control-test').find('.test-preloader').css({
          'transform':'translate(-100%, 0px)'
        })
        $('#control-test').find('.test-preloader > div').css({
          'display':'none'
        })
        $('#control-test').find('.login-preloader-elem4').css({
          'visibility':'hidden',
          'width':'0%'
        })
      }, 250)

    }
  }
}

function windowClose(a, shadow){
  if(shadow !== undefined && shadow){
    var aL = $('window > div')
    var i = 0;
    while(i < aL.length){

      if($($('window > div')[i]).attr('class') != 'window-shadow'){
        if($($('window > div')[i]).attr('class') == 'menu-mobile'){
          $($('window > div')[i]).css({
            'opacity':'0',
            'transform':'scale(1.2)',
          })
        } else{
          if(device == 'phone'){
            $($('window > div')[i]).css({
              'opacity':'0',
              'transform':'translate(-50%, 0%) scale(1.2)',
              'border-radius': 'var(--border-rWindow)'
            })
          } else{
            $($('window > div')[i]).css({
              'opacity':'0',
              'transform':'translate(-50%, -50%) scale(1.2)',
              'border-radius': 'var(--border-rWindow)'
            })
          }
        }



        setTimeout(function(){
          $($('window > div')[i]).css({
            'display':'none'
          })
        }, 250)

      }

      i++;
    }

    if(i == aL.length){
      $('window').css({
        'opacity':'0'
      })
      setTimeout(function(){
        $('window').css({
          'display':'none'
        })
      }, 250)
    }
  } else{
    if(device == 'phone'){
      $(a).parent().css({
        'opacity':'0',
        'transform':'translate(-50%, 0%) scale(1.2)',
        'border-radius': 'var(--border-rWindow)'
      });
    } else{
      $(a).parent().css({
        'opacity':'0',
        'transform':'translate(-50%, -50%) scale(1.2)',
        'border-radius': 'var(--border-rWindow)'
      });
    }

    setTimeout(function(){
      $(a).parent().css('display','none');
    }, 250)
    $(a).parent().parent().css('opacity','0');
    setTimeout(function(){
      $(a).parent().parent().css('display','none');
    }, 250)
  }
  $('#menu-mobile').css({
    'opacity':'0',
  })
  $('#menu-mobile > .menu-mobile-block').css({
    'transform':'scale(1.2)'
  })
  setTimeout(function(){
    $('#menu-mobile').css({
      'display':'none'
    });
  }, 350)
}

function windowOpen(a){

  var secLocal = 50;

  $('#menu-mobile').css({
    'opacity':'0',
  })
  $('#menu-mobile > .menu-mobile-block').css({
    'transform':'scale(1.2)'
  })
  setTimeout(function(){
    $('#menu-mobile').css({
      'display':'none'
    });
  }, 350)
  if($('window').css('display') == 'none'){
    $('window').css('display','block')
    $('window').find('.window-shadow').css('display','block')
    setTimeout(function(){
      $('window').find('.window-shadow').css('opacity','1')
    }, 1)
  }

  for(let i = 0; i < $('.window > div').length; i++){
    if($($('window > div')[i]).attr('class') != 'window-shadow'){
      if($($('window > div')[i]).attr('class') == 'menu-mobile'){
        $($('window > div')[i]).css({
          'opacity':'0',
          'transform':'scale(1.2)',
        })
        setTimeout(function(){
          $($('window > div')[i]).css('display','none')
        }, 150)
      } else{
        if($($('window > div')[i]).css('display') == 'block'){
          if(device == 'phone'){
            $($('window > div')[i]).css({
              'opacity':'0',
              'transform':'translate(-50%, 0%) scale(1.2)',
              'border-radius': 'var(--border-rWindow)'
            })
            setTimeout(function(){
              $($('window > div')[i]).css('display','none')
            }, 150)
          } else{
            $($('window > div')[i]).css({
              'opacity':'0',
              'transform':'translate(-50%, -50%) scale(1.2)',
              'border-radius': 'var(--border-rWindow)'
            })
            setTimeout(function(){
              $($('window > div')[i]).css('display','none')
            }, 150)
          }
          secLocal += 150;
        }
      }

    }
  }


  setTimeout(function(){
    $(a).css('display','block')
    setTimeout(function(){
      $('window').css('opacity','1')
      if(device == 'phone'){
        $(a).css({
          'opacity':'1',
          'transform':'translate(-50%, 0%) scale(1)',
          'border-radius': 'var(--border-rWindow)'
        })
      } else{
        $(a).css({
          'opacity':'1',
          'transform':'translate(-50%, -50%) scale(1)',
          'border-radius': 'var(--border-rWindow)'
        })
      }
    },10)
  }, secLocal)

}

function deviceDefinition(){let tmpClienWidth = document.documentElement.clientWidth;if(tmpClienWidth <= 623){device = 'phone';} else if(tmpClienWidth > 640 && tmpClienWidth <= 974){device = 'tablet';} else{device = 'pc';}}

function loaderMain(action){
  if(action === undefined){
    if($('.loader-nav-main').css('display') == 'block'){
      return true;
    } else{
      return false;
    }
  } else{
    if(action === 'show'){
      $('nav').css({
        'top':'4px',
        'transition':'0.35s all'
      });
      $('.loader-nav-main').css({
        'display':'block'
      });
      $('.loader-nav').css({
        'z-index':'1000',
      });
      setTimeout(function(){
        $('.loader-nav-main').css({
          'visibility':'visible',
          'z-index':'1000',
          'opacity':'1',
          'transform':'translate(0%, 0px)'
        });
      }, 1)
    }
    if(action === 'hidden'){
      $('nav').css({
        'top':'0px'
      });
      setTimeout(function(){
        $('nav').css({
          'transition':'0s all'
        });
      }, 380)
      $('.loader-nav-main').css({
        'transform':'translate(190vw, 0px)'
      });
      setTimeout(function(){
        $('.loader-nav-main').css({
          'visibility':'hidden',
          'opacity':'0',
          'display':'none',
          'z-index':'1',
          'transform':'translate(-100%, 0px)'
        });
        $('.loader-nav').css({
          'z-index':'1',
        });
      }, 350)
    }
  }
}

function notification_add(type, title, text, timer, src){

  var tmpType = /^(default|line|stop|error|warning|info|text|question)$/ui,
      notificationsBlock = $('notifications'),
      idNotification = idGenerator(),
      tmpNitiString = '';
  if(type === undefined){
    return console.error('Не определен тип уведомления!')
  }
  if(timer === undefined){
    timer = closeNotificatTime;
  }
  if(title === undefined){
    title = '';
  }
  if(text === undefined){
    text = '';
  }

  if(type.match(/^(default)$/ui) && src === undefined){
    return console.error('Не возможно вызвать стандартное уведомление без иконки!');
  }

  if(type.match(tmpType)){

    // уведомление стандартное
    if(type.match(/^(default)$/ui)){


      tmpNitiString += '<div class="notification" style="transform: translate(150%, 0px); max-height: 0px; padding: 0px 10px 0px 15px;" id="' + idNotification + '">';
      tmpNitiString += '<div class="notification-exit" title="Закрыть уведомление" onclick="notification_close(this)">';
      tmpNitiString += '<div class="notification-exit-line1"></div>';
      tmpNitiString += '<div class="notification-exit-line2"></div>';
      tmpNitiString += '</div>';
      tmpNitiString += '<div class="notification-ico" style="background-image: url(' + src + ')"></div>';
      tmpNitiString += '<div class="notification-text">';
      tmpNitiString += '<div class="notification-text-title">' + title + '</div>';
      tmpNitiString += '<div class="notification-text-main">' + text + '</div>';
      tmpNitiString += '</div>';
      tmpNitiString += '</div>';

    }

    // уведомление строкой
    else if(type.match(/^(line)$/ui)){

      tmpNitiString += '<div class="notification" style="transform: translate(150%, 0px); max-height: 0px; padding: 0px 10px 0px 15px;" id="' + idNotification + '" style="padding: 10px 10px 10px 15px">';
      tmpNitiString += '<div class="notification-exit-full" title="Закрыть уведомление" onclick="notification_close(this)">';
      tmpNitiString += '<div class="notification-exit-line1"></div>';
      tmpNitiString += '<div class="notification-exit-line2"></div>';
      tmpNitiString += '</div>';
      tmpNitiString += '<div class="notification-text" style="width: calc(100% - 20px);">';
      tmpNitiString += '<div class="notification-text-title">' + text + '</div>';
      tmpNitiString += '</div>';
      tmpNitiString += '</div>';

    }

    // уведомление стоп
    else if(type.match(/^(stop)$/ui)){

      tmpNitiString = '';
      tmpNitiString += '<div class="notification" style="transform: translate(150%, 0px); max-height: 0px; padding: 0px 10px 0px 15px;" id="' + idNotification + '">';
      tmpNitiString += '<div class="notification-exit" title="Закрыть уведомление" onclick="notification_close(this)">';
      tmpNitiString += '<div class="notification-exit-line1"></div>';
      tmpNitiString += '<div class="notification-exit-line2"></div>';
      tmpNitiString += '</div>';
      tmpNitiString += '<div class="notification-ico-f icons-stop-bolt" style="color: var(--orangeDark);">';
      tmpNitiString += '<div class="notification-ico-f-helper"></div>';
      tmpNitiString += '</div>';
      tmpNitiString += '<div class="notification-text">';
      tmpNitiString += '<div class="notification-text-title">' + title + '</div>';
      tmpNitiString += '<div class="notification-text-main">' + text + '</div>';
      tmpNitiString += '</div>';
      tmpNitiString += '</div>';


    }

    // уведомление об ошибке
    else if(type.match(/^(error)$/ui)){

      tmpNitiString = '';
      tmpNitiString += '<div class="notification" style="transform: translate(150%, 0px); max-height: 0px; padding: 0px 10px 0px 15px;" id="' + idNotification + '">';
      tmpNitiString += '<div class="notification-exit" title="Закрыть уведомление" onclick="notification_close(this)">';
      tmpNitiString += '<div class="notification-exit-line1"></div>';
      tmpNitiString += '<div class="notification-exit-line2"></div>';
      tmpNitiString += '</div>';
      tmpNitiString += '<div class="notification-ico-f icons-error-bolt">';
      tmpNitiString += '<div class="notification-ico-f-helper"></div>';
      tmpNitiString += '</div>';
      tmpNitiString += '<div class="notification-text">';
      tmpNitiString += '<div class="notification-text-title">' + title + '</div>';
      tmpNitiString += '<div class="notification-text-main">' + text + '</div>';
      tmpNitiString += '</div>';
      tmpNitiString += '</div>';


    }

    // уведомление с предупреждением
    else if(type.match(/^(warning)$/ui)){

      tmpNitiString = '';
      tmpNitiString += '<div class="notification" style="transform: translate(150%, 0px); max-height: 0px; padding: 0px 10px 0px 15px;" id="' + idNotification + '">';
      tmpNitiString += '<div class="notification-exit" title="Закрыть уведомление" onclick="notification_close(this)">';
      tmpNitiString += '<div class="notification-exit-line1"></div>';
      tmpNitiString += '<div class="notification-exit-line2"></div>';
      tmpNitiString += '</div>';
      tmpNitiString += '<div class="notification-ico-f icons-warning-bolt" style="color: var(--orange);">';
      tmpNitiString += '<div class="notification-ico-f-helper"></div>';
      tmpNitiString += '</div>';
      tmpNitiString += '<div class="notification-text">';
      tmpNitiString += '<div class="notification-text-title">' + title + '</div>';
      tmpNitiString += '<div class="notification-text-main">' + text + '</div>';
      tmpNitiString += '</div>';
      tmpNitiString += '</div>';

    }

    // уведомление с информацией
    else if(type.match(/^(info|question)$/ui)){

      tmpNitiString = '';
      tmpNitiString += '<div class="notification" style="transform: translate(150%, 0px); max-height: 0px; padding: 0px 10px 0px 15px;" id="' + idNotification + '">';
      tmpNitiString += '<div class="notification-exit" title="Закрыть уведомление" onclick="notification_close(this)">';
      tmpNitiString += '<div class="notification-exit-line1"></div>';
      tmpNitiString += '<div class="notification-exit-line2"></div>';
      tmpNitiString += '</div>';
      tmpNitiString += '<div class="notification-ico-f icons-info-bolt" style="color: var(--blue);">';
      tmpNitiString += '<div class="notification-ico-f-helper"></div>';
      tmpNitiString += '</div>';
      tmpNitiString += '<div class="notification-text">';
      tmpNitiString += '<div class="notification-text-title">' + title + '</div>';
      tmpNitiString += '<div class="notification-text-main">' + text + '</div>';
      tmpNitiString += '</div>';
      tmpNitiString += '</div>';

    }

    // уведомление с текстом
    else if(type.match(/^(text)$/ui)){

      tmpNitiString = '';
      tmpNitiString += '<div class="notification" style="transform: translate(150%, 0px); max-height: 0px; padding: 0px 10px 0px 15px;" id="' + idNotification + '">';
      tmpNitiString += '<div class="notification-exit" title="Закрыть уведомление" onclick="notification_close(this)">';
      tmpNitiString += '<div class="notification-exit-line1"></div>';
      tmpNitiString += '<div class="notification-exit-line2"></div>';
      tmpNitiString += '</div>';
      tmpNitiString += '<div class="notification-text" style="width: calc(100% - 20px);">';
      tmpNitiString += '<div class="notification-text-title">' + title + '</div>';
      tmpNitiString += '<div class="notification-text-main">' + text + '</div>';
      tmpNitiString += '</div>';
      tmpNitiString += '</div>';

    }

    // ошибка в типе
    else{
      return console.error('Тип уведомления определен не верно!')
    }

    if(document.documentElement.clientWidth <= 991){
      notificationsBlock.prepend(tmpNitiString)
    } else{
      notificationsBlock.append(tmpNitiString)
    }


    if(!type.match(/^(line)$/ui)){
      setTimeout(function(){
        $('#' + idNotification).css({
            'max-height':'250px',
            'padding': '10px 10px 15px 15px',
            'transform':'translate(0%, 0px)'
         })}, 20)
    } else{
      setTimeout(function(){
        $('#' + idNotification).css({
            'max-height':'250px',
            'padding': '10px 10px 10px 15px',
            'transform':'translate(0%, 0px)'
         })}, 20)
    }


  } else{
    // ошибка в типе
    return console.error('Тип уведомления определен не верно!')
  }

  if(timer > 0){
    setTimeout(function(){
      notification_close('#' + idNotification);
    }, timer * 1000)

  }

}

function notification_close(block){
  if(String(block).match(/^#+/ui)){
    $(block).css({
        'max-height':'0px',
        'margin-top':'0px',
        'margin-bottom':'0px',
        'padding': '0px 10px 0px 15px',
        'transform':'translate(150%, 0px)'
     })
     setTimeout(function(){
       $(block).remove()
     }, 450)
  } else{
    $(block).parent().css({
        'max-height':'0px',
        'margin-top':'0px',
        'margin-bottom':'0px',
        'padding': '0px 10px 0px 15px',
        'transform':'translate(150%, 0px)'
     })
     setTimeout(function(){
       $(block).parent().remove()
     }, 450)
  }

}

function idGenerator(count, where){
  let alphabet = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789',
      string = '';

  if(where === undefined){
    where = 5;
  }

  if(count === undefined){
    count = 20;
  }

  if(where > 0){
    for(let i = 0; i < count; i++){
      if(i % where == 0 && i != count && i != 0){
        string += '-';
      } else{
        if(i == 0){
          string += 'd';
        } else{
          string += alphabet[Math.round(Math.random() * (alphabet.length - 1))];
        }
      }
    }
  } else{
    for(let i = 0; i < count; i++){
      if(i == 0){
        string += 'd';
      } else{
        string += alphabet[Math.round(Math.random() * (alphabet.length - 1))];
      }
    }
  }
  if(where < 0){
    console.warn('Предупреждение: переменную where не рукомендуется писать меньше нуля')
  }
  return string;
}

var thisSrollTop;

function sliderNoFull(a){
  var tmpSlider = $(a).parent().parent();
  $(a).removeAttr('class');
  $(a).removeAttr('title');
  $(a).removeAttr('onclick');
  $(a).attr('class','article-slider-main-full-ico icons-full');
  $(a).attr('title','На весь экран');
  $(a).attr('onclick','sliderFull(this)');
  $('body').css({
    'height':'auto',
    'overflow':'auto'
  });
  $(tmpSlider).css({
    'position': 'relative',
    'background-size':'contain',
    'background-color':'#00000042',
    'z-index': '1',
    'left': 'initial',
    'top': 'initial',
    'height':'100%',
    'width': '100%'
  });
  $(window).scrollTop(thisSrollTop);
}

function sliderFull(a){
  thisSrollTop = $(window).scrollTop();
  var tmpSlider = $(a).parent().parent();
  $(a).removeAttr('class');
  $(a).removeAttr('title');
  $(a).removeAttr('onclick');
  $(a).attr('class','article-slider-main-full-ico icons-nofull');
  $(a).attr('title','Свернуть');
  $(a).attr('onclick','sliderNoFull(this)');
  $('body').css({
    'height':'100vh',
    'overflow':'hidden'
  });
  $(tmpSlider).css({
    'position': 'fixed',
    'background-size':'contain',
    'background-color':'#000000a1',
    'z-index': '999999999999999',
    'left': '0',
    'top': '0',
    'height':'100vh',
    'width': '100vw'
  });
  $('body').scrollTop(thisSrollTop);
}

// склонения по числительным
function declOfNumber(number, decl) {
  var strnum = String(number);
  var number1 = Number(strnum.substring(strnum.length - 1, strnum.length));
  var number2 = Number(strnum.substring(strnum.length - 2, strnum.length));
  if((number2 >= 10 && number2 <= 20) || (number1 >= 5 && number1 <= 9) || number1 == 0) {
    // род. п.
    if(decl == 'год') return 'лет';
    if(decl == 'месяц') return 'месяцев';
    if(decl == 'день') return 'дней';
    if(decl == 'элемент') return 'элементов';
    if(decl == 'просмотр') return 'просмотров';
    if(decl == 'балл') return 'баллов';
    if(decl == 'человек') return 'человек';
  }
  else if(number1 == 1) {
    // им. п.
    return decl;
  }
  else if(number1 >= 2 && number1 <= 4) {
    // дат. п.
    if(decl == 'год') return 'года';
    if(decl == 'месяц') return 'месяца';
    if(decl == 'день') return 'дня';
    if(decl == 'элемент') return 'элемента';
    if(decl == 'просмотр') return 'просмотра';
    if(decl == 'балл') return 'балла';
    if(decl == 'человек') return 'человека';
  }
  else {
    return undefined;
  }
}

//сократить число
function shortNum(num, mode) {
  var mode;
  if(typeof(mode) == 'undefined') mode = 0;
  var word = '';
  var map = [
    ['', 'ТЫС.', 'МЛН.', 'МЛРД.', 'ТРЛН.', 'КВАДРЛН.', 'КВИНТЛН.'],
    ['', 'тысяча', 'миллион', 'миллиард', 'триллион', 'квадриллион', 'квинтиллион']
  ];
  var i = 0;
  while(num > 999) {
    if(i > 6) break;
    num /= 1000;
    i++;
    word = map[mode][i];
  }
  //if(i > 0) num = num.toFixed(1);
  return [Math.floor(num), word];
}

// === profile settings ========================================================

// initialize
$(document).ready(function() {
  // fill init data
  ProfileForm.main.prop.name1 = userData['name1'];
  ProfileForm.main.prop.name2 = userData['name2'];
  ProfileForm.main.prop.name3 = userData['name3'];
  ProfileForm.main.prop.email = userData['email'];
  ProfileForm.main.prop.phone = userData['phone'];
  ProfileForm.main.prop.country = userData['country'];
  ProfileForm.main.prop.city = userData['city'];
  ProfileForm.main.prop.gender = (userData['gender'] == 'male') ? 'male' : 'female';
  // add listeners
  // main form listeners
  for(field in ProfileForm.main.field) {
    if(field == 'email') {
      $('#' + ProfileForm.main.field[field]).on('input', function() {
        $('#profile-form-save-btn-2').attr('class', 'window-btn');
        ProfileForm.main.ready = true;
        ProfileForm.code.verifyed = false;
      });
    }
    else if(field == 'gender') {
      $('#' + ProfileForm.main.field[field]).on('click', function() {
        $('#profile-form-save-btn-2').attr('class', 'window-btn');
        ProfileForm.main.ready = true;
      });
      $('#id-female-profile').on('click', function() {
        $('#profile-form-save-btn-2').attr('class', 'window-btn');
        ProfileForm.main.ready = true;
      });
    }
    else {
      $('#' + ProfileForm.main.field[field]).on('input', function() {
        $('#profile-form-save-btn-2').attr('class', 'window-btn');
        ProfileForm.main.ready = true;
      });
    }
  }
  // password change form listeners
  for(field in ProfileForm.password.field) {
    $('#' + ProfileForm.password.field[field]).on('input', function() {
      ProfileForm.password.check();
    });
  }
  // input type=file listener
  $('#' + ProfileForm.icon.field.input).on('change', function() {
    ProfileForm.icon.upload();
  });
});

var ProfileForm = {
  main: {
    ready: false,
    field: {
      name1: 'id-name-profile',
      name2: 'id-name1-profile',
      name3: 'id-name2-profile',
      email: 'id-email-profile',
      phone: 'id-tel-profile',
      country: 'id-country-profile',
      city: 'id-city-profile',
      gender: 'id-male-profile'
    },
    prop: {
      name1: undefined,
      name2: undefined,
      name3: undefined,
      email: undefined,
      phone: undefined,
      country: undefined,
      city: undefined,
      gender: undefined
    },
    open: function() {
      // recover fields
      for(field in ProfileForm.main.field) {
        let value = ProfileForm.main.prop[field];
        if(field == 'gender') {
          let gender = (value == 'male') ? true : false;
          $('#' + ProfileForm.main.field[field]).prop('checked', gender);
        }
        else {
          $('#' + ProfileForm.main.field[field]).val(value);
        }
      }
      ProfileForm.code.verifyed = true;
      // password-change fields
      for(field in ProfileForm.password.field) {
        $('#' + ProfileForm.password.field[field]).val('');
      }
      // button, window
      $('#profile-form-save-btn-2').attr('class', 'window-btn-noactive');
      ProfileForm.main.ready = false;
      windowOpen('#profile');
    },
    save: function() {
      if(ProfileForm.main.ready == false) return;
      // read
      var tmpArr = [];
      var errc = 0;
      for(field in ProfileForm.main.field) {
        let value;
        if(field == 'gender') {
          value = ($('#' + ProfileForm.main.field[field]).prop('checked') == true) ? 'male' : 'female';
        }
        else {
          value = $('#' + ProfileForm.main.field[field]).val();
        }
        // check names, city
        if(new Array('name1', 'name2', 'name3').indexOf(field) != -1) {
          if(!value.match(nameRegex)) {
            notification_add('error', 'Ошибка', 'ФИО указаны некорректно!');
            errc++;
          }
        }
        // city
        if(field == 'city') {
          if(!value.match(/^([a-zA-Z0-9-а-яА-ЯёЁ\s\-]){2,32}$/u)) {
            notification_add('error', 'Ошибка', 'Город указан некорректно!');
            errc++;
          }
        }
        // check email
        if(field == 'email') {
          if(!value.match(emailRegex)) {
            notification_add('error', 'Ошибка', 'Адрес эл. почты указан некорректно!');
            errc++;
          }
        }
        // check phone
        if(field == 'phone' && (value != '')) {
          if(!value.match(phoneRegex)) {
            notification_add('error', 'Ошибка', 'Номер телефона указан некорректно!');
            errc++;
          }
        }
        tmpArr[field] = value;
      }
      // stop
      if(errc > 0) return;
      // email verify code
      if((tmpArr['email'] != ProfileForm.main.prop.email) && (ProfileForm.code.verifyed == false)) {
        ProfileForm.code.open(tmpArr['email']);
        return;
      }
      // change icon
      ProfileForm.icon.gender(tmpArr['gender']);
      // save
      for(field in ProfileForm.main.field) {
        ProfileForm.main.prop[field] = tmpArr[field];
      }
      // send
      $.ajax({
        type: 'POST',
        url: 'php/db_auth.php',
        data: {
          profile_form: true,
          name1: ProfileForm.main.prop['name1'],
          name2: ProfileForm.main.prop['name2'],
          name3: ProfileForm.main.prop['name3'],
          phone: ProfileForm.main.prop['phone'],
          country: ProfileForm.main.prop['country'],
          city: ProfileForm.main.prop['city'],
          gender: ProfileForm.main.prop['gender']
        },
        complete: function(){
          loaderMain('hidden');
        },
        beforeSend: function(){
          loaderMain('show');
        },
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.')) {
            notification_add('line', '', 'Изменения сохранены');
            windowClose($('#profile .window-exit'));
          }
          else if(checkResponseCode('NAME1.')) {
            notification_add('error', 'Ошибка', 'Имя указано некорректно!');
          }
          else if(checkResponseCode('NAME2.')) {
            notification_add('error', 'Ошибка', 'Фамилия указана некорректно!');
          }
          else if(checkResponseCode('NAME3.')) {
            notification_add('error', 'Ошибка', 'Отчество указано некорректно!');
          }
          else if(checkResponseCode('EMAIL.')) {
            notification_add('error', 'Ошибка', 'Адрес эл. почты указан некорректно!');
          }
          else if(checkResponseCode('PHONE.')) {
            notification_add('error', 'Ошибка', 'Номер телефона указан некорректно!');
          }
          else if(checkResponseCode('BANNED.')) {
            notification_add('error', 'Нет доступа', 'Обнаружена подозрительная активность');
          }
          else if(checkResponseCode('ERROR.')) {
            notification_add('error', 'Ошибка', 'Неизвестная ошибка');
          }
          else if(checkResponseCode('AUTH.')) {
            document.location.reload(true);
          }
          else {
            notification_add('error', 'Ошибка', 'Неизвестная ошибка');
            console.log('error: ' + response);
          }
        },
        error: function(jqXHR, status) {
          notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
          console.log('error: ' + status + ', ' + jqXHR);
        }
      });
    }
  },
  code: {
    field: 'id-code-email-profile',
    verifyed: true,
    open: function(email) {
      $.ajax({
        type: 'POST',
        url: 'php/db_auth.php',
        data: {
          ce_code_form: true,
          email: email
        },
        complete: function(){
          loaderMain('hidden');
        },
        beforeSend: function(){
          loaderMain('show');
        },
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.')) {
            $('#' + ProfileForm.code.field).val('');
            windowOpen('#email-code');
            notification_add('line', '', 'Код отправлен на указанную почту');
          }
          else if(checkResponseCode('EMAIL_LIMIT.')) {
            notification_add('error', 'Ошибка', 'Указанный адрес эл. почты уже занят');
          }
          else if(checkResponseCode('BANNED.')) {
            notification_add('error', 'Нет доступа', 'Обнаружена подозрительная активность');
          }
          else if(checkResponseCode('ERROR.') || checkResponseCode('WRONG.')) {
            notification_add('error', 'Ошибка', 'Неизвестная ошибка');
          }
          else if(checkResponseCode('AUTH.')) {
            document.location.reload(true);
          }
          else {
            notification_add('error', 'Ошибка', 'Неизвестная ошибка');
            console.log('error: ' + response);
          }
        },
        error: function(jqXHR, status) {
          notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
          console.log('error: ' + status + ', ' + jqXHR);
        }
      });
    },
    send: function() {
      var code = $('#' + ProfileForm.code.field).val();
      if(code.length != 16) {
        notification_add('error', 'Ошибка', 'Код указан некорректно!');
        return;
      }
      // send code
      $.ajax({
        type: 'POST',
        url: 'php/db_auth.php',
        data: {
          change_email_form: true,
          code: code
        },
        complete: function(){
          loaderMain('hidden');
        },
        beforeSend: function(){
          loaderMain('show');
        },
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.')) {
            ProfileForm.code.verifyed = true;
            ProfileForm.main.save();
          }
          else if(checkResponseCode('WRONG.')) {
            notification_add('error', 'Ошибка', 'Неверный код!');
          }
          else if(checkResponseCode('ERROR.')) {
            notification_add('error', 'Ошибка', 'Неизвестная ошибка');
          }
          else if(checkResponseCode('BANNED.')) {
            notification_add('error', 'Нет доступа', 'Обнаружена подозрительная активность');
          }
          else if(checkResponseCode('AUTH.')) {
            document.location.reload(true);
          }
          else {
            notification_add('error', 'Ошибка', 'Неизвестная ошибка');
            console.log('error: ' + response);
          }
        },
        error: function(jqXHR, status) {
          notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
          console.log('error: ' + status + ', ' + jqXHR);
        }
      });
    }
  },
  password: {
    ready: false,
    field: {
      old: 'id-oldpass-profile',
      new1: 'id-newpass1-profile',
      new2: 'id-newpass2-profile'
    },
    check: function() {
      // read
      var tmpArr = [];
      var errc = 0;
      for(field in ProfileForm.password.field) {
        let value = $('#' + ProfileForm.password.field[field]).val();
        // check
        tmpArr[field] = value;
        if(!value.match(passwordRegex)) {
          errc++;
        }
      }
      // check
      if(tmpArr['new1'] != tmpArr['new2']) errc++;
      // stop
      if(errc > 0) {
        $('#profile-form-save-btn-1').attr('class', 'window-btn-noactive');
        ProfileForm.password.ready = false;
      }
      // ok
      else {
        $('#profile-form-save-btn-1').attr('class', 'window-btn');
        ProfileForm.password.ready = true;
      }
    },
    change: function() {
      if(!ProfileForm.password.ready) return;
      // read
      var passwordOld = $('#' + ProfileForm.password.field.old).val();
      var passwordNew = $('#' + ProfileForm.password.field.new1).val();
      // send
      $.ajax({
        type: 'POST',
        url: 'php/db_auth.php',
        data: {
          change_password_form: true,
          old_password: passwordOld,
          new_password: passwordNew
        },
        beforeSend: function() {
          ProfileForm.password.ready = false;
          loaderMain('show');
        },
        complete: function() {
          ProfileForm.password.ready = true;
          loaderMain('hidden');
        },
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.')) {
            notification_add('line', '', 'Пароль изменен');
            windowOpen('#profile');
          }
          else if(checkResponseCode('WRONG.')) {
            notification_add('error', 'Ошибка', 'Неверный пароль!');
          }
          else if(checkResponseCode('BANNED.')) {
            notification_add('error', 'Нет доступа', 'Обнаружена подозрительная активность');
          }
          else if(checkResponseCode('ERROR.')) {
            notification_add('error', 'Ошибка', 'Неизвестная ошибка');
          }
          else if(checkResponseCode('AUTH.')) {
            document.location.reload(true);
          }
          else {
            notification_add('error', 'Ошибка', 'Неизвестная ошибка');
            console.log('error: ' + response);
          }
        },
        error: function(jqXHR, status) {
          notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
          console.log('error: ' + status + ', ' + jqXHR);
        }
      });
    }
  },
  icon: {
    field: {
      input: 'id-image-profile',
      label: 'id-image-block',
      nav: [
        'nav-profile-icon-1',
        'nav-profile-icon-2'
      ]
    },
    clear: function(id) {
      if(typeof(id) == 'undefined') id = ProfileForm.icon.field.input;
      $('#' + id).val('');
    },
    gender: function(gender) {
      if(userData['profile_icon'].substring(userData['profile_icon'].lastIndexOf('/') + 1) == 'avatar.png') return;
      if(!(gender == 'male' || gender == 'female')) return;
      $('#' + ProfileForm.icon.field.label).css('background-image', 'none');
      for(let i = 0; i < 2; i++) {
        $('#' + ProfileForm.icon.field.nav[i]).css('background-image', 'none');
      }
      setTimeout(function() {
        let bkgi = 'url("media/svg/' + gender + '_avatar.svg")';
        userData['profile_icon'] = 'media/svg/' + gender + '_avatar.svg';
        $('#' + ProfileForm.icon.field.label).css('background-image', bkgi);
        for(let i = 0; i < 2; i++) {
          $('#' + ProfileForm.icon.field.nav[i]).css('background-image', bkgi);
        }
      }, 20);
    },
    upload: function(files) {
      if(typeof(files) == 'undefined') {
        files = document.getElementById(ProfileForm.icon.field.input).files;
      }
      if(typeof(files) == 'undefined' || files.length == 0) {
        return;
      }
      var data = new FormData();
      $.each(files, function(key, value) {
        data.append(key, value);
        return;
      });
      data.append('change_icon', 1);
      $.ajax({
        url: 'php/db_auth.php',
        type: 'POST',
        data: data,
        cache: false,
        processData: false,
        contentType: false,
        beforeSend: function(){
          loaderMain('show');
        },
        complete: function() {
          ProfileForm.icon.clear();
          loaderMain('hidden');
        },
        success: function(response) {
          ProfileForm.icon.clear();
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.', response)) {
            notification_add('line', '', 'Фотография профиля обновлена');
            $('#' + ProfileForm.icon.field.label).css('background-image', 'none');
            for(let i = 0; i < 2; i++) {
              $('#' + ProfileForm.icon.field.nav[i]).css('background-image', 'none');
            }
            setTimeout(function() {
              let bkgi = 'url("users/public/' + userData['account'] + '/avatar.png")';
              userData['profile_icon'] = 'users/public/' + userData['account'] + '/avatar.png';
              $('#' + ProfileForm.icon.field.label).css('background-image', bkgi);
              for(let i = 0; i < 2; i++) {
                $('#' + ProfileForm.icon.field.nav[i]).css('background-image', bkgi);
              }
            }, 20);
          }
          else if(response == 'AUTH.') {
            document.location.reload(true);
          }
          else if(checkResponseCode('LIMIT.', response)) {
            notification_add('error', 'Ошибка', 'Размер файла не должен превышать 10 МБ');
          }
          else if(checkResponseCode('MIME.', response)) {
            notification_add('error', 'Ошибка', 'Недопустимый формат');
          }
          else {
            notification_add('error', 'Ошибка сервера', 'Неизвестая ошибка');
            console.log('error: ' + response);
          }
        },
        error: function(jqXHR, status) {
          notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
          console.log('error: ' + status + ', ' + jqXHR);
        }
      });
    }
  }
};

/*function escapeHtml(text) {
  return text;
  //return text.replace(/[\"&<>]/g, function(a) { return { '"': '&quot;', '&': '&amp;', '<': '&lt;', '>': '&gt;' }[a]; });
}*/

/*function decodeHTML(str) {
  var codes = [['&amp;', '&'], ['&lt;', '<'], ['&gt;', '>'], ['&sol;', '/'], ['&nbsp;', ' ']];
  for(var i = 0; i < codes.length; i++) {
    str =str.replace(new RegExp(codes[i][0], 'g'), codes[i][1]);
  }
  return str;
}*/

function getCurrentDateTimeMySql() {
  var tzoffset = (new Date()).getTimezoneOffset() * 60000;
  var localISOTime = (new Date(Date.now() - tzoffset)).toISOString().slice(0, 19).replace('T', ' ');
  var mySqlDT = localISOTime;
  return mySqlDT;
}

function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function objClone(obj) {
  if(null == obj || "object" != typeof obj) return obj;
  var copy = obj.constructor();
  for(var attr in obj) {
    if(obj.hasOwnProperty(attr)) copy[attr] = obj[attr];
  }
  return copy;
}
