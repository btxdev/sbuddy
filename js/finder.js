var update = false;
$(document).ready(function() {
  Finder.listing();
  $(".drive-main").on("contextmenu", false);
  $('#' + Finder.field.upload.input).on('change', function() { Finder.upload(this.files); });
  $('#' + Finder.field.upload.multiple).on('change', function() { Finder.uploadFolder(event); });

  $(window).resize(function(){
    if(document.documentElement.clientWidth < 641){
      if(!update){
        update = true;
        Finder.listing();
      }
    } else{
      if(update){
        update = false;
        Finder.listing();
      }
    }
  })
});



function funcSelect(block){

  if(block != false){
    if(window.navigator){
      window.navigator.vibrate(35)
    }

    if($(block).find('input').prop('checked')){
      $(block).find('input').prop('checked','')
      $(block).find('.drive-main-main-elem-ch').css({
        'opacity':'0',
        'visibility':'hidden'
      })
    } else{
      $(block).find('input').prop('checked','checked')
      $(block).find('.drive-main-main-elem-ch').css({
        'opacity':'1',
        'visibility':'visible'
      })
    }
  }

}

var Finder = {
  field: {
    files: {
      title: 'drive-files-title',
      icon: 'drive-files-icon',
      container: 'drive-files-container'
    },
    rename: {
      window: 'window-rename',
      input: 'id-rename',
      button: 'drive-rename-btn'
    },
    memory: {
      percent: 'drive-memory-percent',
      used: 'drive-memory-used',
      max: 'drive-memory-max',
      bar: 'drive-memory-bar'
    },
    menu: {
      prev: 'drive-menu-prev',
      remove: 'drive-menu-remove',
      download: 'drive-menu-download',
      rename: 'drive-menu-rename'
    },
    upload: {
      input: 'drive-menu-upload-input',
      multiple: 'drive-menu-upload-folder'
    }
  },
  currentPath: '/',
  currentFiles: [],
  selectedFiles: [],
  selectedAll: false,
  history: {
    prev: function() {
      $.ajax({
        type: 'POST',
        url: 'php/db_finder.php',
        data: {
          set_prev_catalog: true
        },
        beforeSend: function(){
          loaderMain('show');
        },
        complete: function(){
          loaderMain('hidden');
        },
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.')) {
            Finder.listing();
          }
          else if(response == 'AUTH.') {
            document.location.reload(true);
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
    }
  },
  setCatalog: function(path) {
    $.ajax({
      type: 'POST',
      url: 'php/db_finder.php',
      data: {
        set_catalog: path
      },
      beforeSend: function(){
        loaderMain('show');
      },
      complete: function(){
        loaderMain('hidden');
      },
      success: function(response) {
        function checkResponseCode(code, rsp) {
          if(typeof(rsp) == 'undefined') rsp = response;
          return (response.substring(0, code.length) == code);
        }
        if(checkResponseCode('OK.')) {
          Finder.listing();
        }
        else if(response == 'AUTH.') {
          document.location.reload(true);
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
  convertSize: function(size) {
    var step = 0;
    var map = ['б', 'КБ', 'МБ', 'ГБ', 'ТБ'];
    while(size > 500) {
      size /= 1024;
      step++;
    }
    if(size > 100) {
      return String(Math.floor(size) + ' ' + map[step]);
    }
    else {
      return String(size.toFixed(2) + ' ' + map[step]);
    }
  },
  getIconByExtension: function(ext, type) {
    if(typeof(ext) == 'undefined') {
      ext = 'none';
    }
    if(typeof(type) == 'undefined') {
      type = 'other';
    }
    ext = ext.toUpperCase();
    if(ext == '7z') { return 'media/svg/file/7z.svg'; }
    else if(ext == 'AAC') { return 'media/svg/file/AAC.svg'; }
    else if(ext == 'APK') { return 'media/svg/file/APK.svg'; }
    else if(ext == 'AVI') { return 'media/svg/file/AVI.svg'; }
    else if(ext == 'BAT') { return 'media/svg/file/BAT.svg'; }
    else if(ext == 'CR2') { return 'media/svg/file/CR2.svg'; }
    else if(ext == 'CSS') { return 'media/svg/file/CSS.svg'; }
    else if(ext == 'DEV') { return 'media/svg/file/DEV.svg'; }
    else if(ext == 'DMG') { return 'media/svg/file/DMG.svg'; }
    else if(ext == 'DOC') { return 'media/svg/file/DOC.svg'; }
    else if(ext == 'EXE') { return 'media/svg/file/EXE.svg'; }
    else if(ext == 'EXEL') { return 'media/svg/file/EXEL.svg'; }
    else if(ext == 'FLV') { return 'media/svg/file/FLV.svg'; }
    else if(ext == 'HTML') { return 'media/svg/file/HTML.svg'; }
    else if(ext == 'IMG') { return 'media/svg/file/IMG.svg'; }
    else if(ext == 'INI') { return 'media/svg/file/INI.svg'; }
    else if(ext == 'INO') { return 'media/svg/file/INO.svg'; }
    else if(ext == 'JS') { return 'media/svg/file/JS.svg'; }
    else if(ext == 'JSON') { return 'media/svg/file/JSON.svg'; }
    else if(ext == 'LIB') { return 'media/svg/file/LIB.svg'; }
    else if(ext == 'MOV') { return 'media/svg/file/MOV.svg'; }
    else if(ext == 'MP3') { return 'media/svg/file/MP3.svg'; }
    else if(ext == 'MP4') { return 'media/svg/file/MP4.svg'; }
    else if(ext == 'MPG') { return 'media/svg/file/MPG.svg'; }
    else if(ext == 'MYSQL') { return 'media/svg/file/MYSQL.svg'; }
    else if(ext == 'OGG') { return 'media/svg/file/OGG.svg'; }
    else if(ext == 'OTF') { return 'media/svg/file/OTF.svg'; }
    else if(ext == 'PHP') { return 'media/svg/file/PHP.svg'; }
    else if(ext == 'PPT') { return 'media/svg/file/PPT.svg'; }
    else if(ext == 'PSD') { return 'media/svg/file/PSD.svg'; }
    else if(ext == 'RAR') { return 'media/svg/file/RAR.svg'; }
    else if(ext == 'RAW') { return 'media/svg/file/RAW.svg'; }
    else if(ext == 'SRYPT') { return 'media/svg/file/SRYPT.svg'; }
    else if(ext == 'TTF') { return 'media/svg/file/TTF.svg'; }
    else if(ext == 'TXT') { return 'media/svg/file/TXT.svg'; }
    else if(ext == 'WAV') { return 'media/svg/file/WAV.svg'; }
    else if(ext == 'WEBM') { return 'media/svg/file/WEBM.svg'; }
    else if(ext == 'WMA') { return 'media/svg/file/WMA.svg'; }
    else if(ext == 'WOFF') { return 'media/svg/file/WOFF.svg'; }
    else if(ext == 'WORD') { return 'media/svg/file/WORD.svg'; }
    else if(ext == 'ZIP') { return 'media/svg/file/ZIP.svg'; }
    else if(ext == 'JPEG') { return 'media/svg/file/IMG.svg'; }
    else if(ext == 'JPG') { return 'media/svg/file/IMG.svg'; }
    else if(ext == 'GIF') { return 'media/svg/file/IMG.svg'; }
    else if(ext == 'PNG') { return 'media/svg/file/IMG.svg'; }
    else if(ext == 'WEBP') { return 'media/svg/file/IMG.svg'; }
    else if(ext == 'SVG') { return 'media/svg/file/IMG.svg'; }
    else if(ext == 'KEY') { return 'media/svg/file/document.svg'; }
    else if(ext == 'ODP') { return 'media/svg/file/document.svg'; }
    else if(ext == 'PPS') { return 'media/svg/file/document.svg'; }
    else if(ext == 'PPT') { return 'media/svg/file/document.svg'; }
    else if(ext == 'PPTX') { return 'media/svg/file/document.svg'; }
    else if(ext == 'DOC') { return 'media/svg/file/document.svg'; }
    else if(ext == 'DOCX') { return 'media/svg/file/document.svg'; }
    else if(ext == 'ODT') { return 'media/svg/file/document.svg'; }
    else if(ext == 'PDF') { return 'media/svg/file/document.svg'; }
    else if(ext == 'RTF') { return 'media/svg/file/document.svg'; }
    else if(ext == 'TEX') { return 'media/svg/file/document.svg'; }
    else if(ext == 'TXT') { return 'media/svg/file/document.svg'; }
    else if(ext == 'WPD') { return 'media/svg/file/document.svg'; }
    else if(ext == 'PDPP') { return 'media/svg/file/document.svg'; }
    else if(ext == 'APK') { return 'media/svg/file/BAT.svg'; }
    else if(ext == 'BAT') { return 'media/svg/file/BAT.svg'; }
    else if(ext == 'BIN') { return 'media/svg/file/BAT.svg'; }
    else if(ext == 'CGI') { return 'media/svg/file/BAT.svg'; }
    else if(ext == 'PL') { return 'media/svg/file/BAT.svg'; }
    else if(ext == 'COM') { return 'media/svg/file/BAT.svg'; }
    else if(ext == 'EXE') { return 'media/svg/file/BAT.svg'; }
    else if(ext == 'GADGET') { return 'media/svg/file/BAT.svg'; }
    else if(ext == 'JAR') { return 'media/svg/file/BAT.svg'; }
    else if(ext == 'MSI') { return 'media/svg/file/BAT.svg'; }
    else if(ext == 'PY') { return 'media/svg/file/BAT.svg'; }
    else if(ext == 'WSF') { return 'media/svg/file/BAT.svg'; }
    else if(ext == '7Z') { return 'media/svg/file/ARCHIVE.svg'; }
    else if(ext == 'ARJ') { return 'media/svg/file/ARCHIVE.svg'; }
    else if(ext == 'DEB') { return 'media/svg/file/ARCHIVE.svg'; }
    else if(ext == 'PKG') { return 'media/svg/file/ARCHIVE.svg'; }
    else if(ext == 'RAR') { return 'media/svg/file/ARCHIVE.svg'; }
    else if(ext == 'RPM') { return 'media/svg/file/ARCHIVE.svg'; }
    else if(ext == 'GZ') { return 'media/svg/file/ARCHIVE.svg'; }
    else if(ext == 'Z') { return 'media/svg/file/ARCHIVE.svg'; }
    else if(ext == 'ZIP') { return 'media/svg/file/ARCHIVE.svg'; }
    else if(ext == 'AIF') { return 'media/svg/file/AUDIO.svg'; }
    else if(ext == 'CDA') { return 'media/svg/file/AUDIO.svg'; }
    else if(ext == 'MID') { return 'media/svg/file/AUDIO.svg'; }
    else if(ext == 'MIDI') { return 'media/svg/file/AUDIO.svg'; }
    else if(ext == 'MP3') { return 'media/svg/file/AUDIO.svg'; }
    else if(ext == 'MPA') { return 'media/svg/file/AUDIO.svg'; }
    else if(ext == 'OGG') { return 'media/svg/file/AUDIO.svg'; }
    else if(ext == 'WAV') { return 'media/svg/file/AUDIO.svg'; }
    else if(ext == 'WMA') { return 'media/svg/file/AUDIO.svg'; }
    else if(ext == 'WPL') { return 'media/svg/file/AUDIO.svg'; }
    else if(ext == '3G2') { return 'media/svg/file/VIDEO.svg'; }
    else if(ext == '3GP') { return 'media/svg/file/VIDEO.svg'; }
    else if(ext == 'AVI') { return 'media/svg/file/VIDEO.svg'; }
    else if(ext == 'FLV') { return 'media/svg/file/VIDEO.svg'; }
    else if(ext == 'H264') { return 'media/svg/file/VIDEO.svg'; }
    else if(ext == 'M4V') { return 'media/svg/file/VIDEO.svg'; }
    else if(ext == 'MKV') { return 'media/svg/file/VIDEO.svg'; }
    else if(ext == 'MOV') { return 'media/svg/file/VIDEO.svg'; }
    else if(ext == 'MP4') { return 'media/svg/file/VIDEO.svg'; }
    else if(ext == 'MPG') { return 'media/svg/file/VIDEO.svg'; }
    else if(ext == 'MPEG') { return 'media/svg/file/VIDEO.svg'; }
    else if(ext == 'RM') { return 'media/svg/file/VIDEO.svg'; }
    else if(ext == 'SWF') { return 'media/svg/file/VIDEO.svg'; }
    else if(ext == 'VOB') { return 'media/svg/file/VIDEO.svg'; }
    else if(ext == 'WMV') { return 'media/svg/file/VIDEO.svg'; }
    else if(type == 'video') { return 'media/svg/file/VIDEO.svg'; }
    else if(type == 'audio') { return 'media/svg/file/AUDIO.svg'; }
    else if(type == 'compressed') { return 'media/svg/file/ARCHIVE.svg'; }
    else if(type == 'executable') { return 'media/svg/file/BAT.svg'; }
    else if(type == 'document') { return 'media/svg/file/document.svg'; }
    else if(type == 'image') { return 'media/svg/file/IMG.svg'; }
    else if(type == 'other') { return 'media/svg/file/DEV.svg'; }
    else if(type == 'file') { return 'media/svg/file/DEV.svg'; }
    else { return 'media/svg/file/ERROR.svg'; }
  },
  memory: function(used, max) {
    var percent = Math.floor(used / max * 100);
    if(percent > 100) percent = 100;
    if(percent < 0) percent = 0;
    $('#' + Finder.field.memory.percent).text('Занято ' + percent + '%');
    $('#' + Finder.field.memory.bar).css('width', percent + '%');
    $('#' + Finder.field.memory.used).text(Finder.convertSize(used));
    $('#' + Finder.field.memory.max).text(Finder.convertSize(max));
  },
  listing: function() {
    // request
    $.ajax({
      type: 'POST',
      url: 'php/db_finder.php',
      data: {
        listing: true
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
          // change icon
          $('#' + Finder.field.files.icon).css('background-image', 'url("media/svg/file/folderColor.svg")');
          // parent volume
          $('#' + Finder.field.files.title).attr('data-size', ' ');
          // parse path
          var path = responseData.path;
          Finder.currentPath = path;
          // parse parent folder (title)
          var title = '';
          if(path == '/' || path == '') {
            title = 'Корневой каталог';
          }
          else {
            title = path.substring(0, path.length - 1);
            title = title.substring(title.lastIndexOf('/') + 1);
          }
          $('#' + Finder.field.files.title).text(title);
          // parse memory
          var memory = responseData.memory.val;
          var memoryMax = responseData.memory.max;
          Finder.memory(memory, memoryMax);
          // buttons
          var prevAvailable = responseData.history_data.prev;
          if(prevAvailable) {
            $('#' + Finder.field.menu.prev).attr('class', 'drive-info-container-btn2');
          }
          else {
            $('#' + Finder.field.menu.prev).attr('class', 'drive-info-container-btn2 drive-info-container-btn-none');
          }
          // files data
          var listing = responseData.listing;
          Finder.currentFiles = [];
          // clear finder
          $('#' + Finder.field.files.container).empty();
          // parse files
          for(var i = 0; i < listing.length; i++) {
            var file = listing[i];
            var fullpath = path + file.filename + '/';
            var fullpath_file = path + file.filename;
            var ext = file.filename.substring(file.filename.lastIndexOf('.') + 1);
            var output = '';
            // is directory

            if(file.filetype == 'directory') {
              if(device == 'phone'){
                output += '<div class="drive-main-main-elem" oncontextmenu="funcSelect(this); Finder.elements.select(\'drive-listing-f'+i+'\', \''+fullpath+'\');" onclick="Finder.setCatalog(\'' + fullpath + '\')">\n';
                output += '<div class="drive-main-main-elem-ch" style="opacity: 0; visibility: hidden;">\n';
              } else{
                output += '<div class="drive-main-main-elem" ondblclick="Finder.setCatalog(\'' + fullpath + '\')">\n';
                output += '<div class="drive-main-main-elem-ch">\n';
              }

              output += '<label class="checkbox" style="margin-top: 0px; margin-bottom: 0px; margin-left: 2px;" for="drive-listing-f'+i+'">\n';
              if(device == 'phone'){
                output += '<input type="checkbox" onclick="Finder.elements.select(\'drive-listing-f'+i+'\', \''+fullpath+'\')" class="checkbox-checked" id="drive-listing-f'+i+'" style="display: none;">\n';
              } else{
                output += '<input type="checkbox" onclick="Finder.elements.select(\'drive-listing-f'+i+'\', \''+fullpath+'\')" class="checkbox-checked" id="drive-listing-f'+i+'" style="display: none;">\n';
              }
              output += '<label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="drive-listing-f'+i+'">\n';
              output += '<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="drive-listing-f'+i+'"></label>\n';
              output += '</label>\n';
              output += '</label>\n';
              output += '</div>\n';
              output += '<div class="drive-main-main-elem-ico" style="background-image: url(&quot;media/svg/file/folderColor.svg&quot;)"></div>\n';
              output += '<div class="drive-main-main-elem-name">'+file.filename+'</div>\n';
              output += '<div class="drive-main-main-elem-date">'+file.date+'</div>\n';
              output += '<div class="drive-main-main-elem-size"></div>\n';
              output += '</div>\n';
              Finder.currentFiles[Finder.currentFiles.length] = [fullpath, file.filetype];
            }
            // is file
            else {
              var icon = Finder.getIconByExtension(ext, file.filetype);
              if(device == 'phone'){
                output += '<div class="drive-main-main-elem" oncontextmenu="funcSelect(this); Finder.elements.select(\'drive-listing-f'+i+'\', \''+fullpath_file+'\')">\n';
                output += '<div class="drive-main-main-elem-ch" style="opacity: 0; visibility: hidden;">\n';
              } else{
                output += '<div class="drive-main-main-elem">\n';
                output += '<div class="drive-main-main-elem-ch">\n';
              }

              output += '<label class="checkbox" style="margin-top: 0px; margin-bottom: 0px; margin-left: 2px;" for="drive-listing-f'+i+'">\n';
              output += '<input type="checkbox" onclick="Finder.elements.select(\'drive-listing-f'+i+'\', \''+fullpath_file+'\')" class="checkbox-checked" id="drive-listing-f'+i+'" style="display: none;">\n';
              output += '<label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="drive-listing-f'+i+'">\n';
              output += '<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="drive-listing-f'+i+'"></label>\n';
              output += '</label>\n';
              output += '</label>\n';
              output += '</div>\n';
              output += '<div class="drive-main-main-elem-ico" style="background-image: url(&quot;'+icon+'&quot;)"></div>\n';
              output += '<div class="drive-main-main-elem-name">'+file.filename+'</div>\n';
              output += '<div class="drive-main-main-elem-date">'+file.date+'</div>\n';
              output += '<div class="drive-main-main-elem-size">'+Finder.convertSize(file.size)+'</div>\n';
              output += '</div>\n';
              Finder.currentFiles[Finder.currentFiles.length] = [fullpath_file, file.filetype];
            }
            $('#' + Finder.field.files.container).append(output);
            funcSelect(false);
          }
          // sort elements
          setTimeout(function() {
            //sort_name(document.getElementById('sorting-by-name-id'));
          }, 0);
          setTimeout(function() {
            //sort_name(document.getElementById('sorting-by-name-id'));
          }, 1);
          // clear selection
          Finder.elements.clear();
          // end
        }
        else if(checkResponseCode('WRONG.')) {
          notification_add('error', 'Ошибка', 'Неизвестная ошибка');
        }
        else if(response == 'AUTH.') {
          document.location.reload(true);
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
  createCatalog: function() {
    // searching function
    function nameAlreadyExists(name) {
      for(var i = 0; i < Finder.currentFiles.length; i++) {
        if(Finder.currentFiles[i][0] == (Finder.currentPath + name + '/')) {
          return true;
        }
      }
      return false;
    }
    // find folder with new name
    var newFolderName = 'Новая папка';
    var newFolderTryCounter = 0;
    var newFolderState = true;
    while(newFolderState) {
      // define name
      if(newFolderTryCounter > 0) {
        newFolderName = 'Новая папка (' + newFolderTryCounter + ')';
      }
      else {
        newFolderName = 'Новая папка';
      }
      // check
      if(nameAlreadyExists(newFolderName)) {
        // next
        newFolderState = true;
      }
      else {
        // stop
        newFolderState = false;
      }
      // counter
      newFolderTryCounter++;
    }
    // new path
    var folderPath = Finder.currentPath + newFolderName + '/';
    // send request
    $.ajax({
      type: 'POST',
      url: 'php/db_finder.php',
      data: {
        create_catalog: folderPath
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
          notification_add('line', '', 'Новая папка создана');
          Finder.listing();
        }
        else if(checkResponseCode('ERROR.')) {
          notification_add('error', 'Ошибка', 'Не удалось создать папку');
        }
        else if(response == 'AUTH.') {
          document.location.reload(true);
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
  rename: {
    in: undefined,
    from: undefined,
    to: undefined,
    mode: 'file',
    request: function(in_path, from_name, to_name) {
      $.ajax({
        type: 'POST',
        url: 'php/db_finder.php',
        data: {
          finder_rename: true,
          rename_dir: in_path,
          rename_from: from_name,
          rename_to: to_name
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
            notification_add('line', '', 'Название изменено');
            Finder.listing();
          }
          else if(checkResponseCode('ERROR.')) {
            notification_add('error', 'Ошибка', 'Не удалось изменить название');
          }
          else if(checkResponseCode('EXISTS.')) {
            notification_add('error', 'Ошибка', 'Указанное имя занято');
          }
          else if(response == 'AUTH.') {
            document.location.reload(true);
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
    window: function() {
      if(Finder.selectedFiles.length == 0) return;
      // min finder
      var file = Finder.selectedFiles[0];
      var mode = file;
      if(file.substring(file.length - 1) == '/') {
        file = file.substring(0, file.length - 1);
        in_path = file.substring(0, file.lastIndexOf('/') + 1);
        from_name = file.substring(file.lastIndexOf('/') + 1);
        mode = 'directory';
      }
      else {
        in_path = file.substring(0, file.lastIndexOf('/') + 1);
        from_name = file.substring(file.lastIndexOf('/') + 1);
      }
      // set
      Finder.rename.in = in_path;
      Finder.rename.from = from_name;
      Finder.rename.mode = mode;
      // change field
      $('#' + Finder.field.rename.input).val(from_name);
      // window
      windowOpen('#' + Finder.field.rename.window);
    },
    accept: function(mode) {
      // get
      var in_path = Finder.rename.in;
      var from_name = Finder.rename.from;
      var to_name = $('#' + Finder.field.rename.input).val();
      Finder.rename.to = to_name;
      if(typeof(mode) == 'undefined') {
        mode = Finder.rename.mode;
      }
      // check
      if((typeof(in_path) == 'undefined') || (typeof(from_name) == 'undefined') || (typeof(to_name) == 'undefined')) {
        return;
      }
      if(in_path == '' || from_name == '' || to_name == '') {
        notification_add('error', 'Ошибка', 'Название не может быть пустым');
        return;
      }
      if(from_name == to_name) {
        return;
      }
      // send request
      if(mode == 'directory') {
        Finder.rename.request(in_path, from_name + '/', to_name + '/');
      }
      else {
        var extension = from_name.substring(from_name.lastIndexOf('.') + 1, from_name.length);
        if(extension.length > 0 && extension.length != from_name.length) {
          to_name += '.' + extension;
        }
        Finder.rename.request(in_path, from_name, to_name);
      }
      // success
      windowClose($('.window-shadow'), true);
    }
  },
  remove: {
    file: function(where, filename, silent) {
      $.ajax({
        type: 'POST',
        url: 'php/db_finder.php',
        data: {
          remove_file: true,
          remove_file_where: where,
          remove_file_name: filename
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
            if(typeof(silent) == 'undefined') {
              notification_add('line', '', 'Файл перемещен в корзину');
              Finder.listing();
            }
          }
          else if(checkResponseCode('ERROR.')) {
            notification_add('error', 'Ошибка', 'Не удалось удалить файл');
          }
          else if(response == 'AUTH.') {
            document.location.reload(true);
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
    catalog: function(path, silent) {
      $.ajax({
        type: 'POST',
        url: 'php/db_finder.php',
        data: {
          remove_catalog: path
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
            if(typeof(silent) == 'undefined') {
              notification_add('line', '', 'Папка удалена');
              Finder.listing();
            }
          }
          else if(checkResponseCode('ERROR.')) {
            notification_add('error', 'Ошибка', 'Не удалось удалить директорию');
          }
          else if(response == 'AUTH.') {
            document.location.reload(true);
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
    filePath: function(path, silent) {
      // is dir ?
      if(String(path.substring(0, path.length - 1) + '/') == path) {
        return false;
      }
      var pos = path.lastIndexOf('/');
      // get filename
      var filename = path.substring(pos + 1, path.length);
      // get path
      var where = path.substring(0, pos + 1);
      // send
      Finder.remove.file(where, filename, silent);
    },
    selected: function() {
      // check
      var listSize = Finder.selectedFiles.length;
      if(listSize == 0) {
        // empty
        return;
      }
      // listing
      var path = Finder.selectedFiles[listSize - 1];
      if(String(path.substring(0, path.length - 1) + '/') == path) {
        // is dir
        Finder.remove.catalog(path, true);
      }
      else {
        // is file
        Finder.remove.filePath(path, true);
      }
      // remove element
      Finder.selectedFiles.splice(listSize - 1, 1);
      // check
      var listSize = Finder.selectedFiles.length;
      if(listSize == 0) {
        // stop
        notification_add('line', '', 'Выделенные элементы удалены');
        Finder.listing();
        return;
      }
      else {
        // recursive
        setTimeout(Finder.remove.selected, 15);
      }
    }
  },
  copycut: {
    copycut: function(path, mode, reset) {
      $.ajax({
        type: 'POST',
        url: 'php/db_finder.php',
        data: {
          finder_copycut: path,
          copycut_mode: mode,
          copycut_reset: String(reset)
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
            if(reset === true) {
              if(mode == 'cut') {
                notification_add('line', '', 'Элемент вырезан');
              }
              else {
                notification_add('line', '', 'Элемент скопирован');
              }
            }
          }
          else if(checkResponseCode('ERROR.')) {
            notification_add('error', 'Ошибка', 'Не удалось скопировать этот файл или папку');
            console.log('error: ' + response);
          }
          else if(response == 'AUTH.') {
            document.location.reload(true);
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
    selected: function(mode, first) {
      // check
      var listSize = Finder.selectedFiles.length;
      if(listSize == 0) {
        // empty
        return;
      }
      // mode
      if(mode != 'copy' && mode != 'cut') {
        mode = 'copy';
      }
      // copy or cut
      var path = Finder.selectedFiles[listSize - 1];
      var firstmode = (typeof(first) != 'undefined') ? true : false;
      Finder.copycut.copycut(path, mode, firstmode);
      // remove element
      Finder.selectedFiles.splice(listSize - 1, 1);
      // check
      var listSize = Finder.selectedFiles.length;
      if(listSize == 0) {
        // stop
        if(mode == 'copy') {
          notification_add('line', '', 'Выделенные элементы скопированы');
        }
        else {
          notification_add('line', '', 'Выделенные элементы вырезаны');
        }
        return;
      }
      else {
        // recursive
        setTimeout(Finder.copycut.selected, 15, mode);
      }
    },
    cutOne: function(path) {
      Finder.copycut.copycut(path, 'cut', true);
    },
    copyOne: function(path) {},
    pasteTo: function() {
      // multiply files
      var multiply = false;
      // current catalog
      var directory = Finder.currentPath;
      // request
      $.ajax({
        type: 'POST',
        url: 'php/db_finder.php',
        data: {
          finder_paste_to: directory
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
            if(!multiply) {
              notification_add('line', '', 'Элемент перемещен');
            }
            // update
            Finder.listing();
          }
          else if(checkResponseCode('ERROR.')) {
            if(!multiply) {
              notification_add('error', 'Ошибка', 'Не удалость переместить элемент');
            }
          }
          else if(checkResponseCode('EMPTY.')) {
            notification_add('line', '', 'Буфер пуст');
          }
          else if(response == 'AUTH.') {
            document.location.reload(true);
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
    }
  },
  elements: {
    clear: function(update) {
      // update
      for(var i = 0; i < Finder.currentFiles.length; i++) {
        // set array
        $('#' + Finder.field.files.container).children().eq(i).children().eq(0).children().eq(0).children().eq(0).prop('checked', false);
      }
      // clear array
      Finder.selectedFiles.splice(0, Finder.selectedFiles.length);
    },
    select: function(element, path, not_switch, checked) {
      // find element in array
      var ind = Finder.selectedFiles.indexOf(path);
      if(ind < 0) {
        // not found, add
        Finder.selectedFiles.push(path);
      }
      else {
        // founded, remove
        if(typeof(not_switch) == 'undefined') {
          Finder.selectedFiles.splice(ind, 1);
        }
      }
      // menu buttons
      if(Finder.selectedFiles.length > 0) {
        $('#' + Finder.field.menu.download).attr('class', 'drive-info-container-btn3');
        $('#' + Finder.field.menu.remove).attr('class', 'drive-info-container-btn2');
      }
      else {
        $('#' + Finder.field.menu.download).attr('class', 'drive-info-container-btn3 drive-info-container-btn-none');
        $('#' + Finder.field.menu.remove).attr('class', 'drive-info-container-btn2 drive-info-container-btn-none');
      }
      if(Finder.selectedFiles.length == 1) {
        $('#' + Finder.field.menu.rename).attr('class', 'drive-info-container-btn1');
      }
      else {
        $('#' + Finder.field.menu.rename).attr('class', 'drive-info-container-btn1 drive-info-container-btn-none');
      }
    },
    selectAll: function() {
      if(Finder.selectedFiles.length == Finder.currentFiles.length) Finder.selectedAll = true;
      else Finder.selectedAll = false;
      if(Finder.selectedAll) {
        // unselect
        Finder.elements.clear(true);
        Finder.selectedAll = false;
        // hide buttons
        $('#' + Finder.field.menu.download).attr('class', 'drive-info-container-btn3 drive-info-container-btn-none');
        $('#' + Finder.field.menu.remove).attr('class', 'drive-info-container-btn2 drive-info-container-btn-none');
        for(var i = 0; i < Finder.currentFiles.length; i++) {
          var element = $('#' + Finder.field.files.container).children().eq(i).children().eq(0).children().eq(0).children().eq(0);
          element.prop('checked', true);
          if(device == 'phone'){
            $($(element).parent().parent()).css({
              'opacity':'0',
              'visibility':'hidden'
            })
          }
        }
      }
      else {
        // select
        for(var i = 0; i < Finder.currentFiles.length; i++) {
          // set array
          Finder.elements.select(undefined, Finder.currentFiles[i][0], true);
          // update
          var element = $('#' + Finder.field.files.container).children().eq(i).children().eq(0).children().eq(0).children().eq(0);
          element.prop('checked', true);
          if(device == 'phone'){
            $($(element).parent().parent()).css({
              'opacity':'1',
              'visibility':'visible'
            })
          }
        }
        Finder.selectedAll = true;
        // show buttons
        $('#' + Finder.field.menu.download).attr('class', 'drive-info-container-btn3');
        $('#' + Finder.field.menu.remove).attr('class', 'drive-info-container-btn2');
      }
      // hide rename button
      $('#' + Finder.field.menu.rename).attr('class', 'drive-info-container-btn1 drive-info-container-btn-none');
    }
  },
  openParentOf: function(filepath) {
    var parent = filepath.substring(0, filepath.lastIndexOf('/'));
    Finder.setCatalog(parent);
  },
  download: {
    file: function(path) {
      $.ajax({
        type: 'POST',
        url: 'php/db_finder.php',
        data: {
          download_file: path
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
            // open new window
            var hash = response.substring(3, response.length);
            window.open('php/db_finder.php?dfut=' + hash, '_blank');
            // end
            notification_add('line', '', 'Файл доступен для загрузки');
          }
          else if(checkResponseCode('ACCESS.')) {
            notification_add('warning', 'Нет доступа', 'У вас нет доступа к этому файлу');
            console.log('error: ' + response);
          }
          else if(checkResponseCode('EMPTY.')) {
            notification_add('error', 'Ошибка', 'Файл не найден');
            console.log('error: ' + response);
          }
          else if(checkResponseCode('ERROR.')) {
            notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
            console.log('error: ' + response);
          }
          else if(response == 'AUTH.') {
            document.location.reload(true);
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
    catalog: function(path) {
      $.ajax({
        type: 'POST',
        url: 'php/db_finder.php',
        data: {
          download_catalog: path
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
            // open new window
            var hash = response.substring(3, response.length);
            window.open('php/db_finder.php?dfut=' + hash, '_blank');
            // end
            notification_add('line', '', 'Архив доступен для загрузки');
          }
          else if(checkResponseCode('ACCESS.')) {
            notification_add('warning', 'Нет доступа', 'У вас нет доступа к этому каталогу');
            console.log('error: ' + response);
          }
          else if(checkResponseCode('EMPTY.')) {
            notification_add('error', 'Ошибка', 'Указанный каталог не найден');
            console.log('error: ' + response);
          }
          else if(checkResponseCode('ERROR.')) {
            notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
            console.log('error: ' + response);
          }
          else if(checkResponseCode('LIMIT.')) {
            notification_add('error', 'Ошибка', 'Недостаточно свободного пространства');
          }
          else if(response == 'AUTH.') {
            document.location.reload(true);
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
    selected: function() {
      if(Finder.selectedFiles.length == 0) return;
      for(var i = 0; i < Finder.selectedFiles.length; i++) {
        var file = Finder.selectedFiles[i];
        if(file.substring(file.length - 1) == '/') {
          Finder.download.catalog(file);
        }
        else {
          Finder.download.file(file);
        }
      }
      Finder.elements.clear(true);
    }
  },
  upload: function(files) {
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
    data.append('finder_upload_file', 1);
    // send files
    $.ajax({
      url: 'php/db_finder.php',
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
        $('#' + Finder.field.upload.input).val('');
        // update
        Finder.listing();
      },
      success: function(response) {
        function checkResponseCode(code, rsp) {
          if(typeof(rsp) == 'undefined') rsp = response;
          return (response.substring(0, code.length) == code);
        }
        if(checkResponseCode('OK.')) {
          notification_add('line', '', 'Файлы загружены на сервер');
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
          notification_add('error', 'Ошибка', 'Можно загружать не более 20 файлов за раз');
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
  uploadFolder: function(event) {
    // get file
    var files = event.target.files;
    if(typeof(files) == 'undefined' || files.length == 0) {
      return;
    }
    var data = new FormData();
    var relativeData = [];
    $.each(files, function(key, value) {
      data.append(key, value);
      relativeData.push(value.webkitRelativePath);
    });
    data.append('finder_upload_multiply', JSON.stringify(relativeData));
    // send file
    $.ajax({
      url: 'php/db_finder.php',
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
        $('#' + Finder.field.upload.multiple).val('');
      },
      success: function(response) {
        function checkResponseCode(code, rsp) {
          if(typeof(rsp) == 'undefined') rsp = response;
          return (response.substring(0, code.length) == code);
        }
        if(checkResponseCode('OK.')) {
          notification_add('line', '', 'Файлы загружены на сервер');
          Finder.listing();
        }
        else if(checkResponseCode('AUTH.')) {
          document.location.reload(true);
        }
        else if(checkResponseCode('RESET.')) {
          notification_add('error', 'Ошибка загрузки файлов', 'Страница будет перезагружена');
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
        else if(checkResponseCode('NO_FILE.')) {
          notification_add('error', 'Ошибка', 'Сервер принял пустой запрос');
        }
        else if(checkResponseCode('LIMIT.')) {
          notification_add('error', 'Ошибка', 'Превышен допустимый размер одного из файлов');
        }
        else if(checkResponseCode('DOWNLOADING_ERROR.')) {
          notification_add('error', 'Ошибка сервера', 'Не удалось сохранить папку');
        }
        else if(checkResponseCode('MEMORY_LIM.')) {
          notification_add('error', 'Ошибка', 'Недостаточно свободного пространства');
        }
        else if(checkResponseCode('COUNT_LIM.')) {
          notification_add('error', 'Ошибка', 'Можно загружать не более 20 файлов за раз');
        }
        else {
          notification_add('error', 'Ошибка сервера', 'Загрузка папки невозможна');
          console.log('response: ' + response);
        }
      },
      error: function(jqXHR, status) {
        notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
        console.log('error: ' + status + ', ' + jqXHR);
      }
    });
  }
};
