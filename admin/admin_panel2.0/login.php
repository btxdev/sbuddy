<?php

  $sess_name = 'INSOAdminPanel';
  $sess_v = '2';

  session_name($sess_name);
  session_start();

  if(strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')!==false || strpos($_SERVER['HTTP_USER_AGENT'],'rv:11.0')!==false){
    header('location: badbrowser.php');
  }

  // create guest session
  if(!isset($_SESSION) || !isset($_SESSION['auth'])) {
    $_SESSION['auth'] = false;
  }

  // arguments to send next
  $get_args = Array();

  if(isset($_GET['mobileApp']) && ($_GET['mobileApp'] == 'true')) {
    $get_args['mobileApp'] = true;
  }

  // check authorization
  if($_SESSION['auth']) {
    // compose header
    $the_header = 'Location: ./';
    if($get_args['mobileApp']) $the_header = $the_header.'?mobileApp=true';
    header($the_header);
    exit();
  }

?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login Admin panel</title>
    <link rel="shortcut icon" href="media/img/logo.png" type="image/png">
    <link rel="stylesheet" href="media/fonts/fonts.css">
    <link rel="stylesheet" href="style/login.css">
    <link rel="stylesheet" href="style/circle.css">
    <link rel="stylesheet" href="style/preloader.css">
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <script type="text/javascript" src="js/jquery.maskedinput.js"></script>
    <script type="text/javascript" src="js/login.js"></script>
    <?php if (@$_COOKIE["theme"] == 'black'): ?>
      <script>
        $('html').get(0).style.setProperty('--main-bg','#1a1a1a')
      </script>
    <?php endif; ?>
    <script>var get_args = [<?php implode(',', array_keys($get_args)); ?>];</script>
  </head>
  <body>
    <svg style='position: absolute; visibility: hidden;'>
      <defs>
        <linearGradient
           id="linearGradient2344">
          <stop
             style="stop-color:#0021ca;stop-opacity:1"
             offset="0"
             id="stop2340" />
          <stop
             style="stop-color:#6c82ff;stop-opacity:1"
             offset="1"
             id="stop2342" />
        </linearGradient>
        <linearGradient
           id="linearGradient2321">
          <stop
             id="stop2317"
             offset="0"
             style="stop-color:#0020be;stop-opacity:1" />
          <stop
             id="stop2319"
             offset="1"
             style="stop-color:#5d78ff;stop-opacity:1" />
        </linearGradient>
        <marker>
          <path />
        </marker>
        <marker>
          <path />
        </marker>
        <marker
           style="overflow:visible"
           id="marker1574"
           refX="0"
           refY="0"
           orient="auto">
          <path
             transform="matrix(0.8,0,0,0.8,10,0)"
             style="fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:1.00000003pt;stroke-opacity:1"
             d="M 0,0 5,-5 -12.5,0 5,5 Z"
             id="path1572" />
        </marker>
        <marker
           style="overflow:visible"
           id="marker1435"
           refX="0"
           refY="0"
           orient="auto">
          <path
             transform="matrix(0.2,0,0,0.2,1.2,0)"
             style="fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:1.00000003pt;stroke-opacity:1"
             d="M 0,0 5,-5 -12.5,0 5,5 Z"
             id="path1433" />
        </marker>
        <marker
           style="overflow:visible"
           id="Arrow1Sstart"
           refX="0"
           refY="0"
           orient="auto">
          <path
             transform="matrix(0.2,0,0,0.2,1.2,0)"
             style="fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:1.00000003pt;stroke-opacity:1"
             d="M 0,0 5,-5 -12.5,0 5,5 Z"
             id="path1012" />
        </marker>
        <marker
           orient="auto"
           refY="0"
           refX="0"
           id="DistanceStart"
           style="overflow:visible">
          <g
             style="fill:#000000;fill-opacity:1;stroke:#000000;stroke-opacity:1"
             id="g2300">
            <path
               id="path2306"
               d="M 0,0 H 2"
               style="fill:#000000;fill-opacity:1;stroke:#000000;stroke-width:1.14999998;stroke-linecap:square;stroke-opacity:1" />
            <path
               id="path2302"
               d="M 0,0 13,4 9,0 13,-4 Z"
               style="fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-opacity:1" />
            <path
               id="path2304"
               d="M 0,-4 V 40"
               style="fill:#000000;fill-opacity:1;stroke:#000000;stroke-width:1;stroke-linecap:square;stroke-opacity:1" />
          </g>
        </marker>
        <marker
           style="overflow:visible"
           id="Arrow1Lstart"
           refX="0"
           refY="0"
           orient="auto">
          <path
             transform="matrix(0.8,0,0,0.8,10,0)"
             style="fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:1.00000003pt;stroke-opacity:1"
             d="M 0,0 5,-5 -12.5,0 5,5 Z"
             id="path1000" />
        </marker>
        <linearGradient
           gradientUnits="userSpaceOnUse"
           y2="185.28906"
           x2="443.60504"
           y1="284.28906"
           x1="342.60547"
           id="linearGradient2323"
           xlink:href="#linearGradient2321" />
        <linearGradient
           gradientUnits="userSpaceOnUse"
           y2="591.8457"
           x2="339.54691"
           y1="490.8457"
           x1="440.59131"
           id="linearGradient2331"
           xlink:href="#linearGradient2344" />
        <linearGradient
           y2="591.8457"
           x2="339.54691"
           y1="490.8457"
           x1="440.59131"
           gradientUnits="userSpaceOnUse"
           id="linearGradient2352"
           xlink:href="#linearGradient2344" />
        <linearGradient
           y2="185.28906"
           x2="443.60504"
           y1="284.28906"
           x1="342.60547"
           gradientUnits="userSpaceOnUse"
           id="linearGradient2354"
           xlink:href="#linearGradient2321" />
      </defs>
    </svg>
    <div class='main'>
      <div class='main-second'>
        <div class='main-second-nav'>
          <div class='main-second-logo'>
            <svg
                   xmlns:dc="http://purl.org/dc/elements/1.1/"
                   xmlns:cc="http://creativecommons.org/ns#"
                   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                   xmlns:svg="http://www.w3.org/2000/svg"
                   xmlns="http://www.w3.org/2000/svg"
                   xmlns:xlink="http://www.w3.org/1999/xlink"
                   version="0.0"
                   viewBox="0 0 124.10456 124.10457"
                   height="124.10457mm"
                   width="124.10457mm">
                  <g
                     transform="translate(-61.077338,-72.896314)"
                     id="layer1">
                    <g
                       transform="translate(2.1166666)"
                       id="g2350">
                      <path
                         style="opacity:1;fill:#5d78ff;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:36.84435654;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:normal"
                         d="M 309.60547,156.03906 172.04688,293.5957 c -10e-4,10e-4 -0.003,0.003 -0.004,0.004 -1.00247,1.00304 -1.88069,2.08406 -2.63281,3.2207 -0.75307,1.13817 -1.38074,2.33254 -1.88282,3.56641 -0.50203,1.23383 -0.87794,2.5056 -1.1289,3.79687 -0.25096,1.29132 -0.37696,2.60168 -0.37696,3.91211 0,1.31044 0.126,2.62084 0.37696,3.91211 0.25103,1.29132 0.62687,2.56497 1.1289,3.79883 0.50208,1.23387 1.12975,2.42828 1.88282,3.56641 0.7531,1.13813 1.63261,2.21855 2.63672,3.22265 l 144,144 152.05859,-152.05664 z"
                         transform="matrix(0.26458333,0,0,0.26458333,17.150846,31.610984)"
                         id="rect2160" />
                      <path
                         style="opacity:1;fill:#6c84ff;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:36.84435654;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:normal"
                         d="m 468.10547,314.53906 -14.5,14.5 -123.05859,123.0586 -14.5,14.49804 14.5,14.5 129.5,129.5 14.5,14.5 14.5,-14.5 123.05859,-123.05859 c 1.00367,-1.00382 1.88194,-2.08298 2.63476,-3.2207 0.7531,-1.13814 1.38075,-2.3345 1.88282,-3.56836 0.50206,-1.23386 0.87982,-2.50559 1.13086,-3.79688 0.25103,-1.29129 0.375,-2.60167 0.375,-3.91211 0,-1.31044 -0.12397,-2.62081 -0.375,-3.91211 -0.25104,-1.29129 -0.6288,-2.56301 -1.13086,-3.79687 -0.50207,-1.23386 -1.12972,-2.43022 -1.88282,-3.56836 -0.75309,-1.13814 -1.63064,-2.21853 -2.63476,-3.22266 l -129.5,-129.5 z"
                         transform="matrix(0.26458333,0,0,0.26458333,17.150846,31.610984)"
                         id="path2236" />
                      <path
                         style="opacity:1;fill:url(#linearGradient2352);fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:58.33576965;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:normal"
                         d="M 316.19531,466.5957 175.70312,610.70898 c -0.68324,0.76225 -1.30013,1.56235 -1.8496,2.39258 -0.75311,1.13817 -1.38071,2.33254 -1.88282,3.56641 -0.55724,1.44139 -0.47308,2.715 0.0723,3.94726 0.61393,1.38721 1.45142,1.88081 2.67578,2.40235 1.22748,0.51745 2.51425,0.91928 3.85156,1.1914 1.05357,0.21457 2.13895,0.34559 3.2461,0.39258 l 292.73047,0.49414 z"
                         transform="matrix(0.26458333,0,0,0.26458333,17.150846,31.610984)"
                         id="path2177" />
                      <path
                         style="opacity:1;fill:url(#linearGradient2354);fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:58.33576965;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:normal"
                         d="m 309.60547,156.03906 158.35156,158.5 140.70313,-144.33008 c 0.60419,-0.69397 1.15889,-1.41551 1.65429,-2.16406 0.75311,-1.13815 1.38071,-2.33252 1.88282,-3.5664 0.55665,-1.44016 0.53378,-2.54264 -0.01,-3.77344 -0.61295,-1.38797 -1.51387,-2.05462 -2.73828,-2.57617 -1.22748,-0.51746 -2.51425,-0.91917 -3.85156,-1.19141 -1.22813,-0.25002 -2.49978,-0.3831 -3.79883,-0.4043 z"
                         transform="matrix(0.26458333,0,0,0.26458333,17.150846,31.610984)"
                         id="path2177-8" />
                    </g>
                  </g>
                </svg>
            <div class='logo-title'><hb><a href='http://swiftly.insoweb.ru' target="_blank">Swiftly</a></hb><br>admin panel</div>
          </div>
          <div class='menu' title='Открыть' onclick="menu(this)">
            <div class='menu-line' id='menu-line-1'></div>
            <div class='menu-line' id='menu-line-2'></div>
            <div class='menu-line' id='menu-line-3'></div>
            <div class='interest'>
              <div class="c100 p0 small" style='font-size: 45px;'>
                <div class="slice">
                    <div class="bar"></div>
                    <div class="fill"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class='main-second-info' style="transform: translate(0px, 0%);">
          <div class='main-second-info-img'></div>
          <div class='main-second-info-text'>Управляем вместе!</div>
          <a href='https://insoweb.ru' target="_blank" class='main-second-info-a' style='margin-left: 10px;'>INSOweb</a>
          <a href='doc/Privacy policy.php' target="_blank" class='main-second-info-a'>Условия конфиденциальности</a>
        </div>
        <div class='main-second-info-2' style="transform: translate(0px, 0%);">

          <div class='main-second-info-2-block'>
            <div class='main-second-info-2-block-conteiner'>
              <div class='main-second-info-2-block-circle'>1</div>
              <div class='main-second-info-2-block-title'>Работа в команде</div>
              <div class='main-second-info-2-block-text'>Swiftly admin panel разрешает создавать неограниченное количество пользователей для совместной работы с разными задачами.</div>
            </div>
            <div class='main-second-info-2-block-conteiner-2'>
              <div class='main-second-info-2-block-conteiner-2-img1'></div>
            </div>
          </div>

          <div class='main-second-info-2-block'>
            <div class='main-second-info-2-block-conteiner'>
              <div class='main-second-info-2-block-circle'>2</div>
              <div class='main-second-info-2-block-title'>Работайте 24/7</div>
              <div class='main-second-info-2-block-text'>Продолжайте работать над проектом из любой точки мира в удобное для вас время.</div>
            </div>
            <div class='main-second-info-2-block-conteiner-2'>
              <div class='main-second-info-2-block-conteiner-2-img2'></div>
            </div>
          </div>

          <div class='main-second-info-2-block'>
            <div class='main-second-info-2-block-conteiner'>
              <div class='main-second-info-2-block-circle'>3</div>
              <div class='main-second-info-2-block-title'>Добавляйте партнёров</div>
              <div class='main-second-info-2-block-text'>Добавляйте модераторов, администраторов и редакторов для скорейшего завершения проекта.</div>
            </div>
            <div class='main-second-info-2-block-conteiner-2'>
              <div class='main-second-info-2-block-conteiner-2-img3'></div>
            </div>
          </div>

          <div class='main-second-info-2-block'>
            <div class='main-second-info-2-block-conteiner'>
              <div class='main-second-info-2-block-circle'>4</div>
              <div class='main-second-info-2-block-title'>Постоянная поддержка</div>
              <div class='main-second-info-2-block-text'>Появились вопросы? Пишите! Мы на связи и готовы помочь вам с вашими трудностями!</div>
            </div>
            <div class='main-second-info-2-block-conteiner-2'>
              <div class='main-second-info-2-block-conteiner-2-img4'></div>
            </div>
          </div>

          <div class='main-second-info-2-block'>
            <div class='main-second-info-2-block-conteiner'>
              <div class='main-second-info-2-block-circle'>5</div>
              <div class='main-second-info-2-block-title'>Регулярные обновления</div>
              <div class='main-second-info-2-block-text'>Swiftly admin panel постоянно совершенствуется и обновляется для вашей качественной работы.</div>
            </div>
            <div class='main-second-info-2-block-conteiner-2'>
              <div class='main-second-info-2-block-conteiner-2-img5'></div>
            </div>
          </div>

          <a href='https://insoweb.ru' target="_blank" class='main-second-info-a' style='margin-left: 10px;'>INSOweb</a>
          <a href='doc/Privacy policy.php' target="_blank" class='main-second-info-a'>Условия конфиденциальности</a>

        </div>
      </div>
      <div class='main-first'>
        <div class='main-first-preloader'>
          <div class='main-first-preloader-block'>
            <div class='main-first-preloader-block-ico'>
              <div class="loader-update">
                <svg class="circular" viewBox="25 25 50 50">
                  <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="5" stroke-miterlimit="10"></circle>
                </svg>
              </div>
            </div>
            <div class='main-first-preloader-block-text'>Загрузка...</div>
          </div>
        </div>
        <div class='main-first-recovery'>

          <div class='main-first-login-title'>Восстановление доступа</div>

          <div id='recovery-stage1-block' class='main-first-recovery-block' style='transform: translate(00%, 0px); opacity: 1;'>

            <div class='main-first-recovery-text'>Для восстановления доступа введите свой логин или почту</div>

            <div class='input-login'>
              <input id='recovery-login-field' required='required' type='text'>
              <label for='recovery-login-field' class='placeholder'>Логин или почта</label>
            </div>

            <div id='recovery-code-div' class='input-login' id='key' style='opacity: 0;'>
              <input id='recovery-code-field' required='required' type='key'>
              <label for='recovery-code-field' class='placeholder'>Код</label>
            </div>

          </div>

          <div id='recovery-stage2-block' class='main-first-recovery-block' style='transform: translate(00%, 0px); opacity: 0;'>

            <div class='main-first-recovery-text'>Для восстановления доступа введите новый пароль</div>

            <div class='input-login'>
              <input id='password-recovery' required='required' type='password'>
              <label for='password-recovery' class='placeholder'>Пароль</label>
              <label for='password-recovery' class='eye icon-eye' onclick='password_open(this)' title='Показать пароль'>
                <div class='eye-not'></div>
              </label>
            </div>

            <div class='input-login'>
              <input id='password-recovery-2' required='required' type='password'>
              <label for='password-recovery-2' class='placeholder'>Пароль</label>
              <label for='password-recovery-2' class='eye icon-eye' onclick='password_open(this)' title='Показать пароль'>
                <div class='eye-not'></div>
              </label>
            </div>

          </div>

          <div id='recovery-error-message' class='block-error' style="opacity: 0;">
            <div class='block-error-img icon-error'></div>
            <div class='block-error-text'>
              <div class='block-error-text-title'>Ошибка</div>
              <div class='block-error-text-text'>Таким образом консультация с широким активом представляет собой интересный эксперимент</div>
            </div>
          </div>

          <div id='recovery-code-btn' class='main-first-btn'>Отправить</div>
          <div id='recovery-code-back' class='main-first-btn-2' onclick="back()">Назад</div>

        </div>
        <div class='main-first-login'>

          <div class='main-first-login-title'>Авторизация</div>

          <div class='input-login'>
            <input id='login-login-field' required='required' type='text'>
            <label for='login-login-field' class='placeholder'>Логин</label>
          </div>

          <div class='input-login'>
            <input id='password1' required='required' type='password'>
            <label for='password1' class='placeholder'>Пароль</label>
            <label for='password1' class='eye icon-eye' title='Показать пароль' onclick='password_open(this)'>
              <div class='eye-not'></div>
            </label>
          </div>

          <div id='recovery-open-btn' class='main-first-login-recovery' onclick="recovery()">Забыли пароль?</div>

          <div id='login-error-message' class='block-error' style='opacity: 0;'>
            <div class='block-error-img icon-error'></div>
            <div class='block-error-text'>
              <div class='block-error-text-title'>Ошибка</div>
              <div class='block-error-text-text'>Таким образом консультация с широким активом представляет собой интересный эксперимент</div>
            </div>
          </div>

          <div id='login-form-btn' class='main-first-btn'>Войти</div>
          <div class='main-first-btn-2' onclick="register()" style='right: 27px;'>Регистрация</div>

        </div>
        <div class='main-first-register'>

          <div class='main-first-login-title'>Регистрация</div>

          <div class='input-login'>
            <input id='register-login-field' required='required' type='text'>
            <label fpr='register-login-field' class='placeholder'>Логин</label>
          </div>

          <div class='input-login'>
            <input id='password2' required='required' type='password'>
            <label for='password2' class='placeholder'>Пароль</label>
            <label for='password2' class='eye icon-eye' title='Показать пароль' onclick='password_open(this)'>
              <div class='eye-not'></div>
            </label>
          </div>

          <div class='input-login'>
            <input id='password3' required='required' type='password'>
            <label for='password3' class='placeholder'>Пароль</label>
            <label for='password3' class='eye icon-eye' title='Показать пароль' onclick='password_open(this)'>
              <div class='eye-not'></div>
            </label>
          </div>

          <div class='input-login'>
            <input id='register-name-field' required='required' type='text'>
            <label for='register-name-field' class='placeholder'>Имя</label>
          </div>

          <div class='input-login'>
            <input id='register-email-field' required='required' type='text'>
            <label for='register-email-field' class='placeholder'>Почта</label>
          </div>

          <div class='input-login'>
            <input id='register-phone-field' required='required' type='tel'>
            <label for='register-phone-field' class='placeholder'>Телефон</label>
          </div>

          <div class='checkbox-login'>
            <input type='checkbox' id='chb1' style='display: none;'>
            <label for='chb1' class='checkbox-login-chb1'></label>
            <label for='chb1' class='checkbox-login-chb2'>
              <div>Согласен с <a href='doc/Terms of use.php' target="_blank">пользовательским соглашением</a> и <a href='doc/Privacy policy.php' target="_blank">политикой конфиденциальности</a></div>
            </label>
          </div>

          <div class='checkbox-login'>
            <input type='checkbox' checked id='chb2' style='display: none;'>
            <label for='chb2' class='checkbox-login-chb1'></label>
            <label for='chb2' class='checkbox-login-chb3'>
              <div>Получать новостную рассылку</div>
            </label>
          </div>

          <div id='register-error-message' class='block-error' style='display: none; opacity: 0;'>
            <div class='block-error-img icon-error'></div>
            <div class='block-error-text'>
              <div class='block-error-text-title'>Ошибка</div>
              <div class='block-error-text-text'>Таким образом консультация с широким активом представляет собой интересный эксперимент</div>
            </div>
          </div>

          <div class='main-first-btn-3'>Зарегистрироваться</div>
          <div class='main-first-btn-4' onclick="back()">Назад</div>

        </div>
      </div>
    </div>
  </body>
</html>
