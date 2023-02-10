/*
 *  Swiftly Admin Panel v1.12 alpha
 *  (c) 2019-2020 INSOweb  <http://insoweb.ru>
 *  All rights reserved.
 */

var GlobalTheme,localTheme,GlobalWinter,theme_chart_color,localWinter,GlobalStat,localStat,GlobalError,localError,GlobalName,localName,GlobalDescription,localDescription,GlobalEmailMain,localEmailMain,GlobalEmailForm,localEmailForm,GlobalTel,localTel,GlobalNoti,localNoti,GlobalMsg,localMsg,GlobalLang,localLang,cursourPos,browser,
    NavStat                   =    false,
    count_chart               =    0,
    theme_chart               =    "light",
    newsColor                 =    '#f00',
    newsBgColor               =    '#ff0',
    companyColor              =    '#f00',
    companyBgColors           =    '#ff0',
    status_set_news           =    false,
    timeUpdateCharts          =    60000, // время автоматического обновления графика в миллисекундах
    animateCharts             =    true, // анимация в графиках
    elem                      =    document.documentElement,
    adaptiveDesignS           =    '',
    ststus_open_ind_msg       =    false,
    count_type_chart3         =    0,
    newUserStage              =    0,
    tagsSpacebar              =    false,
    passwordChangeDate        =    'нет изменений',
    registrationTimestamp     =    undefined,
    accountDays               =    0,
    profileCheckCodeReady     =    false,
    taskFirstFlag             =    false,
    tasksUpdateArray          =    [],
    currentTaskDay            =    undefined,
    tasksFilterDate           =    'none',
    arrayIconsDev             =    [],
    developmentCount          =    0;
    timetableElemEnable       =    false;

//====================- REGULAR EXPRESSIONS (start) -=========================//

var siteTitleRegex            =    new RegExp(/^([a-zA-Zа-яёА-ЯЁ0-9 ]){0,100}$/gu),
    siteDescriptionRegex      =    new RegExp(/^([ ()№#$%'"<>_+=|}{@&?a-zA-Zа-яёА-ЯЁ0-9!.,:-]){0,400}$/gmu),
    siteEmailRegex            =    new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/g),
    sitePhoneRegex            =    new RegExp(/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/),
    nameRegex                 =    /^([A-Za-zА-ЯЁа-яё]){2,32}$/gu,
    emailRegex                =    /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/g,
    codeRegex                 =    /^([0-9]){7}$/g,
    phoneRegex                =    /^([0-9]){11}$/g,
    passwordRegex             =    /^([a-zA-Z0-9-.,_!а-яА-ЯёЁ]){8,64}$/g;

//=====================- REGULAR EXPRESSIONS (end) -==========================//
//
//=========================- OBJECTS (start) -================================//

var SettingsField = {
  title: undefined,
  description: undefined,
  tags: undefined,
  userEmail: undefined,
  formEmail: undefined,
  phone: undefined,
  chkbox_nyd: undefined,
  chkbox_stats: undefined,
  chkbox_logs: undefined,
  form_ready: false,
  chkbox_ready: false,
  params: {}
},
    News = {
  titleId: undefined,
  textId: undefined,
  mode: undefined,
  fontSizeTimeout: undefined,
  fontSizeClicks: undefined,
  fontSizeContenteditable: undefined,
  undoCounter: undefined,
  lastSelection: undefined,
  updateFlag: undefined,
  updateId: undefined,
  notificationFunc: undefined,
  deleteFlag: undefined,
  recordsList: undefined,
  savedRecordFlag: undefined,
  searchTimemark: undefined,
  searchText: undefined,
  filterParams: {
    sortBy: 'date',
    sortOrder: 'desc',
    username: '',
    needPublished: true,
    needSaved: true,
    startDate: undefined,
    endDate: undefined
  },
  attachmentMime: undefined,
  attachments: undefined
},
    ProfileWindow = {
  validForm: false,
  changedFields: 0,
  phoneConfirmed: undefined,
  phoneWasConfirmed: undefined,
  emailConfirmed: undefined,
  emailWasConfirmed: undefined,
  data: {},
  passwordChange: false
},
    ProfileIcon = {
  onceOpened: false,
  icons: [],
  icon: undefined
},
    ContactsForm = {
  ready: false,
  city: undefined,
  street: undefined,
  building: undefined,
  office: undefined,
  level: undefined,
  postcode: undefined,
  maplink: undefined,
  worktimeStart: undefined,
  worktimeEnd: undefined,
  rqLA: undefined,
  rqTIN: undefined,
  rqCOR: undefined,
  rqPSRN: undefined,
  phoneArray: [],
  emailArray: [],
  phoneArrayOld: [],
  emailArrayOld: []
};

//==========================- OBJECTS (end) -=================================//
//

/* Function to open fullscreen mode (start)*/
function openFullscreen(a) {
  $(a).attr('onclick','closeFullscreen(this)')
  $(a).find('.main-nav-profile-profile-block-elem-text').text('Режим окна')
  $(a).find('.main-nav-profile-profile-block-elem-ico').attr('class','main-nav-profile-profile-block-elem-ico icon-nofull')
  if (elem.requestFullscreen) {
    elem.requestFullscreen();
  } else if (elem.mozRequestFullScreen) { /* Firefox */
    elem.mozRequestFullScreen();
  } else if (elem.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
    elem.webkitRequestFullscreen();
  } else if (elem.msRequestFullscreen) { /* IE/Edge */
    elem = window.top.document.body; //To break out of frame in IE
    elem.msRequestFullscreen();
  }
}

function closeFullscreen(a) {
  $(a).attr('onclick','openFullscreen(this)')
  $(a).find('.main-nav-profile-profile-block-elem-text').text('На весь экран')
  $(a).find('.main-nav-profile-profile-block-elem-ico').attr('class','main-nav-profile-profile-block-elem-ico icon-full')
  if (document.exitFullscreen) {
    document.exitFullscreen();
  } else if (document.mozCancelFullScreen) {
    document.mozCancelFullScreen();
  } else if (document.webkitExitFullscreen) {
    document.webkitExitFullscreen();
  } else if (document.msExitFullscreen) {
    window.top.document.msExitFullscreen();
  }
}

document.addEventListener("fullscreenchange", function() {
  if(true){
    setTimeout(function(){
      if(window.innerHeight == screen.height) {
        $('#fullscreenBlock').attr('onclick','closeFullscreen(this)')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-text').text('Режим окна')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-ico').attr('class','main-nav-profile-profile-block-elem-ico icon-nofull')
      } else{
        $('#fullscreenBlock').attr('onclick','openFullscreen(this)')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-text').text('На весь экран')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-ico').attr('class','main-nav-profile-profile-block-elem-ico icon-full')
      }
    },20)
  }
});

document.addEventListener("mozfullscreenchange", function() {
  if(true){
    setTimeout(function(){
      if( window.innerHeight == screen.height) {
        $('#fullscreenBlock').attr('onclick','closeFullscreen(this)')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-text').text('Режим окна')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-ico').attr('class','main-nav-profile-profile-block-elem-ico icon-nofull')

      } else{
        $('#fullscreenBlock').attr('onclick','openFullscreen(this)')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-text').text('На весь экран')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-ico').attr('class','main-nav-profile-profile-block-elem-ico icon-full')
      }
    },20)
  }
});

document.addEventListener("webkitfullscreenchange", function() {
  if(true){
    setTimeout(function(){
      if( window.innerHeight == screen.height) {
        $('#fullscreenBlock').attr('onclick','closeFullscreen(this)')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-text').text('Режим окна')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-ico').attr('class','main-nav-profile-profile-block-elem-ico icon-nofull')

      } else{
        $('#fullscreenBlock').attr('onclick','openFullscreen(this)')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-text').text('На весь экран')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-ico').attr('class','main-nav-profile-profile-block-elem-ico icon-full')
      }
    },20)
  }
});

document.addEventListener("msfullscreenchange", function() {
  if(true){
    setTimeout(function(){
      if( window.innerHeight == screen.height) {
        $('#fullscreenBlock').attr('onclick','closeFullscreen(this)')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-text').text('Режим окна')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-ico').attr('class','main-nav-profile-profile-block-elem-ico icon-nofull')

      } else{
        $('#fullscreenBlock').attr('onclick','openFullscreen(this)')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-text').text('На весь экран')
        $('#fullscreenBlock').find('.main-nav-profile-profile-block-elem-ico').attr('class','main-nav-profile-profile-block-elem-ico icon-full')
      }
    },20)
  }
});

/* Function to open fullscreen mode (end)*/

function newsSliderStatistic(a,b) {
  var newsBlockStat = $('#newsSliderStatistic');
  var newsBlockStatCount = $('#newsSliderStatistic').find('.newsStatistic-line-elem');
  var thisBlock = $(b).parent().find('.newsStatistic-line-point');

  for(let i = 0; i < thisBlock.length; i++){
    $(thisBlock[i]).css('background-color','#5d78ff2e')
  }
  $(b).css('background-color','#5d78ff')

  if(a == 1){

    $($('#newsSliderStatistic').children()[0]).css({
      'margin-left':'calc(0px)',
      'opacity':'1',
      'visibility':'visible',
    })
    setTimeout(function(){
      $($('#newsSliderStatistic').children()[1]).css({
        'opacity':'0',
        'visibility':'hidden',
      })
    }, 350)

  } else if(a == 2){

    $($('#newsSliderStatistic').children()[0]).css({
      'margin-left':'calc(-100% - 20px)'
    })
    setTimeout(function(){
      $($('#newsSliderStatistic').children()[0]).css({
        'opacity':'0',
        'visibility':'hidden',
      })
    }, 350)
    $($('#newsSliderStatistic').children()[1]).css({
      'opacity':'1',
      'visibility':'visible',
    })

  }

}

function newsSliderStatistic1(a,b) {
  var newsBlockStat = $('#newsSliderStatistic1');
  var newsBlockStatCount = $('#newsSliderStatistic1').find('.newsStatistic-line-elem');
  var thisBlock = $(b).parent().find('.newsStatistic-line-point');

  for(let i = 0; i < thisBlock.length; i++){
    $(thisBlock[i]).css('background-color','#5d78ff2e')
  }
  $(b).css('background-color','#5d78ff')

  if(a == 1){

    $($('#newsSliderStatistic1').children()[0]).css({
      'margin-left':'calc(0px)',
      'opacity':'1',
      'visibility':'visible',
    })
    setTimeout(function(){
      $($('#newsSliderStatistic1').children()[1]).css({
        'opacity':'0',
        'visibility':'hidden',
      })
    }, 350)

  } else if(a == 2){

    $($('#newsSliderStatistic1').children()[0]).css({
      'margin-left':'calc(-100% - 20px)'
    })
    setTimeout(function(){
      $($('#newsSliderStatistic1').children()[0]).css({
        'opacity':'0',
        'visibility':'hidden',
      })
    }, 350)
    $($('#newsSliderStatistic1').children()[1]).css({
      'opacity':'1',
      'visibility':'visible',
    })

  }

}

function count_chart_test(){
  var tmp = 0;
  for(let i = 1; true; i++){
    if($('body').find('#chart' + i).length != 0){
      tmp += $('body').find('#chart' + i).length;
    } else{
      if(development_state){
        console.log("Charts: " + tmp)
      }
      return tmp;
    }
  }

};

function hashSumm(a){
  $.ajax({
    type: "POST",
    url: "php/console.php",
    data: {
      type: 'hash_key_window'
    },
    cache: false,
    error: function (response) {
      $('#NbPxR-Y6Nr-JoSR').text('Не определено');
      console.error(response);
    },
    beforeSend: function(){
      loader('show');
      $('#NbPxR-Y6Nr-JoSR').text('Определение...');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      $('#NbPxR-Y6Nr-JoSR').text(response);
    },
  }).done();
}

function newConfigPhp(){

  var ConfigPHP = {
    sql_host: $('#X5Cw6-U8Oj-5sBY').val(),
    sql_db:$('#M5NV2-vq2C-m5DI').val(),
    sql_user:$('#OBPYG-upTb-eNNN').val(),
    sql_password:$('#MXFKd-LZKI-ARt6').val(),
    sql_charset:$('#owz1g-PkPx-8Gxn').val(),

    sql_site_host: $('#X5Cw6-U8Oj-5sBY1').val(),
    sql_site_db:$('#M5NV2-vq2C-m5DI1').val(),
    sql_site_user:$('#OBPYG-upTb-eNNN1').val(),
    sql_site_password:$('#MXFKd-LZKI-ARt61').val(),
    sql_site_charset:$('#owz1g-PkPx-8Gxn1').val(),

    serial_Number:$('#zICx3-PoaJ-jqiz').val(),
    phone_service_works:$('#ZyHZF-TgDY-2PGq').val(),
    account_phonenumbers_limit:$('#kuNoH-pxHv-jg6e').val(),
    finder_maximum_volume: $('#vPpIS-LqN7-TjE5').val(),
    account_emails_limit:$('#Aqg9m-0Vq3-TFKt').val(),
    profile_photos_count:$('#vPpIS-LqN7-uneF').val(),
    timezone:$('#vPpIS-LqN7-TFKt').val(),
    root_relative_path:$('#8tMiB-Qi68-DetA').val(),
    trash_path:$('#qO20F-5OlF-d72n').val(),
    users_files_path:$('#rKV3E-vCdZ-AP6d').val(),
    docs_files_path:$('#MuLFZ-4SJe-ihF2').val(),
    books_files_path:$('#MuLFZ-4SJe-4SJe').val(),
    tmp_files_path:$('#MuLFZ-4SJe-MuLFZ').val(),
  }

  $.ajax({
    type: "POST",
    url: "php/config.php",
    data: {
      type: 'notDefault',
      config: JSON.stringify(ConfigPHP)
    },
    cache: false,
    error: function (response) {
      console.error(response);
    },
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      if(response == '200'){
        notification_add('line','','Конфиг изменен успешно!', 7.5);
        notification_add('question','Перезагрузка','Страница будет перезагружена через 3 секунды!', 7.5);
        setTimeout(function(){
          document.location.reload(true);
        }, 3000);
        NowConfigPHP = ConfigPHP;
      } else{
        notification_add('error', 'Ошибка', response, 7.5);
      }
    },
  }).done();

}

function defaultConfigPhp(){
  $.ajax({
    type: "POST",
    url: "php/config.php",
    data: {
      type: 'default'
    },
    cache: false,
    error: function (response) {
      console.error(response);
    },
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      if(response == '200'){
        notification_add('line','','Сброс произошел успешно!', 7.5);
        notification_add('question','Перезагрузка','Страница будет перезагружена через 3 секунды!', 7.5);
        setTimeout(function(){
          document.location.reload(true);
        }, 3000)
        return;
      }
      if(response == '500'){
        notification_add('error','Неизвестная ошибка','Неизвестная ошибка, мы уже работаем над её исправлением!', 7.5)
        return;
      }
      if(response == '404'){
        notification_add('error','Ошибка сброса','Нельзя произвести сброс, настроек по умолчанию!', 7.5)
        return;
      }
    },
  }).done();
}

var NotificationWin = {
  arrayNoti: [],
  arrayNotiId: [],
}
var Mail = {
  arrayNoti: [],
  arrayNotiId: [],
}

function mailWinAdd(parameters){
  // parameters = {
  //   type: 'other',
  //   title: 'Заголовок уведомления',
  //   text: 'Текст сообщения',
  //   ico: 'icon-warning',
  //   color: '#fba'
  // }


  if(parameters.type.match(/^(del|delete|remove)$/ui)){
    let iSum = 10;
    for(let i = 0; i < Mail.arrayNotiId.length; i++){
      let tmpHeight = $('#' + Mail.arrayNotiId[Mail.arrayNotiId.length - 1 - i]).height()
      setTimeout(function(){
        $('#' + Mail.arrayNotiId[Mail.arrayNotiId.length - 1 - i]).css({
          'transform':'translate(-100%, 0px)',
          'height': tmpHeight + 'px',
        });
        setTimeout(function(){
          if(Mail.arrayNotiId.length - 1 - i > 100){
            $('#mail-nav-count').text('100+ ' + declOfNumber(100,'новый'))
          } else{
            $('#mail-nav-count').text((Mail.arrayNotiId.length - 1 - i) + declOfNumber(Mail.arrayNotiId.length - 1 - i,'новый'))
          }
          $('#' + Mail.arrayNotiId[Mail.arrayNotiId.length - 1 - i]).css({
            'padding-top':'0px',
            'padding-bottom':'0px',
            'height':'0px',
            'overflow':'hidden',
            'margin-bottom':'0px'
          });
          setTimeout(function(){
            $('#' + Mail.arrayNotiId[Mail.arrayNotiId.length - 1 - i]).remove();
            if(i + 1 == Mail.arrayNotiId.length){
              Mail = {
                arrayNoti: [],
                arrayNotiId: [],
              }
              open_win('#mail-nav', false);
            }
            if(6 >= Mail.arrayNotiId.length - i){
              $('.main-nav-profile-mail-count').css({
                'visibility':'hidden',
                'opacity':'0'
              })
              $('#notificationPanelNavMail').css({
                'visibility':'hidden',
                'opacity':'0'
              })
              $('#mail-nav-none').css({
                'visibility':'visible',
                'overflow':'hidden',
                'height':'200px',
                'padding-top':'5px',
                'padding-bottom':'5px',
                'margin-bottom':'2px',
                'opacity':'1'
              })
              $('#mail-nav-del').css({
                'visibility':'hidden',
                'opacity':'0'
              });
              $('#mail-nav-count').text('')
              $('#mail-nav-count').css({
                'visibility':'hidden',
                'opacity':'0'
              });
            }
          }, 250)
        }, 20)
      }, 20 + ((i - 0) * 35))




    }
  } else if(parameters.type.match(/^(open)$/ui)){
    var arrayNoti = Mail.arrayNoti;
    var arrayNotiId = Mail.arrayNotiId;
    var tmpIdBlock = $(parameters.this).attr('id');
    var indexElemArray = 0;
    for(let i = 0; i < Mail.arrayNotiId.length; i++){
      if(tmpIdBlock == Mail.arrayNotiId[i]){
        indexElemArray = i;
      }
    }
    Mail.arrayNotiId.splice(indexElemArray, 1);
    Mail.arrayNoti.splice(indexElemArray, 1);

    let tmpHeight = $(parameters.this).height();
    $(parameters.this).css({
      'transform':'translate(100%, 0px)',
      'height': tmpHeight + 'px',
    });
    setTimeout(function(){
      $(parameters.this).css({
        'padding-top':'0px',
        'padding-bottom':'0px',
        'height':'0px',
        'overflow':'hidden',
        'margin-bottom':'0px'
      });
      setTimeout(function(){
        $(parameters.this).remove();
      }, 250)
    }, 10);
    if(arrayNoti.length > 0){
      $('#mail-nav-none').css({
        'visibility':'hidden',
        'overflow':'hidden',
        'height':'0px',
        'padding-top':'0px',
        'padding-bottom':'0px',
        'margin-bottom':'0px',
        'opacity':'0'
      })
      $('#mail-nav-count').css({
        'visibility':'visible',
        'opacity':'1'
      });
      $('#mail-nav-del').css({
        'visibility':'visible',
        'opacity':'1'
      });
      if(arrayNoti.length > 100){
        $('#mail-nav-count').text('100+ ' + declOfNumber(100,'новый'))
      } else{
        $('#mail-nav-count').text(arrayNoti.length + ' ' + declOfNumber(arrayNoti.length,'новый'))
      }
      $('.main-nav-profile-mail-count').css({
        'visibility':'visible',
        'opacity':'1'
      })
      $('#notificationPanelNavMail').css({
        'visibility':'visible',
        'opacity':'1'
      })
    } else{
      $('.main-nav-profile-mail-count').css({
        'visibility':'hidden',
        'opacity':'0'
      })
      $('#notificationPanelNavMail').css({
        'visibility':'hidden',
        'opacity':'0'
      })
      $('#mail-nav-none').css({
        'visibility':'visible',
        'overflow':'hidden',
        'height':'200px',
        'padding-top':'5px',
        'padding-bottom':'5px',
        'margin-bottom':'2px',
        'opacity':'1'
      })
      $('#mail-nav-count').text('')
      $('#mail-nav-count').css({
        'visibility':'hidden',
        'opacity':'0'
      });
      $('#mail-nav-del').css({
        'visibility':'hidden',
        'opacity':'0'
      });
    }
  } else{
    var arrayNoti = Mail.arrayNoti;
    var arrayNotiId = Mail.arrayNotiId;
    var tmpArrayNotiId = stringGenerator(35,5);

    output = '';
    parameters.function = parameters.function + '; mailWinAdd({type: &quot;open&quot;, this: this});';

    if(parameters.type == 'generalChat'){
      output += '<div style="transform: translate(-100%, 0px); max-height: 0px; margin-bottom: 2px; padding-bottom: 0px; padding-top: 0px;" class="main-nav-profile-mail-block-main-elem" onclick="' + parameters.function + '" id="' + tmpArrayNotiId + '">';
      output += '<div class="main-nav-profile-mail-block-main-elem-circle icon-msg2" style="background-color: #0abb87;"></div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text">';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-name">' + parameters.title + '</div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-msg">' + parameters.text + '</div>';
      output += '</div>';
      output += '</div>';
    }
    else if(parameters.type == 'msg'){
      output += '<div style="transform: translate(-100%, 0px); max-height: 0px; margin-bottom: 2px; padding-bottom: 0px; padding-top: 0px;" class="main-nav-profile-mail-block-main-elem" onclick="' + parameters.function + '" id="' + tmpArrayNotiId + '">';
      output += '<div class="main-nav-profile-mail-block-main-elem-circle icon-msg" style="background-color: #fd397a;"></div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text">';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-name">' + parameters.title + '</div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-msg">' + parameters.text + '</div>';
      output += '</div>';
      output += '</div>';
    }
    else if(parameters.type == 'support'){
      output += '<div style="transform: translate(-100%, 0px); max-height: 0px; margin-bottom: 2px; padding-bottom: 0px; padding-top: 0px;" class="main-nav-profile-mail-block-main-elem" onclick="' + parameters.function + '" id="' + tmpArrayNotiId + '">';
      output += '<div class="main-nav-profile-mail-block-main-elem-circle icon-support" style="background-color: #5d78ff;"></div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text">';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-name">' + parameters.title + '</div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-msg">' + parameters.text + '</div>';
      output += '</div>';
      output += '</div>';
    }
    else if(parameters.type == 'other'){
      output += '<div style="transform: translate(-100%, 0px); max-height: 0px; margin-bottom: 2px; padding-bottom: 0px; padding-top: 0px;" class="main-nav-profile-mail-block-main-elem" onclick="' + parameters.function + '" id="' + tmpArrayNotiId + '">';
      output += '<div class="main-nav-profile-mail-block-main-elem-circle ' + parameters.ico + '" style="background-color: ' + parameters.color + ';"></div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text">';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-name">' + parameters.title + '</div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-msg">' + parameters.text + '</div>';
      output += '</div>';
      output += '</div>';
    }
    else{
      console.error('Ошибка в типе!');
      return;
    }

    $('#main-nav-profile-mail-block-main2').prepend(output);
    setTimeout(function(){
      $('#' + tmpArrayNotiId).css({
        'transform':'translate(0px,0px)',
        'max-height':'90px',
        'padding-top':'5px',
        'margin-bottom':'2px',
        'padding-bottom':'5px',
      })
    }, 10)
    arrayNotiId.push(tmpArrayNotiId);
    arrayNoti.push(output);
    open_win('#mail-nav', false);

    if(arrayNoti.length > 0){
      $('#mail-nav-none').css({
        'visibility':'hidden',
        'overflow':'hidden',
        'height':'0px',
        'padding-top':'0px',
        'padding-bottom':'0px',
        'margin-bottom':'0px',
        'opacity':'0'
      })
      $('#mail-nav-count').css({
        'visibility':'visible',
        'opacity':'1'
      });
      $('#mail-nav-del').css({
        'visibility':'visible',
        'opacity':'1'
      });
      if(arrayNoti.length > 100){
        $('#mail-nav-count').text('100+ ' + declOfNumber(100,'новый'))
      } else{
        $('#mail-nav-count').text(arrayNoti.length + ' ' + declOfNumber(arrayNoti.length,'новый'))
      }
      $('.main-nav-profile-mail-count').css({
        'visibility':'visible',
        'opacity':'1'
      })
      $('#notificationPanelNavMail').css({
        'visibility':'visible',
        'opacity':'1'
      })
    } else{
      $('.main-nav-profile-mail-count').css({
        'visibility':'hidden',
        'opacity':'0'
      })
      $('#notificationPanelNavMail').css({
        'visibility':'hidden',
        'opacity':'0'
      })
      $('#mail-nav-none').css({
        'visibility':'visible',
        'overflow':'hidden',
        'height':'200px',
        'padding-top':'5px',
        'padding-bottom':'5px',
        'margin-bottom':'2px',
        'opacity':'1'
      })
      $('#mail-nav-count').text('')
      $('#mail-nav-count').css({
        'visibility':'hidden',
        'opacity':'0'
      });
      $('#mail-nav-del').css({
        'visibility':'hidden',
        'opacity':'0'
      });
    }
  }

}

function notificationWinAdd(parameters){
  // parameters = {
  //   type: 'other',
  //   title: 'Заголовок уведомления',
  //   text: 'Текст сообщения',
  //   ico: 'icon-warning',
  //   color: '#fba',
  //   function: 'test();',
  //   this: this
  // }


  if(parameters.type.match(/^(del|delete|remove)$/ui)){

    let iSum = 10;
    for(let i = 0; i < NotificationWin.arrayNotiId.length; i++){
      let tmpHeight = $('#' + NotificationWin.arrayNotiId[NotificationWin.arrayNotiId.length - 1 - i]).height()
      setTimeout(function(){
        $('#' + NotificationWin.arrayNotiId[NotificationWin.arrayNotiId.length - 1 - i]).css({
          'transform':'translate(-100%, 0px)',
          'height': tmpHeight + 'px',
        });
        setTimeout(function(){
          if(NotificationWin.arrayNotiId.length - 1 - i > 100){
            $('#noti-nav-count').text('100+ ' + declOfNumber(100,'новый'))
          } else{
            $('#noti-nav-count').text((NotificationWin.arrayNotiId.length - 1 - i) + declOfNumber(NotificationWin.arrayNotiId.length - 1 - i,'новый'))
          }
          $('#' + NotificationWin.arrayNotiId[NotificationWin.arrayNotiId.length - 1 - i]).css({
            'padding-top':'0px',
            'padding-bottom':'0px',
            'height':'0px',
            'overflow':'hidden',
            'margin-bottom':'0px'
          });
          setTimeout(function(){
            $('#' + NotificationWin.arrayNotiId[NotificationWin.arrayNotiId.length - 1 - i]).remove();
            if(i + 1 == NotificationWin.arrayNotiId.length){
              NotificationWin = {
                arrayNoti: [],
                arrayNotiId: [],
              }
              open_win('#noti-nav', false);
            }
            if(6 >= NotificationWin.arrayNotiId.length - i){
              $('.main-nav-profile-notification-count').css({
                'visibility':'hidden',
                'opacity':'0'
              })
              $('#notificationPanelNavNoti').css({
                'visibility':'hidden',
                'opacity':'0'
              })
              $('#noti-nav-none').css({
                'visibility':'visible',
                'overflow':'hidden',
                'height':'220px',
                'padding-top':'5px',
                'padding-bottom':'5px',
                'margin-bottom':'2px',
                'opacity':'1'
              })
              $('#noti-nav-del').css({
                'visibility':'hidden',
                'opacity':'0'
              });
              $('#noti-nav-count').text('')
              $('#noti-nav-count').css({
                'visibility':'hidden',
                'opacity':'0'
              });
            }
          }, 250)
        }, 20)
      }, 20 + ((i - 0) * 35))




    }

    // for(let i = 0; i < NotificationWin.arrayNotiId.length; i++){
    //   $('#' + NotificationWin.arrayNotiId[i]).remove();
    // }
    // open_win('#noti-nav', false);
    // $('.main-nav-profile-notification-count').css({
    //   'visibility':'hidden',
    //   'opacity':'0'
    // })
    // $('#noti-nav-none').css({
    //   'display':'block'
    // })
    // $('#noti-nav-del').css({
    //   'visibility':'hidden',
    //   'opacity':'0'
    // });
    // $('#noti-nav-count').text('')
    // $('#noti-nav-count').css({
    //   'visibility':'hidden',
    //   'opacity':'0'
    // });
    // NotificationWin = {
    //   arrayNoti: [],
    //   arrayNotiId: [],
    // }
  }
  else if(parameters.type.match(/^(open)$/ui)){
    var arrayNoti = NotificationWin.arrayNoti;
    var arrayNotiId = NotificationWin.arrayNotiId;
    var tmpIdBlock = $(parameters.this).attr('id');
    var indexElemArray = 0;
    for(let i = 0; i < NotificationWin.arrayNotiId.length; i++){
      if(tmpIdBlock == NotificationWin.arrayNotiId[i]){
        indexElemArray = i;
      }
    }
    NotificationWin.arrayNotiId.splice(indexElemArray, 1);
    NotificationWin.arrayNoti.splice(indexElemArray, 1);
    let tmpHeight = $(parameters.this).height();
    $(parameters.this).css({
      'transform':'translate(100%, 0px)',
      'height': tmpHeight + 'px',
    });
    setTimeout(function(){
      $(parameters.this).css({
        'padding-top':'0px',
        'padding-bottom':'0px',
        'height':'0px',
        'overflow':'hidden',
        'margin-bottom':'0px'
      });
      setTimeout(function(){
        $(parameters.this).remove();
      }, 250)
    }, 10);
    if(arrayNoti.length > 0){
      $('#noti-nav-none').css({
        'visibility':'hidden',
        'overflow':'hidden',
        'height':'0px',
        'padding-top':'0px',
        'padding-bottom':'0px',
        'margin-bottom':'0px',
        'opacity':'0'
      })
      $('#noti-nav-count').css({
        'visibility':'visible',
        'opacity':'1'
      });
      $('#noti-nav-del').css({
        'visibility':'visible',
        'opacity':'1'
      });
      if(arrayNoti.length > 100){
        $('#noti-nav-count').text('100+ ' + declOfNumber(100,'новый'))
      } else{
        $('#noti-nav-count').text(arrayNoti.length + ' ' + declOfNumber(arrayNoti.length,'новый'))
      }
      $('.main-nav-profile-notification-count').css({
        'visibility':'visible',
        'opacity':'1'
      })
      $('#notificationPanelNavNoti').css({
        'visibility':'visible',
        'opacity':'1'
      })
    } else{
      $('.main-nav-profile-notification-count').css({
        'visibility':'hidden',
        'opacity':'0'
      })
      $('#notificationPanelNavNoti').css({
        'visibility':'hidden',
        'opacity':'0'
      })
      $('#noti-nav-none').css({
        'visibility':'visible',
        'overflow':'hidden',
        'height':'220px',
        'padding-top':'5px',
        'padding-bottom':'5px',
        'margin-bottom':'2px',
        'opacity':'1'
      })
      $('#noti-nav-count').text('')
      $('#noti-nav-count').css({
        'visibility':'hidden',
        'opacity':'0'
      });
      $('#noti-nav-del').css({
        'visibility':'hidden',
        'opacity':'0'
      });
    }
  }
  else{
    var arrayNoti = NotificationWin.arrayNoti;
    var arrayNotiId = NotificationWin.arrayNotiId;
    var tmpArrayNotiId = stringGenerator(35,5);

    output = '';
    parameters.function = parameters.function + '; notificationWinAdd({type: &quot;open&quot;, this: this});'

    if(parameters.type == 'security'){
      output += '<div style="transform: translate(-100%, 0px); max-height: 0px; margin-bottom: 2px; padding-bottom: 0px; padding-top: 0px;" class="main-nav-profile-mail-block-main-elem" onclick="' + parameters.function + '" id="' + tmpArrayNotiId + '">';
      output += '<div class="main-nav-profile-mail-block-main-elem-circle icon-shield" style="background-color: #cb2222;"></div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text">';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-name">' + parameters.title + '</div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-msg">' + parameters.text + '</div>';
      output += '</div>';
      output += '</div>';
    }
    else if(parameters.type == 'article'){
      output += '<div style="transform: translate(-100%, 0px); max-height: 0px; margin-bottom: 2px; padding-bottom: 0px; padding-top: 0px;" class="main-nav-profile-mail-block-main-elem" onclick="' + parameters.function + '" id="' + tmpArrayNotiId + '">';
      output += '<div class="main-nav-profile-mail-block-main-elem-circle icon-fast" style="background-color: #7f36dc;"></div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text">';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-name">' + parameters.title + '</div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-msg">' + parameters.text + '</div>';
      output += '</div>';
      output += '</div>';
    }
    else if(parameters.type == 'music'){
      output += "<div class='main-nav-profile-mail-block-main-elem1' title='" + parameters.title + "'>";
      output += "<div class='main-nav-profile-mail-block-main-elem-circleImage'>";
      output += "<div class='main-nav-profile-mail-block-main-elem-circleImage-disk'>";
      output += "<div class='main-nav-profile-mail-block-main-elem-circleImage-disk-none'></div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-circleImage-disk-image'></div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-circleImage-disk-border'></div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-circleImage-disk-border' style='height: 60%; width: 60%;'></div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-circleImage-disk-border' style='height: 40%; width: 40%;'></div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-circleImage-disk-border' style='height: 20%; width: 20%;'></div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-circleImage-disk-line'></div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-circleImage-disk-line' style='transform: rotate(-105deg); background-color: #e6d06a;'></div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-circleImage-disk-line' style='transform: rotate(120deg); background-color: #e6d06a;'></div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-circleImage-disk-line' style='transform: rotate(-62deg); background-color: #e6d06a;'></div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-circleImage-disk-line' style='transform: rotate(190deg); background-color: #d3ae6f;'></div>";
      output += "</div>";
      output += "</div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-text2'>";
      output += "<div class='main-nav-profile-mail-block-main-elem-text-name'>Название файла</div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-text-progressbar'>";
      output += "<div class='main-nav-profile-mail-block-main-elem-text-progressbar-line' style='width: 100%;' value='02:12'></div>";
      output += "</div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-text-btn'>";
      output += "<div class='main-nav-profile-mail-block-main-elem-text-btn-elem icon-reload' title='Повтор'></div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-text-btn-elem icon-forward' title='Предыдущая'></div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-text-btn-elem icon-play' title='Запустить'></div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-text-btn-elem icon-next' title='Следующая'></div>";
      output += "<div class='main-nav-profile-mail-block-main-elem-text-btn-elem icon-volume2' title='Громкость'></div>";
      output += "</div>";
      output += "</div>";
      output += "</div>";
    }
    else if(parameters.type == 'like'){
      output += '<div style="transform: translate(-100%, 0px); max-height: 0px; margin-bottom: 2px; padding-bottom: 0px; padding-top: 0px;" class="main-nav-profile-mail-block-main-elem" onclick="' + parameters.function + '" id="' + tmpArrayNotiId + '">';
      output += '<div class="main-nav-profile-mail-block-main-elem-circle icon-heart"></div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text">';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-name">' + parameters.title + '</div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-msg">' + parameters.text + '</div>';
      output += '</div>';
      output += '</div>';
    }
    else if(parameters.type == 'other'){
      output += '<div style="transform: translate(-100%, 0px); max-height: 0px; margin-bottom: 2px; padding-bottom: 0px; padding-top: 0px;" class="main-nav-profile-mail-block-main-elem" onclick="' + parameters.function + '" id="' + tmpArrayNotiId + '">';
      output += '<div class="main-nav-profile-mail-block-main-elem-circle ' + parameters.ico + '" style="background-color: ' + parameters.color + ';"></div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text">';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-name">' + parameters.title + '</div>';
      output += '<div class="main-nav-profile-mail-block-main-elem-text-msg">' + parameters.text + '</div>';
      output += '</div>';
      output += '</div>';
    }
    else{
      console.error('Ошибка в типе!');
      return;
    }

    $('#main-nav-profile-mail-block-main1').prepend(output);
    setTimeout(function(){
      $('#' + tmpArrayNotiId).css({
        'transform':'translate(0px,0px)',
        'max-height':'90px',
        'padding-top':'5px',
        'margin-bottom':'2px',
        'padding-bottom':'5px',
      })
    }, 10)
    arrayNotiId.push(tmpArrayNotiId);
    arrayNoti.push(output);
    open_win('#noti-nav', false);

    if(arrayNoti.length > 0){
      $('#noti-nav-none').css({
        'visibility':'hidden',
        'overflow':'hidden',
        'height':'0px',
        'padding-top':'0px',
        'padding-bottom':'0px',
        'margin-bottom':'0px',
        'opacity':'0'
      })
      $('#noti-nav-count').css({
        'visibility':'visible',
        'opacity':'1'
      });
      $('#noti-nav-del').css({
        'visibility':'visible',
        'opacity':'1'
      });
      if(arrayNoti.length > 100){
        $('#noti-nav-count').text('100+ ' + declOfNumber(100,'новый'))
      } else{
        $('#noti-nav-count').text(arrayNoti.length + ' ' + declOfNumber(arrayNoti.length,'новый'))
      }
      $('.main-nav-profile-notification-count').css({
        'visibility':'visible',
        'opacity':'1'
      })
      $('#notificationPanelNavNoti').css({
        'visibility':'visible',
        'opacity':'1'
      })
    } else{
      $('.main-nav-profile-notification-count').css({
        'visibility':'hidden',
        'opacity':'0'
      })
      $('#notificationPanelNavNoti').css({
        'visibility':'hidden',
        'opacity':'0'
      })
      $('#noti-nav-none').css({
        'visibility':'visible',
        'overflow':'hidden',
        'height':'220px',
        'padding-top':'5px',
        'padding-bottom':'5px',
        'margin-bottom':'2px',
        'opacity':'1'
      })
      $('#noti-nav-count').text('')
      $('#noti-nav-count').css({
        'visibility':'hidden',
        'opacity':'0'
      });
      $('#noti-nav-del').css({
        'visibility':'hidden',
        'opacity':'0'
      });
    }
  }

}

$(document).ready(function(){

  // функция плавного открытия меню по свайпу
  var maxblockSwipe = $('body').width() / 3;
  swipeMove(30, '#mainResize', maxblockSwipe, 'right', 'nav_open');

  // функция плавного закрытия меню по свайпу
  swipeMove(30, 'nav', 0, 'left', 'nav_open');

  swipe(50, '.panel-conteiner-width-small3', 0, 'right', "change_type_chart3('.panel-conteiner-width-small3-type-elem','bottom','#main-stat-gfh4sdt3')");
  swipe(50, '.panel-conteiner-width-small3', 0, 'left', "change_type_chart3('.panel-conteiner-width-small3-type-elem','top','#main-stat-gf75ddf')");
});

$(document).ready(function(){

  $("#X5Cw6-U8Oj-5sBY").on("input",function() {
    if($(this).val() != NowConfigPHP.sql_host){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#M5NV2-vq2C-m5DI").on("input",function() {
    if($(this).val() != NowConfigPHP.sql_db){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#OBPYG-upTb-eNNN").on("input",function() {
    if($(this).val() != NowConfigPHP.sql_user){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#MXFKd-LZKI-ARt6").on("input",function() {
    if($(this).val() != NowConfigPHP.sql_password){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#owz1g-PkPx-8Gxn").on("input",function() {
    if($(this).val() != NowConfigPHP.sql_charset){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#X5Cw6-U8Oj-5sBY1").on("input",function() {
    if($(this).val() != NowConfigPHP.sql_site_host){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#M5NV2-vq2C-m5DI1").on("input",function() {
    if($(this).val() != NowConfigPHP.sql_site_db){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#OBPYG-upTb-eNNN1").on("input",function() {
    if($(this).val() != NowConfigPHP.sql_site_user){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#MXFKd-LZKI-ARt61").on("input",function() {
    if($(this).val() != NowConfigPHP.sql_site_password){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#owz1g-PkPx-8Gxn1").on("input",function() {
    if($(this).val() != NowConfigPHP.sql_site_charset){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#zICx3-PoaJ-jqiz").on("input",function() {
    if($(this).val() != NowConfigPHP.serial_Number){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#ZyHZF-TgDY-2PGq").on("input",function() {
    if($(this).val() != NowConfigPHP.phone_service_works){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#kuNoH-pxHv-jg6e").on("input",function() {
    if($(this).val() != NowConfigPHP.account_phonenumbers_limit){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#vPpIS-LqN7-TjE5").on("input",function() {
    if($(this).val() != NowConfigPHP.finder_maximum_volume){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#Aqg9m-0Vq3-TFKt").on("input",function() {
    if($(this).val() != NowConfigPHP.account_emails_limit){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#vPpIS-LqN7-uneF").on("input",function() {
    if($(this).val() != NowConfigPHP.profile_photos_count){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#vPpIS-LqN7-TFKt").on("input",function() {
    if($(this).val() != NowConfigPHP.timezone){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#8tMiB-Qi68-DetA").on("input",function() {
    if($(this).val() != NowConfigPHP.root_relative_path){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#qO20F-5OlF-d72n").on("input",function() {
    if($(this).val() != NowConfigPHP.trash_path){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#rKV3E-vCdZ-AP6d").on("input",function() {
    if($(this).val() != NowConfigPHP.users_files_path){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#MuLFZ-4SJe-ihF2").on("input",function() {
    if($(this).val() != NowConfigPHP.docs_files_path){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#MuLFZ-4SJe-4SJe").on("input",function() {
    if($(this).val() != NowConfigPHP.books_files_path){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });
  $("#MuLFZ-4SJe-MuLFZ").on("input",function() {
    if($(this).val() != NowConfigPHP.tmp_files_path){
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('onclick','newConfigPhp()');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#vPpIS-LqN7-une2').removeAttr('class');
      $('#vPpIS-LqN7-une2').attr('class','window-block-conteiner-left-btn-none');
      $('#vPpIS-LqN7-une2').removeAttr('onclick');
    }
  });

  if($('#ch4').length == 1){
    if(!Config.newYearPanel){
      $("#ch4").on("change",function() {
        if($("#ch4").prop('checked')){
          open_window('#sale-newYear','sale');
          setTimeout(function(){
            $("#ch4").prop('checked', false)
            localWinter = false;
          }, 1);
        }
      });
    }
  }

  $('#develop-false').on('change', function(){
    var devchecked = $(this).prop('checked');
    if(devchecked){
      $.cookie('development','true',{expires: 99999});
    } else{
      $.cookie('development','false',{expires: 99999});
      notification_add('question','Для разработчиков','Изменения вступят в силу после перезагрузки страницы!', 7.5)
    }
  });


  if(userData['access'] != 'default'){
    $('#serialNumber').click(function(){
      if($.cookie('development') == 'true'){
        notification_add('line','','Вы уже разработчик!', 7.5)
      } else{
        if(developmentCount >= 5){
          notification_add('question','Для разработчиков','Вы активировали режим разработчика, страница будет перезагружена через 3 секунды!', 7.5)
          $.cookie('development','true',{expires: 99999});
          updateURL('?dev=true');
          setTimeout(function(){
            document.location.reload(true);
          }, 3000)
        } else{
          if(developmentCount >= 4){
            notification_add('line','','Осталось еще ' + (5 - developmentCount) + ' шаг', 7.5)
            developmentCount++;
          }
          else if(developmentCount >= 2){
            notification_add('line','','Осталось еще ' + (5 - developmentCount) + ' шага', 7.5)
            developmentCount++;
          } else{
            developmentCount++;
          }

        }
      }
    });
  }

  if(userData['access'] != 'default'){
    $('#chDevWindow').on('change', function(e){
      if($(this).prop('checked')){
        $.cookie('development_help','false',{expires: 99999});
      } else{
        $.cookie('development_help','true',{expires: 99999});
      }
    });
  }



  $('.window-block-elem').click(function(){
    var count_window = $('window').find('.window-block-elem')
    for(let i = 0; i < count_window.length; i++){
      $(count_window[i]).css('z-index','1')
      $(count_window[i]).css('box-shadow','0px 0px 12px -4px rgba(0,0,0,0.18)')
    }
    $(this).css('z-index','99')
    $(this).css('box-shadow','0px 0px 12px -4px rgba(0,0,0,0.44)')
  });
});

$(document).ready(function() {

  setInterval(loader_text, 350);

  $('#searchFilter223').on('change', function() {
    var date = $('#searchFilter223').val();
    if(date.match(/(\d{4})-(\d{2})-(\d{2})/g)) {
      tasksFilterDate = date;
    }
    else {
      tasksFilterDate = 'none';
    }
    tasksUpdateList();
  });
});

$(document).ready(function() {
  contactsReload();
});

$(document).ready(function() {
  tasksLoadList();
  currentTaskDay = new Date().toLocaleDateString();
});

// choose file
$(document).ready(function() {
  $('#upload_file_profile_image').on('change', function() {
    uploadProfileIcon();
  });
});

$(document).ready(function(){

  $('input[type="tel"]').inputmask("+7 (999) 999-99-99");
  $('input[type="mail"]').inputmask({
    mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
    greedy: false,
    onBeforePaste: function (pastedValue, opts) {
      pastedValue = pastedValue.toLowerCase();
      return pastedValue.replace("mailto:", "");
    },
    definitions: {
      '*': {
        validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
        cardinality: 1,
        casing: "lower"
      }
    }
  });

  // open_panel('#statistics');

  $('html').keydown(function(eventObject){
    var blockWindow = $('.window').find('.window-zindex');
    for(let i = 0; i < blockWindow.length; i++){
      if($(blockWindow[i]).attr('id') != 'settings' || $(blockWindow[i]).attr('id') != 'hello'){
        if($(blockWindow[i]).css('display') == 'block'){
          if(event.keyCode == 27){
            close_window('.close_window');
          }
        }
      }
    }
  });

  /*$('.panel-msg-block-text-del').click(function(){
    var blockInput = $(this).parent().find('input').prop('checked'),
        block = $(this);

    if(blockInput){
      block.find('.panel-news-block-img-ch-line1').css({'width':'11px','transform':'translate(6px, 19px) rotate(45deg)','opacity':'1'})
      setTimeout(function(){
        block.find('.panel-news-block-img-ch-line2').css({'width':'20px','transform':'translate(14px, 18px) rotate(-45deg)','opacity':'1'})
      }, 80)
    } else{
      block.find('.panel-news-block-img-ch-line2').css({'width':'0px','transform':'translate(14px, 18px) rotate(-45deg)','opacity':'1'})
      setTimeout(function(){
        block.find('.panel-news-block-img-ch-line1').css({'width':'0px','transform':'translate(6px, 19px) rotate(45deg)','opacity':'1'})
      }, 80)
    }
  });*/

  count_chart = count_chart_test();
  time_analog();
  setInterval(function() {
    time_analog();
  }, 1);
  $('.window-block-main-lock').click(function(){
    $('.window-block-main-lock').css('color','#fd3939')
    setTimeout(function(){
      $('.window-block-lock-upper').css('transition','0.2s all')
      $('.window-block-lock-lower').css('transition','0.2s all')
      $('.window-block-lock').css('animation','error 0.35s infinite')
      setTimeout(function(){
        $('.window-block-lock').css('animation','initial')
        setTimeout(function(){
          $('.window-block-lock-lower').css('transition','0s all')
          $('.window-block-main-lock').css('color','var(--color)')
          $('.window-block-lock-upper').css('transition','0.3s all cubic-bezier(0.64, 0.66, 0.15, 1)')
        }, 50)
      }, 350)
    }, 50)
  });


  if(development_state){
    console.log('это android? - ' + isAndroid);
  }

  if(document.documentElement.clientWidth <= 835){
    screenUser('small');
  }else if(document.documentElement.clientWidth > 835 && document.documentElement.clientWidth < 1183){
    screenUser('medium');
  } else {
    screenUser('high');
  }

  res(0,0,0,0);

  $(window).resize(function(){
      if(document.documentElement.clientWidth <= 835){
        NavStat = false;
        screenUser('small');
      }else if(document.documentElement.clientWidth > 835 && document.documentElement.clientWidth < 1183){
        NavStat = false;
        screenUser('medium');
      } else {
        NavStat = false;
        screenUser('high');
      }
  });

  if (navigator.userAgent.search(/MSIE/) > 0){
    browser = 'Internet Explorer or Edge';
    $('#fullscreenBlock').remove();
  }


  $('input[type="key"]').mask("9-999-999")

  $("nav").scroll(function(){
    $('.logo').css({top: -$("nav").scrollTop()/4})
  });

  GlobalName = $('#GlobalName').val();
  GlobalDescription = $('#GlobalDescription').val();
  GlobalEmailMain = $('#GlobalEmailMain').val();
  GlobalEmailForm = $('#GlobalEmailForm').val();
  GlobalTel = $('#GlobalTel').val();
  localName = GlobalName;
  localDescription = GlobalDescription;
  localEmailMain = GlobalEmailMain;
  localEmailForm = GlobalEmailForm;
  localTel = GlobalTel;

  var root12 = document.querySelector('html');
  var rootStyles12 = getComputedStyle(root12);
  var mainColor12 = rootStyles12.getPropertyValue('--color');


  if($('#ch1').prop("checked")){
    GlobalTheme = 'black';
    localTheme = 'black';
    // updateChartsNew('dark');

    for(let i = 1; i <= count_chart; i++){
      window['chart' + i + '_params'].tooltip.theme = 'dark';
    }
    chart3_1_params.tooltip.theme = 'dark';
  } else{
    GlobalTheme = 'white';
    localTheme = 'white';

    for(let i = 1; i <= count_chart; i++){
      window['chart' + i + '_params'].tooltip.theme = 'light';
    }
    chart3_1_params.tooltip.theme = 'light';
  }

  GlobalLang = $("#LangPanel").val();
  localLang = $("#LangPanel").val();
  $("#LangPanel").change(langPanelF);

  if($('#ch2').prop("checked")){
    GlobalStat = true;
    localStat = true;
  } else{
    GlobalStat = false;
    localStat = false;
  }

  if($('#ch3').prop("checked")){
    GlobalError = true;
    localError = true;
  } else{
    GlobalError = false;
    localError = false;
  }

  if($('#ch4').prop("checked")){
    GlobalWinter = true;
    localWinter = true;
  } else{
    GlobalWinter = false;
    localWinter = false;
  }

  if($('#ch5').prop("checked")){
    GlobalMsg = true;
    localMsg = true;
  } else{
    GlobalMsg = false;
    localMsg = false;
  }

  if($('#ch6').prop("checked")){
    GlobalNoti = true;
    localNoti = true;
  } else{
    GlobalNoti = false;
    localNoti = false;
  }

  $(document).mouseup(function (e){ // событие клика по веб-документу
		var div = $("#noti-nav"),
        div1 = $(".menu-elem-btn-more-block-not"),
        div2 = $("#mail-nav"),
        div3 = $("#msgSettings1"),
        div4 = $(".emoji-block"),
        div5 = $("#msgSettings2"),
        div6 = $("#profile-nav"),
        div7 = $("#finderSettings");

		if (!div.is(e.target) && div.has(e.target).length === 0 && !$('.main-nav-profile-notification').is(e.target) && $('.main-nav-profile-notification').has(e.target).length === 0 && !$('.main-nav-profile-profile-block').is(e.target) && $('.main-nav-profile-profile-block').has(e.target).length === 0) {
      div.css('opacity','0')
      if(document.documentElement.clientWidth <= 835){
        div.css('transform','translate(calc(0px),15px)')
      } else{
        div.css('transform','translate(calc(-50% + 20px),15px)')
      }
      setTimeout(function(){
        div.css('display','none')
      },200)
		}

    if (!div1.is(e.target) && div1.has(e.target).length === 0 && !$('.menu').is(e.target) && $('.menu').has(e.target).length === 0) {
      div1.css('opacity','0')
      div1.css('transform','translate(0px, 15px)')
      setTimeout(function(){
        div1.css('display','none')
      },200)
		}

    if (!div2.is(e.target) && div2.has(e.target).length === 0 && !$('.main-nav-profile-mail').is(e.target) && $('.main-nav-profile-mail').has(e.target).length === 0 && !$('.main-nav-profile-profile-block').is(e.target) && $('.main-nav-profile-profile-block').has(e.target).length === 0) {
      div2.css('opacity','0')
      if(document.documentElement.clientWidth <= 835){
        div2.css('transform','translate(calc(0px),15px)')
      } else{
        div2.css('transform','translate(calc(-50% + 20px),15px)')
      }
      setTimeout(function(){
        div2.css('display','none')
      },200)
		}

    if (!div3.is(e.target) && div3.has(e.target).length === 0 && !$('.panel-user-ab-btn-1').is(e.target) && $('.panel-user-ab-btn-1').has(e.target).length === 0) {
      div3.css('opacity','0')
      div3.css('transform','scale(1.25) translate(calc(-50% + 20px),15px)')
      setTimeout(function(){
        div3.css('display','none')
      },200)
		}

    if (!div4.is(e.target) && div4.has(e.target).length === 0 && !$('.panel-msg-block-msg-textinput-textarea-emoji').is(e.target) && $('.panel-msg-block-msg-textinput-textarea-emoji').has(e.target).length === 0) {
      div4.css('opacity','0')
      div4.css('transform','translate(calc(-50% + 20px),15px)')
      setTimeout(function(){
        div4.css('display','none')
      },200)
		}

    if (!div5.is(e.target) && div5.has(e.target).length === 0 && !$('.panel-user-ab-btn-1').is(e.target) && $('.panel-user-ab-btn-1').has(e.target).length === 0) {
      div5.css('opacity','0')
      div5.css('transform','scale(1.25) translate(calc(-50% + 20px),15px)')
      setTimeout(function(){
        div5.css('display','none')
      },200)
    }

    if (!div6.is(e.target) && div6.has(e.target).length === 0 && !$('.main-nav-profile-profile').is(e.target) && $('.main-nav-profile-profile').has(e.target).length === 0) {
      div6.css('opacity','0')

      if(document.documentElement.clientWidth <= 410){
        div6.css('transform','translate(80px,0px)')
      } else{
        div6.css('transform','translate(0px,15px)')
      }
      setTimeout(function(){
        div6.css('display','none')
      },200)
    }

    if (!div7.is(e.target) && div7.has(e.target).length === 0 && !$('#file_manager-action-btn').is(e.target) && $('#file_manager-action-btn').has(e.target).length === 0) {
      div7.css('opacity','0')
      div7.css('transform','translate(calc(50% - 10px),15px)')
      setTimeout(function(){
        div7.css('display','none')
      },200)
    }

	});

});

$(document).ready(function() {

  // save settings form
  function saveValues() {

    //console.log('ok');

    // get current values
    /*var title = $('#GlobalName').val();
    var description = $('#GlobalDescription').text();
    var userEmail = $('#GlobalEmailMain').val();
    var formEmail = $('#GlobalEmailForm').val();
    var phonenumber = $('#GlobalTel').val().replace(/[^0-9]/gim, '');
    var newYearDesign = undefined;
    if(document.getElementById('ch4')) {
      newYearDesign = document.getElementById('ch4').checked;
    }
    var collectStat = document.getElementById('ch2').checked;
    var errorLogs = document.getElementById('ch3').checked;


    var params = { ssets: 'true', stat: collectStat, logs: errorLogs };

    if((title != undefined) && (title != '')) {
      params['title'] = title;
    }
    if((description != undefined) && (description != '')) {
      params['desc'] = description;
    }
    if(userEmail != GlobalEmailMain) {
      params['uemail'] = userEmail;
    }
    if((formEmail != GlobalEmailForm) && (formEmail != undefined) && (formEmail != '')) {
      params['femail'] = formEmail;
    }
    if(phonenumber != GlobalTel.replace(/[^0-9]/gim, '')) {
      params['phone'] = phonenumber;
    }
    if(newYearDesign != undefined) {
      params['nyd'] = newYearDesign;
    }

    console.log(params);


    // query to server
    $.post('db_profile.php', params).done(function(data) {
      if(data == 'OK.') {
        close_window('#General_settings');
      }
      else {
        close_window('#General_settings')
        console.log('response: ' + data);
      }
    });*/

    //console.log(SettingsField.params);

    settingsFormCheck();

    if(SettingsField.form_ready) {
      if(typeof SettingsField.title !== 'undefined') {
        localName = SettingsField.title.pres;
      }
      if(typeof SettingsField.description !== 'undefined') {
        localDescription = SettingsField.description.pres;
      }
      localEmailMain = SettingsField.userEmail.pres;
      if(typeof SettingsField.formEmail !== 'undefined') {
        localEmailForm = SettingsField.formEmail.pres;
      }
      localTel = SettingsField.phone.pres;
      if(typeof SettingsField.chkbox_nyd !== 'undefined') {
        localWinter = SettingsField.chkbox_nyd.pres;
      }
      localStat = SettingsField.chkbox_stats.pres;
      localError = SettingsField.chkbox_logs.pres;
    }

    GlobalTheme        =  localTheme;
    GlobalWinter       =  localWinter;
    GlobalStat         =  localStat;
    GlobalError        =  localError;
    GlobalName         =  localName;
    GlobalDescription  =  localDescription;
    GlobalEmailMain    =  localEmailMain;
    GlobalEmailForm    =  localEmailForm;
    GlobalTel          =  localTel;
    GlobalNoti         =  localNoti;
    GlobalMsg          =  localMsg;
    GlobalLang         =  localLang;

    // change theme
    $.cookie('theme', GlobalTheme, {expires: 99999});
    $.cookie('sound_msg', GlobalMsg, {expires: 99999});
    $.cookie('sound_noti', GlobalNoti, {expires: 99999});
    $.cookie('language', GlobalLang, {expires: 99999});

    if(GlobalTheme == 'black') {
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
    }
    else if(GlobalTheme == 'white') {
      theme_chart = "light";
      updateChartsNew('light');
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
    }

    if(SettingsField.form_ready) {
      SettingsField.params['ssets'] = true;
      // query to server
      $.post('db_profile.php', SettingsField.params).done(function(data) {
        if(data == 'OK.') {
          var needReload = false;
          if(typeof SettingsField.title !== 'undefined') {
            SettingsField.title.prev = SettingsField.title.pres;
          }
          if(typeof SettingsField.description !== 'undefined') {
            SettingsField.description.prev = SettingsField.description.pres;
          }
          if(typeof SettingsField.tags !== 'undefined') {
            SettingsField.tags.prev = SettingsField.tags.pres;
          }
          SettingsField.userEmail.prev = SettingsField.userEmail.pres;
          if(typeof SettingsField.formEmail !== 'undefined') {
            SettingsField.formEmail.prev = SettingsField.formEmail.pres;
            needReload = true;
          }
          SettingsField.phone.prev = SettingsField.phone.pres;
          if(typeof SettingsField.chkbox_nyd !== 'undefined') {
            SettingsField.chkbox_nyd.prev = SettingsField.chkbox_nyd.pres;
            needReload = true;
          }
          SettingsField.chkbox_stats.prev = SettingsField.chkbox_stats.pres;
          SettingsField.chkbox_logs.prev = SettingsField.chkbox_logs.pres;
          settingsFormCheck();
          //close_window('#General_settings');
          notification_add('line', '', 'Изменения сохранены', 5);
          if(needReload) {
            notification_add('line', '', 'Страница будет перезагружена', 5);
            setTimeout(function() {
              document.location.reload(true);
            }, 2000);
          }
        }
        else if(data == 'AUTH.') {
          document.location.reload(true);
        }
        else if(data == 'PHONENUMBER.') {
          notification_add('error', 'Ошибка', 'Неверный формат номера телефона', 5);
        }
        else if(data == 'PHONE_LIMIT.') {
          notification_add('error', 'Ошибка', 'Введенный вами номер телефона уже занят', 5);
        }
        else if(data == 'USER_EMAIL.') {
          notification_add('error', 'Ошибка', 'Неверный формат адреса эл. почты', 5);
        }
        else if(data == 'EMAIL_LIMIT.') {
          notification_add('error', 'Ошибка', 'Введенный вами адрес эл. почты уже занят', 5);
        }
        else {
          close_window('#General_settings');
          notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка', 5);
          console.log('response: ' + data);
        }
    });
    }
    if(SettingsField.chkbox_ready) {
      close_window('#General_settings');
    }
  }

  // change border-color of field
  function settingsSetBorder(element, valid) {
    var element, valid;
    if(valid) {
      $(element).css('border','');
    }
    else {
      $(element).css('border','2px solid #b32424');
    }
  }

  // form validation
  function settingsFormCheck() {
    // get values
    var title = $('#GlobalName').val();
    var description = $('#GlobalDescription').val();
    var userEmail = $('#GlobalEmailMain').val();
    var formEmail = $('#GlobalEmailForm').val();
    var phone = $('#GlobalTel').val().replace(/[^0-9]/gim, '');
    var chkbox_nyd = undefined;
    if(document.getElementById('ch4')) {
      chkbox_nyd = document.getElementById('ch4').checked;
    }
    var chkbox_stats = document.getElementById('ch2').checked;
    var chkbox_logs = document.getElementById('ch3').checked;

    // post data
    SettingsField.params = {};

    SettingsField.form_ready = false;

    // if fields exists
    // site title
    if((title != undefined) && (SettingsField.title != undefined)) {
      SettingsField.title.pres = title;
      // regex
      siteTitleRegex.lastIndex = 0;
      if(siteTitleRegex.test(title)) {
        // valid
        settingsSetBorder('#GlobalName', true);
        SettingsField.title.ready = true;
      }
      else {
        // not valid
        settingsSetBorder('#GlobalName', false);
        SettingsField.title.ready = false;
      }
      // match
      if(SettingsField.title.pres == SettingsField.title.prev) {
        SettingsField.title.ready = false;
      }
      if(SettingsField.title.ready) {
        SettingsField.params['title'] = title;
      }
    }

    // site description
    if((description != undefined) && (SettingsField.description != undefined)) {
      SettingsField.description.pres = description;
      // regex
      siteDescriptionRegex.lastIndex = 0;
      if(siteDescriptionRegex.test(description)) {
        // valid
        settingsSetBorder('#GlobalDescription', true);
        SettingsField.description.ready = true;
      }
      else {
        // not valid
        settingsSetBorder('#GlobalDescription', false);
        SettingsField.description.ready = false;
      }
      // match
      if(SettingsField.description.pres == SettingsField.description.prev) {
        SettingsField.description.ready = false;
      }
      if(SettingsField.description.ready) {
        SettingsField.params['description'] = description;
      }
    }

    // user email
    if((userEmail != undefined) && (SettingsField.userEmail != undefined)) {
      SettingsField.userEmail.pres = userEmail;
      // regex
      siteEmailRegex.lastIndex = 0;
      if(siteEmailRegex.test(userEmail)) {
        // valid
        settingsSetBorder('#GlobalEmailMain', true);
        SettingsField.userEmail.ready = true;
      }
      else {
        // not valid
        settingsSetBorder('#GlobalEmailMain', false);
        SettingsField.userEmail.ready = false;
      }
      // match
      if(SettingsField.userEmail.pres == SettingsField.userEmail.prev) {
        SettingsField.userEmail.ready = false;
      }
      if(SettingsField.userEmail.ready) {
        SettingsField.params['userEmail'] = userEmail;
      }
    }

    // form email
    if((formEmail != undefined) && (SettingsField.formEmail != undefined)) {
      SettingsField.formEmail.pres = formEmail;
      // regex
      siteEmailRegex.lastIndex = 0;
      if(siteEmailRegex.test(formEmail)) {
        // valid
        settingsSetBorder('#GlobalEmailForm', true);
        SettingsField.formEmail.ready = true;
      }
      else {
        // not valid
        settingsSetBorder('#GlobalEmailForm', false);
        SettingsField.formEmail.ready = false;
      }
      // match
      if(SettingsField.formEmail.pres == SettingsField.formEmail.prev) {
        SettingsField.formEmail.ready = false;
        settingsSetBorder('#GlobalEmailForm', true);
      }
      if(SettingsField.formEmail.ready) {
        SettingsField.params['formEmail'] = formEmail;
      }
    }

    // user phone
    if((phone != undefined) && (SettingsField.phone != undefined)) {
      SettingsField.phone.pres = phone;
      // regex
      sitePhoneRegex.lastIndex = 0;
      if(sitePhoneRegex.test(phone)) {
        // valid
        settingsSetBorder('#GlobalTel', true);
        SettingsField.phone.ready = true;
      }
      else {
        // not valid
        settingsSetBorder('#GlobalTel', false);
        SettingsField.phone.ready = false;
      }
      // match
      if(SettingsField.phone.pres == SettingsField.phone.prev) {
        SettingsField.phone.ready = false;
      }
      if(SettingsField.phone.ready) {
        SettingsField.params['phone'] = phone;
      }
    }

    // checkbox - new year design
    if((chkbox_nyd != undefined) && (SettingsField.chkbox_nyd != undefined)) {
      SettingsField.chkbox_nyd.pres = chkbox_nyd;
      // match
      SettingsField.chkbox_nyd.ready = true;
      if(SettingsField.chkbox_nyd.pres == SettingsField.chkbox_nyd.prev) {
        SettingsField.chkbox_nyd.ready = false;
      }
      if(SettingsField.chkbox_nyd.ready) {
        SettingsField.params['chkbox_nyd'] = chkbox_nyd;
      }
    }

    // checkbox - statistics
    if((chkbox_stats != undefined) && (SettingsField.chkbox_stats != undefined)) {
      SettingsField.chkbox_stats.pres = chkbox_stats;
      // match
      SettingsField.chkbox_stats.ready = true;
      if(SettingsField.chkbox_stats.pres == SettingsField.chkbox_stats.prev) {
        SettingsField.chkbox_stats.ready = false;
      }
      if(SettingsField.chkbox_stats.ready) {
        SettingsField.params['chkbox_stats'] = chkbox_stats;
      }
    }

    // checkbox - error logs
    if((chkbox_logs != undefined) && (SettingsField.chkbox_logs != undefined)) {
      SettingsField.chkbox_logs.pres = chkbox_logs;
      // match
      SettingsField.chkbox_logs.ready = true;
      if(SettingsField.chkbox_logs.pres == SettingsField.chkbox_logs.prev) {
        SettingsField.chkbox_logs.ready = false;
      }
      if(SettingsField.chkbox_logs.ready) {
        SettingsField.params['chkbox_logs'] = chkbox_logs;
      }
    }

    // if tags exists
    if(typeof(SettingsField.tags) != 'undefined') {
      if(SettingsField.tags.ready) {
        //SettingsField.params['tags'] = SettingsField.tags.pres;
        SettingsField.params['tags'] = SettingsField.tags.formatted;
      }
    }

    // ready
    if(Object.keys(SettingsField.params).length > 0) {
      SettingsField.form_ready = true;
      $('#settings-save-btn').css('transition','0.15s all');
      setTimeout(function() {
        $('#settings-save-btn').css('opacity', 1);
        $('#settings-save-btn').css('cursor', 'pointer');
      }, 10);
    }
    else {
      SettingsField.form_ready = false;
      $('#settings-save-btn').css('opacity', 0.2);
      $('#settings-save-btn').css('cursor', 'default');
      setTimeout(function(){
        $('#settings-save-btn').css('transition','9999999999s all')
      }, 150);
    }
  }

  // add event listeners and initialize variables
  $('#settings-save-btn').on('click', function() {
    saveValues();
  });
  if($("#GlobalName")) {
    SettingsField.title = {prev: $('#GlobalName').val(), pres: undefined, ready: false};
    $("#GlobalName").on("input",function() {
      settingsFormCheck();
    });
  }
  if($("#GlobalDescription")) {
    SettingsField.description = {prev: $('#GlobalDescription').val(), pres: undefined, ready: false};
    $("#GlobalDescription").on("input",function() {
      settingsFormCheck();
    });
  }
  SettingsField.userEmail = {prev: $('#GlobalEmailMain').val(), pres: undefined, ready: false};
  $("#GlobalEmailMain").on("input",function() {
    settingsFormCheck();
  });
  if($("#GlobalEmailForm")) {
    SettingsField.formEmail = {prev: $('#GlobalEmailForm').val(), pres: undefined, ready: false};
    $("#GlobalEmailForm").on("input",function() {
      settingsFormCheck();
    });
  }
  SettingsField.phone = {prev: $('#GlobalTel').val().replace(/[^0-9]/gim, ''), pres: undefined, ready: false};
  $("#GlobalTel").on("input",function() {
    settingsFormCheck();
  });
  if(document.getElementById('ch4')) {
    SettingsField.chkbox_nyd = {prev: document.getElementById('ch4').checked, pres: undefined, ready: false};
    $("#ch4").on("change",function() {
      settingsFormCheck();
    });
  }
  SettingsField.chkbox_stats = {prev: document.getElementById('ch2').checked, pres: undefined, ready: false};
  $("#ch2").on("change",function() {
    settingsFormCheck();
  });
  SettingsField.chkbox_logs = {prev: document.getElementById('ch3').checked, pres: undefined, ready: false};
  $("#ch3").on("change",function() {
    settingsFormCheck();
  });
  if($('#GlobalTags') && document.getElementById('GlobalTags')) {
    SettingsField.tags = {prev: document.getElementById('GlobalTags').innerHTML, pres: '', ready: false};
  }
  else {
    SettingsField.tags = {prev: '', pres: '', ready: false};
  }
  $("#GlobalTags").on("input", function() {
    function decodeHTML(str) {
      var codes = [['&amp;', '&'], ['&lt;', '<'], ['&gt;', '>'], ['&sol;', '/'], ['&nbsp;', ' ']];
      for(var i = 0; i < codes.length; i++) {
        str =str.replace(new RegExp(codes[i][0], 'g'), codes[i][1]);
      }
      return str;
		}
    // get raw value
    var value = document.getElementById('GlobalTags').innerHTML;
    // remove crutch
    value = value.replace('<b>!', '');
    value = value.replace('</b>', '');
    // decode
    value = decodeHTML(value);
    // clean and split
    value = value.replace(/<div>/g, '');
    value = value.replace(/<\/div>/g, '<d>');
    // split
    var tags = value.split('<d>');
    // clay
    var output = '';
    var formTags = [];
    var newTag = false;
    for(var i = 0; i < tags.length; i++) {
      if(tags[i] != '') {
        if(tags[i][tags[i].length - 1] == ' ') {
          //console.log('spacebar');
          newTag = true;
          tags[i] = tags[i].slice(0, -1);
        }
        output += '<div>';
        var tag = tags[i].replace(/([^a-zа-яё0-9 ])/g, '');
        formTags.push(tag);
        output += tag;
        output += '</div>';
      }
    }
    // crutch
    if(output == '') {
      newTag = true;
    }
    // add new tag
    if(newTag) {
      // crutch
      output += '<div><b>!</b></div>';
    }
    // output
    document.getElementById('GlobalTags').innerHTML = output;
    // place caret at last tag
    var elem = document.getElementById('GlobalTags');
    var a = elem.getElementsByTagName('div');
    var b = a.length;
    placeCaretAtEnd(a[b - 1]);
    // form
    SettingsField.tags.pres = output;
    var joined = formTags.join(',');
    SettingsField.tags['formatted'] = joined;
    SettingsField.tags.ready = (SettingsField.tags.pres != SettingsField.tags.prev) ? true : false;
    settingsFormCheck();
  });

});

$(document).ready(function() {
  newsInitDoc();
  // fontSize field changed
  $('.panel-news_add-nav-elem-size1').on("input", function() {
    var size = $(this).text();
    if(size != '') {
      var regex1 = /^([0-9]){0,3}$/g;
      var regex2 = /\r?\n/g;
      if(!regex1.test(size) || !regex2.test(size)) {
        $('.panel-news_add-nav-elem-size1').text(size.replace(/([^0-9])/g, ''));
        placeCaretAtEnd(document.getElementById('panel-news_add-nav-elem-size1'));
      }
    }
  });
  // fontSize field clicked
  $('.panel-news_add-nav-elem-size1').on("click", function() {
    if(News.fontSizeClicks == 0) {
      var size = $(this).text();
      if(size == '' || size < 6) {
        size = 6;
      }
      setTimeout(newsFontSizeClicks, News.fontSizeTimeout, size);
    }
    News.fontSizeClicks++;
  });
  // remove contenteditable attribute if font size clicked in another place
  $(document).mouseup(function (e) {
  		var div = $("#panel-news_add-nav-elem-size1");
  		if (!div.is(e.target) && div.has(e.target).length === 0) {
        if(News.fontSizeContenteditable) {
          $('.panel-news_add-nav-elem-size1').removeAttr('contenteditable');
          News.fontSizeContenteditable = false;
          var size = $('#panel-news_add-nav-elem-size1').text();
          if(size == '' || size < 6) {
            $('#panel-news_add-nav-elem-size1').text('6');
          }
        }
  		}
  	});
  //  remove contenteditable attribute if the enter key was pressed
  $('#panel-news_add-nav-elem-size1').keydown(function(e) {
    if(e.keyCode == 13) {
      $('#panel-news_add-nav-elem-size1').removeAttr('contenteditable');
      News.fontSizeContenteditable = false;
      var size = $('#panel-news_add-nav-elem-size1').text();
      if(size == '' || size < 6) {
        $('#panel-news_add-nav-elem-size1').text('6');
      }
    }
  });
  // news field selected
  $('#panel-news-conteiner-text-id').on('mouseup', function() {
    News.lastSelection = document.getSelection();
  });
  // file import
  $('#file_import_DOC').on('change', function() {
    // get file
  	var files = this.files;
    if(typeof(files) == 'undefined') {
      return;
    }
    var data = new FormData();
    $.each(files, function(key, value) {
		  data.append(key, value);
    });
    data.append('news_doc_import', 1);
    data.append('stage', 0);
    // progressbar functions
    function displayProgressBar(display, elementId) {
      var elementId, display;
      if(typeof(elementId) == 'undefined') {
        elementId = '.preloader-percentage';
      }
      if(typeof(display) == 'undefined') {
        display = true;
      }
      if(display) {
        $(elementId + ' .preloader-percentage-ico-progress-text').text('Обработано 0%');
        $(elementId + '-ico-progress-status').css('width', '0%');
        $(elementId).css('display', 'block');
        setTimeout(function() {
          $(elementId).css('opacity', '1');
        }, 10);
      }
      else {
        $(elementId).css('opacity', '0');
        setTimeout(function() {
          $(elementId).css('display', 'none');
        }, 200);
        setTimeout(function() {
          $(elementId + '-ico-progress-status').css('width', '0%');
        }, 1000);
      }
    }
    function setProgressBar(e, set) {
      if(typeof(set) == 'undefined') {
        if(e.lengthComputable) {
          var complete = Math.floor(e.loaded / e.total * 100);
          setTimeout(function() {
            $('.preloader-percentage .preloader-percentage-ico-progress-status').css('width', complete + '%');
          }, 100);
          $('.preloader-percentage .preloader-percentage-ico-progress-text').text('Обработано ' + complete + '%');
        }
      }
      else {
        $('.preloader-percentage .preloader-percentage-ico-progress-text').text('Обработано ' + e + '%');
      }
    }
    // display progressbar
    displayProgressBar();
    // hide progressbar
    $('.preloader-percentage-ico-stop').click(function() {
      displayProgressBar(false);
    });
    // send file
    $.ajax({
      xhr: function() {
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", setProgressBar, false);
        xhr.addEventListener("progress", setProgressBar, false);
        return xhr;
      },
      url: 'db_profile.php',
      type: 'POST',
      data: data,
      cache: false,
      processData: false,
      contentType: false,
      beforeSend: function(){
        loader('show');
      },
      complete: function(){
        loader('hidden');
      },
      success: function(respond) {
        if(respond == 'OK.') {
          $.ajax({
            type: 'POST',
            url: 'db_profile.php',
            data: {
              news_doc_import: 1,
              stage: 1
            },
            beforeSend: function(){
              loader('show');
            },
            complete: function(){
              loader('hidden');
            },
            success: function(response) {
              if(response.substring(0, 3) == 'OK.') {
                var responseText = response.substring(3, response.length);
                setProgressBar(100);
                $('#panel-news-conteiner-text-id').html(responseText);
                $('#file_import_DOC').val(null);
                //document.getElementById('your_input_id').value = null;
              }
              else if(response == 'AUTH.') {
                document.location.reload(true);
              }
              else if(response == 'READ.') {
                notification_add('error', 'Ошибка сервера', 'Не удалось импортировать файл', 5);
              }
              else {
                notification_add('error', 'Ошибка сервера', 'Не удалось импортировать файл', 5);
                console.log('response: ' + response);
              }
              setTimeout(function() {
                displayProgressBar(false);
              }, 200);
            },
            error: function(jqXHR, status) {
              notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
              console.log('error: ' + status + ', ' + jqXHR);
            }
          });

        }
        else if(respond == 'AUTH.') {
          document.location.reload(true);
        }
        else if(respond == 'RESET.') {
          notification_add('error', 'Ошибка загрузки файла', 'Страница будет перезагружена', 5);
          setTimeout(function() {
            document.location.reload(true);
          }, 1000);
        }
        else if(respond == 'INVALID_PARAMETERS.') {
          notification_add('error', 'Ошибка', 'Данные повреждены', 5);
          displayProgressBar(false);
        }
        else if(respond == 'NO_FILE.') {
          notification_add('error', 'Ошибка', 'Сервер принял пустой запрос', 5);
          displayProgressBar(false);
        }
        else if(respond == 'LIMIT.') {
          notification_add('error', 'Ошибка', 'Превышен допустимый размер файла', 5);
          displayProgressBar(false);
        }
        else if(respond == 'DOWNLOADING_ERROR.') {
          notification_add('error', 'Ошибка сервера', 'Не удалось сохранить файл', 5);
          displayProgressBar(false);
        }
        else {
          notification_add('error', 'Ошибка сервера', 'Загрузка этого файла невозможна', 5);
          displayProgressBar(false);
          console.log('response: ' + respond);
        }
      },
      error: function(jqXHR, status) {
        notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
        displayProgressBar(false);
        console.log('error: ' + status + ', ' + jqXHR);
      }
    });
  });
});

$(document).ready(function() {
  $('#searchnews').on('input', function() {
    if(News.deleteFlag) {
      var a = $('#panel-filter-title-menu icon-left');
      open_set_news(a);
    }
    else {
      newsSearchRecord();
      setTimeout(function() { newsSearchRecord(); }, 500);
      setTimeout(function() { newsSearchRecord(); }, 1000);
      setTimeout(function() { newsSearchRecord(); }, 4000);
    }
  });
});

$(document).ready(function() {

  newsUpdateList();

  // news filters
  var inputElement = '#window-block-conteiner-news-search-input';
  // display block with users
  function displayUsersBlock(state) {
    if(typeof(state) == 'undefined') {
      state = true;
    }
    if(state) {
      $(inputElement).parent().find('.window-block-conteiner-news-search-search').css('display','block');
    }
    else {
      $(inputElement).parent().find('.window-block-conteiner-news-search-search').css('display','none');
    }
  }
  // display placeholder
  function displayPlaceholder(state) {
    if(state) {
      $(inputElement).attr('placeholder','Имя пользователя');
    }
    else {
      $(inputElement).attr('placeholder','');
    }
  }
  // username open/close
  $('body').click(function(element) {
    function setClickedAccount(id) {
      var account = News.filterParams.accounts[id];
      News.filterParams['username'] = account;
      News.filtersWindowFlag = false;
      $(inputElement).text(account);
      displayUsersBlock(false);
    }
    if(element.target.id == 'window-block-conteiner-news-search-input') {
      News.filtersWindowFlag = true;
    }
    else if(($(element.target).attr('class') == 'window-block-conteiner-news-search-search-elem') || ($(element.target).attr('class') == 'window-block-conteiner-news-search-search-elem-photo') || ($(element.target).attr('class') == 'window-block-conteiner-news-search-search-elem-text') || ($(element.target).attr('class') == 'window-block-conteiner-news-search-search-elem-text-login') || ($(element.target).attr('class') == 'window-block-conteiner-news-search-search-elem-text-name')) {
      setClickedAccount(parseInt($(element.target).closest('.window-block-conteiner-news-search-search-elem').attr('id').substring(6)));
    }
    else {
      if(News.filtersWindowFlag == true) {
        News.filterParams['username'] = $(inputElement).text();
        displayUsersBlock(false);
        News.filtersWindowFlag = false;
      }
    }
  });
  // username input
  $('#window-block-conteiner-news-search-input').on('input', function() {

    var account = $(inputElement).text();
    // placeholder
    if(account.length > 0) {
      displayPlaceholder(false);
    }
    else {
      displayPlaceholder(true);
    }

    if(!News.filtersWindowFlag) {
      return;
    }

    // check field
    var loginRegex = /^([a-z0-9]){2,32}$/g;
    var nameRegex = /^([A-Za-zА-ЯЁа-яё]){2,32}$/gu;
    loginRegex.lastIndex = 0;
    nameRegex.lastIndex = 0;
    if((!loginRegex.test(account) && !nameRegex.test(account)) || (account.length < 1) || (account == News.findAccountPrev)) {
      displayUsersBlock(false);
      $('.window-block-conteiner-news-search-search').empty();
      return;
    }
    News.findAccountPrev = account;
    // request
    $.ajax({
      type: 'POST',
      url: 'db_profile.php',
      data: {
        find_username: true,
        find_username_needle: account
      },
      beforeSend: function(){
        loader('show');
      },
      complete: function(){
        loader('hidden');
      },
      success: function(response) {
        var accountsArray = [];
        function checkResponseCode(code, rsp) {
          if(typeof(rsp) == 'undefined') rsp = response;
          return (response.substring(0, code.length) == code);
        }
        if(checkResponseCode('OK.')) {
          var responseText = response.substring(3, response.length);
          var responseData = JSON.parse(responseText);
          // output
          var output = '';
          for(var i = 0; i < responseData.length; i++) {
            accountsArray.push(responseData[i].account);
            output += '<div class="window-block-conteiner-news-search-search-elem" id="wbcnsi' + i + '">';
            output += '<div class="window-block-conteiner-news-search-search-elem-photo" style="background-image: url(' + responseData[i].path + ')"></div>';
            output += '<div class="window-block-conteiner-news-search-search-elem-text">';
            output += '<div class="window-block-conteiner-news-search-search-elem-text-name">';
            output += responseData[i].name2;
            output += ' ';
            output += responseData[i].name1;
            output += '</div>';
            output += '<div class="window-block-conteiner-news-search-search-elem-text-login">' + responseData[i].account + '</div>';
            output += '</div>';
            output += '</div>';
          }
          $('.window-block-conteiner-news-search-search').empty();
          $('.window-block-conteiner-news-search-search').append(output);
          displayUsersBlock(true);
        }
        else if(response == 'AUTH.') {
          document.location.reload(true);
        }
        else if(checkResponseCode('EMPTY.')) {
          // clear
          displayUsersBlock(false);
          $('.window-block-conteiner-news-search-search').empty();
        }
        else {
          console.log('error: ' + response);
        }
        News.filterParams.accounts = accountsArray;
      },
      error: function(jqXHR, status) {
        notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
        console.log('error: ' + status + ', ' + jqXHR);
      }
    });
  });
  // sort animation
  function updateSortingButtons() {
    if(News.filterParams['sortOrder'] == 'desc') {
      $('#window_sort_news_id_style_1').find('.window-block-sort-elem-ico-small').css('transform','rotate(0deg)');
      $('#window_sort_news_id_style_2').find('.window-block-sort-elem-ico-small').css('transform','rotate(0deg)');
      $('#window_sort_news_id_style_3').find('.window-block-sort-elem-ico-small').css('transform','rotate(0deg)');
    }
    else {
      $('#window_sort_news_id_style_1').find('.window-block-sort-elem-ico-small').css('transform','rotate(180deg)');
      $('#window_sort_news_id_style_2').find('.window-block-sort-elem-ico-small').css('transform','rotate(180deg)');
      $('#window_sort_news_id_style_3').find('.window-block-sort-elem-ico-small').css('transform','rotate(180deg)');
    }
  }
  // sort by
  $('#window_sort_news_id_style_1').click(function() {
    if(News.filterParams['sortBy'] == 'views') {
      if(News.filterParams['sortOrder'] == 'desc') {
        News.filterParams['sortOrder'] = 'asc';
      }
      else {
        News.filterParams['sortOrder'] = 'desc';
      }
    }
    else {
      News.filterParams['sortBy'] = 'views';
      News.filterParams['sortOrder'] = 'desc';
    }
    updateSortingButtons();
  });
  $('#window_sort_news_id_style_2').click(function() {
    if(News.filterParams['sortBy'] == 'alphabet') {
      if(News.filterParams['sortOrder'] == 'desc') {
        News.filterParams['sortOrder'] = 'asc';
      }
      else {
        News.filterParams['sortOrder'] = 'desc';
      }
    }
    else {
      News.filterParams['sortBy'] = 'alphabet';
      News.filterParams['sortOrder'] = 'desc';
    }
    updateSortingButtons();
  });
  $('#window_sort_news_id_style_3').click(function() {
    if(News.filterParams['sortBy'] == 'date') {
      if(News.filterParams['sortOrder'] == 'desc') {
        News.filterParams['sortOrder'] = 'asc';
      }
      else {
        News.filterParams['sortOrder'] = 'desc';
      }
    }
    else {
      News.filterParams['sortBy'] = 'date';
      News.filterParams['sortOrder'] = 'desc';
    }
    updateSortingButtons();
  });
  // checkboxes
  $('#chb1-01-01').on('click', function() {
    News.filterParams['needPublished'] = $('#chb1-01-01').is(':checked');
  });

  $('#chb1-01-02').on('click', function() {
    News.filterParams['needSaved'] = $('#chb1-01-02').is(':checked');
  });
  // filter by date
  $('#news-filter-date-start-1').on('change', function() {
    News.filterParams['startDate'] = $('#news-filter-date-start-1').val();
  });

  $('#news-filter-date-end-1').on('change', function() {
    News.filterParams['endDate'] = $('#news-filter-date-end-1').val();
  });

});

$(document).ready(function() {
  // update attachments
  dropAttachments();
  // attach file
  $('#file-add-attachment').on('change', function() {
    upload_news_file(this);
  });
});

function updateURL(get) {
    if (history.pushState) {
        var baseUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
        var newUrl = baseUrl + get;
        history.pushState(null, null, newUrl);
    }
    else {
        console.warn('History API не поддерживается');
    }
}

function upload_news_file(a, file, mime) {
  if(typeof(mime) != 'undefined') setAttachmentMime(mime);
  // get file
  var files;

  if(a === true){
    files = file;
  } else{
    files = a.files;
  }

  if(typeof(files) == 'undefined' || files.length == 0) {
    return;
  }
  var data = new FormData();
  $.each(files, function(key, value) {
    data.append(key, value);
  });
  data.append('attach_file', 1);
  data.append('attach_file_mime', News.attachmentMime);
  // if record exists
  if(News.updateFlag) {
    data.append('attach_record_id', News.updateId);
  }
  else {
    data.append('attach_record_id', 0);
  }
  // progressbar functions
  function displayProgressBar(display, elementId) {
    var elementId, display;
    if(typeof(elementId) == 'undefined') {
      elementId = '.preloader-percentage';
    }
    if(typeof(display) == 'undefined') {
      display = true;
    }
    if(display) {
      $(elementId + ' .preloader-percentage-ico-progress-text').text('Обработано 0%');
      $(elementId + '-ico-progress-status').css('width', '0%');
      $(elementId).css('display', 'block');
      setTimeout(function() {
        $(elementId).css('opacity', '1');
      }, 10);
    }
    else {
      $(elementId).css('opacity', '0');
      setTimeout(function() {
        $(elementId).css('display', 'none');
      }, 200);
      setTimeout(function() {
        $(elementId + '-ico-progress-status').css('width', '0%');
      }, 1000);
    }
  }
  function setProgressBar(e, set) {
    if(typeof(set) == 'undefined') {
      if(e.lengthComputable) {
        var complete = Math.floor(e.loaded / e.total * 100);
        setTimeout(function() {
          $('.preloader-percentage .preloader-percentage-ico-progress-status').css('width', complete + '%');
        }, 100);
        $('.preloader-percentage .preloader-percentage-ico-progress-text').text('Обработано ' + complete + '%');
      }
    }
    else {
      $('.preloader-percentage .preloader-percentage-ico-progress-text').text('Обработано ' + e + '%');
    }
  }
  // display progressbar
  displayProgressBar();
  // hide progressbar
  $('.preloader-percentage-ico-stop').click(function() {
    displayProgressBar(false);
  });
  // send file
  $.ajax({
    xhr: function() {
      var xhr = new window.XMLHttpRequest();
      xhr.upload.addEventListener("progress", setProgressBar, false);
      xhr.addEventListener("progress", setProgressBar, false);
      return xhr;
    },
    url: 'db_profile.php',
    type: 'POST',
    data: data,
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    cache: false,
    processData: false,
    contentType: false,
    success: function(response) {
      displayProgressBar(false);
      clearInputTypeFile('file-add-attachment');
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.', response)) {
        var responseText = response.substring(3, response.length);
        var responseData = JSON.parse(responseText);
        //console.log(responseData);
        News.attachments[News.attachments.length] = responseData;
        displayAttachment(responseData);
        close_window('news-add-file');
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else if(checkResponseCode('MIME.', response)) {
        notification_add('error', 'Ошибка', 'Неверный тип файла', 5);
      }
      else if(checkResponseCode('LIMIT.', response)) {
        notification_add('error', 'Ошибка', 'Превышен допустимый размер файла в 200 МБ', 5);
      }
      else if(checkResponseCode('DOWNLOADING_ERROR.', response)) {
        notification_add('error', 'Ошибка сервера', 'Ошибка загрузки файла', 5);
      }
      else {
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
      displayProgressBar(false);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

$(document).ready(function() {

  ProfileWindow.phoneConfirmed = userData['phone_verify'];
  ProfileWindow.phoneWasConfirmed = userData['phone_verify'];
  ProfileWindow.emailConfirmed = userData['email_verify'];
  ProfileWindow.emailWasConfirmed = userData['email_verify'];

  // initialize profile window
  $('#profileWindow-input-name1').on('input', function() {
    checkProfileWindow();
  });
  $('#profileWindow-input-name2').on('input', function() {
    checkProfileWindow();
  });
  $('#profileWindow-input-birthday').on('input', function() {
    checkProfileWindow();
  });
  $('#profileWindow-input-country').on('input', function() {
    checkProfileWindow();
  });
  $('#profileWindow-input-city').on('input', function() {
    checkProfileWindow();
  });
  $('#profileWindow-input-phone').on('input', function() {
    ProfileWindow.phoneConfirmed = false;
    checkProfileWindow();
  });
  $('#profileWindow-input-email').on('input', function() {
    ProfileWindow.emailConfirmed = false;
    checkProfileWindow();
  });
  $('#chb1-0').on('click', function() {
    checkProfileWindow();
  });
  $('#chb2-0').on('click', function() {
    checkProfileWindow();
  });
  $('#profileWindow-btn-phone').on('click', function() {
    checkProfileWindow();
  });
  $('#profileWindow-btn-email').on('click', function() {
    checkProfileWindow();
  });

  closeProfileWindow();

});

$(document).ready(function() {

  // initialize profile window
  $('#password-edit-profile-1').on('input', function() {
    profileChangePasswordCheck();
  });
  $('#password-edit-profile-2').on('input', function() {
    profileChangePasswordCheck();
  });
  $('#password-edit-profile-3').on('input', function() {
    profileChangePasswordCheck();
  });

  // reset fields in password change window
  $('#profileWindow-btn-change').click(function() {
    profileSetBorder('#password-edit-profile-1', true);
    profileSetBorder('#password-edit-profile-2', true);
    profileSetBorder('#password-edit-profile-3', true);
    $('#password-edit-profile-1').val('');
    $('#password-edit-profile-2').val('');
    $('#password-edit-profile-3').val('');
  });

});

// profile check code by regex
$(document).ready(function() {
  $('#code-edit-profile').on('keyup', function() {
    profileTestCode();
  });
  $('#profile-email-code-btn').click(function() {
    profileCheckCode();
  });
});

$(document).ready(function() {
  ProfileIcon.icon = userData.icon;
});

function isFunction(functionToCheck){
  var getType = {};
  return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
}

function movingWindow(header, main){
  var time_header = document.getElementById(header);
  var time_main = document.getElementById(main);



  time_header.onmousedown = function(e) {


    var count_window = $('window').find('.window-block-elem')
    for(let i = 0; i < count_window.length; i++){
      $(count_window[i]).css('z-index','1')
      $(count_window[i]).css('box-shadow','0px 0px 12px -4px rgba(0,0,0,0.18)')
    }
    $('#' + main).css('z-index','99')
    $('#' + main).css('box-shadow','0px 0px 12px -4px rgba(0,0,0,0.44)')

    if(true){
      var coords = getCoords(time_main);
      var shiftX = e.pageX - coords.left;
      var shiftY = e.pageY - coords.top;

      time_main.style.position = 'fixed';
      moveAt(e);

      // time_main.style.zIndex = 999999999999999999999999999999999999999999999; // над другими элементами
      $(time_main).css('transition','0s all')

      function moveAt(e) {
        time_main.style.left = e.clientX - shiftX + 'px';
        time_main.style.top = e.clientY - shiftY + 'px';
      }

      document.onmousemove = function(e) {
        moveAt(e);
      };

      document.onmouseup = function() {
        var topBlock = $(time_main.style.top.split('px'))[0];
        if(topBlock < 0){
          time_main.style.top = '0px';
        }


        document.onmousemove = null;
        time_header.onmouseup = null;
        $(time_main).css('transition','0.15s cubic-bezier(0, 1.14, 1, 1) all')
      };
    }

  }

  time_header.ondragstart = function() {
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
}

function time_analog(){
  var d = new Date(),
      t_sec = 6*d.getSeconds() + (1/170)*d.getMilliseconds(),  //Определяем угол для секунд
      t_min = 6*(d.getMinutes() + (1/60)*d.getSeconds()), //Определяем угол для минут
      t_hour = 30*(d.getHours() + (1/60)*d.getMinutes());  //Определяем угол для часов
      sec = d.getSeconds(),
      min = d.getMinutes(),
      hour = d.getHours(),
      date = d.getDate(),
      arrMonth = ['Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Ноября', 'Декабря'];

  if(date < 10){
    date = '0' + date;
  }

  var tmp = date + " " + arrMonth[d.getMonth()] + ' ' + d.getFullYear();
  if(d.getSeconds() < 10){
    sec = '0' + sec;
  }
  if(d.getMinutes() < 10){
    min = '0' + min;
  }
  if(d.getHours() < 10){
    hour = '0' + hour;
  }

  $('.panel-conteiner-width-small-main-elem2-time-second2').css('transform','translate(-50%, 3px) rotate(' + t_sec + 'deg)')
  $('.panel-conteiner-width-small-main-elem2-time-minute2').css('transform','translate(-50%, 3px) rotate(' + t_min + 'deg)')
  $('.panel-conteiner-width-small-main-elem2-time-sentinel2').css('transform','translate(-50%, 7px) rotate(' + t_hour + 'deg)')
  $('.window-time-main-text-text-time').text(hour + ':' + min + ':' + sec)
  $('.window-time-main-text-text-date').text(tmp)

  $('.panel-conteiner-width-small-main-elem2-time').attr('title','Текущее время: ' + hour + ':' + min + ':' + sec)
  $('.panel-conteiner-width-small-main-elem2-time-minute').css('transform','translate(-50%, 2px) rotate(' + t_min + 'deg)')
  $('.panel-conteiner-width-small-main-elem2-time-sentinel').css('transform','translate(-50%, 6px) rotate(' + t_hour + 'deg)')
}

function open_set_news(a){
  var count_tmp = $('.panel-filter-parameters').find('.panel-filter-parameters-elem').length,
      string_tmp = 0,
      blocksTmp1 = $('#newsSaved').find('.panel-news-block-img'),
      blocksTmp1_1 = $('#newsSaved').find('.panel-news-block-img1'),
      blocksTmp2 = $('#newsPublished').find('.panel-news-block-img'),
      blocksTmp2_1 = $('#newsPublished').find('.panel-news-block-img1');

  if(!status_set_news){
    status_set_news = true;
    News.deleteFlag = true;
    $(a).css('transform','rotate(90deg)')
    for(let i = 0; i < count_tmp; i++){
      string_tmp += 45;
    }
    $('.panel-filter-parameters').css('height',string_tmp + 'px')
    $('.panel-filter-parameters').css('margin-bottom','10px')

    for(let i = 0; i < blocksTmp1.length; i++){
      $(blocksTmp1[i]).css('font-size','0px');
      $(blocksTmp1[i]).attr('class','panel-news-block-img1');
      $(blocksTmp1[i]).find('span').css('display','block');
      setTimeout(function(){
        $(blocksTmp1[i]).find('span').css('opacity','1');
      }, 1)
    }
    for(let i = 0; i < blocksTmp2.length; i++){
      $(blocksTmp2[i]).css('font-size','0px');
      $(blocksTmp2[i]).attr('class','panel-news-block-img1');
      $(blocksTmp2[i]).find('span').css('display','block');
      setTimeout(function(){
        $(blocksTmp2[i]).find('span').css('opacity','1');
      }, 1)
    }
  } else{
    status_set_news = false;
    News.deleteFlag = false;
    $('.panel-filter-parameters').css('height','0px')
    $(a).css('transform','rotate(-90deg)')
    $('.panel-filter-parameters').css('margin-bottom','0px')

    for(let i = 0; i < blocksTmp1_1.length; i++){
      $(blocksTmp1_1[i]).css('font-size','26px');
      $(blocksTmp1_1[i]).attr('class','panel-news-block-img icon-article');
      $(blocksTmp1_1[i]).find('span').css('opacity','0');
      setTimeout(function(){
        $(blocksTmp1_1[i]).find('span').css('display','none');
      }, 150)
    }
    for(let i = 0; i < blocksTmp2_1.length; i++){
      $(blocksTmp2_1[i]).css('font-size','26px');
      $(blocksTmp2_1[i]).attr('class','panel-news-block-img icon-article');
      $(blocksTmp2_1[i]).find('span').css('opacity','0');
      setTimeout(function(){
        $(blocksTmp2_1[i]).find('span').css('display','none');
      }, 150)
    }

  }
}

function saleAnimation(){
  $('.window-block-sale-img-svg').css({
    'transform':'translate(0px, 0%)',
  });
  setTimeout(function(){
    $('.window-block-sale-img-svg1').css({
      'transform':'translate(-50%, -50%) rotate(-36deg)',
      'transition':'0.3s all ease-in-out'
    });
    setTimeout(function(){
      $('.window-block-sale-img-svg1').css({
        'transform':'translate(-50%, -50%) rotate(12deg)',
        'transition':'0.2s all ease-in-out'
      });
      setTimeout(function(){
        $('.window-block-sale-img-svg1').css({
          'transform':'translate(-50%, -50%) rotate(-6deg)',
          'transition':'0.15s all ease-in-out'
        });
        setTimeout(function(){
          $('.window-block-sale-img-svg1').css({
            'transform':'translate(-50%, -50%) rotate(0deg)',
          });
          $('.UjdaJ-sZyS-pRZH').css({
            'transform':'translate(0%, 0%)',
            'opacity':'1',
            'visibility':'visible'
          });
          setTimeout(function(){
            $('.f3Fmt-USvm-5ziz').css({
              'transform':'translate(0%, 0%)',
              'opacity':'1',
              'visibility':'visible'
            });
            setTimeout(function(){
              $('.OaOks-qL1H-EBhz').css({
                'transform':'translate(0%, 0%)',
                'opacity':'1',
                'visibility':'visible'
              });
              setTimeout(function(){
                $('.Wzc0k-CXJG-hW7v').css({
                  'transform':'translate(0%, 0%)',
                  'opacity':'1',
                  'visibility':'visible'
                });
                setTimeout(function(){
                  $('.uQEjJ-ccg4-8Y8Z').css({
                    'transform':'translate(0%, 0%)',
                    'opacity':'1',
                    'visibility':'visible'
                  });
                  setTimeout(function(){
                    $('.9pv7i-XdcM-h6VB').css({
                      'transform':'translate(0%, 0%)',
                      'opacity':'1',
                      'visibility':'visible'
                    });
                    setTimeout(function(){
                      $('.V5XhN-OOgb-shr5').css({
                        'transform':'translate(0%, 0%)',
                        'opacity':'1',
                        'visibility':'visible'
                      });
                      setTimeout(function(){
                        $('.J5Q2E-tp3s-TGGd').css({
                          'transform':'translate(0%, 0%)',
                          'opacity':'1',
                          'visibility':'visible'
                        });
                      }, 340)
                    }, 300)
                  }, 260)
                }, 220)
              }, 180)
            }, 120)
          }, 60)
        }, 150)
      }, 200)
    }, 300)
  }, 180)

}

function newsDelete(){
  var blockChecked = $('#newsSaved').find('input');
  var blockChecked2 = $('#newsPublished').find('input');
  var countTmp = 0;
  var idArray = [];

  for(let i = 0; i < blockChecked.length; i++){
    if(!($(blockChecked[i]).prop('checked'))){
      countTmp += 1;
    }
  }
  for(let i = 0; i < blockChecked2.length; i++){
    if(!($(blockChecked2[i]).prop('checked'))){
      countTmp += 1;
    }
  }

  if(!(countTmp > 0)){
    notification_add('error','Ошибка в удалении','Нельзя удалить 0 статей!', 5);
  } else{
    for(let i = 0; i < blockChecked.length; i++){
      if($(blockChecked[i]).prop('checked') == false){
        var tmpBlock = $(blockChecked[i]).parent().parent().parent();
        idArray.push($(blockChecked[i]).attr('id').split('_')[1].substr(1));
        $(tmpBlock).css('height','0px');
        $(tmpBlock).css('margin-top','0px');
        setTimeout(function(){
          $(tmpBlock).remove();
        }, 150)
      }
    }
    for(let i = 0; i < blockChecked2.length; i++){
      if($(blockChecked2[i]).prop('checked') == false){
        var tmpBlock = $(blockChecked2[i]).parent().parent().parent();
        idArray.push($(blockChecked2[i]).attr('id').split('_')[1].substr(1));
        $(tmpBlock).css('height','0px');
        $(tmpBlock).css('margin-top','0px');
        setTimeout(function(){
          $(tmpBlock).remove();
        }, 150)
      }
    }
  }
  if(idArray.length > 0) {
    newsDeleteRecord(idArray);
  }
}

/*function window_block_conteiner_news_search_input(elem) {
  var a = '#window-block-conteiner-news-search-input';
  // display/functional part
  function displayBlock(state) {
    if(typeof(state) == 'undefined') {
      state = true;
    }
    if(state) {
      $(a).parent().find('.window-block-conteiner-news-search-search').css('display','block');
    }
    else {
      $(a).parent().find('.window-block-conteiner-news-search-search').css('display','none');
    }
  }
  // account list part
  function newsFilterByAccount(account) {
    // check field
    var loginRegex = /^([a-z0-9]){2,32}$/g;
    var nameRegex = /^([A-Za-zА-ЯЁа-яё]){2,32}$/gu;
    loginRegex.lastIndex = 0;
    nameRegex.lastIndex = 0;
    if((!loginRegex.test(account) && !nameRegex.test(account)) || (account.length < 1) || (account == News.findAccountPrev)) {
      displayBlock(false);
      $('.window-block-conteiner-news-search-search').empty();
      return;
    }
    News.findAccountPrev = account;
    // get accounts
    $.ajax({
      type: 'POST',
      url: 'db_profile.php',
      data: {
        find_username: true,
        find_username_needle: account
      },
      success: function(response) {
        function checkResponseCode(code, rsp) {
          if(typeof(rsp) == 'undefined') rsp = response;
          return (response.substring(0, code.length) == code);
        }
        if(checkResponseCode('OK.')) {
          var responseText = response.substring(3, response.length);
          var responseData = JSON.parse(responseText);
          // output
          var output = '';
          for(var i = 0; i < responseData.length; i++) {
            output += '<div class="window-block-conteiner-news-search-search-elem" >'; // onmousedown="$(\'' + a + '\').text(\'' + responseData[i].account + '\');"  onmouseover="News.filterNameHint = \'' + responseData[i].account + '\';"
            output += '<div class="window-block-conteiner-news-search-search-elem-photo" style="background-image: url(' + responseData[i].path + ')"></div>';
            output += '<div class="window-block-conteiner-news-search-search-elem-text">';
            output += '<div class="window-block-conteiner-news-search-search-elem-text-name">';
            output += responseData[i].name2;
            output += ' ';
            output += responseData[i].name1;
            output += '</div>';
            output += '<div class="window-block-conteiner-news-search-search-elem-text-login">' + responseData[i].account + '</div>';
            output += '</div>';
            output += '</div>';
          }
          $('.window-block-conteiner-news-search-search').empty();
          $('.window-block-conteiner-news-search-search').append(output);
          displayBlock(true);
          // events
          //$('.window-block-conteiner-news-search-search').on('mousemove', function(event) {
            //News.filterNameFlag = true;
            //News.filterNameTarget = event.target;
          //});
        }
        else if(checkResponseCode('EMPTY.')) {
          // clear
          displayBlock(false);
          $('.window-block-conteiner-news-search-search').empty();
        }
        else {
          console.log('error: ' + response);
        }
      },
      error: function(jqXHR, status) {
        notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
        console.log('error: ' + status + ', ' + jqXHR);
      }
    });
  }
  var inputData = $(a).text();
  // placeholder
  if(inputData.length > 0) {
    $(a).attr('placeholder','');
  }
  else {
    $(a).attr('placeholder','Имя пользователя');
  }
  // main part
  newsFilterByAccount(inputData);
}*/

function open_support(){
  $('.panel-conteiner-width-support-hello').css('opacity','0')
  setTimeout(function(){
    $('.panel-conteiner-width-support-hello').css('display','none')
    $('#panel-msg-block-msg-conteiner-main-support').css('display','block')
    setTimeout(function(){
      $('#panel-msg-block-msg-conteiner-main-support').css('opacity','1')
    }, 10)
  }, 250)
}

function suppor_assessment(){
  var assessmentHeight = $('.panel-msg-block-msg-conteiner-main-conteiner-block-assessment').height();

  $('.panel-msg-block-msg-conteiner-main-conteiner-block-assessment').css('height',assessmentHeight + 'px')
  $('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-good').css('display','block');
  setTimeout(function(){
    $('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-good').css('opacity','1');
    setTimeout(function(){
      $('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-good-ab-text').css({'transform':'translate(0px,0px)','opacity':'1'})
      setTimeout(function(){
        $('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-good-ab-ico').css('opacity','1')
        setTimeout(function(){
          $('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-good-ab-ico-1').css('height','22px')
          setTimeout(function(){
            $('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-good-ab-ico-2').css('height','36px')
            setTimeout(function(){
              $('.panel-msg-block-msg-conteiner-main-conteiner-block-assessment').css({'height':'0px', 'padding-top':'0px','padding-bottom':'0px'})
              setTimeout(function(){
                $('.panel-msg-block-msg-conteiner-main-conteiner-block-assessment').remove();
              }, 250)
            }, 2500)
          }, 100)
        }, 100)
      }, 50)
    }, 200)
  }, 10)
}

function res(width, height, timeout, validation) {
  var item = document.getElementById('mainResize');

  if((width == item.offsetWidth) && (height == item.offsetHeight)) {

    if(validation) {
      adaptiveDesign();
    } else {
      timeout = 500;
    }
    window.setTimeout(res, timeout, width, height, timeout, 0);

  } else {
    width = item.offsetWidth;
    height = item.offsetHeight;
    timeout = 200;
    window.setTimeout(res, timeout, width, height, timeout, 1);
  }

}

function dragAndDrop(event){

  if($('#file_manager').css('display') == 'block'){
    event.preventDefault();
    event.stopPropagation();

    var tmpFolderOrFile;

    for(let i = 0; i < event.dataTransfer.files.length; i++){
      if(event.dataTransfer.files[i].type.length == 0 && !event.dataTransfer.files[i].type && event.dataTransfer.files[i].size%4096 == 0){
        // get folder
        tmpFolderOrFile = 'folder';
      } else {
        // get file
        tmpFolderOrFile = 'file';
      }
    }

    if(tmpFolderOrFile == 'file'){

      // get file
      var files = event.dataTransfer.files;

      if(typeof(files) == 'undefined' || files.length == 0) {
        return;
      }
      var data = new FormData();
      $.each(files, function(key, value) {
        data.append(key, value);
      });
      data.append('finder_upload_file', 1);
      // progressbar functions
      function displayProgressBar(display, elementId) {
        var elementId, display;
        if(typeof(elementId) == 'undefined') {
          elementId = '.preloader-percentage';
        }
        if(typeof(display) == 'undefined') {
          display = true;
        }
        if(display) {
          $(elementId + ' .preloader-percentage-ico-progress-text').text('Обработано 0%');
          $(elementId + '-ico-progress-status').css('width', '0%');
          $(elementId).css('display', 'block');
          setTimeout(function() {
            $(elementId).css('opacity', '1');
          }, 10);
        }
        else {
          $(elementId).css('opacity', '0');
          setTimeout(function() {
            $(elementId).css('display', 'none');
          }, 200);
          setTimeout(function() {
            $(elementId + '-ico-progress-status').css('width', '0%');
          }, 1000);
        }
      }
      function setProgressBar(e, set) {
        if(typeof(set) == 'undefined') {
          if(e.lengthComputable) {
            var complete = Math.floor(e.loaded / e.total * 100);
            setTimeout(function() {
              $('.preloader-percentage .preloader-percentage-ico-progress-status').css('width', complete + '%');
            }, 100);
            $('.preloader-percentage .preloader-percentage-ico-progress-text').text('Обработано ' + complete + '%');
          }
        }
        else {
          $('.preloader-percentage .preloader-percentage-ico-progress-text').text('Обработано ' + e + '%');
        }
      }
      // display progressbar
      displayProgressBar();
      // hide progressbar
      $('.preloader-percentage-ico-stop').click(function() {
        displayProgressBar(false);
      });
      // send file
      $.ajax({
        xhr: function() {
          var xhr = new window.XMLHttpRequest();
          xhr.upload.addEventListener("progress", setProgressBar, false);
          xhr.addEventListener("progress", setProgressBar, false);
          return xhr;
        },
        url: 'db_finder.php',
        type: 'POST',
        data: data,
        cache: false,
        processData: false,
        contentType: false,
        beforeSend: function(){
          loader('show');
        },
        complete: function(){
          loader('hidden');
          closeContextMenu();
          displayProgressBar(false);
          // clear input
          $('#finder-upload-file-input').val('');
        },
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.')) {
            notification_add('line', '', 'Файл загружен на сервер', 5);
            finderListing();
          }
          else if(checkResponseCode('AUTH.')) {
            document.location.reload(true);
          }
          else if(checkResponseCode('RESET.')) {
            notification_add('error', 'Ошибка загрузки файла', 'Страница будет перезагружена', 5);
            setTimeout(function() {
              document.location.reload(true);
            }, 1000);
          }
          else if(checkResponseCode('INVALID_PARAMETERS.')) {
            notification_add('error', 'Ошибка', 'Данные повреждены', 5);
          }
          else if(checkResponseCode('WRONG_FILENAME.')) {
            notification_add('error', 'Ошибка', 'Недопустимое имя файла', 5);
          }
          else if(checkResponseCode('NO_FILE.')) {
            notification_add('error', 'Ошибка', 'Сервер принял пустой запрос', 5);
          }
          else if(checkResponseCode('LIMIT.')) {
            notification_add('error', 'Ошибка', 'Превышен допустимый размер файла', 5);
          }
          else if(checkResponseCode('DOWNLOADING_ERROR.')) {
            notification_add('error', 'Ошибка сервера', 'Не удалось сохранить файл', 5);
          }
          else {
            notification_add('error', 'Ошибка сервера', 'Загрузка этого файла невозможна', 5);
            console.log('response: ' + response);
          }
        },
        error: function(jqXHR, status) {
          notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
          displayProgressBar(false);
          console.log('error: ' + status + ', ' + jqXHR);
        }
      });
    }

    if(tmpFolderOrFile == 'folder'){
      notification_add('error','Ошибка загрузки','К сожалению сейчас нельзя загружать папки таким образом, воспользуйтесь правой кнопкой мыши.', 7)
    }

    }

}

function adaptiveDesign(){
  var widthMainBlock = $('.main').width();
  var global_search = $('#global_search');
  var statistics_block = $('#statistics');
  var finder_block = $("#file_manager");
  var profile_block = $("#profile");
  var global_chat = $("#general_chat");
  var timetable = $("#timetable");


  var tmpBloks = $('#folderSort').html();
  var tmpBloks2;

  // phone
  if(widthMainBlock <= 840){

    adaptiveDesignS = 'phone';

    // other
    $('.window-block-sale-img').css({'width':'100%'})
    $('.panel-conteiner-width-small-footer-elem1-span').find('span').css('font-size','14px');
    $('.panel-conteiner-width-small4-text-desc').css('font-size','14px');
    $('.panel-conteiner-width-small-main-elem-block1').find('span').css('font-size','30px');
    $('.main-nav-search-2').css('border-radius','7px')
    $('.visible-phone').css('display','')

    // main
    $($('#main').find('.panel-conteiner-width')).css({'margin-left':'10px','width':'calc(100% - 20px)','margin-bottom':'20px'})
    $($('#main').find('.panel-conteiner-width')[0]).find('.panel-conteiner-main-block').css('border-radius','15px')
    $('#chart1').css('width','calc(100% - 10px)')
    $('.panel-title').css('display','none')
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small').css({'width':'calc(100% - 0px)','border-radius':'15px','margin-bottom':'20px'})
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small2').css({'width':'calc(100% - 0px)','margin-bottom':'30px'})
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small2').find('.panel-conteiner-width-small2-main').css('border-radius','15px')
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small2').find('.panel-conteiner-width-small2-header').css('border-radius','15px')
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small3').css({'width':'calc(100% - 0px)','border-radius':'15px','margin-bottom':'-25px'})
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small4').css({'width':'calc(100% - 0px)','border-radius':'15px','margin-bottom':'20px'})

    // timetable
    timetable.find('.panel-conteiner').css({
      'width':'100%',
      'margin-left':'0px',
      'margin-top':'-30px'
    })
    timetable.find('.panel-filter').css({
      'border-radius':'0px',
      'max-height':'calc(100vh - 105px)',
      'margin-bottom':'0px',
    })
    timetable.find('.panel-filter > span').css({
      'width':'100vw',
      'display':'block',
    })
    timetable.find('.panel-filter-title-ab2').css({
      'width':'calc(100vw - 163px)',
    })
    timetable.find('#searchFilter223').css({
      'width':'calc(100vw - 69px)'
    })
    timetable.find('.panel-news-block-half').css({
      'width':'calc((100vw / 2) - 23px)',
    })
    timetable.find('.panel-news-block').css({
      'width':'calc(100vw - 31px)'
    })
    timetable.find('.panel-conteiner-full').css({
      'width':'calc(100% - 0px)',
      'margin-left':'0px',
      'margin-top':'-50px',
    })
    timetable.find('.panel-news_add').css({
      'margin-bottom':'0px',
      'height':'calc(100vh - 89px)',
      'width':'calc(100vw + 4px)',
      'min-width':'initial'
    })
    timetable.find('#timetable-d46wq').css({
      'width':'100vw'
    })
    timetable.find('.panel-news_add-title').css({
      'max-width':'100%'
    })
    timetable.find('.checkbox-login').css({
      'margin-left':'0px',
      'padding-left':'20px',
      'margin-top':'15px',
      'overflow':'auto',
      'padding-bottom':'10px',
      'width':'calc(100vw - 11px)'
    })
    timetable.find('.panel-news-description2').css({
      'width':'calc(100vw - 30px)'
    })
    timetable.find('.checkbox-login-chb3').css({
      'width':'calc(100% - 65px)'
    })

    if(timetableElemEnable){
      timetable.find('.panel-conteiner-full').css({
        'display':'inline-block',
      })
      timetable.find('.panel-filter').css({
        'display':'none',
      })
    } else{
      timetable.find('.panel-conteiner-full').css({
        'display':'none',
      })
      timetable.find('.panel-filter').css({
        'display':'inline-block',
      })
    }


    // general_chat
    global_chat.find('.panel-conteiner').css({'display':'none'})
    global_chat.find('.panel-conteiner-full').css({
      'width':'calc(100% - 0px)',
      'margin-top':'-30px',
      'margin-left':'0px'
    })
    global_chat.find('.panel-msg').css({
      'border-radius':'0px',
      'height':'calc(100vh - 70px)'
    })
    global_chat.find('.panel-msg-block-msg-textinput-file').css({
      'width':'22px',
      'margin-left':'8px',
      'border-radius':'10px'
    })
    global_chat.find('.panel-msg-block-msg-textinput-textarea').css({
      'width':'calc(100vw - 90px)',
      'margin-left':'0px',
      'border-radius':'10px'
    })
    global_chat.find('.panel-msg-block-msg-textinput-send').css({
      'margin-left':'0px',
      'border-radius':'10px'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-block').css({
      'width':'calc(100vw - 0px)',
      'padding-left':'11px',
      'padding-right':'0px'
    })
    global_chat.find('.panel-msg-block-msg-textinput').css({
      'position':'fixed',
      'white-space':'nowrap',
      'bottom':'0'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-help').css({
      'margin-top':'30px',
      'height':'30px'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-block-inv').css({
      'width':'calc(100vw - 0px)',
      'padding-left':'0px'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv').css({
      'margin-right':'5px'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-block-inv').find('.panel-msg-block-msg-conteiner-main-conteiner-block-photo').css({
      'display':'none'
    })
    $('.emoji-block').css({
      'bottom':'71px',
      'position':'fixed',
      'right':'-83%',
      'left':'0',
      'margin':'auto',
      'width':'85vw',
      'border-radius':'10px'
    })
    $('.panel-conteiner-all-block-main-2-main-btnTop2').css({
      'position':'fixed',
      'top':'calc(100% - 129px)'
    })

    // finder
    closeContextMenu();
    finderMenuClose();
    finder_block.find('.panel-conteiner-all').css({'width':'100%','margin-left':'0px','user-select':'none'})
    finder_block.find('.panel-conteiner-all-block-2').css({'border-radius':'0px','height':'calc(100vh - 73px)','margin-bottom':'-11px','margin-top':'-30px'})
    finder_block.find('.file_manager-action').css({'display':'none'})
    finder_block.find('.file_manager-action-btn').css({'padding-left':'2.5px','padding-right':'2.5px'})
    finder_block.find('.file_manager-action-btn-none').css({'padding-left':'2.5px','padding-right':'2.5px'})
    finder_block.find('.file_manager-btn-action-2').css({'margin-left':'4px','width':'calc(100% - 60px)'})
    finder_block.find('.file_manager-btn-action-3').css({'display':'none'})
    finder_block.find('.panel-conteiner-all-block-main-2-nav').css({'position':'absolute','z-index':'9','background-color':'var(--white)','transform':'translate(-100%, 0px)'})
    finder_block.find('.panel-conteiner-all-block-main-2-main').css({'width':'calc(100% + 4px)'})
    finder_block.find('.panel-conteiner-all-block-main-2-main-elem-ch').css({'display':'none'})
    $(finder_block.find('.panel-conteiner-all-block-main-2-main-filter-elem')).css({'width':'calc(20% - 0px)'})
    $(finder_block.find('.panel-conteiner-all-block-main-2-main-filter-elem')[0]).css({'margin-left':'49px','width':'calc(31% - 1px)'})
    $(finder_block.find('.panel-conteiner-all-block-main-2-main-filter-elem')[2]).css({'display':'none'})
    finder_block.find('.panel-conteiner-all-block-main-2').css({'height':'calc(100% - 42px)'})
    finder_block.find('.file_manager-contextmenu').css({'position':'fixed','left':'10px','bottom':'10px','width':'calc(100% - 20px)'})
    $('.file_manager-contextmenu').css({'border-radius':'15px'})
    // old version
    //$('.panel-conteiner-all-block-main-2-main-title').attr('oncontextmenu',"add_contextmenu([['menu','finderMenuOpen()'],['line'],['past','finderPasteTo()'],['new_folder','finderCreateNewCatalog()'],['upload',''],['upload_folder',''],['line'],['lock','finderSetPassword()']]);")
    // no password version
    $('.panel-conteiner-all-block-main-2-main-title').attr('oncontextmenu',"add_contextmenu([['menu','finderMenuOpen()'],['line'],['past','finderPasteTo()'],['line'],['new_folder','finderCreateNewCatalog()'],['upload',''],['upload_folder','']]);");
    tmpBloks2 = $('#folderSort').html();
    if(tmpBloks != tmpBloks2){
      finderListing();
    }


    // profile
    profile_block.find('.panel-conteiner-all').css({'width':'calc(100% - 20px)','margin-left':'10px'})
    profile_block.find('.panel-profile-block').css({'border-radius':'15px','margin-bottom':'10px'})
    profile_block.find('.panel-profile-block-2').css({'width':'100%','margin-bottom':'0px'})
    profile_block.find('.panel-profile-block-3').css({'min-width':'auto','width':'100%'})
    profile_block.find('.panel-profile-block-img').css({'left':'0','right':'0','margin':'auto','display':'block'})
    $(profile_block.find('.panel-profile-block')[0]).css({'text-align':'center'})
    profile_block.find('.panel-profile-block-text').css({'margin-left':'0px','width':'100%','margin-top':'15px'})
    profile_block.find('.panel-profile-block-text-name').css({'display':'block','margin-right':'0px'})
    profile_block.find('.panel-profile-block-text-status').css({'margin-top':'10px'})
    profile_block.find('.panel-profile-block-text-login').css({'display':'block'})
    $(profile_block.find('.panel-profile-block-3')).find('.panel-profile-block-conteiner-info-elem').css({'width':'100%','display':'block','border':'none'})
    $(profile_block.find('.panel-profile-block-conteiner-info-elem')[0]).css({'border-bottom':'1px solid var(--border-color)'})
    $(profile_block.find('.panel-profile-block-conteiner-info-elem')[1]).css({'border-bottom':'1px solid var(--border-color)'})


    // global_search
    global_search.find('.panel-conteiner-all').css({'width':'calc(100% - 20px)','margin-left':'10px'})
    global_search.find('.global_search-main').css({'width':'calc(100% - 40px)','min-width':'initial','border-radius':'15px'})
    global_search.find('.global_search-main-search').css({'border-radius':'10px'})
    global_search.find('.global_search-notFound').css({'width':'calc(100% - 40px)','min-width':'initial','border-radius':'15px'})
    global_search.find('.global_search-Found').css({'width':'calc(100% - 40px)','margin-top':'20px','min-width':'initial','border-radius':'15px'})
    global_search.find('.global_search-Found-main-elem').css({'width':'calc(100% - 20px)','border-radius':'10px'})
    global_search.find('.global_search-Found-main-timetable').css({'width':'calc(100% - 5px)','border-radius':'10px'})
    global_search.find('.global_search-Found-main-section').css({'width':'calc(100% - 5px)','border-radius':'10px'})
    global_search.find('.global_search-loader').css({'width':'calc(100% - 40px)','margin-top':'20px','min-width':'initial','border-radius':'15px'})
    global_search.find('.global_search-loader-main-section').css({'width':'calc(100% - 5px)','border-radius':'10px'})


    // statistics
    statistics_block.find('.panel-conteiner-all').css({'width':'calc(100% - 20px)','margin-left':'10px'})
    statistics_block.find('.panel-conteiner-main-block').css({'border-radius':'15px','height':'415px'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4')[0]).css({'border-radius':'15px','width':'100%'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4')[1]).css({'border-radius':'15px','width':'100%','margin-right':'-initial'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4')[2]).css({'border-radius':'15px','width':'100%','margin-top':'initial'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4')[3]).css({'border-radius':'15px','width':'100%','margin-right':'initial','margin-top':'initial'})
    statistics_block.find('.panel-conteiner-all > span').css({'margin-top':'20px'})
    statistics_block.find('.panel-conteiner-all > span > .panel-conteiner-main-block_divide_by_4').css({'margin-bottom':'20px'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4_2').css({'height':'auto','width':'100%'})
    statistics_block.find('.panel-conteiner-width-small').css({'border-radius':'15px','width':'100%'})
    $(statistics_block.find('.panel-conteiner-width-small')[0]).css('margin-top','-20px')
    $(statistics_block.find('.panel-conteiner-width-small')[1]).css({'margin-top':'20px','margin-left':'0px'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4_3').css({'width':'100%','margin-top':'20px','height':'auto'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4_3-block').css({'width':'100%','border-radius':'15px'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4_3-block')[1]).css({'margin-left':'0px','margin-top':'20px','padding-bottom':'19px'})
    statistics_block.find('.panel-conteiner-width2').css('width','100%')
    $(statistics_block.find('.panel-conteiner-width2')[1]).css({'margin-left':'0px','margin-top':'20px'})
    statistics_block.find('.panel-conteiner-main-block').css('height','auto')
    statistics_block.find('.panel-conteiner-main-block-hei139').css({'border':'none','width':'100%'})
    $(statistics_block.find('.panel-conteiner-main-block-hei139')[0]).css('border-bottom','1px solid var(--border-color)')
    $(statistics_block.find('.panel-conteiner-main-block-hei139')[1]).css('border-bottom','1px solid var(--border-color)')
    $(statistics_block.find('.panel-conteiner-main-block-hei139')[2]).css('border-bottom','1px solid var(--border-color)')
    statistics_block.find('.panel-conteiner-main-block_divide_by_4-conteiner-news-search').css('dispay','block')
    statistics_block.find('#panel-conteiner-main-block_divide_by_4-conteiner-news-search-span').css('display','none')
    statistics_block.find('.panel-conteiner-main-block_divide_by_4-conteiner-news-search').css({'height':'auto','width':'100%'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4-conteiner-news-list').css({'width':'100%','margin-left':'0px','margin-bottom':'20px'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4-conteiner').css('height','auto')
    $($(statistics_block.find('.panel-conteiner-width2').children()[2]).parent().parent().find('.panel-conteiner-main-block_divide_by_4')[0]).css({'border-radius':'15px','margin-left':'0px','margin-right':'0px','margin-top':'20px','width':'calc(calc(100% / 1) - 0px)'})
    $($(statistics_block.find('.panel-conteiner-width2').children()[2]).parent().parent().find('.panel-conteiner-main-block_divide_by_4')[1]).css({'border-radius':'15px','margin-left':'0px','margin-top':'0px','width':'calc(calc(100% / 1) - 0px)'})
    $($(statistics_block.find('.panel-conteiner-width2').children()[2]).parent().parent().find('.panel-conteiner-main-block_divide_by_4').find('.panel-conteiner-main-block_divide_by_4-conteiner-news-list')).css({'margin-left':'0px','padding-right':'10px','width':'calc(100% - 5px)'})
    statistics_block.find('#stat-map-block').css('margin-top','0px')
    statistics_block.find('#stat-map').css({'width':'100%','height':'174px'})
    statistics_block.find('#stat-map-block').find('.panel-conteiner-main-block_divide_by_4-conteiner-map-elem').css({'width':'100%','margin-bottom':'20px'})
    $(statistics_block.find('#stat-map-block').find('.panel-conteiner-width2')[0]).css('margin-bottom','20px')
    $(statistics_block.find('#stat-map-block').find('.panel-conteiner-width2')[1]).css('margin-left','0px')
    $(statistics_block.find('#stat-map-block').find('.panel-conteiner-width2')[1]).find('.panel-conteiner-main-block').css('padding-bottom','15px')
    statistics_block.find('#panel-conteiner-main-block-about').css({'margin-top':'20px','height':'45px','margin-bottom':'-25px'})



  }

  // tablet
  else if(widthMainBlock <= 1112){

    adaptiveDesignS = 'tablet';

    // other
    $('.window-block-sale-img').css({'width':'400px'})
    $('.panel-conteiner-width-small-footer-elem1-span').find('span').css('font-size','14px');
    $('.panel-conteiner-width-small4-text-desc').css('font-size','14px');
    $('.panel-conteiner-width-small-main-elem-block1').find('span').css('font-size','30px');
    $('.main-nav-search-2').css('border-radius','5px')
    $('#stat-map').text(' ');
    $('.visible-phone').css('display','none')

    // main
    $($('#main').find('.panel-conteiner-width')).css({'margin-left':'40px','width':'calc(100% - 80px)','margin-bottom':'20px'})
    $($('#main').find('.panel-conteiner-width')[0]).find('.panel-conteiner-main-block').css('border-radius','6px')
    $('#chart1').css('width','calc(100% - 20px)')
    $('.panel-title').css('display','block')
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small').css({'width':'calc(50% - 10px)','border-radius':'6px','margin-bottom':'initial'})
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small2').css({'width':'calc(50% - 40px)','margin-bottom':'initial'})
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small2').find('.panel-conteiner-width-small2-main').css('border-radius','6px')
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small2').find('.panel-conteiner-width-small2-header').css('border-radius','6px')
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small3').css({'width':'calc(50% - 10px)','border-radius':'6px','margin-bottom':'-initial'})
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small4').css({'width':'calc(50% - 40px)','border-radius':'6px','margin-bottom':'initial'})

    // global_search
    global_search.find('.panel-conteiner-all').css({'width':'calc(100% - 40px)','margin-left':'20px'})
    global_search.find('.global_search-main').css({'width':'calc(50.2% - 40px)','min-width':'620px','border-radius':'6px'})
    global_search.find('.global_search-main-search').css({'border-radius':'4px'})
    global_search.find('.global_search-notFound').css({'width':'calc(50.2% - 40px)','min-width':'620px','border-radius':'6px'})
    global_search.find('.global_search-Found').css({'width':'calc(50.2% - 40px)','margin-top':'40px','min-width':'620px','border-radius':'6px'})
    global_search.find('.global_search-Found-main-elem').css({'width':'220px','border-radius':'4px'})
    global_search.find('.global_search-Found-main-timetable').css({'width':'calc(50% - 16px)','border-radius':'4px'})
    global_search.find('.global_search-Found-main-section').css({'width':'calc(50% - 16px)','border-radius':'4px'})
    global_search.find('.global_search-loader').css({'width':'calc(50.2% - 40px)','margin-top':'40px','min-width':'620px','border-radius':'6px'})
    global_search.find('.global_search-loader-main-section').css({'width':'calc(50% - 16px)','border-radius':'4px'})


    // general_chat
    global_chat.find('.panel-conteiner').css({'display':'none'})
    global_chat.find('.panel-conteiner-full').css({
      'width':'calc(100% - 80px)',
      'margin-top':'0px',
      'margin-left':'40px'
    })
    global_chat.find('.panel-msg').css({
      'border-radius':'6px',
      'height':'calc(100vh - 202px)'
    })
    global_chat.find('.panel-msg-block-msg-textinput-file').css({
      'width':'37px',
      'margin-left':'15px',
      'border-radius':'6px'
    })
    global_chat.find('.panel-msg-block-msg-textinput-textarea').css({
      'width':'calc(100% - 155px)',
      'margin-left':'10px',
      'border-radius':'6px'
    })
    global_chat.find('.panel-msg-block-msg-textinput-send').css({
      'margin-left':'15px',
      'border-radius':'6px'
    })
    global_chat.find('.panel-msg-block-msg-textinput').css({
      'position':'relative',
      'white-space':'normal',
      'bottom':'initial'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-help').css({
      'margin-top':'15px',
      'height':'15px'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-block').css({
      'width':'calc(100% - 40px)',
      'padding-left':'20px',
      'padding-right':'20px'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-block-inv').css({
      'width':'calc(100% - 40px)',
      'padding-left':'20px'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-block-inv').find('.panel-msg-block-msg-conteiner-main-conteiner-block-photo').css({
      'display':'inline-block'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv').css({
      'margin-right':'48px'
    })
    $('.emoji-block').css({
      'bottom':'40px',
      'position':'absolute',
      'right':'-132px',
      'left':'initial',
      'margin':'initial',
      'width':'260px',
      'border-radius':'6px'
    })
    $('.panel-conteiner-all-block-main-2-main-btnTop2').css({
      'position':'sticky',
      'top':'calc(100% - 60px)'
    })

    // profile
    profile_block.find('.panel-conteiner-all').css({'width':'calc(100% - 80px)','margin-left':'40px'})
    profile_block.find('.panel-profile-block').css({'border-radius':'6px','margin-bottom':'25px'})
    profile_block.find('.panel-profile-block-2').css({'width':'270px','margin-bottom':'25px'})
    profile_block.find('.panel-profile-block-3').css({'min-width':'450','width':'calc(100% - 294px)'})
    profile_block.find('.panel-profile-block-img').css({'left':' ','right':' ','margin':' ','margin-left':'30px','display':'inline-block'})
    $(profile_block.find('.panel-profile-block')[0]).css({'text-align':'left'})
    profile_block.find('.panel-profile-block-text').css({'margin-left':'30px','width':'calc(100% - 220px)','margin-top':' '})
    profile_block.find('.panel-profile-block-text-name').css({'display':'inline-block','margin-right':'15px'})
    profile_block.find('.panel-profile-block-text-status').css({'margin-top':' '})
    profile_block.find('.panel-profile-block-text-login').css({'display':'block'})
    $(profile_block.find('.panel-profile-block-3')).find('.panel-profile-block-conteiner-info-elem').css({'width':'calc(calc(100% / 3) - 1px)','display':'inline-block','border':'none'})
    $(profile_block.find('.panel-profile-block-conteiner-info-elem')[0]).css({'border-right':'1px solid var(--border-color)'})
    $(profile_block.find('.panel-profile-block-conteiner-info-elem')[1]).css({'border-right':'1px solid var(--border-color)'})


    // statistics
    statistics_block.find('.panel-conteiner-all').css({'width':'calc(100% - 80px)','margin-left':'40px'})
    statistics_block.find('.panel-conteiner-main-block').css({'border-radius':'6px','height':'394px'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4')[0]).css({'border-radius':'6px','width':'calc(calc(100% / 2) - 20px)'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4')[1]).css({'border-radius':'6px','width':'calc(calc(100% / 2) - 20px)','margin-right':'-4px'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4')[2]).css({'border-radius':'6px','width':'calc(calc(100% / 2) - 20px)','margin-top':'40px'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4')[3]).css({'border-radius':'6px','width':'calc(calc(100% / 2) - 20px)','margin-right':'-4px','margin-top':'40px'})
    statistics_block.find('.panel-conteiner-all > span').css({'margin-top':'40px'})
    statistics_block.find('.panel-conteiner-all > span > .panel-conteiner-main-block_divide_by_4').css({'margin-bottom':'initial'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4_2').css({'height':'auto','width':'100%'})
    statistics_block.find('.panel-conteiner-width-small').css({'border-radius':'6px','width':'calc(50% - 20px)'})
    $(statistics_block.find('.panel-conteiner-width-small')[0]).css({'margin-top':'0px','margin-right':'0px'})
    $(statistics_block.find('.panel-conteiner-width-small')[1]).css({'margin-top':'0px','margin-left':'36px','margin-right':'0px'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4_3').css({'width':'100%','margin-top':'40px','height':'auto'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4_3-block').css({'width':'100%','border-radius':'6px'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4_3-block')[1]).css({'margin-left':'0px','margin-top':'40px','padding-bottom':'19px'})
    statistics_block.find('.panel-conteiner-width2').css('width','100%')
    $(statistics_block.find('.panel-conteiner-width2')[1]).css({'margin-left':'0px','margin-top':'40px'})
    statistics_block.find('.panel-conteiner-main-block').css('height','auto')
    statistics_block.find('.panel-conteiner-main-block-hei139').css({'border':'none','width':'100%'})
    $(statistics_block.find('.panel-conteiner-main-block-hei139')[0]).css({'width':'50%','border-bottom':'1px solid var(--border-color)','border-right':'1px solid var(--border-color)'})
    $(statistics_block.find('.panel-conteiner-main-block-hei139')[1]).css({'width':'calc(50% - 1px)','border-bottom':'1px solid var(--border-color)'})
    $(statistics_block.find('.panel-conteiner-main-block-hei139')[2]).css({'width':'50%','border-right':'1px solid var(--border-color)'})
    $(statistics_block.find('.panel-conteiner-main-block-hei139')[3]).css({'width':'calc(50% - 1px)'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4-conteiner-news-search').css('dispay','inline-block')
    statistics_block.find('#panel-conteiner-main-block_divide_by_4-conteiner-news-search-span').css('display','block')
    statistics_block.find('.panel-conteiner-main-block_divide_by_4-conteiner-news-search').css({'height':'320px','width':'180px'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4-conteiner-news-list').css({'width':'calc(100% - 195px)','margin-left':'15px','margin-bottom':'20px'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4-conteiner').css('height','auto')
    $($(statistics_block.find('.panel-conteiner-width2').children()[2]).parent().parent().find('.panel-conteiner-main-block_divide_by_4')[0]).css({'border-radius':'6px','margin-left':'0px','margin-right':'40px','margin-top':'40px','width':'calc(calc(100% / 2) - 20px)'})
    $($(statistics_block.find('.panel-conteiner-width2').children()[2]).parent().parent().find('.panel-conteiner-main-block_divide_by_4')[1]).css({'border-radius':'6px','margin-left':'0px','margin-top':'40px','width':'calc(calc(100% / 2) - 20px)'})
    $($(statistics_block.find('.panel-conteiner-width2').children()[2]).parent().parent().find('.panel-conteiner-main-block_divide_by_4').find('.panel-conteiner-main-block_divide_by_4-conteiner-news-list')).css({'margin-left':'0px','padding-right':'10px','width':'calc(100% - 5px)'})
    statistics_block.find('#stat-map-block').css('margin-top','40px')
    statistics_block.find('#stat-map-block').find('.panel-conteiner-main-block_divide_by_4-conteiner-map-elem').css({'height':'auto','width':'220px','margin-bottom':'20px'})
    statistics_block.find('#stat-map').css({'width':'calc(100% - 235px)','height':'300px'})
    $(statistics_block.find('#stat-map-block').find('.panel-conteiner-width2')[0]).css('margin-bottom','40px')
    $(statistics_block.find('#stat-map-block').find('.panel-conteiner-width2')[1]).css('margin-left','0px')
    $(statistics_block.find('#stat-map-block').find('.panel-conteiner-width2')[1]).find('.panel-conteiner-main-block').css('padding-bottom','15px')
    statistics_block.find('#panel-conteiner-main-block-about').css({'margin-top':'40px','height':'45px','margin-bottom':'0px'})

    // finder
    finderMenuClose();
    closeContextMenu();
    finder_block.find('.panel-conteiner-all').css({'width':'calc(100% - 80px)','margin-left':'40px','user-select':'none'})
    finder_block.find('.panel-conteiner-all-block-2').css({'border-radius':'6px','height':'calc(100vh - 193px)','margin-bottom':'25px','margin-top':' 0px'})
    finder_block.find('.file_manager-action').css({'display':'block'})
    finder_block.find('.file_manager-action-btn').css({'padding-left':'2.5px','padding-right':'2.5px'})
    finder_block.find('.file_manager-action-btn-none').css({'padding-left':'2.5px','padding-right':'2.5px'})
    finder_block.find('.file_manager-btn-action-2').css({'margin-left':'4px','width':'calc(100% - 274px)'})
    finder_block.find('.file_manager-btn-action-3').css({'display':'inline-block'})
    finder_block.find('.panel-conteiner-all-block-main-2-nav').css({'position':'absolute','z-index':'9','background-color':'var(--white)','transform':'translate(-100%, 0px)'})
    finder_block.find('.panel-conteiner-all-block-main-2-main').css({'width':'calc(100% + 4px)'})
    finder_block.find('.panel-conteiner-all-block-main-2-main-elem-ch').css({'display':'inline-block'})
    $(finder_block.find('.panel-conteiner-all-block-main-2-main-filter-elem')).css({'width':'calc(calc(100% / 7) - 10px)'})
    $(finder_block.find('.panel-conteiner-all-block-main-2-main-filter-elem')[0]).css({'margin-left':'110px','width':'31%'})
    $(finder_block.find('.panel-conteiner-all-block-main-2-main-filter-elem')[2]).css({'display':'inline-block'})
    finder_block.find('.panel-conteiner-all-block-main-2').css({'height':'calc(100% - 71px)'})
    finder_block.find('.file_manager-contextmenu').css({'position':'absolute','left':'0px','bottom':'initial','width':'275px'})
    $('.file_manager-contextmenu').css({'border-radius':'7.5px'})
    // old version
    //$('.panel-conteiner-all-block-main-2-main-title').attr('oncontextmenu',"add_contextmenu([['menu','finderMenuOpen()'],['line'],['past','finderPasteTo()'],['new_folder','finderCreateNewCatalog()'],['upload',''],['upload_folder',''],['line'],['lock','finderSetPassword()']]);")
    // no password version
    $('.panel-conteiner-all-block-main-2-main-title').attr('oncontextmenu',"add_contextmenu([['menu','finderMenuOpen()'],['line'],['past','finderPasteTo()'],['line'],['new_folder','finderCreateNewCatalog()'],['upload',''],['upload_folder','']]);")
    tmpBloks2 = $('#folderSort').html();
    if(tmpBloks != tmpBloks2){
      finderListing();
    }


  }

  // PC
  else{

    adaptiveDesignS = 'PC';

    // other
    if(widthMainBlock < 1231){
      $('.panel-conteiner-width-small-footer-elem1-span').find('span').css('font-size','0px');
    } else{
      $('.panel-conteiner-width-small-footer-elem1-span').find('span').css('font-size','14px');
    }
    if(widthMainBlock < 1427){
      $('.panel-conteiner-width-small4-text-desc').css('font-size','0px');
    } else{
      $('.panel-conteiner-width-small4-text-desc').css('font-size','14px');
    }
    if(widthMainBlock < 1500){
      $('.panel-conteiner-width-small-main-elem-block1').find('span').css('font-size','18px');
    } else{
      $('.panel-conteiner-width-small-main-elem-block1').find('span').css('font-size','30px');
    }
    $('.window-block-sale-img').css({'width':'400px'})
    $('.visible-phone').css('display','none')

    // main
    $($('#main').find('.panel-conteiner-width')).css({'margin-left':'40px','width':'calc(50% - 60px)','margin-bottom':'initial'})
    $($('#main').find('.panel-conteiner-width')[0]).find('.panel-conteiner-main-block').css('border-radius','6px')
    $('#chart1').css('width','calc(100% - 20px)')
    $('.panel-title').css('display','block')
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small').css({'width':'calc(50% - 10px)','border-radius':'6px','margin-bottom':'initial'})
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small2').css({'width':'calc(50% - 40px)','margin-bottom':'initial'})
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small2').find('.panel-conteiner-width-small2-main').css('border-radius','6px')
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small2').find('.panel-conteiner-width-small2-header').css('border-radius','6px')
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small3').css({'width':'calc(50% - 10px)','border-radius':'6px','margin-bottom':'-initial'})
    $($('#main').find('.panel-conteiner-width')[1]).find('.panel-conteiner-width-small4').css({'width':'calc(50% - 40px)','border-radius':'6px','margin-bottom':'initial'})

    // general_chat
    global_chat.find('.panel-conteiner').css({'display':'inline-block'})
    global_chat.find('.panel-conteiner-full').css({
      'width':'calc(100% - 375px)',
      'margin-top':'0px',
      'margin-left':'40px'
    })
    global_chat.find('.panel-msg').css({
      'border-radius':'6px',
      'height':'calc(100vh - 202px)'
    })
    global_chat.find('.panel-msg-block-msg-textinput-file').css({
      'width':'37px',
      'margin-left':'15px',
      'border-radius':'6px'
    })
    global_chat.find('.panel-msg-block-msg-textinput-textarea').css({
      'width':'calc(100% - 155px)',
      'margin-left':'10px',
      'border-radius':'6px'
    })
    global_chat.find('.panel-msg-block-msg-textinput-send').css({
      'margin-left':'15px',
      'border-radius':'6px'
    })
    global_chat.find('.panel-msg-block-msg-textinput').css({
      'position':'relative',
      'white-space':'normal',
      'bottom':'initial'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-help').css({
      'margin-top':'15px',
      'height':'15px'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-block').css({
      'width':'calc(100% - 40px)',
      'padding-left':'20px',
      'padding-right':'20px'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-block-inv').css({
      'width':'calc(100% - 40px)',
      'padding-left':'20px'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-block-inv').find('.panel-msg-block-msg-conteiner-main-conteiner-block-photo').css({
      'display':'inline-block'
    })
    global_chat.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv').css({
      'margin-right':'48px'
    })
    $('.emoji-block').css({
      'bottom':'40px',
      'position':'absolute',
      'right':'-132px',
      'left':'initial',
      'margin':'initial',
      'width':'260px',
      'border-radius':'6px'
    })
    $('.panel-conteiner-all-block-main-2-main-btnTop2').css({
      'position':'sticky',
      'top':'calc(100% - 60px)'
    })

    // global_search
    global_search.find('.panel-conteiner-all').css({'width':'calc(100% - 40px)','margin-left':'20px'})
    global_search.find('.global_search-main').css({'width':'calc(50.2% - 40px)','min-width':'620px','border-radius':'6px'})
    global_search.find('.global_search-main-search').css({'border-radius':'4px'})
    global_search.find('.global_search-notFound').css({'width':'calc(50.2% - 40px)','min-width':'620px','border-radius':'6px'})
    global_search.find('.global_search-Found').css({'width':'calc(50.2% - 40px)','margin-top':'40px','min-width':'620px','border-radius':'6px'})
    global_search.find('.global_search-Found-main-elem').css({'width':'220px','border-radius':'4px'})
    global_search.find('.global_search-Found-main-timetable').css({'width':'calc(50% - 16px)','border-radius':'4px'})
    global_search.find('.global_search-Found-main-section').css({'width':'calc(50% - 16px)','border-radius':'4px'})
    global_search.find('.global_search-loader').css({'width':'calc(50.2% - 40px)','margin-top':'40px','min-width':'620px','border-radius':'6px'})
    global_search.find('.global_search-loader-main-section').css({'width':'calc(50% - 16px)','border-radius':'4px'})


    // statistics
    var statistics_block = $('#statistics');
    statistics_block.find('.panel-conteiner-all').css({'width':'calc(100% - 80px)','margin-left':'40px'})
    statistics_block.find('.panel-conteiner-main-block').css({'border-radius':'6px','height':'auto'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4')[0]).css({'border-radius':'6px','width':'calc(calc(100% / 4) - 30px)','margin-right':'37px'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4')[1]).css({'border-radius':'6px','width':'calc(calc(100% / 4) - 30px)','margin-right':'37px'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4')[2]).css({'border-radius':'6px','width':'calc(calc(100% / 4) - 30px)','margin-top':'0px','margin-right':'37px'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4')[3]).css({'border-radius':'6px','width':'calc(calc(100% / 4) - 30px)','margin-right':'-4px','margin-top':'0px'})
    statistics_block.find('.panel-conteiner-all > span').css({'margin-top':'40px'})
    statistics_block.find('.panel-conteiner-all > span > .panel-conteiner-main-block_divide_by_4').css({'margin-bottom':'initial'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4_2').css({'height':'auto','width':'calc(calc(100% / 4) - 30px)'})
    statistics_block.find('.panel-conteiner-width-small').css({'border-radius':'6px','width':'100%'})
    $(statistics_block.find('.panel-conteiner-width-small')[0]).css({'margin-top':'0px','margin-right':'36px'})
    $(statistics_block.find('.panel-conteiner-width-small')[1]).css({'margin-top':'40px','margin-left':'0','margin-right':'36px'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4_3').css({'width':'calc(calc(100% / 4) * 3 - 11px)','margin-top':'0px','height':'334px'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4_3-block').css({'width':'calc(100% / 2 - 20px)','border-radius':'6px'})
    $(statistics_block.find('.panel-conteiner-main-block_divide_by_4_3-block')[1]).css({'margin-left':'40px','margin-top':'0px','padding-bottom':'0px'})
    statistics_block.find('.panel-conteiner-width2').css('width','calc(50% - 20px)')
    $(statistics_block.find('.panel-conteiner-width2')[1]).css({'margin-left':'40px','margin-top':'0px'})
    statistics_block.find('.panel-conteiner-main-block-hei139').css({'border':'none','width':'100%'})
    $(statistics_block.find('.panel-conteiner-main-block-hei139')[0]).css({'width':'calc((100% / 4) - 1px)','border-right':'1px solid var(--border-color)'})
    $(statistics_block.find('.panel-conteiner-main-block-hei139')[1]).css({'width':'calc((100% / 4) - 1px)','border-right':'1px solid var(--border-color)'})
    $(statistics_block.find('.panel-conteiner-main-block-hei139')[2]).css({'width':'calc((100% / 4) - 1px)','border-right':'1px solid var(--border-color)'})
    $(statistics_block.find('.panel-conteiner-main-block-hei139')[3]).css({'width':'calc((100% / 4) - 1px)'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4-conteiner-news-search').css('dispay','inline-block')
    statistics_block.find('#panel-conteiner-main-block_divide_by_4-conteiner-news-search-span').css('display','block')
    statistics_block.find('.panel-conteiner-main-block_divide_by_4-conteiner-news-search').css({'height':'320px','width':'180px'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4-conteiner-news-list').css({'width':'calc(100% - 195px)','margin-left':'15px','margin-bottom':'20px'})
    statistics_block.find('.panel-conteiner-main-block_divide_by_4-conteiner').css('height','auto')
    $($(statistics_block.find('.panel-conteiner-width2').children()[2]).parent().parent().find('.panel-conteiner-main-block_divide_by_4')[0]).css({'border-radius':'6px','margin-left':'40px','margin-right':'0px','margin-top':'0px','width':'calc(calc(100% / 4) - 30px)'})
    $($(statistics_block.find('.panel-conteiner-width2').children()[2]).parent().parent().find('.panel-conteiner-main-block_divide_by_4')[1]).css({'border-radius':'6px','margin-left':'36px','margin-top':'0px','width':'calc(calc(100% / 4) - 30px)'})
    $($(statistics_block.find('.panel-conteiner-width2').children()[2]).parent().parent().find('.panel-conteiner-main-block_divide_by_4').find('.panel-conteiner-main-block_divide_by_4-conteiner-news-list')).css({'margin-left':'0px','padding-right':'10px','width':'calc(100% - 5px)'})
    statistics_block.find('#stat-map-block').css('margin-top','40px')
    statistics_block.find('#stat-map-block').find('.panel-conteiner-main-block_divide_by_4-conteiner-map-elem').css({'height':'auto','width':'220px','margin-bottom':'20px'})
    statistics_block.find('#stat-map').css({'width':'calc(100% - 235px)','height':'300px'})
    $(statistics_block.find('#stat-map-block').find('.panel-conteiner-width2')[0]).css('margin-bottom','40px')
    $(statistics_block.find('#stat-map-block').find('.panel-conteiner-width2')[1]).css('margin-left','40px')
    $(statistics_block.find('#stat-map-block').find('.panel-conteiner-width2')[1]).find('.panel-conteiner-main-block').css('padding-bottom','0px')
    statistics_block.find('#panel-conteiner-main-block-about').css({'margin-top':'40px','height':'58px','margin-bottom':'0px'})

    // profile
    profile_block.find('.panel-conteiner-all').css({'width':'calc(100% - 80px)','margin-left':'40px'})
    profile_block.find('.panel-profile-block').css({'border-radius':'6px','margin-bottom':'25px'})
    profile_block.find('.panel-profile-block-2').css({'width':'270px','margin-bottom':'25px'})
    profile_block.find('.panel-profile-block-3').css({'min-width':'450','width':'calc(100% - 294px)'})
    profile_block.find('.panel-profile-block-img').css({'left':' ','right':' ','margin':' ','margin-left':'30px','display':'inline-block'})
    $(profile_block.find('.panel-profile-block')[0]).css({'text-align':'left'})
    profile_block.find('.panel-profile-block-text').css({'margin-left':'30px','width':'calc(100% - 220px)','margin-top':' '})
    profile_block.find('.panel-profile-block-text-name').css({'display':'inline-block','margin-right':'15px'})
    profile_block.find('.panel-profile-block-text-status').css({'margin-top':' '})
    profile_block.find('.panel-profile-block-text-login').css({'display':'block'})
    $(profile_block.find('.panel-profile-block-3')).find('.panel-profile-block-conteiner-info-elem').css({'width':'calc(calc(100% / 3) - 1px)','display':'inline-block','border':'none'})
    $(profile_block.find('.panel-profile-block-conteiner-info-elem')[0]).css({'border-right':'1px solid var(--border-color)'})
    $(profile_block.find('.panel-profile-block-conteiner-info-elem')[1]).css({'border-right':'1px solid var(--border-color)'})


    // finder
    finderMenuClose();
    closeContextMenu();
    finder_block.find('.panel-conteiner-all').css({'width':'calc(100% - 80px)','margin-left':'40px','user-select':'none'})
    finder_block.find('.panel-conteiner-all-block-2').css({'border-radius':'6px','height':'calc(100vh - 193px)','margin-bottom':'25px','margin-top':' 0px'})
    finder_block.find('.file_manager-action').css({'display':'block'})
    finder_block.find('.file_manager-action-btn').css({'padding-left':'7.5px','padding-right':'7.5px'})
    finder_block.find('.file_manager-action-btn-none').css({'padding-left':'7.5px','padding-right':'7.5px'})
    finder_block.find('.file_manager-btn-action-2').css({'margin-left':'4px','width':'calc(100% - 295px)'})
    finder_block.find('.file_manager-btn-action-3').css({'display':'inline-block'})
    finder_block.find('.panel-conteiner-all-block-main-2-nav').css({'position':'relative','z-index':'9','background-color':'var(--white)','transform':'translate(00%, 0px)'})
    finder_block.find('.panel-conteiner-all-block-main-2-main').css({'width':'calc(100% - 250px)'})
    finder_block.find('.panel-conteiner-all-block-main-2-main-elem-ch').css({'display':'inline-block'})
    $(finder_block.find('.panel-conteiner-all-block-main-2-main-filter-elem')).css({'width':'calc(calc(100% / 7) - 10px)'})
    $(finder_block.find('.panel-conteiner-all-block-main-2-main-filter-elem')[0]).css({'margin-left':'110px','width':'31%'})
    $(finder_block.find('.panel-conteiner-all-block-main-2-main-filter-elem')[2]).css({'display':'inline-block'})
    finder_block.find('.panel-conteiner-all-block-main-2').css({'height':'calc(100% - 71px)'})
    finder_block.find('.file_manager-contextmenu').css({'position':'absolute','left':'0px','bottom':'initial','width':'275px'})
    $('.file_manager-contextmenu').css({'border-radius':'7.5px'})
    // old version
    //$('.panel-conteiner-all-block-main-2-main-title').attr('oncontextmenu',"add_contextmenu([['past','finderPasteTo()'],['new_folder','finderCreateNewCatalog()'],['upload',''],['upload_folder',''],['line'],['lock','finderSetPassword()']]);")
    // no password version
    $('.panel-conteiner-all-block-main-2-main-title').attr('oncontextmenu',"add_contextmenu([['past','finderPasteTo()'],['line'],['new_folder','finderCreateNewCatalog()'],['upload',''],['upload_folder','']]);")
    tmpBloks2 = $('#folderSort').html();
    if(tmpBloks != tmpBloks2){
      finderListing();
    }



  }

  if(adaptiveDesignS == 'phone'){
    $('.main-nav-profile-notification > span').css({
      'display':'none',
    })
    $('.main-nav-profile-notification').css({
      'height':'0px',
      'width':'0px'
    })
    $('.main-nav-profile-mail > span').css({
      'display':'none',
    })
    $('.main-nav-profile-mail').css({
      'height':'0px',
      'width':'0px'
    })
    $('.elemNotiPanelNav').css({
      'display':'block'
    })
  } else{
    $('.elemNotiPanelNav').css({
      'display':'none'
    })
    $('.main-nav-profile-notification').css({
      'display':'inline-block',
      'height':'40px',
      'width':'40px'
    })
    $('.main-nav-profile-notification > span').css('display','block')
    $('.main-nav-profile-mail').css({
      'display':'inline-block',
      'height':'40px',
      'width':'40px'
    })
    $('.main-nav-profile-mail > span').css('display','block')
  }

  setTimeout(function(){
    updateChartsNew(theme_chart);
  }, 50)
  setTimeout(function(){
    if($('#statistics').css('display') == 'block'){
      statMap('#stat-map');
    }
  }, 250)
}

function screenUser(type,status,degug){
  if(true){
    if(degug != true || degug == undefined){
      degug = false;
    } else{
      degug = true;
      console.log(type + ' screen')
    }

    if(status != true || status == undefined){
      status = false;
    } else{
      status = true;
    }

    $('html').get(0).style.setProperty('--height-filter', $('.panel-conteiner-all-block-filter').outerHeight())

    if(document.documentElement.clientWidth <= 551){
      $('#hello > .window-block').css('width','95%')
      $('#hello > .window-block').css('max-height','98vh')
      $('#hello > .window-block').css('border-radius','20px')
      $('#hello > .window-block').css('overflow','auto')
      $('#hello > .window-block').css('bottom','8px')
      $('#hello > .window-block').css('left','0')
      $('#hello > .window-block').css('right','0')
      $('#hello > .window-block').css('margin-top','8px')
      $('#hello > .window-block').css('transform','initial')
      $('#hello > .window-block').css('margin-left','initial')
      $('#hello > .window-block').css('margin','auto')
      $('#hello > .window-block').css('position','fixed')
      $('.window-block-hello-img').css('width','100%')
      $('.window-block-hello-img-title').css('width','95%')
      $('.window-block-hello-img-title').css('left','0')
      $('.window-block-hello-img-title').css('right','0')
      $('.window-block-hello-img-title').css('margin','auto')
      $('.window-block-hello-block').css('width','100%')
      $('.window-block-hello-block-conteiner-stage1-gender').css('margin-left','')
      $('.window-block-hello-block-conteiner-stage1-gender').css('width','264px')
      $('.window-block-hello-block-conteiner-stage1-gender').css('left','0')
      $('.window-block-hello-block-conteiner-stage1-gender').css('right','0')
      $('.window-block-hello-block-conteiner-stage1-gender').css('margin','auto')
      $('.window-block-hello-block-conteiner-stage3-chb').css('margin-left','0px')
      $('.checkbox-login > .checkbox-login-chb3').css('width','calc(100% + 15px)')
      $('.window-block-hello-block-conteiner-stage1-gender > .checkbox-login').css('margin-left','0px')
    } else{
      $('#hello > .window-block').css('width','auto')
      $('#hello > .window-block').css('max-height','95vh')
      $('#hello > .window-block').css('border-radius','7.5px')
      $('#hello > .window-block').css('overflow','')
      $('.window-block-hello-img').css('width','450px')
      $('.window-block-hello-img-title').css('width','95%')
      $('.window-block-hello-img-title').css('left','0')
      $('.window-block-hello-img-title').css('right','0')
      $('.window-block-hello-img-title').css('margin','auto')
      $('.window-block-hello-block').css('width','450px')
      $('.window-block-hello-block-conteiner-stage1-gender').css('margin-left','66px')
      $('.window-block-hello-block-conteiner-stage1-gender').css('width','')
      $('.window-block-hello-block-conteiner-stage1-gender').css('left','')
      $('.window-block-hello-block-conteiner-stage1-gender').css('right','')
      $('.window-block-hello-block-conteiner-stage1-gender').css('margin','')
      $('.window-block-hello-block-conteiner-stage3-chb').css('margin-left','-70px')
      $('#hello > .window-block').css('bottom','')
      $('#hello > .window-block').css('left','')
      $('#hello > .window-block').css('right','')
      $('#hello > .window-block').css('margin-top','50vh')
      $('#hello > .window-block').css('transform','translate(-50%, -50%)')
      $('#hello > .window-block').css('margin-left','50vw')
      $('#hello > .window-block').css('margin','')
      $('#hello > .window-block').css('position','absolute')
      $('.checkbox-login > .checkbox-login-chb3').css('width','calc(100% - 65px)')
    }

    // if(document.documentElement.clientWidth <= 995){
    //   $('.panel-conteiner-all').css('width','calc(100% - 40px)')
    //   $('.panel-conteiner-all').css('margin-left','20px')
    //   $('.panel-profile-block').css('border-radius','15px')
    //   $('.panel-profile-block').css('margin-bottom','20px')
    //   $('.panel-profile-block-img').css('display','block')
    //   $('.panel-profile-block-img').css('vertical-align','initial')
    //   $('.panel-profile-block-img').css('margin-left','initial')
    //   $('.panel-profile-block-img').css('left','0')
    //   $('.panel-profile-block-img').css('right','0')
    //   $('.panel-profile-block-img').css('margin','auto')
    //   $('.panel-profile-block-text').css('display','block')
    //   $('.panel-profile-block-text').css('vertical-align','top')
    //   $('.panel-profile-block-text').css('padding-top','17px')
    //   $('.panel-profile-block-text').css('width','100%')
    //   $('.panel-profile-block-text').css('margin-left','initial')
    //   $('.panel-profile-block-text').css('overflow','initial')
    //   $('.panel-profile-block-text').css('text-align','center')
    //   $('.panel-profile-block-text').css('left','0')
    //   $('.panel-profile-block-text').css('right','0')
    //   $('.panel-profile-block-text').css('margin','auto')
    //   $('.panel-profile-block-text-name').css('display','block')
    //   $('.panel-profile-block-text-name').css('margin-right','initial')
    //   $('.panel-profile-block-text-status').css('margin-top','5px')
    //   $('.panel-profile-block-text-status').css('vertical-align','initial')
    //   $('.panel-profile-block-text-btn').css('margin-top','-11px')
    //   $('.panel-profile-block-2').css('width','100%')
    //   $('.panel-profile-block-3').css('width','calc(100% - 0px)')
    //   $('.panel-profile-block-3').css('min-width','initial')
    //   $('.panel-profile-block-3').css('margin-top','-20px')
    //   $('.panel-profile-block-conteiner-info').css('margin-top','15px')
    //   $('.panel-profile-block-conteiner-info').css('border-top','initial')
    //   $('.panel-profile-block-conteiner-info-elem').css('border-right','initial')
    //   $('.panel-profile-block-conteiner-info-elem').css('width','100%')
    //   $('.panel-profile-block-conteiner-info-elem').css('vertical-align','initial')
    //   $('.panel-profile-block-conteiner-info-elem').css('margin-right','initial')
    //   $('.panel-profile-block-conteiner-info-elem').css('text-align','center')
    // } else{
    //   $('.panel-conteiner-all').css('width','calc(100% - 80px)')
    //   $('.panel-conteiner-all').css('margin-left','40px')
    //   $('.panel-profile-block').css('border-radius','6px')
    //   $('.panel-profile-block').css('margin-bottom','25px')
    //   $('.panel-profile-block-img').css('display','inline-block')
    //   $('.panel-profile-block-img').css('vertical-align','top')
    //   $('.panel-profile-block-img').css('margin-left','30px')
    //   $('.panel-profile-block-img').css('left','0')
    //   $('.panel-profile-block-img').css('right','0')
    //   $('.panel-profile-block-text').css('display','inline-block')
    //   $('.panel-profile-block-text').css('vertical-align','top')
    //   $('.panel-profile-block-text').css('padding-top','0px')
    //   $('.panel-profile-block-text').css('width','calc(100% - 220px)')
    //   $('.panel-profile-block-text').css('margin-left','30px')
    //   $('.panel-profile-block-text').css('overflow','hidden')
    //   $('.panel-profile-block-text').css('text-align','left')
    //   $('.panel-profile-block-text').css('left','initial')
    //   $('.panel-profile-block-text').css('right','initial')
    //   $('.panel-profile-block-text-name').css('display','inline-block')
    //   $('.panel-profile-block-text-name').css('margin-right','15px')
    //   $('.panel-profile-block-text-status').css('margin-top','0px')
    //   $('.panel-profile-block-text-status').css('vertical-align','middle')
    //   $('.panel-profile-block-text-btn').css('margin-top','5px')
    //   $('.panel-profile-block-2').css('width','270px')
    //   $('.panel-profile-block-3').css('width','calc(100% - 294px)')
    //   $('.panel-profile-block-3').css('min-width','555px')
    //   $('.panel-profile-block-3').css('margin-top','0px')
    //   $('.panel-profile-block-conteiner-info').css('margin-top','50px')
    //   $('.panel-profile-block-conteiner-info').css('border-top','1px solid var(--border-color)')
    //   $($('.panel-profile-block-conteiner-info').find('.panel-profile-block-conteiner-info-elem')[0]).css('border-right','1px solid var(--border-color)')
    //   $($('.panel-profile-block-conteiner-info').find('.panel-profile-block-conteiner-info-elem')[1]).css('border-right','1px solid var(--border-color)')
    //   $('.panel-profile-block-conteiner-info-elem').css('width','calc(calc(100% / 3) - 1px)')
    //   $('.panel-profile-block-conteiner-info-elem').css('vertical-align','middle')
    //   $('.panel-profile-block-conteiner-info-elem').css('margin-right','-4px')
    //   $('.panel-profile-block-conteiner-info-elem').css('text-align','center')
    // }

    if(document.documentElement.clientWidth <= 410){
      $('.main-nav-profile-profile-block').css('width','50px')
      $('.main-nav-profile-profile-block').css('bottom','15px')
      $('.main-nav-profile-profile-block').css('right','15px')
      $('.main-nav-profile-profile-block').css('border-radius','100px')
      $('.main-nav-profile-profile-block').css('top','initial')
      $('.main-nav-profile-profile-block').css('transform','translate(80px, 0px)')
    } else{
      $('.main-nav-profile-profile-block').css('width','250px')
      $('.main-nav-profile-profile-block').css('bottom','initial')
      $('.main-nav-profile-profile-block').css('right','20px')
      $('.main-nav-profile-profile-block').css('border-radius','0.25rem')
      $('.main-nav-profile-profile-block').css('top','70px')
      $('.main-nav-profile-profile-block').css('transform','translate(0px, 15px)')
    }

    var tOF_block = $("#TOF");
    var pdpp_block = $("#pdpp");

    $('#develop-device').text(type);
    $('#develop-height').text(document.documentElement.clientHeight + 'px');
    $('#develop-width').text(document.documentElement.clientWidth + 'px');
    $('#develop-heightDevice').text(screen.height + 'px');
    $('#develop-widthDevice').text(screen.width + 'px');
    if(type == 'small'){
      $('nav').css('position','fixed')
      $('nav').css('width','270px')
      $('nav').css('z-index','99')
      $('nav').css('left','-100%')
      $('.logo-dev').css({
        'opacity':'0.3',
        'visibility':'visible'
      })
      $('#develop-notification-nav').html('Включен режим разработчика');
      $('#develop-notification-nav').attr('class','logo-dev-line');
      $('.main').css('width','calc(100% + 5px)')
      $('html').get(0).style.setProperty('--width-nav','250px')
      $('.logo-img > svg').css('transform','scale(1)')
      $('.logo-title').css({'opacity':'1','display':'inline-block'})
      $('.logo-info').css('opacity','1')
      $('.menu').css('margin-top','140px')
      $('.menu').css('border-radius','20px')
      $('.menu-profile').css('background-color','var(--menu-profile)')
      $('.menu-profile').css('padding-top','10px')
      $('.menu-profile').css('margin-top','10px')
      $('.menu-profile-img').css('width','50px')
      $('.menu-profile-img').css('margin-left','10px')
      $('.menu-profile-block').css('display','inline-block')
      $('.menu-profile-btn').css('display','block')
      $('.menu-profile-btn').css('height','30px')
      $('.menu-profile-btn').css('margin-top','10px')
      $('.menu-elem-title').css('display','block')
      $('.menu-elem-title').css('margin-bottom','15px')
      $('.menu-elem-btn-text').css('max-height','20px')
      $('.menu-elem-btn-ico').css('font-size','16px')
      $('.menu-elem-btn-ico').css('margin-left','0px')
      $('.menu-elem-btn').css('margin-top','15px')
      $('.menu-elem-btn').css('margin-bottom','15px')
      $('.menu-elem-btn-more').css('opacity','1')
      $('.menu-elem-btn-text-count-msg').css('right','15px')
      $('.menu-elem-btn-text-count-msg').css('height','auto')
      $('.menu-elem-btn-text-count-msg').css('width','auto')
      $('.menu-elem-btn-text-count-msg').css('border','0px solid var(--dark)')
      $('.menu-elem-btn-text-count-msg').css('overflow','auto')
      $('.menu-elem-btn-text-count-msg').css('padding-left','7.5px')
      $('.menu-elem-btn-text-count-msg').css('padding-right','7.5px')
      $('.menu-elem-btn-text-count-msg').css('color','#fff')
      $('.menu-elem-btn-text-count-msg').css('padding-top','2px')
      $('.menu-elem-btn-text-count-msg').css('padding-bottom','2px')
      $('.menu-elem-btn-text-count-msg').css('top','7px')
      $('.menu-elem-btn-text-count-msg2').css('right','15px')
      $('.menu-elem-btn-text-count-msg2').css('height','auto')
      $('.menu-elem-btn-text-count-msg2').css('width','auto')
      $('.menu-elem-btn-text-count-msg2').css('border','0px solid var(--dark)')
      $('.menu-elem-btn-text-count-msg2').css('overflow','auto')
      $('.menu-elem-btn-text-count-msg2').css('padding-left','7.5px')
      $('.menu-elem-btn-text-count-msg2').css('padding-right','7.5px')
      $('.menu-elem-btn-text-count-msg2').css('color','#fff')
      $('.menu-elem-btn-text-count-msg2').css('padding-top','2px')
      $('.menu-elem-btn-text-count-msg2').css('padding-bottom','2px')
      $('.menu-elem-btn-text-count-msg2').css('top','7px')
      $('.main-nav-profile-profile-name').css('max-width','0px')
      $('#i1_label').css('width','153px')
      $('#i1').css('width','115px')
      $('.main-shadow').css('opacity','0')
      $('.main-shadow').css('display','none')
      $('#s1-not').attr('id','s1')
      $('#s2-not').attr('id','s2')
      $('#s3-not').attr('id','s3')
      $('.main-nav-profile-mail-block').css('width','95%')
      $('.main-nav-profile-mail-block').css('left','0px')
      $('.main-nav-profile-mail-block').css('top','initial')
      $('.main-nav-profile-mail-block').css('bottom','10px')
      $('.main-nav-profile-mail-block').css('right','0px')
      $('.main-nav-profile-mail-block').css('margin','auto')
      $('.main-nav-profile-mail-block').css('border-radius','20px')
      $('.main-nav-profile-mail-block').css('transform','translate(calc(0px),15px)')
      $('.window-block').css('position','fixed')
      $('.window-block').css('border-radius','20px')
      $('.window-block').css('margin-left','initial')
      $('.window-block').css('margin-top','initial')
      $('.window-block').css('left','0')
      $('.window-block').css('right','0')
      $('.window-block').css('margin','auto')
      $('.window-block').css('bottom','10px')
      $('.window-block').css('width','95vw')
      $('.window-block').css('transform','initial')
      $('.window-block-title').css('width','calc(100% - 67px)')
      $('.window-block-settings-block-input').css('margin-top','5px')
      $('.window-block-settings-block-input').css('margin-left','29px')
      $('.window-block-settings-block-text').css('max-width','60vw')
      $('.window-block-settings-block-text').css('white-space','normal')
      $('.window-block-settings-block-select').css('position','absolute')
      $('.window-block-settings-block-select').css('right','26px')
      $('.window-block-settings-block-input[type=checkbox]:checked ~ label').css('right','26px')
      $('.window-block-settings-block-input[type=checkbox]:not(checked) ~ label').css('right','26px')
      $('.window-block-settings-block > span').css({
        'width':'calc(100% - 0px)',
        'text-align':'left'
      });
      $('#id-develop-false').css('right','10px')
      $('#id-joinTables').css('right','10px')
      $('#id-joinTables2').css('right','10px')
      // TOF
      tOF_block.find('.window-block-main').css({'width':'100%'})
      tOF_block.find('.window-block-main > div').css({'width':'100%'})
      tOF_block.find('.pdpp-search-titleH2').css({'display':'none'})
      tOF_block.find('.pdpp-search-titleH2-span').css({'display':'none'})
      tOF_block.find('.pdpp-search').css({'display':'block','width':'calc(100% - 58px)'})
      tOF_block.find('.pdpp-main').css({'width':'calc(100% - 60px)','max-height':'initial'})

      pdpp_block.find('.window-block-main').css({'width':'100%'})
      pdpp_block.find('.window-block-main > div').css({'width':'100%'})
      pdpp_block.find('.pdpp-search-titleH2').css({'display':'none'})
      pdpp_block.find('.pdpp-search-titleH2-span').css({'display':'none'})
      pdpp_block.find('.pdpp-search').css({'display':'block','width':'calc(100% - 58px)'})
      pdpp_block.find('.pdpp-main').css({'width':'calc(100% - 60px)','max-height':'initial'})

      $('#user-edit').find('.window-block-main-main-left').css('width','calc(100% - 30px)')
      $('#user-edit').find('.window-block-main-main-left').css('display','block')
      $('#user-edit').find('.window-block-main-main-left').css('margin-right','0px')
      $('#user-edit').find('.window-block-main-main-right').css('width','100%')
      $('#user-edit').find('.window-block-main-main-right').css('min-width','initial')
      $('#user-edit').find('.window-block-main-main-right').css('margin-left','0px')
      $('#user-edit').find('.window-block-main-main-right').css('display','block')
      $('#user-edit').find('.window-block-main-main-right').css('padding-top','20px')
      $('#user-edit').find('.window-block-conteiner-left-img').css('left','0')
      $('#user-edit').find('.window-block-conteiner-left-img').css('right','0')
      $('#user-edit').find('.window-block-conteiner-left-img').css('margin','auto')
      $('#user-edit').find('.window-block-conteiner-left-img').css('display','block')
      $('#user-edit').find('.window-block-conteiner-left-img').css('margin-bottom','15px')
      $('#user-edit').find('.window-block-conteiner-left-img').css('transform','initial')
      $('#user-edit').find('.window-block-main-main-left').find('.window-block-conteiner-left-btn').css('left','0')
      $('#user-edit').find('.window-block-main-main-left').find('.window-block-conteiner-left-btn').css('right','0')
      $('#user-edit').find('.window-block-main-main-left').find('.window-block-conteiner-left-btn').css('margin','auto')
      $('#user-edit').find('.window-block-main-main-left').find('.window-block-conteiner-left-btn').css('margin-bottom','7.5px')
      $('#user-edit').find('.window-block-main-main-left').find('.window-block-conteiner-left-btn').css('width','calc(100% - 25px)')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('margin-left','0px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('width','auto')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('max-width','320px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('min-width','100px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('margin-right','30px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('margin-bottom','15px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('margin-left','0px')
      $('#user-edit').find('.window-block-main-main-right').find('.window-block-main-main-left-select').css('width','calc(100% - 30px)')
      $('#user-edit').find('.window-block-main-main-right').find('.window-block-main-main-left-select').css('margin-bottom','15px')
      $('#user-edit').find('.window-block-main-main-right').find('.window-block-main-main-left-select').css('margin-top','2px')

      $('.loader').parent().css('background-color','var(--menu-profile)')
      $('.loader').css('left','-13px')
      $('.loader-text').css({'visibility':'visible','opacity':'1'})

      // var individual_msg_block = $('#individual_msg');
      // var general_msg_block = $('#general_chat');
      // var support_chat_block = $('#support_chat');

      //
      // individual_msg_block.find('.panel-conteiner').css({'margin-left':'0px','width':'100%','margin-top':'-30px'})
      // individual_msg_block.find('.panel-user').css({'border-radius':'0px','height':'calc(100vh - 85px)','padding-bottom':'0px','margin-bottom':'0px','max-height':'initial'})
      // individual_msg_block.find('.panel-user-ab-btn').css('display','none')
      // individual_msg_block.find('.panel-filter-title').css('font-size','22px')
      // individual_msg_block.find('.main-nav-search-2').css('border-radius','10px')
      // individual_msg_block.find('.main-nav-search-input-2').css('width','calc(100% - 80px)')
      // individual_msg_block.find('.panel-msg-block').css({'width':'calc(100% - 44px)','border-radius':'10px'})
      // if(ststus_open_ind_msg){
      //   individual_msg_block.find('.panel-conteiner-full').css('display','block');
      //   individual_msg_block.find('.panel-conteiner').css('display','none');
      // } else{
      //   individual_msg_block.find('.panel-conteiner-full').css('display','none');
      //   individual_msg_block.find('.panel-conteiner').css('display','block');
      // }
      // individual_msg_block.find('.panel-filter-title-2').css('display','none')
      // $(individual_msg_block.find('.panel-msg-conteiner')[0]).css('margin-top','5px')
      //
      // individual_msg_block.find('.panel-conteiner-full').css({'margin-left':'0px','width':'100%','margin-top':'-37px'})
      // individual_msg_block.find('.panel-msg').css({'border-radius':'0px','height':'calc(100vh - 63px)','min-width':'initial'})
      // individual_msg_block.find('.panel-filter-title-2').css('display','none')
      // individual_msg_block.find('.panel-msg-block-msg-conteiner-nav-act').css('display','none')
      // individual_msg_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-photo').css('display','none')
      // individual_msg_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg2').css('margin-left','5px')
      // individual_msg_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv').css('margin-right','5px')
      // individual_msg_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg1').css('border-radius','18px')
      // individual_msg_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg2').css('border-radius','18px')
      // individual_msg_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv').css('border-radius','18px')
      // individual_msg_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv').css('border-radius','18px')
      // individual_msg_block.find('.emoji-block').css('width','calc(100vw - 164px)')
      // individual_msg_block.find('.panel-msg-block-msg-textinput-textarea').css('border-radius','10px')
      // individual_msg_block.find('.panel-msg-block-msg-textinput-send').css('border-radius','10px')
      // individual_msg_block.find('.panel-msg-block-msg-textinput-file').css('border-radius','10px')
      // individual_msg_block.find('.emoji-block').css('border-radius','10px')
      // $('html').find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-image').css('border-radius','8px')
      // $('html').find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file').css('border-radius','8px')
      // individual_msg_block.find('.panel-msg-block-msg-conteiner-nav-name').css({'font-size':'17px','font-weight':'700'})
      // individual_msg_block.find('.panel-msg-block-msg-conteiner-nav-ico').css('display','inline-block')
      //
      // general_msg_block.find('.panel-msg-block-msg-conteiner-nav-window').css('display','block');
      // general_msg_block.find('.panel-conteiner').css('display','none');
      // general_msg_block.find('.panel-conteiner-full').css({'width':'100%','margin-left':'0px'});
      // general_msg_block.find('.panel-msg').css({'height':'calc(100vh - 64px)','min-width':'initial','margin-top':'-36px'});
      // general_msg_block.find('.panel-msg-block-msg-conteiner-nav-status').css({'display':'none'});
      // general_msg_block.find('.panel-msg-block-msg-conteiner-nav-act').css({'display':'none'});
      // general_msg_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-inv').find('.panel-msg-block-msg-conteiner-main-conteiner-block-photo').css({'display':'none'});
      // general_msg_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv').css({'margin-right':'5px'});
      // general_msg_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg1').css('border-radius','18px')
      // general_msg_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg2').css('border-radius','18px')
      // general_msg_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv').css('border-radius','18px')
      // general_msg_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv').css('border-radius','18px')
      // general_msg_block.find('.emoji-block').css('width','calc(100vw - 164px)')
      // general_msg_block.find('.panel-msg-block-msg-textinput-textarea').css('border-radius','10px')
      // general_msg_block.find('.panel-msg-block-msg-textinput-send').css('border-radius','10px')
      // general_msg_block.find('.panel-msg-block-msg-textinput-file').css('border-radius','10px')
      // general_msg_block.find('.emoji-block').css('border-radius','10px')
      // general_msg_block.find('.panel-msg-block-msg-conteiner-nav-name').css({'font-size':'17px','font-weight':'700'})
      // general_msg_block.find('.panel-msg-block-msg-conteiner-nav-ico').css('display','inline-block')
      // general_msg_block.find('.panel-msg-block-msg-conteiner-nav-name').css('max-width','220px')

      $('.notification').css('width','calc(100% - 21px)');

      // support_chat_block.find('.panel-conteiner-all').css({'width':'100%','margin-left':'0px'})
      // support_chat_block.find('.panel-conteiner-width-support').css({'border-radius':'0px','margin-bottom':'0px','margin-top':'-30px','margin-bottom':'0px','height':'calc(100vh - 85px)'})
      // support_chat_block.find('.panel-conteiner-width-support-hello-block').css({'width':'100vw'})
      // support_chat_block.find('.panel-conteiner-width-support-hello-block-img').css({'height':'30vh'})
      // support_chat_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-inv').find('.panel-msg-block-msg-conteiner-main-conteiner-block-photo').css({'display':'none'});
      // support_chat_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv').css({'margin-right':'5px'});
      // support_chat_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg1').css('border-radius','18px')
      // support_chat_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg2').css('border-radius','18px')
      // support_chat_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv').css('border-radius','18px')
      // support_chat_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv').css('border-radius','18px')
      // support_chat_block.find('.emoji-block').css('width','calc(100vw - 164px)')
      // support_chat_block.find('.panel-msg-block-msg-textinput-textarea').css('border-radius','10px')
      // support_chat_block.find('.panel-msg-block-msg-textinput-send').css('border-radius','10px')
      // support_chat_block.find('.panel-msg-block-msg-textinput-file').css('border-radius','10px')
      // support_chat_block.find('.emoji-block').css('border-radius','10px')
      // support_chat_block.find('.panel-msg-block-msg-conteiner-nav-name').css({'font-size':'17px','font-weight':'700'})
      // support_chat_block.find('.panel-msg-block-msg-conteiner-nav-ico').css('display','inline-block')
      // support_chat_block.find('.panel-msg-block-msg-conteiner-nav-name').css('max-width','220px')
      //
      // support_chat_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-assessment').css({'margin-left':'9px','width':'calc(100% - 20px)'})
      // support_chat_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup').css({'max-width':'calc(100% - 25px)','border-radius':'15px'})
      // support_chat_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-textarea').css({'border-radius':'8px','max-width':'calc(100% - 9px)','min-width':'calc(100% - 9px)','width':'calc(100% - 9px)'})
      // support_chat_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-btn').css({'border-radius':'8px'})
      // support_chat_block.find('.panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-assessment-elem').css({'border-radius':'8px'})
      //

      //


      for(let i = 0; i < $('.notification').children().length; i++){
        $($('.notification').children()[i]).css({'border-radius':'15px','width':'100%'})
      }


      $(document).load("js/charts.js", function(){
        setTimeout(function(){
          updateChartsNew(theme_chart);
        }, 450)

      })


      setTimeout(function(){
        $('.menu-elem-title').css('max-height','20px')
        $('.menu-profile-block').css('opacity','1')
        $('.menu-profile-btn').css('opacity','1')

      }, 1)
    }
    if(type == 'medium'){
      $('html').get(0).style.setProperty('--width-nav','60px')
      $('nav').css('position','relative')
      $('nav').css('width','var(--width-nav)')
      $('nav').css('z-index','99')
      $('nav').css('left','0px')
      $('.logo-dev').css({
        'opacity':'0',
        'visibility':'hidden'
      })
      $('#develop-notification-nav').html('');
      $('#develop-notification-nav').attr('class','logo-dev-line icon-warning');
      $('.main').css('width','calc(100% - var(--width-nav))')
      $('.logo-img > svg').css('transform','scale(0.65) translate(-46px, -29px)')
      $('.logo-title').css('opacity','0')
      $('.logo-info').css('opacity','0')
      $('.menu').css('margin-top','63px')
      $('.menu').css('border-radius','100px')
      $('.menu-profile').css('background-color','transparent')
      $('.menu-profile').css('padding-top','19px')
      $('.menu-profile').css('margin-top','-27px')
      $('.menu-profile-img').css('width','39px')
      $('.menu-profile-img').css('margin-left','-9px')
      $('.menu-profile-block').css('opacity','0')
      $('.menu-profile-btn').css('opacity','0')
      $('.menu-profile-btn').css('height','0px')
      $('.menu-profile-btn').css('margin-top','0px')
      $('.menu-elem-title').css('max-height','0px')
      $('.menu-elem-btn-text').css('max-height','0px')
      $('.menu-elem-btn-ico').css('font-size','22px')
      $('.menu-elem-btn-ico').css('margin-left','-10px')
      $('.menu-elem-btn').css('margin-top','22px')
      $('.menu-elem-btn').css('margin-bottom','22px')
      $('.menu-elem-title').css('margin-bottom','-20px')
      $('.menu-elem-btn-more').css('opacity','0')
      $('.menu-elem-btn-text-count-msg').css('right','12px')
      $('.menu-elem-btn-text-count-msg').css('height','8px')
      $('.menu-elem-btn-text-count-msg').css('width','10px')
      $('.menu-elem-btn-text-count-msg').css('border','3px solid var(--menu)')
      $('.menu-elem-btn-text-count-msg').css('overflow','hidden')
      $('.menu-elem-btn-text-count-msg').css('padding-left','0px')
      $('.menu-elem-btn-text-count-msg').css('padding-right','0px')
      $('.menu-elem-btn-text-count-msg').css('color','#fd397a')
      $('.menu-elem-btn-text-count-msg').css('padding-top','0px')
      $('.menu-elem-btn-text-count-msg').css('padding-bottom','2px')
      $('.menu-elem-btn-text-count-msg').css('top','14px')
      $('.menu-elem-btn-text-count-msg2').css('right','12px')
      $('.menu-elem-btn-text-count-msg2').css('height','8px')
      $('.menu-elem-btn-text-count-msg2').css('width','10px')
      $('.menu-elem-btn-text-count-msg2').css('border','3px solid var(--menu)')
      $('.menu-elem-btn-text-count-msg2').css('overflow','hidden')
      $('.menu-elem-btn-text-count-msg2').css('padding-left','0px')
      $('.menu-elem-btn-text-count-msg2').css('padding-right','0px')
      $('.menu-elem-btn-text-count-msg2').css('color','#0abb87')
      $('.menu-elem-btn-text-count-msg2').css('padding-top','0px')
      $('.menu-elem-btn-text-count-msg2').css('padding-bottom','2px')
      $('.menu-elem-btn-text-count-msg2').css('top','14px')
      $('.main-nav-profile-profile-name').css('max-width','130px')
      $('#i1_label').css('width','210px')
      $('#i1').css('width','160px')
      $('.main-nav-profile-mail').css('display','inline-block')
      $('.main-nav-profile-notification').css('display','inline-block')
      $('.main-shadow').css('opacity','0')
      $('.main-shadow').css('display','none')
      $('#s1').attr('id','s1-not')
      $('#s2').attr('id','s2-not')
      $('#s3').attr('id','s3-not')
      $('.main-nav-profile-mail-block').css('width','350px')
      $('.main-nav-profile-mail-block').css('bottom','')
      $('.main-nav-profile-mail-block').css('top','70px')
      $('.main-nav-profile-mail-block').css('left','')
      $('.main-nav-profile-mail-block').css('right','')
      $('.main-nav-profile-mail-block').css('margin','')
      $('.main-nav-profile-mail-block').css('border-radius','0.25rem')
      $('.main-nav-profile-mail-block').css('transform','translate(calc(-50% + 20px),15px)')
      $('.main-nav-profile-profile-block').css('width','250px')
      $('.main-nav-profile-profile-block').css('bottom','initial')
      $('.main-nav-profile-profile-block').css('right','20px')
      $('.main-nav-profile-profile-block').css('border-radius','0.25rem')
      $('.main-nav-profile-profile-block').css('top','70px')
      $('.main-nav-profile-profile-block').css('transform','translate(0px, 15px)')
      $('.window-block').css('position','absolute')
      $('.window-block').css('border-radius','7.5px')
      $('.window-block').css('margin-left','50vw')
      $('.window-block').css('margin-top','50vh')
      $('.window-block').css('left','initial')
      $('.window-block').css('right','initial')
      $('.window-block').css('bottom','initial')
      $('.window-block').css('width','auto')
      $('.window-block').css('transform','translate(-50%, -50%)')
      $('.window-block-title').css('width','550px')
      $('.window-block-settings-block-input').css('margin-top','0px')
      $('.window-block-settings-block-input').css('margin-left','0px')
      $('.window-block-settings-block-text').css('max-width','initial')
      $('.window-block-settings-block-text').css('white-space','normal')
      $('.window-block-settings-block-select').css('position','absolute')
      $('.window-block-settings-block-select').css('right','91px')
      $('.window-block-settings-block-input[type=checkbox]:checked ~ label').css('right','91px')
      $('.window-block-settings-block-input[type=checkbox]:not(checked) ~ label').css('right','91px')
      $('#id-joinTables').css('right','15px')
      $('#id-develop-false').css('right','20px')
      $('.window-block-settings-block > span').css({
        'width':'calc(100% - 275px)',
        'text-align':'right'
      })
      //TOF
      tOF_block.find('.window-block-main').css({'width':'700px'})
      tOF_block.find('.window-block-main > div').css({'width':'auto'})
      tOF_block.find('.pdpp-search-titleH2').css({'display':'block'})
      tOF_block.find('.pdpp-search-titleH2-span').css({'display':'block'})
      tOF_block.find('.pdpp-search').css({'display':'inline-block','width':'220px'})
      tOF_block.find('.pdpp-main').css({'width':'calc(100% - 240px)','max-height':'600px'})

      pdpp_block.find('.window-block-main').css({'width':'700px'})
      pdpp_block.find('.window-block-main > div').css({'width':'auto'})
      pdpp_block.find('.pdpp-search-titleH2').css({'display':'block'})
      pdpp_block.find('.pdpp-search-titleH2-span').css({'display':'block'})
      pdpp_block.find('.pdpp-search').css({'display':'inline-block','width':'220px'})
      pdpp_block.find('.pdpp-main').css({'width':'calc(100% - 240px)','max-height':'600px'})

      $('#user-edit').find('.window-block-main-main-left').css('width','150px')
      $('#user-edit').find('.window-block-main-main-left').css('display','inline-block')
      $('#user-edit').find('.window-block-main-main-left').css('margin-right','26px')
      $('#user-edit').find('.window-block-main-main-right').css('width','calc(100% - 200px)')
      $('#user-edit').find('.window-block-main-main-right').css('min-width','280px')
      $('#user-edit').find('.window-block-main-main-right').css('margin-left','20px')
      $('#user-edit').find('.window-block-main-main-right').css('display','inline-block')
      $('#user-edit').find('.window-block-main-main-right').css('padding-top','0px')
      $('#user-edit').find('.window-block-conteiner-left-img').css('left','initial')
      $('#user-edit').find('.window-block-conteiner-left-img').css('right','initial')
      $('#user-edit').find('.window-block-conteiner-left-img').css('margin','initial')
      $('#user-edit').find('.window-block-conteiner-left-img').css('margin-left','50%')
      $('#user-edit').find('.window-block-conteiner-left-img').css('display','block')
      $('#user-edit').find('.window-block-conteiner-left-img').css('margin-bottom','25px')
      $('#user-edit').find('.window-block-conteiner-left-img').css('transform','translate(-50%,0px)')
      $('#user-edit').find('.window-block-main-main-left').find('.window-block-conteiner-left-btn').css('left','initial')
      $('#user-edit').find('.window-block-main-main-left').find('.window-block-conteiner-left-btn').css('right','0')
      $('#user-edit').find('.window-block-main-main-left').find('.window-block-conteiner-left-btn').css('margin-bottom','7.5px')
      $('#user-edit').find('.window-block-main-main-left').find('.window-block-conteiner-left-btn').css('width','125px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('margin-left','0px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('width','auto')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('max-width','320px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('min-width','100px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('margin-right','0px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login-tel-email').css('margin-right','30px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login-tel-email').css('margin-right','30px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('margin-bottom','15px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('margin-left','0px')
      $('#user-edit').find('.window-block-main-main-right').find('.window-block-main-main-left-select').css('width','100%')
      $('#user-edit').find('.window-block-main-main-right').find('.window-block-main-main-left-select').css('margin-bottom','15px')
      $('#user-edit').find('.window-block-main-main-right').find('.window-block-main-main-left-select').css('margin-top','2px')
      $('.loader').parent().css('background-color','var(--menu)')
      $('.loader').css('left','-20px')
      $('.loader-text').css({'visibility':'hidden','opacity':'0'})
      //
      // var global_search = $('#global_search');
      //



      // if(document.documentElement.clientWidth <= 1506 && document.documentElement.clientWidth > 1040){
      //   $('.panel-conteiner-width-small-main-elem-block1 > span').css('font-size','20px')
      //   var tmpCount = $('html').find('.panel-conteiner-width-small-footer-elem1-span');
      //   $('.panel-conteiner-width-small-footer-elem1-span').find('span').css('font-size','0px')
      // } else if(document.documentElement.clientWidth <= 1040){
      //   $('.panel-conteiner-width-small-main-elem-block1 > span').css('font-size','20px')
      //   var tmpCount = $('html').find('.panel-conteiner-width-small-footer-elem1-span');
      //   $('.panel-conteiner-width-small-footer-elem1-span').find('span').css('font-size','0px')
      // } else{
      //   $('.panel-conteiner-width-small-main-elem-block1 > span').css('font-size','30px')
      //   $('.panel-conteiner-width-small-footer-elem1-span').find('span').css('font-size','14px')
      // }
      //
      // if(document.documentElement.clientWidth <= 875){
      //   $('.panel-conteiner-width-small-main-elem-block1 > span').css({'font-size':'0px'})
      // }
      // if(document.documentElement.clientWidth <= 1291){
      //   $('.panel-conteiner-width-small-footer-elem1').css({'opacity':'0','visibility':'hidden'})
      // } else{
      //   $('.panel-conteiner-width-small-footer-elem1').css({'opacity':'1','visibility':'visible'})
      // }

      $('.notification').css('width','auto');
      for(let i = 0; i < $('.notification').children().length; i++){
        $($('.notification').children()[i]).css({'border-radius':'6px','width':'340px'})
      }

      setTimeout(function(){
        $('.logo-title').css('display','none')
        $('.menu-profile-block').css('display','none')
        $('.menu-profile-btn').css('display','none')
      }, 350)
    }
    if(type == 'high'){
      $('nav').css('position','relative')
      $('nav').css('width','var(--width-nav)')
      $('nav').css('z-index','99')
      $('nav').css('left','0px')
      $('.logo-dev').css({
        'opacity':'0.3',
        'visibility':'visible'
      })
      $('#develop-notification-nav').html('Включен режим разработчика');
      $('#develop-notification-nav').attr('class','logo-dev-line');
      $('.main').css('width','calc(100% - var(--width-nav))')
      $('html').get(0).style.setProperty('--width-nav','250px')
      $('.logo-img > svg').css('transform','scale(1)')
      $('.logo-title').find('hb').css('font-size','27.5px')
      $('.logo-title').css('opacity','1')
      $('.logo-title').css('display','inline-block')
      $('.logo-info').css('opacity','1')
      $('.menu').css('margin-top','140px')
      $('.menu').css('border-radius','20px')
      $('.menu-profile').css('background-color','var(--menu-profile)')
      $('.menu-profile').css('padding-top','10px')
      $('.menu-profile').css('margin-top','10px')
      $('.menu-profile-img').css('width','50px')
      $('.menu-profile-img').css('margin-left','10px')
      $('.menu-profile-block').css('display','inline-block')
      $('.menu-profile-btn').css('display','block')
      $('.menu-profile-btn').css('height','30px')
      $('.menu-profile-btn').css('margin-top','10px')
      $('.menu-elem-title').css('display','block')
      $('.menu-elem-title').css('margin-bottom','15px')
      $('.menu-elem-btn-text').css('max-height','20px')
      $('.menu-elem-btn-ico').css('font-size','16px')
      $('.menu-elem-btn-ico').css('margin-left','0px')
      $('.menu-elem-btn').css('margin-top','15px')
      $('.menu-elem-btn').css('margin-bottom','15px')
      $('.menu-elem-btn-more').css('opacity','1')
      $('.menu-elem-btn-text-count-msg').css('right','15px')
      $('.menu-elem-btn-text-count-msg').css('height','auto')
      $('.menu-elem-btn-text-count-msg').css('width','auto')
      $('.menu-elem-btn-text-count-msg').css('border','0px solid var(--dark)')
      $('.menu-elem-btn-text-count-msg').css('overflow','auto')
      $('.menu-elem-btn-text-count-msg').css('padding-left','7.5px')
      $('.menu-elem-btn-text-count-msg').css('padding-right','7.5px')
      $('.menu-elem-btn-text-count-msg').css('color','#fff')
      $('.menu-elem-btn-text-count-msg').css('padding-top','2px')
      $('.menu-elem-btn-text-count-msg').css('padding-bottom','2px')
      $('.menu-elem-btn-text-count-msg').css('top','7px')
      $('.menu-elem-btn-text-count-msg2').css('right','15px')
      $('.menu-elem-btn-text-count-msg2').css('height','auto')
      $('.menu-elem-btn-text-count-msg2').css('width','auto')
      $('.menu-elem-btn-text-count-msg2').css('border','0px solid var(--dark)')
      $('.menu-elem-btn-text-count-msg2').css('overflow','auto')
      $('.menu-elem-btn-text-count-msg2').css('padding-left','7.5px')
      $('.menu-elem-btn-text-count-msg2').css('padding-right','7.5px')
      $('.menu-elem-btn-text-count-msg2').css('color','#fff')
      $('.menu-elem-btn-text-count-msg2').css('padding-top','2px')
      $('.menu-elem-btn-text-count-msg2').css('padding-bottom','2px')
      $('.menu-elem-btn-text-count-msg2').css('top','7px')
      $('.main-nav-profile-profile-name').css('max-width','130px')
      $('#i1_label').css('width','210px')
      $('#i1').css('width','160px')
      $('.main-nav-profile-mail').css('display','inline-block')
      $('.main-nav-profile-notification').css('display','inline-block')
      $('#s1-not').attr('id','s1')
      $('#s2-not').attr('id','s2')
      $('#s3-not').attr('id','s3')
      $('.main-nav-profile-mail-block').css('width','350px')
      $('.main-nav-profile-mail-block').css('bottom','')
      $('.main-nav-profile-mail-block').css('top','70px')
      $('.main-nav-profile-mail-block').css('left','')
      $('.main-nav-profile-mail-block').css('right','')
      $('.main-nav-profile-mail-block').css('margin','')
      $('.main-nav-profile-mail-block').css('transform','translate(calc(-50% + 20px),15px)')
      $('.main-nav-profile-mail-block').css('border-radius','0.25rem')
      $('.main-nav-profile-profile-block').css('width','250px')
      $('.main-nav-profile-profile-block').css('bottom','initial')
      $('.main-nav-profile-profile-block').css('right','20px')
      $('.main-nav-profile-profile-block').css('border-radius','0.25rem')
      $('.main-nav-profile-profile-block').css('top','70px')
      $('.main-nav-profile-profile-block').css('transform','translate(0px, 15px)')
      $('.window-block').css('position','absolute')
      $('.window-block').css('border-radius','7.5px')
      $('.window-block').css('margin-left','50vw')
      $('.window-block').css('margin-top','50vh')
      $('.window-block').css('left','initial')
      $('.window-block').css('right','initial')
      $('.window-block').css('bottom','initial')
      $('.window-block').css('width','auto')
      $('.window-block').css('transform','translate(-50%, -50%)')
      $('.window-block-title').css('width','550px')
      $('.window-block-settings-block-input').css('margin-top','0px')
      $('.window-block-settings-block-input').css('margin-left','0px')
      $('.window-block-settings-block-text').css('max-width','initial')
      $('.window-block-settings-block-text').css('white-space','normal')
      $('.window-block-settings-block-select').css('position','absolute')
      $('.window-block-settings-block-select').css('right','91px')
      $('.window-block-settings-block-input[type=checkbox]:checked ~ label').css('right','91px')
      $('.window-block-settings-block-input[type=checkbox]:not(checked) ~ label').css('right','91px')
      $('#id-develop-false').css('right','10px')
      $('#id-joinTables').css('right','10px')
$('#id-joinTables2').css('right','10px')
      $('.window-block-settings-block > span').css({
        'width':'calc(100% - 275px)',
        'text-align':'right'
      })
      //TOF
      tOF_block.find('.window-block-main').css({'width':'700px'})
      tOF_block.find('.window-block-main > div').css({'width':'auto'})
      tOF_block.find('.pdpp-search-titleH2').css({'display':'block'})
      tOF_block.find('.pdpp-search-titleH2-span').css({'display':'block'})
      tOF_block.find('.pdpp-search').css({'display':'inline-block','width':'220px'})
      tOF_block.find('.pdpp-main').css({'width':'calc(100% - 240px)','max-height':'600px'})

      pdpp_block.find('.window-block-main').css({'width':'700px'})
      pdpp_block.find('.window-block-main > div').css({'width':'auto'})
      pdpp_block.find('.pdpp-search-titleH2').css({'display':'block'})
      pdpp_block.find('.pdpp-search-titleH2-span').css({'display':'block'})
      pdpp_block.find('.pdpp-search').css({'display':'inline-block','width':'220px'})
      pdpp_block.find('.pdpp-main').css({'width':'calc(100% - 240px)','max-height':'600px'})

      $('#user-edit').find('.window-block-main-main-left').css('width','150px')
      $('#user-edit').find('.window-block-main-main-left').css('display','inline-block')
      $('#user-edit').find('.window-block-main-main-left').css('margin-right','26px')
      $('#user-edit').find('.window-block-main-main-right').css('width','calc(100% - 200px)')
      $('#user-edit').find('.window-block-main-main-right').css('min-width','280px')
      $('#user-edit').find('.window-block-main-main-right').css('margin-left','20px')
      $('#user-edit').find('.window-block-main-main-right').css('display','inline-block')
      $('#user-edit').find('.window-block-main-main-right').css('padding-top','0px')
      $('#user-edit').find('.window-block-conteiner-left-img').css('left','initial')
      $('#user-edit').find('.window-block-conteiner-left-img').css('right','initial')
      $('#user-edit').find('.window-block-conteiner-left-img').css('margin','initial')
      $('#user-edit').find('.window-block-conteiner-left-img').css('margin-left','50%')
      $('#user-edit').find('.window-block-conteiner-left-img').css('display','block')
      $('#user-edit').find('.window-block-conteiner-left-img').css('margin-bottom','25px')
      $('#user-edit').find('.window-block-conteiner-left-img').css('transform','translate(-50%,0px)')
      $('#user-edit').find('.window-block-main-main-left').find('.window-block-conteiner-left-btn').css('left','initial')
      $('#user-edit').find('.window-block-main-main-left').find('.window-block-conteiner-left-btn').css('right','0')
      $('#user-edit').find('.window-block-main-main-left').find('.window-block-conteiner-left-btn').css('margin-bottom','7.5px')
      $('#user-edit').find('.window-block-main-main-left').find('.window-block-conteiner-left-btn').css('width','125px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('margin-left','0px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('width','auto')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('max-width','320px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('min-width','100px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('margin-right','0px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login-tel-email').css('margin-right','30px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login-tel-email').css('margin-right','30px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('margin-bottom','15px')
      $('#user-edit').find('.window-block-main-main-right').find('.input-login').css('margin-left','0px')
      $('#user-edit').find('.window-block-main-main-right').find('.window-block-main-main-left-select').css('width','100%')
      $('#user-edit').find('.window-block-main-main-right').find('.window-block-main-main-left-select').css('margin-bottom','15px')
      $('#user-edit').find('.window-block-main-main-right').find('.window-block-main-main-left-select').css('margin-top','2px')
      $('.loader').parent().css('background-color','var(--menu-profile)')
      $('.loader').css('left','-13px')
      $('.loader-text').css({'visibility':'visible','opacity':'1'})

      $('.notification').css('width','auto');
      for(let i = 0; i < $('.notification').children().length; i++){
        $($('.notification').children()[i]).css({'border-radius':'6px','width':'340px'})
      }

      setTimeout(function(){
        $('.menu-elem-title').css('max-height','20px')
        $('.menu-profile-block').css('opacity','1')
        $('.menu-profile-btn').css('opacity','1')
      }, 1)
      $('.main-shadow').css('opacity','0')
      $('.main-shadow').css('display','none')
    }
  }
}


function nav_open(a){

  if(document.documentElement.clientWidth <= 835){
    if(!NavStat){

      if(a != true){
        NavStat = true;
        setTimeout(function(){
          $('.main-shadow').css({'opacity':'1','transition':'0.25s all ease-in-out'})
        }, 1)
        $('nav').css({'left':'0','transition':'0.35s all ease-in-out'})
      } else{
        $('.main-shadow').css({'transition':'0s all ease-in-out'})
        $('nav').css({'transition':'0s all ease-in-out'})
      }

      $('.main-shadow').css('display','block')
    } else{
      NavStat = false;
      $('nav').css('left','-100%')
      $('.main-shadow').css('opacity','0')
      setTimeout(function(){
        $('.main-shadow').css('display','none')
      }, 250)
    }
  }else if(document.documentElement.clientWidth > 835 && document.documentElement.clientWidth < 1183){
    if(!NavStat){
      NavStat = true;
      screenUser('high')
    } else{
      NavStat = false;
      screenUser('medium')
    }
  } else {
    if(!NavStat){
      NavStat = true;
      screenUser('medium')
    } else{
      NavStat = false;
      screenUser('high')
    }
  }

}

function open_iframe(url){
  window.open(url);
}

$(document).ready(function(){
  $('#KkQof-v7Ni-zO35').on('input', searchDocumentPolicy)
  $('#KkQof-v7Ni-zO34').on('input', searchDocumentTerms)
});

var policyText = '';
var termsText = '';

function searchDocumentPolicy(e){
  try {
    if(policyText.length == 0){
      policyText = $('#pdpp').find('.pdpp-main').html();
    }
    $('.pdpp-main').scrollTop(0)
    if($(this).val().length > 0){
      if($(this).val().match(/(<|>|<p>|<p|<\/p>|<h4>|<h|\/h|<\/h4>|\/)+|(\s){2,}/gi)){
        $('#pdpp').find('#pdpp-search-titleH331').parent().css('max-height','0px')
        $('#pdpp').find('#pdpp-search-titleH331').text(' ')
        $('#pdpp').find('.pdpp-main').html(policyText);
        return;
      }
      if($(this).val().length > 35){
        $('#pdpp').find('#pdpp-search-titleH331').parent().css('max-height','0px')
        $('#pdpp').find('#pdpp-search-titleH331').text(' ')
        $('#pdpp').find('.pdpp-main').html(policyText);
        notification_add('question','Информация','Максимальное количество символов 35', 7);
        return;
      }
      var tmpValSearch = $(this).val().replace(/\s+/g,' ');
      $('#pdpp').find('.pdpp-main').html(policyText);
      var tmpText = $('#pdpp').find('.pdpp-main').html();
      var regexSearchPolicy = new RegExp(tmpValSearch, 'gi');
      var tmpEntry = tmpText.match(regexSearchPolicy);
      if(tmpEntry){
        var tmpCountSearch = tmpText.match(regexSearchPolicy) || [];
        tmpText = tmpText.replace(regexSearchPolicy, '<span class="policy-highlight">' + tmpValSearch + '</span>');
        $('#pdpp').find('.pdpp-main').html(tmpText)
        $('#pdpp').find('#pdpp-search-titleH331').parent().css('max-height','50px')
        $('#pdpp').find('#pdpp-search-titleH331').text(tmpCountSearch.length)
        $('.pdpp-main').scrollTop($('#pdpp').find('span.policy-highlight:first').offset().top - 217)
      } else{
        $('#pdpp').find('#pdpp-search-titleH331').parent().css('max-height','0px')
        $('#pdpp').find('#pdpp-search-titleH331').text(' ')
      }
    } else{
      $('#pdpp').find('#pdpp-search-titleH331').parent().css('max-height','0px')
      $('#pdpp').find('#pdpp-search-titleH331').text(' ')
      $('#pdpp').find('.pdpp-main').html(policyText);
    }
  } catch (e) {}
}

function searchDocumentTerms(e){
  try {
    if(termsText.length == 0){
      termsText = $('#TOF').find('.pdpp-main').html();
    }
    $('.pdpp-main').scrollTop(0)
    if($(this).val().length > 0){
      if($(this).val().match(/(<|>|<p>|<p|<\/p>|<h4>|<h|\/h|<\/h4>|\/)+|(\s){2,}/gi)){
        $('#TOF').find('#pdpp-search-titleH332').parent().css('max-height','0px')
        $('#TOF').find('#pdpp-search-titleH332').text(' ')
        $('#TOF').find('.pdpp-main').html(termsText);
        return;
      }
      if($(this).val().length > 35){
        $('#TOF').find('#pdpp-search-titleH332').parent().css('max-height','0px')
        $('#TOF').find('#pdpp-search-titleH332').text(' ')
        $('#TOF').find('.pdpp-main').html(termsText);
        notification_add('question','Информация','Максимальное количество символов 35', 7);
        return;
      }
      var tmpValSearch = $(this).val().replace(/\s+/g,' ');
      $('#TOF').find('.pdpp-main').html(termsText);
      var tmpText = $('#TOF').find('.pdpp-main').html();
      var regexSearchPolicy = new RegExp(tmpValSearch, 'gi');
      var tmpEntry = tmpText.match(regexSearchPolicy);
      if(tmpEntry){
        var tmpCountSearch = tmpText.match(regexSearchPolicy) || [];
        tmpText = tmpText.replace(regexSearchPolicy, '<span class="policy-highlight">' + tmpValSearch + '</span>');
        $('#TOF').find('.pdpp-main').html(tmpText)
        $('#TOF').find('#pdpp-search-titleH332').parent().css('max-height','50px')
        $('#TOF').find('#pdpp-search-titleH332').text(tmpCountSearch.length)
        $('.pdpp-main').scrollTop($('#TOF').find('span.policy-highlight:first').offset().top - 217)

      } else{
        $('#TOF').find('#pdpp-search-titleH332').parent().css('max-height','0px')
        $('#TOF').find('#pdpp-search-titleH332').text(' ')
      }
    } else{
      $('#TOF').find('#pdpp-search-titleH332').parent().css('max-height','0px')
      $('#TOF').find('#pdpp-search-titleH332').text(' ')
      $('#TOF').find('.pdpp-main').html(termsText);
    }
  } catch (e) {}
}

/*function contactsAddTel(a){
  var tmpSpan = $(a);
  var tmpStringGenerator = stringGenerator(15, 5);
  var tmpString = "<div class='input-login' style='margin-bottom: 0px; opacity: 0; visibility: hidden; height: 0px; margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'><input value='' required='required' type='tel' id='" + tmpStringGenerator + "'><label class='placeholder' for='" + tmpStringGenerator + "'>Телефон</label><span class='input-login-delete icon-plus' title='Удалить' onclick='contactsRemove(this)'></span></div>"

  tmpSpan.append(tmpString);
  $('#' + tmpStringGenerator).css({'height':'36px','margin-bottom':'15px'})
  setTimeout(function(){
    $('#' + tmpStringGenerator).css({'opacity':'1','visibility':'visible','height':'auto'})
  }, 250)
  $('#' + tmpStringGenerator).focus()
}

function contactsAddMail(a) {
  var tmpSpan = $(a);
  var tmpStringGenerator = stringGenerator(15, 5);
  var tmpString = "<div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'><input value='' required='required' type='mail' id='" + tmpStringGenerator + "'><label class='placeholder' for='" + tmpStringGenerator + "'>Почта</label><span class='input-login-delete icon-plus' title='Удалить' onclick='contactsRemove(this)'></span></div>"

  tmpSpan.append(tmpString);
  $('#' + tmpStringGenerator).focus()
}

function contactsRemove(a){
  var tmpBlock = $(a).parent();
  tmpBlock.remove();
}*/

function stringGenerator(count, where){
  let alphabet = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789',
      string = '';
  if(where > 0){
    for(let i = 0; i < count; i++){
      if(i % where == 0 && i != count && i != 0){
        string += '-';
      } else{
        string += alphabet[Math.round(Math.random() * (alphabet.length - 1))];
      }
    }
  } else{
    for(let i = 0; i < count; i++){
      string += alphabet[Math.round(Math.random() * (alphabet.length - 1))];
    }
  }
  if(where < 0){
    console.warn('Предупреждение: переменную where не рукомендуется писать меньше нуля')
  }
  return string;
}

function open_nav_elem(a){
  var id = $(a).attr('id');
  var offsetFromScreenTop = $("#" + id).offset().top - $(window).scrollTop();
  if(!NavStat){
    if(document.documentElement.clientWidth > 835 && document.documentElement.clientWidth < 1183){
      if(id == 'menu-elem-btn1'){
        $('.menu-elem-btn-more-block-not').html("<div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#news" + '"' + ")'>Новости</div><div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#timetable" + '"' + ")'>Расписание</div><div class='menu-elem-btn-more-block-not-elem' onclick='open_window(" + '"' + "#contacts" + '"' + ")'>Контакты</div><div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#review" + '"' + ")'>Отзывы</div><div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#Employees" + '"' + ")'>Сотрудники</div><div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#about_company" + '"' + ")'>О компании</div>")
        $('.menu-elem-btn-more-block-not').css('top', offsetFromScreenTop + 'px')
        $('.menu-elem-btn-more-block-not').css('display','block')
        setTimeout(function(){
          $('.menu-elem-btn-more-block-not').css('opacity','1')
          $('.menu-elem-btn-more-block-not').css('transform','translate(0px, 0px)')
        }, 1)
      } else if(id == 'menu-elem-btn2'){
        $('.menu-elem-btn-more-block-not').html("<div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#media_files" + '"' + ")'>Медиафайлы</div><div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#file_manager" + '"' + ")'>Проводник</div>")
        $('.menu-elem-btn-more-block-not').css('top', offsetFromScreenTop + 'px')
        $('.menu-elem-btn-more-block-not').css('display','block')
        setTimeout(function(){
          $('.menu-elem-btn-more-block-not').css('opacity','1')
          $('.menu-elem-btn-more-block-not').css('transform','translate(0px, 0px)')
        }, 1)
      } else if(id == 'menu-elem-btn3'){
        $('.menu-elem-btn-more-block-not').html("<div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#add_user" + '"' + ")'>Добавить нового</div><div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#all_user" + '"' + ")'>Все пользователи</div><div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#profile" + '"' + "); updateAccessLogs();'>Ваш профиль</div>")
        $('.menu-elem-btn-more-block-not').css('top', offsetFromScreenTop + 'px')
        $('.menu-elem-btn-more-block-not').css('display','block')
        setTimeout(function(){
          $('.menu-elem-btn-more-block-not').css('opacity','1')
          $('.menu-elem-btn-more-block-not').css('transform','translate(0px, 0px)')
        }, 1)
      }
    }
  }
  if(NavStat){
    if(document.documentElement.clientWidth >= 1183){
      if(id == 'menu-elem-btn1'){
        $('.menu-elem-btn-more-block-not').html("<div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#news" + '"' + ")'>Новости</div><div class='menu-elem-btn-more-block-not-elem' onclick=''>Расписание</div><div class='menu-elem-btn-more-block-not-elem' onclick='open_window(" + '"' + "#contacts" + '"' + ")'>Контакты</div><div class='menu-elem-btn-more-block-not-elem' onclick=''>Отзывы</div><div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#Employees" + '"' + ")'>Сотрудники</div><div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#about_company" + '"' + ")'>О компании</div>")
        $('.menu-elem-btn-more-block-not').css('top', offsetFromScreenTop + 'px')
        $('.menu-elem-btn-more-block-not').css('display','block')
        setTimeout(function(){
          $('.menu-elem-btn-more-block-not').css('opacity','1')
          $('.menu-elem-btn-more-block-not').css('transform','translate(0px, 0px)')
        }, 1)
      } else if(id == 'menu-elem-btn2'){
        $('.menu-elem-btn-more-block-not').html("<div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#media_files" + '"' + ")'>Медиафайлы</div><div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#file_manager" + '"' + ")'>Проводник</div>")
        $('.menu-elem-btn-more-block-not').css('top', offsetFromScreenTop + 'px')
        $('.menu-elem-btn-more-block-not').css('display','block')
        setTimeout(function(){
          $('.menu-elem-btn-more-block-not').css('opacity','1')
          $('.menu-elem-btn-more-block-not').css('transform','translate(0px, 0px)')
        }, 1)
      } else if(id == 'menu-elem-btn3'){
        $('.menu-elem-btn-more-block-not').html("<div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#add_user" + '"' + ")'>Добавить нового</div><div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#all_user" + '"' + ")'>Все пользователи</div><div class='menu-elem-btn-more-block-not-elem' onclick='open_panel(" + '"' + "#profile" + '"' + "); updateAccessLogs();'>Ваш профиль</div>")
        $('.menu-elem-btn-more-block-not').css('top', offsetFromScreenTop + 'px')
        $('.menu-elem-btn-more-block-not').css('display','block')
        setTimeout(function(){
          $('.menu-elem-btn-more-block-not').css('opacity','1')
          $('.menu-elem-btn-more-block-not').css('transform','translate(0px, 0px)')
        }, 1)
      }
    }
  }
}

function randomInteger(min, max) {
  let rand = min + Math.random() * (max - min);
  return Math.round(rand);
}

function notification_add(type,title,text,timer,src){

  let alphabet = '-_QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789',
      id = '',
      tmpType = 'border-radius: 6px; width: 340px';
  for(let i = 0; i < 15; i++){
      id += alphabet[Math.round(Math.random() * (alphabet.length - 1))];
  }

  if(timer == undefined){
    timer = 7.5;
  }

  if(timer > 0){
    setTimeout(function(){
      close_notification("#" + id)
    }, timer * 1000)
  } else if(timer < 0){
    console.error('Error: время не может быть отрицательным!')
    return;
  }

  if($.cookie('sound_noti') == 'true' && (type == 'standart' || type == 'text' || type == 'line' || type == 'warning' || type == 'question')){
    var audioNotification = new Audio();
    audioNotification.src = 'media/audio/notification.mp3';
    audioNotification.autoplay = true;
  } else{
    window.navigator.vibrate(35)
  }

  if($.cookie('sound_noti') == 'true' && type == 'error'){
    var audioNotificationError = new Audio();
    audioNotificationError.src = 'media/audio/notificationError.mp3';
    audioNotificationError.autoplay = true;
  } else{
    window.navigator.vibrate(35)
  }

  if(document.documentElement.clientWidth <= 835){
    $('.notification').css('width','calc(100% - 21px)');
    tmpType = 'border-radius: 15px; width: 100%';
  }

  if(type == 'standart'){
    $('.notification').append("<div class='notification-standart' style='" + tmpType + "'><div id='" + id + "' class='notification-close icon-close' title='Закрыть' onclick='close_notification(this)'></div><div class='notification-img'><img src='" + src + "' height='55px'></div><div class='notification-text'><div class='notification-text-title'>" + title + "</div><div class='notification-text-text'>" + text + "</div></div></div>")
  } else if(type == 'text'){
    $('.notification').append("<div class='notification-standart-2' style='" + tmpType + "'><div id='" + id + "' class='notification-close icon-close' title='Закрыть' onclick='close_notification(this)'></div><div class='notification-text-2'><div class='notification-text-title'>" + title + "</div><div class='notification-text-text'>" + text + "</div></div></div>")
  } else if(type == 'line'){
    $('.notification').append("<div class='notification-line' style='" + tmpType + "'><div id='" + id + "' class='notification-close-ab icon-close' title='Закрыть' onclick='close_notification(this)'></div><div class='notification-line-text'>" + text + "</div></div>")
  } else if(type == 'error'){
    $('.notification').append("<div class='notification-error' style='" + tmpType + "'><div id='" + id + "' class='notification-close icon-close' title='Закрыть' onclick='close_notification(this)'></div><div class='notification-img-ico icon-error' style='color: #fd3939;'></div><div class='notification-text'><div class='notification-text-title'>" + title + "</div><div class='notification-text-text'>" + text + "</div></div></div>")
  } else if(type == 'warning'){
    $('.notification').append("<div class='notification-warning' style='" + tmpType + "'><div id='" + id + "' class='notification-close icon-close' title='Закрыть' onclick='close_notification(this)'></div><div class='notification-img-ico icon-warning' style='color: #fdbd39;'></div><div class='notification-text'><div class='notification-text-title'>" + title + "</div><div class='notification-text-text'>" + text + "</div></div></div>")
  } else if(type == 'question'){
    $('.notification').append("<div class='notification-question' style='" + tmpType + "'><div id='" + id + "' class='notification-close icon-close' title='Закрыть' onclick='close_notification(this)'></div><div class='notification-img-ico icon-question' style='color: #1d46cc;'></div><div class='notification-text'><div class='notification-text-title'>" + title + "</div><div class='notification-text-text'>" + text + "</div></div></div>")
  } else{
    console.error('Error: ошибка в типе уведомления!')
    return;
  }



  $(".notification").scrollTop($(".notification").prop('scrollHeight'));

}

function close_notification(a){
  var heightBlock = $(a).parent().height();


  $(a).parent().css('height', heightBlock)
  $(a).parent().css('opacity','0')
  $(a).parent().css('transform','translate(0px, 10px)')
  setTimeout(function(){
    $(a).parent().css('margin-top', '0px')
    $(a).parent().css('height', '0px')
    setTimeout(function(){
      $(a).parent().remove()
    }, 250)
  }, 1)

}

function strong_count(a){
  var ta,ba,fa,fe
  switch (a) {
    case 1:
      ta = $('#msg-input-1'),
      ba = $('#panel-msg-block-msg-textinput-textarea'),
      fa = $('#panel-msg-block-msg-textinput-file'),
      fe = $('#panel-msg-block-msg-textinput-send');
    break;
    case 2:
      ta = $('#msg-input-2'),
      ba = $('#panel-msg-block-msg-textinput-textarea-2'),
      fa = $('#panel-msg-block-msg-textinput-file-2'),
      fe = $('#panel-msg-block-msg-textinput-send-2');
    break;
  }
  var widthTS = ta.width() / 8.5;
  if(ta.val().length > widthTS || ta.val().indexOf('\n') > -1){
    ba.css('height','66px')
    ba.css('margin-top','2.5px')
    fa.css('margin-top','5.5px')
    fe.css('margin-top','5.5px')
  } else{
    ba.css('height','38px')
    ba.css('margin-top','17.5px')
    fa.css('margin-top','17.5px')
    fe.css('margin-top','17.5px')
  }
}


function add_emoji(a,b,d){
  strong_count(d)

  var tmpCursorStart = $(b)[0].selectionStart,
      emoji = $(a).html(),
      text = $(b).val();

  var tmpStringEmoji = pasteIn(text, emoji, tmpCursorStart);

  $(b).val(tmpStringEmoji);

  $(b).blur()
  $(b).focus()

  $(b)[0].setSelectionRange(tmpCursorStart, tmpCursorStart);

}

function pasteIn(inStr, subStr, pos) {
  if(typeof(pos) == 'undefined') pos = inStr.length;
  return (inStr.substring(0, pos) + subStr + inStr.substring(pos, inStr.length));
}

function open_panel(a){

  if(a == '#statistics' && !Config.statistics){
    open_window('#sale-stat','sale');
    return;
  }
  if((a == '#add_user' || a == '#all_user') && !Config.users){
    // надо окно с ценником на данный раздел
    open_window('#sale-users','sale');
    return;
  }
  if((a == '#reviews') && !Config.users){
    // надо окно с ценником на данный раздел
    open_window('#sale-reviews','sale');
    return;
  }
  if((a == '#Employees') && !Config.employees){
    // надо окно с ценником на данный раздел
    open_window('#sale-employees','sale');
    return;
  }
  if((a == '#individual_msg') && !Config.individual_msg){
    // надо окно с ценником на данный раздел
    open_window('#sale-indMsg','sale');
    return;
  }
  if(a == '#general_chat'){
    setTimeout(function(){scrollDown(false);}, 300);
  }

  if(window.innerWidth <= 835){
    NavStat = false;
    $('nav').css('left','-100%')
    $('.main-shadow').css('opacity','0')
    setTimeout(function(){
      $('.main-shadow').css('display','none')
    }, 250)
  }
  $('.panel').css('opacity','0')
  setTimeout(function(){
    $('.panel').css('display','none')
    setTimeout(function(){
      $(a).css('display','block')
      setTimeout(function(){
        $(a).css('opacity','1')
        setTimeout(function(){
          if($('#statistics').css('display') == 'block'){
            statMap('#stat-map');
          } else{
            $('#stat-map').text(' ');
            // $('#stat-map').vectorMap({})
          }
        }, 150)
      }, 150)
    },1)
  }, 150)
}

/*function welcome_func_stage(a,b){
  if(b == '1') {
    $(a).attr('onclick','welcome_func_stage(this,"2")');
    newUserStage = 1;
    $('.window-block-hello-block-conteiner-stage1').css('transform','translate(-100%, 0px)');
    $('.window-block-hello-block-conteiner-stage2').css('transform','translate(-100%, 0px)');
    $('.window-block-hello-block-conteiner-stage3').css('transform','translate(-100%, 0px)');
  }
  else if(b == '2') {
    $(a).attr('onclick','welcome_func_stage(this,"save")');
    newUserStage = 2;
    $('.window-block-hello-block-conteiner-stage1').css('transform','translate(-200%, 0px)');
    $('.window-block-hello-block-conteiner-stage2').css('transform','translate(-200%, 0px)');
    $('.window-block-hello-block-conteiner-stage3').css('transform','translate(-200%, 0px)');
    $(a).attr('onclick','welcome_func_stage(this,"save")');
    //$(a).css('display','none');
    //$('.window-block-hello-block-btn-further').text('Сохранить');
  }
  else if(b == 'save') {

  }
  else {}
}*/

function password_open(a){
  var input      = $(a).parent().find('input'),
      inputTYPE  = input.attr('type')

  if(inputTYPE == 'password'){
    input.attr('type','text')
    $(a).attr('title','Скрыть пароль')
    $(a).css('opacity','0.5')
    $(a).find('div').css('opacity','1')
  } else{
    input.attr('type','password')
    $(a).attr('title','Показать пароль')
    $(a).find('div').css('opacity','0')
    $(a).css('opacity','0.5')
  }
  input.focus()
}

/*function opacity_save_settings_f(){
  $('.window-block-settings-save').css('opacity','0.5')
  $('.window-block-settings-save').css('cursor','default')
  $('.window-block-settings-save').removeAttr('onclick')
  setTimeout(function(){
    $('.window-block-settings-save').css('transition','9999999999s all')
  }, 150)
}*/

function opacity_save_settings(){

  //console.log('opacity_save_settings() function');
  //return;

  // --------------------------------------------------
  if(localName != GlobalName || localLang != GlobalLang || localMsg != GlobalMsg || localNoti != GlobalNoti || localDescription != GlobalDescription || localEmailMain != GlobalEmailMain || localEmailForm != GlobalEmailForm || localTel != GlobalTel || localTheme != GlobalTheme || localWinter != GlobalWinter || localStat != GlobalStat || localError != GlobalError){
    $('.window-block-settings-save').css('transition','0.15s all')
    setTimeout(function(){
      $('.window-block-settings-save').css('opacity','1');
      $('.window-block-settings-save').css('cursor','pointer');
      SettingsField.chkbox_ready = true;
      //$('.window-block-settings-save').prop('disabled', false)
      //$('.window-block-settings-save').attr('onclick','save_settings()')
    },1)
  } else{
    SettingsField.chkbox_ready = false;
    $('.window-block-settings-save').css('opacity','0.2');
    $('.window-block-settings-save').css('cursor','default');
    //$('.window-block-settings-save').prop('disabled', true)
    //$('.window-block-settings-save').removeAttr('onclick')
    setTimeout(function(){
      $('.window-block-settings-save').css('transition','9999999999s all')
    }, 150)
  }
}

function open_msg_ind(){
  // это функция для примера перехода
  if(document.documentElement.clientWidth <= 835){
    $('#individual_msg-preloader').css('display','none')
    $('#individual_msg-msg').css('display','block')
    $('#individual_msg-msg').css('opacity','1')
    if(!ststus_open_ind_msg){
      ststus_open_ind_msg = true;
      $('#individual_msg').find('.panel-conteiner').css('display','none');
      $('#individual_msg').find('.panel-conteiner-full').css('display','block');
      $('#individual_msg').find('.panel-msg-block-msg-conteiner-main').scrollTop(
        $('#individual_msg').find('.panel-msg-block-msg-conteiner-main').prop('scrollHeight')
      );
    } else{
      ststus_open_ind_msg = false;
      $('#individual_msg').find('.panel-conteiner').css('display','block');
      $('#individual_msg').find('.panel-conteiner-full').css('display','none');
    }
  } else{
    $('#individual_msg-preloader').css('opacity','0')
    setTimeout(function(){
      $('#individual_msg-preloader').css('display','none')
      $('#individual_msg-msg').css('display','block')
      setTimeout(function(){
        $('#individual_msg-msg').css('opacity','1')
        $('#individual_msg').find('.panel-msg-block-msg-conteiner-main').scrollTop(
          $('#individual_msg').find('.panel-msg-block-msg-conteiner-main').prop('scrollHeight')
        );
      }, 30)
    }, 250)


  }


}

function change_user_rights(a){
  var status_user_rights = $('#user_select_main > option:selected').text(),
      b1 = $('#user_select_finder'),
      b2 = $('#user_select_statistic'),
      b3 = $('#user_select_addNews'),
      b4 = $('#user_select_statisticNews'),
      b5 = $('#user_select_accessMsg'),
      b6 = $('#user_select_contacts'),
      b7 = $('#user_select_reviews'),
      b8 = $('#user_select_timetable');

  b1.find("option").removeAttr("selected")
  b2.find("option").removeAttr("selected")
  b3.find("option").removeAttr("selected")
  b4.find("option").removeAttr("selected")
  b5.find("option").removeAttr("selected")
  b6.find("option").removeAttr("selected")
  b7.find("option").removeAttr("selected")
  b8.find("option").removeAttr("selected")

  if(status_user_rights == 'Стандартный'){
    b1.find("option:contains('Нет доступа')").attr("selected", true)
    b2.find("option:contains('Нет доступа')").attr("selected", true)
    b3.find("option:contains('Нет доступа')").attr("selected", true)
    b4.find("option:contains('Нет доступа')").attr("selected", true)
    b5.find("option:contains('Нет доступа')").attr("selected", true)
    b6.find("option:contains('Нет доступа')").attr("selected", true)
    b7.find("option:contains('Нет доступа')").attr("selected", true)
    b8.find("option:contains('Нет доступа')").attr("selected", true)
  }
  if(status_user_rights == 'Редактор'){
    b1.find("option:contains('Нет доступа')").attr("selected", true)
    b2.find("option:contains('Нет доступа')").attr("selected", true)
    b3.find("option:contains('Просмотр и редактирование')").attr("selected", true)
    b4.find("option:contains('Разрешено')").attr("selected", true)
    b5.find("option:contains('Нет доступа')").attr("selected", true)
    b6.find("option:contains('Разрешено')").attr("selected", true)
    b7.find("option:contains('Разрешено')").attr("selected", true)
    b8.find("option:contains('Нет доступа')").attr("selected", true)
  }
  if(status_user_rights == 'Модератор'){
    b1.find("option:contains('Чтение и редактирование')").attr("selected", true)
    b2.find("option:contains('Разрешено')").attr("selected", true)
    b3.find("option:contains('Просмотр и редактирование')").attr("selected", true)
    b4.find("option:contains('Разрешено')").attr("selected", true)
    b5.find("option:contains('Разрешено')").attr("selected", true)
    b6.find("option:contains('Разрешено')").attr("selected", true)
    b7.find("option:contains('Разрешено')").attr("selected", true)
    b8.find("option:contains('Разрешено')").attr("selected", true)
  }
  if(status_user_rights == 'Администратор'){
    b1.find("option:contains('Чтение и редактирование')").attr("selected", true)
    b2.find("option:contains('Разрешено')").attr("selected", true)
    b3.find("option:contains('Просмотр и редактирование')").attr("selected", true)
    b4.find("option:contains('Разрешено')").attr("selected", true)
    b5.find("option:contains('Разрешено')").attr("selected", true)
    b6.find("option:contains('Разрешено')").attr("selected", true)
    b7.find("option:contains('Разрешено')").attr("selected", true)
    b8.find("option:contains('Разрешено')").attr("selected", true)
  }
  if(status_user_rights == 'Главный администратор'){
    b1.find("option:contains('Чтение и редактирование')").attr("selected", true)
    b2.find("option:contains('Разрешено')").attr("selected", true)
    b3.find("option:contains('Просмотр и редактирование')").attr("selected", true)
    b4.find("option:contains('Разрешено')").attr("selected", true)
    b5.find("option:contains('Разрешено')").attr("selected", true)
    b6.find("option:contains('Разрешено')").attr("selected", true)
    b7.find("option:contains('Разрешено')").attr("selected", true)
    b8.find("option:contains('Разрешено')").attr("selected", true)
  }
}

function convertLanguage(lang1, lang2, strong){
  var output = '';
  if(typeof lang1 !== 'undefined' && typeof lang2 !== 'undefined' && typeof strong !== 'undefined'){
    if(lang1 == 'ru'){
      if(lang2 == 'ru'){
        return(strong);
      }
      if(lang2 == 'en'){
        for(let i = 0; i < strong.length; i++){
          if(strong[i] == 'й'){
            output += 'y';
          } else if(strong[i] == 'ц'){
            output += 'ts';
          } else if(strong[i] == 'у'){
            output += 'u';
          } else if(strong[i] == 'к'){
            output += 'k';
          } else if(strong[i] == 'е'){
            output += 'e';
          } else if(strong[i] == 'ё'){
            output += 'e';
          } else if(strong[i] == 'н'){
            output += 'n';
          } else if(strong[i] == 'г'){
            output += 'g';
          } else if(strong[i] == 'ш'){
            output += 'sh';
          } else if(strong[i] == 'щ'){
            output += 'shch';
          } else if(strong[i] == 'з'){
            output += 'z';
          } else if(strong[i] == 'х'){
            output += 'kh';
          } else if(strong[i] == 'ъ'){
            output += '';
          } else if(strong[i] == 'ф'){
            output += 'f';
          } else if(strong[i] == 'ы'){
            output += 'y';
          } else if(strong[i] == 'в'){
            output += 'v';
          } else if(strong[i] == 'а'){
            output += 'a';
          } else if(strong[i] == 'п'){
            output += 'p';
          } else if(strong[i] == 'р'){
            output += 'r';
          } else if(strong[i] == 'о'){
            output += 'o';
          } else if(strong[i] == 'л'){
            output += 'l';
          } else if(strong[i] == 'д'){
            output += 'd';
          } else if(strong[i] == 'ж'){
            output += 'zh';
          } else if(strong[i] == 'э'){
            output += 'e';
          } else if(strong[i] == 'я'){
            output += 'ya';
          } else if(strong[i] == 'ч'){
            output += 'ch';
          } else if(strong[i] == 'с'){
            output += 's';
          } else if(strong[i] == 'м'){
            output += 'm';
          } else if(strong[i] == 'и'){
            output += 'i';
          } else if(strong[i] == 'т'){
            output += 't';
          } else if(strong[i] == 'ь'){
            output += '';
          } else if(strong[i] == 'б'){
            output += 'b';
          } else if(strong[i] == 'ю'){
            output += 'yu';
          } else if(strong[i] == 'Й'){
            output += 'Y';
          } else if(strong[i] == 'Ц'){
            output += 'Ts';
          } else if(strong[i] == 'У'){
            output += 'U';
          } else if(strong[i] == 'К'){
            output += 'K';
          } else if(strong[i] == 'Е'){
            output += 'E';
          } else if(strong[i] == 'Н'){
            output += 'N';
          } else if(strong[i] == 'Г'){
            output += 'G';
          } else if(strong[i] == 'Ш'){
            output += 'Sh';
          } else if(strong[i] == 'Щ'){
            output += 'Shch';
          } else if(strong[i] == 'З'){
            output += 'Z';
          } else if(strong[i] == 'Х'){
            output += 'Kh';
          } else if(strong[i] == 'Ъ'){
            output += '';
          } else if(strong[i] == 'Ф'){
            output += 'F';
          } else if(strong[i] == 'Ы'){
            output += 'Y';
          } else if(strong[i] == 'В'){
            output += 'V';
          } else if(strong[i] == 'А'){
            output += 'A';
          } else if(strong[i] == 'П'){
            output += 'P';
          } else if(strong[i] == 'Р'){
            output += 'R';
          } else if(strong[i] == 'О'){
            output += 'O';
          } else if(strong[i] == 'Л'){
            output += 'L';
          } else if(strong[i] == 'Д'){
            output += 'D';
          } else if(strong[i] == 'Ж'){
            output += 'Zh';
          } else if(strong[i] == 'Э'){
            output += 'E';
          } else if(strong[i] == 'Я'){
            output += 'Ya';
          } else if(strong[i] == 'Ч'){
            output += 'Ch';
          } else if(strong[i] == 'С'){
            output += 'S';
          } else if(strong[i] == 'М'){
            output += 'M';
          } else if(strong[i] == 'И'){
            output += 'I';
          } else if(strong[i] == 'Т'){
            output += 'T';
          } else if(strong[i] == 'Ь'){
            output += '';
          } else if(strong[i] == 'Б'){
            output += 'B';
          } else if(strong[i] == 'Ю'){
            output += 'Yu';
          } else{
            output += strong[i];
          }
        }
        return(output);
      }
    }
    // if(lang1 == 'en'){
    //   if(lang2 == 'ru'){
    //     for(let i = 0; i < strong.length; i++){
    //       if(strong[i] == 'q'){
    //
    //       } else if(strong[i] == 'w'){
    //
    //       } else if(strong[i] == 'e'){
    //
    //       } else if(strong[i] == 'r'){
    //
    //       } else if(strong[i] == 't'){
    //
    //       } else if(strong[i] == 'y'){
    //
    //       } else if(strong[i] == 'u'){
    //
    //       } else if(strong[i] == 'i'){
    //
    //       } else if(strong[i] == 'o'){
    //
    //       } else if(strong[i] == 'p'){
    //
    //       } else if(strong[i] == 'a'){
    //
    //       } else if(strong[i] == 's'){
    //
    //       } else if(strong[i] == 'd'){
    //
    //       } else if(strong[i] == 'f'){
    //
    //       } else if(strong[i] == 'g'){
    //
    //       } else if(strong[i] == 'h'){
    //
    //       } else if(strong[i] == 'j'){
    //
    //       } else if(strong[i] == 'k'){
    //
    //       } else if(strong[i] == 'l'){
    //
    //       } else if(strong[i] == 'z'){
    //
    //       } else if(strong[i] == 'x'){
    //
    //       } else if(strong[i] == 'c'){
    //
    //       } else if(strong[i] == 'v'){
    //
    //       } else if(strong[i] == 'b'){
    //
    //       } else if(strong[i] == 'n'){
    //
    //       } else if(strong[i] == 'm'){
    //
    //       } else if(strong[i] == 'Q'){
    //
    //       } else if(strong[i] == 'W'){
    //
    //       } else if(strong[i] == 'E'){
    //
    //       } else if(strong[i] == 'R'){
    //
    //       } else if(strong[i] == 'T'){
    //
    //       } else if(strong[i] == 'Y'){
    //
    //       } else if(strong[i] == 'U'){
    //
    //       } else if(strong[i] == 'I'){
    //
    //       } else if(strong[i] == 'P'){
    //
    //       } else if(strong[i] == 'A'){
    //
    //       } else if(strong[i] == 'S'){
    //
    //       } else if(strong[i] == 'D'){
    //
    //       } else if(strong[i] == 'F'){
    //
    //       } else if(strong[i] == 'G'){
    //
    //       } else if(strong[i] == 'H'){
    //
    //       } else if(strong[i] == 'J'){
    //
    //       } else if(strong[i] == 'L'){
    //
    //       } else if(strong[i] == 'Z'){
    //
    //       } else if(strong[i] == 'X'){
    //
    //       } else if(strong[i] == 'C'){
    //
    //       } else if(strong[i] == 'V'){
    //
    //       } else if(strong[i] == 'B'){
    //
    //       } else if(strong[i] == 'N'){
    //
    //       } else if(strong[i] == 'M'){
    //
    //       } else{
    //         output += strong[i];
    //       }
    //
    //     }
    //   }
    //   if(lang2 == 'en'){
    //     return(strong);
    //   }
    // }
  } else if(typeof lang1 === 'undefined'){
    console.error('Язык входа установлен не верно! [Обязательный параметр]')
  } else if(typeof lang2 === 'undefined'){
    console.error('Язык выхода установлен не верно! [Обязательный параметр]')
  } else if(typeof strong === 'undefined'){
    console.error('Входная строка не может отсутствовать! [Обязательный параметр]')
  }
}

function SurnameGenerator(gender){
  var lastname = new Array("Смирнов","Иванов","Кузнецов","Попов","Соколов","Лебедев","Козлов","Новиков","Морозов","Петров","Соловьёв","Волков","Васильев","Зайцев","Павлов","Семёнов","Голубев","Виноградов","Богданов","Воробьёв","Фёдоров","Михайлов","Тарасов","Белов","Комаров","Орлов","Киселёв","Макаров","Андреев","Ковалёв","Ильин","Титов","Гусев","Кузьмин","Кудрявцев","Баранов","Куликов","Алексеев","Степанов","Яковлев","Сорокин","Сергеев","Романов","Захаров","Борисов","Королёв","Герасимов","Пономарёв","Лазарев","Медведев","Ершов","Никитин","Соболев","Рябов","Поляков","Цветков","Данилов","Жуков","Фролов","Журавлёв","Николаев","Крылов","Максимов","Сидоров","Осипов","Белоусов","Федотов","Дорофеев","Егоров","Матвеев","Бобров","Дмитриев","Калинин","Петухов","Антонов","Тимофеев","Баталов","Никифоров","Филиппов","Большаков","Суханов","Ширяев","Коновалов","Шестаков","Жужгов","Жуков","Казаков","Давыдов","Блинов","Афанасьев","Карпов","Тихонов","Горбунов","Быков","Зуев","Панов","Суворов","Мухин","Архипов","Горшков","Мартынов","Селезнёв","Галкин","Беляков","Хохлов","Жданов","Воронцов","Игнатьев","Моисеев","Горбачёв","Ефремов","Калашников","Носков","Прохоров","Харитонов","Шубин","Кононов","Князев","Рожков","Гущин","Субботин","Фокин","Силин","Туров","Иванков","Лыткин","Туров")
  var n = 0;

  if(gender == 'male'){
    n = lastname.length;
  }
  if(gender == 'female'){
    n = lastname.length;
  }
  var r = Math.floor(Math.random() * Math.floor(n - 1))

  if(gender == 'male'){
    return(lastname[r])
  }
  if(gender == 'female'){
    return(lastname[r] + 'а')
  }
}

function gradual_increase_in_number(out, speed, block){
  $(block).animate({ num: out - 3 /* - начало */ }, {
    duration: speed,
    step: function (num){
        this.innerHTML = (num + 3).toFixed(0);
        this.innerHTML = this.innerHTML.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
    }
  });
}

function gradual_increase_in_percent(out, speed, block){
  $(block).animate({ num: out - 3 /* - начало */ }, {
    duration: speed,
    step: function (num){
        this.innerHTML = (num + 3).toFixed(2);
        if(this.innerHTML < 0){
          $(this).css('color','#fd3939')
        } else{
          $(this).css('color','#0abb87')
          this.innerHTML = '+' + this.innerHTML;
        }
        this.innerHTML += '%';
    }
  });
}

function change_type_chart3(a,b,c){
  var block = $(a).find('span');
  var count = $(a).find('span').length;

  if(b == 'top' && count_type_chart3 > 0){
    count_type_chart3--;
    for(let i = 0; i < count; i++){
      $(block[i]).css('transform','translate(0px, ' + (-18 * count_type_chart3) + 'px)')
    }
    if(count_type_chart3 == 0){
      $('.panel-conteiner-width-small3-type-btn').css('opacity','1')
      $('.panel-conteiner-width-small3-type-btn').css('cursor','pointer')
      $(c).css('opacity','0.225')
      $(c).css('cursor','default')

    } else{
      $('.panel-conteiner-width-small3-type-btn').css('opacity','1')
      $('.panel-conteiner-width-small3-type-btn').css('cursor','pointer')
    }

    if(count_type_chart3 == 0){
      chart3.destroy();
      chart3 = new ApexCharts(document.querySelector("#chart3"), chart3_params);
      chart3.render();
      gradual_increase_in_number(gradualIncreaseVal1, 1000, '#panel-conteiner-width-small3-count-id')
      gradual_increase_in_percent(gradualIncreasePer1, 1000, '.panel-conteiner-width-small3-count-percent')
    }
    if(count_type_chart3 == 1){
      chart3.destroy();
      chart3 = new ApexCharts(document.querySelector("#chart3"), chart3_1_params);
      chart3.render();
      gradual_increase_in_number(gradualIncreaseVal2, 1000, '#panel-conteiner-width-small3-count-id')
      gradual_increase_in_percent(gradualIncreasePer2, 1000, '.panel-conteiner-width-small3-count-percent')
    }
  }
  if(b == 'bottom' && count_type_chart3 >= 0 && count - 1 != count_type_chart3){
    count_type_chart3++;
    for(let i = 0; i < count; i++){
      $(block[i]).css('transform','translate(0px, ' + (-18 * count_type_chart3) + 'px)')
    }
    if(count_type_chart3 == count - 1){
      $('.panel-conteiner-width-small3-type-btn').css('opacity','1')
      $('.panel-conteiner-width-small3-type-btn').css('cursor','pointer')
      $(c).css('opacity','0.225')
      $(c).css('cursor','default')
    } else{
      $('.panel-conteiner-width-small3-type-btn').css('opacity','1')
      $('.panel-conteiner-width-small3-type-btn').css('cursor','pointer')
    }
    if(count_type_chart3 == 0){
      gradual_increase_in_number(gradualIncreaseVal1, 1000, '#panel-conteiner-width-small3-count-id')
      gradual_increase_in_percent(gradualIncreasePer1, 1000, '.panel-conteiner-width-small3-count-percent')
      chart3.destroy();
      chart3 = new ApexCharts(document.querySelector("#chart3"), chart3_params);
      chart3.render();
    }
    if(count_type_chart3 == 1){
      gradual_increase_in_number(gradualIncreaseVal2, 1000, '#panel-conteiner-width-small3-count-id')
      gradual_increase_in_percent(gradualIncreasePer2, 1000, '.panel-conteiner-width-small3-count-percent')
      chart3.destroy();
      chart3 = new ApexCharts(document.querySelector("#chart3"), chart3_1_params);
      chart3.render();
    }
  }
}

function EmailGenerator(arrayMETA){
  var output = '';
  var n = arrayMETA.length;
  var arrayMETA_1 = [];

  for(let i = 0; i < n; i++){
    arrayMETA_1.push(convertLanguage('ru','en',arrayMETA[i]))
  }

  if(getRandomInt(2) == 1){
    if(getRandomInt(2) == 1){
      output = arrayMETA_1[0] + '_' + arrayMETA_1[2];
    } else {
      if(getRandomInt(2) == 1){

        if(getRandomInt(2) == 1){
          output = arrayMETA_1[0] + '.' + getRandomInt(999) + '.' + arrayMETA_1[2];
        } else{
          output = arrayMETA_1[0] + '.' + arrayMETA_1[2] + getRandomInt(999);
        }
      } else {
        output = arrayMETA_1[0] + '.' + arrayMETA_1[2];
      }
    }
  } else{
    if(getRandomInt(2) == 1){
      if(getRandomInt(2) == 1){
        output = arrayMETA_1[1] + '_' + arrayMETA_1[2] + getRandomInt(999);
      } else {
        output = arrayMETA_1[1] + '_' + arrayMETA_1[2];
      }

    } else {
      if(getRandomInt(2) == 1){
        output = arrayMETA_1[1] + '.' + arrayMETA_1[2] + getRandomInt(999);
      } else {
        output = arrayMETA_1[1] + '.' + arrayMETA_1[2];
      }
    }
  }
  var randomCount = getRandomInt(9);
  if(randomCount == 0){
    output += '@bk.ru';
  }
  if(randomCount == 1){
    output += '@mail.ru';
  }
  if(randomCount == 2){
    output += '@gmail.com';
  }
  if(randomCount == 3){
    output += '@hotmail.com';
  }
  if(randomCount == 4){
    output += '@yandex.ru';
  }
  if(randomCount == 5){
    output += '@ya.ru';
  }
  if(randomCount == 6){
    output += '@rambler.ru';
  }
  if(randomCount == 7){
    output += '@yandex.com';
  }
  if(randomCount == 8){
    output += '@inbox.com';
  }
  if(randomCount == 9){
    output += '@sibnet.com';
  }

  return(output);
}

function NameGenerator(gender){
  var malename = new Array("Аким","Александр","Алексей", "Анатолий","Андрей","Антон", "Аркадий","Арсен","Арсений", "Артём","Артур","Богдан", "Борис","Борислав","Вадим", "Валерий","Василий","Виктор", "Виталий","Владимир","Владислав", "Вячеслав","Герасим","Георгий", "Глеб","Герман","Давид", "Даниил","Данил","Данила", "Денис","Дмитрий","Добрыня", "Захар","Иван","Игнат","Игорь","Илья","Иосиф","Касьян","Кирилл","Константин","Кузьма","Лев","Леонид","Макар","Максим","Марк","Марат","Михаил","Моисей","Никита","Никола","Николай","Павел","Пётр","Потап","Роман","Ростислав","Рустам","Святослав","Станислав","Степан","Терентий","Тимофей","Тимур","Тихон","Фёдор","Филимон","Филипп","Фома","Харитон","Чарли","Эдуард","Эрик","Юрий","Ян","Яков","Яромир","Ярослав")
  var femalename = new Array("Ада","Альбина","Алёна", "Александрина","Александра","Анисья", "Анфиса","Амалия","Анастасия", "Ангелина","Анжела","Анжелика", "Алина","Анна","Арина", "Ася","Галина","Дарья", "Диана","Дина","Ева", "Елизавета","Екатерина","Зинаида", "Изабелла","Ирина","Кристина", "Ксения","Лера","Людмила", "Наталия","Надежда","Наталья", "Нина","Олеся","Полина", "Соня","Софья","Яна","Ярослава")
  var n = 0;
  if(gender == 'male'){
    n = malename.length;
  }
  if(gender == 'female'){
    n = femalename.length;
  }
  var r = Math.floor(Math.random() * Math.floor(n - 1))

  if(gender == 'male'){
    return(malename[r])
  }
  if(gender == 'female'){
    return(femalename[r])
  }

}

function LoginGenerator(){
  var syllable_1 = new Array("B", "D", "F", "G", "Gl", "H", "K", "L", "M", "N", "R", "S", "T", "Th", "V");
  var syllable_2 = new Array("a", "e", "i", "o", "oi", "u");
  var syllable_3 = new Array("bur", "fur","terac", "gan","glud", "gnus", "gnar", "li", "lin", "lir", "mli", "nar", "nus", "rin", "ran", "sin", "sil", "sur");

  return (syllable_1[Math.round(Math.random()*(15 - 1))] + syllable_2[Math.round(Math.random()*(6 - 1))] + syllable_3[Math.round(Math.random()*(16 - 1))])
}

function NumberPhoneGenerator(type){
  var codeCountry = '+7';
  var codyCity = new Array("900","902","903","904","905","906","908","909","950","951","953","960","961","962","963","964","965","966","967","968","969","980","983","986","901","902","904","908","910","911","912","913","914","915","916","917","918","919","950","978","980","981","982","983","984","985","987","988","989","902","904","908","920","921","922","923","924","925","926","927","928","929","930","931","932","933","934","936","937","938","939","950","951","999","900","901","902","904","908","950","951","952","953","958","977","991","992","993","994","995","996","999","958","991","995","996","999");
  var number = '';
  var n = codyCity.length;
  var r = Math.floor(Math.random() * Math.floor(n - 1))

  if(type == 'style'){
    for(let i = 0; i < 10; i++){
      if(i == 0){
        number = number + ' '
      }
      if(i == 3){
        number = number + '-'
      }
      if(i == 6){
        number = number + '-'
      }
      if(i != 3 && i != 0 && i != 6){
        number = number + getRandomInt(9);
      }
    }
    return(codeCountry + ' (' + codyCity[r] + ')' + number);
  } else{
    for(let i = 0; i < 7; i++){
      number = number + getRandomInt(9);
    }
    return(codeCountry + codyCity[r] + number);
  }
}

function CityGenerator(){
  var city = new Array("Москва","Санкт-Петербург","Новосибирск","Екатеринбург","Нижний Новгород","Самара","Омск","Казань","Челябинск","Ростов-на-Дону","Уфа","Волгоград","Пермь","Красноярск","Воронеж","Саратов","Краснодар","Тольятти","Ижевск","Ульяновск","Барнаул","Владивосток","Ярославль","Иркутск","Тюмень","Махачкала","Хабаровск","Новокузнецк","Оренбург","Кемерово","Рязань","Томск","Астрахань","Пенза","Набережные Челны","Липецк","Тула","Киров","Чебоксары","Калининград","Брянск","Курск","Иваново","Магнитогорск","Улан-Удэ","Тверь","Ставрополь","Нижний Тагил","Белгород","Архангельск","Владимир","Сочи","Курган","Смоленск","Калуга","Чита","Орёл","Волжский","Череповец","Владикавказ","Мурманск","Сургут","Вологда","Саранск","Тамбов","Стерлитамак","Грозный","Якутск","Кострома","Комсомольск-на-Амуре","Петрозаводск","Таганрог","Нижневартовск","Йошкар-Ола","Братск","Новороссийск","Дзержинск","Шахты","Нальчик","Орск","Сыктывкар","Нижнекамск","Ангарск","Старый Оскол","Великий Новгород","Балашиха","Благовещенск","Прокопьевск","Бийск","Химки","Псков","Энгельс","Рыбинск","Балаково","Северодвинск","Армавир","Подольск","Королёв","Южно-Сахалинск","Петропавловск-Камчатский","Сызрань","Норильск","Златоуст","Каменск-Уральский","Мытищи","Люберцы","Волгодонск","Новочеркасск","Абакан","Находка","Уссурийск","Березники","Салават","Электросталь","Миасс","Рубцовск","Альметьевск","Ковров","Коломна","Майкоп","Пятигорск","Одинцово","Колпино","Копейск","Хасавюрт","Кисловодск","Новомосковск","Серпухов","Черкесск","Камышин","Муром","Воткинск","Магадан")
  var n = city.length;
  var r = Math.floor(Math.random() * Math.floor(n - 1))
  return(city[r]);
}

function getRandomInt(max) {
  return Math.floor(Math.random() * Math.floor(max));
}

function PasswordGenerator(count){
  let alphabet = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm1234567890__!!--',
  word = '';
  if(count > 0){
    for(let i = 0; i < count; i++){
      word += alphabet[Math.round(Math.random() * (alphabet.length - 1))];
    }
    return(word);
  } else{
    console.error('Строка не может быть пустой!')
  }
}

function open_elem_and_generate(a){
  if(a == '#panel-user-add'){

    var genderLocal = '';

    if(getRandomInt(2) == 1){
      genderLocal = 'female';
    } else{
      genderLocal = 'male';
    }

    var bb = PasswordGenerator(12);
    var bblogin = LoginGenerator();
    var bbName = NameGenerator(genderLocal);
    var bbSurname = SurnameGenerator(genderLocal);
    var bbEmail = EmailGenerator([bblogin,bbName,bbSurname]);

    $(a).find('#add_user_login').val(bblogin);
    $(a).find('#password-edit-profile-2-0001').val(bb)
    $(a).find('#password-edit-profile-2-0002').val(bb)
    $(a).find('#add_user_name').val(bbName)
    $(a).find('#add_user_surname').val(bbSurname)
    $(a).find('#add_user_city').val(CityGenerator())
    $(a).find('#add_user_phone').val(NumberPhoneGenerator('style'))
    $(a).find('#add_user_email').val(bbEmail)

    $('.panel-table-null').css('opacity','0')
    setTimeout(function(){
      $('.panel-table-null').css('display','none')
      $(a).css('display','block')
      setTimeout(function(){
        $(a).css('opacity','1')
      }, 1);
    }, 150)
  }
}

function open_elem(a){
  if(a == '#panel-user-add'){
    $('.panel-table-null').css('opacity','0')
    setTimeout(function(){
      $('.panel-table-null').css('display','none')
      $(a).css('display','block')
      setTimeout(function(){
        $(a).css('opacity','1')
      }, 1);
    }, 150)
  }
}

function getHexRGBColor(color){
  color = color.replace(/\s/g,"");
  var aRGB = color.match(/^rgb\((\d{1,3}[%]?),(\d{1,3}[%]?),(\d{1,3}[%]?)\)$/i);

  if(aRGB)
  {
    color = '';
    for (var i=1;  i<=3; i++) color += Math.round((aRGB[i][aRGB[i].length-1]=="%"?2.55:1)*parseInt(aRGB[i])).toString(16).replace(/^(.)$/,'0$1');
  }
  else color = color.replace(/^#?([\da-f])([\da-f])([\da-f])$/i, '$1$1$2$2$3$3');

  return color;
}

function add_color(a){
  var localColor = $(a).val();
  var output = '';
  if(arrarColor.length <= 9){
    arrarColor.push(localColor);
  } else{
    arrarColor.shift();
    arrarColor.push(localColor);
  }
  for(let i = 0; i < arrarColor.length; i++){
    output += "<div class='window-block-conteiner-color' style='background-color: " + arrarColor[i] + ";' title='" + arrarColor[i] + "'><div class='window-block-conteiner-color-close icon-plus' title='Удалить' onclick='del_color(this)'></div></div>";
  }
  $('#color-edit-new').html(output)
  $('#color-edit-new-company').html(output)
}

function add_bg_color(a){
  var localColor = $(a).val();
  var output = '';
  if(arrarBgColor.length <= 9){
    arrarBgColor.push(localColor);
  } else{
    arrarBgColor.shift();
    arrarBgColor.push(localColor);
  }
  for(let i = 0; i < arrarBgColor.length; i++){
    output += "<div class='window-block-conteiner-color' style='background-color: " + arrarBgColor[i] + ";' title='" + arrarBgColor[i] + "'><div class='window-block-conteiner-color-close icon-plus' title='Удалить' onclick='del_bg_color(this)'></div></div>";
  }
  $('#bg_color-edit-new').html(output)
  $('#bg_color-edit-new-company').html(output)
}

function del_color(a){
  var localColor = $(a).parent().css('background-color');

  if(localColor.substr(0, 1) != '#'){
    localColor = '#' + getHexRGBColor(localColor);
  }
  var output = '';
  let valueColor = localColor;

  arrarColor = arrarColor.filter(item => item !== valueColor)

  for(let i = 0; i < arrarColor.length; i++){
    output += "<div class='window-block-conteiner-color' onclick='color_selection(this)' style='background-color: " + arrarColor[i] + ";' title='" + arrarColor[i] + "'><div class='window-block-conteiner-color-close icon-plus' title='Удалить' onclick='del_color(this)'></div></div>";
  }
  $('#color-edit-new').html(output)
  $('#color-edit-new-company').html(output)

}

function del_bg_color(a){
  var localColor = $(a).parent().css('background-color');

  if(localColor.substr(0, 1) != '#'){
    localColor = '#' + getHexRGBColor(localColor);
  }
  var output = '';
  let valueColor = localColor;

  arrarBgColor = arrarBgColor.filter(item => item !== valueColor)

  for(let i = 0; i < arrarBgColor.length; i++){
    output += "<div class='window-block-conteiner-color' onclick='color_selection_2(this)' style='background-color: " + arrarBgColor[i] + ";' title='" + arrarBgColor[i] + "'><div class='window-block-conteiner-color-close icon-plus' title='Удалить' onclick='del_bg_color(this)'></div></div>";
  }
  $('#bg_color-edit-new').html(output)
  $('#bg_color-edit-new-company').html(output)

}

function color_selection(a){
  var localColor = $(a).css('background-color');
  localColor = '#' + getHexRGBColor(localColor);
  if($('#news').css('display') == 'block'){
    newsColor = localColor;
    $('#panel-news_add-nav-elem-letter-color-id').css('background-color',localColor)
  } else{
    companyColor = localColor;
    $('#panel-news_add-nav-elem-letter-color-id-company').css('background-color',companyColor)
  }
}

function color_selection_3(){
  localColor2 = $('#panel-news_add-nav-elem-letter-bg_color-id').css('background-color');
  localColor2 = '#' + getHexRGBColor(localColor2);
  localColor = '#fff0';

  if($('#news').css('display') == 'block'){
    newsBgColor = localColor;
    $('#panel-news_add-nav-elem-letter-bg_color-id').css('background-color',newsBgColor)
  } else{
    companyBgColor = localColor;
    $('#panel-news_add-nav-elem-letter-bg_color-id-company').css('background-color',companyBgColor)
  }
  setTimeout(function(){
    if($('#news').css('display') == 'block'){
      newsBgColor = localColor2;
      $('#panel-news_add-nav-elem-letter-bg_color-id').css('background-color',newsBgColor)
    } else{
      companyBgColor = localColor2;
      $('#panel-news_add-nav-elem-letter-bg_color-id-company').css('background-color',companyBgColor)
    }
  }, 1);
}

function color_selection_2(a){
  var localColor = $(a).css('background-color');
  localColor = '#' + getHexRGBColor(localColor);

  if($('#news').css('display') == 'block'){
    newsBgColor = localColor;
    $('#panel-news_add-nav-elem-letter-bg_color-id').css('background-color',newsBgColor)
  } else{
    companyBgColor = localColor;
    $('#panel-news_add-nav-elem-letter-bg_color-id-company').css('background-color',companyBgColor)
  }
}

function save_bg_color_add(a){
  var output = '';

  if(arrarBgColor.length > 0){
    $('#custom-bg_color-new').css('display','block')
    for(let i = 0; i < arrarBgColor.length; i++) {
      output += "<div class='panel-news_add-nav-elem-edit-color-container-big_elem' style='margin-right: 3px;'><div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick='color_selection_2(this)' style='background-color: " + arrarBgColor[i] + ";' title='" + arrarBgColor[i] + "'></div></div>"
    }
  } else{
    $('#custom-bg_color-new').css('display','none')
    output = '';
  }
  $('#custom-bg_color-new-span').html(output)
  $('#custom-bg_color-new-span-company').html(output)
  close_window(a)

  var arrarColorCookies = '';
  for(let i = 0; i < arrarBgColor.length; i++) {
    var arrarColorCookiesL = arrarBgColor[i].slice(1,arrarBgColor[i].length);
    if(i == 0){

      arrarColorCookies += arrarColorCookiesL;
    } else{
      arrarColorCookies += '_' + arrarColorCookiesL;
    }

  }

  $.cookie('NewsColorArray_2', arrarColorCookies, {expires: 99999});

}

function save_color_add(a){
  var output = '';

  if(arrarColor.length > 0){
    $('#custom-color-new').css('display','block')
    for(let i = 0; i < arrarColor.length; i++) {
      output += "<div class='panel-news_add-nav-elem-edit-color-container-big_elem' style='margin-right: 3px;'><div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick='color_selection(this)' style='background-color: " + arrarColor[i] + ";' title='" + arrarColor[i] + "'></div></div>"
    }
  } else{
    $('#custom-color-new').css('display','none')
    output = '';
  }
  $('#custom-color-new-span').html(output)
  $('#custom-color-new-span-company').html(output)
  close_window(a)

  var arrarColorCookies = '';
  for(let i = 0; i < arrarColor.length; i++) {
    var arrarColorCookiesL = arrarColor[i].slice(1,arrarColor[i].length);
    if(i == 0){

      arrarColorCookies += arrarColorCookiesL;
    } else{
      arrarColorCookies += '_' + arrarColorCookiesL;
    }

  }

  $.cookie('NewsColorArray', arrarColorCookies, {expires: 99999});

}

function edit_code_news(a){
	hljs.highlightBlock(a);
}

function news_del_file(a){
  var block = $(a).parent();
  block.css('transform','rotate(-45deg) scale(0.1)')
  block.css('opacity','0')
  setTimeout(function(){
    block.css('width','0px')
    block.css('margin-right','-6.93px')
    setTimeout(function(){
      block.remove();
    }, 250)
  }, 150)
}

function edit_text(a){
  if(a == 'bold'){

  }
}

function news_type(a){
  if(a == 'standart'){
    News['mode'] = 'standart';
    var htmltext = $('#news').find('.panel-news-conteiner-code').text()
    $('#news').find('#panel-news_tabs-tabs-standart').css('padding-bottom','6px')
    $('#news').find('#panel-news_tabs-tabs-pro').css('padding-bottom','0px')
    $('#news').find('#panel-news_tabs-tabs-pro').css('filter','grayscale(100%) brightness(95%)')
    $('#news').find('#panel-news_tabs-tabs-standart').css('filter','initial')
    $('#news').find('.panel-news_add-nav').css('max-height', 'initial')
    $('#news').find('.panel-news-description').css('max-height','0px')
    $('#news').find('.panel-news_add-nav').css('overflow','initial')
    $('#news').find('.panel-news-conteiner-code').parent().css('display','none')
    $('#news').find('.panel-news-conteiner-text').css('display','block')
    $('#news').find('.panel-news-conteiner-text').html(htmltext)
  }
  if(a == 'pro'){
    News['mode'] = 'pro';
    var htmltext = $('#news').find('.panel-news-conteiner-text').html()
    $('#news').find('#panel-news_tabs-tabs-standart').css('padding-bottom','0px')
    $('#news').find('#panel-news_tabs-tabs-pro').css('padding-bottom','6px')
    $('#news').find('#panel-news_tabs-tabs-standart').css('filter','grayscale(100%) brightness(95%)')
    $('#news').find('#panel-news_tabs-tabs-pro').css('filter','initial')
    var height = $('#news').find('.panel-news_add-nav').height();
    $('#news').find('.panel-news_add-nav').css('max-height', height)
    $('#news').find('.panel-news-conteiner-code').text(htmltext)
    setTimeout(function(){
      $('#news').find('.panel-news_add-nav').css('max-height', '0px')
      $('#news').find('.panel-news-description').css('max-height','400px')
      $('#news').find('.panel-news_add-nav').css('overflow','hidden')
      $('#news').find('.panel-news-conteiner-code').parent().css('display','block')
      $('#news').find('.panel-news-conteiner-text').css('display','none')
      setTimeout(function(){
        hljs.highlightBlock(document.getElementById('panel-news-conteiner-code-id'));
      }, 10)
    }, 1)
  }
}

function printDoc(){

  if($('#news').css('display') == 'block'){
    var htmlNewsText = $('#panel-news-conteiner-text-id').html()
    var htmlNewsTitle = $('#panel-news-conteiner-title-id').val()
    var htmlNewsSample = "<!DOCTYPE html> <html dir='ltr'> <head> <meta charset='utf-8'> <title></title> <style> @import 'media/fonts/fonts.css'; body{ font-family: pfm; color: #303036; } .logo{ position: relative; height: 100px; width: 240px; background-image: url('media/img/cloudlyAPLogoText.png'); background-repeat: no-repeat; filter: saturate(2.1) grayscale(1); background-size: contain; } .status{ position: absolute; right: 20px; top: 20px; } .title{ font-family: pfb; font-size: 25px; margin-left: 10px; } .text{ white-space: pre-line; word-wrap: break-word; margin-left: 10px; margin-top: 5px; } .status-text{ position: relative; text-align: left; line-height: 22px; } .status-text-block{ padding-left: 10px; padding-right: 10px; border-radius: 5px; background-color: #d2d2d2; padding-top: 2px; padding-bottom: 2px; font-family: pfm; } </style> </head> <body onload='window.print();'> <div style='width: 100%; height: 210px;'> <div class='status'> <div class='logo'></div> <div class='status-text'> <span style='white:space: nowrap;'>Логин: <span style='font-family: pfl;'>" + userData['login'] + "</span> <span class='status-text-block'>" + userData['access'] + "</span><span> <br> Имя и фамилия: <span style='font-family: pfl;'>" + userData['name1'] + ' ' + userData['name2'] + "</span> <br> Телефон: <span style='font-family: pfl;'>" + userData['phone'] + "</span> <br> Почта: <span style='font-family: pfl;'>" + userData['email'] + "</span> <br> </div> </div> </div> <div class='title'>" + htmlNewsTitle + "</div> <div class='text'>" + htmlNewsText + "</div> </body> </html> ";
    var oPrntWin = window.open("","_blank","width=450,height=470,left=400,top=100,menubar=yes,toolbar=no,location=no,scrollbars=yes");
    oPrntWin.document.open();
    oPrntWin.document.write(htmlNewsSample);
    oPrntWin.document.close();
  } else{
    var htmlNewsText = $('#panel-news-conteiner-text-id-2-company').html()
    var htmlNewsTitle = $('#panel-news-conteiner-title-id-company').text()
    var htmlNewsSample = "<!DOCTYPE html> <html dir='ltr'> <head> <meta charset='utf-8'> <title></title> <style> @import 'media/fonts/fonts.css'; body{ font-family: pfm; color: #303036; } .logo{ position: relative; height: 100px; width: 240px; background-image: url('media/img/cloudlyAPLogoText.png'); background-repeat: no-repeat; filter: saturate(2.1) grayscale(1); background-size: contain; } .status{ position: absolute; right: 20px; top: 20px; } .title{ font-family: pfb; font-size: 25px; margin-left: 10px; } .text{ white-space: pre-line; word-wrap: break-word; margin-left: 10px; margin-top: 5px; } .status-text{ position: relative; text-align: left; line-height: 22px; } .status-text-block{ padding-left: 10px; padding-right: 10px; border-radius: 5px; background-color: #d2d2d2; padding-top: 2px; padding-bottom: 2px; font-family: pfm; } </style> </head> <body onload='window.print();'> <div style='width: 100%; height: 210px;'> <div class='status'> <div class='logo'></div> <div class='status-text'> <span style='white:space: nowrap;'>Логин: <span style='font-family: pfl;'>" + userData['login'] + "</span> <span class='status-text-block'>" + userData['access'] + "</span><span> <br> Имя и фамилия: <span style='font-family: pfl;'>" + userData['name1'] + ' ' + userData['name2'] + "</span> <br> Телефон: <span style='font-family: pfl;'>" + userData['phone'] + "</span> <br> Почта: <span style='font-family: pfl;'>" + userData['email'] + "</span> <br> </div> </div> </div> <div class='title'>" + htmlNewsTitle + "</div> <div class='text'>" + htmlNewsText + "</div> </body> </html> ";
    var oPrntWin = window.open("","_blank","width=450,height=470,left=400,top=100,menubar=yes,toolbar=no,location=no,scrollbars=yes");
    oPrntWin.document.open();
    oPrntWin.document.write(htmlNewsSample);
    oPrntWin.document.close();
  }
}

function about_company_type(a){
  if(a == 'standart'){
    var htmltext = $('#about_company').find('.panel-news-conteiner-code').text()
    $('#about_company').find('#panel-news_tabs-tabs-standart-2').css('padding-bottom','6px')
    $('#about_company').find('#panel-news_tabs-tabs-pro-2').css('padding-bottom','0px')
    $('#about_company').find('#panel-news_tabs-tabs-pro-2').css('filter','grayscale(100%) brightness(95%)')
    $('#about_company').find('#panel-news_tabs-tabs-standart-2').css('filter','initial')
    $('#about_company').find('.panel-news_add-nav').css('max-height', 'initial')
    $('#about_company').find('.panel-news-description').css('max-height','0px')
    $('#about_company').find('.panel-news_add-nav').css('overflow','initial')
    $('#about_company').find('.panel-news-conteiner-code').parent().css('display','none')
    $('#about_company').find('.panel-news-conteiner-text').css('display','block')
    $('#about_company').find('.panel-news-conteiner-text').html(htmltext)
  }
  if(a == 'pro'){
    var htmltext = $('#about_company').find('.panel-news-conteiner-text').html()
    $('#about_company').find('#panel-news_tabs-tabs-standart-2').css('padding-bottom','0px')
    $('#about_company').find('#panel-news_tabs-tabs-pro-2').css('padding-bottom','6px')
    $('#about_company').find('#panel-news_tabs-tabs-standart-2').css('filter','grayscale(100%) brightness(95%)')
    $('#about_company').find('#panel-news_tabs-tabs-pro-2').css('filter','initial')
    var height = $('#about_company').find('.panel-news_add-nav').height();
    $('#about_company').find('.panel-news_add-nav').css('max-height', height)
    $('#about_company').find('.panel-news-conteiner-code').text(htmltext)
    setTimeout(function(){
      $('#about_company').find('.panel-news_add-nav').css('max-height', '0px')
      $('#about_company').find('.panel-news-description').css('max-height','400px')
      $('#about_company').find('.panel-news_add-nav').css('overflow','hidden')
      $('#about_company').find('.panel-news-conteiner-code').parent().css('display','block')
      $('#about_company').find('.panel-news-conteiner-text').css('display','none')
      setTimeout(function(){
        hljs.highlightBlock(document.getElementById('panel-news-conteiner-code-id-2-company'));
      }, 10)
    }, 1)
  }
}

function welcome_theme(a){
  if(a == 'white'){
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
  } else{
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
  }
  $.cookie('theme', GlobalTheme, {expires: 99999});
}

function placeCaretAtEnd(el) {
  el.focus();
  if(typeof window.getSelection != "undefined" && typeof document.createRange != "undefined") {
    var range = document.createRange();
    range.selectNodeContents(el);
    range.collapse(false);
    var sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(range);
  }
  else if(typeof document.body.createTextRange != "undefined") {
    var textRange = document.body.createTextRange();
    textRange.moveToElementText(el);
    textRange.collapse(false);
    textRange.select();
  }
}

function change_stat(a){
  if(!$(a).prop("checked")){
    localStat = true;
    opacity_save_settings()
  } else{
    localStat = false;
    opacity_save_settings()
  }
}

function langPanelF(){
  localLang = $('#LangPanel').val();
  opacity_save_settings();
}

function change_error(a){
  if(!$(a).prop("checked")){
    localError = true;
    opacity_save_settings()
  } else{
    localError = false;
    opacity_save_settings()
  }
}

function change_noti(a){
  if(!$(a).prop("checked")){
    localNoti = true;
    opacity_save_settings()
  } else{
    localNoti = false;
    opacity_save_settings()
  }
}

function change_msg(a){
  if(!$(a).prop("checked")){
    localMsg = true;
    opacity_save_settings()
  } else{
    localMsg = false;
    opacity_save_settings()
  }
}

function change_winter(a){
  if(!$(a).prop("checked")){
    localWinter = true;
    opacity_save_settings()
  } else{
    localWinter = false;
    opacity_save_settings()
  }
}

function change_media(){
  setTimeout(function(){
    var line  = $('#typeline').prop('checked'),
        block = $('#typeblock').prop('checked');

    if(line){
      $('.panel-conteiner-all-block-main-block').css('display','block')
      $('.panel-conteiner-all-block-main-block').css('vertical-align','')
      $('.panel-conteiner-all-block-main-block').css('border-left','')
      $('.panel-conteiner-all-block-main-block').css('border-right','')
      $('.panel-conteiner-all-block-main-block').css('border-top','')
      $('.panel-conteiner-all-block-main-block').css('margin-top','0px')
      $('.panel-conteiner-all-block-main-block').css('margin-bottom','0px')
      $('.panel-conteiner-all-block-main-block').css('margin-left','0px')
      $('.panel-conteiner-all-block-main-block').css('border-radius','0px')
      $('.panel-conteiner-all-block-main-block-img').css('left','')
      $('.panel-conteiner-all-block-main-block-img').css('right','')
      $('.panel-conteiner-all-block-main-block-img').css('margin','')
      $('.panel-conteiner-all-block-main-block-img').css('display','inline-block')
      $('.panel-conteiner-all-block-main-block-img').css('margin-bottom','10px')
      $('.panel-conteiner-all-block-main-block-text').css('display','inline-block')
      $('.panel-conteiner-all-block-main-block-text-main').css('width','calc(100% - 120px)')
      $('.panel-conteiner-all-block-main-block-text-main').css('text-align','left')
      $('.panel-conteiner-all-block-main-block-text-main-title').css('margin-left','8px')
      $('.panel-conteiner-all-block-main-block-text-main-title-size').css('margin-left','10px')
      $('.panel-conteiner-all-block-main-block-text-main-info').css('display','block')
      $('.panel-conteiner-all-block-main-block-text-main-btn').css('margin-left','8px')
      $('.panel-conteiner-all-block-main-block-text-main-btn-del').text('Удалить навсегда')
      $('.panel-conteiner-all-block-main-block-text-date').css('display','inline-block')
      $('.panel-conteiner-all-block-main-block').css('width','auto')
      $('.panel-conteiner-all-block-main-block-text').css('width','calc(100% - 88px)')
      $('.panel-conteiner-all-block-main-block-text').css('left','')
      $('.panel-conteiner-all-block-main-block-text').css('right','')
      $('.panel-conteiner-all-block-main-block-text').css('margin','')
      $('.panel-conteiner-all-block-main-block-text').css('text-align','left')
      $('.panel-conteiner-all-block-main-block').css('height','auto')
      $('.panel-conteiner-all-block-main-block-text-main-title').css('max-height','')
    } else{
      $('.panel-conteiner-all-block-main-block').css('display','inline-block')
      $('.panel-conteiner-all-block-main-block').css('vertical-align','middle')
      $('.panel-conteiner-all-block-main-block').css('border-left','1px solid var(--border-color)')
      $('.panel-conteiner-all-block-main-block').css('border-right','1px solid var(--border-color)')
      $('.panel-conteiner-all-block-main-block').css('border-top','1px solid var(--border-color)')
      $('.panel-conteiner-all-block-main-block').css('margin-top','7.5px')
      $('.panel-conteiner-all-block-main-block').css('margin-bottom','7.5px')
      $('.panel-conteiner-all-block-main-block').css('margin-left','15px')
      $('.panel-conteiner-all-block-main-block').css('border-radius','7.5px')
      $('.panel-conteiner-all-block-main-block-img').css('left','0')
      $('.panel-conteiner-all-block-main-block-img').css('right','0')
      $('.panel-conteiner-all-block-main-block-img').css('margin','auto')
      $('.panel-conteiner-all-block-main-block-img').css('display','block')
      $('.panel-conteiner-all-block-main-block-img').css('margin-bottom','9px')
      $('.panel-conteiner-all-block-main-block-text').css('display','block')
      $('.panel-conteiner-all-block-main-block-text-main').css('width','120px')
      $('.panel-conteiner-all-block-main-block-text-main').css('text-align','center')
      $('.panel-conteiner-all-block-main-block-text-main-title').css('margin-left','0px')
      $('.panel-conteiner-all-block-main-block-text-main-title-size').css('margin-left','0px')
      $('.panel-conteiner-all-block-main-block-text-main-info').css('display','none')
      $('.panel-conteiner-all-block-main-block-text-main-btn').css('margin-left','0px')
      $('.panel-conteiner-all-block-main-block-text-main-btn-del').text('Удалить')
      $('.panel-conteiner-all-block-main-block-text-date').css('display','none')
      $('.panel-conteiner-all-block-main-block').css('width','150px')
      $('.panel-conteiner-all-block-main-block-text').css('width','auto')
      $('.panel-conteiner-all-block-main-block-text').css('left','0')
      $('.panel-conteiner-all-block-main-block-text').css('right','0')
      $('.panel-conteiner-all-block-main-block-text').css('margin','auto')
      $('.panel-conteiner-all-block-main-block-text').css('text-align','center')
      $('.panel-conteiner-all-block-main-block').css('height','150px')
      $('.panel-conteiner-all-block-main-block-text-main-title').css('max-height','66px')
    }
  },10)
}

function change_theme(a){
  if(!$(a).prop("checked")){
    localTheme = 'black';
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
    opacity_save_settings()
  } else{
    localTheme = 'white';
    theme_chart = "light";
    updateChartsNew('light');
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
    opacity_save_settings()
  }
}

function more_notification_close(a,b,c){
  var Block        = $(c),
      b            = $(b),
      e            = "more_notification('" + a + "',this,'" + c + "')"

  b.css('padding-top','5px')
  b.css('padding-bottom','5px')
  Block.css('max-height','350px')
  b.text('Развернуть')
  b.attr('onclick', e)
}

function more_notification(a,b,c){
  var Block        = $(c),
      b            = $(b),
      e            = "more_notification_close('" + a + "',this,'" + c + "')"

  b.css('padding-top','0px')
  b.css('padding-bottom','0px')
  Block.css('max-height','calc(100vh - 200px)')
  b.text('Свернуть')
  b.attr('onclick', e)
}

function open_block(a,b){
  if(b == '1'){
    $(a).css('display','block')
    setTimeout(function(){
      $(a).css('opacity','1')
      $(a).css('transform','scale(1.25) translate(calc(-50% + 20px),0px)')
    }, 1)
  } else if(b == 'center'){
    $(a).css('display','block')
    setTimeout(function(){
      $(a).css('opacity','1')
      $(a).css('transform','translate(calc(50% - 10px),0px)')
    }, 1)
  } else{
    cursourPos = $('#msg-input-1').offset()
    $(a).css('display','block')
    setTimeout(function(){
      $(a).css('opacity','1')
      $(a).css('transform','translate(calc(-50% + 20px),0px)')
    }, 1)
  }

}

function open_win(a, c){
  if(c === undefined){
    c = true;
  }
  var b1 = $(a + ' > .main-nav-profile-mail-block-main > .main-nav-profile-mail-block-main-elem').length
  if(a == '#profile-nav'){
    $(a).css('display','block')
    setTimeout(function(){
      $(a).css('opacity','1')
      if(document.documentElement.clientWidth <= 410){
        $(a).css('transform','translate(0px,0px)')
      } else{
        $(a).css('transform','translate(0px,0px)')
      }

    }, 1)
  } else{
    for(let i = 0; i < $('.main-nav-profile-mail-block').length; i++){
      if(('#' + $($('.main-nav-profile-mail-block')[i]).attr('id')) != a){
        $($('.main-nav-profile-mail-block')[i]).css('opacity','0')
        $($('.main-nav-profile-mail-block')[i]).css({
          'bottom':'10px',
          'display':'none',
          'transition':'all 0.2s ease-in-out 0s'
        });
        if(document.documentElement.clientWidth <= 835){
          $($('.main-nav-profile-mail-block')[i]).css('transform','translate(calc(0px),15px)')
        } else{
          $($('.main-nav-profile-mail-block')[i]).css('transform','translate(calc(-50% + 20px),15px)')
        }
      }
    }


    setTimeout(function(){
      if(document.documentElement.clientWidth <= 835){
        if(b1 > 6){
          $(a + ' > .main-nav-profile-mail-block-more').css('display','block')
        } else{
          $(a + ' > .main-nav-profile-mail-block-more').css('display','none')
        }
        if(c){
          $(a).css('display','block')
          setTimeout(function(){
            $(a).css('opacity','1')
            $(a).css('transform','translate(calc(0px),0px)')
          }, 1)
        }
      } else{
        if(b1 > 6){
          $(a + ' > .main-nav-profile-mail-block-more').css('display','block')
        } else{
          $(a + ' > .main-nav-profile-mail-block-more').css('display','none')
        }
        if(c){
          $(a).css('display','block')
          setTimeout(function(){
            $(a).css('opacity','1')
            $(a).css('transform','translate(calc(-50% + 20px),0px)')
          }, 1)
        }
      }
    }, 5)
    setTimeout(function(){
      if(adaptiveDesignS == 'phone'){
        $('.main-nav-profile-profile-block').css({
          'display':'block',
          'opacity': '0',
          'transition':'0.35s all cubic-bezier(0.6,-0.49, 0, 1.7)',
          'transform':'translate(80px, 0px)'
        })
      }
    }, 5)
  }

}

function tester(){
  if(GlobalTheme == 'black'){
    localTheme = 'black'
    updateChartsNew('dark');
    $('#ch1').prop('checked', true);
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
  } else{
    localTheme = 'white'
    updateChartsNew('light');
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
    $('#ch1').prop('checked', false);
  }

  if(GlobalStat){
    localStat = true
    $('#ch2').prop('checked', true);
  } else{
    localStat = false
    $('#ch2').prop('checked', false);
  }

  if(GlobalError){
    localError = true
    $('#ch3').prop('checked', true);
  } else{
    localError = false
    $('#ch3').prop('checked', false);
  }

  if(GlobalLang == 'en'){
    $('#LangPanel option[value="en"]').prop('selected', true);
    localLang = GlobalLang;
  } else if(GlobalLang == 'ru'){
    $('#LangPanel option[value="ru"]').prop('selected', true);
    localLang = GlobalLang;
  } else if(GlobalLang == 'ua'){
    $('#LangPanel option[value="ua"]').prop('selected', true);
    localLang = GlobalLang;
  }

  if(GlobalWinter){
    localWinter = true
    $('#ch4').prop('checked', true);
  } else{
    localWinter = false
    $('#ch4').prop('checked', false);
  }

  if(GlobalMsg){
    localMsg = true
    $('#ch5').prop('checked', true);
  } else{
    localMsg = false
    $('#ch5').prop('checked', false);
  }

  if(GlobalNoti){
    localNoti = true
    $('#ch6').prop('checked', true);
  } else{
    localNoti = false
    $('#ch6').prop('checked', false);
  }

  if(GlobalName != localName){
    localName = GlobalName;
    $("#GlobalName").val(GlobalName)
  } else{
    localName = GlobalName;
    $("#GlobalName").val(localName)
  }

  if(GlobalDescription != localDescription){
    localDescription = GlobalDescription;
    $("#GlobalDescription").val(GlobalDescription)
  } else{
    localDescription = GlobalDescription;
    $("#GlobalDescription").val(localDescription)
  }

  if($('#GlobalTags')) {
    document.getElementById('GlobalTags').innerHTML = SettingsField.tags.prev;
  }

  if(GlobalEmailMain != localEmailMain){
    localEmailMain = GlobalEmailMain;
    $("#GlobalEmailMain").val(GlobalEmailMain)
  } else{
    localEmailMain = GlobalEmailMain;
    $("#GlobalEmailMain").val(localEmailMain)
  }

  if(GlobalEmailForm != localEmailForm){
    localEmailForm = GlobalEmailForm;
    $("#GlobalEmailForm").val(GlobalEmailForm)
  } else{
    localEmailForm = GlobalEmailForm;
    $("#GlobalEmailForm").val(localEmailForm)
  }

  if(GlobalTel != localTel){
    localTel = GlobalTel;
    $("#GlobalTel").val(GlobalTel)
  } else{
    localTel = GlobalTel;
    $("#GlobalTel").val(localTel)
  }
}

function close_window(a){

  if($('#settings').css('display') == 'block'){
    $.cookie('theme', GlobalTheme, {expires: 99999});
  }

  $('.window').css('opacity','0')


  setTimeout(function(){
    $('.window').css('display','none')
    $(a).parent().parent().css('display','none')
    $(a).parent().parent().css('opacity','0')
    if($('#settings').css('display') == 'block'){
      tester()
    }
  }, 150)
}

function close_window_shadow(a){

  if($('#profile-edit').css('display') == 'block') {
    closeProfileWindow();
  }

  if($('#settings').css('display') == 'block'){
    $.cookie('theme', GlobalTheme, {expires: 99999});
  }


  $('.window').css('opacity','0')

  setTimeout(function(){
    $('.window').css('display','none')
    $(a).parent().css('display','none')
    $(a).parent().css('opacity','0')
    if($('#settings').css('display') == 'block'){
      tester()
    }
  }, 150)

  if($('#settings').css('display') == 'block'){
    opacity_save_settings()
  }

}




var swipeGlobal = true;

$(document).ready(function(){
  swipeCloseWindow({
    selector: $('.window > div > .window-block'),
  });
  swipeCloseWindow({
    selector: $('.main-nav-profile-mail-block'),
  });
  swipeCloseWindow({
    selector: $('.file_manager-contextmenu'),
  });
  swipeOpenFastElem({
    debug: false,
    bottom: 0,
    right: 0,
    height: $('.main-nav-profile-profile-block').outerHeight() + 15,
    width: 65,
    selector: $('.main'),
  })
})

function swipeOpenFastElem(param){
  if(param.debug == undefined){
    param.debug = false;
  }



  if(param.debug && document.documentElement.clientWidth <= 410){
    param.id = "I" + stringGenerator(19,5);
    var output = '';
        output += "<div id='" + param.id + "' style='";
        output += "position: fixed;";
        output += "margin: auto;";
        if(param.left != undefined){
          output += "left: " + param.left + "px;";
        }
        if(param.right != undefined){
          output += "right: " + param.right + "px;";
        }
        if(param.top != undefined){
          output += "top: " + param.top + "px;";
        }
        if(param.bottom != undefined){
          output += "bottom: " + param.bottom + "px;";
        }
        if(param.height != undefined){
          output += "height: " + param.height + "px;";
        }
        if(param.width != undefined){
          output += "width: " + param.width + "px;";
        }

        output += "background-color: #5d78ff59;";
        output += "z-index: 99;";
        output += "border: 1px dashed #00000059;";
        output += "'>";
        output += "</div>";

    $('body').prepend(output);
    param.selector = $('#' + param.id);
  }

  if(param.right == undefined){
    param.right = 0;
  }
  if(param.left == undefined){
    param.left = 0;
  }
  if(param.top == undefined){
    param.top = 0;
  }
  if(param.bottom == undefined){
    param.bottom = 0;
  }
  $(param.selector).on('touchstart', function(e){
    if(document.documentElement.clientWidth <= 410){
      param.start = {
        x: 0,
        y: 0,
      };
      param.move = {
        x: 0,
        y: 0,
      };
      param.end = {
        x: 0,
        y: 0,
      };
      param.distance = {
        x: 0,
        y: 0,
        xy: 0,
      };
      param.deg = 0;
      param.swipe = false;
      param.speed = 0;
      param.time = 0;
      param.operation = 0;
      param.status = false;
      param.leftToRight = false;

      param.start.x = e.changedTouches[0].pageX;
      param.start.y = e.changedTouches[0].pageY;

      param.time = 0;
      param.interval = setInterval(function () {
        param.time += 1;
      }, 1);

      param.x1 = document.documentElement.clientWidth - param.width - param.right - param.left;
      param.y1 = document.documentElement.clientHeight - param.height - param.bottom - param.top;
      param.x2 = document.documentElement.clientWidth - param.right - param.left;
      param.y2 = document.documentElement.clientHeight - param.height - param.bottom - param.top;
      param.x3 = document.documentElement.clientWidth - param.width - param.right - param.left + param.height;
      param.y3 = document.documentElement.clientHeight - param.bottom - param.top;
      param.x4 = document.documentElement.clientWidth - param.right - param.left + param.height;
      param.y4 = document.documentElement.clientHeight - param.bottom - param.top;

      param.touchX = ((param.start.x >= param.x1) && (param.start.x <= param.x2) || (param.start.x >= param.x3) && (param.start.x <= param.x4));
      param.touchY = ((param.start.y >= param.y1) && (param.start.y <= param.y3) || (param.start.y >= param.y2) && (param.start.y <= param.y4));

      if($('.main-nav-profile-profile-block').css('transform') == 'matrix(1, 0, 0, 1, 0, 0)'){
        param.leftToRight = true;
      }

      if(param.touchX && param.touchY){
        param.swipe = true;
      }
    }
  });
  $(param.selector).on('touchmove', function(e){
    if(document.documentElement.clientWidth <= 410){
      if(param.swipe){

        param.move.x = e.changedTouches[0].pageX;
        param.move.y = e.changedTouches[0].pageY;

        // определяем скорость и расстояние пройденное пальцем
        param.distance.x = param.start.x - param.move.x;
        param.distance.y = param.start.y - param.move.y;
        param.distance.xy = Math.sqrt(Math.pow(param.distance.x, 2) + Math.pow(param.distance.y, 2));
        param.speed = param.distance.xy / param.time;

        if(param.operation < 1){
          param.deg = Math.acos((param.start.x - param.move.x)/param.distance.xy) * 180 / Math.PI;
          param.operation++;
        }
        if(param.operation >= 1){
          if((param.deg >= 0 && param.deg < 45) || (param.deg > 135 && param.deg <= 180)){
            param.scroll = 100 / 80 * Math.abs(param.distance.x);
            if(param.scroll >= 100){
              param.scroll = 100;
            }
            if(param.scroll <= 0){
              param.scroll = 0;
            }
            if(param.move.x > param.start.x){
              if(param.leftToRight){
                if(param.speed > 0 && param.speed < 6){

                  param.transform = 0 + ((80 - 0) * (param.scroll / 100));
                  param.opacity = 1 + ((0 - 1) * (param.scroll / 100));


                  $('.main-nav-profile-profile-block').css({
                    'display':'block',
                    'opacity':param.opacity,
                    'transition':'0s all cubic-bezier(0.6,-0.49, 0, 1.7)',
                    'transform':'translate(' + param.transform + 'px, 0px)'
                  })
                }
                //console.log('слева на направо');
              }
            }
            if(param.move.x < param.start.x){
              if(param.speed > 0 && param.speed < 6){

                param.transform = 80 + ((0 - 80) * (param.scroll / 100));
                param.opacity = 0 + ((1 - 0) * (param.scroll / 100));


                if($('.main-nav-profile-profile-block').css('transform') != 'matrix(1, 0, 0, 1, 0, 0)'){
                  $('.main-nav-profile-profile-block').css({
                    'display':'block',
                    'opacity':param.opacity,
                    'transition':'0s all cubic-bezier(0.6,-0.49, 0, 1.7)',
                    'transform':'translate(' + param.transform + 'px, 0px)'
                  })
                }
              }
              //console.log('справа на лево');
            }
          }

        }



      }
    }
  });
  $(param.selector).on('touchend', function(e){
    if(document.documentElement.clientWidth <= 410){
      param.swipe = false;
      param.status = false;
      param.end.x = e.changedTouches[0].pageX;
      param.end.y = e.changedTouches[0].pageY;

      clearInterval(param.interval);

      if(param.operation >= 1){
        if((param.deg >= 0 && param.deg < 45) || (param.deg > 135 && param.deg <= 180)){
          if(param.move.x > param.start.x){
            //console.log('слева на направо: конец');
            if(param.speed > 0 && param.speed < 6 && param.distance.xy > 5){
              if(Math.abs(param.distance.x) < 80 && Math.abs(param.distance.x) >= 20){
                $('.main-nav-profile-profile-block').css({
                  'display':'block',
                  'opacity': '0',
                  'transition':'0.35s all cubic-bezier(0.6,-0.49, 0, 1.7)',
                  'transform':'translate(80px, 0px)'
                })
              } else if(Math.abs(param.distance.x) < 20 && Math.abs(param.distance.x) > 0){
                $('.main-nav-profile-profile-block').css({
                  'display':'block',
                  'opacity': '1',
                  'transition':'0.35s all cubic-bezier(0.6,-0.49, 0, 1.7)',
                  'transform':'translate(0px, 0px)'
                })
              }
            } else{
              $('.main-nav-profile-profile-block').css({
                'display':'block',
                'opacity': '0',
                'transition':'0.35s all cubic-bezier(0.6,-0.49, 0, 1.7)',
                'transform':'translate(80px, 0px)'
              })
            }


          }
          if(param.move.x < param.start.x){
            //console.log('справа на лево: конец');
            if(param.speed > 0 && param.speed < 6){
              if(Math.abs(param.distance.x) < 80 && Math.abs(param.distance.x) >= 20){
                $('.main-nav-profile-profile-block').css({
                  'display':'block',
                  'opacity': '1',
                  'transition':'0.35s all cubic-bezier(0.6,-0.49, 0, 1.7)',
                  'transform':'translate(0px, 0px)'
                })
              } else if(Math.abs(param.distance.x) < 20 && Math.abs(param.distance.x) >= 0){
                $('.main-nav-profile-profile-block').css({
                  'display':'block',
                  'opacity': '0',
                  'transition':'0.35s all cubic-bezier(0.6,-0.49, 0, 1.7)',
                  'transform':'translate(80px, 0px)'
                })
              } else{
                $('.main-nav-profile-profile-block').css({
                  'display':'block',
                  'opacity': '1',
                  'transition':'0.35s all cubic-bezier(0.6,-0.49, 0, 1.7)',
                  'transform':'translate(0px, 0px)'
                })
              }
            } else{
              $('.main-nav-profile-profile-block').css({
                'display':'block',
                'opacity': '1',
                'transition':'0.35s all cubic-bezier(0.6,-0.49, 0, 1.7)',
                'transform':'translate(0px, 0px)'
              })
            }

          }
        }
      }

      param.time = 0;
    }
  });
}

function swipeCloseWindow(parameters){

  $(parameters.selector).on('touchstart', parameters.selector, function(e){
    // обнуляем все переменные
    parameters = {
      speed: 0,
      time: 0,
      animate: false,
      animateBottom: false,
      animateTop: false,
      distance:{
        x: 0,
        y: 0,
        xy: 0,
      },
      start: {
        x: e.changedTouches[0].pageX,
        y: e.changedTouches[0].pageY,
      },
      move: {
        x: 0,
        y: 0,
      },
      end: {
        x: 0,
        y: 0,
      },
      opacity: 1,
      scroll: 0,
      transform: 1,
    }

    parameters.interval = setInterval(function () {
      parameters.time += 1;
    }, 1);


    if($(this).attr('class') == 'window-block'){
      if($(this).scrollTop() == 0 || Math.round($(this).prop('scrollHeight') - $(this).height()) <= Math.round($(this).scrollTop() ) ){
        parameters.animate = true;
      }
      if($(this).height() < Math.round($(this).prop('scrollHeight'))){
        if($(this).scrollTop() == 0){
          parameters.animateTop = true;
        }
        if(Math.round($(this).prop('scrollHeight') - $(this).height()) <= Math.round($(this).scrollTop())){
          parameters.animateBottom = true;
        }

        if(($(this).scrollTop() == 0) && (Math.round($(this).prop('scrollHeight') - $(this).height()) <= Math.round($(this).scrollTop() ) )){
          parameters.animateTop = false;
          parameters.animateBottom = false;
          parameters.animate = true;
        }
      }

    }
    if($(this).attr('class') == 'main-nav-profile-mail-block'){
      if($(this).find('.main-nav-profile-mail-block-main').scrollTop() == 0 || Math.round($($(this).find('.main-nav-profile-mail-block-main')).prop('scrollHeight') - $($(this).find('.main-nav-profile-mail-block-main')).height()) <= Math.round($($(this).find('.main-nav-profile-mail-block-main')).scrollTop() )){
        parameters.animate = true;
      }
      if($($(this).find('.main-nav-profile-mail-block-main')).height() < Math.round($($(this).find('.main-nav-profile-mail-block-main')).prop('scrollHeight'))){
        if($($(this).find('.main-nav-profile-mail-block-main')).scrollTop() == 0){
          parameters.animateTop = true;
        }
        if(Math.round($($(this).find('.main-nav-profile-mail-block-main')).prop('scrollHeight') - $($(this).find('.main-nav-profile-mail-block-main')).height()) <= Math.round($($(this).find('.main-nav-profile-mail-block-main')).scrollTop())){
          parameters.animateBottom = true;
        }
        if(($(this).find('.main-nav-profile-mail-block-main').scrollTop() == 0) && (Math.round($(this).find('.main-nav-profile-mail-block-main').prop('scrollHeight') - $(this).find('.main-nav-profile-mail-block-main').innerHeight()) <= Math.round($(this).find('.main-nav-profile-mail-block-main').scrollTop() ) )){
          parameters.animateTop = false;
          parameters.animateBottom = false;
          parameters.animate = true;
        }
      }
    }
    if($(this).attr('class') == 'file_manager-contextmenu'){
      if(adaptiveDesignS != 'phone'){
        return;
      }
      if($(this).scrollTop() == 0 || Math.round($(this).prop('scrollHeight') - $(this).height()) <= Math.round($(this).scrollTop() ) ){
        parameters.animate = true;
      }
      if($(this).height() < Math.round($(this).prop('scrollHeight'))){
        if($(this).scrollTop() == 0){
          parameters.animateTop = true;
        }
        if(Math.round($(this).prop('scrollHeight') - $(this).height()) <= Math.round($(this).scrollTop())){
          parameters.animateBottom = true;
        }
        if(($(this).scrollTop() == 0) && (Math.round($(this).prop('scrollHeight') - $(this).innerHeight()) <= Math.round($(this).scrollTop() ) )){
          parameters.animateTop = false;
          parameters.animateBottom = false;
          parameters.animate = true;
        }
      }
    }
  });
  $(parameters.selector).on('touchmove', parameters.selector, function(e){


    if(!parameters.animate){
      return;
    }

    parameters.move.x = e.changedTouches[0].pageX;
    parameters.move.y = e.changedTouches[0].pageY;


    // определяем скорость и расстояние пройденное пальцем
    parameters.distance.x = parameters.start.x - parameters.move.x;
    parameters.distance.y = parameters.start.y - parameters.move.y;
    parameters.distance.xy = Math.sqrt(Math.pow(parameters.distance.x, 2) + Math.pow(parameters.distance.y, 2));
    parameters.speed = parameters.distance.xy / parameters.time;

    parameters.scroll = 100 / 350 * Math.abs(parameters.distance.y);
    parameters.opacity = 1 + ((0 - 1) * (parameters.scroll / 100));
    parameters.transform = 1 + ((0.8 - 1) * (parameters.scroll / 100));

    if(parameters.move.y > parameters.start.y){
      if(!parameters.animateBottom){
        if($(this).scrollTop() == 0){
          if(parameters.speed >= 1){
            if(adaptiveDesignS == 'phone'){
              $(this).css({
                'bottom': ((parameters.distance.y) + 10) + 'px',
                'transition': '0s all',
                'opacity': parameters.opacity,
                'transform': 'scale(' + parameters.transform + ')'
              })
            } else{
              if($(parameters.selector).attr('class') != 'file_manager-contextmenu'){
                $(this).css({
                  'margin-top': 'calc(' + (-parameters.distance.y) + 'px + 50vh)',
                  'transition': '0s all',
                  'opacity': parameters.opacity,
                  'transform': 'translate(-50%, -50%) scale(' + parameters.transform + ')'
                })
              }
            }
          }
        }
        if(Math.round($(this).prop('scrollHeight') - $(this).height()) <= Math.round($(this).scrollTop())){
          if(parameters.speed >= 1){
            if(adaptiveDesignS == 'phone'){
              $(this).css({
                'bottom': ((parameters.distance.y) + 10) + 'px',
                'transition': '0s all',
                'opacity': parameters.opacity,
                'transform': 'scale(' + parameters.transform + ')'
              })
            } else{
              if($(parameters.selector).attr('class') != 'file_manager-contextmenu'){
                $(this).css({
                  'margin-top': 'calc(' + (-parameters.distance.y) + 'px + 50vh)',
                  'transition': '0s all',
                  'opacity': parameters.opacity,
                  'transform': 'translate(-50%, -50%) scale(' + parameters.transform + ')'
                })
              }
            }
          }
        }

      }
    } else if(parameters.move.y < parameters.start.y){
      if(!parameters.animateTop){
        if($(this).scrollTop() == 0){
          if(parameters.speed >= 1){
            if(adaptiveDesignS == 'phone'){
              $(this).css({
                'bottom': ((parameters.distance.y) + 10) + 'px',
                'transition': '0s all',
                'opacity': parameters.opacity,
                'transform': 'scale(' + parameters.transform + ')'
              })
            } else{
              if($(parameters.selector).attr('class') != 'file_manager-contextmenu'){
                $(this).css({
                  'margin-top': 'calc(' + (-parameters.distance.y) + 'px + 50vh)',
                  'transition': '0s all',
                  'opacity': parameters.opacity,
                  'transform': 'translate(-50%, -50%) scale(' + parameters.transform + ')'
                })
              }
            }
          }
        }
        if(Math.round($(this).prop('scrollHeight') - $(this).height()) <= Math.round($(this).scrollTop())){
          if(parameters.speed >= 1){
            if(adaptiveDesignS == 'phone'){
              $(this).css({
                'bottom': ((parameters.distance.y) + 10) + 'px',
                'transition': '0s all',
                'opacity': parameters.opacity,
                'transform': 'scale(' + parameters.transform + ')'
              })
            } else{
              if($(parameters.selector).attr('class') != 'file_manager-contextmenu'){
                $(this).css({
                  'margin-top': 'calc(' + (-parameters.distance.y) + 'px + 50vh)',
                  'transition': '0s all',
                  'opacity': parameters.opacity,
                  'transform': 'translate(-50%, -50%) scale(' + parameters.transform + ')'
                })
              }
            }
          }
        }
      }
    }

  });
  $(parameters.selector).on('touchend', parameters.selector, function(e){
    clearInterval(parameters.interval);

    // определяем координаты конца
    parameters.end.x = e.changedTouches[0].pageX;
    parameters.end.y = e.changedTouches[0].pageY;

    if(!parameters.animate){
      return;
    }

    if((parameters.end.x == parameters.start.x)&&(parameters.end.y == parameters.start.y)){
      return;
    }

    // определяем скорость и расстояние пройденное пальцем
    parameters.distance.x = parameters.start.x - parameters.end.x;
    parameters.distance.y = parameters.start.y - parameters.end.y;
    parameters.distance.xy = Math.sqrt(Math.pow(parameters.distance.x, 2) + Math.pow(parameters.distance.y, 2));
    parameters.speed = parameters.distance.xy / parameters.time;


    if(Math.abs(parameters.distance.y) >= ($(this).height() / 4)){
      if(parameters.end.y > parameters.start.y){
        if($(this).scrollTop() == 0){
          closeAnimateWindow({
            direction: 'top',
            selector: $(this),
            speed: 10
          })
          parameters.time = 0;
          return;
        }
        if(Math.round($(this).prop('scrollHeight') - $(this).height()) <= Math.round($(this).scrollTop())){
          closeAnimateWindow({
            direction: 'top',
            selector: $(this),
            speed: 10
          })
          parameters.time = 0;
          return;
        }

      }
      else if(parameters.end.y < parameters.start.y){
        if($(this).scrollTop() == 0){
          closeAnimateWindow({
            direction: 'bottom',
            selector: $(this),
            speed: 10
          })
          parameters.time = 0;
          return;
        }
        if(Math.round($(this).prop('scrollHeight') - $(this).height()) <= Math.round($(this).scrollTop())){
          closeAnimateWindow({
            direction: 'bottom',
            selector: $(this),
            speed: 10
          })
          parameters.time = 0;
          return;
        }

      } else{
        closeAnimateWindow({
          start: true,
          selector: $(this),
        })
      }

    } else{
      closeAnimateWindow({
        start: true,
        selector: $(this),
      })
    }
    // console.table(parameters.speed + "px/мс")


    if(parameters.end.y > parameters.start.y){
      if($(this).scrollTop() == 0){
        // alert(parameters.speed)
        closeAnimateWindow({
          direction: 'top',
          selector: $(this),
          speed: parameters.speed
        })
      }
      if(Math.round($(this).prop('scrollHeight') - $(this).height()) <= Math.round($(this).scrollTop())){
        // alert(parameters.speed)
        closeAnimateWindow({
          direction: 'top',
          selector: $(this),
          speed: parameters.speed
        })
      }
    } else if(parameters.end.y < parameters.start.y){
      if($(this).scrollTop() == 0){
        // alert(parameters.speed)
        closeAnimateWindow({
          direction: 'bottom',
          selector: $(this),
          speed: parameters.speed
        })
      }
      if(Math.round($(this).prop('scrollHeight') - $(this).height()) <= Math.round($(this).scrollTop())){
        // alert(parameters.speed)
        closeAnimateWindow({
          direction: 'bottom',
          selector: $(this),
          speed: parameters.speed
        })
      }
    }





    parameters.time = 0;
  });

  function closeAnimateWindow(params){
    if(params.start == undefined){
      params.start = false;
    }
    if(!params.start){
      if(params.direction == 'bottom'){

        if(params.speed >= 1 && params.speed <= 6){
          if(adaptiveDesignS == 'phone'){
            $(params.selector).css({
              'bottom': (params.y + 10) + 'px',
              'transition': '0s all'
            })
          } else{

          }

        } else if(params.speed > 6){
          if(adaptiveDesignS == 'phone'){
            if($(params.selector).attr('class') == 'window-block'){
              $(params.selector).css({
                'bottom': '40vh',
                'transition': '0.2s all ease-in-out',
                'transform':'scale(0.45)',
                'opacity':'0'
              });
              setTimeout(function(){

                close_window_shadow('.window-shadow');

                setTimeout(function(){
                  $(params.selector).css({
                    'bottom': '10px',
                    'transition': '0.15s all ease-in-out',
                    'transform':'scale(1)',
                    'opacity':'1'
                  });
                }, 100)
              }, 200)
            }
            if($(params.selector).attr('class') == 'main-nav-profile-mail-block'){
              $(params.selector).css('opacity','0')
              $(params.selector).css({
                'transition':'all 0.2s ease-in-out 0s'
              })
              if(document.documentElement.clientWidth <= 835){
                $(params.selector).css('transform','translate(calc(0px),150px) scale(0.65)')
              } else{
                $(params.selector).css('transform','translate(calc(-50% + 20px),150px) scale(0.65)')
              }
              setTimeout(function(){
                $(params.selector).css('display','none');
                $(params.selector).css({
                  'bottom':'10px',
                  'transition':'all 0.2s ease-in-out 0s'
                });
                if(document.documentElement.clientWidth <= 835){
                  $(params.selector).css('transform','translate(calc(0px),15px)')
                } else{
                  $(params.selector).css('transform','translate(calc(-50% + 20px),15px)')
                }
              },200)
            }
            if($(params.selector).attr('class') == 'file_manager-contextmenu'){
              if(adaptiveDesignS == 'phone'){
                $(params.selector).css({
                  'bottom': '40vh',
                  'transition': '0.2s all ease-in-out',
                  'transform':'scale(0.45)',
                  'opacity':'0'
                });
                setTimeout(function(){
                  setTimeout(function(){
                    $(params.selector).css({
                      'bottom': '10px',
                      'transition': '0.15s all ease-in-out',
                      'transform':'scale(1)',
                      'opacity':'1',
                      'display':'none'
                    });
                  }, 100)
                }, 200)
              }

            }
          } else{
            if($(params.selector).attr('class') != 'file_manager-contextmenu'){
              $(params.selector).css({
                'margin-top': '-90vh',
                'transition': '0.2s all ease-in-out',
                'transform':'scale(0.45) translate(-50%, -50%)',
                'opacity':'0'
              });
              setTimeout(function(){
                if($(params.selector).attr('class') == 'window-block'){
                  close_window_shadow('.window-shadow');
                }
                setTimeout(function(){
                  $(params.selector).css({
                    'margin-top': '50vh',
                    'transition': '0.15s all ease-in-out',
                    'transform':'scale(1) translate(-50%, -50%)',
                    'opacity':'1'
                  });
                }, 100)
              }, 200)
            }
          }

        }

      }
      if(params.direction == 'top'){
        if(params.speed >= 1 && params.speed <= 6){
          if(adaptiveDesignS == 'phone'){

          } else{

          }
        } else if(params.speed > 6){
          if(adaptiveDesignS == 'phone'){
            if($(params.selector).attr('class') == 'window-block'){
              $(params.selector).css({
                'bottom': '-40vh',
                'transition': '0.2s all ease-in-out',
                'transform':'scale(0.45)',
                'opacity':'0'
              });
              setTimeout(function(){
                close_window_shadow('.window-shadow');
                setTimeout(function(){
                  $(params.selector).css({
                    'bottom': '10px',
                    'transition': '0.15s all ease-in-out',
                    'transform':'scale(1)',
                    'opacity':'1'
                  });
                }, 100)
              }, 200)
            }
            if($(params.selector).attr('class') == 'main-nav-profile-mail-block'){
              $(params.selector).css('opacity','0')
              $(params.selector).css({
                'transition':'all 0.2s ease-in-out 0s'
              })
              if(document.documentElement.clientWidth <= 835){
                $(params.selector).css('transform','translate(calc(0px),150px) scale(0.65)')
              } else{
                $(params.selector).css('transform','translate(calc(-50% + 20px),150px) scale(0.65)')
              }
              setTimeout(function(){
                $(params.selector).css('display','none')
                $(params.selector).css({
                  'bottom':'10px',
                  'transition':'all 0.2s ease-in-out 0s'
                })
                if(document.documentElement.clientWidth <= 835){
                  $(params.selector).css('transform','translate(calc(0px),15px)')
                } else{
                  $(params.selector).css('transform','translate(calc(-50% + 20px),15px)')
                }
              },200)
            }
            if($(params.selector).attr('class') == 'file_manager-contextmenu' && adaptiveDesignS == 'phone'){
              $(params.selector).css({
                'bottom': '-40vh',
                'transition': '0.2s all ease-in-out',
                'transform':'scale(0.45)',
                'opacity':'0'
              });
              setTimeout(function(){
                setTimeout(function(){
                  $(params.selector).css({
                    'bottom': '10px',
                    'transition': '0.15s all ease-in-out',
                    'transform':'scale(1)',
                    'opacity':'1',
                    'display':'none'
                  });
                }, 100)
              }, 200)
            }
          } else{
            if($(params.selector).attr('class') != 'file_manager-contextmenu'){
              $(params.selector).css({
                'margin-top': '90vh',
                'transition': '0.2s all ease-in-out',
                'transform':'scale(0.45) translate(-50%, -50%)',
                'opacity':'0'
              });
              setTimeout(function(){
                if($(params.selector).attr('class') == 'window-block'){
                  close_window_shadow('.window-shadow');
                }
                setTimeout(function(){
                  $(params.selector).css({
                    'margin-top': '50vh',
                    'transition': '0.15s all ease-in-out',
                    'transform':'scale(1) translate(-50%, -50%)',
                    'opacity':'1'
                  });
                }, 100)
              }, 200)
            }
          }

        }
      }
    } else{
      if(adaptiveDesignS == 'phone'){
        $(params.selector).css({
          'bottom': '10px',
          'transition': '0.35s all cubic-bezier(0.34, 0.21, 0.41, 1.43)',
          'transform':'scale(1)',
          'opacity':'1'
        });
        setTimeout(function(){
          $(params.selector).css({
            'bottom': '10px',
            'transition': '0.15s all ease-in-out',
          });
        }, 350)
      } else{
        if($(params.selector).attr('class') != 'file_manager-contextmenu'){
          $(params.selector).css({
            'margin-top': '50vh',
            'transition': '0.35s all cubic-bezier(0.34, 0.21, 0.41, 1.43)',
            'transform':'scale(1) translate(-50%, -50%)',
            'opacity':'1'
          });
          setTimeout(function(){
            $(params.selector).css({
              'transition': '0.15s all ease-in-out',
              'transform': 'translate(-50%, -50%)',
            });
          }, 350)
        }
      }
    }

  }
}

function swipeMove(resize, block, max, action, func, development_state){

  var SwipeMove = {
    StartSwipeX: '',
    StartSwipeY: '',
    EndSwipeX: '',
    EndSwipeY: '',
    ResultSwipeX: '',
    ResultSwipeY: '',
    NowResultSwipeX: '',
    NowResultSwipeY: '',
    NowResultSwipeXY: '',
    NowResultSwipeDeg: '',
    result: false,
    operation: 0,
    operation2: 0,
    direction: '',
    swipeStart: false
  }

  if(max == undefined || max == 0){
    max = document.documentElement.clientWidth;
  }

  var tmpWidthMoveC;
  $(block).on('touchstart', function(e){
    if(adaptiveDesignS != 'phone'){
      return;
    }
    swipeGlobal = false;
    SwipeMove.StartSwipeX = e.changedTouches[0].pageX;
    SwipeMove.StartSwipeY = e.changedTouches[0].pageY;
  })
  $(block).on('touchmove', function(e){

    SwipeMove.NowResultSwipeX = SwipeMove.StartSwipeX - e.changedTouches[0].pageX;
    SwipeMove.NowResultSwipeY = SwipeMove.StartSwipeY - e.changedTouches[0].pageY;
    SwipeMove.NowResultSwipeXY = Math.sqrt(Math.abs(SwipeMove.NowResultSwipeX) * Math.abs(SwipeMove.NowResultSwipeX) + (Math.abs(SwipeMove.NowResultSwipeY) * Math.abs(SwipeMove.NowResultSwipeY)))
    SwipeMove.NowResultSwipeDeg = Math.acos(SwipeMove.NowResultSwipeX/SwipeMove.NowResultSwipeXY) * 180 / Math.PI;

    if(SwipeMove.operation <= 4){
      if(!((SwipeMove.NowResultSwipeDeg >= 0 && SwipeMove.NowResultSwipeDeg < 45) || (SwipeMove.NowResultSwipeDeg > 135 && SwipeMove.NowResultSwipeDeg <= 180))){
        SwipeMove.result = false;
      } else{
        SwipeMove.result = true;
      }
      SwipeMove.operation++;
      return;
    }



    if(!SwipeMove.result){
      return;
    }

    if(SwipeMove.operation2 <= 4){
      SwipeMove.direction = '';
      if(SwipeMove.NowResultSwipeX > 0){
        SwipeMove.swipeStart = true;
        SwipeMove.direction = 'left';
      } else{
        SwipeMove.swipeStart = true;
        SwipeMove.direction = 'right';
      }
      SwipeMove.operation2++;
      return;
    }

    if(SwipeMove.operation2 == 4){
      if(SwipeMove.direction != 'right' || SwipeMove.direction != 'left'){
        SwipeMove.swipeStart = false;
      }
    }



    // Определили направление стороны
    if(SwipeMove.direction == 'left' && SwipeMove.direction == action && SwipeMove.swipeStart){
      if(SwipeMove.StartSwipeX < max){
        if(func == 'nav_open'){
          if(NavStat){

            if(NavStat){
              $('.main-shadow').css({'transition':'0s all ease-in-out'})
              $('nav').css({'transition':'0s all ease-in-out'})
            }
            var tmpWidthNav = $('nav').width(); // ширина меню
                tmpWidthMoveC = (100 / tmpWidthNav * SwipeMove.NowResultSwipeX) - 7; // процент на который открыто меню
            var tmpWidthMove = 0 + ((tmpWidthNav - 0) * (tmpWidthMoveC / 100));
            var tmpOpacityMove = Math.abs(1 + ((0 - 1) * (tmpWidthMoveC / 100)));

            if(tmpWidthMoveC > 100){
              tmpWidthMoveC = 100;
              return;
            }
            if(tmpWidthMoveC < 0){
              tmpWidthMoveC = 0;
              return;
            }
            $('.main-shadow').css('opacity',tmpOpacityMove)
            $('nav').css('left',tmpWidthNav - tmpWidthMove - tmpWidthNav + 'px')
          } else{
            return;
          }
        }
      }
    }

    // Определили направление стороны
    if(SwipeMove.direction == 'right' && SwipeMove.direction == action && SwipeMove.swipeStart){

      if(SwipeMove.StartSwipeX < max){

        if(func == 'nav_open'){

          // проверка открыто ли меню
          if(!NavStat){
            if(!NavStat){
              nav_open(true);
            }
            var tmpWidthNav = $('nav').width(); // ширина меню
                tmpWidthMoveC = (100 / tmpWidthNav * SwipeMove.NowResultSwipeX) + 7; // процент на который открыто меню
            var tmpWidthMove = tmpWidthNav + ((0 - tmpWidthNav) * (tmpWidthMoveC / 100));
            var tmpOpacityMove = Math.abs(0 + ((1 - 0) * (tmpWidthMoveC / 100)));

            if(tmpWidthMoveC < -100 || tmpWidthMoveC > 0){
              return;
            }
            $('.main-shadow').css('opacity',tmpOpacityMove)
            $('nav').css('left',(tmpWidthMove - tmpWidthNav * 2) + 'px')
          }
        }
      }
    }

  })
  $(block).on('touchend', function(e){

    swipeGlobal = true;
    if(SwipeMove.direction == action && SwipeMove.direction == 'left' && SwipeMove.swipeStart && SwipeMove.operation == 5 && SwipeMove.operation2 == 5){
      if(SwipeMove.StartSwipeX < max){
        if(func == 'nav_open'){
          if(NavStat){
            if(false){
              NavStat = false;
              $('nav').css({'transition':'0.35s all ease-in-out','left':'-100%'})
              $('.main-shadow').css({'transition':'0.25s all ease-in-out','opacity':'0'})
              setTimeout(function(){
                $('.main-shadow').css('display','none')
              }, 250)
              return;
            } else{
              if(Math.abs(tmpWidthMoveC) > 20){
                NavStat = false;
                $('nav').css({'transition':'0.35s all ease-in-out','left':'-100%'})
                $('.main-shadow').css({'transition':'0.25s all ease-in-out','opacity':'0'})
                setTimeout(function(){
                  $('.main-shadow').css('display','none')
                }, 250)
              } else{
                NavStat = true;
                $('.main-shadow').css({'transition':'0.25s all ease-in-out','opacity':'1'})
                $('nav').css({'transition':'0.35s all ease-in-out','left':'0px'});
              }
            }
          } else{
            return;
          }

        }
      }
    }

    if(SwipeMove.direction == action && SwipeMove.direction == 'right' && SwipeMove.swipeStart && SwipeMove.operation == 5 && SwipeMove.operation2 == 5){
      if(SwipeMove.StartSwipeX < max){
        if(func == 'nav_open'){
          if(Math.abs(tmpWidthMoveC) <= 30){
            NavStat = false;
            $('nav').css({'transition':'0.35s all ease-in-out','left':'-100%'})
            $('.main-shadow').css({'transition':'0.25s all ease-in-out','opacity':'0'})
            setTimeout(function(){
              $('.main-shadow').css('display','none')
            }, 250)
          } else{
            NavStat = true;
            $('.main-shadow').css({'transition':'0.25s all ease-in-out','opacity':'1'})
            $('nav').css({'transition':'0.35s all ease-in-out','left':'0px'});
          }
        }
      }

    }

    SwipeMove.operation = 0;
    SwipeMove.operation2 = 0;
    SwipeMove.result = false;

  })
}

function swipe(resize, block, max, action, func, development_state){

  var TmpSwipe = {
    StartSwipeX:'',
    StartSwipeY:'',
    EndSwipeX:'',
    EndSwipeY:'',
    result: 0,
    result2: 0
  };

  if(resize === undefined || resize < 0){
    resize = 100;
  }
  if(max === undefined || max == 0){
    max = document.documentElement.clientWidth;
  }
  if(development_state === undefined){
    development_state = window.development_state;
  }



  $(block).on('touchstart', function(e){
    TmpSwipe.StartSwipeX = e.changedTouches[0].pageX;
    TmpSwipe.StartSwipeY = e.changedTouches[0].pageY;
  });
  $(block).on('touchend', function(e){
    // setTimeout(function(){
    //   if(window.swipeGlobal) return;
    // }, 10)
    TmpSwipe.EndSwipeX = e.changedTouches[0].pageX;
    TmpSwipe.EndSwipeY = e.changedTouches[0].pageY;

    TmpSwipe.result = TmpSwipe.StartSwipeX - TmpSwipe.EndSwipeX;
    TmpSwipe.result2 = TmpSwipe.StartSwipeY - TmpSwipe.EndSwipeY;

    if((TmpSwipe.result >= 0 || TmpSwipe.result <= 0) && (TmpSwipe.result2 >= 80 || TmpSwipe.result2 <= -80)){
      // if(development_state){
      //   console.log('Swipe: top or bottom');
      // }
      return;
    }
    if(TmpSwipe.result > resize && TmpSwipe.result2 < 100 && TmpSwipe.result2 > -100 && TmpSwipe.StartSwipeX < max){
      TmpSwipe.result = 'left';
    }
    if(TmpSwipe.result < -resize && TmpSwipe.result2 < 100 && TmpSwipe.result2 > -100 && TmpSwipe.StartSwipeX < max){
      TmpSwipe.result = 'right';
    }


    if(action == TmpSwipe.result){
      eval(func);
      if(development_state){
        console.log('Swipe: ' + TmpSwipe.result);
      }
    }


  });





}

function open_window(a, b){

  var secLocal = 50;

  if($('.window').css('display') == 'none'){
    $('.window').css('display','block')
    $('.window').find('.window-shadow').css('display','block')
    setTimeout(function(){
      $('.window').find('.window-shadow').css('opacity','1')
    }, 1)
  }

  for(let i = 0; i < $('.window > div').length; i++){
    if($($('.window > div')[i]).attr('class') != 'window-shadow'){
      if($($('.window > div')[i]).css('display') == 'block'){
        $($('.window > div')[i]).css({
          'opacity':'0',
        })
        $($('.window > div')[i]).css('display','none')
        secLocal += 150;
      }
    }
  }


  setTimeout(function(){
    $(a).css('display','block')
    if(adaptiveDesignS == 'phone'){
      $(a).find('.window-block').css({
        'transform': 'translate(0px, 200%)',
      })
    }
    setTimeout(function(){
      $('.window').css('opacity','1')
      $(a).css('opacity','1')
      if(adaptiveDesignS == 'phone'){
        $(a).find('.window-block').css({
          'transition':'0.55s all cubic-bezier(0.34, 0.21, 0.29, 1.21)',
          'transform': 'translate(0px, 0px)',
        });
      }
      setTimeout(function(){
        $(a).find('.window-block').css({
          'transition':'all 0.15s ease-in-out',
        });
      }, 50)
    },10)
  }, 0)


  if(a == '#settings'){
    opacity_save_settings();
    if($.cookie('theme') == 'white'){
      $('#ch1').prop('checked', false);
    } else{
      $('#ch1').prop('checked', true);
    }
  }

  if(b == 'sale' && b != undefined){
    setTimeout(function(){
      saleAnimation();
    }, 250)
  }
}

function close_error_profile(a,b){
  var parent = $(a).parent();
  var parentH = $(a).parent().height();

  parent.css('height',parentH)

  setTimeout(function(){
    parent.css('height','0px')
    parent.css('margin-bottom','0px')
    parent.css('opacity','0')
    setTimeout(function(){
      parent.css('display','none')
    }, 150)
  }, 1)

  if(b == 'email'){
    $.cookie('error_profile_email', false, {expires: 99999});
  }
  if(b == 'phone'){
    $.cookie('error_profile_phone', false, {expires: 99999});
  }
}

function test(){
  Console.log('test')
}

function sendExitForm() {
  if(user_token && (user_token != '') && (user_token.length > 1)) {
    $.post('db_login.php', {
      exitacc: 'true',
      token: user_token
    }).done(function(data) {
      if(data == 'GRANTED.') {
        document.location.reload(true);
      }
      else {
        console.log('response: ' + data);
      }
    });
  }
}

function newUserForm(skip) {
  var skip;
  if(newUserStage == 0) {

    function next() {
      $('.window-block-hello-block-conteiner-stage1').css('transform','translate(-100%, 0px)');
      $('.window-block-hello-block-conteiner-stage2').css('transform','translate(-100%, 0px)');
      $('.window-block-hello-block-conteiner-stage3').css('transform','translate(-100%, 0px)');
      newUserStage = 1;
    }

    if(skip) { next(); return; }

    var name = $('#new-user-form-name').val();
    var birthday = $('#new-user-form-birthday').val();
    var chb1 = document.getElementById('chb1').checked;
    var chb2 = document.getElementById('chb2').checked;
    var sex = (chb1 && !chb2) ? 'male' : 'female';

    nameRegex.lastIndex = 0;
    if(!nameRegex.test(name)) {
      return;
    }
    if((birthday.length > 10), (birthday == undefined), (birthday == '')) {
      return;
    }
    if(!chb1 && !chb2) {
      return;
    }

    $.post('db_profile.php', {
      nuform: 0,
      f1: name,
      f2: birthday,
      f3: sex
    }).done(function(data) {
      if(data == 'NEXT.') {
        next();
      }
      else if(data == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        console.log('response: ' + data);
      }
    });

  }
  else if(newUserStage == 1) {

    function next() {
      $('.window-block-hello-block-conteiner-stage1').css('transform','translate(-200%, 0px)');
      $('.window-block-hello-block-conteiner-stage2').css('transform','translate(-200%, 0px)');
      $('.window-block-hello-block-conteiner-stage3').css('transform','translate(-200%, 0px)');
      $('.window-block-hello-block-btn-further').text('Отправить');
      $('.window-block-hello-block-btn-skip').text('Закрыть');
      newUserStage = 2;
    }

    next();

  }
  else if(newUserStage == 2) {

    function close() {
      newUserStage = 0;
      close_window($('#hello .to_close icon-close'));
    }
    function next() {
      $('.window-block-hello-block-btn-skip').css('display', 'none');
      $('.window-block-hello-block-btn-further').text('Сохранить');
      newUserStage = 3;
    }

    var chb = document.getElementById('chb3').checked;
    var field = $('#new-user-form-email').val();

    if(skip || !chb) { close(); return; }

    emailRegex.lastIndex = 0;
    if(!emailRegex.test(field)) {
      return;
    }

    $.post('db_profile.php', {
      nuform: 2,
      f1: field
    }).done(function(data) {
      if(data == 'NEXT.') {
        next();
      }
      else if(data == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        console.log('response: ' + data);
      }
    });

  }
  else if(newUserStage == 3) {

    function close() {
      newUserStage = 0;
      close_window($('#hello .to_close icon-close'));
    }

    var field = $('#new-user-form-code').val().replace(/[^0-9]/gim, '');

    codeRegex.lastIndex = 0;
    if(!codeRegex.test(field)) {
      return;
    }

    $.post('db_profile.php', {
      nuform: 3,
      f1: field
    }).done(function(data) {
      if(data == 'DONE.') {
        close();
      }
      else if(data == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        console.log('response: ' + data);
      }
    });

  }
  else {}
}

function newsInitDoc(titleId, textId, mode) {
  var titleId, textId, mode;
  if(typeof(titleId) == 'undefined') { titleId = 'panel-news-conteiner-title-id'; }
  if(typeof(textId) == 'undefined') { textId = 'panel-news-conteiner-text-id'; }
  if(typeof(mode) == 'undefined') { mode = 'standart'; }
  News.titleId = titleId;
  News.textId = textId;
  News.mode = mode;
  News.fontSizeClicks = 0;
  News.fontSizeTimeout = 200;
  News.fontSizeContenteditable = false;
  News.undoCounter = 0;
  News.updateFlag = false;
  News.updateId = 0;
  News.notificationFunc = 'reset';
  News.deleteFlag = false;
  News.savedRecordFlag = false;
  News.searchTimemark = Date.now();
  News.searchText = '';
  News.attachments = [];
}

function newsFontSizeClicks(size) {
  var size;
  if(typeof(size) == 'undefined') {
    size = 6;
  }
  if(size < 6) {
    return;
  }
  // double click
  if(News.fontSizeClicks > 1) {
    // now field is contenteditable
    $('.panel-news_add-nav-elem-size1').attr('contenteditable', 'true');
    $('.panel-news_add-nav-elem-size1').focus();
    News.fontSizeContenteditable = true;
  }
  // clicked once
  else {
    var attr = $('.panel-news_add-nav-elem-size1').attr('contenteditable');
    // if not contenteditable (change font size)
    if(!((typeof(attr) == undefined) || (attr == true))) {
      News.fontSizeContenteditable = true;
      newsSelectFontSize(Number(size), false);
    }
    else {
      News.fontSizeContenteditable = false;
    }
  }
  News.fontSizeClicks = 0;
}

function getSelectionParentElement() {
  var parentEl = null, sel;
  if(window.getSelection) {
    sel = window.getSelection();
    if(sel.rangeCount) {
      parentEl = sel.getRangeAt(0).commonAncestorContainer;
      if(parentEl.nodeType != 1) {
        parentEl = parentEl.parentNode;
      }
    }
  }
  else if((sel = document.selection) && sel.type != "Control") {
    parentEl = sel.createRange().parentElement();
  }
  return parentEl;
}

function newsFormatDoc(cmd, value) {
  if($(getSelectionParentElement()).closest("#panel-news-conteiner-text-id").attr('id') == 'panel-news-conteiner-text-id') {
    document.execCommand(cmd, false, value);
    document.getElementById(News.textId).focus();
  }
}

function newsSaveDoc() {
  newsPublishDoc(false);
}

function newsPublishDoc(publish) {
  if(typeof(publish) == 'undefined') publish = true;
  if(publish !== true) publish = false;
  var text = $('#panel-news-conteiner-text-id').html();
  var title = $('#panel-news-conteiner-title-id').val();
  if(title.length < 1) {
    notification_add('error', 'Ошибка', 'Необходимо указать заголовок статьи', 5);
    return;
  }
  if(text.length < 1) {
    notification_add('error', 'Ошибка', 'Нельзя сохранить пустую статью', 5);
    return;
  }
  //console.log(News.attachments);
  if(News.updateFlag) {
    $.ajax({
      type: 'POST',
      url: 'db_profile.php',
      data: {
        news_update_record: true,
        record_id: News.updateId,
        //news_update_title: title,
        news_update_data: JSON.stringify({title: title, text: text}),
        news_publish_state: publish,
        news_attachments_json: JSON.stringify(News.attachments)
      },
      beforeSend: function(){
        loader('show');
      },
      complete: function(){
        loader('hidden');
      },
      success: function(response) {
        function checkResponseCode(code, rsp) {
          if(typeof(rsp) == 'undefined') rsp = response;
          return (response.substring(0, code.length) == code);
        }
        if(checkResponseCode('OK.')) {
          notification_add('line', '', 'Изменения сохранены', 7);
          newsUpdateList();
          setTimeout(function() {
          }, 1000);
        }
        else if(response == 'AUTH.') {
          document.location.reload(true);
        }
        else if(checkResponseCode('TITLE_SZ.')) {
          notification_add('error', 'Ошибка', 'Превышена максимальная длина заголовка', 5);
        }
        else if(checkResponseCode('TITLE.')) {
          notification_add('error', 'Ошибка', 'Недопустимый формат заголовка', 5);
        }
        else if(checkResponseCode('DATA.')) {
          notification_add('error', 'Ошибка', 'Превышен максимальный объём статьи', 5);
        }
        else {
          notification_add('error', 'Ошибка сервера', 'Не удалось сохранить статью', 5);
          console.log('response: ' + response);
        }
      },
      error: function(jqXHR, status) {
        notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
        console.log('error: ' + status + ', ' + jqXHR);
      }
    });
  }
  else {
    $.ajax({
      type: 'POST',
      url: 'db_profile.php',
      data: {
        news_publish: publish,
        news_publish_data: JSON.stringify({title: title, text: text}),
        news_attachments_json: JSON.stringify(News.attachments)
        /*news_publish_title: title,
        news_publish_data: data*/
      },
      beforeSend: function(){
        loader('show');
      },
      complete: function(){
        loader('hidden');
      },
      success: function(response) {
        function checkResponseCode(code, rsp) {
          if(typeof(rsp) == 'undefined') rsp = response;
          return (response.substring(0, code.length) == code);
        }
        if(checkResponseCode('OK.')) {
          /*var responseText = response.substring(3, response.length);*/
          if(publish) {
            notification_add('line', '', 'Статья опубликована', 7);
            newsUpdateList();
            setTimeout(function() {
              newsCreateNew(1);
            }, 1000);
          }
          else {
            notification_add('line', '', 'Статья сохранена', 7);
            newsUpdateList();
            setTimeout(function() {
              newsCreateNew(1);
            }, 1000);
          }
        }
        else if(response == 'AUTH.') {
          document.location.reload(true);
        }
        else if(checkResponseCode('TITLE_SZ.')) {
          notification_add('error', 'Ошибка', 'Превышена максимальная длина заголовка', 5);
        }
        else if(checkResponseCode('TITLE.')) {
          notification_add('error', 'Ошибка', 'Недопустимый формат заголовка', 5);
        }
        else if(checkResponseCode('DATA.')) {
          notification_add('error', 'Ошибка', 'Превышен максимальный объём статьи', 5);
        }
        else {
          notification_add('error', 'Ошибка сервера', 'Не удалось опубликовать статью', 5);
          console.log('response: ' + response);
        }
      },
      error: function(jqXHR, status) {
        notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
        console.log('error: ' + status + ', ' + jqXHR);
      }
    });
  }
}

function newsLoadAndEdit(id, stage) {
  if(!News.deleteFlag) {
    if(typeof(stage) == 'undefined') {
      // уведомление
      News.updateId = id;
      News.notificationFunc = 'load&edit';
      $('#panel-news-confirm-text').text('Вы уверены, что хотите открыть другую статью? Внесенные изменения будут отменены.');
      open_window('#panel-news-confirm');
    }
    else if(stage == 1) {
      // уведомление
      close_window();
      // кнопки
      if(News.savedRecordFlag) {
        $('#newsRecordSaveButton1').css('display', 'block');
        $('#newsRecordSaveButton2').css('display', 'none');
      }
      else {
        $('#newsRecordSaveButton1').css('display', 'none');
        $('#newsRecordSaveButton2').css('display', 'block');
      }
      // стандартный режим
      news_type('standart');
      // загрузка
      $('#panel-news-add-title').text('Редактирование статьи');
      News.updateFlag = true;
      $.ajax({
        type: 'POST',
        url: 'db_profile.php',
        data: {
          news_get_record: true,
          record_id: id
        },
        beforeSend: function(){
          loader('show');
        },
        complete: function(){
          loader('hidden');
        },
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.')) {
            var delimeterPos = response.lastIndexOf('*?*');
            var responseText;
            var attachments;
            if(delimeterPos == -1) {
              responseText = response.substring(3);
            }
            else {
              responseText = response.substring(3, delimeterPos);
              attachments = response.substring(delimeterPos + 3);
            }
            var responseData = JSON.parse(responseText);
            $('#panel-news-conteiner-title-id').val(responseData[0].title);
            $('#panel-news-conteiner-text-id').html($.parseHTML(responseData[0].text));
            News.editingRecordBy =responseData[0].account;
            var parsed_attachments = [];
            if(typeof(attachments) != 'undefined') {
              parsed_attachments = JSON.parse(attachments);
            }
            //dropAttachments();
            setAttachments(parsed_attachments, true);
          }
          else if(response == 'AUTH.') {
            document.location.reload(true);
          }
          else if(checkResponseCode('EMPTY.')) {
            notification_add('error', 'Ошибка', 'Запись не найдена', 5);
            newsUpdateList();
          }
          else {
            notification_add('error', 'Ошибка сервера', 'Не удалось загрузить статью', 5);
            console.log('response: ' + response);
          }
        },
        error: function(jqXHR, status) {
          notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
          console.log('error: ' + status + ', ' + jqXHR);
        }
      });
    }
    else {
      return;
    }
  }
}

function newsSearchRecord() {
  if(News.deleteFlag) {
    return;
  }
  var timemark = Date.now();
  if((timemark - News.searchTimemark) < 1200) {
    return;
  }
  News.searchTimemark = timemark;
  var needle = $('#searchnews').val();
  if(News.searchText == needle) {
    return;
  }
  News.searchText = needle;
  if(needle.length > 0) {
    newsUpdateList(needle);
  }
  else {
    newsUpdateList();
  }
}

function newsDeleteRecord(array, done, founded) {
  if(typeof(done) != 'undefined') {
    if(done) {
      if(founded == true) {
        News.updateFlag = false;
        News.updateId = 0;
      }
      return;
    }
  }
  else {
    if(array.length > 0) {
      newsDeleteRecordRecursive(array, founded);
    }
  }
}

function newsDeleteRecordRecursive(array, founded) {
  if(typeof(founded) == 'undefined') {
    founded = false;
  }
  if(array.length > 0) {
    var id = array.pop();
    $.ajax({
      type: 'POST',
      url: 'db_profile.php',
      data: {
        news_delete_record: true,
        ndr_id: id
      },
      beforeSend: function(){
        loader('show');
      },
      complete: function(){
        loader('hidden');
      },
      success: function(response) {
        function checkResponseCode(code, rsp) {
          if(typeof(rsp) == 'undefined') rsp = response;
          return (response.substring(0, code.length) == code);
        }
        if(checkResponseCode('OK.')) {
          setTimeout(function() {
            var isFound = false;
            if(News.updateId == id) {
              isFound = true;
            }
            newsDeleteRecordRecursive(array, isFound);
          }, 250);
        }
        else if(response == 'AUTH.') {
          document.location.reload(true);
        }
        else if(checkResponseCode('EMPTY.')) {
          notification_add('error', 'Ошибка', 'Запись не найдена', 5);
        }
        else {
          notification_add('error', 'Ошибка сервера', 'Не удалось удалить статью', 5);
          console.log('response: ' + response);
        }
      },
      error: function(jqXHR, status) {
        notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
        console.log('error: ' + status + ', ' + jqXHR);
      }
    });
  }
  else {
    newsDeleteRecord([], true, founded);
  }
}

function newsUpdateList(search) {
  // default
  var data = {news_get_list: true};
  // filter by search field
  if(typeof(search) != 'undefined') {
    data.news_get_search = String(search);
  }
  // filter by username
  if((typeof(News.filterParams['username']) != 'undefined') && (News.filterParams['username'].length > 0)) {
    var loginRegex = /^([a-z0-9]){4,32}$/g;
    loginRegex.lastIndex = 0;
    if(!loginRegex.test(News.filterParams['username'])) {
      return;
    }
    else {
      data.news_get_filter_username = News.filterParams['username'];
    }
  }
  // filter by type (need published)
  if(News.filterParams['needPublished']) {
    data.news_get_filter_published = true;
  }
  // filter by type (need saved)
  if(News.filterParams['needSaved']) {
    data.news_get_filter_saved = true;
  }
  // filter by date (start)
  if((typeof(News.filterParams['startDate']) != 'undefined') && (News.filterParams['startDate'].length > 0)) {
    data.news_get_filter_start = News.filterParams['startDate'];
  }
  // filter by date (end)
  if((typeof(News.filterParams['endDate']) != 'undefined') && (News.filterParams['endDate'].length > 0)) {
    data.news_get_filter_end = News.filterParams['endDate'];
  }
  // add sorting
  data.news_get_sortby = News.filterParams['sortBy'];
  data.news_get_sortorder = News.filterParams['sortOrder'];
  // request
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: data,
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        // empty block
        $('#news-search-block').css('opacity', '0');
        setTimeout(function() {
          $('#news-search-block').css('display', 'none');
        }, 100);
        //
        var responseText = response.substring(3, response.length);
        var responseData = JSON.parse(responseText);
        //console.log(responseData[0]['debug']);
        News.recordsList = responseData;
        var outputPublicated = '';
        var outputSaved = '';
        for(var i = 0; i < responseData.length; i++) {
          if(responseData[i].publicated == 1) {
            outputPublicated += '<div class="panel-news-block" onclick="News.savedRecordFlag = false; newsLoadAndEdit(' + responseData[i].id + ');">';
            outputPublicated += '<div class="panel-news-block-img icon-article_good" style="background-color: #0abb87;">';
            outputPublicated += '<span style="opacity: 0; display: none; transition: 0.1s all;">';
            outputPublicated += '<input checked type="checkbox" class="panel-news-block-img-ch" id="panel-news-block-img-ch-G53F_t' + responseData[i].id + '" style="display: none">';
            outputPublicated += '<label for="panel-news-block-img-ch-G53F_t' + responseData[i].id + '" class="panel-msg-block-text-del">';
            outputPublicated += '<div class="panel-news-block-img-ch-line1" style="background-color: #0abb87;"></div>';
            outputPublicated += '<div class="panel-news-block-img-ch-line2" style="background-color: #0abb87;"></div>';
            outputPublicated += '</label>';
            outputPublicated += '</span>';
            outputPublicated += '</div>';
            outputPublicated += '<div class="panel-news-block-text">';
            let title = responseData[i].title.replace(/([^a-zA-Zа-яёА-ЯЁ0-9., ])/g, '');
            outputPublicated += '<div class="panel-msg-block-text-title">' + title + '</div>';
            let text = responseData[i].data.replace(/([^a-zA-Zа-яёА-ЯЁ0-9., ])/g, '');
            outputPublicated += '<div class="panel-msg-block-text-msg">' + text + '</div>';
            outputPublicated += '</div>';
            outputPublicated += '</div>';
          }
          else {
            outputSaved += '<div class="panel-news-block" onclick="News.savedRecordFlag = true; newsLoadAndEdit(' + responseData[i].id + ');">';
            outputSaved += '<div class="panel-news-block-img icon-article" style="background-color: #5d78ff;">';
            outputSaved += '<span style="opacity: 0; display: none; transition: 0.1s all;">';
            outputSaved += '<input checked type="checkbox" class="panel-news-block-img-ch" id="panel-news-block-img-ch-G53F_t' + responseData[i].id + '" style="display: none">';
            outputSaved += '<label for="panel-news-block-img-ch-G53F_t' + responseData[i].id + '" class="panel-msg-block-text-del">';
            outputSaved += '<div class="panel-news-block-img-ch-line1"></div>';
            outputSaved += '<div class="panel-news-block-img-ch-line2"></div>';
            outputSaved += '</label>';
            outputSaved += '</span>';
            outputSaved += '</div>';
            outputSaved += '<div class="panel-news-block-text">';
            let title = responseData[i].title.replace(/([^a-zA-Zа-яёА-ЯЁ0-9., ])/g, '');
            outputSaved += '<div class="panel-msg-block-text-title">' + title + '</div>';
            let text = responseData[i].data.replace(/([^a-zA-Zа-яёА-ЯЁ0-9., ])/g, '');
            outputSaved += '<div class="panel-msg-block-text-msg">' + text + '</div>';
            outputSaved += '</div>';
            outputSaved += '</div>';
          }
        }
        // reset
        $('#newsPublished').empty();
        $('#newsSaved').empty();
        // add new blocks
        $('#newsPublished').append(outputPublicated);
        $('#newsSaved').append(outputSaved);
        // add listeners to new blocks
        $('.panel-msg-block-text-del').click(function(){
          var blockInput = $(this).parent().find('input').prop('checked'),
              block = $(this);
          if(blockInput){
            block.find('.panel-news-block-img-ch-line1').css({'width':'11px','transform':'translate(6px, 19px) rotate(45deg)','opacity':'1'})
            setTimeout(function(){
              block.find('.panel-news-block-img-ch-line2').css({'width':'20px','transform':'translate(14px, 18px) rotate(-45deg)','opacity':'1'})
            }, 80)
          } else{
            block.find('.panel-news-block-img-ch-line2').css({'width':'0px','transform':'translate(14px, 18px) rotate(-45deg)','opacity':'1'})
            setTimeout(function(){
              block.find('.panel-news-block-img-ch-line1').css({'width':'0px','transform':'translate(6px, 19px) rotate(45deg)','opacity':'1'})
            }, 80)
          }
        });
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else if(checkResponseCode('EMPTY.')) {
        // reset
        $('#newsPublished').empty();
        $('#newsSaved').empty();
        // empty block
        var filterEnabled = true;
        if(News.filterParams.sortBy == 'date' && News.filterParams.sortOrder == 'desc' && News.filterParams.username.length == 0 && News.filterParams.needPublished == true && News.filterParams.needSaved == true && News.searchText.length == 0) {
          filterEnabled = false;
        }
        // empty block
        if(filterEnabled) {
          $('#news-search-block').css('display', 'block');
          setTimeout(function(){
            $('#news-search-block').css('opacity', '1');
          }, 10);
        }
        else {
          $('#news-search-block').css('opacity', '0');
          setTimeout(function() {
            $('#news-search-block').css('display', 'none');
          }, 100);
        }
      }
      else {
        console.log('error in newsUpdateList(), response: ' + response);
      }
    },
    error: function(jqXHR, status) {
      console.log('undefined error in newsUpdateList(): ' + status + ', ' + jqXHR);
    }
  });
}

function newsApplyFilters() {
  News.filterParams['username'] = $('#window-block-conteiner-news-search-input').text();
  close_window();
  open_set_news();
  newsUpdateList();
}

function newsResetFilters() {
  // clear sorting
  News.filterParams.sortBy = 'date';
  News.filterParams.sortOrder = 'desc';
  document.getElementById('window_sort_news_id_1').checked = false;
  document.getElementById('window_sort_news_id_2').checked = false;
  document.getElementById('window_sort_news_id_3').checked = true;
  $('#window_sort_news_id_style_1').find('.window-block-sort-elem-ico-small').css('transform','rotate(0deg)');
  $('#window_sort_news_id_style_2').find('.window-block-sort-elem-ico-small').css('transform','rotate(0deg)');
  $('#window_sort_news_id_style_3').find('.window-block-sort-elem-ico-small').css('transform','rotate(0deg)');
  // clear username filter
  News.filterParams.username = '';
  News.filtersWindowFlag = false;
  $('#window-block-conteiner-news-search-input').text('');
  $('#window-block-conteiner-news-search-input').attr('placeholder','Имя пользователя');
  // clear record type filters
  News.filterParams.needPublished = true;
  News.filterParams.needSaved = true;
  document.getElementById('chb1-01-01').checked = true;
  document.getElementById('chb1-01-02').checked = true;
  // clear date filter
  News.filterParams.startDate = undefined;
  News.filterParams.endDate = undefined;
  $('#news-filter-date-start-1').val('');
  $('#news-filter-date-end-1').val('');
  // output
  open_set_news();
  newsUpdateList();
}

function newsCreateNew(stage) {
  News.savedRecordFlag = false;
  if(typeof(stage) == 'undefined') {
    // уведомление
    News.notificationFunc = 'reset';
    $('#panel-news-confirm-text').text('Вы уверены, что хотите создать новую статью? Внесенные изменения будут отменены.');
    open_window('#panel-news-confirm');
  }
  else if(stage == 1) {
    // кнопки
    $('#newsRecordSaveButton1').css('display', 'block');
    $('#newsRecordSaveButton2').css('display', 'none');
    // уведомление
    close_window();
    // обновление
    $('#panel-news-add-title').text('Создание новой статьи');
    News.updateFlag = false;
    News.updateId = 0;
    $('#panel-news-conteiner-title-id').val('Заголовок статьи');
    $('#panel-news-conteiner-text-id').html('');
    dropAttachments();
    setAttachments([], true);
  }
  else {
    return;
  }
}

function newsSelectFontSize(size, set) {
  if($(getSelectionParentElement()).closest("#panel-news-conteiner-text-id").attr('id') != 'panel-news-conteiner-text-id') {
    return;
  }
  var size, set;
  if(typeof(set) == 'undefined') {
    set = true;
  }
  if(size < 6) {
    return;
  }
  var unit = 'px';
  var spanString = $('<span/>', {
    'text': document.getSelection()
  }).css('font-size', size + unit).prop('outerHTML');
  document.execCommand('insertHTML', false, spanString);
  if(set) {
    $('.panel-news_add-nav-elem-size1').text(size);
  }
}

function newsCreateLink() {
  var sLnk = prompt('Введите URL:','https:\/\/');

  if(sLnk && sLnk != '' && sLnk != 'http://' && sLnk != 'https://'){
    newsFormatDoc('createLink', sLnk);
  }
}

function newsPrintDoc(){
  if ($('#news').css('display') == 'block') {
    var htmlNewsText = $('#panel-news-conteiner-text-id').html();
    var htmlNewsTitle = $('#panel-news-conteiner-title-id').val();
    var htmlNewsSample = "<!DOCTYPE html> <html dir='ltr'> <head> <meta charset='utf-8'> <title></title> <style> @import 'media/fonts/fonts.css'; body{ font-family: pfm; color: #303036; } .logo{ position: relative; height: 100px; width: 240px; background-image: url('media/img/swiftlyAPLogoText.png'); background-repeat: no-repeat; filter: saturate(2.1) grayscale(1); background-size: contain; } .status{ position: absolute; right: 20px; top: 20px; } .title{ font-family: pfb; font-size: 25px; margin-left: 10px; } .text{ white-space: pre-line; word-wrap: break-word; margin-left: 10px; margin-top: 5px; } .status-text{ position: relative; text-align: left; line-height: 22px; } .status-text-block{ padding-left: 10px; padding-right: 10px; border-radius: 5px; background-color: #d2d2d2; padding-top: 2px; padding-bottom: 2px; font-family: pfm; } </style> </head> <body onload='window.print();' onafterprint='self.close()'> <div style='width: 100%; height: 210px;'> <div class='status'> <div class='logo'></div> <div class='status-text'> <span style='white:space: nowrap;'>Логин: <span style='font-family: pfl;'>" + userData['login'] + "</span> <span class='status-text-block'>" + userData['access'] + "</span><span> <br> Имя и фамилия: <span style='font-family: pfl;'>" + userData['name1'] + ' ' + userData['name2'] + "</span> <br> Телефон: <span style='font-family: pfl;'>" + userData['phone'] + "</span> <br> Почта: <span style='font-family: pfl;'>" + userData['email'] + "</span> <br> </div> </div> </div> <div class='title'>" + htmlNewsTitle + "</div> <div class='text'>" + htmlNewsText + "</div> </body> </html> ";
    var oPrntWin = window.open("","_blank","width=450,height=470,left=400,top=100,menubar=no,toolbar=no,location=no,scrollbars=yes");
    oPrntWin.document.open();
    oPrntWin.document.write(htmlNewsSample);
    oPrntWin.document.close();
  } else{
    var htmlNewsText = $('#panel-news-conteiner-text-id-2-company').html()
    var htmlNewsTitle = $('#panel-news-conteiner-title-id-company').text()
    var htmlNewsSample = "<!DOCTYPE html> <html dir='ltr'> <head> <meta charset='utf-8'> <title></title> <style> @import 'media/fonts/fonts.css'; body{ font-family: pfm; color: #303036; } .logo{ position: relative; height: 100px; width: 240px; background-image: url('media/img/swiftlyAPLogoText.png'); background-repeat: no-repeat; filter: saturate(2.1) grayscale(1); background-size: contain; } .status{ position: absolute; right: 20px; top: 20px; } .title{ font-family: pfb; font-size: 25px; margin-left: 10px; } .text{ white-space: pre-line; word-wrap: break-word; margin-left: 10px; margin-top: 5px; } .status-text{ position: relative; text-align: left; line-height: 22px; } .status-text-block{ padding-left: 10px; padding-right: 10px; border-radius: 5px; background-color: #d2d2d2; padding-top: 2px; padding-bottom: 2px; font-family: pfm; } </style> </head> <body onload='window.print();'> <div style='width: 100%; height: 210px;'> <div class='status'> <div class='logo'></div> <div class='status-text'> <span style='white:space: nowrap;'>Логин: <span style='font-family: pfl;'>" + userData['login'] + "</span> <span class='status-text-block'>" + userData['access'] + "</span><span> <br> Имя и фамилия: <span style='font-family: pfl;'>" + userData['name1'] + ' ' + userData['name2'] + "</span> <br> Телефон: <span style='font-family: pfl;'>" + userData['phone'] + "</span> <br> Почта: <span style='font-family: pfl;'>" + userData['email'] + "</span> <br> </div> </div> </div> <div class='title'>" + htmlNewsTitle + "</div> <div class='text'>" + htmlNewsText + "</div> </body> </html> ";
    var oPrntWin = window.open("","_blank","width=450,height=470,left=400,top=100,menubar=yes,toolbar=no,location=no,scrollbars=yes");
    oPrntWin.document.open();
    oPrntWin.document.write(htmlNewsSample);
    oPrntWin.document.close();
  }
}

function setAttachmentMime(mime) {
  var mime;
  if(typeof(mime) == 'undefined') {
    mime = 'other';
  }
  if(mime != 'image' && mime != 'document' && mime != 'audio' && mime != 'video' && mime != 'other') {
    mime = 'other';
  }
  News.attachmentMime = mime;
}

function removeAttachment(filename) {
  // remove from array
  for(var i = 0; i < News.attachments.length; i++) {
    if(News.attachments[i].hash == filename) {
      News.attachments.splice(i, 1);
    }
  }
  //console.log(News.attachments);
}

function displayAttachment(file, user) {
  if((typeof(file['mime']) != 'undefined') && (typeof(file['filename']) != 'undefined') && (typeof(file['hash']) != 'undefined')) {
    var htmltext = '\n';
    var folder = 'media/users/public/' + userData['login'] + '/attachments/temp/';
    if(user == true && News.editingRecordBy != undefined && News.updateId > 0) {
      folder = 'media/users/public/' + News.editingRecordBy + '/attachments/record' + News.updateId + '/';
    }
    if(file['mime'] == 'image') {
      htmltext += '<div class="panel-news-document-file" style="margin-bottom: 5px;">';
      htmltext += '<div class="panel-news-document-add-mainbg" style="background-image: url(' + folder + file['hash'] + ');"></div>';
      htmltext += '<div class="panel-news-document-add-del icon-plus" onclick="news_del_file(this); removeAttachment(\'' + file['hash'] + '\');" title="Удалить"></div>';
      htmltext += '<div class="panel-news-document-add-bg"></div>';
      htmltext += '</div>';
    }
    else if(file['mime'] == 'document') {
      htmltext += '<div class="panel-news-document-file-noIMG" style="margin-bottom: 5px;">';
      htmltext += '<div class="panel-news-document-add-del icon-plus" onclick="news_del_file(this); removeAttachment(\'' + file['hash'] + '\');" title="Удалить"></div>';
      htmltext += '<div class="panel-news-document-add-ico icon-document"></div>';
      htmltext += '<div class="panel-news-document-add-text">' + file['filename'] + '</div>';
      htmltext += '</div>';
    }
    else if(file['mime'] == 'audio') {
      htmltext += '<div class="panel-news-document-file-noIMG" style="margin-bottom: 5px;">';
      htmltext += '<div class="panel-news-document-add-del icon-plus" onclick="news_del_file(this); removeAttachment(\'' + file['hash'] + '\');" title="Удалить"></div>';
      htmltext += '<div class="panel-news-document-add-ico icon-music"></div>';
      htmltext += '<div class="panel-news-document-add-text">' + file['filename'] + '</div>';
      htmltext += '</div>';
    }
    else if(file['mime'] == 'video') {
      htmltext += '<div class="panel-news-document-file-noIMG" style="margin-bottom: 5px;">';
      htmltext += '<div class="panel-news-document-add-del icon-plus" onclick="news_del_file(this); removeAttachment(\'' + file['hash'] + '\');" title="Удалить"></div>';
      htmltext += '<div class="panel-news-document-add-ico icon-video"></div>';
      htmltext += '<div class="panel-news-document-add-text">' + file['filename'] + '</div>';
      htmltext += '</div>';
    }
    else {
      htmltext += '<div class="panel-news-document-file-noIMG" style="margin-bottom: 5px;">';
      htmltext += '<div class="panel-news-document-add-del icon-plus" onclick="news_del_file(this); removeAttachment(\'' + file['hash'] + '\');" title="Удалить"></div>';
      htmltext += '<div class="panel-news-document-add-ico icon-file2"></div>';
      htmltext += '<div class="panel-news-document-add-text">' + file['filename'] + '</div>';
      htmltext += '</div>';
    }
    //News.attachments.push(file);
    $('#news-attachments-container').append(htmltext);
  }
}

function setAttachments(data, clear) {
  if(clear === true) {
    $('#news-attachments-container').empty();
    News.attachments = [];
    var htmltext = '';
    htmltext += '<div class="panel-news-document-add" style="margin-bottom: 5px;" onclick="open_window(\'#news-add-file\')">';
    htmltext += '<div class="panel-news-document-add-ico icon-file"></div>';
    htmltext += '<div class="panel-news-document-add-text">Прикрепить<br>файл</div>';
    htmltext += '</div>';
    $('#news-attachments-container').append(htmltext);
  }
  if(typeof(data) != 'undefined' && data.length > 0) {
    for(let i = 0; i < data.length; i++) {
      displayAttachment(data[i], true);
      data[i].status = 'old';
    }
  }
  News.attachments = data;
}

function dropAttachments() {
  // from html and variable
  setAttachments([], true);
  // from session
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      drop_temp_attachments: true
    },
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      if(response != 'OK.') {
        console.log('error: ' + response);
      }
      if(response == 'AUTH.') {
        document.location.reload(true);
      }
    },
    error: function(jqXHR, status) {
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

function clearInputTypeFile(id) {
  $('#' + id).val('');
}

function updateAccessLogs() {
  if(typeof(registrationTimestamp) != 'undefined') {
    return;
  }
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      get_login_records: true,
      get_log_rec_count: 19
    },
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      var accountsArray = [];
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        var responseText = response.substring(3, response.length);
        var responseData = JSON.parse(responseText);
        var output = '';
        // parse
        for(var i = 0; i < responseData.length - 1; i++) {
          var title = responseData[i].title;
          var desc = responseData[i].description;
          if(desc != '') {
            desc += ' <br>';
          }
          var date_timestamp = responseData[i].date;
          var the_date = '<b>Дата :</b> ' + date_timestamp.replace(/\-/g, '.').substring(0, date_timestamp.length - 3) + '<br>';
          var ip = '<b>IP :</b> ' + responseData[i].ip + '<br>';
          var loc = '<b>Город :</b> ' + responseData[i].location;
          var WARN_COLOR_CODE = '#fd397a';
          var LOG_COLOR_CODE = '#0abb87';
          var REG_COLOR_CODE = '#ffb822';
          var color = LOG_COLOR_CODE;
          if(title == 'Регистрация') {
            color = REG_COLOR_CODE;
            var registrationTimestampArray = date_timestamp.replace(/\-/g, '.').substring(0, date_timestamp.length - 9).split('.');
            registrationTimestamp = registrationTimestampArray[2] + '.' + registrationTimestampArray[1] + '.' + registrationTimestampArray[0];
            var regDate = new Date(registrationTimestampArray);
            var nowDate = new Date();
            accountDays = (nowDate - regDate) / (1000 * 60 * 60 * 24);
          }
          else if(title == 'Сеанс завершен') {
            color = REG_COLOR_CODE;
          }
          else if(title == 'Изменен пароль') {
            passwordChangeDate = the_date;
            color = REG_COLOR_CODE;
          }
          else if(title == 'Подозрительная активность') {
            color = WARN_COLOR_CODE;
          }
          else {
            color = LOG_COLOR_CODE;
          }
          // compose
          output += '<div class="panel-profile-block-conteiner-history-elem">';
          output += '<div class="panel-profile-block-conteiner-history-elem-line" style="background-color: ' + color + ';"></div>';
          output += '<div class="panel-profile-block-conteiner-history-elem-text">';
          output += '<div class="panel-profile-block-conteiner-history-elem-text-title">' + title + '</div>';
          output += '<div class="panel-profile-block-conteiner-history-elem-text-text">' + desc + the_date + ip + loc + '</div>';
          output += '</div>';
          output += '</div>' + "\n";
        }
        // get password change date
        var responsePasswordChangeDate = responseData[responseData.length - 1];
        if(responsePasswordChangeDate != 'none') {
          var passwordChangeDateObject = new Date(responsePasswordChangeDate);
          var months = [
            'января',
            'февраля',
            'марта',
            'апреля',
            'мая',
            'июня',
            'июля',
            'августа',
            'сентября',
            'ноября',
            'декабря',
          ];
          var pcdDay = passwordChangeDateObject.getDate();
          var pcdMonth = months[passwordChangeDateObject.getMonth()];
          var pcdYear = passwordChangeDateObject.getFullYear();
          passwordChangeDate = pcdDay + ' ' + pcdMonth + ' ' + pcdYear + ' г.';
        }
        $('#passwordChangeDate').html(passwordChangeDate);
        // output
        $('#panel-profile-block-conteiner-history').empty();
        $('#panel-profile-block-conteiner-history').append(output);
        $('#panel-profile-block-conteiner-info-register-date').empty().append(registrationTimestamp);
        if(accountDays < 365) {
          var totalDays = Math.floor(accountDays);
          $('#panel-profile-block-conteiner-info-life-title').empty().append(ucFirst(declOfNumber(totalDays, 'день')) + ' в системе');
          $('#panel-profile-block-conteiner-info-life-time').empty().append(totalDays);
        }
        else {
          var totalYears = Math.floor(accountDays / 365);
          $('#panel-profile-block-conteiner-info-life-title').empty().append(ucFirst(declOfNumber(totalYears, 'год')) + ' в системе');
          $('#panel-profile-block-conteiner-info-life-time').empty().append(totalYears);
        }
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

function declOfNumber(number, decl) {
  var strnum = String(number);
  var number1 = Number(strnum.substring(strnum.length - 1, strnum.length));
  var number2 = Number(strnum.substring(strnum.length - 2, strnum.length));
  if((number2 >= 10 && number2 <= 20) || (number1 >= 5 && number1 <= 9) || number1 == 0) {
    if(decl == 'год') return 'лет';
    if(decl == 'месяц') return 'месяцев';
    if(decl == 'день') return 'дней';
    if(decl == 'элемент') return 'элементов';
    if(decl == 'просмотр') return 'просмотров';
    if(decl == 'Уведомление') return 'Уведомлений';
    if(decl == 'новый') return 'новых';
    if(decl == 'человек') return 'человек';
  }
  else if(number1 == 1) {
    return decl;
  }
  else if(number1 >= 2 && number1 <= 4) {
    if(decl == 'год') return 'года';
    if(decl == 'месяц') return 'месяца';
    if(decl == 'день') return 'дня';
    if(decl == 'элемент') return 'элемента';
    if(decl == 'просмотр') return 'просмотра';
    if(decl == 'Уведомление') return 'Уведомления';
    if(decl == 'новый') return 'новых';
    if(decl == 'человек') return 'человека';
  }
  else {
    return undefined;
  }
}

function ucFirst(str) {
  if(!str) return str;
  return str[0].toUpperCase() + str.slice(1);
}

// =============================================================================

function profileSetBorder(element, valid) {
  var element, valid;
  if(!valid) {
    ProfileWindow.validForm = false;
  }
  if(valid) {
    $(element).parent().css('border','');
  }
  else {
    $(element).parent().css('border','2px solid #b32424');
  }
}

// close_window_shadow
function closeProfileWindow() {
  ProfileWindow.changedFields = 0;
  // change border-color of field
  function profileSetBorder(element, valid) {
    var element, valid;
    if(!valid) {
      ProfileWindow.validForm = false;
    }
    if(valid) {
      $(element).parent().css('border','');
    }
    else {
      $(element).parent().css('border','2px solid #b32424');
    }
  }
  // recover borders
  profileSetBorder('#profileWindow-input-name1', true);
  profileSetBorder('#profileWindow-input-name2', true);
  profileSetBorder('#profileWindow-input-city', true);
  profileSetBorder('#profileWindow-input-phone', true);
  profileSetBorder('#profileWindow-input-email', true);
  // recover values
  var name1 = userData.name1;
  var name2 = userData.name2;
  var birthday = userData.birthday;
  var country = userData.country;
  var city = userData.city;
  var phonenumber = userData.phone;
  var email = userData.email;
  var gender = (userData.gender == 'male') ? true : false;
  $('#profileWindow-input-name1').val(name1);
  $('#profileWindow-input-name2').val(name2);
  $('#profileWindow-input-birthday').val(birthday);
  $('#profileWindow-input-country').val(country);
  $('#profileWindow-input-city').val(city);
  $('#profileWindow-input-phone').val(phonenumber);
  $('#profileWindow-input-email').val(email);
  document.getElementById('chb1-0').checked = gender;
  document.getElementById('chb2-0').checked = !gender;
  // profile form data
  ProfileWindow.validForm = false;
  //ProfileWindow.phoneConfirmed = userData.phone_verify;
  ProfileWindow.phoneConfirmed = ProfileWindow.phoneWasConfirmed;
  //ProfileWindow.emailConfirmed = userData.email_verify;
  ProfileWindow.emailConfirmed = ProfileWindow.emailWasConfirmed;
  // change styles
  if(ProfileWindow.emailConfirmed) {
    $('#profileWindow-valid-good-email').css('display', 'block');
    $('#profileWindow-valid-error-email').css('display', 'none');
    $('#profileWindow-btn-email').css('display', 'none');
  }
  else {
    $('#profileWindow-valid-good-email').css('display', 'none');
    $('#profileWindow-valid-error-email').css('display', 'block');
    $('#profileWindow-btn-email').css('display', 'block');
  }
  if(ProfileWindow.phoneConfirmed) {
    $('#profileWindow-valid-good-phone').css('display', 'block');
    $('#profileWindow-valid-error-phone').css('display', 'none');
    $('#profileWindow-btn-phone').css('display', 'none');
  }
  else {
    $('#profileWindow-valid-good-phone').css('display', 'none');
    $('#profileWindow-valid-error-phone').css('display', 'block');
    $('#profileWindow-btn-phone').css('display', 'block');
  }
}

function checkProfileWindow() {
  ProfileWindow.validForm = true;
  ProfileWindow.changedFields = 0;
  ProfileWindow.data = {};
  var emailRegexValid = true;
  var phoneRegexValid = true;
  // recover borders
  profileSetBorder('#profileWindow-input-name1', true);
  profileSetBorder('#profileWindow-input-name2', true);
  profileSetBorder('#profileWindow-input-city', true);
  profileSetBorder('#profileWindow-input-phone', true);
  profileSetBorder('#profileWindow-input-email', true);
  // read

  $('input[type="tel"]').inputmask("+7 (999) 999-99-99");
  var name1 = $('#profileWindow-input-name1').val();
  var name2 = $('#profileWindow-input-name2').val();
  var birthday = $('#profileWindow-input-birthday').val();
  var country = $('#profileWindow-input-country').val();
  var city = $('#profileWindow-input-city').val();
  var phonenumber = $('#profileWindow-input-phone').val().replace(/(\+|-|\s|\(|\))/g, '');
  var email = $('#profileWindow-input-email').val();
  var gender = document.getElementById('chb1-0').checked;
  // check
  // first name
  nameRegex.lastIndex = 0;
  if(!nameRegex.test(name1)) {
    profileSetBorder('#profileWindow-input-name1', false);
  }
  if(name1 != userData.name1) {
    ProfileWindow.changedFields++;
    ProfileWindow.data['profile_form_name1'] = name1;
  }
  // second name
  if(name2.length > 0 || name2 != userData.name2) {
    nameRegex.lastIndex = 0;
    if(!nameRegex.test(name2)) {
      profileSetBorder('#profileWindow-input-name2', false);
    }
  }
  if(name2 != userData.name2) {
    ProfileWindow.changedFields++;
    ProfileWindow.data['profile_form_name2'] = name2;
  }
  // birthday
  if(birthday != userData.birthday) {
    ProfileWindow.changedFields++;
    ProfileWindow.data['profile_form_birthday'] = birthday;
  }
  // city
  if(city.length > 0 || city != userData.city) {
    nameRegex.lastIndex = 0;
    if(!nameRegex.test(city)) {
      profileSetBorder('#profileWindow-input-city', false);
    }
  }
  if(city != userData.city) {
    ProfileWindow.changedFields++;
    ProfileWindow.data['profile_form_city'] = city;
  }
  // country
  if(country != userData.country) {
    ProfileWindow.changedFields++;
    ProfileWindow.data['profile_form_country'] = country;
  }
  // gender
  if(gender != (userData.gender == 'male')) {
    ProfileWindow.changedFields++;
    ProfileWindow.data['profile_form_gender'] = gender;
  }
  // phonenumber
  phoneRegex.lastIndex = 0;
  if(!phoneRegex.test(phonenumber)) {
    profileSetBorder('#profileWindow-input-phone', false);
    phoneRegexValid = false;
  }
  // phonenumber changed
  if(phonenumber != userData.phone.replace(/\+/g, '')) {
    ProfileWindow.changedFields++;
    ProfileWindow.data['profile_form_phone'] = phonenumber;
  }
  else {
    ProfileWindow.phoneConfirmed = ProfileWindow.phoneWasConfirmed;
  }
  // phonenumber is confirmed
  if(ProfileWindow.phoneConfirmed) {
    $('#profileWindow-valid-good-phone').css('display', 'block');
    $('#profileWindow-valid-error-phone').css('display', 'none');
    $('#profileWindow-btn-phone').css('display', 'none');
  }
  else {
    //ProfileWindow.validForm = false;
    $('#profileWindow-valid-good-phone').css('display', 'none');
    $('#profileWindow-valid-error-phone').css('display', 'block');
    if(phoneRegexValid) {
      $('#profileWindow-btn-phone').css('display', 'block');
    }
    else {
      $('#profileWindow-btn-phone').css('display', 'none');
    }
  }
  // email
  emailRegex.lastIndex = 0;
  if(!emailRegex.test(email)) {
    profileSetBorder('#profileWindow-input-email', false);
    emailRegexValid = false;
  }
  // email changed
  if(email != userData.email) {
    ProfileWindow.changedFields++;
    ProfileWindow.data['profile_form_email'] = email;
  }
  else {
    ProfileWindow.emailConfirmed = ProfileWindow.emailWasConfirmed;
  }
  // email is confirmed
  if(ProfileWindow.emailConfirmed) {
    $('#profileWindow-valid-good-email').css('display', 'block');
    $('#profileWindow-valid-error-email').css('display', 'none');
    $('#profileWindow-btn-email').css('display', 'none');
  }
  else {
    //ProfileWindow.validForm = false;
    $('#profileWindow-valid-good-email').css('display', 'none');
    $('#profileWindow-valid-error-email').css('display', 'block');
    if(emailRegexValid) {
      $('#profileWindow-btn-email').css('display', 'block');
    }
    else {
      $('#profileWindow-btn-email').css('display', 'none');
    }
  }
  // form not changed
  if(ProfileWindow.changedFields == 0) {
    ProfileWindow.validForm = false;
  }
  // end
  if(ProfileWindow.validForm) {
    $('#profileWindow-btn-save').css('opacity', 1);
    $('#profileWindow-btn-save').css('cursor', 'pointer');
  }
  else {
    $('#profileWindow-btn-save').css('opacity', 0.5);
    $('#profileWindow-btn-save').css('cursor', 'default');
  }
}

function saveProfileWindow() {
  // check
  if(!ProfileWindow.validForm) {
    return;
  }
  // prepare
  ProfileWindow.data['profile_form'] = true;
  // send
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: ProfileWindow.data,
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        notification_add('line', '', 'Изменения сохранены', 7);
        setTimeout(function() {
          document.location.reload(true);
        }, 2000);
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else if(checkResponseCode('WRONG.')) {
        notification_add('error', 'Ошибка', 'Проверьте правильность заполненных вами данных', 7);
        console.log(response);
      }
      else if(checkResponseCode('PHONE_LIMIT.')) {
        notification_add('error', 'Ошибка', 'Указанный вами номер телефона уже занят', 5);
      }
      else if(checkResponseCode('EMAIL_LIMIT.')) {
        notification_add('error', 'Ошибка', 'Указанный вами адрес эл. почты уже занят', 5);
      }
      else {
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

function profileChangePasswordCheck() {

  ProfileWindow.passwordChange = true;

  profileSetBorder('#password-edit-profile-1', true);
  profileSetBorder('#password-edit-profile-2', true);
  profileSetBorder('#password-edit-profile-3', true);

  var oldPassword = $('#password-edit-profile-1').val();
  var newPassword = $('#password-edit-profile-2').val();
  var newPasswordCheck = $('#password-edit-profile-3').val();

  passwordRegex.lastIndex = 0;
  if(!passwordRegex.test(oldPassword)) {
    profileSetBorder('#password-edit-profile-1', false);
    ProfileWindow.passwordChange = false;
  }

  passwordRegex.lastIndex = 0;
  if(!passwordRegex.test(newPassword)) {
    profileSetBorder('#password-edit-profile-2', false);
    ProfileWindow.passwordChange = false;
  }

  if(newPassword != newPasswordCheck) {
    profileSetBorder('#password-edit-profile-3', false);
    ProfileWindow.passwordChange = false;
  }

  if(ProfileWindow.passwordChange) {
    $('#password-edit-btn').css('transition','0.15s all');
    setTimeout(function() {
      $('#password-edit-btn').css('opacity','1');
      $('#password-edit-btn').css('cursor','pointer');
    }, 10);
  }
  else {
    $('#password-edit-btn').css('opacity','0.2');
    $('#password-edit-btn').css('cursor','default');
    setTimeout(function() {
      $('#password-edit-btn').css('transition','9999999999s all');
    }, 150);
  }

}

function profileChangePasswordSend() {

  if(!ProfileWindow.passwordChange) {
      return;
  }

  // prepare
  var oldPassword = $('#password-edit-profile-1').val();
  var newPassword = $('#password-edit-profile-2').val();

  // send
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      change_password_f: true,
      change_password_f_old: oldPassword,
      change_password_f_new: newPassword
    },
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        notification_add('line', '', 'Пароль изменен', 5);
        close_window();
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else if(checkResponseCode('WRONG_PASSWORD.')) {
        notification_add('error', 'Ошибка', 'Неверный пароль', 7);
      }
      else if(checkResponseCode('WRONG.')) {
        notification_add('error', 'Ошибка', 'Проверьте правильность заполненных вами данных', 5);
      }
      else {
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });

}

function profileEmailSendCode(hide) {
  // hide resubmit icon
  if(typeof(hide) == 'undefined') {
    profileResubmitEmailCode(true);
  }
  // prepare
  var email = $('#profileWindow-input-email').val();
  // check
  emailRegex.lastIndex = 0;
  if(!emailRegex.test(email)) {
    close_window();
    return;
  }
  // send
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      pce_send: true,
      pce_email: email
    },
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        notification_add('line', '', 'Письмо отправлено', 7);
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else if(checkResponseCode('WRONG.')) {
        notification_add('error', 'Ошибка', 'Проверьте правильность заполненных вами данных', 5);
        close_window();
      }
      else if(checkResponseCode('TIME_LIMIT.')) {
        notification_add('warning', 'Предупреждение', 'Письмо уже отправлено', 5);
      }
      else {
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

function profileTestCode() {
  // get
  var code = $('#code-edit-profile').val().replace(/[-_]/g, '');
  // check
  if(code.length == 7) {
    profileCheckCodeReady = true;
  }
  else {
    profileCheckCodeReady = false;
  }
  // set
  if(profileCheckCodeReady) {
    $('#profile-email-code-btn').css('transition','0.15s all');
    setTimeout(function() {
      $('#profile-email-code-btn').css('opacity','1');
      $('#profile-email-code-btn').css('cursor','pointer');
    }, 10);
  }
  else {
    $('#profile-email-code-btn').css('opacity','0.2');
    $('#profile-email-code-btn').css('cursor','default');
    setTimeout(function() {
      $('#profile-email-code-btn').css('transition','9999999999s all');
    }, 150);
  }

}

function profileResubmitEmailCode(send) {
  if(typeof(send) == 'undefined') {
    profileEmailSendCode(true);
  }
  $("#profile-email-resubmit-icon").css('display', 'none');
  setTimeout(function() {
    $("#profile-email-resubmit-icon").css('display', 'block');
  }, 65000);
  // 185000
}

function profileCheckCode() {
  if(!profileCheckCodeReady) {
    return;
  }
  var code = $('#code-edit-profile').val().replace(/[-_]/g, '');
  // send
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      pce_check: true,
      pce_check_code: code
    },
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        notification_add('line', '', 'Почта подтверждена', 5);
        userData.email = $('#profileWindow-input-email').val();
        userData.email_verify = true;
        ProfileWindow.emailConfirmed = true;
        ProfileWindow.emailWasConfirmed = true;
        $('#profileWindow-valid-good-email').css('display', 'block');
        $('#profileWindow-valid-error-email').css('display', 'none');
        $('#profileWindow-btn-email').css('display', 'none');
        open_window('#profile-edit');
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else if(checkResponseCode('CODE.')) {
        notification_add('error', 'Ошибка', 'Проверьте правильность заполненных вами данных', 5);
      }
      else if(checkResponseCode('NOT_MATCH.')) {
        notification_add('error', 'Ошибка', 'Неверный код', 5);
      }
      else {
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

function updateProfileIcons() {
  if(ProfileIcon.onceOpened === true) {
    return;
  }
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      pi_list: true
    },
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        var responseText = response.substring(3, response.length);
        var responseData = JSON.parse(responseText);
        var output = '';
        for(var i = 0; i < responseData.length; i++) {
          var id = responseData[i].id;
          var path = responseData[i].path;
          output += '<img src="' + path + '" class="window-block-conteiner-right-img" onclick="selectProfileIcon(' + i + ');">\n';
        }
        $('#profile-icons-list').empty();
        $('#profile-icons-list').append(output);
        ProfileIcon.onceOpened = true;
        ProfileIcon.icons = responseData;
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

function resetProfileIcon() {
  ProfileIcon.icon = userData.icon;
  $('#profile-icons-current').attr('src', userData.icon);
  // hide save button
  $('#profile-icons-btn-save').css('opacity','0.5');
  $('#profile-icons-btn-save').css('cursor','default');
  setTimeout(function() {
    $('#profile-icons-btn-save').css('transition','9999999999s all');
  }, 150);
}

function selectProfileIcon(id) {
  //console.log('selected icon: ' + ProfileIcon.icons[id].path + ' is ' + ProfileIcon.icons[id].id);
  $('#profile-icons-current').attr('src', ProfileIcon.icons[id].path);
  ProfileIcon.icon = ProfileIcon.icons[id].path;
  if(ProfileIcon.icons[id].path != userData.icon) {
    // display save button
    $('#profile-icons-btn-save').css('transition','0.15s all');
    setTimeout(function() {
      $('#profile-icons-btn-save').css('opacity','1');
      $('#profile-icons-btn-save').css('cursor','pointer');
    }, 10);
  }
  else {
    // hide save button
    $('#profile-icons-btn-save').css('opacity','0.5');
    $('#profile-icons-btn-save').css('cursor','default');
    setTimeout(function() {
      $('#profile-icons-btn-save').css('transition','9999999999s all');
    }, 150);
  }
}

function saveProfileIcon() {
  // block
  if(ProfileIcon.icon == userData.icon) {
    return;
  }
  // define icon type
  var path = ProfileIcon.icon;
  if(path.substring(12, 18) == 'public') {
    // is profile icon
    type = 'PROFILE';
  }
  else if(path.substring(12).split('.')[0] == 'admin') {
    // is admin icon
    type = 'ADMIN';
  }
  else {
    // is default icon
    type = 'DEF_' + path.substring(12).split('.')[0];
  }
  // request
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      pi_set: true,
      pi_set_icon: type
    },
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        var responseText = response.substring(3, response.length);
        userData.icon = ProfileIcon.icon;
        close_window();
        setTimeout(function() {
          document.location.reload(true);
        }, 1000);
        notification_add('line', '', 'Изображение изменено', 5);
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        notification_add('error', 'Ошибка', 'Неизвестная ошибка', 5);
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

// icon upload
function uploadProfileIcon(draganddrop, file) {
  // get file
  var files;

  if(draganddrop){
    files = file;
  } else{
    files = document.getElementById('upload_file_profile_image').files;
  }
  if(typeof(files) == 'undefined') {
    return;
  }
  var data = new FormData();
  $.each(files, function(key, value) {
    data.append(key, value);
  });
  data.append('pi_upload', 1);
  // send file
  $.ajax({
    url: 'db_profile.php',
    type: 'POST',
    data: data,
    cache: false,
    processData: false,
    contentType: false,
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      clearInputTypeFile('upload_file_profile_image');
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.', response)) {
        // get dir
        var folder = response.substring(3, response.length);
        var path = folder + '/tmp_profile.jpg';
        // change icon
        $('#profile-icons-current').attr('src', path);
        ProfileIcon.icon = path;
        // display save button
        $('#profile-icons-btn-save').css('transition','0.15s all');
        setTimeout(function() {
          $('#profile-icons-btn-save').css('opacity','1');
          $('#profile-icons-btn-save').css('cursor','pointer');
        }, 10);
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else if(checkResponseCode('BUSY.', response)) {
        notification_add('line', '', 'Фотография загружается', 5);
        setTimeout(function() {
          uploadProfileIcon();
        }, 1000);
      }
      else if(checkResponseCode('LIMIT.', response)) {
        notification_add('error', 'Ошибка', 'Изображение должно быть размером не более 10 МБ', 5);
      }
      else if(checkResponseCode('MIME.', response)) {
        notification_add('error', 'Ошибка', 'Недопустимый формат', 5);
      }
      else {
        notification_add('error', 'Ошибка сервера', 'Неизвестая ошибка', 5);
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}


function removeProfileIcon() {
  selectProfileIcon(0);
}

// == tasks table ==============================================================

function tasksLoadList() {
  // send request
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      tasks_list: true
      //tasks_search: tasksFilterPres
    },
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        var responseText = response.substring(3, response.length);
        var responseData = JSON.parse(responseText);
        // sorting
        var sortmap = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
        function sortfunc(arg1, arg2) {
          var a = sortmap.indexOf(arg1.title);
          var b = sortmap.indexOf(arg2.title);
          return (a - b);
        }
        responseData.sort(sortfunc);
        // save to array
        tasksUpdateArray = responseData;
        tasksUpdateList();
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        notification_add('error', 'Ошибка', 'Неизвестная ошибка', 5);
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

function tasksUpdateList() {
  var responseData = tasksUpdateArray;
  // find today exception
  var haveTodayException = false;
  for(var i = 0; i < responseData.length; i++) {
    var elem = responseData[i];
    var exception = elem.exception && elem.today;
    if(exception) {
      haveTodayException = true;
      break;
    }
  }
  // clear
  $('#today-tasks-containter').empty();
  $('#regular-tasks-containter').empty();
  $('#exception-tasks-containter').empty();
  // append
  for(var i = 0; i < responseData.length; i++) {
    // encoding
    var daysEn = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
    var daysRu = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
    // parameters
    var elem = responseData[i];
    var title = elem.title;
    var date = elem.date;
    // have filter
    if(tasksFilterDate != 'none') {
      // check
      var date1arr = date.split('.');
      var date2arr = tasksFilterDate.split('-');
      if((date1arr[0] != date2arr[2]) || (date1arr[1] != date2arr[1]) || (date1arr[2] != date2arr[0])) {
        continue;
      }
    }
    var number = date.substring(0, 2);
    var exception = elem.exception;
    var today = elem.today && (haveTodayException == exception);
    var click = (exception) ? date : daysEn[daysRu.indexOf(title)];
    // output
    var output = '';
    output += '<div class="panel-news-block" onclick="tasksLoadFromServer(\'' + click + '\', \'' + title + '\');">\n';
    output += '<div class="panel-news-block-img-2" style="background-color: #6b5eae;" id="task-today-elem">' + number + '</div>\n';
    output += '<div class="panel-news-block-text">\n';
    output += '<div class="panel-msg-block-text-title">' + title + '</div>\n';
    output += '<div class="panel-msg-block-text-msg">' + date + '</div>\n';
    output += '</div>\n';
    output += '</div>\n';
    // add to html
    if(today) {
      // add to today
      $('#today-tasks-containter').append(output);
      $('#task-today-elem').css('background-color', '#fd397a');
    }
    // add to full list
    // is a regular day
    if(!exception) {
      $('#regular-tasks-containter').append(output);
    }
    // is a exception day
    else {
      $('#exception-tasks-containter').append(output);
    }
  }
}

function tasksSendToServer(taskTables, taskDay) {
  // prepare
  var taskDay;
  if(typeof(taskDay) == 'undefined') {
    taskDay = currentTaskDay;
  }
  // send
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      tasks_save: true,
      task_date: taskDay,
      task_tables: JSON.stringify(taskTables)
    },
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        var responseText = response.substring(3, response.length);
        tasksLoadList();
        notification_add('line', '', 'Расписание изменено', 5);
        taskFirstFlag = false;
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        notification_add('error', 'Ошибка', 'Неизвестная ошибка', 5);
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

function loader(type){
  var tmpBlock = $('.logo-loader');

  if(typeof type == 'undefined'){
    if(tmpBlock.css('visibility') != 'hidden'){
      return true;
    } else{
      return false;
    }
  } else{
    if(type == 'show'){
      tmpBlock.css({'opacity':'1','visibility':'visible','transform':'translate(0px, 0px)'})
      $('.menu').css('margin-bottom','57px')
    }
    if(type == 'hidden'){
      tmpBlock.css({'opacity':'0','visibility':'hidden','transform':'translate(0px, 100%)'})
      $('.menu').css('margin-bottom','0px')
    }
  }
}

function tasksLoadFromServer(date, title) {
  // open current and not in the first time
  if((date == currentTaskDay) && taskFirstFlag) {
    return;
  }
  // now, its opened
  taskFirstFlag = true;
  timetableElemEnable = true;
  // set title
  var title;
  if(typeof(title) == 'undefined') {
    title = 'Ошибка';
  }
  timetableWindow('timetable-main')
  $('#tasks-day-title').text(title);
  // set current table
  currentTaskDay = date;
  // request
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      tasks_load: true,
      task_date: date
    },
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        var responseText = response.substring(3, response.length);
        var responseData = JSON.parse(responseText);
        if(responseData[0][0].length == 0) {
          // empty
          timetable_parseTable(false);
          document.getElementById('1raRg-Pw12-fZ4R').checked = true;
        }
        else {
          timetable_parseTable(responseData);
          document.getElementById('1raRg-Pw12-fZ4R').checked = false;
        }

      }
      else if(checkResponseCode('EMPTY.')) {
        // create epmty
        timetable_parseTable(false);
        //timetable_parseTable([['', ['', '', '', '']]]);
        document.getElementById('1raRg-Pw12-fZ4R').checked = true;
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        notification_add('error', 'Ошибка', 'Неизвестная ошибка', 5);
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

function taskCreateException(date) {
  var now;
  if((typeof(date) != 'undefined') && date.match(/(\d{4})-(\d{2})-(\d{2})/g)) {
    now = new Date(date);
  }
  else {
    now = new Date();
  }
  timetableElemEnable = true;
  // timetableElemEnableF();
  currentTaskDay = now.toLocaleDateString();
  // define day of week
  var dayOfWeek = now.getDay() - 1;
  if(dayOfWeek < 0) dayOfWeek = 6;
  // define title
  var daysRu = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
  var title = daysRu[dayOfWeek];
  // change title
  $('#tasks-day-title').text(title);
  timetableWindow('timetable-main');
  // create new table
  timetable_parseTable([['', ['', '', '', '']]]);
}

function loader_text(){
    var tmpText = $('.loader-text').text();
    if(tmpText == 'Загрузка'){
      $('.loader-text').text('Загрузка.');
    }
    if(tmpText == 'Загрузка.'){
      $('.loader-text').text('Загрузка..');
    }
    if(tmpText == 'Загрузка..'){
      $('.loader-text').text('Загрузка...');
    }
    if(tmpText == 'Загрузка...'){
      $('.loader-text').text('Загрузка');
    }
  }

// experimental
/*
$(document).ready(function() {

  // run by default
  tasksFilterBySearch();

  $('#searchFilter223').on('input', function() {
    var val = $('#searchFilter223').val();
    if(val.length < 3) {
      tasksFilterPres = '';
      return;
    }
    var fieldRegex = /^([A-Za-zА-ЯЁа-яё0-9- .,\(\)]){3,64}$/gu;
    fieldRegex.lastIndex = 0;
    if(!fieldRegex.test(val)) {
      return;
    }
    // set current value
    tasksFilterPres = val;
    // time limit
    if((Date.now() - tasksFilterTimer) > 500) {
      tasksFilterBySearch();
    }
  });
});

var tasksFilterRunning = false;
var tasksFilterTimer = 0;
var tasksFilterPres = '';
var tasksFilterPrev = '';

function tasksFilterBySearch() {
  // check
  if(tasksFilterRunning) {
    return;
  }
  var now = Date.now();
  var diff = now - tasksFilterTimer;
  if(diff < 490) {
    return;
  }
  if(tasksFilterPrev == tasksFilterPres) {
    return;
  }
  // set
  tasksFilterPrev = tasksFilterPres;
  tasksFilterTimer = now;
  setTimeout(tasksFilterBySearch, 500);
  // send
  tasksLoadList();
}*/

// =============================================================================

function contactsReload() {
  // get server values
  ContactsForm.city = (typeof(siteData['contacts_city']) != 'undefined') ? siteData['contacts_city'] : '';
  ContactsForm.street = (typeof(siteData['contacts_street']) != 'undefined') ? siteData['contacts_street'] : '';
  ContactsForm.building = (typeof(siteData['contacts_building']) != 'undefined') ? siteData['contacts_building'] : '';
  ContactsForm.office = (typeof(siteData['contacts_office']) != 'undefined') ? siteData['contacts_office'] : '';
  ContactsForm.level = (typeof(siteData['contacts_level']) != 'undefined') ? siteData['contacts_level'] : '';
  ContactsForm.postcode = (typeof(siteData['contacts_postcode']) != 'undefined') ? siteData['contacts_postcode'] : '';
  ContactsForm.maplink = (typeof(siteData['contacts_maplink']) != 'undefined') ? siteData['contacts_maplink'] : '';
  ContactsForm.worktimeStart = (typeof(siteData['contacts_wt_start']) != 'undefined') ? siteData['contacts_wt_start'] : '';
  ContactsForm.worktimeEnd = (typeof(siteData['contacts_wt_end']) != 'undefined') ? siteData['contacts_wt_end'] : '';
  ContactsForm.rqLA = (typeof(siteData['contacts_LA']) != 'undefined') ? siteData['contacts_LA'] : '';
  ContactsForm.rqTIN = (typeof(siteData['contacts_TIN']) != 'undefined') ? siteData['contacts_TIN'] : '';
  ContactsForm.rqCOR = (typeof(siteData['contacts_COR']) != 'undefined') ? siteData['contacts_COR'] : '';
  ContactsForm.rqPSRN = (typeof(siteData['contacts_PSRN']) != 'undefined') ? siteData['contacts_PSRN'] : '';
  // borders
  contactsSetBorder('#pZuAS-ydHz-Gn5f', true);
  contactsSetBorder('#E6ali-SA35-51IT', true);
  contactsSetBorder('#FdIGC-8Laa-Xviv', true);
  contactsSetBorder('#P4g0p-EWtR-JOE2', true);
  contactsSetBorder('#AXkXv-v4yw-pLn2-nDra', true);
  contactsSetBorder('#bXfwf-sXuJ-eGFG', true);
  contactsSetBorder('#o9T9K-6emq-Isxp', true);
  contactsSetBorder('#4mLMI-Ez1v-NIfS', true);
  contactsSetBorder('#ssUfG-JzqB-jmfC', true);
  contactsSetBorder('#pVDu3-TEtR-30G7', true);
  contactsSetBorder('#UX1YZ-gpuF-VcVN', true);
  contactsSetBorder('#tfZJ1-jpyg-FBmK', true);
  contactsSetBorder('#9NE1J-LoY0-QNPC', true);
  // fill fields
  $('#pZuAS-ydHz-Gn5f').val(ContactsForm.city);
  $('#E6ali-SA35-51IT').val(ContactsForm.street);
  $('#FdIGC-8Laa-Xviv').val(ContactsForm.building);
  $('#P4g0p-EWtR-JOE2').val(ContactsForm.office);
  $('#AXkXv-v4yw-pLn2-nDra').val(ContactsForm.level);
  $('#bXfwf-sXuJ-eGFG').val(ContactsForm.postcode);
  $('#o9T9K-6emq-Isxp').val(ContactsForm.maplink);
  $('#4mLMI-Ez1v-NIfS').val(ContactsForm.worktimeStart);
  $('#ssUfG-JzqB-jmfC').val(ContactsForm.worktimeEnd);
  $('#pVDu3-TEtR-30G7').val(ContactsForm.rqLA);
  $('#UX1YZ-gpuF-VcVN').val(ContactsForm.rqTIN);
  $('#tfZJ1-jpyg-FBmK').val(ContactsForm.rqCOR);
  $('#9NE1J-LoY0-QNPC').val(ContactsForm.rqPSRN);
  // clear phonenumbers
  // create copy
  var tmpArrCopy = [];
  for(var i = 0; i < ContactsForm.phoneArray.length; i++) {
    tmpArrCopy[i] = {
      id: ContactsForm.phoneArray[i].id,
      val: ContactsForm.phoneArray[i].val
    };
  }
  // then remove
  for(var i = 0; i < tmpArrCopy.length; i++) {
    contactsRemove('#' + tmpArrCopy[i].id);
  }
  // parse phonenumbers
  if((typeof(siteData['contacts_phonenumbers']) != 'undefined') && (siteData['contacts_phonenumbers'] != '')) {
    var siteDataPhonenumbers = siteData['contacts_phonenumbers'].split(',');
    for(var i = 0; i < siteDataPhonenumbers.length; i++) {
      contactsAddTel('#GIy2Z-bsFK-WGoe', undefined, siteDataPhonenumbers[i]);
    }
  }
  // clear emails
  // create copy
  var tmpArrCopy = [];
  for(var i = 0; i < ContactsForm.emailArray.length; i++) {
    tmpArrCopy[i] = {
      id: ContactsForm.emailArray[i].id,
      val: ContactsForm.emailArray[i].val
    };
  }
  // then remove
  for(var i = 0; i < tmpArrCopy.length; i++) {
    contactsRemove('#' + tmpArrCopy[i].id);
  }
  // parse emails
  if((typeof(siteData['contacts_emails']) != 'undefined') && (siteData['contacts_emails'] != '')) {
    var siteDataEmails = siteData['contacts_emails'].split(',');
    for(var i = 0; i < siteDataEmails.length; i++) {
      contactsAddMail('#03nMm-r5tt-G1NJ', undefined, siteDataEmails[i]);
    }
  }
  // save to old arrays
  // copy phonenumbers
  ContactsForm.phoneArrayOld = [];
  for(var i = 0; i < ContactsForm.phoneArray.length; i++) {
    ContactsForm.phoneArrayOld.push({
      id: String(ContactsForm.phoneArray[i].id),
      val: String(ContactsForm.phoneArray[i].val)
    });
  }
  // copy emails
  ContactsForm.emailArrayOld = [];
  for(var i = 0; i < ContactsForm.emailArray.length; i++) {
    ContactsForm.emailArrayOld.push({
      id: String(ContactsForm.emailArray[i].id),
      val: String(ContactsForm.emailArray[i].val)
    });
  }
  ContactsForm.ready = false;
}

// change border-color of field
function contactsSetBorder(element, valid) {
  var element, valid;
  if(valid) {
    $(element).parent().css('border','');
  }
  else {
    $(element).parent().css('border','2px solid #b32424');
  }
}

function contactsCheck() {
  ContactsForm.ready = true;
  ContactsForm['formData'] = {};
  // set borders
  contactsSetBorder('#pZuAS-ydHz-Gn5f', true);
  contactsSetBorder('#E6ali-SA35-51IT', true);
  contactsSetBorder('#FdIGC-8Laa-Xviv', true);
  contactsSetBorder('#P4g0p-EWtR-JOE2', true);
  contactsSetBorder('#AXkXv-v4yw-pLn2-nDra', true);
  contactsSetBorder('#bXfwf-sXuJ-eGFG', true);
  contactsSetBorder('#o9T9K-6emq-Isxp', true);
  contactsSetBorder('#4mLMI-Ez1v-NIfS', true);
  contactsSetBorder('#ssUfG-JzqB-jmfC', true);
  contactsSetBorder('#pVDu3-TEtR-30G7', true);
  contactsSetBorder('#UX1YZ-gpuF-VcVN', true);
  contactsSetBorder('#tfZJ1-jpyg-FBmK', true);
  contactsSetBorder('#9NE1J-LoY0-QNPC', true);
  // get values
  var city = $('#pZuAS-ydHz-Gn5f').val();
  var street = $('#E6ali-SA35-51IT').val();
  var building = $('#FdIGC-8Laa-Xviv').val();
  var office = $('#P4g0p-EWtR-JOE2').val();
  var level = $('#AXkXv-v4yw-pLn2-nDra').val();
  var postcode = $('#bXfwf-sXuJ-eGFG').val();
  var maplink = $('#o9T9K-6emq-Isxp').val();
  var worktimeStart = $('#4mLMI-Ez1v-NIfS').val();
  var worktimeEnd = $('#ssUfG-JzqB-jmfC').val();
  var rqLA = $('#pVDu3-TEtR-30G7').val();
  var rqTIN = $('#UX1YZ-gpuF-VcVN').val();
  var rqCOR = $('#tfZJ1-jpyg-FBmK').val();
  var rqPSRN = $('#9NE1J-LoY0-QNPC').val();
  // change maplink
  if(maplink.indexOf('src="') >= 0) {
    maplink = maplink.replace(/.*?src=["\']|["\'].*/ig, '');
    $('#o9T9K-6emq-Isxp').val(maplink);
  }
  // check static values
  // location
  var locationRegex = /^([A-Za-zА-ЯЁа-яё0-9-.,\(\)\s]){1,100}$/gu;
  if(!city.match(locationRegex) && city != '') {
    contactsSetBorder('#pZuAS-ydHz-Gn5f', false);
    ContactsForm.ready = false;
  }
  else { if(city != ContactsForm.city) ContactsForm.formData['contacts_city'] = city; }
  //
  if(!street.match(locationRegex) && street != '') {
    contactsSetBorder('#E6ali-SA35-51IT', false);
    ContactsForm.ready = false;
  }
  else { if(street != ContactsForm.street) ContactsForm.formData['contacts_street'] = street; }
  //
  if(!building.match(locationRegex) && building != '') {
    contactsSetBorder('#FdIGC-8Laa-Xviv', false);
    ContactsForm.ready = false;
  }
  else { if(building != ContactsForm.building) ContactsForm.formData['contacts_building'] = building; }
  //
  if(!office.match(locationRegex) && office != '') {
    contactsSetBorder('#P4g0p-EWtR-JOE2', false);
    ContactsForm.ready = false;
  }
  else { if(office != ContactsForm.office) ContactsForm.formData['contacts_office'] = office; }
  //
  var levelRegex = /^\d{1,3}$/;
  if(!level.match(levelRegex) && level != '') {
    contactsSetBorder('#AXkXv-v4yw-pLn2-nDra', false);
    ContactsForm.ready = false;
  }
  else { if(level != ContactsForm.level) ContactsForm.formData['contacts_level'] = level; }
  //
  var postcodeRegex = /^\d{6}$/;
  if(!postcode.match(postcodeRegex) && postcode != '') {
    contactsSetBorder('#bXfwf-sXuJ-eGFG', false);
    ContactsForm.ready = false;
  }
  else { if(postcode != ContactsForm.postcode) ContactsForm.formData['contacts_postcode'] = postcode; }
  //
  var linkRegex = /^((http|https):\/\/)?(www\.)?([A-Za-zА-Яа-я0-9]{1}[A-Za-zА-Яа-я0-9\-]*\.?)*\.{1}[A-Za-zА-Яа-я0-9-]{2,8}(\/([\w#!:,.?+=&%@!\-\/])*)?$/g;
  if(!maplink.match(linkRegex) && maplink != '') {
    contactsSetBorder('#o9T9K-6emq-Isxp', false);
    ContactsForm.ready = false;
  }
  else { if(maplink != ContactsForm.maplink) ContactsForm.formData['contacts_maplink'] = maplink; }
  //
  if(!rqLA.match(locationRegex) && rqLA != '') {
    contactsSetBorder('#pVDu3-TEtR-30G7', false);
    ContactsForm.ready = false;
  }
  else { if(rqLA != ContactsForm.rqLA) ContactsForm.formData['contacts_LA'] = rqLA; }
  //
  var rqTINRegex = /^[\d+]{10,12}$/g;
  if(!rqTIN.match(rqTINRegex) && rqTIN != '') {
    contactsSetBorder('#UX1YZ-gpuF-VcVN', false);
    ContactsForm.ready = false;
  }
  else { if(rqTIN != ContactsForm.rqTIN) ContactsForm.formData['contacts_TIN'] = rqTIN; }
  //
  var rqCORRegex = /^([0-9]{9})?$/g;
  if(!rqCOR.match(rqCORRegex) && rqCOR != '') {
    contactsSetBorder('#tfZJ1-jpyg-FBmK', false);
    ContactsForm.ready = false;
  }
  else { if(rqCOR != ContactsForm.rqCOR) ContactsForm.formData['contacts_COR'] = rqCOR; }
  //
  var rqPSRNRegex = /^([0-9]{13})?$/g;
  if(!rqPSRN.match(rqPSRNRegex) && rqPSRN != '') {
    contactsSetBorder('#9NE1J-LoY0-QNPC', false);
    ContactsForm.ready = false;
  }
  else { if(rqPSRN != ContactsForm.rqPSRN) ContactsForm.formData['contacts_PSRN'] = rqPSRN; }
  // check time
  if(worktimeStart != ContactsForm.worktimeStart) ContactsForm.formData['contacts_wt_start'] = worktimeStart;
  if(worktimeEnd != ContactsForm.worktimeEnd) ContactsForm.formData['contacts_wt_end'] = worktimeEnd;
  // check dynamic values
  // phonenumbers
  var phoneRegex = new RegExp(/^(\+)?(\(\d{2,3}\) ?\d|\d)(([ \-]?\d)|( ?\(\d{2,3}\) ?)){5,12}\d$/);
  var phonenumbersKey = '';
  var phoneArrayOldMatches = 0;
  var phoneArrayMatchesMax = (ContactsForm.phoneArray.length > ContactsForm.phoneArrayOld.length) ? ContactsForm.phoneArray.length : ContactsForm.phoneArrayOld.length;
  for(var i = 0; i < ContactsForm.phoneArray.length; i++) {
    var id = '#' + ContactsForm.phoneArray[i].id;
    var val = $(id).val();
    ContactsForm.phoneArray[i].val = val;
    if(!val.match(phoneRegex)) {
      contactsSetBorder(id, false);
      ContactsForm.ready = false;
    }
    else {
      contactsSetBorder(id, true);
      if(phonenumbersKey != '') {
        phonenumbersKey = phonenumbersKey + ',';
      }
      phonenumbersKey += val;
    }
    // exists in phoneArrayOld
    for(let j = 0; j < ContactsForm.phoneArrayOld.length; j++) {
      if(val == ContactsForm.phoneArrayOld[j].val) {
        phoneArrayOldMatches++;
        break;
      }
    }
  }
  // add data
  if(phoneArrayMatchesMax != phoneArrayOldMatches) {
    ContactsForm.formData['contacts_phonenumbers'] = phonenumbersKey;
  }
  //emails
  var emailRegex = new RegExp(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/g);
  var emailsKey = '';
  var emailArrayOldMatches = 0;
  var emailArrayMatchesMax = (ContactsForm.emailArray.length > ContactsForm.emailArrayOld.length) ? ContactsForm.emailArray.length : ContactsForm.emailArrayOld.length;
  for(var i = 0; i < ContactsForm.emailArray.length; i++) {
    var id = '#' + ContactsForm.emailArray[i].id;
    var val = $(id).val();
    ContactsForm.emailArray[i].val = val;
    //if(!val.match(emailRegex) && val != '') {
    if(!val.match(emailRegex)) {
      contactsSetBorder(id, false);
      ContactsForm.ready = false;
    }
    else {
      contactsSetBorder(id, true);
      if(emailsKey != '') {
        emailsKey = emailsKey + ',';
      }
      emailsKey += val;
    }
    // exists in emailArrayOld
    for(let j = 0; j < ContactsForm.emailArrayOld.length; j++) {
      if(val == ContactsForm.emailArrayOld[j].val) {
        emailArrayOldMatches++;
        break;
      }
    }
  }
  // add data
  if(emailArrayMatchesMax != emailArrayOldMatches) {
    ContactsForm.formData['contacts_emails'] = emailsKey;
  }
}

function contactsSave() {
  // prepare
  if(!ContactsForm.ready) {
    return;
  }
  if((typeof(ContactsForm.formData) == 'undefined') || (Object.keys(ContactsForm.formData).length == 0)) {
    return;
  }

  // send
  ContactsForm.formData['contacts_form'] = true;
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: ContactsForm.formData,
    beforeSend: function(){
      loader('show');
    },
    complete: function(){
      loader('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        var responseText = response.substring(3, response.length);
        // save to siteData
        for(var key in ContactsForm.formData) {
          if(key != 'contacts_form') {
            siteData[key] = ContactsForm.formData[key];
          }
        }
        // save old values
        ContactsForm.city = $('#pZuAS-ydHz-Gn5f').val();
        ContactsForm.street = $('#E6ali-SA35-51IT').val();
        ContactsForm.building = $('#FdIGC-8Laa-Xviv').val();
        ContactsForm.office = $('#P4g0p-EWtR-JOE2').val();
        ContactsForm.level = $('#AXkXv-v4yw-pLn2-nDra').val();
        ContactsForm.postcode = $('#bXfwf-sXuJ-eGFG').val();
        ContactsForm.maplink = $('#o9T9K-6emq-Isxp').val();
        ContactsForm.worktimeStart = $('#4mLMI-Ez1v-NIfS').val();
        ContactsForm.worktimeEnd = $('#ssUfG-JzqB-jmfC').val();
        ContactsForm.rqLA = $('#pVDu3-TEtR-30G7').val();
        ContactsForm.rqTIN = $('#UX1YZ-gpuF-VcVN').val();
        ContactsForm.rqCOR = $('#tfZJ1-jpyg-FBmK').val();
        ContactsForm.rqPSRN = $('#9NE1J-LoY0-QNPC').val();
        // copy phonenumbers
        ContactsForm.phoneArrayOld = [];
        for(var i = 0; i < ContactsForm.phoneArray.length; i++) {
          ContactsForm.phoneArrayOld.push({
            id: String(ContactsForm.phoneArray[i].id),
            val: String(ContactsForm.phoneArray[i].val)
          });
        }
        // copy emails
        ContactsForm.emailArrayOld = [];
        for(var i = 0; i < ContactsForm.emailArray.length; i++) {
          ContactsForm.emailArrayOld.push({
            id: String(ContactsForm.emailArray[i].id),
            val: String(ContactsForm.emailArray[i].val)
          });
        }
        // clear form data
        ContactsForm['formData'] = {};
        ContactsForm.ready = false;
        // end
        notification_add('line','','Данные сохранены', 5);
        close_window();
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        notification_add('error', 'Ошибка', 'Неизвестная ошибка', 5);
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка, мы уже работаем над её исправлением', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

jQuery.preloadImages = function(){
  for(var i = 0; i < arguments.length; i++)
  {
   jQuery("<img>").attr("src", arguments[ i ]);
  }
 };

for(let i = 0; i < iconsArray.length; i++){
  if(development_state){
    arrayIconsDev.push(iconsArray[i])
  }
  $.preloadImages(iconsArray[i]);
}
if(development_state){
  console.log('Загруженные файлы:');
  console.table(arrayIconsDev);
}


function contactsAddTel(a, id, fieldValue) {
  // limit
  if((ContactsForm.emailArray.length + ContactsForm.phoneArray.length) == 6) {
    return;
  }
  //
  var tmpSpan = $(a);
  var tmpStringGenerator;
  if(typeof(id) != 'undefined') {
    tmpStringGenerator = id;
  }
  else {
    tmpStringGenerator = stringGenerator(15, 5);
  }
  if(typeof(fieldValue) == 'undefined') {
    fieldValue = '';
  }
  var tmpString = "<div class='input-login' style='border: 0px solid var(--border-color); padding: 0px; margin-bottom: 0px; opacity: 0; visibility: hidden; height: 0px; margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'><input value='" + fieldValue + "' required='required' type='tel' id='" + tmpStringGenerator + "'><label class='placeholder' for='" + tmpStringGenerator + "'>Телефон</label><span class='input-login-delete icon-plus' title='Удалить' onclick=\"contactsRemove('#" + tmpStringGenerator + "'); contactsCheck();\"></span></div>";
  tmpSpan.append(tmpString);
  $('#' + tmpStringGenerator).bind('input', contactsCheck);
  setTimeout(function(){
    $('#' + tmpStringGenerator).parent().css({'border':'','padding':'5px','height':'22px','margin-bottom':'15px'})
    setTimeout(function(){
      $('#' + tmpStringGenerator).parent().css({'opacity':'1','visibility':'visible','height':'auto'})
      $('#' + tmpStringGenerator).focus()
    }, 250)
  }, 1);
  ContactsForm.phoneArray[ContactsForm.phoneArray.length] = {
    id: tmpStringGenerator,
    val: fieldValue
  };

  $('input[type="tel"]').inputmask("+7 (999) 999-99-99");
}

function contactsAddMail(a, id, fieldValue) {
  // limit
  if((ContactsForm.emailArray.length + ContactsForm.phoneArray.length) == 6) {
    return;
  }
  //
  var tmpSpan = $(a);
  var tmpStringGenerator;
  if(typeof(id) != 'undefined') {
    tmpStringGenerator = id;
  }
  else {
    tmpStringGenerator = stringGenerator(15, 5);
  }
  if(typeof(fieldValue) == 'undefined') {
    fieldValue = '';
  }
  var tmpString = "<div class='input-login' style='border: 0px solid var(--border-color); padding: 0px; margin-bottom: 0px; opacity: 0; visibility: hidden; height: 0px; margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'><input value='" + fieldValue + "' required='required' type='mail' id='" + tmpStringGenerator + "'><label class='placeholder' for='" + tmpStringGenerator + "'>Почта</label><span class='input-login-delete icon-plus' title='Удалить' onclick=\"contactsRemove('#" + tmpStringGenerator + "'); contactsCheck();\"></span></div>";
  tmpSpan.append(tmpString);
  $('#' + tmpStringGenerator).bind('input', contactsCheck);
  setTimeout(function(){
    $('#' + tmpStringGenerator).parent().css({'border':'2px solid var(--border-color)','padding':'5px','height':'22px','margin-bottom':'15px'})
    setTimeout(function(){
      $('#' + tmpStringGenerator).parent().css({'border':'','opacity':'1','visibility':'visible','height':'auto'})
      $('#' + tmpStringGenerator).focus()
    }, 250)
  }, 1);
  ContactsForm.emailArray[ContactsForm.emailArray.length] = {
    id: tmpStringGenerator,
    val: fieldValue
  };

  $('input[type="mail"]').inputmask({
    mask: "*{1,20}[.*{1,20}][.*{1,20}][.*{1,20}]@*{1,20}[.*{2,6}][.*{1,2}]",
    greedy: false,
    onBeforePaste: function (pastedValue, opts) {
      pastedValue = pastedValue.toLowerCase();
      return pastedValue.replace("mailto:", "");
    },
    definitions: {
      '*': {
        validator: "[0-9A-Za-z!#$%&'*+/=?^_`{|}~\-]",
        cardinality: 1,
        casing: "lower"
      }
    }
  });
}

function contactsRemove(a, id) {
  var tmpBlock = $(a).parent();
  var id;
  // find id
  if(typeof(id) == 'undefined') {
    var needle = a.substring(1);
    // indexOf phoneArray
    id = -1;
    for(var i = 0; i < ContactsForm.phoneArray.length; i++) {
      if(ContactsForm.phoneArray[i].id == needle) {
        // remove from phoneArray
        ContactsForm.phoneArray.splice(i, 1);
        break;
      }
    }
    // indexOf emailArray
    if(id < 0) {
      for(var i = 0; i < ContactsForm.emailArray.length; i++) {
        if(ContactsForm.emailArray[i].id == needle) {
          // remove from emailArray
          ContactsForm.emailArray.splice(i, 1);
          break;
        }
      }
    }
  }
  // remove event
  tmpBlock.unbind('input');
  // remove dom
  tmpBlock.css({'opacity':'0','visibility':'hidden'});
  setTimeout(function() {
    tmpBlock.css({'border':'0px solid var(--border-color)','padding':'0px','margin-bottom':'0px','height':'0px'});
    setTimeout(function() {
      tmpBlock.remove();
    }, 300);
  }, 250);
}

function contactsUpdateCardBlock() {
  if((typeof(siteData['contacts_card']) != 'undefined') && (siteData['contacts_card'] != '')) {
    // hide block 1
    $('#slip-cf1-block').css('display', 'none');
    // show block 2
    $('#slip-cf2-block').css('display', 'block');
    // add filename
    var f = siteData['contacts_card'];
    var filename = f.substring(f.lastIndexOf('/') + 1, f.length);
    $('#slip-cf2-file').text(filename);
  }
  else {
    // show block 1
    $('#slip-cf1-block').css('display', 'block');
    // hide block 2
    $('#slip-cf2-block').css('display', 'none');
  }
}

$(document).ready(function() {
  // file exists
  contactsUpdateCardBlock();
  // choose file
  $('#bi7bn-9xyY-fVEB').on('change', function() {
    contactsUploadCard();
  });
});

function contactsUploadCard(drag, file) {
  // get file
  var files;
  var filename;
  if(drag){
    files = [file];
    filename = file.name;
  } else{
    files = document.getElementById('bi7bn-9xyY-fVEB').files;
  }

  if(typeof(files) == 'undefined' || files.length == 0) {
    return;
  }

  var data = new FormData();
  $.each(files, function(key, value) {
    data.append(key, value);
    filename = value.name;
    return;
  });
  data.append('contacts_card_upload', 1);
  // send file
  $.ajax({
    url: 'db_profile.php',
    type: 'POST',
    data: data,
    cache: false,
    processData: false,
    contentType: false,
    beforeSend: function() {
      loader('show');
    },
    complete: function() {
      loader('hidden');
      clearInputTypeFile('bi7bn-9xyY-fVEB');
    },
    success: function(response) {
      clearInputTypeFile('upload_file_profile_image');
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.', response)) {
        notification_add('line', '', 'Карточка обновлена', 5);
        siteData['contacts_card'] = filename;
        contactsUpdateCardBlock();
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else if(checkResponseCode('LIMIT.', response)) {
        notification_add('error', 'Ошибка', 'Размер файла не должен превышать 20 МБ', 5);
      }
      else if(checkResponseCode('MIME.', response)) {
        notification_add('error', 'Ошибка', 'Недопустимый формат', 5);
      }
      else {
        notification_add('error', 'Ошибка сервера', 'Неизвестая ошибка', 5);
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

function contactsRemoveCard() {
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      contacts_card_remove: true
    },
    beforeSend: function() {
      loader('show');
    },
    complete: function() {
      loader('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        notification_add('line', '', 'Карточка удалена', 5);
        siteData['contacts_card'] = undefined;
        contactsUpdateCardBlock();
      }
      else if(checkResponseCode('ERROR.')) {
        notification_add('error', 'Ошибка', 'Неизвестная ошибка', 5);
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        notification_add('error', 'Ошибка', 'Неизвестная ошибка', 5);
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

function sendAPMark() {
  if(APMark.my < 1 || APMark.my > 5) return;
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      ap_mark: APMark.my
    },
    beforeSend: function() {
      loader('show');
    },
    complete: function() {
      loader('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        notification_add('line', '', 'Спасибо, нам важно Ваше мнение!', 5);
        close_window();
      }
      else if(checkResponseCode('ERROR.')) {
        notification_add('error', 'Ошибка', 'Неизвестная ошибка', 5);
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        notification_add('error', 'Ошибка', 'Неизвестная ошибка', 5);
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

var APMark = {
  radio: [
    'YnL7K-XL0A-L6i2',
    'iZZZO-FTxR-CvWn',
    'W2EWE-gFUU-pjAa',
    'aORFs-gXik-3yoa',
    'RJZyX-TsPZ-Oy4E'
  ],
  my: 0,
  av: 0,
  n: 0,
  s: 0
};

function updateAPMark(my, av) {
  // if new
  if(APMark.my == 0) {
    APMark.n++;
    APMark.s += my;
  }
  else {
    // changed
    if(my != APMark.my) {
      APMark.s = APMark.s - APMark.my + my;
    }
  }
  if(typeof(my) != 'undefined') {
    APMark.my = my;
    $('#apMarkMy').text('Оценка ' + my + ' из 5');
    $('#' + APMark.radio[my - 1]).prop('checked', true);
  }
  if(typeof(av) != 'undefined') {
    APMark.av = av;
  }
  else {
    APMark.av = (APMark.s / APMark.n).toFixed(2);
  }
  $('#apMarkAverage').text('Средний балл ' + APMark.av);
}

function getAPMark() {
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      ap_mark_get: true
    },
    beforeSend: function() {
      loader('show');
    },
    complete: function() {
      loader('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        var data = response.split('.');
        APMark.my = Number(data[1]);
        APMark.n = Number(data[2]);
        APMark.s = Number(data[3]);
        APMark.av = (APMark.n > 0) ? ((APMark.s / APMark.n).toFixed(2)) : 0;
        $('#apMarkMy').text('Оценка ' + APMark.my + ' из 5');
        $('#' + APMark.radio[APMark.my - 1]).prop('checked', true);
        $('#apMarkAverage').text('Средний балл ' + APMark.av);
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        console.log(response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка', 5);
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

$(document).ready(function() {

  getAPMark();
  $('#YnL7K-XL0A-L6i2').click(function() { updateAPMark(1); });
  $('#iZZZO-FTxR-CvWn').click(function() { updateAPMark(2); });
  $('#W2EWE-gFUU-pjAa').click(function() { updateAPMark(3); });
  $('#aORFs-gXik-3yoa').click(function() { updateAPMark(4); });
  $('#RJZyX-TsPZ-Oy4E').click(function() { updateAPMark(5); });

  mainStatisticsInit();

});

setInterval(function(){
    mainStatisticsInit(true);
  }, timeUpdateCharts)

// main stastics
var gradualIncreaseVal1 = 0;
var gradualIncreaseVal2 = 0;
var gradualIncreasePer1 = 0;
var gradualIncreasePer2 = 0;
function mainStatisticsInit(a, b) {
  if(a == undefined){
    a = false;
  }
  if(b == undefined){
    b = false;
  }
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      main_statistics: true
    },
    beforeSend: function(){
      if(a){
        loader('show');
      }
    },
    complete: function(){
      if(a){
        loader('hidden');
      }
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        var responseText = response.substring(3, response.length);
        var data = JSON.parse(responseText);
        //console.log(data);
        // === average scrolling value =========================================
        // scrolling percent value
        $('.panel-conteiner-width-small4-text-count').text(data.scroll.today + '%');
        // scrolling percent bar
        $('.panel-conteiner-width-small4-text-progressbar-status').css('width', data.scroll.today + '%');
        // difference
        var scrollingMessage = '';
        var scrollingPercent = Math.floor((data.scroll.today / data.scroll.yesterday) * 100 - 100);
        if(scrollingPercent > 0) {
          if(scrollingPercent > 2500) {
            scrollingMessage = 'Это больше, чем вчера на более 2500%';
          }
          else {
            scrollingMessage = 'Это больше на '+scrollingPercent+'%, чем вчера';
          }
        }
        else if(scrollingPercent < 0) {
          if(scrollingPercent < -2500) {
            scrollingMessage = 'Это меньше, чем вчера на менее чем -2500%';
          }
          else {
            scrollingMessage = 'Это меньше на '+String(Math.abs(scrollingPercent))+'%, чем вчера';
          }
        }
        else scrollingMessage = 'Это так же, как и вчера';
        $('#main-stat-scrolling-field-1').html(scrollingMessage);

        // === average view time ===============================================
        function prepareTime(val) {
          val = Number(val);
          if(val < 0) { val = 0; }
          if(val > 59) { val = 59; }
          if(val < 10) { val = String('0' + val); }
          else { val = String(val); }
          return val;
        }
        var hours = Math.floor(data.viewtime.all / 60 / 60);
        var minutes = Math.floor(data.viewtime.all / 60) - (hours * 60);
        var seconds = data.viewtime.all % 60;
        var formatted = prepareTime(hours) + ':' + prepareTime(minutes) + ':' + prepareTime(seconds);
        $('.panel-conteiner-width-small-main-elem-block1').text(formatted);
        // average view time percent
        var percentMessage = '';
        var percentMessageMin = '';
        var viewPercent = Math.floor((data.viewtime.yesterday / data.viewtime.all) * 100 - 100);
        if(viewPercent > 0) {
          if(viewPercent > 2500) {
            percentMessage = 'Прирост более 2500%';
            percentMessageMin = '<span>Больше</span> 2500%';
          }
          else {
            percentMessage = 'Больше на '+viewPercent+'%, чем за все время';
            percentMessageMin = '<span>Больше</span> на '+viewPercent+'%';
          }
        }
        else if(viewPercent < 0) {
          if(viewPercent < -2500) {
            percentMessage = 'Спад более 2500%';
            percentMessageMin = '<span>Меньше</span> -2500%';
          }
          else {
            percentMessage = 'Меньше на '+String(Math.abs(viewPercent))+'%, чем за все время';
            percentMessageMin = '<span>Меньше</span> на '+String(Math.abs(viewPercent))+'%';
          }
        }
        else percentMessage = 'Это так же, как и за все время';
        $('#main-stat-field-1').prop('title', percentMessage);
        $('#main-stat-field-1').html(percentMessageMin);

        // === big chart =======================================================
        var viewsPerDay = 0;
        // prepare data
        var pre_table = [];
        var d = new Date();
        var str = String(d.getFullYear() + '-' + String(d.getMonth() + 1) + '-' + d.getDate() + ' ' + d.getHours() + ':00:00');
        var currentDateRounded = new Date(str);
        for(var h = 0; h < 48; h++) {
          var currentHourRounded = currentDateRounded.getTime() - (3600000 * h);
          pre_table[currentHourRounded] = [0, 0, []];
        }
        // unical users list
        var hashes1 = data.hashes1;
        var hashes2 = data.hashes2;
        /*var hashes = [];
        for(var i = 0; i < data.chart1.length; i++) {
          if(!hashes.includes(data.chart1[i].hash)) {
            hashes[hashes.length] = data.chart1[i].hash;
          }
        }*/
        // prepare
        for(var i = 0; i < data.chart1.length; i++) {
          var rowHourRounded = new Date(String(data.chart1[i].date.split(':')[0] + ':00:00')).getTime();
          if(typeof(pre_table[rowHourRounded]) != 'undefined') {
            var c1 = pre_table[rowHourRounded][0] + 1;
            var c2 = pre_table[rowHourRounded][1];
            var h = pre_table[rowHourRounded][2];
            // unical user founded
            if(!hashes1.includes(data.chart1[i].hash) && hashes2.includes(data.chart1[i].hash)) {
              hashes1[hashes1.length] = data.chart1[i].hash;
              c2++;
            }
            //console.log(hashes);
            // every day
            /*if(!h.includes(data.chart1[i].hash)) {
              h[h.length] = data.chart1[i].hash;
              c2++;
            }*/
            // unical
            //if(!hashes.includes(data.chart1[i].hash)) {
              //hashes[hashes.length] = data.chart1[i].hash;
              //h[h.length] = data.chart1[i].hash;
              //c2++;
            //}
            //console.log(hashes);
            pre_table[rowHourRounded] = [c1, c2, h];
            // views per day
            if(rowHourRounded > (currentDateRounded.getTime() - (3600000 * 24))) {
              viewsPerDay++;
            }
          }
        }
        // output
        var s1 = [];
        var s2 = [];
        for(key in pre_table) {
          s1[s1.length] = [Number(key), pre_table[key][0]];
          s2[s2.length] = [Number(key), pre_table[key][1]];
        }
        chart1_params.series = [{
          name: 'Все пользователи',
          data: s1
        },
        {
          name: 'Уникальные пользователи',
          data: s2
        }];
        // views total
        var viewsTotal = data.views.total;
        if(viewsTotal >= 1000) {
          viewsTotal = Number(viewsTotal / 1000).toFixed(1) + 'k+';
        }
        $('#main-stat-chart-big-f2').text(viewsTotal);
        // views per day
        if(viewsPerDay >= 1000) {
          viewsPerDay = Number(viewsPerDay / 1000).toFixed(1) + 'k+';
        }
        $('#main-stat-chart-big-f1').text(viewsPerDay);

        // === small chart =====================================================
        var s1data = [];
        var s1name = [];
        var s2data = [];
        var s2name = [];
        var monthsMap1 = ['января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
        for(var i = 0; i < data.chart2.days.length; i++) {
          var timestamp = data.chart2.days[i].day;
          var count = data.chart2.days[i].count;
          var date = new Date(timestamp * 1000);
          var month = date.getDate() + ' ' + monthsMap1[date.getMonth()];
          s1data[i] = count;
          s1name[i] = month;
        }
        var monthsMap2 = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'];
        for(var i = 0; i < data.chart2.months.length; i++) {
          var timestamp = data.chart2.months[i].month;
          var count = data.chart2.months[i].count;
          var date = new Date(timestamp * 1000);
          var month = monthsMap2[date.getMonth()];
          s2data[i] = count;
          s2name[i] = month;
        }
        //console.log(data.chart2);
        chart3_params.series = [{data: s1data, name: s1name}];
        chart3_1_params.series = [{data: s2data, name: s2name}];
        // views by month
        gradualIncreaseVal1 = data.views.month.pres;
        gradualIncreasePer1 = (data.views.month.pres / data.views.month.prev) * 100 - 100;
        if(gradualIncreasePer1 < -1000) gradualIncreasePer1 = -1000;
        if(gradualIncreasePer1 > 1000) gradualIncreasePer1 = 1000;
        gradualIncreasePer1 = gradualIncreasePer1.toFixed(2);
        // views by year
        gradualIncreaseVal2 = data.views.year.pres;
        gradualIncreasePer2 = (data.views.year.pres / data.views.year.prev) * 100 - 100;
        if(gradualIncreasePer2 < -1000) gradualIncreasePer2 = -1000;
        if(gradualIncreasePer2 > 1000) gradualIncreasePer2 = 1000;
        gradualIncreasePer2 = gradualIncreasePer2.toFixed(2);

        updateChartsNew(theme_chart,b);

        gradual_increase_in_number(gradualIncreaseVal1, 1000, '#panel-conteiner-width-small3-count-id');
        gradual_increase_in_percent(gradualIncreasePer1, 1000, '.panel-conteiner-width-small3-count-percent');

      }
      else {
        console.log(response);
      }
    },
    error: function(jqXHR, status) {
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

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
