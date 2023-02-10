<?php

  $sess_name = 'INSOAdminPanel';
  $sess_v = '2';
  $urlFAQ = '#';
  $browserIE = false;

  session_name($sess_name);
  session_start();

  if(!(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')!==false || strpos($_SERVER['HTTP_USER_AGENT'],'rv:11.0')!==false)){
    //header('location: login.php');
  } else{
    $browserIE = true;
  }

?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Ваш браузер устарел</title>
    <link rel="icon" href="media/img/logo.png" type="image/png" />
    <link rel="shortcut icon" href="media/img/logo.png" type="image/png" />
    <link rel="stylesheet" href="media/fonts/fonts.css">
    <link rel="stylesheet" href="style/badbrowser.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>
  <body>
    <div class='main'>
      <div class='main-ico'>
        <div class='main-ico-logo'></div>
        <div class='main-ico-text'>
          <a href='http://insoweb.ru/swiftly' target="_blank" class='main-ico-text-h1'>Swiftly</a>
          <h1 class='main-ico-text-h2'>admin panel</h1>
        </div>
      </div>
      <div class='main-block'>
        <div class='main-block-ico'></div>
        <div class='main-block-title'>Ваш браузер устарел</div>
        <div class='main-block-text'>
          <?php if($browserIE): ?>
            Почитать, почему мы отказались от поддержки Internet Explorer, можно в
            <a target='_blank' href='<?=$urlFAQ;?>'>FAQ</a>.
            <br>
            <br>
          <?php endif; ?>
          Из-за этого Swiftly может работать медленно и с ошибками. Для быстрой и стабильной работы рекомендуем установить последнюю версию одного из этих браузеров: </div>
        <div class='main-block-browser'>
          <a href='https://www.google.com/chrome/' target="_blank" title='Google Chrome' style='background-image: url("media/svg/chrome.svg");' class='main-block-browser-elem'></a>
          <a href='https://www.opera.com/ru' target="_blank" title='Opera' style='background-image: url("media/svg/opera.svg");' class='main-block-browser-elem'></a>
          <a href='https://www.mozilla.org/ru/' target="_blank" title='Firefox' style='background-image: url("media/svg/Firefox.svg");' class='main-block-browser-elem'></a>
          <a href='https://browser.yandex.ru/' target="_blank" title='Yandex Browser' style='background-image: url("media/svg/Yandex.svg");' class='main-block-browser-elem'></a>
        </div>
      </div>
    </div>
  </body>
</html>
