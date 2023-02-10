$(document).ready(function() {
  IndexNews.load();
  IndexSlider.update(true);
  // auto slide
  IndexSlider.moveInterval = setInterval(IndexSlider.move, IndexSlider.moveIntervalTime, true, false);
});

var nameRegex = /^([A-Za-zА-ЯЁа-яё]){2,48}$/u;
var emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var phoneRegex = /^([0-9]){11}$/g;
var messageRegex = /([^A-Za-zА-Яа-яЁё0-9\,\.\"\'\%\$\#\№\:\;\!\?\[\]\{\}\(\)\=\-\+\*\/\~\@\s])/g;

var countTestStage = 1;


function controlTest(action){
  if(action.toString().match(/^(next|forward)$/ui)){

    $('.window-container-title2').css('visibility','visible')
    $('#test-title').text('Название теста')
    $('#test-title').css({
      'text-align':'left'
    });
    $('#btn-test').css({
      'display':'inline-block'
    })
    if(countTestStage < maxTestStages){
      if(countTestStage + 1 != maxTestStages){
        var tmpTestElem = $('#control-test').find('.window-container-textOverflow-elem');
        $('#control-test-stage').text(countTestStage + 1);
        for(let i = 0; i < tmpTestElem.length; i++){
          let tmpTrans = -(100 * countTestStage);
          let tmpBlock = $($('#control-test').find('.window-container-textOverflow-elem')[i]);
          tmpBlock.css('transform', 'translate(' +  tmpTrans + '%, 0px)');
        }

        countTestStage++;
      } else{
        var tmpTestElem = $('#control-test').find('.window-container-textOverflow-elem');
        for(let i = 0; i < tmpTestElem.length; i++){
          let tmpTrans = -(100 * countTestStage);
          let tmpBlock = $($('#control-test').find('.window-container-textOverflow-elem')[i]);
          tmpBlock.css('transform', 'translate(' +  tmpTrans + '%, 0px)');
        }
        $('#control-test-stage').text(countTestStage + 1);
        $('#control-test').find('.window-btn').val('Завершить');
        $('#control-test').find('.window-btn').attr('onclick',"controlTest('end')")
      }
    }



  }
  if(action.toString().match(/^(end)$/ui)){
    var tmpTestElem = $('#control-test').find('.window-container-textOverflow-elem');
    countTestStage++;
    for(let i = 0; i < tmpTestElem.length; i++){
      let tmpTrans = -(100 * countTestStage);
      let tmpBlock = $($('#control-test').find('.window-container-textOverflow-elem')[i]);
      tmpBlock.css('transform', 'translate(' +  tmpTrans + '%, 0px)');
    }
    $('#control-test').find('.window-container-title2').html("<span id='control-test-stage'></span>Тест завершен!");
    $('#control-test').find('.window-btn').val('Записаться');
    $('#control-test').find('.window-btn').attr('onclick','')

    $('#control-test').find('.window-btn').css({
      'width':'100%'
    })
    $('#control-test').find('.window-container-title').css({
      'text-align':'center',
      'max-width':'100%'
    })
    $('#control-test').find('.window-container-title2').css({
      'text-align':'center',
      'max-width':'100%'
    })
  }
  if(action.toString().match(/^(back)$/ui)){
    console.log('count2')
  }
  if(action > 0 && action < 999 && action !== undefined){
    console.log('count1')
  }
}

function htmlDecode(value) {
  return $('<div/>').html(value).text();
}

var IndexNews = {
  field: 'id-news-slider',
  load: function() {
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
          console.log('НОВОСТИ ЗАГРУЖЕНЫ');
          // parse data
          var responseText = response.substring(3, response.length);
          var tmp = JSON.parse(responseText);
          console.log(tmp);
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
            // output
            output += '<a href="article?id=' + id + '" class="news-slider-elem" style="background-image: url(&quot;' + image + '&quot;);">\n';
            output += '<div class="news-slider-elem-hover">\n';
            output += '<div class="news-slider-elem-hover-title">' + title + '</div>\n';
            output += '<div class="news-slider-elem-hover-author">' + firstName + ' ' + secondName + '</div>\n';
            output += '</div>\n';
            output += '</a>\n';
          }
          // clear
          $('#' + IndexNews.field).empty();
          // add blocks
          $('#' + IndexNews.field).append(output);
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
};

var IndexSlider = {
  field: {
    arrow: {
      left: 'index-slider-arrow-l',
      right: 'index-slider-arrow-r'
    },
    img: 'index-slider-img'
  },
  position: 0,
  photos: [],
  update: function(first) {
    // check position
    if(IndexSlider.position < 0) IndexSlider.position = IndexSlider.photos.length - 1;
    if(IndexSlider.position > (IndexSlider.photos.length - 1)) IndexSlider.position = 0;
    // loading
    if(typeof(IndexSlider.photos[IndexSlider.position]) == 'undefined') {
      setTimeout(IndexSlider.update, 10, true, false);
      return;
    }
    // img
    var duration = 140;
    if(first === true) {
      $('#' + IndexSlider.field.img).css('background-image', 'url("' + IndexSlider.photos[IndexSlider.position].link + '")');
    }
    else {
      setTimeout(function() { $('#' + IndexSlider.field.img).fadeOut(duration); }, 10);
      setTimeout(function() { $('#' + IndexSlider.field.img).css('background-image', 'url("' + IndexSlider.photos[IndexSlider.position].link + '")'); }, Math.floor(duration * 0.75));
      setTimeout(function() { $('#' + IndexSlider.field.img).fadeIn(duration); }, (duration + 15));
    }
    // arrows (disabled)
    /*if(IndexSlider.position < 0) {
      IndexSlider.position = IndexSlider.photos.length - 1;
      $('#' + IndexSlider.field.arrow.left).css('display', 'none');
    }
    else {
      $('#' + IndexSlider.field.arrow.left).css('display', 'block');
    }
    if(IndexSlider.position > (IndexSlider.photos.length - 1)) {
      IndexSlider.position = 0;
      $('#' + IndexSlider.field.arrow.right).css('display', 'none');
    }
    else {
      $('#' + IndexSlider.field.arrow.right).css('display', 'block');
    }*/
  },
  moveInterval: undefined,
  moveIntervalTime: 7500,
  move: function(direction, click) {
    if(click === true && typeof(IndexSlider.moveInterval) != 'undefined') {
      clearInterval(IndexSlider.moveInterval);
      IndexSlider.moveInterval = setInterval(IndexSlider.move, IndexSlider.moveIntervalTime, true, false);
    }
    if(direction === true) {
      IndexSlider.position++;
    }
    else {
      IndexSlider.position--;
    }
    IndexSlider.update();
  }
};

var IndexMail = {
  field: {
    name1: 'mail-name1',
    name2: 'mail-name2',
    phone: 'mail-phone',
    email: 'mail-email',
    msg: 'mail-msg'
  },
  send: function() {
    // check fields
    var tmpArr = [];
    var errc = 0;
    var errc2 = 0;
    for(field in IndexMail.field) {
      let value = $('#' + IndexMail.field[field]).val();
      tmpArr[field] = value;
      if(field == 'name1') {
        if(!value.match(nameRegex)) {
          notification_add('warning', 'Ошибка', 'Имя указано некорректно!');
          errc++;
        }
      }
      if(field == 'name2') {
        if(!value.match(nameRegex)) {
          notification_add('warning', 'Ошибка', 'Фамилия указана некорректно!');
          errc++;
        }
      }
      if(field == 'phone') {
        if(value.length > 0) {
          if(!value.match(phoneRegex)) {
            notification_add('warning', 'Ошибка', 'Номер телефона указан некорректно!');
            errc++;
          }
        }
        else {
          errc2++;
        }
      }
      if(field == 'email') {
        if(value.length > 0) {
          if(!value.match(emailRegex)) {
            notification_add('warning', 'Ошибка', 'Адрес эл. почты указан некорректно!');
            errc++;
          }
        }
        else {
          errc2++;
        }
      }
      if(field == 'msg') {
        if(value.match(messageRegex)) {
          notification_add('warning', 'Ошибка', 'Текст письма содержит запрещенные символы!');
          errc++;
        }
        if(value.length < 10) {
          notification_add('warning', 'Ошибка', 'Сообщение слишком короткое');
          errc++;
        }
      }
    }
    // check
    if(errc > 0) return;
    // empty fields
    if(errc2 > 1) {
      notification_add('warning', 'Ошибка', 'Укажите телефон или адрес эл. почты');
      return;
    }
    // get token
    if(typeof(grecaptcha) == 'undefined') {
      console.error('grecaptcha undefined');
      return;
    }
    grecaptcha.execute('6LdKcfQUAAAAAMtb7qMKV1fs2rAIzLEeJp5UdFX9', {action: 'homepage'}).then(function(token) {
       // send request
       $.ajax({
         type: 'POST',
         url: 'php/db_index.php',
         data: {
           send_mail: true,
           captcha3_token: token,
           user_token: Statistics.userid,
           name1: tmpArr['name1'],
           name2: tmpArr['name2'],
           phone: tmpArr['phone'],
           email: tmpArr['email'],
           msg: tmpArr['msg']
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
           // good
           if(checkResponseCode('OK.')) {
             notification_add('line', '', 'Письмо отправлено!');
             // clear form
             // ...
           }
           else if(checkResponseCode('WRONG.')) {
             notification_add('error', 'Ошибка', 'Проверьте правильность заполенных полей');
           }
           else if(checkResponseCode('ERROR.')) {
             notification_add('error', 'Ошибка', 'Неизвестная ошибка');
           }
           else if(checkResponseCode('NAME1.')) {
             notification_add('error', 'Ошибка', 'Имя указано некорректно!');
           }
           else if(checkResponseCode('NAME2.')) {
             notification_add('error', 'Ошибка', 'Фамилия указана некорректно!');
           }
           else if(checkResponseCode('PHONE.')) {
             notification_add('error', 'Ошибка', 'Номер телефона указан некорректно!');
           }
           else if(checkResponseCode('EMAIL.')) {
             notification_add('error', 'Ошибка', 'Адрес эл. почты указан некорректно!');
           }
           else if(checkResponseCode('MSG.')) {
             notification_add('error', 'Ошибка', 'Текст письма содержит запрещенные символы!');
           }
           else if(checkResponseCode('TIMER.')) {
             notification_add('warning', 'Внимание', 'Можно отправлять не более одного сообщения за 5 минут');
           }
           else if(checkResponseCode('CAPTCHA.')) {
             location.reload(true);
           }
           else {
             notification_add('error', 'Ошибка', 'Неизвестная ошибка');
             console.error('error: ' + response);
           }
         },
         error: function(jqXHR, status) {
           notification_add('error', 'Ошибка сервера', 'Неизвестная ошибка');
           console.error('error: ' + status + ', ' + jqXHR);
         }
       });
    });
  }
};

$(document).ready(function() {
  IndexTest.init();
});

var IndexTest = {
  field: {
    a: ''
  },
  test: [
    {
      title: 'KIDS',
      scoretable: [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
      resulttable: [
        [5, 'Начальный уровень'],
        [13, 'Средний уровень'],
        [19, 'Выше среднего'],
        [25, 'Высокий уровень']
      ],
      questions: [
        {
          title: '– How old are you?<br>– I ___ seven.',
          type: 'choose',
          answers: [
            'is',
            'am',
            'have got'
          ],
          correct: 1
        },
        {
          title: 'What is your favourite colour?',
          type: 'choose',
          answers: [
            'purple',
            'paper',
            'doll'
          ],
          correct: 0
        },
        {
          title: 'Where is your ___?',
          type: 'choose',
          answers: [
            'pencil',
            'books',
            'seven'
          ],
          correct: 0
        },
        {
          title: 'I have got ___ shoes.',
          type: 'choose',
          answers: [
            'parrot',
            'pink',
            'sunny'
          ],
          correct: 1
        },
        {
          title: 'I like ___ weather.',
          type: 'choose',
          answers: [
            'sunny',
            'yellow',
            'rubber'
          ],
          correct: 0
        },
        {
          title: 'It is cloudy and ___',
          type: 'choose',
          answers: [
            'windy',
            'window',
            'five'
          ],
          correct: 0
        },
        {
          title: 'Tom can ___ fast.',
          type: 'choose',
          answers: [
            'sleep',
            'run',
            'jump'
          ],
          correct: 1
        },
        {
          title: 'I ___ from Russia.',
          type: 'choose',
          answers: [
            'am',
            'can',
            'like'
          ],
          correct: 0
        },
        {
          title: 'He ___ a ruler.',
          type: 'choose',
          answers: [
            'has got',
            'can',
            'is'
          ],
          correct: 0
        },
        {
          title: 'I ___ bananas.',
          type: 'choose',
          answers: [
            'am',
            'like',
            'can'
          ],
          correct: 1
        },
        {
          title: 'He ___ ride a scooter, play the piano and swim.',
          type: 'choose',
          answers: [
            'has got',
            'is',
            'can'
          ],
          correct: 2
        },
        {
          title: 'Today she is ___ a dress.',
          type: 'choose',
          answers: [
            'wear',
            'wearing',
            'wears'
          ],
          correct: 1
        },
        {
          title: 'It ___ Friday. She was at home.',
          type: 'choose',
          answers: [
            'was',
            'is',
            'be'
          ],
          correct: 0
        },
        {
          title: 'He has ___ for dinner.',
          type: 'choose',
          answers: [
            'meet',
            'meat',
            'mit'
          ],
          correct: 1
        },
        {
          title: 'He ___ scissors.',
          type: 'choose',
          answers: [
            'isn’t',
            'hasn’t got',
            'don’t have'
          ],
          correct: 1
        },
        {
          title: 'She ___ tennis on Monday.',
          type: 'choose',
          answers: [
            'play',
            'playing',
            'plays'
          ],
          correct: 2
        },
        {
          title: 'Посмотрите на изображение и ответьте на вопрос:<br><img style="border: none; overflow: hidden; border-radius: var(--border-r);" width="250px" src="media/img/test1.jpg"></img><br><br><b>Is there a table?</b>',
          type: 'choose',
          answers: [
            'Yes, there is.',
            'No, there isn’t.'
          ],
          correct: 0
        },
        {
          title: 'Посмотрите на изображение и ответьте на вопрос:<br><img style="border: none; overflow: hidden; border-radius: var(--border-r);" width="250px" src="media/img/test1.jpg"></img><br><br><b>Is there a picture?</b>',
          type: 'choose',
          answers: [
            'Yes, there is.',
            'No, there isn’t.'
          ],
          correct: 0
        },
        {
          title: 'Посмотрите на изображение и ответьте на вопрос:<br><img style="border: none; overflow: hidden; border-radius: var(--border-r);" width="250px" src="media/img/test1.jpg"></img><br><br><b>Where is the lamp?</b>',
          type: 'choose',
          answers: [
            'On the floor.',
            'On the table.'
          ],
          correct: 1
        },
        {
          title: 'Посмотрите на изображение и ответьте на вопрос:<br><img style="border: none; overflow: hidden; border-radius: var(--border-r);" width="250px" src="media/img/test1.jpg"></img><br><br><b>The boy has got a ___</b>',
          type: 'choose',
          answers: [
            'robot',
            'book'
          ],
          correct: 1
        },
        {
          title: 'Посмотрите на изображение и ответьте на вопрос:<br><img style="border: none; overflow: hidden; border-radius: var(--border-r);" width="250px" src="media/img/test1.jpg"></img><br><br><b>Where is the cat?</b>',
          type: 'choose',
          answers: [
            'On the sofa.',
            'Next to the lamp.'
          ],
          correct: 0
        },
        {
          title: 'Посмотрите на изображение и ответьте на вопрос:<br><img style="border: none; overflow: hidden; border-radius: var(--border-r);" width="250px" src="media/img/test1.jpg"></img><br><br><b>Where are the books?</b>',
          type: 'choose',
          answers: [
            'On the table.',
            'On the floor.'
          ],
          correct: 1
        },
        {
          title: 'Посмотрите на изображение и ответьте на вопрос:<br><img style="border: none; overflow: hidden; border-radius: var(--border-r);" width="250px" src="media/img/test1.jpg"></img><br><br><b>There is a sofa in the ___</b>',
          type: 'choose',
          answers: [
            'toilet',
            'kitchen',
            'living room'
          ],
          correct: 2
        },
        {
          title: 'Посмотрите на изображение и ответьте на вопрос:<br><img style="border: none; overflow: hidden; border-radius: var(--border-r);" width="250px" src="media/img/test1.jpg"></img><br><br><b>She has breakfast in the ___</b>',
          type: 'choose',
          answers: [
            'bathroom',
            'kitchen',
            'hall'
          ],
          correct: 1
        },
        {
          title: 'Посмотрите на изображение и ответьте на вопрос:<br><img style="border: none; overflow: hidden; border-radius: var(--border-r);" width="250px" src="media/img/test1.jpg"></img><br><br><b>There is a ___ in the bedroom</b>',
          type: 'choose',
          answers: [
            'bed',
            'tables',
            'cooker'
          ],
          correct: 0
        }
      ]
    },
    {
      title: 'TEENS AND ADULTS',
      scoretable: [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
      resulttable: [
        [15, 'Elementary'],
        [30, 'Intermediate'],
        [45, 'Upper-Intermediate'],
        [60, 'Advanced']
      ],
      questions: [
        {
          title: 'How old are you?',
          type: 'choose',
          answers: [
            'I have 16.',
            'I am 16. ',
            'I have 16 years. ',
            'I am 16 years.'
          ],
          correct: 1
        },
        {
          title: 'Are you having a nice time?',
          type: 'choose',
          answers: [
            'Yes, I’m nice. ',
            'Yes, I’m having it. ',
            'Yes, I am. ',
            'Yes, it is.'
          ],
          correct: 2
        },
        {
          title: 'Could you pass the salt please?',
          type: 'choose',
          answers: [
            'Over there. ',
            'I don’t know. ',
            'Help yourself. ',
            'Here you are.'
          ],
          correct: 3
        },
        {
          title: 'Yesterday I went to the National Museum _______ bus.',
          type: 'choose',
          answers: [
            'on ',
            'in ',
            'by ',
            'with'
          ],
          correct: 2
        },
        {
          title: 'Sue and Mike _______ to go camping.',
          type: 'choose',
          answers: [
            'wanted ',
            'said ',
            'made ',
            'talked'
          ],
          correct: 0
        },
        {
          title: 'Who’s calling?',
          type: 'choose',
          answers: [
            'Just a moment. ',
            'It’s David Parker. ',
            'I’ll call you back. ',
            'Speaking.'
          ],
          correct: 1
        },
        {
          title: 'They were _______ after the long journey, so they went to bed.',
          type: 'choose',
          answers: [
            'hungry ',
            'hot ',
            'lazy ',
            'tired'
          ],
          correct: 3
        },
        {
          title: 'Can you tell me the _______ to the bus station?',
          type: 'choose',
          answers: [
            'road ',
            'way ',
            'direction ',
            'street'
          ],
          correct: 1
        },
        {
          title: '_______ you remember to buy some milk?',
          type: 'choose',
          answers: [
            'Have ',
            'Do ',
            'Should ',
            'Did'
          ],
          correct: 3
        },
        {
          title: '- Don’t forget to put the rubbish out.<br>- I’ve _______ done it!',
          type: 'choose',
          answers: [
            'yet ',
            'still ',
            'already ',
            'even'
          ],
          correct: 2
        },
        {
          title: 'You don’t need to bring _______ to eat.',
          type: 'choose',
          answers: [
            'some ',
            'a food ',
            'many ',
            'anything'
          ],
          correct: 3
        },
        {
          title: 'What about going to the cinema?',
          type: 'choose',
          answers: [
            'Good idea! ',
            'Twice a month. ',
            'It’s Star Wars. ',
            'I think so.'
          ],
          correct: 0
        },
        {
          title: '- What would you like, Sue?<br>- I’d like the same _______ Michael please.',
          type: 'choose',
          answers: [
            'that ',
            'as ',
            'for ',
            'had'
          ],
          correct: 1
        },
        {
          title: '_______ people know the answer to that question.',
          type: 'choose',
          answers: [
            'Few ',
            'Little ',
            'Least ',
            'A little'
          ],
          correct: 0
        },
        {
          title: 'It’s not _______ to walk home by yourself in the dark.',
          type: 'choose',
          answers: [
            'sure ',
            'certain ',
            'safe ',
            'problem'
          ],
          correct: 2
        },
        {
          title: '_______ sure all the windows are locked.',
          type: 'choose',
          answers: [
            'Take ',
            'Have ',
            'Wait ',
            'Make'
          ],
          correct: 3
        },
        {
          title: 'I’ll go and _______ if I can find him.',
          type: 'choose',
          answers: [
            'see ',
            'look ',
            'try ',
            'tell'
          ],
          correct: 0
        },
        {
          title: 'What’s the difference _______ football and rugby?',
          type: 'choose',
          answers: [
            'from ',
            'with ',
            'for ',
            'between'
          ],
          correct: 3
        },
        {
          title: 'My car needs _______ .',
          type: 'choose',
          answers: [
            'repairing ',
            'to repair ',
            'to be repair ',
            'repair'
          ],
          correct: 0
        },
        {
          title: 'Tim was too __________ to ask Monika for a dance.',
          type: 'choose',
          answers: [
            'worried ',
            'shy ',
            'selfish ',
            'polite'
          ],
          correct: 1
        },
        {
          title: 'I haven’t had so much fun _______ I was a young boy!',
          type: 'choose',
          answers: [
            'when ',
            'for ',
            'during ',
            'since'
          ],
          correct: 3
        },
        {
          title: 'Sorry, I don’t know _______ you’re talking about.',
          type: 'choose',
          answers: [
            'that ',
            'what ',
            'which ',
            'why'
          ],
          correct: 1
        },
        {
          title: 'I’m afraid you _______ smoke in here.',
          type: 'choose',
          answers: [
            'could not ',
            'don’t have to ',
            'are not allowed to ',
            'can’t be'
          ],
          correct: 2
        },
        {
          title: 'Everyone wanted to go out _______ John.',
          type: 'choose',
          answers: [
            'apart ',
            'unless ',
            'however ',
            'except'
          ],
          correct: 3
        },
        {
          title: 'Honestly! I saw a ghost! I’m not _______ it up!',
          type: 'choose',
          answers: [
            'having ',
            'laughing ',
            'making ',
            'joking'
          ],
          correct: 2
        },
        {
          title: 'Eat everything up! I don’t want to see anything _______ on your plate!',
          type: 'choose',
          answers: [
            'left ',
            'missing ',
            'put ',
            'staying'
          ],
          correct: 0
        },
        {
          title: 'Take the A20 _______ the roundabout, then turn left.',
          type: 'choose',
          answers: [
            'right ',
            'as far as ',
            'along ',
            'heading north'
          ],
          correct: 1
        },
        {
          title: 'I really hope you can find a _______ to this problem.',
          type: 'choose',
          answers: [
            'result ',
            'way ',
            'conclusion ',
            'solution'
          ],
          correct: 3
        },
        {
          title: 'Could you watch my bag while I go and get a cup of tea?',
          type: 'choose',
          answers: [
            'Of course! ',
            'Never mind. ',
            'If you don’t mind. ',
            'It doesn’t matter.'
          ],
          correct: 0
        },
        {
          title: 'In my country, it is _______ the law to watch an X-rated film if you are under eighteen.',
          type: 'choose',
          answers: [
            'under ',
            'against ',
            'over ',
            'beyond'
          ],
          correct: 1
        },
        {
          title: 'Rebecca had to _______ the invitation, as she was busy studying for her exams.',
          type: 'choose',
          answers: [
            'take off ',
            'put back ',
            'turn down ',
            'get away'
          ],
          correct: 2
        },
        {
          title: 'Police _______ that a terrorist group might be behind the kidnapping.',
          type: 'choose',
          answers: [
            'suppose ',
            'fancy ',
            'suspect ',
            'accuse'
          ],
          correct: 2
        },
        {
          title: 'When Christopher smiles, he _______ me of his grandfather.',
          type: 'choose',
          answers: [
            'remembers ',
            'recalls ',
            'rethinks ',
            'reminds'
          ],
          correct: 3
        },
        {
          title: 'The wonderful smell of freshly _______ coffee hit us as we entered the store.',
          type: 'choose',
          answers: [
            'crushed ',
            'smashed ',
            'ground ',
            'pressed'
          ],
          correct: 2
        },
        {
          title: 'Mike’s dad wouldn’t _______ him go to school with a red streak in his hair.',
          type: 'choose',
          answers: [
            'allow ',
            'permit ',
            'accept ',
            'let'
          ],
          correct: 3
        },
        {
          title: 'If only I _______ made that phone call!',
          type: 'choose',
          answers: [
            'wasn’t ',
            'didn’t ',
            'hadn’t ',
            'haven’t'
          ],
          correct: 2
        },
        {
          title: 'I like Mary for her friendly smile and her _______ of humour.',
          type: 'choose',
          answers: [
            'sense ',
            'manner ',
            'way ',
            'impression'
          ],
          correct: 0
        },
        {
          title: 'These shoes are very _______ for walking in the mountains.',
          type: 'choose',
          answers: [
            'practical ',
            'functional ',
            'realistic ',
            'active'
          ],
          correct: 0
        },
        {
          title: '_______ of the credit for our success has to go to the Chairman, Peter Lewis.',
          type: 'choose',
          answers: [
            'Several ',
            'Much ',
            'Enough ',
            'Sufficient'
          ],
          correct: 1
        },
        {
          title: 'We were surprised that over 500 people _______ for the job.',
          type: 'choose',
          answers: [
            'wrote ',
            'applied ',
            'enquired ',
            'requested'
          ],
          correct: 1
        },
        {
          title: 'The children watched in excitement as she _______ a match and lit the candles.',
          type: 'choose',
          answers: [
            'scratched ',
            'struck ',
            'rubbed ',
            'scraped'
          ],
          correct: 1
        },
        {
          title: 'Sorry about Kate’s strange behaviour, but she’s just not used to _______ lots of people around her.',
          type: 'choose',
          answers: [
            'had ',
            'have ',
            'having ',
            'has'
          ],
          correct: 2
        },
        {
          title: 'Ivan kept running very hard _______ none of the other runners could possibly catch him.',
          type: 'choose',
          answers: [
            'even though ',
            'however ',
            'despite ',
            'as'
          ],
          correct: 0
        },
        {
          title: '‘I did this painting all _______ my own, Dad,’ said Milly.',
          type: 'choose',
          answers: [
            'by ',
            'with ',
            'for ',
            'on'
          ],
          correct: 3
        },
        {
          title: 'You _______ better check all the details are correct before we send it off.',
          type: 'choose',
          answers: [
            'would ',
            'had ',
            'should ',
            'did'
          ],
          correct: 1
        },
        {
          title: 'This game is _______ to be for five year-olds, but I think a two year-old could do it!',
          type: 'choose',
          answers: [
            'expected ',
            'required ',
            'obliged ',
            'supposed'
          ],
          correct: 3
        },
        {
          title: 'Just put this powder down, and it should _______ any more ants from getting in.',
          type: 'choose',
          answers: [
            'prevent ',
            'avoid ',
            'refuse ',
            'forbid'
          ],
          correct: 0
        },
        {
          title: 'When Johnie _______ to do something, you can be sure he’ll do it, and do it well.',
          type: 'choose',
          answers: [
            'gets on ',
            'takes up ',
            'sets out ',
            'brings about'
          ],
          correct: 2
        },
        {
          title: '_______ we get to the top of this hill, the path gets much easier.',
          type: 'choose',
          answers: [
            'At the time ',
            'Eventually ',
            'Once ',
            'Finally'
          ],
          correct: 2
        },
        {
          title: 'Fifty-seven? No, that _______ be the right answer!',
          type: 'choose',
          answers: [
            'can’t ',
            'mustn’t ',
            'wouldn’t ',
            'needn’t'
          ],
          correct: 0
        },
        {
          title: '_______ happens, I’ll always be there for you!',
          type: 'choose',
          answers: [
            'However ',
            'What ',
            'Whatever ',
            'No matter'
          ],
          correct: 2
        },
        {
          title: 'Can you _______ to it that no one uses this entrance?',
          type: 'choose',
          answers: [
            'see ',
            'deal ',
            'ensure ',
            'get'
          ],
          correct: 0
        },
        {
          title: 'A _______ debate ensued, with neither side prepared to give way to the other.',
          type: 'choose',
          answers: [
            'warm ',
            'heated ',
            'hot ',
            'boiling'
          ],
          correct: 1
        },
        {
          title: 'I’ve drunk milk every _______ day of my life, and it’s never done me any harm!',
          type: 'choose',
          answers: [
            'particular ',
            'individual ',
            'single ',
            'one'
          ],
          correct: 2
        },
        {
          title: 'The version of the film I saw had been _______ censored.',
          type: 'choose',
          answers: [
            'strongly ',
            'deeply ',
            'great ',
            'heavily'
          ],
          correct: 3
        },
        {
          title: 'He promised to phone me at nine o’clock exactly, and he was as _______ as his word.',
          type: 'choose',
          answers: [
            'true ',
            'good ',
            'right ',
            'honest'
          ],
          correct: 1
        },
        {
          title: 'There has been so much media _______ of the wedding that I’m completely fed up with it.',
          type: 'choose',
          answers: [
            'circulation ',
            'attention ',
            'broadcasting ',
            'coverage'
          ],
          correct: 3
        },
        {
          title: 'If I were you I would _______ clear of the area around the station late at night.',
          type: 'choose',
          answers: [
            'stick ',
            'steer ',
            'stop ',
            'stand'
          ],
          correct: 1
        },
        {
          title: 'Turning back now is out of the _______ .',
          type: 'choose',
          answers: [
            'agenda ',
            'matter ',
            'question ',
            'possibility'
          ],
          correct: 2
        },
        {
          title: 'Joe’s fear of enclosed spaces _______ from a bad experience he had when he was a child.',
          type: 'choose',
          answers: [
            'stems ',
            'leads ',
            'starts ',
            'flows'
          ],
          correct: 0
        }
        /*{
          title: 'Open the brackets and put the verbs in the correct form.<br><br>Cambridge _______ (be) a beautiful city.',
          type: 'input',
          correct: 'is'
        },*/
        /*{
          title: 'Open the brackets and put the verbs in the correct form.<br><br>Rob loves snow but Tim _______ (not like) it.',
          type: 'input',
          correct: [
            'doesn’t like',
            'doesn\'t like',
            'doesn`t like',
            'doesnt like'
          ]
        },*/
        /*{
          title: 'Complete this message left on the Internet by Ann Brown. Write ONE word for each space.<br>My name is Ann Brown and I am fourteen years old. I live in the center (1) _______ Toronto, Canada. When I grow up, I want to (2) _______ an actress. My father (3) _______ not think this is a good idea! He is a doctor and he wants (4) _______ to study medicine at university. But I know I won’t like medicine. I have Science lessons at school and (5) _______ are very boring. I (6) _______ like to travel all around the world. I (7) _______ only been to Europe once. We usually spend our holidays in Canada but (8) _______ year we may visit Australia. Please (9) _______ an email to tell me (10) _______ your life.',
          type: 'input-big',
          correct: [
            'of',
            ['become', 'be'],
            'does',
            'me',
            'they',
            ['would', '`d', ' `d', '\'d', ' \'d', 'd', ' d'],
            'have',
            'this',
            ['write', 'send'],
            'about'
          ]
        },*/
      ]
    },
  ],
  progress: {
    test: 0,
    answers: [],
    question: 0
  },
  move: function(questionId) {
    // prepare
    if(typeof(questionId) == 'undefined') questionId = 0;
    if(questionId < 0 || questionId >= IndexTest.test[IndexTest.progress.test].questions.length) questionId = IndexTest.test[IndexTest.progress.test].questions.length - 1;
    // direction
    var direction = -1;
    if(questionId < IndexTest.progress.question) direction = 1;
    // chnage test title
    var testTitle = IndexTest.test[IndexTest.progress.test].title;
    $('.window-container-title2').css('visibility', 'visible');
    $('#test-title').text(testTitle);
    // button
    $('#test-title').css({'text-align': 'left'});
    $('#btn-test').css({'display': 'inline-block'});
    // get question
    var question = IndexTest.test[IndexTest.progress.test].questions[questionId];
    // change test iterator
    $('#control-test-stage-container').html('<span>' + String(Number(questionId + 1)) + '</span> вопрос из ' + String(Number(IndexTest.test[IndexTest.progress.test].questions.length)));
    // move containers
    var containers = $('#control-test').find('.window-container-textOverflow-elem');
    for(let i = 0; i < IndexTest.test[IndexTest.progress.test].questions.length + 2; i++) {
      $($('#control-test').find('.window-container-textOverflow-elem')[i]).css('transform', 'translate(' + (direction * 100 * (questionId + 1)) + '%, 0px)');
    }
    // button
    if(questionId == IndexTest.test[IndexTest.progress.test].questions.length - 1) {
      $('#btn-test').val('Завершить тест');
    }
    else {
      $('#btn-test').val('Следующий вопрос');
    }
    // opacity
    setTimeout(function(){
      $($('#control-test').find('.window-container-textOverflow-elem')[questionId]).css({
        'opacity':'0'
      })
    }, 150)
    // container size
    if(IndexTest.test[IndexTest.progress.test].questions[questionId].type == 'input-big') {
      if(document.documentElement.clientWidth >= 641){
        $('#control-test').css({
          'width':'700px'
        })
        $('#control-test').find('.window-container-textOverflow-elem').css({
          'overflow':'auto'
        })
      } else{
        $('#control-test').css({
          'width':'90vw'
        })
        $('#control-test').find('.window-container-textOverflow-elem').css({
          'overflow':'auto'
        })
      }
    }
    else {
      if(document.documentElement.clientWidth >= 641){
        $('#control-test').css({
          'width':'400px'
        })
        $('#control-test').find('.window-container-textOverflow-elem').css({
          'overflow':'hidden'
        })
      } else{
        $('#control-test').css({
          'width':'90vw'
        })
        $('#control-test').find('.window-container-textOverflow-elem').css({
          'overflow':'hidden'
        })
      }
    }
    // add
    if(questionId > 0) {
      IndexTest.progress.question -= direction;
    }
  },
  open: function(testId) {
    // reset parameters
    if(typeof(testId) == 'undefined') testId = 0;
    if(testId < 0 || testId >= IndexTest.test.length) testId = IndexTest.test.length - 1;
    IndexTest.progress.test = testId;
    IndexTest.progress.answers = [];
    IndexTest.progress.question = 0;
    // initialize test
    var test = IndexTest.test[testId];
    for(let i = 0; i < test.questions.length; i++) {
      var question = test.questions[i];
      if(question.type == 'choose') {
        let output = '';
        // add question
        output += '<div class="window-container-textOverflow-elem">\n';
        // add title
        output += '<div class="window-container-textOverflow-elem-text" style="font-family: pfr; margin-bottom: 20px;">\n';
        output += question.title + '\n';
        output += '</div>\n';
        output += '\n';
        // add answers
        for(let j = 0; j < question.answers.length; j++) {
          // get data
          var elemId = 'id-test'+testId+'-q'+i+'-ch'+j;
          var answer = question.answers[j];
          // add answer
          output += '<label class="checkbox" for="'+elemId+'" style="margin-bottom: 20px; display: block;">\n';
          output += '<input type="radio" name="name-test-'+testId+'-q'+i+'" class="checkbox-checked" id="'+elemId+'" style="display: none;" onclick="IndexTest.progress.answers['+i+'] = '+j+';">\n';
          output += '<label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="'+elemId+'">\n';
          output += '<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="'+elemId+'"></label>\n';
          output += '</label>\n';
          output += '<label class="checkbox-text" style="min-height: initial; padding-left: initial;" for="'+elemId+'">'+answer+'</label>\n';
          output += '</label>\n';
        }
        // question end
        output += '</div>\n';
        $('.window-container-textOverflow').append(output);
      }
      if(question.type == 'input') {
        let output = '';
        // add question
        output += '<div class="window-container-textOverflow-elem">\n';
        // add title
        output += '<div class="window-container-textOverflow-elem-text" style="font-family: pfr; margin-bottom: 20px;">\n';
        output += question.title + '\n';
        output += '</div>\n';
        output += '\n';
        // add input
        var elemId = 'id-test'+testId+'-q'+i+'-input';
        output += '<div style="width: calc(100% - 0px);">\n';
        output += '<label class="input" for="'+elemId+'">\n';
        output += '<div class="input-div">\n';
        output += '<label for="'+elemId+'" class="input-icons icons-check"></label>\n';
        output += '<input type="text" class="input-input" required="" id="'+elemId+'">\n';
        output += '<label for="'+elemId+'" class="input-placeholder">Ответ</label>\n';
        output += '<label for="'+elemId+'" class="input-line">\n';
        output += '<label for="'+elemId+'" class="input-line-main0"></label>\n';
        output += '<label for="'+elemId+'" class="input-line-main"></label>\n';
        output += '<label for="'+elemId+'" class="input-line-main2"></label>\n';
        output += '</label>\n';
        output += '</div>\n';
        output += '</label>\n';
        output += '</div>\n';
        // question end
        output += '</div>\n';
        $('.window-container-textOverflow').append(output);
      }
      if(question.type == 'input-big') {
        let output = '';
        // add question
        output += '<div class="window-container-textOverflow-elem" style="height: 359px;">\n';
        // add title
        output += '<div class="window-container-textOverflow-elem-text" style="font-family: pfr; margin-bottom: 20px;">\n';
        output += question.title + '\n';
        output += '</div>\n';
        output += '\n';
        // add inputs
        for(let j = 0; j < question.correct.length; j++) {
          //break;
          var elemId = 'id-test'+testId+'-q'+i+'-input' + j;
          output += '<div style="width: calc(100% - 0px);">\n';
          output += '<label class="input" for="'+elemId+'">\n';
          output += '<div class="input-div">\n';
          output += '<label for="'+elemId+'" class="input-icons icons-check"></label>\n';
          output += '<input type="text" class="input-input" required="" id="'+elemId+'">\n';
          output += '<label for="'+elemId+'" class="input-placeholder">Ответ '+String(Number(j + 1))+'</label>\n';
          output += '<label for="'+elemId+'" class="input-line">\n';
          output += '<label for="'+elemId+'" class="input-line-main0"></label>\n';
          output += '<label for="'+elemId+'" class="input-line-main"></label>\n';
          output += '<label for="'+elemId+'" class="input-line-main2"></label>\n';
          output += '</label>\n';
          output += '</div>\n';
          output += '</label>\n';
          output += '</div>\n';
        }
        // question end
        output += '</div>\n';
        $('.window-container-textOverflow').append(output);
      }
    }
    // finally
    var output = '';
    output += '<div class="window-container-textOverflow-elem">\n';
    output += '<div class="window-container-textOverflow-elem-ico" style="height: 161px; margin-bottom: 25px;"></div>\n';
    output += '<div class="window-container-textOverflow-elem-text2" id="id-test'+testId+'-results0">вапвап Ваш уровень английского языка на высоком уровне! вапвап</div>\n';
    output += '<br>\n';
    output += '<div class="window-container-textOverflow-elem-text3" style="font-size: 20px;" id="id-test'+testId+'-results1">gfdgfgg набрали <b>dfdsf</b> баллов из sdfdsf</div>\n';
    output += '<br>\n';
    output += '</div>\n';
    $('.window-container-textOverflow').append(output);
    // run
    IndexTest.move(0);
    $('#control-test').find('.input-input').attr('tabindex','-1');
    $('#control-test').find('.checkbox-checked').attr('tabindex','-1');
  },
  next: function() {
    // set answers
    var question = IndexTest.test[IndexTest.progress.test].questions[IndexTest.progress.question];
    if(question.type == 'input') {
      IndexTest.progress.answers[IndexTest.progress.question] = $('#id-test'+IndexTest.progress.test+'-q'+IndexTest.progress.question+'-input').val();
    }
    if(question.type == 'input-big') {
      var myAnswers = [];
      for(let j = 0; j < IndexTest.test[IndexTest.progress.test].questions[IndexTest.progress.question].correct.length; j++) {
        myAnswers[j] = $('#id-test'+IndexTest.progress.test+'-q'+IndexTest.progress.question+'-input'+String(j)).val();
      }
      IndexTest.progress.answers[IndexTest.progress.question] = myAnswers;
    }
    // last ?
    if(IndexTest.progress.question >= (IndexTest.test[IndexTest.progress.test].questions.length - 1)) {
      // finish
      IndexTest.finish();
    }
    else {
      // next
      IndexTest.move(IndexTest.progress.question + 1);
    }
  },
  finish: function() {
    // calc score
    var score = 0;
    for(let i = 0; i < IndexTest.test[IndexTest.progress.test].questions.length; i++) {
      var question = IndexTest.test[IndexTest.progress.test].questions[i];
      if(question.type == 'choose') {
        if(IndexTest.progress.answers[i] == question.correct) score += IndexTest.test[IndexTest.progress.test].scoretable[i];
      }
      if(question.type == 'input') {
        var myAnswer = IndexTest.progress.answers[i];
        var correctAnswers = [];
        var cost = IndexTest.test[IndexTest.progress.test].scoretable[i];
        if(typeof(question.correct) == 'string') { correctAnswers = [question.correct]; }
        else { correctAnswers = question.correct; }
        for(let j = 0; j < correctAnswers.length; j++) {
          if(myAnswer.toLowerCase() == correctAnswers[j].toLowerCase()) {
            score += cost;
            break;
          }
        }
      }
      if(question.type == 'input-big') {
        var myAnswers = IndexTest.progress.answers[i];
        var correctAnswers = question.correct;
        var cost = IndexTest.test[IndexTest.progress.test].scoretable[i];
        for(let j = 0; j < myAnswers.length; j++) {
          var myAnswer = myAnswers[j];
          var correctAnswers2 = [];
          if(typeof(correctAnswers[j]) == 'string') { correctAnswers2 = [correctAnswers[j]]; }
          else { correctAnswers2 = correctAnswers[j]; }
          for(let t = 0; t < correctAnswers2.length; t++) {
            if(myAnswer.toLowerCase() == correctAnswers2[t].toLowerCase()) {
              score += cost;
              break;
            }
          }
        }
      }
    }
    // output level
    var level = IndexTest.test[IndexTest.progress.test].resulttable[0][1];
    for(let i = 0; i < IndexTest.test[IndexTest.progress.test].resulttable.length; i++) {
      level = IndexTest.test[IndexTest.progress.test].resulttable[i][1];
      if(score <= IndexTest.test[IndexTest.progress.test].resulttable[i][0]) break;
    }
    $('#id-test'+IndexTest.progress.test+'-results0').html('Ваш уровень английского языка:<br>' + level);
    // output score
    var msg = '';
    if(typeof(userData) != 'undefined' && typeof(userData.name1) != 'undefined') {
      msg = userData.name1 + ', ';
    }
    var maximum = 0;
    for(let i = 0; i < IndexTest.test[IndexTest.progress.test].scoretable.length; i++) {
      maximum += IndexTest.test[IndexTest.progress.test].scoretable[i];
    }
    msg += 'Вы набрали ' + score + ' ' + declOfNumber(score, 'балл') + ' из ' + maximum;
    $('#id-test'+IndexTest.progress.test+'-results1').text(msg);
    // change elements
    $('#control-test').find('.window-container-title2').html('<span id="control-test-stage"></span>Тест завершен!');
    $('#control-test').find('.window-btn').val('Записаться');
    $('#control-test').find('.window-btn').attr('onclick', '');
    $('#control-test').find('.window-btn').css({'width': '100%'});
    $('#control-test').find('.window-container-title').css({'text-align': 'center', 'max-width': '100%'});
    $('#control-test').find('.window-container-title2').css({'text-align': 'center', 'max-width': '100%'});
    // move containers
    var containers = $('#control-test').find('.window-container-textOverflow-elem');
    for(let i = 0; i < IndexTest.test[IndexTest.progress.test].questions.length + 2; i++) {
      $($('#control-test').find('.window-container-textOverflow-elem')[i]).css('transform', 'translate(' + (-100 * (IndexTest.test[IndexTest.progress.test].questions.length + 1)) + '%, 0px)');
    }
  },
  init: function() {
    $('.window-container-textOverflow').empty();
    var output = '';
    output += '<div class="window-container-textOverflow-elem">\n';
    output += '<div class="window-container-textOverflow-elem-ico" style="height: 161px; background-image: url(&quot;media/svg/analyze.svg&quot;)"></div>\n';
    output += '<div class="window-container-textOverflow-elem-text2"></div>\n';
    output += '<br>\n';
    output += '<div class="window-container-textOverflow-elem-text3">Выберите уровень сложности!</div>\n';
    output += '<br>\n';
    for(let i = 0; i < IndexTest.test.length; i++) {
      output += '<input type="button" style="left: 0; right: 0; margin: auto; display: block; margin-bottom: 10px; width: 100%;" class="window-btn" onclick="IndexTest.open(' + i + ')" value="' + IndexTest.test[i].title + '">\n';
    }
    output += '</div>\n';
    $('.window-container-textOverflow').append(output);
  }
};
