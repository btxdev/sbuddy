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

  include_once('../db_includes.php');

  session_name($sess_name);
  session_start();

  if(!isset($_SESSION) || !isset($_SESSION['userid'])) exit('AUTH.');

  ini_set('memory_limit', '60M');
  ini_set('post_max_size', '50M');
  ini_set('upload_max_filesize', '20M');

  // === parameters ============================================================

  $finder_users_dir = '../../USERS_FILES/';                                     // SITE-USERS FILES
  $finder_ap_users_dir = '../media/users/public/';                              // AP-USERS FILES

  $users_folder = '../../../users/';
  $attachments_folder = '../../../media/msga/';
  $msg_h_folder = '../../../php/chat/msg/';

  // === PDO ===================================================================

  // establish connection
  $pdo_dsn = "mysql:host=".$sql_site['host'].";dbname=".$sql_site['db'].";charset=".$sql_site['charset'];
  $pdo = new PDO($pdo_dsn, $sql_site['user'], $sql_site['password'], $pdo_options);

  // === chat functions ========================================================

  function gen_token($length = null) {
    if(is_null($length)) $length = 32;
    if(function_exists('random_bytes')) {
      return bin2hex(random_bytes($length));
    }
    if(function_exists('mcrypt_create_iv')) {
      return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
    }
    if(function_exists('openssl_random_pseudo_bytes')) {
      return bin2hex(openssl_random_pseudo_bytes($length));
    }
  }

  function create_directory($path) {
    return is_dir($path) || mkdir($path, 0700, true);
  }

  function log_action($action) {
    global $pdo;
    function log_action_method($action_str) {
      global $pdo;
      try {
        $stmt = $pdo->prepare("INSERT INTO `chat__actions` (`who`, `ap_user`, `action`, `act_date`) VALUES(:who, :ap_user, :action1, CURRENT_TIMESTAMP()) ON DUPLICATE KEY UPDATE `action` = :action2, `act_date` = CURRENT_TIMESTAMP()");
        $stmt->execute(Array(
          ':who' => $_SESSION['userid'],
          ':ap_user' => 1,
          ':action1' => $action_str,
          ':action2' => $action_str,
        ));
      }
      catch(Exception $e) {
        return false;
      }
    }
    if(!isset($_SESSION['chat_action_timestamp'])) $_SESSION['chat_action_timestamp'] = time() - 61;
    if(isset($_SESSION['chat_action']) && ($_SESSION['chat_action'] == $action)) {
      if((time() - $_SESSION['chat_action_timestamp']) < 60) {
        return false;
      }
      else {
        $_SESSION['chat_action_timestamp'] = time();
      }
    }
    // user is online (default action)
    if(($action === 0) && isset($_SESSION['chat_action']) && ($_SESSION['chat_action'] !== 2)) {
      log_action_method('online');
    }
    // user is offline
    if($action === 1) {
      log_action_method('offline');
    }
    // user prints a message
    if($action === 2) {
      log_action_method('prints');
    }
    // user does not print message or user sent message
    if($action === 3) {
      log_action_method('online');
    }
    $_SESSION['chat_action'] = $action;
    return true;
  }

  function send_msg($msg) {
    global $pdo;
    global $users_folder;
    global $msg_h_folder;
    global $attachments_folder;
    // prepare attachments
    if(!isset($_SESSION['attachments_data'])) $_SESSION['attachments_data'] = Array();
    // send
    log_action(3);
    $msg_id = -1;
    try {
      // save msg to db
      $stmt = $pdo->prepare("INSERT INTO `chat__messages` (`who`, `ap_user`, `message`, `attachments`, `was_read`, `deleted`, `edited`) VALUES(:who, :ap_user, :message, :attachments, :was_read, :deleted, :edited)");
      $stmt->execute(Array(
        ':who' => $_SESSION['userid'],
        ':ap_user' => 1,
        ':message' => $msg,
        ':attachments' => json_encode($_SESSION['attachments_data']),
        ':was_read' => 0,
        ':deleted' => 0,
        ':edited' => 0
      ));
      // var_dump($stmt);
      // send msg to all online-users
      $msg_id = $pdo->lastInsertId();
      $online = who_online();
      if(is_array($online)) {
        foreach($online as $msg_u) {
          $msg_u_id = $msg_u->who;
          $is_admin = $msg_u->admin;
          //if($msg_u_id == $_SESSION['userid']) continue;
          $msg_file = '';
          if($is_admin) {
            $msg_file = $msg_h_folder.'msg_ap_id'.$msg_u_id.'.txt';
          }
          else {
            $msg_file = $msg_h_folder.'msg_uid'.$msg_u_id.'.txt';
          }
          create_directory($msg_h_folder);
          if(file_exists($msg_file)) {
            $the_file_d = fopen($msg_file, 'a');
            fwrite($the_file_d, ','.$msg_id);
            fclose($the_file_d);
          }
          else {
            $the_file_d = fopen($msg_file, 'a');
            fwrite($the_file_d, $msg_id);
            fclose($the_file_d);
          }
        }
      }
    }
    catch(Exception $e) {
      return false;
    }
    $_SESSION['attachments_data'] = Array();
    return $msg_id;
  }

  function who_online() {
    global $pdo;
    $online_list = Array();
    try {
      $stmt = $pdo->prepare("SELECT `who`, `ap_user` FROM `chat__actions` WHERE `action` != 'offline'");
      $stmt->execute();
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        // online
        $online_list[] = (object)Array(
          'who' => $row['who'],
          'admin' => boolval($row['ap_user'])
        );
      }
    }
    catch(Exception $e) {
      return false;
    }
    return $online_list;
  }

  function who_online_details() {
    global $pdo_options;
    global $sql_ap;
    global $pdo;
    // PDO Admin Panel
    $pdo_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
    $pdo_ap = new PDO($pdo_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);
    //
    log_action(0);
    // actions
    $online_list = Array();
    try {
      $stmt = $pdo->prepare("SELECT * FROM `chat__actions`");
      $stmt->execute();
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        // get row
        $userid = $row['who'];
        $admin = boolval($row['ap_user']);
        $action = $row['action'];
        $date = $row['act_date'];
        $online = ($action == 'offline') ? false : true;
        // check action date
        $action_timestamp = strtotime($date);
        $current_timestamp = time();
        // remove action if it's old
        if($online && (($current_timestamp - $action_timestamp) > 900)) {
          $stmt2 = $pdo->prepare("UPDATE `chat__actions` SET `action` = :action WHERE `who` LIKE :who");
          $stmt2->execute(Array(
            ':action' => 'offline',
            ':who' => $userid
          ));
        }
        // get user data
        if($admin) {
          // get from AP
          $stmt2 = $pdo_ap->prepare("SELECT `account`, `first_name`, `second_name`, `gender`, `profile_icon` FROM `accounts` WHERE `account_id` LIKE ?");
          $stmt2->execute([$userid]);
          $row2 = $stmt2->fetch(PDO::FETCH_LAZY);
          // online
          $online_list[] = (object)Array(
            'id' => $userid,
            'admin' => $admin,
            'blocked' => false,
            'online' => $online,
            'login' => $row2['account'],
            'name1' => $row2['first_name'],
            'name2' => $row2['second_name'],
            'gender' => $row2['gender'],
            'icon' => $row2['profile_icon']
          );
        }
        else {
          // default user
          $stmt2 = $pdo->prepare("SELECT `account`, `name1`, `name2`, `gender`, `profile_icon`, `chat_blocked` FROM `accounts` WHERE `account_id` LIKE ?");
          $stmt2->execute([$userid]);
          $row2 = $stmt2->fetch(PDO::FETCH_LAZY);
          // online
          $online_list[] = (object)Array(
            'id' => $userid,
            'admin' => $admin,
            'blocked' => $row2['chat_blocked'],
            'online' => $online,
            'login' => $row2['account'],
            'name1' => $row2['name1'],
            'name2' => $row2['name2'],
            'gender' => $row2['gender'],
            'icon' => $row2['profile_icon']
          );
        }
      }
    }
    catch(Exception $e) {
      return false;
    }
    return $online_list;
  }

  // did someone send messages?
  function msg_monitor() {
    global $msg_h_folder;
    // i am online
    log_action(0);
    // read my file
    $msg_file = $msg_h_folder.'msg_ap_id'.$_SESSION['userid'].'.txt';
    if(file_exists($msg_file)) {
      $msg_id_arr = explode(',', file_get_contents($msg_file));
      unlink($msg_file);
      return $msg_id_arr;
    }
    return false;
  }

  // read msg history
  function read_messages($when = null) {
    global $pdo_options;
    global $sql_ap;
    global $pdo;
    // PDO Admin Panel
    $pdo_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
    $pdo_ap = new PDO($pdo_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);
    // read messages
    if(is_null($when)) { $when = 0; }
    $messages = Array();
    $users = Array();
    try {
      $stmt = $pdo->prepare("SELECT * FROM `chat__messages` ORDER BY `id` DESC LIMIT ?,60");
      $stmt->execute([$when]);
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        // get msg data
        $userid = $row['who'];
        // get user data
        // admin
        if($row['ap_user']) {
          $stmt2 = $pdo_ap->prepare("SELECT `account`, `first_name`, `second_name`, `gender`, `profile_icon` FROM `accounts` WHERE `account_id` LIKE ?");
          $stmt2->execute([$userid]);
          $row2 = $stmt2->fetch(PDO::FETCH_LAZY);
          $messages[] = (object)Array(
            'admin' => true,
            'message' => (object)Array(
              'id' => $row['id'],
              'text' => $row['message'],
              'date' => $row['msg_date'],
              'deleted' => $row['deleted']
            ),
            'user' => (object)Array(
              'id' => $userid,
              'login' => $row2['account'],
              'name1' => $row2['first_name'],
              'name2' => $row2['second_name'],
              'gender' => $row2['gender'],
              'icon' => $row2['profile_icon']
            ),
            'attachments' => json_decode($row['attachments'])
          );
        }
        // default
        else {
          if(!array_key_exists($userid, $users)) {
            $stmt2 = $pdo->prepare("SELECT `account`, `name1`, `name2`, `gender`, `profile_icon` FROM `accounts` WHERE `account_id` LIKE ?");
            $stmt2->execute([$userid]);
            $row2 = $stmt2->fetch(PDO::FETCH_LAZY);
            $users[$userid] = (object)Array(
              'id' => $userid,
              'login' => $row2['account'],
              'name1' => $row2['name1'],
              'name2' => $row2['name2'],
              'gender' => $row2['gender'],
              'icon' => $row2['profile_icon']
            );
          }
          $messages[] = (object)Array(
            'admin' => false,
            'message' => (object)Array(
              'id' => $row['id'],
              'text' => $row['message'],
              'date' => $row['msg_date'],
              'deleted' => $row['deleted']
            ),
            'user' => $users[$userid],
            'attachments' => json_decode($row['attachments'])
          );
        }
      }
    }
    catch(Exception $e) {
      return false;
    }
    // output
    return $messages;
  }

  function read_last_messages() {
    global $pdo_options;
    global $sql_ap;
    global $pdo;
    // PDO Admin Panel
    $pdo_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
    $pdo_ap = new PDO($pdo_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);
    // check
    $msg_id_arr = msg_monitor();
    if(!is_array($msg_id_arr)) return Array();
    // get messages
    $messages = Array();
    $users = Array();
    foreach($msg_id_arr as $msg_id) {
      try {
        $stmt = $pdo->prepare("SELECT * FROM `chat__messages` WHERE `id` LIKE ?");
        $stmt->execute([$msg_id]);
        $row = $stmt->fetch(PDO::FETCH_LAZY);
        if(!empty($row)) {
          // get msg data
          $userid = $row['who'];
          // get user data
          if($row['ap_user']) {
            $stmt2 = $pdo_ap->prepare("SELECT `account`, `first_name`, `second_name`, `gender`, `profile_icon` FROM `accounts` WHERE `account_id` LIKE ?");
            $stmt2->execute([$userid]);
            $row2 = $stmt2->fetch(PDO::FETCH_LAZY);
            $messages[] = (object)Array(
              'admin' => true,
              'message' => (object)Array(
                'id' => $row['id'],
                'text' => $row['message'],
                'date' => $row['msg_date'],
                'deleted' => $row['deleted']
              ),
              'user' => (object)Array(
                'id' => $userid,
                'login' => $row2['account'],
                'name1' => $row2['first_name'],
                'name2' => $row2['second_name'],
                'gender' => $row2['gender'],
                'icon' => $row2['profile_icon']
              ),
              'attachments' => json_decode($row['attachments'])
            );
          }
          // default
          else {
            if(!array_key_exists($userid, $users)) {
              $stmt2 = $pdo->prepare("SELECT `account_id`, `account`, `name1`, `name2`, `gender`, `profile_icon` FROM `accounts` WHERE `account_id` LIKE ?");
              $stmt2->execute([$userid]);
              $row2 = $stmt2->fetch(PDO::FETCH_LAZY);
              $users[$userid] = (object)Array(
                'id' => $row2['account_id'],
                'login' => $row2['account'],
                'name1' => $row2['name1'],
                'name2' => $row2['name2'],
                'gender' => $row2['gender'],
                'icon' => $row2['profile_icon']
              );
            }
            $messages[] = (object)Array(
              'message' => (object)Array(
                'id' => $row['id'],
                'text' => $row['message'],
                'date' => $row['msg_date'],
                'deleted' => $row['deleted']
              ),
              'user' => $users[$userid],
              'attachments' => json_decode($row['attachments'])
            );
          }
        }
      }
      catch(Exception $e) {
        return false;
      }
    }
    return $messages;
  }

  function current_attachments() {
    if(isset($_SESSION['attachments_data'])) {
      return $_SESSION['attachments_data'];
    }
    else {
      return Array();
    }
  }

  function msg_delete_prop($id, $delete) {
    global $pdo;
    try {
      $stmt = $pdo->prepare("UPDATE `chat__messages` SET `deleted` = :deleted WHERE `id` LIKE :msgid");
      $stmt->execute(Array(
        ':msgid' => $id,
        ':deleted' => intval($delete)
      ));
    }
    catch(Exception $e) { return false; }
    return true;
  }

  function user_ban_prop($id, $ban) {
    global $pdo;
    try {
      $stmt = $pdo->prepare("UPDATE `accounts` SET `chat_blocked` = :banned WHERE `account_id` LIKE :userid");
      $stmt->execute(Array(
        ':userid' => $id,
        ':banned' => intval($ban)
      ));
    }
    catch(Exception $e) { return false; }
    return true;
  }

  // === finder functions ======================================================

  function file_is_forbidden($filename) {
    if(mb_substr($filename, 0, 3) == '.ht') return true;
    if(mb_substr($filename, -4) == '.ini') return true;
    if(mb_substr($filename, -4) == '.lnk') return true;
    return false;
  }

  function finder_prepare_filename($filename) {
    $new_filename = '';
    $len = mb_strlen($filename, 'UTF-8');
    $dot = 0;
    for($p = 0; $p < $len; $p++) {
      // char
      $s = mb_substr($filename, $p, 1, 'UTF-8');
      // is dot
      if($s == '.' || $s == ',') {
        $dot++;
        // is first
        if($p == 0) {
          $new_filename = $new_filename.'_';
          continue;
        }
      }
      else { $dot = 0; }
      // prev is dot
      if($dot > 1) {
        continue;
      }
      // check by regex
      if(preg_match('/^[А-ЯЁа-яё]+$/u', $s) || preg_match('/^([^*|:;"\'<>?\/\\\\])+$/ui', $s)) {
        $new_filename = $new_filename.$s;
      }
      else {
        $new_filename = $new_filename.'_';
      }
    }
    return $new_filename;
  }

  // === chat requests =========================================================

  if(isset($_POST['act_prints'])) {
    log_action(2);
    exit();
  }

  // ===========================================================================

  if(isset($_POST['act_not_prints'])) {
    log_action(3);
    exit();
  }

  // ===========================================================================

  if(isset($_POST['act_exit'])) {
    log_action(1);
    exit();
  }

  // ===========================================================================

  if(isset($_POST['send_message'])) {
    // prepare message
    $msg = htmlspecialchars($_POST['send_message'], ENT_HTML5);
    // check string length
    $msg_test = preg_replace('/\s+/', '', $msg);
    $msg_test = preg_replace('/[\n\r]/', '', $msg_test);
    $len = mb_strlen($msg_test);
    // prepare message
    $msg = preg_replace('/[\n\r]/', '<br>', $msg);
    // attachments
    $count = 0;
    if(isset($_SESSION['attachments_data'])) $count = count($_SESSION['attachments_data']);
    // check string length
    if((($len == 0) && ($count <= 1)) || $len > 65534) exit('WRONG.');
    // send
    $status = send_msg($msg);
    if($status !== false) {
      echo('OK.');
      echo($status);
      exit();
    }
    else {
      exit('ERROR.');
    }
    exit();
  }

  // ===========================================================================

  if(isset($_POST['attach_files'])) {
    log_action(0);
    // message token
    if(!isset($_SESSION['attachments_data']) || !is_array($_SESSION['attachments_data']) || !isset($_SESSION['attachments_data'][0])) {
      // create token
      $msg_token = 'msg_'.gen_token(16);
      // attachments data
      $export_data = Array(
        0 => $msg_token
      );
    }
    else {
      // use old token
      $msg_token = $_SESSION['attachments_data'][0];
      // attachments data
      $export_data = $_SESSION['attachments_data'];
    }
    // check attachments size
    if(count($export_data) > 4) exit('COUNT_LIM.');
    // check files corruption
    if(!isset($_FILES) || (count($_FILES) <= 0)) exit('INVALID_PARAMETERS.');
    // check files count
    if(count($_FILES) > 4) exit('COUNT_LIM.');
    // upload files
    for($f = 0; $f < count($_FILES); $f++) {
      // check file corruption
      if (!isset($_FILES[$f]['error']) || is_array($_FILES[$f]['error'])) {
        exit('INVALID_PARAMETERS.');
      }
      switch($_FILES[$f]['error']) {
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
      // check file size
      if ($_FILES[$f]['size'] > 209715200) { // 200 MB
        exit('LIMIT.');
      }
      // =======================================================================
      // get filename
      $filename = $_FILES[$f]['name'];
      // get extension
      $ext = mb_substr($filename, mb_strripos($filename, '.') + 1);
      // check extension
      if(!in_array($ext, $document_extensions)) exit('MIME.');
      // check filename
      $filename = finder_prepare_filename($filename);
      // generate new filename
      if(file_is_forbidden($filename) || in_array($filename, $bad_filenames_array)) { $filename = 'file_'.gen_token(16).'.'.$ext; }
      // file location
      $current_catalog = $finder_ap_users_dir.$_SESSION['username'].'/Общий чат - Вложения/'.$msg_token.'/';
      create_directory($finder_ap_users_dir);
      create_directory($finder_ap_users_dir.$_SESSION['username']);
      create_directory($finder_ap_users_dir.$_SESSION['username'].'/Общий чат - Вложения/');
      create_directory($current_catalog);
      // check file exists
      $counter = 0;
      $not_free = true;
      $new_filename = $filename;
      while($not_free) {
        // create
        if($counter > 0) {
          $new_filename = '('.$counter.') '.$filename;
        }
        else {
          $new_filename = $filename;
        }
        // check
        if(!file_exists($current_catalog.$new_filename)) {
          $not_free = false;
        }
        // limiter
        if($counter > 100) {
          exit('ERROR.');
        }
        // counter
        $counter++;
      }
      $filename = $new_filename;
      //
      // file
      //$file_token = 'file_'.gen_token(16);
      //$filename = $_FILES[$f]['name'];
      // location
      //$current_catalog = $finder_users_dir.$_SESSION['login'].'/Общий чат - Вложения/'.$msg_token.'/';
      //create_directory($finder_users_dir);
      //create_directory($finder_users_dir.$_SESSION['login']);
      //create_directory($finder_users_dir.$_SESSION['login'].'/Общий чат - Вложения/');
      //create_directory($current_catalog);
      //
      // =======================================================================
      // check memory
      //$used = finder_how_much_memory_used() + $_FILES[$f]['size'];
      //$available = finder_how_much_memory_available();
      //if($used > $available) exit('MEMORY_LIM.');
      //finder_update_memory($used);
      // save file
      if(!move_uploaded_file($_FILES[$f]['tmp_name'], $current_catalog.$filename)) {
        exit('DOWNLOADING_ERROR.');
      }
      // export
      $export_data[] = (object)Array(
        'filename' => $filename,
        'token' => $filename
      );
    }
    // end
    echo('OK.');
    // save data to session
    $_SESSION['attachments_data'] = $export_data;
    // export data
    echo(json_encode($export_data));
    exit();
  }

  // ===========================================================================

  if(isset($_POST['remove_attachment'])) {
    log_action(0);
    if(!isset($_SESSION['attachments_data']) || !isset($_SESSION['attachments_data'][0])) {
      exit('OK.');
    }
    //$msg_token = htmlspecialchars($_POST['message'], ENT_QUOTES);
    $msg_token = $_SESSION['attachments_data'][0];
    $file_token = htmlspecialchars($_POST['file'], ENT_QUOTES);
    // remove file
    $filepath = $finder_ap_users_dir.$_SESSION['username'].'/Общий чат - Вложения/'.$msg_token.'/'.$file_token;
    if(file_exists($filepath)) {
      try {
        unlink($filepath);
      }
      catch(Exception $e) {}
    }
    // remove form session data
    if(isset($_SESSION['attachments_data']) && is_array($_SESSION['attachments_data']) && isset($_SESSION['attachments_data'][0])) {
      foreach($_SESSION['attachments_data'] as $key => $value) {
        if($key == 0) {
          //if($value != $msg_token) break;
        }
        else {
          if($value->token == $file_token) {
            unset($_SESSION['attachments_data'][$key]);
            break;
          }
        }
      }
    }
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['online_list'])) {
    $online = who_online_details();
    if($online === false) {
      exit('ERROR.');
    }
    else {
      echo('OK.');
      echo(json_encode($online));
    }
    exit();
  }

  // ===========================================================================

  if(isset($_POST['read_last'])) {
    $messages = read_last_messages();
    if($messages === false) {
      exit('ERROR.');
    }
    else {
      echo('OK.');
      echo(json_encode($messages));
    }
    exit();
  }

  // ===========================================================================

  if(isset($_POST['read_messages'])) {
    // prepare id
    $id = intval(htmlspecialchars($_POST['read_messages'], ENT_QUOTES));
    if($id < 0 || $id > 4294967294) $id = 0;
    // read
    $messages = read_messages($id);
    if($messages === false) {
      exit('ERROR.');
    }
    else {
      echo('OK.');
      echo(json_encode($messages));
    }
    exit();
  }

  // ===========================================================================

  if(isset($_POST['current_attachments'])) {
    echo('OK.');
    echo(json_encode(current_attachments()));
    exit();
  }

  // ===========================================================================

  if(isset($_POST['msg_delswitch'])) {
    // prepare
    $delete = 0;
    if(isset($_POST['mode'])) { if(htmlspecialchars($_POST['mode'], ENT_QUOTES) == 'delete') $delete = 1; }
    $msgid = intval($_POST['msg_delswitch']);
    $status = msg_delete_prop($msgid, $delete);
    if($status) {
      exit('OK.');
    }
    else {
      exit('ERROR.');
    }
    exit();
  }

  // ===========================================================================

  if(isset($_POST['user_banswitch'])) {
    // prepare
    $ban = 0;
    if(isset($_POST['mode'])) { if(htmlspecialchars($_POST['mode'], ENT_QUOTES) == 'ban') $ban = 1; }
    $userid = intval($_POST['user_banswitch']);
    $status = user_ban_prop($userid, $ban);
    if($status) {
      exit('OK.');
    }
    else {
      exit('ERROR.');
    }
    exit();
  }

  // ===========================================================================

  exit();

?>