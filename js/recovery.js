var loginRegex = /^([A-z0-9]){4,32}$/;
var passwordRegex = /^([a-zA-Z0-9-.,_!$#а-яА-ЯёЁ]){8,64}$/u;
var emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

var Recovery = {
  defaultTimerValue: 90,
  field: {
    input: undefined,
    code: undefined,
    pass1: undefined,
    pass2: undefined,
    timer: 'recoveryRepeatMsg',
    btn: 'recovery-btn',
    window: [
      'recoveryStageFirst',
      'recoveryStageSecond',
      'recoveryStageThird'
    ]
  },
  stage: 0,
  setStage: function(stage) {
    if(stage < 0 || stage > 2) return;
    if(Recovery.stage < 0 || Recovery.stage > 2) return;
    if(typeof(stage) == 'undefined') stage = Recovery.stage;
    else Recovery.stage = stage;
    if(stage == 0) {
      $('#' + Recovery.field.window[0]).css('max-height', '1000px');
      $('#' + Recovery.field.window[1]).css('max-height', '0');
      $('#' + Recovery.field.window[2]).css('max-height', '0');
      $('#' + Recovery.field.btn).val('Отправить код');
    }
    if(stage == 1) {
      $('#' + Recovery.field.window[0]).css('max-height', '1000px');
      $('#' + Recovery.field.window[1]).css('max-height', '1000px');
      $('#' + Recovery.field.window[2]).css('max-height', '0');
      if($('#' + Recovery.field.code).val() == '') {
        $('#' + Recovery.field.btn).val('Отправить код');
      }
      else {
        $('#' + Recovery.field.btn).val('Подтвердить код');
      }
    }
    if(stage == 2) {
      $('#' + Recovery.field.window[0]).css('max-height', '0');
      $('#' + Recovery.field.window[1]).css('max-height', '0');
      $('#' + Recovery.field.window[2]).css('max-height', '1000px');
      $('#' + Recovery.field.btn).val('Сохранить');
    }
  },
  check: function() {
    if((Recovery.stage == 0) || ((Recovery.stage == 1) && ($('#' + Recovery.field.code).val() == ''))) {
      // check timer
      if(Recovery.timerValue > 0) {
        notification_add('warning', 'Предупреждение', 'Код подтверждения уже был отправлен');
        return;
      }
      // check fields
      var val = $('#' + Recovery.field.input).val();
      var type = 'invalid';
      if(val.match(loginRegex)) {
        type = 'login';
      }
      else if(val.match(emailRegex)) {
        type = 'email';
      }
      else {
        notification_add('error', 'Ошибка', 'Проверьте правильность введеных вами данных');
        return;
      }
      // send
      $.ajax({
        type: 'POST',
        url: 'php/db_auth.php',
        data: {
          recovery_get_code: true,
          captcha3_token: theCaptchaToken,
          rec_input_val: val,
          rec_input_type: type
        },
        beforeSend: function() {
          loaderMain('show');
        },
        complete: function() {
          captcha3GenToken(true);
          loaderMain('hidden');
        },
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.')) {
            Recovery.setStage(1);
            // set timer
            if(typeof(Recovery.timerInterval) != 'undefined') clearInterval(Recovery.timerInterval);
            Recovery.timerValue = Recovery.defaultTimerValue;
            Recovery.timerInterval = setInterval(Recovery.timer, 1000);
            notification_add('line', '', 'Код подтверждения отправлен');
          }
          else if(checkResponseCode('TIME.')) {
            notification_add('warning', 'Предупреждение', 'Код подтверждения уже был отправлен');
          }
          else if(checkResponseCode('WRONG.')) {
            notification_add('error', 'Ошибка', 'Проверьте правильность заполненных данных');
          }
          else if(checkResponseCode('BANNED.')) {
            notification_add('error', 'Нет доступа', 'Обнаружена подозрительная активность');
          }
          else if(checkResponseCode('EMPTY.')) {
            notification_add('error', 'Ошибка', 'Пользователь не найден');
          }
          else if(checkResponseCode('ERROR.')) {
            notification_add('error', 'Ошибка', 'Неизвестная ошибка');
          }
          else if(checkResponseCode('RELOAD.')) {
            document.location.reload(true);
          }
          else {
            notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
            console.log('error: ' + response);
          }
        },
        error: function(jqXHR, status) {
          notification_add('error', 'Ошибка', 'Нет соединения с сервером');
          console.error(status);
          console.error(jqXHR);
        }
      });
    }
    if((Recovery.stage == 1) && ($('#' + Recovery.field.code).val() != '')) {
      var code = $('#' + Recovery.field.code).val();
      if(code.length < 4 || code.length > 16) {
        notification_add('warning', 'Предупреждение', 'Код подтверждения уже был отправлен');
        return;
      }
      // send
      $.ajax({
        type: 'POST',
        url: 'php/db_auth.php',
        data: {
          recovery_check_code: true,
          captcha3_token: theCaptchaToken,
          rec_code: code
        },
        beforeSend: function() {
          loaderMain('show');
        },
        complete: function() {
          loaderMain('hidden');
        },
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.')) {
            Recovery.setStage(2);
          }
          else if(checkResponseCode('WRONG.')) {
            notification_add('error', 'Ошибка', 'Неверный код');
          }
          else if(checkResponseCode('ERROR.')) {
            notification_add('error', 'Ошибка', 'Неизвестная ошибка');
          }
          else if(checkResponseCode('BAN.')) {
            notification_add('error', 'Нет доступа', 'Превышено число попыток');
          }
          else if(checkResponseCode('BANNED.')) {
            notification_add('error', 'Нет доступа', 'Обнаружена подозрительная активность');
          }
          else if(checkResponseCode('RELOAD.')) {
            document.location.reload(true);
          }
          else {
            notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
            console.log('error: ' + response);
          }
        },
        error: function(jqXHR, status) {
          notification_add('error', 'Ошибка', 'Нет соединения с сервером');
          console.error(status);
          console.error(jqXHR);
        }
      });
    }
    if(Recovery.stage == 2) {
      var pass1 = $('#' + Recovery.field.pass1).val();
      var pass2 = $('#' + Recovery.field.pass2).val();
      if(!pass1.match(passwordRegex) || !pass2.match(passwordRegex)) {
        notification_add('error', 'Ошибка', 'Проверьте правильность введеных вами данных');
        return;
      }
      if(pass1 != pass2) {
        notification_add('error', 'Ошибка', 'Пароли не совпадают');
        return;
      }
      // send
      $.ajax({
        type: 'POST',
        url: 'php/db_auth.php',
        data: {
          recovery_change_pass: true,
          rec_password: pass1
        },
        beforeSend: function() {
          loaderMain('show');
        },
        complete: function() {
          loaderMain('hidden');
        },
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.')) {
            notification_add('line', '', 'Пароль изменен');
            setTimeout(function() { document.location.replace('register'); }, 1500);
          }
          else if(checkResponseCode('WRONG.')) {
            notification_add('error', 'Ошибка', 'Данные указаны неверно');
          }
          else if(checkResponseCode('BANNED.')) {
            notification_add('error', 'Нет доступа', 'Обнаружена подозрительная активность');
          }
          else if(checkResponseCode('ERROR.')) {
            notification_add('error', 'Ошибка', 'Неизвестная ошибка');
          }
          else if(checkResponseCode('RELOAD.')) {
            document.location.reload(true);
          }
          else {
            notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
            console.log('error: ' + response);
          }
        },
        error: function(jqXHR, status) {
          notification_add('error', 'Ошибка', 'Нет соединения с сервером');
          console.error(status);
          console.error(jqXHR);
        }
      });
    }
  },
  timerInterval: undefined,
  timerValue: 0,
  timer: function() {
    $('#' + Recovery.field.timer).text('Отправить можно будет через: ' + Number(Recovery.timerValue--) + ' сек');
    if(Recovery.timerValue <= 0) {
      $('#' + Recovery.field.timer).html('&nbsp;');
      clearInterval(Recovery.timerInterval);
      Recovery.timerValue = 0;
    }
  }
};
