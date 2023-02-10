<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <style>
      body{
        padding: 0;
        margin: 0;
        width: 100%;
        font-family: monospace;
      }
      .main{
        width: calc(100% - 34px);
        padding: 10px;
        border: 2px solid #2a9fd0;
        border-radius: 10px;
        background-color: #e6e8fc6b;
        margin: 5px;
      }
      .main-logo{
        width: 100%;
      }
      .main-logo-ico{
        display: inline-block;
        vertical-align: middle;
        position: relative;
        height: 50px;
        width: 200px;
        margin-left: 15px;
        background-position: center;
        background-repeat: no-repeat;
        background-size: contain;
      }
      .main-text-title{
        font-size: 25px;
        font-family: monospace;
        font-weight: 700;
        margin-left: 15px;
        margin-top: 10px;
      }
      .main-text-text{
        font-family: pfl;
        margin-left: 15px;
        margin-top: 10px;
        text-align: justify;
        margin-right: 15px;
      }
      .main-text-a{
        font-family: monospace;
        font-weight: 100;
        margin-left: 15px;
        margin-top: 10px;
        display: inline-block;
        text-align: justify;
        margin-right: 15px;
        border-bottom: 1px dashed #303036;
        color: #303036;
        text-decoration: none;
      }
      .main-text-a:hover{
        text-decoration: none;
        border-bottom: 1px solid #303036;
      }
      .main-text-auto{
        font-family: monospace;
        font-weight: 100;
        margin-left: 15px;
        margin-top: 10px;
        text-align: justify;
        margin-right: 15px;
        font-style: italic;
      }
      .main-text-password{
        display: inline-block;
        padding: 5px 10px;
        background-color: #2a9fd0;
        margin-left: 15px;
        font-family: monospace;
        font-weight: 100;
        color: #fff;
        border-radius: 5px;
        margin-top: 5px;
      }

    </style>
  </head>
  <body>
    <div class='main'>
      <div class='main-logo'><!-- Сcылку на картинку надо в base64 -->
        <div class='main-logo-ico' style='background-image: url("media/svg/logo.svg")'></div>
      </div>
      <div class='main-text'>
        <div class='main-text-title'>Добро пожаловать!</div>
        <div class='main-text-text'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
        <br>
        <div class='main-text-text'>Ваш логин и пароль:</div>
        <div class='main-text-password'>Логин: GGWPeeee!</div><br>
        <div class='main-text-password'>Пароль: SinXpgrc</div><br><br>
        <a href='#' target="_blank" class='main-text-a'>Перейти на сайт StudyBuddy</a>
        <br><br>
        <div class='main-text-auto'>Это письмо сформировано автоматически. Пожалуйста, не отвечайте на него.
          <br><br>Если у Вас есть вопросы, Вы можете обратиться по электронной почте: <a class='main-text-a' style='margin-left: 0px;' href='mailto:$form_email'></a>
        </div>
        <div class='main-text-text' style='margin-bottom: 15px; margin-top: 25px;'>
          <span>Дата составления письма: </span>
          <span><b>01.01.1970 в 10:21</b></span>
        </div>
      </div>
    </div>
  </body>
</html>
