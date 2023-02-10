$(document).ready(function() {
  sliderSet(0);
});

var Article = {
  likes: 0,
  dislikes: 0,
  recordId: 0,
  mark: 'none',
  slider: [],
  sliderSelected: 0,
  auth: false
};

function articleViews(views) {
  var decl = declOfNumber(views, 'просмотр');
  $('#views-count').text(views + ' ' + decl);
}

function articleDate(date) {
  var dateArr = date.substring(0, date.length - 9).replace(/\-/g, '.').split('.');
  var newDate = dateArr[2] + '.' + dateArr[1] + '.' + dateArr[0];
  $('#publication-date').text(newDate);
}

function articleLikes(likes, dislikes) {
  var wordLikes = shortNum(likes);
  var wordDislikes = shortNum(dislikes);
  var percent;
  if(likes == dislikes) {
    percent = 50;
  }
  else {
    percent = Math.floor((likes / ((likes + dislikes) + 0.0001)) * 100);
    if(percent > 100) percent = 100;
    if(percent < 0) percent = 0;
  }
  Article.likes = likes;
  Article.dislikes = dislikes;
  $('#likes-count').text(wordLikes[0] + ' ' + wordLikes[1]);
  $('#dislikes-count').text(wordDislikes[0] + ' ' + wordDislikes[1]);
  $('#likes-bar').css('width', percent + '%');
}

function setMark(mark, send) {
  if(Article.auth !== true) {
    notification_add('info', 'Уведомление', 'Чтобы оценить запись, Вам необходимо авторизироваться на сайте');
    return;
  }
  if(Article.mark == 'none') {
    if(mark == 'like') {
      Article.likes++;
      Article.mark = 'like';
    }
    else if(mark == 'dislike') {
      Article.dislikes++;
      Article.mark = 'dislike';
    }
    else {}
  }
  else if(Article.mark == 'like') {
    if(mark == 'like') {
      Article.likes--;
      Article.mark = 'none';
    }
    else if(mark == 'dislike') {
      Article.likes--;
      Article.dislikes++;
      Article.mark = 'dislike';
    }
    else {}
  }
  else if(Article.mark == 'dislike') {
    if(mark == 'like') {
      Article.likes++;
      Article.dislikes--;
      Article.mark = 'like';
    }
    else if(mark == 'dislike') {
      Article.dislikes--;
      Article.mark = 'none';
    }
    else {}
  }
  else {}
  articleLikes(Article.likes, Article.dislikes);
  //
  if(send === true) {
    articleDBSendMark(mark);
  }
}

function sliderSet(id) {
  var id;
  if(typeof(id) == 'undefined') {
    id = Article.sliderSelected;
  }
  else {
    Article.sliderSelected = id;
  }
  var path = Article.slider[id];
  $('#slider-big').css('background-image', 'url("' + path + '")');
}

function sliderArrow(mode) {
  if(mode == 'prev') {
    Article.sliderSelected--;
  }
  if(mode == 'next') {
    Article.sliderSelected++;
  }
  if(Article.sliderSelected >= Article.slider.length) Article.sliderSelected = 0;
  if(Article.sliderSelected < 0) Article.sliderSelected = (Article.slider.length - 1);
  sliderSet();
}

function sliderAddPhoto(path) {
  var id = Article.slider.length;
  Article.slider[id] = path;
  var output = '<div class="article-slider-photo-main-elem" style="background-image: url(&quot;' + path + '&quot;);" onclick="sliderSet(' + id + ');"></div>\n';
  $('#slider-mini').append(output);
}

function articleDBGetMark() {
  $.ajax({
    type: 'POST',
    url: 'php/db_article.php',
    data: {
      get_mark: true,
      record: Article.recordId
    },
    complete: function(){
      loaderMain('hidden');
    },
    beforeSend: function(){
      loaderMain('show');
    },
    success: function(response) {
      function checkResponseCode(code, rsp) { if(typeof(rsp) == 'undefined') rsp = response; return (response.substring(0, code.length) == code); }
      if(checkResponseCode('NONE.')) {
        Article.mark = 'none';
      }
      else if(checkResponseCode('LIKE.')) {
        Article.likes--;
        Article.mark = 'none';
        setMark('like');
      }
      else if(checkResponseCode('DISLIKE.')) {
        Article.dislikes--;
        Article.mark = 'none';
        setMark('dislike');
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

function articleDBSendMark(mark) {
  $.ajax({
    type: 'POST',
    url: 'php/db_article.php',
    data: {
      set_mark: true,
      mark: mark,
      record: Article.recordId
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
        // ok
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
