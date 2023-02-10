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
  create_default_session();

  // === parameters ============================================================

  $captcha_url = 'https://www.google.com/recaptcha/api/siteverify';
  $captcha2_params = Array(
    'secret' => '6Lfwa_QUAAAAAOtxv5z-uY9uuJwwiUcjsOH-spe9',
    'response' => isset($_POST['captcha2_token']) ? htmlspecialchars($_POST['captcha2_token'], ENT_QUOTES) : false,
    'remoteip' => $_SERVER['REMOTE_ADDR']
  );
  $captcha3_params = Array(
    'secret' => '6LdKcfQUAAAAAOL_72zA3_KUPmOyH89-s-tzUIkD',
    'response' => isset($_POST['captcha3_token']) ? htmlspecialchars($_POST['captcha3_token'], ENT_QUOTES) : false,
    'remoteip' => $_SERVER['REMOTE_ADDR']
  );

  $login_regex = '/^([A-z0-9]){4,32}$/';
  $password_regex = '/^([a-zA-Z0-9-.,_!\$\#а-яА-ЯёЁ]){8,64}$/u';
  $email_regex = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
  $phone_regex = '/^([0-9]){11}$/';
  $name1_regex = '/^([A-Za-zА-ЯЁа-яё]){2,32}$/u';
  $name2_regex = '/^([A-Za-zА-ЯЁа-яё]){2,48}$/u';
  $name3_regex = '/^([A-Za-zА-ЯЁа-яё]){2,32}$/u';

  $path_to_users_dir = '../users/';

  $account_emails_limit = 1;

  // === db information ========================================================

  $sql_auth = $sql_site;

  // === PDO ===================================================================

  // establish connection
  $pdo_dsn = "mysql:host=".$sql_auth['host'].";dbname=".$sql_auth['db'].";charset=".$sql_auth['charset'];
  $pdo = new PDO($pdo_dsn, $sql_auth['user'], $sql_auth['password'], $pdo_options);

  // == mailing ================================================================

  require_once('phpmailer/PHPMailerAutoload.php');
  $the_php_mailer = new PHPMailer;
  $the_php_mailer->isSMTP();
  $the_php_mailer->CharSet = "UTF-8";
  $the_php_mailer->SMTPAuth = true;
  $the_php_mailer->Host = 'smtp.yandex.ru';
  $the_php_mailer->Username = 'service@insoweb.ru';
  $the_php_mailer->Password = 'udzo@v4r%KkGIEZ';
  $the_php_mailer->SMTPSecure = 'ssl';
  $the_php_mailer->Port = 465;
  $the_php_mailer->setFrom('service@insoweb.ru', 'INSOWEB.RU');

  // === auth functions ========================================================

  function gen_password($p_length = null) {
    if(is_null($p_length)) $p_length = 10;
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789_.$#-';
    $pass = Array();
    $a_length = mb_strlen($alphabet) - 1;
    for($i = 0; $i < $p_length; $i++) {
      $n = random_int(0, $a_length);
      $pass[] = $alphabet[$n];
    }
    return implode($pass);
  }

  // create user session
  function create_user_session($login, $id) {
    // define globals
    global $sess_v;
    // referer
    $referer = 'none';
    if(isset($_SESSION['referer'])) {
      $referer = $_SESSION['referer'];
    }
    // activity token
    $token = gen_token(32);
    if(isset($_SESSION['act_token'])) {
      $token = $_SESSION['act_token'];
    }
    // destroy session
    $_SESSION = Array();
    // create user session
    $_SESSION['auth'] = true;
    $_SESSION['login'] = $login;
    $_SESSION['userid'] = $id;
    $_SESSION['act_token'] = $token;
    $_SESSION['version'] = $sess_v;
    $_SESSION['referer'] = $referer;
  }

  // create directory
  function create_directory($path) {
    return is_dir($path) || mkdir($path, 0700, true);
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

  function register_new($login, $name1, $name2, $name3, $email, $mailing) {
    global $path_to_users_dir;
    global $account_emails_limit;
    global $pdo;
    global $sql_ap;
    global $pdo_options;
    global $the_php_mailer;
    // connect to Admin Panel
    $pdo_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
    $pdo_ap = new PDO($pdo_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);
    // check email is free
    try {
      $stmt = $pdo->prepare("SELECT count FROM emails WHERE email = ?");
      $stmt->execute([$email]);
      $emails_total = $stmt->fetchColumn();
      if(empty($emails_total)) $emails_total = 0;
      if($emails_total >= $account_emails_limit) exit('EMAIL_LIMIT.');
    }
    catch(Exception $e) {
      debuglog();
      exit('ERROR.');
    }
    // check login exists
    try {
      $stmt = $pdo->prepare("SELECT * FROM accounts WHERE account = ?");
      $stmt->execute([$login]);
      $founded = $stmt->fetch();
      if(!empty($founded)) {
        exit('LOGIN_EXISTS.');
      }
    }
    catch(Exception $e) {
      debuglog();
      exit('ERROR.');
    }
    // create user folders
    create_directory($path_to_users_dir.'public/'.$login);
    create_directory($path_to_users_dir.'private/'.$login);
    // generate password
    $password = gen_password();
    $pass_enc = password_hash($password, PASSWORD_BCRYPT);
    // city and country
    $city = get_city_by_ip($_SERVER['REMOTE_ADDR'], 'ru');
    $country = get_country_by_ip($_SERVER['REMOTE_ADDR'], 'ru');
    // gender
    $gender = get_gender_by_name($name1, $name2, $name3);
    // current date
    $reg_date = date('Y-m-d');
    // DB
    try {
      // add record to db
      $stmt = $pdo->prepare("INSERT INTO `accounts` (`account`, `password`, `name1`, `name2`, `name3`, `email`, `country`, `city`, `gender`, `mailing`) VALUES (:account, :password, :name1, :name2, :name3, :email, :country, :city, :gender, :mailing)");
      $stmt->execute(Array(
        ':account' => $login,
        ':password' => $pass_enc,
        ':name1' => $name1,
        ':name2' => $name2,
        ':name3' => $name3,
        ':email' => $email,
        ':country' => $country,
        ':city' => $city,
        ':gender' => $gender,
        ':mailing' => $mailing
      ));
      // check result and get id
      $stmt = $pdo->prepare("SELECT account_id FROM accounts WHERE account = ?");
      $stmt->execute([$login]);
      $id = $stmt->fetchColumn();
      // set emails count
      if($emails_total > 0) {
        $emails_total++;
        $stmt = $pdo->prepare("UPDATE `emails` SET `count`=:count WHERE `email`=:email");
        $stmt->execute(Array(
          ':count' => $emails_total,
          ':email' => $email
        ));
      }
      else {
        $emails_total = 1;
        $stmt = $pdo->prepare("INSERT INTO `emails` (`email`, `count`) VALUES (:email, :count)");
        $stmt->execute(Array(
          ':email' => $email,
          ':count' => $emails_total
        ));
      }
    }
    catch(Exception $e) {
      debuglog();
      exit('ERROR.');
    }
    // grant access
    // email confirm token
    $confirm = gen_token();
    // get formEmail
    $form_email = '';
    try {
      $stmt = $pdo_ap->prepare("SELECT * FROM site_settings WHERE param = ?");
      $stmt->execute(['formEmail']);
      $row = $stmt->fetch(PDO::FETCH_LAZY);
      if(!empty($row)) $form_email = $row['value'];
    }
    catch(Exception $e) { debuglog(); }
    // send mail
    $mail_date = date("d.m.Y в G:i");
    $mailto_str = '';
    if($form_email != '') $mailto_str = "<br><br>Если у Вас есть вопросы, Вы можете обратиться по электронной почте: <a class='main-text-a' style='margin-left: 0px;' href='mailto:$form_email'>$form_email</a>";
    $mail_text = "$name1, Ваш адрес был указан при регистрации на сайте Study Buddy ";
    $mail_link = 'insoweb.ru';
    $base64_logo = insert_base64_encoded_image_src('../media/svg/logo.svg', true);
    $mail_body = "<!DOCTYPE html><html lang=\"ru\" dir=\"ltr\"><head><meta charset=\"utf-8\"><style>body{padding:0;margin:0;width:100%;font-family:monospace}.main{width:calc(100% - 34px);padding:10px;border:2px solid #2a9fd0;border-radius:10px;background-color:#e6e8fc6b;margin:5px}.main-logo{width:100%}.main-logo-ico{display:inline-block;vertical-align:middle;position:relative;height:50px;width:200px;margin-left:15px;background-position:center;background-repeat:no-repeat;background-size:contain}.main-text-title{font-size:25px;font-family:monospace;font-weight:700;margin-left:15px;margin-top:10px}.main-text-text{font-family:pfl;margin-left:15px;margin-top:10px;text-align:justify;margin-right:15px}.main-text-a{font-family:monospace;font-weight:100;margin-left:15px;margin-top:10px;display:inline-block;text-align:justify;margin-right:15px;border-bottom:1px dashed #303036;color:#303036;text-decoration:none}.main-text-a:hover{text-decoration:none;border-bottom:1px solid #303036}.main-text-auto{font-family:monospace;font-weight:100;margin-left:15px;margin-top:10px;text-align:justify;margin-right:15px;font-style:italic}.main-text-password{display:inline-block;padding:5px 10px;background-color:#2a9fd0;margin-left:15px;font-family:monospace;font-weight:100;color:#fff;border-radius:5px;margin-top:5px}</style></head><body><div class='main'><div class='main-logo'><img class='main-logo-ico' src=\"$base64_logo\"></img></div><div class='main-text'><div class='main-text-title'>Добро пожаловать!</div><div class='main-text-text'>$mail_text</div><br><div class='main-text-text'>Ваш логин и пароль:</div><div class='main-text-password'>Логин: $login</div><br><div class='main-text-password'>Пароль: $password</div><br><br><a href='$mail_link' target=\"_blank\" class='main-text-a'>Перейти на сайт StudyBuddy</a><br><br><div class='main-text-auto'>Это письмо сформировано автоматически. Пожалуйста, не отвечайте на него.$mailto_str</div><div class='main-text-text' style='margin-bottom: 15px; margin-top: 25px;'><span>Дата составления письма: </span><span><b>$mail_date</b></span></div></div></div></body></html>";
    $the_php_mailer->addAddress($email, $name1);
    $the_php_mailer->isHTML(true);
    $the_php_mailer->Subject = 'Регистрация Study Buddy';
    $the_php_mailer->Body = $mail_body;
    if(!$the_php_mailer->send()) {
      debuglog('mailing');
    }
    // ok
    return true;
  }

  function log_in($login, $password) {
    global $pdo;
    // get account data
    $access_granted = false;
    try {
      $stmt = $pdo->prepare("SELECT * FROM accounts WHERE account = ?");
      $stmt->execute([$login]);
    }
    catch(Exception $e) {
      debuglog('PDO');
      exit('ERROR.');
    }
    $row = $stmt->fetch(PDO::FETCH_LAZY);
    if(empty($row) || !isset($row['account_id'])) {
      exit('NOT_FOUND.');
    }
    // check password
    if(!password_verify($password, $row['password'])) {
      // ban by ip
      if(!check_counter('SITE_CLOGIN_IP_'.sha1($_SERVER['REMOTE_ADDR']), 10)) {
        ban_ip($_SERVER['REMOTE_ADDR'], 1800);
        set_counter('SITE_CLOGIN_IP_'.sha1($_SERVER['REMOTE_ADDR']), 0);
      }
      exit('WRONG_PASSWORD.');
    }
    // grant access
    create_user_session($login, $row['account_id']);
    return true;
  }

  function log_out($token) {
    if(isset($_SESSION['act_token']) && ($token == $_SESSION['act_token'])) {
      $_SESSION = Array();
      $_SESSION['act_token'] = $token;
      return true;
    }
    else {
      return false;
    }
  }

  function change_password($id, $old_password, $new_password) {
    global $pdo;
    global $password_regex;
    // check
    if(!preg_match($password_regex, $old_password) || !preg_match($password_regex, $new_password)) {
      return false;
    }
    // get old password from db
    try {
      $stmt = $pdo->prepare("SELECT password FROM accounts WHERE account_id = ?");
      $stmt->execute([$id]);
    }
    catch(Exception $e) {
      debuglog('PDO');
      return false;
    }
    $row = $stmt->fetch(PDO::FETCH_LAZY);
    if(empty($row) || !isset($row['password'])) {
      return false;
    }
    // check password
    if(!password_verify($old_password, $row['password'])) {
      return false;
    }
    // save new password
    $password_enc = password_hash($new_password, PASSWORD_BCRYPT);
    try {
      $stmt = $pdo->prepare("UPDATE `accounts` SET `password` = :password WHERE `account_id` = :account_id");
      $stmt->execute(Array(
        ':password' => $password_enc,
        ':account_id' => $id
      ));
    }
    catch(Exception $e) {
      debuglog('PDO');
      return false;
    }
    return true;
  }

  function ce_generate_code($email) {
    global $pdo;
    global $email_regex;
    global $the_php_mailer;
    // check email valid
    if(!preg_match($email_regex, $email)) {
      return false;
    }
    // check email is free
    try {
      $stmt = $pdo->prepare("SELECT count FROM emails WHERE email = ?");
      $stmt->execute([$email]);
      $emails_total = $stmt->fetchColumn();
      if(empty($emails_total)) $emails_total = 0;
      if($emails_total >= 1) return 'EMAIL_LIMIT.';
    }
    catch(Exception $e) {
      return false;
    }
    // generate code
    $code = gen_token(8);
    $_SESSION['ce_code'] = $code;
    $_SESSION['ce_email'] = $email;
    // send email
    $mail_date = date("d.m.Y в G:i");
    $mail_text = "Ваш адрес был указан как основной на сайте Study Buddy ";
    $base64_logo = insert_base64_encoded_image_src('../media/svg/logo.svg', true);
    $mail_body = "<!DOCTYPE html><html lang=\"ru\" dir=\"ltr\"><head><meta charset=\"utf-8\"><style>body{padding:0;margin:0;width:100%;font-family:monospace}.main{width:calc(100% - 34px);padding:10px;border:2px solid #2a9fd0;border-radius:10px;background-color:#e6e8fc6b;margin:5px}.main-logo{width:100%}.main-logo-ico{display:inline-block;vertical-align:middle;position:relative;height:50px;width:200px;margin-left:15px;background-position:center;background-repeat:no-repeat;background-size:contain}.main-text-title{font-size:25px;font-family:monospace;font-weight:700;margin-left:15px;margin-top:10px}.main-text-text{font-family:pfl;margin-left:15px;margin-top:10px;text-align:justify;margin-right:15px}.main-text-a{font-family:monospace;font-weight:100;margin-left:15px;margin-top:10px;display:inline-block;text-align:justify;margin-right:15px;border-bottom:1px dashed #303036;color:#303036;text-decoration:none}.main-text-a:hover{text-decoration:none;border-bottom:1px solid #303036}.main-text-auto{font-family:monospace;font-weight:100;margin-left:15px;margin-top:10px;text-align:justify;margin-right:15px;font-style:italic}.main-text-password{display:inline-block;padding:5px 10px;background-color:#2a9fd0;margin-left:15px;font-family:monospace;font-weight:100;color:#fff;border-radius:5px;margin-top:5px}</style></head><body><div class='main'><div class='main-logo'><img class='main-logo-ico' src=\"$base64_logo\"></img></div><div class='main-text'><div class='main-text-title'>Добро пожаловать!</div><div class='main-text-text'>$mail_text</div><br><div class='main-text-text'>Ваш код для подтверждения:</div><div class='main-text-password'>$code</div><br><br><div class='main-text-auto'>Это письмо сформировано автоматически. Пожалуйста, не отвечайте на него.</div><div class='main-text-text' style='margin-bottom: 15px; margin-top: 25px;'><span>Дата составления письма: </span><span><b>$mail_date</b></span></div></div></div></body></html>";
    $the_php_mailer->addAddress($email, '');
    $the_php_mailer->isHTML(true);
    $the_php_mailer->Subject = 'Код подтверждения Study Buddy';
    $the_php_mailer->Body = $mail_body;
    if(!$the_php_mailer->send()) {
      debuglog('mailing');
      return false;
    }
    return true;
  }

  function change_email($code) {
    global $pdo;
    // check valid
    if((mb_strlen($code) != 16) || !isset($_SESSION['ce_code']) || !isset($_SESSION['ce_email'])) {
      return false;
    }
    // check code
    if($code != $_SESSION['ce_code']) {
      return false;
    }
    // update email
    $id = $_SESSION['userid'];
    $email = $_SESSION['ce_email'];
    try {
      // get old email
      $stmt = $pdo->prepare("SELECT email FROM accounts WHERE account_id = ?");
      $stmt->execute([$id]);
      $founded = $stmt->fetch();
      if(empty($founded) || !isset($founded['email'])) {
        return false;
      }
      $old_email = $founded['email'];
      // set new email in accounts table
      $stmt = $pdo->prepare("UPDATE `accounts` SET `email` = :email WHERE `account_id` = :account_id");
      $stmt->execute(Array(
        ':email' => $email,
        ':account_id' => $id
      ));
      // set new email in emails table
      $stmt = $pdo->prepare("UPDATE `emails` SET `email` = :new_email WHERE `email` = :old_email");
      $stmt->execute(Array(
        ':new_email' => $email,
        ':old_email' => $old_email
      ));
    }
    catch(Exception $e) {
      debuglog('PDO');
      return false;
    }
    // ok
    unset($_SESSION['ce_code']);
    unset($_SESSION['ce_email']);
    return true;
  }

  function check_session_timer($timer_name, $time_limit) {
    $current_time = time();
    // get timer from session
    if(isset($_SESSION['timer_'.$timer_name])) {
      $timer_time = intval($_SESSION['timer_'.$timer_name]);
      // check session timer
      if(($current_time - $timer_time) > $time_limit) return true;
      else return false;
    }
    else {
      $_SESSION['timer_'.$timer_name] = $current_time;
      return true;
    }
  }

  function check_db_timer($timer_name, $timer_limit) {
    global $sql_ap;
    global $pdo_options;
    // connect to Admin Panel
    $pdo_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
    $pdo_ap = new PDO($pdo_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);
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
      debuglog('ERROR IN FILE '.__FILE__.' IN METHOD '.__FUNCTION__.' IN LINE '.__LINE__.' EXCEPTION: ');
      debuglog($e);
      return false;
    }
    $is_empty = true;
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      $is_empty = false;
      if(isset($row['the_time'])) {
        $timer_exists = true;
        $db_timer->setTimestamp(strtotime($row['the_time']));
      }
      else {
        debuglog('ERROR IN FILE '.__FILE__.' IN METHOD '.__FUNCTION__.' IN LINE '.__LINE__.' EXCEPTION: ');
        debuglog($e);
        return false;
      }
    }
    if($is_empty) {
      $timer_exists = false;
    }
    if($timer_exists) {
      $difference = $now_timer->getTimestamp() - $db_timer->getTimestamp();
      if($difference < $timer_limit) {
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
      debuglog('ERROR IN FILE '.__FILE__.' IN METHOD '.__FUNCTION__.' IN LINE '.__LINE__.' EXCEPTION: ');
      debuglog($e);
      return false;
    }
    return true;
  }

  function check_timer($timer_name, $timer_limit) {
    return (check_session_timer($timer_name, $timer_limit) && check_db_timer($timer_name, $timer_limit));
  }

  function check_counter($counter_name, $count_limit) {
    global $sql_ap;
    global $pdo_options;
    // connect to Admin Panel
    $pdo_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
    $pdo_ap = new PDO($pdo_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);
    //
    $counter_exists = false;
    $current_count = 0;
    try {
      $stmt = $pdo_ap->prepare("SELECT the_count FROM counters WHERE counter = ?");
      $stmt->execute([$counter_name]);
    }
    catch(Exception $e) {
      debuglog('ERROR IN FILE '.__FILE__.' IN METHOD '.__FUNCTION__.' IN LINE '.__LINE__.' EXCEPTION: ');
      debuglog($e);
      return false;
    }
    $is_empty = true;
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      $is_empty = false;
      if(isset($row['the_count'])) {
        $counter_exists = true;
        $current_count = intval($row['the_count']);
      }
      else {
        debuglog('ERROR IN FILE '.__FILE__.' IN METHOD '.__FUNCTION__.' IN LINE '.__LINE__.' EXCEPTION: ');
        debuglog($e);
        return false;
      }
    }
    if($is_empty) {
      $counter_exists = false;
    }
    $current_count++;
    if($counter_exists) {
      if($current_count > $count_limit) {
        return false;
      }
    }
    // add counter
    try {
      $stmt = $pdo_ap->prepare("INSERT INTO `counters` (`counter`) VALUES (:countername) ON DUPLICATE KEY UPDATE `the_count` = :thecount");
      $stmt->execute(Array(
        ':countername' => $counter_name,
        ':thecount' => $current_count
      ));
    }
    catch(Exception $e) {
      debuglog('ERROR IN FILE '.__FILE__.' IN METHOD '.__FUNCTION__.' IN LINE '.__LINE__.' EXCEPTION: ');
      debuglog($e);
      return false;
    }
    return true;
  }

  function set_counter($counter_name, $value) {
    global $sql_ap;
    global $pdo_options;
    // connect to Admin Panel
    $pdo_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
    $pdo_ap = new PDO($pdo_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);
    // set
    try {
      $stmt = $pdo_ap->prepare("INSERT INTO `counters` (`counter`) VALUES (:countername) ON DUPLICATE KEY UPDATE `the_count` = :thecount");
      $stmt->execute(Array(
        ':countername' => $counter_name,
        ':thecount' => $value
      ));
    }
    catch(Exception $e) {
      debuglog('ERROR IN FILE '.__FILE__.' IN METHOD '.__FUNCTION__.' IN LINE '.__LINE__.' EXCEPTION: ');
      debuglog($e);
      return false;
    }
    return true;
  }

  function ban_ip($ip, $seconds) {
    global $sql_ap;
    global $pdo_options;
    // connect to Admin Panel
    $pdo_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
    $pdo_ap = new PDO($pdo_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);
    // add timer
    try {
      $stmt = $pdo_ap->prepare("INSERT INTO `banned_ip` (`ip`, `banned`, `ban_time`) VALUES (:ip, CURRENT_TIMESTAMP(), :ban_time1) ON DUPLICATE KEY UPDATE `banned` = CURRENT_TIMESTAMP(), `ban_time` = :ban_time2");
      $stmt->execute(Array(
        ':ip' => $ip,
        ':ban_time1' => $seconds,
        ':ban_time2' => $seconds
      ));
    }
    catch(Exception $e) {
      debuglog('ERROR IN FILE '.__FILE__.' IN METHOD '.__FUNCTION__.' IN LINE '.__LINE__.' EXCEPTION: ');
      debuglog($e);
      return false;
    }
  }

  function is_banned($ip) {
    global $sql_ap;
    global $pdo_options;
    // connect to Admin Panel
    $pdo_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
    $pdo_ap = new PDO($pdo_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);
    // check record
    try {
      $stmt = $pdo_ap->prepare("SELECT * FROM `banned_ip` WHERE `ip` LIKE ?");
      $stmt->execute([$ip]);
      $row = $stmt->fetch(PDO::FETCH_LAZY);
      if(empty($row)) return false;
    }
    catch(Exception $e) {
      debuglog('ERROR IN FILE '.__FILE__.' IN METHOD '.__FUNCTION__.' IN LINE '.__LINE__.' EXCEPTION: ');
      debuglog($e);
      return false;
    }
    // check time
    if((strtotime($row['banned']) + intval($row['ban_time'])) > time()) return true;
    else return false;
  }

  // === IP ban ================================================================

  if(is_banned($_SERVER['REMOTE_ADDR'])) {
    exit('BANNED.');
  }

  // === reCaptcha =============================================================

  function check_captcha($version) {
    global $captcha_url;
    global $captcha2_params;
    global $captcha3_params;
    $version = intval($version);
    $captcha_params = (($version == 3) || ($version == 31)) ? $captcha3_params : $captcha2_params;
    if($captcha_params['response'] === false) return false;
    // send POST request
    $ch = curl_init($captcha_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $captcha_params);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // recieve
    $response = curl_exec($ch);
    if(!empty($response)) $decoded_response = json_decode($response);
    // read
    if($version == 3) {
      if(isset($decoded_response) && property_exists($decoded_response, 'success') && ($decoded_response->success) && ($decoded_response->score > 0.6)) {
        return true;
      }
    }
    else if($version == 31) {
      if(isset($decoded_response) && property_exists($decoded_response, 'success') && ($decoded_response->success) && ($decoded_response->score > 0)) {
        return true;
      }
    }
    else {
      if(isset($decoded_response) && is_object($decoded_response) && property_exists($decoded_response, 'success') && ($decoded_response->success)) {
        return true;
      }
    }
    return false;
  }

  // === auth functions ========================================================

  if(isset($_POST['register_form'])) {
    if(!isset($_POST['login']) || !isset($_POST['name1']) || !isset($_POST['name2']) || !isset($_POST['name3']) || !isset($_POST['email']) || !isset($_POST['mailing'])) {
      exit('WRONG.');
    }
    $login = htmlspecialchars($_POST['login'], ENT_QUOTES);
    if(!preg_match($login_regex, $login)) {
      exit('LOGIN.');
    }
    $name1 = htmlspecialchars($_POST['name1'], ENT_QUOTES);
    if(!preg_match($name1_regex, $name1)) {
      exit('NAME1.');
    }
    $name2 = htmlspecialchars($_POST['name2'], ENT_QUOTES);
    if(!preg_match($name2_regex, $name2)) {
      exit('NAME2.');
    }
    $name3 = htmlspecialchars($_POST['name3'], ENT_QUOTES);
    if(!preg_match($name3_regex, $name3)) {
      exit('NAME3.');
    }
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
    if(!preg_match($email_regex, $email)) {
      exit('EMAIL.');
    }
    $mailing = (htmlspecialchars($_POST['mailing'], ENT_QUOTES) == 'true') ? 1 : 0;
    // check captcha stage 1
    $captcha_success = check_captcha(3);
    if($captcha_success !== false) {
      // register account
      $status = register_new($login, $name1, $name2, $name3, $email, $mailing);
      if($status !== false) {
        exit('OK.');
      }
      else {
        exit('ERROR.');
      }
    }
    else {
      $_SESSION['reg_login'] = $login;
      $_SESSION['reg_name1'] = $name1;
      $_SESSION['reg_name2'] = $name2;
      $_SESSION['reg_name3'] = $name3;
      $_SESSION['reg_email'] = $email;
      $_SESSION['reg_mailing'] = $mailing;
      exit('CAPTCHA.');
    }
  }

  // ===========================================================================

  if(isset($_POST['register_form_2'])) {
    // check captcha stage 2
    $captcha_success = check_captcha(2);
    if($captcha_success !== false) {
      // get data
      if(isset($_SESSION['reg_login'])) $login = $_SESSION['reg_login'];
      else exit('WRONG.'.__LINE__);
      if(isset($_SESSION['reg_name1'])) $name1 = $_SESSION['reg_name1'];
      else exit('WRONG.'.__LINE__);
      if(isset($_SESSION['reg_name2'])) $name2 = $_SESSION['reg_name2'];
      else exit('WRONG.'.__LINE__);
      if(isset($_SESSION['reg_name3'])) $name3 = $_SESSION['reg_name3'];
      else exit('WRONG.'.__LINE__);
      if(isset($_SESSION['reg_email'])) $email = $_SESSION['reg_email'];
      else exit('WRONG.'.__LINE__);
      if(isset($_SESSION['reg_mailing'])) $mailing = $_SESSION['reg_mailing'];
      else exit('WRONG.'.__LINE__);
      // remove
      unset($_SESSION['reg_login']);
      unset($_SESSION['reg_name1']);
      unset($_SESSION['reg_name2']);
      unset($_SESSION['reg_name3']);
      unset($_SESSION['reg_email']);
      unset($_SESSION['reg_mailing']);
      // register account
      $status = register_new($login, $name1, $name2, $name3, $email, $mailing);
      if($status !== false) {
        exit('OK.');
      }
      else {
        exit('ERROR.');
      }
    }
    else {
      exit('CAPTCHA.');
    }
  }

  // ===========================================================================

  if(isset($_POST['login_form'])) {
    if(!isset($_POST['login']) || !isset($_POST['password'])) {
      exit('WRONG.');
    }
    $login = htmlspecialchars($_POST['login'], ENT_QUOTES);
    if(!preg_match($login_regex, $login)) {
      exit('LOGIN.');
    }
    $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
    if(!preg_match($password_regex, $password)) {
      exit('PASSWORD.');
    }
    $captcha_success = check_captcha(31);
    if($captcha_success !== false) {
      // register account
      $status = log_in($login, $password);
      if($status !== false) {
        $referer = 'none';
        if(isset($_SESSION['referer'])) $referer = $_SESSION['referer'];
        echo('OK.');
        if($referer != 'none') {
          echo('REF.');
          echo($referer);
        }
        exit();
      }
      else {
        exit('ERROR.');
      }
    }
    else {
      exit('CAPTCHA.');
    }
  }

  // ===========================================================================

  if(isset($_POST['log_out']) || isset($_GET['log_out'])) {
    if(!isset($_SESSION['auth'])) exit('AUTH.');
    if(isset($_POST['log_out'])) $token = htmlspecialchars($_POST['log_out'], ENT_QUOTES);
    if(isset($_GET['log_out'])) $token = htmlspecialchars($_GET['log_out'], ENT_QUOTES);
    if(mb_strlen($token) > 64) exit();
    if(log_out($token)) {
      if(isset($_GET['log_out'])) { header('Location: ../register'); }
      if(isset($_POST['log_out'])) { exit('OK.'); }
    }
    else {
      exit();
    }
  }

  // ===========================================================================

  if(isset($_POST['profile_form'])) {
    if(!isset($_SESSION['auth'])) exit('AUTH.');
    // name1
    if(isset($_POST['name1'])) {
      $name1 = htmlspecialchars($_POST['name1'], ENT_QUOTES);
      if(!preg_match($name1_regex, $name1)) { exit('NAME1.'); }
    }
    else { exit('NAME1.'); }
    // name2
    if(isset($_POST['name2'])) {
      $name2 = htmlspecialchars($_POST['name2'], ENT_QUOTES);
      if(!preg_match($name2_regex, $name2)) { exit('NAME2.'); }
    }
    else { exit('NAME2.'); }
    // name3
    if(isset($_POST['name3'])) {
      $name3 = htmlspecialchars($_POST['name3'], ENT_QUOTES);
      if(!preg_match($name3_regex, $name3)) { exit('NAME3.'); }
    }
    else { exit('NAME3.'); }
    // phone
    if(isset($_POST['phone'])) {
      $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES);
      if(mb_strlen($phone) > 0) {
        if(!preg_match($phone_regex, $phone)) { exit('PHONE.'); }
      }
    }
    else { exit('PHONE.'); }
    // country
    if(isset($_POST['country'])) {
      $country = htmlspecialchars($_POST['country'], ENT_QUOTES);
      if(!in_array($country, $_ISO_RU)) { exit('COUNTRY.'); }
    }
    else { exit('COUNTRY.'); }
    // city
    if(isset($_POST['city'])) {
      $city = htmlspecialchars($_POST['city'], ENT_QUOTES);
      if(!preg_match($name1_regex, $city)) { exit('CITY.'); }
    }
    else { exit('CITY.'); }
    // gender
    if(isset($_POST['gender'])) {
      $gender = htmlspecialchars($_POST['gender'], ENT_QUOTES);
      $gender = ($gender == 'male') ? 'male' : 'female';
    }
    else { exit('GENDER.'); }
    // request
    $account = $_SESSION['userid'];
    try {
      // add record to db
      $stmt = $pdo->prepare("UPDATE `accounts` SET `name1` = :name1, `name2` = :name2, `name3` = :name3, `country` = :country, `city` = :city, `phone` = :phone, `gender` = :gender WHERE `account_id` = :account_id");
      $stmt->execute(Array(
        ':account_id' => $account,
        ':name1' => $name1,
        ':name2' => $name2,
        ':name3' => $name3,
        ':country' => $country,
        ':city' => $city,
        ':phone' => $phone,
        ':gender' => $gender
      ));
    }
    catch(Exception $e) {
      debuglog();
      exit('ERROR.');
    }
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['ce_code_form'])) {
    if(!isset($_SESSION['auth'])) exit('AUTH.');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES);
    $status = ce_generate_code($email);
    if($status === true) {
      exit('OK.');
    }
    else if($status == false) {
      exit('ERROR.');
    }
    else {
      exit($status);
    }
  }

  // ===========================================================================

  if(isset($_POST['change_email_form'])) {
    if(!isset($_SESSION['auth'])) exit('AUTH.');
    if(!isset($_POST['code'])) {
      exit('WRONG.');
    }
    $code = htmlspecialchars($_POST['code'], ENT_QUOTES);
    $status = change_email($code);
    if($status === true) {
      exit('OK.');
    }
    else {
      exit('WRONG.');
    }
  }

  // ===========================================================================

  if(isset($_POST['change_password_form'])) {
    if(!isset($_SESSION['auth'])) exit('AUTH.');
    if(!isset($_POST['old_password']) || !isset($_POST['new_password'])) {
      exit('WRONG.');
    }
    $old_password = htmlspecialchars($_POST['old_password'], ENT_QUOTES);
    $new_password = htmlspecialchars($_POST['new_password'], ENT_QUOTES);
    $status = change_password($_SESSION['userid'], $old_password, $new_password);
    if($status === true) {
      exit('OK.');
    }
    else {
      exit('WRONG.');
    }
  }

  // ===========================================================================

  if(isset($_POST['change_icon'])) {
    if(!isset($_SESSION['auth'])) exit('AUTH.');
    // check file corruption
    if (!isset($_FILES[0]['error']) || is_array($_FILES[0]['error'])) {
      exit('INVALID_PARAMETERS.');
    }
    switch($_FILES[0]['error']) {
      case UPLOAD_ERR_OK:
      break;
      case UPLOAD_ERR_NO_FILE:
      exit('NO_FILE.');
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
      exit('LIMIT.');
      default:
      exit('UND_ERROR.');
    }
    // check file size 10MB
    if ($_FILES[0]['size'] > 10485760) {
      exit('LIMIT.');
    }
    $image_file = $_FILES[0]['tmp_name'];
    // check mime
    try {
      $mime = mime_content_type($image_file);
      if($mime != 'image/png' && $mime != 'image/jpeg') {
        exit('MIME.');
      }
    }
    catch(Exception $e) {
      exit('ERROR.');
    }
    // SimpleImage
    require_once('SimpleImage.php');
    // open
    $image = new SimpleImage($image_file);
    // square
    $image->square(110);
    $tmp_name = 'avatar.png';
    $user_dir = '../users/public/'.$_SESSION['login'];
    $tmp_icon = "$user_dir/$tmp_name";
    // remove old file
    if(file_exists($tmp_icon)) {
      unlink($tmp_icon);
    }
    // save new file
    try {
      $image->save($tmp_icon);
    }
    catch(Exception $e) {
      exit('ERROR.');
    }
    // save to db
    try {
      $stmt = $pdo->prepare("UPDATE `accounts` SET `profile_icon` = 'TRUE' WHERE `account_id` = ?");
      $stmt->execute([$_SESSION['userid']]);
    }
    catch(Exception $e) {
      exit('ERROR.');
    }
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['recovery_get_code'])) {
    // check captcha
    $captcha_success = check_captcha(3);
    // prepare
    if(!isset($_POST['rec_input_val']) || !isset($_POST['rec_input_type'])) exit('WRONG.');
    $value = htmlspecialchars($_POST['rec_input_val'], ENT_QUOTES);
    $type = htmlspecialchars($_POST['rec_input_type'], ENT_QUOTES);
    if($type != 'login' && $type != 'email') exit('WRONG.');
    // check login
    if($type == 'login' && !preg_match($login_regex, $value)) exit('WRONG.');
    if($type == 'email' && !preg_match($email_regex, $value)) exit('WRONG.');
    // get data
    $query_param = ($type == 'email') ? 'email' : 'account';
    try {
      $stmt = $pdo->prepare("SELECT `account_id`, `account`, `name1`, `email` FROM `accounts` WHERE $query_param LIKE :value LIMIT 1");
      $stmt->execute(Array(':value' => $value));
      $data = $stmt->fetch(PDO::FETCH_LAZY);
      if(empty($data)) {
        exit('EMPTY.');
      }
    }
    catch(Exception $e) {
      debuglog('ERROR IN FILE '.__FILE__.' IN METHOD '.__FUNCTION__.' IN LINE '.__LINE__.' EXCEPTION: ');
      debuglog($e);
      exit('ERROR.');
    }
    // check timer
    $timer = check_timer('SITE_REC_IP_'.sha1($_SERVER['REMOTE_ADDR']), 90);
    if(!$timer) exit('TIME.');
    // generate code
    $code = gen_token(8);
    // write in session
    $_SESSION['recovery_id'] = $data['account_id'];
    $_SESSION['recovery_code'] = $code;
    $_SESSION['recovery_granted'] = false;
    // send mail
    $account = $data['account'];
    $email = $data['email'];
    $name = $data['name1'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $city = get_city_by_ip($ip, 'ru');
    $country = get_country_by_ip($ip, 'ru');
    $mail_date = date("d.m.Y в G:i");
    $mail_link = 'insoweb.ru';
    $base64_logo = insert_base64_encoded_image_src('../media/svg/logo.svg', true);
    $mail_body = "<!DOCTYPE html><html lang='ru' dir='ltr'> <head> <meta charset='utf-8'> <style>body{padding:0;margin:0;width:100%;font-family:monospace}.main{width:calc(100% - 34px);padding:10px;border:2px solid #2a9fd0;border-radius:10px;background-color:#e6e8fc6b;margin:5px}.main-logo{width:100%}.main-logo-ico{display:inline-block;vertical-align:middle;position:relative;height:50px;width:200px;margin-left:15px;background-position:center;background-repeat:no-repeat;background-size:contain}.main-text-title{font-size:25px;font-family:monospace;font-weight:700;margin-left:15px;margin-top:10px}.main-text-text{font-family:pfl;margin-left:15px;margin-top:10px;text-align:justify;margin-right:15px}.main-text-a{font-family:monospace;font-weight:100;margin-left:15px;margin-top:10px;display:inline-block;text-align:justify;margin-right:15px;border-bottom:1px dashed #303036;color:#303036;text-decoration:none}.main-text-a:hover{text-decoration:none;border-bottom:1px solid #303036}.main-text-auto{font-family:monospace;font-weight:100;margin-left:15px;margin-top:10px;text-align:justify;margin-right:15px;font-style:italic}.main-text-password{display:inline-block;padding:5px 10px;background-color:#2a9fd0;margin-left:15px;font-family:monospace;font-weight:100;color:#fff;border-radius:5px;margin-top:5px}</style> </head> <body> <div class='main'> <div class='main-logo'><img class='main-logo-ico' src='$base64_logo'></img></div><div class='main-text'> <div class='main-text-title'>Восстановления аккаунта $account</div><div class='main-text-text'>$name, для Вашего аккаунта был запрошен код восстановления</div><br><div class='main-text-text'>Ваш проверочный код: </div><div class='main-text-password'>$code</div><br><br><div class='main-text-text'>Код был запрошен с IP-адреса $ip</div><div class='main-text-text'>Страна: $country</div><div class='main-text-text'>Город: $city</div><br><br><a href='$mail_link' target='_blank' class='main-text-a'>Перейти на сайт StudyBuddy</a> <br><br><div class='main-text-auto'>Это письмо сформировано автоматически. Пожалуйста, не отвечайте на него.</div><div class='main-text-text' style='margin-bottom: 15px; margin-top: 25px;'><span>Дата составления письма: </span><span><b>$mail_date</b></span></div></div></div></body></html>";
    $the_php_mailer->addAddress($email, $name);
    $the_php_mailer->isHTML(true);
    $the_php_mailer->Subject = 'Код восстановления Study Buddy';
    $the_php_mailer->Body = $mail_body;
    if(!$the_php_mailer->send()) {
      debuglog('mailing, line:'.__LINE__);
      exit('ERROR.');
    }
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['recovery_check_code'])) {
    // check captcha
    $captcha_success = check_captcha(3);
    // prepare
    if(!isset($_POST['rec_code']) || !isset($_SESSION['recovery_code']) || !isset($_SESSION['recovery_id'])) exit('WRONG.');
    $code = htmlspecialchars($_POST['rec_code'], ENT_QUOTES);
    // check counter
    $counter = check_counter('SITE_RCCODE_IP_'.sha1($_SERVER['REMOTE_ADDR']), 10);
    // ban by ip
    if(!$counter) {
      ban_ip($_SERVER['REMOTE_ADDR'], 1800);
      set_counter('SITE_RCCODE_IP_'.sha1($_SERVER['REMOTE_ADDR']), 0);
      exit('BAN.');
    }
    // check code
    if($_SESSION['recovery_code'] != $code) {
      exit('WRONG.');
    }
    $_SESSION['recovery_granted'] = true;
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['recovery_change_pass'])) {
    // prepare
    if(!isset($_SESSION['recovery_code']) || !isset($_POST['rec_password']) || !isset($_SESSION['recovery_id'])) exit('WRONG.');
    if($_SESSION['recovery_granted'] !== true) exit('WRONG.');
    // check password
    $password = htmlspecialchars($_POST['rec_password'], ENT_QUOTES);
    if(!preg_match($password_regex, $password)) exit('WRONG.');
    // save new password
    $password_enc = password_hash($password, PASSWORD_BCRYPT);
    try {
      $stmt = $pdo->prepare("UPDATE `accounts` SET `password` = :password WHERE `account_id` = :account_id");
      $stmt->execute(Array(
        ':password' => $password_enc,
        ':account_id' => $_SESSION['recovery_id']
      ));
    }
    catch(Exception $e) {
      debuglog('ERROR IN FILE '.__FILE__.' IN METHOD '.__FUNCTION__.' IN LINE '.__LINE__.' EXCEPTION: ');
      debuglog($e);
      exit('ERROR.');
    }
    $_SESSION['recovery_granted'] = false;
    set_counter('SITE_RCCODE_IP_'.sha1($_SERVER['REMOTE_ADDR']), 0);
    exit('OK.');
  }

  // ===========================================================================

  exit();

?>