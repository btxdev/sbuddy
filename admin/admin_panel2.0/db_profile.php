<?php

  /*
   *  Swiftly Admin Panel v1.12 alpha
   *  (c) 2019-2020 INSOweb  <http://insoweb.ru>
   *  All rights reserved.
   */

  if(isset($_GET['file_version'])) {
    exit('db_profile VERSION: 37');
  }

  if(empty($_POST) && empty($_GET)) {
    exit('EMPTY.');
  }

  // == setup ==================================================================

  include_once('db_includes.php');

  session_name($sess_name);
  session_start();

  ini_set('memory_limit', '202M');
  ini_set('post_max_size', '201M');
  ini_set('upload_max_filesize', '200M');

  // == regex ==================================================================

  $site_title_regex = '/^([a-zA-Zа-яёА-ЯЁ0-9 ]){0,100}$/u';
  $site_desc_regex = '/^([ ()№#$%\'"<>_+=|}{@&?a-zA-Zа-яёА-ЯЁ0-9!.,:-]){0,400}$/mu';
  $site_tags_regex = '/^([a-zа-яё0-9,]){0,400}$/u';

  // == parameters =============================================================

  $r_email_time_limit = 180;
  $change_email_time_limit = 60; // 180
  $news_searching_fast = false; // fast or slow method

  // == check ==================================================================

  // guests not allowed
  if(!isset($_SESSION) || !isset($_SESSION['auth']) || !$_SESSION['auth']) {
    exit('AUTH.');
  }

  // check other session variables
  if(!isset($_SESSION['auth']) || !isset($_SESSION['username']) || !isset($_SESSION['userid']) || !isset($_SESSION['act_token']) || !isset($_SESSION['version'])) {
    $_SESSION = Array();
    session_destroy();
    exit('AUTH.');
  }

  // == mailing ================================================================

  $phpmailer_host = 'smtp.gmail.com';
  $phpmailer_username = 'inso.web59@gmail.com';
  $phpmailer_password = 'poma098123';
  $phpmailer_setfrom_arg_1 = 'inso.web59@gmail.com';
  $phpmailer_setfrom_arg_2 = 'INSOWEB.RU';

  require_once('php/lib_php/phpmailerNew/PHPMailerAutoload.php');
  $the_php_mailer = new PHPMailer;
  $the_php_mailer->isSMTP();
  $the_php_mailer->CharSet = "UTF-8";
  $the_php_mailer->SMTPAuth = true;
  $the_php_mailer->Host = $phpmailer_host;
  $the_php_mailer->Username = $phpmailer_username;
  $the_php_mailer->Password = $phpmailer_password;
  $the_php_mailer->SMTPSecure = 'ssl';
  $the_php_mailer->Port = 465;
  $the_php_mailer->setFrom($phpmailer_setfrom_arg_1, $phpmailer_setfrom_arg_2);

  // == profile functions ======================================================

  // convert image to base64
  function insert_base64_encoded_image_src($img, $svg = false) {
    $mime = 'image/svg+xml';
    if($svg == false) {
      $mime = getimagesize($img)['mime'];
    }
    $data = base64_encode(file_get_contents($img));
    return "data:{$mime};base64,{$data}";
  }

  // destroy session
  function remove_user_session() {
    $_SESSION = Array();
    session_destroy();
  }

  // create directory
  function create_directory($path) {
    return is_dir($path) || mkdir($path, 0700, true);
  }

  // get user directory
  function get_user_dir($type, $username = null, $userid = null) {
    // arguments
    if(is_null($username)) {
      $username = $_SESSION['username'];
    }
    if(is_null($userid)) {
      $userid = $_SESSION["userid"];
    }
    // type
    if($type == 'private') {
      return('media/users/private/USR'.$userid.'_'.$username);
    }
    else if($type = 'public') {
      return('media/users/public/'.$username);
    }
    else {
      return false;
    }
  }

  // get profile icon
  function get_profile_icon($param, $userid = null, $username = null) {
    $path = '';
    if(substr($param, 0, 8) == 'DEFAULT_') {
      $iconid = substr($param, 8, 2);
      $path = 'media/users/'.$iconid.'.jpg';
    }
    else if($param == 'DEF_ADMIN') {
      $path = 'media/users/admin.jpg';
    }
    else {
      if(!is_null($username)) {
        $path = get_user_dir('public', $username)."/profile.jpg";
      }
    }
    return $path;
  }

  // get file type by extension
  function get_file_type($extension) {
    global $video_extensions;
    global $audio_extensions;
    global $compressed_extensions;
    global $executable_extensions;
    global $document_extensions;
    global $image_extensions;
    $type = 'other';
    if(in_array($extension, $video_extensions)) {
      $type = 'video';
    }
    else if(in_array($extension, $audio_extensions)) {
      $type = 'audio';
    }
    else if(in_array($extension, $compressed_extensions)) {
      $type = 'compressed';
    }
    else if(in_array($extension, $executable_extensions)) {
      $type = 'executable';
    }
    else if(in_array($extension, $document_extensions)) {
      $type = 'document';
    }
    else if(in_array($extension, $image_extensions)) {
      $type = 'image';
    }
    else {
      $type = 'other';
    }
    return $type;
  }

  function clearDirectory($dir) {
    if(!is_dir($dir)) {
      return false;
    }
    $files = scandir($dir);
    foreach($files as $key => $file) {
      if($key < 2) {
        continue;
      }
      unlink("$dir/$file");
    }
    return true;
  }

  function moveFilesFromTo($from_dir, $to_dir) {
    if(!is_dir($from_dir) || !is_dir($to_dir)) {
      return false;
    }
    $files = scandir($from_dir);
    foreach($files as $key => $file) {
      if($key < 2) {
        continue;
      }
      $from_file = "$from_dir/$file";
      $to_file = "$to_dir/$file";
      rename($from_file, $to_file);
    }
  }

  function moveFileFromTo($file, $from_dir, $to_dir) {
    if(!is_dir($from_dir) || !is_dir($to_dir)) {
      return false;
    }
    $from_file = "$from_dir/$file";
    $to_file = "$to_dir/$file";
    if($from_file == $to_file) {
      return true;
    }
    rename($from_file, $to_file);
    return true;
  }

  // == server requests ========================================================

  // new user form skip
  if(isset($_POST['nuformskip'])) {
    $stmt = $pdo->prepare('UPDATE `accounts` SET `is_new` = 0 WHERE `account_id` = :userid');
    $stmt->execute(Array(
      ':userid' => $_SESSION["userid"]
    ));
    exit('DONE.');
  }

  // new user form set
  if(isset($_POST['nuform'])) {

    // form stage
    $stage = intval($_POST['nuform']);
    if($stage == 0) {

      // pre-formatting, check fields
      if(!isset($_POST['f1']) || !isset($_POST['f2']) || !isset($_POST['f3'])) {
        exit('WRONG.');
      }
      $name = htmlspecialchars($_POST['f1'], ENT_QUOTES);
      $birthday = htmlspecialchars($_POST['f2'], ENT_QUOTES);
      $sex = htmlspecialchars($_POST['f3'], ENT_QUOTES) == 'male' ? 'male' : 'female';
      if(!preg_match($name_regex, $name)) { exit('NAME.'); }
      if((strlen($birthday) != 10) || (substr_count($birthday, '-') != 2)) { exit('DATE.'); }
      $birthday_t = strtotime($birthday);
      $birthday = date("Y-m-d", $birthday_t);
      $year = intval(date("Y", $birthday_t));
      if(($year < 1900) || ($year > intval(date("Y")))) { exit('DATE.2.'); }

      // set fields
      $stmt = $pdo->prepare('UPDATE `accounts` SET `second_name` = :name, `gender` = :sex, `birthday` = :birthday WHERE `account_id` = :userid');
      $stmt->execute(Array(
        ':name' => $name,
        ':sex' => $sex,
        ':birthday' => $birthday,
        ':userid' => $_SESSION["userid"]
      ));

      exit('NEXT.');
    }
    else if($stage == 1) {
      // theme
      exit('NEXT.');
    }
    else if($stage == 2) { // reserve email-check stage

      // check for availability
      if(!isset($_POST['f1'])) {
        echo('WRONG.');
        // log illegal activity
        send_log($_SESSION['userid'], 'suspect', array_merge(Array('description' => 'подмена полей на этапе генерации кодов для резервной почты'), prepare_client_data()));
        exit();
      }

      // re-initialize and pre-formatting
      $email = htmlspecialchars($_POST['f1'], ENT_QUOTES);

      // validation
      if(!preg_match($email_regex, $email)) { exit('WRONG.'); }

      // check for available
      $stmt = $pdo->prepare('SELECT count FROM emails WHERE email=?');
      $stmt->execute([$email]);
      $emails_total = $stmt->fetchColumn();
      if(empty($emails_total)) {
        $emails_total = 0;
      }
      if($emails_total >= 1) {
        exit('EMAIL_EXISTS.');
      }

      // check timer
      $timer_exists = false;
      $db_timer = new DateTime();
      $db_timer->format('U = Y-m-d H:i:s');
      $now_timer = new DateTime('now');
      $stmt = $pdo->prepare('SELECT the_time FROM timers WHERE timer = ?');
      $stmt->execute(['RERC_ID_'.strval($_SESSION['userid'])]);

      $is_empty = true;
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        $is_empty = false;
        if(isset($row['the_time'])) {
          $timer_exists = true;
          $db_timer->setTimestamp(strtotime($row['the_time']));
        }
        else {
          echo('ERROR.');
          exit();
        }
      }
      if($is_empty) {
        $timer_exists = false;
      }
      if($timer_exists) {
        $difference = $now_timer->getTimestamp() - $db_timer->getTimestamp();
        if($difference < $r_email_time_limit) {
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
      $_SESSION['r_email_code'] = $code;
      $_SESSION['r_email'] = $email;

      // send code
      $the_php_mailer->addAddress($email, $_SESSION['username']);
      $the_php_mailer->isHTML(true);
      $the_php_mailer->Subject = 'Подтверждение резервной почты';
      $the_php_mailer->Body = "<!DOCTYPE html><html lang='ru' dir='ltr'><head><meta charset='utf-8'><style> a{text-decoration: none;color: #303036;} ::selection{background-color: #ff970840;} .tel{border-bottom: 1px solid transparent;transition: 0.25s border-bottom;color: #303036;} .tel:hover{color: #303036;border-bottom: 1px dashed #303036;} .fotter-a{border-bottom: 1px solid transparent;transition: 0.25s border-bottom;color: #303036;} .fotter-a:hover{border-bottom: 1px dashed #303036;color: #303036;} .footera:hover{color: #303036;border-bottom: 1px dashed #303036;} </style><link href='https://fonts.googleapis.com/css?family=Quicksand:300,400,500,600,700|Roboto:300,400,500,700,900&display=swap&subset=latin-ext' rel='stylesheet'></head><body style='padding: 0;margin: 0;font-family: Quicksand, sans-serif;color: #303036;'><div class='nav' style='height: 75px; width: 120px; margin-top: 30px; position: relative; margin-left: 30px; background-position: center; background-repeat: no-repeat; background-size: contain; white-space: nowrap; transform: scale(1); user-select: none;'><div style='background-image: url(http://insoweb.ru/mail/logo/cloudlyAPLogo.png); background-repeat: no-repeat; width: 80px; height: 69px; background-size: contain; display: inline-block; vertical-align: middle;'></div><div class='nav-text' style='display: inline-block; vertical-align: middle; font-size: 40px; font-weight: 700; color: #303036; line-height: 25px;'><hb><a style='color: #303036;' href='http://cloudly.insoweb.ru' target='_blank'>cloudly</a></hb><br><div class='logo-title-preloader-2' style='font-size: 22.8px; line-height: 35px;'>admin panel</div></div></div><div style='padding-left: 30px; padding-right: 30px; padding-top: 10px; border: 1px solid #303036; padding-bottom: 50px; background-color: #fff; border-radius: 15px; margin-left: 35px; margin-top: 35px; margin-right: 35px; margin-bottom: 25px; box-shadow: 0 0 13px 0 rgba(82, 63, 105, 0.15);'><div class='title' style='font-family: Roboto ,sans-serif; margin-left: 35px; font-size: 25px; margin-top: 30px; font-weight: 700;'>Этот адрес указан как резервный для аккаунта ".$_SESSION['username']."</div><div style='margin-left: 50px; margin-top: 30px; font-family: Roboto,sans-serif;'> Ваш проверочный код для подтверждения резервного адреса электронной почты: <b>".$code."</b><br><br><br>В другом случае, пожалуйста, проигнорируйте это письмо<br><br><br>Если у вас есть вопросы, пожалуйста, напишите нам в службу поддержки: <b><a href='mailto:support@insoweb.ru'>support@insoweb.ru</a></b></div></div><div class='footer' style='font-weight: 700; margin-left: 65px; opacity: 0.5; line-height: 25px; display: block; margin-top: 40px;'>Автоматическое сообщение</div><div class='footer2' style='margin-top: 15px; margin-left: 65px; opacity: 0.5; line-height: 15px;'>С условием обработки персональных<br>данных можно ознакомиться <a class='fotter-a' href='#'>здесь</a>.</div><a href='http://insoweb.ru/' target='_blank' class='footera' style='color: #303036; font-weight: 500; font-family: Roboto,sans-serif; margin-left: 65px; margin-top: 35px; font-size: 16px; margin-bottom: 35px; opacity: 0.5; display: inline-block; transition: 0.25s border-bottom; border-bottom: 1px solid transparent;'>© INSOweb</a></body></html>";
      if(!$the_php_mailer->send()) {
        echo('ERROR.');
        send_log($_SESSION['userid'], 'error', array_merge(Array('description' => $the_php_mailer->ErrorInfo), prepare_client_data()));
        echo($the_php_mailer->ErrorInfo);
        exit();
      }
      else {
        echo('NEXT.');
      }

      // add timer
      $stmt = $pdo->prepare('INSERT INTO `timers` (`timer`) VALUES (:timername) ON DUPLICATE KEY UPDATE `the_time`=CURRENT_TIMESTAMP()');
      $stmt->execute(Array(
        ':timername' => 'RERC_ID_'.strval($_SESSION['userid'])
      ));

      exit();
    }
    else if($stage == 3) { // reserve code-check stage

      if(!isset($_SESSION['r_email_code']) || !isset($_SESSION['r_email'])) {
        send_log($_SESSION['userid'], 'suspect', array_merge(Array('description' => 'проверка несуществующего кода'), prepare_client_data()));
        exit('WRONG.');
      }
      if(!isset($_POST['f1'])) {
        echo('WRONG.');
        send_log($_SESSION['userid'], 'suspect', array_merge(Array('description' => 'подмена полей на этапе проверки кода подтверждения резервной почты'), prepare_client_data()));
        exit();
      }
      $code = htmlspecialchars($_POST['f1'], ENT_QUOTES);
      if(!preg_match($recovery_code_regex, $code)) {
        send_log($_SESSION['userid'], 'suspect', array_merge(Array('description' => 'подмена полей на этапе проверки кода подтверждения резервной почты'), prepare_client_data()));
        exit('CODE.');
      }

      if($code == $_SESSION['r_email_code']) {
        // save email
        // to accounts
        $stmt = $pdo->prepare('UPDATE `accounts` SET `email2`=:email WHERE `account_id`=:account_id');
        $stmt->execute(Array(
          ':email' => $_SESSION['r_email'],
          ':account_id' => $_SESSION['userid']
        ));
        // to emails list
        $stmt = $pdo->prepare('INSERT INTO `emails` (`email`, `count`) VALUES (:email, :count)');
        $stmt->execute(Array(
          ':email' => $_SESSION['r_email'],
          ':count' => 1
        ));
        $_SESSION['r_email_code'] = '0000000';
        exit('DONE.');
      }
      else {
        send_log(0, 'suspect', array_merge(Array('description' => 'возможный перебор кода восстановления'), prepare_client_data()));
        exit('NOT_MATCH.');
      }

    }
    else {

      exit('WRONG.');

    }

  }

  // ===========================================================================

  if(isset($_POST['ssets'])) {
    //exit('DEVELOPMENT.');
    // get old email and phone
    $old_email = 'none';
    $old_phonenumber = 'none';
    if(isset($_POST['phone']) || isset($_POST['userEmail'])) {
      try {
        $stmt = $pdo->prepare("SELECT * FROM `accounts` WHERE `account_id` = ?");
        $stmt->execute([$_SESSION['userid']]);
        while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
          if(isset($row['email'])) {
            $old_email = $row['email'];
          }
          if(isset($row['phonenumber'])) {
            $old_phonenumber = $row['phonenumber'];
          }
        }
      }
      catch(Exception $errorexception) {
        exit('ERROR.');
      }
    }
    // accounts part
    $query = "UPDATE `accounts` SET ";
    $counter = 0;
    $stmt_array = Array();

    // phonenumber
    if(isset($_POST['phone'])) {
      // prepare
      $phonenumber = htmlspecialchars($_POST['phone'], ENT_QUOTES);
      if(!preg_match($phone_regex, $phonenumber)) {
        exit('PHONENUMBER.');
      }
      // check phonenumber is free (max 3 phonenumbers)
      try {
        $stmt = $pdo->prepare('SELECT count FROM phonenumbers WHERE phone=?');
        $stmt->execute([$phonenumber]);
        $phones_total = $stmt->fetchColumn();
        if(empty($phones_total)) {
          $phones_total = 0;
        }
        if($phones_total >= $account_phonenumbers_limit) {
          exit('PHONE_LIMIT.');
        }
      }
      catch(Exception $errorexception) {
        exit('ERROR.');
      }
      // set phonenumbers count
      // +1 phonenumber
      if($phones_total > 0) {
        $phones_total++;
        try {
          $stmt = $pdo->prepare('UPDATE `phonenumbers` SET `count`=:count WHERE `phone`=:phone');
          $stmt->execute(Array(
            ':count' => $phones_total,
            ':phone' => $phonenumber
          ));
        }
        catch(Exception $errorexception) {
          exit('ERROR.');
        }
      }
      else {
        $phones_total = 1;
        try {
          $stmt = $pdo->prepare('INSERT INTO `phonenumbers` (`phone`, `count`) VALUES (:phone, :count)');
          $stmt->execute(Array(
            ':phone' => $phonenumber,
            ':count' => $phones_total
          ));
        }
        catch(Exception $errorexception) {
          exit('ERROR.');
        }
      }
      // get old phonenumbers count
      if($old_phonenumber != 'none') {
        try {
          $stmt = $pdo->prepare('SELECT count FROM phonenumbers WHERE phone=?');
          $stmt->execute([$old_phonenumber]);
          $phones_total = $stmt->fetchColumn();
          if(empty($phones_total)) {
            $phones_total = 0;
          }
        }
        catch(Exception $errorexception) {
          exit('ERROR.');
        }
        // remove record if one phonenumber founded
        if($phones_total == 1) {
          try {
            $stmt = $pdo->prepare("DELETE FROM `phonenumbers` WHERE `phone` = ?");
            $stmt->execute([$old_phonenumber]);
          }
          catch(Exception $errorexception) {
            exit('ERROR.');
          }
        }
        // if multiply
        else if($phones_total > 1) {
          $new_count = $phones_total - 1;
          try {
            $stmt = $pdo->prepare('UPDATE `phonenumbers` SET `count`=:count WHERE `phone`=:phone');
            $stmt->execute(Array(
              ':count' => $new_count,
              ':phone' => $old_phonenumber
            ));
          }
          catch(Exception $errorexception) {
            exit('ERROR.');
          }
        }
        else {
          // not founded
        }
      }
      // compose main request
      $query = $query."`phonenumber` = :phonenumber";
      $stmt_array[':phonenumber'] = $phonenumber;
      $counter++;
    }

    // phone exists
    /*if(isset($_POST['phone'])) {
      // regex
      $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES);
      if(!preg_match($phone_regex, $phone)) { exit('PHONENUMBER.'); }
      $query = $query."`phonenumber` = :phonenumber";
      //array_push($stmt_array, Array(':phonenumber' => $phone));
      $stmt_array[':phonenumber'] = $phone;
      $counter++;
    }*/

    // user email
    if(isset($_POST['userEmail'])) {
      // prepare
      $userEmail = htmlspecialchars($_POST['userEmail'], ENT_QUOTES);
      if(!preg_match($email_regex, $userEmail)) {
        exit('USER_EMAIL.');
      }
      // check email is free (max 1 emails)
      try {
        $stmt = $pdo->prepare('SELECT count FROM emails WHERE email=?');
        $stmt->execute([$userEmail]);
        $emails_total = $stmt->fetchColumn();
        if(empty($emails_total)) {
          $emails_total = 0;
        }
        if($emails_total >= $account_emails_limit) {
          exit('EMAIL_LIMIT.');
        }
      }
      catch(Exception $errorexception) {
        exit('ERROR.');
      }
      // set emails count
      // add +1 email
      if($emails_total > 0) {
        $emails_total++;
        try {
          $stmt = $pdo->prepare('UPDATE `emails` SET `count`=:count WHERE `email`=:email');
          $stmt->execute(Array(
            ':count' => $emails_total,
            ':email' => $userEmail
          ));
        }
        catch(Exception $errorexception) {
          exit('ERROR.');
        }
      }
      else {
        $emails_total = 1;
        $stmt = $pdo->prepare('INSERT INTO `emails` (`email`, `count`) VALUES (:email, :count)');
        $stmt->execute(Array(
          ':email' => $userEmail,
          ':count' => $emails_total
        ));
      }
      // get old emails count
      if($old_email != 'none') {
        try {
          $stmt = $pdo->prepare('SELECT count FROM emails WHERE email=?');
          $stmt->execute([$old_email]);
          $emails_total = $stmt->fetchColumn();
          if(empty($emails_total)) {
            $emails_total = 0;
          }
        }
        catch(Exception $errorexception) {
          exit('ERROR.');
        }
        // remove record if one email founded
        if($emails_total == 1) {
          try {
            $stmt = $pdo->prepare("DELETE FROM `emails` WHERE `email` = ?");
            $stmt->execute([$old_email]);
          }
          catch(Exception $errorexception) {
            exit('ERROR.');
          }
        }
        // if multiply
        else if($emails_total > 1) {
          $new_count = $emails_total - 1;
          try {
            $stmt = $pdo->prepare('UPDATE `emails` SET `count`=:count WHERE `email`=:email');
            $stmt->execute(Array(
              ':count' => $new_count,
              ':email' => $old_phonenumber
            ));
          }
          catch(Exception $errorexception) {
            exit('ERROR.');
          }
        }
        else {
          // not founded
        }
      }
      // compose main request
      if($counter > 0) {
        $query = $query.", ";
      }
      $stmt_array[':email'] = $userEmail;
      $query = $query."`email` = :email";
      $counter++;
    }

    if($counter > 0) {
        $query = $query." WHERE `account_id` = :userid";
        //array_push($stmt_array, Array(':userid' => $_SESSION['userid']));
        $stmt_array[':userid'] = $_SESSION['userid'];
        $stmt = $pdo->prepare($query);
        $stmt->execute($stmt_array);
    }

    // userEmail exists
    /*if(isset($_POST['userEmail'])) {
      // regex
      $userEmail = htmlspecialchars($_POST['userEmail'], ENT_QUOTES);
      if(!preg_match($email_regex, $userEmail)) { exit('USER_EMAIL.'); }
      if($counter > 0) {
        $query = $query.", ";
      }
      //array_push($stmt_array, Array(':email' => $userEmail));
      $stmt_array[':email'] = $userEmail;
      $query = $query."`email` = :email";
      $counter++;
    }
    if($counter > 0) {
        $query = $query." WHERE `account_id` = :userid";
        //array_push($stmt_array, Array(':userid' => $_SESSION['userid']));
        $stmt_array[':userid'] = $_SESSION['userid'];
        $stmt = $pdo->prepare($query);
        $stmt->execute($stmt_array);
    }*/

    // account_settings part
    // check if fields not exists
    $field_exists = false;
    if(isset($_POST['chkbox_logs']) || isset($_POST['chkbox_stats'])) {
      $stmt = $pdo->prepare('SELECT * FROM `account_settings` WHERE `account_id` = ?');
      $stmt->execute([$_SESSION['userid']]);
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        $field_exists = true;
      }
    }
    // create query
    $counter = 0;
    $stmt_array = Array();
    if($field_exists) {
      $query = 'UPDATE `account_settings` SET ';
    }
    else {
      $query_p1 = "INSERT INTO `account_settings` ".'(`account_id`';
      $query_p2 = "VALUES ".'(:account_id';

    }
    //array_push($stmt_array, Array(':account_id' => $_SESSION['userid']));
    $stmt_array[':account_id'] = $_SESSION['userid'];
    $counter++;
    // chkbox_logs exists
    if(isset($_POST['chkbox_logs'])) {
      // regex
      $chkbox_logs = htmlspecialchars($_POST['chkbox_logs'], ENT_QUOTES);
      //$chkbox_logs = boolval($chkbox_logs == 'true');
      $chkbox_logs = ($chkbox_logs == 'true') ? 1 : 0;
      if($field_exists) {
        $query = $query."`send_logs` = :chkbox_logs";
      }
      else {
        $query_p1 = $query_p1.", `send_logs`";
        $query_p2 = $query_p2.", :chkbox_logs";
      }
      //array_push($stmt_array, Array(':chkbox_logs' => $chkbox_logs));
      $stmt_array[':chkbox_logs'] = $chkbox_logs;
      $counter++;
    }
    // chkbox_stats exists
    if(isset($_POST['chkbox_stats'])) {
      // regex
      $chkbox_stats = htmlspecialchars($_POST['chkbox_stats'], ENT_QUOTES);
      //$chkbox_stats = boolval($chkbox_stats == 'true');
      $chkbox_stats = ($chkbox_stats == 'true') ? 1 : 0;
      if($field_exists) {
        if($counter > 1) {
          $query = $query.', ';
        }
        $query = $query."`send_statistics` = :chkbox_stats";
      }
      else {
        if($counter > 1) {
          $query_p1 = $query_p1.', ';
          $query_p2 = $query_p2.', ';
        }
        $query_p1 = $query_p1."`send_statistics`";
        $query_p2 = $query_p2.":chkbox_stats";
      }
      //array_push($stmt_array, Array(':chkbox_stats' => $chkbox_stats));
      $stmt_array[':chkbox_stats'] = $chkbox_stats;
      $counter++;
    }
    if(isset($_POST['chkbox_logs']) || isset($_POST['chkbox_stats'])) {
      if($counter > 0) {
        if($field_exists) {
          //$query = $query.' WHERE `account_id` = :account_id)';
          $query = $query.' WHERE `account_id` = :account_id';
          $stmt = $pdo->prepare($query);
          $stmt->execute($stmt_array);
        }
        else {
          $query_p1 = $query_p1.') ';
          $query_p2 = $query_p2.')';
          //array_push($stmt_array, Array(':account_id' => $_SESSION['userid']));
          $query = $query_p1.$query_p2;
          //echo(var_dump($stmt_array));
          //exit($query);
          $stmt = $pdo->prepare($query);
          $stmt->execute($stmt_array);
        }
      }
    }

    // EXAMPLE
    // prepare('UPDATE `accounts` SET `second_name` = :name, `gender` = :sex, `birthday` = :birthday WHERE `account_id` = :userid');
    // prepare('INSERT INTO `logs` (`account_id`, `action`, `details`) VALUES (:account_id, :action, :details)');
    // --------------------------------------------------------------------------------------------------------------------------------------------

    // site_settings part
    // title exists
    if(($_SESSION['userlvl'] == 'superuser') || ($_SESSION['userlvl'] == 'administrator') || ($_SESSION['userlvl'] == 'moderator')) {
      if(isset($_POST['title'])) {
        // regex
        $title = htmlspecialchars($_POST['title'], ENT_QUOTES);
        if(!preg_match($site_title_regex, $title)) { exit('TITLE.'); }
        // check if not exist in db
        $field_exists = false;
        $stmt = $pdo->prepare('SELECT * FROM `site_settings` WHERE `param` = ?');
        $stmt->execute(['title']);
        while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
          $field_exists = true;
        }
        if($field_exists) {
          $stmt = $pdo->prepare('UPDATE `site_settings` SET `value` = :value WHERE `param` = :param');
          $stmt->execute(Array(
            ':param' => 'title',
            ':value' => $title
          ));
        }
        else {
          $stmt = $pdo->prepare('INSERT INTO `site_settings` (`param`, `value`) VALUES (:param, :value)');
          $stmt->execute(Array(
            ':param' => 'title',
            ':value' => $title
          ));
        }
      }
      // description exists
      if(isset($_POST['description'])) {
        // regex
        $description = htmlspecialchars($_POST['description'], ENT_QUOTES);
        if(!preg_match($site_desc_regex, $description)) { exit('DESCRIPTION.'); }
        // check if not exist in db
        $field_exists = false;
        $stmt = $pdo->prepare('SELECT * FROM `site_settings` WHERE `param` = ?');
        $stmt->execute(['description']);
        while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
          $field_exists = true;
        }
        if($field_exists) {
          $stmt = $pdo->prepare('UPDATE `site_settings` SET `value` = :value WHERE `param` = :param');
          $stmt->execute(Array(
            ':param' => 'description',
            ':value' => $description
          ));
        }
        else {
          $stmt = $pdo->prepare('INSERT INTO `site_settings` (`param`, `value`) VALUES (:param, :value)');
          $stmt->execute(Array(
            ':param' => 'description',
            ':value' => $description
          ));
        }
      }
      // tags exists
      if(isset($_POST['tags'])) {
        $tags = htmlspecialchars($_POST['tags'], ENT_QUOTES);
        if(!preg_match($site_tags_regex, $tags)) { exit('TAGS.'); }
        // check if not exist in db
        $field_exists = false;
        $stmt = $pdo->prepare('SELECT * FROM `site_settings` WHERE `param` = ?');
        $stmt->execute(['tags']);
        while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
          $field_exists = true;
        }
        if($field_exists) {
          $stmt = $pdo->prepare('UPDATE `site_settings` SET `value` = :value WHERE `param` = :param');
          $stmt->execute(Array(
            ':param' => 'tags',
            ':value' => $tags
          ));
        }
        else {
          $stmt = $pdo->prepare('INSERT INTO `site_settings` (`param`, `value`) VALUES (:param, :value)');
          $stmt->execute(Array(
            ':param' => 'tags',
            ':value' => $tags
          ));
        }
      }
      // formEmail exists
      if(isset($_POST['formEmail'])) {
        // regex
        $formEmail = htmlspecialchars($_POST['formEmail'], ENT_QUOTES);
        if(!preg_match($email_regex, $formEmail)) { exit('FORM_EMAIL.'); }
        // check if not exist in db
        $field_exists = false;
        $stmt = $pdo->prepare('SELECT * FROM `site_settings` WHERE `param` = ?');
        $stmt->execute(['formEmail']);
        while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
          $field_exists = true;
        }
        if($field_exists) {
          $stmt = $pdo->prepare('UPDATE `site_settings` SET `value` = :value WHERE `param` = :param');
          $stmt->execute(Array(
            ':param' => 'formEmail',
            ':value' => $formEmail
          ));
        }
        else {
          $stmt = $pdo->prepare('INSERT INTO `site_settings` (`param`, `value`) VALUES (:param, :value)');
          $stmt->execute(Array(
            ':param' => 'formEmail',
            ':value' => $formEmail
          ));
        }
      }
      // chkbox_nyd exists
      if(isset($_POST['chkbox_nyd'])) {
        // regex
        $chkbox_nyd = htmlspecialchars($_POST['chkbox_nyd'], ENT_QUOTES);
        $chkbox_nyd = boolval($chkbox_nyd == 'true');
        // check if not exist in db
        $field_exists = false;
        $stmt = $pdo->prepare('SELECT * FROM `site_settings` WHERE `param` = ?');
        $stmt->execute(['newYearDesign']);
        while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
          $field_exists = true;
        }
        if($field_exists) {
          $stmt = $pdo->prepare('UPDATE `site_settings` SET `value` = :value WHERE `param` = :param');
          $stmt->execute(Array(
            ':param' => 'newYearDesign',
            ':value' => $chkbox_nyd
          ));
        }
        else {
          $stmt = $pdo->prepare('INSERT INTO `site_settings` (`param`, `value`) VALUES (:param, :value)');
          $stmt->execute(Array(
            ':param' => 'newYearDesign',
            ':value' => $chkbox_nyd
          ));
        }
      }
    }
    else {
      exit('FORBIDDEN.');
    }

    exit('OK.');

  }

  // ===========================================================================

  if(isset($_POST['news_doc_import'])) {

    if(!isset($_SESSION['news_doc_import_stage'])) {
      $_SESSION['news_doc_import_stage'] = 0;
    }

    if(($_POST['stage'] != $_SESSION['news_doc_import_stage']) || !isset($_POST['stage'])) {
      $_SESSION['news_doc_import_stage'] = 0;
      //exit('RESET.');
    }

    if($_SESSION['news_doc_import_stage'] == 0) {

      $dir = get_user_dir('private');
      if(!$dir) { exit('ERROR.'); }
      $dir = $dir.'/newsimport';
      if(!create_directory($dir)) {
        exit('ERROR.');
      }

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
      // check file extension
      $pieces = explode('.', $_FILES[0]['name']);
      if($pieces[sizeof($pieces) - 1] != 'docx') {
        exit('INVALID_PARAMETERS.');
      }
      // check file size
      if ($_FILES[0]['size'] > 10485760) {
        exit('LIMIT.');
      }
      // create tmp name
      $tmp_name = sha1(time().random_int(1000, 9999)).'.docx';
      // save file
      if(!move_uploaded_file($_FILES[0]['tmp_name'], "$dir/$tmp_name")) {
        exit('DOWNLOADING_ERROR.');
      }
      $_SESSION['news_doc_import_file'] = $tmp_name;
      $_SESSION['news_doc_import_stage'] = 1;
      //$_SESSION['ndi_dlprocessbar'] = 0;
      exit('OK.');

    }
    if($_SESSION['news_doc_import_stage'] == 1) {
      // user private directory
      $dir = get_user_dir('private');
      // read file =============================================================
      try {
        require('vendor/autoload.php');
        $source = __DIR__.'/'.$dir."\/newsimport/".$_SESSION['news_doc_import_file'];
        $objReader = \PhpOffice\PhpWord\IOFactory::createReader('Word2007');
        try {
          if(!($phpWord = $objReader->load($source))) {
            $_SESSION['news_doc_import_stage'] = 0;
            exit('READ.');
          }
        }
        catch(Exception $theException) {
          $_SESSION['news_doc_import_stage'] = 0;
          exit('READ.');
        }
        $body = '';
        try {
          foreach($phpWord->getSections() as $section) {
          	$arrays = $section->getElements();
          	foreach($arrays as $e) {
          		if(get_class($e) === 'PhpOffice\PhpWord\Element\TextRun') {
                try {
                  foreach($e->getElements() as $text) {
                    if(method_exists($text, 'getFontStyle')) { $font = $text->getFontStyle(); } else { continue; }
                    if(method_exists($text, 'getSize')) { $size = $font->getSize()/10; } else { continue; }
                    if(method_exists($font, 'isBold')) { $bold = $font->isBold() ? 'font-weight:700;' :''; } else { continue; }
                    if(method_exists($font, 'getColor')) { $color = $font->getColor(); } else { continue; }
                    if(method_exists($font, 'getName')) { $fontFamily = $font->getName(); } else { continue; }
                    if(method_exists($text, 'getText')) {
                      $body .= '<span style="font-size:' . $size . 'em;font-family:' . $fontFamily . '; '.$bold.'; color:#'.$color.'">';
                      $body .= $text->getText().'</span>';
                    }
                    else {
                      continue;
                    }
            			}
                }
                catch(Exception $theException) {}
          		}
          		else if(get_class($e) === 'PhpOffice\PhpWord\Element\TextBreak') {
          			$body .= '<br />';
          		}
          		else if(get_class($e) === 'PhpOffice\PhpWord\Element\Table') {
          			$body .= '<table border="2px">';
          			$rows = $e->getRows();
          			foreach($rows as $row) {
          				$body .= '<tr>';
          				$cells = $row->getCells();
          				foreach($cells as $cell) {
          					$body .= '<td style="width:'.$cell->getWidth().'">';
          					$celements = $cell->getElements();
          					foreach($celements as $celem) {
          						if(get_class($celem) === 'PhpOffice\PhpWord\Element\Text') {
                        try {
                          $body .= $celem->getText();
                        }
                        catch(Exception $theException) {}
          						}
          						else if(get_class($celem) === 'PhpOffice\PhpWord\Element\TextRun') {
          							foreach($celem->getElements() as $text) {
                          try {
                            if(method_exists($text, 'getText')) {
                              $body .= $text->getText();
                            }
                            else {
                              continue;
                            }
                          }
                          catch(Exception $theException) {}
          							}
          						}
          						else {
          							//$body .= get_class($celem);
          						}
          					}
          					$body .= '</td>';
          				}
          				$body .= '</tr>';
          			}
          			$body .= '</table>';
          		}
          		else {
                try {
                  if(method_exists($e, 'getText')) {
                    $body .= $e->getText();
                  }
                  else {
                    continue;
                  }
                  /*if(!()) {
                    throw new Exception('Fatal error getText in line '.__LINE__);
                  }*/
                }
                catch(Exception $theException) {}
          		}
          	}
          	break;
          }
        }
        catch(Exception $theException) {}
      }
      catch(Exception $theException) {
        $_SESSION['news_doc_import_stage'] = 0;
        //$_SESSION['ndi_dlprocessbar'] = 100;
        exit('READ.');
      }
      // =====================================================================
      echo('OK.');
      echo($body);
      // reload
      $_SESSION['news_doc_import_stage'] = 0;
      //$_SESSION['ndi_dlprocessbar'] = 100;
      // delete file
      try {
        unlink($dir."\/newsimport/".$_SESSION['news_doc_import_file']);
      }
      catch(Exception $theException) {
        exit('REMOVE.');
      }
      $_SESSION['news_doc_import_file'] = '';
      exit();
    }
    exit('STAGE.');
  }

  // ===========================================================================

  // publicate or save new article
  if(isset($_POST['news_publish'])) {
    // prepare
    if(!isset($_POST['news_publish_data'])) {
      exit('WRONG.');
    }
    // json title and text
    try {
      $data = json_decode($_POST['news_publish_data']);
    }
    catch(Exception $except) {
      exit('ERROR.');
    }
    if(!is_object($data)) {
      exit('WRONG.');
    }
    if(!property_exists($data, 'title') || !property_exists($data, 'text')) {
      exit('WRONG.');
    }
    $title = $data->{'title'};
    $text = $data->{'text'};
    if(!is_string($title) || !is_string($text)) {
      exit('WRONG.');
    }
    //
    $publish = ($_POST['news_publish'] == 'true') ? 1 : 0;
    $account = $_SESSION['userid'];
    // check
    if(strlen($title) < 1 || strlen($title) > 200) {
      exit('TITLE_SZ.');
    }
    if(strlen($text) < 1 || strlen($text) > 16777214) {
      exit('DATA.');
    }
    $title_regex = '/^([ ()№#$%\'"<>_+=|}{@&?a-zA-Zа-яёА-ЯЁ0-9!.,:-]){1,200}$/u';
    if(!preg_match($title_regex, $title)) { exit('TITLE.'.$title); }
    // create main news table
    $stmt = $pdo->prepare('CREATE TABLE IF NOT EXISTS `inso_ap`.`news` (
      `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
      `title` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
      `data` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
      `attachments` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
      `publicated` BOOLEAN NOT NULL DEFAULT FALSE,
      `deleted` BOOLEAN NOT NULL DEFAULT FALSE,
      `account_id` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
      `tags` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
      `publication_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `visitors_day` INT UNSIGNED NOT NULL DEFAULT 0,
      `visitors_month` INT UNSIGNED NOT NULL DEFAULT 0,
      `visitors_total` INT UNSIGNED NOT NULL DEFAULT 0,
      `views_day` INT UNSIGNED NOT NULL DEFAULT 0,
      `views_month` INT UNSIGNED NOT NULL DEFAULT 0,
      `views_total` INT UNSIGNED NOT NULL DEFAULT 0,
      PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;');
    if(!($stmt->execute())) {
      exit('ERROR.');
    }
    // try create fulltext index for title
    try {
      $stmt = $pdo->prepare("ALTER TABLE `inso_ap`.`news` ADD FULLTEXT `title_index` (`title`(10));");
      if(!($stmt->execute())) {
        exit('ERROR.');
      }
    }
    catch(PDOException $theException) {
      // just ignore that shit
    }
    // try create fulltext index for data
    try {
      $stmt = $pdo->prepare("ALTER TABLE `inso_ap`.`news` ADD FULLTEXT `data_index` (`data`(200));");
      if(!($stmt->execute())) {
        exit('ERROR.');
      }
    }
    catch(PDOException $theException) {
      // just ignore that shit
    }
    // try create complex fulltext index for title and data
    try {
      $stmt = $pdo->prepare("ALTER TABLE `inso_ap`.`news` ADD FULLTEXT `title_data_index` (`title`(10), `data`(200));");
      if(!($stmt->execute())) {
        exit('ERROR.');
      }
    }
    catch(PDOException $theException) {
      // just ignore that shit
    }
    // get id
    $stmt = $pdo->prepare('SELECT * FROM `news`');
    $stmt->execute();
    $id = $stmt->rowCount() + 1;
    // json attachments
    $attachments = NULL;
    if(isset($_POST['news_attachments_json'])) {
      $attachments = htmlspecialchars($_POST['news_attachments_json'], ENT_QUOTES);
    }
    // files attachments
    try {
      $user_dir = get_user_dir('public');
      $temp_dir = $user_dir.'/attachments/temp';
      $record_dir = $user_dir.'/attachments/record'.$id;
      // create record directory if not exist
      create_directory($record_dir);
      // move all files from temp to recordID
      moveFilesFromTo($temp_dir, $record_dir);
    }
    catch(Exception $except) {
      exit('MOVE.');
    }
    // clear session
    $_SESSION['attachment_array'] = Array();
    // create new record in main table
    $stmt = $pdo->prepare("INSERT INTO `news` (`id`, `title`, `data`, `attachments`, `publicated`, `deleted`, `account_id`, `tags`, `publication_date`, `visitors_day`, `visitors_month`, `visitors_total`, `views_day`, `views_month`, `views_total`)
    VALUES (:id, :title, :data, :attachments, :publish, FALSE, :account_id, '', CURRENT_TIMESTAMP, '0', '0', '0', '0', '0', '0')");
    $success = $stmt->execute(Array(
      ':id' => $id,
      ':title' => $title,
      ':data' => $text,
      ':attachments' => $attachments,
      ':publish' => $publish,
      ':account_id' => $account
    ));
    if(!$success) {
      exit('ERROR.');
    }
    // create unical table
    $tableid = '`inso_ap`.`news__id'.$id.'`';
    $stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS ".$tableid." (`visitor` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `ip` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `view_time` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0', `view_percent` TINYINT UNSIGNED NOT NULL DEFAULT '0', `view_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (`view_date`)) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;");
    $success = $stmt->execute();
    if(!$success) {
      exit('ERROR.');
    }
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['news_update_record'])) {
    // prepare
    if(!isset($_POST['record_id']) || !isset($_POST['news_update_data']) || !isset($_POST['news_publish_state'])) {
      exit('WRONG.');
    }
    $id = intval($_POST['record_id']);
    // json title and text
    try {
      $data = json_decode($_POST['news_update_data']);
    }
    catch(Exception $except) {
      exit('ERROR.');
    }
    if(!is_object($data)) {
      exit('WRONG.');
    }
    if(!property_exists($data, 'title') || !property_exists($data, 'text')) {
      exit('WRONG.');
    }
    $title = $data->{'title'};
    $text = $data->{'text'};
    if(!is_string($title) || !is_string($text)) {
      exit('WRONG.');
    }
    //
    $publish = ($_POST['news_publish_state'] == 'true') ? 1 : 0;
    // check data
    if(strlen($title) < 1 || strlen($title) > 200) {
      exit('TITLE_SZ.');
    }
    if(strlen($text) < 1 || strlen($text) > 16777214) {
      exit('DATA.');
    }
    $title_regex = '/^([ ()№#$%\'"<>_+=|}{@&?a-zA-Zа-яёА-ЯЁ0-9!.,:-]){1,200}$/u';
    if(!preg_match($title_regex, $title)) { exit('TITLE.'.$title); }
    // check table exists
    try {
      $result = $pdo->prepare("SELECT 1 FROM `news` LIMIT 1");
    }
    catch(Exception $e) {
      exit('EMPTY.');
    }
    // check record exists
    $stmt = $pdo->prepare('SELECT * FROM `news` WHERE `id`=?');
    $stmt->execute([$id]);
    if(!$stmt) {
      exit('ERROR.');
    }
    $row = $stmt->fetch(PDO::FETCH_LAZY);
    if(empty($row)) {
      exit('EMPTY.');
    }
    $userid = $row['account_id'];
    // get username
    $stmt = $pdo->prepare('SELECT `account` FROM `accounts` WHERE `account_id` = ?');
    $stmt->execute([$userid]);
    $row = $stmt->fetch(PDO::FETCH_LAZY);
    if(empty($row)) {
      exit('EMPTY.');
    }
    $username = $row['account'];

    // json attachments
    /*$attachments = NULL;
    if(isset($_POST['news_attachments_json'])) {
      $attachments = htmlspecialchars($_POST['news_attachments_json'], ENT_QUOTES);
    }
    // attachments folder
    $user_dir = get_user_dir('public');
    $record_dir = $user_dir.'attachments/record'.$id;
    // compare files
    $attachments_array = json_decode(html_entity_decode($attachments, ENT_QUOTES));
    // new files list
    $new_files = Array();
    if(!is_array($attachments_array)) {
      exit('WRONG.'.__LINE__);
    }
    foreach($attachments_array as $key => $new_file) {
      if(!is_object($new_file) || !array_key_exists('hash', $new_file)) {
        exit('WRONG.'.__LINE__);
      }
      array_push($new_files, $new_file->hash);
    }
    // old files list
    if(!is_dir($record_dir)) {
      exit('ERROR.');
    }
    $old_files = scandir($record_dir);
    foreach($old_files as $key => $old_file) {
      if($key < 2) {
        continue;
      }
      if(!in_array($old_file, $new_files)) {
        // remove old file
        unlink("$record_dir/$old_file");
      }
    }*/
    // attachments
    $user_dir = get_user_dir('public', $username, $userid);
    $current_user_dir = get_user_dir('public');
    $record_dir = $user_dir.'/attachments/record'.$id;
    $temp_dir = $current_user_dir.'/attachments/temp';
    // parse attachments list
    try {
      $json_attachments = json_decode($_POST['news_attachments_json']);
    }
    catch(Exception $except) {
      exit('ERROR.');
    }
    // list of attachments for db
    $new_attachments = Array();
    // check if is not empty
    if(sizeof($json_attachments) > 0) {
      // scan files in record folder
      if(!is_dir($record_dir)) {
        exit('ERROR.');
      }
      $old_attachments = scandir($record_dir);
      // remove files if they are not in the list
      foreach($old_attachments as $key1 => $old_file) {
        if($key1 < 2) {
          continue;
        }
        $founded = false;
        foreach($json_attachments as $key2 => $json_file) {
          if(!is_object($json_file) || !property_exists($json_file, 'mime') || !property_exists($json_file, 'filename') || !property_exists($json_file, 'hash') || !property_exists($json_file, 'status')) {
            exit('WRONG.');
          }
          if($json_file->status == 'old' && $json_file->hash == $old_file) {
            $founded = true;
            break;
          }
        }
        if($founded) {
          // add if exists
          $new_attachments[] = Array(
            'mime' => $json_file->mime,
            'filename' => $json_file->filename,
            'hash' => $json_file->hash,
            'status' => $json_file->status
          );
        }
        else {
          // remove if conflict (not exists)
          unlink("$record_dir/$old_file");
        }
      }
      // get new files from session list on temp folder and check
      foreach($_SESSION['attachment_array'] as $key => $file) {
        // check
        if(!array_key_exists('mime', $file) || !array_key_exists('filename', $file) || !array_key_exists('hash', $file) || !array_key_exists('status', $file)) {
          continue;
        }
        // move file to record dir
        if(!moveFileFromTo($file['hash'], $temp_dir, $record_dir)) {
          continue;
        }
        $file['status'] = 'old';
        // add to new_attachments
        $new_attachments[] = $file;
      }
    }
    else {
      // clear all folders
      clearDirectory($record_dir);
      clearDirectory($temp_dir);
    }
    // clear session
    $_SESSION['attachment_array'] = Array();
    // prepare attachments
    $attachments = json_encode($new_attachments);
    // update record
    $stmt = $pdo->prepare("UPDATE `news` SET `title` = :title, `data` = :data, `attachments` = :attachments, `publicated` = :publicated WHERE `news`.`id` = :id");
    try {
      $stmt->execute(Array(
        ':id' => $id,
        ':title' => $title,
        ':data' => $text,
        ':attachments' => $attachments,
        ':publicated' => $publish
      ));
    }
    catch(Exception $e) {
      exit('ERROR.');
    }

    /*try {
      $user_dir = get_user_dir('public');
      $temp_dir = $user_dir.'attachments/temp';
      $record_dir = $user_dir.'attachments/record'.$id;
      // create record directory if not exist
      create_directory($record_dir);
      // move all files from temp to recordID
      moveFilesFromTo($temp_dir, $record_dir);
    }
    catch(Exception $except) {
      exit('MOVE.');
    }*/
    exit('OK.');
  }

  // ===========================================================================

  // get list of news
  if(isset($_POST['news_get_list'])) {

    // output mode
    $LAZY_MODE = false;

    // pagination
    $records_limit = 30;
    $pages_limit = 100;
    $page = 1;
    if(isset($_POST['page']) && is_numeric($_POST['page'])) {
      $page = intval($_POST['page']);
      if($page < 1 || $page > $pages_limit) {
        $page = 1;
      }
    }

    // prepare search filter
    $need_search = false;
    $needle = '';
    if(isset($_POST['news_get_search'])) {
      $needle = htmlspecialchars($_POST['news_get_search'], ENT_QUOTES);
      $need_search = true;
    }
    // prepare username filter
    $need_username = false;
    $username = '';
    if(isset($_POST['news_get_filter_username'])) {
      $username = htmlspecialchars($_POST['news_get_filter_username'], ENT_QUOTES);
      if(!preg_match($login_regex, $username)) {
        exit('WRONG.');
      }
      $need_username = true;
    }
    // prepare filter by type (need published)
    $need_published = false;
    if(isset($_POST['news_get_filter_published']) && (htmlspecialchars($_POST['news_get_filter_published'], ENT_QUOTES) == 'true')) {
      $need_published = true;
    }
    // prepare filter by type (need saved)
    $need_saved = false;
    if(isset($_POST['news_get_filter_saved']) && (htmlspecialchars($_POST['news_get_filter_saved'], ENT_QUOTES) == 'true')) {
      $need_saved = true;
    }
    // else don't need any record
    if(!$need_published && !$need_saved) {
      exit('EMPTY.');
    }
    // prepare date filter
    $need_date_filter = false;
    $date_filter_start = date('Y-m-d H:i:s', '0');
    $date_filter_end = date('Y-m-d H:i:s');
    if(isset($_POST['news_get_filter_start'])) {
  		$post_ngfs = htmlspecialchars($_POST['news_get_filter_start'], ENT_QUOTES);
  		if(preg_match($dateonly_regex, $post_ngfs)) {
  			try {
  				$post_ngfs_a = explode('-', $post_ngfs);
  				if((intval($post_ngfs_a[0]) >= 1970) && (intval($post_ngfs_a[1]) <= 12) && (intval($post_ngfs_a[2]) <= 31)) {
  					$date_filter_start = $post_ngfs.' 00:00:00';
            $need_date_filter = true;
  				}
  			}
  			catch(Exception $except) {
  				exit('ERROR.');
  			}
  		}
  	}
    if(isset($_POST['news_get_filter_end'])) {
  		$post_ngfe = htmlspecialchars($_POST['news_get_filter_end'], ENT_QUOTES);
  		if(preg_match($dateonly_regex, $post_ngfe)) {
  			try {
  				$post_ngfe_a = explode('-', $post_ngfe);
  				if((intval($post_ngfe_a[0]) >= 1970) && (intval($post_ngfe_a[1]) <= 12) && (intval($post_ngfe_a[2]) <= 31)) {
  					$date_filter_end = $post_ngfe.' 00:00:00';
            $need_date_filter = true;
  				}
  			}
  			catch(Exception $except) {
  				exit('ERROR.');
  			}
  		}
  	}

    // prepare sorting
    $sort_type = 'date';
    $sort_order = 'DESC';
    if(isset($_POST['news_get_sortby'])) {
      $sortby = htmlspecialchars($_POST['news_get_sortby'], ENT_QUOTES);
      if($sortby == 'views') {
        $sort_type = 'views';
      }
      if($sortby == 'alphabet') {
        $sort_type = 'alphabet';
      }
    }
    if(isset($_POST['news_get_sortorder']) && (htmlspecialchars($_POST['news_get_sortorder'], ENT_QUOTES) == 'asc')) {
      $sort_order = 'ASC';
    }

    // check table exists
    try {
      $result = $pdo->prepare("SELECT 1 FROM `news` LIMIT 1");
    }
    catch(Exception $e) {
      exit('EMPTY.');
    }

    // compose request
    // default
    $query = '';
    $query = $query."SELECT * FROM `news` WHERE `deleted` = 0 ";
    $stmt_array = Array();
    // filter by username
    if($need_username) {
      // get account_id by username
      try {
        $stmt = $pdo->prepare('SELECT `account_id` FROM `accounts` WHERE `account` = ?');
        $stmt->execute([$username]);
        $userid = -1;
        while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
          $userid = $row['account_id'];
        }
        if($userid < 0) {
          exit('EMPTY.');
        }
      }
      catch(Exception $except) {
        exit('ERROR.');
      }
      $query = $query."AND `account_id` = :account_id ";
      $stmt_array['account_id'] = $userid;
    }
    // need publicated but ignore saved records
    if($need_published && !$need_saved) {
      $query = $query."AND `publicated` = 1 ";
    }
    // need saved but ignore publicated records
    if(!$need_published && $need_saved) {
      $query = $query."AND `publicated` = 0 ";
    }
    // search filter
    if($need_search) {
      $query = $query."AND (`title` LIKE concat('%',:needle1,'%') OR `data` LIKE concat('%',:needle2,'%')) ";
      $stmt_array['needle1'] = $needle;
      $stmt_array['needle2'] = $needle;
    }
    // filter by date
    if($need_date_filter) {
      $query = $query."AND `publication_date` >= :dstart AND `publication_date` <= :dend ";
      $stmt_array['dstart'] = $date_filter_start;
      $stmt_array['dend'] = $date_filter_end;
    }
    // sorting
    if($sort_type == 'views') {
      $query = $query."ORDER BY `views_total` ".$sort_order." ";
    }
    else if($sort_type == 'alphabet') {
      $query = $query."ORDER BY `title` ".$sort_order." ";
    }
    else {
      $query = $query."ORDER BY `publication_date` ".$sort_order." ";
    }
    // finally
    $query = $query."LIMIT :lim";
    $stmt_array['lim'] = $records_limit;

    // send request
    try {
      $stmt = $pdo->prepare($query);
      $success = $stmt->execute($stmt_array);
      if(!$success) {
        exit('ERROR_QUERY.');
      }
    }
    catch(Exception $except) {
      echo('ERROR_EXCEPTION.');
      exit($query);
    }

    // processing
    $founded = Array();
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      if(isset($row['deleted']) && $row['deleted']) {
        // deleted
      }
      else {
        if($LAZY_MODE) {
          array_push($founded, $row);
        }
        else {
          array_push($founded, Array(
            'id' => $row['id'],
            'title' => $row['title'],
            'data' => strip_tags(mb_strimwidth($row['data'], 0, 1000, "...")),
            'publicated' => $row['publicated'],
            'date' => $row['publication_date']
            //'debug' => $query
          ));
        }
      }
    }
    if(empty($founded)) {
      exit('EMPTY.');
    }
    else {
      // json
      echo('OK.');
      exit(json_encode($founded));
    }
    exit();
  }

  // ===========================================================================

  if(isset($_POST['news_delete_record'])) {
    if(!isset($_POST['ndr_id'])) {
      exit('WRONG.');
    }
    // prepare
    $id = intval($_POST['ndr_id']);
    // check table exists
    try {
      $stmt = $pdo->prepare("SELECT 1 FROM `news` LIMIT 1");
      $stmt->execute();
    }
    catch(Exception $e) {
      exit('EMPTY.');
    }
    // remove record
    try {
      $stmt = $pdo->prepare("UPDATE `news` SET `deleted` = 1 WHERE `news`.`id` = ?");
      $stmt->execute([$id]);
    }
    catch(Exception $e) {
      exit('ERROR.');
    }
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['news_get_record'])) {
    // prepare
    if(!isset($_POST['record_id'])) {
      exit('WRONG.');
    }
    $id = intval($_POST['record_id']);
    // check table exists
    try {
      $stmt = $pdo->prepare("SELECT 1 FROM `news` LIMIT 1");
      $stmt->execute();
    }
    catch(Exception $e) {
      exit('EMPTY.');
    }
    // get data
    $stmt = $pdo->prepare('SELECT `title`, `data`, `attachments`, `account_id` FROM `news` WHERE `id`=?');
    $stmt->execute([$id]);
    if(!$stmt) {
      exit('ERROR.');
    }
    $row = $stmt->fetch(PDO::FETCH_LAZY);
    if(empty($row)) {
      exit('EMPTY.');
    }
    if(isset($row['deleted']) && $row['deleted']) {
      exit('DELETED.');
    }
    $founded = Array();
    // get account id
    $userid = $row['account_id'];
    // get account
    $stmt = $pdo->prepare('SELECT `account` FROM `accounts` WHERE `account_id` = ?');
    $stmt->execute([$userid]);
    $row2 = $stmt->fetch(PDO::FETCH_LAZY);
    if(empty($row2)) {
      exit('EMPTY.');
    }
    $username = $row2['account'];
    // attachments
    $attachments = '[]';
    if($row['attachments'] != NULL) {
      $attachments = $row['attachments'];
    }
    // clear temp folder
    clearDirectory(get_user_dir('public')."/temp");
    // clear session
    $_SESSION['attachment_array'] = Array();
    // prepare
    array_push($founded, Array('title' => html_entity_decode($row['title'], ENT_HTML5), 'text' => html_entity_decode($row['data'], ENT_HTML5), 'account' => $username));
    // json
    echo('OK.');
    echo(json_encode($founded));
    echo('*?*');
    echo(html_entity_decode($attachments, ENT_QUOTES));
    exit();
  }

  // ===========================================================================

  if(isset($_POST['find_username'])) {
    // prepare
    if(!isset($_POST['find_username_needle'])) {
      exit('WRONG.');
    }
    $needle = htmlspecialchars($_POST['find_username_needle'], ENT_QUOTES);
    // regex
    $realname_regex = '/^([a-z0-9]){2,32}$/u';
    $username_regex = '/^([A-Za-zА-ЯЁа-яё]){2,32}$/u';
    if(!preg_match($realname_regex, $needle) && !preg_match($username_regex, $needle)) {
      exit('REGEX.');
    }
    $founded = Array();
    try {
      //$stmt = $pdo->prepare("SELECT * FROM `accounts` WHERE `account` LIKE concat(:needle,'%')");
      //$success = $stmt->execute(Array(':needle' => $needle));
      $stmt = $pdo->prepare("SELECT * FROM `accounts` WHERE `account` LIKE concat(:needle1,'%') OR `first_name` LIKE concat(:needle2,'%') OR `second_name` LIKE concat(:needle3,'%')");
      $success = $stmt->execute(Array(':needle1' => $needle, ':needle2' => $needle, ':needle3' => $needle));
      if(!$success) {
        exit('ERROR.');
      }
    }
    catch(Exception $exception) {
      exit('ERROR.');
    }
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      if(isset($row['account']) && isset($row['first_name']) && isset($row['second_name']) && isset($row['profile_icon'])) {
        $path = get_profile_icon($row['profile_icon'], null, $row['account']);
        array_push($founded, Array('account' => $row['account'], 'name1' => $row['first_name'], 'name2' => $row['second_name'], 'path' => html_entity_decode($path, ENT_HTML5)));
      }
    }
    if(empty($founded)) {
      exit('EMPTY.');
    }
    else {
      // json
      echo('OK.');
      exit(json_encode($founded));
    }
    exit();
  }

  // ===========================================================================

  if(isset($_POST['drop_temp_attachments'])) {
    // check current user public derectory
    $dir = get_user_dir('public');
    if(!$dir) { exit('ERROR.'); }
    $dir = $dir.'/attachments/temp';
    if(!create_directory($dir)) {
      send_log($_SESSION['userid'], 'error', array_merge(Array('description' => 'drop_attachments ошибка при попытке создать директорию'), prepare_client_data()));
      exit('ERROR.');
    }
    // remove all files in temp folder
    if(!clearDirectory($dir)) {
      exit('RM_ERROR.');
    }
    // clear session variable
    $_SESSION['attachment_array'] = Array();
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['attach_file'])) {

    // prepare
    $is_new_file = false;
    if(!isset($_POST['attach_record_id'])) {
      exit('WRONG.');
    }
    $record_id = intval(htmlspecialchars($_POST['attach_record_id'], ENT_QUOTES));
    if($record_id > 0) {
      $is_new_file = false;
    }
    else {
      $is_new_file = true;
    }
    // mime
    $mime = 'other';
    if(!isset($_POST['attach_file_mime'])) {
      exit('WRONG.');
    }
    $cmime = $_POST['attach_file_mime'];
    if(($cmime == 'image') || ($cmime == 'document') || ($cmime == 'audio') || ($cmime == 'video')) {
      $mime = $cmime;
    }
    else {
      $mime = 'other';
    }

    // if new record
    /*$dir = $dir.'/attachments/temp';
    // if update record
    if(isset($_POST['attach_file_update'])) {
      $record_id = intval(htmlspecialchars($_POST['attach_file_update'], ENT_QUOTES));
      $dir = $dir.'/attachments/record'.$record_id;
    }
    if(!create_directory($dir)) {
      exit('ERROR.');
    }*/

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

    // set extension
    $extension = '.attachment';
    $filename = htmlspecialchars($_FILES[0]['name'], ENT_QUOTES);
    // ignore if file type = other
    if($mime != 'other') {
      $filename_pieces = explode('.', $filename);
      $ext = $filename_pieces[sizeof($filename_pieces) - 1];
      $filetype = get_file_type($ext);
      if($filetype != $mime) {
        exit('MIME.');
      }
      if($filetype != 'other') {
        $extension = '.'.$ext;
      }
    }
    if(strlen($extension) > 20) {
      exit('ERROR.');
    }

    // check file size
    if ($_FILES[0]['size'] > 209715200) {
      exit('LIMIT.');
    }

    // create tmp name
    $tmp_name = sha1(time().random_int(1000, 9999)).$extension;

    $user_dir = get_user_dir('public');
    $temp_dir = $user_dir.'/attachments/temp/';

    // save file
    create_directory($temp_dir);
    if(!move_uploaded_file($_FILES[0]['tmp_name'], $temp_dir.$tmp_name)) {
      exit('DOWNLOADING_ERROR.');
    }

    // prepare
    $data_a = Array();
    $data_a['mime'] = $mime;
    $data_a['filename'] = $filename;
    $data_a['hash'] = $tmp_name;
    $data_a['status'] = 'old';
    // save to session
    if(!isset($_SESSION['attachment_array']) || !is_array($_SESSION['attachment_array'])) {
      $_SESSION['attachment_array'] = Array();
    }
    $_SESSION['attachment_array'][sizeof($_SESSION['attachment_array'])] = $data_a;

    // json output
    $data = json_encode($data_a);
    echo('OK.');
    echo($data);
    exit();

  }

  // ===========================================================================

  if(isset($_POST['get_login_records'])) {
    // prepare
    $default_count = 9;
    $count = $default_count;
    $password_change_date = 'none';
    if(isset($_POST['get_log_rec_count'])) {
      $count = intval(htmlspecialchars($_POST['get_log_rec_count'], ENT_QUOTES));
    }
    if($count < $default_count || $count > ($default_count * 4)) {
      $count = $default_count;
    }
    // request -> get register date (new)
    /*$last_record = Array();
    $stmt = $pdo->prepare("SELECT `reg_date` FROM `accounts` WHERE `account_id` = ?");
    try {
      $stmt->execute([$_SESSION['userid']]);
    }
    catch(Exception $except) {
      exit('ERROR.');
    }
    $row = $stmt->fetch(PDO::FETCH_LAZY);
    if(!empty($row)) {
      $last_record = Array(
        'title' => 'Регистрация',
        'description' => '',
        'date' => $row['reg_date'],
        'ip' => $ip,
        'location' => get_city_by_ip($ip, 'ru')
      );
    }
    else {
      exit('ERROR.');
    }*/
    // request -> get register date
    $last_record = Array();
    $stmt = $pdo->prepare("SELECT * FROM `logs` WHERE `account_id` = ? ORDER BY `timestamp` ASC LIMIT 1");
    try {
      $stmt->execute([$_SESSION['userid']]);
    }
    catch(Exception $except) {
      exit('ERROR.');
    }
    $row = $stmt->fetch(PDO::FETCH_LAZY);
    if(!empty($row)) {
      $details = unserialize(html_entity_decode($row['details'], ENT_HTML5));
      $ip = $details['ip'];
      //$password_change_date = $row['timestamp'];
      $last_record = Array(
        'title' => 'Регистрация',
        'description' => '',
        'date' => $row['timestamp'],
        'ip' => $ip,
        'location' => get_city_by_ip($ip, 'ru')
      );
    }
    else {
      exit('ERROR.');
    }
    // request -> password_change
    $stmt = $pdo->prepare("SELECT * FROM `logs` WHERE `account_id` = ? AND `action` = 'password_change' ORDER BY `timestamp` DESC LIMIT 1");
    try {
      $stmt->execute([$_SESSION['userid']]);
    }
    catch(Exception $except) {
      exit('ERROR.');
    }
    $row = $stmt->fetch(PDO::FETCH_LAZY);
    if(!empty($row)) {
      $password_change_date = $row['timestamp'];
    }
    // request -> logs
    $stmt = $pdo->prepare("SELECT * FROM `logs` WHERE `account_id` = :account_id ORDER BY `timestamp` DESC LIMIT :count");
    try {
      $stmt->execute(Array(
        'account_id' => $_SESSION['userid'],
        'count' => $count
      ));
    }
    catch(Exception $except) {
      exit('ERROR.');
    }
    // parse
    $empty_fields = true;
    $output = Array();
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      // not empty
      $empty_fields = false;
      // get values
      $action = $row['action'];
      $details = unserialize(html_entity_decode($row['details'], ENT_HTML5));
      $date = $row['timestamp'];
      $ip = $details['ip'];
      // prepare
      $title = 'Активность';
      $description = '';
      $location = 'Не определен';
      // title and description
      if($action == 'suspect') {
        $title = 'Подозрительная активность';
        switch($details['description']) {
          case 'возможный перебор паролей':
            $description = 'Попытка входа в аккаунт';
            break;
          case 'попытка сменить пароль':
            $description = 'Попытка смены пароля';
            break;
        }
      }
      else if($action == 'password_change') {
        $title = 'Изменен пароль';
        $description = '';
      }
      else if($details['description'] == 'logout') {
        $title = 'Сеанс завершен';
        $description = '';
      }
      else if($details['description'] == 'registered new account') {
        $title = 'Регистрация';
        $description = '';
      }
      else if($details['description'] == 'logged into account') {
        $title = 'Выполнен вход';
        $description = '';
      }
      else {
        $title = 'Активность';
        $description = '';
      }
      // location
      if(array_key_exists('city', $details)) {
        $location = $details['city'];
      }
      else {
        $location = get_city_by_ip($ip, 'ru');
      }
      // prepare
      $one_record = Array(
        'title' => $title,
        'description' => $description,
        'date' => $date,
        'ip' => $ip,
        'location' => $location
      );
      $output[] = $one_record;
    }
    $output[] = $last_record;
    $output[] = $password_change_date;
    if($empty_fields) {
      exit('EMPTY.');
    }
    $json_output = json_encode($output);
    echo('OK.');
    echo($json_output);
    exit();
  }

  // ===========================================================================

  if(isset($_POST['profile_form'])) {
    //exit('DEVELOPMENT.');
    // get old email and phone
    $old_email = 'none';
    $old_phonenumber = 'none';
    if(isset($_POST['profile_form_email']) || isset($_POST['profile_form_phone'])) {
      try {
        $stmt = $pdo->prepare("SELECT * FROM `accounts` WHERE `account_id` = ?");
        $stmt->execute([$_SESSION['userid']]);
        while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
          if(isset($row['email'])) {
            $old_email = $row['email'];
          }
          if(isset($row['phonenumber'])) {
            $old_phonenumber = $row['phonenumber'];
          }
        }
      }
      catch(Exception $errorexception) {
        exit('ERROR.');
      }
    }
    // prepare composition
    $query = "UPDATE `accounts` SET";
    $stmt_array = Array();
    $phonenumber_changed = false;
    $email_changed = false;
    // first name
    if(isset($_POST['profile_form_name1'])) {
      $name1 = htmlspecialchars($_POST['profile_form_name1'], ENT_QUOTES);
      if(!preg_match($name_regex, $name1)) {
        exit('WRONG.');
      }
      $query = $query." `first_name` = :name1";
      $stmt_array[':name1'] = $name1;
    }
    // second name
    if(isset($_POST['profile_form_name2'])) {
      $name2 = htmlspecialchars($_POST['profile_form_name2'], ENT_QUOTES);
      if(!preg_match($name_regex, $name2)) {
        exit('WRONG.');
      }
      if(!empty($stmt_array)) {
        $query = $query.", ";
      }
      $query = $query."`second_name` = :name2";
      $stmt_array[':name2'] = $name2;
    }
    // gender
    if(isset($_POST['profile_form_gender'])) {
      if(htmlspecialchars($_POST['profile_form_gender'], ENT_QUOTES) == 'true') {
        $gender = 'male';
      }
      else {
        $gender = 'female';
      }
      if(!empty($stmt_array)) {
        $query = $query.", ";
      }
      $query = $query."`gender` = :gender";
      $stmt_array[':gender'] = $gender;
    }
    // birthday
    if(isset($_POST['profile_form_birthday'])) {
      $birthday = htmlspecialchars($_POST['profile_form_birthday'], ENT_QUOTES);
      if(!preg_match($dateonly_regex, $birthday)) {
        exit('WRONG.');
      }
      if(!empty($stmt_array)) {
        $query = $query.", ";
      }
      $query = $query."`birthday` = :birthday";
      $stmt_array[':birthday'] = $birthday;
    }
    // country
    if(isset($_POST['profile_form_country'])) {
      $country = htmlspecialchars($_POST['profile_form_country'], ENT_QUOTES);
      if(!preg_match($city_regex, $country)) {
        exit('WRONG.');
      }
      if(!empty($stmt_array)) {
        $query = $query.", ";
      }
      $query = $query."`country` = :country";
      $stmt_array[':country'] = $country;
    }
    // city
    if(isset($_POST['profile_form_city'])) {
      $city = htmlspecialchars($_POST['profile_form_city'], ENT_QUOTES);
      if(!preg_match($city_regex, $city)) {
        exit('WRONG.');
      }
      if(!empty($stmt_array)) {
        $query = $query.", ";
      }
      $query = $query."`city` = :city";
      $stmt_array[':city'] = $city;
    }

    if(isset($_POST['profile_form_phone'])) {
      // prepare
      $phonenumber = htmlspecialchars($_POST['profile_form_phone'], ENT_QUOTES);
      if(!preg_match($phone_regex, $phonenumber)) {
        exit('WRONG.');
      }
      // check phonenumber is free (max 3 phonenumbers)
      try {
        $stmt = $pdo->prepare('SELECT count FROM phonenumbers WHERE phone=?');
        $stmt->execute([$phonenumber]);
        $phones_total = $stmt->fetchColumn();
        if(empty($phones_total)) {
          $phones_total = 0;
        }
        if($phones_total >= $account_phonenumbers_limit) {
          exit('PHONE_LIMIT.');
        }
      }
      catch(Exception $errorexception) {
        exit('ERROR.');
      }
      // set phonenumbers count
      // +1 phonenumber
      if($phones_total > 0) {
        $phones_total++;
        try {
          $stmt = $pdo->prepare('UPDATE `phonenumbers` SET `count`=:count WHERE `phone`=:phone');
          $stmt->execute(Array(
            ':count' => $phones_total,
            ':phone' => $phonenumber
          ));
        }
        catch(Exception $errorexception) {
          exit('ERROR.');
        }
      }
      else {
        $phones_total = 1;
        try {
          $stmt = $pdo->prepare('INSERT INTO `phonenumbers` (`phone`, `count`) VALUES (:phone, :count)');
          $stmt->execute(Array(
            ':phone' => $phonenumber,
            ':count' => $phones_total
          ));
        }
        catch(Exception $errorexception) {
          exit('ERROR.');
        }
      }
      // get old phonenumbers count
      if($old_phonenumber != 'none') {
        try {
          $stmt = $pdo->prepare('SELECT count FROM phonenumbers WHERE phone=?');
          $stmt->execute([$old_phonenumber]);
          $phones_total = $stmt->fetchColumn();
          if(empty($phones_total)) {
            $phones_total = 0;
          }
        }
        catch(Exception $errorexception) {
          exit('ERROR.');
        }
        // remove record if one phonenumber founded
        if($phones_total == 1) {
          try {
            $stmt = $pdo->prepare("DELETE FROM `phonenumbers` WHERE `phone` = ?");
            $stmt->execute([$old_phonenumber]);
          }
          catch(Exception $errorexception) {
            exit('ERROR.');
          }
        }
        // if multiply
        else if($phones_total > 1) {
          $new_count = $phones_total - 1;
          try {
            $stmt = $pdo->prepare('UPDATE `phonenumbers` SET `count`=:count WHERE `phone`=:phone');
            $stmt->execute(Array(
              ':count' => $new_count,
              ':phone' => $old_phonenumber
            ));
          }
          catch(Exception $errorexception) {
            exit('ERROR.');
          }
        }
        else {
          // not founded
        }
      }
      // compose main request
      if(!empty($stmt_array)) {
        $query = $query.", ";
      }
      $query = $query."`phonenumber` = :phonenumber";
      $stmt_array[':phonenumber'] = $phonenumber;
      $phonenumber_changed = true;
    }
    if(isset($_POST['profile_form_email'])) {
      // prepare
      $email = htmlspecialchars($_POST['profile_form_email'], ENT_QUOTES);
      if(!preg_match($email_regex, $email)) {
        exit('WRONG.');
      }
      // check email is free (max 1 emails)
      try {
        $stmt = $pdo->prepare('SELECT count FROM emails WHERE email=?');
        $stmt->execute([$email]);
        $emails_total = $stmt->fetchColumn();
        if(empty($emails_total)) {
          $emails_total = 0;
        }
        if($emails_total >= $account_emails_limit) {
          exit('EMAIL_LIMIT.');
        }
      }
      catch(Exception $errorexception) {
        exit('ERROR.');
      }
      // set emails count
      // add +1 email
      if($emails_total > 0) {
        $emails_total++;
        try {
          $stmt = $pdo->prepare('UPDATE `emails` SET `count`=:count WHERE `email`=:email');
          $stmt->execute(Array(
            ':count' => $emails_total,
            ':email' => $email
          ));
        }
        catch(Exception $errorexception) {
          exit('ERROR.');
        }
      }
      else {
        $emails_total = 1;
        $stmt = $pdo->prepare('INSERT INTO `emails` (`email`, `count`) VALUES (:email, :count)');
        $stmt->execute(Array(
          ':email' => $email,
          ':count' => $emails_total
        ));
      }
      // get old emails count
      if($old_email != 'none') {
        try {
          $stmt = $pdo->prepare('SELECT count FROM emails WHERE email=?');
          $stmt->execute([$old_email]);
          $emails_total = $stmt->fetchColumn();
          if(empty($emails_total)) {
            $emails_total = 0;
          }
        }
        catch(Exception $errorexception) {
          exit('ERROR.');
        }
        // remove record if one email founded
        if($emails_total == 1) {
          try {
            $stmt = $pdo->prepare("DELETE FROM `emails` WHERE `email` = ?");
            $stmt->execute([$old_email]);
          }
          catch(Exception $errorexception) {
            exit('ERROR.');
          }
        }
        // if multiply
        else if($emails_total > 1) {
          $new_count = $emails_total - 1;
          try {
            $stmt = $pdo->prepare('UPDATE `emails` SET `count`=:count WHERE `email`=:email');
            $stmt->execute(Array(
              ':count' => $new_count,
              ':email' => $old_phonenumber
            ));
          }
          catch(Exception $errorexception) {
            exit('ERROR.');
          }
        }
        else {
          // not founded
        }
      }
      // compose main request
      if(!empty($stmt_array)) {
        $query = $query.", ";
      }
      $query = $query."`email` = :email";
      $stmt_array[':email'] = $email;
      $email_changed = true;
    }
    // check
    if(sizeof($stmt_array) == 0) {
      exit('EMPTY.');
    }
    // email was changed
    if($email_changed) {
      // email confirmed
      $email_confirmed = false;
      if(isset($_SESSION['confirmed_email'])) {
        if($_SESSION['confirmed_email'] == $email) {
          $email_confirmed = true;
          unset($_SESSION['confirmed_email']);
        }
      }
      if($email_confirmed) {
        // set 1 to db
        try {
          $stmt = $pdo->prepare('UPDATE `accounts` SET `email_verify` = 1 WHERE `account_id` = ?');
          $stmt->execute([$_SESSION['userid']]);
        }
        catch(Exception $errorexception) {
          exit('ERROR.');
        }
        // remove
        unset($_SESSION['confirmed_email']);
      }
      else {
        // set 0 to db
        try {
          $stmt = $pdo->prepare('UPDATE `accounts` SET `email_verify` = 0 WHERE `account_id` = ?');
          $stmt->execute([$_SESSION['userid']]);
        }
        catch(Exception $errorexception) {
          exit('ERROR.');
        }
      }
    }
    // phonenumber was changed
    if($phonenumber_changed) {
      // phonenumber confirmed
      $phonenumber_confirmed = false;
      if(isset($_SESSION['confirmed_phonenumber'])) {
        if($_SESSION['confirmed_phonenumber'] == $phonenumber) {
          $phonenumber_confirmed = true;
          unset($_SESSION['confirmed_phonenumber']);
        }
      }
      if($phonenumber_confirmed) {
        // set 1 to db
        try {
          $stmt = $pdo->prepare('UPDATE `accounts` SET `phone_verify` = 1 WHERE `account_id` = ?');
          $stmt->execute([$_SESSION['userid']]);
        }
        catch(Exception $errorexception) {
          exit('ERROR.');
        }
        // remove
        unset($_SESSION['confirmed_phonenumber']);
      }
      else {
        // set 0 to db
        try {
          $stmt = $pdo->prepare('UPDATE `accounts` SET `phone_verify` = 0 WHERE `account_id` = ?');
          $stmt->execute([$_SESSION['userid']]);
        }
        catch(Exception $errorexception) {
          exit('ERROR.');
        }
      }
    }
    // finally
    $query = $query." WHERE `account_id` = :account_id";
    $stmt_array[':account_id'] = $_SESSION['userid'];
    // request
    try {
      $stmt = $pdo->prepare($query);
      $stmt->execute($stmt_array);
    }
    catch(Exception $e) {
      exit('ERROR.');
    }
    exit('OK.');
  }

  // ===========================================================================

  // change password
  if(isset($_POST['change_password_f'])) {

    // prepare
    if(!isset($_POST['change_password_f_old']) || !isset($_POST['change_password_f_new'])) {
      exit('WRONG.');
    }
    // check
    $old_password = htmlspecialchars($_POST['change_password_f_old'], ENT_QUOTES);
    if(!preg_match($password_regex, $old_password)) { exit('OLD_PASSWORD.'); }
    $new_password = htmlspecialchars($_POST['change_password_f_new'], ENT_QUOTES);
    if(!preg_match($password_regex, $new_password)) { exit('NEW_PASSWORD.'); }

    // get old password
    $db_password = 'none';
    try {
      $stmt = $pdo->prepare("SELECT `password` FROM `accounts` WHERE `account_id` = ?");
      $stmt->execute([$_SESSION['userid']]);
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        if(isset($row['password'])) {
          $db_password = $row['password'];
        }
      }
    }
    catch(Exception $e) {
      exit('ERROR.');
    }
    if($db_password == 'none') {
      exit('ERROR.');
    }
    // check old password
    if(!password_verify($old_password, $db_password)) {
      exit('WRONG_PASSWORD.');
    }

    // update password
    $password = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('UPDATE `accounts` SET `password`=:password WHERE `account_id`=:account_id');
    $stmt->execute(Array(
      ':password' => $password,
      ':account_id' => $_SESSION['userid']
    ));
    send_log($_SESSION['userid'], 'password_change', array_merge(Array('description' => 'был сменен пароль'), prepare_client_data()));

    exit('OK.');

  }

  // == send email verification code ===========================================

  if(isset($_POST['pce_send'])) {

    //exit('OK.');

    // prepare
    if(!isset($_POST['pce_email'])) {
      exit('WRONG.');
    }
    // validation
    $email = htmlspecialchars($_POST['pce_email'], ENT_QUOTES);
    if(!preg_match($email_regex, $email)) { exit('WRONG.'); }

    // check timer
    $timer_exists = false;
    $db_timer = new DateTime();
    $db_timer->format('U = Y-m-d H:i:s');
    $now_timer = new DateTime('now');
    $stmt = $pdo->prepare('SELECT the_time FROM timers WHERE timer = ?');
    $stmt->execute(['PCEC_ID_'.strval($_SESSION['userid'])]);

    $is_empty = true;
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      $is_empty = false;
      if(isset($row['the_time'])) {
        $timer_exists = true;
        $db_timer->setTimestamp(strtotime($row['the_time']));
      }
      else {
        exit('ERROR.');
      }
    }
    if($is_empty) {
      $timer_exists = false;
    }
    if($timer_exists) {
      $difference = $now_timer->getTimestamp() - $db_timer->getTimestamp();
      if($difference < $change_email_time_limit) {
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
    $_SESSION['pcec'] = $code;
    $_SESSION['pce_email'] = $email;

    // send code
    $the_php_mailer->addAddress($email, $_SESSION['username']);
    $the_php_mailer->isHTML(true);
    $the_php_mailer->Subject = 'Подтверждение электронной почты';
    $the_php_mailer->Body = "<!DOCTYPE html><html lang='ru' dir='ltr'><head><meta charset='utf-8'><style> a{text-decoration: none;color: #303036;} ::selection{background-color: #ff970840;} .tel{border-bottom: 1px solid transparent;transition: 0.25s border-bottom;color: #303036;} .tel:hover{color: #303036;border-bottom: 1px dashed #303036;} .fotter-a{border-bottom: 1px solid transparent;transition: 0.25s border-bottom;color: #303036;} .fotter-a:hover{border-bottom: 1px dashed #303036;color: #303036;} .footera:hover{color: #303036;border-bottom: 1px dashed #303036;} </style><link href='https://fonts.googleapis.com/css?family=Quicksand:300,400,500,600,700|Roboto:300,400,500,700,900&display=swap&subset=latin-ext' rel='stylesheet'></head><body style='padding: 0;margin: 0;font-family: Quicksand, sans-serif;color: #303036;'><div class='nav' style='height: 75px; width: 120px; margin-top: 30px; position: relative; margin-left: 30px; background-position: center; background-repeat: no-repeat; background-size: contain; white-space: nowrap; transform: scale(1); user-select: none;'><div style='background-image: url(http://insoweb.ru/mail/logo/cloudlyAPLogo.png); background-repeat: no-repeat; width: 80px; height: 69px; background-size: contain; display: inline-block; vertical-align: middle;'></div><div class='nav-text' style='display: inline-block; vertical-align: middle; font-size: 40px; font-weight: 700; color: #303036; line-height: 25px;'><hb><a style='color: #303036;' href='http://cloudly.insoweb.ru' target='_blank'>cloudly</a></hb><br><div class='logo-title-preloader-2' style='font-size: 22.8px; line-height: 35px;'>admin panel</div></div></div><div style='padding-left: 30px; padding-right: 30px; padding-top: 10px; border: 1px solid #303036; padding-bottom: 50px; background-color: #fff; border-radius: 15px; margin-left: 35px; margin-top: 35px; margin-right: 35px; margin-bottom: 25px; box-shadow: 0 0 13px 0 rgba(82, 63, 105, 0.15);'><div class='title' style='font-family: Roboto ,sans-serif; margin-left: 35px; font-size: 25px; margin-top: 30px; font-weight: 700;'>Этот адрес указан как основной для аккаунта ".$_SESSION['username']."</div><div style='margin-left: 50px; margin-top: 30px; font-family: Roboto,sans-serif;'> Ваш проверочный код для подтверждения адреса электронной почты: <b>".$code."</b><br><br><br>В другом случае, пожалуйста, проигнорируйте это письмо<br><br><br>Если у вас есть вопросы, пожалуйста, напишите нам в службу поддержки: <b><a href='mailto:support@insoweb.ru'>support@insoweb.ru</a></b></div></div><div class='footer' style='font-weight: 700; margin-left: 65px; opacity: 0.5; line-height: 25px; display: block; margin-top: 40px;'>Автоматическое сообщение</div><div class='footer2' style='margin-top: 15px; margin-left: 65px; opacity: 0.5; line-height: 15px;'>С условием обработки персональных<br>данных можно ознакомиться <a class='fotter-a' href='#'>здесь</a>.</div><a href='http://insoweb.ru/' target='_blank' class='footera' style='color: #303036; font-weight: 500; font-family: Roboto,sans-serif; margin-left: 65px; margin-top: 35px; font-size: 16px; margin-bottom: 35px; opacity: 0.5; display: inline-block; transition: 0.25s border-bottom; border-bottom: 1px solid transparent;'>© INSOweb</a></body></html>";
    if(!$the_php_mailer->send()) {
      echo('ERROR.');
      send_log($_SESSION['userid'], 'error', array_merge(Array('description' => $the_php_mailer->ErrorInfo), prepare_client_data()));
      echo($the_php_mailer->ErrorInfo);
      exit();
    }

    // add timer
    $stmt = $pdo->prepare('INSERT INTO `timers` (`timer`) VALUES (:timername) ON DUPLICATE KEY UPDATE `the_time`=CURRENT_TIMESTAMP()');
    $stmt->execute(Array(
      ':timername' => 'PCEC_ID_'.strval($_SESSION['userid'])
    ));

    exit('OK.');

  }

  // ===========================================================================

  if(isset($_POST['pce_check'])) {

    //exit('OK.');

    if(!isset($_SESSION['pcec'])) {
      exit('WRONG.');
    }

    // pepare
    if(!isset($_POST['pce_check_code']) || !isset($_SESSION['pce_email'])) {
      exit('WRONG.');
      send_log($_SESSION['userid'], 'suspect', array_merge(Array('description' => 'подмена полей на этапе проверки кода подтверждения основной почты'), prepare_client_data()));
      exit();
    }
    // check
    $code = htmlspecialchars($_POST['pce_check_code'], ENT_QUOTES);
    if(!preg_match($recovery_code_regex, $code)) {
      send_log($_SESSION['userid'], 'suspect', array_merge(Array('description' => 'подмена полей на этапе проверки кода подтверждения основной почты'), prepare_client_data()));
      exit('CODE.');
    }

    if($code == $_SESSION['pcec']) {
      // set verifyed
      $stmt = $pdo->prepare('UPDATE `accounts` SET `email_verify` = 1 WHERE `account_id`=:account_id');
      $stmt->execute(Array(
        ':account_id' => $_SESSION['userid']
      ));

      // set variables
      $_SESSION['confirmed_email'] = $_SESSION['pce_email'];

      // remove variables
      unset($_SESSION['pcec']);
      unset($_SESSION['pce_email']);

      // end
      exit('OK.');
    }
    else {
      send_log($_SESSION['userid'], 'suspect', array_merge(Array('description' => 'возможный перебор кода восстановления'), prepare_client_data()));
      exit('NOT_MATCH.');
    }

    exit();

  }

  // ===========================================================================

  if(isset($_POST['pi_list'])) {

    // icons list
    $icons_array = Array();

    // show default icons
    for($i = 0; $i < $profile_photos_count - 1; $i++) {
      $icons_array[] = Array(
        'id' => 'def-'.$i,
        'path' => 'media/users/'.$i.'.jpg'
      );
    }

    // show admin icon
    if($_SESSION['userlvl'] == 'superuser') {
      $icons_array[] = Array(
        'id' => 'admin',
        'path' => 'media/users/admin.jpg'
      );
    }

    // show user icon
    $user_dir = get_user_dir('public');
    if(file_exists("$user_dir/profile.jpg")) {
      $icons_array[] = Array(
        'id' => 'profile',
        'path' => "$user_dir/profile.jpg"
      );
    }

    // output
    exit('OK.'.json_encode($icons_array));

  }

  // ===========================================================================

  if(isset($_POST['pi_set'])) {

    if(!isset($_POST['pi_set_icon'])) {
      exit('WRONG.');
    }

    // prepare
    $icon = htmlspecialchars($_POST['pi_set_icon'], ENT_QUOTES);

    $user_dir = get_user_dir('public');
    $base_icon_name = 'profile.jpg';
    $tmp_icon_name = 'tmp_profile.jpg';
    $base_icon_path = "$user_dir/$base_icon_name";
    $tmp_icon_path = "$user_dir/$tmp_icon_name";

    // set custom profile icon
    if($icon == 'PROFILE') {
      // busy
      $_SESSION['pi_upload_busy'] = true;
      // prepare
      $save_success = false;
      // if tmp_profile exists -> replace
      if(file_exists($tmp_icon_path)) {
        // remove profile.jpg
        if(file_exists($base_icon_path)) {
          unlink($base_icon_path);
        }
        // rename tmp_profile.jpg -> profile.jpg
        rename($tmp_icon_path, $base_icon_path);
        // save to db
        $save_success = true;
      }
      // keep profile.jpg
      else {
        // if file exists
        if(file_exists($base_icon_path)) {
          $save_success = true;
        }
        else {
          $save_success = false;
        }
      }
      // set custom icon
      if($save_success) {
        $icon_to_db = 'PROFILE';
      }
      // save DEFAULT icon
      else {
        $icon_to_db = 'DEFAULT_0';
      }
      // save to db
      try {
      $stmt = $pdo->prepare("UPDATE `accounts` SET `profile_icon` = :icon_path WHERE `account_id` = :userid");
      $stmt->execute(Array(
        ':icon_path' => $icon_to_db,
        ':userid' => $_SESSION["userid"]
      ));
      }
      catch(Exception $e) {
        $_SESSION['pi_upload_busy'] = false;
        exit('ERROR.');
      }
      // remove tmp_profile
      if(file_exists($tmp_icon_path)) {
        unlink($tmp_icon_path);
      }
      $_SESSION['pi_upload_busy'] = false;
      exit('OK.');
    }
    // set default profile icon
    else if(substr($icon, 0, 4) == 'DEF_') {
      // get icon id
      $iconid = intval(substr($icon, 4, 2));
      // check icon id
      if($iconid < 0 || $iconid >= $profile_photos_count) {
        exit('WRONG.');
      }
      // set default icon
      try {
      $icon_path = 'DEFAULT_'.$iconid;
      $stmt = $pdo->prepare("UPDATE `accounts` SET `profile_icon` = :icon_path WHERE `account_id` = :userid");
      $stmt->execute(Array(
        ':icon_path' => $icon_path,
        ':userid' => $_SESSION["userid"]
      ));
      }
      catch(Exception $e) {
        exit('ERROR.');
      }
      // remove tmp_profile
      if(file_exists($tmp_icon_path)) {
        unlink($tmp_icon_path);
      }
      exit('OK.');
    }
    // admin
    else if($icon == 'ADMIN') {
      if($_SESSION['userlvl'] != 'superuser') {
        exit('WRONG.');
      }
      try {
        $stmt = $pdo->prepare("UPDATE `accounts` SET `profile_icon` = 'DEF_ADMIN' WHERE `account_id` = :userid");
        $stmt->execute(Array(
          ':userid' => $_SESSION["userid"]
        ));
      }
      catch(Exception $e) {
        exit('ERROR.');
      }
      // remove tmp_profile
      if(file_exists($tmp_icon_path)) {
        unlink($tmp_icon_path);
      }
      exit('OK.');
    }
    // other
    else {
      exit('EMPTY.');
    }

    exit();

  }

  // ===========================================================================

  if(isset($_POST['pi_upload'])) {

    if(isset($_SESSION['pi_upload_busy']) && $_SESSION['pi_upload_busy']) {
      exit('BUSY.');
    }

    $_SESSION['pi_upload_busy'] = true;

    // check file corruption
    if (!isset($_FILES[0]['error']) || is_array($_FILES[0]['error'])) {
      $_SESSION['pi_upload_busy'] = false;
      exit('INVALID_PARAMETERS.');
    }
    switch($_FILES[0]['error']) {
      case UPLOAD_ERR_OK:
      break;
      case UPLOAD_ERR_NO_FILE:
      $_SESSION['pi_upload_busy'] = false;
      exit('NO_FILE.');
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
      $_SESSION['pi_upload_busy'] = false;
      exit('LIMIT.');
      default:
      $_SESSION['pi_upload_busy'] = false;
      exit('UND_ERROR.');
    }

    // check file size 10MB
    if ($_FILES[0]['size'] > 10485760) {
      $_SESSION['pi_upload_busy'] = false;
      exit('LIMIT.');
    }

    $image_file = $_FILES[0]['tmp_name'];

    // check mime
    try {
      $mime = mime_content_type($image_file);
      if($mime != 'image/png' && $mime != 'image/jpeg') {
        $_SESSION['pi_upload_busy'] = false;
        exit('MIME.');
      }
    }
    catch(Exception $e) {
      $_SESSION['pi_upload_busy'] = false;
      exit('ERROR.');
    }

    // SimpleImage
    require_once('php/SimpleImage.php');
    // open
    $image = new SimpleImage($image_file);
    // square
    $image->square(150);

    $tmp_name = 'tmp_profile.jpg';
    $user_dir = get_user_dir('public');
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
      $_SESSION['pi_upload_busy'] = false;
      exit('ERROR.');
    }

    $_SESSION['pi_upload_busy'] = false;

    echo('OK.');
    echo($user_dir);
    exit();

  }

  // ===========================================================================

  if(isset($_POST['tasks_save'])) {

    // table regex
    $tasks_time_regex = '/^(([0,1][0-9])|(2[0-3])):[0-5][0-9]$/';
    $tasks_date_regex = '/(\d{2}).(\d{2}).(\d{4})/';
    $tasks_subject_regex = '/^([A-Za-zА-ЯЁа-яё0-9- .,\(\)]){2,64}$/u';
    $tasks_teacher_regex = '/^([A-Za-zА-ЯЁа-яё .]){2,48}$/u';
    $tasks_group_regex = '/^([A-Za-zА-ЯЁа-яё0-9- .,\(\)]){2,48}$/u';
    $tasks_title_regex = '/^([A-Za-zА-ЯЁа-яё0-9- .,\(\)]){2,64}$/u';

    $task_type = 'regular'; // or exception

    // day or exception 1970-01-01 or Monday etc.
    $current_task_date = 'none';

    // check task date exists
    if(!isset($_POST['task_date'])) {
      exit('WRONG.'.__LINE__);
    }

    // check tables data exists
    if(!isset($_POST['task_tables'])) {
      exit('WRONG.'.__LINE__);
    }

    // check task date format
    $task_date = htmlspecialchars($_POST['task_date'], ENT_QUOTES);
    $day_of_week = is_day_of_week($task_date);
    if($day_of_week != false) {
      $task_type = 'regular';
      $task_date = $day_of_week;
    }
    else if(preg_match($tasks_date_regex, $task_date)) {
      $task_type = 'exception';
    }
    else {
      exit('WRONG.'.__LINE__);
    }

    // get tables
    try {
      $tables_array = json_decode($_POST['task_tables']);
    }
    catch(Exception $errorexception) {
      exit('ERROR.'.__LINE__);
    }
    // check table data formatting
    if(!is_array($tables_array)) {
      exit('WRONG.'.__LINE__);
    }
    // delete
    $delete_flag = false;
    $clear_flag = false;
    if(empty($tables_array)) {
      $delete_flag = true;
    }
    // check
    else if(!is_array($tables_array[0])) {
      exit('WRONG.'.__LINE__);
    }
    // clear
    else if(empty($tables_array[0])) {
      $clear_flag = true;
    }
    // check
    else {
      foreach($tables_array as $key1 => $table) {
        if(!is_array($table) || sizeof($table) < 2) {
          exit('WRONG.'.__LINE__);
        }
        foreach($table as $key2 => $str) {
          if($key2 == 0) {
            if(!isset($str) || empty($str) || is_array($str)) {
              exit('WRONG.'.__LINE__);
            }
            $c_title = htmlspecialchars($str, ENT_QUOTES);
            if(!preg_match($tasks_title_regex, $c_title)) {
              exit('WRONG.'.__LINE__);
            }
            continue;
          }
          if(!is_array($str) || (sizeof($str) != 4)) {
            exit('WRONG.'.__LINE__);
          }
          foreach($str as $key3 => $col) {
            switch($key3) {
              case 0:
                // time
                $c_time = htmlspecialchars($col, ENT_QUOTES);
                if(!preg_match($tasks_time_regex, $c_time)) {
                  exit('WRONG.'.__LINE__);
                }
                break;
              case 1:
                // subject
                $c_subject = htmlspecialchars($col, ENT_QUOTES);
                if(!preg_match($tasks_subject_regex, $c_subject)) {
                  exit('WRONG.'.__LINE__);
                }
                break;
              case 2:
                // teacher
                $c_teacher = htmlspecialchars($col, ENT_QUOTES);
                if(!preg_match($tasks_teacher_regex, $c_teacher)) {
                  exit('WRONG.'.__LINE__);
                }
                break;
              case 3:
                // group
                $c_group = htmlspecialchars($col, ENT_QUOTES);
                if(!preg_match($tasks_group_regex, $c_group)) {
                  exit('WRONG.'.__LINE__);
                }
                break;
            }
          }
        }
      }
    }

    // create table if not exists
    try {
      $stmt = $pdo->prepare("CREATE TABLE IF NOT EXISTS `tasks` (`task_date` TINYTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, `task_json` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL, UNIQUE `task_index` (`task_date`(10))) ENGINE = InnoDB CHARSET=utf8 COLLATE utf8_general_ci;");
      $stmt->execute();
    }
    catch(Exception $e) {
      exit('ERROR.'.__LINE__);
    }

    if($delete_flag) {
      // remove record
      try {
        $stmt = $pdo->prepare("DELETE FROM `tasks` WHERE `task_date` = :task_date");
        $stmt->execute([$task_date]);
      }
      catch(Exception $e) {
        exit('ERROR.'.__LINE__);
      }
    }
    else {
      // add or update record
      try {
        // add empty table
        if($clear_flag) {
          $task_tables_data = htmlspecialchars(json_encode(Array(Array('', Array('', '', '', '')))), ENT_QUOTES);
        }
        else {
          $task_tables_data = htmlspecialchars($_POST['task_tables'], ENT_QUOTES);
        }
        $stmt = $pdo->prepare('INSERT INTO `tasks` (`task_date`, `task_json`) VALUES (:task_date1, :task_json1) ON DUPLICATE KEY UPDATE `task_date` = :task_date2, `task_json` = :task_json2');
        $stmt->execute(Array(
          ':task_date1' => $task_date,
          ':task_json1' => $task_tables_data,
          ':task_date2' => $task_date,
          ':task_json2' => $task_tables_data
        ));
      }
      catch(Exception $e) {
        exit('ERROR.'.__LINE__);
      }
    }

    exit('OK.');

  }

  // ===========================================================================

  if(isset($_POST['tasks_load'])) {

    $tasks_date_regex = '/(\d{2}).(\d{2}).(\d{4})/';

    // check task date exists
    if(!isset($_POST['task_date'])) {
      exit('WRONG.'.__LINE__);
    }

    // check task date format
    $task_date = htmlspecialchars($_POST['task_date'], ENT_QUOTES);
    $day_of_week = is_day_of_week($task_date);
    if($day_of_week !== false) {
      $task_type = 'regular';
      $task_date = $day_of_week;
    }
    else if(preg_match($tasks_date_regex, $task_date)) {
      $task_type = 'exception';
    }
    else {
      exit('WRONG.'.__LINE__);
    }

    // check data
    try {
      $stmt = $pdo->prepare("SELECT `task_json` FROM `tasks` WHERE `task_date` = ? LIMIT 1");
      $stmt->execute([$task_date]);
      $row = $stmt->fetch(PDO::FETCH_LAZY);
      if(empty($row) || !isset($row['task_json'])) {
        exit('EMPTY.');
      }
      $json_data = html_entity_decode($row['task_json'], ENT_QUOTES);
      echo('OK.');
      echo($json_data);
      exit();
    }
    catch(Exception $e) {
      exit('EMPTY.');
    }

  }

  // ===========================================================================

  if(isset($_POST['tasks_list'])) {

    // empty fields
    $epmty_fields = Array(true, true, true, true, true, true, true);

    // experimental
    /*$query = "SELECT `task_date` FROM `tasks`";
    $stmt_array = Array();
    if(isset($_POST['tasks_search'])) {
      $needle = htmlspecialchars($_POST['tasks_search'], ENT_QUOTES);
      $the_search_regex = '//';
      if(preg_match($the_search_regex, $needle)) {
        $query = "SELECT `task_date` FROM `tasks` WHERE `task_json` LIKE concat('%',:needle,'%')";
        $stmt_array = Array(':needle' => $needle);
      }
    }*/

    // get list
    $the_list = Array();
    try {
      // experimental
      /*$stmt = $pdo->prepare($query);
      $stmt->execute($stmt_array);*/
      $stmt = $pdo->prepare("SELECT `task_date` FROM `tasks`");
      $stmt->execute();
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        $str = $row['task_date'];
        $founded_key = array_search($str, $days_of_week);
        if($founded_key !== false) {
          $epmty_fields[$founded_key] = false;
        }
        $the_list[] = $str;
      }
    }
    catch(Exception $e) {
      exit('EMPTY.');
    }

    // set default list
    foreach($epmty_fields as $key => $value) {
      if($value) {
        $the_list[] = $days_of_week[$key];
      }
    }

    // parse
    $output_array = Array();
    foreach($the_list as $key => $db_day) {
      // default
      $day_title = 'Ошибка';
      $date_mark = 0;
      $date_text = '01.01.1970';
      $is_today = false;
      $is_exception = false;
      // current day of week (number)
      $day_of_week = convert_day(intval(date('w')));
      // db day of week (number)
      $db_day_key = array_search($db_day, $days_of_week);
      // is a regular day
      if($db_day_key !== false) {
        // title
        $day_title = $days_of_week_rus[$db_day_key];
        // date
        $days_to_next_day_of_week = get_days_to_next_day_of_week($db_day_key);
        $date_mark = time() + ($days_to_next_day_of_week * 24 * 60 * 60);
        // convert date to string
        $date_text = date('d.m.Y', $date_mark);
        // is today
        $is_today = ($days_to_next_day_of_week == 0) ? true : false;
        // is exception
        $is_exception = false;
      }
      else {
        // date string
        $date_text = $db_day;
        // convert string to date
        $date_mark = strtotime($date_text);
        // title
        $day_of_week = convert_day(intval(date('w', $date_mark)));
        $day_title = $days_of_week_rus[$day_of_week];
        // is today
        $diff_dates = time() - $date_mark;
        $is_today = (($diff_dates < (24 * 60 * 60)) && ($diff_dates > 0));
        // is exception
        $is_exception = true;
      }

      // add to array
      $output_array[] = Array(
        'title' => $day_title,
        'date' => $date_text,
        'today' => $is_today,
        'exception' => $is_exception
      );
    }

    // json
    echo('OK.');
    echo(json_encode($output_array));
    exit();

  }

  // ===========================================================================

  if(isset($_POST['tasks_search'])) {
    $needle = mb_strtolower(htmlspecialchars($_POST['tasks_search'], ENT_QUOTES));
    $len = mb_strlen($needle);
    if($len < 3) exit('EMPTY.');
    $output = Array();
    try {
      $stmt = $pdo->prepare("SELECT * FROM `tasks`");
      $stmt->execute();
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        $str = mb_strtolower($row['task_json']);
        $founded = mb_strpos($str, $needle);
        //
        if($founded != false) {
          $output[] = (object)Array(
            'date' => $row['task_date'],
            'raw' => html_entity_decode($row['task_json'], ENT_QUOTES)
          );
        }
      }
    }
    catch(Exception $e) {
      exit('ERROR.');
    }
    echo('OK.');
    echo(json_encode($output));
    exit();
  }

  // ===========================================================================

  if(isset($_POST['contacts_form'])) {

    // regex
    $contacts_location_regex = '/^([A-Za-zА-ЯЁа-яё0-9-.,\(\)\s]){1,100}$/u';
    $contacts_level_regex = '/^\d{1,3}$/';
    $contacts_postcode_regex = '/^\d{6}$/';
    $contacts_TIN_regex = '/^[\d+]{10,12}$/';
    $contacts_COR_regex = '/^([0-9]{9})?$/';
    $contacts_PSRN_regex = '/^([0-9]{13})?$/';
    $contacts_time_regex = '/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/';
    $contacts_phonenumber_regex = '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/';
    $contacts_email_regex = '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';

    // city
    if(isset($_POST['contacts_city'])) {
      $city = htmlspecialchars($_POST['contacts_city'], ENT_QUOTES);
      if(preg_match($contacts_location_regex, $city)) {
        // send
        try {
          $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_city', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
          $stmt->execute(Array(
            ':value1' => $city,
            ':value2' => $city
          ));
        }
        catch(Exception $e) {
          exit('ERROR.');
        }
      }
    }

    // street
    if(isset($_POST['contacts_street'])) {
      $street = htmlspecialchars($_POST['contacts_street'], ENT_QUOTES);
      if(preg_match($contacts_location_regex, $street)) {
        // send
        try {
          $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_street', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
          $stmt->execute(Array(
            ':value1' => $street,
            ':value2' => $street
          ));
        }
        catch(Exception $e) {
          exit('ERROR.');
        }
      }
    }

    // building
    if(isset($_POST['contacts_building'])) {
      $building = htmlspecialchars($_POST['contacts_building'], ENT_QUOTES);
      if(preg_match($contacts_location_regex, $building)) {
        // send
        try {
          $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_building', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
          $stmt->execute(Array(
            ':value1' => $building,
            ':value2' => $building
          ));
        }
        catch(Exception $e) {
          exit('ERROR.');
        }
      }
    }

    // office
    if(isset($_POST['contacts_office'])) {
      $office = htmlspecialchars($_POST['contacts_office'], ENT_QUOTES);
      if(preg_match($contacts_location_regex, $office)) {
        // send
        try {
          $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_office', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
          $stmt->execute(Array(
            ':value1' => $office,
            ':value2' => $office
          ));
        }
        catch(Exception $e) {
          exit('ERROR.');
        }
      }
    }

    // level
    if(isset($_POST['contacts_level'])) {
      $level = htmlspecialchars($_POST['contacts_level'], ENT_QUOTES);
      if(preg_match($contacts_level_regex, $level)) {
        // send
        try {
          $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_level', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
          $stmt->execute(Array(
            ':value1' => $level,
            ':value2' => $level
          ));
        }
        catch(Exception $e) {
          exit('ERROR.');
        }
      }
    }

    // postcode
    if(isset($_POST['contacts_postcode'])) {
      $postcode = htmlspecialchars($_POST['contacts_postcode'], ENT_QUOTES);
      if(preg_match($contacts_postcode_regex, $postcode)) {
        // send
        try {
          $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_postcode', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
          $stmt->execute(Array(
            ':value1' => $postcode,
            ':value2' => $postcode
          ));
        }
        catch(Exception $e) {
          exit('ERROR.');
        }
      }
    }

    // maplink
    if(isset($_POST['contacts_maplink'])) {
      $maplink = htmlspecialchars($_POST['contacts_maplink'], ENT_QUOTES);
      // send
      try {
        $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_maplink', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
        $stmt->execute(Array(
          ':value1' => $maplink,
          ':value2' => $maplink
        ));
      }
      catch(Exception $e) {
        exit('ERROR.');
      }
    }

    // LA
    if(isset($_POST['contacts_LA'])) {
      $rqLA = htmlspecialchars($_POST['contacts_LA'], ENT_QUOTES);
      if(preg_match($contacts_location_regex, $rqLA)) {
        // send
        try {
          $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_LA', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
          $stmt->execute(Array(
            ':value1' => $rqLA,
            ':value2' => $rqLA
          ));
        }
        catch(Exception $e) {
          exit('ERROR.');
        }
      }
    }

    // TIN
    if(isset($_POST['contacts_TIN'])) {
      $rqTIN = htmlspecialchars($_POST['contacts_TIN'], ENT_QUOTES);
      if(preg_match($contacts_TIN_regex, $rqTIN)) {
        // send
        try {
          $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_TIN', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
          $stmt->execute(Array(
            ':value1' => $rqTIN,
            ':value2' => $rqTIN
          ));
        }
        catch(Exception $e) {
          exit('ERROR.');
        }
      }
    }

    // COR
    if(isset($_POST['contacts_COR'])) {
      $rqCOR = htmlspecialchars($_POST['contacts_COR'], ENT_QUOTES);
      if(preg_match($contacts_COR_regex, $rqCOR)) {
        // send
        try {
          $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_COR', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
          $stmt->execute(Array(
            ':value1' => $rqCOR,
            ':value2' => $rqCOR
          ));
        }
        catch(Exception $e) {
          exit('ERROR.');
        }
      }
    }

    // COR
    if(isset($_POST['contacts_PSRN'])) {
      $rqPSRN = htmlspecialchars($_POST['contacts_PSRN'], ENT_QUOTES);
      if(preg_match($contacts_PSRN_regex, $rqPSRN)) {
        // send
        try {
          $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_PSRN', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
          $stmt->execute(Array(
            ':value1' => $rqPSRN,
            ':value2' => $rqPSRN
          ));
        }
        catch(Exception $e) {
          exit('ERROR.');
        }
      }
    }

    // worktime starts at
    if(isset($_POST['contacts_wt_start'])) {
      $wt_start = htmlspecialchars($_POST['contacts_wt_start'], ENT_QUOTES);
      if(preg_match($contacts_time_regex, $wt_start)) {
        // send
        try {
          $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_wt_start', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
          $stmt->execute(Array(
            ':value1' => $wt_start,
            ':value2' => $wt_start
          ));
        }
        catch(Exception $e) {
          exit('ERROR.');
        }
      }
    }

    // worktime ends at
    if(isset($_POST['contacts_wt_end'])) {
      $wt_end = htmlspecialchars($_POST['contacts_wt_end'], ENT_QUOTES);
      if(preg_match($contacts_time_regex, $wt_end)) {
        // send
        try {
          $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_wt_end', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
          $stmt->execute(Array(
            ':value1' => $wt_end,
            ':value2' => $wt_end
          ));
        }
        catch(Exception $e) {
          exit('ERROR.');
        }
      }
    }

    // phonenumbers
    if(isset($_POST['contacts_phonenumbers'])) {
      $phonenumbers = htmlspecialchars($_POST['contacts_phonenumbers'], ENT_QUOTES);
      $verifyed = true;
      if(mb_strlen($phonenumbers) == 0) {
        // clear
      }
      else {
        $phonenumbers_array = explode(',', $phonenumbers);
        foreach($phonenumbers_array as $key => $value) {
          if(!preg_match($contacts_phonenumber_regex, $value)) {
            $verifyed = false;
            break;
          }
        }
      }
      if($verifyed) {
        try {
          $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_phonenumbers', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
          $stmt->execute(Array(
            ':value1' => $phonenumbers,
            ':value2' => $phonenumbers
          ));
        }
        catch(Exception $e) {
          exit('ERROR.');
        }
      }
    }

    // emails
    if(isset($_POST['contacts_emails'])) {
      $emails = htmlspecialchars($_POST['contacts_emails'], ENT_QUOTES);
      $verifyed = true;
      if(mb_strlen($emails) == 0) {
        // clear
      }
      else {
        $emails_array = explode(',', $emails);
        foreach($emails_array as $key => $value) {
          if(!preg_match($contacts_email_regex, $value)) {
            $verifyed = false;
            break;
          }
        }
      }
      if($verifyed) {
        // send
        try {
          $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_emails', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
          $stmt->execute(Array(
            ':value1' => $emails,
            ':value2' => $emails
          ));
        }
        catch(Exception $e) {
          exit('ERROR.');
        }
      }
    }

    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['contacts_card_upload'])) {

    // check file corruption
    if (!isset($_FILES[0]['error']) || is_array($_FILES[0]['error'])) {
      exit('INVALID_PARAMETERS.'.__LINE__);
    }
    switch($_FILES[0]['error']) {
      case UPLOAD_ERR_OK:
      break;
      case UPLOAD_ERR_NO_FILE:
      exit('NO_FILE.'.__LINE__);
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
      exit('LIMIT.'.__LINE__);
      default:
      exit('UND_ERROR.'.__LINE__);
    }

    // check file size 10MB
    if ($_FILES[0]['size'] > 20971520) {
      exit('LIMIT.'.__LINE__);
    }

    // check file extension
    $pieces = explode('.', $_FILES[0]['name']);
    $ext = $pieces[sizeof($pieces) - 1];
    if(!in_array($ext, $document_extensions)) {
      exit('MIME.'.__LINE__);
    }

    $folder = '../DOCS_FILES/';
    $filename = 'company.'.$ext;
    $path = $folder.$filename;

    // check folder
    if(!file_exists($folder)) {
      create_directory($folder);
    }

    // remove old file
    if(file_exists($path)) {
      unlink($path);
    }

    // save file
    if(!move_uploaded_file($_FILES[0]['tmp_name'], $path)) {
      exit('ERROR.'.__LINE__);
    }

    // update record in DB
    try {
      $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_card', :value1) ON DUPLICATE KEY UPDATE `value` = :value2");
      $stmt->execute(Array(
        ':value1' => $filename,
        ':value2' => $filename
      ));
    }
    catch(Exception $e) {
      exit('ERROR.'.__LINE__);
    }

    exit('OK.');

  }

  // ===========================================================================

  if(isset($_POST['contacts_card_remove'])) {
    // remove record from DB
    try {
      $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES ('contacts_card', '') ON DUPLICATE KEY UPDATE `value` = ''");
      $stmt->execute();
    }
    catch(Exception $e) {
      exit('ERROR.');
    }
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['ap_mark'])) {
    $val = intval(htmlspecialchars($_POST['ap_mark'], ENT_QUOTES));
    if($val > 5 || $val < 1) $val = 5;
    try {
      $stmt = $pdo->prepare("INSERT INTO `ap_marks` (`account_id`, `mark`) VALUES (:acc_id, :mark1) ON DUPLICATE KEY UPDATE `mark` = :mark2");
      $stmt->execute(Array(
        ':acc_id' => $_SESSION['userid'],
        ':mark1' => $val,
        ':mark2' => $val
      ));
    }
    catch(Exception $e) {
      exit('ERROR.');
    }
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['ap_mark_get'])) {
    try {
      $stmt = $pdo->prepare("SELECT * FROM ap_marks");
      $stmt->execute();
    }
    catch(Exception $e) {
      exit('ERROR.');
    }
    $n = 0;
    $s = 0;
    $my = 0;
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      $n++;
      $s += intval($row['mark']);
      if(intval($row['account_id']) == intval($_SESSION['userid'])) {
        $my = $row['mark'];
      }
    }
    exit("OK.$my.$n.$s");
  }

  // ===========================================================================

  if(isset($_POST['main_statistics'])) {
    // === data ===
    // average scrolling value
    $average_scroll_t = 0;
    $average_scroll_t_c = 0;
    $average_scroll_y = 0;
    $average_scroll_y_c = 0;
    // average view time
    $view_time = 0;
    $view_time_c = 0;
    $view_time_y = 0;
    $view_time_y_c = 0;
    // views by last/prev year/month
    $views_pres_month = 0;   // -1m < t < 0
    $views_prev_month = 0;   // -2m < t < -1m
    $views_pres_year = 0;    // -1y < t < 0
    $views_prev_year = 0;    // -2y < t < -1y
    // hashes
    $unic_users_old = Array();
    $unic_users_new = Array();
    // views total <500k
    $views_total = 0;
    // data for big chart
    $charts_data = Array();
    // data for small chart
    $stat_by_months = Array();
    $stat_by_days = Array();
    // dates table (optimization)
    // views by months
    $datetable_months = Array();
    for($m = 0; $m <= 11; $m++) {
      // calculate timestamp
      $date1 = new DateTime(date('Y-m-').'01');
      $date2 = new DateTime(date('Y-m-').'01');
      $date1->modify(strval($m - 11).' month');
      $date2->modify(strval($m - 10).' month');
      $datetable_months[$m] = (object)Array(
        'start' => $date1->getTimestamp(),
        'end' => $date2->getTimestamp()
      );
      // fill array
      $stat_by_months[$m] = (object)Array(
        'month' => $date1->getTimestamp(),
        'count' => 0
      );
    }
    // views by days
    $datetable_days = Array();
    for($d = 0; $d <= 29; $d++) {
      // calculate timestamps
      $date1 = new DateTime(date('Y-m-d'));
      $date2 = new DateTime(date('Y-m-d'));
      $date1->modify(strval($d - 29).' day');
      $date2->modify(strval($d - 28).' day');
      $datetable_days[$d] = (object)Array(
        'start' => $date1->getTimestamp(),
        'end' => $date2->getTimestamp()
      );
      // fill array
      $stat_by_days[$d] = (object)Array(
        'day' => $date1->getTimestamp(),
        'count' => 0
      );
    }
    // === get last 500 000 records ===
    try {
      $stmt = $pdo_site->prepare("SELECT * FROM `view_stat` ORDER BY `id` DESC LIMIT 500000");
      $stmt->execute();
      //$rows = $stmt->fetchAll();
      $today_date = new DateTime(date('Y-m-d'));
      $current_time = time();
      $current_time_rounded = $today_date->getTimestamp();
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        // get time
        $view_date = strtotime($row['view_date']);
        // unical users
        $unic_hash = ($row['os'] == 'IOS') ? sha1($row['visitor'].$row['ip']) : sha1($row['visitor']);
        // average scrolling value
        $percent_arr = explode(',', $row['view_percent']);
        $p_count = 1; if(isset($percent_arr[0])) { $p_count = $percent_arr[0]; } if($p_count <= 0) { $p_count = 1; }
        $p_summ = 0; if(isset($percent_arr[1])) { $p_summ = $percent_arr[1]; }
        // average view time for all
        $view_time += intval($row['view_time']);
        $view_time_c++;
        // views in interval with 48h and 24h
        if($view_date > ($current_time - 172800)) { // 48 hours
          // add unical user (NEW)
          if(!in_array($unic_hash, $unic_users_new)) { $unic_users_new[] = $unic_hash; }
          //
          if($view_date > ($current_time - 86400)) { // 24 hours,  -24h < t < 0
            // today
            // scrolling
            $average_scroll_t += round($p_summ / $p_count);
            $average_scroll_t_c++;
          }
          else { // -48h < t < -24h
            // yesterday
            // average view time
            $view_time_y += intval($row['view_time']);
            $view_time_y_c++;
            // scrolling
            $average_scroll_y += round($p_summ / $p_count);
            $average_scroll_y_c++;
          }
          // == charts data ==
          $charts_data[] = (object)Array(
            'hash' => $unic_hash,
            'date' => $row['view_date']
          );
        }
        else {
          // add unical user (OLD)
          if(!in_array($unic_hash, $unic_users_old)) { $unic_users_old[] = $unic_hash; }
        }
        // views by last/prev year
        if($view_date > ($current_time - 63072000)) { // 2 years
          if($view_date > ($current_time - 31536000)) { // 1 year, -1y < t < 0
            // at current year
            $views_pres_year++;
          }
          else { // -2y < t < -1y
            // at previous year
            $views_prev_year++;
          }
        }
        // views by last/prev month
        if($view_date > ($current_time - 5184000)) { // 2 months
          if($view_date > ($current_time - 2592000)) { // 1 month, -1m < t < 0
            // at current month
            $views_pres_month++;
          }
          else { // -2m < t < -1m
            // at previous month
            $views_prev_month++;
          }
        }
        // views by months
        for($m = 0; $m < 12; $m++) {
          if(($view_date > $datetable_months[$m]->start) && ($view_date <= $datetable_months[$m]->end)) {
            $stat_by_months[$m]->count++;
          }
        }
        // views by days
        for($d = 0; $d < 30; $d++) {
          if(($view_date > $datetable_days[$d]->start) && ($view_date <= $datetable_days[$d]->end)) {
            $stat_by_days[$d]->count++;
          }
        }
        // views total
        $views_total++;
      }
    }
    catch(Exception $e) {
      exit('ERROR.');
    }
    // average scrolling value
    // today
    if($average_scroll_t_c == 0) $average_scroll_t_c = 1;
    $average_scroll_t = floor($average_scroll_t / $average_scroll_t_c);
    if($average_scroll_t > 100) $average_scroll_t = 100;
    if($average_scroll_t < 0) $average_scroll_t = 0;
    // yesterday
    if($average_scroll_y_c == 0) $average_scroll_y_c = 1;
    $average_scroll_y = floor($average_scroll_y / $average_scroll_y_c);
    if($average_scroll_y > 100) $average_scroll_y = 100;
    if($average_scroll_y < 0) $average_scroll_y = 0;
    // average view time (yesterday)
    if($view_time_y_c == 0) $view_time_y_c = 1;
    $view_time_y = round($view_time_y / $view_time_y_c);
    // average view time (all time)
    if($view_time_c == 0) $view_time_c = 1;
    $view_time = round($view_time / $view_time_c);
    // prepare json output
    $json_data = (object)Array(
      'hashes1' => $unic_users_old,
      'hashes2' => $unic_users_new,
      'scroll' => (object)Array(
        'yesterday' => $average_scroll_y,
        'today' => $average_scroll_t
      ),
      'viewtime' => (object)Array(
        'all' => $view_time,
        'yesterday' => $view_time_y
      ),
      'views' => (object)Array(
        'year' => (object)Array(
          'prev' => $views_prev_year,
          'pres' => $views_pres_year
        ),
        'month' => (object)Array(
          'prev' => $views_prev_month,
          'pres' => $views_pres_month
        ),
        'total' => $views_total
      ),
      'chart1' => $charts_data,
      'chart2' => (object)Array(
        'days' => $stat_by_days,
        'months' => $stat_by_months
        )
    );
    $output = json_encode($json_data);
    echo('OK.');
    echo($output);
    exit();
  }

  // ===========================================================================

  if(isset($_POST['search_users_global'])) {
    $needle = htmlspecialchars($_POST['search_users_global'], ENT_QUOTES);
    $len = mb_strlen($needle);
    if($len <= 2) exit('EMPTY.');
    $output = Array();
    // find in Admin Panel
    try {
      $stmt = $pdo->prepare("SELECT `account_id`, `account`, `access_type`, `first_name`, `second_name`, `gender`, `profile_icon` FROM `accounts` WHERE `account` LIKE concat('%',:login,'%') OR `first_name` LIKE concat('%',:name1,'%') OR `second_name` LIKE concat('%',:name2,'%')");
      $stmt->execute(Array(
        ':login' => $needle,
        ':name1' => $needle,
        ':name2' => $needle
      ));
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        $output[] = (object)Array(
          'admin' => true,
          'id' => $row['account_id'],
          'login' => $row['account'],
          'level' => $row['access_type'],
          'name1' => $row['first_name'],
          'name2' => $row['second_name'],
          'gender' => $row['gender'],
          'icon' => $row['profile_icon']
        );
      }
    }
    catch(Exception $e) {
      exit('ERROR.'.__LINE__);
    }
    // find in site DB
    try {
      $stmt = $pdo_site->prepare("SELECT `account_id`, `account`, `first_name`, `second_name`, `gender`, `profile_icon` FROM `accounts` WHERE `account` LIKE concat('%',:login,'%') OR `first_name` LIKE concat('%',:name1,'%') OR `second_name` LIKE concat('%',:name2,'%')");
      $stmt->execute(Array(
        ':login' => $needle,
        ':name1' => $needle,
        ':name2' => $needle
      ));
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        $output[] = (object)Array(
          'admin' => false,
          'id' => $row['account_id'],
          'login' => $row['account'],
          'first_name' => $row['first_name'],
          'second_name' => $row['second_name'],
          'gender' => $row['gender'],
          'icon' => $row['profile_icon']
        );
      }
    }
    catch(Exception $e) {
      var_dump($e);
      exit('ERROR.'.__LINE__);
    }
    // output
    echo('OK.');
    echo(json_encode($output));
    exit();
  }

  // ===========================================================================

  if(isset($_POST['update_group_list'])) {
    // prepare
    $groups_str = htmlspecialchars($_POST['update_group_list'], ENT_QUOTES);
    $groups_arr = explode(',', $groups_str);
    $groups_arr2 = Array();
    foreach($groups_arr as $elem) {
      if(!empty($elem)) {
        $groups_arr2[] = $elem;
      }
    }
    $groups_str2 = implode(',', $groups_arr2);
    // db
    try {
      $field_exists = false;
      $stmt = $pdo->prepare("SELECT * FROM `site_settings` WHERE `param` = ?");
      $stmt->execute(['timetable_groups']);
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        $field_exists = true;
      }
      if($field_exists) {
        $stmt = $pdo->prepare("UPDATE `site_settings` SET `value` = :value WHERE `param` = :param");
        $stmt->execute(Array(
          ':param' => 'timetable_groups',
          ':value' => $groups_str2
        ));
      }
      else {
        $stmt = $pdo->prepare("INSERT INTO `site_settings` (`param`, `value`) VALUES (:param, :value)");
        $stmt->execute(Array(
          ':param' => 'timetable_groups',
          ':value' => $groups_str2
        ));
      }
    }
    catch(Exception $e) {
      exit('ERROR.'.__LINE__);
    }
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['show_u_custom_tt'])) {
    // users list
    $users = Array();
    // data
    $output = (object)Array(
      'online' => Array(),
      'group' => Array()
    );
    // db
    try {
      $field_exists = false;
      $stmt = $pdo->prepare("SELECT * FROM `u_custom_timetable`");
      $stmt->execute();
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        $userid = $row['user'];
        $name1 = '';
        $name2 = '';
        if(array_key_exists($userid, $users)) {
          // get user data from array
          $name1 = $users[$userid]->name1;
          $name2 = $users[$userid]->name2;
        }
        else {
          // get user data from db
          $stmt2 = $pdo_site->prepare("SELECT `name1`, `name2` FROM `accounts` WHERE `account_id` LIKE ? LIMIT 1");
          $stmt2->execute([$userid]);
          $row2 = $stmt2->fetch(PDO::FETCH_LAZY);
          $name1 = $row2['name1'];
          $name2 = $row2['name2'];
          // add user to array
          $users[$userid] = (object)Array(
            'name1' => $name1,
            'name2' => $name2
          );
        }
        // record data
        $learning = $row['learning'];
        $day = $row['day'];
        $timerange = explode(',', $row['timerange']);
        $timestamp = $row['changed'];
        // get groups
        $stmt3 = $pdo->prepare("SELECT `groups` FROM `timetable_groups` WHERE `user` = :userid AND `learning` = :learning LIMIT 1");
        $stmt3->execute(Array(
          ':userid' => $userid,
          ':learning' => $learning
        ));
        $groups = explode(',', $stmt3->fetchColumn());
        // save
        if(property_exists($output, $learning)) {
          if(array_key_exists(strval($userid), $output->{$learning})) {
            $output->{$learning}[strval($userid)]->table[$day] = $timerange;
          }
          else {
            $output->{$learning}[strval($userid)] = (object)Array(
              'user' => (object)Array(
                'name1' => $name1,
                'name2' => $name2
              ),
              'groups' => $groups,
              'table' => Array(Array(), Array(), Array(), Array(), Array(), Array(), Array()),
              'timestamp' =>$timestamp
            );
          }
        }
      }
    }
    catch(Exception $e) {
      exit('ERROR.'.__LINE__);
    }
    // output
    echo('OK.');
    echo(json_encode($output));
    exit();
  }

  // ===========================================================================

  if(isset($_POST['book_user'])) {
    // prepare
    $userid = intval($_POST['book_user']);
    $learning = htmlspecialchars($_POST['learning'], ENT_QUOTES);
    $groups = htmlspecialchars($_POST['groups'], ENT_QUOTES);
    if($learning != 'online' && $learning != 'group') exit('WRONG.');
    // book
    try {
      // check exists
      $stmt = $pdo->prepare("SELECT `user` FROM `timetable_groups` WHERE `user` = :user AND `learning` = :learning LIMIT 1");
      $stmt->execute(Array(
        ':user' => $userid,
        ':learning' => $learning
      ));
      if(empty($stmt->fetchColumn())) {
        // add record
        $stmt2 = $pdo->prepare("INSERT INTO `timetable_groups` (`user`, `learning`, `groups`, `viewed`, `add_time`) VALUES (:user, :learning, :groups, 0, CURRENT_TIMESTAMP())");
        $stmt2->execute(Array(
          ':user' => $userid,
          ':learning' => $learning,
          ':groups' => $groups
        ));
      }
      else {
        $stmt2 = $pdo->prepare("UPDATE `timetable_groups` SET `groups` = :groups, `viewed` = 0, `add_time` = CURRENT_TIMESTAMP() WHERE `user` = :user AND `learning` = :learning");
        $stmt2->execute(Array(
          ':user' => $userid,
          ':learning' => $learning,
          ':groups' => $groups
        ));
      }
    }
    catch(Exception $e) {
      exit('ERROR.'.__LINE__);
    }
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['groups_mailing_all'])) {
    // get list of users
    $users = Array();
    try {
      // check exists
      $stmt = $pdo->prepare("SELECT * FROM `timetable_groups`");
      $stmt->execute();
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        $userid = $row['user'];
        $learning = $row['learning'];
        $group = explode(',', $row['groups'])[0];
        if(empty($group)) $group = false;
        $timestamp = mb_substr($row['add_time'], 0, 10);
        if(!array_key_exists($userid, $users)) {
          // add user data
          $stmt2 = $pdo_site->prepare("SELECT `account`, `name1`, `email` FROM `accounts` WHERE `account_id` = ? LIMIT 1");
          $stmt2->execute([$userid]);
          $row2 = $stmt2->fetch(PDO::FETCH_LAZY);
          if(empty($row2)) continue;
          $users[$userid] = (object)Array(
            'user' => (object)Array(
              'login' => $row2['account'],
              'name1' => $row2['name1'],
              'email' => $row2['email']
            ),
            'online' => (object)Array(
              'group' => false,
              'timestamp' => ''
            ),
            'group' =>(object)Array(
              'group' => false,
              'timestamp' => ''
            )
          );
        }
        // add mailing data
        $users[$userid]->{$learning}->group = $group;
        $users[$userid]->{$learning}->timestamp = $timestamp;
      }
    }
    catch(Exception $e) {
      exit('ERROR.'.__LINE__);
    }
    // get form email
    $form_email = '';
    $stmt = $pdo->prepare("SELECT * FROM `site_settings` WHERE `param` = 'formEmail'");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_LAZY);
    if(!empty($row)) $form_email = $row['value'];
    // send mails
    foreach($users as $id => $user) {
      // user data
      $login = $user->user->login;
      $name1 = $user->user->name1;
      $email = $user->user->email;
      // вы были записаны в группу (online learning)
      $online_text = '';
      if($user->online->group != false) {
        $online_text = "<div class='main-text-text'><b>Онлайн обучение</b></div>
        <div class='main-text-text'>Вы были записаны в группу: <div class='main-text-password'>{$user->online->group}</div><br>Дата добавления: {$user->online->timestamp}</div><br><br>";
      }
      // вы были записаны в группу (group learning)
      $group_text = '';
      if($user->group->group != false) {
        $group_text = "<div class='main-text-text'><b>Групповое обучение</b></div>
        <div class='main-text-text'>Вы были записаны в группу: <div class='main-text-password'>{$user->group->group}</div><br>Дата добавления: {$user->group->timestamp}</div><br><br>";
      }
      // logotype
      $base64_logo = insert_base64_encoded_image_src('../../../Projects/Study Buddy/Сайт/ver 2/media/svg/logo.svg', true);
      // link for timetable
      $mail_link = site_Link();
      // feedback
      if($form_email != '') $mailto_str = "<br><br>Если у Вас есть вопросы, Вы можете обратиться по электронной почте: <a class='main-text-a' style='margin-left: 0px;' href='mailto:$form_email'>$form_email</a>";
      // mail created at
      $mail_date = date("d.m.Y в G:i");
      /* === mail === */
      $mail_body = "<!DOCTYPE html><html lang='ru' dir='ltr'> <head> <meta charset='utf-8'> <style>body{padding:0;margin:0;width:100%;font-family:Roboto,sans-serif;}.main{width:calc(100% - 34px);padding:10px;border:2px solid #2a9fd0;border-radius:10px;background-color:#e6e8fc6b;margin:5px}.main-logo{width:100%}.main-logo-ico{display:inline-block;vertical-align:middle;position:relative;height:50px;width:200px;margin-left:15px;background-position:center;background-repeat:no-repeat;background-size:contain}.main-text-title{font-size:25px;font-family:Roboto,sans-serif;font-weight:700;margin-left:15px;margin-top:10px}.main-text-text{font-family:Roboto,sans-serif;margin-left:15px;margin-top:10px;text-align:justify;margin-right:15px}.main-text-a{font-family:Roboto,sans-serif;font-weight:100;margin-left:15px;margin-top:10px;display:inline-block;text-align:justify;margin-right:15px;border-bottom:1px dashed #303036;color:#303036;text-decoration:none}.main-text-a:hover{text-decoration:none;border-bottom:1px solid #303036}.main-text-auto{font-family:Roboto,sans-serif;font-weight:100;margin-left:15px;margin-top:10px;text-align:justify;margin-right:15px;font-style:italic}.main-text-password{display:inline-block;padding:5px 10px;background-color:#2a9fd0;margin-left:15px;font-family:Roboto,sans-serif;font-weight:100;color:#fff;border-radius:5px;margin-top:5px}</style> </head> <body> <div class='main'> <div class='main-logo'><img class='main-logo-ico' src=\"$base64_logo\"></img></div><div class='main-text'> <div class='main-text-title'>Добрый день, $name1!</div><br>$online_text $group_text <div class='main-text-text'>С расписанием Вы можете ознакомиться тут: <a href='$mail_link' target=\"_blank\" class='main-text-a'>Перейти на сайт StudyBuddy</a><br><br></div><br><div class='main-text-auto'>Это письмо сформировано автоматически. Пожалуйста, не отвечайте на него.$mailto_str</div><div class='main-text-text' style='margin-bottom: 15px; margin-top: 25px;'><span>Дата составления письма: </span><span><b>$mail_date</b></span></div></div></div></body></html>";
      // create new mail
      $the_php_mailer = new PHPMailer;
      $the_php_mailer->isSMTP();
      $the_php_mailer->CharSet = "UTF-8";
      $the_php_mailer->SMTPAuth = true;
      $the_php_mailer->Host = $phpmailer_host;
      $the_php_mailer->Username = $phpmailer_username;
      $the_php_mailer->Password = $phpmailer_password;
      $the_php_mailer->SMTPSecure = 'ssl';
      $the_php_mailer->Port = 465;
      $the_php_mailer->setFrom($phpmailer_setfrom_arg_1, $phpmailer_setfrom_arg_2);
      $the_php_mailer->addAddress($email, $name1);
      $the_php_mailer->isHTML(true);
      $the_php_mailer->Subject = 'Оповещение Study Buddy для '.$login;
      $the_php_mailer->Body = $mail_body;
      $the_php_mailer->send();
    }
    exit('OK.');
  }

  // ===========================================================================

  exit('EMPTY.');

?>
