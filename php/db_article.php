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

  // === db information ========================================================

  $sql_site = Array(
  'host' => '127.0.0.1',
  'db' => 'u1878365_sbinsoap',
  'user' => 'u1878365_sbinsoa',
  'password' => '9Y54WG911B',
    'charset' => 'utf8'
  );

  $sql_ap = Array(
  'host' => '127.0.0.1',
  'db' => 'u1878365_sbinsoap',
  'user' => 'u1878365_sbinsoa',
  'password' => '9Y54WG911B',
    'charset' => 'utf8'
  );

  // === PDO ===================================================================

  // site
  $pdo_dsn = "mysql:host=".$sql_site['host'].";dbname=".$sql_site['db'].";charset=".$sql_site['charset'];
  $pdo_site = new PDO($pdo_dsn, $sql_site['user'], $sql_site['password'], $pdo_options);

  // admin panel
  $pdo_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
  $pdo_ap = new PDO($pdo_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);

  // === article functions =====================================================

  // === article requests ======================================================

  if(isset($_POST['get_mark'])) {
    if(!isset($_SESSION) || !isset($_SESSION['auth']) || !isset($_SESSION['userid'])) exit('AUTH.');
    $record = intval($_POST['record']);
    if(($record < 1) || ($record > 9999999)) exit('WRONG.');
    try {
      $stmt = $pdo_site->prepare("SELECT `mark` FROM `news_marks` WHERE `user_id` = :user_id AND `record_id` = :record_id");
      $stmt->execute(Array(
        ':user_id' => $_SESSION['userid'],
        ':record_id' => $record
      ));

    }
    catch(Exception $e) {
      debuglog('sql request fail '.__FILE__.' IN LINE '.__LINE__);
      exit('ERROR.');
    }
    $row = $stmt->fetchColumn();
    if(empty($row)) {
      exit('NONE.');
    }
    else {
      if($row == 'like') {
        exit('LIKE.');
      }
      else if($row == 'dislike') {
        exit('DISLIKE.');
      }
      else {
        exit('NONE.');
      }
    }
    exit();
  }

  // ===========================================================================

  if(isset($_POST['set_mark'])) {
    // prepare
    if(!isset($_SESSION) || !isset($_SESSION['auth']) || !isset($_SESSION['userid'])) exit('AUTH.');
    if(!isset($_POST['mark']) || !isset($_POST['record'])) exit('WRONG.');
    $mark = htmlspecialchars($_POST['mark']);
    if($mark != 'none' && $mark != 'like' && $mark != 'dislike') exit('WRONG.');
    $record = intval($_POST['record']);
    if(($record < 1) || ($record > 9999999)) exit('WRONG.');
    // get marks count
    try {
      $stmt = $pdo_ap->prepare("SELECT `marks_count`, `marks_likes` FROM `news` WHERE `id` = ? LIMIT 1");
      $stmt->execute([$record]);
    }
    catch(Exception $e) {
      debuglog('sql request fail '.__FILE__.' IN LINE '.__LINE__);
      exit('ERROR.');
    }
    $row = $stmt->fetch();
    if(empty($row) || !isset($row['marks_count']) || !isset($row['marks_likes'])) {
      exit('WRONG.');
    }
    $count = intval($row['marks_count']);
    $likes = intval($row['marks_likes']);
    $dislikes = $count - $likes;
    if($dislikes < 0) $dislikes = 0;
    if($likes < 0) $likes = 0;
    if($count < 0) $count = 0;
    // get old mark
    try {
      $stmt = $pdo_site->prepare("SELECT `mark` FROM `news_marks` WHERE `user_id` = :user_id AND `record_id` = :record_id");
      $stmt->execute(Array(
        ':user_id' => $_SESSION['userid'],
        ':record_id' => $record
      ));
    }
    catch(Exception $e) {
      debuglog('sql request fail '.__FILE__.' IN LINE '.__LINE__);
      exit('ERROR.');
    }
    $db_mark = $stmt->fetchColumn();
    if($db_mark != 'like' && $db_mark != 'dislike') {
      $db_mark = 'none';
    }
    // update likes/dislikes
    if($db_mark == 'none') {
      if($mark == 'like') {
        $likes++;
        $db_mark = 'like';
      }
      else if($mark == 'dislike') {
        $dislikes++;
        $db_mark = 'dislike';
      }
      else {}
    }
    else if($db_mark == 'like') {
      if($mark == 'like') {
        $likes--;
        $db_mark = 'none';
      }
      else if($mark == 'dislike') {
        $likes--;
        $dislikes++;
        $db_mark = 'dislike';
      }
      else {}
    }
    else if($db_mark == 'dislike') {
      if($mark == 'like') {
        $likes++;
        $dislikes--;
        $db_mark = 'like';
      }
      else if($mark == 'dislike') {
        $dislikes--;
        $db_mark = 'none';
      }
      else {}
    }
    else {}
    // update marks count in ap db
    $new_count = $dislikes + $likes;
    if($dislikes < 0) $dislikes = 0;
    if($likes < 0) $likes = 0;
    if($new_count < 0) $new_count = 0;
    try {
      $stmt = $pdo_ap->prepare('UPDATE `news` SET `marks_count` = :marks_count, `marks_likes` = :marks_likes WHERE `id` = :record_id');
      $stmt->execute(Array(
        ':record_id' => $record,
        ':marks_count' => $new_count,
        ':marks_likes' => $likes
      ));
    }
    catch(Exception $e) {
      debuglog('sql request fail '.__FILE__.' IN LINE '.__LINE__);
      exit('ERROR.');
    }
    // update mark
    try {
      $stmt = $pdo_site->prepare("INSERT INTO `news_marks` (`user_id`, `record_id`, `mark`) VALUES (:user_id, :record_id, :mark1) ON DUPLICATE KEY UPDATE `mark` = :mark2");
      $stmt->execute(Array(
        ':user_id' => $_SESSION["userid"],
        ':record_id' => $record,
        ':mark1' => $db_mark,
        ':mark2' => $db_mark
      ));
    }
    catch(Exception $e) {
      debuglog('sql request fail '.__FILE__.' IN LINE '.__LINE__);
      exit('ERROR.');
    }
    exit('OK.');
  }

  // ===========================================================================

  exit();

?>
