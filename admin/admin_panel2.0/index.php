<?php

  include_once('db_includes.php');

  $customer_color = '#5d78ff';
  $customer_color2 = '#ffffff';

  session_name($sess_name);
  session_start();

  // get arguments
  $get_args = Array();

  if(isset($_GET['mobileApp']) && ($_GET['mobileApp'] == 'true')) {
    $get_args['mobileApp'] = true;
  }

  // check authorization
  if(!isset($_SESSION) || !isset($_SESSION['auth']) || !$_SESSION['auth']) {
    // compose header
    $the_header = 'Location: login.php';
    if($get_args['mobileApp']) $the_header = $the_header.'?mobileApp=true';
    header($the_header);
    //header('Location: login.php');
    exit();
  }

  // check other session variables
  if(!isset($_SESSION['auth']) || !isset($_SESSION['username']) || !isset($_SESSION['userid']) || !isset($_SESSION['act_token']) || !isset($_SESSION['version'])) {
    $_SESSION = Array();
    session_destroy();
    // compose header
    $the_header = 'Location: login.php';
    if($get_args['mobileApp']) $the_header = $the_header.'?mobileApp=true';
    header($the_header);
    //header('Location: login.php');
    exit();
  }

  // version check
  if($_SESSION['version'] < $sess_v) {
    // HARD
    if(true) {
      $_SESSION = Array();
      session_destroy();
      // compose header
      $the_header = 'Location: login.php';
      if($get_args['mobileApp']) $the_header = $the_header.'?mobileApp=true';
      header($the_header);
      //header('Location: login.php');
      exit();
    }
  }

  // page check
  $pageDis = 'opacity: 0; display: none;';
  $pageBlock = 'opacity: 1; display: block;';
  $page = [['main',$pageDis],
           ['statistics',$pageDis],
           ['timetable',$pageDis],
           ['reviews',$pageDis],
           ['news',$pageDis],
           ['main',$pageDis],
           ['about_company',$pageDis],
           ['all_user',$pageDis],
           ['add_user',$pageDis],
           ['profile',$pageDis],
           ['file_manager',$pageDis],
           ['individual_msg',$pageDis],
           ['general_chat',$pageDis],
           ['support_chat',$pageDis],
           ['global_search',$pageDis]];

  if(isset($_GET['page'])){
    if(iconv_strlen($_GET['page']) > 0){
      $pageActive = $_GET['page'];
      for($i = 0; $i < count($page); $i++){
        if($_GET['page'] == $page[$i][0]){
          $page[$i][1] = $pageBlock;
        }
      }
    } else{
      for($i = 0; $i < count($page); $i++){
        if('main' == $page[$i][0]){
          $page[$i][1] = $pageBlock;
        }
      }
    }
  } else{
    $pageActive = 'main';
    for($i = 0; $i < count($page); $i++){
      if('main' == $page[$i][0]){
        $page[$i][1] = $pageBlock;
      }
    }
  }



  // mobileApp detect
  $mobileApp = false;

  if(isset($_GET['mobileApp'])){
    if($_GET['mobileApp'] == 'true'){
      $mobileApp= true;
    } else{
      $mobileApp= false;
    }
  }

  // == PDO ====================================================================

  // establish connection
  /*$pdo_dsn = "mysql:host=$sql_host;dbname=$sql_db;charset=$sql_charset";
  $pdo_options = Array();
  if($development_state) {
    // development parameters
    $pdo_options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];
  }
  else {
    // release parameters
    $pdo_options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_SILENT,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];
  }

  $pdo = new PDO($pdo_dsn, $sql_user, $sql_password, $pdo_options);*/
  //$pdo_site = new PDO($pdo_dsn, $sql_user, $sql_password, $pdo_options);

  // == NOTICE =================================================================

  /*
  userData['new']           это новый пользователь     логика
  userData['id']            id                         число
  userData['login']         login                      строка
  userData['access']        уровень                    default и т.д.
  userData['name1']         имя                        строка
  userData['name2']         фамилия                    строка
  userData['gender']        пол                        строка
  userData['birthday'],     дата                       строка
  userData['country'],      страна                     строка
  userData['city'],         город                      строка
  userData['phone']         телефон                    строка
  userData['email']         email                      строка
  userData['email2']        reserve email              строка
  userData['phone_verify']  телефон подтвержден        логика
  userData['email_verify']  почта подтверждена         логика
  userData['email2_verify'] р. почта подтверждена      логика
  userData['statistics']    отправлять статистику      логика
  userData['errorlog']      отчеты об ошибках          логика
  userData['mailing']       включена рассылка          логика
  userData['icon']          путь к иконке              media/users/0.jpg
  */

  /*
  области видимости (не используется)
  superuser:

    administrator:
      siteData['title']                       название сайта          строка
      siteData['description']                 краткое описание        строка
      siteData['tags']                        теги                    строка (теги через запятую) массив -> siteData['tags'].split(',') ; explode($siteData['tags'], ',')
      siteData['contacts_city']               контакты: город         строка
      siteData['contacts_street']             контакты: улица         строка
      siteData['contacts_building']           контакты: дом           строка
      siteData['contacts_office']             контакты: офис          строка
      siteData['contacts_postcode']           контакты: индекс        строка
      siteData['contacts_wt_start']           время работы            строка
      siteData['contacts_wt_end']             время работы            строка
      siteData['contacts_phonenumbers']       номера телефонов        строка
      siteData['contacts_emails']             адреса эл. почты        строка
      siteData['contacts_LA']                 юридический адрес       строка
      siteData['contacts_TIN']                ИНН                     строка
      siteData['contacts_COR']                КПП                     строка
      siteData['contacts_PSRN']               ОГРН                    строка

      moderator:

        redactor:
          siteData['formEmail']            email для форм          строка

          default:
            siteData['newYearDesign']      новогодний дизайн       логика
  */

  // finder folders !!! VIRTUAL PATH !!!
  $users_files_path = '/USERS_FILES/';                                          // CHANGE IN db_r_scanner.php AND IN db_finder.php
  $docs_files_path = '/DOCS_FILES/';                                            // CHANGE IN db_r_scanner.php AND IN db_finder.php
  $books_files_path = '/BOOKS_FILES/';                                          // CHANGE IN db_r_scanner.php AND IN db_finder.php

  // get user information
  $userData['id'] = $_SESSION['userid'];
  $userData['login'] = $_SESSION['username'];
  $userData['statistics'] = true;
  $userData['errorlog'] = true;
  $stmt = $pdo->prepare('SELECT * FROM accounts WHERE account = ?');
  $stmt->execute([$_SESSION['username']]);
  while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
    $userData['access'] = isset($row['access_type']) ? $row['access_type'] : 'undefined';
    $userData['name1'] = isset($row['first_name']) ? $row['first_name'] : '';
    $userData['name2'] = isset($row['second_name']) ? $row['second_name'] : '';
    $userData['gender'] = isset($row['gender']) ? $row['gender'] : 'male';
    $userData['birthday'] = isset($row['birthday']) ? $row['birthday'] : '01.01.1970';
    $userData['country'] = isset($row['country']) ? $row['country'] : '';
    $userData['city'] = isset($row['city']) ? $row['city'] : '';
    $userData['phone'] = isset($row['phonenumber']) ? ("+".$row['phonenumber']) : '';
    $userData['email'] = isset($row['email']) ? $row['email'] : '';
    $userData['email2'] = isset($row['email2']) ? $row['email2'] : '';
    $userData['phone_verify'] = isset($row['phone_verify']) ? boolval($row['phone_verify']) : false;
    $userData['email_verify'] = isset($row['email_verify']) ? boolval($row['email_verify']) : false;
    $userData['email2_verify'] = isset($row['email2_verify']) ? boolval($row['email2_verify']) : false;
    $userData['new'] = isset($row['is_new']) ? boolval($row['is_new']) : true;
    $userData['mailing'] = isset($row['mailing']) ? boolval($row['mailing']) : false;
    if(isset($row['profile_icon'])) {
      if(substr($row['profile_icon'], 0, 8) == 'DEFAULT_') {
        $theIcon = substr($row['profile_icon'], 8);
        $userData['icon'] = "media/users/".$theIcon.'.jpg';
      }
      else if($row['profile_icon'] == 'DEF_ADMIN') {
        $userData['icon'] = 'media/users/admin.jpg';
      }
      else {
        $userData['icon'] = "media/users/public/".$_SESSION['username']."/profile.jpg";
      }
    }
    else {
      $userData['icon'] = "media/users/0.jpg";
    }
  }
  $stmt = $pdo->prepare('SELECT `send_statistics`, `send_logs` FROM `account_settings` WHERE `account_id` = ?');
  $stmt->execute([$_SESSION['userid']]);
  while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
    $userData['statistics'] = isset($row['send_statistics']) ? boolval($row['send_statistics']) : true;
    $userData['errorlog'] = isset($row['send_logs']) ? boolval($row['send_logs']) : true;
  }

  // get full site information
  $siteData = Array('title' => '');
  $data = $pdo->query('SELECT * FROM `site_settings`')->fetchAll(PDO::FETCH_UNIQUE);
  foreach($data as $row => $arr) {
    $siteData[strval($row)] = htmlspecialchars($arr['value'], ENT_QUOTES);
  }

  // get registration statistics from site DB
  $reg_stat = Array();
  try {
    $stmt = $pdo_site->prepare("SELECT * FROM accounts");
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      if(isset($row['reg_date'])) {
        $reg_stat[] = $row['reg_date'];
      }
    }
  }
  catch(Exception $e) {
    // shit happens
  }

  if(!(isset($_COOKIE["theme"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('theme', 'white', $time);
  }

  if(!(isset($_COOKIE["sound_msg"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('sound_msg', 'true', $time);
  }

  if(!(isset($_COOKIE["sound_noti"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('sound_noti', 'true', $time);
  }

  $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

  if(!(isset($_COOKIE["language"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('language', $lang, $time);
  }
  if(!(isset($_COOKIE["error_profile_email"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('error_profile_email', 'true', $time);
  }
  if(!(isset($_COOKIE["error_profile_phone"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('error_profile_phone', 'true', $time);
  }
  if(!(isset($_COOKIE["theme_mobile_app"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('theme_mobile_app', 'true', $time);
  }
  if(!(isset($_COOKIE["NewsColorArray"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('NewsColorArray', '000', $time);
  }
  if(!(isset($_COOKIE["timetableJoinEnabled"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('timetableJoinEnabled', 'true', $time);
  }
  if(!(isset($_COOKIE["timetableSortType"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('timetableSortType', 'time', $time);
  }
  if(!(isset($_COOKIE["joinStringTable"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('joinStringTable', 'true', $time);
  }
  if(!(isset($_COOKIE["NewsColorArray_2"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('NewsColorArray_2', '000', $time);
  }
  if(!(isset($_COOKIE["finderImagePreload"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('finderImagePreload', 'false', $time);
  }
  if(!(isset($_COOKIE["development"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('development', 'false', $time);
  }
  if(!(isset($_COOKIE["development_help"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('development_help', 'true', $time);
  }
  if(!(isset($_COOKIE["SpaceLittleWindow"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('SpaceLittleWindow', 'true', $time);
  }
  if(!(isset($_COOKIE["tetris"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('tetris', '0', $time);
  }
  if(!(isset($_COOKIE["fontsSize"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('fontsSize', '3', $time);
  }


  if(@$_COOKIE["language"] == 'ru'){
    $lang = 'ru';
    $langSelectRu = 'selected';
  } else if(@$_COOKIE["language"] == 'ua'){
    $lang = 'ua';
    $langSelectUa = 'selected';
  } else{
    $lang = 'en';
    $langSelectEn = 'selected';
  }

  $ch1 = '';
  $ch5 = '';
  $ch6 = '';
  if(@$_COOKIE["theme"] == 'white'){
    $ch1 = '';
  }
  if (@$_COOKIE["theme"] == 'black') {
    $ch1 = 'checked';
  }

  if(@$_COOKIE["sound_msg"] == 'true'){
    $ch5 = 'checked';
  } else if (@$_COOKIE["sound_msg"] == 'false') {
    $ch5 = '';
  }

  if(@$_COOKIE["sound_noti"] == 'true'){
    $ch6 = 'checked';
  } else if (@$_COOKIE["sound_noti"] == 'false') {
    $ch6 = '';
  }

  $arrayCountry = '';
  $countryArray = file("php/Country.ini");
  foreach($countryArray as $line_num => $line) {
    $text = str_replace("\n", "", htmlspecialchars($line));
    $arrayCountry = $arrayCountry.'<option value="'.$text.'">'.$text.'</option>'."\n";
  }

  $dirIcons = "media/filesICO/svg";
  $dh  = opendir($dirIcons);
  while (false !== readdir($dh)) {
      $files[] = readdir($dh);
  }
  $fileIcons = '';

  for($i = 0; $i < count($files); $i++){
    if($files[$i] != '.' && $files[$i] != '..'){
      if(count($files) - 1 != $i){
        $fileIcons .= "'$dirIcons/".$files[$i]."',";
      } else{
        $fileIcons .= "'$dirIcons/".$files[$i]."'";
      }
    }
  }

  $HistoryConsole = 'null';
  $HistoryConsoleString = '';

  if(!(isset($_COOKIE["HistoryConsole"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('HistoryConsole', $HistoryConsole, $time);
    $HistoryConsoleString = '';
  } else{
    $HistoryConsole = htmlspecialchars_decode($_COOKIE["HistoryConsole"]);
    $HistoryConsole = preg_split("/%8Delimiter8%/", $HistoryConsole);
    $HistoryConsoleString = '';
    for($i = 0; $i < count($HistoryConsole); $i++){
      if($HistoryConsole[$i] != 'null'){
        if(($i + 1) != count($HistoryConsole)){
          $HistoryConsoleString .= '"'.$HistoryConsole[$i].'",';
        } else{
          $HistoryConsoleString .= '"'.$HistoryConsole[$i].'"';
        }
      } else{
        $HistoryConsoleString = '';
      }

    }
  }

  // if($detect->isIE()){
  //   header('Location: test.php');
  // }

  $windowOpen = false;

  if((date('md') < 0131 || date('md') > 1201) && !$siteData['newYearDesign']){
    $windowOpen = true;
  }

?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Admin panel</title>
    <link rel="shortcut icon" href="media/img/logo.png" type="image/png">
    <link rel="stylesheet" href="media/fonts/fonts.css">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/jquery-jvectormap-1.2.2.css">
    <link rel="stylesheet" href="style/preloader.css">
    <link rel="stylesheet" href="style/drag_and_drop.css">
    <link rel="stylesheet" href="style/preloader2.css">
    <link rel="stylesheet" href="style/console.css">
    <link rel="stylesheet" href="style/detectionInactiveUser.css">
    <link rel="stylesheet" href="style/console_style.css">

    <?php if(!($detect->isMobile())):?>
      <link rel="stylesheet" href="style/scrollbar.css">
    <?php endif; ?>
    <link rel="stylesheet" href="style/atom-one-light.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php
      echo('<script>var user_token = "'.$_SESSION['act_token'].'";');
      if( $detect->isAndroidOS() ){
        echo('var isAndroid = true;');
      } else{
        echo('var isAndroid = false;');
      }
      echo '</script>';
    ?>

    <script>
      Date.isLeapYear = function (year) {
        return (((year % 4 === 0) && (year % 100 !== 0)) || (year % 400 === 0));
      };
      Date.getDaysInMonth = function (year, month) {
        return [31, (Date.isLeapYear(year) ? 29 : 28), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31][month];
      };
      Date.prototype.isLeapYear = function () {
        return Date.isLeapYear(this.getFullYear());
      };
      Date.prototype.getDaysInMonth = function () {
        return Date.getDaysInMonth(this.getFullYear(), this.getMonth());
      };
      Date.prototype.addMonths = function (value) {
        var n = this.getDate();
        this.setDate(1);
        this.setMonth(this.getMonth() + value);
        this.setDate(Math.min(n, this.getDaysInMonth()));
        return this;
      };
    </script>

    <script>
      var development_state = <?php if($development_state){echo 'true';}else{echo 'false';};?>;
      var arrayHistoryConsole = [<?=$HistoryConsoleString?>];
      var userData = new Object();
      var siteData = new Object();
      var pageActive = '<?php echo $pageActive;?>';
      var mobileApp = <?php if($mobileApp){echo 'true';}else{echo 'false';};?>;
      var iconsArray = [<?=$fileIcons;?>];
      var typeLearning = [<?php

        for($i = 0; $i < count($typeLearning); $i++){
          if(count($typeLearning) - 1 == $i){
            echo('"'.$typeLearning[$i].'"');
          } else{
            echo('"'.$typeLearning[$i].'", ');
          }
        }

      ?>];
      var Update = {
        timeNow: {
          day: new Date().getDate(),
          month: new Date().getMonth(),
          hours: new Date().getHours(),
          minute: new Date().getMinutes(),
          year: new Date().getFullYear()
        },
      };
      var TimetableSettings = {
        joinEnabled: <?=$_COOKIE["timetableJoinEnabled"];?>,
        sortType: '<?=$_COOKIE["timetableSortType"];?>',
        joinString: <?=$_COOKIE["joinStringTable"];?>,
      }
      var NowConfigPHP = {
        sql_host: '<?=$sql_host;?>',
        sql_db: '<?=$sql_db;?>',
        sql_user: '<?=$sql_user;?>',
        sql_password: '<?=$sql_password;?>',
        sql_charset: '<?=$sql_charset;?>',
        sql_site_host: '<?=$sql_site_host;?>',
        sql_site_db: '<?=$sql_site_db;?>',
        sql_site_user: '<?=$sql_site_user;?>',
        sql_site_password: '<?=$sql_site_password;?>',
        sql_site_charset: '<?=$sql_site_charset;?>',
        serial_Number: '<?=$serialNumber;?>',
        phone_service_works: '<?php if($phone_service_works){echo 'true';}else{echo 'false';};?>',
        account_phonenumbers_limit: '<?=$account_phonenumbers_limit;?>',
        finder_maximum_volume: <?=$maximum_volume;?>,
        account_emails_limit: '<?=$account_emails_limit;?>',
        profile_photos_count: '<?=$profile_photos_count;?>',
        timezone: '<?=$timezone;?>',
        root_relative_path: '<?=$root_relative_path;?>',
        trash_path: '<?=$trash_path;?>',
        users_files_path: '<?=$users_files_path;?>',
        docs_files_path: '<?=$docs_files_path;?>',
        books_files_path: '<?=$books_files_path;?>',
        tmp_files_path: '<?=$tmp_files_path;?>',
      }
      var Config = {
        statistics: <?php if($statisticsPanel){echo 'true';}else{echo 'false';};?>,
        individual_msg: <?php if($individualMsgPanel){echo 'true';}else{echo 'false';};?>,
        chat_msg: <?php if($chatPanel){echo 'true';}else{echo 'false';};?>,
        finder: <?php if($finderPanel){echo 'true';}else{echo 'false';};?>,
        news: <?php if($newsPanel){echo 'true';}else{echo 'false';};?>,
        timetable: <?php if($timeTablePanel){echo 'true';}else{echo 'false';};?>,
        contacts: <?php if($contactsPanel){echo 'true';}else{echo 'false';};?>,
        reviews: <?php if($reviewsPanel){echo 'true';}else{echo 'false';};?>,
        aboutCompany: <?php if($aboutCompanyPanel){echo 'true';}else{echo 'false';};?>,
        users: <?php if($usersPanel){echo 'true';}else{echo 'false';};?>,
        employees: <?php if($employeesPanel){echo 'true';}else{echo 'false';};?>,
        newYearPanel: <?php if($newYearPanel){echo 'true';}else{echo 'false';};?>,
      }

      // === registration statistics ===========================================
      // get registration date list
      var registerStatistics = [<?php $c = count($reg_stat); foreach($reg_stat as $key => $value) {
        echo("'$value'");
        if($key < ($c - 1)) {
          echo(", ");
        }
      } ?>];
      // get date
      var d = new Date();
      var m = d.getMonth() + 1; if(m < 10) m = '0' + String(m);
      var dd = d.getDate(); if(dd < 10) dd = '0' + String(dd);
      var dateTodayStr = String( String(d.getFullYear()) + '-' + m + '-' + String(dd) );
      dateToday = new Date(dateTodayStr);
      var dateLim = dateToday.addMonths(-4);
      dateToday = new Date();
      // add days
      var registerStatisticsStack = [];
      var step = 86400000;
      var max = Math.floor((dateToday.getTime() - dateLim.getTime()) / step);
      for(var i = 0; i < max; i++) {
        var j = (i * step) + dateLim.getTime();
        registerStatisticsStack[j] = 0;
      }
      // group by days
      var todayFounded = false;
      for(var i = 0; i < registerStatistics.length; i++) {
        var dateStr = registerStatistics[i].split(' ')[0];
        var dateUnix = Date.parse(dateStr);
        if(dateUnix == (new Date(dateTodayStr).getTime())) todayFounded = true;
        // limiter
        if(dateUnix < dateLim.getTime()) continue;
        if(typeof(registerStatisticsStack[dateUnix]) == 'undefined') registerStatisticsStack[dateUnix] = 1;
        else registerStatisticsStack[dateUnix]++;
      }
      // graph data
      var registerStatisticsData = [];
      var prev = 0;
      var first = true;
      for(elem in registerStatisticsStack) {
        var date = Number(elem);
        var count = registerStatisticsStack[date];
        // beautify
        if(first) {
          prev = count;
          registerStatisticsData[registerStatisticsData.length] = [date, count];
          first = false;
          continue;
        }
        // draw
        /*if(count != prev) {
          if(count > prev) {
            registerStatisticsData[registerStatisticsData.length] = [date - 86400000, prev];
          }
          registerStatisticsData[registerStatisticsData.length] = [date, count];
        }*/
        registerStatisticsData[registerStatisticsData.length] = [date, count];
        prev = count;
      }
      // today count = 0
      if(!todayFounded) {
        registerStatisticsData[registerStatisticsData.length] = [dateToday.getTime(), 0];
      }
      registerStatisticsData[registerStatisticsData.length] = [dateToday.getTime() + 86400000, 0];

    </script>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.cookie.js"></script>
    <script type="text/javascript" src="js/jquery.maskedinput.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/3.1.60/inputmask/jquery.inputmask.js"></script>
    <script type="text/javascript" src="js/jquery.touchSwipe.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.1.2/dist/confetti.browser.min.js"></script>
    <script type="text/javascript" src="js/jquery-jvectormap-1.2.2.min.js"></script>
    <script type="text/javascript" src="js/jquery-jvectormap-world-mill.js"></script>
    <script type="text/javascript" src="js/standart.js"></script>
    <script type="text/javascript" src="js/preloader.js"></script>
    <script type="text/javascript" src="js/canvasscreenshot.js"></script>
    <script type="text/javascript" src="js/Detection_Inactive_user.js"></script>
    <script type="text/javascript" src="js/console.js"></script>
    <script type="text/javascript" src="js/finder.js"></script>
    <script type="text/javascript" src="js/draganddrop.js"></script>
    <script type="text/javascript" src="js/update.js"></script>
    <script type="text/javascript" src="js/globalchat.js"></script>

    <script type="text/javascript" src="js/apexcharts.js"></script>
    <?php if ($statisticsPanel): ?>
      <script type="text/javascript" src="js/chartsPRO.js"></script>
    <?php else:?>
      <script type="text/javascript" src="js/charts.js"></script>
    <?php endif;?>
    <script type="text/javascript" src="js/tetris.js"></script>
    <script type="text/javascript" src="js/time.js"></script>
    <script type="text/javascript" src="js/experimental.js"></script>

    <script type="text/javascript" src="js/timetable.js"></script>
    <script type="text/javascript" src="js/search.js"></script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.18.1/highlight.min.js"></script>

    <script>hljs.initHighlightingOnLoad();</script>
    <?php if (@$_COOKIE["theme"] == 'black'): ?>
      <script>
        theme_chart = 'dark';
        $('html').get(0).style.setProperty('--color','#fff')
        $('html').get(0).style.setProperty('--colorI','#fff')
        $('html').get(0).style.setProperty('--dark','#121212')
        $('html').get(0).style.setProperty('--menu','#121212')
        $('html').get(0).style.setProperty('--menu-profile','#2b2b2b')
        $('html').get(0).style.setProperty('--main-bg-search','#2b2b2b')
        $('html').get(0).style.setProperty('--main-bg','#1a1a1a')
        $('html').get(0).style.setProperty('--white','#222')
        $('html').get(0).style.setProperty('--border-color','#353535')
        $('html').get(0).style.setProperty('--main-bg-2','#1e1e2d')
        $('html').get(0).style.setProperty('--border-bg','#121212')
        $('html').get(0).style.setProperty('--shadow-name','rgba(43, 43, 43, 1)')
        $('html').get(0).style.setProperty('--menu-status','#434343')
        $('html').get(0).style.setProperty('--bg-color-btn','#333')
        $('html').get(0).style.setProperty('--color-btn-hover','#fff')
        $('html').get(0).style.setProperty('--bg-color-scrollbar','#2b2b2b')
        $('html').get(0).style.setProperty('--noise-bg',"url('../media/img/whitenoise.png')")
      </script>
    <?php endif; ?>
    <?php
      if(isset($_COOKIE['NewsColorArray'])){
        $NewsColorArray11 = $_COOKIE['NewsColorArray'];
        $NewsColorArrayL11Text = '';
        if(!empty($_COOKIE['NewsColorArray'])){
          $NewsColorArray11 = explode('_', $NewsColorArray11);
          if($NewsColorArray11[0] != '000'){
            for($i=0; $i < count($NewsColorArray11); $i++) {
              if($i == 0){
                $NewsColorArrayL11Text = '"#'.$NewsColorArray11[$i].'"';
              } else{
                $NewsColorArrayL11Text = $NewsColorArrayL11Text.', "#'.$NewsColorArray11[$i].'"';
              }
            }
            $NewsColorArrayL11Text = '['.$NewsColorArrayL11Text.']';
            $NewsColorArrayL11Text = '<script>var arrarColor = '.$NewsColorArrayL11Text.'</script>';
            echo($NewsColorArrayL11Text);
          } else{
            $NewsColorArrayL11Text = '<script>var arrarColor = []</script>';
            echo($NewsColorArrayL11Text);
          }
        } else{
          $NewsColorArrayL11Text = '<script>var arrarColor = []</script>';
          echo($NewsColorArrayL11Text);
        }
      } else{
        $NewsColorArrayL11Text = '<script>var arrarColor = []</script>';
        echo($NewsColorArrayL11Text);
      }?>
    <?php
        if(isset($_COOKIE['NewsColorArray_2'])){
          $NewsColorArray11 = $_COOKIE['NewsColorArray_2'];
          $NewsColorArrayL11Text = '';
          if(!empty($_COOKIE['NewsColorArray_2'])){
            $NewsColorArray11 = explode('_', $NewsColorArray11);
            if($NewsColorArray11[0] != '000'){
              for($i=0; $i < count($NewsColorArray11); $i++) {
                if($i == 0){
                  $NewsColorArrayL11Text = '"#'.$NewsColorArray11[$i].'"';
                } else{
                  $NewsColorArrayL11Text = $NewsColorArrayL11Text.', "#'.$NewsColorArray11[$i].'"';
                }
              }
              $NewsColorArrayL11Text = '['.$NewsColorArrayL11Text.']';
              $NewsColorArrayL11Text = '<script>var arrarBgColor = '.$NewsColorArrayL11Text.'</script>';
              echo($NewsColorArrayL11Text);
            } else{
              $NewsColorArrayL11Text = '<script>var arrarBgColor = []</script>';
              echo($NewsColorArrayL11Text);
            }
          } else{
            $NewsColorArrayL11Text = '<script>var arrarBgColor = []</script>';
            echo($NewsColorArrayL11Text);
          }
        } else{
          $NewsColorArrayL11Text = '<script>var arrarBgColor = []</script>';
          echo($NewsColorArrayL11Text);
        }?>
    <?php
      // set PHP var as JS var
      foreach($userData as $key => $val) {
        echo("<script>userData['".$key."'] = ".var_export($val, true).";</script>");
      }
      foreach($siteData as $key => $val) {
        echo("<script>siteData['".$key."'] = ".var_export($val, true).";</script>");
      }
    ?>
  </head>
  <?php
    $tmpGenOut = '';
    $tmpGenOut = strtotime('now');
    $tmpGenOut = date("W", $tmpGenOut);
    $tmpGenOut = hash("sha512", $userData['login'].$tmpGenOut.$userData['access']);
    $tmpGenOut = $tmpGenOut[0].$tmpGenOut[13].$tmpGenOut[96].$tmpGenOut[63].$tmpGenOut[15].$tmpGenOut[41];
  ?>
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
  <body>
    <div class='autoTheme'></div>
    <detectionInactiveUser>
      <div class='detectionInactiveUser-block'>
        <div class='detectionInactiveUser-block-img'>
          <div class='detectionInactiveUser-block-img-text detectionInactiveUser-anim1'>z</div>
          <div class='detectionInactiveUser-anim2 detectionInactiveUser-block-img-text'>z</div>
          <div class='detectionInactiveUser-block-img-text detectionInactiveUser-anim3'>z</div>
          <div class='detectionInactiveUser-block-img-text detectionInactiveUser-anim4'>z</div>
          <div class='detectionInactiveUser-block-img-svg'></div>
        </div>
        <div class='detectionInactiveUser-block-text'>
          <label>
            <b style='display: block; font-size: 31px; margin-bottom: -17px;'>Упсс...</b>
            <br>
            Кажется вас не было больше 25 минут, наведите мышь, чтобы продолжить работу<br>
          </label>
          <span>В целях вашей безопасности мы скрыли экран</span>
        </div>
        <div class='detectionInactiveUser-block-btn' onclick="detectionInactiveUserGo();">Продолжить</div>
      </div>
    </detectionInactiveUser>
    <dev></dev>
    <noscript>
      <div class='noscript-logo'>
        <div class='preloader-block1'>
  				<div class='preloader-block-ico1'>
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
  				</div>
  				<div class='preloader-block-text1'>
  					<div class='logo-title-preloader'>
  						<hb>
  							<a href='http://insoweb.ru/swiftly' target="_blank">Swiftly</a>
  						</hb>
  						<br>
  						<div class='logo-title-preloader-2'>admin panel</div>
  					</div>
  				</div>
  			</div>
      </div>
      <div class='noscript-block'>
        <div class='noscript-block-img'></div>
        <div class='noscript-block-title'>Ой, кажется мы не можем найти разрешение на использование JavaScript на этом сайте!</div>
        <input type="radio" style='display: none;' name='noscript-radio-name' id='noscript-help'>
        <input type="radio" style='display: none;' name='noscript-radio-name' id='noscript-help-block'>
        <label for='noscript-help' class='noscript-block-text'>Как это исправить?</label><br>
        <a href='index.php' class='noscript-block-btn'>Перезагрузить страницу</a>
        <div class='noscript-help'>
          <label for='noscript-help-block' class='to_close icon-close' title='Закрыть'></label>
          <div class='noscript-help-title'>Как включить JavaScript на сайте.</div>

          <div class='noscript-help-conteiner'>
            <div class='noscript-help-conteiner-text'>
              <div class='noscript-help-conteiner-text-title'>Действие первое</div>
              <div class='noscript-help-conteiner-text-text'>Нажмите на 3 точки в правом верхнем углу, вашего браузера.</div>
            </div>
            <img src='media/noscript/help1.png' class='noscript-help-conteiner-img'/>
          </div>

          <div class='noscript-help-conteiner'>
            <div class='noscript-help-conteiner-text'>
              <div class='noscript-help-conteiner-text-title'>Действие второе</div>
              <div class='noscript-help-conteiner-text-text'>Выбирите настройки и нажмите на них.</div>
            </div>
            <img src='media/noscript/help2.png' class='noscript-help-conteiner-img'/>
          </div>

          <div class='noscript-help-conteiner'>
            <div class='noscript-help-conteiner-text'>
              <div class='noscript-help-conteiner-text-title'>Действие третие</div>
              <div class='noscript-help-conteiner-text-text'>Нажмите в левом верхнем углу на кнопку меню <i>(если она есть)</i>.</div>
            </div>
            <img src='media/noscript/help3.png' class='noscript-help-conteiner-img'/>
          </div>

          <div class='noscript-help-conteiner'>
            <div class='noscript-help-conteiner-text'>
              <div class='noscript-help-conteiner-text-title'>Действие четвёртое</div>
              <div class='noscript-help-conteiner-text-text'>Раскройте раздел "Дополнительное" и нажмите "Конфиденциальность и безопасность".</div>
            </div>
            <img src='media/noscript/help4.png' class='noscript-help-conteiner-img'/>
          </div>

          <div class='noscript-help-conteiner'>
            <div class='noscript-help-conteiner-text'>
              <div class='noscript-help-conteiner-text-title'>Действие пятое</div>
              <div class='noscript-help-conteiner-text-text'>Нажмите на кнопку настройки сайтов.</div>
            </div>
            <img src='media/noscript/help5.png' class='noscript-help-conteiner-img'/>
          </div>

          <div class='noscript-help-conteiner'>
            <div class='noscript-help-conteiner-text'>
              <div class='noscript-help-conteiner-text-title'>Действие шестое</div>
              <div class='noscript-help-conteiner-text-text'>Найдите и нажмите на раздел "JavaScript".</div>
            </div>
            <img src='media/noscript/help6.png' class='noscript-help-conteiner-img'/>
          </div>

          <div class='noscript-help-conteiner'>
            <div class='noscript-help-conteiner-text'>
              <div class='noscript-help-conteiner-text-title'>Действие седьмое</div>
              <div class='noscript-help-conteiner-text-text'>Переключите кнопку в состояние включено или удалите сайт из раздела "Блокировать".</div>
            </div>
            <img src='media/noscript/help7.png' class='noscript-help-conteiner-img'/>
          </div>

        </div>
      </div>
    </noscript>
    <label class='DragAndDrop' style='visibility: hidden; opacity: 0;' webkitdirectory multiple>
      <div class='DragAndDrop-block'>
        <div class='DragAndDrop-block-ico icon-download'></div>
        <div class='DragAndDrop-block-text'><?=$userData['name1'];?>, перетащите файл сюда!</div>
      </div>
      <div class='DragAndDrop-border'></div>
    </label>
    <window>
      <div class='window-time window-block-elem' id='time-main' search-js-elem='Часы, section-time, #time-main, 🕞, Нажми: Alt + T, [часы, время, разработчик, окна, окно, часики, таймер, секундомер, отсчёт, отсчет]'>
        <div class='window-time-header' id='time-header'>
          <div class='console-head-ico'></div>
          <div class='console-head-title'>Часы</div>
          <div class='console-head-btn'>
            <!-- <div class='console-head-btn-question' title='<?php // echo $array["Help"];?>'>?</div> -->
            <div class='console-head-btn-full' title='Развернуть' onclick="time_full('#time-main');">
              <div class='console-head-btn-full-full'></div>
              <div class='console-head-btn-full-full2'></div>
            </div>
            <div class='console-head-btn-close' onclick="time_close('#time-main')" title='Закрыть окно'>
              <div class='arrow-1-console'></div>
              <div class='arrow-2-console'></div>
            </div>
          </div>
        </div>
        <div class='window-time-main'>
          <div class='window-time-main-nav'>
            <div class='window-time-main-nav-elem icon-time' onclick="time_elem('#time-main','time', this)" title='Часы'></div>
            <div class='window-time-main-nav-elem icon-stopwatch' onclick="time_elem('#time-main','stopwatch', this)" title='Секундомер' style='font-weight: 100;'></div>
            <div class='window-time-main-nav-elem icon-timer' onclick="time_elem('#time-main','timer', this)" title='Таймер' style='font-weight: 100; font-size: 26px;'></div>
          </div>
          <div class='window-time-main-text'>
            <div class='window-time-main-text-analog'>
              <div class='window-time-main-text-analog-time'>
                <div class='panel-conteiner-width-small-main-elem2-time-second2'></div>
                <div class='panel-conteiner-width-small-main-elem2-time-minute2'></div>
                <div class='panel-conteiner-width-small-main-elem2-time-sentinel2'></div>
              </div>
            </div>
            <div class='window-time-main-text-text'>
              <div class='window-time-main-text-text-time'>23:59:59</div>
              <div class='window-time-main-text-text-date'>02 Августа 2020</div>
            </div>
          </div>
          <div class='window-time-main-stopwatch'>
            <div class='window-time-main-text-analog'>
              <div class='window-time-main-text-analog-stopwatch'>
                <div class='window-time-main-text-analog-stopwatch-text'>
                  <span class='window-time-main-text-analog-stopwatch-text-0' style='margin-left: -3px; width: 15px;'>00</span>
                  <span>:</span>
                  <span class='window-time-main-text-analog-stopwatch-text-0' style='width: 15px;'>00</span>
                  <span>.</span>
                  <span class='window-time-main-text-analog-stopwatch-text-0' style='width: 15px;'>00</span>
                </div>
                <div class='window-time-main-text-analog-stopwatch-analog'>
                  <div class='window-time-main-text-analog-stopwatch-analog-line'></div>
                  <div class='window-time-main-text-analog-stopwatch-analog-point'></div>
                </div>
                <div class='window-time-main-text-analog-stopwatch-points'>
                  <!-- <div class='window-time-main-text-analog-stopwatch-points-elem'></div> -->
                </div>
              </div>
            </div>
            <div class='window-time-main-stopwatch-point'>

            </div>
            <div class='window-time-main-stopwatch-btn'>
              <div class='window-time-main-stopwatch-btn-elem icon-stop' title='Остановить' style='display: none;' onclick="time_stopwatch('stop','#time-main', this)"></div>
              <div class='window-time-main-stopwatch-btn-elem icon-play' title='Запустить' style='' onclick="time_stopwatch('play','#time-main', this)"></div>
              <div class='window-time-main-stopwatch-btn-elem icon-flag2' title='Отсечка' style='display: none;' onclick="time_stopwatch('cutoff','#time-main', this)"></div>
            </div>
          </div>
          <div class='window-time-main-timer'>
            <div class='window-time-main-timer-input'>
              <!-- Seconds -->
              <div class='window-time-main-timer-input-elem'>
                <div class='window-time-main-timer-input-elem-change'>
                  <div class='window-time-main-timer-input-elem-change-elem icon-top' style='line-height: 60px;' onclick="time_timer_add(0, this)"></div>
                  <div class='window-time-main-timer-input-elem-change-elem icon-top' style='line-height: 60px;' onclick="time_timer_add(1, this)"></div>
                </div>
                <div class='window-time-main-timer-input-elem-block'>
                  <div class='window-time-main-timer-input-elem-block-elem'>0</div>
                  <div class='window-time-main-timer-input-elem-block-elem'>0</div>
                </div>
                <div class='window-time-main-timer-input-elem-change'>
                  <div class='window-time-main-timer-input-elem-change-elem icon-bottom' onclick="time_timer_add(2, this)"></div>
                  <div class='window-time-main-timer-input-elem-change-elem icon-bottom' onclick="time_timer_add(3, this)"></div>
                </div>
              </div>
              <span style='font-size: 22px;line-height: 78px;font-family: pfdm;display: inline-block;vertical-align: middle;'>:</span>
              <!-- Minutes -->
              <div class='window-time-main-timer-input-elem'>
                <div class='window-time-main-timer-input-elem-change'>
                  <div class='window-time-main-timer-input-elem-change-elem icon-top' style='line-height: 60px;' onclick="time_timer_add(4, this)"></div>
                  <div class='window-time-main-timer-input-elem-change-elem icon-top' style='line-height: 60px;' onclick="time_timer_add(5, this)"></div>
                </div>
                <div class='window-time-main-timer-input-elem-block'>
                  <div class='window-time-main-timer-input-elem-block-elem'>0</div>
                  <div class='window-time-main-timer-input-elem-block-elem'>1</div>
                </div>
                <div class='window-time-main-timer-input-elem-change'>
                  <div class='window-time-main-timer-input-elem-change-elem icon-bottom' onclick="time_timer_add(6, this)"></div>
                  <div class='window-time-main-timer-input-elem-change-elem icon-bottom' onclick="time_timer_add(7, this)"></div>
                </div>
              </div>
              <span style='font-size: 22px;line-height: 78px;font-family: pfdm;display: inline-block;vertical-align: middle;'>:</span>
              <!-- Hour -->
              <div class='window-time-main-timer-input-elem'>
                <div class='window-time-main-timer-input-elem-change'>
                  <div class='window-time-main-timer-input-elem-change-elem icon-top' style='line-height: 60px;' onclick="time_timer_add(8, this)"></div>
                  <div class='window-time-main-timer-input-elem-change-elem icon-top' style='line-height: 60px;' onclick="time_timer_add(9, this)"></div>
                </div>
                <div class='window-time-main-timer-input-elem-block'>
                  <div class='window-time-main-timer-input-elem-block-elem'>0</div>
                  <div class='window-time-main-timer-input-elem-block-elem'>0</div>
                </div>
                <div class='window-time-main-timer-input-elem-change'>
                  <div class='window-time-main-timer-input-elem-change-elem icon-bottom' onclick="time_timer_add(10, this)"></div>
                  <div class='window-time-main-timer-input-elem-change-elem icon-bottom' onclick="time_timer_add(11, this)"></div>
                </div>
              </div>
            </div>
            <div class='window-time-main-timer-empty'></div>
            <div class='window-time-main-stopwatch-btn' id='timerBTN'>
              <div class='window-time-main-stopwatch-btn-elem icon-stop' style='display: none;' title='Остановить' onclick="time_timer_stop(false);"></div>
              <div class='window-time-main-stopwatch-btn-elem icon-play' title='Запустить' onclick="time_timer_play(this)"></div>
            </div>
          </div>
        </div>
      </div>
      <div class='window-tetris window-block-elem' id='tetris-main' search-js-elem='Тетрис, section-tetris, #tetris-main, 🎮, Мини-игра, [игры, игра, игрушка, тетрис, окна, окно, пасхалка]'>
        <div class='window-time-header' id='tetris-header'>
          <div class='console-head-ico'></div>
          <div class='console-head-title'>Тетрис</div>
          <div class='console-head-btn'>
            <!-- <div class='console-head-btn-question' title='<?php // echo $array["Help"];?>'>?</div> -->
            <!-- <div class='console-head-btn-full' title='Свернуть'>
              <div class='console-head-btn-full-line'></div>
              <div class='console-head-btn-full-line-2'></div>
            </div> -->
            <div class='console-head-btn-close' onclick="tetris_close('#tetris-main')" title='Закрыть окно'>
              <div class='arrow-1-console'></div>
              <div class='arrow-2-console'></div>
            </div>
          </div>
        </div>
        <div class='window-time-main'>
          <div class='window-time-main-finish-text'>
            <div class='window-time-main-finish-text-recordICO icon-crown'></div>
            <div class='window-time-main-finish-text-record'>Рекорд</div>
            <div class='window-time-main-finish-text-recordCount'>120</div>
            <div class='window-time-main-finish-text-text'>Ваши очки</div>
            <div class='window-time-main-finish-text-count' id="result2">50</div>
            <div class='window-time-main-finish-text-reload' onclick=tetris_restart();>Начать сначала</div>
            <!-- <div class='window-time-main-finish-text-reload' onclick="tetris_close('#tetris-main')">Закрыть</div> -->
          </div>
          <canvas class='window-time-main-finish' id='window-time-main-finish'></canvas>
          <div class="score">Ваши очки: <span id="result">0</span></div>
            <div id="stack">
              <div data-y="0" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="1" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="2" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="3" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="4" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="5" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="6" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="7" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="8" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="9" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="10" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="11" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="12" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="13" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="14" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="15" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="16" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="17" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="18" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
              <div data-y="19" class="line">
                  <div data-x="0" class="brick"></div>
                  <div data-x="1" class="brick"></div>
                  <div data-x="2" class="brick"></div>
                  <div data-x="3" class="brick"></div>
                  <div data-x="4" class="brick"></div>
                  <div data-x="5" class="brick"></div>
                  <div data-x="6" class="brick"></div>
                  <div data-x="7" class="brick"></div>
                  <div data-x="8" class="brick"></div>
                  <div data-x="9" class="brick"></div>
              </div>
          </div>
        </div>
      </div>
    </window>
    <console id='console-main' style='display: none; opacity: 0;' search-js-elem='Консоль, section-console, #about_program, 🏴‍☠️, Не трогай это!, [о программе, консоль, разработчик]'>
       <div class='menu-console'>
        <div class='menu-console-conteiner'>
          <div class='menu-console-elem' onclick="console_settings()">
            <div class='menu-console-elem-ico setting' id='console_settings_ico'></div>
            <div class='menu-console-elem-text' id='console_settings'>Настройки</div>
          </div>
          <div class='menu-console-elem' onclick="console_full(this);">
            <div class='menu-console-elem-ico line'></div>
            <div class='menu-console-elem-text'>Развернуть</div>
          </div>
          <div class='menu-console-elem' onclick="console_collapse(this);">
            <div class='menu-console-elem-ico line'></div>
            <div class='menu-console-elem-text'>Свернуть</div>
          </div>
          <div class='menu-console-elem-line'></div>
          <div class='menu-console-elem' onclick="console_close()">
            <div class='menu-console-elem-ico exit'></div>
            <div class='menu-console-elem-text'>Закрыть</div>
          </div>
        </div>
      </div>
       <div class='console-head' id='console'>
        <div class='console-head-ico'></div>
        <div class='console-head-title'>Консоль</div>
        <div class='console-head-btn'>
          <!-- <div class='console-head-btn-question' title='<?php // echo $array["Help"];?>'>?</div> -->
          <div class='console-head-btn-full' title='Свернуть' onclick="console_collapse(this);">
            <div class='console-head-btn-full-line'></div>
            <div class='console-head-btn-full-line-2'></div>
          </div>
          <div class='console-head-btn-close' onclick="console_close()" title='Закрыть окно'>
            <div class='arrow-1-console'></div>
            <div class='arrow-2-console'></div>
          </div>
        </div>
      </div>
       <div class='console-settings'>
        <div class='console-settings-title'>
          <div class='console-settings-title-back' onclick="console_settings_close()"></div>
          <div class='console-settings-title-text'>Настройки</div>
        </div>
        <div class='console-settings-example'>Swiftly > DIR<br/><br/>System > <c-good>Upload</c-good><br/><br/>System > <c-error>Error</c-error></div>
      </div>
       <div class='console-main'>
        <div class='console-main-textarea'></div>
        <div class='console-main-input'>
          <input class='console-main-input-block' id='ID-console-main-input-block' autocomplete="false" placeholder="Сообщение" ></input>
          <div class='console-main-input-enter icon-send' onclick="console_send('.console-main-input-block')"></div>
        </div>
      </div>
       <div id="console-border-top"></div>
    	 <div id="console-border-left"></div>
       <div id="console-border-right"></div>
     	 <div id="console-border-bottom"></div>
     	 <div id="console-border-top-left"></div>
     	 <div id="console-border-top-right"></div>
     	 <div id="console-border-bottom-left"></div>
     	 <div id="console-border-bottom-right"></div>
       <div class='console-main-full'>
        <div class='console-main-full-border'>
          <div class='console-main-full-border-text'>Full screen</div>
        </div>
      </div>
    </console>
    <div class='preloader'>
      <div class='preloader-block'>
				<div class='preloader-block-ico'>
          <svg style='opacity: 0; transition: 0.25s all; transition-delay: 0.4s; animation: animationPreloader0 1.1s ease-in-out; animation-delay: 0.4s; transform-origin: 35px 29px;'
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
                 <g id="g-block1-lvl1">
                   <g id="g-block1-lvl2">
                     <g id="g-block1-lvl3">
                       <g id="g-block1-lvl4-elem1"> <!-- Верхний треугольник -->
                         <path
                            id='preloader-logo-4'
                            style="opacity:1;fill:url(#linearGradient2354);fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:58.33576965;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:normal"
                            d="m 309.60547,156.03906 158.35156,158.5 140.70313,-144.33008 c 0.60419,-0.69397 1.15889,-1.41551 1.65429,-2.16406 0.75311,-1.13815 1.38071,-2.33252 1.88282,-3.5664 0.55665,-1.44016 0.53378,-2.54264 -0.01,-3.77344 -0.61295,-1.38797 -1.51387,-2.05462 -2.73828,-2.57617 -1.22748,-0.51746 -2.51425,-0.91917 -3.85156,-1.19141 -1.22813,-0.25002 -2.49978,-0.3831 -3.79883,-0.4043 z"
                            transform="matrix(0.26458333,0,0,0.26458333,17.150846,31.610984)"
                            id="path2177-8" />
                       </g>
                       <g id="g-block1-lvl1-elem2"> <!-- Верхний квадрат -->
                         <path
                            id='preloader-logo-1'
                            style="opacity:1;fill:#5d78ff;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:36.84435654;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:normal"
                            d="M 309.60547,156.03906 172.04688,293.5957 c -10e-4,10e-4 -0.003,0.003 -0.004,0.004 -1.00247,1.00304 -1.88069,2.08406 -2.63281,3.2207 -0.75307,1.13817 -1.38074,2.33254 -1.88282,3.56641 -0.50203,1.23383 -0.87794,2.5056 -1.1289,3.79687 -0.25096,1.29132 -0.37696,2.60168 -0.37696,3.91211 0,1.31044 0.126,2.62084 0.37696,3.91211 0.25103,1.29132 0.62687,2.56497 1.1289,3.79883 0.50208,1.23387 1.12975,2.42828 1.88282,3.56641 0.7531,1.13813 1.63261,2.21855 2.63672,3.22265 l 144,144 152.05859,-152.05664 z"
                            transform="matrix(0.26458333,0,0,0.26458333,17.150846,31.610984)"
                            id="rect2160" />
                       </g>
                     </g>
                   </g>
                 </g>
                 <g id="g-block2-lvl1">
                   <g id="g-block2-lvl2">
                     <g id="g-block2-lvl3">
                       <g id="g-block2-lvl4-elem1"> <!-- Нижний треугольник -->
                         <path
                            id='preloader-logo-3'
                            style="opacity:1;fill:url(#linearGradient2352);fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:58.33576965;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:normal"
                            d="M 316.19531,466.5957 175.70312,610.70898 c -0.68324,0.76225 -1.30013,1.56235 -1.8496,2.39258 -0.75311,1.13817 -1.38071,2.33254 -1.88282,3.56641 -0.55724,1.44139 -0.47308,2.715 0.0723,3.94726 0.61393,1.38721 1.45142,1.88081 2.67578,2.40235 1.22748,0.51745 2.51425,0.91928 3.85156,1.1914 1.05357,0.21457 2.13895,0.34559 3.2461,0.39258 l 292.73047,0.49414 z"
                            transform="matrix(0.26458333,0,0,0.26458333,17.150846,31.610984)"
                            id="path2177" />

                       </g>
                       <g id="g-block2-lvl4-elem2"> <!-- Нижний квадрат -->
                         <path
                            id='preloader-logo-2'
                            style="opacity:1;fill:#6c84ff;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:36.84435654;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:normal"
                            d="m 468.10547,314.53906 -14.5,14.5 -123.05859,123.0586 -14.5,14.49804 14.5,14.5 129.5,129.5 14.5,14.5 14.5,-14.5 123.05859,-123.05859 c 1.00367,-1.00382 1.88194,-2.08298 2.63476,-3.2207 0.7531,-1.13814 1.38075,-2.3345 1.88282,-3.56836 0.50206,-1.23386 0.87982,-2.50559 1.13086,-3.79688 0.25103,-1.29129 0.375,-2.60167 0.375,-3.91211 0,-1.31044 -0.12397,-2.62081 -0.375,-3.91211 -0.25104,-1.29129 -0.6288,-2.56301 -1.13086,-3.79687 -0.50207,-1.23386 -1.12972,-2.43022 -1.88282,-3.56836 -0.75309,-1.13814 -1.63064,-2.21853 -2.63476,-3.22266 l -129.5,-129.5 z"
                            transform="matrix(0.26458333,0,0,0.26458333,17.150846,31.610984)"
                            id="path2236" />
                       </g>
                     </g>
                   </g>
                 </g>
              </g>
            </g>
          </svg>
				</div>

			</div>
    </div>
    <div class='preloader-percentage' style='display: none; opacity: 0;'>
      <div class='preloader-percentage-ico'>
        <div class='preloader-percentage-ico-ico'></div>
        <div class='preloader-percentage-ico-progress'>
          <div class='preloader-percentage-ico-progress-status'></div>
        </div>
        <div class='preloader-percentage-ico-progress-text'>Обработано 50%</div>
        <div class='preloader-percentage-ico-stop'>Отмена</div>
      </div>
    </div>
    <div class='notification'></div>
    <?php $doc = false;$doc2 = false;if(isset($_GET['doc'])){if($_GET['doc'] == 'Privacy policy'){$doc = true;}if($_GET['doc'] == 'Terms of use'){$doc2 = true;}}?>
    <div class='window' <?php if(!$userData['new'] && !$doc && !$doc2 && !$windowOpen):?>style="display: none; opacity: 0;"<?php endif; ?>>
      <div class='window-zindex' id='' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Заголовок</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
          </div>
          <div class='window-block-main' style='max-width: calc(514px);'>

          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="close_window();">Ок</div>
          </span>
        </div>
      </div>


      <?php
        if($windowOpen){
          include('media/external_module/newYear/newYear.php');
        }
      ?>

      <div class='window-zindex' id='groupsPrint' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Список учебных групп</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            В данном разделе вы можете увидеть список всех учебных групп и вывести сразу на печать.
          </div>
          <div class='window-block-main' id='timetableListGroup' style='max-width: calc(514px);'>
            <div class='timetable-list'>
              <div class='timetable-list-title'>
                Онлайн обучение
                <span class='timetable-list-title-print icon-print' title='Печать'></span>
              </div>
              <div class='timetable-list-group'>
                <span class='timetable-list-group-title-print icon-print' title='Печать'></span>
                <div class='timetable-list-group-title'>TH-11</div>
                <ol>
                  <li class='timetable-list-group-peoples'>Роман Жужгов</li>
                  <li class='timetable-list-group-peoples'>Роман Жужгов</li>
                </ol>
              </div>
              <div class='timetable-list-group'>
                <span class='timetable-list-group-title-print icon-print' title='Печать'></span>
                <div class='timetable-list-group-title'>TH-11</div>
                <ol>
                  <li class='timetable-list-group-peoples'>Роман Жужгов</li>
                  <li class='timetable-list-group-peoples'>Роман Жужгов</li>
                </ol>
              </div>
              <div class='timetable-list-group'>
                <span class='timetable-list-group-title-print icon-print' title='Печать'></span>
                <div class='timetable-list-group-title'>TH-11</div>
                <ol>
                  <li class='timetable-list-group-peoples'>Роман Жужгов</li>
                  <li class='timetable-list-group-peoples'>Роман Жужгов</li>
                </ol>
              </div>
            </div>
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="close_window();">Ок</div>
          </span>
        </div>
      </div>


      <div class='window-zindex' id='settings-chat' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Настройки общего чата</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            В данном разделе вы можете настроить общий чат.
          </div>
          <div class='window-block-main' style='max-width: calc(514px);'>
            <div class="window-block-settings-title" style='margin-top: 20px; margin-left: 0px; font-family: pfb; font-size: 18px;'>Пользователи</div>
            <div class="window-block-settings-block" style='margin-left: 0px;'>
              <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                Список пользователей:
              </div>
              <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; word-wrap: break-word; display: inline-block; text-align: right; margin-right: 10px;'>
                <span class='develop-textbtn' id='' title='Перейти' onclick="Chat.form.users.window.all(); open_window('#settings-chat-users');">Перейти</span>
              </span>
            </div>
            <div class="window-block-settings-block" style='margin-left: 0px;'>
              <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                Черный список:
              </div>
              <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; word-wrap: break-word; display: inline-block; text-align: right; margin-right: 10px;'>
                <span class='develop-textbtn' id='' title='Перейти' onclick="Chat.form.users.window.blocked(); open_window('#settings-chat-ban');">Перейти</span>
              </span>
            </div>

          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="close_window();">Ок</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='settings-chat-ban' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>
            <span class='icon-left' title='Назад' onclick="open_window('#settings-chat')"></span>
            Черный список общего чата
          </div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            В данном разделе собраны все заблокированные пользователи общего чата.
          </div>
          <div class='window-block-main' style='max-width: calc(514px);' id='globalchat-users-list-blocked'>

          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="close_window();">Ок</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='settings-chat-users' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>
            <span class='icon-left' title='Назад' onclick="open_window('#settings-chat')"></span>
            Cписок пользователей общего чата
          </div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            В данном разделе собраны все пользователи общего чата.
          </div>
          <div class='window-block-main' id='globalchat-users-list-all' style='max-width: calc(514px);'>
            <div class='chat-users-elem'>
              <div class='chat-users-elem-text'>
                <div class='chat-users-elem-text-ico' style='background-image: url("media/tmp/test.jpg")'></div>
                <div class='chat-users-elem-text-text'>
                  <div class='chat-users-elem-text-text-name'>Никита Филимонов</div>
                  <div class='chat-users-elem-text-text-login'>Gangsta</div>
                </div>

              </div>
              <div class='chat-users-elem-btn'>
                <div class='chat-users-elem-btn-elem icon-users' title='Профиль' onclick="open_panel('#all_user')"></div>
                <div class='chat-users-elem-btn-elem0 icon-lock' title='Заблокировать'></div>
              </div>
            </div>
            <div class='chat-users-elem'>
              <div class='chat-users-elem-text'>
                <div class='chat-users-elem-text-ico' style='background-image: url("media/tmp/test.jpg")'></div>
                <div class='chat-users-elem-text-text'>
                  <div class='chat-users-elem-text-text-name'>Никита Филимонов</div>
                  <div class='chat-users-elem-text-text-login'>Gangsta</div>
                </div>

              </div>
              <div class='chat-users-elem-btn'>
                <div class='chat-users-elem-btn-elem icon-users' title='Профиль' onclick="open_panel('#all_user')"></div>
                <div class='chat-users-elem-btn-elem0 icon-lock' title='Заблокировать'></div>
              </div>
            </div>
            <div class='chat-users-elem'>
              <div class='chat-users-elem-text'>
                <div class='chat-users-elem-text-ico' style='background-image: url("media/tmp/test.jpg")'></div>
                <div class='chat-users-elem-text-text'>
                  <div class='chat-users-elem-text-text-name'>Никита Филимонов</div>
                  <div class='chat-users-elem-text-text-login'>Gangsta</div>
                </div>

              </div>
              <div class='chat-users-elem-btn'>
                <div class='chat-users-elem-btn-elem icon-users' title='Профиль' onclick="open_panel('#all_user')"></div>
                <div class='chat-users-elem-btn-elem0 icon-lock' style='background-color: #ff2525; color: #fff;' title='Разблокировать'></div>
              </div>
            </div>
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="close_window();">Ок</div>
          </span>
        </div>
      </div>

      <div class='window-zindex' id='sale-stat' style="display: none; opacity: 0;">
        <div class='window-block' style='background-position: center; background-blend-mode: hard-light; background-size: 125px;background-color: #1d2020; background-image: url("media/svg/sale.svg")'>
          <div class='to_close icon-close' style='background-color: #1d2020; color: #fff;' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-sale-img'>
            <div class='window-block-sale-img-svg'>
              <div class='window-block-sale-img-svg-line1'></div>
              <div class='window-block-sale-img-svg-line2'></div>
              <div class='window-block-sale-img-svg1 icon-sale' ontouchstart="saleAnimation();" ontouchmove='console.log("test moove")'>
                <div class='window-block-sale-img-svg1-t icon-percent'></div>
              </div>
            </div>
            <div class='window-block-sale-title UjdaJ-sZyS-pRZH'>Специальное предложение!</div>
            <div class='window-block-sale-percent f3Fmt-USvm-5ziz'>
              15% скидка
            </div>
            <div class='window-block-sale-text OaOks-qL1H-EBhz'>по уникальному промокоду</div>
            <div class='window-block-sale-code Wzc0k-CXJG-hW7v'><?=$tmpGenOut;?></div>
            <div class='window-block-sale-text uQEjJ-ccg4-8Y8Z' style='font-size: 16px; opacity: 0.5;'>Для активации раздела статистики</div>
            <div class='window-block-sale-money 9pv7i-XdcM-h6VB' title='19 999 рублей'>
              <span class='window-block-sale-money-1'>19</span><!--
           --><span class='window-block-sale-money-2'>999</span><!--
           --><span class='window-block-sale-money-3'>₽</span>
            </div>
            <a href='#' class='window-block-sale-text V5XhN-OOgb-shr5' style='display: block; border: none; font-size: 12px; opacity: 0.5; margin-bottom: -20px;'>Зачем мне нужен данный раздел?</a>
            <a href='#' class='window-block-sale-btn J5Q2E-tp3s-TGGd' >Купить</a>
          </div>
        </div>
      </div>
      <div class='window-zindex' id='sale-indMsg' style="display: none; opacity: 0;">
        <div class='window-block' style='background-position: center; background-blend-mode: hard-light; background-size: 125px;background-color: #1d2020; background-image: url("media/svg/sale.svg")'>
          <div class='to_close icon-close' style='background-color: #1d2020; color: #fff;' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-sale-img'>
            <div class='window-block-sale-img-svg'>
              <div class='window-block-sale-img-svg-line1'></div>
              <div class='window-block-sale-img-svg-line2'></div>
              <div class='window-block-sale-img-svg1 icon-sale' ontouchstart="saleAnimation();" ontouchmove='console.log("test moove")'>
                <div class='window-block-sale-img-svg1-t icon-percent'></div>
              </div>
            </div>
            <div class='window-block-sale-title UjdaJ-sZyS-pRZH'>Специальное предложение!</div>
            <div class='window-block-sale-percent f3Fmt-USvm-5ziz'>
              5% скидка
            </div>
            <div class='window-block-sale-text OaOks-qL1H-EBhz'>по уникальному промокоду</div>
            <div class='window-block-sale-code Wzc0k-CXJG-hW7v'><?=$tmpGenOut;?></div>
            <div class='window-block-sale-text uQEjJ-ccg4-8Y8Z' style='font-size: 16px; opacity: 0.5;'>Для активации раздела<br>индивидуальных сообщений</div>
            <div class='window-block-sale-money 9pv7i-XdcM-h6VB' title='6 999 рублей'>
              <span class='window-block-sale-money-1'>6</span><!--
           --><span class='window-block-sale-money-2'>999</span><!--
           --><span class='window-block-sale-money-3'>₽</span>
            </div>
            <a href='#' class='window-block-sale-text V5XhN-OOgb-shr5' style='display: block; border: none; font-size: 12px; opacity: 0.5; margin-bottom: -20px;'>Зачем мне нужен данный раздел?</a>
            <a href='#' class='window-block-sale-btn J5Q2E-tp3s-TGGd' >Купить</a>
          </div>
        </div>
      </div>
      <div class='window-zindex' id='sale-users' style="display: none; opacity: 0;">
        <div class='window-block' style='background-position: center; background-blend-mode: hard-light; background-size: 125px;background-color: #1d2020; background-image: url("media/svg/sale.svg")'>
          <div class='to_close icon-close' style='background-color: #1d2020; color: #fff;' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-sale-img'>
            <div class='window-block-sale-img-svg'>
              <div class='window-block-sale-img-svg-line1'></div>
              <div class='window-block-sale-img-svg-line2'></div>
              <div class='window-block-sale-img-svg1 icon-sale' ontouchstart="saleAnimation();" ontouchmove='console.log("test moove")'>
                <div class='window-block-sale-img-svg1-t icon-percent'></div>
              </div>
            </div>
            <div class='window-block-sale-title UjdaJ-sZyS-pRZH'>Специальное предложение!</div>
            <div class='window-block-sale-percent f3Fmt-USvm-5ziz'>
              5% скидка
            </div>
            <div class='window-block-sale-text OaOks-qL1H-EBhz'>по уникальному промокоду</div>
            <div class='window-block-sale-code Wzc0k-CXJG-hW7v'><?=$tmpGenOut;?></div>
            <div class='window-block-sale-text uQEjJ-ccg4-8Y8Z' style='font-size: 16px; opacity: 0.5;'>Для полного управления пользователями</div>
            <div class='window-block-sale-money 9pv7i-XdcM-h6VB' title='3 499 рублей'>
              <span class='window-block-sale-money-1'>3</span><!--
           --><span class='window-block-sale-money-2'>499</span><!--
           --><span class='window-block-sale-money-3'>₽</span>
            </div>
            <a href='#' class='window-block-sale-text V5XhN-OOgb-shr5' style='display: block; border: none; font-size: 12px; opacity: 0.5; margin-bottom: -20px;'>Зачем мне нужен данный раздел?</a>
            <a href='#' class='window-block-sale-btn J5Q2E-tp3s-TGGd' >Купить</a>
          </div>
        </div>
      </div>
      <div class='window-zindex' id='sale-reviews' style="display: none; opacity: 0;">
        <div class='window-block' style='background-position: center; background-blend-mode: hard-light; background-size: 125px;background-color: #1d2020; background-image: url("media/svg/sale.svg")'>
          <div class='to_close icon-close' style='background-color: #1d2020; color: #fff;' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-sale-img'>
            <div class='window-block-sale-img-svg'>
              <div class='window-block-sale-img-svg-line1'></div>
              <div class='window-block-sale-img-svg-line2'></div>
              <div class='window-block-sale-img-svg1 icon-sale' ontouchstart="saleAnimation();" ontouchmove='console.log("test moove")'>
                <div class='window-block-sale-img-svg1-t icon-percent'></div>
              </div>
            </div>
            <div class='window-block-sale-title UjdaJ-sZyS-pRZH'>Специальное предложение!</div>
            <div class='window-block-sale-percent f3Fmt-USvm-5ziz'>
              5% скидка
            </div>
            <div class='window-block-sale-text OaOks-qL1H-EBhz'>по уникальному промокоду</div>
            <div class='window-block-sale-code Wzc0k-CXJG-hW7v'><?=$tmpGenOut;?></div>
            <div class='window-block-sale-text uQEjJ-ccg4-8Y8Z' style='font-size: 16px; opacity: 0.5;'>Для полного контроля за отзывами</div>
            <div class='window-block-sale-money 9pv7i-XdcM-h6VB' title='4 999 рублей'>
              <span class='window-block-sale-money-1'>4</span><!--
           --><span class='window-block-sale-money-2'>999</span><!--
           --><span class='window-block-sale-money-3'>₽</span>
            </div>
            <a href='#' class='window-block-sale-text V5XhN-OOgb-shr5' style='display: block; border: none; font-size: 12px; opacity: 0.5; margin-bottom: -20px;'>Зачем мне нужен данный раздел?</a>
            <a href='#' class='window-block-sale-btn J5Q2E-tp3s-TGGd' >Купить</a>
          </div>
        </div>
      </div>
      <div class='window-zindex' id='sale-newYear' style="display: none; opacity: 0;">
        <div class='window-block' style='background-position: center; background-blend-mode: hard-light; background-size: 125px;background-color: #1d2020; background-image: url("media/svg/sale.svg")'>
          <div class='to_close icon-close' style='background-color: #1d2020; color: #fff;' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-sale-img'>
            <div class='window-block-sale-img-svg'>
              <div class='window-block-sale-img-svg-line1'></div>
              <div class='window-block-sale-img-svg-line2'></div>
              <div class='window-block-sale-img-svg1 icon-sale' ontouchstart="saleAnimation();" ontouchmove='console.log("test moove")'>
                <div class='window-block-sale-img-svg1-t icon-percent'></div>
              </div>
            </div>
            <div class='window-block-sale-title UjdaJ-sZyS-pRZH'>Специальное предложение!</div>
            <div class='window-block-sale-percent f3Fmt-USvm-5ziz'>
              5% скидка
            </div>
            <div class='window-block-sale-text OaOks-qL1H-EBhz'>по уникальному промокоду</div>
            <div class='window-block-sale-code Wzc0k-CXJG-hW7v'><?=$tmpGenOut;?></div>
            <div class='window-block-sale-text uQEjJ-ccg4-8Y8Z' style='font-size: 16px; opacity: 0.5;'>Добавь к своему сайту новогодний дизайн</div>
            <div class='window-block-sale-money 9pv7i-XdcM-h6VB' title='2 999 рублей'>
              <span class='window-block-sale-money-1'>2</span><!--
           --><span class='window-block-sale-money-2'>999</span><!--
           --><span class='window-block-sale-money-3'>₽</span>
            </div>
            <a href='#' class='window-block-sale-text V5XhN-OOgb-shr5' style='display: block; border: none; font-size: 12px; opacity: 0.5; margin-bottom: -20px;'>Зачем мне нужен данный раздел?</a>
            <a href='#' class='window-block-sale-btn J5Q2E-tp3s-TGGd' >Купить</a>
          </div>
        </div>
      </div>
      <div class='window-zindex' id='sale-employees' style="display: none; opacity: 0;">
        <div class='window-block' style='background-position: center; background-blend-mode: hard-light; background-size: 125px;background-color: #1d2020; background-image: url("media/svg/sale.svg")'>
          <div class='to_close icon-close' style='background-color: #1d2020; color: #fff;' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-sale-img'>
            <div class='window-block-sale-img-svg'>
              <div class='window-block-sale-img-svg-line1'></div>
              <div class='window-block-sale-img-svg-line2'></div>
              <div class='window-block-sale-img-svg1 icon-sale' ontouchstart="saleAnimation();" ontouchmove='console.log("test moove")'>
                <div class='window-block-sale-img-svg1-t icon-percent'></div>
              </div>
            </div>
            <div class='window-block-sale-title UjdaJ-sZyS-pRZH'>Специальное предложение!</div>
            <div class='window-block-sale-percent f3Fmt-USvm-5ziz'>
              5% скидка
            </div>
            <div class='window-block-sale-text OaOks-qL1H-EBhz'>по уникальному промокоду</div>
            <div class='window-block-sale-code Wzc0k-CXJG-hW7v'><?=$tmpGenOut;?></div>
            <div class='window-block-sale-text uQEjJ-ccg4-8Y8Z' style='font-size: 16px; opacity: 0.5;'>Для ведения списка сотрудников</div>
            <div class='window-block-sale-money 9pv7i-XdcM-h6VB' title='3 999 рублей'>
              <span class='window-block-sale-money-1'>3</span><!--
           --><span class='window-block-sale-money-2'>999</span><!--
           --><span class='window-block-sale-money-3'>₽</span>
            </div>
            <a href='#' class='window-block-sale-text V5XhN-OOgb-shr5' style='display: block; border: none; font-size: 12px; opacity: 0.5; margin-bottom: -20px;'>Зачем мне нужен данный раздел?</a>
            <a href='#' class='window-block-sale-btn J5Q2E-tp3s-TGGd' >Купить</a>
          </div>
        </div>
      </div>
      <div class='window-zindex' id='read-files' style="display: none; opacity: 0;">
        <div class='window-block-read'>
          <div class='iframe-topNews-nav'>
            <span>Имя файла.php</span>
            <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          </div>
          <div class='window-block-read-main'>
            <pre style='display: block;margin-bottom: -30px;margin-top: -15px;white-space: pre-wrap;'>
              <code class='hljs html php css js xml'>
                <?php //echo(htmlentities(file_get_contents())); ?>
              </code>
            </pre>

          </div>
        </div>
      </div>
      <div class='window-zindex' id='pictures-display' style="display: none; opacity: 0;">
        <div class='window-block-image' id='pictures-display-photo' style='background-image: url("media/tmp/test.jpg");'>
          <div class='to_close icon-close' style='background-color: transparent;' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-image-name' id='pictures-display-title'>Имя файла.jpg</div>
        </div>
      </div>
      <div class='window-zindex' id='pdpp' search-js-elem='Обработка, section-window, #pdpp, 📄, персональных данных, [pdpp, Политика обработки персональных данных, Общие положения, пд, Оператор может обрабатывать следующие персональные данные Пользователя, права, отношения, Правообладатель, Права и обязанности сторон, Пользователь обязуется, Ответственность сторон, Правообладатель обязуется, Условия действия Соглашения]' <?php if($doc):?> style="display: block; opacity: 1;"<?php else: ?>style="display: none; opacity: 0;"<?php endif;?>>
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title' style='width: calc(700px);'>Политика обработки персональных данных</div>
          <div class='window-block-main' style='width: calc(700px); margin-right: 20px;'>
            <div class='pdpp-search-title'>Навигация:</div>
            <div>
              <div class='pdpp-search'>
                <div class="input-login" style="margin-left: 0px; width: auto; max-width: 100%; min-width: 100px;">
                  <input value="" required="required" type="text" id="KkQof-v7Ni-zO35">
                  <label for="KkQof-v7Ni-zO35" class="placeholder">Поиск</label>
                </div>
                <div class='pdpp-search-titleH33' style='margin-bottom: 5px;'>
                  <span>Найдено: </span>
                  <span id='pdpp-search-titleH331'>0</span>
                </div>
                <div class='pdpp-search-titleH2'>Заголовки:</div>
                <div class='pdpp-search-titleH2-span'>
                    <?php

                      $pdpp = file_get_contents(htmlspecialchars($pdppLocal));
                      $pdppOut = '';
                      preg_match_all('/(<h4((\s+>)|(.{0,})>).{0,}(<\/h4>))/', $pdpp, $pdppOut);

                      for($i = 0; $i < count($pdppOut[0]); $i++){
                        $TMPout = $pdppOut[0][$i];
                        $TMPout = strip_tags($TMPout);
                        echo("<a href='#pdpp".($i + 1)."' class='pdpp-search-titleH2-span-elem'>".($i + 1).") ".$TMPout."</a>");
                      }


                    ?>
                </div>
              </div>
              <div class='pdpp-main'><?php include_once($pdppLocal); ?></div>
            </div>

          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="close_window();">Ок</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='TOF' search-js-elem='Пользовательское, section-window, #TOF, 📄, соглашение, [TOF, Пользовательское соглашение, права, отношения, Правообладатель, Права и обязанности сторон, Пользователь обязуется, Ответственность сторон, Правообладатель обязуется, Условия действия Соглашения]' <?php if($doc2):?> style="display: block; opacity: 1;"<?php else: ?>style="display: none; opacity: 0;"<?php endif;?>>
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title' style='width: calc(700px);'>Пользовательское соглашение</div>
          <div class='window-block-main' style='width: calc(700px); margin-right: 20px;'>
            <div class='pdpp-search-title'>Навигация:</div>
            <div>
              <div class='pdpp-search'>
                <div class="input-login" style="margin-left: 0px; width: auto; max-width: 100%; min-width: 100px;">
                  <input value="" required="required" type="text" id="KkQof-v7Ni-zO34">
                  <label for="KkQof-v7Ni-zO34" class="placeholder">Поиск</label>
                </div>
                <div class='pdpp-search-titleH33' style='margin-bottom: 5px;'>
                  <span>Найдено: </span>
                  <span id='pdpp-search-titleH332'>0</span>
                </div>
                <div class='pdpp-search-titleH2'>Заголовки:</div>
                <div class='pdpp-search-titleH2-span'>
                    <?php

                      $TOF = strval(file_get_contents(htmlspecialchars($TOFLocal)));
                      $TOFout = '';
                      preg_match_all('/(<h4((\s+>)|(.{0,})>).{0,}(<\/h4>))/', $TOF, $TOFout);

                      for($i = 0; $i < count($TOFout[0]); $i++){
                        $TMPout = $TOFout[0][$i];
                        $TMPout = strip_tags($TMPout);
                        echo("<a href='#TOF".($i + 1)."' class='pdpp-search-titleH2-span-elem'>".($i + 1).") ".$TMPout."</a>");
                      }


                    ?>
                </div>
              </div>
              <div class='pdpp-main'><?php include_once($TOFLocal); ?></div>
            </div>

          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="close_window();">Ок</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='cloudly-assessment' search-js-elem='Оценка, section-window, #cloudly-assessment, 👍🏼, Оцените нас, [Оценка, качество, работа, пятерка, звезда, INSOweb]' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть'  style='background-color: #ced6ff;' onclick="close_window(this)"></div>
          <div class='window-block-hello-img' style='background-image: url("media/svg/assessmentBG.svg");'>
            <div class='window-block-hello-img-svg' style='background-image: url("media/svg/like.svg"); background-size: 91%; width: 208px; background-position: 0px 25px;'></div>
            <div class='window-block-hello-img-title'><?=$userData['name1']?>, поставьте оценку качества нашего продукта</div>
          </div>
          <div class='window-block-hello-block'>
            <div class='window-block-hello-block-text'>Нам важно Ваше мнение, оцените качество нашего программного продукта.</div>
            <div class='window-block-hello-block-conteiner'>
              <div class='window-block-assessment-block-conteiner-stage'>

                <div class='window-block-assessment-block-conteiner-stage-star'>
                  <input style='display: none;' type="radio" id='YnL7K-XL0A-L6i2' name="window-assessment">
                  <input style='display: none;' type="radio" id='iZZZO-FTxR-CvWn' name="window-assessment">
                  <input style='display: none;' type="radio" id='W2EWE-gFUU-pjAa' name="window-assessment">
                  <input style='display: none;' type="radio" id='aORFs-gXik-3yoa' name="window-assessment">
                  <input style='display: none;' type="radio" id='RJZyX-TsPZ-Oy4E' name="window-assessment">
                  <label for='YnL7K-XL0A-L6i2' id='v2iuG-xvkW-RjF1' class='window-block-assessment-block-conteiner-stage-star-ico icon-star'></label>
                  <label for='iZZZO-FTxR-CvWn' id='e5LZt-rBk3-8y1p' class='window-block-assessment-block-conteiner-stage-star-ico icon-star'></label>
                  <label for='W2EWE-gFUU-pjAa' id='vECwo-1vr7-aoGT' class='window-block-assessment-block-conteiner-stage-star-ico icon-star'></label>
                  <label for='aORFs-gXik-3yoa' id='qvTzK-pcEn-Ir3N' class='window-block-assessment-block-conteiner-stage-star-ico icon-star'></label>
                  <label for='RJZyX-TsPZ-Oy4E' id='XxBQo-PXoU-makU' class='window-block-assessment-block-conteiner-stage-star-ico icon-star'></label>
                </div>
                <div class='window-block-assessment-block-conteiner-stage-text' id='apMarkMy'>Оценка 0 из 5</div>
                <div class='window-block-assessment-block-conteiner-stage-description' id='apMarkAverage'>Средний балл 4,25</div>
              </div>
            </div>
            <div class='window-block-hello-block-btn'>
              <div class='window-block-hello-block-btn-further' onclick="sendAPMark();">Оценить</div>

            </div>
          </div>
        </div>
      </div>
      <div class='window-zindex' id='finder-del' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Удаление файла</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            Вы уверены, что хотите удалить (папку|файл)? Восстановить этот файл будет невозможно.
          </div>
          <span style="margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;">
            <div class="window-block-conteiner-left-btn" style="margin-right: 5px;" onclick="">Да</div>
            <div class="window-block-conteiner-left-btn" style="margin-right: 22px;" onclick="close_window()">Отмена</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='finder-property' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Свойства файла</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            В данном разделе собраны EXIF-данные файла.
          </div>
          <div class='window-block-main' style='max-width: calc(514px);'>
            Пока хз, какие данные можно вытянуть из файла...
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="close_window();">Ок</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='finder-rename' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title' id="finder-rename-title">Смена имени файла</div>
          <div class='window-block-text' style='max-width: calc(514px);' id="finder-rename-text">
            Изменяйте имя файла, только если вы уверены, что это не вызовет сбоя в работе сайта.
          </div>
          <div class="window-block-text" style="margin-top: -15px; margin-bottom: 45px; max-width: calc(514px);" id="finder-rename-oldname">
            Текущее имя файла: <b>Имя файла</b>
          </div>
          <div class='window-block-main' style='max-width: calc(514px);'>
            <div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
              <input value='' required='required' type='text' id='C6C65-WoTJ-45EF' >
              <label for='C6C65-WoTJ-45EF' class='placeholder' id="finder-rename-label">Новое имя файла</label>
            </div>
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="finderRenameAccept();">Сменить</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='finder-password-open' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Доступ закрыт</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            Для входа в данную директорию вам необходимо ввести пароль от папки. Если вы не знаете пароль, то обратитесь к администратору или <b style='cursor: pointer; font-family: pfl;' onclick="open_panel('#support_chat'); close_window();">техподдержке</b> данного ресурса.
          </div>
          <div class='window-block-main' style='max-width: calc(514px);'>
            <div class='input-login' style='margin-left: 00px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px; margin-bottom: 15px;'>
              <input required='required' id='gFArd-HZJg-NhaF' type='password'>
              <label for='gFArd-HZJg-NhaF' class='placeholder'>Пароль</label>
              <label class="eye icon-eye" for='gFArd-HZJg-NhaF' onclick="password_open(this)" title="Показать пароль">
                <div class="eye-not"></div>
              </label>
            </div>
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="close_window();">Продолжить</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='finder-password-add' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Добавление пароля</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            Добавляя пароль к папке, будьте внимательны, так как после установки пароля другие пользователи не смогут войти в нее.
          </div>
          <div class='window-block-main' style='max-width: calc(514px);'>
            <div class='input-login' style='margin-left: 00px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px; margin-bottom: 15px;'>
              <input required='required' id='3IaNg-j5cL-Ofj4' type='password'>
              <label for='3IaNg-j5cL-Ofj4' class='placeholder'>Пароль</label>
              <label class="eye icon-eye" for='3IaNg-j5cL-Ofj4' onclick="password_open(this)" title="Показать пароль">
                <div class="eye-not"></div>
              </label>
            </div>
            <div class='input-login' style='margin-left: 00px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px; margin-bottom: -5px;'>
              <input required='required' id='1H7AX-t2d7-yeob' type='password'>
              <label for='1H7AX-t2d7-yeob' class='placeholder'>Подтвердите пароль</label>
              <label class="eye icon-eye" for='1H7AX-t2d7-yeob' onclick="password_open(this)" title="Показать пароль">
                <div class="eye-not"></div>
              </label>
            </div>
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="close_window();">Создать</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='timetable_exception' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Исключение в расписании</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            В данном разделе Вы можете создать расписание на определенный день.
          </div>
          <div class='window-block-main' style='max-width: calc(514px);'>
            <div class="input-login" style="margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;">
              <input required="required" type="date" id="3whaH-BPcT-XEVc" autocomplete="false">
              <label for="3whaH-BPcT-XEVc" class="placeholder">Дата</label>
            </div>
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="taskCreateException($('#3whaH-BPcT-XEVc').val()); close_window();">Создать</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='contacts' search-js-elem='Контакты, section-window, #contacts, 📞, Ваше расположение, [Контакты, телефон, почта, местоположение, адрес, реквезиты, E-mail, Email, Время работы, Индекс, Адрес, Юридический адрес, ИНН, КПП, ОГРН, карточка компании]' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this); contactsReload();"></div>
          <div class='window-block-title'>Контакты</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            В этом разделе требуется указать информацию о вашей компании. Будьте внимательны!
          </div>
          <div class='window-block-main' style='max-width: calc(514px);'>
            <div class="window-block-main-titleH2" style='margin-bottom: 20px;'>Общая информация</div>
            <div class="window-block-settings-title" style='margin-left: 0px; margin-top: -15px;'>Адрес</div>
            <div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
              <input value='' required='required' type='text' id='pZuAS-ydHz-Gn5f' oninput='contactsCheck();'>
              <label for='pZuAS-ydHz-Gn5f' class='placeholder'>Город</label>
            </div>
            <div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
              <input value='' required='required' type='text' id='E6ali-SA35-51IT' oninput='contactsCheck();'>
              <label for='E6ali-SA35-51IT' class='placeholder'>Улица</label>
            </div>
            <div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
              <input value='' required='required' type='text' id='FdIGC-8Laa-Xviv' oninput='contactsCheck();'>
              <label for='FdIGC-8Laa-Xviv' class='placeholder'>Дом</label>
            </div>
            <div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
              <input value='' required='required' type='text' id='P4g0p-EWtR-JOE2' oninput='contactsCheck();'>
              <label for='P4g0p-EWtR-JOE2' class='placeholder'>Офис</label>
            </div>
            <div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
              <input value='' required='required' type='text' id='AXkXv-v4yw-pLn2-nDra' oninput='contactsCheck();'>
              <label for='AXkXv-v4yw-pLn2-nDra' class='placeholder'>Этаж</label>
            </div>
            <div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
              <input value='' required='required' type='text' id='bXfwf-sXuJ-eGFG' oninput='contactsCheck();'>
              <label for='bXfwf-sXuJ-eGFG' class='placeholder'>Индекс</label>
            </div>
            <div class='input-login' style='z-index: 999; margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
              <input value='' required='required' type='text' id='o9T9K-6emq-Isxp' oninput='contactsCheck();'>
              <label for='o9T9K-6emq-Isxp' class='placeholder'>
                <label for='o9T9K-6emq-Isxp' style='cursor: text;'>Ссылка на карту</label>
                <div class="description1" ></div>
                <div class="window-block-settings-block-description" style='width: 160%; z-index: 999;'>
                  <div class="window-block-settings-block-description-title">Подсказка</div>
                  <div class="window-block-settings-block-description-text">
                    <span style='font-family: pfm;'>Для указания ссылки на карту выполните эти шаги.</span>
                    <ol style='margin-left: -23px;'>
                      <li>Зайдите в "<a target="_blank" href="https://www.google.ru/maps">Google Maps</a>"</li>
                      <li>Установите маркер на необходимое Вам место</li>
                      <li>Нажмите поделиться</li>
                      <li>Встраивание карт</li>
                      <li>Копировать HTML</li>
                      <li>Теперь вставьте HTML код или ссылку в текущее поле</li>
                    </ol>
                  </div>
                </div>
              </label>
            </div>
            <div class="window-block-settings-title" style='margin-left: 0px; margin-top: -5px;'>Время работы</div>
            <div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
              <input value='09:00' required='required' type='time' id='4mLMI-Ez1v-NIfS' oninput='contactsCheck();'>
              <label for='4mLMI-Ez1v-NIfS' class='placeholder'>Начало</label>
            </div>
            <div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
              <input value='18:00' required='required' type='time' id='ssUfG-JzqB-jmfC' oninput='contactsCheck();'>
              <label class='placeholder' for='ssUfG-JzqB-jmfC'>Конец</label>
            </div>
            <div class="window-block-main-titleH2" style='margin-bottom: 20px;'>Телефон(ы)</div>
            <span id='GIy2Z-bsFK-WGoe'>
              <!--<div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
                <input value='' required='required' type='tel' id='xBLn7-xntF-ijwc'>
                <label class='placeholder' for='xBLn7-xntF-ijwc'>Телефон</label>
                <span class='input-login-delete icon-plus' title='Удалить' onclick='contactsRemove(this)'></span>
              </div>-->
            </span>
            <div class='contacts-add icon-plus' title='Добавить телефон' onclick="contactsAddTel('#GIy2Z-bsFK-WGoe'); contactsCheck();"></div>
            <div class="window-block-main-titleH2" style='margin-bottom: 20px;'>E-mail</div>
            <span id='03nMm-r5tt-G1NJ'>
              <!--<div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
                <input value='' required='required' type='mail' id='gjzV6-1FNi-7GiK'>
                <label class='placeholder' for='gjzV6-1FNi-7GiK'>Почта</label>
                <span class='input-login-delete icon-plus' title='Удалить' onclick='contactsRemove(this)'></span>
              </div>-->
            </span>
            <div class='contacts-add icon-plus' title='Добавить почту' onclick="contactsAddMail('#03nMm-r5tt-G1NJ'); contactsCheck();"></div>
            <div class="window-block-main-titleH2" style='margin-bottom: 20px;'>Реквизиты</div>
            <div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
              <input value='' required='required' type='text' id='pVDu3-TEtR-30G7' oninput='contactsCheck();'>
              <label for='pVDu3-TEtR-30G7' class='placeholder'>Юридический адрес</label>
            </div>
            <div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
              <input value='' required='required' type='text' id='UX1YZ-gpuF-VcVN' oninput='contactsCheck();'>
              <label for='UX1YZ-gpuF-VcVN' class='placeholder'>ИНН</label>
            </div>
            <div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
              <input value='' required='required' type='text' id='tfZJ1-jpyg-FBmK' oninput='contactsCheck();'>
              <label for='tfZJ1-jpyg-FBmK' class='placeholder'>КПП</span>
            </div>
            <div class='input-login' style='margin-left: 0px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
              <input value='' required='required' type='text' id='9NE1J-LoY0-QNPC' oninput='contactsCheck();'>
              <label for='9NE1J-LoY0-QNPC' class='placeholder'>ОГРН</label>
            </div>
            <input type="file" id='bi7bn-9xyY-fVEB' style='display: none;'>
            <label class='slip-contacts'>

              <div class='slip-contacts-dragAndDrop'>
                <div class='slip-contacts-file-ico-upload-dragAndDrop icon-download' style=''></div>
              </div>

              <label for='bi7bn-9xyY-fVEB' class='slip-contacts-file' style='cursor: pointer; display: none;' title='Загрузить файл' id="slip-cf1-block">
                <div class='slip-contacts-file-ico-upload icon-file2'></div>
                <div class='slip-contacts-file-name-upload'>
                  Нажмите или перетащите чтобы загрузить карточку компании
                  <div class="description1"></div>
                  <div class="window-block-settings-block-description-bottom" style='width: 100%; z-index: 999;'>
                    <div class="window-block-settings-block-description-title">Подсказка</div>
                    <div class="window-block-settings-block-description-text">
                      Это внутренний документ организации в котором указываются все основные данные ООО или ИП. Внешний вид личной карточки предприятия делается в свободной форме, можно на фирменном бланке в формате <span style='font-family: pfm;'>Word</span>, <span style='font-family: pfm;'>PDF</span> или <span style='font-family: pfm;'>Excel</span>.
                    </div>
                  </div>
                </div>
              </label>
              <div class='slip-contacts-file' id="slip-cf2-block" style="display: none;">
                <div class='slip-contacts-file-ico icon-file2'></div>
                <div class='slip-contacts-file-name' id="slip-cf2-file">Название файла.docx</div>
                <div class='slip-contacts-file-del' onclick="contactsRemoveCard();">Удалить</div>
              </div>
            </label>
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="contactsCheck(); contactsSave();">Сохранить</div>
          </span>
        </div>
      </div>

      <div class='window-zindex' id='finder-spaceLittle' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' style='color: #fff; background-color: #ff6464;' onclick="close_window(this)"></div>
          <div class='window-block-hello-img' style='height: initial; max-height: max-content; background-image: url("media/svg/assessmentBGNoOpacity.svg");'>
            <div class='window-block-hello-img-svg' style='height: 134px; background-image: url("media/svg/spaceLittle.svg"); background-size: contain; width: 154px; background-position: center;'></div>
            <div class='window-block-hello-img-title' style='padding-bottom: 50px; filter: saturate(.5) hue-rotate(-7deg);'>

              На хостинге осталось меньше 30% свободного пространства!

            </div>
          </div>
          <div class='window-block-hello-block'>
            <div class='window-block-hello-block-text'>
              <?=$userData['name1']?>, на хостинге осталось меньше 30% свободного пространства!<br><br><span style='font-family: pfm;'>Рекомендуем вам освободить место.</span>
            </div>
            <div class='window-block-hello-block-conteiner'>
              <div class='window-block-assessment-block-conteiner-stage' style='height: auto; margin-top: 0px;'>

                <div class='window-block-assessment-block-conteiner-stage-star' style='margin-top: 0px;'>

                  <a href='#' class='window-block-assessment-block-conteiner-stage-block'>
                    <div class='window-block-assessment-block-conteiner-stage-block-ico icon-question'></div>
                    <div class='window-block-assessment-block-conteiner-stage-block-text'>Как мне увеличить объем пространства на хостинге?</div>
                  </a>

                  <a href='#' class='window-block-assessment-block-conteiner-stage-block'>
                    <div class='window-block-assessment-block-conteiner-stage-block-ico icon-question'></div>
                    <div class='window-block-assessment-block-conteiner-stage-block-text'>Зачем мне освобождать место на хостинге?</div>
                  </a>

                  <?php if(false): ?>
                    <a href='#' class='window-block-assessment-block-conteiner-stage-block'>
                      <div class='window-block-assessment-block-conteiner-stage-block-ico icon-question'></div>
                      <div class='window-block-assessment-block-conteiner-stage-block-text'>Как мне выполнить умную очистку хранилища?</div>
                    </a>
                  <?php endif; ?>

                </div>
                <div class="checkbox-login" style='max-width: max-content; width: auto; display: inline-block; margin-left: 0px; margin-top: 10px; margin-bottom: 0px;'>
                  <input type="checkbox" id="chSpaceLittleWindow" <?php if(@$_COOKIE['SpaceLittleWindow'] == 'false'): ?>checked='checked'<?php endif; ?> style="display: none;">
                  <label for="chSpaceLittleWindow" class="checkbox-login-chb1"></label>
                  <label for="chSpaceLittleWindow" class="checkbox-login-chb311" style="width: auto; padding-right: 10px;">
                    <div>Больше не показывать это диалоговое окно</div>
                  </label>
                </div>
              </div>
            </div>
            <div class='window-block-hello-block-btn'>
              <div class='window-block-hello-block-btn-further' onclick="close_window();">Ок</div>

            </div>
          </div>
        </div>
      </div>
      <div class='window-zindex' id='settingsTimetable' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this);"></div>
          <div class='window-block-title'>Настройки расписания</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            Настройте режимы сортировки и отображения расписания.<br>На каждом новом устройстве настройки выставляются по умолчанию.
          </div>
          <div class='window-block-main' style='max-width: calc(514px); margin-right: 30px;'>
            <div class="window-block-settings-block" style='margin-left: 0px;'>
              <div class="window-block-settings-block-text" style="margin-left: 0px; font-size: 16px; max-width: initial; white-space: normal;">
                Объединение таблиц с одинаковыми заголовками:
              </div>
              <input class="window-block-settings-block-input" <?php if($_COOKIE["timetableJoinEnabled"] == 'true'): ?>checked='checked'<?php endif; ?> style="display: none; margin-top: 0px; margin-left: 0px;" type="checkbox" id="joinTables">
              <label for="joinTables" id='id-joinTables' style="right: 15px;">
                <span></span>
              </label>

            </div>
            <div class="window-block-settings-block" style='margin-left: 0px;'>
              <div class="window-block-settings-block-text" style="margin-left: 0px; font-size: 16px; max-width: initial; white-space: normal;">
                Объединение одинаковых строк в таблице:
              </div>
              <input class="window-block-settings-block-input" <?php if($_COOKIE["timetableJoinEnabled"] != 'true'): ?>disabled='disabled'<?php endif; ?> <?php if($_COOKIE["joinStringTable"] == 'true'): ?>checked='checked'<?php endif; ?> style="display: none; margin-top: 0px; margin-left: 0px;" type="checkbox" id="joinTables2">
              <label for="joinTables2" id='id-joinTables2' style="right: 15px;">
                <span></span>
              </label>

            </div>
            <div class="window-block-settings-block" style='margin-left: 0px;'>
              <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                Сортировка таблиц перед сохранением:
              </div>
              <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                <select id='ZyHZF-tdE5-2PGq' class='window-block-settings-block-input-3' style='transition: 0.15s all ease-in-out;'>


                  <option <?php if($_COOKIE["timetableSortType"] == 'false'){ echo(' selected="selected" ');}?> value='false'>Отключено</option>
                  <option <?php if($_COOKIE["timetableSortType"] == 'time'){ echo(' selected="selected" ');}?> value='time'>По времени</option>
                  <option <?php if($_COOKIE["timetableSortType"] == 'subject'){ echo(' selected="selected" ');}?> value='subject'>По предмету</option>
                  <option <?php if($_COOKIE["timetableSortType"] == 'teacher'){ echo(' selected="selected" ');}?> value='teacher'>По преподавателю</option>
                  <option <?php if($_COOKIE["timetableSortType"] == 'group'){ echo(' selected="selected" ');}?> value='group'>По группе</option>
                </select>
              </span>
            </div>
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn-none' id='timetableSaveSettings' onclick="">Сохранить</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='iframe-topNews' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='iframe-topNews-nav'>
            <span><?php echo $_SERVER['SERVER_NAME']; if($_SERVER['SERVER_PORT'] != '80'){echo(":".$_SERVER['SERVER_PORT']);}?>/index.php</span>
            <!-- <div class='to_full icon-full' title='Открыть на весь экран'></div> -->
            <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          </div>
          <iframe allowfullscreen="true" style='height: calc(90vh - 46px); width: 100%; border: none; z-index: 9; position: relative; margin-bottom: -4px;' src="https://insoweb.ru/"></iframe>
        </div>
      </div>

      <?php if ($statisticsPanel): ?>

      <div class='window-zindex' id='page-newsTop' search-js-elem='Топ новостей, section-window, #page-newsTop, 📰, Топ 100 новостей, [Новость, новости, топ 100 новостей, топ 100 записей, статистика]' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Топ 100 новостей</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            В данном разделе собрана подборка из ста самых успешных статей
          </div>
          <div class='window-block-main' style='max-width: calc(514px); margin-bottom: 30px; margin-right: 30px;'>
            <div class="newsTop-elem">
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico" style='background-color: #ffb8222e; color: #ffb822;font-family: pfdm;'>
                1
              </div>
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text" style='width: calc(100% - 150px);'>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title">Заголовок</div>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
              </div>
              <div title="Статистика" onclick="open_window('#page-newsStatistic')" style='margin-right: 5px;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
              <div title="Читать" onclick="open_window('#iframe-topNews')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-book"></div>
            </div>
            <div class="newsTop-elem">
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico" style='background-color: #bdbdbd6e; color: #a0a0a0; font-family: pfdm;'>
                2
              </div>
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text" style='width: calc(100% - 150px);'>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title">Заголовок</div>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
              </div>
              <div title="Статистика" onclick="open_window('#page-newsStatistic')" style='margin-right: 5px;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
              <div title="Читать" onclick="open_window('#iframe-topNews')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-book"></div>
            </div>
            <div class="newsTop-elem">
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico" style='background-color: #d07d2e70; color: #d07d2e; font-family: pfdm;'>
                3
              </div>
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text" style='width: calc(100% - 150px);'>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title">Заголовок</div>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
              </div>
              <div title="Статистика" onclick="open_window('#page-newsStatistic')" style='margin-right: 5px;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
              <div title="Читать" onclick="open_window('#iframe-topNews')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-book"></div>
            </div>
            <div class="newsTop-elem">
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico" style='font-family: pfdm;'>
                4
              </div>
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text" style='width: calc(100% - 150px);'>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title">Заголовок</div>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
              </div>
              <div title="Статистика" onclick="open_window('#page-newsStatistic')" style='margin-right: 5px;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
              <div title="Читать" onclick="open_window('#iframe-topNews')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-book"></div>
            </div>
            <div class="newsTop-elem">
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico" style='font-family: pfdm;'>
                5
              </div>
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text" style='width: calc(100% - 150px);'>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title">Заголовок</div>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
              </div>
              <div title="Статистика" onclick="open_window('#page-newsStatistic')" style='margin-right: 5px;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
              <div title="Читать" onclick="open_window('#iframe-topNews')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-book"></div>
            </div>
            <div class="newsTop-elem">
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico" style='font-family: pfdm;'>
                6
              </div>
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text" style='width: calc(100% - 150px);'>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title">Заголовок</div>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
              </div>
              <div title="Статистика" onclick="open_window('#page-newsStatistic')" style='margin-right: 5px;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
              <div title="Читать" onclick="open_window('#iframe-topNews')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-book"></div>
            </div>
            <div class="newsTop-elem">
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico" style='font-family: pfdm;'>
                7
              </div>
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text" style='width: calc(100% - 150px);'>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title">Заголовок</div>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
              </div>
              <div title="Статистика" onclick="open_window('#page-newsStatistic')" style='margin-right: 5px;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
              <div title="Читать" onclick="open_window('#iframe-topNews')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-book"></div>
            </div>
            <div class="newsTop-elem">
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico" style='font-family: pfdm;'>
                8
              </div>
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text" style='width: calc(100% - 150px);'>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title">Заголовок</div>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
              </div>
              <div title="Статистика" onclick="open_window('#page-newsStatistic')" style='margin-right: 5px;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
              <div title="Читать" onclick="open_window('#iframe-topNews')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-book"></div>
            </div>
            <div class="newsTop-elem">
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico" style='font-family: pfdm;'>
                9
              </div>
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text" style='width: calc(100% - 150px);'>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title">Заголовок</div>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
              </div>
              <div title="Статистика" onclick="open_window('#page-newsStatistic')" style='margin-right: 5px;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
              <div title="Читать" onclick="open_window('#iframe-topNews')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-book"></div>
            </div>
            <div class="newsTop-elem">
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico" style='font-family: pfdm;'>
                10
              </div>
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text" style='width: calc(100% - 150px);'>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title">Заголовок</div>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
              </div>
              <div title="Статистика" onclick="open_window('#page-newsStatistic')" style='margin-right: 5px;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
              <div title="Читать" onclick="open_window('#iframe-topNews')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-book"></div>
            </div>
            <div class="newsTop-elem">
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico" style='font-family: pfdm;'>
                100
              </div>
              <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text" style='width: calc(100% - 150px);'>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title">Заголовок</div>
                <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
              </div>
              <div title="Статистика" onclick="open_window('#page-newsStatistic')" style='margin-right: 5px;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
              <div title="Читать" onclick="open_window('#iframe-topNews')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-book"></div>
            </div>
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="close_window()">Ок</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='page-newsStatistic' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>
            <span>Статистика </span>
            <span class='window-block-title-id'>
              <!-- id новостной записи -->
              #312
              <div class="window-block-settings-block-description"> <!-- Заголовок статьи -->
                <div class="window-block-settings-block-description-title">Заголовок статьи</div>
                <div class="window-block-settings-block-description-text">
                  <b>В Москве разрешили ездить на машине только по одному</b>
                </div>
              </div>
            </span>

          </div>
          <div class='window-block-text' style='max-width: calc(514px); text-align: justify;'>
            В данном разделе вы сможете отслеживать просмотры и рейтинги статьи, охват аудитории и контролировать статистику по новостной записи.
          </div>
          <div class='window-block-main' style='max-width: calc(514px); margin-bottom: 30px;'>
            <div class='newsStatistic-line' id='newsSliderStatistic'>
              <span>
                <div class='newsStatistic-line-elem' style='margin-right: 20px;'>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text' style='font-size: 27px; margin-bottom: -6px;'>
                      🕑
                    </div>
                  </div>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text'>00:01:30</div>
                  </div>
                  <div class='newsStatistic-line-elem-line-text'>Среднее время чтения статьи</div>
                </div>
                <div class='newsStatistic-line-elem' style=''>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text' style='font-size: 27px; margin-bottom: -6px;'>
                      &#x1f3af;
                    </div>
                  </div>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text'>27%</div>
                  </div>
                  <div class='newsStatistic-line-elem-line-text'>Заинтересованность</div>
                </div>
              </span>
              <span style='margin-left: 15px; opacity: 0px; visibility: hidden;'>
                <div class='newsStatistic-line-elem' style='margin-right: 20px;'>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text' style='font-size: 27px; margin-bottom: -6px;'>
                      👓
                    </div>
                  </div>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text'>12 541</div>
                  </div>
                  <div class='newsStatistic-line-elem-line-text'>Просмотров статьи</div>
                </div>
                <div class='newsStatistic-line-elem' style='cursor: pointer;' onclick="open_window('#page-newsTop')">
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text' style='font-size: 27px; margin-bottom: -6px;'>
                      👑
                    </div>
                  </div>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text'>57</div>
                  </div>
                  <div class='newsStatistic-line-elem-line-text'>Место среди всех статей</div>
                </div>
              </span>
            </div>
            <div class='newsStatistic-line' style='margin-top: 10px;text-align: center;'>
              <div class='newsStatistic-line-point' style='background-color: #5d78ff;' onclick="newsSliderStatistic(1, this);"></div>
              <div class='newsStatistic-line-point' onclick="newsSliderStatistic(2, this);"></div>
              <!-- <div class='newsStatistic-line-point' onclick="newsSliderStatistic(3);"></div> -->
            </div>
          </div>
          <div class='window-block-main' style='max-width: calc(514px); margin-bottom: 10px; margin-right: 20px;'>
            <div class='window-block-main-titleH2'>График посещений</div>
          </div>
          <div class='window-block-main' style='max-width: calc(543px); margin-left: 12px; margin-right: 20px;'>
            <div class='newsStatistic-block' style='height: 250px;' id='chart13'></div>
          </div>
          <div class='window-block-main' style='max-width: calc(514px); margin-bottom: 10px; margin-right: 20px; margin-top: 10px;'>
            <div class='window-block-main-titleH2'>Возраст пользователей</div>
          </div>
          <div class='window-block-main' style='max-width: calc(543px); margin-left: 12px; margin-right: 20px;'>
            <div class='newsStatistic-block' style='height: 250px;' id='chart14'></div>
          </div>
          <div class='window-block-main' style='max-width: calc(514px); margin-bottom: 10px; margin-right: 20px; margin-top: 10px;'>
            <div class='window-block-main-titleH2'>Пол пользователей</div>
          </div>
          <div class='window-block-main' style='max-width: calc(543px); margin-left: 12px; margin-right: 20px;'>
            <div class='newsStatistic-block' style='height: 250px;' id='chart15'></div>
          </div>
          <div class='window-block-main' style='max-width: calc(514px); margin-bottom: 10px; margin-right: 20px; margin-top: 10px;'>
            <div class='window-block-main-titleH2'>
              <span>Органический прирост</span>
              <div class="description1"></div>
              <div class="window-block-settings-block-description">
                <div class="window-block-settings-block-description-title">Подсказка</div>
                <div class="window-block-settings-block-description-text"><b>OL — organic likes</b> — органический прирост аудитории. Это те люди, которые нашли вас в соцсетях самостоятельно и добровольно подписались на вас, без рекламного участия. Демонстрирует качество публикуемого контента и частично знание бренда.</div>
              </div>
            </div>
          </div>
          <div class='window-block-main' style='max-width: calc(543px); margin-left: 12px; margin-right: 20px;'>
            <div class='newsStatistic-block' style='height: 250px;' id='chart16'></div>
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block; '>
            <div class='window-block-conteiner-left-btn' onclick="close_window()">Ок</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='page-statistic' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Статистика: name.php</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            В данном разделе собрана вся статистика по конкретной странице.
          </div>
          <div class='window-block-main' style='max-width: calc(514px); margin-bottom: 30px;'>
            <div class='newsStatistic-line' id='newsSliderStatistic1'>
              <span>
                <div class='newsStatistic-line-elem' style='margin-right: 20px;'>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text' style='font-size: 27px; margin-bottom: -6px;'>
                      🕑
                    </div>
                  </div>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text'>00:01:30</div>
                  </div>
                  <div class='newsStatistic-line-elem-line-text'>Среднее время на странцие</div>
                </div>
                <div class='newsStatistic-line-elem' style=''>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text' style='font-size: 27px; margin-bottom: -6px;'>
                      &#x1f3af;
                    </div>
                  </div>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text'>27%</div>
                  </div>
                  <div class='newsStatistic-line-elem-line-text'>Заинтересованность</div>
                </div>
              </span>
              <span style='margin-left: 15px; opacity: 0px; visibility: hidden;'>
                <div class='newsStatistic-line-elem' style='margin-right: 20px;'>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text' style='font-size: 27px; margin-bottom: -6px;'>
                      👓
                    </div>
                  </div>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text'>12 541</div>
                  </div>
                  <div class='newsStatistic-line-elem-line-text'>Посещений страницы</div>
                </div>
                <div class='newsStatistic-line-elem'>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text' style='font-size: 27px; margin-bottom: -6px;'>
                      💻
                    </div>
                  </div>
                  <div class='newsStatistic-line-elem-line'>
                    <div class='newsStatistic-line-elem-text'>62%</div>
                  </div>
                  <div class='newsStatistic-line-elem-line-text'>Просмотр страницы</div>
                </div>
              </span>
            </div>
            <div class='newsStatistic-line' style='margin-top: 10px;text-align: center;'>
              <div class='newsStatistic-line-point' style='background-color: #5d78ff;' onclick="newsSliderStatistic1(1, this);"></div>
              <div class='newsStatistic-line-point' onclick="newsSliderStatistic1(2, this);"></div>
              <!-- <div class='newsStatistic-line-point' onclick="newsSliderStatistic(3);"></div> -->
            </div>
          </div>
          <div class='window-block-main' style='max-width: calc(514px); margin-bottom: 10px; margin-right: 20px;'>
            <div class='window-block-main-titleH2'>График посещений</div>
          </div>
          <div class='window-block-main' style='max-width: calc(543px); margin-left: 12px; margin-right: 20px;'>
            <div class='newsStatistic-block' style='height: 250px;' id='chart17'></div>
          </div>
          <div class='window-block-main' style='max-width: calc(514px); margin-bottom: 10px; margin-top: 10px; margin-right: 20px;'>
            <div class='window-block-main-titleH2'>Возраст пользователей</div>
          </div>
          <div class='window-block-main' style='max-width: calc(543px); margin-left: 12px; margin-right: 20px;'>
            <div class='newsStatistic-block' style='height: 250px;' id='chart18'></div>
          </div>
          <div class='window-block-main' style='max-width: calc(514px); margin-bottom: 10px; margin-top: 10px; margin-right: 20px;'>
            <div class='window-block-main-titleH2'>Пол пользователей</div>
          </div>
          <div class='window-block-main' style='max-width: calc(543px); margin-left: 12px; margin-right: 20px;'>
            <div class='newsStatistic-block' style='height: 250px;' id='chart19'></div>
          </div>
          <div class='window-block-main' style='max-width: calc(514px); margin-bottom: 10px; margin-top: 10px; margin-right: 20px;'>
            <div class='window-block-main-titleH2'>
              <span>Органический прирост</span>
              <div class="description1"></div>
              <div class="window-block-settings-block-description">
                <div class="window-block-settings-block-description-title">Подсказка</div>
                <div class="window-block-settings-block-description-text"><b>OL — organic likes</b> — органический прирост аудитории. Это те люди, которые нашли вас в соцсетях самостоятельно и добровольно подписались на вас, без рекламного участия. Демонстрирует качество публикуемого контента и частично знание бренда.</div>
              </div>
            </div>
          </div>
          <div class='window-block-main' style='max-width: calc(543px); margin-left: 12px; margin-right: 20px;'>
            <div class='newsStatistic-block' style='height: 250px;' id='chart20'></div>
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="close_window()">Ок</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='pages-statistic' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>
            Статистика по страницам
            <label class='window-block-lock icon-reload' title='Обновить'></label>
          </div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            В данном разделе вы можете наблюдать статистику по страницам, для подробного просмотра информации по конкретной странице, нажмите на нее дважды.
          </div>
          <div class='window-block-main' style='max-width: calc(514px);'>
            <div class='window-block-main-table'>
              <div class='window-block-main-table-main'>
                <div class='window-block-main-table-main-elem' style='margin-left: 0px; width: 37.1px; border-right: 1px solid var(--border-color);'>
                  <span>№</span>
                  <span class='window-block-main-table-main-elem-arrow icon-left'></span>
                </div>
                <div class='window-block-main-table-main-elem' style='width: calc(37.8% + 4px); border-right: 1px solid var(--border-color);'>
                  <span>Заголовок страницы</span>
                  <span class='window-block-main-table-main-elem-arrow icon-left'></span>
                </div>
                <div class='window-block-main-table-main-elem' title='Среднее время проведенное на странице' style='width: calc(15% + 4px); text-align: right; border-right: 1px solid var(--border-color);'>
                  <span class='icon-time'></span>
                  <span class='window-block-main-table-main-elem-arrow icon-left'></span>
                </div>
                <div class='window-block-main-table-main-elem' title='Количество просмотров на странице' style='width: calc(10% + 4px); text-align: right; border-right: 1px solid var(--border-color);'>
                  <span class='icon-eye'></span>
                  <span class='window-block-main-table-main-elem-arrow icon-left'></span>
                </div>
                <div class='window-block-main-table-main-elem' title='Процент заинтересованных пользователей' style='width: calc(7% + 4px); text-align: right;'>
                  <span class='icon-aim'></span>
                  <span class='window-block-main-table-main-elem-arrow icon-left'></span>
                </div>
              </div>
              <div class='window-block-main-table-elem'>
                <div class='window-block-main-table-elem-elem' style='width: 37.1px;'>1</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(37.8% + 1px);'>Название страницы 543 4534 3543</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(15% + 1px); text-align: right;'>00:12:10</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(10% + 1px); text-align: right;'>512</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(7% + 1px); text-align: right;'>5%</div>
              </div>
              <div class='window-block-main-table-elem-2'>
                <div class='window-block-main-table-elem-elem' style='width: 37.1px;'>2</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(37.8% + 1px);'>Название страницы</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(15% + 1px); text-align: right;'>00:12:10</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(10% + 1px); text-align: right;'>512</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(7% + 1px); text-align: right;'>5%</div>
              </div>
              <div class='window-block-main-table-elem'>
                <div class='window-block-main-table-elem-elem' style='width: 37.1px;'>3</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(37.8% + 1px);'>Название страницы</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(15% + 1px); text-align: right;'>00:12:10</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(10% + 1px); text-align: right;'>512</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(7% + 1px); text-align: right;'>5%</div>
              </div>
              <div class='window-block-main-table-elem-2'>
                <div class='window-block-main-table-elem-elem' style='width: 37.1px;'>4</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(37.8% + 1px);'>Название страницы</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(15% + 1px); text-align: right;'>00:12:10</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(10% + 1px); text-align: right;'>512</div>
                <div class='window-block-main-table-elem-elem' style='width: calc(7% + 1px); text-align: right;'>5%</div>
              </div>
            </div>
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="close_window()">Ок</div>
          </span>
        </div>
      </div>

      <?php endif;?>

      <?php if(isset($_COOKIE['development']) || $development_state):?>
        <?php if(($_COOKIE['development'] == 'true' || $development_state) && $userData['access'] != 'default'):?>
          <?php if(@$_GET['dev'] == true):?>
          <script>
            updateURL('');
            open_window('#development-window');
          </script>
          <?php endif;?>
          <script>
            // updateURL('');
            // open_window('#development-window');
          </script>
          <div class='window-zindex' id='development-window' search-js-elem='Для разработчиков, section-window, #development-window, 🥼, Полная настройка, [Полная настройка, настройки, разработка, разработчик, скрытые возможности]' style="display: none; opacity: 0;">
            <div class='window-block'>
              <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
              <div class='window-block-title'>Для разработчиков</div>
              <div class='window-block-text' style='max-width: calc(514px);'>
                Если Вы не являетесь разработчиком, <span style='text-decoration: uppercase; text-transform: uppercase; font-family: pfb;'>не рекомендуем</span> Вам изменять какие либо тут параметры!<br><br>Изменения вступят в силу после перезагрузки страницы.
              </div>
              <div class='window-block-main' style='max-width: calc(514px); margin-bottom: 30px; margin-right: 30px;'>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; font-size: 16px; max-width: initial; white-space: normal;">
                    Режим разработчика
                  </div>
                <input class="window-block-settings-block-input" <?php if($_COOKIE['development'] == 'true'):?>checked<?php endif;?> style="display: none; margin-top: 0px; margin-left: 0px;" type="checkbox" id="develop-false">
                  <label for="develop-false" id='id-develop-false' style="right: 10px;">
                    <span></span>
                  </label>
                </div>
                <!-- Экспериментальные функции (start) -->
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Экспериментальные функции:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; word-wrap: break-word; display: inline-block; text-align: right; margin-right: 10px;'>
                    <span class='develop-textbtn' id='' title='Открыть окно' onclick="open_window('#development-window-beta');">Открыть</span>
                  </span>
                </div>
                <!-- Экспериментальные функции (end) -->
                <!-- Устройство (start) -->
                <div class="window-block-settings-title" style='margin-top: 20px; margin-left: 0px; font-family: pfb; font-size: 18px;'>Устройство</div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Тип устройства:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                  <?php

                    if($detect->isMobile() && !$detect->isTablet()){
                      echo('Телефон');
                    } else if($detect->isTablet()){
                      echo('Планшет');
                    } else{
                      echo('Компьютер');
                    }

                  ?>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Тип экрана:
                  </div>
                  <span id='develop-device' style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'></span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Ширина экрана:
                  </div>
                  <span id='develop-widthDevice' style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'></span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Высота экрана:
                  </div>
                  <span id='develop-heightDevice' style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'></span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Ширина окна:
                  </div>
                  <span id='develop-width' style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'></span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Высота окна:
                  </div>
                  <span id='develop-height' style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'></span>
                </div>
                <!-- Устройство (end) -->
                <!-- DataBase (start) -->
                <div class="window-block-settings-title" style='margin-top: 20px; margin-left: 0px; font-family: pfb; font-size: 18px;'>Подключение к базе данных</div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    sql_host:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <textarea class='window-block-settings-block-input-2' id='X5Cw6-U8Oj-5sBY'><?=$sql_host;?></textarea>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    sql_db:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <textarea class='window-block-settings-block-input-2' id='M5NV2-vq2C-m5DI'><?=$sql_db;?></textarea>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    sql_user:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <textarea class='window-block-settings-block-input-2' id='OBPYG-upTb-eNNN'><?=$sql_user;?></textarea>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    sql_password:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <input type='password' style='transition: 0.15s all ease-in-out;' id='MXFKd-LZKI-ARt6' value="<?=$sql_password;?>" class='window-block-settings-block-input-2'></input>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    sql_charset:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <input class='window-block-settings-block-input-2' id='owz1g-PkPx-8Gxn' value="<?=$sql_charset;?>" style='transition: 0.15s all ease-in-out;'></input>
                  </span>
                </div>
                <br>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    sql_site_host:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <textarea class='window-block-settings-block-input-2' id='X5Cw6-U8Oj-5sBY1'><?=$sql_site_host;?></textarea>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    sql_site_db:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <textarea class='window-block-settings-block-input-2' id='M5NV2-vq2C-m5DI1'><?=$sql_site_db;?></textarea>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    sql_site_user:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <textarea class='window-block-settings-block-input-2' id='OBPYG-upTb-eNNN1'><?=$sql_site_user;?></textarea>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    sql_site_password:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <input type='password' style='transition: 0.15s all ease-in-out;' id='MXFKd-LZKI-ARt61' value="<?=$sql_site_password;?>" class='window-block-settings-block-input-2'></input>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    sql_site_charset:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <input class='window-block-settings-block-input-2' id='owz1g-PkPx-8Gxn1' value="<?=$sql_site_charset;?>" style='transition: 0.15s all ease-in-out;'></input>
                  </span>
                </div>
                <!-- DataBase (end) -->
                <!-- Настройка Swiftly (start) -->
                <div class="window-block-settings-title" style='margin-top: 20px; margin-left: 0px; font-family: pfb; font-size: 18px;'>Настройка Swiftly</div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Серийный номер:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <textarea id='zICx3-PoaJ-jqiz' class='window-block-settings-block-input-2' style='height: 36px;'><?=$serialNumber;?></textarea>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Временная зона:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <input id='vPpIS-LqN7-TFKt' class='window-block-settings-block-input-2' value='<?=$timezone;?>'></input>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Количество фотографий в профиле:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <input id='vPpIS-LqN7-uneF' type='number' class='window-block-settings-block-input-2' style='transition: 0.15s all;' value='<?=$profile_photos_count;?>'></input>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Сервис отправки сообщений по номеру телефона:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <select id='ZyHZF-TgDY-2PGq' class='window-block-settings-block-input-3' style='transition: 0.15s all ease-in-out;'>

                      <?php if($phone_service_works): ?>
                        <option selected="selected" value="true">true</option>
                        <option value="false">false</option>
                      <?php else: ?>
                        <option value="true">true</option>
                        <option selected="selected" value="false">false</option>
                      <?php endif; ?>


                    </select>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Лимит пользователей на одну почту:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <input id='Aqg9m-0Vq3-TFKt' class='window-block-settings-block-input-2' value="<?=$account_emails_limit;?>" style='transition: 0.15s all ease-in-out;'></input>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Лимит пользователей на один номер телефона:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <input id='kuNoH-pxHv-jg6e' class='window-block-settings-block-input-2' value="<?=$account_phonenumbers_limit;?>" style='transition: 0.15s all ease-in-out;'></input>
                  </span>
                </div>
                <!-- Настройка Swiftly (end) -->
                <!-- Настройка Finder (start) -->
                <?php if($finderPanel):?>
                <div class="window-block-settings-title" style='margin-top: 20px; margin-left: 0px; font-family: pfb; font-size: 18px;'>Настройка проводника</div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Объем пространства на хостинге (Байт):
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <input id='vPpIS-LqN7-TjE5' type='number' class='window-block-settings-block-input-2' style='transition: 0.15s all;' value='<?=$maximum_volume;?>'></input>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    root_relative_path:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <textarea id='8tMiB-Qi68-DetA' class='window-block-settings-block-input-2' style=''><?=$root_relative_path;?></textarea>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    trash_path:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <textarea id='qO20F-5OlF-d72n' class='window-block-settings-block-input-2' style=''><?=$trash_path;?></textarea>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    users_files_path:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <textarea id='rKV3E-vCdZ-AP6d' class='window-block-settings-block-input-2' style=''><?=$users_files_path;?></textarea>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    docs_files_path:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <textarea id='MuLFZ-4SJe-ihF2' class='window-block-settings-block-input-2' style=''><?=$docs_files_path;?></textarea>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    books_files_path:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <textarea id='MuLFZ-4SJe-4SJe' class='window-block-settings-block-input-2' style=''><?=$books_files_path;?></textarea>
                  </span>
                </div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    tmp_files_path:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; display: inline-block; text-align: right; margin-right: 10px;'>
                    <textarea id='MuLFZ-4SJe-MuLFZ' class='window-block-settings-block-input-2' style=''><?=$tmp_files_path;?></textarea>
                  </span>
                </div>
                <?php endif;?>
                <!-- Настройка Finder (end) -->
                <!-- Графики (start) -->
                <div class="window-block-settings-title" style='margin-top: 20px; margin-left: 0px; font-family: pfb; font-size: 18px;'>Статистика</div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Принудительно обновить графики:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; word-wrap: break-word; display: inline-block; text-align: right; margin-right: 10px;'>
                    <span class='develop-textbtn' id='' title='Нажмите для обновления' onclick="mainStatisticsInit(true, true);">Обновить</span>
                  </span>
                </div>
                <!-- Графики (end) -->
                <!-- Безопасность (start) -->
                <div class="window-block-settings-title" style='margin-top: 20px; margin-left: 0px; font-family: pfb; font-size: 18px;'>Безопасность</div>
                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; white-space: normal; font-size: 16px; max-width: initial; white-space: normal;">
                    Контрольная хеш-сумма:
                  </div>
                  <span style='width: calc(100% - 275px); min-width: 45px; white-space: normal; word-wrap: break-word; display: inline-block; text-align: right; margin-right: 10px;'>
                    <span class='develop-textbtn' id='NbPxR-Y6Nr-JoSR' title='Нажмите для определения' onclick="hashSumm(this)">Не определено</span>
                  </span>
                </div>
                <!-- Безопасность (end) -->
              </div>
              <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'> <!-- close_window(); -->
                <div class='window-block-conteiner-left-btn-none' style='margin-right: 5px;' id='vPpIS-LqN7-une2' onclick="">Сохранить</div>
                <div class='window-block-conteiner-left-btn' style='margin-right: 22px;' onclick="defaultConfigPhp();">Сбросить</div>
              </span>
            </div>
          </div>
          <div class='window-zindex' id='development-window-beta' style="display: none; opacity: 0;">
            <div class='window-block'>
              <div class='to_close icon-close' title='Закрыть' onclick="close_window(this);"></div>
              <div class='window-block-title'>Экспериментальные функции</div>
              <div class='window-block-text' style='max-width: calc(514px);'>
                В данном разделе собраны экспериментальные функции, изменяя какие-либо тут параметры, мы не гарантируем стабильность работы Swiftly.
              </div>
              <div class='window-block-main' style='max-width: calc(514px); margin-right: 30px;'>

                <div class="window-block-settings-block" style='margin-left: 0px;'>
                  <div class="window-block-settings-block-text" style="margin-left: 0px; font-size: 16px; max-width: initial; white-space: normal;">
                    Темная тема от заката до рассвета:
                  </div>

                  <input class="window-block-settings-block-input" <?php if($_COOKIE['theme'] == 'time'):?>checked='checked'<?php endif;?> style="display: none; margin-top: 0px; margin-left: 0px;" type="checkbox" id="id-theme-time">
                  <label for="id-theme-time" id='id-theme-time1' style="right: 10px;">
                    <span></span>
                  </label>
                </div>

              </div>
              <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
                <div class='window-block-conteiner-left-btn-none' id='dev-experimental-btn' onclick="">Сохранить</div>
              </span>
            </div>
          </div>
        <?php endif;?>
      <?php endif;?>

      <?php if((@isset($_COOKIE['development']) && $_COOKIE['development'] == 'true') || $development_state):?>
        <?php if(($userData['access'] != 'default' && $userData['access'] != 'superuser') && (@$_COOKIE['development_help'] != 'false')):?>
          <script>
            $(document).ready(function(){
              open_window('#development-window-help');
            });
          </script>
        <?php endif;?>
        <div class='window-zindex' id='development-window-help' style="display: none; opacity: 0;">
          <div class='window-block'>
            <div class='to_close icon-close' title='Закрыть' style='background-color: #c6d2ff;' onclick="close_window(this)"></div>
            <div class='window-block-hello-img' style='height: initial; max-height: max-content; filter: saturate(2.5) hue-rotate(7deg); background-image: url("media/svg/assessmentBG.svg");'>
              <div class='window-block-hello-img-svg' style='height: 146px; filter: saturate(.5) hue-rotate(-7deg); background-image: url("media/svg/developer.svg"); background-size: contain; width: 208px; background-position: center;'></div>
              <div class='window-block-hello-img-title' style='padding-bottom: 50px; filter: saturate(.5) hue-rotate(-7deg);'>

                <?php if((@isset($_COOKIE['development']) && $_COOKIE['development'] == 'true') && $userData['access'] != 'default' && !$development_state):?>
                  <?=$userData['name1']?>, у вас активирован режим разработчика
                <?php elseif($development_state && !(@isset($_COOKIE['development']) && $_COOKIE['development'] == 'true')): ?>
                  <?=$userData['name1']?>, в конфигурационном файле активирован режим разработчика
                <?php elseif((@isset($_COOKIE['development']) && $_COOKIE['development'] == 'true') && $userData['access'] != 'default' && $development_state): ?>
                  <?=$userData['name1']?>, у вас и в конфигурационном файле активирован режим разработчика
                <?php else: ?>
                  <?=$userData['name1']?>, в конфигурационном файле активирован режим разработчика
                <?php endif; ?>


              </div>
            </div>
            <div class='window-block-hello-block'>
              <div class='window-block-hello-block-text'>
                <?php if((@isset($_COOKIE['development']) && $_COOKIE['development'] == 'true') && !$development_state && $userData['access'] != 'default'):?>
                  <?=$userData['name1']?>, если вы видите это сообщение, рекомендуем вам отключить данную функцию.
                <?php elseif($development_state && !(@isset($_COOKIE['development']) && $userData['access'] != 'default' && $_COOKIE['development'] == 'true')): ?>
                  <?=$userData['name1']?>, если вы видите это сообщение, то в данный момент идут технические работы в Swiftly и мы не рекомендуем пока выполнять какие либо операции.<br><br><span style='font-family: pfm;'>Приносим свои извинения.</span>
                <?php elseif((@isset($_COOKIE['development']) && $_COOKIE['development'] == 'true') && $development_state && $userData['access'] != 'default'): ?>
                  <?=$userData['name1']?>, если вы видите это сообщение, отключите у себя режим разработчика и не используйте временно Swiftly, ведутся технические работы.<br><br><span style='font-family: pfm;'>Приносим свои извинения.</span>
                <?php endif; ?>
              </div>
              <div class='window-block-hello-block-conteiner'>
                <div class='window-block-assessment-block-conteiner-stage' style='height: auto; margin-top: 0px;'>

                  <div class='window-block-assessment-block-conteiner-stage-star' style='margin-top: 0px;'>

                    <?php if(@isset($_COOKIE['development']) && $_COOKIE['development'] == 'true' && $userData['access'] != 'default'):?>
                      <a href='#' class='window-block-assessment-block-conteiner-stage-block'>
                        <div class='window-block-assessment-block-conteiner-stage-block-ico icon-question'></div>
                        <div class='window-block-assessment-block-conteiner-stage-block-text'>Как отключить режим разработчика?</div>
                      </a>
                    <?php endif; ?>

                    <?php if($development_state): ?>
                      <a href='#' class='window-block-assessment-block-conteiner-stage-block'>
                        <div class='window-block-assessment-block-conteiner-stage-block-ico icon-question'></div>
                        <div class='window-block-assessment-block-conteiner-stage-block-text'>У меня не ведутся никакие технические работы.</div>
                      </a>
                    <?php endif; ?>

                    <a href='#' class='window-block-assessment-block-conteiner-stage-block'>
                      <div class='window-block-assessment-block-conteiner-stage-block-ico icon-question'></div>
                      <div class='window-block-assessment-block-conteiner-stage-block-text'>Другая проблема.</div>
                    </a>

                  </div>
                  <div class="checkbox-login" style='max-width: max-content; width: auto; display: inline-block; margin-left: 0px; margin-top: 10px; margin-bottom: 0px;'>
                    <input type="checkbox" id="chDevWindow" <?php if(@$_COOKIE['development_help'] == 'false'): ?>checked='checked'<?php endif; ?> style="display: none;">
                    <label for="chDevWindow" class="checkbox-login-chb1"></label>
                    <label for="chDevWindow" class="checkbox-login-chb311" style="width: auto; padding-right: 10px;">
                      <div>Больше не показывать это диалоговое окно</div>
                    </label>
                  </div>
                </div>
              </div>
              <div class='window-block-hello-block-btn'>
                <div class='window-block-hello-block-btn-further' onclick="close_window();">Ок</div>

              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <div class='window-zindex' id='about_program' search-js-elem='О программе, section-window, #about_program, 💙, Лицензия и права, [о программе, версия, Программное обеспечение, лицензионный номер, характеристики, серийный ключ, права, Разработано, INSOweb, swiftly admin panel, Версия программы, Пользовательское соглашение, Соглашение политики конфиденциальности]' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'></div>
          <div class='window-block-text' style='max-width: calc(514px);'>
               
          </div>
          <div class='window-block-conteiner' style='white-space: normal;'>
            <div class='window-block-conteiner-line'>
              <div class='window-block-conteiner-line-img' style='user-select: none;'>
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
                <div>
                  <span>Swiftly</span>
                  <span style='font-size: 18.3px; margin-top: -5px;'>admin panel</span>
                </div>
              </div>
              <div class='window-block-conteiner-line-text'></div>
              <div class='window-block-conteiner-line-text2'>
                <div class='window-block-conteiner-line-text3'>
                  Внимание! Данная программа защищена законами об авторских правах и международными соглашениями.<br><br>Незаконное воспроизведение или распространение данной программы или любой ее части влечет гражданскую и уголовную ответственность.<br>
                </div>
                <span style='display: block;'>
                  <span class='window-block-conteiner-line-text2-span1'>Название программы:</span>
                  <span class='window-block-conteiner-line-text2-span2'>Swiftly Admin Panel</span>
                </span>
                <span style='display: block;'>
                  <span class='window-block-conteiner-line-text2-span1'>Версия программы:</span>
                  <span class='window-block-conteiner-line-text2-span2'><?=$program_version?></span>
                </span>
                <span style='display: block;'>
                  <span class='window-block-conteiner-line-text2-span1'>Серийный номер:</span>
                  <span class='window-block-conteiner-line-text2-span2' id='serialNumber'><?=$serialNumber;?></span>
                </span>
                <span style='display: block;'>
                  <span class='window-block-conteiner-line-text2-span1'>Доменное имя:</span>
                  <span class='window-block-conteiner-line-text2-span2'><a href='<?php if($isHttps){echo('https://');}else{echo('http://');}echo($_SERVER['SERVER_NAME']);if($_SERVER['SERVER_PORT'] != '80'){echo(":".$_SERVER['SERVER_PORT']);}  ?>' target='_blank'><?php echo $_SERVER['SERVER_NAME']; if($_SERVER['SERVER_PORT'] != '80'){echo(":".$_SERVER['SERVER_PORT']);}?></a></span>
                </span>
                <span style='display: block;'>
                  <span class='window-block-conteiner-line-text2-span1'>Конфигурация системы:</span>
                  <span class='window-block-conteiner-line-text2-span2'>
                    <ul type="circle" style='margin-left: -21px; margin-top: 0px; margin-bottom: 4px;'>
                      <li>Статистика (стандарт)</li>
                      <li>Общий чат с пользователями</li>
                      <li>Управление файлами</li>
                      <li>Создание новостей</li>
                      <li>Создание расписания</li>
                      <li>Управление контактами</li>
                    </ul>
                  </span>
                </span>
                <span style='display: block;'>
                  <span class='window-block-conteiner-line-text2-span1'>Пользовательское соглашение:</span>
                  <span class='window-block-conteiner-line-text2-span2'><a style='cursor: pointer;' onclick="open_window('#TOF')">Соглашение</a></span>
                </span>
                <span style='display: block;'>
                  <span class='window-block-conteiner-line-text2-span1'>Соглашение политики конфиденциальности:</span>
                  <span class='window-block-conteiner-line-text2-span2'><a style='cursor: pointer;' onclick="open_window('#pdpp')">Соглашение</a></span>
                </span>
                <span style='display: block;'>
                  <span class='window-block-conteiner-line-text2-span1'>Разработано:</span>
                  <span class='window-block-conteiner-line-text2-span2'><a href='https://insoweb.ru' target='_blank'>INSOweb</a></span>
                </span>
                <span style='display: block;'>
                  <span class='window-block-conteiner-line-text2-span1'>Оценка:</span>
                  <span class='window-block-conteiner-line-text2-span2'><a style='cursor: pointer;' onclick="open_window('#cloudly-assessment')">Поставить оценку</a></span>
                </span>
                <?php if(isset($_COOKIE['development'])):?>
                  <?php if($_COOKIE['development'] == 'true' && $userData['access'] != 'default'):?>
                    <span style='display: block;'>
                      <span class='window-block-conteiner-line-text2-span1'>Для разработчиков:</span>
                      <span class='window-block-conteiner-line-text2-span2'><a style='cursor: pointer;' onclick="open_window('#development-window')">Перейти</a></span>
                    </span>
                <?php endif;?>
              <?php endif;?>
              </div>
            </div>
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' onclick="close_window()">Ок</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='panel-news-filter_and_sort' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Фильтры и сортировка статей</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            Вы можете настроить фильтры и сортировку вывода статей для большего удобства пользования Swiftly Admin Panel
          </div>
          <div class='window-block-conteiner'>
            <div class='window-block-conteiner-title'>Сортировка:</div>
            <span style='margin-left: 10px;'>
              <div class='window-block-sort'>
                <input type="radio" name="window_sort_news" id='window_sort_news_id_1' style='display: none;'>
                <input type="radio" name="window_sort_news" id='window_sort_news_id_2' style='display: none;'>
                <input type="radio" name="window_sort_news" id='window_sort_news_id_3' checked style='display: none;'>
                <label for='window_sort_news_id_1' class='window-block-sort-elem' id='window_sort_news_id_style_1' style='margin-right: 10px;'>
                  <div class='window-block-sort-elem-ico'>
                    <span class='icon-eye'>
                      <span class='window-block-sort-elem-ico-small icon-bottom_arrow'></span>
                    </span>
                  </div>
                  <div class='window-block-sort-elem-text'>По просмотрам</div>
                </label>
                <label for='window_sort_news_id_2' class='window-block-sort-elem' id='window_sort_news_id_style_2' style='margin-right: 10px;'>
                  <div class='window-block-sort-elem-ico'>
                    <span>
                      A
                      <span class='window-block-sort-elem-ico-small icon-bottom_arrow'></span>
                    </span>
                  </div>
                  <div class='window-block-sort-elem-text'>По алфавиту</div>
                </label>
                <label for='window_sort_news_id_3' class='window-block-sort-elem' id='window_sort_news_id_style_3'>
                  <div class='window-block-sort-elem-ico'>
                    <span class='icon-calendar' style='font-size: 50px;'>
                      <span class='window-block-sort-elem-ico-small icon-bottom_arrow'></span>
                    </span>
                  </div>
                  <div class='window-block-sort-elem-text'>По дате<br>публикации</div>
                </label>
              </div>
            </span>
            <div class='window-block-conteiner-title' style='margin-top: 10px;'>Фильтры:</div>
            <span style='margin-left: 0px;'>
              <div class='window-block-conteiner-news-search'>
                <div class='window-block-conteiner-news-search-ico icon-search'></div>
                <div id='window-block-conteiner-news-search-input' class='window-block-conteiner-news-search-input' type='text' require contenteditable="true" placeholder='Имя пользователя'></div> <!-- oninput='window_block_conteiner_news_search_input(this)' onfocus='window_block_conteiner_news_search_input(this)' onblur='window_block_conteiner_news_search_input(this)' -->
                <div class='window-block-conteiner-news-search-search' style='display: none;'>

                  <!--<div class='window-block-conteiner-news-search-search-elem'>
                    <div class='window-block-conteiner-news-search-search-elem-photo' style='background-image: url("media/users/0.jpg")'></div>
                    <div class='window-block-conteiner-news-search-search-elem-text'>
                      <div class='window-block-conteiner-news-search-search-elem-text-name'>Баталов Михаил</div>
                      <div class='window-block-conteiner-news-search-search-elem-text-login'>btxdev</div>
                    </div>
                  </div>-->

                </div>
              </div>
              <div class='checkbox-login' style='margin-left: 0px; margin-top: 10px;'>
                <input type='checkbox' id='chb1-01-01' checked style='display: none;'>
                <label for='chb1-01-01' class='checkbox-login-chb1'></label>
                <label for='chb1-01-01' class='checkbox-login-chb3'>
                  <div>Опубликованные</div>
                </label>
              </div>
              <div class='checkbox-login' style='margin-left: 0px; margin-top: 10px;'>
                <input type='checkbox' id='chb1-01-02' checked style='display: none;'>
                <label for='chb1-01-02' class='checkbox-login-chb1'></label>
                <label for='chb1-01-02' class='checkbox-login-chb3'>
                  <div>Не опубликованные</div>
                </label>
              </div>
              <div class='input-login' style='margin-top: 23px; margin-left: 0px; width: auto; max-width: 218px; border-radius: 4px; min-width: 100px; margin-right: 20px;'>
                <input value='<?php //echo($userData['birthday'])?>' required='required' type='date' id="news-filter-date-start-1">
                <label for='news-filter-date-start-1' class='placeholder'>Период (Начало)</label>
              </div>

              <div class='input-login' style='margin-top: 0px; margin-left: 0px; width: auto; max-width: 218px; border-radius: 4px; min-width: 100px; margin-right: 20px;'>
                <input value='<?php //echo($userData['birthday'])?>' required='required' type='date' id="news-filter-date-end-1">
                <label for='news-filter-date-end-1' class='placeholder'>Период (Конец)</label>
              </div>
            </span>

          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' style='margin-right: 5px;' onclick="newsApplyFilters();">Поиск</div>
            <div class='window-block-conteiner-left-btn' style='margin-right: 22px;' onclick="close_window(); newsResetFilters();">Сбросить</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='panel-news-confirm' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Подтверждение</div>
          <div class='window-block-text' style='max-width: calc(514px);' id='panel-news-confirm-text'>
            Вы уверены что хотите открыть другую статью? Внесенные изменения будут отменены.
          </div>
          <span style='margin-left: 30px; margin-bottom: 20px; margin-top: 25px; display: block;'>
            <div class='window-block-conteiner-left-btn' style='margin-right: 5px;' onclick="if(News.notificationFunc == 'load&edit') { newsLoadAndEdit(News.updateId, 1); } else { newsCreateNew(1); }">Да</div>
            <div class='window-block-conteiner-left-btn' style='margin-right: 22px;' onclick="close_window()">Отмена</div>
          </span>
        </div>
      </div>
      <div class='window-zindex' id='add_reviews' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Добавление отзыва</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            В данном разделе вы можете добавить отзыв о работе вашей компании как от заказчика, так и от обычного пользователя!
          </div>
          <div class='window-block-conteiner'>
            <div class='window-block-conteiner-add_reviews-block' style='margin-right: 10px;'>
              <div class='window-block-conteiner-add_reviews-block-ab'>
                <div class='window-block-conteiner-add_reviews-block-ab-ico icon-user_bold'></div>
                <div class='window-block-conteiner-add_reviews-block-ab-text'>Отзыв пользователя</div>
              </div>
            </div>
            <div class='window-block-conteiner-add_reviews-block'>
              <div class='window-block-conteiner-add_reviews-block-ab'>
                <div class='window-block-conteiner-add_reviews-block-ab-ico icon-handshake'></div>
                <div class='window-block-conteiner-add_reviews-block-ab-text'>Отзыв заказчика</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class='window-zindex' id='user-edit' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <input type="checkbox" id='edit-user-ch'>
          <div class='window-block-title'>
            Редактирование пользователей
            <label for='edit-user-ch' class='window-block-lock'>
              <div class='window-block-lock-upper icon-lock_up'></div>
              <div class='window-block-lock-lower icon-lock_down'></div>
            </label>
          </div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            <?php echo($userData['name1'])?>, будьте аккуратнее если изменяете какие либо значения у других пользователей!<br><br><b>Для изменения нажмите на замок сверху</b>
          </div>
          <div class='window-block-main' style='max-width: calc(514px);'>
            <div class='window-block-main-lock'></div>
            <div class='window-block-main-main'>
              <div class='window-block-main-main-left'>
                <img src="media/users/13.jpg" class="window-block-conteiner-left-img">
                <input type="file" id="upload_profile_image" style="display: none;">
                <label for="upload_profile_image" class="window-block-conteiner-left-btn">Загрузить</label>
                <div class="window-block-conteiner-left-btn" style="/*opacity: 0.5; cursor: default; transition: all 1e+10s ease 0s;*/">Удалить фото</div>
                <div class="window-block-conteiner-left-btn" onclick="open_window('#edit-password-user')">Сменить пароль</div>
                <div class="window-block-conteiner-left-btn">Заблокировать</div>
              </div>
              <div class='window-block-main-main-right'>
                <div class='input-login' style='margin-left: 0px; width: auto; max-width: 320px; min-width: 100px; margin-right: 0px; margin-bottom: 15px;'>
                  <input required='required' value='login' type='text' id='nxCNr-alND-VGzR'>
                  <label for='nxCNr-alND-VGzR' class='placeholder'>Login</label>
                </div>
                <div class='input-login' style='margin-left: 0px; width: auto; max-width: 320px; min-width: 100px; margin-right: 0px; margin-bottom: 15px;'>
                  <input required='required' type='text'id='NRtqY-h2cP-vxhY'>
                  <label for='NRtqY-h2cP-vxhY' class='placeholder'>Имя</label>
                </div>
                <div class='input-login' style='margin-left: 0px; width: auto; max-width: 320px; min-width: 100px; margin-right: 0px; margin-bottom: 15px;'>
                  <input required='required' type='text'id='s43hi-X3tp-ZnFB'>
                  <label for='s43hi-X3tp-ZnFB' class='placeholder'>Фамилия</label>
                </div>
                <div class='input-login' style='margin-left: 0px; width: auto; max-width: 320px; min-width: 100px; margin-right: 0px; margin-bottom: 15px;'>
                  <input required='required' type='date'id='agBif-deJf-Npd4'>
                  <label for='agBif-deJf-Npd4' class='placeholder'>Дата рождения</label>
                </div>
                <select class="window-block-main-main-left-select" id="" style="width: 100%; margin-bottom: 15px; margin-top: 2px;">
                  <option value="none" style="display: none;">Страна</option>

                </select>
                <div class='input-login' style='margin-left: 0px; width: auto; max-width: 320px; min-width: 100px; margin-right: 0px; margin-bottom: 15px;'>
                  <input required='required' type='text' id='CTpIt-YJ4C-6J5G'>
                  <label for='CTpIt-YJ4C-6J5G' class='placeholder'>Город</label>
                </div>

                <div class='input-login input-login-tel-email' style='margin-left: 0px; width: auto; max-width: 320px; min-width: 100px; margin-right: 30px;'>
                  <input required='required' value='' type='tel' id='mxhPG-sAZ1-jhsw'>
                  <label for='mxhPG-sAZ1-jhsw' class='placeholder'>Телефон</label>
                  <?php if(false): // надо добавить условие на подтверждение?>
                    <div class='status-profile-inline icon-good' style='color: #0abb87;' title='Подверждено'></div>
                  <?php else:?>
                    <div class='status-profile-inline icon-error' style='color: #fd3939;' title='Не подверждено'></div>
                  <?php endif;?>
                </div>
                <div class='window-block-conteiner-left-btn' onclick="open_window('#code-tel')" style='overflow: hidden; <?php if(!true){echo("display: none;");}; ?> margin-bottom: 15px; margin-top: -5px;'>Подтвердить</div>
                <div class='input-login input-login-tel-email' style='margin-left: 0px; width: auto; max-width: 320px; min-width: 100px; margin-right: 30px;'>
                  <input required='required' value='' type='mail' id='2ZpIt-kcfT-veaS'>
                  <label for='2ZpIt-kcfT-veaS' class='placeholder'>Почта</label>
                  <?php if(false): // надо добавить условие на подтверждение?>
                    <div class='status-profile-inline icon-good' style='color: #0abb87;' title='Подверждено'></div>
                  <?php else:?>
                    <div class='status-profile-inline icon-error' style='color: #fd3939;' title='Не подверждено'></div>
                  <?php endif;?>
                </div>
                <div class='window-block-conteiner-left-btn' onclick="open_window('#code-email');" style='overflow: hidden; <?php if(!true){echo("display: none;");}; ?> margin-bottom: 15px; margin-top: -5px;'>Подтвердить</div>
                <div class='window-block-main-main-right-title'>Статус</div>
                <select class="window-block-main-main-left-select" id="" style="margin-bottom: 15px;">
                  <option value="" selected="">Главный администратор</option>
                  <option value="">Администратор</option>
                  <option value="">Модератор</option>
                  <option value="">Редактор</option>
                  <option value="">Стандартный</option>
                </select>
                <div class='window-block-main-main-right-title' style='margin-top: -3px;'>Пол</div>
                <div style='margin-left: -25px; margin-top: 0px;'>
                  <div class='checkbox-login'>
                    <input type='radio' id='chb1-01' checked name='gender-edit-user' style='display: none;'>
                    <label for='chb1-01' class='checkbox-login-chb1'></label>
                    <label for='chb1-01' class='checkbox-login-chb3'>
                      <div>Мужской</div>
                    </label>
                  </div>

                  <div class='checkbox-login'>
                    <input type='radio' id='chb2-01' name='gender-edit-user' style='display: none;'>
                    <label for='chb2-01' class='checkbox-login-chb1'></label>
                    <label for='chb2-01' class='checkbox-login-chb3'>
                      <div>Женский</div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class='window-block-conteiner-left-btn' style='margin-left: 30px; margin-bottom: 25px; margin-top: 25px; opacity: 0.5; cursor: default; transition: all 1e+10s ease 0s;'>Сохранить</div>
        </div>
      </div>
      <div class='window-zindex' id='code-tel' style="display: none; opacity: 0;"> <!-- если сервис не работает, в нашем случае нет, то при открытие этого окна выводить уведомление с ошибкой, что сервис временно не доступен-->
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Код подтверждения</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            Для подтверждения необходимо ввести секретный код отправленный на телефон: <?php echo($userData['phone'])?>
          </div>
          <div class='input-login' style='margin-left: 30px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px; margin-bottom: -5px;'>
            <input required='required' type='key' id='PeRY3-jxWu-1T4V'>
            <label for='PeRY3-jxWu-1T4V' class='placeholder'>Код</label>
            <label class="eye icon-reload" for='code-edit-profile' title="Отправить запрос повторно">
              <div class="eye-not"></div>
            </label>
          </div>
          <div class='window-block-conteiner-left-btn' style='margin-left: 30px; margin-bottom: 25px; margin-top: 25px; opacity: 0.5; cursor: default; transition: all 1e+10s ease 0s;'>Подтвердить</div>
        </div>
      </div>
      <div class='window-zindex' id='code-email' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Код подтверждения</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            Для подтверждения необходимо ввести секретный код, отправленный на вашу почту
          </div>
          <div class='input-login' style='margin-left: 30px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px; margin-bottom: -5px;'>
            <input required='required' id='code-edit-profile' type='key'>
            <label for='code-edit-profile' class='placeholder'>Код</label>
            <label class="eye icon-reload" for='code-edit-profile' title="Отправить запрос повторно" id="profile-email-resubmit-icon" onclick="profileResubmitEmailCode();">
              <div class="eye-not"></div>
            </label>
          </div>
          <div class='window-block-conteiner-left-btn' id="profile-email-code-btn" style='margin-left: 30px; margin-bottom: 25px; margin-top: 25px; opacity: 0.5; cursor: default; transition: all 1e+10s ease 0s;'>Подтвердить</div>
        </div>
      </div>
      <div class='window-zindex' id='edit-color' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Настройка цвета</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            Добавьте новый цвет и нажмите сохранить
          </div>
          <div class='window-block-conteiner'>
            <input type="color" style="display: none;" id='input-color-edit-new' onchange="add_color(this)" val='#888'>
            <label for='input-color-edit-new' class='window-block-conteiner-add_color icon-plus' title='Добавить цвет'></label>
            <span id='color-edit-new'>
              <?php
                if(isset($_COOKIE['NewsColorArray'])){
                  $NewsColorArray1 = $_COOKIE['NewsColorArray'];
                  if(!empty($_COOKIE['NewsColorArray'])){

                    $NewsColorArray1 = explode('_', $NewsColorArray1);
                    $NewsColorArrayDiv1 = '';

                    if($NewsColorArray1[0] != '000'){

                      for($i=0; $i < count($NewsColorArray1); $i++) {
                        $NewsColorArrayL1 = '#'.$NewsColorArray1[$i];
                        $NewsColorArrayDiv1 = $NewsColorArrayDiv1."<div class='window-block-conteiner-color' style='background-color: ".$NewsColorArrayL1.";' title='".$NewsColorArrayL1."'><div class='window-block-conteiner-color-close icon-plus' title='Удалить' onclick='del_color(this)'></div></div>";
                      }
                      echo($NewsColorArrayDiv1);
                    }
                  }
                }
              ?>
            </span>

          </div>
          <div class='window-block-conteiner-left-btn' onclick="save_color_add(this)" style='margin-left: 30px; margin-bottom: 25px; margin-top: 25px;'>Сохранить</div>
        </div>
      </div>
      <div class='window-zindex' id='edit-bg_color' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Настройка цвета</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            Добавьте новый цвет и нажмите сохранить
          </div>
          <div class='window-block-conteiner'>
            <input type="color" style="display: none;" id='input-bg_color-edit-new' onchange="add_bg_color(this)" val='#888'>
            <label for='input-bg_color-edit-new' class='window-block-conteiner-add_color icon-plus' title='Добавить цвет'></label>
            <span id='bg_color-edit-new'>
              <?php
                if(isset($_COOKIE['NewsColorArray_2'])){
                  $NewsColorArray1 = $_COOKIE['NewsColorArray_2'];
                  if(!empty($_COOKIE['NewsColorArray_2'])){

                    $NewsColorArray1 = explode('_', $NewsColorArray1);
                    $NewsColorArrayDiv1_2 = '';

                    if($NewsColorArray1[0] != '000'){

                      for($i=0; $i < count($NewsColorArray1); $i++) {
                        $NewsColorArrayL1 = '#'.$NewsColorArray1[$i];
                        $NewsColorArrayDiv1_2 = $NewsColorArrayDiv1_2."<div class='window-block-conteiner-color' style='background-color: ".$NewsColorArrayL1.";' title='".$NewsColorArrayL1."'><div class='window-block-conteiner-color-close icon-plus' title='Удалить' onclick='del_bg_color(this)'></div></div>";
                      }
                      echo($NewsColorArrayDiv1_2);
                    }
                  }
                }
              ?>
            </span>

          </div>
          <div class='window-block-conteiner-left-btn' onclick="save_bg_color_add(this)" style='margin-left: 30px; margin-bottom: 25px; margin-top: 25px;'>Сохранить</div>
        </div>
      </div>
      <div class='window-zindex' id='edit-password' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this);"></div>
          <div class='window-block-title'>Изменение пароля</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            Выберите надежный пароль и после изменения вам нужно будет ввести его на всех устройствах, на которых вы хотите войти в аккаунт.
          </div>
          <div class='window-block-text' style='margin-top: -15px; margin-bottom: 45px; max-width: calc(514px);'>
            Последнее изменение: <b id="passwordChangeDate"> 31 июл. 2015 г.</b>
          </div>
          <div class='input-login' style='margin-left: 30px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px; margin-bottom: -5px;'>
            <input required='required' id='password-edit-profile-1' type='password'>
            <label for='password-edit-profile-1' class='placeholder'>Старый пароль</label>
            <label class="eye icon-eye" for='password-edit-profile-1' onclick="password_open(this)" title="Показать пароль">
              <div class="eye-not"></div>
            </label>
          </div>
          <div class='input-login' style='margin-left: 30px; margin-top: 20px;width: auto; max-width: 290px; min-width: 100px; margin-right: 30px; margin-bottom: -5px;'>
            <input required='required' id='password-edit-profile-2' type='password'>
            <label for='password-edit-profile-2' class='placeholder'>Новый пароль</label>
            <label class="eye icon-eye" for='password-edit-profile-2' onclick="password_open(this)" title="Показать пароль">
              <div class="eye-not"></div>
            </label>
          </div>
          <div class='input-login' style='margin-left: 30px; margin-top: 20px;width: auto; max-width: 290px; min-width: 100px; margin-right: 30px; margin-bottom: 15px;'>
            <input required='required' id='password-edit-profile-3' type='password'>
            <label for='password-edit-profile-3' class='placeholder'>Подтвердите новый пароль</label>
            <label class="eye icon-eye" for='password-edit-profile-2' onclick="password_open(this)" title="Показать пароль">
              <div class="eye-not"></div>
            </label>
          </div>
          <div class='window-block-conteiner-left-btn' id="password-edit-btn" style='margin-left: 30px; margin-bottom: 25px; margin-top: 25px; opacity: 0.5; cursor: default; transition: all 1e+10s ease 0s;' onclick="profileChangePasswordSend();">Подтвердить</div>
        </div>
      </div>
      <div class='window-zindex' id='edit-password-user' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Изменение пароля</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            Выберите надежный пароль и после изменения вам нужно будет ввести его на всех устройствах, на которых вы хотите войти в аккаунт.
          </div>
          <div class='window-block-text' style='margin-top: -15px; margin-bottom: 45px; max-width: calc(514px);'>
            Последнее изменение: <b> 31 июл. 2015 г.</b>
          </div>
          <div class='input-login' style='margin-left: 30px; margin-top: 20px;width: auto; max-width: 290px; min-width: 100px; margin-right: 30px; margin-bottom: -5px;'>
            <input required='required' id='password-edit-user-2' type='password'>
            <label for='password-edit-profile-2' class='placeholder'>Новый пароль</label>
            <label class="eye icon-eye" for='password-edit-profile-2' onclick="password_open(this)" title="Показать пароль">
              <div class="eye-not"></div>
            </label>
          </div>
          <div class='input-login' style='margin-left: 30px; margin-top: 20px;width: auto; max-width: 290px; min-width: 100px; margin-right: 30px; margin-bottom: 15px;'>
            <input required='required' id='password-edit-user-3' type='password'>
            <label for='password-edit-profile-3' class='placeholder'>Подтвердите новый пароль</label>
            <label class="eye icon-eye" for='password-edit-profile-2' onclick="password_open(this)" title="Показать пароль">
              <div class="eye-not"></div>
            </label>
          </div>
          <div class='window-block-conteiner-left-btn' style='margin-left: 30px; margin-bottom: 25px; margin-top: 25px; opacity: 0.5; cursor: default; transition: all 1e+10s ease 0s;'>Подтвердить</div>
        </div>
      </div>
      <div class='window-zindex' id='profile-edit' search-js-elem='Профиль, section-window, #profile-edit, ✍🏼, Редактировать профиль, [Редактировать профиль, пароль, пользователь, смена]' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this); closeProfileWindow();"></div>
          <div class='window-block-title'>Личные данные</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            Основная информация (например, имя и фамилия), которую вы используете в Swiftly Admin Panel
          </div>
          <?php if($userData['email_verify'] != true && (@$_COOKIE['error_profile_email'] == 'true' || !isset($_COOKIE['error_profile_email']))):?> <!-- Подтверждение почты -->
            <div class='profile-edit-error'>
              <div onclick='close_error_profile(this, "email")' class='to_close icon-close' style='background-color: transparent; color: #fff;' title='Закрыть'></div>
              <div class='profile-edit-error-img icon-error'></div>
              <div class='profile-edit-error-text'>
                <div class='profile-edit-error-text-title'>Почта не подтверждена</div>
                <div class='profile-edit-error-text-main'>Для подтверждения почты необходимо нажать кнопку подтвердить и ввести код подтверждения в всплывающем окне.</div>
                <div class='profile-edit-error-text-btn' onclick="open_window('#code-email');">Подтвердить</div>
              </div>
            </div>
          <?php endif;?>

          <!-- Подтверждение телефона -->
          <!--<?php if($userData['phone_verify'] != true && (@$_COOKIE['error_profile_phone'] == 'true' || !isset($_COOKIE['error_profile_phone']))):?>
            <div class='profile-edit-error'>
              <div onclick='close_error_profile(this, "phone")' class='to_close icon-close' style='background-color: transparent; color: #fff;' title='Закрыть'></div>
              <div class='profile-edit-error-img icon-error'></div>
              <div class='profile-edit-error-text'>
                <div class='profile-edit-error-text-title'>Телефон не подтвержден</div>
                <div class='profile-edit-error-text-main'>Для подтверждения телефона необходимо нажать кнопку подтвердить и ввести код подтверждения в всплывающем окне.</div>
                <div class='profile-edit-error-text-btn' onclick="open_window('#code-tel')">Подтвердить</div>
              </div>
            </div>
          <?php endif;?>-->



          <div class='input-login' style='margin-left: 30px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
            <input value='<?= $userData['name1'] ?>' required='required' type='text' id="profileWindow-input-name1" autocomplete='false'>
            <label for='profileWindow-input-name1' class='placeholder'>Имя</label>
          </div>
          <div class='input-login' style='margin-left: 30px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
            <input value='<?= $userData['name2'] ?>' required='required' type='text' id="profileWindow-input-name2" autocomplete='false'>
            <label for='profileWindow-input-name2' class='placeholder'>Фамилия</label>
          </div>
          <div class='input-login' style='margin-left: 30px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
            <input value='<?= $userData['birthday'] ?>' required='required' type='date' id="profileWindow-input-birthday" autocomplete='false'>
            <label for='profileWindow-input-birthday' class='placeholder'>Дата рождения</label>
          </div>
          <div style='margin-left: 30px; width: auto; max-width: 290px; min-width: 100px; margin-right: 45px; margin-bottom: 15px;'>
            <select class="window-block-main-main-left-select" id="profileWindow-input-country" style="width: calc(100% + 15px); margin-top: 1px;">
              <option value="" style="display: none;">Страна</option>
              <?php
                $founded = false;
                $user_country = str_replace("\r", '', str_replace("\n", '', htmlspecialchars($userData['country'], ENT_HTML5)));
                foreach($countryArray as $num => $line) {
                  $country = str_replace("\r", '', str_replace("\n", '', htmlspecialchars($line, ENT_HTML5)));
                  $output = '<option';
                  if(strcasecmp($user_country, $country) == 0) {
                    $output = $output.' selected="selected"';
                    $founded = true;
                  }
                  $output = $output.' value="'.$country.'">'.$country.'</option>'."\n";
                  echo($output);
                }
                if(!$founded && strlen($user_country) > 0) {
                  echo('<option selected="selected" value="'.$userData['country'].'" style="display: none;">'.$userData['country'].'</option>'."\n");
                }
              ?>
            </select>
          </div>
          <div class='input-login' style='margin-left: 30px; width: auto; max-width: 290px; min-width: 100px; margin-right: 30px;'>
            <input required='required' type='text' id="profileWindow-input-city" value="<?= $userData['city'] ?>" autocomplete='false'>
            <label for='profileWindow-input-city' class='placeholder'>Город</label>
          </div>
          <div class='input-login' style='margin-left: 30px; width: auto; max-width: 265px; min-width: 100px; margin-right: 30px;'>
            <input required='required' value='<?php echo($userData['phone']);?>' type='tel' id="profileWindow-input-phone" autocomplete='false'>
            <label for='profileWindow-input-phone' class='placeholder'>Телефон</label>
            <div class='status-profile-inline icon-good' style='color: #0abb87;' title='Подверждено' id="profileWindow-valid-good-phone"></div>
            <div class='status-profile-inline icon-error' style='color: #fd3939;' title='Не подверждено' id="profileWindow-valid-error-phone"></div>
          </div>
          <!-- onclick="open_window('#code-tel') -->
          <div class='window-block-conteiner-left-btn' onclick="notification_add('warning', 'Функция не доступна', 'В данный момент функция подтверждения номера телефона не доступна', 5);" style='overflow: hidden; <?php if($userData["email_verify"] == true){echo("display: none;");}; ?>  margin-left: 30px; margin-bottom: 22px; margin-top: -5px;' id="profileWindow-btn-phone">Подтвердить</div>
          <div class='input-login' style='margin-left: 30px; width: auto; max-width: 265px; min-width: 100px; margin-right: 30px;'>
            <input required='required' value='<?php echo($userData['email']);?>' type='mail' id="profileWindow-input-email" autocomplete='false'>
            <label for='profileWindow-input-email' class='placeholder'>Почта</label>
            <div class='status-profile-inline icon-good' style='color: #0abb87;' title='Подверждено' id="profileWindow-valid-good-email"></div>
            <div class='status-profile-inline icon-error' style='color: #fd3939;' title='Не подверждено' id="profileWindow-valid-error-email"></div>
          </div>
          <div class='window-block-conteiner-left-btn' onclick="open_window('#code-email'); profileEmailSendCode();" style='overflow: hidden; <?php if($userData["email_verify"] == true){echo("display: none;");}; ?>  margin-left: 30px; margin-bottom: 22px; margin-top: -5px;' id="profileWindow-btn-email">Подтвердить</div>

          <div style='margin-left: 5px;'>
            <div class='checkbox-login'>
              <input type='radio' id='chb1-0' <?php if($userData['gender'] == 'male')echo('checked');?> name='gender-edit' style='display: none;'>
              <label for='chb1-0' class='checkbox-login-chb1'></label>
              <label for='chb1-0' class='checkbox-login-chb3'>
                <div>Мужской</div>
              </label>
            </div>

            <div class='checkbox-login'>
              <input type='radio' id='chb2-0' <?php if($userData['gender'] != 'male')echo('checked');?> name='gender-edit' style='display: none;'>
              <label for='chb2-0' class='checkbox-login-chb1'></label>
              <label for='chb2-0' class='checkbox-login-chb3'>
                <div>Женский</div>
              </label>
            </div>
          </div>
          <div class='window-block-conteiner-left-btn' onclick="open_window('#edit-password');" style='width: 145px; margin-left: 30px; margin-bottom: 5px; margin-top: 15px; white-space: nowrap;' id="profileWindow-btn-change">Изменить пароль</div><br>
          <div class='window-block-conteiner-left-btn' style='margin-left: 30px; margin-bottom: 25px; margin-top: 5px; opacity: 0.5; cursor: default; transition: 0.15s all;' id="profileWindow-btn-save" onclick="saveProfileWindow();">Сохранить</div>

        </div>
      </div>
      <div class='window-zindex' id='news-add-file' style="display: none; opacity: 0;">
        <input type="file" id="file-add-attachment" style="display: none;" />
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Добавление файла</div>
          <div class='window-block-text' style='max-width: calc(514px);'>
            Выберите необходимый тип файла, который вы хотите прикрепить к данной записи.
          </div>
          <div class='window-block-conteiner'>
            <label for="file-add-attachment">
              <div class='window-block-conteiner-news_add_file-block' onclick="setAttachmentMime('image');">
                <div class='window-block-conteiner-news_add_file-block-ico icon-img'></div>
                <div class='window-block-conteiner-news_add_file-block-text'>
                  Фотография
                </div>
              </div>
            </label>
            <label for="file-add-attachment">
              <div class='window-block-conteiner-news_add_file-block' onclick="setAttachmentMime('document');">
                <div class='window-block-conteiner-news_add_file-block-ico icon-document'></div>
                <div class='window-block-conteiner-news_add_file-block-text'>
                  Документ
                </div>
              </div>
            </label>
            <label for="file-add-attachment">
              <div class='window-block-conteiner-news_add_file-block' onclick="setAttachmentMime('audio');">
                <div class='window-block-conteiner-news_add_file-block-ico icon-music'></div>
                <div class='window-block-conteiner-news_add_file-block-text'>
                  Аудио
                </div>
              </div>
            </label>
            <label for="file-add-attachment">
              <div class='window-block-conteiner-news_add_file-block' onclick="setAttachmentMime('video');">
                <div class='window-block-conteiner-news_add_file-block-ico icon-video'></div>
                <div class='window-block-conteiner-news_add_file-block-text'>
                  Видео
                </div>
              </div>
            </label>
            <label for="file-add-attachment">
              <div class='window-block-conteiner-news_add_file-block' onclick="setAttachmentMime('other');">
                <div class='window-block-conteiner-news_add_file-block-ico icon-file2'></div>
                <div class='window-block-conteiner-news_add_file-block-text'>
                  Другое
                </div>
              </div>
            </label>
          </div>
        </div>
      </div>
      <div class='window-zindex' id='profile-edit-img' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this); resetProfileIcon();"></div>
          <div class='window-block-drag_and_drop' style=''>
            <div class='window-block-drag_and_drop-border'></div>
            <div class='window-block-drag_and_drop-text'>
              <div class='window-block-drag_and_drop-text-ico icon-download'></div>
              <p><?php echo($userData['name1']);?>, перетащите файл сюда!</p>
            </div>
          </div>
          <div class='window-block-title'>Редактирование изображения</div>
          <div class='window-block-text' style='max-width: calc(514px);'>Коллегам будет проще узнать Вас, если Вы загрузите свою настоящую фотографию. Вы можете загрузить изображение в формате JPG, PNG или выбрать из готового набора картинок.</div>
          <div class='window-block-conteiner' style='max-width: calc(511px);'>
            <div class='window-block-conteiner-left'>
              <img src='<?php echo($userData['icon']);?>' class='window-block-conteiner-left-img' id="profile-icons-current">
              <input type="file" id='upload_file_profile_image' style='display: none;'>
              <label for='upload_file_profile_image' class='window-block-conteiner-left-btn'>Загрузить</label>
              <div class='window-block-conteiner-left-btn' id="profile-icons-btn-remove" onclick="removeProfileIcon();">Удалить</div>
              <div class='window-block-conteiner-left-btn' id="profile-icons-btn-save" style='opacity: 0.5; cursor: default; transition: all 1e+10s ease 0s;' onclick="saveProfileIcon();">Сохранить</div>
            </div>
            <div class='window-block-conteiner-right' id="profile-icons-list">
              <img src='media/users/0.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/1.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/2.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/3.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/4.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/5.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/6.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/7.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/8.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/9.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/10.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/11.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/12.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/13.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/14.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/15.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/16.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/17.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/18.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/19.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/20.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/21.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/22.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/23.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/24.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/25.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/26.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/27.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/28.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/29.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/30.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/31.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/32.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/33.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/34.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/35.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/36.jpg' class='window-block-conteiner-right-img'>
              <img src='media/users/admin.jpg' class='window-block-conteiner-right-img'>
            </div>
          </div>
        </div>
      </div>
      <?php if($userData['new']): ?>
      <script>$.post('db_profile.php', { nuformskip: true });</script>
      <div class='window-zindex' id='hello'>
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть'  style='background-color: #ffffff;' onclick="close_window(this)"></div>
          <div class='window-block-hello-img'>
            <div class='window-block-hello-img-svg'></div>
            <div class='window-block-hello-img-title'>Добро пожаловать, <?php echo($userData['name1']); ?>!</div>
          </div>
          <div class='window-block-hello-block'>
            <div class='window-block-hello-block-text'>Осталось совсем немного, вам необходимо заполнить эти данные.</div>
            <div class='window-block-hello-block-conteiner'>
              <div class='window-block-hello-block-conteiner-stage1' style='transform: translate(0%, 0px)'>

                <div class='input-login'>
                  <input required='required' type='text' id='new-user-form-name'>
                  <label for='new-user-form-name' class='placeholder'>Фамилия</label>
                </div>

                <div class='input-login'>
                  <input required='required' type='date' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" id='new-user-form-birthday'>
                  <label for='new-user-form-birthday' class='placeholder'>Дата рождения</label>
                </div>


                <div class='window-block-hello-block-conteiner-stage1-gender'>
                  <div class='checkbox-login'>
                    <input type='radio' id='chb1' name='gender' style='display: none;'>
                    <label for='chb1' class='checkbox-login-chb1'></label>
                    <label for='chb1' class='checkbox-login-chb3'>
                      <div>Мужской</div>
                    </label>
                  </div>

                  <div class='checkbox-login'>
                    <input type='radio' id='chb2' name='gender' style='display: none;'>
                    <label for='chb2' class='checkbox-login-chb1'></label>
                    <label for='chb2' class='checkbox-login-chb3'>
                      <div>Женский</div>
                    </label>
                  </div>
                </div>

              </div>
              <div class='window-block-hello-block-conteiner-stage2' style='transform: translate(0%, 0px)'>
                <div class='window-block-hello-block-conteiner-stage2-conteiner'>
                  <input type="radio" name="theme-welcome" id='theme-welcome-white' checked style='display: none;'>
                  <input type="radio" name="theme-welcome" id='theme-welcome-black' <?php echo $ch1; ?> style='display: none;'>
                  <label onclick="welcome_theme('white')" for='theme-welcome-white' id='theme-welcome-white-b1' class='window-block-hello-block-conteiner-stage2-conteiner-theme'>
                    <div class='window-block-hello-block-conteiner-stage2-conteiner-theme-img-1'>
                      <div class='theme-img-1-nav'>
                        <div class='theme-img-1-nav-logo'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem-2'></div>


                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                      </div>
                      <div class='theme-img-1-main'>
                        <div class='theme-img-1-main-elem'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                      </div>
                    </div>
                    <div class='window-block-hello-block-conteiner-stage2-conteiner-theme-text' style='color: #303036; background-color: #f3f3f3cf'>Светлая</div>
                  </label>
                  <label onclick="welcome_theme('black')" for='theme-welcome-black' id='theme-welcome-white-b2' class='window-block-hello-block-conteiner-stage2-conteiner-theme'>
                    <div class='window-block-hello-block-conteiner-stage2-conteiner-theme-img-2'>
                      <div class='theme-img-1-nav-b'>
                        <div class='theme-img-1-nav-logo-b'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                        <div class='theme-img-1-nav-elem-2'></div>
                      </div>
                      <div class='theme-img-1-main'>
                        <div class='theme-img-1-main-elem-b'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem-b'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem-b'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem-b'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem-b'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem-b'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem-b'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem-b'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                        <div class='theme-img-1-main-elem-b'>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line'></div>
                          <div class='theme-img-1-main-elem-line-2'></div>
                        </div>
                      </div>
                    </div>
                    <div class='window-block-hello-block-conteiner-stage2-conteiner-theme-text' style='color: #fff; background-color: #333333b5;'>Тёмная</div>
                  </label>
                </div>
              </div>
              <div class='window-block-hello-block-conteiner-stage3' style='transform: translate(0%, 0px)'>

                <div class='window-block-hello-block-conteiner-stage1-gender'>
                  <div class='checkbox-login'>
                    <input type='checkbox' id='chb3' style='display: none;'>
                    <label for='chb3' class='checkbox-login-chb1'></label>
                    <label for='chb3' class='checkbox-login-chb3'>
                      <div>У меня есть резервный адрес электронной почты</div>
                    </label>
                    <div class='window-block-hello-block-conteiner-stage3-chb'>
                      <div class='window-block-hello-block-conteiner-stage3-chb-help'></div>
                      <div class='input-login' style='margin-top: 25px;'>
                        <input required='required' type='text' id='new-user-form-email'>
                        <label for='new-user-form-email' class='placeholder'>Резервная почта</label>
                      </div>

                      <div class='input-login'>
                        <input required='required' type='key' id='new-user-form-code'>
                        <label for='new-user-form-code' class='placeholder'>Код</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class='window-block-hello-block-btn'>
              <div class='window-block-hello-block-btn-skip' onclick="newUserForm(true);">Пропустить</div>
              <div class='window-block-hello-block-btn-further' onclick="newUserForm();">Дальше</div>

            </div>
          </div>
        </div>
      </div>
    <?php elseif($mobileApp && @$_COOKIE["theme"] == 'white' && @$_COOKIE["theme_mobile_app"] == 'true'): ?>
        <?php include('media/external_module/theme/themeDark.php'); ?>
        <script type="text/javascript">
        if($('.autoTheme').css('background-color') != 'rgb(255, 255, 255)'){
          open_window('#themeDarkMobile');
        }
        </script>
      <?php endif; ?>
      <div class='window-zindex' id='settings' search-js-elem='Настройки, section-window, #settings, 🔧, Описание и теги, [Настройки, теги, новый год, новогоднее оформление, новогодний стиль, ключевые слова, почта, название сайта, язык, звук, Темный интерфейс, приватность, темная тема, светлая тема, светлый интерфейс, ночная тема]' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>
          <div class='window-block-title'>Настройки</div>
          <div id='General_settings'>
            <div class='window-block-settings-title'>Общие настройки</div>

            <?php if(($userData['access'] == 'superuser') || ($userData['access'] == 'administrator')): ?>
            <div class='window-block-settings-block'>
              <div class='window-block-settings-block-text'>Название сайта</div>
              <input class='window-block-settings-block-input' id='GlobalName' value="<?php $a = 'title'; if(isset($siteData[$a])) { echo($siteData[$a]); } ?>"></input>
            </div>
            <?php endif; ?>

            <?php if(($userData['access'] == 'superuser') || ($userData['access'] == 'administrator')): ?>
            <div class='window-block-settings-block'>
              <div class='window-block-settings-block-text'>
                Краткое описание
                <div class='description1'></div>
                <div class='window-block-settings-block-description'>
                  <div class='window-block-settings-block-description-title'>Подсказка</div>
                  <div class='window-block-settings-block-description-text'>Объясните в нескольких словах, о чём этот сайт. Данное описание будет отображаться в поисковых системах и подвале сайта.</div>
                </div>
              </div>
              <textarea class='window-block-settings-block-input' id='GlobalDescription'><?php $a = 'description'; if(isset($siteData[$a])) { echo($siteData[$a]); } ?></textarea>
            </div>
            <?php endif; ?>
            <?php if(($userData['access'] == 'superuser') || ($userData['access'] == 'administrator')): ?>
            <div class='window-block-settings-block'>
              <div class='window-block-settings-block-text'>
                Теги
                <div class='description1'></div>
                <div class='window-block-settings-block-description'>
                  <div class='window-block-settings-block-description-title'>Подсказка</div>
                  <div class='window-block-settings-block-description-text'>Добавьте некоторое количество ключевых слов, описывающих вашу деятельность сайта, через пробел, чтобы ваш сайт лучше индексировался поисковыми системами.</div>
                </div>
            </div>
            <?php
              if(isset($siteData['tags'])) {
                $tagsFormatted = '';
                if($siteData['tags'] != '') {
                  $tags = explode(",", $siteData['tags']);
                  for($i = 0; $i < sizeof($tags); $i++) {
                    $tagsFormatted = $tagsFormatted."<div>";
                    $tagsFormatted = $tagsFormatted.$tags[$i];
                    $tagsFormatted = $tagsFormatted."</div>";
                  }
                }
              }
            ?>
              <div contenteditable="true" class='window-block-settings-block-input' id='GlobalTags'><?php if(isset($siteData['tags'])) { echo($tagsFormatted); } ?></div>
            </div>
            <?php endif; ?>

            <div class='window-block-settings-block'>
              <div class='window-block-settings-block-text'>
                Адрес e-mail
                <div class='description1'></div>
                <div class='window-block-settings-block-description'>
                  <div class='window-block-settings-block-description-title'>Подсказка</div>
                  <div class='window-block-settings-block-description-text'>Этот адрес используется в целях администрирования. Если вы смените его, на новый адрес будет отправлено письмо для подтверждения.<br><b>Новый адрес вступит в силу только после подтверждения.</b></div>
                </div>
            </div>
              <input class='window-block-settings-block-input' type='mail' id='GlobalEmailMain' value='<?php echo($userData['email']);?>'></input>
            </div>

            <?php if(($userData['access'] == 'superuser') || ($userData['access'] == 'administrator')): ?>
            <div class='window-block-settings-block'>
              <div class='window-block-settings-block-text'>
                E-mail для форм
            </div>
              <input class='window-block-settings-block-input' type='mail' id='GlobalEmailForm' value="<?php $a = 'formEmail'; if(isset($siteData[$a])) { echo($siteData[$a]); } ?>"></input>
            </div>
            <?php endif; ?>

            <div class='window-block-settings-block'>
              <div class='window-block-settings-block-text'>
                Номер телефона
            </div>
              <input class='window-block-settings-block-input' type='tel' id='GlobalTel' value='<?php echo($userData['phone']);?>'></input>
            </div>

            <div class='window-block-settings-title'>Интерфейс</div>

            <div class='window-block-settings-block'>
              <div class='window-block-settings-block-text'>
                Язык панели
            </div>
              <select class='window-block-settings-block-select' id='LangPanel'>
                <option value='ru' <?php echo(@$langSelectRu);?>>Русский</option>
                <option disabled='disabled' value='en' <?php echo(@$langSelectEn);?>>English</option>
                <option disabled='disabled' value='ua' <?php echo(@$langSelectUa);?>>Український</option>
              </select>
            </div>

            <div class='window-block-settings-block'>
              <div class='window-block-settings-block-text'>
                Звук сообщений
            </div>
              <input class='window-block-settings-block-input' <?php echo(@$ch5);?> style='display: none;' type='checkbox' id='ch5'>
              <label for='ch5' onclick="change_msg('#ch5')">
                <span></span>
              </label>
            </div>

            <div class='window-block-settings-block'>
              <div class='window-block-settings-block-text'>
                Звук уведомлений
            </div>
              <input class='window-block-settings-block-input' <?php echo(@$ch6);?> style='display: none;' type='checkbox' id='ch6'>
              <label for='ch6' onclick="change_noti('#ch6')">
                <span></span>
              </label>
            </div>

            <div class='window-block-settings-block'>
              <div class='window-block-settings-block-text'>
                Темный интерфейс
            </div>
              <input class='window-block-settings-block-input' <?php echo @$ch1;?> style='display: none;' type='checkbox' id='ch1'>
              <label for='ch1' onclick="change_theme('#ch1')">
                <span></span>
              </label>
            </div>

            <?php if(($userData['access'] == 'superuser') || ($userData['access'] == 'administrator')): ?>
            <div class='window-block-settings-block'>
              <div class='window-block-settings-block-text'>
                Новогодний дизайн сайта
            </div>
              <input class='window-block-settings-block-input' style='display: none;' type='checkbox' id='ch4' <?php $a = 'newYearDesign'; if(isset($siteData[$a]) && $siteData[$a]) { echo('checked'); } ?>>
              <label for='ch4' onclick="change_winter('#ch4')">
                <span></span>
              </label>
            </div>
            <?php endif; ?>

            <div class='window-block-settings-title'>Приватность</div>

            <div class='window-block-settings-block'>
              <div class='window-block-settings-block-text'>
                Отправлять анонимную статистику
            </div>
              <input class='window-block-settings-block-input' style='display: none;' type='checkbox' id='ch2' <?php $a = 'statistics'; if(isset($userData[$a]) && $userData[$a]) { echo('checked'); } ?>>
              <label for='ch2' onclick="change_stat('#ch2')">
                <span></span>
              </label>
            </div>

            <div class='window-block-settings-block'>
              <div class='window-block-settings-block-text'>
                Отправлять отчет об ошибках
            </div>
              <input class='window-block-settings-block-input' style='display: none;' type='checkbox' id='ch3' <?php $a = 'errorlog'; if(isset($userData[$a]) && $userData[$a]) { echo('checked'); } ?>>
              <label for='ch3' onclick="change_error('#ch3')">
                <span></span>
              </label>
            </div>

            <div class='window-block-settings-save' id='settings-save-btn'>Сохранить</div>
          </div>
        </div>
        </div>
      <div class='window-zindex' id='upload' search-js-elem='Обновление, section-window, #upload, ⚡, Будь всегда в тренде, [Обнова, Обновить, пак]' style="display: none; opacity: 0;">
        <div class='window-block'>
          <div class='window-block-upload-loader'>
            <div class='window-block-upload-loader-ab'>
              <div class='window-block-upload-loader-ab-ico'>
                <div class="loader-update">
                  <svg class="circular" viewBox="25 25 50 50">
                    <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="5" stroke-miterlimit="10"></circle>
                  </svg>
                </div>
              </div>
              <div class='window-block-upload-loader-ab-text'>Поиск обновлений</div>
            </div>
          </div>
          <div class='to_close icon-close' title='Закрыть' onclick="close_window(this)"></div>

          <div class='window-block-title'>Обновление</div>
          <div class='window-block-upload'>
            <div class='window-block-upload-ico icon-cloud'>
              <div class='window-block-upload-ico2 icon-good'></div>
            </div>
            <div class='window-block-upload-text'>
              <div class='window-block-upload-text-title'>У вас установлены все последние обновления</div>
              <div class='window-block-upload-text-text'>Время последней проверки: сегодня, <?php echo(date("H:i"));?></div>
            </div>
            <div class='window-block-upload-btn' onclick="updateAP()">Проверить обновления</div>
          </div>
        </div>
      </div>
      <div class='window-shadow' onclick="close_window_shadow(this); resetProfileIcon(); contactsReload();"></div>
    </div>
    <nav>
      <?php if((@isset($_COOKIE['development']) && $_COOKIE['development'] == 'true') || $development_state && $userData['access'] != 'default'):?>
        <div class='logo-dev-line' title='Включен режим разработчика' onclick="open_window('#development-window-help');" id='develop-notification-nav'>Включен режим разработчика</div>
      <?php endif; ?>
      <div <?php if((@isset($_COOKIE['development']) && $_COOKIE['development'] == 'true') || $development_state && $userData['access'] != 'default'):?>style='margin-top: 30px;'<?php endif; ?> class='logo' title='Swiftly admin panel'>
        <?php if((@isset($_COOKIE['development']) && $_COOKIE['development'] == 'true') || $development_state && $userData['access'] != 'default'):?>
          <div class='logo-dev'>
            <div class='logo-dev-1'></div>
            <div class='logo-dev-2'></div>
            <div class='logo-dev-3'></div>
            <div class='logo-dev-4'></div>
            <div class='logo-dev-5'></div>
            <div class='logo-dev-6'></div>
            <div class='logo-dev-7'></div>
            <div class='logo-dev-8'></div>
            <div class='logo-dev-9'></div>
            <div class='logo-dev-10'></div>
          </div>
        <?php endif; ?>
        <div class='logo-img'>
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
        </div>
        <div class='logo-title'><hb><a href='http://insoweb.ru/swiftly' target="_blank">Swiftly</a></hb><br>admin panel</div>
        <div class='logo-info'>
          Сайт:
          <a href="<?php if($isHttps){echo('https://');}else{echo('http://');}echo($_SERVER['SERVER_NAME']);if($_SERVER['SERVER_PORT'] != '80'){echo(":".$_SERVER['SERVER_PORT']);}  ?>" target="_blank"><?php echo $_SERVER['SERVER_NAME']; if($_SERVER['SERVER_PORT'] != '80'){echo(":".$_SERVER['SERVER_PORT']);}?></a>
        </div>
      </div>
      <div class='menu'>
        <input type="checkbox" id="s1" style="display: none;">
        <input type="checkbox" id="s2" style="display: none;">
        <input type="checkbox" id="s3" style="display: none;">
        <div class='menu-profile'>
          <img src='<?php echo($userData['icon']); ?>' class='menu-profile-img' alt="photo">
          <div class='menu-profile-block'>
            <div class='menu-profile-block-name'>
              <div><?php echo($userData['name1'].' '.$userData['name2']);?></div>
              <div class='menu-profile-block-name-shadow'></div>
            </div>
            <div class='menu-profile-block-status'>
              <?php
                if($userData['access'] == 'superuser'){
                  echo('Главный администратор');
                }
                else if($userData['access'] == 'administrator'){
                  echo('Администратор');
                }
                else if($userData['access'] == 'moderator'){
                  echo('Модератор');
                }
                else if($userData['access'] == 'redactor'){
                  echo('Редактор');
                }
                else{
                  echo('Стандартный');
                } ?></div>
          </div>
          <div class='menu-profile-btn'>
            <div class='menu-profile-btn-elem icon-home' title="Главная" onclick="open_panel('#main')"></div>
            <div class='menu-profile-btn-elem icon-reload' title="Обновить" onclick="open_window('#upload')"></div>
            <div class='menu-profile-btn-elem icon-help' title="Документация"></div>
            <div class='menu-profile-btn-elem icon-settings' title="Настройки" onclick="open_window('#settings')"></div>
            <div class='menu-profile-btn-elem icon-exit' title="Выход" onclick='sendExitForm();'></div>
          </div>
        </div>
        <div class='menu-elem-title'>Основное</div>
        <div class='menu-elem-btn' title='Главная' onclick="open_panel('#main')">
          <i class='menu-elem-btn-ico icon-home'></i>
          <span class='menu-elem-btn-text'>Главная</span>
        </div>
        <label for="s1" class='menu-elem-btn' id='menu-elem-btn1' onmouseover="open_nav_elem(this);" title='Разделы'>
          <i class='menu-elem-btn-ico icon-sds'></i>
          <span class='menu-elem-btn-text'>Разделы</span>
          <div class='menu-elem-btn-more'></div>
          <div class='menu-elem-btn-more-block'>
            <div class='menu-elem-btn-more-block-elem' onclick="open_panel('#news')">Новости</div>
            <div class='menu-elem-btn-more-block-elem' onclick="open_panel('#timetable')">Расписание</div>
            <div class='menu-elem-btn-more-block-elem' onclick="open_window('#contacts')">Контакты</div>
            <div class='menu-elem-btn-more-block-elem' onclick="open_panel('#reviews')">Отзывы</div>
            <div class='menu-elem-btn-more-block-elem' onclick="open_panel('#Employees')">Сотрудники</div>
            <div class='menu-elem-btn-more-block-elem' onclick="open_panel('#about_company')">О компании</div>
          </div>
        </label>
        <div class='menu-elem-btn' title='Статистика' onclick="open_panel('#statistics');">
          <i class='menu-elem-btn-ico icon-stat'></i>
          <span class='menu-elem-btn-text'>Статистика</span>
        </div>
        <label class='menu-elem-btn' onclick="open_panel('#file_manager'); finderListing();" title='Проводник'>
          <i class='menu-elem-btn-ico icon-folder'></i>
          <span class='menu-elem-btn-text'>Проводник</span>
        </label>
        <div class='menu-elem-title'>Дополнительно</div>
        <div class='menu-elem-btn' onclick="open_panel('#individual_msg')" title='Сообщения'>
          <i class='menu-elem-btn-ico icon-msg'></i>
          <span class='menu-elem-btn-text'>Сообщения</span>
          <span class='menu-elem-btn-text-count-msg' style='display: none;'>100+</span>
        </div>
        <div class='menu-elem-btn' onclick="open_panel('#general_chat');" title='Общий чат'>
          <i class='menu-elem-btn-ico icon-msg2'></i>
          <span class='menu-elem-btn-text'>Общий чат</span>
          <span class='menu-elem-btn-text-count-msg2' id='globalchat-msg-count'>65</span>
        </div>
        <label for='s3' class='menu-elem-btn' id='menu-elem-btn3' onmouseover="open_nav_elem(this);">
          <i class='menu-elem-btn-ico icon-user'></i>
          <span class='menu-elem-btn-text'>Пользователи</span>
          <div class='menu-elem-btn-more'></div>
          <div class='menu-elem-btn-more-block'>
            <div class='menu-elem-btn-more-block-elem' onclick="open_panel('#add_user')">Добавить нового</div>
            <div class='menu-elem-btn-more-block-elem' onclick="open_panel('#all_user')">Все пользователи</div>
            <div class='menu-elem-btn-more-block-elem' onclick="open_panel('#profile'); updateAccessLogs();">Ваш профиль</div>
          </div>
        </label>
        <div class='menu-elem-btn' title='Служба поддержки' onclick="open_panel('#support_chat')">
          <i class='menu-elem-btn-ico icon-support'></i>
          <span class='menu-elem-btn-text'>Служба поддержки</span>
        </div>
        <div class='menu-elem-btn' title='О программе' onclick="open_window('#about_program')">
          <i class='menu-elem-btn-ico icon-info'></i>
          <span class='menu-elem-btn-text'>О программе</span>
        </div>
      </div>
      <div class='logo-loader' style='visibility: hidden; opacity: 0; transform: translate(0px, 100%)'>
        <div class="loader">
          <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="5" stroke-miterlimit="10"/>
          </svg>
        </div>
        <div class='loader-text'>Загрузка...</div>
      </div>
    </nav>
    <div class='main-shadow' onclick="nav_open()"></div>
    <div style='position: fixed; z-index: 999; top: 0; left: 0;'>
      <div class='menu-elem-btn-more-block-not'>
      </div>
    </div>
    <div class='main' id='mainResize'>
      <div class='main-nav'>
        <div class='main-nav-menu' onclick="nav_open()">
          <div class='main-nav-menu-line'></div>
          <div class='main-nav-menu-line'></div>
          <div class='main-nav-menu-line'></div>
        </div>
        <label for='i1' class='main-nav-search' id='i1_label'>
          <span class='main-nav-search-icon icon-search'></span>
          <input type='text' placeholder="Поиск" id='i1' class='main-nav-search-input'></label>
        <div class='main-nav-profile'>
          <div class='main-nav-profile-mail' onclick="open_win('#mail-nav')">
            <span class='main-nav-profile-mail-icon icon-mail'></span>
            <span class='main-nav-profile-mail-count'></span>
            <div style='display: none; opacity: 0;' id='mail-nav' class='main-nav-profile-mail-block'>
              <div class='main-nav-profile-mail-block-elem'>
                <div class='main-nav-profile-mail-block-elem-title'>Все сообщения</div>
                <div class='main-nav-profile-mail-block-elem-count' id='mail-nav-count'>100+ новых</div>
                <div class='main-nav-profile-mail-block-elem-del' onclick="mailWinAdd({type: 'del'})" id="mail-nav-del">
                  <div>Очистить</div>
                  <div class='main-nav-profile-mail-block-elem-del-line'></div>
                </div>
              </div>
              <div class='main-nav-profile-mail-block-main' id='main-nav-profile-mail-block-main2'>

                <div class='main-nav-profile-mail-block-main-elem0' id='mail-nav-none'>
                  <div class='main-nav-profile-mail-block-main-elem0-block'>
                    <div class='main-nav-profile-mail-block-main-elem0-block-ico' style='background-image: url("media/svg/message.svg");'></div>
                    <div class='main-nav-profile-mail-block-main-elem0-block-text'>Новые сообщения не найдены</div>
                  </div>
                </div>

                <!-- <div class='main-nav-profile-mail-block-main-elem'>
                  <div class='main-nav-profile-mail-block-main-elem-circle icon-msg2' style='background-color: #0abb87;'></div>
                  <div class='main-nav-profile-mail-block-main-elem-text'>
                    <div class='main-nav-profile-mail-block-main-elem-text-name'>Общий чат</div>
                    <div class='main-nav-profile-mail-block-main-elem-text-msg'>65 новых сообщений</div>
                  </div>
                </div>

                <div class='main-nav-profile-mail-block-main-elem'>
                  <div class='main-nav-profile-mail-block-main-elem-circle icon-msg' style='background-color: #fd397a;'></div>
                  <div class='main-nav-profile-mail-block-main-elem-text'>
                    <div class='main-nav-profile-mail-block-main-elem-text-name'>Сообщения</div>
                    <div class='main-nav-profile-mail-block-main-elem-text-msg'>100+ новых сообщений</div>
                  </div>
                </div>

                <div class='main-nav-profile-mail-block-main-elem'>
                  <div class='main-nav-profile-mail-block-main-elem-circle icon-support' style='background-color: #e76d1a;'></div>
                  <div class='main-nav-profile-mail-block-main-elem-text'>
                    <div class='main-nav-profile-mail-block-main-elem-text-name'>Служба поддержки</div>
                    <div class='main-nav-profile-mail-block-main-elem-text-msg'>Это текст сообщения! Просто текст...</div>
                  </div>
                </div> -->

              </div>
              <div class='main-nav-profile-mail-block-more' id='sfd2' style='display: none;' onclick="more_notification('#mail-nav',this,'#main-nav-profile-mail-block-main2')">Развернуть</div>
            </div>
          </div>
          <div class='main-nav-profile-notification' onclick="open_win('#noti-nav')">
            <span class='main-nav-profile-notification-icon icon-notification'></span>
            <span class='main-nav-profile-notification-count'></span>
            <div style='display: none; opacity: 0;' id='noti-nav'class='main-nav-profile-mail-block'>
              <div class='main-nav-profile-mail-block-elem'>
                <div class='main-nav-profile-mail-block-elem-title'>Уведомления</div>
                <div class='main-nav-profile-mail-block-elem-count' id='noti-nav-count'>9 новых</div>
                <div class='main-nav-profile-mail-block-elem-del' id='noti-nav-del' onclick="notificationWinAdd({type: 'del'})">
                  <div>Очистить</div>
                  <div class='main-nav-profile-mail-block-elem-del-line'></div>
                </div>
              </div>
              <div class='main-nav-profile-mail-block-main' id='main-nav-profile-mail-block-main1'>

                <div class='main-nav-profile-mail-block-main-elem0' id='noti-nav-none'>
                  <div class='main-nav-profile-mail-block-main-elem0-block'>
                    <div class='main-nav-profile-mail-block-main-elem0-block-ico'></div>
                    <div class='main-nav-profile-mail-block-main-elem0-block-text'>Новые уведомления не найдены</div>
                  </div>
                </div>

                <!-- <div class='main-nav-profile-mail-block-main-elem1' title='Название файла'>
                  <div class='main-nav-profile-mail-block-main-elem-circleImage'>
                    <div class='main-nav-profile-mail-block-main-elem-circleImage-disk'>
                      <div class='main-nav-profile-mail-block-main-elem-circleImage-disk-none'></div>
                      <div class='main-nav-profile-mail-block-main-elem-circleImage-disk-image'></div>
                      <div class='main-nav-profile-mail-block-main-elem-circleImage-disk-border'></div>
                      <div class='main-nav-profile-mail-block-main-elem-circleImage-disk-border' style='height: 60%; width: 60%;'></div>
                      <div class='main-nav-profile-mail-block-main-elem-circleImage-disk-border' style='height: 40%; width: 40%;'></div>
                      <div class='main-nav-profile-mail-block-main-elem-circleImage-disk-border' style='height: 20%; width: 20%;'></div>
                      <div class='main-nav-profile-mail-block-main-elem-circleImage-disk-line'></div>
                      <div class='main-nav-profile-mail-block-main-elem-circleImage-disk-line' style='transform: rotate(-105deg); background-color: #e6d06a;'></div>
                      <div class='main-nav-profile-mail-block-main-elem-circleImage-disk-line' style='transform: rotate(120deg); background-color: #e6d06a;'></div>
                      <div class='main-nav-profile-mail-block-main-elem-circleImage-disk-line' style='transform: rotate(-62deg); background-color: #e6d06a;'></div>
                      <div class='main-nav-profile-mail-block-main-elem-circleImage-disk-line' style='transform: rotate(190deg); background-color: #d3ae6f;'></div>
                    </div>
                  </div>
                  <div class='main-nav-profile-mail-block-main-elem-text2'>
                    <div class='main-nav-profile-mail-block-main-elem-text-name'>Название файла</div>
                    <div class='main-nav-profile-mail-block-main-elem-text-progressbar'>
                      <div class='main-nav-profile-mail-block-main-elem-text-progressbar-line' style='width: 100%;' value='02:12'></div>
                    </div>
                    <div class='main-nav-profile-mail-block-main-elem-text-btn'>
                      <div class='main-nav-profile-mail-block-main-elem-text-btn-elem icon-reload' title='Повтор'></div>
                      <div class='main-nav-profile-mail-block-main-elem-text-btn-elem icon-forward' title='Предыдущая'></div>
                      <div class='main-nav-profile-mail-block-main-elem-text-btn-elem icon-play' title='Запустить'></div>
                      <div class='main-nav-profile-mail-block-main-elem-text-btn-elem icon-next' title='Следующая'></div>
                      <div class='main-nav-profile-mail-block-main-elem-text-btn-elem icon-volume2' title='Громкость'></div>
                    </div>
                  </div>
                </div> -->

                <!-- <div class='main-nav-profile-mail-block-main-elem'>
                  <div class='main-nav-profile-mail-block-main-elem-circle'></div>
                  <div class='main-nav-profile-mail-block-main-elem-text'>
                    <div class='main-nav-profile-mail-block-main-elem-text-name'>Заголовок</div>
                    <div class='main-nav-profile-mail-block-main-elem-text-msg'>Это текст сообщения! Просто текст...</div>
                  </div>
                </div>

                <div class='main-nav-profile-mail-block-main-elem'>
                  <div class='main-nav-profile-mail-block-main-elem-circle icon-shield' style='background-color: #cb2222;'></div>
                  <div class='main-nav-profile-mail-block-main-elem-text'>
                    <div class='main-nav-profile-mail-block-main-elem-text-name'>Выполнен вход</div>
                    <div class='main-nav-profile-mail-block-main-elem-text-msg'>Россия, Пермь, ip: 188.17.153.138, 20:16</div>
                  </div>
                </div>

                <div class='main-nav-profile-mail-block-main-elem'>
                  <div class='main-nav-profile-mail-block-main-elem-circle icon-fast' style='background-color: #7f36dc;'></div>
                  <div class='main-nav-profile-mail-block-main-elem-text'>
                    <div class='main-nav-profile-mail-block-main-elem-text-name'>Новая запись</div>
                    <div class='main-nav-profile-mail-block-main-elem-text-msg'>Пользователь name опубликовал н...</div>
                  </div>
                </div>

                <div class='main-nav-profile-mail-block-main-elem'>
                  <div class='main-nav-profile-mail-block-main-elem-circle icon-heart'></div>
                  <div class='main-nav-profile-mail-block-main-elem-text'>
                    <div class='main-nav-profile-mail-block-main-elem-text-name'>Заголовок</div>
                    <div class='main-nav-profile-mail-block-main-elem-text-msg'>Это текст сообщения! Просто текст...</div>
                  </div>
                </div>

                <div class='main-nav-profile-mail-block-main-elem'>
                  <div class='main-nav-profile-mail-block-main-elem-circle'></div>
                  <div class='main-nav-profile-mail-block-main-elem-text'>
                    <div class='main-nav-profile-mail-block-main-elem-text-name'>Заголовок</div>
                    <div class='main-nav-profile-mail-block-main-elem-text-msg'>Это текст сообщения! Просто текст...</div>
                  </div>
                </div>

                <div class='main-nav-profile-mail-block-main-elem'>
                  <div class='main-nav-profile-mail-block-main-elem-circle'></div>
                  <div class='main-nav-profile-mail-block-main-elem-text'>
                    <div class='main-nav-profile-mail-block-main-elem-text-name'>Заголовок</div>
                    <div class='main-nav-profile-mail-block-main-elem-text-msg'>Это текст сообщения! Просто текст...</div>
                  </div>
                </div>

                <div class='main-nav-profile-mail-block-main-elem'>
                  <div class='main-nav-profile-mail-block-main-elem-circle'></div>
                  <div class='main-nav-profile-mail-block-main-elem-text'>
                    <div class='main-nav-profile-mail-block-main-elem-text-name'>Заголовок</div>
                    <div class='main-nav-profile-mail-block-main-elem-text-msg'>Это текст сообщения! Просто текст...</div>
                  </div>
                </div>

                <div class='main-nav-profile-mail-block-main-elem'>
                  <div class='main-nav-profile-mail-block-main-elem-circle'></div>
                  <div class='main-nav-profile-mail-block-main-elem-text'>
                    <div class='main-nav-profile-mail-block-main-elem-text-name'>Заголовок</div>
                    <div class='main-nav-profile-mail-block-main-elem-text-msg'>Это текст сообщения! Просто текст...</div>
                  </div>
                </div>

                <div class='main-nav-profile-mail-block-main-elem'>
                  <div class='main-nav-profile-mail-block-main-elem-circle'></div>
                  <div class='main-nav-profile-mail-block-main-elem-text'>
                    <div class='main-nav-profile-mail-block-main-elem-text-name'>Заголовок</div>
                    <div class='main-nav-profile-mail-block-main-elem-text-msg'>Это текст сообщения! Просто текст...</div>
                  </div>
                </div> -->

              </div>
              <div class='main-nav-profile-mail-block-more' id='sfd1' style='display: none;' onclick="more_notification('#noti-nav',this,'#main-nav-profile-mail-block-main1')">Развернуть</div>
            </div>
          </div>
          <div class='main-nav-profile-profile' onclick="open_win('#profile-nav')">
            <img src='<?php echo($userData['icon']); ?>' class='main-nav-profile-profile-img' alt="photo">
            <div class='main-nav-profile-profile-name'><?php echo($userData['name1'].' '.$userData['name2']);?></div>
            <div style='display: none; opacity: 0;' class='main-nav-profile-profile-block' id='profile-nav'>
              <div class='main-nav-profile-profile-block-elem' onclick="open_panel('#profile'); updateAccessLogs();">
                <div class='main-nav-profile-profile-block-elem-ico icon-profile'></div>
                <div class='main-nav-profile-profile-block-elem-text'>Мой профиль</div>
              </div>
              <div class='main-nav-profile-profile-block-elem' id='fullscreenBlock' <?php if($mobileApp):?>style='display: none;'<?php endif;?> onclick="openFullscreen(this)">
                <div class='main-nav-profile-profile-block-elem-ico icon-full' style='font-size: 18px;'></div>
                <div class='main-nav-profile-profile-block-elem-text'>На весь экран</div>
              </div>
              <div class='elemNotiPanelNav main-nav-profile-profile-block-elem' onclick="open_win('#noti-nav')">
                <div class='main-nav-profile-profile-block-elem-ico icon-notification' style='font-size: 18px;'>
                  <div class='main-nav-profile-profile-block-elem-ico-status' id='notificationPanelNavNoti'></div>
                </div>
                <div class='main-nav-profile-profile-block-elem-text'>Уведомления</div>
              </div>
              <div class='elemNotiPanelNav main-nav-profile-profile-block-elem' onclick="open_win('#mail-nav')">
                <div class='main-nav-profile-profile-block-elem-ico icon-mail' style='font-size: 18px;'>
                  <div class='main-nav-profile-profile-block-elem-ico-status' id='notificationPanelNavMail'></div>
                </div>
                <div class='main-nav-profile-profile-block-elem-text'>Почта</div>
              </div>
              <div class='main-nav-profile-profile-block-elem' onclick="open_panel('#individual_msg')">
                <div class='main-nav-profile-profile-block-elem-ico icon-msg' style='font-size: 18px;'></div>
                <div class='main-nav-profile-profile-block-elem-text'>Сообщения</div>
              </div>
              <div class='main-nav-profile-profile-block-elem' onclick="open_window('#settings')">
                <div class='main-nav-profile-profile-block-elem-ico icon-settings' style='font-size: 18px;'></div>
                <div class='main-nav-profile-profile-block-elem-text'>Настройка</div>
              </div>
              <div class='main-nav-profile-profile-block-line'></div>
              <div class='main-nav-profile-profile-block-elem' onclick='sendExitForm();'>
                <div class='main-nav-profile-profile-block-elem-ico icon-exit' style='font-size: 18px; color: #fd397a;'></div>
                <div class='main-nav-profile-profile-block-elem-text'>Выйти</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class='main-conteiner'>
        <div class='panel' id='main' search-js-elem='Главная, section-block, #main, 🌍, Общая информация, [главная, основная, графики, стата, грфики, статистика, Посещения за последние 48 часов, Посещений за день, Всего посещений, Среднее время на сайте, Пользователей, Количество посещений, за последние 30 дней, за последние 12 месяцев, Прокрутка страниц]' style='<?php echo($page[0][1]);?>'>
          <div class='panel-title'>Главная</div>
          <?php if($detect->isAndroidOS() && !$mobileApp):?>
            <div class='panel-conteiner-tel'>
              <div class='panel-conteiner-tel-text'>
                <div class='panel-conteiner-tel-text-title'>Управляй будущим компании!</div>
                <div class='panel-conteiner-tel-text-text'>Пользуйся админкой в мобильном приложении на смартфонах Android</div>
                <div class='panel-conteiner-tel-text-btn'>Скачать</div>
              </div>
              <div class='panel-conteiner-tel-img' style='background-image: url("media/img/Samsung_S10_sq53hjg4.png")'>
                <div class='panel-conteiner-tel-img-2' style='background-image: url("media/img/Samsung_S10_sq53hjg5.png")'></div>
              </div>
            </div>
          <?php endif; ?>
          <div class='panel-conteiner-width'>
            <div class='panel-conteiner-main-block'>
              <div class='panel-conteiner-main-block-title'>Посещения за последние 48 часов</div>
              <div class='panel-conteiner-main-block-info'>
                <div class='panel-conteiner-main-block-info-block' style='margin-right: 20px;'>
                  <div class='panel-conteiner-main-block-info-block-count' style='color: #5d78ff;' id='main-stat-chart-big-f1'>14k+</div>
                  <div class='panel-conteiner-main-block-info-block-desc'>Посещений за день</div>
                </div>
                <div class='panel-conteiner-main-block-info-block'>
                  <div class='panel-conteiner-main-block-info-block-count' style='color: #0abb87;' id='main-stat-chart-big-f2'>152.5k+</div>
                  <div class='panel-conteiner-main-block-info-block-desc'>Всего посещений</div>
                </div>
              </div>
              <div class='panel-conteiner-main-block-chart' id='chart1'></div> <!-- Посещения за посл 48 часов -->
            </div>
          </div>
          <div class='panel-conteiner-width'>
            <div class='panel-conteiner-width-small'>
              <div class='panel-conteiner-width-small-main'>
                <div class='panel-conteiner-width-small-main-elem'>
                  <div class='panel-conteiner-width-small-main-elem-block1'>00:12:35</div>
                  <div class='panel-conteiner-width-small-main-elem-block2'>
                    Среднее время на сайте
                    <div class="description1"></div>
                    <div class="window-block-settings-block-description">
                      <div class="window-block-settings-block-description-title">Подсказка</div>
                      <div class="window-block-settings-block-description-text"><b>TSS — time spent on site</b> — время, проведённое пользователем на сайте. Этот ПФ (поведенческий фактор) учитывается поисковыми системами при ранжировании ресурса. Пользователь может обойти все страницы, но какой в этом толк, если он потратил на это 10 секунд? Небольшое время на сайте — показатель низкой вовлеченности посетителей. Выходит, ресурс неактуален и неинтересен для них. Как результат — выйти в топ вряд ли удастся. Вы же этого не хотите?<br><br><b><a href='#'>Как улучшить этот поведенческий фактор и мотивировать посетителей проводить на сайте как можно больше времени?</a></b></div>
                    </div>
                  </div>
                </div>
                <div class='panel-conteiner-width-small-main-elem2'>
                  <div class='panel-conteiner-width-small-main-elem2-time' title='Текущее время'>
                    <div class='panel-conteiner-width-small-main-elem2-time-minute'></div>
                    <div class='panel-conteiner-width-small-main-elem2-time-sentinel'></div>
                  </div>
                </div>
              </div>
              <div class='panel-conteiner-width-small-footer'>
                <div class='panel-conteiner-width-small-footer-elem1'>
                  <span class='panel-conteiner-width-small-footer-elem1-span icon-line_top' style='margin-right: 3px;'></span>
                  <span class='panel-conteiner-width-small-footer-elem1-span' title='Больше на 45%, чем вчера' id='main-stat-field-1'>
                    <span>Больше</span> на 45%
                  </span>
                </div>
                <div class='panel-conteiner-width-small-footer-elem2' onclick="open_panel('#statistics');">
                  <span class='panel-conteiner-width-small-footer-elem2-span'>Подробнее</span>
                  <span class='panel-conteiner-width-small-footer-elem2-ico icon-left'></span>
                </div>
              </div>
            </div>
            <div class='panel-conteiner-width-small2'>
              <div class='panel-conteiner-width-small2-main'>
                <div class="description1-ab" style='left: 5px; top: 6px;'></div>
                <div class="window-block-settings-block-description" style='left: 40px; top: 84px;'>
                  <div class="window-block-settings-block-description-title">Подсказка</div>
                  <div class="window-block-settings-block-description-text">Данные в графике представлены за последние 4 месяца.<br><br><?php if($statisticsPanel): ?><a onclick="open_window()" style='border-bottom: 1px dashed var(--color); cursor: pointer;'>Открыть полную статистику</a><?php else: ?><b>Для полного просмотра данных о регистрации, необходимо разблокировать раздел <a style='border-bottom: 1px dashed var(--color); cursor: pointer;' onclick="open_panel('#statistics');">статистики</a></b><?php endif; ?></div>
                </div>
                <div class='panel-conteiner-width-small2-header'>
                  <div class='panel-conteiner-width-small2-header-chart' id='chart2'></div> <!-- График регистраций пользователей -->
                </div>
                <div class='panel-conteiner-width-small2-text'>
                  <div class='panel-conteiner-width-small2-text-text'>
                    <div class='panel-conteiner-width-small2-text-text1'><?= count($reg_stat); ?></div>
                    <div class='panel-conteiner-width-small2-text-text2'>
                      <?=num_decline(count($reg_stat), 'Пользователь, Пользователя, Пользователей')?>
                    </div>
                  </div>
                  <div class='panel-conteiner-width-small2-text-ico icon-user'></div>
                </div>
              </div>
            </div>
            <div class='panel-conteiner-width-small3'>
              <div class='panel-conteiner-width-small3-title'>Количество посещений</div>
              <div class='panel-conteiner-width-small3-count'>
                <span id='panel-conteiner-width-small3-count-id'>1 520</span>
                <span class='panel-conteiner-width-small3-count-percent' style='color: #fd3939; transition: all 0.15s ease 0s;' title='Отношение количества посещений прошлого месяца/года к настоящему'>-0.25%</span>
              </div>
              <div class='panel-conteiner-width-small3-type'>
                <span class='panel-conteiner-width-small3-type-elem'>
                  <span>за последние 30 дней</span>
                  <span>за последние 12 месяцев</span>
                </span>
                <span onclick="change_type_chart3('.panel-conteiner-width-small3-type-elem','top',this)" id='main-stat-gf75ddf' class='icon-left panel-conteiner-width-small3-type-btn' style='opacity: 0.225; cursor: default; transform: rotate(90deg);line-height: 9px;transform-origin: 8px 5px;'></span>
                <span onclick="change_type_chart3('.panel-conteiner-width-small3-type-elem','bottom',this)" id='main-stat-gfh4sdt3' class='icon-left panel-conteiner-width-small3-type-btn' style='transform: rotate(-90deg);line-height: 11px;transform-origin: 7px 5px;margin-left: 5px;'></span>
              </div>
              <div class='panel-conteiner-width-small3-chart' id='chart3'></div>
            </div>
            <div class='panel-conteiner-width-small4'>
              <div class='panel-conteiner-width-small4-ico'>
                <div class='panel-conteiner-width-small4-ico-scroll_icon'>
                  <div class='panel-conteiner-width-small4-ico-scroll_icon-wheel'></div>
                </div>
              </div>
              <div class='panel-conteiner-width-small4-text'>
                <span class='panel-conteiner-width-small4-text-span'>
                  <div class='panel-conteiner-width-small4-text-title'>Прокрутка страниц</div>
                  <div class='panel-conteiner-width-small4-text-count'>70%</div>
                  <div class='panel-conteiner-width-small4-text-progressbar'>
                    <div class='panel-conteiner-width-small4-text-progressbar-status' style="width: 70%;"></div>
                  </div>
                  <div class='panel-conteiner-width-small4-text-desc' id='main-stat-scrolling-field-1'>Больше на 15%, чем вчера</div>

                </span>
              </div>
            </div>
          </div>
        </div>
        <?php if ($statisticsPanel): ?>
        <div class='panel' id='statistics' search-js-elem='Статистика, section-block, #statistics, 📈, Графики и диаграммы, [графики, грфики, статистика, пользователи]' style='<?php echo($page[1][1]);?>'>
          <div class='panel-title'>Статистика</div>
          <div class='panel-conteiner-all'>
            <div class='panel-conteiner-main-block'>
              <div class='panel-conteiner-main-block-title'>
                Посещения за период от
                <input class='panel-conteiner-main-block-input' min="2017-01-01" max="<?php echo date('Y-m-d'); ?>" type='date' value="<?php $dateStat = new DateTime('-2 month'); echo $dateStat->format('Y-m-d'); ?>">
                по
                <input class='panel-conteiner-main-block-input' min="2017-01-01" max="<?php echo date('Y-m-d'); ?>" type='date' value="<?php echo date('Y-m-d'); ?>">
              </div>
              <div class='panel-conteiner-main-block-info'>
                <div class='panel-conteiner-main-block-info-block' style='margin-right: 20px;'>
                  <div class='panel-conteiner-main-block-info-block-count' style='color: #5d78ff;'>14 200</div>
                  <div class='panel-conteiner-main-block-info-block-desc'>Посещений за период</div>
                </div>
                <div class='panel-conteiner-main-block-info-block'>
                  <div class='panel-conteiner-main-block-info-block-count' style='color: #0abb87;'>152 500</div>
                  <div class='panel-conteiner-main-block-info-block-desc'>Всего посещений</div>
                </div>
              </div>
              <div class='panel-conteiner-main-block-chart' id='chart4'></div> <!-- Посещения за посл 48 часов -->
            </div>
            <span style='margin-top: 40px; display: block;'>
              <div class='panel-conteiner-main-block_divide_by_4' style='margin-right: 37px;'>
                <div class='panel-conteiner-main-block-title'>Возраст пользователей</div>
                <div class='panel-conteiner-main-block_divide_by_4-conteiner'>
                  <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart' id='chart5'></div>
                  <div class='panel-conteiner-main-block_divide_by_4-conteiner-text'>
                    <span  class='panel-conteiner-main-block_divide_by_4-conteiner-text-span'>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-point' style='background-color: #5d78ff;'></span>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-desc'>Младше 18 лет</span>
                    </span>
                    <span  class='panel-conteiner-main-block_divide_by_4-conteiner-text-span'>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-point' style='background-color: #0abb87;'></span>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-desc'>18 - 24 года</span>
                    </span>
                    <span  class='panel-conteiner-main-block_divide_by_4-conteiner-text-span'>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-point' style='background-color: #00bcd4;'></span>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-desc'>25 - 34 года</span>
                    </span>
                    <span  class='panel-conteiner-main-block_divide_by_4-conteiner-text-span'>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-point' style='background-color: #ffb822;'></span>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-desc'>35 - 44 года</span>
                    </span>
                    <span  class='panel-conteiner-main-block_divide_by_4-conteiner-text-span'>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-point' style='background-color: #fd397a;'></span>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-desc'>45 и старше</span>
                    </span>
                  </div>
                </div>
              </div>
              <div class='panel-conteiner-main-block_divide_by_4' style='margin-right: 37px;'>
                <div class='panel-conteiner-main-block-title'>Пол пользователей</div>
                <div class='panel-conteiner-main-block_divide_by_4-conteiner'>
                  <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart' id='chart6'></div>
                  <div class='panel-conteiner-main-block_divide_by_4-conteiner-text' style='margin-top: 30px;'>
                    <span  class='panel-conteiner-main-block_divide_by_4-conteiner-text-span'>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-point' style='background-color: #5d78ff;'></span>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-desc'>Мужской</span>
                    </span>
                    <span  class='panel-conteiner-main-block_divide_by_4-conteiner-text-span'>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-point' style='background-color: #ffb822;'></span>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-desc'>Женский</span>
                    </span>
                  </div>
                </div>
              </div>
              <div class='panel-conteiner-main-block_divide_by_4' style='margin-right: 36px;'>
                <div class='panel-conteiner-main-block-title'>Авторизация</div>
                <div class='panel-conteiner-main-block_divide_by_4-conteiner'>
                  <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart' id='chart7'></div>
                  <div class='panel-conteiner-main-block_divide_by_4-conteiner-text' style='margin-top: 20px;'>
                    <span  class='panel-conteiner-main-block_divide_by_4-conteiner-text-span'>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-point' style='background-color: #0abb87;'></span>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-desc'>Авторизированных</span>
                    </span><br>
                    <span  class='panel-conteiner-main-block_divide_by_4-conteiner-text-span'>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-point' style='background-color: #5d78ff;'></span>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-desc'>Не авторизированных</span>
                    </span>
                  </div>
                </div>
              </div>
              <div class='panel-conteiner-main-block_divide_by_4'>
                <div class='panel-conteiner-main-block-title'>
                  <span>Вернувшиеся пользователи</span>
                  <div class='description1'></div>
                  <div class='window-block-settings-block-description'>
                    <div class='window-block-settings-block-description-title'>Подсказка</div>
                    <div class='window-block-settings-block-description-text'><b>RV — returning visitors</b> - вернувшиеся пользователи. Почему важно повышать этот показатель?<br><br>Вернувшиеся пользователи с большей долей вероятности совершат покупку/закажут услугу снова. Они ваша постоянная аудитория и помощники в развитии ресурса.<br><br>Следите за их поведением, чтобы понять, верные ли изменения вы делаете на сайте. Новые пользователи отражают первое впечатление, вернувшиеся смотрят глубже и дают больше поводов для беспокойства.</div>
                  </div>
                </div>
                <div class='panel-conteiner-main-block_divide_by_4-conteiner'>
                  <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart' id='chart8'></div>
                  <div class='panel-conteiner-main-block_divide_by_4-conteiner-text' style='margin-top: 20px;'>
                    <span  class='panel-conteiner-main-block_divide_by_4-conteiner-text-span'>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-point' style='background-color: #6b5eae;'></span>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-desc'>Вернувшихся</span>
                    </span><br>
                    <span  class='panel-conteiner-main-block_divide_by_4-conteiner-text-span'>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-point' style='background-color: #00bcd4;'></span>
                      <span class='panel-conteiner-main-block_divide_by_4-conteiner-text-desc'>Очень редкие</span>
                    </span>
                  </div>
                </div>
              </div>
            </span>
            <span style='margin-top: 40px; display: block;'>
              <div class='panel-conteiner-main-block_divide_by_4_2' style='box-shadow: none; margin-right: 37px;'>
                <div class="panel-conteiner-width-small" style='width: 100%; background-color: #ffb822; color: #303036;'>
                  <div class="panel-conteiner-width-small-main">
                    <div class="panel-conteiner-width-small-main-elem">
                      <div class="panel-conteiner-width-small-main-elem-block1">12.5 <span style='font-family: pfl; font-weight: 700;'>страниц</span></div>
                      <div class="panel-conteiner-width-small-main-elem-block2" style='color: #303036;'>
                        Глубина просмотра сайта
                        <div class="description1"></div>
                        <div class="window-block-settings-block-description">
                          <div class="window-block-settings-block-description-title">Подсказка</div>
                          <div class="window-block-settings-block-description-text"><b>PPV — pages per visit</b> — это глубина просмотра сайта, т. е. количество страниц, просмотренных за одно его посещение. Отражает заинтересованность посетителей в контенте ресурса. В редких случаях —свидетельствует о проблемах с навигацией, когда пользователям приходится долго переходить по разным вкладкам, чтобы найти то, что нужно. Этот фактор тесно связан с временем, которое пользователь проводит на сайте. Чем больше он находит интересных страниц, тем дальше пойдет по сайту и тем больше времени проведет на нем.<br><br><b><a href='#'>Как увеличить глубину просмотра сайта?</a></b></div>
                        </div>
                      </div>
                    </div>
                    <div class="panel-conteiner-width-small-main-elem2 icon-eye">
                      <!-- <div class="panel-conteiner-width-small-main-elem2-time" title="Текущее время: 23:13:24">
                        <div class="panel-conteiner-width-small-main-elem2-time-minute" style="transform: translate(-50%, 2px) rotate(80.4deg);"></div>
                        <div class="panel-conteiner-width-small-main-elem2-time-sentinel" style="transform: translate(-50%, 6px) rotate(696.5deg);"></div>
                      </div> -->
                    </div>
                  </div>
                  <div class="panel-conteiner-width-small-footer">
                    <div class="panel-conteiner-width-small-footer-elem1">
                      <span class="panel-conteiner-width-small-footer-elem1-span icon-line_top" style="margin-right: 3px;"></span>
                      <span class="panel-conteiner-width-small-footer-elem1-span" title="Больше на 45%, чем вчера">
                        <span>Больше</span> на 45%
                      </span>
                    </div>
                    <div class="panel-conteiner-width-small-footer-elem2">
                      <span class="panel-conteiner-width-small-footer-elem2-span">Подробнее</span>
                      <span class="panel-conteiner-width-small-footer-elem2-ico icon-left"></span>
                    </div>
                  </div>
                </div>
                <div class="panel-conteiner-width-small" style='width: 100%; margin-top: 40px; background-color: #0abb87;'>
                  <div class="panel-conteiner-width-small-main">
                    <div class="panel-conteiner-width-small-main-elem">
                      <div class="panel-conteiner-width-small-main-elem-block1">00:12:35</div>
                      <div class="panel-conteiner-width-small-main-elem-block2">
                        Среднее время на сайте
                        <div class="description1"></div>
                        <div class="window-block-settings-block-description">
                          <div class="window-block-settings-block-description-title">Подсказка</div>
                          <div class="window-block-settings-block-description-text"><b>TSS — time spent on site</b> — время, проведённое пользователем на сайте. Этот ПФ (поведенческий фактор) учитывается поисковыми системами при ранжировании ресурса. Пользователь может обойти все страницы, но какой в этом толк, если он потратил на это 10 секунд? Небольшое время на сайте — показатель низкой вовлеченности посетителей. Выходит, ресурс неактуален и неинтересен для них. Как результат — выйти в топ вряд ли удастся. Вы же этого не хотите?<br><br><b><a href='#'>Как улучшить этот поведенческий фактор и мотивировать посетителей проводить на сайте как можно больше времени?</a></b></div>
                        </div>
                      </div>
                    </div>
                    <div class="panel-conteiner-width-small-main-elem2">
                      <div class="panel-conteiner-width-small-main-elem2-time" title="Текущее время: 23:13:24">
                        <div class="panel-conteiner-width-small-main-elem2-time-minute" style="transform: translate(-50%, 2px) rotate(80.4deg);"></div>
                        <div class="panel-conteiner-width-small-main-elem2-time-sentinel" style="transform: translate(-50%, 6px) rotate(696.5deg);"></div>
                      </div>
                    </div>
                  </div>
                  <div class="panel-conteiner-width-small-footer">
                    <div class="panel-conteiner-width-small-footer-elem1">
                      <span class="panel-conteiner-width-small-footer-elem1-span icon-line_top" style="margin-right: 3px;"></span>
                      <span class="panel-conteiner-width-small-footer-elem1-span" title="Больше на 45%, чем вчера">
                        <span>Больше</span> на 45%
                      </span>
                    </div>
                    <div class="panel-conteiner-width-small-footer-elem2">
                      <span class="panel-conteiner-width-small-footer-elem2-span">Подробнее</span>
                      <span class="panel-conteiner-width-small-footer-elem2-ico icon-left"></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class='panel-conteiner-main-block_divide_by_4_3'>
                <div class='panel-conteiner-main-block_divide_by_4_3-block'>
                  <div class="panel-conteiner-main-block-title">
                    Показатель отказов
                    <div class="description1"></div>
                    <div class="window-block-settings-block-description">
                      <div class="window-block-settings-block-description-title">Подсказка</div>
                      <div class="window-block-settings-block-description-text"><b>BR — bounce rate</b>— показатель отказов. Это доля посетителей, которые покинули сайт сразу, как только перешли на него, т. е. в рамках визита они просмотрели лишь одну страницу.<br><br>Если тематика вашего сайта предполагает быстрое совершение целевого действия (например, когда посетитель заходит на первую страницу сайта и заказывает пиццу по телефону), высокий показатель BR не критичен. В других случаях чем bounce rate выше, тем больше вопросов нужно задать рекламщику.<br><br><b><a href='#'>Как уменьшить показатель отказов на сайте?</a></b></div>
                    </div>
                  </div>
                  <div class='panel-conteiner-main-block_divide_by_4-conteiner'>
                    <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart1' id='chart9'></div>
                  </div>
                </div>
                <div class='panel-conteiner-main-block_divide_by_4_3-block' style='margin-left: 40px;'>
                  <div class="panel-conteiner-main-block-title">Тип устройств</div>
                  <div class='panel-conteiner-main-block_divide_by_4-conteiner'>
                    <div class='panel-conteiner-main-block_divide_by_4-conteiner-table'>
                      <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-title'>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-title-elem' style='margin-left: 0px; width: 37.1px; border-right: 1px solid var(--border-color);'>
                          <span>№</span>
                          <span class="window-block-main-table-main-elem-arrow icon-left"></span>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-title-elem' style='margin-left: -4px; width: calc(100% - 149px); border-right: 1px solid var(--border-color);'>
                          <span>Модель</span>
                          <span class="window-block-main-table-main-elem-arrow icon-left"></span>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-title-elem' style='margin-left: -4px; width: 50px; border-right: 0px solid var(--border-color);'>
                          <span>%</span>
                          <span class="window-block-main-table-main-elem-arrow icon-left"></span>
                        </div>
                      </div>
                      <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main'>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-1'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: 0px; width: 37.1px;'>
                            <span style='background-color: #5d78ff;' class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem-color'></span>
                            <span>1</span>
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: calc(100% - 149px);'>
                            iphone (Apple)
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: 50px;'>
                            40%
                          </div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-2'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: 0px; width: 37.1px;'>
                            <span style='background-color: #00bcd4;' class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem-color'></span>
                            <span>2</span>
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: calc(100% - 149px);'>
                            Samsung (Samsung)
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: 50px;'>
                            35%
                          </div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-1'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: 0px; width: 37.1px;'>
                            <span style='background-color: #fd397a;' class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem-color'></span>
                            <span>3</span>
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: calc(100% - 149px);'>
                            OnePlus (OnePlus)
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: 50px;'>
                            15%
                          </div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-2'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: 0px; width: 37.1px;'>
                            <span style='background-color: #0abb87;' class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem-color'></span>
                            <span>4</span>
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: calc(100% - 149px);'>
                            Sony (Sony)
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: 50px;'>
                            7.5%
                          </div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-1'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: 0px; width: 37.1px;'>
                            <span style='background-color: #ffc343;' class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem-color'></span>
                            <span>5</span>
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: calc(100% - 149px);'>
                            NokiaLumia (Nokia)
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: 50px;'>
                            2.5%
                          </div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-2'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: 0px; width: 37.1px;'>
                            <span style='background-color: transparent;' class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem-color'></span>
                            <span>6</span>
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: calc(100% - 149px);'>
                            LG (LG)
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: 50px;'>
                            1.5%
                          </div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-1'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: 0px; width: 37.1px;'>
                            <span style='background-color: transparent;' class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem-color'></span>
                            <span>7</span>
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: calc(100% - 149px);'>
                            Fly (FLY)
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: 50px;'>
                            1.2%
                          </div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-2'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: 0px; width: 37.1px;'>
                            <span style='background-color: transparent;' class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem-color'></span>
                            <span>8</span>
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: calc(100% - 149px);'>
                            INOI (INOI)
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: 50px;'>
                            1%
                          </div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-1'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: 0px; width: 37.1px;'>
                            <span style='background-color: transparent;' class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem-color'></span>
                            <span>9</span>
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: calc(100% - 149px);'>
                            Honor (Huawei)
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-table-main-elem-elem' style='margin-left: -4px; width: 50px;'>
                            0.9%
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart-elem'>

                      <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart-type_model'>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart-type_devices-elem' style='height: 40%; background-color: #5d78ff;'>
                          <div style="color: #5d78ff;">
                            <div style='border: 2px solid #5d78ff;'>iphone: 40%</div>
                          </div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart-type_devices-elem' style='height: 35%; background-color: #00bcd4;'>
                          <div style="color: #00bcd4;" >
                            <div style='border: 2px solid #00bcd4;'>Samsung: 35%</div>
                          </div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart-type_devices-elem' style='height: 15%; background-color: #fd397a;'>
                          <div style="color: #fd397a;">
                            <div style='border: 2px solid #fd397a;'>OnePlus: 15%</div>
                          </div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart-type_devices-elem' style='height: 7.5%; background-color: #0abb87;'>
                          <div style="color: #0abb87;">
                            <div style='border: 2px solid #0abb87;'>Sony: 7.5%</div>
                          </div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart-type_devices-elem' style='height: 2.5%; background-color: #ffc343;'>
                          <div style="color: #ffc343;">
                            <div style='border: 2px solid #ffc343;'>NokiaLumia: 2.5%</div>
                          </div>
                        </div>

                      </div>
                      <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart-type_devices'>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart-type_devices-elem' style='height: 50%; background-color: #5d78ff;'>
                          <div>
                            <div>Телефон: 50%</div>
                          </div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart-type_devices-elem' style='height: 15%; background-color: #ffb822;'>
                          <div style="color: #ffb822;" >
                            <div style='border: 2px solid #ffb822;'>Телефон: 15%</div>
                          </div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-chart-type_devices-elem' style='height: 35%; background-color: #fd397a;'>
                          <div style="color: #fd397a;">
                            <div style='border: 2px solid #fd397a;'>Телефон: 35%</div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </span>
            <span style='margin-top: 40px; display: block;'>
              <div class='panel-conteiner-width2'>
                <div class='panel-conteiner-main-block'>
                  <div class='panel-conteiner-main-block-title'>
                    <span>Органический трафик</span>
                    <div class='description1'></div>
                    <div class='window-block-settings-block-description'>
                      <div class='window-block-settings-block-description-title'>Подсказка</div>
                      <div class='window-block-settings-block-description-text'><b>VPK — visits per keyword</b> — объем органического трафика, т. е. количество пользователей, перешедших на сайт из поисковых систем.<br><br>SEO-специалистам этот показатель помогает корректировать стратегию продвижения, а вам — понимать, какой контент интересен аудитории и в каком направлении нужно двигаться.</div>
                    </div>
                  </div>
                  <div class='panel-conteiner-main-block_divide_by_4-conteiner' style='margin-top: 20px; height: 328px;' id='chart10'></div>
                </div>
              </div>
              <div class='panel-conteiner-width2' style='margin-left: 40px;'>
                <div class='panel-conteiner-main-block'>
                  <!-- Ol -->
                  <div class='panel-conteiner-main-block-title'>
                    <span>Органический прирост</span>
                    <div class='description1'></div>
                    <div class='window-block-settings-block-description'>
                      <div class='window-block-settings-block-description-title'>Подсказка</div>
                      <div class='window-block-settings-block-description-text'><b>OL — organic likes</b> — органический прирост аудитории. Это те люди, которые нашли вас в соцсетях самостоятельно и добровольно подписались на вас, без рекламного участия. Демонстрирует качество публикуемого контента и частично знание бренда.</div>
                    </div>
                  </div>
                  <div class='panel-conteiner-main-block_divide_by_4-conteiner' style='margin-top: 20px; height: 328px;' id='chart11'></div>
                </div>
              </div>
            </span>
            <span style='margin-top: 40px; display: block;'>
              <div class='panel-conteiner-main-block' style='height: auto;'>
                <div class='panel-conteiner-main-block-hei139' style='border-right: 1px solid var(--border-color)'>
                  <div class='panel-conteiner-main-block-hei139-conteiner'>
                    <div class='panel-conteiner-main-block-hei139-conteiner-line'>
                      <div class='panel-conteiner-main-block-hei139-conteiner-line-ico icon-lid' style='color: #5d78ff;'></div>
                      <div class='panel-conteiner-main-block-hei139-conteiner-line-text'>2 368</div>
                    </div>
                    <div class='panel-conteiner-main-block-hei139-conteiner-line' style='margin-top: 10px;'>
                      <span>Закрытие лидов</span>
                      <div class='description1'></div>
                      <div class='window-block-settings-block-description'>
                        <div class='window-block-settings-block-description-title'>Подсказка</div>
                        <div class='window-block-settings-block-description-text'><b>LCR — lead-close rate</b> — коэффициент закрытия лидов. Сколько людей были готовы купить/скачать/подписаться? А сколько сделали это в итоге и почему не 100 %? Ответы вам придётся найти самостоятельно, а вместе с ними увидеть все пробелы в рекламной кампании. Проводя ежемесячный расчет, вы поймете, насколько целевой трафик получаете и качественно ли обрабатывает заявки ваш отдел продаж. LCR рассчитывается как отношение количества клиентов за отчётный период к общему количеству лидов за отчётный период.</div>
                      </div>
                    </div>
                    <div class='panel-conteiner-main-block-hei139-conteiner-progressbar'>
                      <div class='panel-conteiner-main-block-hei139-conteiner-progressbar-progress' data-info='12% пользователей' style='width: 12%; background-color: #5d78ff;'></div>
                    </div>
                  </div>
                </div>
                <div class='panel-conteiner-main-block-hei139' style='border-right: 1px solid var(--border-color)'>
                  <div class='panel-conteiner-main-block-hei139-conteiner'>
                    <div class='panel-conteiner-main-block-hei139-conteiner-line'>
                      <div class='panel-conteiner-main-block-hei139-conteiner-line-ico icon-loyal' style='color: #5d78ff;'></div>
                      <div class='panel-conteiner-main-block-hei139-conteiner-line-text'>12 501</div>
                    </div>
                    <div class='panel-conteiner-main-block-hei139-conteiner-line' style='margin-top: 10px;'>
                      <span>Лояльные пользователи</span>
                      <div class='description1'></div>
                      <div class='window-block-settings-block-description'>
                        <div class='window-block-settings-block-description-title'>Подсказка</div>
                        <div class='window-block-settings-block-description-text'><b>NPS — net promoter score</b> — индекс лояльности, который используется для определения удовлетворенности потребителей товаром, сервисом или брендом. Другими словами, это процент пользователей, готовых рекомендовать вашу продукцию. Как ни развивается маркетинг, как ни нативится реклама, а сарафанное радио остаётся самым убедительным инструментом. Чем больше пользователей настроятся на вашу волну, тем выше прибыль.
                          <br>
                          <br>
                          NPS вычисляется с помощью опросов пользователей, по результатам которого их можно разделить на три группы:
                          <ul>
                            <li><b>Промоутеры</b> — лояльные клиенты, готовы рекомендовать</li>
                            <li><b>Нейтралы — клиенты</b>, которые в целом удовлетворены, но имеют некоторые замечания, не будут рекомендовать</li>
                            <li><b>Критики</b> — не удовлетворены, рекомендовать не будут</li>
                          </ul>
                          NPS рассчитывается как разница между долей промоутеров и долей критиков. Нейтралы в расчете NPS не участвуют.
                        </div>
                      </div>
                    </div>
                    <div class='panel-conteiner-main-block-hei139-conteiner-progressbar'>
                      <div class='panel-conteiner-main-block-hei139-conteiner-progressbar-progress' data-info='50% пользователей' style='width: 50%; background-color: #5d78ff;'></div>
                    </div>
                  </div>
                </div>
                <div class='panel-conteiner-main-block-hei139' style='border-right: 1px solid var(--border-color)'>
                  <div class='panel-conteiner-main-block-hei139-conteiner'>
                    <div class='panel-conteiner-main-block-hei139-conteiner-line'>
                      <div class='panel-conteiner-main-block-hei139-conteiner-line-ico icon-like' style='color: #5d78ff;'></div>
                      <div class='panel-conteiner-main-block-hei139-conteiner-line-text'>6 057</div>
                    </div>
                    <div class='panel-conteiner-main-block-hei139-conteiner-line' style='margin-top: 10px;'>
                      <span>Вовлеченность</span>
                      <div class='description1'></div>
                      <div class='window-block-settings-block-description'>
                        <div class='window-block-settings-block-description-title'>Подсказка</div>
                        <div class='window-block-settings-block-description-text'><b>ER — engagement rate</b> — уровень вовлечения посетителей. Высокий уровень вовлечённости пользователей говорит о качестве и востребованности ресурса, что улучшает поведенческие факторы ранжирования сайта.<br><br>ER рассчитывается как отношение количества действий на странице/сайте (скроллинг до N %, комментарии, заполнение формы и др.) к числу просмотров страницы/сайта (измеряется в процентах).</div>
                      </div>
                    </div>
                    <div class='panel-conteiner-main-block-hei139-conteiner-progressbar'>
                      <div class='panel-conteiner-main-block-hei139-conteiner-progressbar-progress' data-info='83% пользователей' style='width: 83%; background-color: #5d78ff;'></div>
                    </div>
                  </div>
                </div>
                <div class='panel-conteiner-main-block-hei139'style='width: calc(100% / 4 + 2px);'>
                  <div class='panel-conteiner-main-block-hei139-conteiner'>
                    <div class='panel-conteiner-main-block-hei139-conteiner-line'>
                      <div class='panel-conteiner-main-block-hei139-conteiner-line-ico icon-download' style='color: #5d78ff;'></div>
                      <div class='panel-conteiner-main-block-hei139-conteiner-line-text'>351</div>
                    </div>
                    <div class='panel-conteiner-main-block-hei139-conteiner-line' style='margin-top: 10px;'>
                      <span>Скачиваний</span>
                    </div>
                    <div class='panel-conteiner-main-block-hei139-conteiner-progressbar'>
                      <div class='panel-conteiner-main-block-hei139-conteiner-progressbar-progress' data-info='4% скачиваний' style='width: 4%; background-color: #5d78ff;'></div>
                    </div>
                  </div>
                </div>
              </div>
            </span>
            <span style='margin-top: 40px; display: block;'>
              <div class='panel-conteiner-width2'>
                <div class='panel-conteiner-main-block'>
                  <div class='panel-conteiner-main-block-title'>
                    <span>Статистика по новостям</span>
                    <div class='description1'></div>
                    <div class='window-block-settings-block-description'>
                      <div class='window-block-settings-block-description-title'>Подсказка</div>
                      <div class='window-block-settings-block-description-text'><b>Новости</b> - в данном разделе вы можете мониторить статистику по новостным записям, отслеживать качество статей, получать рекомендации к их продвежению.</div>
                    </div>
                  </div>
                  <div class='panel-conteiner-main-block_divide_by_4-conteiner' style='margin-top: 20px; height: 328px;'>
                    <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-search'>
                      <label for="searchmsg1" class="main-nav-search-2" style='margin-left: 0px; margin-top: 0px;width: 100%;'>
                        <span class="main-nav-search-icon icon-search"></span>
                        <input type="text" placeholder="Поиск" class="main-nav-search-input-2" style='width: calc(100% - 36px);'>
                      </label>
                      <span id='panel-conteiner-main-block_divide_by_4-conteiner-news-search-span'>
                        <div class="checkbox-login" style="margin-left: 0px; margin-top: 10px; width: calc(100% - 0px);">
                          <input type="checkbox" id="chb1-230-01" checked="" style="display: none;">
                          <label for="chb1-230-01" class="checkbox-login-chb1"></label>
                          <label for="chb1-230-01" class="checkbox-login-chb5">
                            <div>Опубликованные</div>
                          </label>
                        </div>
                        <div class="checkbox-login" style="margin-left: 0px; margin-top: 10px; width: calc(100% - 0px);">
                          <input type="checkbox" id="chb1-231-01" checked="" style="display: none;">
                          <label for="chb1-231-01" class="checkbox-login-chb1"></label>
                          <label for="chb1-231-01" class="checkbox-login-chb5">
                            <div>Не опубликованные</div>
                          </label>
                        </div>
                        <div class="input-login" style="margin-top: 23px; margin-left: 0px; max-width: calc(100% - 14px); border-radius: 4px; min-width: 100px; margin-right: 20px;">
                          <input value="" required="required" type="date">
                          <span class="placeholder-white">Период (Начало)</span>
                        </div>
                        <div class="input-login" style="margin-top: 0px; margin-left: 0px; max-width: calc(100% - 14px); border-radius: 4px; min-width: 100px; margin-right: 20px;">
                          <input value="" required="required" type="date">
                          <span class="placeholder-white">Период (Конец)</span>
                        </div>
                        <span style="position: absolute; bottom: -11px; display: block; width: calc(100% - 25px);">
                          <div class="window-block-conteiner-left-btn" style='width: 100%;'>Поиск</div>
                        </span>
                      </span>

                    </div>
                    <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list'>
                      <span>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-article'></div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text'>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title'>Заголовок</div>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-newsStatistic')" class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat'></div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-article'></div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text'>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title'>Заголовок</div>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-newsStatistic')" class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat'></div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-article'></div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text'>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title'>Заголовок</div>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-newsStatistic')" class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat'></div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-article'></div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text'>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title'>Заголовок</div>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-newsStatistic')" class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat'></div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-article'></div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text'>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title'>Заголовок</div>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-newsStatistic')" class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat'></div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-article'></div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text'>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title'>Заголовок</div>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-newsStatistic')" class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat'></div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-article'></div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text'>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title'>Заголовок</div>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-newsStatistic')" class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat'></div>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-article'></div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text'>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title'>Заголовок</div>
                            <div class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-newsStatistic')" class='panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat'></div>
                        </div>
                      </span>
                    </div>
                  </div>
                </div>
              </div>
              <div class='panel-conteiner-main-block_divide_by_4' style='margin-left: 40px; margin-right: 36px;'>
                <div class='panel-conteiner-main-block-title'>
                  <span>Точки входа</span>
                  <div class='description1'></div>
                  <div class='window-block-settings-block-description'>
                    <div class='window-block-settings-block-description-title'>Подсказка</div>
                    <div class='window-block-settings-block-description-text'>Страницы, с которых посетители начинают просмотр вашего сайта. «Точка входа» является первой страницей сессии.</div>
                  </div>
                </div>
                <div class='panel-conteiner-main-block_divide_by_4-conteiner'>
                  <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list" style='width: calc(100% - 0px); height: 325px; margin-top: 15px; margin-left: 0px;'>
                      <span>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power" style='background-color: #fd397a2e; color: #fd397a;'></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>2 625</span>
                              <span class='icon-login' title='Входов 2 625' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power" style='background-color: #fd397a2e; color: #fd397a;'></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>2 625</span>
                              <span class='icon-login' title='Входов 2 625' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power" style='background-color: #fd397a2e; color: #fd397a;'></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>2 625</span>
                              <span class='icon-login' title='Входов 2 625' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power" style='background-color: #fd397a2e; color: #fd397a;'></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>2 625</span>
                              <span class='icon-login' title='Входов 2 625' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power" style='background-color: #fd397a2e; color: #fd397a;'></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>2 625</span>
                              <span class='icon-login' title='Входов 2 625' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power" style='background-color: #fd397a2e; color: #fd397a;'></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>2 625</span>
                              <span class='icon-login' title='Входов 2 625' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power" style='background-color: #fd397a2e; color: #fd397a;'></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>2 625</span>
                              <span class='icon-login' title='Входов 2 625' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power" style='background-color: #fd397a2e; color: #fd397a;'></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>2 625</span>
                              <span class='icon-login' title='Входов 2 625' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power" style='background-color: #fd397a2e; color: #fd397a;'></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>2 625</span>
                              <span class='icon-login' title='Входов 2 625' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                      </span>
                    </div>
                </div>
              </div>
              <div class='panel-conteiner-main-block_divide_by_4'>
                <div class='panel-conteiner-main-block-title'>
                  <span>Точки выхода</span>
                  <div class='description1'></div>
                  <div class='window-block-settings-block-description'>
                    <div class='window-block-settings-block-description-title'>Подсказка</div>
                    <div class='window-block-settings-block-description-text'>Страницы, которые посетители просматривают последними в течение сессии.</div>
                  </div>
                </div>
                <div class='panel-conteiner-main-block_divide_by_4-conteiner'>
                  <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list" style='width: calc(100% - 0px); height: 325px; margin-top: 15px; margin-left: 0px;'>
                      <span>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power" style='background-color: #ffb8222e; color: #ffb822;'></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>2 625</span>
                              <span class='icon-exit' title='Выходов 2 625' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div style='background-color: #ffb8222e; color: #ffb822;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power"></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>2 287</span>
                              <span class='icon-exit' title='Выходов 2 287' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div style='background-color: #ffb8222e; color: #ffb822;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power"></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>1 856</span>
                              <span class='icon-exit' title='Выходов 1 856' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div style='background-color: #ffb8222e; color: #ffb822;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power"></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>1 856</span>
                              <span class='icon-exit' title='Выходов 1 856' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div style='background-color: #ffb8222e; color: #ffb822;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power"></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>1 856</span>
                              <span class='icon-exit' title='Выходов 1 856' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div style='background-color: #ffb8222e; color: #ffb822;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power"></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>1 856</span>
                              <span class='icon-exit' title='Выходов 1 856' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div style='background-color: #ffb8222e; color: #ffb822;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power"></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>1 856</span>
                              <span class='icon-exit' title='Выходов 1 856' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                        <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem">
                          <div style='background-color: #ffb8222e; color: #ffb822;' class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-ico icon-power"></div>
                          <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text">
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-title" title="title страницы">title страницы</div>
                            <div class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-text-description">
                              <span style='font-family: pfdr; opacity: 0.75;'>1 856</span>
                              <span class='icon-exit' title='Выходов 1 856' style='opacity: 0.75;'></span>
                            </div>
                          </div>
                          <div title='Статистика' onclick="open_window('#page-statistic')" class="panel-conteiner-main-block_divide_by_4-conteiner-news-list-elem-btn icon-stat"></div>
                        </div>
                      </span>
                    </div>
                </div>
              </div>
            </span>
            <span style='margin-top: 40px; margin-bottom: 40px; display: block;' id='stat-map-block'>
              <div class='panel-conteiner-width2' style='width: calc(75% - 170px);'>
                <div class="panel-conteiner-main-block">
                  <div class="panel-conteiner-main-block-title">
                    Сессии посетителей
                    <div class="description1"></div>
                    <div class="window-block-settings-block-description">
                      <div class="window-block-settings-block-description-title">Подсказка</div>
                      <div class="window-block-settings-block-description-text"><b>Сессия посетителей</b> — показывает координаты посещений вашего сайта.</div>
                    </div>
                  </div>
                  <div class="panel-conteiner-main-block_divide_by_4-conteiner" style='margin-top: 15px; height: 326px;'>
                    <div class="panel-conteiner-main-block_divide_by_4-conteiner-map-elem" style='position: relative; margin-right: 15px; overflow: hidden; background-color: #0000;width: calc(100% - 235px); min-width: 100px; overflow-x: auto;' id='stat-map'></div>
                    <div class="panel-conteiner-main-block_divide_by_4-conteiner-map-elem">
                      <div class="panel-conteiner-main-block_divide_by_4-conteiner-map-elem-title">Топ 5 по странам</div>
                      <div class="panel-conteiner-main-block_divide_by_4-conteiner-map-elem-chart" id='chart12'></div>
                      <div class="panel-conteiner-main-block_divide_by_4-conteiner-map-elem-line">
                        <span>
                          <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-span">
                            <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-point" style="background-color: #5d78ff;"></span>
                            <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-desc">Сингапур</span>
                          </span><br>
                          <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-span">
                            <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-point" style="background-color: #0abb87;"></span>
                            <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-desc">Мальдивы</span>
                          </span><br>
                          <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-span">
                            <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-point" style="background-color: #00bcd4;"></span>
                            <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-desc">Сан-Марино</span>
                          </span><br>
                          <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-span">
                            <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-point" style="background-color: #ffb822;"></span>
                            <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-desc">Ватикан</span>
                          </span><br>
                          <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-span">
                            <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-point" style="background-color: #fd397a;"></span>
                            <span class="panel-conteiner-main-block_divide_by_4-conteiner-text-desc">Бахрейн</span>
                          </span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class='panel-conteiner-width2' style='margin-left: 40px; width: calc(25% + 130px);'>
                <div class="panel-conteiner-main-block" style='background-color: #5d78ff; overflow: hidden; height: 294px;'>
                  <div class="panel-conteiner-main-block-title" style='color: var(--colorI); opacity: 1;'>
                    Лучший редактор и статья
                    <div class="description1"></div>
                    <div class="window-block-settings-block-description">
                      <div class="window-block-settings-block-description-title">Подсказка</div>
                      <div class="window-block-settings-block-description-text">
                        <b>Лучший редактор</b> — это человек, у которого наибольший средний показатель вовлеченности читателей среди всех написанных им статей.
                        <br><br>
                        <b>Лучшая статья</b> — это человек, у которого наибольший средний показатель вовлеченности читателей среди всех написанных им статей.
                      </div>
                    </div>
                  </div>
                  <div class="panel-conteiner-main-block_divide_by_4-conteiner" style='margin-top: 0px; height: 326px;'>
                    <div class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-line'>
                      <div class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-line-photo'>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-line-photo-1' style="background-image: url('media/users/18.jpg');"></div>
                      </div>
                      <div class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-line-text'>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-line-text-name'>Соня Рожкова</div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-line-text-info'>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-line-text-info-elem' style='margin-top: 5px;'>
                            <span class='icon-point' style='margin-right: 7px;'></span>
                            <span style='white-space: normal; word-wrap: normal;' class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-line-text-info-elem-span'>
                              Россия, Пермь
                            </span>
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-line-text-info-elem'>
                            <span class='icon-mail' style='margin-right: 7px;'></span>
                            <span style='white-space: normal; word-wrap: normal;' class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-line-text-info-elem-span'>
                              <a style='color: var(--colorI); white-space: normal; word-wrap: normal;' href='mailto:example@yandex.ru'>example@yandex.ru</a>
                            </span>
                          </div>
                          <div class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-line-text-info-elem'>
                            <span class='icon-tel' style='margin-right: 7px;'></span>
                            <span style='white-space: normal; word-wrap: normal;' class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-line-text-info-elem-span'>
                              <a style='color: var(--colorI); white-space: normal; word-wrap: normal;' href='tel:+79126963716'>+7 (912) 69-63-716</a>
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-block'>
                      <div class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-block-conteiner'>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-block-conteiner-elem icon-crown' style='margin-right: 9px; background-color: #ffb8222e; color: #fdc625; font-size: 30px;'>
                          <br>
                          <span style='font-size: 16px; margin-top: -6px; display: block;'>лучшая</span>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-block-conteiner-elem icon-eye' style='margin-right: 9px; background-color: #1fca943b; color: #0abb87; font-size: 30px;'>
                          <br>
                          <span style='font-size: 16px; margin-top: -6px; display: block;'>115k+</span>
                        </div>
                        <div class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-block-conteiner-elem icon-share' style='margin-right: 9px; background-color: #00bcd42e; color: #00bcd4; font-size: 26px; line-height: 39px;'>
                          <br>
                          <span style='font-size: 16px; margin-top: -6px; display: block; line-height: 15px;'>75k+</span>
                        </div>
                        <div onclick="open_window('#iframe-topNews');" class='panel-conteiner-main-block_divide_by_4-conteiner-attachment_top-block-conteiner-elema icon-book' style='color: #5d78ff; font-size: 26px; line-height: 39px;'>
                          <br>
                          <span style='font-size: 16px; margin-top: -6px; display: block; line-height: 15px;'>Читать</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class='panel-conteiner-main-block_divide_by_4-ab'></div>
                </div>
                <div class="panel-conteiner-main-block" id='panel-conteiner-main-block-about' onclick="open_window('#about_program')" style='cursor: pointer; margin-top: 40px; overflow: hidden; height: 60px;'>
                  <span style='display: block; height: 100%;'>
                    <span class='panel-conteiner-main-block-logo'>
                      <svg style='position: absolute; height: 100%; width: 135px; left: -51px; '
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
                    </span>
                    <span class='panel-conteiner-main-block-a' style='left: 64px;'>Swiftly Admin Panel</span>
                  </span>
                </div>
              </div>
            </span>
          </div>
        </div>
        <?php endif;?>
        <?php if ($timeTablePanel): ?>
        <div class='panel' id='timetable' search-js-elem='Расписание, section-block, #timetable, 📅, Таблицы с занятиями, [Расписание, Таблицы, Занятия]' style='<?php echo($page[2][1]);?>'>
          <link rel='stylesheet' href="style/timetable.css">
          <div class='panel-title'>Расписание</div>
          <div class='panel-conteiner'>
            <div class='panel-filter'>
              <span>
                <div class='panel-filter-title'>Фильтры</div>
                <div class='panel-filter-title-ab2'>
                  <span class='icon-settings' title='Настройки' onclick="open_window('#settingsTimetable')"></span>
                </div>
              </span>
              <label for='searchFilter223' class='main-nav-search-2'>
                <span class='main-nav-search-icon icon-search'></span>
                <input type='date' placeholder="Поиск" id='searchFilter223' class='main-nav-search-input-2'>
              </label>

              <div class='panel-msg-conteiner' style='margin-top: 10px;'>

                <!-- <div class='panel-news-block' onclick="open_window('#timetable_exception')">
                  <div class='panel-news-block-img-2 icon-chart' style='background-color: #5d78ff;'></div>
                  <div class='panel-news-block-text'>
                    <div class='panel-msg-block-text-title' style='white-space: normal;'>

                      Статистика

                    </div>
                    <div class='panel-msg-block-text-msg'>Данные о записи</div>
                  </div>
                </div> -->
                <div class='panel-news-block-half icon-chart' title='Данные о записи' onclick="usersBookingTable(); timetableWindow('timetable-stat'); currentTaskDay = '';"></div>
                <div class='panel-news-block-half icon-user' style="margin-left: 11px;" title='Создание групп' onclick="timetableWindow('timetable-group'); currentTaskDay = '';"></div>

                <div class='panel-news-block' onclick="open_window('#timetable_exception')" style='background-color: var(--main-bg-search);'>
                  <div class='panel-news-block-img-2 icon-plus' style='background-color: #5d78ff;'></div>
                  <div class='panel-news-block-text'>
                    <div class='panel-msg-block-text-title' style='white-space: normal;'>

                      Добавить

                    </div>
                    <div class='panel-msg-block-text-msg'>Новый день</div>
                  </div>
                </div>

              </div>

              <div class='panel-filter-title-2'>Сегодня</div>
              <div class='panel-msg-conteiner' style='margin-top: 10px;' id="today-tasks-containter">

                <div class='panel-news-block'>
                  <div class='panel-news-block-img-2' style='background-color: #fd397a;'><?php $a = new DateTime(date('Ymd')); echo($a->format("d")); ?></div>
                  <div class='panel-news-block-text'>
                    <div class='panel-msg-block-text-title'>

                      <?php

                        $tmp = date("N");

                        if($tmp == 1){
                          echo("Понедельник");
                        }
                        if($tmp == 2){
                          echo("Вторник");
                        }
                        if($tmp == 3){
                          echo("Среда");
                        }
                        if($tmp == 4){
                          echo("Четверг");
                        }
                        if($tmp == 5){
                          echo("Пятница");
                        }
                        if($tmp == 6){
                          echo("Суббота");
                        }
                        if($tmp == 7){
                          echo("Воскресенье");
                        }

                      ?>

                    </div>
                    <div class='panel-msg-block-text-msg'><?php $a = new DateTime(date('Ymd')); echo($a->format("d.m.Y")); ?></div>
                  </div>
                </div>

              </div>

              <div class='panel-filter-title-2'>Дни недели</div>
              <div class='panel-msg-conteiner' style='margin-top: 10px;' id="regular-tasks-containter">


                <div class='panel-news-block'>
                  <div class='panel-news-block-img-2' style='background-color: #6b5eae;'><?php $a = new DateTime();$b = date("w");$a->modify("+".($b==0?1:8-$b)." day");echo $a->format("d");?></div>
                  <div class='panel-news-block-text'>
                    <div class='panel-msg-block-text-title'>Понедельник</div>
                    <div class='panel-msg-block-text-msg'><?php $a = new DateTime();$b = date("w");$a->modify("+".($b==0?1:8-$b)." day");echo $a->format("d.m.Y");?></div>
                  </div>
                </div>

                <div class='panel-news-block'>
                  <div class='panel-news-block-img-2' style='background-color: #6b5eae;'><?php $a = new DateTime();$b = date("w");$a->modify("+".($b==1?1:8-$b)." day");echo $a->format("d");?></div>
                  <div class='panel-news-block-text'>
                    <div class='panel-msg-block-text-title'>Вторник</div>
                    <div class='panel-msg-block-text-msg'><?php $a = new DateTime();$b = date("w");$a->modify("+".($b==1?1:8-$b)." day");echo $a->format("d.m.Y");?></div>
                  </div>
                </div>

                <div class='panel-news-block'>
                  <div class='panel-news-block-img-2' style='background-color: #6b5eae;'><?php $a = new DateTime();$b = date("w");$a->modify("+".($b==1?2:8-$b)." day");echo $a->format("d");?></div>
                  <div class='panel-news-block-text'>
                    <div class='panel-msg-block-text-title'>Среда</div>
                    <div class='panel-msg-block-text-msg'><?php $a = new DateTime();$b = date("w");$a->modify("+".($b==1?2:8-$b)." day");echo $a->format("d.m.Y");?></div>
                  </div>
                </div>
                <div class='panel-news-block'>
                  <div class='panel-news-block-img-2' style='background-color: #6b5eae;'><?php $a = new DateTime();$b = date("w");$a->modify("+".($b==1?3:8-$b)." day");echo $a->format("d");?></div>
                  <div class='panel-news-block-text'>
                    <div class='panel-msg-block-text-title'>Четверг</div>
                    <div class='panel-msg-block-text-msg'><?php $a = new DateTime();$b = date("w");$a->modify("+".($b==1?3:8-$b)." day");echo $a->format("d.m.Y");?></div>
                  </div>
                </div>
                <div class='panel-news-block'>
                  <div class='panel-news-block-img-2' style='background-color: #6b5eae;'><?php $a = new DateTime();$b = date("w");$a->modify("+".($b==1?4:8-$b)." day");echo $a->format("d");?></div>
                  <div class='panel-news-block-text'>
                    <div class='panel-msg-block-text-title'>Пятница</div>
                    <div class='panel-msg-block-text-msg'><?php $a = new DateTime();$b = date("w");$a->modify("+".($b==1?4:8-$b)." day");echo $a->format("d.m.Y");?></div>
                  </div>
                </div>
                <div class='panel-news-block'>
                  <div class='panel-news-block-img-2' style='background-color: #6b5eae;'><?php $a = new DateTime();$b = date("w");$a->modify("+".($b==1?5:8-$b)." day");echo $a->format("d");?></div>
                  <div class='panel-news-block-text'>
                    <div class='panel-msg-block-text-title'>Суббота</div>
                    <div class='panel-msg-block-text-msg'><?php $a = new DateTime();$b = date("w");$a->modify("+".($b==1?5:8-$b)." day");echo $a->format("d.m.Y");?></div>
                  </div>
                </div>
                <div class='panel-news-block'>
                  <div class='panel-news-block-img-2' style='background-color: #6b5eae;'><?php $a = new DateTime();$b = date("w");$a->modify("+".($b==1?6:8-$b)." day");echo $a->format("d");?></div>
                  <div class='panel-news-block-text'>
                    <div class='panel-msg-block-text-title'>Воскресенье</div>
                    <div class='panel-msg-block-text-msg'><?php $a = new DateTime();$b = date("w");$a->modify("+".($b==1?6:8-$b)." day");echo $a->format("d.m.Y");?></div>
                  </div>
                </div>

              </div>


              <div class='panel-filter-title-2'>Исключения</div>

              <div class='panel-msg-conteiner' style='margin-top: 10px;' id="exception-tasks-containter">

                <div class='panel-news-block'>
                  <div class='panel-news-block-img-2' style='background-color: #6b5eae;'>06</div>
                  <div class='panel-news-block-text'>
                    <div class='panel-msg-block-text-title'>Суббота</div>
                    <div class='panel-msg-block-text-msg'>06.04.2019</div>
                  </div>
                </div>

              </div>

              <!-- <div class='panel-filter-btn'>Найти</div> -->

            </div>
          </div>
          <div class='panel-conteiner-full'>
            <div class='panel-news_add' id='timetable-elements'>
              <span id='timetable-main' style='display: none;'>
                <div class='panel-news_add-title'>
                  <span class='visible-phone back-slide-elem icon-left' onclick="timetableElemEnable = false; currentTaskDay = ''; timetableElemEnableF(); "></span>
                  <span id="tasks-day-title">Понедельник</span>
                </div>
                <div class='panel-news-description2'>Создавая расписание не забывайте, что несбалансированная нагрузка может навредить в усвоении материала учениками и отразиться на проведении занятий преподавателями.</div>
                <div class="checkbox-login" style='margin-left: 20px; margin-top: 15px;'>
                  <input type="checkbox" id="1raRg-Pw12-fZ4R" name="chb-filter-all-user" style="display: none;">
                  <label for="1raRg-Pw12-fZ4R" class="checkbox-login-chb1"></label>
                  <label for="1raRg-Pw12-fZ4R" class="checkbox-login-chb3" style="width: calc(100% - 65px);">
                    <div>Это неучебный день</div>
                  </label>
                </div>
                <span id='timetable-d46wq'>
                  <div class='timetable-d46wq-ab'></div>
                  <div class='panel-timetable-edit'>
                    <div class='panel-timetable-edit-del icon-plus' title='Удалить таблицу' onclick='timetable_del_table(this)'></div>
                    <?php

                    $outputTimetable = '<select class="panel-timetable-edit-title" placeholder="Заголовок таблицы">';
                    for($i = 0; $i < count($typeLearning); $i++){
                      $outputTimetable .= '<option>' . $typeLearning[$i] . '</option>';
                    }
                    $outputTimetable .= '</select>';
                    echo($outputTimetable);

                    ?>
                    <div class='panel-timetable-edit-header'>
                      <div class='panel-timetable-edit-elem-elem' title="Нажмите для сортировки" onclick="timetableSort('time', this);" style='width: 75px;'>Время</div>
                      <div class='panel-timetable-edit-elem-elem' title="Нажмите для сортировки" onclick="timetableSort('subject', this);" style='width: calc(50% - 2px);'>Название предмета</div>
                      <div class='panel-timetable-edit-elem-elem' title="Нажмите для сортировки" onclick="timetableSort('teacher', this);" style='width: calc(25% - 2px);'>Преподаватель</div>
                      <div class='panel-timetable-edit-elem-elem' title="Нажмите для сортировки" onclick="timetableSort('group', this);" style='width: calc(15% - 2px); border-right: 0px solid var(--border-color);'>Группа</div>
                    </div>
                    <span class='timetable-d46wq-LWZx'>
                      <div class='panel-timetable-edit-add'>
                        <div class='panel-timetable-edit-add-plus icon-addline' title='Добавить строку' onclick='timetable_add_line(this)'></div>
                      </div>
                      <div class='panel-timetable-edit-elem'>
                        <input class='panel-timetable-edit-input-elem' type='time' style='width: 74px; padding-bottom: 9px; padding-top: 9px;' placeholder='Время'></input>
                        <input class='panel-timetable-edit-input-elem' style='width: calc(50% - 6px);' placeholder='Название предмета'></input>
                        <input class='panel-timetable-edit-input-elem' style='width: calc(25% - 6px);' placeholder='Преподаватель'></input>
                        <select class='panel-timetable-edit-input-elem' style='width: calc(15% - 6px); border-right: 0px solid var(--border-color);' placeholder='Группа'>
                          <option style='display: none; opacity: 0.5;' value=''>Группа</option>
                          <option value="ГК-11">ГК-11</option>
                        </select>
                        <div class='panel-timetable-edit-input-del icon-delline' title='Удалить строку' onclick='timetable_del_line(this)'></div>
                      </div>
                      <div class='panel-timetable-edit-add'>
                        <div class='panel-timetable-edit-add-plus icon-addline' title='Добавить строку' onclick='timetable_add_line(this)'></div>
                      </div>
                    </span>
                  </div>
                </span>
                <span style="margin-left: 20px; margin-top: 25px; display: block; ">
                  <div class="window-block-conteiner-left-btn" style='width: 160px;' onclick="timetable_addTable()">Добавить таблицу</div><br>
                  <div class="window-block-conteiner-left-btn" onclick="tasksSendToServer([]);">Удалить</div><br>
                  <div class="window-block-conteiner-left-btn" onclick="timetable_save();">Сохранить</div>
                </span>
              </span>
              <span id='timetable-group' style='display: none;'>
                <div class='panel-news_add-title'>
                  <span class='visible-phone back-slide-elem icon-left' onclick="timetableElemEnable = false; timetableElemEnableF();"></span>
                  Создание учебных групп
                </div>
                <div class='panel-news-description2'>В данном разделе вы можете создавать или редактировать учебные группы. Укажите список групп через запятую.</div>
                <div class="checkbox-login" style='margin-left: 20px; margin-top: 15px;'>
                  <textarea class='panel-timetable-textarea' id='select-group-user'></textarea>
                </div>
                <span style="margin-left: 20px; margin-top: 25px; display: block; ">
                  <div class="window-block-conteiner-left-btn" onclick="$('#select-group-user').val('')">Очистить</div><br>
                  <div class="window-block-conteiner-left-btn" onclick="saveGroup('#select-group-user')">Сохранить</div>
                </span>
              </span>
              <span id='timetable-stat' style='display: block;'>
                <div class='panel-news_add-title'>
                  <span class='visible-phone back-slide-elem icon-left' onclick="timetableElemEnable = false; timetableElemEnableF();"></span>
                  Данные о записи на занятия
                </div>
                <div class='panel-news-description2'>В данном разделе вы можете видеть предпочитаемое время занятий у пользователей на сайте.</div>
                <div style='margin-left: 20px; margin-top: 10px; margin-right: 20px;'>
                  <input type="checkbox" id="1raRg-Pw12-fQWR" style="display: none;" onclick="usersBookingTable(this);">
                  <label for='1raRg-Pw12-fQWR' class='timetable-btn icon-select_all' title='Режим отображения'></label>
                  <div class='timetable-btn icon-mail' title='Оповестить всех пользователей' onclick='timetableSendMsgAll(this);'></div>
                  <div class='timetable-btn icon-list' onclick="open_window('#groupsPrint'); TimetableGroupsData.get(listGroup);" title='Список всех групп'></div>
                </div>
                <!-- <div class="checkbox-login" style='display: inline-block; width: auto; margin-left: 20px; margin-top: 15px;'>
                  <label for="1raRg-Pw12-fQWR" class="checkbox-login-chb1"></label>
                  <label for="1raRg-Pw12-fQWR" class="checkbox-login-chb344" style="width: auto;">
                    <div>Режим таблицы</div>
                  </label>
                </div> -->

                <div class="checkbox-login" style='margin-left: 20px; margin-top: 15px;'>
                  <span id='timetable-stat-table' style='display: none;'>

                    <table class='timetable-stat-table'>
                      <tr class='timetable-stat-table-tr'>
                        <td></td>
                        <td>Группа</td>
                        <td title='Понедельник'>Пн</td>
                        <td title='Вторник'>Вт</td>
                        <td title='Среда'>Ср</td>
                        <td title='Четверг'>Чт</td>
                        <td title='Пятница'>Пт</td>
                        <td title='Суббота'>Сб</td>
                        <td title='Воскресенье'>Вс</td>
                      </tr>
                      <tr class='timetable-stat-table-tr'>
                        <td title='Вася Пупок'>Вася Пупок</td>
                        <td>
                          <select>
                            <option value="">Не определена</option>
                            <option value="">1</option>
                            <option value="">1</option>
                          </select>
                        </td>
                        <td>
                          10:00 - 12:00<br>
                          14:00 - 18:00
                        </td>
                        <td>-</td>
                        <td>
                          -
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                      </tr>
                      <tr class='timetable-stat-table-tr'>
                        <td title='Вася Пупок'>Вася Пупок</td>
                        <td>
                          <select>
                            <option value="">Не определена</option>
                            <option value="">1</option>
                            <option value="">1</option>
                          </select>
                        </td>
                        <td>
                          10:00 - 12:00<br>
                          14:00 - 18:00
                        </td>
                        <td>-</td>
                        <td>
                          -
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                      </tr>
                      <tr class='timetable-stat-table-tr'>
                        <td title='Вася Пупок'>Вася Пупок</td>
                        <td>
                          <select>
                            <option value="">Не определена</option>
                            <option value="">1</option>
                            <option value="">1</option>
                          </select>
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                          -
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                          10:00 - 12:00<br>
                          14:00 - 18:00
                        </td>
                        <td>-</td>
                      </tr>
                      <tr class='timetable-stat-table-tr'>
                        <td title='Вася Пупок'>Вася Пупок</td>
                        <td>
                          <select>
                            <option value="">Не определена</option>
                            <option value="">1</option>
                            <option value="">1</option>
                          </select>
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                          -
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                          10:00 - 12:00<br>
                          14:00 - 18:00
                        </td>
                        <td>-</td>
                      </tr>
                      <tr class='timetable-stat-table-tr'>
                        <td title='Вася Пупок'>Вася Пупок</td>
                        <td>
                          <select>
                            <option value="">Не определена</option>
                            <option value="">1</option>
                            <option value="">1</option>
                          </select>
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                          -
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                          10:00 - 12:00<br>
                          14:00 - 18:00
                        </td>
                        <td>-</td>
                      </tr>
                      <tr class='timetable-stat-table-tr'>
                        <td title='Вася Пупок'>Вася Пупок</td>
                        <td>
                          <select>
                            <option value="">Не определена</option>
                            <option value="">1</option>
                            <option value="">1</option>
                          </select>
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                          -
                        </td>
                        <td>-</td>
                        <td>-</td>
                        <td>
                          10:00 - 12:00<br>
                          14:00 - 18:00
                        </td>
                        <td>-</td>
                      </tr>
                    </table>
                  </span>
                  <span id='timetable-stat-timeline' style='display: block;'>
                    <table class='timeline-table' border="0">
                      <caption>Онлайн обучение</caption>
                      <tr>
                        <td class='timeline-name' title='Вася Пупкин'>Вася Пупкин</td>
                        <td class='timeline-select'>
                          <select>
                            <option value="">ГК-11</option>
                            <option value="">ТП-11</option>
                          </select>
                        </td>
                        <td>
                          <div class='timeline'>
                            <div title="Понедельник" class='timeline-day'>
                              <span class='timeline-day-title'>ПН</span>
                              <span class='timeline-day-time'>
                                <span class='timeline-day-time-elem' title='Понедельник: 10:00 - 12:00' style="width: 10%; left: 15%;"></span>
                                <span class='timeline-day-time-elem' title='Понедельник: 10:00 - 12:00' style="width: 20%; left: 48%;"></span>
                              </span>
                            </div>
                            <div title="Вторник" class='timeline-day'>
                              <span class='timeline-day-title'>Вт</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Среда" class='timeline-day'>
                              <span class='timeline-day-title'>Ср</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Четверг" class='timeline-day'>
                              <span class='timeline-day-title'>Чт</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Пятница" class='timeline-day'>
                              <span class='timeline-day-title'>Пт</span>
                              <span class='timeline-day-time'>
                                <span class='timeline-day-time-elem' title='Пятница: 10:00 - 12:00' style="width: 10%; left: 15%;"></span>
                                <span class='timeline-day-time-elem' title='Пятница: 10:00 - 12:00' style="width: 25%; left: 60%;"></span>
                              </span>
                            </div>
                            <div title="Суббота" class='timeline-day'>
                              <span class='timeline-day-title'>Сб</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Воскресенье" class='timeline-day'>
                              <span class='timeline-day-title'>Вс</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr style='height: 22px;'></tr>
                      <tr>
                        <td class='timeline-name' title='Вася Пупкин'>Никита Пупкин</td>
                        <td class='timeline-select'>
                          <select>
                            <option value="">ГК-11</option>
                            <option value="">ТП-11</option>
                          </select>
                        </td>
                        <td>
                          <div class='timeline'>
                            <div title="Понедельник" class='timeline-day'>
                              <span class='timeline-day-title'>ПН</span>
                              <span class='timeline-day-time'>
                                <span class='timeline-day-time-elem' title='Понедельник: 10:00 - 12:00' style="width: 10%; left: 15%;"></span>
                                <span class='timeline-day-time-elem' title='Понедельник: 10:00 - 12:00' style="width: 20%; left: 48%;"></span>
                              </span>
                            </div>
                            <div title="Вторник" class='timeline-day'>
                              <span class='timeline-day-title'>Вт</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Среда" class='timeline-day'>
                              <span class='timeline-day-title'>Ср</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Четверг" class='timeline-day'>
                              <span class='timeline-day-title'>Чт</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Пятница" class='timeline-day'>
                              <span class='timeline-day-title'>Пт</span>
                              <span class='timeline-day-time'>
                                <span class='timeline-day-time-elem' title='Пятница: 10:00 - 12:00' style="width: 10%; left: 15%;"></span>
                                <span class='timeline-day-time-elem' title='Пятница: 10:00 - 12:00' style="width: 25%; left: 60%;"></span>
                              </span>
                            </div>
                            <div title="Суббота" class='timeline-day'>
                              <span class='timeline-day-title'>Сб</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Воскресенье" class='timeline-day'>
                              <span class='timeline-day-title'>Вс</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </table>
                    <table class='timeline-table' border="0">
                      <caption>Групповое обучение</caption>
                      <tr>
                        <td class='timeline-name' title='Вася Пупкин'>Вася Пупкин</td>
                        <td class='timeline-select'>
                          <select>
                            <option value="">ГК-11</option>
                            <option value="">ТП-11</option>
                          </select>
                        </td>
                        <td>
                          <div class='timeline'>
                            <div title="Понедельник" class='timeline-day'>
                              <span class='timeline-day-title'>ПН</span>
                              <span class='timeline-day-time'>
                                <span class='timeline-day-time-elem' title='Понедельник: 10:00 - 12:00' style="width: 10%; left: 15%;"></span>
                                <span class='timeline-day-time-elem' title='Понедельник: 10:00 - 12:00' style="width: 20%; left: 48%;"></span>
                              </span>
                            </div>
                            <div title="Вторник" class='timeline-day'>
                              <span class='timeline-day-title'>Вт</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Среда" class='timeline-day'>
                              <span class='timeline-day-title'>Ср</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Четверг" class='timeline-day'>
                              <span class='timeline-day-title'>Чт</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Пятница" class='timeline-day'>
                              <span class='timeline-day-title'>Пт</span>
                              <span class='timeline-day-time'>
                                <span class='timeline-day-time-elem' title='Пятница: 10:00 - 12:00' style="width: 10%; left: 15%;"></span>
                                <span class='timeline-day-time-elem' title='Пятница: 10:00 - 12:00' style="width: 25%; left: 60%;"></span>
                              </span>
                            </div>
                            <div title="Суббота" class='timeline-day'>
                              <span class='timeline-day-title'>Сб</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Воскресенье" class='timeline-day'>
                              <span class='timeline-day-title'>Вс</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                          </div>
                        </td>
                      </tr>
                      <tr style='height: 22px;'></tr>
                      <tr>
                        <td class='timeline-name' title='Вася Пупкин'>Никита Пупкин</td>
                        <td class='timeline-select'>
                          <select>
                            <option value="">ГК-11</option>
                            <option value="">ТП-11</option>
                          </select>
                        </td>
                        <td>
                          <div class='timeline'>
                            <div title="Понедельник" class='timeline-day'>
                              <span class='timeline-day-title'>ПН</span>
                              <span class='timeline-day-time'>
                                <span class='timeline-day-time-elem' title='Понедельник: 10:00 - 12:00' style="width: 10%; left: 15%;"></span>
                                <span class='timeline-day-time-elem' title='Понедельник: 10:00 - 12:00' style="width: 20%; left: 48%;"></span>
                              </span>
                            </div>
                            <div title="Вторник" class='timeline-day'>
                              <span class='timeline-day-title'>Вт</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Среда" class='timeline-day'>
                              <span class='timeline-day-title'>Ср</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Четверг" class='timeline-day'>
                              <span class='timeline-day-title'>Чт</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Пятница" class='timeline-day'>
                              <span class='timeline-day-title'>Пт</span>
                              <span class='timeline-day-time'>
                                <span class='timeline-day-time-elem' title='Пятница: 10:00 - 12:00' style="width: 10%; left: 15%;"></span>
                                <span class='timeline-day-time-elem' title='Пятница: 10:00 - 12:00' style="width: 25%; left: 60%;"></span>
                              </span>
                            </div>
                            <div title="Суббота" class='timeline-day'>
                              <span class='timeline-day-title'>Сб</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                            <div title="Воскресенье" class='timeline-day'>
                              <span class='timeline-day-title'>Вс</span>
                              <span class='timeline-day-time'>
                              </span>
                            </div>
                          </div>
                        </td>
                      </tr>
                    </table>
                  </span>
                </div>
              </span>
            </div>
          </div>
        </div>
        <?php endif;?>
        <?php if ($reviewsPanel): ?>
        <div class='panel' id='reviews' search-js-elem='Отзывы, section-block, #reviews, 📝, Отзыв о Вас, [Отзыв, Отзывы, Комментарии, комменты, критика, лайк, диз, дизлайк]' style='<?php echo($page[3][1]);?>'>
          <div class='panel-title'>Отзывы</div>
          <div class='panel-conteiner'>
            <div class='panel-filter'>
              <div class='panel-filter-title'>Фильтры</div>
              <div class='panel-filter-title-ab' onclick="open_window('#add_reviews')">Добавить</div>
              <label for='searchFilter1' class='main-nav-search-2'>
                <span class='main-nav-search-icon icon-search'></span>
                <input type='text' placeholder="Поиск" id='searchFilter22' class='main-nav-search-input-2'>
              </label>
              <div class='panel-filter-title-2'>Разделы</div>
              <div style='margin-left: -5px;'>
                <div class='checkbox-login'>
                  <input type='checkbox' id='chb-filter-all-user-1' checked name='chb-filter-all-user' style='display: none;'>
                  <label for='chb-filter-all-user-1' class='checkbox-login-chb1'></label>
                  <label for='chb-filter-all-user-1' class='checkbox-login-chb3'>
                    <div>Отзывы заказчиков</div>
                  </label>
                </div>

                <div class='checkbox-login'>
                  <input type='checkbox' id='chb-filter-all-user-2' name='chb-filter-all-user' style='display: none;'>
                  <label for='chb-filter-all-user-2' class='checkbox-login-chb1'></label>
                  <label for='chb-filter-all-user-2' class='checkbox-login-chb3'>
                    <div>Отзывы пользователей</div>
                  </label>
                </div>
              </div>

              <div class='panel-filter-title-2'>Дата публикации</div>

              <div class='input-login' style='margin-left: 20px; width: auto; max-width: 290px; min-width: 100px; margin-right: 20px;'>
                <input value='<?php //echo($userData['birthday'])?>' required='required' type='date' id="news-filter-date-start-2">
                <span class='placeholder-white'>Начало</span>
              </div>

              <div class='input-login' style='margin-left: 20px; width: auto; max-width: 290px; min-width: 100px; margin-right: 20px;'>
                <input value='<?php //echo($userData['birthday'])?>' required='required' type='date' id="news-filter-date-end-2">
                <span class='placeholder-white'>Конец</span>
              </div>

              <div class='panel-filter-btn'>Найти</div>

            </div>
            <div class='panel-table-btn'>
              <div class='panel-filter-title' style='margin-bottom: 15px;'>Настройка</div>
              <div class="placeholder-white-help">
                <select class='input-login-select' style='margin-left: 20px; width: calc(100% - 40px); outline: none; max-width: 290px; min-width: 100px; margin-right: 20px; margin-bottom: 0px;'>
                  <option>10</option>
                  <option>25</option>
                  <option>50</option>
                  <option>100</option>
                  <option>500</option>
                </select>
                <span class='placeholder-white-help-1'>Элементов</span>
              </div>

            </div>
            <div class='panel-table-btn'>
              <div class='panel-filter-title' style='margin-bottom: 15px;'>Страницы</div>
              <div class='panel-table-btn-elem icon-left' title='Назад'></div>
              <div class='panel-table-btn-elem' style='font-size: 16px;' title='2 страница'>2</div>
              <div class='panel-table-btn-elem' style='font-size: 16px;' title='3 страница'>3</div>
              <div class='panel-table-btn-elem' style='font-size: 16px;' title='4 страница'>4</div>
              <div class='panel-table-btn-elem icon-right' title='Вперед'></div>
            </div>
          </div>
          <div class='panel-reviews'>
            <div class='panel-reviews-sort'>
              <div class='panel-table-reviews-id'>
                <span>№</span>
                <span class='panel-table-reviews-ico icon-left'></span>
              </div>
              <div class='panel-table-reviews-text'>
                <span>Содержание</span>
                <span class='icon-left panel-table-reviews-ico'></span>
              </div>
              <div class='panel-table-reviews-date'>
                <span>Дата</span>
                <span class='icon-left panel-table-reviews-ico'></span>
              </div>
              <div class='panel-table-reviews-type'>
                <span>Тип</span>
                <span class='icon-left panel-table-reviews-ico'></span>
              </div>
              <div class='panel-table-reviews-action'>
                <span>Действия</span>
                <span class='icon-left panel-table-reviews-ico'></span>
              </div>
            </div>
            <div>
              <div class='panel-reviews-block'>
                <div class='panel-reviews-block-id'>1</div>
                <div class='panel-reviews-block-main'>
                  <div class='panel-reviews-block-main-title'>Заголовок</div>
                  <div class='panel-reviews-block-main-description'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident.</div>
                  <div class='panel-reviews-block-main-file'>
                    <div class="panel-news-document-file" style="margin-bottom: 5px;">
                      <div class="panel-news-document-add-mainbg" style="background-image: url('media/tmp/test2.jpg');"></div>
                      <div class="panel-news-document-add-bg"></div>
                    </div>
                    <div class="panel-news-document-file-noIMG" style="margin-bottom: 5px;">
                      <div class="panel-news-document-add-ico icon-video"></div>
                      <div class="panel-news-document-add-text">Имя_файла.mp4</div>
                    </div>
                  </div>
                </div>
                <div class='panel-reviews-block-date'>01.01.1970</div>
                <div class='panel-reviews-block-type'>
                  <div class='panel-reviews-block-type-block' style='background-color: #ffb822;'>Заказчик</div>
                </div>
                <div class='panel-reviews-block-action'>
                  <div class='panel-reviews-block-action-elem'>Редактировать</div><br>
                  <div class='panel-reviews-block-action-elem'>Удалить</div>
                </div>
              </div>
              <div class='panel-reviews-block'>
                <div class='panel-reviews-block-id'>2</div>
                <div class='panel-reviews-block-main'>
                  <div class='panel-reviews-block-main-img'>
                    <img src="media/users/0.jpg" alt="login">
                  </div>
                  <div class='panel-reviews-block-main-block'>
                    <div class='panel-reviews-block-main-block-name'>
                      Имя Фамилия
                      <span>login</span>
                    </div>
                    <div class='panel-reviews-block-main-block-main'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
                  </div>
                </div>
                <div class='panel-reviews-block-date'>01.01.1970</div>
                <div class='panel-reviews-block-type'>
                  <div class='panel-reviews-block-type-block' style='background-color: #6b5eae;'>Пользователь</div>
                </div>
                <div class='panel-reviews-block-action'>
                  <div class='panel-reviews-block-action-elem'>Редактировать</div><br>
                  <div class='panel-reviews-block-action-elem'>Удалить</div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <?php endif;?>
        <?php if ($newsPanel): ?>
        <div class='panel' id='news' search-js-elem='Новости, section-block, #news, 🎥, Статьи и публикации, [Новсти, Новости, Записи, лайк, комментарий, комменты, критика, просмотры, статьи, статья, публикации]' style='<?php echo($page[4][1]);?>'>
          <div class='panel-title'>Новости</div>
          <div class='panel-conteiner'>
            <div class='panel-user'>
              <div class='panel-filter-title'>
                <span>Статьи</span>
                <span class='panel-filter-title-menu icon-left' id='panel-filter-title-menu icon-left' title='Параметры' onclick="open_set_news(this)"></span>
              </div>
              <div class='panel-filter-parameters' style='height: 0px; margin-bottom: 0px;'>
                <div class='panel-filter-parameters-elem' onclick="newsDelete();">
                  <div class='panel-filter-parameters-elem-ico icon-basket' style='background-color: #fd3939cf;'></div>
                  <div class='panel-filter-parameters-elem-text'>Удалить</div>
                </div>
                <div class='panel-filter-parameters-elem' onclick="open_window('#panel-news-filter_and_sort')">
                  <div class='panel-filter-parameters-elem-ico icon-filter' style='background-color: #5d78ff;'></div>
                  <div class='panel-filter-parameters-elem-text'>Сортировка</div>
                </div>
              </div>
              <label for='searchmsg1' class='main-nav-search-2'>
                <span class='main-nav-search-icon icon-search'></span>
                <input type='text' placeholder="Поиск" id='searchnews' class='main-nav-search-input-2'>
              </label>
              <span id='news-search-block' style='display: none; opacity: 0;'>
                <div class='news-search-block-conteiner'>
                  <div class='news-search-block-conteiner-img icon-fast2'></div>
                  <div class='news-search-block-conteiner-text'>
                    Упс... Такая статья не найдена!
                  </div>
                </div>
              </span>
              <span id='news-filder-block'>
                <div class='panel-filter-title-2'>Создание</div>

                <div class='panel-msg-conteiner' onclick="newsCreateNew();">

                  <div class='panel-news-block'>
                    <div class='panel-news-block-img icon-article_new' style='background-color: #fd397a;'></div>
                    <div class='panel-news-block-text'>
                      <div class='panel-msg-block-text-title'>Создать</div>
                      <div class='panel-msg-block-text-msg'>Новую статью</div>
                    </div>
                  </div>

                </div>

                <div class='panel-filter-title-2'>Черновики</div>
                <div class='panel-msg-conteiner' id="newsSaved">

                  <!--<div class='panel-news-block'>
                    <div class='panel-news-block-img icon-article' style='background-color: #5d78ff;'>
                      <span style='opacity: 0; display: none; transition: 0.1s all;'>
                        <input checked type="checkbox" class='panel-news-block-img-ch' id='panel-news-block-img-ch-G53F_t31' style='display: none'>
                        <label for='panel-news-block-img-ch-G53F_t31' class='panel-msg-block-text-del'>
                          <div class='panel-news-block-img-ch-line1'></div>
                          <div class='panel-news-block-img-ch-line2'></div>
                        </label>
                      </span>
                    </div>
                    <div class='panel-news-block-text'>
                      <div class='panel-msg-block-text-title'>Заголовок</div>
                      <div class='panel-msg-block-text-msg'>Начало статьи па...</div>
                    </div>
                  </div>

                  <div class='panel-news-block'>
                    <div class='panel-news-block-img icon-article' style='background-color: #5d78ff;'>
                      <span style='opacity: 0; display: none; transition: 0.1s all;'>
                        <input checked type="checkbox" class='panel-news-block-img-ch' id='panel-news-block-img-ch-G53F_t32' style='display: none'>
                        <label for='panel-news-block-img-ch-G53F_t32' class='panel-msg-block-text-del'>
                          <div class='panel-news-block-img-ch-line1'></div>
                          <div class='panel-news-block-img-ch-line2'></div>
                        </label>
                      </span>
                    </div>
                    <div class='panel-news-block-text'>
                      <div class='panel-msg-block-text-title'>Заголовок</div>
                      <div class='panel-msg-block-text-msg'>Начало статьи па...</div>
                    </div>
                  </div>-->

                </div>
                <div class='panel-filter-title-2'>Опубликованные</div>
                <div class='panel-msg-conteiner' id="newsPublished">

                  <!--<div class='panel-news-block'>
                    <div class='panel-news-block-img icon-article_good' style='background-color: #0abb87;'>
                      <span style='opacity: 0; display: none; transition: 0.1s all;'>
                        <input checked type="checkbox" class='panel-news-block-img-ch' id='panel-news-block-img-ch-G53F_t33' style='display: none'>
                        <label for='panel-news-block-img-ch-G53F_t33' class='panel-msg-block-text-del'>
                          <div class='panel-news-block-img-ch-line1' style='background-color: #0abb87;'></div>
                          <div class='panel-news-block-img-ch-line2' style='background-color: #0abb87;'></div>
                        </label>
                      </span>
                    </div>
                    <div class='panel-news-block-text'>
                      <div class='panel-msg-block-text-title'>Заголовок</div>
                      <div class='panel-msg-block-text-msg'>Начало статьи па...</div>
                    </div>
                  </div>

                  <div class='panel-news-block'>
                    <div class='panel-news-block-img icon-article_good' style='background-color: #0abb87;'>
                      <span style='opacity: 0; display: none; transition: 0.1s all;'>
                        <input checked type="checkbox" class='panel-news-block-img-ch' id='panel-news-block-img-ch-G53F_t34' style='display: none'>
                        <label for='panel-news-block-img-ch-G53F_t34' class='panel-msg-block-text-del'>
                          <div class='panel-news-block-img-ch-line1' style='background-color: #0abb87;'></div>
                          <div class='panel-news-block-img-ch-line2' style='background-color: #0abb87;'></div>
                        </label>
                      </span>
                    </div>
                    <div class='panel-news-block-text'>
                      <div class='panel-msg-block-text-title'>Заголовок</div>
                      <div class='panel-msg-block-text-msg'>Начало статьи па...</div>
                    </div>
                  </div>-->

                </div>
              </span>


            </div>
          </div>
          <div class='panel-conteiner-full'>

            <div class='panel-news_add'>
              <div class='panel-conteiner-news-draganddrop' style='opacity:0; visibility: hidden;'>
                <div class='panel-conteiner-news-draganddrop-elem'>
                  <div class='panel-conteiner-news-draganddrop-elem-border2'>
                    <div class='panel-conteiner-news-draganddrop-elem-border-text'>
                      <div class='panel-conteiner-news-draganddrop-elem-border-text-ico icon-download'></div>
                      <div class='panel-conteiner-news-draganddrop-elem-border-text-text'><?=$userData['name1']?>, перенесите сюда вашу фотографию для загрузки</div>
                    </div>
                  </div>
                </div>
                <div class='panel-conteiner-news-draganddrop-elem'>
                  <div class='panel-conteiner-news-draganddrop-elem-border'>
                    <div class='panel-conteiner-news-draganddrop-elem-border-text'>
                      <div class='panel-conteiner-news-draganddrop-elem-border-text-ico icon-download'></div>
                      <div class='panel-conteiner-news-draganddrop-elem-border-text-text'><?=$userData['name1']?>, перенесите ваш файл сюда, чтобы загрузить его как документ</div>
                    </div>

                  </div>
                </div>
              </div>
              <div class='panel-news_add-title' id='panel-news-add-title'>Создание новой статьи</div>
              <div class='panel-news_tabs'>
                <span>
                  <div class='panel-news_tabs-tabs' id='panel-news_tabs-tabs-standart' onclick="news_type('standart')">Стандартный</div>
                  <div class='panel-news_tabs-tabs' id='panel-news_tabs-tabs-pro' onclick="news_type('pro')" style='z-index: -1; padding-bottom: 0px; filter: grayscale(100%) brightness(95%);'>Профи</div>
                </span>
                <div class='panel-news_tabs-main'>
                  <div class='panel-news_add-nav'>
                    <div class='panel-news_add-nav-elem icon-file2' id='panel-news_add-nav-elem-file' onclick="">
                      <div class='panel-news_add-nav-elem-edit-file' title=''>
                        <div class='panel-news_add-nav-elem-edit-file-elem' onclick="newsSaveDoc();" id="newsRecordSaveButton1">
                          <div class='panel-news_add-nav-elem-edit-file-elem-ico icon-save2'></div>
                          <div class='panel-news_add-nav-elem-edit-file-elem-text'>Сохранить</div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-file-elem' onclick="newsPublishDoc();">
                          <div class='panel-news_add-nav-elem-edit-file-elem-ico icon-publish'></div>
                          <div class='panel-news_add-nav-elem-edit-file-elem-text'>Опубликовать</div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-file-elem' onclick="newsSaveDoc();" id="newsRecordSaveButton2" style="display: none;">
                          <div class='panel-news_add-nav-elem-edit-file-elem-ico icon-article2'></div>
                          <div class='panel-news_add-nav-elem-edit-file-elem-text'>Перенести в черновик</div>
                        </div>
                        <input accept=".docx" type="file" id='file_import_DOC' style='display: none;'>
                        <label for='file_import_DOC' class='panel-news_add-nav-elem-edit-file-elem' onclick="">
                          <div class='panel-news_add-nav-elem-edit-file-elem-ico icon-upload2'></div>
                          <div class='panel-news_add-nav-elem-edit-file-elem-text'>Импорт</div>
                        </label>
                        <div class='panel-news_add-nav-elem-edit-file-elem' onclick="newsPrintDoc();">
                          <div class='panel-news_add-nav-elem-edit-file-elem-ico icon-print'></div>
                          <div class='panel-news_add-nav-elem-edit-file-elem-text'>Печать</div>
                        </div>
                      </div>
                    </div>
                    <div class='panel-news_add-nav-elem icon-back' title='Отменить' onclick='newsFormatDoc("undo");'></div>
                    <div class='panel-news_add-nav-elem icon-repeat' title='Повторить' onclick='newsFormatDoc("redo");'></div>
                    <div class='panel-news_add-nav-elem icon-clear' style='font-weight: 500;' title='Очистить форматирование' onclick='newsFormatDoc("removeFormat");'></div>
                    <div class='panel-news_add-nav-elem icon-bold' title='Полужирный' onclick="newsFormatDoc('bold')"></div>
                    <div class='panel-news_add-nav-elem icon-italic' title='Курсив' onclick='newsFormatDoc("italic");'></div>
                    <div class='panel-news_add-nav-elem icon-unline' title='Подчёркнутый' onclick='newsFormatDoc("underline");'></div>
                    <div class='panel-news_add-nav-elem icon-strike' title='Зачёркнутый' onclick='newsFormatDoc("strikeThrough");'></div>
                    <div class='panel-news_add-nav-elem-size' title='Размер'>
                      <div class='panel-news_add-nav-elem-size1' id='panel-news_add-nav-elem-size1' onblur="$(this).parent().removeAttr('style');" onfocus="$(this).parent().css('border','2px solid var(--border-color)'); $(this).parent().css('background-color','var(--main-bg)');">12</div>
                      <div class='panel-news_add-nav-elem-size2 icon-left'></div>
                      <div class='panel-news_add-nav-elem-size-select' onhover="$(this).parent().css('border','2px solid var(--border-color)'); $(this).parent().css('background-color','var(--main-bg)')">
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(8);">8</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(9);">9</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(10);">10</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(11);">11</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(12);">12</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(14);">14</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(16);">16</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(18);">18</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(20);">20</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(22);">22</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(24);">24</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(26);">26</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(28);">28</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(36);">36</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(48);">48</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem' onclick="newsSelectFontSize(72);">72</div>
                      </div>
                    </div>
                    <!-- <div class='panel-news_add-nav-elem-size' title='Регистр' style='width: 46px;'>
                      <div class='panel-news_add-nav-elem-size11 icon-register'></div>
                      <div class='panel-news_add-nav-elem-size2 icon-left'></div>
                      <div class='panel-news_add-nav-elem-size-select-2' onhover="$(this).parent().css('border','2px solid var(--border-color)'); $(this).parent().css('background-color','var(--main-bg)')">
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2' onclick="newsChangeRegister()">Как в предложениях</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2'>все строчные</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2'>ВСЕ ПРОПИСНЫЕ</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2'>Наничнать С Прописных</div>
                      </div>
                    </div> -->
                    <div class='panel-news_add-nav-elem-letter' title='Цвет' onclick="newsFormatDoc('forecolor',newsColor)">
                      A
                      <div class='panel-news_add-nav-elem-letter-color' id='panel-news_add-nav-elem-letter-color-id'></div>
                      <div class='panel-news_add-nav-elem-edit-color' title=''>
                      <div class='panel-news_add-nav-elem-edit-color-title'>Настройка цвета текста</div>
                      <div class='panel-news_add-nav-elem-edit-color-container'>
                        <div class='panel-news_add-nav-elem-edit-color-title-2'>Цвета темы</div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #ffffff;' title='#ffffff'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #f2f2f2;' title='#f2f2f2'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #d8d8d8;' title='#d8d8d8'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #bfbfbf;' title='#bfbfbf'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #a5a5a5;' title='#a5a5a5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #7f7f7f;' title='#7f7f7f'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #000000;' title='#000000'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #7f7f7f;' title='#7f7f7f'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #595959;' title='#595959'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #3f3f3f;' title='#3f3f3f'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #262626;' title='#262626'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #0c0c0c;' title='#0c0c0c'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #e7e6e6;' title='#e7e6e6'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #d0cece;' title='#d0cece'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #aeabab;' title='#aeabab'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #757070;' title='#757070'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #3a3838;' title='#3a3838'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #171616;' title='#171616'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #44546a;' title='#44546a'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #d6dce4;' title='#d6dce4'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #adb9ca;' title='#adb9ca'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #8496b0;' title='#8496b0'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #323f4f;' title='#323f4f'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #222a35;' title='#222a35'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #4472c4;' title='#4472c4'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #d9e2f3;' title='#d9e2f3'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #b4c6e7;' title='#b4c6e7'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #8eaadb;' title='#8eaadb'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #2f5496;' title='#2f5496'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #1f3864;' title='#1f3864'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #ed7d31;' title='#ed7d31'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #fbe5d5;' title='#fbe5d5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #f7cbac;' title='#f7cbac'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #f4b183;' title='#f4b183'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #c55a11;' title='#c55a11'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #833c0b;' title='#833c0b'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #a5a5a5;' title='#a5a5a5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #ededed;' title='#ededed'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #dbdbdb;' title='#dbdbdb'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #c9c9c9;' title='#c9c9c9'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #7b7b7b;' title='#7b7b7b'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #525252;' title='#525252'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #ffc000;' title='#ffc000'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #fff2cc;' title='#fff2cc'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #fee599;' title='#fee599'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #ffd965;' title='#ffd965'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #bf9000;' title='#bf9000'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #7f6000;' title='#7f6000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #5b9bd5;' title='#5b9bd5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #deebf6;' title='#deebf6'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #bdd7ee;' title='#bdd7ee'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #9cc3e5;' title='#9cc3e5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #2e75b5;' title='#2e75b5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #1e4e79;' title='#1e4e79'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #70ad47;' title='#70ad47'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #e2efd9;' title='#e2efd9'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #c5e0b3;' title='#c5e0b3'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #a8d08d;' title='#a8d08d'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #538135;' title='#538135'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #375623;' title='#375623'></div>
                        </div>
                      </div>
                      <div class='panel-news_add-nav-elem-edit-color-container'>
                        <div class='panel-news_add-nav-elem-edit-color-title-2'>Стандартные цвета</div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #c00000;' title='#c00000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #ff0000;' title='#ff0000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #ffc000;' title='#ffc000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #ffff00;' title='#ffff00'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #92d050;' title='#92d050'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #00b050;' title='#00b050'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #00b0f0;' title='#00b0f0'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #0070c0;' title='#0070c0'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #002060;' title='#002060'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #7030a0;' title='#7030a0'></div>
                        </div>
                      </div>
                      <?php

                        if(isset($_COOKIE['NewsColorArray'])){
                          $NewsColorArray = $_COOKIE['NewsColorArray'];
                          if(!empty($_COOKIE['NewsColorArray'])){

                            $NewsColorArray = explode('_', $NewsColorArray);
                            $NewsColorArrayDiv = '';

                            if($NewsColorArray[0] != '000'){
                              $NewsColorArrayStyle = 'display: block;';

                              for($i=0; $i < count($NewsColorArray); $i++) {
                                $NewsColorArrayL = '#'.$NewsColorArray[$i];
                                $NewsColorArrayDiv = $NewsColorArrayDiv."<div class='panel-news_add-nav-elem-edit-color-container-big_elem' style='margin-right: 3px;'><div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick='color_selection(this)' style='background-color: ".$NewsColorArrayL.";' title='".$NewsColorArrayL."'></div></div>";
                              }


                            } else{
                              $NewsColorArrayStyle = 'display: none;';
                            }
                          } else{
                            $NewsColorArrayStyle = 'display: none;';
                          }
                        } else{
                          $NewsColorArrayStyle = 'display: none;';
                        }?>
                      <div class='panel-news_add-nav-elem-edit-color-container' id='custom-color-new' style='<?php echo(@$NewsColorArrayStyle);?>'>
                        <div class='panel-news_add-nav-elem-edit-color-title-2'>Мои цвета</div>
                        <span id='custom-color-new-span'>
                          <?php echo(@$NewsColorArrayDiv);?>
                        </span>
                      </div>
                      <div class='panel-news_add-nav-elem-edit-color-container-hover' onclick="open_window('#edit-color')">
                        <div class='panel-news_add-nav-elem-edit-color-title-2' style='margin-bottom: 0px;'>
                          <div class='panel-news_add-nav-elem-edit-color-title-2-ico'></div>
                          <div class='panel-news_add-nav-elem-edit-color-title-2-text'>Другие цета</div>
                        </div>
                      </div>

                    </div></div>
                    <div class='panel-news_add-nav-elem-letter icon-paint' title='Цвет' onclick="newsFormatDoc('BackColor',newsBgColor)">

                      <div class='panel-news_add-nav-elem-letter-color' id='panel-news_add-nav-elem-letter-bg_color-id'></div>
                      <div class='panel-news_add-nav-elem-edit-color' title=''>
                      <div class='panel-news_add-nav-elem-edit-color-title'>Настройка фона текста</div>
                      <div class='panel-news_add-nav-elem-edit-color-container'>
                        <div class='panel-news_add-nav-elem-edit-color-container-hover' onclick="color_selection_3(this); newsFormatDoc('BackColor','#fff0')">
                          <div class='panel-news_add-nav-elem-edit-color-title-2' style='margin-bottom: 0px;'>
                            <div class='panel-news_add-nav-elem-edit-color-title-2-ico-2 icon-no_paint'></div>
                            <div class='panel-news_add-nav-elem-edit-color-title-2-text'>Нет цвета</div>
                          </div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-title-2'>Цвета темы</div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #ffffff;' title='#ffffff'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #f2f2f2;' title='#f2f2f2'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #d8d8d8;' title='#d8d8d8'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #bfbfbf;' title='#bfbfbf'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #a5a5a5;' title='#a5a5a5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #7f7f7f;' title='#7f7f7f'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #000000;' title='#000000'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #7f7f7f;' title='#7f7f7f'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #595959;' title='#595959'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #3f3f3f;' title='#3f3f3f'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #262626;' title='#262626'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #0c0c0c;' title='#0c0c0c'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #e7e6e6;' title='#e7e6e6'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #d0cece;' title='#d0cece'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #aeabab;' title='#aeabab'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #757070;' title='#757070'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #3a3838;' title='#3a3838'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #171616;' title='#171616'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #44546a;' title='#44546a'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #d6dce4;' title='#d6dce4'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #adb9ca;' title='#adb9ca'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #8496b0;' title='#8496b0'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #323f4f;' title='#323f4f'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #222a35;' title='#222a35'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #4472c4;' title='#4472c4'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #d9e2f3;' title='#d9e2f3'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #b4c6e7;' title='#b4c6e7'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #8eaadb;' title='#8eaadb'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #2f5496;' title='#2f5496'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #1f3864;' title='#1f3864'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #ed7d31;' title='#ed7d31'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #fbe5d5;' title='#fbe5d5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #f7cbac;' title='#f7cbac'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #f4b183;' title='#f4b183'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #c55a11;' title='#c55a11'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #833c0b;' title='#833c0b'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #a5a5a5;' title='#a5a5a5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #ededed;' title='#ededed'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #dbdbdb;' title='#dbdbdb'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #c9c9c9;' title='#c9c9c9'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #7b7b7b;' title='#7b7b7b'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #525252;' title='#525252'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #ffc000;' title='#ffc000'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #fff2cc;' title='#fff2cc'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #fee599;' title='#fee599'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #ffd965;' title='#ffd965'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #bf9000;' title='#bf9000'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #7f6000;' title='#7f6000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #5b9bd5;' title='#5b9bd5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #deebf6;' title='#deebf6'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #bdd7ee;' title='#bdd7ee'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #9cc3e5;' title='#9cc3e5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #2e75b5;' title='#2e75b5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #1e4e79;' title='#1e4e79'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #70ad47;' title='#70ad47'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #e2efd9;' title='#e2efd9'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #c5e0b3;' title='#c5e0b3'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #a8d08d;' title='#a8d08d'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #538135;' title='#538135'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #375623;' title='#375623'></div>
                        </div>
                      </div>
                      <div class='panel-news_add-nav-elem-edit-color-container'>
                        <div class='panel-news_add-nav-elem-edit-color-title-2'>Стандартные цвета</div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #c00000;' title='#c00000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #ff0000;' title='#ff0000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #ffc000;' title='#ffc000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #ffff00;' title='#ffff00'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #92d050;' title='#92d050'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #00b050;' title='#00b050'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #00b0f0;' title='#00b0f0'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #0070c0;' title='#0070c0'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #002060;' title='#002060'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #7030a0;' title='#7030a0'></div>
                        </div>
                      </div>
                      <?php

                        if(isset($_COOKIE['NewsColorArray_2'])){
                          $NewsColorArray = $_COOKIE['NewsColorArray_2'];
                          if(!empty($_COOKIE['NewsColorArray_2'])){

                            $NewsColorArray = explode('_', $NewsColorArray);
                            $NewsColorArrayDiv_2 = '';

                            if($NewsColorArray[0] != '000'){
                              $NewsColorArrayStyle = 'display: block;';

                              for($i=0; $i < count($NewsColorArray); $i++) {
                                $NewsColorArrayL = '#'.$NewsColorArray[$i];
                                $NewsColorArrayDiv_2 = $NewsColorArrayDiv_2."<div class='panel-news_add-nav-elem-edit-color-container-big_elem' style='margin-right: 3px;'><div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick='color_selection_2(this)' style='background-color: ".$NewsColorArrayL.";' title='".$NewsColorArrayL."'></div></div>";
                              }


                            } else{
                              $NewsColorArrayStyle_2 = 'display: none;';
                            }
                          } else{
                            $NewsColorArrayStyle_2 = 'display: none;';
                          }
                        } else{
                          $NewsColorArrayStyle_2 = 'display: none;';
                        }?>
                      <div class='panel-news_add-nav-elem-edit-color-container' id='custom-bg_color-new' style='<?php echo(@$NewsColorArrayStyle_2);?>'>
                        <div class='panel-news_add-nav-elem-edit-color-title-2'>Мои цвета</div>
                        <span id='custom-bg_color-new-span'>
                          <?php echo(@$NewsColorArrayDiv_2);?>
                        </span>
                      </div>
                      <div class='panel-news_add-nav-elem-edit-color-container-hover' onclick="open_window('#edit-bg_color')">
                        <div class='panel-news_add-nav-elem-edit-color-title-2' style='margin-bottom: 0px;'>
                          <div class='panel-news_add-nav-elem-edit-color-title-2-ico'></div>
                          <div class='panel-news_add-nav-elem-edit-color-title-2-text'>Другие цета</div>
                        </div>
                      </div>

                    </div></div>
                    <div class='panel-news_add-nav-elem-size' style='width: 50px;' title='Размер'>
                      <div class='panel-news_add-nav-elem-size21 icon-text_left'></div>
                      <div class='panel-news_add-nav-elem-size2 icon-left' style='margin-left: 4px;'></div>
                      <div class='panel-news_add-nav-elem-size-select-108' style='width: calc(100% + 10px);' onhover="$(this).parent().css('border','2px solid var(--border-color)'); $(this).parent().css('background-color','var(--main-bg)')">
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2 icon-text_left' title='Выравнивание по левому краю' onclick='newsFormatDoc("justifyLeft");'></div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2 icon-text_center' title='Выравнивание по центру' onclick='newsFormatDoc("justifyCenter");'></div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2 icon-text_right' title='Выравнивание по правому краю' onclick='newsFormatDoc("justifyRight");'></div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2 icon-text_justify' title='Выравнивание по ширине' onclick='newsFormatDoc("justifyFull");'></div>
                      </div>
                    </div>
                    <div class='panel-news_add-nav-elem icon-marker_list' style='font-weight: 500;' title='Маркерованный список' onclick='newsFormatDoc("insertUnorderedList");'></div>
                    <div class='panel-news_add-nav-elem icon-numbered_list' style='font-weight: 500;' title='Нумерованный список' onclick='newsFormatDoc("insertOrderedList");'></div>
                    <div class='panel-news_add-nav-elem icon-link' style='font-weight: 500;' title='Добавить ссылку' onclick='newsCreateLink();'></div>
                    <!-- <div class='panel-news_add-nav-elem' title=''></div> -->

                  </div>
                  <div class='panel-news-description'>Данный раздел предназначен только для грамотных пользователей. Будьте аккуратны когда изменяете здесь, что-то это может вызвать фатальные ошибки в работе сайта!</div>
                  <div class='panel-news-conteiner'>
                    <input class='panel-news-conteiner-title' id='panel-news-conteiner-title-id' placeholder="Заголовок статьи">
                    <div contenteditable="true" class='panel-news-conteiner-text' id='panel-news-conteiner-text-id'></div>
                    <pre style='display: none; margin-bottom: -30px; margin-top: -15px; white-space: pre-wrap;'>
                      <code  contenteditable="true" onblur="edit_code_news(this)" id='panel-news-conteiner-code-id' class='panel-news-conteiner-code html'></code>
                    </pre>
                  </div>
                  <div class='panel-news-document' id="news-attachments-container">
                    <div class='panel-news-document-add' style='margin-bottom: 5px;' onclick="open_window('#news-add-file')">
                      <div class='panel-news-document-add-ico icon-file'></div>
                      <div class='panel-news-document-add-text'>Прикрепить<br>файл</div>
                    </div>
                    <div class='panel-news-document-file' style='margin-bottom: 5px;'>
                      <div class='panel-news-document-add-mainbg' style='background-image: url("media/tmp/test.jpg");'></div>
                      <div class='panel-news-document-add-del icon-plus' onclick="news_del_file(this);" title='Удалить'></div>
                      <div class='panel-news-document-add-bg'></div>
                    </div>
                    <div class='panel-news-document-file' style='margin-bottom: 5px;'>
                      <div class='panel-news-document-add-mainbg' style='background-image: url("media/img/online.png");'></div>
                      <div class='panel-news-document-add-del icon-plus' onclick="news_del_file(this);" title='Удалить'></div>
                      <div class='panel-news-document-add-bg'></div>
                    </div>
                    <div class='panel-news-document-file-noIMG' style='margin-bottom: 5px;'>
                      <div class='panel-news-document-add-del icon-plus' onclick="news_del_file(this);" title='Удалить'></div>
                      <div class='panel-news-document-add-ico icon-video'></div>
                      <div class='panel-news-document-add-text'>Имя_файла.mp4</div>
                    </div>
                    <div class='panel-news-document-file-noIMG' style='margin-bottom: 5px;'>
                      <div class='panel-news-document-add-del icon-plus' onclick="news_del_file(this);" title='Удалить'></div>
                      <div class='panel-news-document-add-ico icon-document'></div>
                      <div class='panel-news-document-add-text'>Новый документ.docx</div>
                    </div>
                    <div class='panel-news-document-file-noIMG' style='margin-bottom: 5px;'>
                      <div class='panel-news-document-add-del icon-plus' onclick="news_del_file(this);" title='Удалить'></div>
                      <div class='panel-news-document-add-ico icon-music'></div>
                      <div class='panel-news-document-add-text'>F24C.mp3</div>
                    </div>
                    <div class='panel-news-document-file-noIMG' style='margin-bottom: 5px;'>
                      <div class='panel-news-document-add-del icon-plus' onclick="news_del_file(this);" title='Удалить'></div>
                      <div class='panel-news-document-add-ico icon-file2'></div>
                      <div class='panel-news-document-add-text'>Новый архив 22.zip</div>
                    </div>
                  </div>
                </div>
              </div>


            </div>
          </div>
        </div>
        <?php endif;?>
        <?php if ($aboutCompanyPanel): ?>
        <div class='panel' id='about_company' search-js-elem='О компании, section-block, #about_company, 👔, Ваша история, [История компании, о компании, компания]' style='<?php echo($page[6][1]);?>'>
          <div class='panel-title'>О компании</div>
          <div class='panel-conteiner-all'>
            <div class='panel-news_add'>
              <div class='panel-news_add-title'>Описание компании</div>
              <div class='panel-news_tabs'>
                <span>
                  <div class='panel-news_tabs-tabs' id='panel-news_tabs-tabs-standart-2' onclick="about_company_type('standart')">Стандартный</div>
                  <div class='panel-news_tabs-tabs' id='panel-news_tabs-tabs-pro-2' onclick="about_company_type('pro')" style='z-index: -1; padding-bottom: 0px; filter: grayscale(100%) brightness(95%);'>Профи</div>
                </span>
                <div class='panel-news_tabs-main'>
                  <div class='panel-news_add-nav'>
                    <div class='panel-news_add-nav-elem icon-save2'  title='Сохранить' onclick=""></div>
                    <div class='panel-news_add-nav-elem icon-publish'  title='Опубликовать' onclick=""></div>
                    <div class='panel-news_add-nav-elem icon-print' style='font-weight: 500;' title='Печать' onclick="printDoc();"></div>
                    <div class='panel-news_add-nav-elem icon-back' title='Отменить' onclick=""></div>
                    <div class='panel-news_add-nav-elem-disabled icon-repeat' title='Повторить' onclick=""></div>
                    <div class='panel-news_add-nav-elem icon-clear' style='font-weight: 500;' title='Отчистить все' onclick=""></div>
                    <div class='panel-news_add-nav-elem icon-bold' title='Полужирный' onclick="edit_text('bold')"></div>
                    <div class='panel-news_add-nav-elem icon-italic' title='Курсив'></div>
                    <div class='panel-news_add-nav-elem icon-unline' title='Подчёркнутый'></div>
                    <div class='panel-news_add-nav-elem icon-strike' title='Зачёркнутый'></div>
                    <div class='panel-news_add-nav-elem-size' title='Размер'>
                      <div contenteditable="true" class='panel-news_add-nav-elem-size1' onblur="$(this).parent().removeAttr('style');" onfocus="$(this).parent().css('border','2px solid var(--border-color)'); $(this).parent().css('background-color','var(--main-bg)')">12</div>
                      <div class='panel-news_add-nav-elem-size2 icon-left'></div>
                      <div class='panel-news_add-nav-elem-size-select' onhover="$(this).parent().css('border','2px solid var(--border-color)'); $(this).parent().css('background-color','var(--main-bg)')">
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>8</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>9</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>10</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>11</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>12</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>14</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>16</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>18</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>20</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>22</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>24</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>26</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>28</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>36</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>48</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem'>72</div>
                      </div>
                    </div>
                    <div class='panel-news_add-nav-elem-size' title='Регистр' style='width: 46px;'>
                      <div class='panel-news_add-nav-elem-size11 icon-register'></div>
                      <div class='panel-news_add-nav-elem-size2 icon-left'></div>
                      <div class='panel-news_add-nav-elem-size-select-2' onhover="$(this).parent().css('border','2px solid var(--border-color)'); $(this).parent().css('background-color','var(--main-bg)')">
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2'>Как в предложениях</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2'>все строчные</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2'>ВСЕ ПРОПИСНЫЕ</div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2'>Наничнать С Прописных</div>
                      </div>
                    </div>
                    <div class='panel-news_add-nav-elem-letter' title='Цвет'>
                      A
                      <div class='panel-news_add-nav-elem-letter-color' id='panel-news_add-nav-elem-letter-color-id-company'></div>
                      <div class='panel-news_add-nav-elem-edit-color' title=''>
                      <div class='panel-news_add-nav-elem-edit-color-title'>Настройка цвета текста</div>
                      <div class='panel-news_add-nav-elem-edit-color-container'>
                        <div class='panel-news_add-nav-elem-edit-color-title-2'>Цвета темы</div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #ffffff;' title='#ffffff'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #f2f2f2;' title='#f2f2f2'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #d8d8d8;' title='#d8d8d8'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #bfbfbf;' title='#bfbfbf'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #a5a5a5;' title='#a5a5a5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #7f7f7f;' title='#7f7f7f'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #000000;' title='#000000'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #7f7f7f;' title='#7f7f7f'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #595959;' title='#595959'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #3f3f3f;' title='#3f3f3f'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #262626;' title='#262626'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #0c0c0c;' title='#0c0c0c'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #e7e6e6;' title='#e7e6e6'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #d0cece;' title='#d0cece'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #aeabab;' title='#aeabab'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #757070;' title='#757070'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #3a3838;' title='#3a3838'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #171616;' title='#171616'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #44546a;' title='#44546a'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #d6dce4;' title='#d6dce4'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #adb9ca;' title='#adb9ca'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #8496b0;' title='#8496b0'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #323f4f;' title='#323f4f'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #222a35;' title='#222a35'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #4472c4;' title='#4472c4'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #d9e2f3;' title='#d9e2f3'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #b4c6e7;' title='#b4c6e7'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #8eaadb;' title='#8eaadb'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #2f5496;' title='#2f5496'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #1f3864;' title='#1f3864'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #ed7d31;' title='#ed7d31'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #fbe5d5;' title='#fbe5d5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #f7cbac;' title='#f7cbac'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #f4b183;' title='#f4b183'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #c55a11;' title='#c55a11'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #833c0b;' title='#833c0b'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #a5a5a5;' title='#a5a5a5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #ededed;' title='#ededed'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #dbdbdb;' title='#dbdbdb'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #c9c9c9;' title='#c9c9c9'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #7b7b7b;' title='#7b7b7b'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #525252;' title='#525252'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #ffc000;' title='#ffc000'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #fff2cc;' title='#fff2cc'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #fee599;' title='#fee599'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #ffd965;' title='#ffd965'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #bf9000;' title='#bf9000'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #7f6000;' title='#7f6000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #5b9bd5;' title='#5b9bd5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #deebf6;' title='#deebf6'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #bdd7ee;' title='#bdd7ee'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #9cc3e5;' title='#9cc3e5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #2e75b5;' title='#2e75b5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #1e4e79;' title='#1e4e79'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='margin-bottom: 6px; background-color: #70ad47;' title='#70ad47'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #e2efd9;' title='#e2efd9'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #c5e0b3;' title='#c5e0b3'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #a8d08d;' title='#a8d08d'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #538135;' title='#538135'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #375623;' title='#375623'></div>
                        </div>
                      </div>
                      <div class='panel-news_add-nav-elem-edit-color-container'>
                        <div class='panel-news_add-nav-elem-edit-color-title-2'>Стандартные цвета</div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #c00000;' title='#c00000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #ff0000;' title='#ff0000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #ffc000;' title='#ffc000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #ffff00;' title='#ffff00'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #92d050;' title='#92d050'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #00b050;' title='#00b050'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #00b0f0;' title='#00b0f0'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #0070c0;' title='#0070c0'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #002060;' title='#002060'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection(this)" style='background-color: #7030a0;' title='#7030a0'></div>
                        </div>
                      </div>
                      <?php

                        if(isset($_COOKIE['NewsColorArray'])){
                          $NewsColorArray = $_COOKIE['NewsColorArray'];
                          if(!empty($_COOKIE['NewsColorArray'])){

                            $NewsColorArray = explode('_', $NewsColorArray);
                            $NewsColorArrayDiv = '';

                            if($NewsColorArray[0] != '000'){
                              $NewsColorArrayStyle = 'display: block;';

                              for($i=0; $i < count($NewsColorArray); $i++) {
                                $NewsColorArrayL = '#'.$NewsColorArray[$i];
                                $NewsColorArrayDiv = $NewsColorArrayDiv."<div class='panel-news_add-nav-elem-edit-color-container-big_elem' style='margin-right: 3px;'><div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick='color_selection(this)' style='background-color: ".$NewsColorArrayL.";' title='".$NewsColorArrayL."'></div></div>";
                              }


                            } else{
                              $NewsColorArrayStyle = 'display: none;';
                            }
                          } else{
                            $NewsColorArrayStyle = 'display: none;';
                          }
                        } else{
                          $NewsColorArrayStyle = 'display: none;';
                        }?>
                      <div class='panel-news_add-nav-elem-edit-color-container' id='custom-color-new-company' style='<?php echo(@$NewsColorArrayStyle);?>'>
                        <div class='panel-news_add-nav-elem-edit-color-title-2'>Мои цвета</div>
                        <span id='custom-color-new-span-company'>
                          <?php echo(@$NewsColorArrayDiv);?>
                        </span>
                      </div>
                      <div class='panel-news_add-nav-elem-edit-color-container-hover' onclick="open_window('#edit-color')">
                        <div class='panel-news_add-nav-elem-edit-color-title-2' style='margin-bottom: 0px;'>
                          <div class='panel-news_add-nav-elem-edit-color-title-2-ico'></div>
                          <div class='panel-news_add-nav-elem-edit-color-title-2-text'>Другие цета</div>
                        </div>
                      </div>

                    </div></div>
                    <div class='panel-news_add-nav-elem-letter icon-paint' title='Цвет'>

                      <div class='panel-news_add-nav-elem-letter-color' id='panel-news_add-nav-elem-letter-bg_color-id-company'></div>
                      <div class='panel-news_add-nav-elem-edit-color' title=''>
                      <div class='panel-news_add-nav-elem-edit-color-title'>Настройка фона текста</div>
                      <div class='panel-news_add-nav-elem-edit-color-container'>
                        <div class='panel-news_add-nav-elem-edit-color-container-hover'>
                          <div class='panel-news_add-nav-elem-edit-color-title-2' style='margin-bottom: 0px;'>
                            <div class='panel-news_add-nav-elem-edit-color-title-2-ico-2 icon-no_paint'></div>
                            <div class='panel-news_add-nav-elem-edit-color-title-2-text'>Нет цвета</div>
                          </div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-title-2'>Цвета темы</div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #ffffff;' title='#ffffff'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #f2f2f2;' title='#f2f2f2'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #d8d8d8;' title='#d8d8d8'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #bfbfbf;' title='#bfbfbf'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #a5a5a5;' title='#a5a5a5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #7f7f7f;' title='#7f7f7f'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #000000;' title='#000000'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #7f7f7f;' title='#7f7f7f'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #595959;' title='#595959'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #3f3f3f;' title='#3f3f3f'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #262626;' title='#262626'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #0c0c0c;' title='#0c0c0c'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #e7e6e6;' title='#e7e6e6'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #d0cece;' title='#d0cece'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #aeabab;' title='#aeabab'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #757070;' title='#757070'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #3a3838;' title='#3a3838'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #171616;' title='#171616'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #44546a;' title='#44546a'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #d6dce4;' title='#d6dce4'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #adb9ca;' title='#adb9ca'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #8496b0;' title='#8496b0'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #323f4f;' title='#323f4f'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #222a35;' title='#222a35'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #4472c4;' title='#4472c4'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #d9e2f3;' title='#d9e2f3'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #b4c6e7;' title='#b4c6e7'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #8eaadb;' title='#8eaadb'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #2f5496;' title='#2f5496'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #1f3864;' title='#1f3864'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #ed7d31;' title='#ed7d31'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #fbe5d5;' title='#fbe5d5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #f7cbac;' title='#f7cbac'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #f4b183;' title='#f4b183'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #c55a11;' title='#c55a11'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #833c0b;' title='#833c0b'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #a5a5a5;' title='#a5a5a5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #ededed;' title='#ededed'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #dbdbdb;' title='#dbdbdb'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #c9c9c9;' title='#c9c9c9'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #7b7b7b;' title='#7b7b7b'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #525252;' title='#525252'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #ffc000;' title='#ffc000'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #fff2cc;' title='#fff2cc'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #fee599;' title='#fee599'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #ffd965;' title='#ffd965'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #bf9000;' title='#bf9000'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #7f6000;' title='#7f6000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #5b9bd5;' title='#5b9bd5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #deebf6;' title='#deebf6'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #bdd7ee;' title='#bdd7ee'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #9cc3e5;' title='#9cc3e5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #2e75b5;' title='#2e75b5'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #1e4e79;' title='#1e4e79'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='margin-bottom: 6px; background-color: #70ad47;' title='#70ad47'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #e2efd9;' title='#e2efd9'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #c5e0b3;' title='#c5e0b3'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #a8d08d;' title='#a8d08d'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #538135;' title='#538135'></div>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #375623;' title='#375623'></div>
                        </div>
                      </div>
                      <div class='panel-news_add-nav-elem-edit-color-container'>
                        <div class='panel-news_add-nav-elem-edit-color-title-2'>Стандартные цвета</div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #c00000;' title='#c00000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #ff0000;' title='#ff0000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #ffc000;' title='#ffc000'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #ffff00;' title='#ffff00'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #92d050;' title='#92d050'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #00b050;' title='#00b050'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #00b0f0;' title='#00b0f0'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #0070c0;' title='#0070c0'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #002060;' title='#002060'></div>
                        </div>
                        <div class='panel-news_add-nav-elem-edit-color-container-big_elem'>
                          <div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick="color_selection_2(this)" style='background-color: #7030a0;' title='#7030a0'></div>
                        </div>
                      </div>
                      <?php

                        if(isset($_COOKIE['NewsColorArray_2'])){
                          $NewsColorArray = $_COOKIE['NewsColorArray_2'];
                          if(!empty($_COOKIE['NewsColorArray_2'])){

                            $NewsColorArray = explode('_', $NewsColorArray);
                            $NewsColorArrayDiv_2 = '';

                            if($NewsColorArray[0] != '000'){
                              $NewsColorArrayStyle = 'display: block;';

                              for($i=0; $i < count($NewsColorArray); $i++) {
                                $NewsColorArrayL = '#'.$NewsColorArray[$i];
                                $NewsColorArrayDiv_2 = $NewsColorArrayDiv_2."<div class='panel-news_add-nav-elem-edit-color-container-big_elem' style='margin-right: 3px;'><div class='panel-news_add-nav-elem-edit-color-container-big_elem-elem' onclick='color_selection_2(this)' style='background-color: ".$NewsColorArrayL.";' title='".$NewsColorArrayL."'></div></div>";
                              }


                            } else{
                              $NewsColorArrayStyle_2 = 'display: none;';
                            }
                          } else{
                            $NewsColorArrayStyle_2 = 'display: none;';
                          }
                        } else{
                          $NewsColorArrayStyle_2 = 'display: none;';
                        }?>
                      <div class='panel-news_add-nav-elem-edit-color-container' id='custom-bg_color-new-company' style='<?php echo(@$NewsColorArrayStyle_2);?>'>
                        <div class='panel-news_add-nav-elem-edit-color-title-2'>Мои цвета</div>
                        <span id='custom-bg_color-new-span-company'>
                          <?php echo(@$NewsColorArrayDiv_2);?>
                        </span>
                      </div>
                      <div class='panel-news_add-nav-elem-edit-color-container-hover' onclick="open_window('#edit-bg_color')">
                        <div class='panel-news_add-nav-elem-edit-color-title-2' style='margin-bottom: 0px;'>
                          <div class='panel-news_add-nav-elem-edit-color-title-2-ico'></div>
                          <div class='panel-news_add-nav-elem-edit-color-title-2-text'>Другие цета</div>
                        </div>
                      </div>

                    </div></div>
                    <div class='panel-news_add-nav-elem-size' style='width: 46px;' title='Размер'>
                      <div class='panel-news_add-nav-elem-size21 icon-text_left'></div>
                      <div class='panel-news_add-nav-elem-size2 icon-left'></div>
                      <div class='panel-news_add-nav-elem-size-select-108' style='width: calc(100% + 10px);' onhover="$(this).parent().css('border','2px solid var(--border-color)'); $(this).parent().css('background-color','var(--main-bg)')">
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2 icon-text_left' title='Выравнивание по левому краю'></div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2 icon-text_center' title='Выравнивание по центру'></div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2 icon-text_right' title='Выравнивание по правому краю'></div>
                        <div class='panel-news_add-nav-elem-size-select-select-elem-2 icon-text_justify' title='Выравнивание по ширине'></div>
                      </div>
                    </div>
                    <div class='panel-news_add-nav-elem icon-marker_list' style='font-weight: 500;' title='Маркерованный список'></div>
                    <div class='panel-news_add-nav-elem icon-numbered_list' style='font-weight: 500;' title='Нумерованный список'></div>
                    <div class='panel-news_add-nav-elem icon-link' style='font-weight: 500;' title='Добавить ссылку' onclick=""></div>
                    <!-- <div class='panel-news_add-nav-elem' title=''></div> -->

                  </div>
                  <div class='panel-news-description'>Данный раздел предназначен только для грамотных пользователей. Будьте аккуратны когда изменяете здесь, что-то это может вызвать фатальные ошибки в работе сайта!</div>
                  <div class='panel-news-conteiner'>
                    <div contenteditable="true" class='panel-news-conteiner-title' id='panel-news-conteiner-title-id-company'>Заголовок статьи</div>
                    <div contenteditable="true" onselect="select_text();" class='panel-news-conteiner-text' id='panel-news-conteiner-text-id-2-company'><p><b>many</b> symbols</p>
                      <script>
                      function that_click() {
                          var selected_text= window.getSelection();
                          alert(selected_text);
                      }
                      </script>
                      <a onclick="that_click()">click to try</a>
                      </div>
                    <pre style='display: none; margin-bottom: -30px; margin-top: -15px;'>
                      <code  contenteditable="true" onblur="edit_code_news(this)" id='panel-news-conteiner-code-id-2-company' class='panel-news-conteiner-code html'></code>
                    </pre>
                  </div>
                  <div class='panel-news-document'>
                    <div class='panel-news-document-add' style='margin-bottom: 5px;' onclick="open_window('#news-add-file')">
                      <div class='panel-news-document-add-ico icon-file'></div>
                      <div class='panel-news-document-add-text'>Прикрепить<br>файл</div>
                    </div>
                    <div class='panel-news-document-file' style='margin-bottom: 5px;'>
                      <div class='panel-news-document-add-mainbg' style='background-image: url("media/tmp/test.jpg");'></div>
                      <div class='panel-news-document-add-del icon-plus' onclick="news_del_file(this);" title='Удалить'></div>
                      <div class='panel-news-document-add-bg'></div>
                    </div>
                    <div class='panel-news-document-file' style='margin-bottom: 5px;'>
                      <div class='panel-news-document-add-mainbg' style='background-image: url("media/img/online.png");'></div>
                      <div class='panel-news-document-add-del icon-plus' onclick="news_del_file(this);" title='Удалить'></div>
                      <div class='panel-news-document-add-bg'></div>
                    </div>
                    <div class='panel-news-document-file-noIMG' style='margin-bottom: 5px;'>
                      <div class='panel-news-document-add-del icon-plus' onclick="news_del_file(this);" title='Удалить'></div>
                      <div class='panel-news-document-add-ico icon-video'></div>
                      <div class='panel-news-document-add-text'>Имя_файла.mp4</div>
                    </div>
                    <div class='panel-news-document-file-noIMG' style='margin-bottom: 5px;'>
                      <div class='panel-news-document-add-del icon-plus' onclick="news_del_file(this);" title='Удалить'></div>
                      <div class='panel-news-document-add-ico icon-document'></div>
                      <div class='panel-news-document-add-text'>Новый документ.docx</div>
                    </div>
                    <div class='panel-news-document-file-noIMG' style='margin-bottom: 5px;'>
                      <div class='panel-news-document-add-del icon-plus' onclick="news_del_file(this);" title='Удалить'></div>
                      <div class='panel-news-document-add-ico icon-music'></div>
                      <div class='panel-news-document-add-text'>F24C.mp3</div>
                    </div>
                    <div class='panel-news-document-file-noIMG' style='margin-bottom: 5px;'>
                      <div class='panel-news-document-add-del icon-plus' onclick="news_del_file(this);" title='Удалить'></div>
                      <div class='panel-news-document-add-ico icon-file2'></div>
                      <div class='panel-news-document-add-text'>Новый архив 22.zip</div>
                    </div>
                  </div>
                </div>
              </div>


            </div>
          </div>
        </div>
        <?php endif;?>
        <?php if ($usersPanel): ?>
        <div class='panel' id='all_user' search-js-elem='Все пользователи, section-block, #all_user, 🤟🏼, Удаляй и редактируй, [Все пользователи, Пользователи, Юзеры, Бан, блокировка]' style='<?php echo($page[7][1]);?>'>
          <div class='panel-title'>Все пользователи</div>
          <div class='panel-conteiner'>
            <div class='panel-filter'>
              <div class='panel-filter-title'>Фильтры</div>
              <label for='searchFilter1' class='main-nav-search-2'>
                <span class='main-nav-search-icon icon-search'></span>
                <input type='text' placeholder="Поиск" id='searchFilter1' class='main-nav-search-input-2'>
              </label>
              <div class='panel-filter-title-2'>Пользователи</div>
              <div style='margin-left: -5px;'>
                <div class='checkbox-login'>
                  <input type='checkbox' id='chb-filter-all-user-1' checked name='chb-filter-all-user' style='display: none;'>
                  <label for='chb-filter-all-user-1' class='checkbox-login-chb1'></label>
                  <label for='chb-filter-all-user-1' class='checkbox-login-chb3'>
                    <div>Swiftly Admin Panel</div>
                  </label>
                </div>

                <div class='checkbox-login'>
                  <input type='checkbox' id='chb-filter-all-user-2' name='chb-filter-all-user' style='display: none;'>
                  <label for='chb-filter-all-user-2' class='checkbox-login-chb1'></label>
                  <label for='chb-filter-all-user-2' class='checkbox-login-chb3'>
                    <div><?php echo $_SERVER['SERVER_NAME']; if($_SERVER['SERVER_PORT'] != '80'){echo(":".$_SERVER['SERVER_PORT']);}?></div>
                  </label>
                </div>
              </div>

              <div class='panel-filter-title-2'>Статус</div>
              <div style='margin-left: 20px; margin-right: 20px;'>

                <input id='filter2' type='checkbox' style='display: none;' >
                <label class='panel-filter-elem' id='filter2-1' for='filter2'>
                  <span class='panel-filter-elem-text'>Администратор</span>
                  <span class='icon-add panel-filter-elem-plus'></span>
                </label>

                <input id='filter1' type='checkbox' style='display: none;' >
                <label class='panel-filter-elem' id='filter1-1' for='filter1'>
                  <span class='panel-filter-elem-text'>Главный<br>администратор</span>
                  <span class='icon-add panel-filter-elem-plus'></span>
                </label>

                <input id='filter6' type='checkbox' style='display: none;' >
                <label class='panel-filter-elem' id='filter6-1' for='filter6'>
                  <span class='panel-filter-elem-text'>Стандартный</span>
                  <span class='icon-add panel-filter-elem-plus'></span>
                </label>

                <input id='filter3' type='checkbox' style='display: none;' >
                <label class='panel-filter-elem' id='filter3-1' for='filter3'>
                  <span class='panel-filter-elem-text'>Модератор</span>
                  <span class='icon-add panel-filter-elem-plus'></span>
                </label>

                <input id='filter5' type='checkbox' style='display: none;' >
                <label class='panel-filter-elem' id='filter5-1' for='filter5'>
                  <span class='panel-filter-elem-text'>Редактор</span>
                  <span class='icon-add panel-filter-elem-plus'></span>
                </label>

              </div>

              <div class='panel-filter-title-2'>Регистрация</div>

              <div class='input-login' style='margin-left: 20px; width: auto; max-width: 290px; min-width: 100px; margin-right: 20px;'>
                <input value='<?php //echo($userData['birthday'])?>' required='required' type='date'>
                <span class='placeholder-white'>Начало</span>
              </div>

              <div class='input-login' style='margin-left: 20px; width: auto; max-width: 290px; min-width: 100px; margin-right: 20px;'>
                <input value='<?php //echo($userData['birthday'])?>' required='required' type='date'>
                <span class='placeholder-white'>Конец</span>
              </div>

              <div class='panel-filter-btn'>Найти</div>

            </div>
            <div class='panel-table-btn'>
              <div class='panel-filter-title' style='margin-bottom: 15px;'>Настройка</div>
              <div class="placeholder-white-help">
                <select class='input-login-select' style='margin-left: 20px; width: calc(100% - 40px); outline: none; max-width: 290px; min-width: 100px; margin-right: 20px; margin-bottom: 0px;'>
                  <option>10</option>
                  <option>25</option>
                  <option>50</option>
                  <option>100</option>
                  <option>500</option>
                </select>
                <span class='placeholder-white-help-1'>Элементов</span>
              </div>

            </div>
            <div class='panel-table-btn'>
              <div class='panel-filter-title' style='margin-bottom: 15px;'>Страницы</div>
              <div class='panel-table-btn-elem icon-left' title='Назад'></div>
              <div class='panel-table-btn-elem' style='font-size: 16px;' title='2 страница'>2</div>
              <div class='panel-table-btn-elem' style='font-size: 16px;' title='3 страница'>3</div>
              <div class='panel-table-btn-elem' style='font-size: 16px;' title='4 страница'>4</div>
              <div class='panel-table-btn-elem icon-right' title='Вперед'></div>
            </div>
          </div>
          <div class='panel-table'>
            <div class='panel-table-title'>Все пользователи Swiftly Admin Panel</div>
            <div class='panel-table-filter'>
              <div class='panel-table-filter-elem-id'>
                id
                <div class='panel-table-filter-elem-ico icon-left'></div>
              </div>
              <div class='panel-table-filter-elem-main'>
                Имя
                <div class='panel-table-filter-elem-ico icon-left'></div>
              </div>
              <div class='panel-table-filter-elem-date' title='Дата регистрации'>
                Дата рег...
                <div class='panel-table-filter-elem-ico icon-left'></div>
              </div>
              <div class='panel-table-filter-elem-status'>
                Статус
                <div class='panel-table-filter-elem-ico icon-left'></div>
              </div>
              <div class='panel-table-filter-elem-act'>
                Действия
                <div class='panel-table-filter-elem-ico icon-left'></div>
              </div>
            </div>
            <div class='panel-table-new-main'>
              <div class='panel-table-new-main-elem'>
                <div class='panel-table-new-main-elem-id'>1</div>
                <div class='panel-table-new-main-elem-main'>
                  <div class='panel-table-new-main-elem-main-img' style='background-image: url("media/users/support.jpg")'>
                    <div class='panel-table-new-main-elem-main-img-online' title='Online'></div>
                  </div>
                  <div class='panel-table-new-main-elem-main-text'>
                    <div class='panel-table-new-main-elem-main-text-name'>
                      <div class='panel-table-new-main-elem-main-text-name-name'>INSOweb</div>
                      <div class='panel-table-new-main-elem-main-text-name-login'>insoweb</div>
                    </div>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-mail'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>support@insoweb.ru</div>
                    </a><br>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-tel'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>+7 (912) 00 00 001</div>
                    </a>
                  </div>
                </div>
                <div class='panel-table-new-main-elem-date'>12.01.2019</div>
                <div class='panel-table-new-main-elem-status'>
                  <div class='panel-table-new-main-elem-status-block' style='background-color: #ffb822;'>Главный администратор</div>
                </div>
                <div class='panel-table-new-main-elem-act'>
                  <div class='panel-table-new-main-elem-act-edit'>Редактирование прав</div>
                  <div class='panel-table-new-main-elem-act-edit' onclick="open_window('#user-edit')">Редактирование пользователя</div>
                  <div class='panel-table-new-main-elem-act-edit'>Написать сообщение</div>
                </div>
              </div>
              <div class='panel-table-new-main-elem'>
                <div class='panel-table-new-main-elem-id'>2</div>
                <div class='panel-table-new-main-elem-main'>
                  <div class='panel-table-new-main-elem-main-img' style='background-image: url("media/users/8.jpg")'>
                    <div class='panel-table-new-main-elem-main-img-online' title='Online'></div>
                  </div>
                  <div class='panel-table-new-main-elem-main-text'>
                    <div class='panel-table-new-main-elem-main-text-name'>
                      <div class='panel-table-new-main-elem-main-text-name-name'>Имя Фамилия</div>
                      <div class='panel-table-new-main-elem-main-text-name-login'>login</div>
                    </div>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-mail'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>example@domen.ru</div>
                    </a><br>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-tel'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>+7 (912) 00 00 000</div>
                    </a>
                  </div>
                </div>
                <div class='panel-table-new-main-elem-date'>19.05.2019</div>
                <div class='panel-table-new-main-elem-status'>
                  <div class='panel-table-new-main-elem-status-block' style='background-color: #fd397a;'>Редактор</div>
                </div>
                <div class='panel-table-new-main-elem-act'>
                  <div class='panel-table-new-main-elem-act-edit'>Редактирование прав</div>
                  <div class='panel-table-new-main-elem-act-edit' onclick="open_window('#user-edit')">Редактирование пользователя</div>
                  <div class='panel-table-new-main-elem-act-edit'>Написать сообщение</div>
                </div>
              </div>
              <div class='panel-table-new-main-elem'>
                <div class='panel-table-new-main-elem-id'>3</div>
                <div class='panel-table-new-main-elem-main'>
                  <div class='panel-table-new-main-elem-main-img' style='background-image: url("media/users/11.jpg")'>
                  </div>
                  <div class='panel-table-new-main-elem-main-text'>
                    <div class='panel-table-new-main-elem-main-text-name'>
                      <div class='panel-table-new-main-elem-main-text-name-name'>Имя Фамилия</div>
                      <div class='panel-table-new-main-elem-main-text-name-login'>login</div>
                    </div>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-mail'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>example@domen.ru</div>
                    </a><br>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-tel'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>+7 (912) 00 00 000</div>
                    </a>
                  </div>
                </div>
                <div class='panel-table-new-main-elem-date'>19.05.2019</div>
                <div class='panel-table-new-main-elem-status'>
                  <div class='panel-table-new-main-elem-status-block' style='background-color: #5d78ff;'>Модератор</div>
                </div>
                <div class='panel-table-new-main-elem-act'>
                  <div class='panel-table-new-main-elem-act-edit'>Редактирование прав</div>
                  <div class='panel-table-new-main-elem-act-edit' onclick="open_window('#user-edit')">Редактирование пользователя</div>
                  <div class='panel-table-new-main-elem-act-edit'>Написать сообщение</div>
                </div>
              </div>
              <div class='panel-table-new-main-elem'>
                <div class='panel-table-new-main-elem-id'>4</div>
                <div class='panel-table-new-main-elem-main'>
                  <div class='panel-table-new-main-elem-main-img' style='background-image: url("media/users/14.jpg")'>
                  </div>
                  <div class='panel-table-new-main-elem-main-text'>
                    <div class='panel-table-new-main-elem-main-text-name'>
                      <div class='panel-table-new-main-elem-main-text-name-name'>Имя Фамилия</div>
                      <div class='panel-table-new-main-elem-main-text-name-login'>login</div>
                    </div>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-mail'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>example@domen.ru</div>
                    </a><br>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-tel'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>+7 (912) 00 00 000</div>
                    </a>
                  </div>
                </div>
                <div class='panel-table-new-main-elem-date'>19.05.2019</div>
                <div class='panel-table-new-main-elem-status'>
                  <div class='panel-table-new-main-elem-status-block' style='background-color: #0abb87;'>Администратор</div>
                </div>
                <div class='panel-table-new-main-elem-act'>
                  <div class='panel-table-new-main-elem-act-edit'>Редактирование прав</div>
                  <div class='panel-table-new-main-elem-act-edit' onclick="open_window('#user-edit')">Редактирование пользователя</div>
                  <div class='panel-table-new-main-elem-act-edit'>Написать сообщение</div>
                </div>
              </div>
              <div class='panel-table-new-main-elem'>
                <div class='panel-table-new-main-elem-id'>5</div>
                <div class='panel-table-new-main-elem-main'>
                  <div class='panel-table-new-main-elem-main-img' style='background-image: url("media/users/29.jpg")'>
                    <div class='panel-table-new-main-elem-main-img-online' title='Online'></div>
                  </div>
                  <div class='panel-table-new-main-elem-main-text'>
                    <div class='panel-table-new-main-elem-main-text-name'>
                      <div class='panel-table-new-main-elem-main-text-name-name'>Имя Фамилия</div>
                      <div class='panel-table-new-main-elem-main-text-name-login'>login</div>
                    </div>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-mail'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>example@domen.ru</div>
                    </a><br>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-tel'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>+7 (912) 00 00 000</div>
                    </a>
                  </div>
                </div>
                <div class='panel-table-new-main-elem-date'>19.05.2019</div>
                <div class='panel-table-new-main-elem-status'>
                  <div class='panel-table-new-main-elem-status-block' style='background-color: #6b5eae;'>Стандартный</div>
                </div>
                <div class='panel-table-new-main-elem-act'>
                  <div class='panel-table-new-main-elem-act-edit'>Редактирование прав</div>
                  <div class='panel-table-new-main-elem-act-edit' onclick="open_window('#user-edit')">Редактирование пользователя</div>
                  <div class='panel-table-new-main-elem-act-edit'>Написать сообщение</div>
                </div>
              </div>
              <div class='panel-table-new-main-elem'>
                <div class='panel-table-new-main-elem-id'>7</div>
                <div class='panel-table-new-main-elem-main'>
                  <div class='panel-table-new-main-elem-main-img' style='background-image: url("media/users/23.jpg")'>
                    <div class='panel-table-new-main-elem-main-img-online' title='Online'></div>
                  </div>
                  <div class='panel-table-new-main-elem-main-text'>
                    <div class='panel-table-new-main-elem-main-text-name'>
                      <div class='panel-table-new-main-elem-main-text-name-name'>Имя Фамилия</div>
                      <div class='panel-table-new-main-elem-main-text-name-login'>login</div>
                    </div>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-mail'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>example@domen.ru</div>
                    </a><br>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-tel'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>+7 (912) 00 00 000</div>
                    </a>
                  </div>
                </div>
                <div class='panel-table-new-main-elem-date'>19.05.2019</div>
                <div class='panel-table-new-main-elem-status'>
                  <div class='panel-table-new-main-elem-status-block' style='background-color: #6b5eae;'>Стандартный</div>
                </div>
                <div class='panel-table-new-main-elem-act'>
                  <div class='panel-table-new-main-elem-act-edit'>Редактирование прав</div>
                  <div class='panel-table-new-main-elem-act-edit' onclick="open_window('#user-edit')">Редактирование пользователя</div>
                  <div class='panel-table-new-main-elem-act-edit'>Написать сообщение</div>
                </div>
              </div>
              <div class='panel-table-new-main-elem'>
                <div class='panel-table-new-main-elem-id'>8</div>
                <div class='panel-table-new-main-elem-main'>
                  <div class='panel-table-new-main-elem-main-img' style='background-image: url("media/users/33.jpg")'>
                    <div class='panel-table-new-main-elem-main-img-online' title='Online'></div>
                  </div>
                  <div class='panel-table-new-main-elem-main-text'>
                    <div class='panel-table-new-main-elem-main-text-name'>
                      <div class='panel-table-new-main-elem-main-text-name-name'>Имя Фамилия</div>
                      <div class='panel-table-new-main-elem-main-text-name-login'>login</div>
                    </div>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-mail'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>example@domen.ru</div>
                    </a><br>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-tel'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>+7 (912) 00 00 000</div>
                    </a>
                  </div>
                </div>
                <div class='panel-table-new-main-elem-date'>19.05.2019</div>
                <div class='panel-table-new-main-elem-status'>
                  <div class='panel-table-new-main-elem-status-block' style='background-color: #6b5eae;'>Стандартный</div>
                </div>
                <div class='panel-table-new-main-elem-act'>
                  <div class='panel-table-new-main-elem-act-edit'>Редактирование прав</div>
                  <div class='panel-table-new-main-elem-act-edit' onclick="open_window('#user-edit')">Редактирование пользователя</div>
                  <div class='panel-table-new-main-elem-act-edit'>Написать сообщение</div>
                </div>
              </div>
              <div class='panel-table-new-main-elem'>
                <div class='panel-table-new-main-elem-id'>9</div>
                <div class='panel-table-new-main-elem-main'>
                  <div class='panel-table-new-main-elem-main-img' style='background-image: url("media/users/17.jpg")'>
                    <div class='panel-table-new-main-elem-main-img-online' title='Online'></div>
                  </div>
                  <div class='panel-table-new-main-elem-main-text'>
                    <div class='panel-table-new-main-elem-main-text-name'>
                      <div class='panel-table-new-main-elem-main-text-name-name'>Имя Фамилия</div>
                      <div class='panel-table-new-main-elem-main-text-name-login'>login</div>
                    </div>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-mail'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>example@domen.ru</div>
                    </a><br>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-tel'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>+7 (912) 00 00 000</div>
                    </a>
                  </div>
                </div>
                <div class='panel-table-new-main-elem-date'>19.05.2019</div>
                <div class='panel-table-new-main-elem-status'>
                  <div class='panel-table-new-main-elem-status-block' style='background-color: #6b5eae;'>Стандартный</div>
                </div>
                <div class='panel-table-new-main-elem-act'>
                  <div class='panel-table-new-main-elem-act-edit'>Редактирование прав</div>
                  <div class='panel-table-new-main-elem-act-edit' onclick="open_window('#user-edit')">Редактирование пользователя</div>
                  <div class='panel-table-new-main-elem-act-edit'>Написать сообщение</div>
                </div>
              </div>
              <div class='panel-table-new-main-elem' style='border: none;'>
                <div class='panel-table-new-main-elem-id'>10</div>
                <div class='panel-table-new-main-elem-main'>
                  <div class='panel-table-new-main-elem-main-img' style='background-image: url("media/users/24.jpg")'>
                    <div class='panel-table-new-main-elem-main-img-online' title='Online'></div>
                  </div>
                  <div class='panel-table-new-main-elem-main-text'>
                    <div class='panel-table-new-main-elem-main-text-name'>
                      <div class='panel-table-new-main-elem-main-text-name-name'>Имя Фамилия</div>
                      <div class='panel-table-new-main-elem-main-text-name-login'>login</div>
                    </div>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-mail'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>example@domen.ru</div>
                    </a><br>
                    <a href='#' class='panel-table-new-main-elem-main-text-block'>
                      <div class='panel-table-new-main-elem-main-text-block-ico icon-tel'></div>
                      <div class='panel-table-new-main-elem-main-text-block-text'>+7 (912) 00 00 000</div>
                    </a>
                  </div>
                </div>
                <div class='panel-table-new-main-elem-date'>19.05.2019</div>
                <div class='panel-table-new-main-elem-status'>
                  <div class='panel-table-new-main-elem-status-block' style='background-color: #6b5eae;'>Стандартный</div>
                </div>
                <div class='panel-table-new-main-elem-act'>
                  <div class='panel-table-new-main-elem-act-edit'>Редактирование прав</div>
                  <div class='panel-table-new-main-elem-act-edit' onclick="open_window('#user-edit')">Редактирование пользователя</div>
                  <div class='panel-table-new-main-elem-act-edit'>Написать сообщение</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class='panel' id='add_user' search-js-elem='Новый пользователь, section-block, #add_user, 🤝🏼, Добавляте новых людей, [Новый пользователь, новый, юзеры, add]' style='<?php echo($page[8][1]);?>'>
          <div class='panel-title'>Добавление пользователя</div>
          <div class='panel-conteiner'>
            <div class='panel-conteiner-main'>
              <div class='panel-filter-title' style='margin-bottom: 15px;'>Действия</div>
              <div class='panel-conteiner-main-btn' onclick="open_elem('#panel-user-add')">
                <div class='panel-conteiner-main-btn-text'>Добавить</div>
              </div>
              <div class='panel-conteiner-main-btn' onclick="open_elem_and_generate('#panel-user-add')" style='margin-bottom: 0px;'>
                <div class='panel-conteiner-main-btn-text'>Сгенерировать</div>
              </div>
            </div>
          </div>
          <div class='panel-table' style='max-width: 850px; padding-bottom: 75px;'>
            <div class='panel-table-null' style='display: one; opacity: 1;'>
              <div class='panel-table-null-block'>
                <div class='panel-table-null-block-ico'>
                  <svg
                     width="100%"
                     height="100%"
                     viewBox="0 0 285.18301 285.18301">
                    <defs
                       id="defs2">
                      <linearGradient
                         id="linearGradient840-5"
                         inkscape:collect="always">
                        <stop
                           id="stop836"
                           offset="0"
                           style="stop-color:#64e0f6;stop-opacity:1" />
                        <stop
                           id="stop838"
                           offset="1"
                           style="stop-color:#8764f6;stop-opacity:1" />
                      </linearGradient>
                      <radialGradient
                         inkscape:collect="always"
                         xlink:href="#linearGradient840-5"
                         id="radialGradient956"
                         cx="46.500984"
                         cy="283.75436"
                         fx="46.500984"
                         fy="283.75436"
                         r="84.288696"
                         gradientTransform="matrix(2.5473069,-1.9798207,2.2570379,2.9039846,-647.39363,-421.15853)"
                         gradientUnits="userSpaceOnUse" />
                      <radialGradient
                         inkscape:collect="always"
                         xlink:href="#linearGradient840-5"
                         id="radialGradient964"
                         cx="-65.01606"
                         cy="259.79672"
                         fx="-65.01606"
                         fy="259.79672"
                         r="18.520834"
                         gradientTransform="matrix(11.48623,-10.591427,12.801275,13.882777,-2445.8925,-3971.004)"
                         gradientUnits="userSpaceOnUse" />
                      <radialGradient
                         inkscape:collect="always"
                         xlink:href="#linearGradient840-5"
                         id="radialGradient972"
                         cx="162.65767"
                         cy="232.0659"
                         fx="162.65767"
                         fy="232.0659"
                         r="18.520834"
                         gradientTransform="matrix(11.181672,-11.279547,14.037637,13.915827,-4920.3373,-1067.9187)"
                         gradientUnits="userSpaceOnUse" />
                    </defs>
                    <g
                       inkscape:label="Layer 1"
                       inkscape:groupmode="layer"
                       id="layer1"
                       transform="translate(95.43898,-77.584861)">
                      <rect
                         style="opacity:1;fill:url(#radialGradient964);fill-opacity:1;stroke:none;stroke-width:14.01722527;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1;paint-order:markers fill stroke"
                         id="rect828"
                         width="37.041668"
                         height="144.19792"
                         x="-95.43898"
                         y="157.71577"
                         rx="12"
                         ry="12" />
                      <rect
                         style="opacity:1;fill:url(#radialGradient972);fill-opacity:1;stroke:none;stroke-width:14.01722527;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1;paint-order:markers fill stroke"
                         id="rect828-1"
                         width="37.041668"
                         height="144.19792"
                         x="152.70238"
                         y="157.1488"
                         rx="12"
                         ry="12" />
                      <rect
                         style="opacity:1;fill:url(#radialGradient956);fill-opacity:1;stroke:none;stroke-width:15.72299957;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1;paint-order:markers fill stroke;fill-rule:nonzero"
                         id="rect855"
                         width="168.57739"
                         height="218.84822"
                         x="-35.529762"
                         y="111.4137"
                         rx="30"
                         ry="30" />
                      <path
                         style="fill: var(--white);fill-opacity:1;stroke-width:2.87768912"
                         d="m 94.993478,257.89395 c -1.43782,-14.68955 -15.11715,-21.00319 -45.505938,-21.00319 -30.3888,0 -44.0680657,6.31364 -45.5059257,21.00319 l -0.90174,9.21252 H 49.48754 95.895238 Z"
                         id="path826"
                         inkscape:connector-curvature="0"
                         sodipodi:nodetypes="csccccc" />
                      <path
                         style="opacity:1;fill:var(--white);fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:13.40065861;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-opacity:1;paint-order:markers fill stroke"
                         id="path991"
                         sodipodi:type="arc"
                         sodipodi:cx="48.283684"
                         sodipodi:cy="204.78169"
                         sodipodi:rx="20.049009"
                         sodipodi:ry="20.049009"
                         sodipodi:start="0"
                         sodipodi:end="6.2831504"
                         d="M 68.332693,204.78169 A 20.049009,20.049009 0 0 1 48.283859,224.8307 20.049009,20.049009 0 0 1 28.234674,204.78204 20.049009,20.049009 0 0 1 48.283159,184.73268 20.049009,20.049009 0 0 1 68.332693,204.78099 l -20.049009,7e-4 z" />
                    </g>
                  </svg>
                </div>
                <div class='panel-table-null-block-text'>Для продолжения выберите одно из действий слева</div>
              </div>
            </div>
            <div class='panel-table-user' id='panel-user-add' style='display: none; opacity: 0;'>
              <div class='panel-table-user-title'>Создание аккаунта</div>
              <div class='panel-table-user-main'>
                <div class='panel-table-user-main-1'>
                  <div class='panel-table-user-main-1-img' onclick="open_window('#profile-edit-img'); updateProfileIcons();">
                    <div class="panel-profile-block-img-hover icon-add_photo"></div>
                    <img src="media/users/5.jpg">
                  </div>
                  <div class='panel-table-user-main-1-text'>
                    <div class='panel-table-user-main-1-text-title'>Регистрация нового аккаунта</div>
                    <div class='panel-table-user-main-1-text-text'>Будьте внимательны при создании аккаунта. Проверяйте информацию на достоверность и правильность. Выбор статуса помогает выставить нужные настройки доступа для пользователя: стандартный - может только пользоваться информацией на сайте, редактировать и управлять не может, редактор - удаляет, обновляет и редактирует контент сайта, модератор - собирает статистику и управляет редакторами, администратор - управляет всеми настройками. Придумайте сложный пароль, желательно, чтобы он содержал не менее 6 символов, заглавную букву и цифры. Желаем успехов!</div>
                  </div>
                </div>
                <div class='panel-table-user-main-test'>
                  <div class='panel-table-user-main-2'>
                    <div class='panel-table-user-main-2-title'>Общие данные</div>
                    <div class='input-login' style='width: auto; max-width: 320px; width: calc(100% - 45px); margin-left: 0px; margin-top: 10px; min-width: 100px;'>
                      <input required='required' type='text' id='add_user_login'>
                      <span class='placeholder-white'>Логин</span>
                    </div>
                    <div class='input-login' style='width: auto; max-width: 320px; width: calc(100% - 45px); margin-left: 0px; margin-top: 10px; min-width: 100px;'>
                      <input id='password-edit-profile-2-0001' required='required' type='password'>
                      <span class='placeholder-white'>Пароль</span>
                      <label class="eye icon-eye" for="password-edit-profile-2-0001" onclick="password_open(this)" title="Показать пароль">
                        <div class="eye-not"></div>
                      </label>
                    </div>
                    <div class='input-login' style='width: auto; max-width: 320px; width: calc(100% - 45px); margin-left: 0px; margin-top: 10px; min-width: 100px;'>
                      <input id='password-edit-profile-2-0002' required='required' type='password'>
                      <span class='placeholder-white'>Подтвердите новый пароль</span>
                      <label class="eye icon-eye" for="password-edit-profile-2-0002" onclick="password_open(this)" title="Показать пароль">
                        <div class="eye-not"></div>
                      </label>
                    </div>
                    <div class='input-login' style='width: auto; max-width: 320px; width: calc(100% - 45px); margin-left: 0px; margin-top: 10px; min-width: 100px;'>
                      <input required='required' type='text' id='add_user_name'>
                      <span class='placeholder-white'>Имя</span>
                    </div>
                    <div class='input-login' style='width: auto; max-width: 320px; width: calc(100% - 45px); margin-left: 0px; margin-top: 10px; min-width: 100px;'>
                      <input required='required' type='text' id='add_user_surname'>
                      <span class='placeholder-white'>Фамилия</span>
                    </div>
                    <div class="placeholder-white-help">
                      <select class="input-login-select" style="margin-bottom: 3px; outline: none; width: auto; max-width: 335px; width: calc(100% - 30px); margin-left: 0px; min-width: 100px;">
                        <?php echo($arrayCountry); ?>
                      </select>
                      <span class="placeholder-white-help-1" style='left: 7px;'>Страна</span>
                    </div>
                    <div class='input-login' style='width: auto; max-width: 320px; width: calc(100% - 45px); margin-left: 0px; margin-top: 10px; min-width: 100px;'>
                      <input required='required' type='text' id='add_user_city'>
                      <span class='placeholder-white'>Город</span>
                    </div>
                    <div class='input-login' style='width: auto; max-width: 320px; width: calc(100% - 45px); margin-left: 0px; margin-top: 10px; min-width: 100px;'>
                      <input required='required' type='tel' id='add_user_phone'>
                      <span class='placeholder-white'>Телефон</span>
                    </div>
                    <div class='input-login' style='width: auto; max-width: 320px; width: calc(100% - 45px); margin-left: 0px; margin-top: 10px; min-width: 100px;'>
                      <input required='required' type='mail' id='add_user_email'>
                      <span class='placeholder-white'>Почта</span>
                    </div>
                  </div>
                  <div class='panel-table-user-main-3'>
                    <div class='panel-table-user-main-2-title'>Настройки доступа</div>
                    <div class="placeholder-white-help" style='margin-top: 11.5px; margin-right: -25px;'>
                      <select onchange='change_user_rights(this);' id='user_select_main' class="input-login-select" style="margin-bottom: 3px; outline: none; width: auto; max-width: 335px; width: calc(100% - 30px); margin-left: 0px; min-width: 100px;">
                        <option>Главный администратор</option>
                        <option>Администратор</option>
                        <option>Модератор</option>
                        <option>Редактор</option>
                        <option selected>Стандартный</option>
                      </select>
                      <span class="placeholder-white-help-1" style='left: 7px;'>Статус</span>
                    </div>
                    <div class="placeholder-white-help" style='margin-top: 11.5px; margin-right: -25px;'>
                      <select class="input-login-select" id='user_select_finder' style="margin-bottom: 3px; outline: none; width: auto; max-width: 335px; width: calc(100% - 30px); margin-left: 0px; min-width: 100px;">
                        <option val='0'>Нет доступа</option>
                        <option val='1'>Только чтение</option>
                        <option val='2'>Чтение и редактирование</option>
                      </select>
                      <span class="placeholder-white-help-1" style='left: 7px;'>Проводник</span>
                    </div>
                    <div class="placeholder-white-help" style='margin-top: 11.5px; margin-right: -25px;'>
                      <select class="input-login-select" id='user_select_statistic' style="margin-bottom: 3px; outline: none; width: auto; max-width: 335px; width: calc(100% - 30px); margin-left: 0px; min-width: 100px;">
                        <option>Нет доступа</option>
                        <option>Разрешено</option>
                      </select>
                      <span class="placeholder-white-help-1" style='left: 7px;'>Статистика</span>
                    </div>
                    <div class="placeholder-white-help" style='margin-top: 11.5px; margin-right: -25px;'>
                      <select class="input-login-select" id='user_select_addNews' style="margin-bottom: 3px; outline: none; width: auto; max-width: 335px; width: calc(100% - 30px); margin-left: 0px; min-width: 100px;">
                        <option>Нет доступа</option>
                        <option>Только просмотр</option>
                        <option>Просмотр и редактирование</option>
                      </select>
                      <span class="placeholder-white-help-1" style='left: 7px;'>Публикация новостей</span>
                    </div>
                    <div class="placeholder-white-help" style='margin-top: 11.5px; margin-right: -25px;'>
                      <select class="input-login-select" id='user_select_statisticNews' style="margin-bottom: 3px; outline: none; width: auto; max-width: 335px; width: calc(100% - 30px); margin-left: 0px; min-width: 100px;">
                        <option>Нет доступа</option>
                        <option>Разрешено</option>
                      </select>
                      <span class="placeholder-white-help-1" style='left: 7px;'>Статистика новостей</span>
                    </div>
                    <div class="placeholder-white-help" style='margin-top: 11.5px; margin-right: -25px;'>
                      <select class="input-login-select" id='user_select_accessMsg' style="margin-bottom: 3px; outline: none; width: auto; max-width: 335px; width: calc(100% - 30px); margin-left: 0px; min-width: 100px;">
                        <option>Нет доступа</option>
                        <option>Разрешено</option>
                      </select>
                      <span class="placeholder-white-help-1" style='left: 7px;'>Доступ к сообщениям с клиентами</span>
                    </div>
                    <div class="placeholder-white-help" style='margin-top: 11.5px; margin-right: -25px;'>
                      <select class="input-login-select" id='user_select_contacts' style="margin-bottom: 3px; outline: none; width: auto; max-width: 335px; width: calc(100% - 30px); margin-left: 0px; min-width: 100px;">
                        <option>Нет доступа</option>
                        <option>Разрешено</option>
                      </select>
                      <span class="placeholder-white-help-1" style='left: 7px;'>Редактирование контактов</span>
                    </div>
                    <div class="placeholder-white-help" style='margin-top: 11.5px; margin-right: -25px;'>
                      <select class="input-login-select" id='user_select_reviews' style="margin-bottom: 3px; outline: none; width: auto; max-width: 335px; width: calc(100% - 30px); margin-left: 0px; min-width: 100px;">
                        <option>Нет доступа</option>
                        <option>Разрешено</option>
                      </select>
                      <span class="placeholder-white-help-1" style='left: 7px;'>Просмотр отзывов</span>
                    </div>
                    <div class="placeholder-white-help" style='margin-top: 11.5px; margin-right: -25px;'>
                      <select class="input-login-select" id='user_select_timetable' style="margin-bottom: 3px; outline: none; width: auto; max-width: 335px; width: calc(100% - 30px); margin-left: 0px; min-width: 100px;">
                        <option>Нет доступа</option>
                        <option>Разрешено</option>
                      </select>
                      <span class="placeholder-white-help-1" style='left: 7px;'>Редактирование расписания</span>
                    </div>
                  </div>
                </div>

              </div>
              <div class='panel-table-user-btn' style='opacity: 0.5; cursor: default; transition: all 1e+10s ease 0s;'>Сохранить</div>
            </div>
          </div>
        </div>
        <?php endif;?>
        <div class='panel' id='profile' search-js-elem='Ваш профиль, section-block, #profile, 💪🏼, Ваши данные, [мой профиль, о мне]' style='<?php echo($page[9][1]);?>'>
          <div class='panel-title'>Профиль</div>
          <div class='panel-conteiner-all'>
            <div class='panel-profile-block'>
              <div class='panel-profile-block-img' onclick="open_window('#profile-edit-img'); updateProfileIcons();">
                <div class='panel-profile-block-img-hover icon-add_photo'></div>
                <img src='<?php echo($userData['icon']);?>' alt='<?php echo($userData['login']);?>'>
              </div>
              <div class='panel-profile-block-text'>
                <div class='panel-profile-block-text-name'><?php echo($userData['name1']." ".$userData['name2']);?></div>
                <?php if ($userData['access'] == 'redactor'):?>
                  <div class='panel-profile-block-text-status' style='background-color: #fd397a;'>Редактор</div>
                <?php elseif ($userData['access'] == 'moderator'):?>
                  <div class='panel-profile-block-text-status' style='background-color: #5d78ff;'>Модератор</div>
                <?php elseif ($userData['access'] == 'administrator'):?>
                  <div class='panel-profile-block-text-status' style='background-color: #0abb87;'>Администратор</div>
                <?php elseif ($userData['access'] == 'superuser'):?>
                  <div class='panel-profile-block-text-status' style='background-color: #ffb822;'>Главный администратор</div>
                <?php else:?>
                  <div class='panel-profile-block-text-status' style='background-color: #6b5eae;'>Стандартный</div>
                <?php endif;?>
                <br>
                <div class='panel-profile-block-text-login'>
                  <div class='panel-profile-block-text-login-1'>Логин: </div>
                  <div class='panel-profile-block-text-login-2'><?php echo($userData['login']);?></div>
                </div>
                <br>
                <div class='panel-profile-block-text-btn' onclick="open_window('#profile-edit');">Редактировать профиль</div>
              </div>
            </div>
            <div class='panel-profile-block-2'>
              <div class='panel-profile-block'>
                <div class='panel-profile-block-conteiner' style='margin-top: -15px;'>
                  <div class='panel-profile-block-2-conteiner-title-h1'>История посещений</div>
                  <div class='panel-profile-block-conteiner-history' id="panel-profile-block-conteiner-history">

                    <div class='panel-profile-block-conteiner-history-elem'>
                      <div class='panel-profile-block-conteiner-history-elem-line' style='background-color: #0abb87;'></div>
                      <div class='panel-profile-block-conteiner-history-elem-text'>
                        <div class='panel-profile-block-conteiner-history-elem-text-title'>Выполнен вход</div>
                        <div class='panel-profile-block-conteiner-history-elem-text-text'><b>Дата :</b> 25.01.2020 15:47<br><b>IP :</b> 188.17.153.138<br><b>Город :</b> Пермь</div>
                      </div>
                    </div>

                    <div class='panel-profile-block-conteiner-history-elem'>
                      <div class='panel-profile-block-conteiner-history-elem-line' style='background-color: #fd397a;'></div>
                      <div class='panel-profile-block-conteiner-history-elem-text'>
                        <div class='panel-profile-block-conteiner-history-elem-text-title'>Подозрительная активность</div>
                        <div class='panel-profile-block-conteiner-history-elem-text-text'>Попытка входа в аккаунт! <br><b>Дата :</b> 18.01.2020 20:19<br><b>IP :</b> 52.117.53.64<br><b>Город :</b> Пермь</div>
                      </div>
                    </div>

                    <div class='panel-profile-block-conteiner-history-elem'>
                      <div class='panel-profile-block-conteiner-history-elem-line' style='background-color: #0abb87;'></div>
                      <div class='panel-profile-block-conteiner-history-elem-text'>
                        <div class='panel-profile-block-conteiner-history-elem-text-title'>Выполнен вход</div>
                        <div class='panel-profile-block-conteiner-history-elem-text-text'><b>Дата :</b> 18.01.2020 21:52<br><b>IP :</b> 188.17.153.138<br><b>Город :</b> Пермь</div>
                      </div>
                    </div>

                    <div class='panel-profile-block-conteiner-history-elem'>
                      <div class='panel-profile-block-conteiner-history-elem-line' style='background-color: #0abb87;'></div>
                      <div class='panel-profile-block-conteiner-history-elem-text'>
                        <div class='panel-profile-block-conteiner-history-elem-text-title'>Выполнен вход</div>
                        <div class='panel-profile-block-conteiner-history-elem-text-text'><b>Дата :</b> 19.01.2020 10:35<br><b>IP :</b> 188.17.153.138<br><b>Город :</b> Пермь</div>
                      </div>
                    </div>

                    <div class='panel-profile-block-conteiner-history-elem'>
                      <div class='panel-profile-block-conteiner-history-elem-line' style='background-color: #ffb822;'></div>
                      <div class='panel-profile-block-conteiner-history-elem-text'>
                        <div class='panel-profile-block-conteiner-history-elem-text-title'>Регистрация</div>
                        <div class='panel-profile-block-conteiner-history-elem-text-text'><b>Дата :</b> 25.12.2019 16:28<br><b>IP :</b> 188.17.153.138<br><b>Город :</b> Пермь</div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>
            <div class='panel-profile-block-3'>
              <div class='panel-profile-block'>
                <div class='panel-profile-block-conteiner' style='margin-top: -15px;'>
                  <div class='panel-profile-block-conteiner-title-h1'>Общая информация</div>
                </div>
                <div class='panel-profile-block-conteiner'>
                  <div class='panel-profile-block-conteiner-title'>Дата рождения:</div>
                  <div class='panel-profile-block-conteiner-value'><?php echo(form_birthday($userData['birthday'])); ?></div>
                </div>
                <div class='panel-profile-block-conteiner'>
                  <div class='panel-profile-block-conteiner-title'>Страна:</div>
                  <div class='panel-profile-block-conteiner-value'><?=$userData['country']?></div>
                </div>
                <div class='panel-profile-block-conteiner'>
                  <div class='panel-profile-block-conteiner-title'>Город:</div>
                  <div class='panel-profile-block-conteiner-value'><?=$userData['city']?></div>
                </div>
                <div class='panel-profile-block-conteiner'>
                  <div class='panel-profile-block-conteiner-title'>Язык:</div>
                  <div class='panel-profile-block-conteiner-value'>
                    <?php
                      if(@$_COOKIE['language'] == 'ua'){
                        echo('Український');
                      } else if(@$_COOKIE['language'] == 'en'){
                        echo('English');
                      } else{
                        echo('Русский');
                      }
                    ?>
                  </div>
                </div>
                <div class='panel-profile-block-conteiner'>
                  <div class='panel-profile-block-conteiner-title-h1'>Контакты</div>
                </div>
                <div class='panel-profile-block-conteiner'>
                  <div class='panel-profile-block-conteiner-title'>Телефон:</div>
                  <a class='panel-profile-block-conteiner-value-a' href='tel:<?php echo($userData['phone']);?>'><?php echo($userData['phone']);?></a>
                </div>
                <div class='panel-profile-block-conteiner'>
                  <div class='panel-profile-block-conteiner-title'>Почта:</div>
                  <a class='panel-profile-block-conteiner-value-a' href='mailto:<?php echo($userData['email']);?>'><?php echo($userData['email']);?></a>
                </div>
                <div class='panel-profile-block-conteiner-info'>
                  <div class='panel-profile-block-conteiner-info-elem' style='border-right: 1px solid var(--border-color);'>
                    <div class='panel-profile-block-conteiner-info-elem-1' id="panel-profile-block-conteiner-info-life-time"></div>
                    <div class='panel-profile-block-conteiner-info-elem-2' id="panel-profile-block-conteiner-info-life-title"></div>
                  </div>
                  <div class='panel-profile-block-conteiner-info-elem' style='border-right: 1px solid var(--border-color);'>
                    <div class='panel-profile-block-conteiner-info-elem-1' id='panel-profile-block-conteiner-info-register-date'></div>
                    <div class='panel-profile-block-conteiner-info-elem-2'>Дата регистрации</div>
                  </div>
                  <div class='panel-profile-block-conteiner-info-elem'>
                    <div class='panel-profile-block-conteiner-info-elem-1'>0</div>
                    <div class='panel-profile-block-conteiner-info-elem-2'>Сообщений</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php if ($finderPanel): ?>
        <div class='panel' id='file_manager' search-js-elem='Проводник, section-block, #file_manager, 📁, Файловый менеджер, [Файловый менеджер, проводник, finder, файлы, удаление, корзина]' style='<?php echo($page[10][1]);?>' >
          <div class='file_manager-contextmenu' style='opacity: 0; display: none;'>

            <!-- <div onclick='' class='file_manager-contextmenu-elem'>
              <div class='file_manager-contextmenu-elem-img icon-file2'></div>
              <div class='file_manager-contextmenu-elem-text'>Открыть файл</div>
            </div>
            <div onclick='' class='file_manager-contextmenu-elem'>
              <div class='file_manager-contextmenu-elem-img icon-folder'></div>
              <div class='file_manager-contextmenu-elem-text'>Открыть папку</div>
            </div>
            <div onclick='' class='file_manager-contextmenu-elem'>
              <div class='file_manager-contextmenu-elem-img icon-add_folder'></div>
              <div class='file_manager-contextmenu-elem-text'>Новая папка</div>
            </div>
            <div onclick='' class='file_manager-contextmenu-elem'>
              <div class='file_manager-contextmenu-elem-img icon-new_file2'></div>
              <div class='file_manager-contextmenu-elem-text'>Новый файл</div>
            </div>
            <div class='file_manager-contextmenu-elem-line'></div>
            <div onclick='' class='file_manager-contextmenu-elem'>
              <div class='file_manager-contextmenu-elem-img icon-zip'></div>
              <div class='file_manager-contextmenu-elem-text'>Создать архив</div>
            </div>
            <div onclick='' class='file_manager-contextmenu-elem'>
              <div class='file_manager-contextmenu-elem-img icon-open_zip'></div>
              <div class='file_manager-contextmenu-elem-text'>Распаковать архив</div>
            </div>
            <div onclick='' class='file_manager-contextmenu-elem'>
              <div class='file_manager-contextmenu-elem-img icon-copy'></div>
              <div class='file_manager-contextmenu-elem-text'>Копировать</div>
            </div>
            <div onclick='' class='file_manager-contextmenu-elem'>
              <div class='file_manager-contextmenu-elem-img icon-past'></div>
              <div class='file_manager-contextmenu-elem-text'>Вставить</div>
            </div>
            <div onclick='' class='file_manager-contextmenu-elem'>
              <div class='file_manager-contextmenu-elem-img icon-cut_out'></div>
              <div class='file_manager-contextmenu-elem-text'>Вырезать</div>
            </div>
            <div onclick='' class='file_manager-contextmenu-elem'>
              <div class='file_manager-contextmenu-elem-img icon-rename'></div>
              <div class='file_manager-contextmenu-elem-text'>Переименовать</div>
            </div>
            <div onclick='' class='file_manager-contextmenu-elem'>
              <div class='file_manager-contextmenu-elem-img icon-lock'></div>
              <div class='file_manager-contextmenu-elem-text'>Добавить пароль</div>
            </div>
            <div onclick='' class='file_manager-contextmenu-elem'>
              <div class='file_manager-contextmenu-elem-img icon-question'></div>
              <div class='file_manager-contextmenu-elem-text'>Показать свойства</div>
            </div>
            <div onclick='' class='file_manager-contextmenu-elem'>
              <div class='file_manager-contextmenu-elem-img icon-download'></div>
              <div class='file_manager-contextmenu-elem-text'>Скачать</div>
            </div>
            <div class='file_manager-contextmenu-elem-line'></div>
            <div onclick='' class='file_manager-contextmenu-elem'>
              <div class='file_manager-contextmenu-elem-img icon-basket'></div>
              <div class='file_manager-contextmenu-elem-text'>Удалить</div>
            </div> -->

          </div>
          <div class='panel-title'>Проводник</div>
          <div class='panel-conteiner-all'>
            <div class='panel-conteiner-all-block-2'>
              <div class='panel-conteiner-all-block-filter'>
                <div class='file_manager-action'>
                  <div class='file_manager-action-btn' onclick="finderCopycutSelected('copy', true);">
                    <div class='file_manager-action-btn-ico icon-copy'></div>
                    <div class='file_manager-action-btn-text'>Копировать</div>
                  </div>
                  <div class='file_manager-action-btn' onclick="finderPasteTo();">
                    <div class='file_manager-action-btn-ico icon-past'></div>
                    <div class='file_manager-action-btn-text'>Вставить</div>
                  </div>
                  <div class='file_manager-action-btn' onclick="finderCopycutSelected('cut', true);">
                    <div class='file_manager-action-btn-ico icon-cut_out'></div>
                    <div class='file_manager-action-btn-text'>Вырезать</div>
                  </div>
                  <div class='file_manager-action-line'></div>
                  <div class='file_manager-action-btn' onclick="finderRemoveSelected();">
                    <div class='file_manager-action-btn-ico icon-basket'></div>
                    <div class='file_manager-action-btn-text'>Удалить</div>
                  </div>
                  <!--<div class='file_manager-action-btn'>
                    <div class='file_manager-action-btn-ico icon-rename'></div>
                    <div class='file_manager-action-btn-text'>Переименовать</div>
                  </div>-->
                  <div class='file_manager-action-line'></div>
                  <div class='file_manager-action-btn' onclick="finderCreateNewCatalog();">
                    <div class='file_manager-action-btn-ico icon-add_folder'></div>
                    <div class='file_manager-action-btn-text'>Новая папка</div>
                  </div>
                  <div class='file_manager-action-btn' onclick="finderElementsSelectAll();">
                    <div class='file_manager-action-btn-ico icon-select_all'></div>
                    <div class='file_manager-action-btn-text'>Выбрать все</div>
                  </div>
                  <div class='file_manager-action-line'></div>
                  <div class='file_manager-action-btn' id='file_manager-action-btn' onclick="open_block('#finderSettings','center')">
                    <div class='panel-user-ab-btn-2-point'></div>
                    <div class='panel-user-ab-btn-2-point'></div>
                    <div class='panel-user-ab-btn-2-point'></div>
                    <div class='panel-finder-ab-btn-conteiner' id='finderSettings' style='cursor: default; opacity: 0; display: none;'>
                      <div class='panel-user-ab-btn-conteiner-title'>Настройки</div>

                      <div class='panel-finder-ab-btn-conteiner-block' style='margin-bottom: 15px;'>
                        <div class='panel-user-ab-btn-conteiner-block-title3'>
                          <span>Предзагрузка изображений</span>
                          <div class="description1"></div>
                          <div class="window-block-settings-block-description">
                            <div class="window-block-settings-block-description-title">Подсказка</div>
                            <div class="window-block-settings-block-description-text">Данная функция выводит содержимое изображений вместо иконок <br><br> <b>Если у вас медленный интернет не рекомендуем включать данную настройку!</b></div>
                          </div>
                        </div>
                        <div class='panel-user-ab-btn-conteiner-block-ch'>
                          <input id="w7MDL-uSjk-XnaJ" class="finder-block-settings-block-input" <?php if(isset($_COOKIE["finderImagePreload"])){if($_COOKIE["finderImagePreload"] == 'true'){echo('checked');}} ?> style="display: none; margin-top: 0px; margin-left: 0px;" type="checkbox">
                          <label for="w7MDL-uSjk-XnaJ" onclick="finderImagePreload($('#w7MDL-uSjk-XnaJ').prop('checked')); finderListing();" style="right: 0; top: 0;">
                            <span></span>
                          </label>
                        </div>
                      </div>
                      <div class='panel-finder-ab-btn-conteiner-block' style='margin-bottom: 15px;'>
                        <div class='panel-user-ab-btn-conteiner-block-title3'>
                          <span>Оповещение о малом объеме памяти на хостинге</span>
                          <!-- <div class="description1"></div>
                          <div class="window-block-settings-block-description">
                            <div class="window-block-settings-block-description-title">Подсказка</div>
                            <div class="window-block-settings-block-description-text">Данная функция выводит содержимое изображений вместо иконок <br><br> <b>Если у вас медленный интернет не рекомендуем включать данную настройку!</b></div>
                          </div> -->
                        </div>
                        <div class='panel-user-ab-btn-conteiner-block-ch'>
                          <input id="chSpaceLittleWindow1" class="finder-block-settings-block-input" <?php if(isset($_COOKIE["SpaceLittleWindow"])){if($_COOKIE["SpaceLittleWindow"] == 'true'){echo('checked');}} ?> style="display: none; margin-top: 0px; margin-left: 0px;" type="checkbox">
                          <label for="chSpaceLittleWindow1" style="right: 0; top: 0;">
                            <span></span>
                          </label>
                        </div>
                      </div>
                      <div class='panel-finder-ab-btn-conteiner-block'>
                        <div class='panel-user-ab-btn-conteiner-block-title'>Интерфейс</div>
                        <div class='panel-user-ab-btn-conteiner-block-title-2'>M</div>
                        <input class='panel-user-ab-btn-conteiner-block-range' id='panel-user-ab-btn-conteiner-block-range1' onfocus='change_fontsSize_finder(this, 1, true);' onmouseup='change_fontsSize_finder(this, 1, true);' type='range' min='0' max='6' step='1'>
                      </div>
                      <div class='panel-finder-ab-btn-conteiner-block-2'>
                        <div class='panel-user-ab-btn-conteiner-block-title'>Интерфейс</div><br>
                        <input style='display: none;' checked type='radio' id='typeStyleFinder-line' name='typeStyleFinder'>
                        <input style='display: none;' type='radio' id='typeStyleFinder-block' name='typeStyleFinder'>
                        <label for='typeStyleFinder-line' style='margin-right: 6px;' id='typeStyleFinder-line1' class='typeStyleFinderBlock' onclick='change_style_finder("line");'>
                          <div class='typeStyleFinderBlock-first'>
                            <div class='typeStyleFinderBlock-first-title'>
                              <div class='typeStyleFinderBlock-first-title-ico icon-folder'></div>
                              <div class='typeStyleFinderBlock-first-title-text'>Folder</div>
                            </div>
                            <div class='typeStyleFinderBlock-first-description'>
                              <div class='typeStyleFinderBlock-first-description-line'></div>
                              <div class='typeStyleFinderBlock-first-description-line2'></div>
                            </div>
                          </div>
                          <div class='typeStyleFinderBlock-first-2'>
                            <div class='typeStyleFinderBlock-first-2-description-ico'></div>
                            <div class='typeStyleFinderBlock-first-2-description-line'></div>
                            <div class='typeStyleFinderBlock-first-2-description-line2'></div>
                            <div class='typeStyleFinderBlock-first-2-description-line3'></div>
                          </div>
                          <div class='typeStyleFinderBlock-first-2'>
                            <div class='typeStyleFinderBlock-first-2-description-ico'></div>
                            <div class='typeStyleFinderBlock-first-2-description-line'></div>
                            <div class='typeStyleFinderBlock-first-2-description-line2'></div>
                            <div class='typeStyleFinderBlock-first-2-description-line3'></div>
                          </div>
                          <div class='typeStyleFinderBlock-first-2'>
                            <div class='typeStyleFinderBlock-first-2-description-ico'></div>
                            <div class='typeStyleFinderBlock-first-2-description-line'></div>
                            <div class='typeStyleFinderBlock-first-2-description-line2'></div>
                            <div class='typeStyleFinderBlock-first-2-description-line3'></div>
                          </div>
                          <div class='typeStyleFinderBlock-first-2'>
                            <div class='typeStyleFinderBlock-first-2-description-ico'></div>
                            <div class='typeStyleFinderBlock-first-2-description-line'></div>
                            <div class='typeStyleFinderBlock-first-2-description-line2'></div>
                            <div class='typeStyleFinderBlock-first-2-description-line3'></div>
                          </div>
                        </label>
                        <label for='typeStyleFinder-block' id='typeStyleFinder-block1' class='typeStyleFinderBlock' onclick='change_style_finder("block");'>
                          <div class='typeStyleFinderBlock-first'>
                            <div class='typeStyleFinderBlock-first-title'>
                              <div class='typeStyleFinderBlock-first-title-ico icon-folder'></div>
                              <div class='typeStyleFinderBlock-first-title-text'>Folder</div>
                            </div>
                            <div class='typeStyleFinderBlock-first-description'>
                              <div class='typeStyleFinderBlock-first-description-line'></div>
                              <div class='typeStyleFinderBlock-first-description-line2'></div>
                            </div>
                          </div>
                          <div class='typeStyleFinderBlock-first3-0'>
                            <div class='typeStyleFinderBlock-first3'>
                              <div class='typeStyleFinderBlock-first3-ico'></div>
                              <div class='typeStyleFinderBlock-first3-text'></div>
                              <div class='typeStyleFinderBlock-first3-text-2'></div>
                            </div>
                            <div class='typeStyleFinderBlock-first3'>
                              <div class='typeStyleFinderBlock-first3-ico'></div>
                              <div class='typeStyleFinderBlock-first3-text'></div>
                              <div class='typeStyleFinderBlock-first3-text-2'></div>
                            </div>
                            <div class='typeStyleFinderBlock-first3'>
                              <div class='typeStyleFinderBlock-first3-ico'></div>
                              <div class='typeStyleFinderBlock-first3-text'></div>
                              <div class='typeStyleFinderBlock-first3-text-2'></div>
                            </div>
                            <div class='typeStyleFinderBlock-first3'>
                              <div class='typeStyleFinderBlock-first3-ico'></div>
                              <div class='typeStyleFinderBlock-first3-text'></div>
                              <div class='typeStyleFinderBlock-first3-text-2'></div>
                            </div>
                            <div class='typeStyleFinderBlock-first3'>
                              <div class='typeStyleFinderBlock-first3-ico'></div>
                              <div class='typeStyleFinderBlock-first3-text'></div>
                              <div class='typeStyleFinderBlock-first3-text-2'></div>
                            </div>
                            <div class='typeStyleFinderBlock-first3'>
                              <div class='typeStyleFinderBlock-first3-ico'></div>
                              <div class='typeStyleFinderBlock-first3-text'></div>
                              <div class='typeStyleFinderBlock-first3-text-2'></div>
                            </div>
                            <div class='typeStyleFinderBlock-first3'>
                              <div class='typeStyleFinderBlock-first3-ico'></div>
                              <div class='typeStyleFinderBlock-first3-text'></div>
                              <div class='typeStyleFinderBlock-first3-text-2'></div>
                            </div>
                            <div class='typeStyleFinderBlock-first3'>
                              <div class='typeStyleFinderBlock-first3-ico'></div>
                              <div class='typeStyleFinderBlock-first3-text'></div>
                              <div class='typeStyleFinderBlock-first3-text-2'></div>
                            </div>
                            <div class='typeStyleFinderBlock-first3'>
                              <div class='typeStyleFinderBlock-first3-ico'></div>
                              <div class='typeStyleFinderBlock-first3-text'></div>
                              <div class='typeStyleFinderBlock-first3-text-2'></div>
                            </div>
                            <div class='typeStyleFinderBlock-first3'>
                              <div class='typeStyleFinderBlock-first3-ico'></div>
                              <div class='typeStyleFinderBlock-first3-text'></div>
                              <div class='typeStyleFinderBlock-first3-text-2'></div>
                            </div>
                            <div class='typeStyleFinderBlock-first3'>
                              <div class='typeStyleFinderBlock-first3-ico'></div>
                              <div class='typeStyleFinderBlock-first3-text'></div>
                              <div class='typeStyleFinderBlock-first3-text-2'></div>
                            </div>
                            <div class='typeStyleFinderBlock-first3'>
                              <div class='typeStyleFinderBlock-first3-ico'></div>
                              <div class='typeStyleFinderBlock-first3-text'></div>
                              <div class='typeStyleFinderBlock-first3-text-2'></div>
                            </div>
                          </div>

                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class='file_manager-btn' style='margin-top: 5px;'>
                  <div class='file_manager-btn-action'>
                    <div class='file_manager-action-btn' id="finder-history-btn-left" title='Назад' onclick="finderHistoryPrevCatalog();">
                      <div class='file_manager-btn-action-ico icon-left'></div>
                    </div>
                    <div class='file_manager-action-btn-none' id="finder-history-btn-right" title='Вперед' onclick="finderHistoryNextCatalog();">
                      <div class='file_manager-btn-action-ico icon-right'></div>
                    </div>
                  </div>
                  <div class='file_manager-btn-action-2'>
                    <div class='file_manager-btn-action-way'>
                      <div class='file_manager-btn-action-way-main'>

                        <div class='file_manager-btn-action-way-main-div'>

                          <div class='file_manager-btn-action-way-main-GlobalICO' title='Сервер'></div>

                          <div class='file_manager-btn-action-way-main-arrow icon-right'></div>

                          <div class='file_manager-btn-action-way-main-elem' title='Файлы'>Файлы</div>

                          <div class='file_manager-btn-action-way-main-arrow icon-right'></div>

                          <div class='file_manager-btn-action-way-main-elem' title='Название папки'>Название папки</div>

                        </div>

                      </div>
                      <div class='file_manager-btn-action-way-btn' onclick="finderListing(); $('.file_manager-btn-action-way-btn-elem').css('transform','rotate(360deg)');
                      setTimeout(function(){
                        $('.file_manager-btn-action-way-btn-elem').css({
                          'transform':'rotate(0deg)',
                          'transition':'0s all ease-out'
                        });
                        setTimeout(function(){
                          $('.file_manager-btn-action-way-btn-elem').css({
                            'transition':'0.25s all ease-out'
                          });
                        }, 50)
                      }, 250);">
                        <div class='file_manager-btn-action-way-btn-elem icon-reload' title='Обновить'></div>
                      </div>
                    </div>
                  </div>
                  <div class='file_manager-btn-action-3'>
                    <label class='file_manager-btn-action-way' for='file_manager-btn-action-way-search'>
                      <input id='file_manager-btn-action-way-search' type='text' placeholder='Поиск'>
                      <div class='file_manager-btn-action-way-search-ico icon-search'></div>
                    </label>
                  </div>
                </div>
              </div>
              <div class='panel-conteiner-all-block-main-2'>
                <div class='panel-conteiner-all-block-main-2-nav'>
                  <div class='preloader-finder'>
                    <div class='preloader-finder-ico'>
                      <div class="circles" id='preloader-finder-ico'>
                        <div class="large-circle"></div>
                        <div class="mid-circle"></div>
                        <div class="small-circle"></div>
                      </div>
                    </div>
                    <div class='preloader-finder-text'>Загрузка...</div>
                  </div>
                  <div class='panel-conteiner-all-block-main-2-nav-title'>
                    <span style='display: inline-block; vertical-align: middle;'>Ярлыки</span>
                    <div class="description1"></div>
                    <div class="window-block-settings-block-description">
                      <div class="window-block-settings-block-description-title">Подсказка</div>
                      <div class="window-block-settings-block-description-text">
                        Быстрые переходы между самыми важными папками.
                      </div>
                    </div>
                  </div>
                  <!-- <div class='panel-conteiner-all-block-main-2-nav-block'>
                    <div class='panel-conteiner-all-block-main-2-nav-block-ico icon-img'></div>
                    <div class='panel-conteiner-all-block-main-2-nav-block-text'>Фотографии</div>
                  </div> -->
                  <div class='panel-conteiner-all-block-main-2-nav-block' onclick="finderSetCatalog('<?=$users_files_path?>', 'users'); finderMenuClose();">
                    <div class='panel-conteiner-all-block-main-2-nav-block-ico icon-users1' style='font-size: calc(19px * var(--fontsSizeFinder)); font-weight: 500;'></div>
                    <div class='panel-conteiner-all-block-main-2-nav-block-text'>Пользователи</div>
                  </div>
                  <div class='panel-conteiner-all-block-main-2-nav-block' onclick="finderSetCatalog('<?=$books_files_path?>', 'books'); finderMenuClose();">
                    <div class='panel-conteiner-all-block-main-2-nav-block-ico icon-books' style='font-size: calc(22px * var(--fontsSizeFinder))'></div>
                    <div class='panel-conteiner-all-block-main-2-nav-block-text'>Учебники</div>
                  </div>
                  <div class='panel-conteiner-all-block-main-2-nav-block' onclick="finderSetCatalog('<?=$docs_files_path?>', 'docs'); finderMenuClose();">
                    <div class='panel-conteiner-all-block-main-2-nav-block-ico icon-document'></div>
                    <div class='panel-conteiner-all-block-main-2-nav-block-text'>Документы</div>
                  </div>
                  <div class='panel-conteiner-all-block-main-2-nav-block' onclick="finderSetCatalog('/', 'all'); finderMenuClose();">
                    <div class='panel-conteiner-all-block-main-2-nav-block-ico icon-all_files'></div>
                    <div class='panel-conteiner-all-block-main-2-nav-block-text'>Все файлы</div>
                  </div>
                  <div class='panel-conteiner-all-block-main-2-nav-line'></div>
                  <div class='panel-conteiner-all-block-main-2-nav-block' onclick="finderListingTrash(); finderMenuClose();">
                    <div class='panel-conteiner-all-block-main-2-nav-block-ico icon-basket' style='font-size: calc(14px * var(--fontsSizeFinder)); line-height: 17px; font-weight: 700;'></div>
                    <div class='panel-conteiner-all-block-main-2-nav-block-text'>Корзина</div>
                  </div>
                  <?php if($usersPanel):?>
                  <div>
                    <div class='panel-conteiner-all-block-main-2-nav-title'>
                      <span style='display: inline-block; vertical-align: middle;'>Люди</span>
                      <div class="description1"></div>
                      <div class="window-block-settings-block-description">
                        <div class="window-block-settings-block-description-title">Подсказка</div>
                        <div class="window-block-settings-block-description-text">
                          Все пользователи у кого есть доступ к проводнику.
                        </div>
                      </div>
                    </div>
                    <div class='panel-conteiner-all-block-main-2-nav-block'>
                      <img src='media/tmp/photo.jpg' class='panel-conteiner-all-block-main-2-nav-block-img'/>
                      <div class='panel-conteiner-all-block-main-2-nav-block-name'>
                        <div class='panel-conteiner-all-block-main-2-nav-block-name'>Имя Фамилия</div><br>
                        <div class='panel-conteiner-all-block-main-2-nav-block-text'>login</div>
                      </div>
                    </div>
                    <div class='panel-conteiner-all-block-main-2-nav-block'>
                      <img src='media/tmp/photo.jpg' class='panel-conteiner-all-block-main-2-nav-block-img'/>
                      <div class='panel-conteiner-all-block-main-2-nav-block-name'>
                        <div class='panel-conteiner-all-block-main-2-nav-block-name'>Имя Фамилия</div><br>
                        <div class='panel-conteiner-all-block-main-2-nav-block-text'>login</div>
                      </div>
                    </div>
                    <div class='panel-conteiner-all-block-main-2-nav-block'>
                      <img src='media/tmp/photo.jpg' class='panel-conteiner-all-block-main-2-nav-block-img'/>
                      <div class='panel-conteiner-all-block-main-2-nav-block-name'>
                        <div class='panel-conteiner-all-block-main-2-nav-block-name'>Имя Фамилия</div><br>
                        <div class='panel-conteiner-all-block-main-2-nav-block-text'>login</div>
                      </div>
                    </div>
                  </div>
                  <?php else:?>
                    <br>
                  <?php endif;?>
                  <div class='panel-conteiner-all-block-main-2-nav-title'>
                    Пространство
                  </div>
                  <div class='panel-conteiner-all-block-main-2-nav-block-0'>
                    <div class='progress'>
                      <div class='progress-value' value='24%' style='width: 24%;'></div>
                    </div>
                    <div class='progress-data'>
                      <div class='progress-data-used'>2.41 Гб</div>
                      <div class='progress-data-total'>10 Гб</div>
                    </div>
                  </div>

                  <?php if(!$usersPanel):?>
                  <div class='panel-conteiner-all-block-main-2-nav-block-sale'>
                    <div class='panel-conteiner-all-block-main-2-nav-block-sale-ico'></div>
                    <div class='panel-conteiner-all-block-main-2-nav-block-sale-text'>
                      <?php
                        $rndFinderSale = mt_rand(0,2);
                        if($rndFinderSale == 0){
                          echo('Хочешь работать не один?');
                        }
                        if($rndFinderSale == 1){
                          echo('Устал работать один?');
                        }
                        if($rndFinderSale == 2){
                          echo('Хочешь пользователя в помощь?');
                        }
                      ?>

                    </div>
                    <div class='panel-conteiner-all-block-main-2-nav-block-sale-btn' onclick="open_panel('#all_user');">Добавить пользователей</div>
                  </div>
                  <?php endif;?>
                </div>
                <div class='panel-conteiner-all-block-main-2-main'>
                  <div class='shadow-finder' onclick="finderMenuClose()"></div>
                  <div class='finder-dragAndDrop'>
                    <div class='finder-dragAndDrop-border'>
                      <div class='finder-dragAndDrop-border-text'>
                        <div class='finder-dragAndDrop-border-text-ico icon-download'></div>
                        <div class='finder-dragAndDrop-border-text-text'><?=$userData['name1']?>, перенесите файлы сюда!</div>
                      </div>
                    </div>
                  </div>
                  <div class='panel-conteiner-all-block-main-2-main-smallTitle'>
                    <div class='panel-conteiner-all-block-main-2-main-smallTitle-ico'></div>
                    <div class='panel-conteiner-all-block-main-2-main-smallTitle-text'>Название папки</div>
                  </div>
                  <div class='panel-conteiner-all-block-main-2-main-btnTop icon-top' title='Наверх' onclick="topScrollFinder()"></div>
                  <div class='preloader-2-finder'></div>
                  <input type="file" id="finder-upload-file-input" style="display: none;" />
                  <input type="file" id="finder-upload-folder-input" style="display: none;" webkitdirectory multiple />
                  <div class='panel-conteiner-all-block-main-2-main-title' oncontextmenu="">
                  <!--<div class='panel-conteiner-all-block-main-2-main-title' oncontextmenu="add_contextmenu([['new_file','finderCreateNewFile()'],['new_folder','finderCreateNewCatalog()'],['upload','tvoya_function()'],['upload_folder','tvoya_function()'],['line'],['new_zip','tvoya_function()'],['rename','tvoya_function()'],['lock','tvoya_function()'],['info','tvoya_function()'],['download','tvoya_function()'],['line'],['del','tvoya_function()']]);">-->
                    <div class='panel-conteiner-all-block-main-2-main-title-ico' id="finder-title-icon"></div>
                    <div class='panel-conteiner-all-block-main-2-main-title-text'>
                      <div class='panel-conteiner-all-block-main-2-main-title-text-name' id="finder-title-id">Название папки</div>
                      <div class='panel-conteiner-all-block-main-2-main-title-text-description'>
                        <div class='panel-conteiner-all-block-main-2-main-title-text-description-elem' id="finder-title-count-id">9 элементов</div>
                        <div class='panel-conteiner-all-block-main-2-main-title-text-description-elem' id="finder-title-volume-id">Объем папки 1.52 Гб</div>
                      </div>
                    </div>
                  </div>

                  <div class='panel-conteiner-all-block-main-2-main-filter'>
                    <div class='panel-conteiner-all-block-main-2-main-filter-elem' id="sorting-by-name-id" onclick="sort_name(this)" style='margin-left: 110px; width: 31%;'>
                      <div class='panel-conteiner-all-block-main-2-main-filter-elem-text'>Имя</div>
                      <div class='panel-conteiner-all-block-main-2-main-filter-elem-ico icon-left'></div>
                    </div>
                    <div class='panel-conteiner-all-block-main-2-main-filter-elem' onclick="sort_date(this)" style='text-align: right; width: calc(calc(100% / 7) - 10px);'>
                      <div class='panel-conteiner-all-block-main-2-main-filter-elem-text'>Даты</div>
                      <div class='panel-conteiner-all-block-main-2-main-filter-elem-ico icon-left'></div>
                    </div>
                    <div class='panel-conteiner-all-block-main-2-main-filter-elem' onclick="sort_type(this)" style='text-align: right; width: calc(calc(100% / 7) - 10px);'>
                      <div class='panel-conteiner-all-block-main-2-main-filter-elem-text'>Тип</div>
                      <div class='panel-conteiner-all-block-main-2-main-filter-elem-ico icon-left'></div>
                    </div>
                    <div class='panel-conteiner-all-block-main-2-main-filter-elem' onclick="sort_size(this)" style='text-align: right; width: calc(calc(100% / 7) - 10px);'>
                      <div class='panel-conteiner-all-block-main-2-main-filter-elem-text'>Размер</div>
                      <div class='panel-conteiner-all-block-main-2-main-filter-elem-ico icon-left'></div>
                    </div>
                  </div>

                  <div id='folderSort'>

                    <div class='panel-conteiner-all-block-main-2-main-elem' oncontextmenu="add_contextmenu([['open_folder','tvoya_function()'],['line'],['new_zip','tvoya_function()'],['copy','tvoya_function()'],['cut_out','tvoya_function()'],['rename','tvoya_function()'],['lock','tvoya_function()'],['info','tvoya_function()'],['download','tvoya_function()'],['line'],['del','tvoya_function()']]);">
                      <div class='panel-conteiner-all-block-main-2-main-elem-ch'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-ico-finder'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-name'>Фотки 01.09.2016</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-date'>01.02.2020</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-type'>Папка с файлами</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-size'>1.25 Гб</div>
                    </div>

                    <div class='panel-conteiner-all-block-main-2-main-elem' oncontextmenu="add_contextmenu([['open_folder','tvoya_function()'],['line'],['new_zip','tvoya_function()'],['copy','tvoya_function()'],['cut_out','tvoya_function()'],['rename','tvoya_function()'],['lock','tvoya_function()'],['info','tvoya_function()'],['download','tvoya_function()'],['line'],['del','tvoya_function()']]);">
                      <div class='panel-conteiner-all-block-main-2-main-elem-ch'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-ico-finder'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-name'>Новая папка</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-date'>05.02.2020</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-type'>Папка с файлами</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-size'>85.3 Кб</div>
                    </div>

                    <div class='panel-conteiner-all-block-main-2-main-elem' oncontextmenu="add_contextmenu([['open_folder','tvoya_function()'],['line'],['new_zip','tvoya_function()'],['copy','tvoya_function()'],['cut_out','tvoya_function()'],['rename','tvoya_function()'],['lock','tvoya_function()'],['info','tvoya_function()'],['download','tvoya_function()'],['line'],['del','tvoya_function()']]);">
                      <div class='panel-conteiner-all-block-main-2-main-elem-ch'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-ico-finder'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-name'>.Очень важна инфа!</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-date'>21.02.2020</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-type'>Папка с файлами</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-size'>253 Мб</div>
                    </div>

                    <div class='panel-conteiner-all-block-main-2-main-elem' oncontextmenu="add_contextmenu([['open_file','tvoya_function()'],['line'],['copy','tvoya_function()'],['cut_out','tvoya_function()'],['rename','tvoya_function()'],['info','tvoya_function()'],['download','tvoya_function()'],['line'],['del','tvoya_function()']]);">
                      <div class='panel-conteiner-all-block-main-2-main-elem-ch'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-ico' style='background-image: url("media/filesICO/svg/word.svg"); filter: grayscale(0)'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-name'>Новый текстовый документ</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-date'>11.02.2020</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-type'>Файл "docx"</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-size'>15.9 Мб</div>
                    </div>

                    <div class='panel-conteiner-all-block-main-2-main-elem' oncontextmenu="add_contextmenu([['open_file','tvoya_function()'],['line'],['copy','tvoya_function()'],['cut_out','tvoya_function()'],['rename','tvoya_function()'],['info','tvoya_function()'],['download','tvoya_function()'],['line'],['del','tvoya_function()']]);">
                      <div class='panel-conteiner-all-block-main-2-main-elem-ch'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-ico' style='background-image: url("media/filesICO/svg/txt.svg"); filter: grayscale(0)'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-name'>Новый текстовый документ</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-date'>18.01.2020</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-type'>Файл "txt"</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-size'>9.5 Кб</div>
                    </div>

                    <div class='panel-conteiner-all-block-main-2-main-elem' oncontextmenu="add_contextmenu([['open_file','tvoya_function()'],['line'],['copy','tvoya_function()'],['cut_out','tvoya_function()'],['rename','tvoya_function()'],['info','tvoya_function()'],['download','tvoya_function()'],['line'],['del','tvoya_function()']]);">
                      <div class='panel-conteiner-all-block-main-2-main-elem-ch'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-ico' style='background-image: url("media/filesICO/svg/html.svg"); filter: grayscale(0)'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-name'>index</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-date'>14.11.2019</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-type'>Файл "html"</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-size'>15.9 Мб</div>
                    </div>

                    <div class='panel-conteiner-all-block-main-2-main-elem' oncontextmenu="add_contextmenu([['open_zip','tvoya_function()'],['line'],['copy','tvoya_function()'],['cut_out','tvoya_function()'],['rename','tvoya_function()'],['info','tvoya_function()'],['download','tvoya_function()'],['line'],['del','tvoya_function()']]);">
                      <div class='panel-conteiner-all-block-main-2-main-elem-ch'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-ico' style='background-image: url("media/filesICO/svg/7z.svg"); filter: grayscale(0)'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-name'>Имя архива</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-date'>19.05.2018</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-type'>Архив 7z</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-size'>62.3 Мб</div>
                    </div>

                    <div class='panel-conteiner-all-block-main-2-main-elem' oncontextmenu="add_contextmenu([['open_folder','tvoya_function()'],['line'],['new_zip','tvoya_function()'],['copy','tvoya_function()'],['cut_out','tvoya_function()'],['rename','tvoya_function()'],['lock','tvoya_function()'],['info','tvoya_function()'],['download','tvoya_function()'],['line'],['del','tvoya_function()']]);">
                      <div class='panel-conteiner-all-block-main-2-main-elem-ch'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-ico-finder'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-name'>Новая папка 1</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-date'>14.01.2018</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-type'>Папка с файлами</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-size'>85.3 Кб</div>
                    </div>

                    <div class='panel-conteiner-all-block-main-2-main-elem' oncontextmenu="add_contextmenu([['open_file','tvoya_function()'],['line'],['copy','tvoya_function()'],['cut_out','tvoya_function()'],['rename','tvoya_function()'],['info','tvoya_function()'],['download','tvoya_function()'],['line'],['del','tvoya_function()']]);">
                      <div class='panel-conteiner-all-block-main-2-main-elem-ch'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-ico' style='background-image: url("media/filesICO/svg/ini.svg"); filter: grayscale(0)'></div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-name'>desktop</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-date'>12.04.2017</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-type'>Файл "ini"</div>
                      <div class='panel-conteiner-all-block-main-2-main-elem-size'>1 байт</div>
                    </div>

                  </div>

                </div>
              </div>
            </div>
          </div>
        </div>
        <?php endif;?>
        <?php if ($individualMsgPanel): ?>
        <div class='panel' id='individual_msg' search-js-elem='Сообщения, section-block, #individual_msg, 💌, Ваши диалоги, [Сообщения, Чат, переписка, общение]' style='<?php echo($page[11][1]);?>'>
          <div class='panel-title'>Сообщения</div>
          <div class='panel-conteiner'>
            <div class='panel-user'>
              <div class='panel-user-ab-btn' style='display: none;'>
                <div class='panel-user-ab-btn-1' onclick="open_block('#msgSettings1','1')" title='Настройки'>
                  <div class='panel-user-ab-btn-2-conteiner'>
                    <div class='panel-user-ab-btn-2-point'></div>
                    <div class='panel-user-ab-btn-2-point'></div>
                    <div class='panel-user-ab-btn-2-point'></div>
                  </div>
                </div>
                <div class='panel-user-ab-btn-conteiner' id='msgSettings1' style='opacity: 0; display: none;'>
                  <div class='panel-user-ab-btn-conteiner-title'>Настройки</div>
                  <div class='panel-user-ab-btn-conteiner-block'>
                    <div class='panel-user-ab-btn-conteiner-block-text'>Сжатый интерфейс</div>
                    <div class='description1'></div>
                    <div style='width: 250px;' class='window-block-settings-block-description'>Текст</div>
                    <div class='panel-user-ab-btn-conteiner-block-ch'>
                      <input disabled class='window-block-settings-block-input' style='display: none;' type='checkbox' id='msgInput1'>
                      <label style='right: 0;' for='msgInput1' onclick="">
                        <span></span>
                      </label>
                    </div>
                  </div>
                </div>
              </div>
              <div class='panel-filter-title'>Пользователи</div>
              <label for='searchmsg1' class='main-nav-search-2'>
                <span class='main-nav-search-icon icon-search'></span>
                <input type='text' placeholder="Поиск" id='searchmsg1' class='main-nav-search-input-2'>
              </label>
              <div class='panel-filter-title-2'>Открытые диалоги</div>

              <div class='panel-msg-conteiner'>

                <div class='panel-msg-block' onclick="open_msg_ind();">
                  <img alt='login' src="media/tmp/photo.jpg" class='panel-msg-block-img'>
                  </img>
                  <div class='panel-msg-block-online'></div>
                  <div class='panel-msg-block-text'>
                    <div class='panel-msg-block-text-title'>Роман Жужгов</div>
                    <div class='panel-msg-block-text-msg'>Гл. администратор</div>
                  </div>
                </div>

                <div class='panel-msg-block' onclick="open_msg_ind();">
                  <img alt='login' src="media/tmp/photo.jpg" class='panel-msg-block-img'></img>
                  <div class='panel-msg-block-online'></div>
                  <div class='panel-msg-block-text'>
                    <div class='panel-msg-block-text-title'>Михаил Баталов</div>
                    <div class='panel-msg-block-text-msg'>Гл. администратор</div>
                  </div>
                </div>

              </div>

              <div class='panel-filter-title-2'>Все диалоги</div>

              <div class='panel-msg-conteiner'>

                <div class='panel-msg-block' onclick="open_msg_ind();">
                  <img alt='login' src="media/tmp/photo.jpg" class='panel-msg-block-img'></img>
                  <div class='panel-msg-block-text'>
                    <div class='panel-msg-block-text-title'>Александр</div>
                    <div class='panel-msg-block-text-msg'>Форма заявки</div>
                  </div>
                </div>

                <div class='panel-msg-block' onclick="open_msg_ind();">
                  <img alt='login' src="media/tmp/photo.jpg" class='panel-msg-block-img'></img>
                  <div class='panel-msg-block-text'>
                    <div class='panel-msg-block-text-title'>Владислав</div>
                    <div class='panel-msg-block-text-msg'>Форма заявки</div>
                  </div>
                </div>

                <div class='panel-msg-block' onclick="open_msg_ind();">
                  <img alt='login' src="media/tmp/photo.jpg" class='panel-msg-block-img'></img>
                  <div class='panel-msg-block-text'>
                    <div class='panel-msg-block-text-title'>Андрей Суворов</div>
                    <div class='panel-msg-block-text-msg'>Редактор</div>
                  </div>
                </div>

              </div>
            </div>
          </div>
          <div class='panel-conteiner-full'>
            <div class='panel-msg'>

              <div class='panel-msg-block-msg' id='individual_msg-preloader' style='display: one; opacity: 1;'>
                <div class='panel-msg-no_msg-block'>
                  <div class='panel-msg-no_msg-block-ico'></div>
                  <div class='panel-msg-no_msg-block-text'>Выберите диалог и начните общение!</div>
                </div>
              </div>

              <div class='panel-msg-block-msg' id='individual_msg-msg' style='display: none; opacity: 0;'>
                <div class='panel-msg-block-msg-conteiner'>
                  <div class='panel-msg-block-msg-conteiner-nav'>
                    <div class='panel-msg-block-msg-conteiner-nav-ico icon-bottom_arrow' style='display: none;' onclick="open_msg_ind();"></div>
                    <div class='panel-msg-block-msg-conteiner-nav-name'>Баталов Михаил</div>
                    <div class='panel-msg-block-msg-conteiner-nav-status'>Online</div>
                    <div class='panel-msg-block-msg-conteiner-nav-act'>
                      <div class='icon-edit'></div>
                      Набирает сообщение...</div>
                    <!-- <div class='panel-msg-block-msg-conteiner-nav-window'>

                      <div class='panel-msg-block-msg-conteiner-nav-window-btn icon-copy' title='Поверх всех окон'></div>
                      <div class='panel-msg-block-msg-conteiner-nav-window-btn' title='Настройки'>
                        <div class="panel-user-ab-btn-2-conteiner" style='margin-top: -2px;'>
                          <div class="panel-user-ab-btn-2-point"></div>
                          <div class="panel-user-ab-btn-2-point"></div>
                          <div class="panel-user-ab-btn-2-point"></div>
                        </div>
                      </div>

                    </div> -->
                  </div>
                  <div class='panel-msg-block-msg-conteiner-main'>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner'>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-slice' data-text='20 февраля 2020'></div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>Ща еще сделаю чтобы писал логин в письме восстановления</div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-slice' data-text='24 февраля 2020'></div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2'>обычно там еще описание пишут</div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2'>
                          <span>ну пускай будет минимализм. Я хочу сделать переадресацию доменного имени.</span>
                          <span class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-image' title='login.png' style='background-image: url("media/img/login.png")'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-image' title='help5.png' style='background-image: url("media/noscript/help5.png")'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-image' title='not_found.png' style='background-image: url("media/img/not_found.png")'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file' title='name.exe'>
                              <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-ico icon-file2'></div>
                              <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-name'>name.exe</div>
                              <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-download icon-download' title='Скачать: name.exe'></div>
                            </div>
                          </span>
                        </div>
                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv'>ну пускай будет минимализм</div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv'>ну пускай будет минимализм</div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>Ща еще сделаю чтобы писал логин в письме восстановления</div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-slice' data-text='25 февраля 2020'></div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>Ща еще сделаю чтобы писал логин в письме восстановления</div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2'>обычно там еще описание пишут</div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2'>ну пускай будет минимализм</div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv'>Значимость этих проблем настолько очевидна, что новая модель организационной деятельности позволяет выполнять важные задания по разработке существенных финансовых и административных условий. Задача организации, в особенности же дальнейшее развитие различных форм деятельности обеспечивает широкому кругу (специалистов) участие в формировании новых предложений. Значимость этих проблем настолько очевидна, что укрепление и развитие структуры позволяет выполнять важные задания по разработке дальнейших направлений развития. Повседневная практика показывает, что консультация с широким активом представляет собой интересный эксперимент проверки новых предложений. Таким образом начало повседневной работы по формированию позиции требуют от нас анализа соответствующий условий активизации. С другой стороны реализация намеченных плановых заданий представляет собой интересный эксперимент проверки существенных финансовых и административных условий. Товарищи! укрепление и развитие структуры влечет за собой процесс внедрения и модернизации форм развития. Таким образом укрепление и развитие структуры представляет собой интересный эксперимент проверки новых предложений. Таким образом укрепление и развитие структуры влечет за собой процесс внедрения и модернизации систем массового участия. Разнообразный и богатый опыт дальнейшее развитие различных форм деятельности позволяет выполнять важные задания по разработке существенных финансовых и административных условий. Разнообразный и богатый опыт новая модель организационной деятельности представляет собой интересный эксперимент проверки новых предложений. Таким образом укрепление и развитие структуры требуют определения и уточнения соответствующий условий активизации.</div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv'>ну пускай будет минимализм</div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>Ща еще сделаю чтобы писал логин в письме восстановления</div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv'>ну пускай будет минимализм</div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv'>ну пускай будет минимализм</div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-warning' oncontextmenu="notification_add('warning','Предупреждение','Описание предупреждения!',8)" title='Предупреждение: описание'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>
                          <span>Ща еще сделаю чтобы писал логин в письме восстановления</span>
                          <span class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file' title='database.sql'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-ico icon-file2'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-name'>database.sql</div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-download icon-download' title='Скачать: database.sql'></div>
                          </div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file' title='database1.sql'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-ico icon-file2'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-name'>database1.sql</div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-download icon-download' title='Скачать: database1.sql'></div>
                          </div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file' title='database2.sql'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-ico icon-file2'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-name'>database2.sql</div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-download icon-download' title='Скачать: database2.sql'></div>
                          </div>
                        </span>
                        </div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-time' title='Отправка сообщения'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv'>ну пускай будет минимализм</div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-error' oncontextmenu="notification_add('error','Ошибка','Описание ошибки!',8)" title='Ошибка: описание ошибки'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                        </div>

                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv'>Ахахах, ору 😂</div>

                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-help'></div>
                    </div>
                  </div>
                </div>
                <div class='panel-msg-block-msg-textinput'>
                  <input type='file' id='msg-file-2' style='display: none;'>
                  <label class='panel-msg-block-msg-textinput-file icon-file' id='panel-msg-block-msg-textinput-file' for='msg-file'></label>
                  <div class='panel-msg-block-msg-textinput-textarea' id='panel-msg-block-msg-textinput-textarea'>
                    <textarea class='area-123' id='msg-input-1' onblur="strong_count(1)" onkeyup="strong_count(1)" onkeydown="strong_count(1)" onclick="strong_count(1)" onfocus="strong_count(1)" onkeypress="strong_count(1)" placeholder="Напишите сообщение..."></textarea>
                    <div class='panel-msg-block-msg-textinput-textarea-emoji icon-emoji' title='Emoji' onclick="open_block('.emoji-block','0')">
                      <div class='emoji-block' style='display: none; opacity: 0;'>
                        <div class='emoji-block-title'>Emoji</div>
                        <div class='emoji-block-title-2'>Стандартные</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f601;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f602;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f603;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f604;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f605;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f606;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f607;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f608;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f609;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f60a;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f60b;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f60c;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f60d;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f60e;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f60f;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f610;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f612;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f613;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f614;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f616;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f618;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f61a;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f61c;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f61d;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f61e;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f620;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f621;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f622;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f623;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f624;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f625;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f628;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f629;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f62a;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f62b;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f62d;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f630;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f631;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f632;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f633;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f635;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f636;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f637;</div>
                        </div>
                        <div class='emoji-block-title-2'>Котя и обезьянка</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f638;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f639;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f63a;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f63b;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f63c;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f63d;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f63e;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f63f;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f640;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f648;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f649;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f64a;</div>
                        </div>
                        <div class='emoji-block-title-2'>Растения</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f331;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f334;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f335;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f337;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f338;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f339;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f33a;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f33b;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f33c;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f33d;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f33e;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f33f;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f340;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f341;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f342;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f343;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f344;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f345;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f346;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f347;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f348;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f349;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f34a;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f34c;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f34d;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f34e;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f34f;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f351;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f352;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f353;</div>
                        </div>
                        <div class='emoji-block-title-2'>Еда</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f354;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f355;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f356;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f357;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f358;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f359;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f35a;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f35b;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f35c;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f35d;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f35e;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f35f;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f360;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f361;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f362;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f363;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f364;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f365;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f366;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f367;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f368;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f369;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f36a;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f36b;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f36c;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f36d;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f36e;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f36f;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f370;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f371;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f372;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f373;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f375;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f376;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f377;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f378;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f379;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f37a;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f37b;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f382;</div>
                        </div>
                        <div class='emoji-block-title-2'>Другое</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f645;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f646;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f647;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f64b;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f64c;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f64d;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f64e;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f64f;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f384;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f385;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f38e;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f393;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3a3;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3a1;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f392;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3a5;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3a8;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3a7;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3b0;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3af;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3c0;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3b9;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3b5;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3b6;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3c1;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3c3;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3b7;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3b8;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3b8;</div>
                          <div onclick="add_emoji(this,'#msg-input-1',1)">&#x1f3bb;</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class='panel-msg-block-msg-textinput-send icon-send' id='panel-msg-block-msg-textinput-send' title='Отправить'></div>
                </div>
              </div>

            </div>
          </div>
        </div>
        <?php endif;?>
        <?php if ($chatPanel): ?>
        <div class='panel' id='general_chat' search-js-elem='Общий чат, section-block, #general_chat, 💭, Диалог со всеми, [Диалог со всеми, общий чат, Чат, переписка, общение, беседа]' style='<?php echo($page[12][1]);?>'>
          <link rel='stylesheet' href="style/general_chat.css">
          <div class='panel-title'>Общий чат</div>
          <div class='panel-conteiner'>
            <div class='panel-user'>
              <div class='panel-user-ab-btn' style=''>
                <div class='panel-user-ab-btn-1' onclick="open_window('#settings-chat')" title='Настройки'>
                  <div class='panel-user-ab-btn-2-conteiner icon-settings'></div>
                </div>
              </div>
              <div class='panel-filter-title'>Пользователи</div>
              <label for='searchmsg1' class='main-nav-search-2'>
                <label class='main-nav-search-icon icon-search'></label>
                <input for='searchmsg1' type='text' style='width: 142px;' placeholder="Поиск" id='searchmsg2' class='main-nav-search-input-2'>
                <span class='main-nav-search-icon-del icon-plus' title='Очистить'></span>
              </label>
              <div class='panel-filter-title-2'>В диалоге</div>

              <div class='panel-msg-conteiner' style='display: none;' id='globalchat-users-list-online-test'></div>
              <div class='panel-msg-conteiner' id='globalchat-users-list-online'>

                <div class='panel-msg-block'>
                  <img alt='login' src="media/tmp/photo.jpg" class='panel-msg-block-img'>
                  </img>
                  <div class='panel-msg-block-ofline'></div>
                  <div class='panel-msg-block-text'>
                    <div class='panel-msg-block-text-title'>Роман Жужгов</div>
                    <div class='panel-msg-block-text-msg'>Гл. администратор</div>
                  </div>
                </div>

                <div class='panel-msg-block'>
                  <img alt='login' src="media/tmp/photo.jpg" class='panel-msg-block-img'></img>
                  <div class='panel-msg-block-online' title='В сети'></div>
                  <div class='panel-msg-block-text'>
                    <div class='panel-msg-block-text-title'>Михаил Баталов</div>
                    <div class='panel-msg-block-text-msg'>Гл. администратор</div>
                  </div>
                </div>

              </div>

            </div>
          </div>
          <div class='panel-conteiner-full'>
            <div class='panel-msg'>

              <div class='panel-msg-block-msg'>
                <div class='panel-msg-block-msg-conteiner'>
                  <div class='panel-msg-block-msg-conteiner-nav'>
                    <div class='panel-msg-block-msg-conteiner-nav-name' spellcheck="false" contenteditable="false">Общий чат</div>
                    <a class='panel-msg-block-msg-conteiner-nav-status' id='globalchat-users-count-title' style='cursor: pointer;' title='Открыть список' onclick="Chat.form.users.window.all(); open_window('#settings-chat-users');">22 участника</a>
                    <div class='panel-msg-block-msg-conteiner-nav-act' style='display: none;'>
                      <div class='icon-edit'></div>
                      Никита набирает сообщение...</div>
                    <div class='panel-msg-block-msg-conteiner-nav-window' style='display: none;'>

                      <!-- <div class='panel-msg-block-msg-conteiner-nav-window-btn icon-copy' title='Поверх всех окон'></div> -->
                      <div class='panel-msg-block-msg-conteiner-nav-window-btn' title='Подробнее' onclick="open_window('')">
                        <div class="panel-user-ab-btn-2-conteiner" style='margin-top: -2px;'>
                          <div class="panel-user-ab-btn-2-point"></div>
                          <div class="panel-user-ab-btn-2-point"></div>
                          <div class="panel-user-ab-btn-2-point"></div>
                        </div>
                      </div>

                    </div>
                  </div>
                  <div class='panel-msg-block-msg-conteiner-main' id='panel-msg-block-msg-conteiner-main-scroll'>
                    <div id='btnChatDown' class="panel-conteiner-all-block-main-2-main-btnTop2 icon-top" title="В самый конец" onclick="scrollDown()" style="opacity: 1; transform: translate(0%, 200%) rotate(180deg); visibility: visible;"></div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner'>
                      <span id='general_chat_block'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-plus' style='transform: rotate(45deg) scale(1.2);'></div>
                                  <div class='chat-set-msg-elem-text'>Заблокировать пользователя</div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1-title'>Никита Башкиров</div>
                            Ща еще сделаю чтобы писал логин в письме восстановления
                          </div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-plus' style='transform: rotate(45deg) scale(1.2);'></div>
                                  <div class='chat-set-msg-elem-text'>Заблокировать пользователя</div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2'>
                            обычно там еще описание пишут
                          </div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-recovery'></div>
                                  <div class='chat-set-msg-elem-text'>Восстановить</div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2' style='opacity: 0.3;'>ну пускай будет минимализм</div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-slice' data-text='24 февраля 2020'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg-inv'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv'>ну пускай будет минимализм</div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg-inv'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv'>ну пускай будет минимализм</div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-plus' style='transform: rotate(45deg) scale(1.2);'></div>
                                  <div class='chat-set-msg-elem-text'>Заблокировать пользователя</div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1-title'>Артем Ефремов</div>
                            <span>ну пускай будет минимализм. Я хочу сделать переадресацию доменного имени.</span>
                            <span class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file'>
                              <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-image' title='help1.png' style='background-image: url("media/noscript/help1.png")'></div>
                              <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-image' title='help3.png' style='background-image: url("media/noscript/help3.png")'></div>
                              <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-image' title='help2.png' style='background-image: url("media/noscript/help2.png")'></div>
                              <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-image' title='login.png' style='background-image: url("media/img/login.png")'></div>
                              <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-image' title='help5.png' style='background-image: url("media/noscript/help5.png")'></div>
                              <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-image' title='not_found.png' style='background-image: url("media/img/not_found.png")'></div>
                            </span>
                          </div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-plus' style='transform: rotate(45deg) scale(1.2);'></div>
                                  <div class='chat-set-msg-elem-text'>Заблокировать пользователя</div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1-title'>Даниил Камшотов</div>
                            <span>Ща еще сделаю чтобы писал логин в письме восстановления</span>
                            <span class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file' title='database.sql'>
                              <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-ico icon-file2'></div>
                              <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-name'>database.sql</div>
                              <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-download icon-download' title='Скачать: database.sql'></div>
                            </div>
                          </span>
                          </div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-plus' style='transform: rotate(45deg) scale(1.2);'></div>
                                  <div class='chat-set-msg-elem-text'>Заблокировать пользователя</div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2'>обычно там еще описание пишут</div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-plus' style='transform: rotate(45deg) scale(1.2);'></div>
                                  <div class='chat-set-msg-elem-text'>Заблокировать пользователя</div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2'>ну пускай будет минимализм</div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg-inv'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv'>Значимость этих проблем настолько очевидна, что новая модель организационной деятельности позволяет выполнять важные задания по разработке существенных финансовых и административных условий. Задача организации, в особенности же дальнейшее развитие различных форм деятельности обеспечивает широкому кругу (специалистов) участие в формировании новых предложений. Значимость этих проблем настолько очевидна, что укрепление и развитие структуры позволяет выполнять важные задания по разработке дальнейших направлений развития. Повседневная практика показывает, что консультация с широким активом представляет собой интересный эксперимент проверки новых предложений. Таким образом начало повседневной работы по формированию позиции требуют от нас анализа соответствующий условий активизации. С другой стороны реализация намеченных плановых заданий представляет собой интересный эксперимент проверки существенных финансовых и административных условий. Товарищи! укрепление и развитие структуры влечет за собой процесс внедрения и модернизации форм развития. Таким образом укрепление и развитие структуры представляет собой интересный эксперимент проверки новых предложений. Таким образом укрепление и развитие структуры влечет за собой процесс внедрения и модернизации систем массового участия. Разнообразный и богатый опыт дальнейшее развитие различных форм деятельности позволяет выполнять важные задания по разработке существенных финансовых и административных условий. Разнообразный и богатый опыт новая модель организационной деятельности представляет собой интересный эксперимент проверки новых предложений. Таким образом укрепление и развитие структуры требуют определения и уточнения соответствующий условий активизации.</div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg-inv'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv'>ну пускай будет минимализм</div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-plus' style='transform: rotate(45deg) scale(1.2);'></div>
                                  <div class='chat-set-msg-elem-text'>Заблокировать пользователя</div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1-title'>Артем Ефремов</div>
                            Ща еще сделаю чтобы писал логин в письме восстановления
                          </div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg-inv'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv'>ну пускай будет минимализм</div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                          </div>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv'>ну пускай будет минимализм</div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-warning' title='Предупреждение: описание'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-plus' style='transform: rotate(45deg) scale(1.2);'></div>
                                  <div class='chat-set-msg-elem-text'>Заблокировать пользователя</div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1-title'>Стас Старвец</div>
                            Ща еще сделаю чтобы писал логин в письме восстановления
                          </div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-time' title='Отправка сообщения'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg-inv'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv'>ну пускай будет минимализм</div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-error' title='Ошибка: описание ошибки'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg-inv'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                              </div>
                            </div>
                          </div>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv'>Ахахах, ору 😂</div>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>
                              12:15
                              <span class='icon-settings' title='Параметры'></span>
                              <div class='chat-set-msg'>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-basket'></div>
                                  <div class='chat-set-msg-elem-text'>Удалить</div>
                                </div>
                                <div class='chat-set-msg-elem'>
                                  <div class='chat-set-msg-elem-ico icon-plus' style='transform: rotate(45deg) scale(1.2);'></div>
                                  <div class='chat-set-msg-elem-text'>Заблокировать пользователя</div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1-title'>Артем Ефремов</div>
                            Ща еще сделаю чтобы писал логин в письме восстановления
                          </div>

                        </div>
                      </span>


                      <div class='panel-msg-block-msg-conteiner-main-conteiner-help'></div>
                    </div>
                  </div>
                </div>
                <div class='panel-msg-block-msg-textinput'>
                  <input type='file' id='msg-file' style='display: none;' multiple />
                  <label class='panel-msg-block-msg-textinput-file-label'>
                    <label class='panel-msg-block-msg-textinput-file icon-file' for='msg-file' id='panel-msg-block-msg-textinput-file-2'>
                      <div class='panel-msg-block-msg-textinput-file-count' style='display: none;' title='4 файла'>4</div>
                    </label>
                    <div class='panel-msg-block-msg-textinput-file-block' style='display: none;'>
                      <div class='panel-msg-block-msg-textinput-file-block-title'>Прикрепленные файлы</div>
                      <span>
                      </span>

                    </div>
                  </label>

                  <div class='panel-msg-block-msg-textinput-textarea' id='panel-msg-block-msg-textinput-textarea-2'>
                    <textarea class='area-123' id='msg-input-2' oninput='strong_count(2)' placeholder="Напишите сообщение..."></textarea>
                    <div class='panel-msg-block-msg-textinput-textarea-emoji icon-emoji' title='Emoji' onclick="open_block('#emoji-block-2','0')">
                      <div class='emoji-block' id='emoji-block-2' style='display: none; opacity: 0;'>
                        <div class='emoji-block-title'>Emoji</div>
                        <div class='emoji-block-title-2'>Стандартные</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f601;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f602;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f603;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f604;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f605;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f606;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f607;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f608;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f609;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f60a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f60b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f60c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f60d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f60e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f60f;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f610;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f612;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f613;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f614;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f616;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f618;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f61a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f61c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f61d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f61e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f620;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f621;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f622;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f623;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f624;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f625;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f628;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f629;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f62a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f62b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f62d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f630;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f631;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f632;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f633;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f635;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f636;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f637;</div>
                        </div>
                        <div class='emoji-block-title-2'>Котя и обезьянка</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f638;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f639;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f63a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f63b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f63c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f63d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f63e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f63f;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f640;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f648;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f649;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f64a;</div>
                        </div>
                        <div class='emoji-block-title-2'>Растения</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f331;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f334;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f335;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f337;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f338;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f339;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f33a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f33b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f33c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f33d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f33e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f33f;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f340;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f341;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f342;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f343;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f344;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f345;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f346;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f347;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f348;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f349;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f34a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f34c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f34d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f34e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f34f;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f351;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f352;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f353;</div>
                        </div>
                        <div class='emoji-block-title-2'>Еда</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f354;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f355;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f356;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f357;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f358;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f359;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f35a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f35b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f35c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f35d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f35e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f35f;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f360;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f361;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f362;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f363;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f364;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f365;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f366;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f367;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f368;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f369;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f36a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f36b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f36c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f36d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f36e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f36f;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f370;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f371;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f372;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f373;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f375;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f376;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f377;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f378;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f379;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f37a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f37b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f382;</div>
                        </div>
                        <div class='emoji-block-title-2'>Другое</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f645;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f646;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f647;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f64b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f64c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f64d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f64e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f64f;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f384;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f385;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f38e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f393;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3a3;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3a1;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f392;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3a5;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3a8;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3a7;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3b0;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3af;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3c0;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3b9;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3b5;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3b6;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3c1;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3c3;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3b7;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3b8;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3b8;</div>
                          <div onclick="add_emoji(this,'#msg-input-2',2)">&#x1f3bb;</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class='panel-msg-block-msg-textinput-send icon-send' id='panel-msg-block-msg-textinput-send-2' title='Отправить' onclick='Chat.form.send();'></div>
                </div>
              </div>

            </div>
          </div>
        </div>
        <?php endif;?>
        <div class='panel' id='support_chat' search-js-elem='Служба поддержки, section-block, #support_chat, 🚀, Персональная помощь, [поддержка, техподдержка, помощь, help, служба поддержки]' style='<?php echo($page[13][1]);?>'>
          <div class='panel-title'>Техподдержка</div>
          <div class='panel-conteiner-all'>
            <div class='panel-conteiner-width-support'>
              <div class='panel-conteiner-width-support-hello'>
                <div class='panel-conteiner-width-support-hello-block'>
                  <div class='panel-conteiner-width-support-hello-block-img'></div>
                  <div class='panel-conteiner-width-support-hello-block-text'>Напишите нам о вашей проблеме и мы поможем вам её решить!</div>
                  <div class='panel-conteiner-width-support-hello-block-btn' onclick="open_support()">Написать</div>
                </div>
              </div>
              <span style='display: none; opacity: 0; transition: 0.25s all; height: 100%;' id='panel-msg-block-msg-conteiner-main-support'>
                <div class='panel-msg-block-msg-conteiner-main' style='margin-top: -15px; margin-bottom: -10px; height: calc(100% - 55px);'>
                  <div class='panel-msg-block-msg-conteiner-main-conteiner'>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>Ща еще сделаю чтобы писал логин в письме восстановления</div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2'>обычно там еще описание пишут</div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2'>ну пускай будет минимализм</div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv'>ну пускай будет минимализм</div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv'>ну пускай будет минимализм</div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>Ща еще сделаю чтобы писал логин в письме восстановления</div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>Ща еще сделаю чтобы писал логин в письме восстановления</div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2'>обычно там еще описание пишут</div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-slice' data-text='Архив'></div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2'>ну пускай будет минимализм</div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv'>
                        <span>
                          Значимость этих проблем настолько очевидна, что новая модель организационной деятельности позволяет выполнять важные задания по разработке существенных финансовых и административных условий. Задача организации, в особенности же дальнейшее развитие различных форм деятельности обеспечивает широкому кругу (специалистов) участие в формировании новых предложений. Значимость этих проблем настолько очевидна, что укрепление и развитие структуры позволяет выполнять важные задания по разработке дальнейших направлений развития. Повседневная практика показывает, что консультация с широким активом представляет собой интересный эксперимент проверки новых предложений. Таким образом начало повседневной работы по формированию позиции требуют от нас анализа соответствующий условий активизации. С другой стороны реализация намеченных плановых заданий представляет собой интересный эксперимент проверки существенных финансовых и административных условий. Товарищи! укрепление и развитие структуры влечет за собой процесс внедрения и модернизации форм развития. Таким образом укрепление и развитие структуры представляет собой интересный эксперимент проверки новых предложений. Таким образом укрепление и развитие структуры влечет за собой процесс внедрения и модернизации систем массового участия. Разнообразный и богатый опыт дальнейшее развитие различных форм деятельности позволяет выполнять важные задания по разработке существенных финансовых и административных условий. Разнообразный и богатый опыт новая модель организационной деятельности представляет собой интересный эксперимент проверки новых предложений. Таким образом укрепление и развитие структуры требуют определения и уточнения соответствующий условий активизации.
                        </span>
                        <span class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-image' title='login.png' style='background-image: url("media/img/login.png")'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-image' title='help5.png' style='background-image: url("media/noscript/help5.png")'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-image' title='not_found.png' style='background-image: url("media/img/not_found.png")'></div>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file' title='name.exe'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-ico icon-file2'></div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-name'>name.exe</div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-file-file-download icon-download' title='Скачать: name.exe'></div>
                          </div>
                        </span>
                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv'>ну пускай будет минимализм</div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>Ща еще сделаю чтобы писал логин в письме восстановления</div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv'>ну пускай будет минимализм</div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-read' title='Отправлено'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv'>ну пускай будет минимализм</div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-warning' title='Предупреждение: описание'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg1'>Ща еще сделаю чтобы писал логин в письме восстановления</div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-time' title='Отправка сообщения'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-inv'>ну пускай будет минимализм</div>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-photo'></div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block-inv'>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-2'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-stat icon-status-error' title='Ошибка: описание ошибки'></div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-constatus-time'>12:15</div>
                      </div>

                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg2-inv'>Ахахах, ору 😂</div>

                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-block-assessment'>
                      <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup'>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-good' style='opacity: 0; display: none;'>
                          <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-good-ab'>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-good-ab-ico'>
                              <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-good-ab-ico-1'></div>
                              <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-good-ab-ico-2'></div>
                            </div>
                            <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-good-ab-text'>Спасибо Вам, за ваш отзыв!</div>
                          </div>
                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-title'>Как вы оцениваете нашу поддержку?</div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-assessment'>
                          <input type="radio" name="support-assessment" id='support-assessment-1' style='display: none;'>
                          <input type="radio" name="support-assessment" id='support-assessment-2' style='display: none;'>
                          <input type="radio" name="support-assessment" id='support-assessment-3' style='display: none;'>
                          <input type="radio" name="support-assessment" id='support-assessment-4' style='display: none;'>
                          <input type="radio" name="support-assessment" id='support-assessment-5' style='display: none;'>

                          <label for='support-assessment-1' id='support-assessment-11' class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-assessment-elem' title='Очень плохо'>&#x1F621;</label>
                          <label for='support-assessment-2' id='support-assessment-21' class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-assessment-elem' title='Плохо'>&#x1F641;</label>
                          <label for='support-assessment-3' id='support-assessment-31' class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-assessment-elem' title='Удовлетворительно'>&#x1F610;</label>
                          <label for='support-assessment-4' id='support-assessment-41' class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-assessment-elem' title='Хорошо'>&#x1F642;</label>
                          <label for='support-assessment-5' id='support-assessment-51' class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-assessment-elem' title='Отлично' style='margin-right: -4px;'>&#x1F600;</label>

                        </div>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-description'>Напишите, если есть за что нас похвалить или поругать:</div>
                        <textarea class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-textarea'></textarea>
                        <div class='panel-msg-block-msg-conteiner-main-conteiner-block-msg-sup-btn' onclick="suppor_assessment()">Отправить</div>
                      </div>
                    </div>
                    <div class='panel-msg-block-msg-conteiner-main-conteiner-help'></div>
                  </div>
                </div>
                <div class='panel-msg-block-msg-textinput' style='margin-bottom: -20px;'>
                  <input type='file' id='msg-file-support' style='display: none;'>
                  <label class='panel-msg-block-msg-textinput-file icon-file' for='msg-file-support' id='panel-msg-block-msg-textinput-file-2-support'></label>
                  <div class='panel-msg-block-msg-textinput-textarea' id='panel-msg-block-msg-textinput-textarea-2-support'>
                    <textarea class='area-123' id='msg-input-2-support' onblur="strong_count(2)" onkeyup="strong_count(2)" onkeydown="strong_count(2)" onclick="strong_count(2)" onfocus="strong_count(2)" onkeypress="strong_count(2)" placeholder="Напишите сообщение..."></textarea>
                    <div class='panel-msg-block-msg-textinput-textarea-emoji icon-emoji' title='Emoji' onclick="open_block('#emoji-block-2-support','0')">
                      <div class='emoji-block' id='emoji-block-2-support' style='display: none; opacity: 0;'>
                        <div class='emoji-block-title'>Emoji</div>
                        <div class='emoji-block-title-2'>Стандартные</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f601;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f602;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f603;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f604;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f605;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f606;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f607;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f608;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f609;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f60a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f60b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f60c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f60d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f60e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f60f;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f610;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f612;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f613;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f614;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f616;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f61a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f618;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f61c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f61d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f61e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f620;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f621;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f622;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f623;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f624;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f625;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f628;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f629;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f62a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f62b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f62d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f630;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f631;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f632;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f633;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f635;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f636;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f637;</div>
                        </div>
                        <div class='emoji-block-title-2'>Котя и обезьянка</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f638;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f639;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f63a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f63b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f63c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f63d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f63e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f63f;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f640;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f648;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f649;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f64a;</div>
                        </div>
                        <div class='emoji-block-title-2'>Растения</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f331;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f334;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f335;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f337;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f338;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f339;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f33a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f33b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f33c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f33d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f33e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f33f;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f340;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f341;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f342;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f343;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f344;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f345;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f346;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f347;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f348;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f349;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f34a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f34c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f34d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f34e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f34f;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f351;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f352;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f353;</div>
                        </div>
                        <div class='emoji-block-title-2'>Еда</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f354;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f355;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f356;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f357;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f358;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f359;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f35a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f35b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f35c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f35d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f35e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f35f;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f360;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f361;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f362;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f363;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f364;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f365;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f366;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f367;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f368;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f369;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f36a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f36b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f36c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f36d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f36e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f36f;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f370;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f371;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f372;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f373;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f375;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f376;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f377;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f378;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f379;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f37a;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f37b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f382;</div>
                        </div>
                        <div class='emoji-block-title-2'>Другое</div>
                        <div class='emoji-block-group'>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f645;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f646;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f647;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f64b;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f64c;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f64d;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f64e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f64f;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f384;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f385;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f38e;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f393;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3a3;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3a1;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f392;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3a5;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3a8;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3a7;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3b0;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3af;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3c0;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3b9;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3b5;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3b6;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3c1;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3c3;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3b7;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3b8;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3b8;</div>
                          <div onclick="add_emoji(this,'#msg-input-2-support',2)">&#x1f3bb;</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class='panel-msg-block-msg-textinput-send icon-send' id='panel-msg-block-msg-textinput-send-3' title='Отправить'></div>
                </div>
              </span>
            </div>
          </div>
        </div>
        <div class='panel' id='global_search' style='<?php echo($page[14][1]);?>'>
          <div class='panel-title'>Поиск</div>
          <div class='panel-conteiner-all' style='margin-bottom: 40px;'>
            <div class='global_search-main'>
              <label for='oXduh-QXOU-BPTZ' class='global_search-main-search'>
                <label for='oXduh-QXOU-BPTZ' class='global_search-main-search-ico icon-search'></label>
                <input id='oXduh-QXOU-BPTZ' class='global_search-main-search-input' placeholder="Поиск"></input>
              </label>
              <div class='global_search-main-resultCount'>
                <span>Результатов: </span>
                <span id='global_search-main-resultCount-span'>18</span>
              </div>
            </div>
            <!-- Ничего не найдено // start // -->
            <div class='global_search-notFound' style='display: none;'>
              <span class='global_search-notFound-span'>
                <div class='global_search-notFound-ico icon-plus'></div>
                <div class='global_search-notFound-text'>По вашему запросу ничего не найдено!</div>
              </span>
              <span class='global_search-notFound-span1' onclick="open_panel('#main'); $('#i1').val('');">
                <div class='global_search-notFound-span1-elem'>
                  <div class='global_search-notFound-span1-elem-ico'>🌍</div>
                  <div class='global_search-notFound-span1-elem-text'>Главная</div>
                </div>
                <div class='global_search-notFound-span1-elem' onclick="open_panel('#all_user'); $('#i1').val('');">
                  <div class='global_search-notFound-span1-elem-ico'>👫</div>
                  <div class='global_search-notFound-span1-elem-text'>Пол пользователей</div>
                </div>
                <div class='global_search-notFound-span1-elem' onclick="open_panel('#news'); $('#i1').val('');">
                  <div class='global_search-notFound-span1-elem-ico'>📘</div>
                  <div class='global_search-notFound-span1-elem-text'>Заголовок статьи</div>
                </div>
                <div class='global_search-notFound-span1-elem' onclick="open_panel('#general_chat'); $('#i1').val('');">
                  <div class='global_search-notFound-span1-elem-ico'>💭</div>
                  <div class='global_search-notFound-span1-elem-text'>Общий чат</div>
                </div>
                <div class='global_search-notFound-span1-elem' onclick="open_time('#time-main');">
                  <div class='global_search-notFound-span1-elem-ico'>🕞</div>
                  <div class='global_search-notFound-span1-elem-text'>Часы</div>
                </div>
                <div class='global_search-notFound-span1-elem' onclick="open_window('#settings');">
                  <div class='global_search-notFound-span1-elem-ico'>🌙</div>
                  <div class='global_search-notFound-span1-elem-text'>Ночная тема</div>
                </div>
                <div class='global_search-notFound-span1-elem' onclick="open_panel('#individual_msg'); $('#i1').val('');">
                  <div class='global_search-notFound-span1-elem-ico'>💌</div>
                  <div class='global_search-notFound-span1-elem-text'>Сообщения</div>
                </div>
                <div class='global_search-notFound-span1-elem' onclick="open_panel('#support_chat'); $('#i1').val('');">
                  <div class='global_search-notFound-span1-elem-ico'>🚀</div>
                  <div class='global_search-notFound-span1-elem-text'>Служба поддержки</div>
                </div>
              </span>
            </div>
            <!-- Ничего не найдено // end // -->
            <!-- Элемент(ы) найдены // start // -->
            <span id='globalSearchIdOutput'>
              <div class='global_search-Found'>
                <div class='global_search-Found-title'>Новости</div>
                <div class='global_search-Found-main'>
                  <div class='global_search-Found-main-elem'>
                    <div class='global_search-Found-main-elem-hover'>
                      <div onclick="open_window('#iframe-topNews')">
                        Читать
                        <span style='transform: rotate(180deg); display: inline-block;'class='icon-left'></span>
                      </div>
                      <br>
                      <div style='margin-top: -3px; margin-right: 29px;' onclick="open_window('#page-newsStatistic')">
                        Статистика
                        <span style='transform: rotate(180deg); display: inline-block;'class='icon-left'></span>
                      </div>
                    </div>
                    <div class='global_search-Found-main-elem-title'>В Москве разрешили езди...</div>
                    <div class='global_search-Found-main-elem-text'>
                      Ездить на машине в Москве можно только по одному. Об этом сообщил председатель <span style='background-color: #5d78ff2e; border-radius: 2px; padding: 0px 4px 0px 4px;'>Мосгордумы</span> Алексей Шапошников в эфире Первого канала.. По его словам, причина в том, что это нарушение дистанции в 1,5 метра. За это будет полагаться штраф. Исключение здесь составляет только семья, которая совместно находится на самоизоляции. Однако с собой все равно необходимо брать паспорта. При этом он подчеркнул, что доехать от дома до дачи.
                    </div>
                  </div>
                  <div class='global_search-Found-main-elem'>
                    <div class='global_search-Found-main-elem-hover'>
                      <div onclick="open_window('#iframe-topNews')">
                        Читать
                        <span style='transform: rotate(180deg); display: inline-block;'class='icon-left'></span>
                      </div>
                      <br>
                      <div style='margin-top: -3px; margin-right: 29px;' onclick="open_window('#page-newsStatistic')">
                        Статистика
                        <span style='transform: rotate(180deg); display: inline-block;'class='icon-left'></span>
                      </div>
                    </div>
                    <div class='global_search-Found-main-elem-title'>В Москве разрешили езди...</div>
                    <div class='global_search-Found-main-elem-text'>
                      Ездить на машине в Москве можно только по одному. Об этом сообщил председатель <span style='background-color: #5d78ff2e; border-radius: 2px; padding: 0px 4px 0px 4px;'>Мосгордумы</span> Алексей Шапошников в эфире Первого канала.. По его словам, причина в том, что это нарушение дистанции в 1,5 метра. За это будет полагаться штраф. Исключение здесь составляет только семья, которая совместно находится на самоизоляции. Однако с собой все равно необходимо брать паспорта. При этом он подчеркнул, что доехать от дома до дачи.
                    </div>
                  </div>
                </div>
              </div>
              <div class='global_search-Found'>
                <div class='global_search-Found-title'>Расписание</div>
                <div class='global_search-Found-main'>
                  <div class='global_search-Found-main-timetable'>
                    <div class='global_search-Found-main-timetable-point'>
                      <div class='global_search-Found-main-timetable-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-timetable-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-timetable-ico'>📅</div>
                    <div class='global_search-Found-main-timetable-text'>
                      <div class='global_search-Found-main-timetable-text-title'>Заголовок таблицы</div>
                      <div class='global_search-Found-main-timetable-text-description'>
                        <span>02.02.2020</span>
                        <span style='font-style: italic;'>Воскресенье</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-timetable'>
                    <div class='global_search-Found-main-timetable-point'>
                      <div class='global_search-Found-main-timetable-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-timetable-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-timetable-ico'>📅</div>
                    <div class='global_search-Found-main-timetable-text'>
                      <div class='global_search-Found-main-timetable-text-title'>Заголовок таблицы</div>
                      <div class='global_search-Found-main-timetable-text-description'>
                        <span>03.02.2020</span>
                        <span style='font-style: italic;'>Понедельник</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-timetable'>
                    <div class='global_search-Found-main-timetable-point'>
                      <div class='global_search-Found-main-timetable-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-timetable-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-timetable-ico'>📅</div>
                    <div class='global_search-Found-main-timetable-text'>
                      <div class='global_search-Found-main-timetable-text-title'>Заголовок таблицы</div>
                      <div class='global_search-Found-main-timetable-text-description'>
                        <span>05.02.2020</span>
                        <span style='font-style: italic;'>Среда</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-timetable'>
                    <div class='global_search-Found-main-timetable-point'>
                      <div class='global_search-Found-main-timetable-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-timetable-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-timetable-ico'>📅</div>
                    <div class='global_search-Found-main-timetable-text'>
                      <div class='global_search-Found-main-timetable-text-title'>Заголовок таблицы</div>
                      <div class='global_search-Found-main-timetable-text-description'>
                        <span>08.02.2020</span>
                        <span style='font-style: italic;'>Суббота</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class='global_search-Found'>
                <div class='global_search-Found-title'>Пользователи</div>
                <div class='global_search-Found-main'>
                  <div class='global_search-Found-main-section' title='Главный администратор: login'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-img' style='background-image: url("media/users/0.jpg")'></div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Имя фамилия</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span class='global_search-Found-main-section-text-description-status' style='background-color: #ffb822;' title='Главный администратор'>Логин</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' title='Редактор: login'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-img' style='background-image: url("media/users/0.jpg")'></div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Имя фамилия</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span class='global_search-Found-main-section-text-description-status' style='background-color: #fd397a;' title='Редактор'>Логин</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' title='Модератор: login'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-img' style='background-image: url("media/users/0.jpg")'></div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Имя фамилия</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span class='global_search-Found-main-section-text-description-status' style='background-color: #5d78ff;' title='Модератор'>Логин</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' title='Администратор: login'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-img' style='background-image: url("media/users/0.jpg")'></div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Имя фамилия</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span class='global_search-Found-main-section-text-description-status' style='background-color: #0abb87;' title='Администратор'>Логин</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' title='Стандартный: login'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-img' style='background-image: url("media/users/0.jpg")'></div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Имя фамилия</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span class='global_search-Found-main-section-text-description-status' style='background-color: #6b5eae;' title='Стандартный'>Логин</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class='global_search-Found'>
                <div class='global_search-Found-title'>Разделы</div>
                <div class='global_search-Found-main'>
                  <div class='global_search-Found-main-section'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>📈</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Статистика</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Графики, диаграммы</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>🌍</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Главная</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Общая информация</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>🎥</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Новости</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Статьи, публикации</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>📅</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Расписание</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Таблицы с занятиями</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>🎓</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Помощь</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Инструкция</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' onclick="open_window('#contacts')">
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>📞</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Контакты</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Ваше расположение</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' onclick="open_window('#upload')">
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>⚡</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Обновление</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Обновлений нет</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' onclick="open_window('#settings')">
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>🔧</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Настройки</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Описание, теги</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>📝</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Отзывы</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Отзыв о вас</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>👔</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>О компании</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Ваша история</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>📁</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Проводник</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Файловый менеджер</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>💌</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Сообщения</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Ваши диалоги</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' onclick="open_console();">
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>🏴‍☠️</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Консоль</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Не трогай это!</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>💭</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Общий чат</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Диалог со всеми</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>🤝🏼</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Новый пользователь</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Добавляте новых людей</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>🤟🏼</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Все пользователи</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Удаляй, редактируй</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>💪🏼</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Ваш профиль</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Ваши данные</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>🚀</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Служба поддержки</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Персональная помощь</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' onclick="open_window('#about_program')">
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>💙</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>О программе</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Лицензия, права</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' onclick="open_time('#time-main');">
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>🕞</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Часы</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Alt + T</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' onclick="open_tetris('#tetris-main'); tetris();">
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>🎮</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Тетрис</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Мини-игра</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class='global_search-Found'>
                <div class='global_search-Found-title'>Файлы</div>
                <div class='global_search-Found-main'>
                  <div class='global_search-Found-main-section' title='Имя папки'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>📁</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Имя папки</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Размер: 16Кб</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' title='Имя папки'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>📁</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Имя папки</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Размер: 153Мб</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' title='Имя файла.php'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-svg' style='background-image: url("media/filesICO/svg/PHP.svg")'></div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Имя файла.php</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Размер: 17.2Мб</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' title='Имя файла.raw'>
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-svg' style='background-image: url("media/filesICO/svg/RAW.svg")'></div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Имя файла.raw</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Размер: 32Мб</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class='global_search-Found'>
                <div class='global_search-Found-title'>Другое</div>
                <div class='global_search-Found-main'>
                  <div class='global_search-Found-main-section' onclick="sendExitForm();">
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>🦶🏼</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Выход</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Выйти из админки</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' onclick="open_window('#edit-password')">
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>💂🏼‍♂️🏼</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Пароль</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Изменить пароль</span>
                      </div>
                    </div>
                  </div>
                  <div class='global_search-Found-main-section' onclick="open_window('#profile-edit')">
                    <div class='global_search-Found-main-section-point'>
                      <div class='global_search-Found-main-section-point-textOpen'>Открываю</div>
                      <div class='global_search-Found-main-section-point-text icon-left'></div>
                    </div>
                    <div class='global_search-Found-main-section-ico'>✍🏼</div>
                    <div class='global_search-Found-main-section-text'>
                      <div class='global_search-Found-main-section-text-title'>Профиль</div>
                      <div class='global_search-Found-main-section-text-description'>
                        <span>Редактировать профиль</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </span>
            <!-- Элемент(ы) найдены // end // -->
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
