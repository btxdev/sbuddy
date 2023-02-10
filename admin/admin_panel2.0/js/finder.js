/*
 *  Swiftly Admin Panel v1.12 alpha
 *  (c) 2019-2020 INSOweb  <http://insoweb.ru>
 *  All rights reserved.
 */


var sortDate                  =  false,
    sortSize                  =  false,
    sortType                  =  false,
    sortName                  =  false,
    style_finder              =  '',
    finderListingErrorCounter =  0,
    windthG, // ширина блока с классом .file_manager-btn-action-way-main
    windthL, // ширина блока с классом .file_manager-btn-action-way-main-div
    windowSpaceLittle         =  true;


var arrayFinderWay = [['_SERVER','путь 1'],['Фото','путь 2'],['Фото за Ноябрь','путь 3'],['Название папки','id папки']];
var arrayContextMenu = [['open_folder','tvoya_function()'],['line'],['new_zip','tvoya_function()']];

var Finder = {
  currentPath: '/',
  currentFiles: [],
  selectedFiles: [],
  selectedFilesTrash: [],
  contextMenuReady: true,
  rename: {
    in: undefined,
    from: undefined,
    to: undefined,
    mode: 'file'
  },
  selectedAll: false,
  searchTimer: Date.now(),
  searchLock: false,
  listingTo: 'all' // users, books, docs, all, trash, search
};


$(document).ready(function(){

  swipe(110, '#file_manager', 0, 'right', 'finderMenuOpen()');
  swipe(110, '#file_manager', 0, 'left', 'finderHistoryPrevCatalog()');

  $("#file_manager").on("contextmenu", false);

  finder_way_auto(arrayFinderWay, true)

  change_fontsSize_finder('#panel-user-ab-btn-conteiner-block-range1',1,false)

  $(window).resize(function(){
    finder_way_auto(arrayFinderWay, true)
  });

  // верхняя меню в проводнике
  $('.panel-conteiner-all-block-main-2-main').scroll(function(){
    var FinderSmallNav = {
      HeightSroll: $('.panel-conteiner-all-block-main-2-main').prop('scrollHeight'),
      Height: $('.panel-conteiner-all-block-main-2-main').height(),
      ScrollTop: $('.panel-conteiner-all-block-main-2-main').scrollTop(),
      ico: $('.panel-conteiner-all-block-main-2-main-title-ico').css('background-image'),
      name: $('.panel-conteiner-all-block-main-2-main-title-text-name').html()
    }

    if(FinderSmallNav.HeightSroll - FinderSmallNav.Height >= 150 && adaptiveDesignS != 'phone'){
      if(FinderSmallNav.ScrollTop <= 150 && FinderSmallNav.ScrollTop >= 90){
        var sumScroll = 100 / 60 * (FinderSmallNav.ScrollTop - 90);
        var sizeNav = 0 + ((40 - 0) * (sumScroll / 100));
        $('.panel-conteiner-all-block-main-2-main-smallTitle').css({
          'transform':'translate(0px, ' + (sizeNav - 40) + 'px)',
          'opacity':'1',
          'visibility':'visible',
          'margin-top':FinderSmallNav.ScrollTop + 'px',
        })
        $('.panel-conteiner-all-block-main-2-main-smallTitle-ico').css({
          'background-image':FinderSmallNav.ico
        })
        $('.panel-conteiner-all-block-main-2-main-smallTitle-text').html(FinderSmallNav.name)
      } else if(FinderSmallNav.ScrollTop >= 150){
        $('.panel-conteiner-all-block-main-2-main-smallTitle').css({
          'transform':'translate(0px, ' + 0 + 'px)',
          'opacity':'1',
          'visibility':'visible',
          'margin-top':FinderSmallNav.ScrollTop + 'px',
        })
        $('.panel-conteiner-all-block-main-2-main-smallTitle-ico').css({
          'background-image':FinderSmallNav.ico
        })
        $('.panel-conteiner-all-block-main-2-main-smallTitle-text').html(FinderSmallNav.name)
      } else{
        $('.panel-conteiner-all-block-main-2-main-smallTitle').css({
          'opacity':'0',
          'visibility':'hidden',
        })
        $('.panel-conteiner-all-block-main-2-main-smallTitle-ico').css({
          'background-image':FinderSmallNav.ico
        })
        $('.panel-conteiner-all-block-main-2-main-smallTitle-text').html(FinderSmallNav.name)
      }
    } else{
      $('.panel-conteiner-all-block-main-2-main-smallTitle').css({
        'opacity':'0',
        'visibility':'hidden',
      })
    }
  });

  var tmpHeightBtnTop = $('.panel-conteiner-all-block-main-2-main-btnTop').css('top');
  // кнопка вверх в проводнике
  $('.panel-conteiner-all-block-main-2-main').scroll(function(){
    var FinderTop = {
      HeightSroll: $('.panel-conteiner-all-block-main-2-main').prop('scrollHeight'),
      Height: $('.panel-conteiner-all-block-main-2-main').height(),
      ScrollTop: $('.panel-conteiner-all-block-main-2-main').scrollTop(),
    }
    if(FinderTop.HeightSroll - FinderTop.Height >= 150 && adaptiveDesignS != 'phone'){
      if(FinderTop.ScrollTop >= 150){
        $('.panel-conteiner-all-block-main-2-main-btnTop').css({
          'opacity':'1',
          'transform':'translate(0px, 0%)',
          'visibility':'visible',
          'top': 'calc(' + FinderTop.ScrollTop + 'px + ' + tmpHeightBtnTop + ')'
        })
        setTimeout(function(){
          $('.panel-conteiner-all-block-main-2-main-btnTop').css({
            'transition':'0s all ease-in-out',
          })
        }, 1)
      } else{
        $('.panel-conteiner-all-block-main-2-main-btnTop').css({
          'transition':'0.15s all ease-in-out',
          'opacity':'0',
          'transform':'translate(200%, 00%)',
          'visibility':'hidden',
          'top': 'calc(' + FinderTop.ScrollTop + 'px + ' + tmpHeightBtnTop + ')'
        })
      }
    } else{
      $('.panel-conteiner-all-block-main-2-main-btnTop').css({
        'transition':'0.15s all ease-in-out',
        'opacity':'0',
        'transform':'translate(0px, 00%)',
        'visibility':'hidden',
        'display':'none'
      })
    }

  });

  $(document).contextmenu(function(){

  });



  $(document).mouseup(function (e){
    if(event.which == 1){
      var div = $(".file_manager-contextmenu");

      if (!div.is(e.target) && div.has(e.target).length === 0) {
        div.css('opacity','0')
        div.css('transform','translate(calc(0px),15px)')
        setTimeout(function(){
          div.css('display','none')
        },200)
      }
    }
  });
  // $("#file_manager").mouseup(function (e){
  //   if(event.which == 3){
  //     add_contextmenu(arrayContextMenu);
  //   }
  // });

  sort_name(document.getElementById('sorting-by-name-id'));

  if(pageActive == 'file_manager'){
    finderListing();
  }

});

function topScrollFinder(){
  $('.panel-conteiner-all-block-main-2-main').scrollTop(0);
}

function add_contextmenu(array_elem, debug) {
  var x = event.clientX - $('nav').width();
  var y = event.clientY - $('.main-nav').height();
  var sum = 22;

  arrayContextMenu = array_elem;

  if(debug == true){
    console.log( 'Координаты курсора: (' + x + '; ' + y + ')' );
  }

  if($('.file_manager-contextmenu').css('display') == 'block'){
    if(adaptiveDesignS == 'phone'){
      $('.file_manager-contextmenu').css({'transform':'translate(0px, 150px)','border-radius':'15px'})
    } else{
      $('.file_manager-contextmenu').css({'transform':'translate(0px,15px)','border-radius':'7.5px'})
    }

    $('.file_manager-contextmenu').css('opacity','0')
    setTimeout(function(){
      $('.file_manager-contextmenu').css('display','none')
      setTimeout(function(){
        add_contextmenu_function();
      },10)
    },250)
  } else{
    setTimeout(function(){
      add_contextmenu_function();
    },50)
  }

  function add_contextmenu_function(){

    $('.file_manager-contextmenu').text('')

    for(let i = 0; i < array_elem.length; i++){
      if(array_elem[i][0] == 'open_file'){
        sum = sum + 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-file2'></div><div class='file_manager-contextmenu-elem-text'>Открыть файл</div></div>")
      }
      if(array_elem[i][0] == 'open_folder'){
        sum = sum + 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-folder'></div><div class='file_manager-contextmenu-elem-text'>Открыть папку</div></div>")
      }
      if(array_elem[i][0] == 'open_parent'){
        sum = sum + 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-folder'></div><div class='file_manager-contextmenu-elem-text'>Расположение файла</div></div>")
      }
      if(array_elem[i][0] == 'new_folder'){
        sum = sum + 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-add_folder'></div><div class='file_manager-contextmenu-elem-text'>Новая папка</div></div>")
      }
      if(array_elem[i][0] == 'upload_folder'){
        sum = sum + 35;
        //$('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-upload_folder'></div><div class='file_manager-contextmenu-elem-text'>Загрузить папку</div></div>")
        //$('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + " $(&quot;#finder-upload-folder-input&quot;).bind(&quot;change&quot;, finderUploadMultiply());' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-upload_folder'></div><input type=\"file\" style=\"display: none;\" id=\"finder-upload-folder-input\" webkitdirectory multiple /><label for=\"finder-upload-folder-input\" style=\"cursor: pointer;\"><div class='file_manager-contextmenu-elem-text'>Загрузить папку</div></label></div>")
        //$('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-upload_folder'></div><input type=\"file\" style=\"display: none;\" id=\"finder-upload-folder-input\" webkitdirectory multiple /><label for=\"finder-upload-folder-input\" style=\"cursor: pointer;\"><div class='file_manager-contextmenu-elem-text'>Загрузить папку</div></label></div>")
        $('.file_manager-contextmenu').append("<label for=\"finder-upload-folder-input\" class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-upload_folder'></div><label for=\"finder-upload-folder-input\" style=\"cursor: pointer;\"><div class='file_manager-contextmenu-elem-text'>Загрузить папку</div></label></label>")
      }
      if(array_elem[i][0] == 'menu'){
        sum = sum + 35;
        //$('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-upload_file'></div><div class='file_manager-contextmenu-elem-text'>Загрузить файл</div></div>")
        //$('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + " $(&quot;#finder-upload-file-input&quot;).bind(&quot;change&quot;, finderUploadOneFile());' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-upload_file'></div><input type=\"file\" style=\"display: none;\" id=\"finder-upload-file-input\" /><label for=\"finder-upload-file-input\" style=\"cursor: pointer;\"><div class='file_manager-contextmenu-elem-text'>Загрузить файл</div></label></div>")
        //$('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-upload_file'></div><input type=\"file\" style=\"display: none;\" id=\"finder-upload-file-input\" /><label for=\"finder-upload-file-input\" style=\"cursor: pointer;\"><div class='file_manager-contextmenu-elem-text'>Загрузить файл</div></label></div>")
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-menu'></div><label style=\"cursor: pointer;\"><div class='file_manager-contextmenu-elem-text'>Открыть меню</div></label></div>")
      }
      if(array_elem[i][0] == 'upload'){
        sum = sum + 35;
        //$('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-upload_file'></div><div class='file_manager-contextmenu-elem-text'>Загрузить файл</div></div>")
        //$('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + " $(&quot;#finder-upload-file-input&quot;).bind(&quot;change&quot;, finderUploadOneFile());' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-upload_file'></div><input type=\"file\" style=\"display: none;\" id=\"finder-upload-file-input\" /><label for=\"finder-upload-file-input\" style=\"cursor: pointer;\"><div class='file_manager-contextmenu-elem-text'>Загрузить файл</div></label></div>")
        //$('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-upload_file'></div><input type=\"file\" style=\"display: none;\" id=\"finder-upload-file-input\" /><label for=\"finder-upload-file-input\" style=\"cursor: pointer;\"><div class='file_manager-contextmenu-elem-text'>Загрузить файл</div></label></div>")
        $('.file_manager-contextmenu').append("<label for=\"finder-upload-file-input\" class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-upload_file'></div><label for=\"finder-upload-file-input\" style=\"cursor: pointer;\"><div class='file_manager-contextmenu-elem-text'>Загрузить файл</div></label></label>")
      }
      if(array_elem[i][0] == 'new_file'){
        sum = sum + 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-new_file2'></div><div class='file_manager-contextmenu-elem-text'>Новый файл</div></div>")
      }
      if(array_elem[i] == 'line'){
        sum += 21;
        $('.file_manager-contextmenu').append("<div class='file_manager-contextmenu-elem-line'></div>")
      }
      if(array_elem[i][0] == 'new_zip'){
        sum += 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-zip'></div><div class='file_manager-contextmenu-elem-text'>Создать архив</div></div>")
      }
      if(array_elem[i][0] == 'open_zip'){
        sum += 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-open_zip'></div><div class='file_manager-contextmenu-elem-text'>Распаковать архив</div></div>")
      }
      if(array_elem[i][0] == 'copy'){
        sum += 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-copy'></div><div class='file_manager-contextmenu-elem-text'>Копировать</div></div>")
      }
      if(array_elem[i][0] == 'past'){
        sum += 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-past'></div><div class='file_manager-contextmenu-elem-text'>Вставить</div></div>")
      }
      if(array_elem[i][0] == 'cut_out'){
        sum += 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-cut_out'></div><div class='file_manager-contextmenu-elem-text'>Вырезать</div></div>")
      }
      if(array_elem[i][0] == 'rename'){
        sum += 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-rename'></div><div class='file_manager-contextmenu-elem-text'>Переименовать</div></div>")
      }
      if(array_elem[i][0] == 'lock'){
        sum += 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-lock'></div><div class='file_manager-contextmenu-elem-text'>Добавить пароль</div></div>")
      }
      if(array_elem[i][0] == 'info'){
        sum += 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-question'></div><div class='file_manager-contextmenu-elem-text'>Показать свойства</div></div>")
      }
      if(array_elem[i][0] == 'download'){
        sum += 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-download'></div><div class='file_manager-contextmenu-elem-text'>Скачать</div></div>")
      }
      if(array_elem[i][0] == 'del'){
        sum += 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-basket'></div><div class='file_manager-contextmenu-elem-text'>Удалить</div></div>")
      }
      if(array_elem[i][0] == 'recovery'){
        sum += 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-recovery'></div><div class='file_manager-contextmenu-elem-text'>Восстановить</div></div>")
      }
      if(array_elem[i][0] == 'open_image'){
        sum = sum + 35;
        $('.file_manager-contextmenu').append("<div onclick='" + array_elem[i][1] + "' class='file_manager-contextmenu-elem'><div class='file_manager-contextmenu-elem-img icon-file2'></div><div class='file_manager-contextmenu-elem-text'>Просмотр</div></div>")
      }
      // if(array_elem[i][0] == ''){
      //   $('.file_manager-contextmenu').append("")
      // }
    }

    if(adaptiveDesignS == 'phone'){
      $('.file_manager-contextmenu').css('left', 10 + 'px')
      $('.file_manager-contextmenu').css('top', 'initial')
      $('.file_manager-contextmenu').css('bottom', 10 + 'px')
      $('.file_manager-contextmenu').css('display','block')
    } else{
      if(document.documentElement.clientWidth - x - 277 - $('nav').width() < 0 && document.documentElement.clientHeight > y + sum + $('.main-nav').height()){
        x = document.documentElement.clientWidth - 277 - $('nav').width();
      }
      if(document.documentElement.clientHeight < y + sum + $('.main-nav').height() && document.documentElement.clientWidth - x - 277 - $('nav').width() > 0){
        y = document.documentElement.clientHeight - sum - $('.main-nav').height();
      }
      if(document.documentElement.clientWidth - x - 277 - $('nav').width() < 0 && document.documentElement.clientHeight < y + sum + $('.main-nav').height()){
        x = document.documentElement.clientWidth - 277 - $('nav').width();
        y = document.documentElement.clientHeight - sum - $('.main-nav').height();
      }


      $('.file_manager-contextmenu').css('left', x + 'px')
      $('.file_manager-contextmenu').css('top', y + 'px')
      $('.file_manager-contextmenu').css('bottom', 'initial')
      $('.file_manager-contextmenu').css('display','block')
    }



    setTimeout(function(){
      $('.file_manager-contextmenu').css('opacity','1')
      $('.file_manager-contextmenu').css('transform','translate(0px,0px)')
    },1)
  }

}

function finder_way_auto(array_folder, resize){
      arrayFinderWay = array_folder; // обновляем массив arrayFinderWay

  var nameFUNC = 'finderSetCatalog', // название функции, котороя работает по клику на папку в пути
      array_local = [], // это массив с элементами папкок
      array_local_resize = array_local, // это урезанный массив с элементами папкок если экран меньше чем сам путь
      array_local_resize_hidden = []; // в этом массиве записаны папки, которые скрыты

  $('.file_manager-btn-action-way-main-div').text('') // Отчищаем в блоке с классом file_manager-btn-action-way-main-div все содержимое

  if(array_folder.length > 0 || resize == true){
    if(array_folder.length > 0){
      for(let i = 0; i < array_folder.length; i++){
        if(array_folder[i][0] == '_SERVER' && i == 0){
          array_local.push("<div class='file_manager-btn-action-way-main-GlobalICO' title='Корневой каталог' onclick='" + nameFUNC + "(" + '"' + array_folder[i][1] + '"' + ");'></div>");
        }
        if(array_folder[i][0] != '_SERVER'){
          array_local.push("<div class='file_manager-btn-action-way-main-elem' onclick='" + nameFUNC + "(" + '"' + array_folder[i][1] + '"' + ");' title='" + array_folder[i][0] + "'>" + array_folder[i][0] + "</div>")
        }
        if(i != (array_folder.length - 1)){
          array_local.push("<div class='file_manager-btn-action-way-main-arrow icon-right'></div>")
        }
      }
      local_way_auto();
      // console.log(array_local_resize_hidden)

    } else{
      console.error('Массив не может быть пустым!')
    }

  } else{
    // error
  }
  function local_way_auto(){
    $('.file_manager-btn-action-way-main-div').text('')
    for(let i = 0; i < array_local.length; i++){
      if(i == 1 && array_local_resize_hidden.length > 0){
        $('.file_manager-btn-action-way-main-div').append("<div  class='file_manager-btn-action-way-main-arrow icon-right-second'></div>")
      } else{
        $('.file_manager-btn-action-way-main-div').append(array_local[i])
      }

    }

    windthG = $('.file_manager-btn-action-way-main').outerWidth();
    windthL = $('.file_manager-btn-action-way-main-div').outerWidth();

    if(array_local_resize.length > 3){
      if(windthG - windthL < -5){

        array_local_resize_hidden.push(array_local_resize[2])
        array_local_resize.splice(2, 2)

        $('.file_manager-btn-action-way-main-div').text('')

        for(let i = 0; i < array_local_resize.length; i++){
          $('.file_manager-btn-action-way-main-div').append(array_local_resize[i])
        }

        windthG = $('.file_manager-btn-action-way-main').outerWidth();
        windthL = $('.file_manager-btn-action-way-main-div').outerWidth();

        local_way_auto();
      } else{
        array_local_resize = array_local;
      }
    }
  }
}

function change_fontsSize_finder(a,c,d){
  var a = $(a),
      b = a.parent().find('.panel-user-ab-btn-conteiner-block-title-2');

  if(a.val() == 0){
    b.text('XXS')
  } else if(a.val() == 1){
    b.text('XS')
  } else if(a.val() == 2){
    b.text('S')
  } else if(a.val() == 3){
    b.text('M')
  } else if(a.val() == 4){
    b.text('L')
  } else if(a.val() == 5){
    b.text('XL')
  } else if(a.val() == 6){
    b.text('XXL')
  }

  if(c == 1){
    if(d){
      $.cookie('fontsSize', a.val(), {expires: 99999});
    } else{
      for(let i = 0; i < 7; i++){
        if(Number($.cookie('fontsSize')) == i){
          a.val(i);
        }
      }
    }
    if($.cookie('fontsSize') == 0){
      $('html').get(0).style.setProperty('--fontsSizeFinder', 0.5)
    }
    if($.cookie('fontsSize') == 1){
      $('html').get(0).style.setProperty('--fontsSizeFinder', 0.65)
    }
    if($.cookie('fontsSize') == 2){
      $('html').get(0).style.setProperty('--fontsSizeFinder', 0.85)
    }
    if($.cookie('fontsSize') == 3){
      $('html').get(0).style.setProperty('--fontsSizeFinder', 1)
    }
    if($.cookie('fontsSize') == 4){
      $('html').get(0).style.setProperty('--fontsSizeFinder', 1.25)
    }
    if($.cookie('fontsSize') == 5){
      $('html').get(0).style.setProperty('--fontsSizeFinder', 1.5)
    }
    if($.cookie('fontsSize') == 6){
      $('html').get(0).style.setProperty('--fontsSizeFinder', 1.8)
    }
  }
}

$(document).ready(function(){
  $('#chSpaceLittleWindow').on('change', function(e){
    if($(this).prop('checked')){
      $.cookie('SpaceLittleWindow','false',{expires: 99999});
      $('#chSpaceLittleWindow1').prop('checked',false)
    } else{
      $.cookie('SpaceLittleWindow','true',{expires: 99999});
      $('#chSpaceLittleWindow1').prop('checked',true)
    }
  });
  $('#chSpaceLittleWindow1').on('change', function(e){
    if($(this).prop('checked')){
      $.cookie('SpaceLittleWindow','true',{expires: 99999});
      $('#chSpaceLittleWindow').prop('checked',false)
    } else{
      $.cookie('SpaceLittleWindow','false',{expires: 99999});
      $('#chSpaceLittleWindow').prop('checked',true)
    }
  });
});

function server_memory_info(value, valueMAX){

  var block = $('.panel-conteiner-all-block-main-2-nav-block-0');

  var value1 = Math.ceil((value * 100) / valueMAX);

  if(value1 > 70){
    if(windowSpaceLittle && $.cookie('SpaceLittleWindow') == 'true'){
      setTimeout(function(){
        if($('#file_manager').css('display') == 'block'){
          windowSpaceLittle = false;
          open_window('#finder-spaceLittle');

        }
      }, 250)
    }
    block.find('.progress').css({
      'filter':'saturate(2.3) hue-rotate(130deg)'
    })
  } else{
    block.find('.progress').css({
      'filter':'saturate(1) hue-rotate(0deg)'
    })
  }

  if(value < 1000){
    value = value.toFixed(2) + ' байт';
  }
  if(value >= 1000 && value < 1000000){
    value = (value / 1000).toFixed(2) + ' Кб';
  }
  if(value >= 1000000 && value < 1000000000){
    value = (value / 1000 / 1000).toFixed(2) + ' Мб';
  }
  if(value >= 1000000000 && value < 1000000000000){
    value = (value / 1000 / 1000 / 1000).toFixed(2) + ' Гб';
  }
  if(value >= 1000000000000 && value < 1000000000000000){
    value = (value / 1000 / 1000 / 1000 / 1000).toFixed(2) + ' Тб';
  }
  if(value >= 1000000000000000 && value < 1000000000000000000){
    value = (value / 1000 / 1000 / 1000 / 1000 / 1000).toFixed(2) + ' Пб';
  }
  if(value >= 1000000000000000000 && value < 1000000000000000000000){
    value = (value / 1000 / 1000 / 1000 / 1000 / 1000 / 1000).toFixed(2) + ' Эб';
  }
  if(value >= 1000000000000000000000 && value < 1000000000000000000000000){
    value = (value / 1000 / 1000 / 1000 / 1000 / 1000 / 1000 / 1000).toFixed(2) + ' Зб';
  }
  if(value >= 1000000000000000000000000 && value < 1000000000000000000000000000){
    value = (value / 1000 / 1000 / 1000 / 1000 / 1000 / 1000 / 1000 / 1000).toFixed(2) + ' Йб';
  }

  if(valueMAX < 1000){
    valueMAX = valueMAX.toFixed(0) + ' байт';
  }
  if(valueMAX >= 1000 && valueMAX < 1000000){
    valueMAX = (valueMAX / 1024).toFixed(0) + ' Кб';
  }
  if(valueMAX >= 1000000 && valueMAX < 1000000000){
    valueMAX = (valueMAX / 1024 / 1024).toFixed(0) + ' Мб';
  }
  if(valueMAX >= 1000000000 && valueMAX < 1000000000000){
    valueMAX = (valueMAX / 1024 / 1024 / 1024).toFixed(0) + ' Гб';
  }
  if(valueMAX >= 1000000000000 && valueMAX < 1000000000000000){
    valueMAX = (valueMAX / 1024 / 1024 / 1024 / 1024).toFixed(0) + ' Тб';
  }
  if(valueMAX >= 1000000000000000 && valueMAX < 1000000000000000000){
    valueMAX = (valueMAX / 1024 / 1024 / 1024 / 1024 / 1024).toFixed(0) + ' Пб';
  }
  if(valueMAX >= 1000000000000000000 && valueMAX < 1000000000000000000000){
    valueMAX = (valueMAX / 1024 / 1024 / 1024 / 1024 / 1024 / 1024).toFixed(0) + ' Эб';
  }
  if(valueMAX >= 1000000000000000000000 && valueMAX < 1000000000000000000000000){
    valueMAX = (valueMAX / 1024 / 1024 / 1024 / 1024 / 1024 / 1024 / 1024).toFixed(0) + ' Зб';
  }
  if(valueMAX >= 1000000000000000000000000 && valueMAX < 1000000000000000000000000000){
    valueMAX = (valueMAX / 1024 / 1024 / 1024 / 1024 / 1024 / 1024 / 1024  / 1024).toFixed(0) + ' Йб';
  }

  block.find('.progress-value').attr('value', value1 + '%')
  block.find('.progress-value').css('width', value1 + '%')
  block.find('.progress-data-used').text(value)
  block.find('.progress-data-total').text(valueMAX)

}

function finderImagePreload(a){
  if(a){
    $.cookie('finderImagePreload', 'false', {expires: 99999});
  } else{
    $.cookie('finderImagePreload', 'true', {expires: 99999});
  }

}

function change_style_finder(type){
  if(type.length > 0){
    if(type == 'block'){
      style_finder = 'block';
      $.cookie('style_finder', 'block', {expires: 99999});
      $('.panel-conteiner-all-block-main-2-main-filter').css('display','none')
      $('#typeStyleFinder-block').attr('checked','checked')
      $('#folderSort').css('text-align','left')
      $('#folderSort').css('padding-left','15px')
      $('#folderSort').css('padding-right','15px')
      $('.panel-conteiner-all-block-main-2-main-elem').css('cursor','pointer')
      $('.panel-conteiner-all-block-main-2-main-elem').css('display','inline-block')
      $('.panel-conteiner-all-block-main-2-main-elem').css('height','calc(100px * var(--fontsSizeFinder))')
      $('.panel-conteiner-all-block-main-2-main-elem').css('width','calc(100px * var(--fontsSizeFinder))')
      $('.panel-conteiner-all-block-main-2-main-elem').css('border','0px solid var(--border-color)')
      $('.panel-conteiner-all-block-main-2-main-elem').css('border-radius','7.5px')
      $('.panel-conteiner-all-block-main-2-main-elem').css('vertical-align','middle')
      $('.panel-conteiner-all-block-main-2-main-elem').css('margin-bottom','10px')
      $('.panel-conteiner-all-block-main-2-main-elem').css('margin-right','10px')
      $('.panel-conteiner-all-block-main-2-main-elem-ch-a').css({'display':'block','position':'absolute','opacity':'1','z-index':'1','left':'10px','margin-left':'0px'})
      $('.panel-conteiner-all-block-main-2-main-elem-ch').css({'display':'block','position':'absolute','opacity':'0','z-index':'1','left':'10px','margin-left':'0px'})
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('margin-left','0px')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('left','0')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('right','0')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('margin','auto')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('display','block')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('height','calc(60px * var(--fontsSizeFinder))')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('width','calc(60px * var(--fontsSizeFinder))')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('background-size','calc(60px * var(--fontsSizeFinder))')
      $('.panel-conteiner-all-block-main-2-main-elem-name').css('display','block')
      $('.panel-conteiner-all-block-main-2-main-elem-name').css('width','100%')
      $('.panel-conteiner-all-block-main-2-main-elem-name').css({'text-align':'center','font-size':'calc(14px * var(--fontsSizeFinder))'})
      $('.panel-conteiner-all-block-main-2-main-elem-name').css({'margin-left':'0px','font-family':'pfl'})
      $('.panel-conteiner-all-block-main-2-main-elem-name').css({'margin-top':'8px','margin-left':'-10px','bottom':'10px','position':'absolute'})
      $('.panel-conteiner-all-block-main-2-main-elem-name').css('max-height','41px')
      $('.panel-conteiner-all-block-main-2-main-elem-name').css('overflow','auto')
      $('.panel-conteiner-all-block-main-2-main-elem-date').css('display','none')
      $('.panel-conteiner-all-block-main-2-main-elem-type').css('display','none')
      $('.panel-conteiner-all-block-main-2-main-elem-size').css('display','none')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('display','block')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('margin-left','0px')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('left','0')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('right','0')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('margin','auto')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('height','calc(60px * var(--fontsSizeFinder))')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('width','calc(60px * var(--fontsSizeFinder))')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('background-size','calc(60px * var(--fontsSizeFinder))')
    }
    if(type == 'line'){
      style_finder = 'line';
      $.cookie('style_finder', 'line', {expires: 99999});
      $('#typeStyleFinder-line').attr('checked','checked')
      $('.panel-conteiner-all-block-main-2-main-filter').css('display','block')
      $('#typeStyleFinder-line').attr('checked','checked')
      $('#folderSort').css('text-align','left')
      $('#folderSort').css('padding-left','0px')
      $('#folderSort').css('padding-right','0px')
      $('.panel-conteiner-all-block-main-2-main-elem').css('cursor','default')
      $('.panel-conteiner-all-block-main-2-main-elem').css('display','block')
      $('.panel-conteiner-all-block-main-2-main-elem').css('height','auto')
      $('.panel-conteiner-all-block-main-2-main-elem').css('width','calc(100% - 20px)')
      $('.panel-conteiner-all-block-main-2-main-elem').css('border','none')
      $('.panel-conteiner-all-block-main-2-main-elem').css('border-bottom','1px solid var(--border-color)')
      $('.panel-conteiner-all-block-main-2-main-elem').css('border-radius','0px')
      $('.panel-conteiner-all-block-main-2-main-elem').css('vertical-align','initial')
      $('.panel-conteiner-all-block-main-2-main-elem').css('margin-bottom','0px')
      $('.panel-conteiner-all-block-main-2-main-elem').css('margin-right','0px')
      if(adaptiveDesignS == 'phone'){
        $('.panel-conteiner-all-block-main-2-main-elem-ch-a').css({'display':'none','position':'absolute','opacity':'0','z-index':'1','left':'10px','margin-left':'0px'})
        $('.panel-conteiner-all-block-main-2-main-elem-ch').css({'display':'none','position':'absolute','opacity':'0','z-index':'1','left':'10px','margin-left':'0px'})
      } else{
        $('.panel-conteiner-all-block-main-2-main-elem-ch-a').css({'display':'inline-block','position':'relative','opacity':'initial','z-index':'1','left':'initial','margin-left':'20px'})
        $('.panel-conteiner-all-block-main-2-main-elem-ch').css({'display':'inline-block','position':'relative','opacity':'initial','z-index':'1','left':'initial','margin-left':'20px'})
      }

      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('margin-left','17.5px')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('left','initial')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('right','initial')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('margin','initial')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('margin-left','17.5px')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('display','inline-block')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('height','calc(25px * var(--fontsSizeFinder)')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('width','calc(25px * var(--fontsSizeFinder)')
      $('.panel-conteiner-all-block-main-2-main-elem-ico-finder').css('background-size','calc(25px * var(--fontsSizeFinder)')

      if(adaptiveDesignS == 'phone'){
        $('.panel-conteiner-all-block-main-2-main-elem-name').css('margin-left','2.5px')
        $('.panel-conteiner-all-block-main-2-main-elem-date').css('width','calc(20% - 0px)')
        $('.panel-conteiner-all-block-main-2-main-elem-size').css('width','calc(20% - 0px)')
      } else{
        $('.panel-conteiner-all-block-main-2-main-elem-name').css('margin-left','17.5px')
        $('.panel-conteiner-all-block-main-2-main-elem-date').css('width','calc(calc(100% / 7) - 10px)')
        $('.panel-conteiner-all-block-main-2-main-elem-size').css('width','calc(calc(100% / 7) - 10px)')
      }

      $('.panel-conteiner-all-block-main-2-main-elem-name').css('display','inline-block')
      $('.panel-conteiner-all-block-main-2-main-elem-name').css('overflow','hidden')
      $('.panel-conteiner-all-block-main-2-main-elem-name').css('width','31%')

      $('.panel-conteiner-all-block-main-2-main-elem-name').css({'text-align':'left','font-size':'calc(16px * var(--fontsSizeFinder))'})
      $('.panel-conteiner-all-block-main-2-main-elem-name').css({'font-family':'pfm'})
      $('.panel-conteiner-all-block-main-2-main-elem-name').css({'margin-top':'0px','bottom':'initial','position':'relative'})
      $('.panel-conteiner-all-block-main-2-main-elem-name').css('max-height','initial')


      $('.panel-conteiner-all-block-main-2-main-elem-date').css('display','inline-block')
      if(adaptiveDesignS == 'phone'){
        $('.panel-conteiner-all-block-main-2-main-elem-type').css('display','none')
      } else{
        $('.panel-conteiner-all-block-main-2-main-elem-type').css('display','inline-block')
      }

      $('.panel-conteiner-all-block-main-2-main-elem-size').css('display','inline-block')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('display','inline-block')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('margin-left','17.5px')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('left','initial')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('right','initial')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('margin','initial')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('margin-left','17.5px')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('height','calc(25px * var(--fontsSizeFinder)')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('width','calc(25px * var(--fontsSizeFinder)')
      $('.panel-conteiner-all-block-main-2-main-elem-ico').css('background-size','calc(25px * var(--fontsSizeFinder)')
    }
  } else{
    console.error('Ошибка в переменной "type"!')
  }
}

function finderMenuOpen(){
  $('.shadow-finder').css({'opacity':'1','visibility':'visible'})
  $('.panel-conteiner-all-block-main-2-nav').css({'transform':'translate(0%, 0px)','z-index':'9'})
  closeContextMenu();
}

function finderMenuClose(){
  if(adaptiveDesignS != 'PC'){
    $('.shadow-finder').css({'opacity':'0','visibility':'hidden'})
    $('.panel-conteiner-all-block-main-2-nav').css({'transform':'translate(-100%, 0px)','z-index':'9'})
  }
}

function load_finder(a){
  if(a == 'hidden'){
    loader('hidden');
    $('.shadow-finder').attr('onclick','finderMenuClose()')
    $('.shadow-finder').css({
      'opacity':'0',
      'visibility':'hidden'
    })
  }
  if(a == 'show'){
    loader('show');
    $('.shadow-finder').removeAttr('onclick')
    $('.shadow-finder').css({
      'opacity':'0.25',
      'visibility':'visible'
    })
  }
}

function sort_date(a){
  var b = $(a).parent().parent().find('.panel-conteiner-all-block-main-2-main-elem')
  var arrayDate = [];
  $(a).parent().find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('opacity','0')
  $(a).find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('opacity','1')
  for(let i = 0; i < b.length; i++){
    var date = $(b[i]).find('.panel-conteiner-all-block-main-2-main-elem-date').text()
    date = date.split('.');
    date = date[2]+''+date[1]+''+date[0];
    arrayDate.push([date, b[i]]);
  }
  $('#folderSort').empty()

  if(!sortDate){
    sortDate = true;
    sortType = false;
    sortSize = false;
    sortName = false;
    $(a).find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('transform','rotate(-90deg)')
    arrayDate.sort(function(a,b){
      return a[0] - b[0];
    })
  } else{
    sortDate = false;
    sortType = false;
    sortSize = false;
    sortName = false;
    $(a).find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('transform','rotate(90deg)')
    arrayDate.sort(function(a,b){
      return b[0] - a[0];
    })
  }

  for(let i = 0; i < arrayDate.length; i++){
    $('#folderSort').append(arrayDate[i][1])
  }
}

function sort_type(a){
  var b = $(a).parent().parent().find('.panel-conteiner-all-block-main-2-main-elem')
  var arrayDate = [];
  $(a).parent().find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('opacity','0')
  $(a).find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('opacity','1')
  for(let i = 0; i < b.length; i++){
    var date = $(b[i]).find('.panel-conteiner-all-block-main-2-main-elem-type').text()
    date = date.split('.');
    date = date[2]+''+date[1]+''+date[0];
    arrayDate.push([date, b[i]]);
  }
  $('#folderSort').empty()

  if(!sortType){
    sortDate = false;
    sortType = true;
    sortSize = false;
    sortName = false;
    $(a).find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('transform','rotate(-90deg)')
    arrayDate.sort()
  } else{
    sortDate = false;
    sortType = false;
    sortSize = false;
    sortName = false;
    $(a).find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('transform','rotate(90deg)')
    arrayDate.reverse()
  }

  for(let i = 0; i < arrayDate.length; i++){
    $('#folderSort').append(arrayDate[i][1])
  }
}

function sort_size(a){
  var b = $(a).parent().parent().find('.panel-conteiner-all-block-main-2-main-elem')
  var arrayDate = [];
  $(a).parent().find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('opacity','0')
  $(a).find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('opacity','1')
  for(let i = 0; i < b.length; i++){
    var size = $(b[i]).find('.panel-conteiner-all-block-main-2-main-elem-size').text(),
        sizeNew;
    size = size.split(' ');

    if(size[1] == 'ТБ'){
      sizeNew = size[0] * 1024 * 1024 * 1024 * 1024 * 8;
    }
    if(size[1] == 'ГБ'){
      sizeNew = size[0] * 1024 * 1024 * 1024 * 8;
    }
    if(size[1] == 'МБ'){
      sizeNew = size[0] * 1024 * 1024 * 8;
    }
    if(size[1] == 'КБ'){
      sizeNew = size[0] * 1024 * 8;
    }
    if(size[1] == 'б'){
      sizeNew = size[0] * 8;
    }
    arrayDate.push([sizeNew, b[i]]);
  }
  $('#folderSort').empty()

  if(!sortSize){
    sortDate = false;
    sortType = false;
    sortSize = true;
    sortName = false;
    $(a).find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('transform','rotate(-90deg)')
    arrayDate.sort(function(a,b){
      return a[0] - b[0];
    })
  } else{
    sortDate = false;
    sortType = false;
    sortSize = false;
    sortName = false;
    $(a).find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('transform','rotate(90deg)')
    arrayDate.sort(function(a,b){
      return b[0] - a[0];
    })
  }

  for(let i = 0; i < arrayDate.length; i++){
    $('#folderSort').append(arrayDate[i][1])
  }
}

function sort_name(a){
  var b = $(a).parent().parent().find('.panel-conteiner-all-block-main-2-main-elem')
  var arrayDate = [];
  var arrayDate2 = [];
  $(a).parent().find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('opacity','0')
  $(a).find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('opacity','1')
  for(let i = 0; i < b.length; i++){
    var date = $(b[i]).find('.panel-conteiner-all-block-main-2-main-elem-name').text()
    if($(b[i]).find('.panel-conteiner-all-block-main-2-main-elem-ico-finder').length > 0){
      arrayDate.push([date, b[i]]);
    }
  }
  arrayDate.sort()
  for(let i = 0; i < b.length; i++){
    var date = $(b[i]).find('.panel-conteiner-all-block-main-2-main-elem-name').text()
    if($(b[i]).find('.panel-conteiner-all-block-main-2-main-elem-ico-finder').length == 0){
      arrayDate2.push([date, b[i]]);
    }
  }
  arrayDate2.sort()

  $('#folderSort').empty()

  for(let i = 0; i < arrayDate2.length; i++){
    arrayDate.push(arrayDate2[i])
  }

  if(!sortName){
    sortDate = false;
    sortType = false;
    sortSize = false;
    sortName = true;
    $(a).find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('transform','rotate(-90deg)')
  } else{
    sortDate = false;
    sortType = false;
    sortSize = false;
    sortName = false;
    $(a).find('.panel-conteiner-all-block-main-2-main-filter-elem-ico').css('transform','rotate(90deg)')
    arrayDate.reverse()
  }

  for(let i = 0; i < arrayDate.length; i++){
    $('#folderSort').append(arrayDate[i][1])
  }
}

function finderHistoryPrevCatalog() {
  if(Finder.listingTo == 'search' || Finder.listingTo == 'trash') {
    finderListing('all');
    return;
  }
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      set_prev_catalog: true
    },
    beforeSend: function(){
      load_finder('show');
    },
    complete: function(){
      load_finder('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        finderListing();
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

function finderHistoryNextCatalog() {
  if(Finder.listingTo == 'search' || Finder.listingTo == 'trash') {
    return;
  }
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      set_next_catalog: true
    },
    beforeSend: function(){
      load_finder('show');
    },
    complete: function(){
      load_finder('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        finderListing();
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

function finderSetCatalog(path, mode) {
  // set mode to default (all)
  if(typeof(mode) == 'undefined') {
    Finder.listingTo = 'all';
  }
  // set custom mode
  else {
    Finder.listingTo = mode;
  }
  // request
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      set_catalog: path
    },
    beforeSend: function(){
      load_finder('show');
    },
    complete: function(){
      load_finder('hidden');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        finderListing();
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

function finderConvertSize(size) {
  var step = 0;
  var map = ['б', 'КБ', 'МБ', 'ГБ', 'ТБ'];
  while(size > 100) {
    size /= 1024;
    step++;
  }
  return String(size.toFixed(2) + ' ' + map[step]);
}

function finderGetIconByExtension(ext, type) {
  if(typeof(ext) == 'undefined') {
    ext = 'none';
  }
  if(typeof(type) == 'undefined') {
    type = 'other';
  }
  ext = ext.toUpperCase();
  // ===========================================================================
  if(ext == '7z') { return 'media/filesICO/svg/7z.svg'; }
  else if(ext == 'AAC') { return 'media/filesICO/svg/AAC.svg'; }
  else if(ext == 'APK') { return 'media/filesICO/svg/APK.svg'; }
  else if(ext == 'AVI') { return 'media/filesICO/svg/AVI.svg'; }
  else if(ext == 'BAT') { return 'media/filesICO/svg/BAT.svg'; }
  else if(ext == 'CR2') { return 'media/filesICO/svg/CR2.svg'; }
  else if(ext == 'CSS') { return 'media/filesICO/svg/CSS.svg'; }
  else if(ext == 'DEV') { return 'media/filesICO/svg/DEV.svg'; }
  else if(ext == 'DMG') { return 'media/filesICO/svg/DMG.svg'; }
  else if(ext == 'DOC') { return 'media/filesICO/svg/DOC.svg'; }
  else if(ext == 'EXE') { return 'media/filesICO/svg/EXE.svg'; }
  else if(ext == 'EXEL') { return 'media/filesICO/svg/EXEL.svg'; }
  else if(ext == 'FLV') { return 'media/filesICO/svg/FLV.svg'; }
  else if(ext == 'HTML') { return 'media/filesICO/svg/HTML.svg'; }
  else if(ext == 'IMG') { return 'media/filesICO/svg/IMG.svg'; }
  else if(ext == 'INI') { return 'media/filesICO/svg/INI.svg'; }
  else if(ext == 'INO') { return 'media/filesICO/svg/INO.svg'; }
  else if(ext == 'JS') { return 'media/filesICO/svg/JS.svg'; }
  else if(ext == 'JSON') { return 'media/filesICO/svg/JSON.svg'; }
  else if(ext == 'LIB') { return 'media/filesICO/svg/LIB.svg'; }
  else if(ext == 'MOV') { return 'media/filesICO/svg/MOV.svg'; }
  else if(ext == 'MP3') { return 'media/filesICO/svg/MP3.svg'; }
  else if(ext == 'MP4') { return 'media/filesICO/svg/MP4.svg'; }
  else if(ext == 'MPG') { return 'media/filesICO/svg/MPG.svg'; }
  else if(ext == 'MYSQL') { return 'media/filesICO/svg/MYSQL.svg'; }
  else if(ext == 'OGG') { return 'media/filesICO/svg/OGG.svg'; }
  else if(ext == 'OTF') { return 'media/filesICO/svg/OTF.svg'; }
  else if(ext == 'PHP') { return 'media/filesICO/svg/PHP.svg'; }
  else if(ext == 'PPT') { return 'media/filesICO/svg/PPT.svg'; }
  else if(ext == 'PSD') { return 'media/filesICO/svg/PSD.svg'; }
  else if(ext == 'RAR') { return 'media/filesICO/svg/RAR.svg'; }
  else if(ext == 'RAW') { return 'media/filesICO/svg/RAW.svg'; }
  else if(ext == 'SRYPT') { return 'media/filesICO/svg/SRYPT.svg'; }
  else if(ext == 'TTF') { return 'media/filesICO/svg/TTF.svg'; }
  else if(ext == 'TXT') { return 'media/filesICO/svg/TXT.svg'; }
  else if(ext == 'WAV') { return 'media/filesICO/svg/WAV.svg'; }
  else if(ext == 'WEBM') { return 'media/filesICO/svg/WEBM.svg'; }
  else if(ext == 'WMA') { return 'media/filesICO/svg/WMA.svg'; }
  else if(ext == 'WOFF') { return 'media/filesICO/svg/WOFF.svg'; }
  else if(ext == 'WORD') { return 'media/filesICO/svg/WORD.svg'; }
  else if(ext == 'ZIP') { return 'media/filesICO/svg/ZIP.svg'; }
  else if(ext == 'JPEG') { return 'media/filesICO/svg/IMG.svg'; }
  else if(ext == 'JPG') { return 'media/filesICO/svg/IMG.svg'; }
  else if(ext == 'GIF') { return 'media/filesICO/svg/IMG.svg'; }
  else if(ext == 'PNG') { return 'media/filesICO/svg/IMG.svg'; }
  else if(ext == 'WEBP') { return 'media/filesICO/svg/IMG.svg'; }
  else if(ext == 'SVG') { return 'media/filesICO/svg/IMG.svg'; }
  else if(ext == 'KEY') { return 'media/filesICO/svg/document.svg'; }
  else if(ext == 'ODP') { return 'media/filesICO/svg/document.svg'; }
  else if(ext == 'PPS') { return 'media/filesICO/svg/document.svg'; }
  else if(ext == 'PPT') { return 'media/filesICO/svg/document.svg'; }
  else if(ext == 'PPTX') { return 'media/filesICO/svg/document.svg'; }
  else if(ext == 'DOC') { return 'media/filesICO/svg/document.svg'; }
  else if(ext == 'DOCX') { return 'media/filesICO/svg/document.svg'; }
  else if(ext == 'ODT') { return 'media/filesICO/svg/document.svg'; }
  else if(ext == 'PDF') { return 'media/filesICO/svg/document.svg'; }
  else if(ext == 'RTF') { return 'media/filesICO/svg/document.svg'; }
  else if(ext == 'TEX') { return 'media/filesICO/svg/document.svg'; }
  else if(ext == 'TXT') { return 'media/filesICO/svg/document.svg'; }
  else if(ext == 'WPD') { return 'media/filesICO/svg/document.svg'; }
  else if(ext == 'PDPP') { return 'media/filesICO/svg/document.svg'; }
  else if(ext == 'APK') { return 'media/filesICO/svg/BAT.svg'; }
  else if(ext == 'BAT') { return 'media/filesICO/svg/BAT.svg'; }
  else if(ext == 'BIN') { return 'media/filesICO/svg/BAT.svg'; }
  else if(ext == 'CGI') { return 'media/filesICO/svg/BAT.svg'; }
  else if(ext == 'PL') { return 'media/filesICO/svg/BAT.svg'; }
  else if(ext == 'COM') { return 'media/filesICO/svg/BAT.svg'; }
  else if(ext == 'EXE') { return 'media/filesICO/svg/BAT.svg'; }
  else if(ext == 'GADGET') { return 'media/filesICO/svg/BAT.svg'; }
  else if(ext == 'JAR') { return 'media/filesICO/svg/BAT.svg'; }
  else if(ext == 'MSI') { return 'media/filesICO/svg/BAT.svg'; }
  else if(ext == 'PY') { return 'media/filesICO/svg/BAT.svg'; }
  else if(ext == 'WSF') { return 'media/filesICO/svg/BAT.svg'; }
  else if(ext == '7Z') { return 'media/filesICO/svg/ARCHIVE.svg'; }
  else if(ext == 'ARJ') { return 'media/filesICO/svg/ARCHIVE.svg'; }
  else if(ext == 'DEB') { return 'media/filesICO/svg/ARCHIVE.svg'; }
  else if(ext == 'PKG') { return 'media/filesICO/svg/ARCHIVE.svg'; }
  else if(ext == 'RAR') { return 'media/filesICO/svg/ARCHIVE.svg'; }
  else if(ext == 'RPM') { return 'media/filesICO/svg/ARCHIVE.svg'; }
  else if(ext == 'GZ') { return 'media/filesICO/svg/ARCHIVE.svg'; }
  else if(ext == 'Z') { return 'media/filesICO/svg/ARCHIVE.svg'; }
  else if(ext == 'ZIP') { return 'media/filesICO/svg/ARCHIVE.svg'; }
  else if(ext == 'AIF') { return 'media/filesICO/svg/AUDIO.svg'; }
  else if(ext == 'CDA') { return 'media/filesICO/svg/AUDIO.svg'; }
  else if(ext == 'MID') { return 'media/filesICO/svg/AUDIO.svg'; }
  else if(ext == 'MIDI') { return 'media/filesICO/svg/AUDIO.svg'; }
  else if(ext == 'MP3') { return 'media/filesICO/svg/AUDIO.svg'; }
  else if(ext == 'MPA') { return 'media/filesICO/svg/AUDIO.svg'; }
  else if(ext == 'OGG') { return 'media/filesICO/svg/AUDIO.svg'; }
  else if(ext == 'WAV') { return 'media/filesICO/svg/AUDIO.svg'; }
  else if(ext == 'WMA') { return 'media/filesICO/svg/AUDIO.svg'; }
  else if(ext == 'WPL') { return 'media/filesICO/svg/AUDIO.svg'; }
  else if(ext == '3G2') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(ext == '3GP') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(ext == 'AVI') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(ext == 'FLV') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(ext == 'H264') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(ext == 'M4V') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(ext == 'MKV') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(ext == 'MOV') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(ext == 'MP4') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(ext == 'MPG') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(ext == 'MPEG') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(ext == 'RM') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(ext == 'SWF') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(ext == 'VOB') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(ext == 'WMV') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(type == 'video') { return 'media/filesICO/svg/VIDEO.svg'; }
  else if(type == 'audio') { return 'media/filesICO/svg/AUDIO.svg'; }
  else if(type == 'compressed') { return 'media/filesICO/svg/ARCHIVE.svg'; }
  else if(type == 'executable') { return 'media/filesICO/svg/BAT.svg'; }
  else if(type == 'document') { return 'media/filesICO/svg/document.svg'; }
  else if(type == 'image') { return 'media/filesICO/svg/IMG.svg'; }
  else if(type == 'other') { return 'media/filesICO/svg/DEV.svg'; }
  else if(type == 'file') { return 'media/filesICO/svg/DEV.svg'; }
  else { return 'media/filesICO/svg/ERROR.svg'; }
  // ===========================================================================
}

function closeContextMenu() {
  var div = $(".file_manager-contextmenu");
  div.css('opacity','0');
  div.css('transform','translate(calc(0px),15px)');
  setTimeout(function() {
    div.css('display','none');
  }, 200);
}

function finderListing(set_mode) {
  // set listing mode
  if(typeof(set_mode) != 'undefined') {
    Finder.listingTo = set_mode;
  }
  // clear search field
  $('#file_manager-btn-action-way-search').val('');
  // request
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      listing: true
    },
    beforeSend: function(){
      load_finder('show');
    },
    complete: function(){
      load_finder('hidden');
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
        $('#finder-title-icon').css('background-image', 'url(media/svg/folderColor.svg)');
        // parent volume
        $('#finder-title-volume-id').text(' ');
        // parse path
        var path = responseData.path;
        Finder.currentPath = path;
        var pathArray = path.split('/');
        var newPathArray = [];
        var fullPathStringNew = '';
        for(var i = 0; i < pathArray.length - 1; i++) {
          if(i == 0) {
            newPathArray[i] = ['_SERVER', '/'];
            fullPathStringNew += '/';
          }
          else {
            fullPathStringNew += pathArray[i] + '/';
            newPathArray[i] = [pathArray[i], fullPathStringNew];
          }
        }
        arrayFinderWay = newPathArray;
        finder_way_auto(arrayFinderWay, true);
        // parse parent folder (title)
        var title = '';
        if(path == '/') {
          title = 'Корневой каталог';
        }
        else if(path == '/USERS_FILES/') {
          title = 'Пользователи';
        }
        else if(path == '/BOOKS_FILES/') {
          title = 'Учебники';
        }
        else if(path == '/DOCS_FILES/') {
          title = 'Документы';
        }
        else {
          title = path.substring(0, path.length - 1);
          title = title.substring(title.lastIndexOf('/') + 1);
        }
        $('#finder-title-id').text(title);
        // parse memory
        var memory = responseData.memory.val;
        var memoryMax = responseData.memory.max;
        server_memory_info(memory, memoryMax);
        // buttons
        var prevAvailable = responseData.history_data.prev;
        var nextAvailable = responseData.history_data.next;
        if(prevAvailable) {
          $('#finder-history-btn-left').attr('class', 'file_manager-action-btn');
        }
        else {
          $('#finder-history-btn-left').attr('class', 'file_manager-action-btn-none');
        }
        if(nextAvailable) {
          $('#finder-history-btn-right').attr('class', 'file_manager-action-btn');
        }
        else {
          $('#finder-history-btn-right').attr('class', 'file_manager-action-btn-none');
        }
        // files data
        var listing = responseData.listing;
        Finder.currentFiles = [];
        // files count
        var count = listing.length;
        //var cstr = String(count);
        var word = declOfNumber(count, 'элемент');
        $('#finder-title-count-id').text(String(count) + ' ' + word);
        // clear finder
        $('#folderSort').empty();
        // parse files
        for(var i = 0; i < listing.length; i++) {
          var file = listing[i];
          var fullpath = path + file.filename + '/';
          var fullpath_file = path + file.filename;
          var output = '';
          if(file.filetype == 'directory') {
            output += '<div class="panel-conteiner-all-block-main-2-main-elem" oncontextmenu="finderElementsClear(true); ';
            output += 'add_contextmenu([[\'open_folder\', \'finderSetCatalog(&quot;' + fullpath + '&quot;)\'], [\'line\'], [\'new_zip\', \'finderCreateZip(&quot;' + fullpath + '&quot;)\'], [\'copy\', \'finderCopyOne(&quot;' + fullpath + '&quot;)\'], [\'cut_out\', \'finderCutOne(&quot;' + fullpath + '&quot;)\'], [\'rename\', \'finderRenameWindow(&quot;' + path + '&quot;, &quot;' + file.filename + '&quot;, &quot;directory&quot;)\'], [\'download\', \'finderDownloadCatalog(&quot;' + fullpath + '&quot;)\'], [\'line\'], [\'del\', \'finderRemoveCatalog(&quot;' + fullpath + '&quot;)\']]);"';
            // phone design
            if(adaptiveDesignS == 'phone') {
              output += 'onclick="finderSetCatalog(\'' + fullpath + '\')">\n';
            }
            else {
              output += 'ondblclick="finderSetCatalog(\'' + fullpath + '\')" ';
              output += 'onclick="finderElementsSelect(this, \'' + fullpath + '\')">\n';
            }
            output += '<div class="panel-conteiner-all-block-main-2-main-elem-ch"></div>\n';
            output += '<div class="panel-conteiner-all-block-main-2-main-elem-ico-finder"></div>\n';
            output += '<div class="panel-conteiner-all-block-main-2-main-elem-name">' + file.filename + '</div>\n';
            output += '<div class="panel-conteiner-all-block-main-2-main-elem-date">' + file.date + '</div>\n';
            output += '<div class="panel-conteiner-all-block-main-2-main-elem-type">Папка с файлами</div>\n';
            output += '<div class="panel-conteiner-all-block-main-2-main-elem-size">' + finderConvertSize(file.size) + '</div>\n';
            output += '</div>\n';

            Finder.currentFiles[Finder.currentFiles.length] = [fullpath, file.filetype];
          }
          else {
            var ext = file.filename.substring(file.filename.lastIndexOf('.') + 1);
            // set icon
            var image = finderGetIconByExtension(ext, file.filetype);
            // load picture
            if(($.cookie('style_finder') == 'block') && ($.cookie('finderImagePreload') == 'true') && ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
              image = '&quot..' + fullpath_file + '&quot';
            }
            output += '<div class="panel-conteiner-all-block-main-2-main-elem" oncontextmenu="finderElementsClear(true); add_contextmenu([';
            // open picture function
            if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
              output += '[\'open_image\',\'finderOpenPhoto(&quot;' + fullpath_file + '&quot;)\'],[\'line\'],';
            }
            output += '[\'copy\',\'finderCopyOne(&quot;' + fullpath_file + '&quot;)\'],[\'cut_out\',\'finderCutOne(&quot;' + fullpath_file + '&quot;)\'],[\'rename\',\'finderRenameWindow(&quot;' + path + '&quot;, &quot;' + file.filename + '&quot;, &quot;file&quot;)\'],[\'download\',\'finderDownloadFile(&quot;' + fullpath_file + '&quot;)\'],[\'line\'],[\'del\',\'finderRemoveFilePath(&quot;' + fullpath_file + '&quot;)\']]);"';
            // phone design
            if(adaptiveDesignS == 'phone') {
              // finderOpenPhoto
              if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
                output += ' onclick="finderOpenPhoto(\'' + fullpath_file + '\')"';
              }
            }
            else {
              // finderOpenPhoto
              if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
                output += ' ondblclick="finderOpenPhoto(\'' + fullpath_file + '\')"';
              }
              output += ' onclick="finderElementsSelect(this, \'' + fullpath_file + '\')"';
            }
            output += '>\n';
            output += '<div class="panel-conteiner-all-block-main-2-main-elem-ch"></div>\n';
            output += '<div class="panel-conteiner-all-block-main-2-main-elem-ico" style="background-image: url(' + image + '); filter: grayscale(0)"></div>\n';
            output += '<div class="panel-conteiner-all-block-main-2-main-elem-name">' + file.filename + '</div>\n';
            output += '<div class="panel-conteiner-all-block-main-2-main-elem-date">' + file.date + '</div>\n';
            output += '<div class="panel-conteiner-all-block-main-2-main-elem-type">Файл "' + ext + '"</div>\n';
            output += '<div class="panel-conteiner-all-block-main-2-main-elem-size">' + finderConvertSize(file.size) + '</div>\n';
            output += '</div>\n';

            Finder.currentFiles[Finder.currentFiles.length] = [fullpath_file, file.filetype];
          }
          $('#folderSort').append(output);
        }
        // change style
        if($.cookie('style_finder') == 'block') {
          change_style_finder('block');
        }
        else {
          change_style_finder('line');
        }
        // sort elements
        setTimeout(function() {
          sort_name(document.getElementById('sorting-by-name-id'));
        }, 0);
        setTimeout(function() {
          sort_name(document.getElementById('sorting-by-name-id'));
        }, 1);
        // clear selection
        finderElementsClear();
        // end
      }
      else if(checkResponseCode('WRONG.')) {
        finderListingErrorCounter++;
        if(finderListingErrorCounter < 3) {
          finderSetCatalog('/');
        }
        else {
          notification_add('error', 'Ошибка', 'Неизвестная ошибка', 5);
          console.log('error: ' + response);
        }
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

function finderCreateNewCatalog() {
  if(Finder.listingTo == 'trash' || Finder.listingTo == 'search') {
    closeContextMenu();
    return;
  }
  // close
  if(!Finder.contextMenuReady) {
    return;
  }
  Finder.contextMenuReady = false;
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
    url: 'db_finder.php',
    data: {
      create_catalog: folderPath
    },
    beforeSend: function() {
      loader('show');
      setTimeout(function() { Finder.contextMenuReady = true; }, 1000);
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
        notification_add('line', '', 'Новая папка создана', 5);
        closeContextMenu();
        finderListing();
      }
      else if(checkResponseCode('ERROR.')) {
        notification_add('error', 'Ошибка', 'Не удалось создать папку', 5);
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

function finderRenameRequest(in_path, from_name, to_name) {
  // request
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      finder_rename: true,
      rename_dir: in_path,
      rename_from: from_name,
      rename_to: to_name
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
        notification_add('line', '', 'Название изменено', 5);
        closeContextMenu();
        finderListing();
      }
      else if(checkResponseCode('ERROR.')) {
        notification_add('error', 'Ошибка', 'Не удалось изменить название', 5);
        closeContextMenu();
      }
      else if(checkResponseCode('EXISTS.')) {
        notification_add('error', 'Ошибка', 'Указанное имя занято', 5);
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

function finderRenameWindow(in_path, from_name, mode) {
  // get
  Finder.rename.in = in_path;
  Finder.rename.from = from_name;
  if(typeof(mode) == 'undefined') {
    mode = 'file';
  }
  Finder.rename.mode = mode;
  // change fields
  var word = 'файла';
  if(mode == 'directory') {
    word = 'папки';
  }
  else {
    from_name = from_name.substring(0, from_name.lastIndexOf('.'));
  }
  $('#C6C65-WoTJ-45EF').val('');
  $('#finder-rename-title').html('Смена имени ' + word);
  $('#finder-rename-text').html('Изменяйте имя ' + word + ', только если вы уверены, что это не вызовет сбоя в работе сайта.');
  $('#finder-rename-oldname').html('\n    Текущее имя ' + word + ': <b>' + from_name + '</b>');
  $('#finder-rename-label').html('Новое имя ' + word);
  // window
  open_window('#finder-rename');
}

function finderRenameAccept(mode) {
  // get
  var in_path = Finder.rename.in;
  var from_name = Finder.rename.from;
  var to_name = $('#C6C65-WoTJ-45EF').val();
  Finder.rename.to = to_name;
  if(typeof(mode) == 'undefined') {
    mode = Finder.rename.mode;
  }
  // check
  if((typeof(in_path) == 'undefined') || (typeof(from_name) == 'undefined') || (typeof(to_name) == 'undefined')) {
    return;
  }
  if(in_path == '' || from_name == '' || to_name == '') {
    notification_add('error', 'Ошибка', 'Название не может быть пустым', 5);
    return;
  }
  // send request
  if(mode == 'directory') {
    //console.log(in_path + ', ' + from_name + '/, ' + to_name + '/');
    finderRenameRequest(in_path, from_name + '/', to_name + '/');
  }
  else {
    var extension = from_name.substring(from_name.lastIndexOf('.') + 1, from_name.length);
    if(extension.length > 0 && extension.length != from_name.length) {
      to_name += '.' + extension;
    }
    //console.log(in_path + ', ' + from_name + ', ' + to_name);
    finderRenameRequest(in_path, from_name, to_name);
  }
  // success
  close_window();
}

function finderRemoveFilePath(path, silent) {

  // is dir ?
  if(String(path.substring(0, path.length - 1) + '/') == path) {
    closeContextMenu();
    return false;
  }
  var pos = path.lastIndexOf('/');
  // get filename
  var filename = path.substring(pos + 1, path.length);
  // get path
  var where = path.substring(0, pos + 1);
  // send
  finderRemoveFile(where, filename, silent);
}

function finderRemoveFile(where, filename, silent) {
  // request
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      remove_file: true,
      remove_file_where: where,
      remove_file_name: filename
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
        if(typeof(silent) == 'undefined') {
          notification_add('line', '', 'Файл перемещен в корзину', 5);
          closeContextMenu();
          finderListing();
        }
      }
      else if(checkResponseCode('ERROR.')) {
        notification_add('error', 'Ошибка', 'Не удалось удалить файл', 5);
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

function finderRemoveCatalog(path, silent) {
  // request
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      remove_catalog: path
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
        if(typeof(silent) == 'undefined') {
          notification_add('line', '', 'Папка удалена', 5);
          closeContextMenu();
          finderListing();
        }
      }
      else if(checkResponseCode('ERROR.')) {
        notification_add('error', 'Ошибка', 'Не удалось удалить директорию', 5);
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

function finderRemoveSelected() {
  if(Finder.listingTo == 'trash') {
    finderTrashRemoveSelected();
    return;
  }
  if(Finder.listingTo == 'search') {
    closeContextMenu();
    //return;
  }
  // phone design
  if(adaptiveDesignS == 'phone') return;
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
    finderRemoveCatalog(path, true);
  }
  else {
    // is file
    finderRemoveFilePath(path, true);
  }
  // remove element
  Finder.selectedFiles.splice(listSize - 1, 1);
  // check
  var listSize = Finder.selectedFiles.length;
  if(listSize == 0) {
    // stop
    notification_add('line', '', 'Выделенные элементы удалены', 5);
    closeContextMenu();
    finderListing();
    return;
  }
  else {
    // recursive
    setTimeout(finderRemoveSelected, 15);
  }
}

function finderCopycutSelected(mode, first) {
  if(Finder.listingTo == 'trash') {
    closeContextMenu();
    return;
  }
  if(Finder.listingTo == 'search') {
    closeContextMenu();
    //return;
  }
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
  finderCopycut(path, mode, firstmode);

  // remove element
  Finder.selectedFiles.splice(listSize - 1, 1);
  // check
  var listSize = Finder.selectedFiles.length;
  if(listSize == 0) {
    // stop
    if(mode == 'copy') {
      notification_add('line', '', 'Выделенные элементы скопированы', 5);
    }
    else {
      notification_add('line', '', 'Выделенные элементы вырезаны', 5);
    }
    closeContextMenu();
    return;
  }
  else {
    // recursive
    setTimeout(finderCopycutSelected, 15, mode);
  }
}

function finderCopycut(path, mode, reset) {
  if(Finder.listingTo == 'trash') {
    closeContextMenu();
    return;
  }
  if(Finder.listingTo == 'search') {
    closeContextMenu();
    //return;
  }
  // request
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      finder_copycut: path,
      copycut_mode: mode,
      copycut_reset: String(reset)
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
        if(reset === true) {
          if(mode == 'cut') {
            notification_add('line', '', 'Элемент вырезан', 5);
          }
          else {
            notification_add('line', '', 'Элемент скопирован', 5);
          }
        }
      }
      else if(checkResponseCode('ERROR.')) {
        notification_add('error', 'Ошибка', 'Не удалось скопировать этот файл или папку', 5);
        console.log('error: ' + response);
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

function finderCutOne(path) {
  finderCopycut(path, 'cut', true);
  closeContextMenu();
}

function finderCopyOne(path) {
  finderCopycut(path, 'copy', true);
  closeContextMenu();
}

function finderPasteTo() {
  if(Finder.listingTo == 'trash' || Finder.listingTo == 'search') {
    closeContextMenu();
    return;
  }
  // multiply files
  var multiply = false;
  // current catalog
  var directory = Finder.currentPath;
  // close
  closeContextMenu();
  // request
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      finder_paste_to: directory
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
        if(!multiply) {
          notification_add('line', '', 'Элемент перемещен', 5);
        }
        // update
        finderListing();
      }
      else if(checkResponseCode('ERROR.')) {
        if(!multiply) {
          notification_add('error', 'Ошибка', 'Не удалость переместить элемент', 5);
        }
      }
      else if(checkResponseCode('EMPTY.')) {
        notification_add('line', '', 'Буфер пуст', 3);
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

function finderElementsClear(update) {
  // phone design
  if(adaptiveDesignS == 'phone') return;
  // clear array
  Finder.selectedFiles.splice(0, Finder.selectedFiles.length);
  // update
  if(typeof(update) != 'undefined') {
    for(var i = 0; i < Finder.currentFiles.length; i++) {
      var element = $('#folderSort').children().eq(i).children().eq(0);
      element.removeAttr('class');
      element.attr('class', 'panel-conteiner-all-block-main-2-main-elem-ch');
      element.parent().css('background-color','')
      if($.cookie('style_finder') == 'block') {
        element.css({'opacity':'0'})
      }
      else {
        element.css({'opacity':'initial'})
      }
    }
  }
}

function finderElementsSelectAll() {
  // phone design
  if(adaptiveDesignS == 'phone') return;
  //
  if(Finder.selectedAll) {
    // unselect
    finderElementsClear(true);
    Finder.selectedAll = false;
  }
  else {
    // select
    for(var i = 0; i < Finder.currentFiles.length; i++) {
      // set array
      finderElementsSelect(undefined, Finder.currentFiles[i][0], true);
      // update
      var element = $('#folderSort').children().eq(i).children().eq(0);
      element.removeAttr('class');
      element.attr('class', 'panel-conteiner-all-block-main-2-main-elem-ch-a');
      element.css({'opacity':'1'})
      element.parent().css('background-color','#5d78ff1a')
    }
    Finder.selectedAll = true;
  }
}

function finderElementsSelect(element, path, not_switch) {
  // phone design
  if(adaptiveDesignS == 'phone') return;
  // not checked
  var findElement;
  if(typeof(element) != 'undefined') {
    findElement = $(element).find('.panel-conteiner-all-block-main-2-main-elem-ch');
    if(findElement.length > 0) {
      findElement.removeAttr('class');
      findElement.attr('class', 'panel-conteiner-all-block-main-2-main-elem-ch-a');
      findElement.css({'opacity':'1'})
      findElement.parent().css('background-color','#5d78ff1a')
    }
    else {
      // checked
      findElement = $(element).find('.panel-conteiner-all-block-main-2-main-elem-ch-a');
      findElement.removeAttr('class');
      findElement.attr('class', 'panel-conteiner-all-block-main-2-main-elem-ch');
      findElement.parent().css('background-color','')
      if($.cookie('style_finder') == 'block') {
        findElement.css({'opacity':'0'})
      }
      else {
        findElement.css({'opacity':'initial'})
      }
    }
  }
  // find element in array
  var ind = Finder.selectedFiles.indexOf(path);
  if(ind < 0) {
    // not found, add
    Finder.selectedFiles.push(path);
    if(Finder.listingTo == 'trash') {
      if(typeof(findElement) != 'undefined') {
        var id = findElement.parent().attr('id');
        Finder.selectedFilesTrash[path] = id;
      }
    }
  }
  else {
    // founded, remove
    if(typeof(not_switch) == 'undefined') {
      Finder.selectedFiles.splice(ind, 1);
    }
  }
}

function finderOpenParentOf(filepath) {
  var parent = filepath.substring(0, filepath.lastIndexOf('/'));
  finderSetCatalog(parent);
  closeContextMenu();
}

// === finder file uploading ===================================================

$(document).ready(function() {
  // file uploading
  $('#finder-upload-file-input').on('change', function() {
    // get file
    var files = this.files;
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
  });
  // multiply files uploading
  $('#finder-upload-folder-input').on('change', function() {
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
      beforeSend: function() {
        loader('show');
      },
      complete: function() {
        loader('hidden');
        closeContextMenu();
        displayProgressBar(false);
        // clear input
        $('#finder-upload-folder-input').val('');
      },
      success: function(response) {
        function checkResponseCode(code, rsp) {
          if(typeof(rsp) == 'undefined') rsp = response;
          return (response.substring(0, code.length) == code);
        }
        if(checkResponseCode('OK.')) {
          notification_add('line', '', 'Файлы загружены на сервер', 5);
          finderListing();
        }
        else if(checkResponseCode('AUTH.')) {
          document.location.reload(true);
        }
        else if(checkResponseCode('RESET.')) {
          notification_add('error', 'Ошибка загрузки файлов', 'Страница будет перезагружена', 5);
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
  });
});

// === finder file downloading =================================================

function finderDownloadFile(path) {
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      download_file: path
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
        // open new window
        var hash = response.substring(3, response.length);
        window.open('db_finder.php?dfut=' + hash, '_blank');
        // end
        notification_add('line', '', 'Файл доступен для загрузки', 5);
        closeContextMenu();
      }
      else if(checkResponseCode('ACCESS.')) {
        notification_add('warn', 'Нет доступа', 'У вас нет доступа к этому файлу', 5);
        console.log('error: ' + response);
      }
      else if(checkResponseCode('EMPTY.')) {
        notification_add('error', 'Ошибка', 'Файл не найден', 5);
        console.log('error: ' + response);
      }
      else if(checkResponseCode('ERROR.')) {
        notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка', 5);
        console.log('error: ' + response);
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

function finderDownloadCatalog(path) {
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      download_catalog: path
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
        // open new window
        var hash = response.substring(3, response.length);
        window.open('db_finder.php?dfut=' + hash, '_blank');
        // end
        notification_add('line', '', 'Архив доступен для загрузки', 5);
        closeContextMenu();
      }
      else if(checkResponseCode('ACCESS.')) {
        notification_add('warn', 'Нет доступа', 'У вас нет доступа к этому каталогу', 5);
        console.log('error: ' + response);
      }
      else if(checkResponseCode('EMPTY.')) {
        notification_add('error', 'Ошибка', 'Указанный каталог не найден', 5);
        console.log('error: ' + response);
      }
      else if(checkResponseCode('ERROR.')) {
        notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка', 5);
        console.log('error: ' + response);
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

function finderCreateZip(path) {
  if(Finder.listingTo == 'trash' || Finder.listingTo == 'search') {
    closeContextMenu();
    return;
  }
  // request
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      create_zip: path
    },
    beforeSend: function() {
      loader('show');
    },
    complete: function() {
      loader('hidden');
      closeContextMenu();
    },
    success: function(response) {
      function checkResponseCode(code, rsp) {
        if(typeof(rsp) == 'undefined') rsp = response;
        return (response.substring(0, code.length) == code);
      }
      if(checkResponseCode('OK.')) {
        notification_add('line', '', 'Архив создан', 5);
        finderListing();
      }
      else if(checkResponseCode('EMPTY.')) {
        notification_add('error', 'Ошибка', 'Указанный путь не найден', 5);
        finderListing();
      }
      else if(checkResponseCode('ERROR.')) {
        notification_add('error', 'Ошибка', 'Не удалось создать архив', 5);
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

//  === finder scanner routine =================================================

$(document).ready(function() {
  finderRunRoutineScanner();
});

function finderRunRoutineScanner() {
  $.ajax({
    type: 'POST',
    url: 'db_r_scanner.php',
    data: {
      the_request_argv: true
    },
    success: function(response) {
      if(response != 'OK.') {
        console.log('routine_files_scanner: ' + response);
      }
    },
    error: function(jqXHR, status) {
      console.log('error: ' + status + ', ' + jqXHR);
    }
  });
}

// === finder searching ========================================================

function finderSearchListing(data, phrase) {
  // set listing mode
  Finder.listingTo = 'search';
  // change path
  finder_way_auto([['_SERVER', '/']], true);
  // change icon
  $('#finder-title-icon').css('background-image', 'url(media/svg/search.svg)');
  // change title
  $('#finder-title-id').html('Поиск: <span style="font-family: pfl;">' + phrase + '</span>');
  // buttons
  $('#finder-history-btn-left').attr('class', 'file_manager-action-btn');
  $('#finder-history-btn-right').attr('class', 'file_manager-action-btn-none');
  // files count
  var count = data.length;
  var word = declOfNumber(count, 'элемент');
  $('#finder-title-count-id').text(String(count) + ' ' + word);
  // clear finder
  $('#folderSort').empty();
  Finder.currentFiles = [];
  // listing
  for(var i = 0; i < data.length; i++) {
    var file = data[i];
    var path = file.path;
    var path = (file.path.substring(file.path.length - 1) == '/') ? file.path.substring(0, file.path.length - 1) : file.path;
    var fdate = file.date;
    var type = file.type;
    var size = file.size;
    var filename = path.substring(path.lastIndexOf('/') + 1, path.length);
    var output = '';
    Finder.currentFiles[Finder.currentFiles.length] = [path, type];
    if($.cookie('style_finder') == 'block') {
      // block
      if(type == 'directory') {
        output += '<div class="panel-conteiner-all-block-main-2-main-elem" oncontextmenu="finderElementsClear(true); ';
        output += 'add_contextmenu([[\'open_folder\', \'finderSetCatalog(&quot;' + path + '&quot;)\'], [\'line\'], [\'copy\', \'finderCopyOne(&quot;' + path + '&quot;)\'], [\'cut_out\', \'finderCutOne(&quot;' + path + '&quot;)\'], [\'rename\', \'finderRenameWindow(&quot;' + path + '&quot;, &quot;' + filename + '&quot;, &quot;directory&quot;)\'], [\'download\', \'finderDownloadCatalog(&quot;' + path + '&quot;)\'], [\'line\'], [\'del\', \'finderRemoveCatalog(&quot;' + path + '&quot;)\']]);"';
        // phone design
        if(adaptiveDesignS == 'phone') {
          output += 'onclick="finderSetCatalog(\'' + path + '\')">\n';
        }
        else {
          output += 'ondblclick="finderSetCatalog(\'' + path + '\')" ';
          output += 'onclick="finderElementsSelect(this, \'' + path + '\')"';
        }
        output += 'style="cursor: pointer; display: inline-block; height: calc(100px * var(--fontsSizeFinder)); width: calc(100px * var(--fontsSizeFinder)); border: 0px solid var(--border-color); border-radius: 7.5px; vertical-align: middle; margin-bottom: 10px; margin-right: 10px;">\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-ch" style="display: block; position: absolute; opacity: 0; z-index: 1; left: 10px; margin-left: 0px;"></div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-ico-finder" style="margin: auto; left: 0px; right: 0px; display: block; height: calc(60px * var(--fontsSizeFinder)); width: calc(60px * var(--fontsSizeFinder)); background-size: calc(60px * var(--fontsSizeFinder));"></div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-name" style="display: block; width: 100%; text-align: center; font-size: calc(14px * var(--fontsSizeFinder)); margin-left: -10px; font-family: pfl; margin-top: 8px; bottom: 10px; position: absolute; max-height: 41px; overflow: auto;">' + filename + '</div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-date" style="display: none;"></div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-type" style="display: none;">Папка с файлами</div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-size" style="display: none;">' + finderConvertSize(size) + '</div>\n';
        output += '</div>\n';
      }
      else {
        var ext = filename.substring(filename.lastIndexOf('.') + 1);
        var image = finderGetIconByExtension(ext, type);
        if(($.cookie('style_finder') == 'block') && ($.cookie('finderImagePreload') == 'true') && ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
          image = '&quot..' + path + '&quot';
        }
        output += '<div class="panel-conteiner-all-block-main-2-main-elem" oncontextmenu="finderElementsClear(true); add_contextmenu([';
        // open image function
        if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
          output += '[\'open_image\',\'finderOpenPhoto(&quot;' + path + '&quot;)\'],[\'line\'],';
        }
        output += '[\'open_parent\',\'finderOpenParentOf(&quot;' + path + '&quot;)\'],[\'line\'],[\'copy\',\'finderCopyOne(&quot;' + path + '&quot;)\'],[\'cut_out\',\'finderCutOne(&quot;' + path + '&quot;)\'],[\'rename\',\'finderRenameWindow(&quot;' + path + '&quot;, &quot;' + filename + '&quot;, &quot;file&quot;)\'],[\'download\',\'finderDownloadFile(&quot;' + path + '&quot;)\'],[\'line\'],[\'del\',\'finderRemoveFilePath(&quot;' + path + '&quot;)\']]);"';
        // phone design

        if(adaptiveDesignS == 'phone') {
          // finderOpenPhoto
          if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
            output += ' onclick="finderOpenPhoto(\'' + path + '\')"';
          }
        }
        else {
          // finderOpenPhoto
          if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
            output += ' ondblclick="finderOpenPhoto(\'' + path + '\')"';
          }
          output += ' onclick="finderElementsSelect(this, \'' + path + '\')"';
        }
        output += ' style="cursor: pointer; display: inline-block; height: calc(100px * var(--fontsSizeFinder)); width: calc(100px * var(--fontsSizeFinder)); border: 0px solid var(--border-color); border-radius: 7.5px; vertical-align: middle; margin-bottom: 10px; margin-right: 10px;"';
        output += '>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-ch" style="display: block; position: absolute; opacity: 0; z-index: 1; left: 10px; margin-left: 0px;"></div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-ico" style="background-image: url(' + image + '); filter: grayscale(0); display: block; margin: auto; left: 0px; right: 0px; height: calc(60px * var(--fontsSizeFinder)); width: calc(60px * var(--fontsSizeFinder)); background-size: calc(60px * var(--fontsSizeFinder));""></div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-name" style="display: block; width: 100%; text-align: center; font-size: calc(14px * var(--fontsSizeFinder)); margin-left: -10px; font-family: pfl; margin-top: 8px; bottom: 10px; position: absolute; max-height: 41px; overflow: auto;">' + filename + '</div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-date" style="display: none;">' + fdate + '</div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-type" style="display: none;">Файл "' + ext + '"</div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-size" style="display: none;">' + finderConvertSize(size) + '</div>\n';
        output += '</div>\n';
      }
    } else{
      // line
      if(type == 'directory') {
        output += '<div class="panel-conteiner-all-block-main-2-main-elem" oncontextmenu="finderElementsClear(true); ';
        output += 'add_contextmenu([[\'open_folder\', \'finderSetCatalog(&quot;' + path + '&quot;)\'], [\'line\'], [\'copy\', \'finderCopyOne(&quot;' + path + '&quot;)\'], [\'cut_out\', \'finderCutOne(&quot;' + path + '&quot;)\'], [\'rename\', \'finderRenameWindow(&quot;' + path + '&quot;, &quot;' + filename + '&quot;, &quot;directory&quot;)\'], [\'download\', \'finderDownloadCatalog(&quot;' + path + '&quot;)\'], [\'line\'], [\'del\', \'finderRemoveCatalog(&quot;' + path + '&quot;)\']]);"';
        // phone design
        if(adaptiveDesignS == 'phone') {
          output += 'onclick="finderSetCatalog(\'' + path + '\')">\n';
        }
        else {
          output += 'ondblclick="finderSetCatalog(\'' + path + '\')" ';
          output += 'onclick="finderElementsSelect(this, \'' + path + '\')">\n';
        }
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-ch"></div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-ico-finder"></div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-name">' + filename + '</div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-date"></div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-type">Папка с файлами</div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-size">' + finderConvertSize(size) + '</div>\n';
        output += '</div>\n';
      }
      else {
        var ext = filename.substring(filename.lastIndexOf('.') + 1);
        var image = finderGetIconByExtension(ext, type);
        output += '<div class="panel-conteiner-all-block-main-2-main-elem" oncontextmenu="finderElementsClear(true); add_contextmenu([';
        // open image function
        if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
          output += '[\'open_image\',\'finderOpenPhoto(&quot;' + path + '&quot;)\'],[\'line\'],';
        }
        output += '[\'open_parent\',\'finderOpenParentOf(&quot;' + path + '&quot;)\'],[\'line\'],[\'copy\',\'finderCopyOne(&quot;' + path + '&quot;)\'],[\'cut_out\',\'finderCutOne(&quot;' + path + '&quot;)\'],[\'rename\',\'finderRenameWindow(&quot;' + path + '&quot;, &quot;' + filename + '&quot;, &quot;file&quot;)\'],[\'download\',\'finderDownloadFile(&quot;' + path + '&quot;)\'],[\'line\'],[\'del\',\'finderRemoveFilePath(&quot;' + path + '&quot;)\']]);"';
        // phone design

        if(adaptiveDesignS == 'phone') {
          // finderOpenPhoto
          if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
            output += ' onclick="finderOpenPhoto(\'' + path + '\')"';
          }
        }
        else {
          // finderOpenPhoto
          if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
            output += ' ondblclick="finderOpenPhoto(\'' + path + '\')"';
          }
          output += ' onclick="finderElementsSelect(this, \'' + path + '\')"';
        }
        output += '>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-ch"></div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-ico" style="background-image: url(' + image + '); filter: grayscale(0)"></div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-name">' + filename + '</div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-date">' + fdate + '</div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-type">Файл "' + ext + '"</div>\n';
        output += '<div class="panel-conteiner-all-block-main-2-main-elem-size">' + finderConvertSize(size) + '</div>\n';
        output += '</div>\n';
      }
    }
    $('#folderSort').append(output);
  }
  // change style

  // if($.cookie('style_finder') == 'block') {
  //   change_style_finder('block');
  // }
  // else {
  //   change_style_finder('line');
  // }

  // clear selection
  finderElementsClear();
  // end
}

function finderSearchField() {
  // time limit
  if((Date.now() - Finder.searchTimer) < 100) {
    // locked
    if(!Finder.searchLock) {
      Finder.searchLock = true;
      setTimeout(finderSearchField, 110);
    }
    return;
  }
  // check field
  var input = $('#file_manager-btn-action-way-search').val();
  if(input.length == 0) {
    finderListing();
    return;
  }
  if(!input.match(/^([^*|:"<>?\/\\\\])+$/gui)) {
    return;
  }
  // send request
  finderSearchRequest(input);
  // set timer
  Finder.searchTimer = Date.now();
  // unlock
  Finder.searchLock = false;
}

$(document).ready(function() {
  $('#file_manager-btn-action-way-search').on('input', finderSearchField);
});

function finderSearchRequest(needle) {
  // request
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      finder_search: needle
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
        var responseText = response.substring(3, response.length);
        var responseData = JSON.parse(responseText);
        finderSearchListing(responseData, needle);
      }
      else if(checkResponseCode('EMPTY.')) {
        finderSearchListing([], needle);
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

function finderListingTrash() {
  // set mode
  Finder.listingTo = 'trash';
  // request
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      finder_listing_trash: true
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
        var responseText = response.substring(3, response.length);
        var data = JSON.parse(responseText);
        // =====================================================================
        // change path
        finder_way_auto([['_SERVER', '/']], true);
        // change icon
        $('#finder-title-icon').css('background-image', 'url(media/svg/basket.svg)');
        // change title
        $('#finder-title-id').html('Корзина');
        // buttons
        $('#finder-history-btn-left').attr('class', 'file_manager-action-btn');
        $('#finder-history-btn-right').attr('class', 'file_manager-action-btn-none');
        // files count
        var count = data.length;
        // clear finder
        $('#folderSort').empty();
        Finder.currentFiles = [];
        // listing
        var realCounter = 0;
        for(var i = 0; i < data.length; i++) {
          var file = data[i];
          var hash = file.hash;
          var path = file.path;
          var type = file.type;
          var size = file.size;
          var date = file.date;
          var path_t = '/TRASH_CAN/' + hash;
          var filename = path.substring(path.substring(0, path.length - 1).lastIndexOf('/') + 1, path.length);
          var pointpos = filename.lastIndexOf('.');
          var ext = filename.substring(pointpos + 1);
          var name = filename.substring(0, pointpos);
          if(filename == 'desktop.ini') {
            continue;
          }
          var image = finderGetIconByExtension(ext, type);
          if(($.cookie('style_finder') == 'block') && ($.cookie('finderImagePreload') == 'true') && ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
            image = '&quot../TRASH_CAN/' + hash + '&quot';
          }
          var tmp_id = stringGenerator(15, 5);
          var output = '';
          if($.cookie('style_finder') == 'block') {
            // block
            if(type != 'directory') {
              output += '<div id="' + tmp_id + '" class="panel-conteiner-all-block-main-2-main-elem" oncontextmenu="finderElementsClear(true); add_contextmenu([';
              // open image function
              if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
                output += '[\'open_image\',\'finderOpenPhoto(&quot;/TRASH_CAN/' + hash + '&quot;, &quot;' + filename + '&quot;)\'],[\'line\'],';
              }
              output += '[\'recovery\',\'finderTrashRecovery(&quot;' + hash + '&quot;, &quot;' + path + '&quot;, &quot;' + tmp_id + '&quot;)\'],[\'download\',\'finderDownloadFile(&quot;' + path_t + '&quot;)\'],[\'line\'],[\'del\',\'finderTrashRemove(&quot;' + hash + '&quot;, &quot;' + tmp_id + '&quot;)\']]);"';
              // phone design
              if(adaptiveDesignS == 'phone') {
                // finderOpenPhoto
                if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
                  output += ' onclick="finderOpenPhoto(\'/TRASH_CAN/' + hash + '\', \'' + filename + '\')"';
                }
              }
              else {
                // finderOpenPhoto
                if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
                  output += ' ondblclick="finderOpenPhoto(\'/TRASH_CAN/' + hash + '\', \'' + filename + '\')"';
                }
                output += ' onclick="finderElementsSelect(this, \'' + hash + '\')"';
              }
              output += ' style="cursor: pointer; display: inline-block; height: calc(100px * var(--fontsSizeFinder)); width: calc(100px * var(--fontsSizeFinder)); border: 0px solid var(--border-color); border-radius: 7.5px; vertical-align: middle; margin-bottom: 10px; margin-right: 10px;"';
              output += '>\n';
              output += '<div class="panel-conteiner-all-block-main-2-main-elem-ch" style="display: block; position: absolute; opacity: 0; z-index: 1; left: 10px; margin-left: 0px;"></div>\n';
              output += '<div class="panel-conteiner-all-block-main-2-main-elem-ico" style="background-image: url(' + image + '); filter: grayscale(0); display: block; margin: auto; left: 0px; right: 0px; height: calc(60px * var(--fontsSizeFinder)); width: calc(60px * var(--fontsSizeFinder)); background-size: calc(60px * var(--fontsSizeFinder));""></div>\n';
              output += '<div class="panel-conteiner-all-block-main-2-main-elem-name" style="display: block; width: 100%; text-align: center; font-size: calc(14px * var(--fontsSizeFinder)); margin-left: -10px; font-family: pfl; margin-top: 8px; bottom: 10px; position: absolute; max-height: 41px; overflow: auto;">' + name + '</div>\n';
              output += '<div class="panel-conteiner-all-block-main-2-main-elem-date" style="display: none;">' + date + '</div>\n';
              output += '<div class="panel-conteiner-all-block-main-2-main-elem-type" style="display: none;">Файл "' + ext + '"</div>\n';
              output += '<div class="panel-conteiner-all-block-main-2-main-elem-size" style="display: none;">' + finderConvertSize(size) + '</div>\n';
              output += '</div>\n';

              Finder.currentFiles[Finder.currentFiles.length] = [hash, type];
            }
          }
          else {
            // line
            if(type != 'directory') {
              output += '<div id="' + tmp_id + '" class="panel-conteiner-all-block-main-2-main-elem" oncontextmenu="finderElementsClear(true); add_contextmenu([';
              // open image function
              if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
                output += '[\'open_image\',\'finderOpenPhoto(&quot;/TRASH_CAN/' + hash + '&quot;, &quot;' + filename + '&quot;)\'],[\'line\'],';
              }
              output += '[\'recovery\',\'finderTrashRecovery(&quot;' + hash + '&quot;, &quot;' + path + '&quot;, &quot;' + tmp_id + '&quot;)\'],[\'line\'],[\'download\',\'finderDownloadFile(&quot;' + path_t + '&quot;)\'],[\'line\'],[\'del\',\'finderTrashRemove(&quot;' + hash + '&quot;, &quot;' + tmp_id + '&quot;)\']]);"';
              // phone design
              if(adaptiveDesignS == 'phone') {
                // finderOpenPhoto
                if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
                  output += ' onclick="finderOpenPhoto(\'/TRASH_CAN/' + hash + '\', \'' + filename + '\')"';
                }
              }
              else {
                // finderOpenPhoto
                if(ext.match(/^(jpg|svg|png|webp|jpeg|bmp)$/ui)) {
                  output += ' ondblclick="finderOpenPhoto(\'/TRASH_CAN/' + hash + '\', \'' + filename + '\')"';
                }
                output += ' onclick="finderElementsSelect(this, \'' + hash + '\')"';
              }
              output += '>\n';
              output += '<div class="panel-conteiner-all-block-main-2-main-elem-ch"></div>\n';
              output += '<div class="panel-conteiner-all-block-main-2-main-elem-ico" style="background-image: url(' + image + '); filter: grayscale(0)"></div>\n';
              output += '<div class="panel-conteiner-all-block-main-2-main-elem-name">' + name + '</div>\n';
              output += '<div class="panel-conteiner-all-block-main-2-main-elem-date">' + date + '</div>\n';
              output += '<div class="panel-conteiner-all-block-main-2-main-elem-type">Файл "' + ext + '"</div>\n';
              output += '<div class="panel-conteiner-all-block-main-2-main-elem-size">' + finderConvertSize(size) + '</div>\n';
              output += '</div>\n';

              Finder.currentFiles[Finder.currentFiles.length] = [hash, type];
            }
          }
          $('#folderSort').append(output);
          realCounter++;
        }
        // set title (files count)
        var word = declOfNumber(realCounter, 'элемент');
        $('#finder-title-count-id').text(String(realCounter) + ' ' + word);
        // clear selection
        finderElementsClear();
        // end
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

function finderTrashRemove(hash, id, multiply) {
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      trash_remove_one: hash
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
        if(typeof(multiply) == 'undefined') {
          notification_add('line', '', 'Файл удален', 5);
          closeContextMenu();
        }
        if(typeof(id) != 'undefined') {
          // remove element
          $('#' + id).remove();
          // update count
          var count = Number($('#finder-title-count-id').text().split(' ')[0]) - 1;
          if(count < 0) count = 0;
          var word = declOfNumber(count, 'элемент');
          $('#finder-title-count-id').text(String(count) + ' ' + word);
        }
      }
      else if(checkResponseCode('ERROR.')) {
        notification_add('error', 'Ошибка', 'Не удалось удалить файл', 5);
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

function finderTrashRecovery(hash, path, id, multiply) {
  $.ajax({
    type: 'POST',
    url: 'db_finder.php',
    data: {
      trash_recovery_one: hash,
      recovery_to: path
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
        // add notification
        if(typeof(multiply) == 'undefined') {
          notification_add('line', '', 'Файл восстановлен в ' + path, 5);
        }
        if(typeof(id) != 'undefined') {
          // remove element
          $('#' + id).remove();
          // update count
          var count = Number($('#finder-title-count-id').text().split(' ')[0]) - 1;
          if(count < 0) count = 0;
          var word = declOfNumber(count, 'элемент');
          $('#finder-title-count-id').text(String(count) + ' ' + word);
        }
        closeContextMenu();
      }
      else if(checkResponseCode('ERROR.') || checkResponseCode('WRONG.')) {
        notification_add('error', 'Ошибка', 'Не удалось восстановить файл', 5);
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

function finderTrashRemoveSelected() {
  if(Finder.listingTo != 'trash') {
    closeContextMenu();
    return;
  }
  // phone design
  if(adaptiveDesignS == 'phone') return;
  // check
  var listSize = Finder.selectedFiles.length;
  if(listSize == 0) {
    // empty
    return;
  }
  // rm
  var hash = Finder.selectedFiles[listSize - 1];
  var id = Finder.selectedFilesTrash[hash];
  finderTrashRemove(hash, id, true);
  // remove element
  Finder.selectedFiles.splice(listSize - 1, 1);
  // check
  var listSize = Finder.selectedFiles.length;
  if(listSize == 0) {
    // stop
    notification_add('line', '', 'Выделенные файлы удалены', 5);
    closeContextMenu();
    if(Finder.selectedAll) {
      Finder.selectedAll = false;
      finderListingTrash();
    }
    return;
  }
  else {
    // recursive
    setTimeout(finderTrashRemoveSelected, 15);
  }
}

function finderOpenPhoto(path, spec_title) {
  var path = (path.substring(path.length - 1, 1) == '/') ? path.substring(0, path.length - 1) : path;
  var title = (typeof(spec_title) == 'undefined') ? path.substring(path.lastIndexOf('/') + 1, path.length) : title = spec_title;
  $('#pictures-display-photo').css('background-image', 'url("..' + path + '")');
  document.getElementById('pictures-display-photo').backgroundImage = 'url("..' + path + '")';
  $('#pictures-display-title').text(title);
  open_window('#pictures-display');
}
