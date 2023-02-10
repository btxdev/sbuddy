var reg, html, result, result_arr;
var ajaxEnabled = false;
var timeid = null;

$(document).ready(function(){
  $('#i1').on('input', function(){
    Search.input(this, 0)
  })
  $('#oXduh-QXOU-BPTZ').on('input', function(){
    Search.input(this, 1)
  })
})

var Search = {
  window: {
    finder: '',
    news: '',
    table: '',
    users: '',
    count: 0,
    section: '',
    outputPreloader: ''
  },
  input: function(block, a){
    if($('#global_search').css('display') == 'none'){
      open_panel('#global_search');
    }
    Search.text = $(block).val();

    if(a == 0){
      $('#oXduh-QXOU-BPTZ').val(Search.text)
    }
    if(a == 1){
      $('#i1').val(Search.text)
    }

    if(!development_state && Search.text == ' '){
      Search.complete();
      return;
    }
    clearTimeout(timeid);
    timeid = setTimeout(function(){
      Search.add({type: 'page'});
      Search.function();
    }, 400)

  },
  text: undefined,
  function: function(){
    if(true){
      if(Search.executeState == true) { return; }
      Search.executeState = true;
      /*
         вот тут надо делать запросы,

         1) до отправки данных на сервер в разделе beforeSend, вызвать функцию Search.beforeSend();

         2) вызывать функцию в разделе success

         finder:
            Search.add({
               type: 'finder',
               name: 'name',
               description: 'description',
               ico: '📁',
               click: function,
             });

          news:
            Search.add({
               type: 'news',
               name: 'name',
               description: 'text',
               link: 'url article',
               statistic: function
             });

          users:
             Search.add({
               type: 'users',
               name: 'Имя Фамилия',
               status: 'superuser',
               login: 'login',
               ico: 'media/users/0.jpg',
               click: ''
             });

          timetable:
              Search.add({
                type: 'timetable',
                name: 'Заголовок таблицы',
                date: '02.02.2020',
                day: 'Понедельник',
                click: ''
              });

         3) когда ответы полность пришли, то вызвать функцию Search.complete();


         type: бывает 'finder', 'users', 'news', 'tables'
         click - это функция которая будет происходить по нажатию на кнопку;
         ico: finder: 📁 или иконка файла,
              users: иконка профиля

      */
      // === top code 1337 =====================================================
      var readyCounter = 0;
      var needle = Search.text;
      Search.beforeSend();
      function synchronize() {
        if(++readyCounter == 4) {
          Search.executeState = false;
          Search.complete();
        }
      }
      function ajaxContainer(url, data, callback) {
        $.ajax({
          type: 'POST',
          url: url,
          data: data,
          success: function(response) {
            function checkResponseCode(code, rsp) {
              if(typeof(rsp) == 'undefined') rsp = response;
              return (response.substring(0, code.length) == code);
            }
            if(checkResponseCode('OK.')) {
              var responseText = response.substring(3, response.length);
              callback(responseText);
            }
            else if(checkResponseCode('EMPTY.')) {
              callback('[]');
            }
            else if(response == 'AUTH.') {
              document.location.reload(true);
            }
            else {
              console.log('error: ' + response);
            }
          },
          error: function(jqXHR, status) {
            notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
            console.log('error: ' + status + ', ' + jqXHR);
          }
        });
      }
      // finder
      function srchFinder(response) {
        var data = JSON.parse(response);
        for(var i = 0; i < data.length; i++) {
          var file = data[i];
          var path = (file.path.substring(file.path.length - 1) == '/') ? file.path.substring(0, file.path.length - 1) : file.path;
          var filename = path.substring(path.lastIndexOf('/') + 1, path.length);
          var ext = filename.substring(filename.lastIndexOf('.') + 1);
          var description;
          if(file.size > 0) { description = 'Размер: ' + finderConvertSize(file.size); }
          else if(file.date != '') { description = 'Дата: ' + file.date; }
          else { description = ' '; }
          var icon = '📁'; if(file.type != 'directory') { icon = finderGetIconByExtension(ext, file.type); }
          var func;
          if(file.type == 'directory') { func = 'finderSetCatalog(\''+path+'\'); open_panel(\'#file_manager\');'; } else { func = 'finderOpenParentOf(\''+path+'\'); open_panel(\'#file_manager\');'; }
          Search.add({
            type: 'finder',
            name: filename,
            description: description,
            ico: icon,
            click: func
          });
        }
        synchronize();
      }
      ajaxContainer('db_finder.php', {finder_search: needle}, srchFinder);
      // news
      function srchNews(response) {
        var data = JSON.parse(response);
        for(var i = 0; i < data.length; i++) {
          var record = data[i];
          var status = ' '; if(record.publicated) { status = 'Опубликовано.'; }
          Search.add({
            type: 'news',
            name: record.title,
            description: status + '<br>Дата: ' + record.date.split(' ')[0].split('-').reverse().join('.') + '<br>Текст: ' + escapeHtml(record.data),
            link: 'http://188.17.153.138:1337/Cloud/INSOweb/Projects/Study%20Buddy/%D0%A1%D0%B0%D0%B9%D1%82/ver%202/article?id=' + record.id,
            statistic: ' '
          });
        }
        synchronize();
      }
      ajaxContainer('db_profile.php', {
        news_get_list: true,
        news_get_search: needle,
        news_get_filter_published: true,
        news_get_filter_saved: true,
        news_get_sortby: 'date',
        news_get_sortorder: 'desc'
      }, srchNews);
      // timetable
      function srchTimetable(response) {
        var data = JSON.parse(response);
        var daysEn = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        var daysRu = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
        for(var i = 0; i < data.length; i++) {
          var day = data[i];
          var dateraw = day.date;
          var ind = daysEn.indexOf(dateraw);
          var datename;
          var datestamp = ' ';
          if(ind == -1) {
            var dayN = new Date(dateraw.split('.').reverse().join('-')).getDay() - 1;
            if(dayN < 0) dayN = 6;
            datename = daysRu[dayN];
            datestamp = dateraw;
          }
          else { datename = daysRu[ind]; }
          var title = JSON.parse(day.raw)[0][0];
          Search.add({
            type: 'timetable',
            name: title,
            date: datestamp,
            day: datename,
            click: 'tasksLoadFromServer(\'' + dateraw + '\', \'' + datename + '\'); open_panel(\'#timetable\');'
          });
        }
        synchronize();
      }
      ajaxContainer('db_profile.php', {tasks_search: needle}, srchTimetable);
      // users
      function srchUsers(response) {
        var data = JSON.parse(response);
        for(var i = 0; i < data.length; i++) {
          var user = data[i];
          var login = user.login;
          var status = ' ';
          var icon = ' ';
          if(user.admin) {
            status = user.level;
            if(user.icon == 'PROFILE') {
              icon = 'media/users/public/' + login + '/profile.jpg';
            }
            else {
              var iconId = user.icon.substring(user.icon.lastIndexOf('_') + 1, user.icon.length);
              icon = 'media/users/' + iconId + '.jpg';
            }
          }
          else {
            icon = '../../users/public/' + login + '/avatar.png';
            if(user.icon == 'null' || user.icon == null || typeof(user.icon) == 'null') {
              if(user.gender == 'male') { icon = '../../media/svg/male_avatar.svg'; }
              else { icon = '../../media/svg/female_avatar.svg'; }
            }
          }
          Search.add({
            type: 'users',
            name: user.name1 + ' ' + user.name2,
            status: status,
            login: login,
            ico: icon,
            click: 'open_panel(\'#all_user\')'
          });
        }
        synchronize();
      }
      ajaxContainer('db_profile.php', {search_users_global: needle}, srchUsers);
      // =======================================================================
    } else{
      // Search.beforeSend();
      // Search.add({
      //   type: 'news',
      //   name: 'Смысл сайта',
      //   description: 'Сайт рыбатекст поможет дизайнеру, верстальщику, вебмастеру сгенерировать несколько абзацев более менее осмысленного текста рыбы на русском языке, а начинающему оратору отточить навык публичных выступлений в домашних условиях. При создании генератора мы использовали небезизвестный универсальный код речей. Текст генерируется абзацами случайным образом от двух до десяти предложений в абзаце, что позволяет сделать текст более привлекательным и живым для визуально-слухового восприятия. По своей сути рыбатекст является альтернативой традиционному lorem ipsum, который вызывает у некторых людей недоумение при попытках прочитать рыбу текст. В отличии от lorem ipsum, текст рыба на русском языке наполнит любой макет непонятным смыслом и придаст неповторимый колорит советских времен.',
      //   link: 'http://188.17.153.138:1337/Cloud/INSOweb/Projects/Study%20Buddy/%D0%A1%D0%B0%D0%B9%D1%82/ver%202/article?id=5',
      //   statistic: ''
      // });
      // Search.add({
      //   type: 'finder',
      //   name: 'Название папки',
      //   description: 'Размер: 16Кб',
      //   ico: '📁',
      //   click: ''
      // });
      // Search.add({
      //   type: 'finder',
      //   name: 'Название файла.exe',
      //   description: 'Размер: 115Мб',
      //   ico: 'media/filesICO/svg/EXE.svg',
      //   click: ''
      // });
      // Search.add({
      //   type: 'timetable',
      //   name: 'Заголовок таблицы',
      //   date: '02.02.2020',
      //   day: 'Понедельник',
      //   click: ''
      // });
      // Search.add({
      //   type: 'users',
      //   name: 'Имя Фамилия',
      //   status: 'superuser',
      //   login: 'login',
      //   ico: 'media/users/0.jpg',
      //   click: ''
      // });
      // setTimeout(function(){
      //   Search.complete();
      // }, 1500)

    }
  },
  add: function(func){

    if(typeof func.click == 'string'){
      func.click = func.click.replace(/'/giu, '&#39;');
      func.click = func.click.replace(/"/giu, '&#34;');
    }

    Search.window.count++;

    if(func.type.match(/^page/iu)){

      Search.window.count = 0;
      var elemSearch = $('[search-js-elem]');
      var arraySearch = [];

      for(let i = 0; i < elemSearch.length; i++){
        arraySearch.push($(elemSearch[i]).attr('search-js-elem'));
      }
      for(let i = 0; i < arraySearch.length; i++){
        arraySearch[i] = arraySearch[i].split(', [');
      }
      for(let i = 0; i < arraySearch.length; i++){
        arraySearch[i][0] = arraySearch[i][0].split(', ');
        arraySearch[i][1] = arraySearch[i][1].substring(0, arraySearch[i][1].length - 1);
      }
      Search.window.section = '';


      for(let i = 0; i < arraySearch.length; i++){

        try {
          var regx = new RegExp(Search.text, 'gmiu');

          if(i == 0){
            if(adaptiveDesignS == 'phone'){
              Search.window.section = '<div class="global_search-Found" style="width: calc(100% - 40px);margin-top: 20px;min-width: initial;border-radius: 15px;">\n';
            } else{
              Search.window.section = '<div class="global_search-Found" style="width: calc(50.2% - 40px); margin-top: 40px; min-width: 620px; border-radius: 6px;">\n';
            }
            Search.window.section += '<div class="global_search-Found-title">Разделы</div>\n';
            Search.window.section += '<div class="global_search-Found-main">\n';
          }

          if(arraySearch[i][0][0].indexOf(Search.text) > -1 || arraySearch[i][1].match(regx)) {
            Search.window.count++;
            if(arraySearch[i][0][1] == 'section-block'){
              if(adaptiveDesignS == 'phone'){
                if(arraySearch[i][0][2].match(/#profile/gium)){
                  Search.window.section += '<div onclick="open_panel(' + "'" + arraySearch[i][0][2] + "'" + '); $(&#39;#i1&#39;).val(&#39;&#39;); updateAccessLogs();" class="global_search-Found-main-section" style="width: calc(100% - 5px);margin-top: 0px;min-width: initial;border-radius: 10px;">\n';
                } else{
                  Search.window.section += '<div onclick="open_panel(' + "'" + arraySearch[i][0][2] + "'" + '); $(&#39;#i1&#39;).val(&#39;&#39;)" class="global_search-Found-main-section" style="width: calc(100% - 5px);margin-top: 0px;min-width: initial;border-radius: 10px;">\n';
                }
              } else{
                if(arraySearch[i][0][2].match(/#profile/gium)){
                  Search.window.section += '<div onclick="open_panel(' + "'" + arraySearch[i][0][2] + "'" + '); $(&#39;#i1&#39;).val(&#39;&#39;); updateAccessLogs();" class="global_search-Found-main-section" style="width: calc(50% - 16px); border-radius: 4px;">\n';
                } else{
                  Search.window.section += '<div onclick="open_panel(' + "'" + arraySearch[i][0][2] + "'" + '); $(&#39;#i1&#39;).val(&#39;&#39;)" class="global_search-Found-main-section" style="width: calc(50% - 16px); border-radius: 4px;">\n';
                }
              }
              Search.window.section += '<div class="global_search-Found-main-section-point">\n';
              Search.window.section += '<div class="global_search-Found-main-section-point-textOpen">Открываю</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-point-text icon-left"></div>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-ico">' + arraySearch[i][0][3] + '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-text">\n';
              Search.window.section += '<div class="global_search-Found-main-section-text-title">' + arraySearch[i][0][0] + '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-text-description">\n';
              Search.window.section += '<span>' + arraySearch[i][0][4] + '</span>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '</div>\n';
            }
            if(arraySearch[i][0][1] == 'section-window'){
              if(adaptiveDesignS == 'phone'){
                Search.window.section += '<div onclick="open_window(' + "'" + arraySearch[i][0][2] + "'" + '); $(&#39;#i1&#39;).val(&#39;&#39;)" class="global_search-Found-main-section" style="width: calc(100% - 5px);margin-top: 0px;min-width: initial;border-radius: 10px;">\n';
              } else{
                Search.window.section += '<div onclick="open_window(' + "'" + arraySearch[i][0][2] + "'" + '); $(&#39;#i1&#39;).val(&#39;&#39;)" class="global_search-Found-main-section" style="width: calc(50% - 16px); border-radius: 4px;">\n';
              }
              Search.window.section += '<div class="global_search-Found-main-section-point">\n';
              Search.window.section += '<div class="global_search-Found-main-section-point-textOpen">Открываю</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-point-text icon-left"></div>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-ico">' + arraySearch[i][0][3] + '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-text">\n';
              Search.window.section += '<div class="global_search-Found-main-section-text-title">' + arraySearch[i][0][0] + '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-text-description">\n';
              Search.window.section += '<span>' + arraySearch[i][0][4] + '</span>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '</div>\n';
            }
            if(arraySearch[i][0][1] == 'section-time'){
              if(adaptiveDesignS == 'phone'){
                Search.window.section += '<div onclick="open_time(' + "'" + arraySearch[i][0][2] + "'" + '); $(&#39;#i1&#39;).val(&#39;&#39;)" class="global_search-Found-main-section" style="width: calc(100% - 5px);margin-top: 0px;min-width: initial;border-radius: 10px;">\n';
              } else{
                Search.window.section += '<div onclick="open_time(' + "'" + arraySearch[i][0][2] + "'" + '); $(&#39;#i1&#39;).val(&#39;&#39;)" class="global_search-Found-main-section" style="width: calc(50% - 16px); border-radius: 4px;">\n';
              }
              Search.window.section += '<div class="global_search-Found-main-section-point">\n';
              Search.window.section += '<div class="global_search-Found-main-section-point-textOpen">Открываю</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-point-text icon-left"></div>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-ico">' + arraySearch[i][0][3] + '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-text">\n';
              Search.window.section += '<div class="global_search-Found-main-section-text-title">' + arraySearch[i][0][0] + '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-text-description">\n';
              Search.window.section += '<span>' + arraySearch[i][0][4] + '</span>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '</div>\n';
            }
            if(arraySearch[i][0][1] == 'section-tetris'){
              if(adaptiveDesignS == 'phone'){
                Search.window.section += '<div onclick="open_tetris(' + "'" + arraySearch[i][0][2] + "'" + '); tetris(); $(&#39;#i1&#39;).val(&#39;&#39;)" class="global_search-Found-main-section" style="width: calc(100% - 5px);margin-top: 0px;min-width: initial;border-radius: 10px;">\n';
              } else{
                Search.window.section += '<div onclick="open_tetris(' + "'" + arraySearch[i][0][2] + "'" + '); tetris(); $(&#39;#i1&#39;).val(&#39;&#39;)" class="global_search-Found-main-section" style="width: calc(50% - 16px); border-radius: 4px;">\n';
              }
              Search.window.section += '<div class="global_search-Found-main-section-point">\n';
              Search.window.section += '<div class="global_search-Found-main-section-point-textOpen">Открываю</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-point-text icon-left"></div>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-ico">' + arraySearch[i][0][3] + '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-text">\n';
              Search.window.section += '<div class="global_search-Found-main-section-text-title">' + arraySearch[i][0][0] + '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-text-description">\n';
              Search.window.section += '<span>' + arraySearch[i][0][4] + '</span>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '</div>\n';
            }
            if(arraySearch[i][0][1] == 'section-console'){
              if(adaptiveDesignS == 'phone'){
                Search.window.section += '<div onclick="open_console(); $(&#39;#i1&#39;).val(&#39;&#39;)" class="global_search-Found-main-section" style="width: calc(100% - 5px);margin-top: 0px;min-width: initial;border-radius: 10px;">\n';
              } else{
                Search.window.section += '<div onclick="open_console(); $(&#39;#i1&#39;).val(&#39;&#39;)" class="global_search-Found-main-section" style="width: calc(50% - 16px); border-radius: 4px;">\n';
              }
              Search.window.section += '<div class="global_search-Found-main-section-point">\n';
              Search.window.section += '<div class="global_search-Found-main-section-point-textOpen">Открываю</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-point-text icon-left"></div>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-ico">' + arraySearch[i][0][3] + '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-text">\n';
              Search.window.section += '<div class="global_search-Found-main-section-text-title">' + arraySearch[i][0][0] + '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-text-description">\n';
              Search.window.section += '<span>' + arraySearch[i][0][4] + '</span>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '</div>\n';
            }
            if(Search.text.match(/^(insoweb)|(INSO web)|(Пасхалка)$/iu)){
              if(adaptiveDesignS == 'phone'){
                Search.window.section += '<a href="http://insoweb.ru/" target="_blank" class="global_search-Found-main-section" style="width: calc(100% - 5px);margin-top: 0px;min-width: initial;border-radius: 10px;">\n';
              } else{
                Search.window.section += '<a href="http://insoweb.ru/" target="_blank" class="global_search-Found-main-section" style="width: calc(50% - 16px); border-radius: 4px;">\n';
              }
              Search.window.section += '<div class="global_search-Found-main-section-point">\n';
              Search.window.section += '<div class="global_search-Found-main-section-point-textOpen">Открываю</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-point-text icon-left"></div>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-ico">🧡</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-text">\n';
              Search.window.section += '<div class="global_search-Found-main-section-text-title">INSOweb</div>\n';
              Search.window.section += '<div class="global_search-Found-main-section-text-description">\n';
              Search.window.section += '<span>Разработка сайтов</span>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '</div>\n';
              Search.window.section += '</a>\n';
            }
          }
          if(i == arraySearch.length){
            Search.window.section += '</div>\n';
            Search.window.section += '</div>\n';
          }
        } catch (e) {}
      }

    }
    if(func.type.match(/^finder/iu)){
      let output = '';
      if(Search.window.finder.length == 0){
        if(adaptiveDesignS == 'phone'){
          output += '<div class="global_search-Found" style="width: calc(100% - 40px);margin-top: 20px;min-width: initial;border-radius: 15px;">\n';
        } else{
          output += '<div class="global_search-Found" style="width: calc(50.2% - 40px); margin-top: 40px; min-width: 620px; border-radius: 6px;">\n';
        }
        output += '<div class="global_search-Found-title">Файлы</div>\n';
        output += '<div class="global_search-Found-main">\n';
      }
      if(adaptiveDesignS == 'phone'){
        output += '<div class="global_search-Found-main-section" onclick="' + func.click + '" title="' + func.name + '" style="width: calc(100% - 5px); border-radius: 10px;">\n';
      } else{
        output += '<div class="global_search-Found-main-section" onclick="' + func.click + '" title="' + func.name + '" style="width: calc(50% - 16px); border-radius: 4px;">\n';
      }
      output += '<div class="global_search-Found-main-section-point">\n';
      output += '<div class="global_search-Found-main-section-point-textOpen">Открываю</div>\n';
      output += '<div class="global_search-Found-main-section-point-text icon-left"></div>\n';
      output += '</div>\n';
      if(func.ico.length > 4){
        output += '<div class="global_search-Found-main-section-ico" style="background-image: url(&#39;' + func.ico + '&#39;)"></div>\n';
      } else{
        output += '<div class="global_search-Found-main-section-ico">' + func.ico + '</div>\n';
      }
      output += '<div class="global_search-Found-main-section-text">\n';
      output += '<div class="global_search-Found-main-section-text-title">' + func.name + '</div>\n';
      output += '<div class="global_search-Found-main-section-text-description">\n';
      output += '<span>' + func.description + '</span>\n';
      output += '</div>\n';
      output += '</div>\n';
      output += '</div>\n';

      Search.window.finder += output;
    }
    if(func.type.match(/^news$/iu)){
      let output = '';
      if(func.name > 25){
        func.name.replace(0, 23) + '...';
      }

      try {
        var regexSearch = new RegExp(Search.text, 'gi');
        if(func.description.match(regexSearch)){
          func.description = func.description.replace(regexSearch, '<span style="background-color: #5d78ff2e; border-radius: 2px; padding: 0px 4px 0px 4px;">' + Search.text + '</span>');
        }
      } catch (e) {}


      if(Search.window.news.length == 0){
        if(adaptiveDesignS == 'phone'){
          output += '<div class="global_search-Found" style="width: calc(100% - 40px);margin-top: 20px;min-width: initial;border-radius: 15px;">\n';
        } else{
          output += '<div class="global_search-Found" style="width: calc(50.2% - 40px); margin-top: 40px; min-width: 620px; border-radius: 6px;">\n';
        }
        output += '<div class="global_search-Found-title">Новости</div>\n';
        output += '<div class="global_search-Found-main">\n';
      }

      if(adaptiveDesignS == 'phone'){
        output += '<div class="global_search-Found-main-elem" style="width: calc(100% - 20px); border-radius: 10px;">\n';
      } else{
        output += '<div class="global_search-Found-main-elem" style="width: 220px; border-radius: 4px;">\n';
      }
      output += '<div class="global_search-Found-main-elem-hover">\n';
      output += '<div onclick="open_iframe(&#39;' + func.link + '&#39;)">\n';
      output += 'Читать\n';
      output += '<span style="transform: rotate(180deg); display: inline-block;" class="icon-left"></span>\n';
      output += '</div>\n';
      output += '<br>\n';
      output += '<div style="margin-top: -3px; margin-right: 29px;" onclick="open_panel(&#39;#statistics&#39;);">\n';
      output += 'Статистика\n';
      output += '<span style="transform: rotate(180deg); display: inline-block;" class="icon-left"></span>\n';
      output += '</div>\n';
      output += '</div>\n';
      output += '<div class="global_search-Found-main-elem-title">' + func.name + '</div>\n';
      output += '<div class="global_search-Found-main-elem-text">\n';
      output += '' + func.description + '\n';
      output += '</div>\n';
      output += '</div>\n';

      Search.window.news += output;
    }
    if(func.type.match(/^users/iu)){

      color = '';
      if(func.status.match(/superuser/ui)){
        func.status = 'Главный администратор';
        color = '#ffb822';
      } else if(func.status.match(/administrator/ui)){
        func.status = 'Администратор';
        color = '#0abb87';
      } else if(func.status.match(/moderator/ui)){
        func.status = 'Модератор';
        color = '#5d78ff';
      } else if(func.status.match(/redactor/ui)){
        func.status = 'Редактор';
        color = '#fd397a';
      } else if(func.status.match(/default/ui)){
        func.status = 'Стандартный';
        color = '#6b5eae';
      } else{
        func.status = 'Пользователь сайта';
        color = '#6b5eae';
      }
      let output = '';
      if(Search.window.users.length == 0){
        if(adaptiveDesignS == 'phone'){
          output += '<div class="global_search-Found" style="width: calc(100% - 40px);margin-top: 20px;min-width: initial;border-radius: 15px;">\n';
        } else{
          output += '<div class="global_search-Found" style="width: calc(50.2% - 40px); margin-top: 40px; min-width: 620px; border-radius: 6px;">\n';
        }
        output += '<div class="global_search-Found-title">Пользователи</div>\n';
        output += '<div class="global_search-Found-main">\n';
      }
      if(adaptiveDesignS == 'phone'){
        output += '<div class="global_search-Found-main-section" title="' + func.status + ': ' + func.login + '" style="width: calc(100% - 5px); border-radius: 10px;">\n';
      } else{
        output += '<div class="global_search-Found-main-section" title="' + func.status + ': ' + func.login + '" style="width: calc(50% - 16px); border-radius: 4px;">\n';
      }
      output += '<div class="global_search-Found-main-section-point">\n';
      output += '<div class="global_search-Found-main-section-point-textOpen">Открываю</div>\n';
      output += '<div class="global_search-Found-main-section-point-text icon-left"></div>\n';
      output += '</div>\n';
      output += '<div class="global_search-Found-main-section-img" style="background-image: url(&quot;' + func.ico + '&quot;)"></div>\n';
      output += '<div class="global_search-Found-main-section-text">\n';
      output += '<div class="global_search-Found-main-section-text-title">' + func.name + '</div>\n';
      output += '<div class="global_search-Found-main-section-text-description">\n';
      output += '<span class="global_search-Found-main-section-text-description-status" style="background-color: ' + color + ';" title="' + func.status + '">' + func.login + '</span>\n';
      output += '</div>\n';
      output += '</div>\n';
      output += '</div>\n';
      Search.window.users += output;
    }
    if(func.type.match(/^timetable/iu)){
      let output = '';

      if(Search.window.tables.length == 0){
        if(adaptiveDesignS == 'phone'){
          output += '<div class="global_search-Found" style="width: calc(100% - 40px);margin-top: 20px;min-width: initial;border-radius: 15px;">\n';
        } else{
          output += '<div class="global_search-Found" style="width: calc(50.2% - 40px); margin-top: 40px; min-width: 620px; border-radius: 6px;">\n';
        }
        output += '<div class="global_search-Found-title">Расписание</div>\n';
        output += '<div class="global_search-Found-main">\n';
      }

      if(adaptiveDesignS == 'phone'){
        output += '<div onclick="' + func.click + '" class="global_search-Found-main-timetable" style="width: calc(100% - 5px); border-radius: 10px;">\n';
      } else{
        output += '<div onclick="' + func.click + '" class="global_search-Found-main-timetable" style="width: calc(50% - 16px); border-radius: 4px;">\n';
      }
      output += '<div class="global_search-Found-main-timetable-point">\n';
      output += '<div class="global_search-Found-main-timetable-point-textOpen">Открываю</div>\n';
      output += '<div class="global_search-Found-main-timetable-point-text icon-left"></div>\n';
      output += '</div>\n';
      output += '<div class="global_search-Found-main-timetable-ico">📅</div>\n';
      output += '<div class="global_search-Found-main-timetable-text">\n';
      output += '<div class="global_search-Found-main-timetable-text-title">' + func.name + '</div>\n';
      output += '<div class="global_search-Found-main-timetable-text-description">\n';
      output += '<span>' + func.date + '</span>\n';
      output += '<span style="font-style: italic;">' + func.day + '</span>\n';
      output += '</div>\n';
      output += '</div>\n';
      output += '</div>\n';

      Search.window.tables += output;

    }
  },
  complete: function(){

    Search.window.outputPreloader = '';
    if(Search.text.length > 0){
      if(Search.window.count != 0){
        $('#globalSearchIdOutput').empty();
        $('.global_search-notFound').css({
          'display':'none'
        });
        $('#globalSearchIdOutput').css({
          'display':'block'
        });
        $('.global_search-main-resultCount').css({
          'height':'19px',
          'opacity':'1',
          'visibility':'visible'
        });
        $('#global_search-main-resultCount-span').text(Search.window.count);
      } else{
        $('.global_search-main-resultCount').css({
          'height':'0px',
          'opacity':'0',
          'visibility':'hidden'
        });
        $('.global_search-notFound').css({
          'display':'block'
        });
        $('#globalSearchIdOutput').css({
          'display':'none'
        })
      }


      if(Search.window.section.match(/class="global_search-Found-main-section"/gmiu) != null){
        $('#globalSearchIdOutput').html(Search.window.section);
      }

      if(Search.window.news.length > 5){
        Search.window.news += '</div>\n</div>\n';
        $('#globalSearchIdOutput').append(Search.window.news);
      }

      if(Search.window.tables.length > 5){
        Search.window.tables += '</div>\n</div>\n';
        $('#globalSearchIdOutput').append(Search.window.tables);
      }

      if(Search.window.finder.length > 5){
        Search.window.finder += '</div>\n</div>\n';
        $('#globalSearchIdOutput').append(Search.window.finder);
      }

      if(Search.window.users.length > 5){
        Search.window.users += '</div>\n</div>\n';
        $('#globalSearchIdOutput').append(Search.window.users);
      }

      $('#globalSearchIdOutput').append('<div style="margin-top: 75px;"></div>');
    } else{
      $('#globalSearchIdOutput').empty();
      $('.global_search-main-resultCount').css({
        'height':'0px',
        'opacity':'0',
        'visibility':'hidden'
      });
    }

  },
  beforeSend: function(){


    if(Search.window.outputPreloader.length < 5){
      $('#globalSearchIdOutput').empty()
      Search.window.outputPreloader = '';

      for (let i = 0; i < 2; i++) {
        if(adaptiveDesignS == 'phone'){
          Search.window.outputPreloader += "<div class='global_search-loader' style='width: calc(100% - 40px);margin-top: 20px;min-width: initial;border-radius: 15px;'>\n";
        } else{
          Search.window.outputPreloader += "<div class='global_search-loader' style='width: calc(50.2% - 40px); margin-top: 40px; min-width: 620px; border-radius: 6px;'>\n";
        }

        Search.window.outputPreloader += "<div class='global_search-loader-title'></div>\n";
        Search.window.outputPreloader += "<div class='global_search-loader-main'>\n";

        for(let j = 0; j < 10; j++) {
          if(adaptiveDesignS == 'phone'){
            Search.window.outputPreloader += "<div style='width: calc(100% - 5px); border-radius: 10px;' class='global_search-loader-main-section'>\n";
          } else{
            Search.window.outputPreloader += "<div style='width: calc(50% - 16px); border-radius: 4px;' class='global_search-loader-main-section'>\n";
          }
          Search.window.outputPreloader += "<div class='global_search-loader-main-section-img'></div>\n";
          Search.window.outputPreloader += "<div class='global_search-Found-main-section-text'>\n";
          Search.window.outputPreloader += "<div class='global_search-loader-main-section-text-title'></div>\n";
          Search.window.outputPreloader += "<div class='global_search-loader-main-section-text-description'></div>\n";
          Search.window.outputPreloader += "</div>\n";
          Search.window.outputPreloader += "</div>\n";
        }

        Search.window.outputPreloader += "</div>\n";
        Search.window.outputPreloader += "</div>\n";
      }


      $('#globalSearchIdOutput').append(Search.window.outputPreloader);
    }

    $('.global_search-main-resultCount').css({
      'height':'0px',
      'opacity':'0',
      'visibility':'hidden'
    });
    Search.window.finder  =  '';
    Search.window.news    =  '';
    Search.window.users   =  '';
    Search.window.tables  =  '';
  }
}
