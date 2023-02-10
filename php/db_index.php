<?php

  /*
   *  Study Buddy v1.01
   *  (c) 2019-2020 INSOweb  <http://insoweb.ru>
   *  All rights reserved.
   */

   if(empty($_POST) && empty($_GET)) {
     exit('EMPTY.');
   }

  // === setup =================================================================

  require_once('db_includes.php');

  session_name($sess_name);
  session_start();

  // === parameters ============================================================

  $timer_time = 300;

  $captcha_url = 'https://www.google.com/recaptcha/api/siteverify';
  $captcha3_params = Array(
    'secret' => '6LdKcfQUAAAAAOL_72zA3_KUPmOyH89-s-tzUIkD',
    'response' => isset($_POST['captcha3_token']) ? htmlspecialchars($_POST['captcha3_token'], ENT_QUOTES) : false,
    'remoteip' => $_SERVER['REMOTE_ADDR']
  );

  $email_regex = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
  $phone_regex = '/^([0-9]){11}$/';
  $name_regex = '/^([A-Za-zА-ЯЁа-яё]){2,48}$/u';
  $text_regex = '/([^A-Za-zА-Яа-яЁё0-9\,\.\"\'\%\$\#\№\:\;\!\?\[\]\{\}\(\)\=\-\+\*\/\~\@\s])/u';

  // === PDO ===================================================================

  // establish connection
  $pdo_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
  $pdo_ap = new PDO($pdo_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);

  // == mailing ================================================================

  require_once('phpmailer/PHPMailerAutoload.php');
  $the_php_mailer = new PHPMailer;
  $the_php_mailer->isSMTP();
  $the_php_mailer->CharSet = "UTF-8";
  $the_php_mailer->SMTPAuth = true;
  $the_php_mailer->Host = 'smtp.gmail.com';
  $the_php_mailer->Username = 'inso.web59@gmail.com';
  $the_php_mailer->Password = 'poma098123';
  $the_php_mailer->SMTPSecure = 'ssl';
  $the_php_mailer->Port = 465;
  $the_php_mailer->setFrom('inso.web59@gmail.com', 'INSOWEB.RU');

  // === index functions =======================================================

  // reCaptcha
  function check_captcha() {
    global $captcha_url;
    global $captcha3_params;
    if($captcha3_params['response'] === false) return false;
    // send POST request
    $ch = curl_init($captcha_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $captcha3_params);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // recieve
    $response = curl_exec($ch);
    if(!empty($response)) $decoded_response = json_decode($response);
    // read
    if(isset($decoded_response) && property_exists($decoded_response, 'success') && ($decoded_response->success) && ($decoded_response->score > 0.5)) {
      return true;
    }
    return false;
  }

  // convert image to base64
  function insert_base64_encoded_image_src($img, $svg = false) {
    $mime = 'image/svg+xml';
    if($svg == false) {
      $mime = getimagesize($img)['mime'];
    }
    $data = base64_encode(file_get_contents($img));
    return "data:{$mime};base64,{$data}";
  }

  function check_timer($timer_name, $the_time_limit) {
    global $pdo_ap;
    // check timer
    $timer_exists = false;
    $db_timer = new DateTime();
    $db_timer->format('U = Y-m-d H:i:s');
    $now_timer = new DateTime('now');
    try {
      $stmt = $pdo_ap->prepare("SELECT the_time FROM timers WHERE timer = ?");
      $stmt->execute([$timer_name]);
    }
    catch(Exception $e) {
      return 'ERROR.LINE.'.__LINE__;
    }
    $is_empty = true;
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      $is_empty = false;
      if(isset($row['the_time'])) {
        $timer_exists = true;
        $db_timer->setTimestamp(strtotime($row['the_time']));
      }
      else {
        return 'ERROR.LINE.'.__LINE__;
      }
    }
    if($is_empty) {
      $timer_exists = false;
    }
    if($timer_exists) {
      $difference = $now_timer->getTimestamp() - $db_timer->getTimestamp();
      if($difference < $the_time_limit) {
        return false;
      }
    }
    // add timer
    try {
      $stmt = $pdo_ap->prepare("INSERT INTO `timers` (`timer`) VALUES (:timername) ON DUPLICATE KEY UPDATE `the_time`=CURRENT_TIMESTAMP()");
      $stmt->execute(Array(
        ':timername' => $timer_name
      ));
    }
    catch(Exception $e) {
      return 'ERROR.LINE.'.__LINE__;
    }
    return true;
  }

  // === index requests ========================================================

  if(isset($_POST['send_mail'])) {
    // prepare
    if(!isset($_POST['user_token']) || !isset($_POST['name1']) || !isset($_POST['name2']) || !isset($_POST['msg'])) {
      exit('WRONG.');
    }
    if((!isset($_POST['phone']) || empty($_POST['phone'])) && (!isset($_POST['email']) || empty($_POST['email']))) {
      exit('WRONG.');
    }
    $subject = 'Study Buddy';
    $name1 = htmlspecialchars($_POST['name1'], ENT_QUOTES);
    $name2 = htmlspecialchars($_POST['name2'], ENT_QUOTES);
    $email = 'Не указана';
    if(isset($_POST['email']) && !empty($_POST['email'])) {
      $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
      $subject = "Study Buddy заявка $email";
      if(!preg_match($email_regex, $email)) exit('EMAIL.');
    }
    $phone = 'Не указан';
    if(isset($_POST['phone']) && !empty($_POST['phone'])) {
      $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES);
      $subject = "Study Buddy заявка $phone";
      if(!preg_match($phone_regex, $phone)) exit('PHONE.');
    }
    $message = htmlspecialchars($_POST['msg'], ENT_QUOTES);
    $login = 'Анонимный пользователь';
    if(isset($_SESSION['login'])) {
      $login = 'Имя пользователя: '.$_SESSION['login'];
    }
    $ip = $_SERVER['REMOTE_ADDR'];
    $country = get_country_by_ip($ip, 'ru');
    $city = get_city_by_ip($ip, 'ru');
    // check data
    if(!preg_match($name_regex, $name1)) exit('NAME1.');
    if(!preg_match($name_regex, $name2)) exit('NAME2.');
    if(preg_match($text_regex, $message)) exit('MSG.');
    // check usertoken
    $usertoken = htmlspecialchars($_POST['user_token'], ENT_QUOTES);
    $len = mb_strlen($usertoken);
    if($len < 16 || $len > 64) exit('WRONG.');
    // ip hash
    $sha_ip = sha1($_SERVER['REMOTE_ADDR']);
    // check timer by ip
    if(check_timer('SITEMAIL_IP_'.$sha_ip, $timer_time) !== true) exit('TIMER.');
    // check timer by token
    if(check_timer('SITEMAIL_T_'.$usertoken, $timer_time) !== true) exit('TIMER.');
    // check captcha
    $captcha_status = check_captcha();
    if($captcha_status === false) {
      exit('CAPTCHA.');
    }
    // get formEmail
    $form_email = '';
    try {
      $stmt = $pdo_ap->prepare("SELECT * FROM site_settings WHERE param = ?");
      $stmt->execute(['formEmail']);
      $row = $stmt->fetch(PDO::FETCH_LAZY);
      if(!empty($row)) $form_email = $row['value'];
    }
    catch(Exception $e) { debuglog('PDO '.__FILE__.' :: '.__LINE__); exit('ERROR.'); }
    // send mail
    $mail_date = date("d.m.Y в G:i");
    $base64_logo = insert_base64_encoded_image_src('../media/svg/logo.svg', true);
    $mail_body = "<!DOCTYPE html><html lang=\"ru\" dir=\"ltr\"><head><meta charset=\"utf-8\"><style>body{padding:0;margin:0;width:100%;font-family:monospace}.main{width:calc(100% - 34px);padding:10px;border:2px solid #2a9fd0;border-radius:10px;background-color:#e6e8fc6b;margin:5px}.main-logo{width:100%}.main-logo-ico{display:inline-block;vertical-align:middle;position:relative;height:50px;width:200px;margin-left:15px;background-position:center;background-repeat:no-repeat;background-size:contain}.main-text-title{font-size:25px;font-family:monospace;font-weight:700;margin-left:15px;margin-top:10px}.main-text-text{font-family:pfl;margin-left:15px;margin-top:10px;text-align:justify;margin-right:15px}.main-text-a{font-family:monospace;font-weight:100;margin-left:15px;margin-top:10px;display:inline-block;text-align:justify;margin-right:15px;border-bottom:1px dashed #303036;color:#303036;text-decoration:none}.main-text-a:hover{text-decoration:none;border-bottom:1px solid #303036}.main-text-auto{font-family:monospace;font-weight:100;margin-left:15px;margin-top:10px;text-align:justify;margin-right:15px;font-style:italic}.main-text-password{display:inline-block;padding:5px 10px;background-color:#2a9fd0;margin-left:15px;font-family:monospace;font-weight:100;color:#fff;border-radius:5px;margin-top:5px}</style></head><body><div class='main'><div class='main-logo'><img class='main-logo-ico' src=\"$base64_logo\"></img></div><div class='main-text'><div class='main-text-text'>$name1 $name2 оставил заявку</div><br><div class='main-text-text'>Его телефон: $phone</div><br><div class='main-text-text'>Его почта: $email</div><br><div class='main-text-text'>$login</div><br><br><div class='main-text-text'>IP пользователя: <b>$ip</b></div><br><div class='main-text-text'>Вероятнее всего, пользователь из города $city, $country</div><br><br><div class='main-text-text'><b>Текст письма:</b></div><br><br><div class='main-text-text'>$message</div><br><br><div class='main-text-text' style='margin-bottom: 15px; margin-top: 25px;'><span>Дата составления письма: </span><span><b>$mail_date</b></span></div></div></div></body></html> ";
    $the_php_mailer->addAddress($form_email, 'Study Buddy');
    $the_php_mailer->isHTML(true);
    $the_php_mailer->Subject = $subject;
    $the_php_mailer->Body = $mail_body;
    if(!$the_php_mailer->send()) {
      debuglog('mailing');
      exit('ERROR.');
    }
    // end
    exit('OK.');
  }

  // ===========================================================================

  exit();

?>