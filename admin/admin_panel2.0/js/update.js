
$(document).ready(function(){
  setInterval(updateInterval, 500)
})

function updateInterval(){
  var tmpMinUpdate    =  Update.timeNow.minute;
  var tmpHoursUpdate  =  Update.timeNow.hours;
  var tmpDayUpdate    =  Update.timeNow.day;
  var tmpMonthUpdate  =  Update.timeNow.month;
  var tmpYearUpdate   =  Update.timeNow.year;

  if(tmpMinUpdate < 10){
    tmpMinUpdate = '0' + tmpMinUpdate;
  }
  if(tmpHoursUpdate < 10){
    tmpHoursUpdate = '0' + tmpHoursUpdate;
  }
  if(tmpDayUpdate < 10){
    tmpDayUpdate = '0' + tmpDayUpdate;
  }
  if(tmpMonthUpdate < 10){
    tmpMonthUpdate = '0' + tmpMonthUpdate;
  }
  if(tmpYearUpdate < 10){
    tmpYearUpdate = '0' + tmpYearUpdate;
  }

  if(new Date().getDate() - Update.timeNow.day == 0){
    $('.window-block-upload-text-text').text('Время последней проверки: сегодня, ' + tmpHoursUpdate + ':' + tmpMinUpdate)
  }
  else if(new Date().getDate() - Update.timeNow.day == 1){
    $('.window-block-upload-text-text').text('Время последней проверки: вчера, ' +  tmpHoursUpdate + ':' + tmpMinUpdate)
  }
  else{
    $('.window-block-upload-text-text').text('Время последней проверки: ' + tmpDayUpdate + '.' + tmpMonthUpdate + '.' + tmpYearUpdate + ' ' + tmpHoursUpdate + ':' + tmpMinUpdate)
  }

}

function updateAP(){
  if(!loaderUpdate()){
    loaderUpdate('show');
    setTimeout(function(){
      Update.timeNow.day     =  new Date().getDate();
      Update.timeNow.month   =  new Date().getMonth();
      Update.timeNow.year    =  new Date().getFullYear();
      Update.timeNow.minute  =  new Date().getMinutes();
      Update.timeNow.hours   =  new Date().getHours();
      loaderUpdate('hidden');
      $('.console-main-textarea').append('<c-good>Обновления не найдены!</c-good></br>У вас установлена самая последняя версия Swiftly<br>');
      $('.console-main-textarea').scrollTop($('.console-main-textarea').prop('scrollHeight'));
      notification_add('question','Обновления не найдены','У вас установлена самая последняя версия Swiftly', 7.5)
    }, randomInteger(700, 8500))
  }
}

function loaderUpdate(action){
  if(action === undefined){
    if($('.window-block-upload-loader').css('visibility') == 'hidden'){
      return false;
    } else{
      return true;
    }
  }
  else if(action == 'show'){
    $('.window-block-upload-loader').css({
      'opacity':'1',
      'visibility':'visible'
    });
    setTimeout(function(){
      $('.window-block-upload-loader-ab').css({
        'opacity':'1',
        'visibility':'visible',
      });
    }, 150)
  }
  else if(action == 'hidden'){
    $('.window-block-upload-loader-ab').css({
      'opacity':'0',
      'visibility':'hidden',
    });
    setTimeout(function(){
      $('.window-block-upload-loader').css({
        'opacity':'0',
        'visibility':'hidden'
      });
    }, 150)
  }
  else{
    console.error('Ошибка в аргументе action')
  }
}
