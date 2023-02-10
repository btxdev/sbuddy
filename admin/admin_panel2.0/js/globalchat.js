scrollBottomChat = 0;
scrollInfo = true;
msgSoundActive = false;
var chatElemUsers = undefined;
var chatElemUsers0 = undefined;

$(document).ready(function() {

  Chat.form.init();

  window.addEventListener('beforeunload', Chat.basic.exit);

  $('.panel-msg-block-msg-conteiner-main').scroll(function(){
    scrollBottomChat = $('.panel-msg-block-msg-conteiner-main').prop('scrollHeight') - $('.panel-msg-block-msg-conteiner-main').scrollTop() - $('.panel-msg-block-msg-conteiner-main').height();
    if(scrollBottomChat >= 250){
      $('#btnChatDown').css({
        'transform':'translate(0%, 0%) rotate(180deg)',
        'opacity':'1',
        'visibility':'visible'
      })
    } else{
      $('#btnChatDown').css({
        'transform':'translate(0%, 200%) rotate(180deg)',
        'opacity':'0',
        'visibility':'hidden'
      })
    }
  })

  $('#msg-input-2').focus(function(){
    if(adaptiveDesignS == 'phone'){
      if(scrollBottomChat <= 2){
        setTimeout(function(){
          $('.panel-msg-block-msg-conteiner-main').scrollTop($('.panel-msg-block-msg-conteiner-main').prop('scrollHeight'));
        }, 150)
      }
    }
  })

  $('.panel-msg-block-msg-conteiner-main').scroll(function(){
    scrollChatF();
    if(adaptiveDesignS == 'phone'){
      if((scrollBottomChat < 50 || scrollBottomChat > 1) && scrollBottomChat > 50){
        $('#msg-input-2').blur();
      }
    }
  })
  scrollChatF(true);

  $('body').hover(function(e){

    if(e.type == 'mouseleave'){
      msgSoundActive = true;
    } else{
      msgSoundActive = false;
    }
  })
  $(window).on('blur', function(){
    msgSoundActive = true;
  });
  $(window).on('focus', function(){
    msgSoundActive = false;
  });

  $('#searchmsg2').on('input', function(){
    searchChatFunc(this);
  })



});

var GlobalChatFiles = {
  add: function(name, func){
    $('.panel-msg-block-msg-textinput-file-block').css({
      'display':'block'
    });
    if($('.panel-msg-block-msg-textinput-file-block').find('.panel-msg-block-msg-textinput-file-block-elem').length > 0){
      let block = "";
          block += "<div class='panel-msg-block-msg-textinput-file-block-elem'>\n";
          block += "<div class='panel-msg-block-msg-textinput-file-block-elem-del icon-plus' onclick='" + func + "; GlobalChatFiles.del(this);' title='Удалить'></div>\n";
          block += "<div class='panel-msg-block-msg-textinput-file-block-elem-ico icon-file2'></div>\n";
          block += "<div class='panel-msg-block-msg-textinput-file-block-elem-name'>" + name + "</div>\n";
          block += "</div>\n";
      $('.panel-msg-block-msg-textinput-file-block').find('span').prepend(block);
    } else{
      $('.panel-msg-block-msg-textinput-file-block').css({
        'display':'block',
        'transform':'',
        'opacity':'',
        'visibility':''
      });
      let block = "";
          block += "<div class='panel-msg-block-msg-textinput-file-block-elem'>\n";
          block += "<div class='panel-msg-block-msg-textinput-file-block-elem-del icon-plus' onclick='" + func + "; GlobalChatFiles.del(this);' title='Удалить'></div>\n";
          block += "<div class='panel-msg-block-msg-textinput-file-block-elem-ico icon-file2'></div>\n";
          block += "<div class='panel-msg-block-msg-textinput-file-block-elem-name'>" + name + "</div>\n";
          block += "</div>\n";
      $('.panel-msg-block-msg-textinput-file-block').find('span').prepend(block);
    }
    $('.panel-msg-block-msg-textinput-file-count').css({
      'display':''
    })
    $('.panel-msg-block-msg-textinput-file-count').text($('.panel-msg-block-msg-textinput-file-block').find('.panel-msg-block-msg-textinput-file-block-elem').length)
  },
  del: function(block){
    $(block).parent().css({
      'transform':'rotate(90deg) scale(0)'
    });
    setTimeout(function(){
      $(block).parent().css({
        'width':'0px',
        'height':'0px',
        'margin-right':'0px',
        'border':'0px solid #5d78ff'
      });
      setTimeout(function(){
        $(block).parent().remove();
        $('.panel-msg-block-msg-textinput-file-count').text($('.panel-msg-block-msg-textinput-file-block').find('.panel-msg-block-msg-textinput-file-block-elem').length)
        if($('.panel-msg-block-msg-textinput-file-block').find('.panel-msg-block-msg-textinput-file-block-elem').length == 0){
          $('.panel-msg-block-msg-textinput-file-block').css({
            'transform':'translate(0px, 20px)',
            'opacity':'0',
            'visibility':'hidden'
          });
          $('.panel-msg-block-msg-textinput-file-count').css({
            'display':'none'
          })
          setTimeout(function(){
            $('.panel-msg-block-msg-textinput-file-block').css({
              'display':'none'
            });
          }, 450)
        }
      }, 350)
    }, 250)
  },
  delAll: function(){
    $('.panel-msg-block-msg-textinput-file-block').find('span').empty();
    $('.panel-msg-block-msg-textinput-file-count').css({
      'display':'none'
    })
    $('.panel-msg-block-msg-textinput-file-block').css({
      'transform':'translate(0px, 20px)',
      'opacity':'0',
      'visibility':'hidden',
      'display':'none'
    });
  }
}

function searchChatFunc(block){
  var searchChat = $(block);

  if(searchChat.val().length > 0){
    searchChat.next().css({
      'opacity':'1',
      'visibility':'visible'
    });



    if(chatElemUsers == undefined){
      chatElemUsers = $(searchChat.parent().parent().find('.panel-msg-conteiner')[1]);
      let output1 = '';
      for(let i = 0; i < chatElemUsers.length; i++){
        output1 += $(chatElemUsers[i]).html() + "\n";
      }
      $('#globalchat-users-list-online-test').html(output1);
      chatElemUsers0 = $('#globalchat-users-list-online-test').find('.panel-msg-block');
    }

    let output = '';

    for(let i = 0; i < chatElemUsers0.length; i++){
      var search = $(chatElemUsers0[i]).html().toLowerCase().indexOf(searchChat.val().toLowerCase());
      if(search > 0){
        output += '<div class="panel-msg-block">' + $(chatElemUsers0[i]).html() + "</div>\n";
      }
    }
    if(output.length == 0){
      output = "";
      output += '<span>\n';
      output += '<div class="news-search-block-conteiner">\n';
      output += '<div class="news-search-block-conteiner-img icon-fast2"></div>\n';
      output += '<div class="news-search-block-conteiner-text">\n';
      output += 'Упс... Такой пользователь не найден!\n';
      output += '</div>\n';
      output += '</div>\n';
      output += '</span>\n';

      $(searchChat.parent().parent().find('.panel-msg-conteiner')[1]).html(output)
    } else{
      $(searchChat.parent().parent().find('.panel-msg-conteiner')[1]).html(output)
    }

    $(searchChat.next()).on('click', function(){
      searchChat.val('');
      $('#globalchat-users-list-online').html($('#globalchat-users-list-online-test').html());
      chatElemUsers = undefined;
      searchChat.next().css({
        'opacity':'0',
        'visibility':'hidden'
      });
    })

  } else{
    $('#globalchat-users-list-online').html($('#globalchat-users-list-online-test').html());
    chatElemUsers = undefined;
    searchChat.next().css({
      'opacity':'0',
      'visibility':'hidden'
    });
  }
}

function audioMsgChat(){

  if(msgSoundActive){
    if($.cookie('sound_msg') == 'true'){

      var audioNotification = new Audio();
      audioNotification.src = 'media/audio/newMsg.mp3';
      audioNotification.autoplay = true;
    } else{
      window.navigator.vibrate(35)
    }
  }

}

function scrollDown(animate){
  if(animate == undefined){
    animate = true;
  }
  if(animate){
    $('.panel-msg-block-msg-conteiner-main').stop().animate({
      scrollTop: $('.panel-msg-block-msg-conteiner-main').prop('scrollHeight'),
      duration: 'slow',
      easing: "easeOut"
    })
  } else{
    $('.panel-msg-block-msg-conteiner-main').scrollTop($('.panel-msg-block-msg-conteiner-main').prop('scrollHeight'))
  }

}

function scrollChatF(a){
  if(a == undefined){
    a = false;
  }
  let scrollTop = $('.panel-msg-block-msg-conteiner-main').scrollTop();

  if((scrollTop < 450 && scrollInfo) || a){
    scrollInfo = false;
    Chat.form.msgHistory();
  }
}

/*function inputTextareaChat(block){
    if($(block).val().length > 10){
      $('.panel-msg-block-msg-textinput-textarea > .area-123').css({
        'height':'100%',
        'margin-top':'0px'
      })
    } else{
      $('.chat-main-msg-text-main-text > textarea').css({
        'height':'27px',
        'margin-top':'13px'
      })
    }
}*/

var Chat = {
  history: [],
  basic: {
    send: function(msg, callback) {
      $.ajax({
        type: 'POST',
        url: 'php/db_globalchat.php',
        data: {
          send_message: msg
        },
        beforeSend: function() {},
        complete: function() {},
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.')) {
            var responseText = response.substring(3, response.length);
            if(typeof(callback) != 'undefined') { callback(msg, Number(responseText)); }
          }
          else {
            console.log('error, response: ' + response);
            if(typeof(callback) != 'undefined') { callback(msg, false); }
          }
        },
        error: function(jqXHR, status) {
          console.log('error: ' + status + ', ' + jqXHR);
          if(typeof(callback) != 'undefined') { callback(msg, false); }
        }
      });
    },
    msg: {
      when: function(when, callback) {
        if(typeof(when) == 'undefined') when = 0;
        $.ajax({
          type: 'POST',
          url: 'php/db_globalchat.php',
          data: {
            read_messages: when
          },
          beforeSend: function() {},
          complete: function() {},
          success: function(response) {
            function checkResponseCode(code, rsp) {
              if(typeof(rsp) == 'undefined') rsp = response;
              return (response.substring(0, code.length) == code);
            }
            if(checkResponseCode('OK.')) {
              var responseText = response.substring(3, response.length);
              var responseData = JSON.parse(responseText);
              if(typeof(callback) != 'undefined') { callback(responseData); }
            }
            else {
              console.log('error, response: ' + response);
              if(typeof(callback) != 'undefined') { callback([]); }
            }
          },
          error: function(jqXHR, status) {
            console.log('error: ' + status + ', ' + jqXHR);
            if(typeof(callback) != 'undefined') { callback([]); }
          }
        });
      },
      last: function(callback) {
        $.ajax({
          type: 'POST',
          url: 'php/db_globalchat.php',
          data: {
            read_last: true
          },
          beforeSend: function() {},
          complete: function() {},
          success: function(response) {
            function checkResponseCode(code, rsp) {
              if(typeof(rsp) == 'undefined') rsp = response;
              return (response.substring(0, code.length) == code);
            }
            if(checkResponseCode('OK.')) {
              var responseText = response.substring(3, response.length);
              var responseData = JSON.parse(responseText);
              if(typeof(callback) != 'undefined') { callback(responseData); }
            }
            else {
              console.log('error, response: ' + response);
              if(typeof(callback) != 'undefined') { callback([]); }
            }
          },
          error: function(jqXHR, status) {
            console.log('error: ' + status + ', ' + jqXHR);
            if(typeof(callback) != 'undefined') { callback([]); }
          }
        });
      },
      delete: function(msgid, callback) {
        $.ajax({
          type: 'POST',
          url: 'php/db_globalchat.php',
          data: {
            msg_delswitch: msgid,
            mode: 'delete'
          },
          beforeSend: function() {},
          complete: function() {},
          success: function(response) {
            function checkResponseCode(code, rsp) {
              if(typeof(rsp) == 'undefined') rsp = response;
              return (response.substring(0, code.length) == code);
            }
            if(checkResponseCode('OK.')) {
              var responseText = response.substring(3, response.length);
              if(typeof(callback) != 'undefined') { callback(true); }
            }
            else {
              console.log('error, response: ' + response);
              if(typeof(callback) != 'undefined') { callback(false); }
            }
          },
          error: function(jqXHR, status) {
            console.log('error: ' + status + ', ' + jqXHR);
            if(typeof(callback) != 'undefined') { callback(false); }
          }
        });
      },
      restore: function(msgid, callback) {
        $.ajax({
          type: 'POST',
          url: 'php/db_globalchat.php',
          data: {
            msg_delswitch: msgid,
            mode: 'restore'
          },
          beforeSend: function() {},
          complete: function() {},
          success: function(response) {
            function checkResponseCode(code, rsp) {
              if(typeof(rsp) == 'undefined') rsp = response;
              return (response.substring(0, code.length) == code);
            }
            if(checkResponseCode('OK.')) {
              var responseText = response.substring(3, response.length);
              if(typeof(callback) != 'undefined') { callback(true); }
            }
            else {
              console.log('error, response: ' + response);
              if(typeof(callback) != 'undefined') { callback(false); }
            }
          },
          error: function(jqXHR, status) {
            console.log('error: ' + status + ', ' + jqXHR);
            if(typeof(callback) != 'undefined') { callback(false); }
          }
        });
      }
    },
    online: function(callback) {
      $.ajax({
        type: 'POST',
        url: 'php/db_globalchat.php',
        data: {
          online_list: true
        },
        beforeSend: function() {},
        complete: function() {},
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.')) {
            var responseText = response.substring(3, response.length);
            var responseData = JSON.parse(responseText);
            callback(responseData);
          }
          else {
            console.log('error, response: ' + response);
            callback([]);
          }
        },
        error: function(jqXHR, status) {
          console.log('error: ' + status + ', ' + jqXHR);
          callback([]);
        }
      });
    },
    exit: function() {
      // data
      var url = 'php/db_globalchat.php';
      var data = {
        act_exit: true
      };
      // request
      if(navigator.sendBeacon) {
        var formData = new FormData();
        for(key in data) {
          formData.append(key, data[key]);
        }
        navigator.sendBeacon(url, formData);
      }
      else {
        $.ajax({
          type: 'POST',
          url: url,
          data: data,
          success: function(response) { }
        });
      }
    },
    user: {
      ban: function(userid, callback) {
        $.ajax({
          type: 'POST',
          url: 'php/db_globalchat.php',
          data: {
            user_banswitch: userid,
            mode: 'ban'
          },
          beforeSend: function() {},
          complete: function() {},
          success: function(response) {
            function checkResponseCode(code, rsp) {
              if(typeof(rsp) == 'undefined') rsp = response;
              return (response.substring(0, code.length) == code);
            }
            if(checkResponseCode('OK.')) {
              var responseText = response.substring(3, response.length);
              if(typeof(callback) != 'undefined') { callback(true); }
            }
            else {
              console.log('error, response: ' + response);
              if(typeof(callback) != 'undefined') { callback(false); }
            }
          },
          error: function(jqXHR, status) {
            console.log('error: ' + status + ', ' + jqXHR);
            if(typeof(callback) != 'undefined') { callback(false); }
          }
        });
      },
      restore: function(userid, callback) {
        $.ajax({
          type: 'POST',
          url: 'php/db_globalchat.php',
          data: {
            user_banswitch: userid,
            mode: 'restore'
          },
          beforeSend: function() {},
          complete: function() {},
          success: function(response) {
            function checkResponseCode(code, rsp) {
              if(typeof(rsp) == 'undefined') rsp = response;
              return (response.substring(0, code.length) == code);
            }
            if(checkResponseCode('OK.')) {
              var responseText = response.substring(3, response.length);
              if(typeof(callback) != 'undefined') { callback(true); }
            }
            else {
              console.log('error, response: ' + response);
              if(typeof(callback) != 'undefined') { callback(false); }
            }
          },
          error: function(jqXHR, status) {
            console.log('error: ' + status + ', ' + jqXHR);
            if(typeof(callback) != 'undefined') { callback(false); }
          }
        });
      }
    }
  },
  form: {
    field: {},
    init: function() {
      $('#general_chat_block').empty();
      // message history
      setTimeout(function() { Chat.basic.msg.when(0, Chat.form.msgHistoryCallback); }, 50);
      // new messages
      Chat.form.msgMonitorInterval = setInterval(Chat.form.msgMonitor, 1000);
      // scroll
      setTimeout(function() { scrollDown(false); }, 2500);
      // hotkeys
      $('#msg-input-2').keydown(function (e) {
        // ctrl + enter
         if((e.ctrlKey || e.shiftKey) && e.keyCode == 13) {
           e.preventDefault();
           //$('#msg-input-2').val($('#msg-input-2').val() + '\n');
           $('#msg-input-2').focus().val($('#msg-input-2').val() + '\n');
         }
         // enter
         else if(e.keyCode == 13) {
           e.preventDefault();
           Chat.form.send();
         }
         else {}
       }
     );
     // online list
     Chat.form.onlineListInterval = setInterval(Chat.form.onlineList, 30000);
     Chat.form.onlineList();
     // attachments
     Chat.form.attachments.block.clear();
     $('#msg-file').on('change', function() { Chat.form.attachments.add(this.files); });
     Chat.form.attachments.current();
    },
    appendMode: 'pre',
    appendBuffer: [],
    addMsg: function(msgId, type, text, date, icon, name1, name2, username, userId, attachments, admin, deleted) {
      // empty
      for(let i = 0; i < Chat.history.length; i++) {
        if(Chat.history[i].msgId == msgId) {
          return;
        }
      }
      // admin
      if(typeof(admin) == 'undefined') admin = false;
      // define type
      var time = '';
      var timeMin = '';
      if(typeof(date) != 'undefined') {
        time = date.split(' ')[1];
        timeMin = time.substring(0, time.lastIndexOf(':'));
      }
      if(typeof(type) == 'undefined') {
        type = 'another_first';
      }
      // attachments
      function printAttachments(files, login) {
        var output = '';
        if(typeof(files) == 'undefined') return '';
        if(typeof(login) == 'undefined') login = username;
        if(Object.keys(files).length <= 1) return '';
        var msgToken = files[0];
        output += '<span class="panel-msg-block-msg-conteiner-main-conteiner-block-msg-file">\n';
        for(file in files) {
          if(file == 0) continue;
          var fileToken = files[file].token;
          if(admin) {
            output += '<a href="media/users/public/'+login+'/Общий чат - Вложения/'+msgToken+'/'+fileToken+'" target="_blank">\n';
          }
          else {
            output += '<a href="../USERS_FILES/'+login+'/Общий чат - Вложения/'+msgToken+'/'+fileToken+'" target="_blank">\n';
          }
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file" title="'+files[file].filename+'">\n';
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-ico icon-file2"></div>\n';
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-name">'+files[file].filename+'</div>\n';
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-download icon-download" title="Скачать: '+files[file].filename+'"></div>\n';
          output += '</div></a>\n';
        }
        output += '</span>\n';
        return output;
      }
      // output
      if(type == 'my_first') {
        var output = '';
        // output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-inv" id="msg-id-'+msgId+'">\n';
        if(adaptiveDesignS == 'phone'){
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-inv" style="width: calc(100vw - 0px); padding-left: 0px;" id="msg-id-'+msgId+'">\n';
        } else if(adaptiveDesignS == 'tablet'){
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-inv" id="msg-id-'+msgId+'">\n';
        } else{
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-inv" id="msg-id-'+msgId+'">\n';
        }
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2">\n';
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read" title="Отправлено"></div>\n';
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time">';
        output += timeMin;
        output += '<span class="icon-settings" title="Параметры"></span>\n';
        output += '<div class="chat-set-msg-inv">\n';
        output += '<div class="chat-set-msg-elem" onclick="Chat.form.msg.switch('+msgId+');">\n';
        output += '<div class="chat-set-msg-elem-ico icon-basket"></div>\n';
        output += '<div class="chat-set-msg-elem-text" id="msg-id-'+msgId+'-delswitch" >'; if(deleted) { output += 'Восстановить'; } else { output += 'Удалить'; } output += '</div>\n';
        output += '</div>\n';
        output += '</div>\n';
        output += '</div>\n';
        output += '</div>\n';
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv" id="msg-id-'+msgId+'-msgopacity"'; if(deleted) { output += ' style="opacity: 0.3;" title="Сообщение удалено"'; } output += '>\n';
        if(text.match(/^([\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2694-\u2697]|\uD83E[\uDD10-\uDD5D])$/)){
          if(text.match(/^(❤|🧡|💛|💚|💙|💜|🤎|🖤|🤍)$/)){
            output += '<span style="font-size: 40px; animation: heart 1.1s infinite linear; display: inline-block;">'+text+'</span>\n';
          } else{
            output += '<span style="font-size: 40px;">'+text+'</span>\n';
          }
        } else if(text.match(/^((https|http)?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/)){
          output += '<span><a title="Нажмите для открытия ссылки" href=' + text + ' style="color: var(--colorLogo); text-decoration: underline;" target="_blank">'+text+'</a><span>\n';
        } else if(text.match(/^(👨‍💻🎸|🎸👨‍💻|#INSOweb)$/ui)){
          output += '<span><a title="Нажмите для открытия ссылки" href="http://insoweb.ru/" style="color: var(--orange); text-decoration: underline;" target="_blank">Сайт разработан INSOweb</a><span>\n';
        }  else{
          output += '<span>'+text+'</span>\n';
        }

        output += printAttachments(attachments);
        output += '</div>\n';
        if(adaptiveDesignS == 'phone'){
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-photo" style="display: none; background-image: url(&quot;'+icon+'&quot;);"></div>\n';
        } else if(adaptiveDesignS == 'tablet'){
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-photo" style="background-image: url(&quot;'+icon+'&quot;);"></div>\n';
        } else{
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-photo" style="background-image: url(&quot;'+icon+'&quot;);"></div>\n';
        }
        output += '</div>\n';
      }
      if(type == 'my') {
        var output = '';
        if(adaptiveDesignS == 'phone'){
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-inv" style="width: calc(100vw - 0px); padding-left: 0px;" id="msg-id-'+msgId+'">\n';
        } else if(adaptiveDesignS == 'tablet'){
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-inv" id="msg-id-'+msgId+'">\n';
        } else{
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-inv" id="msg-id-'+msgId+'">\n';
        }
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2">\n';
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read" title="Отправлено"></div>\n';
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time">';
        output += timeMin;
        output += '<span class="icon-settings" title="Параметры"></span>\n';
        output += '<div class="chat-set-msg-inv">\n';
        output += '<div class="chat-set-msg-elem" onclick="Chat.form.msg.switch('+msgId+');">\n';
        output += '<div class="chat-set-msg-elem-ico icon-basket"></div>\n';
        output += '<div class="chat-set-msg-elem-text" id="msg-id-'+msgId+'-delswitch">'; if(deleted) { output += 'Восстановить'; } else { output += 'Удалить'; } output += '</div>\n';
        output += '</div>\n';
        output += '</div>\n';
        output += '</div>\n';
        output += '</div>\n';
        if(adaptiveDesignS == 'phone'){
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv" style="margin-right: 5px;" id="msg-id-'+msgId+'-msgopacity"';
        } else if(adaptiveDesignS == 'tablet'){
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv" id="msg-id-'+msgId+'-msgopacity"';
        } else{
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv" id="msg-id-'+msgId+'-msgopacity"';
        }
        if(deleted) { output += ' style="opacity: 0.3;" title="Сообщение удалено"'; }
        output += '>\n';
        if(text.match(/^([\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2694-\u2697]|\uD83E[\uDD10-\uDD5D])$/)){
          if(text.match(/^(❤|🧡|💛|💚|💙|💜|🤎|🖤|🤍)$/)){
            output += '<span style="font-size: 40px; animation: heart 1.1s infinite linear; display: inline-block;">'+text+'</span>\n';
          } else{
            output += '<span style="font-size: 40px;">'+text+'</span>\n';
          }
        } else if(text.match(/^((https|http)?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/)){
          output += '<span><a title="Нажмите для открытия ссылки" href=' + text + ' style="color: var(--colorLogo); text-decoration: underline;" target="_blank">'+text+'</a><span>\n';
        } else if(text.match(/^(👨‍💻🎸|🎸👨‍💻|#INSOweb)$/ui)){
          output += '<span><a title="Нажмите для открытия ссылки" href="http://insoweb.ru/" style="color: var(--orange); text-decoration: underline;" target="_blank">Сайт разработан INSOweb</a><span>\n';
        }  else{
          output += '<span>'+text+'</span>\n';
        }
        output += printAttachments(attachments);
        output += '</div>\n';
        output += '</div>\n';
      }
      if(type == 'another_first') {
        var output = '';
        if(adaptiveDesignS == 'phone'){
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block" style="width: calc(100vw - 0px); padding-left: 11px; padding-right: 0px;" id="msg-id-'+msgId+'">\n';
        } else if(adaptiveDesignS == 'tablet'){
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block" id="msg-id-'+msgId+'">\n';
        } else{
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block" id="msg-id-'+msgId+'">\n';
        }
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-constatus">\n';
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read" title="Отправлено"></div>\n';
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time">\n';
        output += timeMin + '\n';
        output += '<span class="icon-settings" title="Параметры"></span>\n';
        output += '<div class="chat-set-msg">\n';
        output += '<div class="chat-set-msg-elem" onclick="Chat.form.msg.switch('+msgId+');">\n';
        output += '<div class="chat-set-msg-elem-ico icon-basket"></div>\n';
        output += '<div class="chat-set-msg-elem-text" id="msg-id-'+msgId+'-delswitch">'; if(deleted) { output += 'Восстановить'; } else { output += 'Удалить'; } output += '</div>\n';
        output += '</div>\n';
        if(!admin) {
          output += '<div class="chat-set-msg-elem">\n';
          output += '<div class="chat-set-msg-elem-ico icon-plus" style="transform: rotate(45deg) scale(1.2);"></div>\n';
          output += '<div class="chat-set-msg-elem-text" id="msg-id-'+msgId+'-user-'+userId+'" onclick="Chat.form.users.switch('+userId+');">';
          if(Chat.form.users.isBlocked(userId)) { output += 'Разблокировать пользователя'; }
          else { output += 'Заблокировать пользователя'; }
          output += '</div>\n';
          output += '</div>\n';
        }
        output += '</div>\n';
        output += '</div>\n';
        output += '</div>\n';
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-photo" style="background-image: url(&quot;'+icon+'&quot;);"></div>\n';
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-msg1" id="msg-id-'+msgId+'-msgopacity"';
        if(deleted) { output += ' style="opacity: 0.3;" title="Сообщение удалено"'; }
        output += '>\n';
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-msg1-title">'+name1+' '+name2+'</div>\n';
        if(text.match(/^([\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2694-\u2697]|\uD83E[\uDD10-\uDD5D])$/)){
          if(text.match(/^(❤|🧡|💛|💚|💙|💜|🤎|🖤|🤍)$/)){
            output += '<span style="font-size: 40px; animation: heart 1.1s infinite linear; display: inline-block;">'+text+'</span>\n';
          } else{
            output += '<span style="font-size: 40px;">'+text+'</span>\n';
          }
        } else if(text.match(/^((https|http)?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/)){
          output += '<span><a title="Нажмите для открытия ссылки" href=' + text + ' style="color: var(--colorLogo); text-decoration: underline;" target="_blank">'+text+'</a><span>\n';
        } else if(text.match(/^(👨‍💻🎸|🎸👨‍💻|#INSOweb)$/ui)){
          output += '<span><a title="Нажмите для открытия ссылки" href="http://insoweb.ru/" style="color: var(--orange); text-decoration: underline;" target="_blank">Сайт разработан INSOweb</a><span>\n';
        }  else{
          output += '<span>'+text+'</span>\n';
        }
        output += printAttachments(attachments);
        output += '</div>\n';
        output += '</div>\n';
      }
      if(type == 'another') {
        var output = '';
        if(adaptiveDesignS == 'phone'){
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block" style="width: calc(100vw - 0px); padding-left: 11px; padding-right: 0px;" id="msg-id-'+msgId+'">\n';
        } else if(adaptiveDesignS == 'tablet'){
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block" id="msg-id-'+msgId+'">\n';
        } else{
          output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block" id="msg-id-'+msgId+'">\n';
        }
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-constatus">\n';
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read" title="Отправлено"></div>\n';
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time">\n';
        output += timeMin + '\n';
        output += '<span class="icon-settings" title="Параметры"></span>\n';
        output += '<div class="chat-set-msg">\n';
        output += '<div class="chat-set-msg-elem" onclick="Chat.form.msg.switch('+msgId+');">\n';
        output += '<div class="chat-set-msg-elem-ico icon-basket"></div>\n';
        output += '<div class="chat-set-msg-elem-text" id="msg-id-'+msgId+'-delswitch">'; if(deleted) { output += 'Восстановить'; } else { output += 'Удалить'; } output += '</div>\n';
        output += '</div>\n';
        if(!admin) {
          output += '<div class="chat-set-msg-elem">\n';
          output += '<div class="chat-set-msg-elem-ico icon-plus" style="transform: rotate(45deg) scale(1.2);"></div>\n';
          output += '<div class="chat-set-msg-elem-text" id="msg-id-'+msgId+'-user-'+userId+'" onclick="Chat.form.users.switch('+userId+');">';
          if(Chat.form.users.isBlocked(userId)) { output += 'Разблокировать пользователя'; }
          else { output += 'Заблокировать пользователя'; }
          output += '</div>\n';
          output += '</div>\n';
        }
        output += '</div>\n';
        output += '</div>\n';
        output += '</div>\n';
        output += '<div class="panel-msg-block-msg-conteiner-main-conteiner-block-msg2" id="msg-id-'+msgId+'-msgopacity"'; if(deleted) { output += ' style="opacity: 0.3;" title="Сообщение удалено"'; } output += '>\n';
        if(text.match(/^([\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2694-\u2697]|\uD83E[\uDD10-\uDD5D])$/)){
          if(text.match(/^(❤|🧡|💛|💚|💙|💜|🤎|🖤|🤍)$/)){
            output += '<span style="font-size: 40px; animation: heart 1.1s infinite linear; display: inline-block;">'+text+'</span>\n';
          } else{
            output += '<span style="font-size: 40px;">'+text+'</span>\n';
          }
        } else if(text.match(/^((https|http)?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/)){
          output += '<span><a title="Нажмите для открытия ссылки" href=' + text + ' style="color: var(--colorLogo); text-decoration: underline;" target="_blank">'+text+'</a><span>\n';
        } else if(text.match(/^(👨‍💻🎸|🎸👨‍💻|#INSOweb)$/ui)){
          output += '<span><a title="Нажмите для открытия ссылки" href="http://insoweb.ru/" style="color: var(--orange); text-decoration: underline;" target="_blank">Сайт разработан INSOweb</a><span>\n';
        }  else{
          output += '<span>'+text+'</span>\n';
        }
        output += printAttachments(attachments);
        output += '</div>\n';
        output += '</div>\n';
      }
      // append data
      if(Chat.form.appendMode == 'pre') {
        $('#general_chat_block').prepend(output);
        Chat.history.unshift({
          msgId: msgId,
          userId: userId,
          username: username,
          type: type,
          deleted: deleted
        });
      }
      if(Chat.form.appendMode == 'post') {
        $('#general_chat_block').append(output);
        Chat.history.push({
          msgId: msgId,
          userId: userId,
          username: username,
          type: type,
          deleted: deleted
        });
      }
    },
    sendCallback: function(msg, msgId) {
      if(msgId === false) return;
      if(Object.keys(Chat.form.attachments.files).length == 0) {
        Chat.form.lastMsgCallback([{
          admin: true,
          message: {
            id: msgId,
            text: escapeHtml(msg).replace(/[\n\r]/g, '<br>'),
            date: getCurrentDateTimeMySql()
          },
          user: {
            name1: userData['name1'],
            name2: userData['name1'],
            login: userData['login'],
            id: userData['id']
          }
        }], userData['icon']);
        $('#panel-msg-block-msg-conteiner-main-scroll').scrollTop($('#panel-msg-block-msg-conteiner-main-scroll').prop('scrollHeight'));
        $('#panel-msg-block-msg-conteiner-main-scroll-main-hello').css({
          'display':'none'
        });
      }
      else {
        Chat.form.attachments.block.clear();
      }
    },
    send: function() {
      var msg = $('#msg-input-2').val();
      var msgTest = msg.replace(/\s+/g, '');
      msgTest = msgTest.replace(/[\n\r]/g, '');
      if(msgTest.length == 0 && (Object.keys(Chat.form.attachments.files).length == 0)) return;
      $('#msg-input-2').focus().val('');
      var msgId = Chat.basic.send(msg, Chat.form.sendCallback);
    },
    msgHistoryCallback: function(messages) {
      if(messages != false) {
        for(let i = 0; i < messages.length; i++) {
          // get next message
          var nextMsg = false;
          if(typeof(messages[i + 1]) != 'undefined') { nextMsg = messages[i + 1]; }
          else { nextMsg = false; }
          // current message
          var theMsg = messages[i];
          // get profile icon
          var icon;
          if(theMsg.admin) {
            if(theMsg.user.icon == 'PROFILE') {
              icon = 'media/users/public/' + theMsg.user.login + '/profile.jpg';
            }
            else {
              var iconId = theMsg.user.icon.substring(theMsg.user.icon.lastIndexOf('_') + 1, theMsg.user.icon.length);
              icon = 'media/users/' + iconId + '.jpg';
            }
          }
          else {
            icon = '../../../Projects/Study Buddy/Сайт/ver 2/users/public/' + theMsg.user.login + '/avatar.png';
            if(theMsg.user.icon == 'null' || theMsg.user.icon == null || typeof(theMsg.user.icon) == 'null') {
              if(theMsg.user.gender == 'male') { icon = '../../../Projects/Study Buddy/Сайт/ver 2/media/svg/male_avatar.svg'; }
              else { icon = '../../../Projects/Study Buddy/Сайт/ver 2/media/svg/female_avatar.svg'; }
            }
          }
          // get message type
          var type = 'another_first';
          if(theMsg.admin && (theMsg.user.id == userData['id'])) {
            if(nextMsg != false && nextMsg.user.id == theMsg.user.id) { type = 'my'; }
            else { type = 'my_first'; }
          }
          else {
            if(nextMsg != false && nextMsg.user.id == theMsg.user.id) { type = 'another'; }
            else { type = 'another_first'; }
          }
          // add message
          Chat.form.appendMode = 'pre';
          Chat.form.addMsg(theMsg.message.id, type, theMsg.message.text, theMsg.message.date, icon, theMsg.user.name1, theMsg.user.name2, theMsg.user.login, theMsg.user.id, theMsg.attachments, theMsg.admin, theMsg.message.deleted);
        }
        // scrolling limiter
        scrollInfo = true;
        // preloader off
        $('.chat-main-msg-msgs-text-main-preloader').css({
          'display':'none'
        });
      }
      else {
        $('.chat-main-msg-msgs-text-main-preloader').css({
          'display':'none'
        });
        // truncate
        // first notification
        if(Chat.form.msgHistoryPosition <= 60) {
          $('.chat-main-msg-msgs-text-main-hello').css({
            'display':'block'
          })
        } else{
          $('.chat-main-msg-msgs-text-main-hello').css({
            'display':'none'
          })
        }

      }
    },
    msgHistoryPosition: 0,
    msgHistory: function(position) {
      if(typeof(position) == 'undefined') {
        position = Chat.form.msgHistoryPosition;
        Chat.form.msgHistoryPosition += 60;
      }
      // preloader on
      $('.chat-main-msg-msgs-text-main-preloader').css({
        'display':'block'
      });
      Chat.basic.msg.when(position, Chat.form.msgHistoryCallback);
    },
    lastMsgCounter: 0,
    lastMsgCallback: function(messages, customIcon) {
      // new msg count
      if($('#general_chat').css('display') == 'block') {
        Chat.form.lastMsgCounter = 0;
      }
      if(typeof(messages) == 'object') {
        // new message by another user
        if(messages.length > 0 && typeof(customIcon) == 'undefined') {
          // scroll
          setTimeout(function() { $('#panel-msg-block-msg-conteiner-main-scroll').scrollTop($('#panel-msg-block-msg-conteiner-main-scroll').prop('scrollHeight')); }, 100);
        }
        for(let i = 0; i < messages.length; i++) {
          // get previous message
          var lastMsg = false;
          if(Chat.history.length > 0) { lastMsg = Chat.history[Chat.history.length - 1]; }
          var theMsg = messages[i];
          // get profile icon
          var icon;
          if(typeof(customIcon) != 'undefined') {
            icon = customIcon;
          }
          else {
            // notifications
            if($('#general_chat').css('display') != 'block') {
              // msg count
              Chat.form.lastMsgCounter++;
              // sound
              audioMsgChat();
              // notification
              var notifyText = theMsg.message.text;
              if(theMsg.attachments.length > 0 && theMsg.message.text.length == 0) { notifyText = 'Прикрепленные файлы' }
              if(theMsg.user.login != userData['login']) {
                mailWinAdd({
                  type: 'generalChat',
                  title: 'Новое сообщение',
                  text: theMsg.user.name1 + ' ' + theMsg.user.name2 + ': ' + notifyText,
                  function: 'open_panel(\'#general_chat\'); setTimeout(function(){scrollDown(false);}, 300);'
                });
              }
            }
            // icon
            if(theMsg.admin) {
              if(theMsg.user.icon == 'PROFILE') {
                icon = 'media/users/public/' + theMsg.user.login + '/profile.jpg';
              }
              else {
                var iconId = theMsg.user.icon.substring(theMsg.user.icon.lastIndexOf('_') + 1, theMsg.user.icon.length);
                icon = 'media/users/' + iconId + '.jpg';
              }
            }
            else {
              // icon
              icon = '../../../Projects/Study Buddy/Сайт/ver 2/users/public/' + theMsg.user.login + '/avatar.png';
              if(theMsg.user.icon == 'null' || theMsg.user.icon == null || typeof(theMsg.user.icon) == 'null') {
                if(theMsg.user.gender == 'male') { icon = '../../../Projects/Study Buddy/Сайт/ver 2/media/svg/male_avatar.svg'; }
                else { icon = '../../../Projects/Study Buddy/Сайт/ver 2/media/svg/female_avatar.svg'; }
              }
            }
          }
          // get message type
          var type = 'another_first';
          if(theMsg.admin && (theMsg.user.id == userData['id'])) {
            if(lastMsg != false && lastMsg.userId == theMsg.user.id) { type = 'my'; }
            else { type = 'my_first'; }
          }
          else {
            if(lastMsg != false && lastMsg.userId == theMsg.user.id) { type = 'another'; }
            else { type = 'another_first'; }
          }
          // add message
          Chat.form.appendMode = 'post';
          Chat.form.addMsg(theMsg.message.id, type, theMsg.message.text, theMsg.message.date, icon, theMsg.user.name1, theMsg.user.name2, theMsg.user.login, theMsg.user.id, theMsg.attachments, theMsg.admin, theMsg.message.deleted);
          // hide block
          if($('.chat-main-msg-msgs-text-main-hello').css('display') == 'block'){
            $('.chat-main-msg-msgs-text-main-hello').css({
              'display':'none'
            })
          }
        }
      }
      else {
        notification_add('error', 'Ошибка', 'Неизвестная ошибка');
        clearInterval(Chat.form.msgMonitorInterval);
        //setTimeout(function() { document.location.reload(true); }, 4000);
      }
      // new msg counter
      if(Chat.form.lastMsgCounter > 0) {
        $('#globalchat-msg-count').css({
          'visibility':'visible',
          'opacity':'1'
        });
        $('#globalchat-msg-count').text(Chat.form.lastMsgCounter);
      }
      else {
        $('#globalchat-msg-count').css({
          'visibility':'hidden',
          'opacity':'0'
        });
        setTimeout(function(){
          $('#globalchat-msg-count').text('');
        }, 250);
      }
    },
    msgMonitorInterval: undefined,
    msgMonitor: function() {
      Chat.basic.msg.last(Chat.form.lastMsgCallback);
    },
    onlineListInterval: undefined,
    onlineListCallback: function(data) {
      // error
      if(typeof(data) != 'object') {
        clearInterval(Chat.form.onlineListInterval);
        return;
      }
      // ok
      var count = data.length;
      // change title
      var title = String(count) + ' ' + declOfNumber(count, 'человек');
      $('#globalchat-users-count-title').text(title);
      // add elements
      $('#globalchat-users-list-online').empty();
      $('#globalchat-users-list-online-test').empty();
      for(let i = 0; i < count; i++) {
        var user = data[i];
        // get profile icon
        var icon;
        if(user.admin) {
          if(user.icon == 'PROFILE') {
            icon = 'media/users/public/' + data[i].login + '/profile.jpg';
          }
          else {
            var iconId = user.icon.substring(user.icon.lastIndexOf('_') + 1, user.icon.length);
            icon = 'media/users/' + iconId + '.jpg';
          }
        }
        else {
          icon = '../../../Projects/Study Buddy/Сайт/ver 2/users/public/' + user.login + '/avatar.png';
          if(user.icon == 'null' || user.icon == null || typeof(user.icon) == 'null') {
            if(user.gender == 'male') { icon = '../../../Projects/Study Buddy/Сайт/ver 2/media/svg/male_avatar.svg'; }
            else { icon = '../../../Projects/Study Buddy/Сайт/ver 2/media/svg/female_avatar.svg'; }
          }
        }
        // add elements
        var output = '';
        output += '<div class="panel-msg-block">\n';
        output += '<img alt="login" src="'+icon+'" class="panel-msg-block-img"></img>\n';
        if(user.online) {
          output += '<div class="panel-msg-block-online" title="В сети"></div>\n';
        }
        else {
          output += '<div class="panel-msg-block-ofline"></div>\n';
        }
        output += '<div class="panel-msg-block-text">\n';
        output += '<div class="panel-msg-block-text-title">'+user.name1+' '+user.name2+'</div>\n';
        output += '<div class="panel-msg-block-text-msg">'; if(user.admin) { output += 'Администратор'; } else { output += 'Пользователь'; } output += '</div>\n';
        output += '</div>\n';
        output += '</div>\n';
        $('#globalchat-users-list-online').append(output);
        $('#globalchat-users-list-online-test').append(output);

        // add user to list
        Chat.form.users.add(user.id, user.login, user.name1, user.name2, icon, user.blocked, user.admin);

        if($('#searchmsg2').val().length > 0){
          searchChatFunc('#searchmsg2');
        }

      }
    },
    onlineList: function() {
      Chat.basic.online(Chat.form.onlineListCallback);
    },
    attachments: {
      counter: 0,
      files: [],
      add: function(files) {
        if(typeof(files) == 'undefined') {
          files = this.files;
        }
        if(typeof(files) == 'undefined' || files.length == 0) {
          return;
        }
        var data = new FormData();
        $.each(files, function(key, value) {
          data.append(key, value);
        });
        data.append('attach_files', 1);
        // send files
        $.ajax({
          url: 'php/db_globalchat.php',
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
            // clear input
            $('#msg-file').val('');
          },
          success: function(response) {
            function checkResponseCode(code, rsp) {
              if(typeof(rsp) == 'undefined') rsp = response;
              return (response.substring(0, code.length) == code);
            }
            if(checkResponseCode('OK.')) {
              var responseText = response.substring(3, response.length);
              var responseData = JSON.parse(responseText);
              Chat.form.attachments.block.clear();
              for(let i = 1; i < responseData.length; i++) {
                Chat.form.attachments.block.add(responseData[i].filename, responseData[i].token);
              }
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
            else if(checkResponseCode('MIME.')) {
              notification_add('error', 'Ошибка', 'Недопустимый формат файла!<br>Разрешено загружать только текстовые файлы!', 5);
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
            else if(checkResponseCode('COUNT_LIM.')) {
              notification_add('error', 'Ошибка', 'Можно прикреплять не более 4 файлов к сообщению', 5);
            }
            else if(checkResponseCode('MEMORY_LIM.')) {
              notification_add('error', 'Ошибка', 'Недостаточно свободного пространства', 5);
            }
            else {
              notification_add('error', 'Ошибка сервера', 'Загрузка одного из файлов невозможна', 5);
              console.log('response: ' + response);
            }
          },
          error: function(jqXHR, status) {
            notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка', 5);
            console.log('error: ' + status + ', ' + jqXHR);
          }
        });
      },
      remove: function(id) {
        // remove block, get token
        var token = Chat.form.attachments.block.remove(id);
        // remove request
        $.ajax({
          type: 'POST',
          url: 'php/db_globalchat.php',
          data: {
            remove_attachment: true,
            file: token
          },
          beforeSend: function() {},
          complete: function() {},
          success: function(response) {
            function checkResponseCode(code, rsp) {
              if(typeof(rsp) == 'undefined') rsp = response;
              return (response.substring(0, code.length) == code);
            }
            if(checkResponseCode('OK.')) {
              //
            }
            else {
              console.log('error, response: ' + response);
            }
          },
          error: function(jqXHR, status) {
            console.log('error: ' + status + ', ' + jqXHR);
          }
        });
      },
      current: function() {
        $.ajax({
          type: 'POST',
          url: 'php/db_globalchat.php',
          data: {
            current_attachments: true
          },
          beforeSend: function() {},
          complete: function() {},
          success: function(response) {
            function checkResponseCode(code, rsp) {
              if(typeof(rsp) == 'undefined') rsp = response;
              return (response.substring(0, code.length) == code);
            }
            if(checkResponseCode('OK.')) {
              var responseText = response.substring(3, response.length);
              var responseData = JSON.parse(responseText);
              for(element in responseData) {
                if(element == 0) continue;
                Chat.form.attachments.block.add(responseData[element].filename, responseData[element].token);
              }
            }
            else {
              console.log('error, response: ' + response);
            }
          },
          error: function(jqXHR, status) {
            console.log('error: ' + status + ', ' + jqXHR);
          }
        });
      },
      block: {
        clear: function() {
          $('#attachments-container').empty();
          Chat.form.attachments.files = [];
          if(typeof(GlobalChatFiles.delAll) != 'undefined') { GlobalChatFiles.delAll(); }
        },
        add: function(filename, token, id) {
          var len = Object.keys(Chat.form.attachments.files).length;
          if(len >= 4) return false;
          // generate id
          if(typeof(id) == 'undefined') { id = Chat.form.attachments.counter; Chat.form.attachments.counter++; }
          if(typeof(id) == 'number') { id = 'attachment-block-id-' + String(id); }
          // add block
          GlobalChatFiles.add(filename, 'Chat.form.attachments.remove(&quot;'+id+'&quot;)');
          // add to array
          Chat.form.attachments.files[String(id)] = {
            id: id,
            token: token,
            filename: filename
          };
        },
        remove: function(id) {
          if(typeof(id) == 'number') { id = 'attachment-block-id-' + String(id); }
          // remove from array
          for(element in Chat.form.attachments.files) {
            if(element == id) {
              var token = Chat.form.attachments.files[element].token;
              delete Chat.form.attachments.files[element];
              return token;
            }
          }
        }
      }
    },
    users: {
      list: [],
      add: function(id, login, name1, name2, iconpath, blocked, admin) {
        // check
        if(typeof(blocked) == 'undefined') blocked = false;
        if(typeof(admin) == 'undefined') admin = false;
        // find user
        for(user in Chat.form.users.list) {
          if(Chat.form.users.list[user].id == id) return;
        }
        // add user
        Chat.form.users.list[Chat.form.users.list.length] = {
          id: id,
          login: login,
          name1: name1,
          name2: name2,
          icon: iconpath,
          blocked: blocked,
          admin: admin
        };
      },
      isBlocked: function(userid) {
        for(user in Chat.form.users.list) {
          if((Chat.form.users.list[user].id == userid) && Chat.form.users.list[user].blocked) return true;
        }
        return false;
      },
      switch: function(userid) {
        for(user in Chat.form.users.list) {
          if(Chat.form.users.list[user].id == userid && !Chat.form.users.list[user].admin) {
            if(Chat.form.users.list[user].blocked) {
              // DB request
              Chat.basic.user.restore(userid);
              // change buttons in msg history
              for(msg in Chat.history) {
                var msgid = Chat.history[msg].msgId;
                $('#msg-id-'+msgid+'-user-'+userid).text('Заблокировать пользователя');
              }
              // change buttons in users list
              $('#globalchat-users-list-all-user-' + userid).prop('title', 'Заблокировать');
              $('#globalchat-users-list-all-user-' + userid).css('background-color', '');
              $('#globalchat-users-list-all-user-' + userid).css('color', '');
              // blocked list
              $('#globalchat-users-list-blocked-user-' + userid).prop('title', 'Заблокировать');
              $('#globalchat-users-list-blocked-user-' + userid).css('background-color', '');
              $('#globalchat-users-list-blocked-user-' + userid).css('color', '');
            }
            else {
              // DB request
              Chat.basic.user.ban(userid);
              // change buttons in msg history
              for(msg in Chat.history) {
                var msgid = Chat.history[msg].msgId;
                $('#msg-id-'+msgid+'-user-'+userid).text('Разблокировать пользователя');
              }
              // change buttons in users list
              $('#globalchat-users-list-all-user-' + userid).prop('title', 'Разблокировать');
              $('#globalchat-users-list-all-user-' + userid).css('background-color', '#ff2525');
              $('#globalchat-users-list-all-user-' + userid).css('color', '#ffffff');
              // blocked list
              $('#globalchat-users-list-blocked-user-' + userid).prop('title', 'Разблокировать');
              $('#globalchat-users-list-blocked-user-' + userid).css('background-color', '#ff2525');
              $('#globalchat-users-list-blocked-user-' + userid).css('color', '#ffffff');
            }
            Chat.form.users.list[user].blocked = !Chat.form.users.list[user].blocked;
          }
        }
      },
      window: {
        all: function() {
          $('#globalchat-users-list-all').empty();
          for(user in Chat.form.users.list) {
            var data = Chat.form.users.list[user];
            let output = '';
            output += '<div class="chat-users-elem">\n';
            output += '<div class="chat-users-elem-text">\n';
            output += '<div class="chat-users-elem-text-ico" style="background-image: url(&quot;'+data.icon+'&quot;)"></div>\n';
            output += '<div class="chat-users-elem-text-text">\n';
            output += '<div class="chat-users-elem-text-text-name">'+data.name1+' '+data.name2+'</div>\n';
            output += '<div class="chat-users-elem-text-text-login">'+data.login+'</div>\n';
            output += '</div>\n';
            output += '</div>\n';
            output += '<div class="chat-users-elem-btn">\n';
            if(data.admin){
              output += '<div class="chat-users-elem-btn-elem icon-users" style="width: calc((100% / 1) - 23px);" title="Профиль" onclick="open_panel(' + "'#all_user'" + ')"></div>\n';
            } else{
              output += '<div class="chat-users-elem-btn-elem icon-users" title="Профиль" onclick="open_panel(' + "'#all_user'" + ')"></div>\n';
            }
            if(!data.admin) {
              if(data.blocked) {
                output += '<div class="chat-users-elem-btn-elem0 icon-lock" id="globalchat-users-list-all-user-'+data.id+'" style="background-color: #ff2525; color: #fff;" title="Разблокировать" onclick="Chat.form.users.switch('+data.id+');"></div>\n';
              }
              else {
                output += '<div class="chat-users-elem-btn-elem0 icon-lock" id="globalchat-users-list-all-user-'+data.id+'" title="Заблокировать" onclick="Chat.form.users.switch('+data.id+');"></div>';
              }
            }
            output += '</div>\n';
            output += '</div>\n';
            $('#globalchat-users-list-all').append(output);
          }
        },
        blocked: function() {
          $('#globalchat-users-list-blocked').empty();
          var empty = true;
          for(user in Chat.form.users.list) {
            var data = Chat.form.users.list[user];
            if(!data.blocked) continue;
            empty = false;
            let output = '';
            output += '<div class="chat-users-elem">\n';
            output += '<div class="chat-users-elem-text">\n';
            output += '<div class="chat-users-elem-text-ico" style="background-image: url(&quot;'+data.icon+'&quot;)"></div>\n';
            output += '<div class="chat-users-elem-text-text">\n';
            output += '<div class="chat-users-elem-text-text-name">'+data.name1+' '+data.name2+'</div>\n';
            output += '<div class="chat-users-elem-text-text-login">'+data.login+'</div>\n';
            output += '</div>\n';
            output += '</div>\n';
            output += '<div class="chat-users-elem-btn">\n';
            output += '<div class="chat-users-elem-btn-elem icon-users" title="Профиль" onclick="open_panel(' + "'#all_user'" + ')"></div>\n';
            if(!data.admin) {
              output += '<div class="chat-users-elem-btn-elem0 icon-lock" id="globalchat-users-list-blocked-user-'+data.id+'" style="background-color: #ff2525; color: #fff;" title="Разблокировать" onclick="Chat.form.users.switch('+data.id+');"></div>\n';
            }
            output += '</div>\n';
            output += '</div>\n';
            $('#globalchat-users-list-blocked').append(output);
          }
          // empty block
          if(empty) {
            let output = "";
                output += "<div class='chat-users-elem'>\n";
                output += "<div class='chat-users-elem-text' style='width: calc(100% - 0px);'>\n";
                output += "<div class='chat-users-elem-text-ico icon-question' style='background-color: #5d78ff;'></div>\n";
                output += "<div class='chat-users-elem-text-text'>\n";
                output += "<div class='chat-users-elem-text-text-name'>Список заблокированных пользователей пустой</div>\n";
                output += "<div class='chat-users-elem-text-text-login'>0 пользователей</div>\n";
                output += "</div>\n";
                output += "</div>\n";
                output += "</div>\n";

            $('#globalchat-users-list-blocked').append(output);
          }
        }
      }
    },
    msg: {
      switch: function(msgid) {
        for(msg in Chat.history) {
          if(Chat.history[msg].msgId == msgid) {
            if(Chat.history[msg].deleted) {
              Chat.basic.msg.restore(msgid);
              $('#msg-id-'+msgid+'-delswitch').text('Удалить');
              $('#msg-id-'+msgid+'-delswitch').parent().find('.chat-set-msg-elem-ico').attr('class','chat-set-msg-elem-ico icon-basket');
              $('#msg-id-'+msgid+'-msgopacity').css('opacity', '1');
              $('#msg-id-'+msgid+'-msgopacity').removeAttr('title');
            }
            else {
              Chat.basic.msg.delete(msgid);
              $('#msg-id-'+msgid+'-delswitch').text('Восстановить');
              $('#msg-id-'+msgid+'-delswitch').parent().find('.chat-set-msg-elem-ico').attr('class','chat-set-msg-elem-ico icon-recovery');
              $('#msg-id-'+msgid+'-msgopacity').css('opacity', '0.3');
              $('#msg-id-'+msgid+'-msgopacity').attr('title', 'Сообщение удалено');
            }
            Chat.history[msg].deleted = !Chat.history[msg].deleted;
          }
        }
      }
    }
  }
};
