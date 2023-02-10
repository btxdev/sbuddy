// *
// *  Study Buddy
// *  (c) 2020 Study Buddy
// *  All rights reserved.
// *
// *  Developed by INSOweb
// *  <http://insoweb.ru>
// *
// *
// *
// *
// ========================== Function (start) ===============================//



var developed = true, // статус разработки
    closeNotificatTime = 7.5, // время закрытия уведомления (сек)
    GetParams = window.location.search.replace('?','').split('&').reduce(
      function(p,e){
        var a = e.split('=');
        p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
        return p;
      },
      {}
    ),
    currentFileName = currentFileNameF(),
    device = function(){let tmpClienWidth = document.documentElement.clientWidth;if(tmpClienWidth <= 623){device = 'phone';} else if(tmpClienWidth > 640 && tmpClienWidth <= 974){device = 'tablet';} else{device = 'pc';}}; // экран устройства (3 вида (phone|tablet|pc))

// =========================== Function (end) ================================//

function currentFileNameF(){  // имя файла (index или news.php)
  let CurrentFile = {
    name: (decodeURI(document.location.href.split('/')[document.location.href.split('/').length - 1]).replace(/(\..*|\?.*|\#..*)/igm, '')).toLowerCase()
  }
  if(CurrentFile.name == 'article'){
    CurrentFile.name = (decodeURI(document.location.href.split('/')[document.location.href.split('/').length - 1]).replace(/(\..*|\?.*|\#..*)/igm, '')).toLowerCase() + GetParams.id;
  }
  return CurrentFile.name;
}
