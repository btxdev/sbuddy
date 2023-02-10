var deeebug = false;

function register() {

  if(Register.sent) return;

  var loginRegex        =  /^([A-z0-9]){4,32}$/,
      email_regex       =  /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
      name_regex        =  /^([A-Za-zА-ЯЁа-яё]){2,32}$/u,
      secondName_regex  =  /^([A-Za-zА-ЯЁа-яё]){2,48}$/u,
      middleName_regex  =  /^([A-Za-zА-ЯЁа-яё]){2,32}$/u,
      arrayError        =  [];


  // проверяем login
  if(!Register.login.val().match(loginRegex)){
    Register.login.siblings('.input-line').css({
      'background-color':'var(--red)'
    });
    setTimeout(function(){
      Register.login.siblings('.input-line').css({
        'background-color':'#d4d4d4'
      });
    }, 3000);
    notification_add('Error','Ошибка','Логин указан некорректно!');
    arrayError.push(true);
  }

  // проверяем имя
  if(!Register.name.val().match(name_regex)){
    Register.name.siblings('.input-line').css({
      'background-color':'var(--red)'
    });
    setTimeout(function(){
      Register.name.siblings('.input-line').css({
        'background-color':'#d4d4d4'
      });
    }, 3000);
    notification_add('Error','Ошибка','Имя указано некорректно!');
    arrayError.push(true);
  }

  // проверяем фамилию
  if(!Register.secondName.val().match(secondName_regex)){
    Register.secondName.siblings('.input-line').css({
      'background-color':'var(--red)'
    });
    setTimeout(function(){
      Register.secondName.siblings('.input-line').css({
        'background-color':'#d4d4d4'
      });
    }, 3000);
    notification_add('Error','Ошибка','Фамилия указана некорректно!');
    arrayError.push(true);
  }

  // проверяем отчество
  if(!Register.middleName.val().match(middleName_regex)){
    Register.middleName.siblings('.input-line').css({
      'background-color':'var(--red)'
    });
    setTimeout(function(){
      Register.middleName.siblings('.input-line').css({
        'background-color':'#d4d4d4'
      });
    }, 3000);
    notification_add('Error','Ошибка','Отчество указано некорректно!');
    arrayError.push(true);
  }

  // проверяем email
  if(!Register.email.val().match(email_regex)){
    Register.email.siblings('.input-line').css({
      'background-color':'var(--red)'
    });
    setTimeout(function(){
      Register.email.siblings('.input-line').css({
        'background-color':'#d4d4d4'
      });
    }, 3000);
    notification_add('Error','Ошибка','E-mail указан некорректно!');
    arrayError.push(true);
  }

  // проверяем согласие
  if(!Register.chb1.prop('checked')){
    notification_add('Error','Ошибка','Вы не согласны с соглашением!');
    arrayError.push(true);
  }

  // проверяем капчу
  if(typeof(captcha3Token) == 'undefined' || captcha3Token == undefined) {
    notification_add('error', 'Ошибка', 'Нет подключения с сервером');
    console.error('captcha token: ' + typeof(captcha3Token));
    setTimeout(function() {
      if(!deeebug) location.reload(true);
    }, 3000);
    return;
  }

  // если ошибок нет, то отправляем данные
  if(arrayError.length == 0) {
    $.ajax({
      type: 'POST',
      url: 'php/db_auth.php',
      data: {
        register_form: true,
        captcha3_token: captcha3Token,
        login: Register.login.val(),
        name1: Register.name.val(),
        name2: Register.secondName.val(),
        name3: Register.middleName.val(),
        email: Register.email.val(),
        mailing: Register.chb2.prop('checked')
      },
      beforeSend: function() {
        loaderRegister('show');
      },
      complete: function() {
        setTimeout(function() {
          loaderRegister('hidden');
        }, 1000);
      },
      success: function(response) {
        function checkResponseCode(code, rsp) {
          if(typeof(rsp) == 'undefined') rsp = response;
          return (response.substring(0, code.length) == code);
        }
        // create new token
        if(!checkResponseCode('OK.')) {
          captcha3GenToken(true);
        }
        // good
        if(checkResponseCode('OK.')) {
          notification_add('line', '', 'Регистрация завершена. Пароль был отправлен на вашу почту.');
          windowOpen('#login');
          Register.sent = true;
        }
        else if(checkResponseCode('WRONG.')) {
          notification_add('error', 'Ошибка', 'Проверьте правильность заполенных полей');
        }
        else if(checkResponseCode('EMAIL_LIMIT.')) {
          notification_add('error', 'Ошибка', 'Указанный адрес эл. почты уже используется');
        }
        else if(checkResponseCode('LOGIN_EXISTS.')) {
          notification_add('error', 'Ошибка', 'Пользователь с указанным именем уже существует');
        }
        else if(checkResponseCode('LOGIN.')) {
          notification_add('error', 'Ошибка', 'Логин указан некорректно!');
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
        else if(checkResponseCode('ERROR.')) {
          notification_add('error', 'Ошибка', 'Неизвестная ошибка');
        }
        // stage 2
        else if(checkResponseCode('CAPTCHA.')) {
          notification_add('warning', 'Предупреждение', 'Выходите с поднятыми манипуляторами!');
          captcha2GenToken();
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
}

function register2() {
  if(Register.sent) return;
  // check captcha
  if(typeof(captcha2Token) == 'undefined' || captcha2Token == undefined) {
    notification_add('error', 'Ошибка', 'Нет подключения с сервером');
    console.error('captcha token: ' + typeof(captcha2Token));
    setTimeout(function() {
      if(!deeebug) location.reload(true);
    }, 3000);
    return;
  }
  // request
  $.ajax({
    type: 'POST',
    url: 'php/db_auth.php',
    data: {
      register_form_2: true,
      captcha2_token: captcha2Token
    },
    success: function(response) {
      if(response != 'OK.') {
        grecaptcha.reset();
      }
      if(response == 'OK.') {
        notification_add('line', '', 'Регистрация завершена. Пароль был отправлен на вашу почту.');
        windowOpen('#login');
        Register.sent = true;
      }
      else if(response == 'WRONG.') {
        notification_add('error', 'Ошибка', 'Проверьте правильность заполенных полей');
      }
      else if(response == 'EMAIL_LIMIT.') {
        notification_add('error', 'Ошибка', 'Указанный адрес эл. почты уже используется');
      }
      else if(response == 'LOGIN_EXISTS.') {
        notification_add('error', 'Ошибка', 'Пользователь с указанным именем уже существует');
      }
      else if(response == 'LOGIN.') {
        notification_add('error', 'Ошибка', 'Логин указан некорректно!');
      }
      else if(response == 'NAME1.') {
        notification_add('error', 'Ошибка', 'Имя указано некорректно!');
      }
      else if(response == 'NAME2.') {
        notification_add('error', 'Ошибка', 'Фамилия указана некорректно!');
      }
      else if(response == 'NAME3.') {
        notification_add('error', 'Ошибка', 'Отчество указано некорректно!');
      }
      else if(response == 'EMAIL.') {
        notification_add('error', 'Ошибка', 'Адрес эл. почты указан некорректно!');
      }
      else if(response == 'CAPTCHA.') {
        notification_add('error', 'Ошибка', 'Нет подключения с сервером');
        if(!deeebug) location.reload(true);
      }
      else {
        notification_add('error', 'Ошибка', 'Ошибка сервера');
        setTimeout(function() {
          if(!deeebug) location.reload(true);
        }, 3000);
        console.error('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
      console.error('error: ' + status + ', ' + jqXHR);
    }
  });
}
