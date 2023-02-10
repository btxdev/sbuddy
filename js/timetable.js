var Timetable = {
  online: {
    field: {
      date: 'tt-online-date',
      dateSelect: 'tt-online-dselect',
      container: {
        list: 'tt-online-list',
        table: 'tt-online-table'
      }
    },
    list: function() {
      $.ajax({
        type: 'POST',
        url: 'php/db_table.php',
        data: {
          timetable_list: true
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
            // remove excess days
            var daysArr = [];
            for(var i = 0; i < responseData.length; i++) {
              var elem = responseData[i];
              function parseDate(str) { timearr = str.split('.'); return Date.parse(timearr[2] + '-' + timearr[1] + '-' + timearr[0]); }
              var elemTime = parseDate(elem.date);
              var todayTime = new Date().getTime();
              if((elemTime < todayTime) && !elem.today) continue;
              if(typeof(daysArr[elem.title]) == 'undefined') {
                daysArr[elem.title] = elem;
              }
              else {
                var oldTime = parseDate(daysArr[elem.title].date);
                if(elemTime < oldTime) {
                  if(!daysArr[elem.title].exception) {
                    daysArr[elem.title] = elem;
                  }
                }
              }
            }
            // reorganize array
            var responseData2 = [];
            var sortmap = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
            for(day in daysArr) {
              var num = sortmap.indexOf(day);
              responseData2[num] = daysArr[day];
            }
            responseData = [];
            for(day in responseData2) {
              responseData[responseData.length] = responseData2[day];
            }
            // sorting
            var sortmap = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
            function sortfunc(arg1, arg2) {
              var a = sortmap.indexOf(arg1.title);
              var b = sortmap.indexOf(arg2.title);
              return (a - b);
            }
            responseData.sort(sortfunc);
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
            $('#' + Timetable.online.field.container.list).empty();
            // add title
            $('#' + Timetable.online.field.container.list).append('<div class="onlinelearning-date-elem-title">Дни недели</div>\n');
            // colors
            var colorCounter = 1;
            // append
            for(var i = 0; i < responseData.length; i++) {
              // encoding
              var daysEn = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
              var daysRu = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
              // parameters
              var elem = responseData[i];
              var title = elem.title;
              var date = elem.date;
              var number = date.substring(0, 2);
              var exception = elem.exception;
              var today = elem.today && (haveTodayException == exception);
              var click = (exception) ? date : daysEn[daysRu.indexOf(title)];
              // output
              var output = '';
              // block
              output += '<div class="onlinelearning-date-elem-elem' + colorCounter + '" id="id-tt-list-i' + i + '" onclick="Timetable.online.select(\'' + i + '\', ' + colorCounter + '); Timetable.online.load(\'' + click + '\', \'' + date + '\', ' + colorCounter + ');">\n';
              output += '<div class="onlinelearning-date-elem-elem-ico" style="">' + number;
              if(today) {
                output += '<div class="onlinelearning-date-elem-elem-ico-checked icons-checked" title="Сегодня"></div>\n';
              }
              output += '</div>\n';
              output += '<div class="onlinelearning-date-elem-elem-text">\n';
              output += '<div class="onlinelearning-date-elem-elem-text-title">' + title + '</div>\n';
              output += '<div class="onlinelearning-date-elem-elem-text-date">' + date + '</div>\n';
              output += '</div>\n';
              output += '</div>\n';
              // colors
              colorCounter++;
              if(colorCounter > 3) colorCounter = 1;
              // add
              $('#' + Timetable.online.field.container.list).append(output);
              // display today day
              if(today) {
                Timetable.online.select(i, colorCounter);
                Timetable.online.load(click, date, colorCounter);
              }
            }
          }
          else if(checkResponseCode('ERROR.')) {
            notification_add('error', 'Ошибка', 'Неизвестная ошибка');
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
    select: function(i, colid) {
      var color = 'var(--colorMainPurple2)';
      if(colid == 2) color = 'var(--colorMainYellow2)';
      if(colid == 3) color = 'var(--colorMainBlue2)';
      for(var j = 0; j < 7; j++) {
        $('#id-tt-list-i' + j).css('background-color', '');
      }
      $('#id-tt-list-i' + i).css('background-color', color);
    },
    table: function(data, date, colid) {
      // date
      var daysArr = ['воскресенье', 'понедельник', 'вторник', 'среду', 'четверг', 'пятницу', 'субботу'];
      var day = daysArr[new Date(date.split('.').reverse().join('-')).getDay()];
      $('#' + Timetable.online.field.date).text('Расписание на ' + day);
      $('#' + Timetable.online.field.date).attr('date', date);
      // clear
      $('#' + Timetable.online.field.container.table).empty();
      // empty block
      var isEmpty = true;
      function emptyBlock() {
        var output = '';
        output += '<div class="onlinelearning-date-elem-block-image">\n';
        output += '<div class="onlinelearning-date-elem-block-image-ico"></div>\n';
        output += '<div class="onlinelearning-date-elem-block-image-text">В этот день занятий нет</div>\n';
        output += '</div>\n';
        $('#' + Timetable.online.field.container.table).append(output);
        return;
      }
      if(data == false || data.length == 0) {
        emptyBlock(); return;
      }
      // table color
      var color1 = 'var(--colorMainPurple)';
      var color2 = 'var(--colorMainPurple2)';
      if(colid == 2) { color1 = 'var(--colorMainYellow)'; color2 = 'var(--colorMainYellow2)'; }
      if(colid == 3) { color1 = 'var(--colorMainBlue)'; color2 = 'var(--colorMainBlue2)'; }
      // tables
      var output = '';
      for(var tb = 0; tb < data.length; tb++) {
        output += '<div class="onlinelearning-date-elem-block-table">\n';
        // title
        var title = data[tb][0];
        if(title == 'Групповое обучение') continue;
        isEmpty = false;
        // header
        output += '<div class="onlinelearning-date-elem-block-table-title" style="border: 2px solid '+color1+'; background-color: '+color2+';">\n';
        output += '<div class="onlinelearning-date-elem-block-table-title-td" style="border-right: 2px solid '+color1+';">Время</div>\n';
        output += '<div class="onlinelearning-date-elem-block-table-title-td" style="border-right: 2px solid '+color1+';">Название предмета</div>\n';
        output += '<div class="onlinelearning-date-elem-block-table-title-td" style="border-right: 2px solid '+color1+';">Преподаватель</div>\n';
        output += '<div class="onlinelearning-date-elem-block-table-title-td">Группа</div>\n';
        output += '</div>\n';
        // rows
        for(var tr = 1; tr < data[tb].length; tr++) {
          if(tr == (data[tb].length - 1)) output += '<div class="onlinelearning-date-elem-block-table-main" style="border: 2px solid '+color1+'; border-top: none; border-radius: 0px 0px var(--border-rSmall) var(--border-rSmall);">\n';
          else output += '<div class="onlinelearning-date-elem-block-table-main" style="border: 2px solid '+color1+'; border-top: none;">\n';
          // cols
          var time = data[tb][tr][0];
          var subject = data[tb][tr][1];
          var teacher = data[tb][tr][2];
          var group = data[tb][tr][3];
          output += '<div class="onlinelearning-date-elem-block-table-main-td" style="border-right: 2px solid '+color1+';">' + time + '</div>\n';
          output += '<div class="onlinelearning-date-elem-block-table-main-td" style="border-right: 2px solid '+color1+';">' + subject + '</div>\n';
          output += '<div class="onlinelearning-date-elem-block-table-main-td" style="border-right: 2px solid '+color1+';">' + teacher + '</div>\n';
          output += '<div class="onlinelearning-date-elem-block-table-main-td">' + group + '</div>\n';
          //
          output += '</div>\n';
        }
        output += '</div><br>\n';
      }
      if(isEmpty) {
        emptyBlock(); return;
      }
      else {
        $('#' + Timetable.online.field.container.table).append(output);
      }
    },
    load: function(date, dateReal, colid) {
      $.ajax({
        type: 'POST',
        url: 'php/db_table.php',
        data: {
          timetable_load: true,
          date: date
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
            if(responseData[0][0].length == 0) {
              Timetable.online.table(false, dateReal);
            }
            else {
              Timetable.online.table(responseData, dateReal, colid);
            }
          }
          else if(checkResponseCode('EMPTY.')) {
            Timetable.online.table(false, dateReal);
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
    dateSelect: function() {
      // clear selection
      for(var j = 0; j < 7; j++) {
        $('#id-tt-list-i' + j).css('background-color', '');
      }
      // date
      var raw = $('#' + Timetable.online.field.dateSelect).val();
      var date = raw.split('-').reverse().join('.');
      Timetable.online.load(date, date, 3);
    }
  },
  group: {
    field: {
      date: 'tt-group-date',
      dateSelect: 'tt-group-dselect',
      container: {
        list: 'tt-group-list',
        table: 'tt-group-table'
      }
    },
    list: function() {
      $.ajax({
        type: 'POST',
        url: 'php/db_table.php',
        data: {
          timetable_list: true
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
            // remove excess days
            var daysArr = [];
            for(var i = 0; i < responseData.length; i++) {
              var elem = responseData[i];
              function parseDate(str) { timearr = str.split('.'); return Date.parse(timearr[2] + '-' + timearr[1] + '-' + timearr[0]); }
              var elemTime = parseDate(elem.date);
              var todayTime = new Date().getTime();
              if((elemTime < todayTime) && !elem.today) continue;
              if(typeof(daysArr[elem.title]) == 'undefined') {
                daysArr[elem.title] = elem;
              }
              else {
                var oldTime = parseDate(daysArr[elem.title].date);
                if(elemTime < oldTime) {
                  if(!daysArr[elem.title].exception) {
                    daysArr[elem.title] = elem;
                  }
                }
              }
            }
            // reorganize array
            var responseData2 = [];
            var sortmap = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
            for(day in daysArr) {
              var num = sortmap.indexOf(day);
              responseData2[num] = daysArr[day];
            }
            responseData = [];
            for(day in responseData2) {
              responseData[responseData.length] = responseData2[day];
            }
            // sorting
            var sortmap = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
            function sortfunc(arg1, arg2) {
              var a = sortmap.indexOf(arg1.title);
              var b = sortmap.indexOf(arg2.title);
              return (a - b);
            }
            responseData.sort(sortfunc);
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
            $('#' + Timetable.group.field.container.list).empty();
            // add title
            $('#' + Timetable.group.field.container.list).append('<div class="onlinelearning-date-elem-title">Дни недели</div>\n');
            // colors
            var colorCounter = 1;
            // append
            for(var i = 0; i < responseData.length; i++) {
              // encoding
              var daysEn = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
              var daysRu = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
              // parameters
              var elem = responseData[i];
              var title = elem.title;
              var date = elem.date;
              var number = date.substring(0, 2);
              var exception = elem.exception;
              var today = elem.today && (haveTodayException == exception);
              var click = (exception) ? date : daysEn[daysRu.indexOf(title)];
              // output
              var output = '';
              // block
              output += '<div class="onlinelearning-date-elem-elem' + colorCounter + '" id="id-tt-list-i' + i + '" onclick="Timetable.group.select(\'' + i + '\', ' + colorCounter + '); Timetable.group.load(\'' + click + '\', \'' + date + '\', ' + colorCounter + ');">\n';
              output += '<div class="onlinelearning-date-elem-elem-ico" style="">' + number;
              if(today) {
                output += '<div class="onlinelearning-date-elem-elem-ico-checked icons-checked" title="Сегодня"></div>\n';
              }
              output += '</div>\n';
              output += '<div class="onlinelearning-date-elem-elem-text">\n';
              output += '<div class="onlinelearning-date-elem-elem-text-title">' + title + '</div>\n';
              output += '<div class="onlinelearning-date-elem-elem-text-date">' + date + '</div>\n';
              output += '</div>\n';
              output += '</div>\n';
              // colors
              colorCounter++;
              if(colorCounter > 3) colorCounter = 1;
              // add
              $('#' + Timetable.group.field.container.list).append(output);
              // display today day
              if(today) {
                Timetable.group.select(i, colorCounter);
                Timetable.group.load(click, date, colorCounter);
              }
            }
          }
          else if(checkResponseCode('ERROR.')) {
            notification_add('error', 'Ошибка', 'Неизвестная ошибка');
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
    select: function(i, colid) {
      var color = 'var(--colorMainPurple2)';
      if(colid == 2) color = 'var(--colorMainYellow2)';
      if(colid == 3) color = 'var(--colorMainBlue2)';
      for(var j = 0; j < 7; j++) {
        $('#id-tt-list-i' + j).css('background-color', '');
      }
      $('#id-tt-list-i' + i).css('background-color', color);
    },
    table: function(data, date, colid) {
      // date
      var daysArr = ['воскресенье', 'понедельник', 'вторник', 'среду', 'четверг', 'пятницу', 'субботу'];
      var day = daysArr[new Date(date.split('.').reverse().join('-')).getDay()];
      $('#' + Timetable.group.field.date).text('Расписание на ' + day);
      $('#' + Timetable.group.field.date).attr('date', date);
      // clear
      $('#' + Timetable.group.field.container.table).empty();
      // empty block
      var isEmpty = true;
      function emptyBlock() {
        var output = '';
        output += '<div class="onlinelearning-date-elem-block-image">\n';
        output += '<div class="onlinelearning-date-elem-block-image-ico"></div>\n';
        output += '<div class="onlinelearning-date-elem-block-image-text">В этот день занятий нет</div>\n';
        output += '</div>\n';
        $('#' + Timetable.group.field.container.table).append(output);
        return;
      }
      if(data == false || data.length == 0) {
        emptyBlock(); return;
      }
      // table color
      var color1 = 'var(--colorMainPurple)';
      var color2 = 'var(--colorMainPurple2)';
      if(colid == 2) { color1 = 'var(--colorMainYellow)'; color2 = 'var(--colorMainYellow2)'; }
      if(colid == 3) { color1 = 'var(--colorMainBlue)'; color2 = 'var(--colorMainBlue2)'; }
      // tables
      var output = '';
      for(var tb = 0; tb < data.length; tb++) {
        output += '<div class="onlinelearning-date-elem-block-table">\n';
        // title
        var title = data[tb][0];
        if(title == 'Онлайн обучение') continue;
        isEmpty = false;
        // header
        output += '<div class="onlinelearning-date-elem-block-table-title" style="border: 2px solid '+color1+'; background-color: '+color2+';">\n';
        output += '<div class="onlinelearning-date-elem-block-table-title-td" style="border-right: 2px solid '+color1+';">Время</div>\n';
        output += '<div class="onlinelearning-date-elem-block-table-title-td" style="border-right: 2px solid '+color1+';">Название предмета</div>\n';
        output += '<div class="onlinelearning-date-elem-block-table-title-td" style="border-right: 2px solid '+color1+';">Преподаватель</div>\n';
        output += '<div class="onlinelearning-date-elem-block-table-title-td">Группа</div>\n';
        output += '</div>\n';
        // rows
        for(var tr = 1; tr < data[tb].length; tr++) {
          if(tr == (data[tb].length - 1)) output += '<div class="onlinelearning-date-elem-block-table-main" style="border: 2px solid '+color1+'; border-top: none; border-radius: 0px 0px var(--border-rSmall) var(--border-rSmall);">\n';
          else output += '<div class="onlinelearning-date-elem-block-table-main" style="border: 2px solid '+color1+'; border-top: none;">\n';
          // cols
          var time = data[tb][tr][0];
          var subject = data[tb][tr][1];
          var teacher = data[tb][tr][2];
          var group = data[tb][tr][3];
          output += '<div class="onlinelearning-date-elem-block-table-main-td" style="border-right: 2px solid '+color1+';">' + time + '</div>\n';
          output += '<div class="onlinelearning-date-elem-block-table-main-td" style="border-right: 2px solid '+color1+';">' + subject + '</div>\n';
          output += '<div class="onlinelearning-date-elem-block-table-main-td" style="border-right: 2px solid '+color1+';">' + teacher + '</div>\n';
          output += '<div class="onlinelearning-date-elem-block-table-main-td">' + group + '</div>\n';
          //
          output += '</div>\n';
        }
        output += '</div><br>\n';
      }
      if(isEmpty) {
        emptyBlock(); return;
      }
      else {
        $('#' + Timetable.group.field.container.table).append(output);
      }
    },
    load: function(date, dateReal, colid) {
      $.ajax({
        type: 'POST',
        url: 'php/db_table.php',
        data: {
          timetable_load: true,
          date: date
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
            if(responseData[0][0].length == 0) {
              Timetable.group.table(false, dateReal);
            }
            else {
              Timetable.group.table(responseData, dateReal, colid);
            }
          }
          else if(checkResponseCode('EMPTY.')) {
            Timetable.group.table(false, dateReal);
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
    dateSelect: function() {
      // clear selection
      for(var j = 0; j < 7; j++) {
        $('#id-tt-list-i' + j).css('background-color', '');
      }
      // date
      var raw = $('#' + Timetable.group.field.dateSelect).val();
      var date = raw.split('-').reverse().join('.');
      Timetable.group.load(date, date, 3);
    }
  }
};
