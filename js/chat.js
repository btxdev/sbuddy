var infoOpen = false;
var scrollChat = 0;

function chatInfo(a){

  if(a == 'close'){
    if(device == 'phone'){
      if(infoOpen){
        infoOpen = false;
        $('.chat-main').css({'width':'100%','margin-left':'0px'})
        $('.chat-info').css({'width':'100%','margin-left':'-100vw'})
      } else{
        infoOpen = true;
        $('.chat-main').css({'width':'calc(100vw)','margin-left':'100vw'})
        $('.chat-info').css({'width':'100%','margin-left':'00px'})
      }
    } else{
      if(infoOpen){
        infoOpen = false;
        $('.chat-main').css({'width':'100%'})
        $('.chat-info').css({'margin-left':'-300px'})
      } else{
        infoOpen = true;
        $('.chat-main').css({'width':'calc(100% - 300px)'})
        $('.chat-info').css({'margin-left':'00px'})
      }
    }
  } else{
    if(device == 'phone'){
      if(infoOpen){
        infoOpen = false;
        $('.chat-main').css({'width':'100%','margin-left':'0vw'})
        $('.chat-info').css({'width':'100%','margin-left':'-300px'})
      } else{
        infoOpen = true;
        $('.chat-main').css({'width':'calc(100% - 00px)','margin-left':'100vw'})
        $('.chat-info').css({'width':'100%','margin-left':'00px'})
      }
    } else{
      if(infoOpen){
        infoOpen = false;
        $('.chat-main').css({'width':'100%'})
        $('.chat-info').css({'margin-left':'-300px'})
      } else{
        infoOpen = true;
        $('.chat-main').css({'width':'calc(100% - 300px)'})
        $('.chat-info').css({'margin-left':'00px'})
      }
    }
  }
}

function inputTextareaChat(block){
    if($(block).val().length > 10){
      $('.chat-main-msg-text-main-text > textarea').css({
        'height':'100%',
        'margin-top':'0px'
      })
    } else{
      $('.chat-main-msg-text-main-text > textarea').css({
        'height':'27px',
        'margin-top':'13px'
      })
    }
}

function openChatFull(){

}

function openEmojiBlock(){
  $('.emoji-block').css({
    'opacity':'1',
    'visibility':'visible'
  })

}

function addEmoji(a){
  var tmpCursorStart = $('#chat-textarea')[0].selectionStart,
      emoji = $(a).html(),
      text = $('#chat-textarea').val();

  var tmpStringEmoji = pasteIn(text, emoji, tmpCursorStart);

  $('#chat-textarea').val(tmpStringEmoji);

  $('#chat-textarea').blur()
  $('#chat-textarea').focus()

  $('#chat-textarea')[0].setSelectionRange(tmpCursorStart, tmpCursorStart);
}

function pasteIn(inStr, subStr, pos) {
  if(typeof(pos) == 'undefined') pos = inStr.length;
  return (inStr.substring(0, pos) + subStr + inStr.substring(pos, inStr.length));
}

var scrollTopBody = 0;
var scrollBottomChat = 0;

$(document).ready(function() {

  if(device != 'phone'){
    $('#chat-textarea').focus()
  }

  $('.chat-main-msg-msgs-text').scroll(function(){
    scrollBottomChat = $('.chat-main-msg-msgs-text').prop('scrollHeight') - $('.chat-main-msg-msgs-text').scrollTop() - $('.chat-main-msg-msgs-text').height();
  })

  $('#chat-textarea').focus(function(){
    if(device == 'phone'){
      if(scrollBottomChat <= 2){
        setTimeout(function(){
          $('.chat-main-msg-msgs-text').scrollTop($('.chat-main-msg-msgs-text').prop('scrollHeight'));
        }, 150)
      }
    }
  })

  $(fullBlockChId).on('change', function(){
    var status = $(this).prop('checked');
    if(status){
      scrollTopBody = $('html, body').scrollTop();

      $('html, body').animate({scrollTop:0}, '50');
      $('.chat-main').css({
        'position':'fixed',
        'z-index':'999',
        'top':'0'
      })
      $('body').css({
        'height':'100vh',
        'overflow':'hidden'
      })

    } else{
      $('.chat-main').css({
        'position':'relative',
        'z-index':'9',
        'top':'initial'
      })
      $('body').css({
        'height':'auto',
        'overflow':'auto'
      })
      $('html, body').animate({scrollTop: scrollTopBody}, '50');
    }
  })

  $('.chat-main-msg-text-main-text > textarea').on('input',function(){
    inputTextareaChat(this);
  });

  $(document).mouseup(function (e){
    var div = $(".emoji-block");

    if (!div.is(e.target) && div.has(e.target).length === 0 && !$('.emoji-block').is(e.target) && $('.emoji-block').has(e.target).length === 0) {
      div.css({
        'opacity':'0',
        'visibility':'hidden'
      });
  	}
  });


  Chat.form.init();

  window.addEventListener('beforeunload', Chat.basic.exit);

  $('.chat-main-msg-msgs-text').scroll(function(){
    scrollChatF();
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

});

msgSoundActive = false;
scrollInfo = true;

function scrollChatF(a){
  if(a == undefined){
    a = false;
  }
  // let maxHeightScroll = $('.chat-main-msg-msgs-text').prop('scrollHeight');
  let scrollTop = $('.chat-main-msg-msgs-text').scrollTop();

  if((scrollTop < 450 && scrollInfo) || a){
    scrollInfo = false;
    Chat.form.msgHistory();
  } else{
    //scrollInfo = true;
  }
  // console.log((scrollTop * 100 / maxHeightScroll).toFixed(2) + '%')
  // console.log(maxHeightScroll)


  //
}

function audioMsgChat(file){

  if(msgSoundActive){
    if($.cookie('sound_msg_site') == 'true'){

      var audioNotification = new Audio();
      audioNotification.src = 'media/audio/msg.mp3';
      audioNotification.autoplay = true;
    } else{
      window.navigator.vibrate(35)
    }
  }

}

var Chat = {
  history: [],
  basic: {
    send: function(msg, callback) {
      $.ajax({
        type: 'POST',
        url: 'php/chat/chat.php',
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
          else if(checkResponseCode('BLOCKED.')) {
            notification_add('warning', 'Невозможно отправить сообщение', 'Вы заблокированы администрацией сайта');
            if(typeof(callback) != 'undefined') { callback(msg, false); }
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
          url: 'php/chat/chat.php',
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
          url: 'php/chat/chat.php',
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
      }
    },
    online: function(callback) {
      $.ajax({
        type: 'POST',
        url: 'php/chat/chat.php',
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
      var url = 'php/chat/chat.php';
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
    }
  },
  form: {
    field: {},
    init: function() {
      $('#chat-msg-container').empty();
      // message history
      Chat.basic.msg.when(0, Chat.form.msgHistoryCallback);
      // new messages
      Chat.form.msgMonitorInterval = setInterval(Chat.form.msgMonitor, 1000);
      // scroll
      setTimeout(function() { $('.chat-main-msg-msgs-text').scrollTop($('.chat-main-msg-msgs-text').prop('scrollHeight')); }, 500);
      // hotkeys
      $('#chat-textarea').keydown(function (e) {
        // ctrl + enter
         if((e.ctrlKey || e.shiftKey) && e.keyCode == 13) {
           e.preventDefault();
           //$('#chat-textarea').val($('#chat-textarea').val() + '\n');
           $('#chat-textarea').focus().val($('#chat-textarea').val() + '\n');
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
     Chat.form.onlineListInterval = setInterval(Chat.form.onlineList, 150000);
     Chat.form.onlineList();
     // attachments
     Chat.form.attachments.block.clear();
     $('#attachments-input').on('change', function() { Chat.form.attachments.add(this.files); });
     Chat.form.attachments.current();
    },
    appendMode: 'pre',
    appendBuffer: [],
    addMsg: function(msgId, type, text, date, icon, name1, name2, username, userId, attachments, admin) {
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
        output += '<div class="chat-main-msg-msgs-text-main-elem-msg-file">';
        for(file in files) {
          if(file == 0) continue;
          var fileToken = files[file].token;
          if(admin) {
            output += '<a href="./admin/admin_panel2.0/media/users/public/'+login+'/Общий чат - Вложения/'+msgToken+'/'+fileToken+'" target="_blank">\n';
          }
          else {
            output += '<a href="./admin/admin_panel2.0/USERS_FILES/'+login+'/Общий чат - Вложения/'+msgToken+'/'+fileToken+'" target="_blank">\n';
          }
          output += '<div class="chat-main-msg-msgs-text-main-elem-msg-file-elem" title="Нажмите для открытия">\n';
          output += '<div class="chat-main-msg-msgs-text-main-elem-msg-file-elem-ico icons-files"></div>\n';
          output += '<div class="chat-main-msg-msgs-text-main-elem-msg-file-elem-name" title="'+files[file].filename+'">'+files[file].filename+'</div>\n';
          output += '</div></a>\n';
        }
        output += '</div>';
        //return '';
        return output;
      }
      // output
      if(type == 'my_first') {
        var output = '';
        output += '<div class="chat-main-msg-msgs-text-main-elemI" id="msg-id-'+msgId+'">\n';
        output += '<div class="chat-main-msg-msgs-text-main-elemI-msg">\n';

        if(text.match(/^([\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2694-\u2697]|\uD83E[\uDD10-\uDD5D])$/)){
          if(text.match(/^(❤|🧡|💛|💚|💙|💜|🤎|🖤|🤍)$/)){
            output += '<div class="chat-main-msg-msgs-text-main-elem-msg-text" style="font-size: 40px; animation: heart 1.1s infinite linear; display: inline-block;">'+text+'</div>\n';
          } else{
            output += '<div class="chat-main-msg-msgs-text-main-elem-msg-text" style="font-size: 40px;">'+text+'</div>\n';
          }

        } else if(text.match(/^((https|http)?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/)){
          output += '<a title="Нажмите для открытия ссылки" href=' + text + ' style="color: var(--colorLogo); text-decoration: underline;" target="_blank" class="chat-main-msg-msgs-text-main-elem-msg-text">'+text+'</a>\n';
        } else if(text.match(/^(👨‍💻🎸|🎸👨‍💻|#INSOweb)$/ui)){
          output += '<a title="Нажмите для открытия ссылки" href="http://insoweb.ru/" style="color: var(--orange); text-decoration: underline;" target="_blank" class="chat-main-msg-msgs-text-main-elem-msg-text">Сайт разработан INSOweb</a>\n';
        } else{
          output += '<div class="chat-main-msg-msgs-text-main-elem-msg-text">'+text+'</div>\n';
        }

        output += printAttachments(attachments);

        output += '</div>\n';
        output += '<div class="chat-main-msg-msgs-text-main-elemI-photo" title="'+username+'" style="background-image: url(&quot;'+icon+'&quot;);"></div>\n';
        output += '<div class="chat-main-msg-msgs-text-main-elemI-info">\n';
        output += '<div class="chat-main-msg-msgs-text-main-elem-info-ico icons-good" title="Прочитано" style="color: var(--green);"></div>\n';
        output += '<div class="chat-main-msg-msgs-text-main-elem-info-date" title="'+time+'">'+timeMin+'</div>\n';
        output += '</div>\n';
        output += '</div>\n';
      }
      if(type == 'my') {
        var output = '';
        output += '<div class="chat-main-msg-msgs-text-main-elemI" id="msg-id-'+msgId+'">\n';
        output += '<div class="chat-main-msg-msgs-text-main-elemI-msg2">\n';

        if(text.match(/^([\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2694-\u2697]|\uD83E[\uDD10-\uDD5D])$/)){
          if(text.match(/^(❤|🧡|💛|💚|💙|💜|🤎|🖤|🤍)$/)){
            output += '<div class="chat-main-msg-msgs-text-main-elem-msg-text" style="font-size: 40px; animation: heart 1.1s infinite linear; display: inline-block;">'+text+'</div>\n';
          } else{
            output += '<div class="chat-main-msg-msgs-text-main-elem-msg-text" style="font-size: 40px;">'+text+'</div>\n';
          }
        } else if(text.match(/^((https|http)?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/)){
          output += '<a title="Нажмите для открытия ссылки" href=' + text + ' style="color: var(--colorLogo); text-decoration: underline;" target="_blank" class="chat-main-msg-msgs-text-main-elem-msg-text">'+text+'</a>\n';
        } else if(text.match(/^(👨‍💻🎸|🎸👨‍💻|#INSOweb)$/ui)){
          output += '<a title="Нажмите для открытия ссылки" href="http://insoweb.ru/" style="color: var(--orange); text-decoration: underline;" target="_blank" class="chat-main-msg-msgs-text-main-elem-msg-text">Сайт разработан INSOweb</a>\n';
        } else{
          output += '<div class="chat-main-msg-msgs-text-main-elem-msg-text">'+text+'</div>\n';
        }

        output += printAttachments(attachments);

        output += '</div>\n';
        output += '<div class="chat-main-msg-msgs-text-main-elemI-info">\n';
        output += '<div class="chat-main-msg-msgs-text-main-elem-info-ico icons-good" title="Прочитано" style="color: var(--green);"></div>\n';
        output += '<div class="chat-main-msg-msgs-text-main-elem-info-date" title="'+time+'">'+timeMin+'</div>\n';
        output += '</div>\n';
        output += '</div>\n';
      }
      if(type == 'another_first') {
        var output = '';
        output += '<div class="chat-main-msg-msgs-text-main-elem" id="msg-id-'+msgId+'">\n';
        output += '<div class="chat-main-msg-msgs-text-main-elem-photo" title="'+username+'" style="background-image: url(&quot;'+icon+'&quot;);"></div>\n';
        output += '<div class="chat-main-msg-msgs-text-main-elem-msg">\n';

        // admin
        if(admin) {
          output += '<div class="chat-main-msg-msgs-text-main-elem-msg-name1" data-rights="&#128081;" style="color: #da9700" title="Администратор">'+name1+' '+name2+'</div>\n';
        }
        else {
          output += '<div class="chat-main-msg-msgs-text-main-elem-msg-name">'+name1+' '+name2+'</div>\n';
        }

        if(text.match(/^([\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2694-\u2697]|\uD83E[\uDD10-\uDD5D])$/)){
          if(text.match(/^(❤|🧡|💛|💚|💙|💜|🤎|🖤|🤍)$/)){
            output += '<div class="chat-main-msg-msgs-text-main-elem-msg-text" style="font-size: 40px; animation: heart 1.1s infinite linear; display: inline-block;">'+text+'</div>\n';
          } else{
            output += '<div class="chat-main-msg-msgs-text-main-elem-msg-text" style="font-size: 40px;">'+text+'</div>\n';
          }
        } else if(text.match(/^((https|http)?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/)){
          output += '<a title="Нажмите для открытия ссылки" href=' + text + ' style="color: var(--colorLogo); text-decoration: underline;" target="_blank" class="chat-main-msg-msgs-text-main-elem-msg-text">'+text+'</a>\n';
        } else if(text.match(/^(👨‍💻🎸|🎸👨‍💻|#INSOweb)$/ui)){
          output += '<a title="Нажмите для открытия ссылки" href="http://insoweb.ru/" style="color: var(--orange); text-decoration: underline;" target="_blank" class="chat-main-msg-msgs-text-main-elem-msg-text">Сайт разработан INSOweb</a>\n';
        } else{
          output += '<div class="chat-main-msg-msgs-text-main-elem-msg-text">'+text+'</div>\n';
        }

        output += printAttachments(attachments);

        output += '</div>\n';
        output += '<div class="chat-main-msg-msgs-text-main-elem-info">\n';
        output += '<div class="chat-main-msg-msgs-text-main-elem-info-ico icons-good" title="Прочитано" style="color: var(--green);"></div>\n';
        output += '<div class="chat-main-msg-msgs-text-main-elem-info-date" title="'+time+'">'+timeMin+'</div>\n';
        output += '</div>\n';
        output += '</div>\n';
      }
      if(type == 'another') {
        var output = '';
        output += '<div class="chat-main-msg-msgs-text-main-elem" id="msg-id-'+msgId+'">\n';
        output += '<div class="chat-main-msg-msgs-text-main-elem-msg2">\n';

        if(text.match(/^([\uE000-\uF8FF]|\uD83C[\uDC00-\uDFFF]|\uD83D[\uDC00-\uDFFF]|[\u2694-\u2697]|\uD83E[\uDD10-\uDD5D])$/)){
          if(text.match(/^(❤|🧡|💛|💚|💙|💜|🤎|🖤|🤍)$/)){
            output += '<div class="chat-main-msg-msgs-text-main-elem-msg-text" style="font-size: 40px; animation: heart 1.1s infinite linear; display: inline-block;">'+text+'</div>\n';
          } else{
            output += '<div class="chat-main-msg-msgs-text-main-elem-msg-text" style="font-size: 40px;">'+text+'</div>\n';
          }
        } else if(text.match(/^((https|http)?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/)){
          output += '<a title="Нажмите для открытия ссылки" href=' + text + ' style="color: var(--colorLogo); text-decoration: underline;" target="_blank" class="chat-main-msg-msgs-text-main-elem-msg-text">'+text+'</a>\n';
        } else if(text.match(/^(👨‍💻🎸|🎸👨|#INSOweb‍💻)ui$/)){
          output += '<a title="Нажмите для открытия ссылки" href="http://insoweb.ru/" style="color: var(--orange); text-decoration: underline;" target="_blank" class="chat-main-msg-msgs-text-main-elem-msg-text">Сайт разработан INSOweb</a>\n';
        } else{
          output += '<div class="chat-main-msg-msgs-text-main-elem-msg-text">'+text+'</div>\n';
        }

        output += printAttachments(attachments);

        output += '</div>\n';
        output += '<div class="chat-main-msg-msgs-text-main-elem-info">\n';
        output += '<div class="chat-main-msg-msgs-text-main-elem-info-ico icons-good" style="color: var(--green);"></div>\n';
        output += '<div class="chat-main-msg-msgs-text-main-elem-info-date" title="'+time+'">'+timeMin+'</div>\n';
        output += '</div>\n';
        output += '</div>\n';
      }
      // append data
      if(Chat.form.appendMode == 'pre') {
        $('#chat-msg-container').prepend(output);
        Chat.history.unshift({
          msgId: msgId,
          userId: userId,
          username: username,
          type: type
        });
      }
      if(Chat.form.appendMode == 'post') {
        $('#chat-msg-container').append(output);
        Chat.history.push({
          msgId: msgId,
          userId: userId,
          username: username,
          type: type
        });
      }
    },
    sendCallback: function(msg, msgId) {
      if(msgId === false) return;
      if(Object.keys(Chat.form.attachments.files).length == 0) {
        Chat.form.lastMsgCallback([{
          message: {
            id: msgId,
            text: escapeHtml(msg).replace(/[\n\r]/g, '<br>'),
            date: getCurrentDateTimeMySql()
          },
          user: {
            name1: userData['name1'],
            name2: userData['name1'],
            login: userData['account'],
            id: userData['account_id']
          }
        }], userData['profile_icon']);
        $('.chat-main-msg-msgs-text').scrollTop($('.chat-main-msg-msgs-text').prop('scrollHeight'));
        $('.chat-main-msg-msgs-text-main-hello').css({
          'display':'none'
        });
      }
      else {
        Chat.form.attachments.block.clear();
      }
    },
    send: function() {
      var msg = $('#chat-textarea').val();
      var msgTest = msg.replace(/\s+/g, '');
      msgTest = msgTest.replace(/[\n\r]/g, '');
      if(msgTest.length == 0 && (Object.keys(Chat.form.attachments.files).length == 0)) return;
      $('#chat-textarea').focus().val('');
      inputTextareaChat('.chat-main-msg-text-main-text > textarea');
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
              icon = '../admin/admin_panel2.0/media/users/public/' + theMsg.user.login + '/profile.jpg';
            }
            else {
              var iconId = theMsg.user.icon.substring(theMsg.user.icon.lastIndexOf('_') + 1, theMsg.user.icon.length);
              icon = '../admin/admin_panel2.0/media/users/' + iconId + '.jpg';
            }
          }
          else {
            icon = 'users/public/' + theMsg.user.login + '/avatar.png';
            if(theMsg.user.icon == 'null' || theMsg.user.icon == null || typeof(theMsg.user.icon) == 'null') {
              if(theMsg.user.gender == 'male') { icon = 'media/svg/male_avatar.svg'; }
              else { icon = 'media/svg/female_avatar.svg'; }
            }
          }
          // get message type
          var type = 'another_first';
          if(!theMsg.admin && (theMsg.user.id == userData['account_id'])) {
            if(nextMsg != false && nextMsg.user.id == theMsg.user.id) { type = 'my'; }
            else { type = 'my_first'; }
          }
          else {
            if(nextMsg != false && nextMsg.user.id == theMsg.user.id) { type = 'another'; }
            else { type = 'another_first'; }
          }
          // add message
          Chat.form.appendMode = 'pre';
          Chat.form.addMsg(theMsg.message.id, type, theMsg.message.text, theMsg.message.date, icon, theMsg.user.name1, theMsg.user.name2, theMsg.user.login, theMsg.user.id, theMsg.attachments, theMsg.admin);
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
    lastMsgCallback: function(messages, customIcon) {
      if(typeof(messages) == 'object') {
        // new message by another user
        if(messages.length > 0 && typeof(customIcon) == 'undefined') {
          // sound
          audioMsgChat();
          // scroll
          setTimeout(function() { $('.chat-main-msg-msgs-text').scrollTop($('.chat-main-msg-msgs-text').prop('scrollHeight')); }, 100);
        }
        //messages = messages.reverse();
        for(let i = 0; i < messages.length; i++) {
          // get previous message
          var lastMsg = false;
          if(Chat.history.length > 0) { lastMsg = Chat.history[Chat.history.length - 1]; }
          var theMsg = messages[i];
          // get profile icon
          var icon;
          if(theMsg.admin) {
            if(theMsg.user.icon == 'PROFILE') {
              icon = '../admin/admin_panel2.0/media/users/public/' + theMsg.user.login + '/profile.jpg';
            }
            else {
              var iconId = theMsg.user.icon.substring(theMsg.user.icon.lastIndexOf('_') + 1, theMsg.user.icon.length);
              icon = '../admin/admin_panel2.0/media/users/' + iconId + '.jpg';
            }
          }
          else {
            icon = 'users/public/' + theMsg.user.login + '/avatar.png';
            if(theMsg.user.icon == 'null' || theMsg.user.icon == null || typeof(theMsg.user.icon) == 'null') {
              if(theMsg.user.gender == 'male') { icon = 'media/svg/male_avatar.svg'; }
              else { icon = 'media/svg/female_avatar.svg'; }
            }
          }
          if(typeof(customIcon) != 'undefined') {
            icon = customIcon;
          }
          // get message type
          var type = 'another_first';
          if(!theMsg.admin && (theMsg.user.id == userData['account_id'])) {
            if(lastMsg != false && lastMsg.userId == theMsg.user.id) { type = 'my'; }
            else { type = 'my_first'; }
          }
          else {
            if(lastMsg != false && lastMsg.userId == theMsg.user.id) { type = 'another'; }
            else { type = 'another_first'; }
          }
          // add message
          Chat.form.appendMode = 'post';
          Chat.form.addMsg(theMsg.message.id, type, theMsg.message.text, theMsg.message.date, icon, theMsg.user.name1, theMsg.user.name2, theMsg.user.login, theMsg.user.id, theMsg.attachments, theMsg.admin);
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
      $('#chat-title-count').attr('data-peoples', title);
      $('#chat-title-count').attr('title', title);
      $('#chat-ulist-title').attr('data-peoples', String(count));
      $('#chat-ulist-title').attr('title', title);
      // add elements
      $('#chat-ulist-container').empty();
      for(let i = 0; i < count; i++) {
        // get profile icon
        var icon;
        if(data[i].admin) {
          if(data[i].icon == 'PROFILE') {
            icon = '../admin/admin_panel2.0/media/users/public/' + data[i].login + '/profile.jpg';
          }
          else {
            var iconId = data[i].icon.substring(data[i].icon.lastIndexOf('_') + 1, data[i].icon.length);
            icon = '../admin/admin_panel2.0/media/users/' + iconId + '.jpg';
          }
        }
        else {
          icon = 'users/public/' + data[i].login + '/avatar.png';
          if(data[i].icon == 'null' || data[i].icon == null || typeof(data[i].icon) == 'null') {
            if(data[i].gender == 'male') { icon = 'media/svg/male_avatar.svg'; }
            else { icon = 'media/svg/female_avatar.svg'; }
          }
        }
        var output = '';
        output += '<div class="chat-info-container-main-elem" style="margin-bottom: 10px;">\n';
        output += '<div class="chat-info-container-main-elem-photo" style="background-image: url(&quot;' + icon + '&quot;)"></div>\n';
        output += '<div class="chat-info-container-main-elem-name">' + data[i].name1 + ' ' + data[i].name2 + '</div>\n';
        output += '</div>\n';
        $('#chat-ulist-container').append(output);
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
          url: 'php/chat/chat.php',
          type: 'POST',
          data: data,
          cache: false,
          processData: false,
          contentType: false,
          beforeSend: function() {
            loaderMain('show');
          },
          complete: function() {
            loaderMain('hidden');
            // clear input
            $('#attachments-input').val('');
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
              notification_add('error', 'Ошибка загрузки файла', 'Страница будет перезагружена');
              setTimeout(function() {
                document.location.reload(true);
              }, 1000);
            }
            else if(checkResponseCode('INVALID_PARAMETERS.')) {
              notification_add('error', 'Ошибка', 'Данные повреждены');
            }
            else if(checkResponseCode('WRONG_FILENAME.')) {
              notification_add('error', 'Ошибка', 'Недопустимое имя файла');
            }
            else if(checkResponseCode('MIME.')) {
              notification_add('error', 'Ошибка', 'Недопустимый формат файла!<br>Разрешено загружать только текстовые файлы!');
            }
            else if(checkResponseCode('NO_FILE.')) {
              notification_add('error', 'Ошибка', 'Сервер принял пустой запрос');
            }
            else if(checkResponseCode('LIMIT.')) {
              notification_add('error', 'Ошибка', 'Превышен допустимый размер файла');
            }
            else if(checkResponseCode('DOWNLOADING_ERROR.')) {
              notification_add('error', 'Ошибка сервера', 'Не удалось сохранить файл');
            }
            else if(checkResponseCode('COUNT_LIM.')) {
              notification_add('error', 'Ошибка', 'Можно прикреплять не более 4 файлов к сообщению');
            }
            else if(checkResponseCode('MEMORY_LIM.')) {
              notification_add('error', 'Ошибка', 'Недостаточно свободного пространства');
            }
            else {
              notification_add('error', 'Ошибка сервера', 'Загрузка одного из файлов невозможна');
              console.log('response: ' + response);
            }
          },
          error: function(jqXHR, status) {
            notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
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
          url: 'php/chat/chat.php',
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
          url: 'php/chat/chat.php',
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
        updateCss: function() {
          var count = Object.keys(Chat.form.attachments.files).length;
          if(count == 0) {
            $('.chat-main-msg-text').css('height', '74px');
            $('.chat-main-msg-msgs').css('height', 'calc(100% - 75px)');
            return;
          }
          if(device == 'pc') {
            if(count <= 2) {
              $('.chat-main-msg-msgs').css('height', 'calc(100% - 127px)');
            }
            else {
              $('.chat-main-msg-msgs').css('height', 'calc(100% - 184px)');
            }
          }
          else {
            switch(count) {
              case 1:
                $('.chat-main-msg-msgs').css('height', 'calc(100% - 129px)');
                break;
              case 2:
                $('.chat-main-msg-msgs').css('height', 'calc(100% - 184px)');
                break;
              case 3:
                $('.chat-main-msg-msgs').css('height', 'calc(100% - 240px)');
                break;
              case 4:
                $('.chat-main-msg-msgs').css('height', 'calc(100% - 295px)');
                break;
            }
          }
        },
        clear: function() {
          $('#attachments-container').empty();
          Chat.form.attachments.files = [];
          // style
          Chat.form.attachments.block.updateCss();
        },
        add: function(filename, token, id) {
          var len = Object.keys(Chat.form.attachments.files).length;
          if(len >= 4) return false;
          // generate id
          if(typeof(id) == 'undefined') { id = Chat.form.attachments.counter; Chat.form.attachments.counter++; }
          if(typeof(id) == 'number') { id = 'attachment-block-id-' + String(id); }
          // add block
          var output = '';
          output += '<div class="chat-main-msg-text-file-elem" id="'+id+'" title="'+filename+'">\n';
          output += '<div class="chat-main-msg-text-file-elem-del icons-plus" title="Удалить" onclick="Chat.form.attachments.remove(\''+id+'\');"></div>\n';
          output += '<div class="chat-main-msg-text-file-elem-ico icons-files"></div>\n';
          output += '<div class="chat-main-msg-text-file-elem-name">'+filename+'</div>\n';
          output += '</div>\n';
          $('#attachments-container').append(output);
          // add to array
          Chat.form.attachments.files[String(id)] = {
            id: id,
            token: token,
            filename: filename
          };
          // style
          Chat.form.attachments.block.updateCss();
        },
        remove: function(id) {
          if(typeof(id) == 'number') { id = 'attachment-block-id-' + String(id); }
          // remove block
          $('#' + id).remove();
          // remove from array
          for(element in Chat.form.attachments.files) {
            if(element == id) {
              var token = Chat.form.attachments.files[element].token;
              delete Chat.form.attachments.files[element];
              // style
              Chat.form.attachments.block.updateCss();
              //
              return token;
            }
          }
        }
      }
    }
  }
};
