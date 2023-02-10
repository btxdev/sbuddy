var StatusWindow = false,
    NotificationBlockStandart,
    widthNoti;

function open_window(){
  StatusWindow = true;
  $('.main-search-none').css('display','block')
  setTimeout(function(){
    if(window.innerWidth >= 780){
      $('.main-search-none').css('top','0px')
    } else{
      $('.main-search-none').css('top','0px')
    }
    $('.edge1').css('filter','brightness(0.5) opacity(0.5)')
    $('.edge1').css('z-index','-999')
    $('.main-search-none').css('opacity','1')
  }, 1)
}

function close_window(a){
  StatusWindow = false;
  $(a).parent().css('top','150vh')
  $(a).parent().css('opacity','0')
  $('.edge1').css('filter','brightness(1) opacity(1)')
  $('.edge1').css('z-index','0')
  $('#user-select').css('z-index','-15')
  setTimeout(function(){
    $(a).parent().css('display','none')
  }, 500)
}
function redirect(a){
  window.location.href = a;
}

function close_noti(a){
  $(a).parent().css('height','0px')
  $(a).parent().css('opacity','0')
  $(a).parent().css('transform','translate(0px, 20px)')
  setTimeout(function(){
    $(a).parent().remove()
  },250)
}

function msg_to_send(){
  var name   =   $('#name').val(),
      tel    =   $('#tel').val(),
      email  =   $('#email').val(),
      text   =   $('#text').val(),
      date   =   new Date();
      date   =   date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds() + " " + date.getDate() + "." + date.getMonth() + "." + date.getFullYear();

  if(name.length > 1 && tel.length > 8 && email.length > 4){
    if(name.match(/^[A-zА-яЁё ]+$/)){
      if(tel.match(/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/)){
        if(email.match(/^\w.+@[a-zA-Z_0-9]+?\.[a-zA-Z]{2,5}$/gm)){
          console.log('true')
          if(text.length > 5 && text.length < 9999){
            // все хорошо, отправляем запрос
            $('.main-search-none-preloader').css('display','block')
            setTimeout(function(){
              $('.main-search-none-preloader').css('opacity','1')
            },1)

            $.ajax({
              type: "POST",
              url: "php/support.mail.php",
              data: {name: name, tel: tel, email: email, text: text, date: date},
              error: function (response) {
                var id = str_rand(10);
                var NotificationBlockStandart1 = NotificationBlockStandart.replace("$system_@id@_js$", id)
                $('.notification').append(NotificationBlockStandart1)
                $('#' + id).css('display','block')
                $('#' + id).html($('#' + id).html().replace("$system_@text@_js$", "Ошибка сервера!"))
                setTimeout(function(){
                  $('#' + id).css('height','39px')
                  $('#' + id).css('opacity','1')
                  $('#' + id).css('transform','translate(0px, 0px)')
                },1)
                $('.main-search-none-preloader').css('opacity','0')
                setTimeout(function(){
                  $('.main-search-none-preloader').css('display','none')
                },250)
              },
              success: function(response) {
                var id = str_rand(10);
                var NotificationBlockStandart1 = NotificationBlockStandart.replace("$system_@id@_js$", id)
                $('.notification').append(NotificationBlockStandart1)
                $('#' + id).css('display','block')
                $('#' + id).html($('#' + id).html().replace("$system_@text@_js$", response))
                setTimeout(function(){
                  $('#' + id).css('height','39px')
                  $('#' + id).css('opacity','1')
                  $('#' + id).css('transform','translate(0px, 0px)')
                },1)
                $('.main-search-none-preloader').css('opacity','0')
                setTimeout(function(){
                  $('.main-search-none-preloader').css('display','none')
                },250)
              },
            }).done();

          } else{
            // ошибка в тексте
            var id = str_rand(10);
            var NotificationBlockStandart1 = NotificationBlockStandart.replace("$system_@id@_js$", id)
            $('.notification').append(NotificationBlockStandart1)
            $('#' + id).css('display','block')
            $('#' + id).html($('#' + id).html().replace("$system_@text@_js$", "Сообщение от 6 до 9999 символов!"))
            setTimeout(function(){
              $('#' + id).css('height','39px')
              $('#' + id).css('opacity','1')
              $('#' + id).css('transform','translate(0px, 0px)')
            },1)
            $('.main-search-none-preloader').css('opacity','0')
            setTimeout(function(){
              $('.main-search-none-preloader').css('display','none')
            },250)
          }
        } else{
          // ошибка в почте
          var id = str_rand(10);
          var NotificationBlockStandart1 = NotificationBlockStandart.replace("$system_@id@_js$", id)
          $('.notification').append(NotificationBlockStandart1)
          $('#' + id).css('display','block')
          $('#' + id).html($('#' + id).html().replace("$system_@text@_js$", "Неверно указана почта!"))
          setTimeout(function(){
            $('#' + id).css('height','39px')
            $('#' + id).css('opacity','1')
            $('#' + id).css('transform','translate(0px, 0px)')
          },1)
          $('.main-search-none-preloader').css('opacity','0')
          setTimeout(function(){
            $('.main-search-none-preloader').css('display','none')
          },250)
        }
      } else{
        // ошибка в телефоне
        var id = str_rand(10);
        var NotificationBlockStandart1 = NotificationBlockStandart.replace("$system_@id@_js$", id)
        $('.notification').append(NotificationBlockStandart1)
        $('#' + id).css('display','block')
        $('#' + id).html($('#' + id).html().replace("$system_@text@_js$", "Неверно указан номер телефона!"))
        setTimeout(function(){
          $('#' + id).css('height','39px')
          $('#' + id).css('opacity','1')
          $('#' + id).css('transform','translate(0px, 0px)')
        },1)
        $('.main-search-none-preloader').css('opacity','0')
        setTimeout(function(){
          $('.main-search-none-preloader').css('display','none')
        },250)
      }
    } else{
      // ошибка в имени
      var id = str_rand(10);
      var NotificationBlockStandart1 = NotificationBlockStandart.replace("$system_@id@_js$", id)
      $('.notification').append(NotificationBlockStandart1)
      $('#' + id).css('display','block')
      $('#' + id).html($('#' + id).html().replace("$system_@text@_js$", "Ошибка в имени!"))
      setTimeout(function(){
        $('#' + id).css('height','39px')
        $('#' + id).css('opacity','1')
        $('#' + id).css('transform','translate(0px, 0px)')
      },1)
      $('.main-search-none-preloader').css('opacity','0')
      setTimeout(function(){
        $('.main-search-none-preloader').css('display','none')
      },250)
    }
  } else{
      // мало знаков в имени, телефоне, почте

      var id = str_rand(10);
      var NotificationBlockStandart1 = NotificationBlockStandart.replace("$system_@id@_js$", id)
      $('.notification').append(NotificationBlockStandart1)
      $('#' + id).css('display','block')
      $('#' + id).html($('#' + id).html().replace("$system_@text@_js$", "Неверно заполнены поля!"))
      setTimeout(function(){
        $('#' + id).css('height','39px')
        $('#' + id).css('opacity','1')
        $('#' + id).css('transform','translate(0px, 0px)')
      },1)
      $('.main-search-none-preloader').css('opacity','0')
      setTimeout(function(){
        $('.main-search-none-preloader').css('display','none')
      },250)
  }
}

function str_rand(a) {
  var result       = '';
  var words        = '0123456789_qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
  var max_position = words.length - 1;

  for( i = 0; i < a; ++i ) {
    position = Math.floor ( Math.random() * max_position );
    result = result + words.substring(position, position + 1);
  }
  return result;
}

$(document).ready(function(e){
  $('input[type="tel"]').mask("+7 (999) 99-99-999")
  if(window.innerWidth <= 780){
    widthNoti = 'width: calc(100vw - 105px);'
  } else{
    widthNoti = 'max-width: 320px;'
  }
  NotificationBlockStandart = $('.notification-block-standart').html()
  NotificationBlockStandart = "<div class='notification-block-standart' style='" + widthNoti + "' id='$system_@id@_js$'>" + NotificationBlockStandart + "</div>"
  $('.notification').empty()
   var left = 0,
   top = 0,
   element = jQuery('body'),
   offset =  { left: element.offset().left, top: element.offset().top };
   element.bind('mousemove', function(e){
      left = window.innerWidth/2 - (e.pageX-offset.left);
      top = window.innerHeight/2 - (e.pageY-offset.top);
      if(!StatusWindow){
        $('#user-select').css('top',0.03*top + "px")
        $('#user-select').css('left',0.03*left + "px")
        $('.main-title').css('top',0.055*top + "px")
        $('.main-title').css('left',0.055*left + "px")
        $('.main-text-1').css('top',0.055*top + "px")
        $('.main-text-1').css('left',0.055*left + "px")
        $('.main-text-2').css('top',0.055*top + "px")
        $('.main-text-2').css('left',0.055*left + "px")
        $('.main-btn').css('top',0.055*top + "px")
        $('.main-btn').css('left',0.055*left + "px")
        $('.main-search').css('top',0.055*top + "px")
        $('.main-search').css('left',0.055*left + "px")
        $('.main-title-a').css('top',0.0625*top + "px")
        $('.main-title-a').css('left',0.0625*left + "px")
        $('.main-text-1-a').css('top',-72 + 0.059*top + "px")
        $('.main-text-1-a').css('left',0.059*left + "px")
        $('.main-btn-a').css('top',454 + 0.0605*top + "px")
        $('.main-btn-a').css('left',212 + 0.0605*left + "px")
      } else{
        $('#user-select').css('top',0.003*top + "px")
        $('#user-select').css('left',0.003*left + "px")
        $('.main-title').css('top',0.0055*top + "px")
        $('.main-title').css('left',0.0055*left + "px")
        $('.main-text-1').css('top',0.0055*top + "px")
        $('.main-text-1').css('left',0.0055*left + "px")
        $('.main-text-2').css('top',0.0055*top + "px")
        $('.main-text-2').css('left',0.0055*left + "px")
        $('.main-btn').css('top',0.0055*top + "px")
        $('.main-btn').css('left',0.0055*left + "px")
        $('.main-search').css('top',0.0055*top + "px")
        $('.main-search').css('left',0.0055*left + "px")
        $('.main-title-a').css('top',0.00625*top + "px")
        $('.main-title-a').css('left',0.00625*left + "px")
        $('.main-text-1-a').css('top',-72 + 0.0059*top + "px")
        $('.main-text-1-a').css('left',0.0059*left + "px")
        $('.main-btn-a').css('top',454 + 0.00605*top + "px")
        $('.main-btn-a').css('left',212 + 0.00605*left + "px")
      }
    });

    $(document).mouseup(function (e){ // событие клика по веб-документу
  		var div = $(".main-search-none");
  		if (!div.is(e.target) && div.has(e.target).length === 0 && !$('.main-search').is(e.target) && $('.main-search').has(e.target).length === 0 && !$('.notification').is(e.target) && $('.notification').has(e.target).length === 0) {
        close_window('#close');
  		}
  	});

    if(window.innerWidth >= 780){
      $('#line3').css('top','300px')
      $('#line3').css('left','100px')
      $('#circle2').css('top','192px')
      $('#circle2').css('left','181px')
      $('.main-text-2-a').css('display','block')
      $('.main-text-1-a').css('display','block')
      $('.main-title-a').css('display','block')
      $('.main-btn-a').css('display','block')
      $('.main').css('width','700px')
      $('.main').css('overflow','visible')
      $('.main').css('height','530px')
      $('.main').css('transform','translate(-50%, -50%) scale(1)')
      $('.main-search-none').css('height','auto')
      $('.main-search-none').css('max-height','650px')
      $('.main-search-none').css('width','450px')
      $('.main-search-none > textarea').css('min-width','425px')
      $('.main-search-none > textarea').css('max-width','425px')
      $('.main-text-2').css('font-size','25px')
      $('.main-text-1').css('font-size','60px')
      $('.main-title').css('font-size','190px')
      $('.main-title').css('margin-bottom','0px')
      if(window.innerHeight <= 500){
        $('.main').css('transform','translate(-50%,-50%) scale(0.75)')
      } else if(window.innerHeight <= 600){
        $('.main').css('transform','translate(-50%,-50%) scale(0.8)')
      } else if(window.innerHeight <= 720){
        $('.main').css('transform','translate(-50%,-50%) scale(0.85)')
      } else if(window.innerHeight > 720){
        $('.main').css('transform','translate(-50%,-50%) scale(1)')
      }
    } else{
      $('.main').css('width','100%')
      $('.main').css('overflow','hidden')
      $('.main').css('height','680px')
      $('.main').css('transform','translate(-50%,calc(-50% + 61px))')
      $('.main-search-none').css('height','calc(100% - 63px)')
      $('.main-search-none').css('width','calc(100% - 72px)')
      $('.main-search-none > textarea').css('min-width','calc(100% - 22px)')
      $('.main-search-none > textarea').css('max-width','calc(100% - 22px)')
      $('.main-text-2').css('font-size','17px')
      $('.main-text-1').css('font-size','35px')
      $('.main-title').css('font-size','120px')
      $('.main-title').css('margin-bottom','30px')
      $('#line3').css('top','426px')
      $('#line3').css('left','300px')
      $('#circle2').css('top','192px')
      $('#circle2').css('left','312px')
      $('.main-text-2-a').css('display','none')
      $('.main-text-1-a').css('display','none')
      $('.main-title-a').css('display','none')
      $('.main-btn-a').css('display','none')
    }


    $(document).keyup(function(e) {
      if (e.key === "Escape") { // escape key maps to keycode '27'
        if(StatusWindow){
          close_window('#close')
        }
      }
    });

    $(window).resize(function(){

      if($('.notification-block-standart').length > 0){
        if(window.innerWidth <= 780){
          widthNoti = 'width: calc(100vw - 105px); max-width: auto;'
          $('.notification-block-standart').css("width","calc(100vw - 105px)")
          $('.notification-block-standart').css("max-width","auto")
        }else {
          widthNoti = 'max-width: 320px; width: auto;'
          $('.notification-block-standart').css("width","auto")
          $('.notification-block-standart').css("max-width","max-width: 320px")
        }
      }
      if(window.innerWidth >= 780){
        $('#line3').css('top','300px')
        $('#line3').css('left','100px')
        $('#circle2').css('top','192px')
        $('#circle2').css('left','181px')
        $('.main-text-2-a').css('display','block')
        $('.main-text-1-a').css('display','block')
        $('.main-title-a').css('display','block')
        $('.main-btn-a').css('display','block')
        $('.main').css('width','700px')
        $('.main').css('overflow','visible')
        $('.main').css('height','530px')
        $('.main').css('transform','translate(-50%, -50%) scale(1)')
        $('.main-search-none').css('height','auto')
        $('.main-search-none').css('max-height','650px')
        $('.main-search-none').css('width','450px')
        $('.main-search-none > textarea').css('min-width','425px')
        $('.main-search-none > textarea').css('max-width','425px')
        $('.main-text-2').css('font-size','25px')
        $('.main-text-1').css('font-size','60px')
        $('.main-title').css('font-size','190px')
        $('.main-title').css('margin-bottom','0px')
        if(window.innerHeight <= 400){
          $('.main-title').css('font-size','150px')
          $('.main').css('transform','translate(-50%,-50%) scale(0.75)')
          $('.main').css('top','10px')
        }else if(window.innerHeight <= 500){
          $('.main').css('transform','translate(-50%,-50%) scale(0.75)')
        } else if(window.innerHeight <= 600){
          $('.main').css('transform','translate(-50%,-50%) scale(0.8)')
        } else if(window.innerHeight <= 720){
          $('.main').css('transform','translate(-50%,-50%) scale(0.85)')
        } else if(window.innerHeight > 720){
          $('.main').css('transform','translate(-50%,-50%) scale(1)')
        }
      } else{
        $('.main').css('width','100%')
        $('.main').css('overflow','hidden')
        $('.main').css('height','680px')
        $('.main').css('transform','translate(-50%,calc(-50% + 61px))')
        $('.main-search-none').css('height','calc(100% - 63px)')
        $('.main-search-none').css('width','calc(100% - 72px)')
        $('.main-search-none > textarea').css('min-width','calc(100% - 22px)')
        $('.main-search-none > textarea').css('max-width','calc(100% - 22px)')
        $('.main-text-2').css('font-size','17px')
        $('.main-text-1').css('font-size','35px')
        $('.main-title').css('font-size','120px')
        $('.main-title').css('margin-bottom','30px')
        $('#line3').css('top','426px')
        $('#line3').css('left','300px')
        $('#circle2').css('top','192px')
        $('#circle2').css('left','312px')
        $('.main-text-2-a').css('display','none')
        $('.main-text-1-a').css('display','none')
        $('.main-title-a').css('display','none')
        $('.main-btn-a').css('display','none')
      }
    })
});
