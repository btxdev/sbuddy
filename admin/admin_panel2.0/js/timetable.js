var tableErrorStat = false;
var sortEnabled;
var joinStringTable;
var optionGroup;


if($.cookie('timetableSortType') != 'false'){
  sortEnabled = true;
} else{
  sortEnabled = false;
}
if($.cookie('joinStringTable') != 'false'){
  joinStringTable = true;
} else{
  joinStringTable = false;
}

var SortTable = {
  time: false,
  subject: false,
  teacher: false,
  group: false,
}
var saveEnabledSettingsTable = [0,0,0];

function printArrayFullSelect(array, key){
  output = '';
  for(let i = 0; i < array.length; i++){

    if(i == 0){
      output += '<option value="" style="display: none;">Группа</option>';
    }
    if(key != undefined && key == array[i]){
      output += '<option value="' + array[i] + '" selected>' + array[i] + '</option>';
    } else{
      output += '<option value="' + array[i] + '">' + array[i] + '</option>';
    }

  }
  return output;
}

$(document).ready(function(){
  $('#timetableListGroup').empty();

  optionGroup = siteData['timetable_groups'].split(',');
  for(let i = 0; i < optionGroup.length; i++){
    if(i != optionGroup.length){
      $('#select-group-user').val($('#select-group-user').val() + optionGroup[i] + ', ')
    } else{
      $('#select-group-user').val($('#select-group-user').val() + optionGroup[i])
    }
  }

  $('#1raRg-Pw12-fQWR').on('change', function(){
    if($(this).prop('checked')){
      $('#timetable-stat-table').css({
        'display':'block'
      })
      $('#timetable-stat-timeline').css({
        'display':'none'
      })
    } else{
      $('#timetable-stat-table').css({
        'display':'none'
      })
      $('#timetable-stat-timeline').css({
        'display':'block'
      })
    }
  })

  timetable_ready();
  $('#joinTables').on('change', function(){
    let a = false;
    if($(this).prop('checked') != TimetableSettings.joinEnabled){
      saveEnabledSettingsTable[0] -= 1;
    } else{
      saveEnabledSettingsTable[0] += 1;
    }
    for(let i = 0; i < saveEnabledSettingsTable.length; i++){
      if(saveEnabledSettingsTable[i] < 0){
        a = true;
      }
    }
    if($(this).prop('checked')){
      $('#joinTables2').removeAttr('disabled')
    } else{
      $('#joinTables2').attr('disabled','disabled')
    }
    if(a){
      $('#timetableSaveSettings').removeAttr('class');
      $('#timetableSaveSettings').attr('onclick','timetable_save_settings()');
      $('#timetableSaveSettings').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#timetableSaveSettings').removeAttr('class');
      $('#timetableSaveSettings').attr('class','window-block-conteiner-left-btn-none');
      $('#timetableSaveSettings').removeAttr('onclick');
    }

  })
  $('#joinTables2').on('change', function(){
    let a = false;
    if($(this).prop('checked') != TimetableSettings.joinString){
      saveEnabledSettingsTable[2] -= 1;
    } else{
      saveEnabledSettingsTable[2] += 1;
    }
    for(let i = 0; i < saveEnabledSettingsTable.length; i++){
      if(saveEnabledSettingsTable[i] < 0){
        a = true;
      }
    }

    if(a){
      $('#timetableSaveSettings').removeAttr('class');
      $('#timetableSaveSettings').attr('onclick','timetable_save_settings()');
      $('#timetableSaveSettings').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#timetableSaveSettings').removeAttr('class');
      $('#timetableSaveSettings').attr('class','window-block-conteiner-left-btn-none');
      $('#timetableSaveSettings').removeAttr('onclick');
    }
  })
  $('#ZyHZF-tdE5-2PGq').on('change', function(){
    let a = false;
    if($(this).val() != TimetableSettings.sortType){
      if(saveEnabledSettingsTable[1] == 0){
        saveEnabledSettingsTable[1] = -1;
      }
    } else{
      if(saveEnabledSettingsTable[1] == -1){
        saveEnabledSettingsTable[1] = 0;
      }
    }
    for(let i = 0; i < saveEnabledSettingsTable.length; i++){
      if(saveEnabledSettingsTable[i] < 0){
        a = true;
      }
    }

    if(a){
      $('#timetableSaveSettings').removeAttr('class');
      $('#timetableSaveSettings').attr('onclick','timetable_save_settings()');
      $('#timetableSaveSettings').attr('class','window-block-conteiner-left-btn');
    } else{
      $('#timetableSaveSettings').removeAttr('class');
      $('#timetableSaveSettings').attr('class','window-block-conteiner-left-btn-none');
      $('#timetableSaveSettings').removeAttr('onclick');
    }

  })
});

function printGroup(type, array, type2){
  if(type == 'online') type = 'Онлайн обучение';
  else type = 'Групповое обучение';
  var output = '';

  if(type2 == undefined){
    output += '<!DOCTYPE html> <html dir="ltr"> <head> <meta charset="utf-8"> <title></title> <style> @import "media/fonts/fonts.css"; body{ font-family: pfm; color: #000; }</style> </head> <body ';
    output += "onload='window.print();' onafterprint='self.close()'";
    output += '>\n';
    output += '<span style="font-family: pfb; font-size: 21px; margin-bottom: 5px; display: inline-block; border-bottom: 1px dashed #000;">' + type + '</span><br>'
    output += '<span style="font-family: pfl; font-size: 16px; margin-bottom: 15px; display: inline-block;">Количество групп: ' + array.length + '</span>\n';
    for(let i = 0; i < array.length; i++){
      output += '<div style="font-size: 15px; font-family: pfm; min-width: 400px;">\n';
      output += '<caption style="text-align: left; font-size: 18px; font-family: pfb;">' + array[i][0] + '</caption><ol style="margin-left: -24px;">\n';
      var count = 0;
      for(let j = 0; j < array[i][1].length; j++){
        output += '<li>';
        output += array[i][1][j];
        output += '</li>\n';
        count++;
      }
      output += '</ol></div>\n';
      output += '<div style="font-size: 15px; margin-bottom: 25px; font-family: pfl;">Человек в группе: ' + count + '</div>';
    }
    output += '</body> </html>\n';
  }
  if(type2 == 'individual'){
    output += '<!DOCTYPE html> <html dir="ltr"> <head> <meta charset="utf-8"> <title></title> <style> @import "media/fonts/fonts.css"; body{ font-family: pfm; color: #000; }</style> </head> <body ';
    output += "onload='window.print();' onafterprint='self.close()'";
    output += '>\n';
    output += '<span style="font-family: pfb; font-size: 21px; margin-bottom: 15px; display: inline-block; border-bottom: 1px dashed #000;">' + type + '</span><br>'
    output += '<div style="font-size: 15px; font-family: pfm; min-width: 400px;">\n';
    output += '<caption style="text-align: left; font-size: 18px; font-family: pfb;">' + array[0] + '</caption><ol style="margin-left: -24px;">\n';
    var count = 0;

    for(let i = 0; i < array[1].length; i++){
      output += '<li>';
      output += array[1][i];
      output += '</li>\n';
      count++;
    }
    output += '</ol></div>\n';
    output += '<div style="font-size: 15px; margin-bottom: 25px; font-family: pfl;">Человек в группе: ' + count + '</div>';
    output += '</body> </html>\n';
  }

  var groupPrint = window.open("","_blank","width=450,height=470,left=400,top=100,menubar=yes,toolbar=no,location=no,scrollbars=yes");
      groupPrint.document.open();
      groupPrint.document.write(output);
      groupPrint.document.close();
}

function listGroup(list) {
  if(list === false) return;
  var output = '';


  $('#timetableListGroup').empty();
  for(type in list) {
    var arrayOutput = [];


    if(Object.keys(list[type]).length == 0) return;
    output += '<div class="timetable-list">\n';
    output += '<div class="timetable-list-title">\n';
    if(type == 'online') output += 'Онлайн обучение\n';
    else output += 'Групповое обучение\n';

    for(group in list[type]) arrayOutput.push([group]);
    for(let i = 0; i < arrayOutput.length; i++){
      var arrayOutput2 = [];
      for(group in list[type]) {
        if(group == arrayOutput[i]){
          for(user in list[type][group]) {
            arrayOutput2.push(list[type][group][user].name1+' '+list[type][group][user].name2)
          }
        }
      }
      arrayOutput[i].push(arrayOutput2.sort())
    }

    output += '<span class="timetable-list-title-print icon-print" onclick=' + "'" + 'printGroup("' + type + '", ' + JSON.stringify(arrayOutput) + ');' + "'" + ' title="Печать"></span>\n';
    output += '</div>\n';
    for(group in list[type]) {
      var arrayOutput1 = [];
      arrayOutput1.push(group);
      var arrayOutput2 = [];
      for(user in list[type][group]) {
        arrayOutput2.push(list[type][group][user].name1+' '+list[type][group][user].name2)
      }
      arrayOutput1.push(arrayOutput2.sort())
      output += '<div class="timetable-list-group">\n';
      output += '<span onclick=' + "'" + 'printGroup("' + type + '", ' + JSON.stringify(arrayOutput1) + ', "individual");' + "'" + ' class="timetable-list-group-title-print icon-print" title="Печать"></span>\n';
      output += '<div class="timetable-list-group-title">'+group+'</div>\n';
      output += '<ol>\n';
      for(user in list[type][group]) {
        output += '<li class="timetable-list-group-peoples">'+list[type][group][user].name1+' '+list[type][group][user].name2+'</li>\n';
      }
      output += '</ol>\n';
      output += '</div>\n';
    }
    output += '</div>\n';
  }
  $('#timetableListGroup').html(output);
}

function timetableElemEnableF(){
  var timetable = $("#timetable");
  if(adaptiveDesignS == 'phone'){
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
  } else{
    timetable.find('.panel-conteiner-full').css({
      'display':'inline-block',
    })
    timetable.find('.panel-filter').css({
      'display':'inline-block',
    })
  }
}

function timetableWindow(id){

  timetableElemEnable = true;
  timetableElemEnableF();

  for(let i = 0; i < $('#timetable-elements > span').length; i++){
    if($($('#timetable-elements > span')[i]).attr('id') == id){
      $($('#timetable-elements > span')[i]).css({
        'display':'block'
      })
    } else{
      $($('#timetable-elements > span')[i]).css({
        'display':'none'
      })
    }
  }
}

function saveGroup(a){
  var block = $(a);

  if(block.val().match(/^([A-Za-zА-ЯЁа-яё ,.\-_0-9])*$/gmiu)){
    optionGroup.length = 0;
    let elemArray = block.val().replace(/([\s]*),([\s]*)+/g,',').split(',');
    for(let i = 0; i < elemArray.length; i++){
      if(elemArray[i].length > 0){
        optionGroup.push(elemArray[i])
      }
    }
    block.val('');
    for(let i = 0; i < optionGroup.length; i++){
      if(i != optionGroup.length){
        block.val(block.val() + optionGroup[i] + ', ')
      } else{
        block.val(block.val() + optionGroup[i])
      }
    }
    // request
    $.ajax({
      type: 'POST',
      url: 'db_profile.php',
      data: {
        update_group_list: optionGroup.join(',')
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
          siteData['timetable_groups'] = optionGroup.join(',');
          notification_add('line', '', 'Изменения внесены');
        }
        else if(response == 'AUTH.') {
          document.location.reload(true);
        }
        else {
          notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
          console.log('error: ' + response);
        }
      },
      error: function(jqXHR, status) {
        notification_add('error', 'Ошибка', 'Не удалось установить соединение');
        console.log('error: ' + status + ', ' + jqXHR);
      }
    });
  } else{
    notification_add('error','Ошибка','При создании списка групп произошла ошибка!',7.5)
    block.css({
      'border-color':'#ff4141'
    });
    setTimeout(function(){
      block.css({
        'border-color':''
      });
    }, 2500)
  }
}

function timetable_add_line(a){

  let alphabet = '-_QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789',
      id = '';

  for(let i = 0; i < 15; i++){
    if(i % 5 == 0 && i != 15 && i != 0){
      id += '-';
    } else{
      id += alphabet[Math.round(Math.random() * (alphabet.length - 1))];
    }
  }

  var tmpBlockLine = $(a).parent()
  var tmpLine = "<div id='" + id + "' class='panel-timetable-edit-elem' style='opacity: 0; max-height: 0px;'><input type='time' class='panel-timetable-edit-input-elem' style='width: 74px; padding-bottom: 9px; padding-top: 9px;' placeholder='Время'></input><input class='panel-timetable-edit-input-elem' style='margin-left: 4px; width: calc(50% - 6px);' placeholder='Название предмета'></input><input class='panel-timetable-edit-input-elem' style='margin-left: 3px; width: calc(25% - 6px);' placeholder='Преподаватель'></input><select class='panel-timetable-edit-input-elem' style='margin-left: 4px; width: calc(15% - 6px); border-right: 0px solid var(--border-color);' placeholder='Группа'>" + printArrayFullSelect(optionGroup) + "</select><div class='panel-timetable-edit-input-del icon-delline' title='Удалить строку' onclick='timetable_del_line(this)'></div></div>";
  var tmpAdd = "<div class='panel-timetable-edit-add'><div class='panel-timetable-edit-add-plus icon-addline' title='Добавить строку' onclick='timetable_add_line(this)'></div></div>";

  tmpBlockLine.after(tmpLine + tmpAdd);

  setTimeout(function(){
    $('#' + id).css({'max-height':'39px','opacity':'1'})
  }, 1)

}

function timetable_del_line(a){
  var tmpBlock = $(a).parent();

  $(a).parent().css({'max-height':'0px','border':'0px solid transparent','opacity':'0'});

  setTimeout(function(){
    $(a).parent().next().remove()
    $(a).parent().remove()

  }, 250)


}

var tmpHeightTables = 0;

function timetable_showTable(state) {
  if(!state) {
    tmpHeightTables = $('#timetable').find('#timetable-d46wq').height();
    $('#timetable').find('#timetable-d46wq').css({'height':tmpHeightTables});
    setTimeout(function(){
      $('#timetable').find('#timetable-d46wq').find('.timetable-d46wq-ab').css({'visibility':'visible','opacity':'1'});
      $('#timetable').find('#timetable-d46wq').css({'opacity':'0.75','cursor':'default','height':'0px','overflow':'hidden'});
    }, 1);
  }
  else {
    $('#timetable').find('#timetable-d46wq').css({'opacity':'1','cursor':'initial','height':tmpHeightTables,'overflow':''});
    $('#timetable').find('#timetable-d46wq').find('.timetable-d46wq-ab').css({'visibility':'hidden','opacity':'0'});
    setTimeout(function(){
      $('#timetable').find('#timetable-d46wq').css({'height':'auto'});
    }, 250);
  }
}

function timetable_ready(){

  $('#1raRg-Pw12-fZ4R').on("change",function() {

    //timetable_showTable($(this).prop('checked'));

    if($(this).prop('checked') == true){
      tmpHeightTables = $('#timetable').find('#timetable-d46wq').height()
      $('#timetable').find('#timetable-d46wq').css({'height':tmpHeightTables})
      setTimeout(function(){
        $('#timetable').find('#timetable-d46wq').find('.timetable-d46wq-ab').css({'visibility':'visible','opacity':'1'})
        $('#timetable').find('#timetable-d46wq').css({'opacity':'0.75','cursor':'default','height':'0px'})
      }, 1)
    } else{
      $('#timetable').find('#timetable-d46wq').css({'opacity':'1','cursor':'initial','height':tmpHeightTables})
      $('#timetable').find('#timetable-d46wq').find('.timetable-d46wq-ab').css({'visibility':'hidden','opacity':'0'})
      setTimeout(function(){
        $('#timetable').find('#timetable-d46wq').css({'height':'auto'})
      }, 250)
    }
  });

}

function timetable_parseTable(tabledata) {
  var hide = false;
  if(tabledata === false) {
    hide = true;
    tabledata = [['', ['', '', '', '']]];
  }
  // clear
  $('#timetable-d46wq').empty();
  // parse
  for(var table = 0; table < tabledata.length; table++) {
    var output = '';
    var generateId = stringGenerator(15, 5);
    output += '<div class="panel-timetable-edit" style="" id="' + generateId + '">\n';
    output += '<div class="panel-timetable-edit-del icon-plus" title="Удалить таблицу" onclick="timetable_del_table(this)"></div>\n';
    var title = tabledata[table][0];
    output += '<select class="panel-timetable-edit-title" placeholder="Заголовок таблицы">\n';
    for(let i = 0; i < typeLearning.length; i++){
      if(i == 0){
        output += '<option selected="selected" style="font-family: pfl; display: none; opacity: 0.5;" value="">Заголовок таблицы</option>\n';
      }
      if(typeLearning[i] == title){
        output += '<option style="font-family: pfl;" selected="selected">' + typeLearning[i] + '</option>\n';
      } else{
        output += '<option style="font-family: pfl;" >' + typeLearning[i] + '</option>\n';
      }
    }
    output += '</select>\n';
    output += '<div class="panel-timetable-edit-header">\n';
    output += '<div class="panel-timetable-edit-elem-elem" title="Нажмите для сортировки" onclick="timetableSort(&#39;time&#39;, this);" style="width: 75px;">Время</div>\n';
    output += '<div class="panel-timetable-edit-elem-elem" title="Нажмите для сортировки" onclick="timetableSort(&#39;subject&#39;, this);" style="width: calc(50% - 2px);">Название предмета</div>\n';
    output += '<div class="panel-timetable-edit-elem-elem" title="Нажмите для сортировки" onclick="timetableSort(&#39;teacher&#39;, this);" style="width: calc(25% - 2px);">Преподаватель</div>\n';
    output += '<div class="panel-timetable-edit-elem-elem" title="Нажмите для сортировки" onclick="timetableSort(&#39;group&#39;, this);" style="width: calc(15% - 2px); border-right: 0px solid var(--border-color);">Группа</div>\n';
    output += '</div>\n';
    output += '<span class="timetable-d46wq-LWZx">\n';
    output += '<div class="panel-timetable-edit-add">\n';
    output += '<div class="panel-timetable-edit-add-plus icon-addline" title="Добавить строку" onclick="timetable_add_line(this)"></div>\n';
    output += '</div>\n';
    for(var str = 1; str < tabledata[table].length; str++) {
      var time = tabledata[table][str][0];
      var subject = tabledata[table][str][1];
      var teacher = tabledata[table][str][2];
      var group = tabledata[table][str][3];
      output += '<div class="panel-timetable-edit-elem">\n';
      output += '<input class="panel-timetable-edit-input-elem" type="time" style="width: 74px; padding-bottom: 9px; padding-top: 9px;" placeholder="Время" value="' + time + '"></input>\n';
      output += '<input class="panel-timetable-edit-input-elem" style="width: calc(50% - 6px);" placeholder="Название предмета" value="' + subject + '"></input>\n';
      output += '<input class="panel-timetable-edit-input-elem" style="width: calc(25% - 6px);" placeholder="Преподаватель" value="' + teacher + '"></input>\n';
      // output += '<input class="panel-timetable-edit-input-elem" style="width: calc(15% - 8px); border-right: 0px solid var(--border-color);" placeholder="Группа" value="' + group + '"></input>\n';
      output += "<select class='panel-timetable-edit-input-elem' style='margin-left: 0px; width: calc(15% - 6px); border-right: 0px solid var(--border-color);' placeholder='Группа'>";
      output += printArrayFullSelect(optionGroup, group);
      output += "</select>";
      output += '<div class="panel-timetable-edit-input-del icon-delline" title="Удалить строку" onclick="timetable_del_line(this)"></div>\n';
      output += '</div>\n';
      output += '<div class="panel-timetable-edit-add">\n';
      output += '<div class="panel-timetable-edit-add-plus icon-addline" title="Добавить строку" onclick="timetable_add_line(this)"></div>\n';
      output += '</div>\n';
    }
    output += '</span>\n';
    output += '</div>\n';
    // add one table
    $('#timetable-d46wq').append(output);
  }
  if(hide) {
    timetable_showTable(false);
  }
  else {
    timetable_showTable(true);
  }
}

function timetable_addTable() {
  var output = '';
  var generateId = stringGenerator(15, 5);
  output += '<div class="panel-timetable-edit" style="height: 0px; overflow: hidden; border: 0px solid #303036; opacity: 0; margin-top: 0px;" id="' + generateId + '">\n';
  output += '<div class="panel-timetable-edit-del icon-plus" title="Удалить таблицу" onclick="timetable_del_table(this)"></div>\n';
  output += '<select class="panel-timetable-edit-title" placeholder="Заголовок таблицы">\n';
  for(let i = 0; i < typeLearning.length; i++){
    if(i == 0){
      output += '<option selected="selected" style="font-family: pfl; display: none; opacity: 0.5;" value="">Заголовок таблицы</option>\n';
    }
    output += '<option>' + typeLearning[i] + '</option>\n';
  }


  output += '</select>\n';
  output += '<div class="panel-timetable-edit-header">\n';
  output += '<div class="panel-timetable-edit-elem-elem" title="Нажмите для сортировки" onclick="timetableSort(&#39;time&#39;, this);" style="width: 75px;">Время</div>\n';
  output += '<div class="panel-timetable-edit-elem-elem" title="Нажмите для сортировки" onclick="timetableSort(&#39;subject&#39;, this);" style="width: calc(50% - 2px);">Название предмета</div>\n';
  output += '<div class="panel-timetable-edit-elem-elem" title="Нажмите для сортировки" onclick="timetableSort(&#39;teacher&#39;, this);" style="width: calc(25% - 2px);">Преподаватель</div>\n';
  output += '<div class="panel-timetable-edit-elem-elem" title="Нажмите для сортировки" onclick="timetableSort(&#39;group&#39;, this);" style="width: calc(15% - 2px); border-right: 0px solid var(--border-color);">Группа</div>\n';
  output += '</div>\n';
  output += '<span class="timetable-d46wq-LWZx">\n';
  output += '<div class="panel-timetable-edit-add">\n';
  output += '<div class="panel-timetable-edit-add-plus icon-addline" title="Добавить строку" onclick="timetable_add_line(this)"></div>\n';
  output += '</div>\n';
  output += '<div class="panel-timetable-edit-elem">\n';
  output += '<input class="panel-timetable-edit-input-elem" type="time" style="width: 74px; padding-bottom: 9px; padding-top: 9px;" placeholder="Время"></input>\n';
  output += '<input class="panel-timetable-edit-input-elem" style="width: calc(50% - 6px);" placeholder="Название предмета"></input>\n';
  output += '<input class="panel-timetable-edit-input-elem" style="width: calc(25% - 6px);" placeholder="Преподаватель"></input>\n';
  output += "<select class='panel-timetable-edit-input-elem' style='margin-left: 0px; width: calc(15% - 6px); border-right: 0px solid var(--border-color);' placeholder='Группа'>";
  output += printArrayFullSelect(optionGroup);
  output += "</select>";
  output += '<div class="panel-timetable-edit-input-del icon-delline" title="Удалить строку" onclick="timetable_del_line(this)"></div>\n';
  output += '</div>\n';
  output += '<div class="panel-timetable-edit-add">\n';
  output += '<div class="panel-timetable-edit-add-plus icon-addline" title="Добавить строку" onclick="timetable_add_line(this)"></div>\n';
  output += '</div>\n';
  output += '</span>\n';
  output += '</div>\n';

  $('#timetable-d46wq').append(output);

  setTimeout(function(){
    $('#' + generateId).css({'height':'125px','border':'2px solid var(--border-color)','margin-top':'15px'})
    setTimeout(function(){
      $('#' + generateId).css({'overflow':'initial','height':'auto','opacity':'1'})
    }, 350)
  }, 1)
}

function timetable_del_table(a){
  var tmpBlock = $(a).parent();
  var tmpBlockHeight = tmpBlock.outerHeight();

  tmpBlock.css({'height':tmpBlock.height() + 'px', 'overflow':'hidden'})
  setTimeout(function(){
    tmpBlock.css({'height':'0px','border':'0px solid var(--border-color)','transition':'0.35s all'})
    setTimeout(function(){
      setTimeout(function(){
        tmpBlock.css({'margin-top':'0px'})
        setTimeout(function(){
          tmpBlock.remove()
        }, 200)
      }, 150)
    }, 350)
  }, 10)

}

function timetable_save_settings(){
  close_window();
  $.cookie('timetableJoinEnabled',$('#joinTables').prop('checked'),{expires: 99999});
  $.cookie('joinStringTable',$('#joinTables2').prop('checked'),{expires: 99999});
  $.cookie('timetableSortType',$('#ZyHZF-tdE5-2PGq').val(),{expires: 99999});

  TimetableSettings = {
    joinEnabled: $('#joinTables').prop('checked'),
    sortType: $('#ZyHZF-tdE5-2PGq').val(),
    joinString: $('#joinTables2').prop('checked')
  }
  saveEnabledSettingsTable = [0,0,0];
  setTimeout(function(){
    $('#timetableSaveSettings').removeAttr('class');
    $('#timetableSaveSettings').attr('class','window-block-conteiner-left-btn-none');
    $('#timetableSaveSettings').removeAttr('onclick');
  }, 10)
}

function timetable_save(){
  var tmpCountTable = $('#timetable-d46wq').find('.panel-timetable-edit');
  var tmpTitleTable = '';
  var tmpError = false;
  var tmpStateCb = $('#1raRg-Pw12-fZ4R').prop('checked');

  var regTime = /^(([0,1][0-9])|(2[0-3])):[0-5][0-9]$/,
      regThing = /^([A-Za-zА-ЯЁа-яё0-9- .,\(\)]){2,64}$/u,
      regHeader = /^([A-Za-zА-ЯЁа-яё0-9- .,\(\)]){2,64}$/u,
      regTeacher = /^([A-Za-zА-ЯЁа-яё .]){2,48}$/u,
      regGroup = /^([A-Za-zА-ЯЁа-яё0-9- .,\(\)]){2,48}$/u;

  // массив таблиц
  var currentDayArray = [];

  if($.cookie('timetableSortType') != 'false'){
    sortEnabled = true;
  } else{
    sortEnabled = false;
  }
  // сортировка, если включена
  if(sortEnabled == true){
    if($.cookie('timetableSortType') == 'time'){
      var blockTmpSort = $('#timetable-d46wq').find('.panel-timetable-edit-elem-elem')
      for(let i = 0; i <= blockTmpSort.length / 4; i++){
        var blockTmpSort2 = $(blockTmpSort[i * 4])
        timetableSort($.cookie('timetableSortType'), blockTmpSort2, false)
      }

    }
    if($.cookie('timetableSortType') == 'subject'){
      var blockTmpSort = $('#timetable-d46wq').find('.panel-timetable-edit-elem-elem')
      for(let i = 0; i <= blockTmpSort.length / 4; i++){
        var blockTmpSort2 = $(blockTmpSort[(i * 4) + 1])
        timetableSort($.cookie('timetableSortType'), blockTmpSort2, false)
      }
    }
    if($.cookie('timetableSortType') == 'teacher'){
      var blockTmpSort = $('#timetable-d46wq').find('.panel-timetable-edit-elem-elem')
      for(let i = 0; i <= blockTmpSort.length / 4; i++){
        var blockTmpSort2 = $(blockTmpSort[(i * 4) + 2])
        timetableSort($.cookie('timetableSortType'), blockTmpSort2, false)
      }
    }
    if($.cookie('timetableSortType') == 'group'){
      var blockTmpSort = $('#timetable-d46wq').find('.panel-timetable-edit-elem-elem')
      for(let i = 0; i <= blockTmpSort.length / 4; i++){
        var blockTmpSort2 = $(blockTmpSort[(i * 4) + 3])
        timetableSort($.cookie('timetableSortType'), blockTmpSort2, false)
      }
    }
  }

  // проход по таблицам
  if(tmpCountTable.length > 0 && !tableErrorStat && !tmpStateCb){
    // Существует
    for(let i = 0; i < tmpCountTable.length; i++){
      // новая таблица
      var currentTableArray = [];
      // взяли заголовок
      tmpTitleTable = $(tmpCountTable[i]).find('.panel-timetable-edit-title').val();
      tmpTitle = $(tmpCountTable[i]).find('.panel-timetable-edit-title');
      if(tmpTitleTable.match(regHeader)){
        // Гуд
        // записали в нулевую строку
        currentTableArray[0] = tmpTitleTable;
        // arrayTable.push(tmpTitleTable);
        // проход по строчкам (количество)
        var tmpCountString = $(tmpCountTable[i]).find('.timetable-d46wq-LWZx').find('.panel-timetable-edit-elem');

        if(tmpCountString.length == 0){
          notification_add('error','Ошибка в таблице!','Пустая таблица существовать не может',7);
          return;
        }

        for(let j = 0; j < tmpCountString.length; j++){
          // console.log(tmpCountString.length)
          // новая строка
          var currentStringArray = [];
          // проход по колонкам в строке (количество)
          var tmpElemString = $(tmpCountString[j]).find('.panel-timetable-edit-input-elem');

          for(let t = 0; t < tmpElemString.length; t++){
            // берешь колонку
              switch (t) {
                case 0:
                  if(!$(tmpElemString[t]).val().match(regTime)) {
                    tableErrorStat = true;
                    tmpError = true;
                    $(tmpElemString[t]).focus()
                    $(tmpElemString[t]).removeAttr('class')
                    $(tmpElemString[t]).attr('class','panel-timetable-edit-input-elem-error')
                    notification_add('error','Ошибка в таблице!','Дата занятия не соответствует стандарту часы:минуты <span style="font-style: italic;">(Пример: 12:45)</span>',7);
                    setTimeout(function(){
                      tableErrorStat = false;
                      $(tmpElemString[t]).removeAttr('class')
                      $(tmpElemString[t]).attr('class','panel-timetable-edit-input-elem')
                    }, 2000)
                    return;
                  }
                  break;
                case 1:
                  if(!$(tmpElemString[t]).val().match(regThing)) {
                    tableErrorStat = true;
                    tmpError = true;
                    $(tmpElemString[t]).focus()
                    $(tmpElemString[t]).removeAttr('class')
                    $(tmpElemString[t]).attr('class','panel-timetable-edit-input-elem-error')
                    notification_add('error','Ошибка в таблице!','Название предмета заполнено некорректно',7);
                    setTimeout(function(){
                      tableErrorStat = false;
                      $(tmpElemString[t]).removeAttr('class')
                      $(tmpElemString[t]).attr('class','panel-timetable-edit-input-elem')
                    }, 2000)
                    return;
                  }
                  break;
                case 2:
                  if(!$(tmpElemString[t]).val().match(regTeacher)) {
                    tableErrorStat = true;
                    tmpError = true;
                    $(tmpElemString[t]).focus()
                    $(tmpElemString[t]).removeAttr('class')
                    $(tmpElemString[t]).attr('class','panel-timetable-edit-input-elem-error')
                    notification_add('error','Ошибка в таблице!','ФИО преподавателя заполнено некорректно',7);
                    setTimeout(function(){
                      tableErrorStat = false;
                      $(tmpElemString[t]).removeAttr('class')
                      $(tmpElemString[t]).attr('class','panel-timetable-edit-input-elem')
                    }, 2000)
                    return;
                  }
                  break;
                case 3:
                  if(!$(tmpElemString[t]).val().match(regGroup)) {
                    $(tmpElemString[t]).focus()
                    $(tmpElemString[t]).removeAttr('class')
                    $(tmpElemString[t]).attr('class','panel-timetable-edit-input-elem-error')
                    tableErrorStat = true;
                    tmpError = true;
                    notification_add('error','Ошибка в таблице!','Группа указана некорректно',7);
                    setTimeout(function(){
                      tableErrorStat = false;
                      $(tmpElemString[t]).removeAttr('class')
                      $(tmpElemString[t]).attr('class','panel-timetable-edit-input-elem')
                    }, 2000)
                    return;
                  }
                  break;
              }
            // запись
            currentStringArray.push($(tmpElemString[t]).val());

          }
          // полученную строку добавить в таблицу

          currentTableArray.push(currentStringArray);
          //arrayTable[i].push($(tmpCountString[j]).val());
        }
        // добавить таблицу
        currentDayArray.push(currentTableArray);
      } else{
        notification_add('error','Ошибка в таблице!','Таблица без заголовка существовать не может',7);
        tableErrorStat = true;
        $(tmpTitle).focus()
        $(tmpTitle).removeAttr('class')
        $(tmpTitle).attr('class','panel-timetable-edit-title-error')
        setTimeout(function(){
          $('.panel-timetable-edit-title-error').css({
            'text-align-last':'center'
          })
        }, 1)
        setTimeout(function(){
          $(tmpTitle).removeAttr('class')
          $(tmpTitle).attr('class','panel-timetable-edit-title')
          tableErrorStat = false;
        }, 2000)
        return;
      }
    }

  } else if(tableErrorStat){
    tmpError = true;
  } else {
    if(tmpStateCb){
      currentDayArray = [[]]; // not training
    } else{
      tmpError = true;
      notification_add('error','Таблицы отсутствуют!','Создать расписание без таблицы невозможно',7);
    }
  }


  if(!tmpError){
    //Отправка

    if($.cookie('timetableJoinEnabled') == 'true'){

      tasksSendToServer(tableJoin(currentDayArray));
    } else{
      tasksSendToServer(currentDayArray);
    }

    //console.log(currentDayArray);
  }

}

function tableJoin(tables){
  var tmpArraySumm = [];
  var tmp_tables_length = 0;
  for(let i = 0; i < typeLearning.length; i++){
    window['tmp_tables_' + i] = [];
    tmp_tables_length++;
  }
  for(let i = 0; i < tables.length; i++){
    var tmpTitleTable = tables[i][0];
    for(let j = 0; j < typeLearning.length; j++){
      if(typeLearning[j] == tmpTitleTable){
        window['tmp_tables_' + j].push(tables[i])
      }
    }
  }
  for(let i = 0; i < tmp_tables_length; i++){
    for(let j = 0; j < window['tmp_tables_' + i].length; j++){
      if(j == 0){
        window['tmp_tables_' + i][j]
      } else{
        window['tmp_tables_' + i][j].shift();
        for(let g = 0; g < window['tmp_tables_' + i][j].length; g++){
          window['tmp_tables_' + i][0].push(window['tmp_tables_' + i][j][g]);
        }
      }

    }
  }
  if($.cookie('joinStringTable') == 'true'){
    for(let i = 0; i < tmp_tables_length; i++){
      window['tmp_tables_' + i + '_sum'] = [];
      if(window['tmp_tables_' + i][0] != undefined){
        for(j = 1; j < window['tmp_tables_' + i][0].length; j++){
          window['tmp_tables_' + i + '_sum'].push(window['tmp_tables_' + i][0][j]);
        }
      }
      for(j = 0; j < window['tmp_tables_' + i + '_sum'].length; j++){
        for(g = 0; g < window['tmp_tables_' + i + '_sum'].length; g++){
          if(j != g && window['tmp_tables_' + i + '_sum'][j].toString() == window['tmp_tables_' + i + '_sum'][g].toString()){
            window['tmp_tables_' + i + '_sum'][j] = window['tmp_tables_' + i + '_sum'][g];
            window['tmp_tables_' + i + '_sum'].splice(g, 1);
          } else{
          }
        }
      }
      if(window['tmp_tables_' + i] != undefined){
        for(j = 0; j < window['tmp_tables_' + i].length; j++){
          if(window['tmp_tables_' + i + '_sum'][0] != window['tmp_tables_' + i][0][0]){
            window['tmp_tables_' + i + '_sum'].unshift(window['tmp_tables_' + i][0][0])
          }
        }
      }

    }
    for(let i = 0; i < tmp_tables_length; i++){
      if(window['tmp_tables_' + i + '_sum'].length != 0){
        tmpArraySumm.push(window['tmp_tables_' + i + '_sum']);
      }
    }
  } else{
    for(let i = 0; i < tmp_tables_length; i++){
      if(window['tmp_tables_' + i][0] != undefined){
        tmpArraySumm.push(window['tmp_tables_' + i][0]);
      }
    }
  }
  return tmpArraySumm;
}

function timetableSort(type, block, gg){
  if(gg === undefined){
    gg = true;
  }
  var tmpBlocks = $(block).parent().next().find('.panel-timetable-edit-elem');
  var tmpArrayBlock = [];
  if(type == 'time'){
    for(let i = 0; i < tmpBlocks.length; i++){
      var time = $($(tmpBlocks[i]).find('.panel-timetable-edit-input-elem')[0]).val();
      time = Date.parse('2020-01-16T' + time + ':00');

      tmpArrayBlock.push([time, tmpBlocks[i]]);
    }
    tmpArrayBlock.sort()
    $(block).parent().next().empty();

    if(gg){
      if(SortTable.time){
        SortTable.time = false;
      } else{
        SortTable.time = true;
        tmpArrayBlock.reverse();
      }
    }

    for(let i = 0; i < tmpArrayBlock.length; i++){
      $(block).parent().next().append(tmpArrayBlock[i][1])
      $(block).parent().next().append('<div class="panel-timetable-edit-add"><div class="panel-timetable-edit-add-plus icon-addline" title="Добавить строку" onclick="timetable_add_line(this)"></div></div>')
    }
  }
  if(type == 'subject'){
    for(let i = 0; i < tmpBlocks.length; i++){
      var time = $($(tmpBlocks[i]).find('.panel-timetable-edit-input-elem')[1]).val();
      tmpArrayBlock.push([time, tmpBlocks[i]]);
    }
    tmpArrayBlock.sort()
    $(block).parent().next().empty();

    if(gg){
      if(SortTable.subject){
        SortTable.subject = false;
      } else{
        SortTable.subject = true;
        tmpArrayBlock.reverse();
      }
    }

    for(let i = 0; i < tmpArrayBlock.length; i++){
      $(block).parent().next().append(tmpArrayBlock[i][1])
      $(block).parent().next().append('<div class="panel-timetable-edit-add"><div class="panel-timetable-edit-add-plus icon-addline" title="Добавить строку" onclick="timetable_add_line(this)"></div></div>')
    }
  }
  if(type == 'teacher'){
    for(let i = 0; i < tmpBlocks.length; i++){
      var time = $($(tmpBlocks[i]).find('.panel-timetable-edit-input-elem')[2]).val();
      tmpArrayBlock.push([time, tmpBlocks[i]]);
    }
    tmpArrayBlock.sort()
    $(block).parent().next().empty();

    if(gg){
      if(SortTable.teacher){
        SortTable.teacher = false;
      } else{
        SortTable.teacher = true;
        tmpArrayBlock.reverse();
      }
    }

    for(let i = 0; i < tmpArrayBlock.length; i++){
      $(block).parent().next().append(tmpArrayBlock[i][1])
      $(block).parent().next().append('<div class="panel-timetable-edit-add"><div class="panel-timetable-edit-add-plus icon-addline" title="Добавить строку" onclick="timetable_add_line(this)"></div></div>')
    }
  }
  if(type == 'group'){
    for(let i = 0; i < tmpBlocks.length; i++){
      var time = $($(tmpBlocks[i]).find('.panel-timetable-edit-input-elem')[3]).val();
      tmpArrayBlock.push([time, tmpBlocks[i]]);
    }
    tmpArrayBlock.sort()
    $(block).parent().next().empty();

    if(gg){
      if(SortTable.group){
        SortTable.group = false;
      } else{
        SortTable.group = true;
        tmpArrayBlock.reverse();
      }
    }

    for(let i = 0; i < tmpArrayBlock.length; i++){
      $(block).parent().next().append(tmpArrayBlock[i][1])
      $(block).parent().next().append('<div class="panel-timetable-edit-add"><div class="panel-timetable-edit-add-plus icon-addline" title="Добавить строку" onclick="timetable_add_line(this)"></div></div>')
    }
  }
}

$(document).ready(function() {
  usersBookingTable();
});
var timetableSortEnable = {
  name: false,
  learning: false,
  group: false
}
function timetableSortLearning(type, block){
  var tr = $(block).parent().parent().find('tr');
  var tmpArray = [];
  var sortText;
  var output = '';


  if(type == 'name'){
    for(let i = 1; i < tr.length; i++){
      sortText = $($(tr[i]).find('td')[0]).text()
      tmpArray.push([sortText, $(tr[i]).html()])
    }
    tmpArray.sort();


    if(timetableSortEnable.name){
      timetableSortEnable.name = false;
      tmpArray.reverse();
    } else{
      timetableSortEnable.name = true;
    }

  }
  if(type == 'learning'){
    for(let i = 1; i < tr.length; i++){
      sortText = $($(tr[i]).find('td')[1]).text()
      tmpArray.push([sortText, $(tr[i]).html()])
    }
    tmpArray.sort();

    if(timetableSortEnable.learning){
      timetableSortEnable.learning = false;
      tmpArray.reverse();
    } else{
      timetableSortEnable.learning = true;
    }

  }
  if(type == 'group'){
    for(let i = 1; i < tr.length; i++){
      sortText = $($(tr[i]).find('td')[2]).find('select > option:selected').text()
      // console.log($($(tr[i]).find('td')[2]).find('select > option:selected').text())
      tmpArray.push([sortText, $(tr[i]).html()])
    }
    tmpArray.sort();

    if(timetableSortEnable.group){
      timetableSortEnable.group = false;
      tmpArray.reverse();
    } else{
      timetableSortEnable.group = true;
    }

  }

  output += '<tbody>\n';
  output += '<tr class="timetable-stat-table-tr">\n';
  output += '<td title="Нажмите для сортировки" style="cursor: pointer;" onclick="timetableSortLearning(&#39;name&#39;, this)">Имя Фамилия</td>\n';
  output += '<td title="Нажмите для сортировки" style="cursor: pointer;" onclick="timetableSortLearning(&#39;learning&#39;, this)">Обучение</td>\n';
  output += '<td title="Нажмите для сортировки" style="cursor: pointer;" onclick="timetableSortLearning(&#39;group&#39;, this)">Группа</td>\n';
  output += '<td title="Понедельник">Пн</td>\n';
  output += '<td title="Вторник">Вт</td>\n';
  output += '<td title="Среда">Ср</td>\n';
  output += '<td title="Четверг">Чт</td>\n';
  output += '<td title="Пятница">Пт</td>\n';
  output += '<td title="Суббота">Сб</td>\n';
  output += '<td title="Воскресенье">Вс</td>\n';
  output += '</tr>\n';
  for(let i = 0; i < tmpArray.length; i++){
    output += '<tr class="timetable-stat-table-tr">' + tmpArray[i][1] + '</tr>';
  }
  output += '</tbody>\n';

  $('#timetable-stat-table').find('table').html(output);
}

function timetableLearningSearch(block){
  console.log($(block).val())
}

function usersBookingTable(block) {
  if($(block).prop('checked')){
    $(block).next().attr(
      'class','timetable-btn icon-timeline'
    )
  } else{
    $(block).next().attr(
      'class','timetable-btn icon-select_all'
    )
  }
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      show_u_custom_tt: true
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
        var onlineList = responseData.online;
        var groupList = responseData.group;
        var groupsArray = siteData['timetable_groups'].split(',');
        // === table mode ======================================================
        var output = '';
            output += '<div>\n';
            output += '<div class="input-login" style="border-radius: 4.5px; margin-left: 0px; width: auto; max-width: 200px; min-width: 100px; margin-right: 30px;">\n';
            output += '<input value="" required="required" type="text" id="pZuAS-ydHz-FF5o" oninput="timetableLearningSearch(this);">\n';
            output += '<label for="pZuAS-ydHz-FF5o" class="placeholder-white">Поиск по таблице</label>\n';
            output += '</div>\n';
            output += '</div>\n';
            output += '<table class="timetable-stat-table">\n';
            output += '<tr class="timetable-stat-table-tr">\n';
            output += '<td title="Нажмите для сортировки" style="cursor: pointer;" onclick="timetableSortLearning(&#39;name&#39;, this)">Имя Фамилия</td>\n';
            output += '<td title="Нажмите для сортировки" style="cursor: pointer;" onclick="timetableSortLearning(&#39;learning&#39;, this)">Обучение</td>\n';
            output += '<td title="Нажмите для сортировки" style="cursor: pointer;" onclick="timetableSortLearning(&#39;group&#39;, this)">Группа</td>\n';
            output += '<td title="Понедельник">Пн</td>\n';
            output += '<td title="Вторник">Вт</td>\n';
            output += '<td title="Среда">Ср</td>\n';
            output += '<td title="Четверг">Чт</td>\n';
            output += '<td title="Пятница">Пт</td>\n';
            output += '<td title="Суббота">Сб</td>\n';
            output += '<td title="Воскресенье">Вс</td>\n';
            output += '</tr>\n';
        // === universal method ================================================
        function displayThisShit1(list, learningType) {
          var learningTypeRu = (learningType == 'online') ? 'Онлайн' : 'Групповое';
          for(userid in list) {
            var tr = list[userid];
            // name
            output += '<tr class="timetable-stat-table-tr">\n';
            output += '<td title="'+tr.user.name1+' '+tr.user.name2+'">'+tr.user.name1+' '+tr.user.name2+'</td>\n';
            // learning
            output += '<td>'+learningTypeRu+'</td>\n';
            // group
            output += '<td>\n';
            output += '<select onchange="userBookingTableSelectGroup('+userid+', \''+learningType+'\', this);">\n';
            output += '<option value="">Не определена</option>\n';
            for(g in groupsArray) {
              var option = groupsArray[g];
              var selected = '';
              if(tr.groups.indexOf(option) >= 0) selected = 'selected';
              output += '<option '+selected+' value="'+option+'">'+option+'</option>\n';
            }
            output += '</select>\n';
            output += '</td>\n';
            // days
            for(var d = 0; d < 7; d++) {
              output += '<td>\n';
              // time
              var dayRecord = tr.table[d];
              if(dayRecord.length == 0) {
                output += ' - \n';
              }
              else {
                for(var t = 0; t < Math.floor(dayRecord.length / 2); t++) {
                  var start = dayRecord[t * 2];
                  var end = dayRecord[t * 2 + 1];
                  output += start + ' - ' + end;
                  if(t < (Math.floor(dayRecord.length / 2) - 1)) {
                    output += '<br>';
                  }
                  output += '\n';
                }
              }
              output += '</td>\n';
            }
            output += '</tr>\n';
          }
        }
        // === online learning =================================================
        displayThisShit1(onlineList, 'online');
        // === group learning ==================================================
        displayThisShit1(groupList, 'group');
        // finally
        output += '</table>\n';
        // add DOM
        $('#timetable-stat-table').empty();
        $('#timetable-stat-table').append(output);
        //
        // === timeline mode ===================================================
        var output = '';
        function displayThisShit2(list, learningType) {
          var daysMap1 = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота', 'Воскресенье'];
          var daysMap2 = ['ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ', 'ВС'];
          var learningTypeRu = (learningType == 'online') ? 'Онлайн' : 'Групповое';
          // title
          output += '<table class="timeline-table" border="0">\n';
          output += '<caption>'+learningTypeRu+' обучение</caption>\n';
          // users
          for(userid in list) {
            var tr = list[userid];
            output += '<tr>\n';
            // name
            output += '<td class="timeline-name" title="'+tr.user.name1+' '+tr.user.name2+'">'+tr.user.name1+' '+tr.user.name2+'</td>\n';
            // group
            output += '<td class="timeline-select">\n';
            output += '<select onchange="userBookingTableSelectGroup('+userid+', \''+learningType+'\', this);">\n';
            output += '<option value="">Не определена</option>\n';
            for(g in groupsArray) {
              var option = groupsArray[g];
              var selected = '';
              if(tr.groups.indexOf(option) >= 0) selected = 'selected';
              output += '<option '+selected+' value="'+option+'">'+option+'</option>\n';
            }
            output += '</select>\n';
            output += '</td>\n';
            // timeline
            output += '<td>\n';
            output += '<div class="timeline">\n';
            // days
            for(var d = 0; d < 7; d++) {
              output += '<div title="'+daysMap1[d]+'" class="timeline-day">\n';
              output += '<span class="timeline-day-title">'+daysMap2[d]+'</span>\n';
              output += '<span class="timeline-day-time">\n';
              // blocks
              var dayRecord = tr.table[d];
              if(dayRecord.length > 0) {
                for(var t = 0; t < Math.floor(dayRecord.length / 2); t++) {
                  var start = dayRecord[t * 2];
                  var end = dayRecord[t * 2 + 1];
                  var tmp0 = start.split(':');
                  var tmp1 = end.split(':');
                  var prc0 = Number((tmp0[0] / 24 * 100) + (tmp0[1] / 60 / 24 * 100)).toFixed(2);
                  var prc1 = Number((tmp1[0] / 24 * 100) + (tmp1[1] / 60 / 24 * 100) - prc0).toFixed(2);
                  output += '<span class="timeline-day-time-elem" title="'+daysMap1[d]+': '+start+' - '+end+'" style="width: '+prc1+'%; left: '+prc0+'%;"></span>\n';
                }
                output += '</span>\n';
              }
              output += '</div>\n';
            }
            output += '</div>\n';
            output += '</td>\n';
            output += '</tr>\n';
            // space
            if(userid != Object.keys(list)[Object.keys(list).length - 1]) {
              output += '<tr style="height: 22px;"></tr>\n';
            }
          }
          // finally
          output += '</table>\n';
        }
        // === online learning =================================================
        displayThisShit2(onlineList, 'online');
        // === group learning ==================================================
        displayThisShit2(groupList, 'group');
        // add DOM
        $('#timetable-stat-timeline').empty();
        $('#timetable-stat-timeline').append(output);
      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка', 'Не удалось установить соединение');
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

// choose the group name for user in users-custom timetable
function userBookingTableSelectGroup(userid, learning, selectedGroup) {
  console.log(learning);
  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      book_user: userid,
      learning: learning,
      groups: selectedGroup.value
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        var stringSelect = $(selectedGroup).parent().find('select').html().replace('selected=""','').replace('  ',' ').split('\n');
        var output = '';
        for(let i = 0; i < stringSelect.length; i++){
          if(('<option value="' + $(selectedGroup).val() + '">' + $(selectedGroup).val() + '</option>') == stringSelect[i]){
            output += '<option selected="" value="' + $(selectedGroup).val() + '">' + $(selectedGroup).val() + '</option>\n';
          } else{
            output += stringSelect[i] + '\n';
          }
        }
        $(selectedGroup).parent().find('select').empty();
        $(selectedGroup).parent().find('select').html(output)
        notification_add('line', '', 'Изменения сохранены', 2);

      }
      else if(response == 'AUTH.') {
        document.location.reload(true);
      }
      else {
        notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      notification_add('error', 'Ошибка', 'Не удалось установить соединение');
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

var TimetableGroupsData = {
  get: function(callback) {
    $.ajax({
      type: 'POST',
      url: 'db_profile.php',
      data: {
        show_u_custom_tt: true
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
          var theObj = {
            online: [],
            group: []
          };
          function addGroup(type, name) {
            if(type != 'online' && type != 'group') return;
            if(name == '') return;
            if(!theObj[type].hasOwnProperty(name)) {
              theObj[type][name] = [];
            }
          }
          function addUser(type, group, userId, name1, name2) {
            if(type != 'online' && type != 'group') return;
            if(group == '') return;
            theObj[type][group][Object.keys(theObj[type][group]).length] = {id: userId, name1: name1, name2: name2};
          }
          for(type in responseData) {
            for(userId in responseData[type]) {
              var userObj = responseData[type][userId];
              var name1 = userObj.user.name1;
              var name2 = userObj.user.name2;
              var groups = userObj.groups;
              for(var i = 0; i < groups.length; i++) {
                var group = groups[i];
                addGroup(type, group);
                addUser(type, group, userId, name1, name2);
              }
            }
          }
          callback(theObj);
        }
        else {
          console.log('error: ' + response);
          callback(false);
        }
      },
      error: function(jqXHR, status) {
        console.log('error: ' + status + ', ' + jqXHR);
        callback(false);
      }
    });
  }
};

function timetableSendMsgAll(block) {

  $.ajax({
    type: 'POST',
    url: 'db_profile.php',
    data: {
      groups_mailing_all: true
    },
    cache: false,
    beforeSend: function(){
      notification_add('line', '', 'Идет рассылка уведомлений...');
      loader('show');
      $(block).removeAttr('class');
      $(block).removeAttr('onclick');
      $(block).attr('class','timetable-btn-none icon-mail');
    },
    complete: function(){
      loader('hidden');
      $(block).removeAttr('class');
      $(block).attr('class','timetable-btn icon-mail');
      $(block).attr('onclick','timetableSendMsgAll(this)');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        notification_add('line', '', 'Уведомления отправлены');
      }
      else {
        notification_add('error', 'Рассылка уведомлений', 'Не удалось разослать письма');
        console.log('error: ' + response);
      }
    },
    error: function(jqXHR, status) {
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}
