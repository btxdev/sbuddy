<?php

  /*
   *  Swiftly Admin Panel v1.12 alpha
   *  (c) 2019-2020 INSOweb  <http://insoweb.ru>
   *  All rights reserved.
   */

  if(isset($_GET['file_version'])) {
    exit('db_login VERSION: 63');
  }

  if(empty($_POST)) {
    exit('EMPTY.');
  }

  // == setup ==================================================================

  include_once('db_includes.php');

  session_name($sess_name);
  session_start();

  // create guest session
  if(!isset($_SESSION)) {
    $_SESSION['auth'] = false;
  }

  // if already authorized
  if(isset($_SESSION['auth']) && $_SESSION['auth']) {
    if(!isset($_POST['exitacc'])) { // exception
      exit('AUTHORIZED.');
    }
  }

  // == parameters =============================================================

  $recovery_time_limit = 180;

  // == mailing ================================================================

  require_once('php/lib_php/phpmailerNew/PHPMailerAutoload.php');
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

  // == login functions ========================================================

  // destroy session
  function remove_user_session() {
    $_SESSION = Array();
    session_destroy();
  }

  // activity token
  function gen_token($username) {
    return md5(rand(0, 32767).$username);
  }

  // create user session
  function create_user_session($username, $userid, $userlvl) {
    // define globals
    global $sess_name;
    global $sess_v;
    // destroy session
    $_SESSION = Array();
    // create user session
    $_SESSION['auth'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['userid'] = $userid;
    $_SESSION['userlvl'] = $userlvl;
    $_SESSION['act_token'] = gen_token($username);
    $_SESSION['version'] = $sess_v;
  }

  // === IP ban ================================================================

  if(is_banned($_SERVER['REMOTE_ADDR'])) {
    exit('BANNED.');
  }

  // == server requests ========================================================

  // register new account
  if(isset($_POST['regacc'])) {

    // check for availability
    if(!isset($_POST['f1']) || !isset($_POST['f2']) || !isset($_POST['f3']) || !isset($_POST['f4']) || !isset($_POST['f5']) || !isset($_POST['f6']) || !isset($_POST['f7'])) {
      echo('WRONG.');
      // log illegal activity
      send_log(0, 'suspect', array_merge(Array('description' => 'подмена полей на этапе регистрации'), prepare_client_data()));
      exit();
    }

    // re-initialize and pre-formatting
    $login = htmlspecialchars($_POST['f1'], ENT_QUOTES);
    $password = htmlspecialchars($_POST['f2'], ENT_QUOTES);
    $name = htmlspecialchars($_POST['f3'], ENT_QUOTES);
    $email = htmlspecialchars($_POST['f4'], ENT_QUOTES);
    $phone = htmlspecialchars($_POST['f5'], ENT_QUOTES);
    $terms = htmlspecialchars($_POST['f6'], ENT_QUOTES);
    $mailing = htmlspecialchars($_POST['f7'], ENT_QUOTES);

    // final validation
    if(!preg_match($login_regex, $login)) { exit('LOGIN.'); }
    if(!preg_match($password_regex, $password)) { exit('PASSWORD.'); }
    if(!preg_match($name_regex, $name)) { exit('NAME.'); }
    if(!preg_match($email_regex, $email)) { exit('EMAIL.'); }
    if(!preg_match($phone_regex, $phone)) { exit('PHONENUMBER.'); }
    if($terms != 'true') { exit('TERMS.'); }
    $mailing = ($mailing == 'true') ? 1 : 0;

    // check email is free (max 1 emails)
    $stmt = $pdo->prepare('SELECT count FROM emails WHERE email=?'); if(!$stmt) { echo('ERROR_PREPARE.'); send_log(0, 'error', array_merge(Array('description' => 'PDO prepare REGISTER 189 line'), prepare_client_data())); exit(); }
    $exec_status = $stmt->execute([$email]); if(!$exec_status) { echo('ERROR_EXECUTE.'); send_log(0, 'error', array_merge(Array('description' => 'PDO execute REGISTER 197 line'), prepare_client_data())); exit(); }
    $emails_total = $stmt->fetchColumn();
    if(empty($emails_total)) {
      $emails_total = 0;
    }
    if($emails_total >= $account_emails_limit) {
      exit('EMAIL_LIMIT.');
    }

    // check phonenumber is free (max 3 phonenumbers)
    $stmt = $pdo->prepare('SELECT count FROM phonenumbers WHERE phone=?'); if(!$stmt) { echo('ERROR_PREPARE.'); send_log(0, 'error', array_merge(Array('description' => 'PDO prepare REGISTER 200 line'), prepare_client_data())); exit(); }
    $exec_status = $stmt->execute([$phone]); if(!$exec_status) { echo('ERROR_EXECUTE.'); send_log(0, 'error', array_merge(Array('description' => 'PDO execute REGISTER 201 line'), prepare_client_data())); exit(); }
    $phones_total = $stmt->fetchColumn();
    if(empty($phones_total)) {
      $phones_total = 0;
    }
    if($phones_total >= $account_phonenumbers_limit) {
      exit('PHONE_LIMIT.');
    }

    // check login exists
    $stmt = $pdo->prepare('SELECT EXISTS (SELECT * FROM accounts WHERE account = ?)');
    $stmt->execute([$login]);
    // parse
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      if($row[0] > 0) {
        // log to db
        send_log(0, 'suspect', array_merge(Array('description' => 'возможный скан логинов'), prepare_client_data()));
        exit('LOGIN_EXISTS.');
      }
    }

    // encrypt
    $pass_enc = password_hash($password, PASSWORD_BCRYPT);

    // random profile icon
    $the_default_icon = 'DEFAULT_'.strval(rand(0, ($profile_photos_count - 1)));

    // user level
    $user_level = 'default';

    // city and country
    $city = get_city_by_ip($_SERVER['REMOTE_ADDR'], 'ru');
    $country = get_country_by_ip($_SERVER['REMOTE_ADDR'], 'ru');

    // current date
    $reg_date = date('Y-m-d');

    // create new account
    $stmt = $pdo->prepare('INSERT INTO `accounts` (`account`, `password`, `access_type`, `first_name`, `reg_date`, `country`, `city`, `phonenumber`, `email`, `email_verify`, `mailing`, `profile_icon`) VALUES (:account, :password, :access_type, :first_name, :reg_date, :country, :city, :phonenumber, :email, :email_verify, :mailing, :profile_icon)');
    $stmt->execute(Array(
      ':account' => $login,
      ':password' => $pass_enc,
      ':access_type' => $user_level,
      ':first_name' => $name,
      ':reg_date' => $reg_date,
      ':country' => $country,
      ':city' => $city,
      ':phonenumber' => $phone,
      ':email' => $email,
      ':email_verify' => 0,
      ':mailing' => $mailing,
      ':profile_icon' => $the_default_icon
    ));

    // check result and get id
    $stmt = $pdo->prepare('SELECT account_id FROM accounts WHERE account=?');
    $stmt->execute([$login]);
    $user_id = $stmt->fetchColumn();

    // set emails count
    if($emails_total > 0) {
      $emails_total++;
      $stmt = $pdo->prepare('UPDATE `emails` SET `count`=:count WHERE `email`=:email');
      $stmt->execute(Array(
        ':count' => $emails_total,
        ':email' => $email
      ));
    }
    else {
      $emails_total = 1;
      $stmt = $pdo->prepare('INSERT INTO `emails` (`email`, `count`) VALUES (:email, :count)');
      $stmt->execute(Array(
        ':email' => $email,
        ':count' => $emails_total
      ));
    }

    // set phones count
    if($phones_total > 0) {
      $phones_total++;
      $stmt = $pdo->prepare('UPDATE `phonenumbers` SET `count`=:count WHERE `phone`=:phone');
      $stmt->execute(Array(
        ':count' => $phones_total,
        ':phone' => $phone
      ));
    }
    else {
      $phones_total = 1;
      $stmt = $pdo->prepare('INSERT INTO `phonenumbers` (`phone`, `count`) VALUES (:phone, :count)');
      $stmt->execute(Array(
        ':phone' => $phone,
        ':count' => $phones_total
      ));
    }

    // grant access
    create_user_session($login, $user_id, $user_level);

    echo('GRANTED.');

    // log to db
    send_log($user_id, 'account', array_merge(Array('description' => 'registered new account'), prepare_client_data()));

    // generate hash for email confirm
    $hash = hash('sha256', random_int(10000, 99999).$login.$email);
    // write to db
    // ...

    // send mail
    $the_php_mailer->addAddress($email, $name);
    $the_php_mailer->isHTML(true);
    $the_php_mailer->Subject = 'Добро пожаловать в INSO Admin Panel';
    $the_php_mailer->Body = "<!DOCTYPE html><html lang='ru' dir='ltr'><head><meta charset='utf-8'><style> a{text-decoration: none;color: #303036;} ::selection{background-color: #ff970840;} .tel{border-bottom: 1px solid transparent;transition: 0.25s border-bottom;color: #303036;} .tel:hover{color: #303036;border-bottom: 1px dashed #303036;} .fotter-a{border-bottom: 1px solid transparent;transition: 0.25s border-bottom;color: #303036;} .fotter-a:hover{border-bottom: 1px dashed #303036;color: #303036;} .footera:hover{color: #303036;border-bottom: 1px dashed #303036;} </style><link href='https://fonts.googleapis.com/css?family=Quicksand:300,400,500,600,700|Roboto:300,400,500,700,900&display=swap&subset=latin-ext' rel='stylesheet'></head><body style='padding: 0;margin: 0;font-family: Quicksand, sans-serif;color: #303036;'><div class='nav' style='height: 75px; width: 120px; margin-top: 30px; position: relative; margin-left: 30px; background-position: center; background-repeat: no-repeat; background-size: contain; white-space: nowrap; transform: scale(1); user-select: none;'><div style='background-image: url(http://insoweb.ru/mail/logo/cloudlyAPLogo.png); background-repeat: no-repeat; width: 80px; height: 69px; background-size: contain; display: inline-block; vertical-align: middle;'></div><div class='nav-text' style='display: inline-block; vertical-align: middle; font-size: 40px; font-weight: 700; color: #303036; line-height: 25px;'><hb><a style='color: #303036;' href='http://insoweb.ru/swiftly' target='_blank'>Swiftly</a></hb><br><div class='logo-title-preloader-2' style='font-size: 22.8px; line-height: 35px;'>admin panel</div></div></div><div style='padding-left: 30px; padding-right: 30px; padding-top: 10px; border: 1px solid #303036; padding-bottom: 50px; background-color: #fff; border-radius: 15px; margin-left: 35px; margin-top: 35px; margin-right: 35px; margin-bottom: 25px; box-shadow: 0 0 13px 0 rgba(82, 63, 105, 0.15);'><div class='title' style='font-family: Roboto ,sans-serif; margin-left: 35px; font-size: 25px; margin-top: 30px; font-weight: 700;'>Добро пожаловать, ".$name."</div><div style='margin-left: 50px; margin-top: 30px; font-family: Roboto,sans-serif;'> Добро пожаловать в Swiftly Admin Panel<br><br>Для подтверждения аккаунта перейдите по ссылке <b><a href='http://insoweb.ru/email_confirm?id=".$hash."' target='_blank'>подтвердить ваш эл. адрес</a></b><br><br>Если аккаунт пренадлежит не вам, то проигнорируйте это письмо<br>Либо перейдите по ссылке, и аккаунт с вашей почтой будет заморожен: <b><a href='http://insoweb.ru/email_confirm?block=".$hash."' target='_blank'>я не регистрировал этот аккаунт</a></b><br><br><br>Если у вас есть вопросы, пожалуйста, напишите нам в службу поддержки: <b><a href='mailto:support@insoweb.ru'>support@insoweb.ru</a></b></div></div><div class='footer' style='font-weight: 700; margin-left: 65px; opacity: 0.5; line-height: 25px; display: block; margin-top: 40px;'>Автоматическое сообщение</div><div class='footer2' style='margin-top: 15px; margin-left: 65px; opacity: 0.5; line-height: 15px;'>С условием обработки персональных<br>данных можно ознакомиться <a class='fotter-a' href='#'>здесь</a>.</div><a href='http://insoweb.ru/' target='_blank' class='footera' style='color: #303036; font-weight: 500; font-family: Roboto,sans-serif; margin-left: 65px; margin-top: 35px; font-size: 16px; margin-bottom: 35px; opacity: 0.5; display: inline-block; transition: 0.25s border-bottom; border-bottom: 1px solid transparent;'>© INSOweb</a></body></html>";
    if(!$the_php_mailer->send()) {
      send_log(0, 'error', array_merge(Array('description' => $the_php_mailer->ErrorInfo), prepare_client_data()));
    }

    exit();

  }

  // login into account
  if(isset($_POST['logacc'])) {

    // check for availability
    if(!isset($_POST['f1']) || !isset($_POST['f2'])) {
      echo('WRONG.');
      // log illegal activity
      send_log(0, 'suspect', array_merge(Array('description' => 'подмена полей на этапе авторизации'), prepare_client_data()));
      exit();
    }

    // re-initialize and pre-formatting
    $login = htmlspecialchars($_POST['f1'], ENT_QUOTES);
    $password = htmlspecialchars($_POST['f2'], ENT_QUOTES);
    $user_id = -1;
    $user_level = 'undefined';
    $is_new_user = true;
    $need_update_icon = false;

    // final validation
    if(!preg_match($login_regex, $login)) { exit('LOGIN.'); }
    if(!preg_match($password_regex, $password)) { exit('PASSWORD.'); }

    // get login, user_id, user_level, salt, hash, second_name, birthday, profile_icon
    $access_granted = false;
    $stmt = $pdo->prepare('SELECT account_id, password, access_type, second_name, birthday, profile_icon FROM accounts WHERE account = ?');
    $stmt->execute([$login]);

    // parse
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      // check user exists
      if($row[0] < 1) {
        send_log(0, 'suspect', array_merge(Array('description' => 'возможный скан логинов'), prepare_client_data()));
        exit('NOT_EXISTS.');
      }

      // check password
      if(isset($row['account_id']) && isset($row['password'])) {
        // get data from db
        $pass_enc = $row['password'];
        $user_id = $row['account_id'];
        // password check
        if(password_verify($password, $pass_enc)) {
          $access_granted = true;
        }
        else {
          // ban by ip
          if(!check_counter('AP_CLOGIN_IP_'.sha1($_SERVER['REMOTE_ADDR']), 10)) {
            ban_ip($_SERVER['REMOTE_ADDR'], 1800);
            set_counter('AP_CLOGIN_IP_'.sha1($_SERVER['REMOTE_ADDR']), 0);
          }
          send_log($user_id, 'suspect', array_merge(Array('description' => 'возможный перебор паролей'), prepare_client_data()));
          exit('WRONG_PASSWORD.');
        }
      }
      else {
        // error log
        echo('ERROR.'); send_log(0, 'error', array_merge(Array('description' => 'UNDEFINED ERROR 376 line'), prepare_client_data())); exit();
      }

      // check profile_icon
      if(!isset($row['profile_icon'])) {
        $need_update_icon = true;
      }

      // get access level
      if(!isset($row['access_type'])) {
        // error log
        echo('ERROR.'); send_log(0, 'error', array_merge(Array('description' => 'UNDEFINED ERROR 389 line'), prepare_client_data())); exit();
      }
      else {
        $user_level = $row['access_type'];
      }

    }

    if(!$access_granted) {
      send_log(0, 'suspect', array_merge(Array('description' => 'возможный скан логинов'), prepare_client_data()));
      exit('NOT_EXISTS.');
    }

    // update icon
    $the_default_icon = 'DEFAULT_'.strval(rand(0, ($profile_photos_count - 1)));
    if($need_update_icon) {
      $stmt = $pdo->prepare('UPDATE `accounts` SET `profile_icon` = :profile_icon WHERE account = :account');
      $stmt->execute(Array(
        ':profile_icon' => $the_default_icon,
        ':account' => $login
      ));
    }

    // another useless check
    if($user_level == 'undefined') {
      // error log
      echo('ERROR.'); send_log(0, 'error', array_merge(Array('description' => 'UNDEFINED ERROR 412 line'), prepare_client_data())); exit();
    }

    // grant access
    create_user_session($login, $user_id, $user_level);

    set_counter('AP_CLOGIN_IP_'.sha1($_SERVER['REMOTE_ADDR']), 0);
    echo('GRANTED.');

    // log to db
    send_log($user_id, 'account', array_merge(Array('description' => 'logged into account'), prepare_client_data()));

    exit();

  }

  // exit from account
  if(isset($_POST['exitacc'])) {

    $account_id = 0;

    // check for availability
    if(!isset($_POST['token'])) {
      exit('WRONG.');
      // log illegal activity
      send_log(0, 'suspect', array_merge(Array('description' => 'подмена полей на этапе выхода'), prepare_client_data()));
    }
    if(!isset($_SESSION) || !isset($_SESSION['act_token'])) {
      // not authorized
      echo('WRONG.');
      exit();
    }

    // re-initialize and pre-formatting
    $token = htmlspecialchars($_POST['token'], ENT_QUOTES);

    if($token == $_SESSION['act_token']) {
      if(isset($_SESSION['userid'])) {
        $account_id = $_SESSION['userid'];
      }
      send_log($account_id, 'account', array_merge(Array('description' => 'logout'), prepare_client_data()));
      remove_user_session();
      exit('GRANTED.');
    }
    else {
      // wrong token
      echo('WRONG.');
      // log illegal activity
      send_log(0, 'suspect', array_merge(Array('description' => 'возможный перебор токенов'), prepare_client_data()));
      exit();
    }

  }

  // generate password recovery code
  if(isset($_POST['gen_recovery'])) {

    // check for availability
    if(!isset($_POST['f1'])) {
      echo('WRONG.');
      // log illegal activity
      send_log(0, 'suspect', array_merge(Array('description' => 'подмена полей на этапе генерации кодов восстановления'), prepare_client_data()));
      exit();
    }

    // re-initialize and pre-formatting
    $undefined_field = htmlspecialchars($_POST['f1'], ENT_QUOTES);
    $account_login = 'login';
    $account_id = 0;
    $email = 'undefined';
    $name = 'user';

    // is email or login ?
    if(strpos($undefined_field, '@') == false) {
      // validation
      if(!preg_match($login_regex, $undefined_field)) { exit('LOGIN.'); }

      // exists
      $stmt = $pdo->prepare('SELECT account_id, email, first_name FROM accounts WHERE account = ?');
      $stmt->execute([$undefined_field]);
      $is_empty = true;
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        $is_empty = false;
        if(isset($row['account_id']) && isset($row['email']) && isset($row['first_name'])) {
          // get data from db
          $account_login = $undefined_field;
          $account_id = $row['account_id'];
          $email = $row['email'];
          $name = $row['first_name'];
        }
        else {
          // log to db
          exit('ERROR 2.');
        }
      }
      if($is_empty) {
        // ban by ip
        if(!check_counter('AP_CREC_SCAN_IP_'.sha1($_SERVER['REMOTE_ADDR']), 15)) {
          ban_ip($_SERVER['REMOTE_ADDR'], 1800);
          set_counter('AP_CREC_SCAN_IP_'.sha1($_SERVER['REMOTE_ADDR']), 0);
        }
        send_log(0, 'suspect', array_merge(Array('description' => 'возможный скан логинов'), prepare_client_data()));
        exit('NOT_EXISTS.');
      }
    }
    else {
      // validation
      if(!preg_match($email_regex, $undefined_field)) { exit('EMAIL.'); }

      // exists
      $stmt = $pdo->prepare('SELECT account_id, account, first_name FROM accounts WHERE email = ?');
      $stmt->execute([$undefined_field]);
      $is_empty = true;
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        $is_empty = false;
        if(isset($row['account_id']) && isset($row['first_name']) && isset($row['account'])) {
          // get data from db
          $account_login = $row['account'];
          $account_id = $row['account_id'];
          $email = $undefined_field;
          $name = $row['first_name'];
        }
        else {
          // log to db
          exit('ERROR 1.');
        }
      }
      if($is_empty) {
        // ban by ip
        if(!check_counter('AP_CREC_SCAN_IP_'.sha1($_SERVER['REMOTE_ADDR']), 15)) {
          ban_ip($_SERVER['REMOTE_ADDR'], 1800);
          set_counter('AP_CREC_SCAN_IP_'.sha1($_SERVER['REMOTE_ADDR']), 0);
        }
        send_log(0, 'suspect', array_merge(Array('description' => 'возможный скан логинов'), prepare_client_data()));
        exit('NOT_EXISTS.');
      }
    }

    // check timer
    $timer_exists = false;
    $db_timer = new DateTime();
    $db_timer->format('U = Y-m-d H:i:s');
    $now_timer = new DateTime('now');
    $stmt = $pdo->prepare('SELECT the_time FROM timers WHERE timer = ?');
    $stmt->execute(['LRC_ID_'.strval($account_id)]);

    $is_empty = true;
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      $is_empty = false;
      if(isset($row['the_time'])) {
        $timer_exists = true;
        $db_timer->setTimestamp(strtotime($row['the_time']));
      }
      else {
        echo('ERROR 3.');
        send_log(0, 'error', array_merge(Array('description' => 'UNDEFINED ERROR 560 line'), prepare_client_data()));
        exit();
      }
    }
    if($is_empty) {
      $timer_exists = false;
    }
    if($timer_exists) {
      $difference = $now_timer->getTimestamp() - $db_timer->getTimestamp();
      if($difference < $recovery_time_limit) {
        exit('TIME_LIMIT.');
      }
    }

    // generate code
    $code = '';
    $easy_number = random_int(0, 9);
    for($i = 0; $i < 7; $i++) {
      if(($i == 3) || ($i == 6)) {
        $generated = $easy_number;
      }
      else {
        $generated = random_int(0, 9);
      }
      $code = $code.strval($generated);
    }
    $_SESSION['recovery_code'] = $code;
    $_SESSION['recovery_account'] = $account_id;
    $_SESSION['recovery_granted'] = false;

    // send code
    $the_php_mailer->addAddress($email, $name);
    $the_php_mailer->isHTML(true);
    $the_php_mailer->Subject = 'Восстановление доступа';
    $the_php_mailer->Body = "<!DOCTYPE html><html lang='ru' dir='ltr'><head><meta charset='utf-8'><style> a{text-decoration: none;color: #303036;} ::selection{background-color: #ff970840;} .tel{border-bottom: 1px solid transparent;transition: 0.25s border-bottom;color: #303036;} .tel:hover{color: #303036;border-bottom: 1px dashed #303036;} .fotter-a{border-bottom: 1px solid transparent;transition: 0.25s border-bottom;color: #303036;} .fotter-a:hover{border-bottom: 1px dashed #303036;color: #303036;} .footera:hover{color: #303036;border-bottom: 1px dashed #303036;} </style><link href='https://fonts.googleapis.com/css?family=Quicksand:300,400,500,600,700|Roboto:300,400,500,700,900&display=swap&subset=latin-ext' rel='stylesheet'></head><body style='padding: 0;margin: 0;font-family: Quicksand, sans-serif;color: #303036;'><div class='nav' style='height: 75px; width: 120px; margin-top: 30px; position: relative; margin-left: 30px; background-position: center; background-repeat: no-repeat; background-size: contain; white-space: nowrap; transform: scale(1); user-select: none;'><div style='background-image: url(http://insoweb.ru/mail/logo/cloudlyAPLogo.png); background-repeat: no-repeat; width: 80px; height: 69px; background-size: contain; display: inline-block; vertical-align: middle;'></div><div class='nav-text' style='display: inline-block; vertical-align: middle; font-size: 40px; font-weight: 700; color: #303036; line-height: 25px;'><hb><a style='color: #303036;' href='http://insoweb.ru/swiftly' target='_blank'>Swiftly</a></hb><br><div class='logo-title-preloader-2' style='font-size: 22.8px; line-height: 35px;'>admin panel</div></div></div><div style='padding-left: 30px; padding-right: 30px; padding-top: 10px; border: 1px solid #303036; padding-bottom: 50px; background-color: #fff; border-radius: 15px; margin-left: 35px; margin-top: 35px; margin-right: 35px; margin-bottom: 25px; box-shadow: 0 0 13px 0 rgba(82, 63, 105, 0.15);'><div class='title' style='font-family: Roboto ,sans-serif; margin-left: 35px; font-size: 25px; margin-top: 30px; font-weight: 700;'>Восстановление доступа для аккаунта ".$account_login."</div><div style='margin-left: 50px; margin-top: 30px; font-family: Roboto,sans-serif;'>".$name.", ваш проверочный код для восстановления доступа: <b>".$code."</b><br><br><br>В другом случае, пожалуйста, проигнорируйте это письмо<br><br><br>Если у вас есть вопросы, пожалуйста, напишите нам в службу поддержки: <b><a href='mailto:support@insoweb.ru'>support@insoweb.ru</a></b></div></div><div class='footer' style='font-weight: 700; margin-left: 65px; opacity: 0.5; line-height: 25px; display: block; margin-top: 40px;'>Автоматическое сообщение</div><div class='footer2' style='margin-top: 15px; margin-left: 65px; opacity: 0.5; line-height: 15px;'>С условием обработки персональных<br>данных можно ознакомиться <a class='fotter-a' href='#'>здесь</a>.</div><a href='http://insoweb.ru/' target='_blank' class='footera' style='color: #303036; font-weight: 500; font-family: Roboto,sans-serif; margin-left: 65px; margin-top: 35px; font-size: 16px; margin-bottom: 35px; opacity: 0.5; display: inline-block; transition: 0.25s border-bottom; border-bottom: 1px solid transparent;'>© INSOweb</a></body></html>";
    //$the_php_mailer->Body = $name.', Ваш проверочный код для сброса пароля: '.$code;
    if(!$the_php_mailer->send()) {
      echo('ERROR 4.');
      send_log(0, 'error', array_merge(Array('description' => $the_php_mailer->ErrorInfo), prepare_client_data()));
      echo($the_php_mailer->ErrorInfo);
      exit();
    }
    else {
      echo('SENT.');
    }

    // add timer
    $stmt = $pdo->prepare('INSERT INTO `timers` (`timer`) VALUES (:timername) ON DUPLICATE KEY UPDATE `the_time`=CURRENT_TIMESTAMP()');
    $stmt->execute(Array(
      ':timername' => 'LRC_ID_'.strval($account_id)
    ));

    exit();

  }

  // check password recovery code
  if(isset($_POST['chk_recovery'])) {

    if(!isset($_SESSION['recovery_code']) || !isset($_SESSION['recovery_granted'])) {
      // ban by ip
      if(!check_counter('AP_CREC_CODE_IP_'.sha1($_SERVER['REMOTE_ADDR']), 10)) {
        ban_ip($_SERVER['REMOTE_ADDR'], 1800);
        set_counter('AP_CREC_CODE_IP_'.sha1($_SERVER['REMOTE_ADDR']), 0);
      }
      send_log(0, 'suspect', array_merge(Array('description' => 'проверка несуществующего кода'), prepare_client_data()));
      exit('WRONG.');
    }
    if(!isset($_POST['f1'])) {
      echo('WRONG.');
      send_log(0, 'suspect', array_merge(Array('description' => 'подмена полей на этапе проверки кодов восстановления'), prepare_client_data()));
      exit();
    }
    $code = htmlspecialchars($_POST['f1'], ENT_QUOTES);
    if(!preg_match($recovery_code_regex, $code)) {
      send_log(0, 'suspect', array_merge(Array('description' => 'подмена полей на этапе проверки кодов восстановления'), prepare_client_data()));
      exit('CODE.');
    }

    if($code == $_SESSION['recovery_code']) {
      $_SESSION['recovery_granted'] = true;
      exit('MATCH.');
    }
    else {
      // ban by ip
      if(!check_counter('AP_CREC_CODE_IP_'.sha1($_SERVER['REMOTE_ADDR']), 10)) {
        ban_ip($_SERVER['REMOTE_ADDR'], 1800);
        set_counter('AP_CREC_CODE_IP_'.sha1($_SERVER['REMOTE_ADDR']), 0);
      }
      send_log(0, 'suspect', array_merge(Array('description' => 'возможный перебор кода восстановления'), prepare_client_data()));
      exit('NOT_MATCH.');
    }

  }

  // change password
  if(isset($_POST['set_recovery'])) {

    if(!isset($_SESSION['recovery_granted']) || ($_SESSION['recovery_granted'] != true)) {
      echo('WRONG.');
      $suspect_id = 0;
      if(isset($_SESSION['recovery_account']) && is_int($_SESSION['recovery_account'])) {
        $suspect_id = $_SESSION['recovery_account'];
      }
      // ban by ip
      if(!check_counter('AP_CPSWRDCHANGE_IP_'.sha1($_SERVER['REMOTE_ADDR']), 20)) {
        ban_ip($_SERVER['REMOTE_ADDR'], 1800);
        set_counter('AP_CPSWRDCHANGE_IP_'.sha1($_SERVER['REMOTE_ADDR']), 0);
      }
      send_log($suspect_id, 'suspect', array_merge(Array('description' => 'попытка сменить пароль'), prepare_client_data()));
      exit();
    }
    if(!isset($_POST['f1'])) {
      echo('WRONG.');
      send_log(0, 'suspect', array_merge(Array('description' => 'подмена полей на этапе смены пароля'), prepare_client_data()));
      exit();
    }
    if(!isset($_SESSION['recovery_account'])) {
      send_log(0, 'suspect', array_merge(Array('description' => 'смена пароля'), prepare_client_data()));
      exit('WRONG.');
    }
    $password = htmlspecialchars($_POST['f1'], ENT_QUOTES);
    if(!preg_match($password_regex, $password)) { exit('PASSWORD.'); }

    // update password
    $new_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('UPDATE `accounts` SET `password`=:password WHERE `account_id`=:account_id');
    $stmt->execute(Array(
      ':password' => $new_password,
      ':account_id' => $_SESSION['recovery_account']
    ));
    send_log($_SESSION['recovery_account'], 'password_change', array_merge(Array('description' => 'был сменен пароль'), prepare_client_data()));

    set_counter('AP_CPSWRDCHANGE_IP_'.sha1($_SERVER['REMOTE_ADDR']), 0);
    set_counter('AP_CREC_SCAN_IP_'.sha1($_SERVER['REMOTE_ADDR']), 0);
    set_counter('AP_CREC_CODE_IP_'.sha1($_SERVER['REMOTE_ADDR']), 0);

    exit('SUCCESS.');

  }

?>
