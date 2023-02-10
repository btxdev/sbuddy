$(document).ready(function() {
  // load all reviews
  $('#' + ReviewsForm.reviews.field.container).empty();
  ReviewsForm.reviews.load();
  // load all certificates
  $('#' + ReviewsForm.certificates.field.container).empty();
  ReviewsForm.certificates.load();
  // load my review
  if(typeof(userData['account']) != 'undefined') {
    ReviewsForm.reviews.loadMy();
  }
  // format review textarea
  $('#' + ReviewsForm.reviews.field.review).on('input', function() {
    ReviewsForm.reviews.format();
  });
});

var ReviewsForm = {
  reviews: {
    field: {
      review: 'user-reviews-textarea',
      container: 'reviews-container',
      button: 'reviews-more-button',
      stars: [
        'dPSsL-gDfd-HHLt-AFUh',
        'dI3Um-mvVN-kjUz-pmO5',
        'd7Dnv-IqPz-msrt-5WkQ',
        'dwWHy-vXKn-MmpN-NwpS',
        'dhtKH-yljk-PUi0-wF86'
      ]
    },
    position: 0,
    mark: {
      value: 0,
      set: function(mark) {
        if(mark > 0 && mark < 6) {
          ReviewsForm.reviews.mark.value = mark;
        }
      },
      star: function(mark) {
        if(mark > 0 && mark < 6) {
          for(let i = (mark - 1); i < 5; i++) {
            $('#' + ReviewsForm.reviews.field.stars[i]).prop('checked', false);
          }
          $('#' + ReviewsForm.reviews.field.stars[mark - 1]).prop('checked', true);
        }
      }
    },
    load: function() {
      // request
      $.ajax({
        type: 'POST',
        url: 'php/db_reviews.php',
        data: {
          reviews_listing: true,
          page: ReviewsForm.reviews.position
        },
        complete: function(){
          loaderMain('hidden');
        },
        beforeSend: function(){
          loaderMain('show');
        },
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.')) {
            var responseText = response.substring(3, response.length);
            var responseData = JSON.parse(responseText);
            // button
            var end = responseData[0][0];
            if(end == true) {
              setTimeout(function() {
                $('#reviews-more-button').css({
                  'padding':'0px 15px 0px 15px',
                  'font-size':'0px',
                  'margin-top':'0px',
                  'margin-bottom':'0px',
                  'max-height':'0px',
                  'visibility':'hidden',
                  'opacity':'0'
                });
              }, 500);
            }
            // listing
            for(var i = 1; i < responseData.length; i++) {
              var output = '';
              var review = responseData[i];
              var username = review.username;
              var mark = review.mark;
              var text = review.text;
              var icon = review.icon;
              var name1 = review.name1;
              var name2 = review.name2;
              let d = review.date.split(' ')[0].split('-');
              var date = d[2] + '.' + d[1] + '.' + d[0];
              var id = idGenerator(15, 5);
              output += '<div class="container" id="' + id + '" style=" transition: 0.25s all; margin-bottom: 0px;">\n';
              output += '<div class="col-xs-0 col-sm-1 col-md-3 col-lg-3 col-xl-3"></div>\n';
              output += '<div class="col-xs-12 col-sm-10 col-md-6 col-lg-6 col-xl-6">\n';
              output += '<div class="user-reviews" style="border: 0px solid var(--colorMainBlue);">\n';
              output += '<div class="user-reviews-photo" style="background-image: url(&quot;' + icon + '&quot;);"></div>\n';
              output += '<div class="user-reviews-text">\n';
              output += '<div class="user-reviews-text-name" data-login="' + username + '">' + name1 + ' ' + name2 + '</div>\n';
              output += '<div class="user-reviews-text-assessment">\n';
              output += '<div>Оценка ' + mark + ' из 5</div>\n';
              for(let m = 0; m < mark; m++) {
                output += '<span class="user-reviews-text-assessment-star icons-star"></span>\n';
              }
              output += '</div>\n';
              output += '<div class="user-reviews-text-text">\n';
              output += '<div>Комментарий</div>\n';
              output += '<span>' + text + '</span>\n';
              output += '</div>\n';
              output += '<div class="user-reviews-text-text">\n';
              output += '<div>Дата публикации</div>\n';
              output += '<span>' + date + '</span>\n';
              output += '</div>\n';
              output += '</div>\n';
              output += '</div>\n';
              output += '</div>\n';
              output += '<div class="col-xs-0 col-sm-1 col-md-3 col-lg-3 col-xl-3"></div>\n';
              output += '</div>\n';
              // output
              $('#' + ReviewsForm.reviews.field.container).append(output);
              function revFade(id) {
                $('#' + id).css({
                  'margin-bottom':'25px'
                });
                $('#' + id).find('.user-reviews').css({
                  'max-height':'600px',
                  'padding':'15px 20px 15px 20px',
                  'border':'2px solid var(--colorMainBlue)',
                  'opacity':'1'
                });
                setTimeout(function(){
                  $('#' + id).find('.user-reviews').css({
                    'opacity':'1'
                  });
                }, 350);
                setTimeout(function(){
                  $('#' + id).find('.user-reviews').css({
                    'border':'',
                    'max-height':'max-content'
                  });
                }, 450);
              }
              setTimeout(revFade, (50 + ((i - 1) * 250)), id);
            }
            // finally
            ReviewsForm.reviews.position++;
          }
          else if(checkResponseCode('EMPTY.')) {
            console.log('empty');
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
    loadMy: function() {
      // request
      $.ajax({
        type: 'POST',
        url: 'php/db_reviews.php',
        data: {
          get_my_review: true
        },
        complete: function(){
          loaderMain('hidden');
        },
        beforeSend: function(){
          loaderMain('show');
        },
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('NONE.')) {
            // ignore
          }
          else if(checkResponseCode('OK.')) {
            var responseText = response.substring(3, response.length);
            var responseData = JSON.parse(responseText);
            var mark = Number(responseData.mark);
            if(mark > 0 && mark < 6) ReviewsForm.reviews.mark.star(mark);
            var text = responseData.text;
            if(text.length > 0) $('#' + ReviewsForm.reviews.field.review).val(text);
          }
          else if(checkResponseCode('AUTH.')) {
            // hide block
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
    format: function() {
      var text = $('#' + ReviewsForm.reviews.field.review).val();
      text = text.replace(/([^A-Za-zА-Яа-яЁё0-9\,\.\"\'\%\$\#\№\:\;\!\?\[\]\{\}\(\)\=\-\+\*\/\~\@\s])/g, '');
      $('#' + ReviewsForm.reviews.field.review).val(text);
    },
    send: function() {
      var mark = ReviewsForm.reviews.mark.value;
      var text = $('#' + ReviewsForm.reviews.field.review).val();
      var errc = 0;
      if(text.length > 10000) {
        notification_add('warning', 'Не удалось отправить отзыв', 'Слишком большой отзыв');
        errc++;
      }
      if(mark < 1 || mark > 5) {
        notification_add('warning', 'Не удалось отправить отзыв', 'Поставьте оценку');
        errc++;
      }
      if(errc > 0) return;
      // send
      $.ajax({
        type: 'POST',
        url: 'php/db_reviews.php',
        data: {
          send_review: true,
          mark: mark,
          text: text
        },
        complete: function(){
          loaderMain('hidden');
        },
        beforeSend: function(){
          loaderMain('show');
        },
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('SET.')) {
            notification_add('line', '', 'Спасибо! Ваш отзыв сохранен.');
          }
          else if(checkResponseCode('UPD.')) {
            notification_add('line', '', 'Спасибо! Ваш отзыв обновлен.');
          }
          else if(checkResponseCode('AUTH.')) {
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
  certificates: {
    field: {
      container: 'sertificates-container'
    },
    position: 0,
    load: function() {
      // request
      $.ajax({
        type: 'POST',
        url: 'php/db_reviews.php',
        data: {
          cert_listing_2: true,
          page: ReviewsForm.certificates.position
        },
        complete: function(){
          loaderMain('hidden');
        },
        beforeSend: function(){
          loaderMain('show');
        },
        success: function(response) {
          function checkResponseCode(code, rsp) {
            if(typeof(rsp) == 'undefined') rsp = response;
            return (response.substring(0, code.length) == code);
          }
          if(checkResponseCode('OK.')) {
            var responseText = response.substring(3, response.length);
            var responseData = JSON.parse(responseText);
            // button
            var end = responseData[0][0];
            if(end == true) {
              setTimeout(function() {
                $('#certificates-more-button').css('display', 'none');
              }, 500);
            }
            // listing
            for(var i = 1; i < responseData.length; i++) {
              var output = '';
              var certificate = responseData[i];
              var title = certificate.title;
              var text = certificate.text;
              var issued = certificate.issued;
              var link = certificate.link;
              let d = certificate.date.split(' ')[0].split('-');
              var date = d[2] + '.' + d[1] + '.' + d[0];
              var id = idGenerator(15, 5);
              output += '<div class="container" id="' + id + '" style="display: none;">\n';
              output += '<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-4 super-review">\n';
              output += '<div class="super-review-block">\n';
              output += '<img src="' + link + '" class="super-review-block-image"></img>\n';
              output += '</div>\n';
              output += '</div>\n';
              output += '<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 col-xl-8">\n';
              output += '<div class="super-review-title">' + title + '</div>\n';
              output += '<div class="super-review-description">' + text + '</div>\n';
              output += '<div class="super-review-date">\n';
              output += '<span class="super-review-date-text">Выдано:</span>\n';
              output += '<span class="super-review-date-text2">' + issued + '</span>\n';
              output += '</div><br>\n';
              output += '<div class="super-review-date">\n';
              output += '<span class="super-review-date-text">Дата:</span>\n';
              output += '<span class="super-review-date-text2">' + date + '</span>\n';
              output += '</div><br>\n';
              output += '<div class="super-review-download">\n';
              output += '<span class="super-review-date-text"><a href="' + link + '" target="_blank" style="color: inherit;">Скачать</a></span>\n';
              output += '</div>\n';
              output += '</div>\n';
              output += '</div>\n';
              // output
              $('#' + ReviewsForm.certificates.field.container).append(output);
              function certFade(id) {
                $('#' + id).fadeIn(500);
              }
              setTimeout(certFade, (50 + ((i - 1) * 250)), id);
            }
            ReviewsForm.certificates.position = 1;
          }
          else if(checkResponseCode('EMPTY.')) {
            console.log('empty');
          }
          else {
            console.log('error: ' + response);
          }
        },
        error: function(jqXHR, status) {
          console.log('error: ' + status + ', ' + jqXHR);
        }
      });
    }
  }
};
