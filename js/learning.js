$(document).ready(function() {
  // onload
  /*$('.window-container-text-main-elem-title-ch-learning-online').on('change', function(){
    if($(this).prop('checked')){
      $(this).parent().parent().parent().find('.window-container-text-main-elem-main').css('max-height','500px')
    } else{
      $(this).parent().parent().parent().find('.window-container-text-main-elem-main').css('max-height','0px')
    }
  })*/
  /*$('.window-container-text-main-elem-main-main-btn').click(function(){
    var idTime = idGenerator();
    let output = '';
        output += '<div class="window-container-text-main-elem-main-main-time" style="max-height: 0px; overflow: hidden;" id=' + idTime + '>\n';
        output += '<input class="window-container-text-main-elem-main-main-time-input" placeholder="123" required="" type="time">\n';
        output += '<span>-</span>\n';
        output += '<input class="window-container-text-main-elem-main-main-time-input" required="" type="time">\n';
        output += '<span class="window-container-text-main-elem-main-main-time-del icons-plus" onclick="timeLearningDel(this)" title=""></span>\n';
        output += '</div>\n';
    $(this).parent().parent().find('.window-container-text-main-elem-main-main-time-span').append(output)

    setTimeout(function(){
      $('#' + idTime).css({
        'max-height':'36px',
      })
    }, 1)
  });*/
  /*$('.window-container-text-main-elem-main-main-time-del').click(function(){
    timeLearningDel(this);
  });*/

  Learning.recorded();

  for(var i = 0; i < 7; i++) {
    // online
    $('#learning-window-day'+String(i)+'-btn-add').click({day: i}, function(event) {
      Learning.online.add(event.data.day);
      Learning.online.updateDOM();
    });
    $('#id-learning-online-'+String(i)).change({day: i}, function(event) {
      if($(this).prop('checked')) {
        Learning.online.add(event.data.day);
      }
      else {
        Learning.online.days.pres[event.data.day] = [];
      }
      Learning.online.updateDOM();
    });
    // group
    $('#learning-window-day'+String(i)+'-btn-add').click({day: i}, function(event) {
      Learning.group.add(event.data.day);
      Learning.group.updateDOM();
    });
    $('#id-learning-online-'+String(i)).change({day: i}, function(event) {
      if($(this).prop('checked')) {
        Learning.group.add(event.data.day);
      }
      else {
        Learning.group.days.pres[event.data.day] = [];
      }
      Learning.group.updateDOM();
    });
  }

});

/*function timeLearningDel(block){
  $(block).parent().css({
    'max-height':'0px',
    'overflow':'hidden'
  })
  setTimeout(function(){
    $(block).parent().remove();
  }, 180)
}*/

var Learning = {
  field: {
    window: 'learning-online',
    title: 'learning-window-title',
    btn: 'learning-window-btn-save'
  },
  recorded: function() {
    if(typeof(userData['account_id']) == 'undefined') return;
    $.ajax({
      type: 'POST',
      url: 'php/db_learning.php',
      data: {
        im_recorded: true
      },
      success: function(response) {
        function checkResponseCode(code, rsp) {
          if(typeof(rsp) == 'undefined') rsp = response;
          return (response.substring(0, code.length) == code);
        }
        if(checkResponseCode('OK.')) {
          var responseText = response.substring(3, response.length);
          var responseData = JSON.parse(responseText);
          for(i in responseData) {
            var record = responseData[i];
            if(record.learning == 'online') {
              // create notification
              if(record.notify) {
                notification_add('info', 'Обучение', 'Вы были записаны на онлайн обучение', 10);
              }
              // add blocks
              for(j in record.groups) {
                var group = record.groups[j];
                Learning.online.reg.status = true;
                Learning.online.reg.groups = [group];
                break;
              }
            }
            if(record.learning == 'group') {
              // create notification
              if(record.notify) {
                notification_add('info', 'Обучение', 'Вы были записаны на групповое обучение', 10);
              }
              // add blocks
              for(j in record.groups) {
                var group = record.groups[j];
                Learning.group.reg.status = true;
                Learning.group.reg.groups = [group];
                break;
              }
            }
          }
        }
        else {
          console.log('error: ' + response);
        }
      },
      error: function(jqXHR, status) {
        console.log('error: ' + status + ', ' + jqXHR);
      }
    });
  },
  load: function(type) {
    $.ajax({
      type: 'POST',
      url: 'php/db_learning.php',
      data: {
        load_records: true
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
          var responseText = response.substring(3, response.length);
          var responseData = JSON.parse(responseText);
          for(var i = 0; i < responseData.length; i++) {
            var record = responseData[i];
            if(record.learning == 'online') {
              Learning.online.cancel();
              if(Learning.online.set(record.day, record.timerange) === false) console.error('wtf');
            }
            if(record.learning == 'group') {
              Learning.group.cancel();
              if(Learning.group.set(record.day, record.timerange) === false) console.error('wtf');
            }
          }
          if(typeof(type) == 'undefined') { type = 'online'; }
          if(type == 'online') {
            Learning.online.display();
          }
          if(type == 'group') {
            Learning.group.display();
          }
        }
        else if(checkResponseCode('AUTH.')) {
          notification_add('error', 'Войдите в аккаунт', 'Чтобы записаться на занятия, необходимо авторизироваться!');
          console.error('AUTH');
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
  online: {
    days: {
      pres: [[], [], [], [], [], [], []],
      prev: [[], [], [], [], [], [], []]
    },
    reg: {
      status: false,
      groups: [],
      date: ''
    },
    idc: 0,
    set: function(day, timerange) {
      var timerangeArray = timerange.split(',');
      if(timerangeArray.length % 2 != 0) return false;
      var ranges = [];
      for(var i = 0; i < Math.floor(timerangeArray.length / 2); i++) {
        ranges[i] = {
          id: Learning.online.idc++,
          start: timerangeArray[i * 2],
          end: timerangeArray[i * 2 + 1]
        };
      }
      Learning.online.days.pres[day] = objClone(ranges);
      Learning.online.days.prev[day] = objClone(ranges);
    },
    remove: function(day, time) {
      // define id's
      var element = '#learning-elem-day'+String(day)+'-time'+String(time)+'-t0';
      var checkbox = '#id-learning-online-' + String(day);
      // find and remove time-range from array
      for(var i = 0; i < Learning.online.days.pres[day].length; i++) {
        if(Learning.online.days.pres[day][i].id == time) {
          Learning.online.days.pres[day].splice(i, 1);
          break;
        }
      }
      // hide time-range block
      $(element).parent().css({'max-height':'0px', 'overflow':'hidden'});
      setTimeout(function() { $(element).parent().remove(); }, 180);
      // remove all blocks and time-range elements (when chkbx unchecked)
      var len = Learning.online.days.pres[day].length;
      if(len == 0) {
        $(checkbox).prop('checked', false);
        $(element).parent().parent().parent().parent().css('max-height', '0');
        Learning.online.days.pres[day] = [];
      }
    },
    add: function(day) {
      var len = Learning.online.days.pres[day].length;
      Learning.online.days.pres[day][len] = {
        id: Learning.online.idc++,
        start: '00:00',
        end: '23:59'
      };
      Learning.online.formCheck();
    },
    val: function(day, rangeId, isStart, time) {
      // find time-range from array
      for(var i = 0; i < Learning.online.days.pres[day].length; i++) {
        if(Learning.online.days.pres[day][i].id == rangeId) {
          if(isStart) { Learning.online.days.pres[day][i].start = time; }
          else { Learning.online.days.pres[day][i].end = time; }
        }
      }
    },
    formCheck: function() {
      for(var day = 0; day < 7; day++) {
        for(range in Learning.online.days.pres[day]) {
          var id0 = '#learning-elem-day' + day + '-time' + Learning.online.days.pres[day][range].id + '-t0';
          var id1 = '#learning-elem-day' + day + '-time' + Learning.online.days.pres[day][range].id + '-t1';
          var t0 = $(id0).val();
          var t1 = $(id1).val();
          if(t0 > t1) {
            $(id0).val(t1);
            Learning.online.days.pres[day][range].start = t1;
          }
        }
      }
    },
    updateDOM: function() {
      for(var i = 0; i < 7; i++) {
        var day = Learning.online.days.pres[i];
        var element = '#id-learning-online-' + String(i);
        if(day.length > 0) {
          var output = '';
          for(var j = 0; j < day.length; j++) {
            output += '<div class="window-container-text-main-elem-main-main-time" style="max-height: 36px; overflow: hidden;">\n';
            output += '<input class="window-container-text-main-elem-main-main-time-input" required="" type="time" id="learning-elem-day'+i+'-time'+day[j].id+'-t0" onchange="Learning.online.val('+i+', '+day[j].id+', true, this.value);" value="'+day[j].start+'">\n';
            output += '<span>-</span>\n';
            output += '<input class="window-container-text-main-elem-main-main-time-input" required="" type="time" id="learning-elem-day'+i+'-time'+day[j].id+'-t1" onchange="Learning.online.val('+i+', '+day[j].id+', false, this.value);" value="'+day[j].end+'">\n';
            output += '<span class="window-container-text-main-elem-main-main-time-del icons-plus" onclick="Learning.online.remove('+i+', '+day[j].id+')" title=""></span>\n';
            output += '</div>\n';
          }
          $(element).parent().parent().parent().find('.window-container-text-main-elem-main-main-time-span').empty();
          $(element).parent().parent().parent().find('.window-container-text-main-elem-main-main-time-span').append(output);
          $(element).prop('checked', true);
          $(element).parent().parent().parent().find('.window-container-text-main-elem-main').css('max-height', '500px');
        }
        else {
          $(element).prop('checked', false);
          $(element).parent().parent().parent().find('.window-container-text-main-elem-main').css('max-height', '0');
        }
      }
    },
    display: function() {
      // title
      $('#' + Learning.field.title).text('Запись на онлайн обучение');
      // reg status
      if(Learning.online.reg.status) {
        var group = Learning.online.reg.groups;
        //var date = Learning.online.reg.date;
        //var day = 0;
        //var dayRu = 'Вторник';
        //$('#learning-window-reg-block .window-container-text-learning-text-main').html('Ваша группа <b style="font-family: pfr;">'+group+'</b><br>Следующее занятие в '+dayRu+' <i>('+date+')</i>.');
        $('#learning-window-reg-block .window-container-text-learning-text-main').html('Ваша группа: <b style="font-family: pfr;">'+group+'</b><br><br>Вы можете посмотреть расписание <a href="Online learning.php" style="text-decoration: underline;">тут</a>');
        $('#learning-window-reg-block').css('display', 'block');
      }
      else {
        $('#learning-window-reg-block').css('display', 'none');
      }
      // days
      Learning.online.updateDOM();
      // events
      for(var i = 0; i < 7; i++) {
        // disable events
        $('#learning-window-day'+String(i)+'-btn-add').off();
        $('#id-learning-online-'+String(i)).off();
        // add events (online)
        $('#learning-window-day'+String(i)+'-btn-add').click({day: i}, function(event) {
          Learning.online.add(event.data.day);
          Learning.online.updateDOM();
        });
        $('#id-learning-online-'+String(i)).change({day: i}, function(event) {
          if($(this).prop('checked')) {
            Learning.online.add(event.data.day);
          }
          else {
            Learning.online.days.pres[event.data.day] = [];
          }
          Learning.online.updateDOM();
        });
      }
      // button
      $('#' + Learning.field.btn).off();
      $('#' + Learning.field.btn).click(function() { Learning.online.save(); });
      // open
      windowOpen('#' + Learning.field.window);
    },
    save: function(daysObj) {
      if(typeof(daysObj) == 'object') {
        Learning.online.days.prev = objClone(daysObj);
        Learning.online.days.pres = objClone(daysObj);
      }
      else {
        Learning.online.days.prev = objClone(Learning.online.days.pres);
      }
      // save in DB
      function formOk() {
        notification_add('line', '', 'Заявка отправлена');
        windowClose($('#' + Learning.field.window), true);
      }
      var errorTimeout;
      var okTimeout;
      for(var day = 0; day < 7; day++) {
        var timerangeArray = [];
        var i = 0;
        for(rangeElem in Learning.online.days.pres[day]) {
          timerangeArray[i] = Learning.online.days.pres[day][rangeElem].start;
          timerangeArray[i + 1] = Learning.online.days.pres[day][rangeElem].end;
          i += 2;
        }
        $.ajax({
          type: 'POST',
          url: 'php/db_learning.php',
          data: {
            update_record: true,
            learning: 'online',
            day: day,
            time: timerangeArray.join(',')
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
              clearTimeout(okTimeout);
              okTimeout = setTimeout(formOk, 500);
              Learning.online.updateDOM();
            }
            else if(checkResponseCode('AUTH.')) {
              document.location.reload(true);
            }
            else {
              clearTimeout(errorTimeout);
              errorTimeout = setTimeout(function() { notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка'); }, 1000);
              console.log('error: ' + response);
            }
          },
          error: function(jqXHR, status) {
            clearTimeout(errorTimeout);
            errorTimeout = setTimeout(function() { notification_add('error', 'Ошибка', 'Невозможно установить соединение'); }, 1000);
            console.log('error: ' + status + ', ' + jqXHR);
          }
        });
      }
    },
    cancel: function() {
      // clone array
      for(day in Learning.online.days.prev) {
        var tmpArray = [];
        for(elem in Learning.online.days.prev[day]) {
          tmpArray[tmpArray.length] = objClone(Learning.online.days.prev[day][elem]);
        }
        Learning.online.days.pres[day] = tmpArray;
      }
      Learning.online.updateDOM();
    }
  },
  group: {
    days: {
      pres: [[], [], [], [], [], [], []],
      prev: [[], [], [], [], [], [], []]
    },
    reg: {
      status: false,
      groups: [],
      date: ''
    },
    idc: 0,
    set: function(day, timerange) {
      var timerangeArray = timerange.split(',');
      if(timerangeArray.length % 2 != 0) return false;
      var ranges = [];
      for(var i = 0; i < Math.floor(timerangeArray.length / 2); i++) {
        ranges[i] = {
          id: Learning.group.idc++,
          start: timerangeArray[i * 2],
          end: timerangeArray[i * 2 + 1]
        };
      }
      Learning.group.days.pres[day] = objClone(ranges);
      Learning.group.days.prev[day] = objClone(ranges);
    },
    remove: function(day, time) {
      // define id's
      var element = '#learning-elem-day'+String(day)+'-time'+String(time)+'-t0';
      var checkbox = '#id-learning-online-' + String(day);
      // find and remove time-range from array
      for(var i = 0; i < Learning.group.days.pres[day].length; i++) {
        if(Learning.group.days.pres[day][i].id == time) {
          Learning.group.days.pres[day].splice(i, 1);
          break;
        }
      }
      // hide time-range block
      $(element).parent().css({'max-height':'0px', 'overflow':'hidden'});
      setTimeout(function() { $(element).parent().remove(); }, 180);
      // remove all blocks and time-range elements (when chkbx unchecked)
      var len = Learning.group.days.pres[day].length;
      if(len == 0) {
        $(checkbox).prop('checked', false);
        $(element).parent().parent().parent().parent().css('max-height', '0');
        Learning.group.days.pres[day] = [];
      }
    },
    add: function(day) {
      var len = Learning.group.days.pres[day].length;
      Learning.group.days.pres[day][len] = {
        id: Learning.group.idc++,
        start: '00:00',
        end: '23:59'
      };
      Learning.group.formCheck();
    },
    val: function(day, rangeId, isStart, time) {
      // find time-range from array
      for(var i = 0; i < Learning.group.days.pres[day].length; i++) {
        if(Learning.group.days.pres[day][i].id == rangeId) {
          if(isStart) { Learning.group.days.pres[day][i].start = time; }
          else { Learning.group.days.pres[day][i].end = time; }
        }
      }
    },
    formCheck: function() {
      for(var day = 0; day < 7; day++) {
        for(range in Learning.group.days.pres[day]) {
          var id0 = '#learning-elem-day' + day + '-time' + Learning.group.days.pres[day][range].id + '-t0';
          var id1 = '#learning-elem-day' + day + '-time' + Learning.group.days.pres[day][range].id + '-t1';
          var t0 = $(id0).val();
          var t1 = $(id1).val();
          if(t0 > t1) {
            $(id0).val(t1);
            Learning.group.days.pres[day][range].start = t1;
          }
        }
      }
    },
    updateDOM: function() {
      for(var i = 0; i < 7; i++) {
        var day = Learning.group.days.pres[i];
        var element = '#id-learning-online-' + String(i);
        if(day.length > 0) {
          var output = '';
          for(var j = 0; j < day.length; j++) {
            output += '<div class="window-container-text-main-elem-main-main-time" style="max-height: 36px; overflow: hidden;">\n';
            output += '<input class="window-container-text-main-elem-main-main-time-input" required="" type="time" id="learning-elem-day'+i+'-time'+day[j].id+'-t0" onchange="Learning.group.val('+i+', '+day[j].id+', true, this.value);" value="'+day[j].start+'">\n';
            output += '<span>-</span>\n';
            output += '<input class="window-container-text-main-elem-main-main-time-input" required="" type="time" id="learning-elem-day'+i+'-time'+day[j].id+'-t1" onchange="Learning.group.val('+i+', '+day[j].id+', false, this.value);" value="'+day[j].end+'">\n';
            output += '<span class="window-container-text-main-elem-main-main-time-del icons-plus" onclick="Learning.group.remove('+i+', '+day[j].id+')" title=""></span>\n';
            output += '</div>\n';
          }
          $(element).parent().parent().parent().find('.window-container-text-main-elem-main-main-time-span').empty();
          $(element).parent().parent().parent().find('.window-container-text-main-elem-main-main-time-span').append(output);
          $(element).prop('checked', true);
          $(element).parent().parent().parent().find('.window-container-text-main-elem-main').css('max-height', '500px');
        }
        else {
          $(element).prop('checked', false);
          $(element).parent().parent().parent().find('.window-container-text-main-elem-main').css('max-height', '0');
        }
      }
    },
    display: function() {
      // title
      $('#' + Learning.field.title).text('Запись на групповое обучение');
      // reg status
      if(Learning.group.reg.status) {
        var group = Learning.group.reg.groups;
        //var date = Learning.group.reg.date;
        //var day = 0;
        //var dayRu = 'Вторник';
        //$('#learning-window-reg-block .window-container-text-learning-text-main').html('Ваша группа <b style="font-family: pfr;">'+group+'</b><br>Следующее занятие в '+dayRu+' <i>('+date+')</i>.');
        $('#learning-window-reg-block .window-container-text-learning-text-main').html('Ваша группа: <b style="font-family: pfr;">'+group+'</b><br><br>Вы можете посмотреть расписание <a href="Group training.php" style="text-decoration: underline;">тут</a>');
        $('#learning-window-reg-block').css('display', 'block');
      }
      else {
        $('#learning-window-reg-block').css('display', 'none');
      }
      // days
      Learning.group.updateDOM();
      // events
      for(var i = 0; i < 7; i++) {
        // disable events
        $('#learning-window-day'+String(i)+'-btn-add').off();
        $('#id-learning-online-'+String(i)).off();
        // add events (group)
        $('#learning-window-day'+String(i)+'-btn-add').click({day: i}, function(event) {
          Learning.group.add(event.data.day);
          Learning.group.updateDOM();
        });
        $('#id-learning-online-'+String(i)).change({day: i}, function(event) {
          if($(this).prop('checked')) {
            Learning.group.add(event.data.day);
          }
          else {
            Learning.group.days.pres[event.data.day] = [];
          }
          Learning.group.updateDOM();
        });
      }
      // button
      $('#' + Learning.field.btn).off();
      $('#' + Learning.field.btn).click(function() { Learning.group.save(); });
      // open
      windowOpen('#' + Learning.field.window);
    },
    save: function(daysObj) {
      if(typeof(daysObj) == 'object') {
        Learning.group.days.prev = objClone(daysObj);
        Learning.group.days.pres = objClone(daysObj);
      }
      else {
        Learning.group.days.prev = objClone(Learning.group.days.pres);
      }
      // save in DB
      function formOk() {
        notification_add('line', '', 'Заявка отправлена');
        windowClose($('#' + Learning.field.window), true);
      }
      var errorTimeout;
      var okTimeout;
      for(var day = 0; day < 7; day++) {
        var timerangeArray = [];
        var i = 0;
        for(rangeElem in Learning.group.days.pres[day]) {
          timerangeArray[i] = Learning.group.days.pres[day][rangeElem].start;
          timerangeArray[i + 1] = Learning.group.days.pres[day][rangeElem].end;
          i += 2;
        }
        $.ajax({
          type: 'POST',
          url: 'php/db_learning.php',
          data: {
            update_record: true,
            learning: 'group',
            day: day,
            time: timerangeArray.join(',')
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
              clearTimeout(okTimeout);
              okTimeout = setTimeout(formOk, 500);
              Learning.group.updateDOM();
            }
            else if(checkResponseCode('AUTH.')) {
              document.location.reload(true);
            }
            else {
              clearTimeout(errorTimeout);
              errorTimeout = setTimeout(function() { notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка'); }, 1000);
              console.log('error: ' + response);
            }
          },
          error: function(jqXHR, status) {
            clearTimeout(errorTimeout);
            errorTimeout = setTimeout(function() { notification_add('error', 'Ошибка', 'Невозможно установить соединение'); }, 1000);
            console.log('error: ' + status + ', ' + jqXHR);
          }
        });
      }
    },
    cancel: function() {
      // clone array
      for(day in Learning.group.days.prev) {
        var tmpArray = [];
        for(elem in Learning.group.days.prev[day]) {
          tmpArray[tmpArray.length] = objClone(Learning.group.days.prev[day][elem]);
        }
        Learning.group.days.pres[day] = tmpArray;
      }
      Learning.group.updateDOM();
    }
  }
};
