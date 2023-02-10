<?php

  // === setup =================================================================
  require_once('db_includes.php');
  session_name($sess_name);
  session_start();

  // === check data ============================================================
  if(!isset($_POST['view']) || !isset($_POST['view_key']) || !isset($_POST['record']) || !isset($_POST['id'])) exit();
  function check_data($field) {
    $key = htmlspecialchars($field, ENT_QUOTES);
    $length = mb_strlen($key);
    if(($length > 100) || ($length < 2)) exit();
    else return $key;
  }
  function check_data2($field) {
    $val = htmlspecialchars($field, ENT_QUOTES);
    $ival = intval($val);
    if($ival < 0 || $ival > 999999999) exit();
    return $ival;
  }
  $view = check_data($_POST['view']); // array key name
  $view_key = check_data($_POST['view_key']); // array value in key
  $record = check_data2($_POST['record']); // record id
  $id = check_data($_POST['id']); // user token
  if(!isset($_SESSION[$view])) exit();
  if($_SESSION[$view] != $view_key) exit();

  // === connect to DB =========================================================
  $sql = $sql_ap;
  $pdo_dsn = "mysql:host=".$sql['host'].";dbname=".$sql['db'].";charset=".$sql['charset'];
  $pdo = new PDO($pdo_dsn, $sql['user'], $sql['password'], $pdo_options);

  // === SQL ===================================================================
  $founded = false;
  // check table exists
  try {
    $sql = 'SELECT 1 FROM';
    $sql .= ' `news__id';
    $sql .= strval($record);
    $sql .= "` LIMIT 1";
    $stmt = $pdo->prepare($sql);
  }
  catch(Exception $e) {
    exit();
  }
  // check record exists
  $stmt = $pdo->prepare("SELECT `visitor` FROM `news__id".strval($record)."` WHERE `visitor` LIKE ? LIMIT 1");
  try {
    $stmt->execute([strval($id)]);
  }
  catch(Exception $e) {
    debuglog('sql error: check record exists', 'view_key');
    exit();
  }
  $row = $stmt->fetch(PDO::FETCH_LAZY);
  if(!empty($row)) $founded = true;
  // update news__id(ID)
  $stmt = $pdo->prepare("INSERT INTO `news__id".strval($record)."` (`visitor`, `ip`, `view_time`, `view_percent`, `view_date`) VALUES (:visitor, :ip, :view_time, :view_percent, CURRENT_TIMESTAMP())");
  try {
  $stmt->execute(Array(
    ':visitor' => $id,
    ':ip' => $_SERVER['REMOTE_ADDR'],
    ':view_time' => 5,
    ':view_percent' => 0
  ));
  }
  catch(Exception $e) {
    debuglog('sql error: update news__id(ID)', 'view_key');
    exit();
  }
  // update news
  $stmt = '';
  if($founded) {
    // old user
    $stmt = $pdo->prepare('UPDATE `news` SET `views_total` = `views_total` + 1 WHERE `id` = :recordid');
  }
  else {
    // new user
    $stmt = $pdo->prepare('UPDATE `news` SET `views_total` = `views_total` + 1, `visitors_total` = `visitors_total` + 1 WHERE `id` = :recordid');
  }
  try {
    $stmt->execute(Array(
      ':recordid' => $record
    ));
  }
  catch(Exception $e) {
    debuglog('sql error: update news', 'view_key');
    exit();
  }
  //
  exit('OK.');
?>