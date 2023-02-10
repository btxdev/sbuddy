// === onload ==================================================================
$(document).ready(function() {
  if(typeof(GET_ARGS['page']) != 'undefined') News.page = GET_ARGS['page'];
  if(typeof(GET_ARGS['search']) != 'undefined') News.needle = GET_ARGS['search'];
  if(typeof(GET_ARGS['sort']) != 'undefined') News.sortBy = GET_ARGS['sort'];
  $('#news-filter-search').val(News.needle);
  if(News.sortBy == 'date') {
    document.getElementById('dXsPs-IEz0-SAhP-XHVf').checked = true;
    document.getElementById('dZdeB-aBjL-rNsI-FQRc').checked = false;
  }
  else {
    document.getElementById('dXsPs-IEz0-SAhP-XHVf').checked = false;
    document.getElementById('dZdeB-aBjL-rNsI-FQRc').checked = true;
  }
  newsBest();
  newsListing();
});

// === news listing default parameters =========================================
var News = {
  page: 1,
  pages: 65535,
  needle: '',
  sortBy: 'date'
};

// === basic ===================================================================
function htmlDecode(value) {
  return $('<div/>').html(value).text();
}

// === set title ===============================================================
function updateSiteTitle() {
  var oldUrl = window.location.href;
  GET_ARGS['page'] = News.page;
  GET_ARGS['search'] = News.needle;
  GET_ARGS['sort'] = News.sortBy;
  var newStr = 'news';
  let i = 0;
  for(var key in GET_ARGS) {
    if(i == 0) newStr += '?';
    i++;
    newStr += key + '=' + encodeURIComponent(GET_ARGS[key]) + '&';
  }
  var newUrl = oldUrl.substring(0, oldUrl.lastIndexOf('/') + 1) + newStr;
  window.history.pushState('data', 'Title', newUrl);
}

// === pagination ==============================================================
function setPage(mode, page, update) {
  var mode, page, update;
  if(mode == 'set') {
    News.page = Number(page);
  }
  if(mode == 'prev') {
    page = Number(News.page) - 1;
    News.page = page;
  }
  if(mode == 'next') {
    page = Number(News.page) + 1;
    News.page = page;
  }
  if(typeof(News.page) != 'number') {
    page = 1;
    News.page = 1;
  }
  // check
  if(page < 1) {
    page = (News.pages);
    News.page = page;
  }
  if(page > (News.pages)) {
    page = 1;
    News.page = page;
  }
  // send
  if(typeof(update) != 'undefined') {
    newsListing();
  }
  return page;
}
function prevPage() { setPage('prev', 1, true); }
function nextPage() { setPage('next', 1, true); }
function updatePagination() {
  var cur = News.page;     // current page
  var max = News.pages;    // pages count
  var lim = max;           // buttons count
  if(lim > 5) lim = 5;     // limit for buttons
  var pages = [];
  for(let i = 0; i < lim; i++) {
    var num;
    if(i == 0) { num = 1; }
    else if(i == 4) { num = max; }
    else { num = Math.floor(max / lim) * (i + 1); }
    if(num == 0) { num = 1; }
    var direction = true;
    while(pages.includes(num) || (num == cur)) {
      if((num >= max) && (max > 5)) direction = false;
      if(direction) num++;
      else num--;
    }
    pages[i] = num;
  }
  if(max == 1) {
    // hide
    $('#news-pagination').css('display', 'none');
  }
  else {
    // show
    $('#news-pagination').css('display', 'inline-block');
    // adaptive
    if(max < 7) {
      // clear
      $('#news-pagination').empty();
      // add
      var output = '';
      output += '<a style="width: calc((100% / ' + (max + 1) + ') - 4px);" class="pages-news-elem icons-left" title="Предыдущая" onclick="prevPage();"></a>\n';
      for(let i = 1; i < max; i++) {
        output += '<a style="width: calc((100% / ' + (max + 1) + ') - 4px);" class="pages-news-elem" id="pagination-p' + i + '" onclick="setPage(&quot;set&quot;, $(this).text(), true);">' + pages[i - 1] + '</a>\n';
      }
      output += '<a style="width: calc((100% / ' + (max + 1) + ') - 4px);" class="pages-news-elem icons-right" title="Следующая" onclick="nextPage();"></a>\n';
      $('#news-pagination').append(output);
      //
    }
    // simple
    else {
      for(let i = 1; i < 6; i++) { $('#pagination-p' + i).text(pages[i - 1]); }
    }
  }
  return pages;
}

// === news listing ============================================================
function newsListing(page, needle, sortBy) {
  var page, needle, sortBy;
  // save
  if(typeof(page) == 'undefined') { page = News.page; } else { News.page = page; }
  if(typeof(needle) == 'undefined') { needle = News.needle; } else { News.needle = needle; }
  if(typeof(sortBy) == 'undefined') { sortBy = News.sortBy; } else { News.sortBy = sortBy; }
  // check data
  if((News.page < 1) || (News.page > News.pages)) News.page = 1;
  if((page < 1) || (page > News.pages)) page = 1;
  if((News.sortBy != 'date') && (News.sortBy != 'views')) News.sortBy = 'date';
  if((sortBy != 'date') && (sortBy != 'views')) sortBy = 'date';
  // request
  $.ajax({
    type: 'POST',
    url: 'php/db_news.php',
    data: {
      news_listing: true,
      page: page,
      needle: needle,
      sortby: sortBy
    },
    complete: function(){
      loaderMain('hidden');
    },
    beforeSend: function(){
      loaderMain('show');
    },
    success: function(response) {
      var tmpWidthNewsArticles = tmpWidthNewsArticles();
      function tmpWidthNewsArticles(){
        if(device == 'phone'){
          return 'width: 100%; ';
        } else{
          return 'width: 95.5%; ';
        }
      }
      function checkResponseCode(code, rsp) { if(typeof(rsp) == 'undefined') rsp = response; return (response.substring(0, code.length) == code); }
      function showEmptyBlock() {
        // clear
        $('#news-listing').empty();
        // 'not found' block
        var output = '';
        output += '<div class="news-filter-elem" style="' + tmpWidthNewsArticles + ' height: 80px;">\n';
        output += '<div class="news-filter-elem-photo" style="filter: contrast(1) saturate(1) sepia(0); min-height: 80px; width: 80px; background-color: var(--red)">\n';
        output += '<div class="news-filter-elem-photo-ico icons-error-bolt" style="transform: scale(0.85);">\n';
        output += '<div class="news-filter-elem-photo-ico-helper"></div>\n';
        output += '</div>\n';
        output += '</div>\n';
        output += '<div class="news-filter-elem-text">\n';
        output += '<div class="news-filter-elem-text-empty">По вашему запросу ничего не найдено!</div>\n';
        output += '</div>\n';
        output += '</div>\n';
        $('#news-listing').append(output);
      }
      if(checkResponseCode('OK.')) {
        // parse data
        var responseText = response.substring(3, response.length);
        var responseData = JSON.parse(responseText);
        var recordsCount = Math.ceil(Number(responseData[0][0]) /  Number(responseData[0][1]));
        News.pages = recordsCount;
        // listing
        var output = '';
        for(var i = 1; i < responseData.length; i++) {
          var record = responseData[i];
          var id = record.id;
          var title = record.title.replace(/([^a-zA-Zа-яёА-ЯЁ0-9\&\$\#\№\@.,:;!?\(\)\[\]\"\'\- ])/g, '');
          var dateArr = record.date.substring(0, record.date.length - 9).replace(/\-/g, '.').split('.');
          var date = dateArr[2] + '.' + dateArr[1] + '.' + dateArr[0];
          var image = record.image;
          // code
          output += '<div class="news-filter-elem" style="' + tmpWidthNewsArticles + '">\n';
          //

          if(image == 'none') {

            if(device == 'phone'){
              output += '<div class="news-filter-elem-photo" style="display: block; width: 100%;">\n';
            } else{
              output += '<div class="news-filter-elem-photo">\n';
            }

            output += '<div class="news-filter-elem-photo-ico">📖</div>\n';
            output += '</div>\n';
          }
          else {
            if(device == 'phone'){
              output += '<div class="news-filter-elem-photo" style="display: block; width: 100%; background-image: url(&quot;' + image + '&quot;);"></div>\n';
            } else{
              output += '<div class="news-filter-elem-photo" style="background-image: url(&quot;' + image + '&quot;);"></div>\n';
            }
          }
          //
          if(device == 'phone'){
            output += '<div class="news-filter-elem-text" style="width: calc(100% - 20px);">\n';
            output += '<div class="news-filter-elem-text-title" style="max-height: 200px;">' + title + '</div>\n';
          } else{
            output += '<div class="news-filter-elem-text">\n';
            output += '<div class="news-filter-elem-text-title">' + title + '</div>\n';
          }


          output += '<div class="news-filter-elem-text-date">\n';
          output += '<div class="news-filter-elem-text-date-ico icons-date"></div>\n';
          output += '<div class="news-filter-elem-text-date-line"></div>\n';
          output += '<div class="news-filter-elem-text-date-text">' + date + '</div>\n';
          output += '</div>\n';
          if(device == 'phone'){
            output += '<div class="news-filter-elem-text-btn" style="position: relative; bottom: initial; text-align: left;">\n';
          } else{
            output += '<div class="news-filter-elem-text-btn">\n';
          }

          output += '<a href="article?id=' + id + '" class="news-filter-elem-text-btn-block">Читать</a>\n'; //  onclick="newsRead(' + id + ')"
          output += '</div>\n';
          output += '</div>\n';
          output += '</div>\n';
        }

        // pagination
        updateSiteTitle();
        updatePagination();
        // empty list
        if(responseData.length == 1) {
          showEmptyBlock();
        }
        // OK
        else {
          // clear
          $('#news-listing').empty();
          // add blocks
          $('#news-listing').append(output);
        }
      }
      else if(checkResponseCode('EMPTY.')) {
        showEmptyBlock();
      }
      else if(checkResponseCode('ERROR.')) {
        notification_add('error', 'Ошибка', 'Неизвестная ошибка');
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
function newsBest() {
  $.ajax({
    type: 'POST',
    url: 'php/db_news.php',
    data: {
      news_best: true
    },
    complete: function(){
      loaderMain('hidden');
    },
    beforeSend: function(){
      loaderMain('show');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) { if(typeof(rsp) == 'undefined') rsp = response; return (response.substring(0, code.length) == code); }
      if(checkResponseCode('OK.')) {
        // parse data
        var responseText = response.substring(3, response.length);
        var tmp = JSON.parse(responseText);
        var responseData = [tmp[1], tmp[0], tmp[2]];
        // listing
        var output = '';
        for(var i = 0; i < 3; i++) {
          var record = responseData[i];
          var id = record.id;
          var title = record.title.replace(/([^a-zA-Zа-яёА-ЯЁ0-9\&\$\#\№\@.,:;!?\(\)\[\]\"\'\- ])/g, '');
          var text = htmlDecode(record.data);
          var firstName = record.first;
          var secondName = record.second;
          var image = record.image;
          var color;
          var position;
          switch(i) {
            case 0:
              color = '#c7c7c7';
              position = '2';
              break;
            case 1:
              color = '#ffeb00';
              position = '1';
              break;
            case 2:
              color = '#a05b15';
              position = '3';
              break;
          }
          widthTmpNewsTop = '90%';
          if(device == 'phone'){
            widthTmpNewsTop = '100%';
          } else{
            widthTmpNewsTop = '90%';
          }
          // code
          output += '<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" style="text-align: center;">\n';
          output += '<a href="article?id=' + id + '" class="news-elem" style="width:' + widthTmpNewsTop + '">\n';
          //
          if(image == 'none') {
            output += '<div class="news-elem-photo">📖\n';
          }
          else {
            output += '<div class="news-elem-photo" style="background-image: url(&quot;' + image + '&quot;);">\n';
          }
          output += '<div title="'+position+' место" data-place="'+position+'" class="news-elem-photo-place icons-place-bolt" style="color: '+color+';"></div>\n';
          output += '</div>\n';
          output += '<div class="news-elem-text"';
          // blue background and white text
          var txtcolor = '';
          if(i == 1) {
            output += ' style="background-color: var(--colorLogo);"';
            txtcolor = ' style="color: #ffffff;"';
          }
          //
          output += '>\n';
          output += '<div class="news-elem-text-title"' + txtcolor + '>' + title + '</div>\n';
          output += '<div class="news-elem-text-author"' + txtcolor + '>' + firstName + ' ' + secondName + '</div>\n';
          output += '<div class="news-elem-text-article"' + txtcolor + '>' + text + '</div>\n';
          output += '</div>\n';
          output += '</a>\n';
          output += '</div>\n';
          //
        }
        // clear
        $('#news-best-container').empty();
        // add blocks
        $('#news-best-container').append(output);
        // show
        $('#news-best-container').css('display', 'block');
        $('#news-best-title').css('display', 'block');
      }
      else if(checkResponseCode('FEW.')) {
        // hide
        $('#news-best-container').css('display', 'none');
        $('#news-best-title').css('display', 'none');
      }
      else if(checkResponseCode('ERROR.')) {
        notification_add('error', 'Ошибка', 'Неизвестная ошибка');
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

// === news filter fields ======================================================
function newsFilterSortBy(type) {
  News.sortBy = (type == 'views') ? 'views' : 'date';
  newsListing();
  updateSiteTitle();
}
function newsFilterSearch() {
  var needle = $('#news-filter-search').val();
  // check
  needle = needle.replace(/([^a-zA-Zа-яёА-ЯЁ0-9., ])/g, '');
   $('#news-filter-search').val(needle);
  News.needle = needle;
  newsListing();
  updateSiteTitle();
}
