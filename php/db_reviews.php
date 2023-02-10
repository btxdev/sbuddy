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

  $review_regex = '/([^A-Za-zА-Яа-яЁё0-9\,\.\"\'\%\$\#\№\:\;\!\?\[\]\{\}\(\)\=\-\+\*\/\~\@\s])/u';
  $path_to_users_dir = '../users/';

  // === db information ========================================================

  $sql_auth = Array(
  'host' => '127.0.0.1',
  'db' => 'u1878365_sbinsoap',
  'user' => 'u1878365_sbinsoa',
  'password' => '9Y54WG911B',
    'charset' => 'utf8'
  );

  // === PDO ===================================================================

  // establish connection
  $pdo_dsn = "mysql:host=".$sql_auth['host'].";dbname=".$sql_auth['db'].";charset=".$sql_auth['charset'];
  $pdo = new PDO($pdo_dsn, $sql_auth['user'], $sql_auth['password'], $pdo_options);

  // === reviews functions =====================================================



  // === reviews requests ======================================================

  if(isset($_POST['send_review'])) {
    // check authorization
    if(!isset($_SESSION) || !isset($_SESSION['auth']) || !isset($_SESSION['login'])) { exit('AUTH.'); }
    // prepare
    if(!isset($_POST['mark']) || !isset($_POST['text'])) exit('WRONG.');
    $mark = intval($_POST['mark']);
    if($mark < 1 || $mark > 5) exit('WRONG.');
    $text = htmlspecialchars($_POST['text'], ENT_QUOTES);
    if(preg_match($review_regex, $text)) exit('WRONG.');
    if(mb_strlen($text) > 10000) exit('WRONG.');
    // save to db
    try {
      $stmt = $pdo->prepare("SELECT id FROM reviews WHERE userid = ?");
      $stmt->execute([$_SESSION['userid']]);
      $have_review = $stmt->fetchColumn();
      if(empty($have_review)) {
        // insert
        $stmt2 = $pdo->prepare("INSERT INTO `reviews` (`userid`, `username`, `mark`, `text`) VALUES (:userid, :username, :mark, :review_text)");
        $stmt2->execute(Array(
          ':userid' => $_SESSION['userid'],
          ':username' => $_SESSION['login'],
          ':mark' => $mark,
          ':review_text' => $text
        ));
        exit('SET.');
      }
      else {
        // update
        $review_id = intval($have_review);
        $stmt2 = $pdo->prepare("UPDATE `reviews` SET `userid` = :userid, `username` = :username, `mark` = :mark, `text` = :review_text WHERE `id` = :review_id");
        $stmt2->execute(Array(
          ':review_id' => $review_id,
          ':userid' => $_SESSION['userid'],
          ':username' => $_SESSION['login'],
          ':mark' => $mark,
          ':review_text' => $text
        ));
        exit('UPD.');
      }
    }
    catch(Exception $e) {
      debuglog('PDO');
      exit('ERROR.');
    }
    exit();
  }

  // ===========================================================================

  if(isset($_POST['get_my_review'])) {
    // check authorization
    if(!isset($_SESSION) || !isset($_SESSION['auth']) || !isset($_SESSION['login'])) { exit('AUTH.'); }
    //
    try {
      $stmt = $pdo->prepare("SELECT mark, text FROM reviews WHERE userid = ?");
      $stmt->execute([$_SESSION['userid']]);
    }
    catch(Exception $e) {
      debuglog('PDO');
      exit('ERROR.');
    }
    $row = $stmt->fetch(PDO::FETCH_LAZY);
    if(empty($row)) exit('NONE.');
    $output = json_encode((object)Array(
      'mark' => $row['mark'],
      'text' => $row['text']
    ));
    echo('OK.'.$output);
    exit();
  }

  // ===========================================================================

  if(isset($_POST['reviews_listing'])) {
    // prepare
    $end = false;
    $pos = 0;
    $page = 0;
    if(isset($_POST['page'])) {
      $page_val = intval($_POST['page']);
      if($page_val > 0 && $page_val < 2796200) {
        $page = $page_val;
      }
    }
    $pos = $page * 6;
    // reviews count
    try {
      $stmt = $pdo->prepare("SELECT COUNT(*) FROM `reviews`");
      $stmt->execute();
      $row = $stmt->fetchColumn();
      if(empty($row)) {
        exit('EMPTY.');
      }
      $reviews_count = intval($row);
    }
    catch(Exception $e) {
      debuglog('PDO');
      exit('ERROR.');
    }
    if(($reviews_count - (6 * ($page + 1))) <= 0) {
      $end = true;
    }
    // request
    try {
      $stmt = $pdo->prepare("SELECT * FROM `reviews` ORDER BY `publication_date` DESC, `mark` DESC LIMIT :pos, 6");
      $stmt->execute(Array(
        ':pos' => $pos
      ));
    }
    catch(Exception $e) {
      debuglog('PDO');
      exit('ERROR.');
    }
    // finally
    $founded = Array();
    // status
    $founded[] = Array($end);
    // listing
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      // get review data
      $recid = $row['id'];
      $userid = $row['userid'];
      $username = $row['username'];
      $mark = $row['mark'];
      $text = $row['text'];
      $name1 = '';
      $name2 = '';
      $date = $row['publication_date'];
      $icon = 'media/svg/male_avatar.svg';
      $gender = 'male';
      // get profile icon
      try {
        $stmt2 = $pdo->prepare("SELECT `name1`, `name2`, `gender`, `profile_icon` FROM `accounts` WHERE `account_id` = ?");
        $stmt2->execute([$userid]);
        $row2 = $stmt2->fetch();
      }
      catch(Exception $e) {
        debuglog('PDO');
        exit('ERROR.');
      }
      if(!empty($row2)) {
        if(!empty($row2['name1'])) { $name1 = $row2['name1']; }
        if(!empty($row2['name2'])) { $name2 = $row2['name2']; }
        if(!empty($row2['profile_icon'])) {
          $icon = "users/public/$username/avatar.png";
        }
        else {
          if(!empty($row2['gender']) && ($row2['gender'] == 'female')) { $gender = 'female'; }
          $icon = 'media/svg/'.$gender.'_avatar.svg';
        }
      }
      // add
      $founded[] = Array(
        'username' => $username,
        'mark' => $mark,
        'text' => $text,
        'name1' => $name1,
        'name2' => $name2,
        'icon' => $icon,
        'date' => $date
      );
    }
    // check exists
    if(empty($founded)) {
      exit('EMPTY.');
    }
    // json output
    else {
      echo('OK.');
      exit(json_encode($founded));
    }
    exit();
  }

  // ===========================================================================

  if(isset($_POST['cert_listing'])) {
    // prepare
    $end = false;
    $pos = 0;
    $page = 0;
    if(isset($_POST['page'])) {
      $page_val = intval($_POST['page']);
      if($page_val > 0 && $page_val < 2796200) {
        $page = $page_val;
      }
    }
    $pos = $page * 6;
    // certificates count
    try {
      $stmt = $pdo->prepare("SELECT COUNT(*) FROM `certificates`");
      $stmt->execute();
      $row = $stmt->fetchColumn();
      if(empty($row)) {
        exit('EMPTY.');
      }
      $certificates_count = intval($row);
    }
    catch(Exception $e) {
      debuglog('PDO');
      exit('ERROR.');
    }
    if(($certificates_count - (6 * ($page + 1))) <= 0) {
      $end = true;
    }
    // request
    try {
      $stmt = $pdo->prepare("SELECT * FROM `certificates` ORDER BY `publication_date` DESC LIMIT :pos, 6");
      $stmt->execute(Array(
        ':pos' => $pos
      ));
    }
    catch(Exception $e) {
      debuglog('PDO');
      exit('ERROR.');
    }
    // finally
    $founded = Array();
    // status
    $founded[] = Array($end);
    // listing
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      // get review data
      $title = $row['title'];
      $text = $row['text'];
      $issued = $row['issued'];
      $link = $row['link'];
      $date = $row['publication_date'];
      // add
      $founded[] = Array(
        'title' => $title,
        'text' => $text,
        'issued' => $issued,
        'link' => $link,
        'date' => $date
      );
    }
    // check exists
    if(empty($founded)) {
      exit('EMPTY.');
    }
    // json output
    else {
      echo('OK.');
      exit(json_encode($founded));
    }
    exit();
  }

  // ===========================================================================

  if(isset($_POST['cert_listing_2'])) {
    // prepare
    $end = false;
    $pos = 0;
    if(isset($_POST['page'])) {
      $page_val = intval($_POST['page']);
      if($page_val == 1) {
        $pos = $page_val;
      }
    }
    // certificates count
    try {
      $stmt = $pdo->prepare("SELECT COUNT(*) FROM `certificates`");
      $stmt->execute();
      $row = $stmt->fetchColumn();
      if(empty($row)) {
        exit('EMPTY.');
      }
      $certificates_count = intval($row);
    }
    catch(Exception $e) {
      debuglog('PDO');
      exit('ERROR.');
    }
    if(($certificates_count > 2) && ($pos == 0)) $end = false;
    else $end = true;
    // request
    try {
      if($pos == 0) {
        $stmt = $pdo->prepare("SELECT * FROM `certificates` ORDER BY `publication_date` DESC LIMIT 2");
        $stmt->execute();
      }
      else {
        $stmt = $pdo->prepare("SELECT * FROM `certificates` ORDER BY `publication_date` DESC LIMIT 2, :lim");
        $stmt->execute(Array(
          ':lim' => ($certificates_count - 2)
        ));
      }
    }
    catch(Exception $e) {
      debuglog('PDO');
      exit('ERROR.');
    }
    // finally
    $founded = Array();
    // status
    $founded[] = Array($end);
    // listing
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      // get review data
      $title = $row['title'];
      $text = $row['text'];
      $issued = $row['issued'];
      $link = $row['link'];
      $date = $row['publication_date'];
      // add
      $founded[] = Array(
        'title' => $title,
        'text' => $text,
        'issued' => $issued,
        'link' => $link,
        'date' => $date
      );
    }
    // check exists
    if(empty($founded)) {
      exit('EMPTY.');
    }
    // json output
    else {
      echo('OK.');
      exit(json_encode($founded));
    }
    exit();
  }

  // ===========================================================================

  exit();

?>
