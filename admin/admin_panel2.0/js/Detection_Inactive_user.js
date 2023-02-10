var no_active_delay = 1500 * 4; // Количество секунд простоя мыши, при котором пользователь считается неактивным
var now_no_active_logout = 2100 * 4; // Выкидываем из профиля через 35 мин бездействия
if(development_state){now_no_active_logout = 64800 * 4;} // Если разработчик, то выкидываем через 18 часов бездействия
var now_no_active = 0; // Текущее количество секунд простоя мыши
var inactiveUser = setInterval("now_no_active++;", 250); // Каждую секунду увеличиваем количество секунд простоя мыши
var inactiveUser = setInterval("detectionInactiveUser()", 250); // Запускаем функцию detectionInactiveUser() через определённый интервал

$(document).on('mousemove',function(){ // Ставим обработчик на движение курсора мыши
  activeUser();
});
$(document).on('keypress',function(){ // Ставим обработчик на нажатик клавиш
  activeUser();
});

function activeUser() {
  now_no_active = 0; // Обнуляем счётчик простоя секунд
}

function detectionInactiveUser() {
  if(now_no_active >= now_no_active_logout){
    sendExitForm();
  } else if (now_no_active >= no_active_delay) { // Проверяем не превышен ли "предел активности" пользователя
    $('detectionInactiveUser').css('display','block')
    setTimeout(function(){
      $('detectionInactiveUser').css('opacity','1')
    }, 50)
    return;
  } else{
    $('detectionInactiveUser').css('opacity','0')
    setTimeout(function(){
      $('detectionInactiveUser').css('display','none')
    }, 250)
  }
}


function detectionInactiveUserGo(){
  $('detectionInactiveUser').css('opacity','0')
  setTimeout(function(){
    $('detectionInactiveUser').css('display','none')
    activeUser();
  }, 250)
}
