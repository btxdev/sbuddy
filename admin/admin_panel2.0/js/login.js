var HeightWindow = window.innerHeight,
    WidthtWindow = window.innerWidth,
    StatusMenu = false;

var currentWindowIs = 'login';

$(document).ready(function(){
  $('input[type="tel"]').mask("+7 (999) 99-99-999")
  $('input[type="key"]').mask("9-999-999")
  $('#login-form-btn').on('click', sendLoginForm)
  $('.main-first-btn-3').on('click', sendRegisterForm)
  $('#the_best_id_for_exit_button_lol').on('click', sendExitForm)
  $('#recovery-code-btn').on('click', sendRecoveryCodeForm)
  $('#recovery-code-back').on('click', function(){ if(passwordRecoveryStage == 1) { passwordRecoveryStage = 0; } if(passwordRecoveryStage == 2) { passwordRecoveryStage = 1; } })

  if(document.documentElement.clientWidth < 900){
    $('.main-second-info').css('display','block')
    $('.main-second-info-2').css('display','none')
    $('.menu').css({'display':'none'})
    $('.main-second-nav').css({
      'background-color':'#fff',
      'box-shadow':'0px 41px 7px 2px rgb(255, 255, 255), 0px -96px 7px 49px rgb(255, 255, 255)'
    })
    $('body').css('background-image','url("media/svg/helloBG.svg")')
    $('.main-first').css('z-index','999999888')
  } else{
    $('.main-second-info').css('display','block')
    $('.main-second-info-2').css('display','block')
    $('.menu').css({'display':'block'})
    $('.main-second-nav').css({
      'background-color':'#f2f3f7',
      'box-shadow':'0px 41px 7px 2px rgb(242, 243, 248), 0px -96px 7px 49px rgb(242, 243, 248)'
    });
    $('body').css('background-image','url("media/svg/login.svg")');
    $('.main-first').css('z-index','1')
  }

  // enter button
  var enterKeyUp = true;
  $(document).keypress(function(event){
      if(enterKeyUp) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if(keycode == '13'){
          switch(currentWindowIs) {
            case 'login':
              sendLoginForm();
              break;
            case 'register':
              sendRegisterForm();
              break;
            case 'recovery':
              sendRecoveryCodeForm();
              break;
          }
        }
        enterKeyUp = false;
      }
  });
  $(document).keyup(function() {
    enterKeyUp = true;
  });

  $('.main-second-info-2').scroll(function(){
    var sroll        =  $('.main-second-info-2').scrollTop(),
        heightsroll  =  $('.main-second-info-2').prop('scrollHeight') - 435,
        n = (sroll * 100) / heightsroll;
        n = n.toFixed(0)

    $('.interest').find('.c100').attr('class','c100 p' + n + ' small')
  });

  $(window).resize(function(){
    var heightWindow = window.innerHeight,
        widthtWindow = window.innerWidth;

    if(document.documentElement.clientWidth < 900){
      $('.main-second-info').css('display','block')
      $('.main-second-info-2').css('display','none')
      $('.menu').css({'display':'none'})
      $('.main-second-nav').css({
        'background-color':'#fff',
        'box-shadow':'0px 41px 7px 2px rgb(255, 255, 255), 0px -96px 7px 49px rgb(255, 255, 255)'
      });
      $('body').css('background-image','url("media/svg/helloBG.svg")');
      $('.main-first').css('z-index','999999888')
    } else{
      $('.main-second-info').css('display','block')
      $('.main-second-info-2').css('display','block')
      $('.menu').css({'display':'block'})
      $('.main-second-nav').css({
        'background-color':'#f2f3f7',
        'box-shadow':'0px 41px 7px 2px rgb(242, 243, 248), 0px -96px 7px 49px rgb(242, 243, 248)'
      })
      $('body').css('background-image','url("media/svg/login.svg")')
      $('.main-first').css('z-index','1')
    }

    if(widthtWindow > 1060){
      $('.main').css('transform','translate(-50%, -50%)')
      $('.main').css('width','1000px')
      fyDDs();
    } else if(widthtWindow <= 1060 && widthtWindow > 950){
      $('.main').css('transform','translate(-50%, -50%) scale(0.92)')
      $('.main').css('width','1000px')
      fyDDs();
    } else if(widthtWindow <= 950 && widthtWindow >= 900){
      $('.main').css('transform','translate(-50%, -50%) scale(0.92)')
      $('.main').css('width','950px')
      fyDDs();
    } else{
      if(heightWindow < 570){
        $('.main-first-btn').css('position','fixed')
        $('.main-first-btn').css('top','calc(100vh - 180px)')
        $('.main-first-btn').css('bottom','auto')
        $('.main-first-btn-2').css('position','fixed')
        $('.main-first-btn-2').css('top','calc(100vh - 180px)')
        $('.main-first-btn-2').css('bottom','auto')
      } else{
        $('.main-first-btn').css('position','fixed')
        $('.main-first-btn').css('top','calc(100vh - 220px)')
        $('.main-first-btn').css('bottom','auto')
        $('.main-first-btn-2').css('position','fixed')
        $('.main-first-btn-2').css('top','calc(100vh - 220px)')
        $('.main-first-btn-2').css('bottom','auto')
      }
      $('.main').css('height','100vh')
      $('.main').css('transform','translate(-50%, -54%)')
      $('.main').css('width','100vw')
      $('.main').css('box-shadow','none')
      $('.main').css('border','none')
      $('.main').css('background-color','#fff')
      $('.main').css('border-radius','0px')
      $('.main-first').css('border','none')
      $('.main-first').css('right','0px')
      $('.main-first').css('transform','translate(0px, 56px)')
      $('.main-first').css('width','100%')
      $('.main-first-btn').css('border-radius','15px')
      $('.main-first-btn-2').css('border-radius','15px')
      $('.main-first-btn-3').css('border-radius','15px')
      $('.main-second').css('height',' ')
      $('.main-second').css('width','calc(100% + 27px)')
      $('.main-second').css('left','-8px')
      $('.main-second-info').css('display','none')
    }
  });

  if(WidthtWindow > 1060){
    $('.main').css('transform','translate(-50%, -50%)')
    $('.main-second-info').css('display','block')
    $('.main-second-info-2').css('display','block')
  } else if(WidthtWindow <= 1060 && WidthtWindow > 950){
    $('.main').css('transform','translate(-50%, -50%) scale(0.92)')
    $('.main-second-info').css('display','block')
    $('.main-second-info-2').css('display','block')
  } else if(WidthtWindow <= 950 && WidthtWindow >= 900){
    $('.main').css('transform','translate(-50%, -50%) scale(0.92)')
    $('.main').css('width','950px')
    $('.main-second-info').css('display','block')
    $('.main-second-info-2').css('display','block')
  } else{
    if(HeightWindow < 570){
      $('.main-first-btn').css('position','fixed')
      $('.main-first-btn').css('top','calc(100vh - 180px)')
      $('.main-first-btn').css('bottom','auto')
      $('.main-first-btn-2').css('position','fixed')
      $('.main-first-btn-2').css('top','calc(100vh - 180px)')
      $('.main-first-btn-2').css('bottom','auto')
      $('.main-second-info').css('display','block')
      $('.main-second-info-2').css('display','none')
    } else{
      $('.main-first-btn').css('position','fixed')
      $('.main-first-btn').css('top','calc(100vh - 220px)')
      $('.main-first-btn').css('bottom','auto')
      $('.main-first-btn-2').css('position','fixed')
      $('.main-first-btn-2').css('top','calc(100vh - 220px)')
      $('.main-first-btn-2').css('bottom','auto')
      $('.main-second-info').css('display','block')
      $('.main-second-info-2').css('display','none')

    }
    $('.main').css('height','100vh')
    $('.main').css('transform','translate(-50%, -54%)')
    $('.main').css('width','100vw')
    $('.main').css('box-shadow','none')
    $('.main').css('border','none')
    $('.main').css('background-color','#fff')
    $('.main').css('border-radius','0px')
    $('.main-first').css('border','none')
    $('.main-first').css('right','0px')
    $('.main-first').css('transform','translate(0px, 56px)')
    $('.main-first').css('width','100%')
    $('.main-first-btn').css('border-radius','15px')
    $('.main-first-btn-2').css('border-radius','15px')
    $('.main-first-btn-3').css('border-radius','15px')
    $('.main-second').css('height',' ')
    $('.main-second').css('width','calc(100% + 27px)')
    $('.main-second').css('left','-8px')
    $('.main-second-info').css('display','none')

  }
});

function loader_login(action){
  if(action === undefined){
    if($('.main-first-preloader').css('visibility') == 'hidden'){
      return false;
    } else{
      return true;
    }
  } else{
    if(action == 'show'){
      $('.main-first-preloader').css({
        'opacity':'1',
        'visibility':'visible'
      })
    }
    else if(action == 'hidden'){
      $('.main-first-preloader').css({
        'opacity':'0',
        'visibility':'hidden'
      })
    }
    else{
      console.error('Ошибка в action')
    }
  }
}

function fyDDs(){
  $('.main-first-btn').css('position','absolute')
  $('.main-first-btn').css('top','auto')
  $('.main-first-btn').css('bottom','20px')
  $('.main-first-btn-2').css('position','absolute')
  $('.main-first-btn-2').css('top','auto')
  $('.main-first-btn-2').css('bottom','20px')
  $('.main').css('height','550px')
  $('.main').css('box-shadow','0 0 13px 0 rgba(82, 63, 105, 0.05)')
  $('.main').css('border','1px solid #d9e3e9')
  $('.main').css('background-color','#f2f3f8')
  $('.main').css('border-radius','15px')
  $('.main-first').css('border','1px solid #d9e3e9')
  $('.main-first').css('right','50px')
  $('.main-first').css('transform','translate(0px, 0px)')
  $('.main-first').css('width','350px')
  $('.main-first-btn').css('border-radius','5px')
  $('.main-first-btn-2').css('border-radius','5px')
  $('.main-first-btn-3').css('border-radius','5px')
  $('.main-second').css('height','100%')
  $('.main-second').css('width','calc(100% - 400px)')
  $('.main-second').css('left','auto')

}

function menu(a){
  if(!StatusMenu){
    StatusMenu = true;
    var sroll        =  $('.main-second-info-2').scrollTop(),
        heightsroll  =  $('.main-second-info-2').prop('scrollHeight') - 435,
        n = (sroll * 100) / heightsroll;
        n = n.toFixed(0)


    $('.main-second-info').css('transform','translate(0px, -150%)')
    $('.main-second-info-2').css('transform','translate(0px, -101%)')
    $(a).attr('title','Закрыть')
    $(a).find('#menu-line-1').css('transform','rotate(45deg)')
    $(a).find('#menu-line-1').css('margin-top','8px')
    $(a).find('#menu-line-3').css('transform','rotate(-45deg)')
    $(a).find('#menu-line-3').css('margin-top','-10px')
    $(a).find('#menu-line-2').css('opacity','0')
    $(a).find('.menu-line').css('height','2px')
    $(a).find('.menu-line').css('width','40%')
    $(a).css('transform','scale(0.85)')
    $(a).css('height','31.5px')
    $(a).css('padding-top','13.5px')
    $(a).find('.c100').attr('class','c100 p' + n + ' small')
    setTimeout(function(){
      $(a).find('.interest').css('opacity','1')
    },350)


  } else{
    StatusMenu = false;
    $('.main-second-info').css({
      'transform':'translate(0px, 0%)'
    })
    $('.main-second-info-2').css('transform','translate(0px, 0%)')
    $(a).attr('title','Открыть')
    $(a).find('#menu-line-1').css('transform','rotate(0deg)')
    $(a).find('#menu-line-1').css('margin-top','3px')
    $(a).find('#menu-line-3').css('transform','rotate(0deg)')
    $(a).find('#menu-line-3').css('margin-top','3px')
    $(a).find('#menu-line-2').css('opacity','1')
    $(a).find('.menu-line').css('height','3px')
    $(a).find('.menu-line').css('width','3px')
    $(a).css('transform','scale(0.85) rotate(270deg)')
    $(a).css('height','33.5px')
    $(a).css('padding-top','11.5px')
    $(a).find('.interest').css('opacity','0')
    $(a).find('.c100').attr('class','c100 p0 small')
  }
}

function password_open(a){
  var input      = $(a).parent().find('input'),
      inputTYPE  = input.attr('type')

  if(inputTYPE == 'password'){
    input.attr('type','text')
    $(a).attr('title','Скрыть пароль')
    $(a).find('div').css('opacity','1')
  } else{
    input.attr('type','password')
    $(a).attr('title','Показать пароль')
    $(a).find('div').css('opacity','0')
  }
  input.focus()
}

function recovery(){
  currentWindowIs = 'recovery';
  $('.main-first-login').css('transform','translate(0%, 0px)')
  $('.main-first-register').css('transform','translate(0%, 0px)')
  $('.main-first-recovery').css('transform','translate(0%, 0px)')
}

function back(){
  currentWindowIs = 'login';
  $('.main-first-login').css('transform','translate(-100%, 0px)')
  $('.main-first-register').css('transform','translate(-100%, 0px)')
  $('.main-first-recovery').css('transform','translate(-100%, 0px)')
}

function register(){
  currentWindowIs = 'register';
  $('.main-first-login').css('transform','translate(-200%, 0px)')
  $('.main-first-register').css('transform','translate(-200%, 0px)')
  $('.main-first-recovery').css('transform','translate(-200%, 0px)')
}

// =============================================================================

var loginRegex = /^([a-z0-9]){4,32}$/g;
var passwordRegex = /^([a-zA-Z0-9-.,_!а-яА-ЯёЁ]){8,64}$/g;
var nameRegex = /^([A-Za-zА-ЯЁа-яё]){2,32}$/gu;
var emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/g;
var phoneRegex = /^([0-9]){11}$/g;
var codeRegex = /^([0-9]){7}$/g;

function sendRegisterForm() {

  var login, password1, password2, name, email, phone, terms, mailing;
  var errorWindow = $('#register-error-message');
  var errorMsg = $('#register-error-message .block-error-text-text');

  login = $('#register-login-field').val();
  password1 = $('#password2').val();
  password2 = $('#password3').val();
  name = $('#register-name-field').val();
  email = $('#register-email-field').val();
  phone = $('#register-phone-field').val().replace(/[^0-9]/gim, '');
  terms = document.getElementById('chb1').checked ? 'true' : 'false';
  mailing = document.getElementById('chb2').checked ? 'true' : 'false';

  loginRegex.lastIndex = 0;
  passwordRegex.lastIndex = 0;
  nameRegex.lastIndex = 0;
  emailRegex.lastIndex = 0;
  phoneRegex.lastIndex = 0;

  function errorLog(log) {
    var log;
    if(log === false) {
      errorWindow.css('opacity', '0');
      setTimeout(function() { errorWindow.css('display', 'none'); }, 10);
    }
    else {
      errorMsg.html(log);
      errorWindow.css('display', 'block');
      setTimeout(function() { errorWindow.css('opacity', '1'); }, 10);
    }
  }

  if(!loginRegex.test(login)) {
    errorLog('Логин может состоять только от 4 до 32 цифр и букв латинского алфавита.');
    return;
  }
  if(password1 != password2) {
    errorLog('Пароли не совпадают');
    return;
  }
  if(!passwordRegex.test(password1)) {
    errorLog('Недопустимый пароль');
    return;
  }
  if(!nameRegex.test(name)) {
    errorLog('Недопустимое имя');
    return;
  }
  if(!emailRegex.test(email)) {
    errorLog('Недопустимый адрес');
    return;
  }
  if(!phoneRegex.test(phone)) {
    errorLog('Недопустимый номер телефона');
    return;
  }
  if(terms != 'true') {
    errorLog('Прочитайте пользовательское соглашение');
    return;
  }

  errorLog(false);

  // query to server
  $.ajax({
    url: 'db_login.php',
    type: "POST",
    data: {
      regacc: 'true',
      f1: login,
      f2: password1,
      f3: name,
      f4: email,
      f5: phone,
      f6: terms,
      f7: mailing
    },
    success: function(data){
      if(data == 'GRANTED.') {
        $('.main-second-logo > svg').css({
          'transform':'scale(55.5) translate(-24px, 0px)'
        });
        $('.main-first').css('z-index','1')
        setTimeout(function(){
          $('body').css({
            'opacity':'0',
            'background-image':'url()',
            'background-color':'var(--main-bg)'
          })
          setTimeout(function(){
            document.location.reload(true);
          }, 450)
        }, 550)
      }
      else if(data == 'LOGIN.') {
        errorLog('Логин может состоять только от 4 до 32 цифр и букв латинского алфавита.');
      }
      else if(data == 'PASSWORD.') {
        errorLog('Недопустимый пароль');
      }
      else if(data == 'NAME.') {
        errorLog('Недопустимое имя');
      }
      else if(data == 'EMAIL.') {
        errorLog('Недопустимый адрес');
      }
      else if(data == 'PHONENUMBER.') {
        errorLog('Недопустимый номер телефона');
      }
      else if(data == 'BANNED.') {
        errorLog('Обнаружена подозрительная активность');
      }
      else if(data == 'TERMS.') {
        errorLog('Прочитайте пользовательское соглашение');
      }
      else if(data == 'PHONE_LIMIT.') {
        errorLog('Превышен лимит количества пользователей для введенного номера телефона');
      }
      else if(data == 'EMAIL_LIMIT.') {
        errorLog('Превышен лимит количества пользователей для введенного адреса электронной почты');
      }
      else if(data == 'AUTHORIZED.') {
        errorLog('Вы уже вошли в аккаунт');
      }
      else {
        errorLog('Произошла ошибка. Мы уже работаем над её исправлением');
        console.log('response: ' + data);
      }
    },
    beforeSend: function(){
      loader_login('show');
    },
    complete: function(){
      loader_login('hidden');
    },
  });

  // $.post('db_login.php', {
  //   regacc: 'true',
  //   f1: login,
  //   f2: password1,
  //   f3: name,
  //   f4: email,
  //   f5: phone,
  //   f6: terms,
  //   f7: mailing
  // }).done(function(data) {
  //   if(data == 'GRANTED.') {
  //     $('.main-second-logo > svg').css({
  //       'transform':'scale(55.5) translate(-24px, 0px)'
  //     });
  //     $('.main-first').css('z-index','1')
  //     setTimeout(function(){
  //       $('body').css({
  //         'opacity':'0',
  //         'background-image':'url()',
  //         'background-color':'var(--main-bg)'
  //       })
  //       setTimeout(function(){
  //         document.location.reload(true);
  //       }, 450)
  //     }, 550)
  //   }
  //   else if(data == 'LOGIN.') {
  //     errorLog('Логин может состоять только от 4 до 32 цифр и букв латинского алфавита.');
  //   }
  //   else if(data == 'PASSWORD.') {
  //     errorLog('Недопустимый пароль');
  //   }
  //   else if(data == 'NAME.') {
  //     errorLog('Недопустимое имя');
  //   }
  //   else if(data == 'EMAIL.') {
  //     errorLog('Недопустимый адрес');
  //   }
  //   else if(data == 'PHONENUMBER.') {
  //     errorLog('Недопустимый номер телефона');
  //   }
  //   else if(data == 'TERMS.') {
  //     errorLog('Прочитайте пользовательское соглашение');
  //   }
  //   else if(data == 'PHONE_LIMIT.') {
  //     errorLog('Превышен лимит количества пользователей для введенного номера телефона');
  //   }
  //   else if(data == 'EMAIL_LIMIT.') {
  //     errorLog('Превышен лимит количества пользователей для введенного адреса электронной почты');
  //   }
  //   else if(data == 'AUTHORIZED.') {
  //     errorLog('Вы уже вошли в аккаунт');
  //   }
  //   else {
  //     errorLog('Произошла ошибка. Мы уже работаем над её исправлением');
  //     console.log('response: ' + data);
  //   }
  // });

}

function sendLoginForm() {

  var login, password;
  var errorWindow = $('#login-error-message');
  var errorMsg = $('#login-error-message .block-error-text-text');

  login = $('#login-login-field').val();
  password = $('#password1').val();

  loginRegex.lastIndex = 0;
  passwordRegex.lastIndex = 0;

  function errorLog(log) {
    var log;
    if(log === false) {
      errorWindow.css('opacity', '0');
    }
    else {
      errorMsg.html(log);
      errorWindow.css('opacity', '1');
    }
  }

  if(!loginRegex.test(login)) {
    errorLog('Логин может состоять только от 4 до 32 цифр и букв латинского алфавита');
    return;
  }
  if(!passwordRegex.test(password)) {
    errorLog('Недопустимый пароль');
    return;
  }

  errorLog(false);

  // query to server
  $.ajax({
    url: 'db_login.php',
    type: "POST",
    data: {
      logacc: 'true',
      f1: login,
      f2: password
    },
    success: function(data){
      if(data == 'GRANTED.') {
        $('.main-first').css('z-index','1')
        setTimeout(function(){
          $('body').css({
            'opacity':'0',
            'background-image':'url()',
            'background-color':'var(--main-bg)'
          })
          setTimeout(function(){
            document.location.reload(true);
          }, 450)
        }, 550)
      }
      else if(data == 'LOGIN.') {
        $('.main-second-logo > svg').css({
          'transform':'scale(0.6)'
        })
        errorLog('Логин может состоять только от 4 до 32 цифр и букв латинского алфавита.');
      }
      else if(data == 'BANNED.') {
        errorLog('Обнаружена подозрительная активность');
      }
      else if(data == 'PASSWORD.') {
        $('.main-second-logo > svg').css({
          'transform':'scale(0.6)'
        })
        errorLog('Недопустимый пароль');
      }
      else if(data == 'NOT_EXISTS.') {
        $('.main-second-logo > svg').css({
          'transform':'scale(0.6)'
        })
        errorLog('Неверный логин или пароль');
      }
      else if(data == 'WRONG_PASSWORD.') {
        $('.main-second-logo > svg').css({
          'transform':'scale(0.6)'
        })
        errorLog('Неверный логин или пароль');
      }
      else if(data == 'AUTHORIZED.') {
        $('.main-second-logo > svg').css({
          'transform':'scale(0.6)'
        })
        errorLog('Вы уже вошли в аккаунт');
      }
      else {
        $('.main-second-logo > svg').css({
          'transform':'scale(0.6)'
        })
        errorLog('Произошла ошибка. Мы уже работаем над её исправлением');
        console.log('response: ' + data);
      }
    },
    beforeSend: function(){
      loader_login('show');
    },
    complete: function(){
      loader_login('hidden');
    },
  });
  // $.post('db_login.php', {
  //   logacc: 'true',
  //   f1: login,
  //   f2: password
  // }).done(function(data) {
  //   if(data == 'GRANTED.') {
  //     $('.main-second-logo > svg').css({
  //       'transform':'scale(55.5) translate(-24px, 0px)'
  //     });
  //     $('.main-first').css('z-index','1')
  //     setTimeout(function(){
  //       $('body').css({
  //         'opacity':'0',
  //         'background-image':'url()',
  //         'background-color':'var(--main-bg)'
  //       })
  //       setTimeout(function(){
  //         document.location.reload(true);
  //       }, 450)
  //     }, 550)
  //   }
  //   else if(data == 'LOGIN.') {
  //     $('.main-second-logo > svg').css({
  //       'transform':'scale(0.6)'
  //     })
  //     errorLog('Логин может состоять только от 4 до 32 цифр и букв латинского алфавита.');
  //   }
  //   else if(data == 'PASSWORD.') {
  //     $('.main-second-logo > svg').css({
  //       'transform':'scale(0.6)'
  //     })
  //     errorLog('Недопустимый пароль');
  //   }
  //   else if(data == 'NOT_EXISTS.') {
  //     $('.main-second-logo > svg').css({
  //       'transform':'scale(0.6)'
  //     })
  //     errorLog('Неверный логин или пароль');
  //   }
  //   else if(data == 'WRONG_PASSWORD.') {
  //     $('.main-second-logo > svg').css({
  //       'transform':'scale(0.6)'
  //     })
  //     errorLog('Неверный логин или пароль');
  //   }
  //   else if(data == 'AUTHORIZED.') {
  //     $('.main-second-logo > svg').css({
  //       'transform':'scale(0.6)'
  //     })
  //     errorLog('Вы уже вошли в аккаунт');
  //   }
  //   else {
  //     $('.main-second-logo > svg').css({
  //       'transform':'scale(0.6)'
  //     })
  //     errorLog('Произошла ошибка. Мы уже работаем над её исправлением');
  //     console.log('response: ' + data);
  //   }
  // });

}

function sendExitForm() {
  if(user_token && (user_token != '') && (user_token.length > 1)) {
    $.ajax({
      url: 'db_login.php',
      type: "POST",
      data: {
        exitacc: 'true',
        token: user_token
      },
      success: function(data){
        if(data == 'GRANTED.') {
          document.location.reload(true);
        }
        else {
          console.log('response: ' + data);
        }
      },
      beforeSend: function(){
        loader_login('show');
      },
      complete: function(){
        loader_login('hidden');
      },
    });
    // $.post('db_login.php', {
    //   exitacc: 'true',
    //   token: user_token
    // }).done(function(data) {
    //   if(data == 'GRANTED.') {
    //     document.location.reload(true);
    //   }
    //   else {
    //     console.log('response: ' + data);
    //   }
    // });
  }
}

var passwordRecoveryStage = 0;

function sendRecoveryCodeForm() {

  var currentField, code, password1, password2;
  var errorWindow = $('#recovery-error-message');
  var errorMsg = $('#recovery-error-message .block-error-text-text');

  function errorLog(log) {
    var log;
    if(log === false) {
      errorWindow.css('opacity', '0');
    }
    else {
      errorMsg.html(log);
      errorWindow.css('opacity', '1');
    }
  }

  if(passwordRecoveryStage === 0) {

    currentField = $('#recovery-login-field').val();
    if(currentField.indexOf('@') == -1) {
      // is login
      loginRegex.lastIndex = 0;
      if(!loginRegex.test(currentField)) {
        errorLog('Недопустимый логин');
        return;
      }
    }
    else {
      // is email
      emailRegex.lastIndex = 0;
      if(!emailRegex.test(currentField)) {
        errorLog('Недопустимый адрес электронной почты');
        return;
      }
    }

    errorLog(false);


    // query to server

    $.ajax({
      url: 'db_login.php',
      type: "POST",
      data: {
        gen_recovery: 'true',
        f1: currentField
      },
      success: function(data){
        if(data == 'EMAIL.') {
          errorLog('Недопустимый адрес электронной почты');
        }
        else if(data == 'NOT_EXISTS.') {
          errorLog('Аккаунт не найден');
        }
        else if(data == 'LOGIN.') {
          errorLog('Недопустимый логин');
        }
        else if(data == 'TIME_LIMIT.') {
          errorLog('Повторно код можно отправить через 3 минуты');
        }
        else if(data == 'BANNED.') {
          errorLog('Обнаружена подозрительная активность');
        }
        else if(data == 'AUTHORIZED.') {
          errorLog('Вы уже вошли в аккаунт');
        }
        else if(data == 'SENT.') {
          $('#recovery-code-div').css({'opacity':'1','visibility':'visible'});
          $('#recovery-code-back').css('opacity', '0');
          $('#recovery-code-btn').html('Проверить код');
          errorLog(false);
          passwordRecoveryStage = 1;
        }
        else {
          errorLog('Произошла ошибка. Мы уже работаем над её исправлением');
          console.log('response: ' + data);
        }
      },
      beforeSend: function(){
        loader_login('show');
      },
      complete: function(){
        loader_login('hidden');
      },
    });
    // $.post('db_login.php', {
    //   gen_recovery: 'true',
    //   f1: currentField
    // }).done(function(data) {
    //   if(data == 'EMAIL.') {
    //     errorLog('Недопустимый адрес электронной почты');
    //   }
    //   else if(data == 'NOT_EXISTS.') {
    //     errorLog('Аккаунт не найден');
    //   }
    //   else if(data == 'LOGIN.') {
    //     errorLog('Недопустимый логин');
    //   }
    //   else if(data == 'TIME_LIMIT.') {
    //     errorLog('Повторно код можно отправить через 3 минуты');
    //   }
    //   else if(data == 'AUTHORIZED.') {
    //     errorLog('Вы уже вошли в аккаунт');
    //   }
    //   else if(data == 'SENT.') {
    //     $('#recovery-code-div').css({'opacity':'1','visibility':'visible'});
    //     $('#recovery-code-back').css('opacity', '0');
    //     $('#recovery-code-btn').html('Проверить код');
    //     errorLog(false);
    //     passwordRecoveryStage = 1;
    //   }
    //   else {
    //     errorLog('Произошла ошибка. Мы уже работаем над её исправлением');
    //     console.log('response: ' + data);
    //   }
    // });

  }
  else if(passwordRecoveryStage === 1) {

    code = $('#recovery-code-field').val().replace(/[^0-9]/gim, '');
    codeRegex.lastIndex = 0;
    if(!codeRegex.test(code)) {
      errorLog('Недопустимый код');
      return;
    }

    // query to server

    $.ajax({
      url: 'db_login.php',
      type: "POST",
      data: {
        chk_recovery: 'true',
        f1: code
      },
      success: function(data){
        if(data == 'CODE.') {
          errorLog('Недопустимый код');
        }
        else if(data == 'MATCH.') {
          $('#recovery-stage1-block').css('transform','translate(-100%, 0)');
          $('#recovery-stage2-block').css('opacity', '1');
          $('#recovery-stage2-block').css('transform','translate(-100%, 0)');
          setTimeout(function(){
            $('#recovery-stage1-block').css('opacity', '0');
          }, 500);
          $('#recovery-code-btn').html('Сменить пароль');
          errorLog(false);
          passwordRecoveryStage = 2;
        }
        else if(data == 'NOT_MATCH.') {
          errorLog('Неверный код');
        }
        else if(data == 'BANNED.') {
          errorLog('Обнаружена подозрительная активность');
        }
        else {
          errorLog('Произошла ошибка. Мы уже работаем над её исправлением');
          console.log('response: ' + data);
        }
      },
      beforeSend: function(){
        loader_login('show');
      },
      complete: function(){
        loader_login('hidden');
      },
    });
    // $.post('db_login.php', {
    //   chk_recovery: 'true',
    //   f1: code
    // }).done(function(data) {
    //   if(data == 'CODE.') {
    //     errorLog('Недопустимый код');
    //   }
    //   else if(data == 'MATCH.') {
    //     $('#recovery-stage1-block').css('transform','translate(-100%, 0)');
    //     $('#recovery-stage2-block').css('opacity', '1');
    //     $('#recovery-stage2-block').css('transform','translate(-100%, 0)');
    //     setTimeout(function(){
    //       $('#recovery-stage1-block').css('opacity', '0');
    //     }, 500);
    //     $('#recovery-code-btn').html('Сменить пароль');
    //     errorLog(false);
    //     passwordRecoveryStage = 2;
    //   }
    //   else if(data == 'NOT_MATCH.') {
    //     errorLog('Неверный код');
    //   }
    //   else {
    //     errorLog('Произошла ошибка. Мы уже работаем над её исправлением');
    //     console.log('response: ' + data);
    //   }
    // });

  }
  else if(passwordRecoveryStage === 2) {

    password1 = $('#password-recovery').val();
    password2 = $('#password-recovery-2').val();

    if(password1 != password2) {
      errorLog('Пароли не совпадают');
      return;
    }
    passwordRegex.lastIndex = 0;
    if(!passwordRegex.test(password1)) {
      errorLog('Недопустимый пароль');
      return;
    }

    errorLog(false);

    // query to server
    $.ajax({
      url: 'db_login.php',
      type: "POST",
      data: {
        set_recovery: 'true',
        f1: password1
      },
      success: function(data){
        if(data == 'SUCCESS.') {
          errorLog(false);
          //$('#recovery-code-btn').css('opacity', '0');
          $('#recovery-open-btn').css('display','none');
          $('#recovery-stage2-block').css('transform','translate(0, 0)');
          $('.main-first-login').css('transform','translate(-100%, 0px)');
          $('.main-first-register').css('transform','translate(-100%, 0px)');
          $('.main-first-recovery').css('transform','translate(-100%, 0px)');
          setTimeout(function(){
            $('#recovery-stage2-block').css('opacity', '0');
          }, 500);
          //document.location.reload(true);
        }
        else if(data == 'LOGIN.') {
          errorLog('Логин может состоять только от 4 до 32 цифр и букв латинского алфавита.');
        }
        else if(data == 'PASSWORD.') {
          errorLog('Недопустимый пароль');
        }
        else if(data == 'BANNED.') {
          errorLog('Обнаружена подозрительная активность');
        }
        else {
          errorLog('Произошла ошибка. Мы уже работаем над её исправлением');
          console.log('response: ' + data);
        }
      },
      beforeSend: function(){
        loader_login('show');
      },
      complete: function(){
        loader_login('hidden');
      },
    });

    // $.post('db_login.php', {
    //   set_recovery: 'true',
    //   f1: password1
    // }).done(function(data) {
    //   if(data == 'SUCCESS.') {
    //     errorLog(false);
    //     //$('#recovery-code-btn').css('opacity', '0');
    //     $('#recovery-open-btn').css('display','none');
    //     $('#recovery-stage2-block').css('transform','translate(0, 0)');
    //     $('.main-first-login').css('transform','translate(-100%, 0px)');
    //     $('.main-first-register').css('transform','translate(-100%, 0px)');
    //     $('.main-first-recovery').css('transform','translate(-100%, 0px)');
    //     setTimeout(function(){
    //       $('#recovery-stage2-block').css('opacity', '0');
    //     }, 500);
    //     //document.location.reload(true);
    //   }
    //   else if(data == 'LOGIN.') {
    //     errorLog('Логин может состоять только от 4 до 32 цифр и букв латинского алфавита.');
    //   }
    //   else if(data == 'PASSWORD.') {
    //     errorLog('Недопустимый пароль');
    //   }
    //   else {
    //     errorLog('Произошла ошибка. Мы уже работаем над её исправлением');
    //     console.log('response: ' + data);
    //   }
    // });

  }
  else {  }

}
