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

  // check authorization
  if(!isset($_SESSION) || !isset($_SESSION['auth']) || !isset($_SESSION['login'])) { exit('AUTH.'); }

  // === parameters ============================================================

  $time_regex = '/^(0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/';

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

  // establish connection
  $pdo_site_dsn = "mysql:host=".$sql_site['host'].";dbname=".$sql_site['db'].";charset=".$sql_site['charset'];
  $pdo_site = new PDO($pdo_site_dsn, $sql_site['user'], $sql_site['password'], $pdo_options);
  $pdo_ap_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
  $pdo_ap = new PDO($pdo_ap_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);

  // === learning functions ====================================================

  // === learning requests =====================================================

  if(isset($_POST['update_record'])) {
    // prepare
    if(!isset($_POST['learning']) || !isset($_POST['day']) || !isset($_POST['time'])) { exit('WRONG.'); }
    $learning = htmlspecialchars($_POST['learning'], ENT_QUOTES);
    if(!in_array($learning, Array('online', 'group', 'individual'))) { exit('WRONG.'); }
    $day = intval($_POST['day']);
    if($day < 0 || $day > 7) { exit('WRONG.'); }
    $time_str = htmlspecialchars($_POST['time'], ENT_QUOTES);
    if(mb_strlen($time_str) < 11) {
      // remove record
      try {
        $stmt = $pdo_ap->prepare("DELETE FROM `u_custom_timetable` WHERE `user` = :user AND `learning` = :learning AND `day` = :day");
        $stmt->execute(Array(
          ':user' => $_SESSION['userid'],
          ':learning' => $learning,
          ':day' => $day
        ));
      }
      catch(Exception $e) {
        debuglog('ERROR IN FILE '.__FILE__.' AT LINE '.__LINE__.' ERROR EXCEPTION: ');
        debuglog($e);
        exit('ERROR.');
      }
    }
    else {
      // update record
      $time_arr = explode(',', $time_str);
      if(!is_array($time_arr)) { exit('WRONG.'); }
      if(count($time_arr) % 2 != 0) { exit('WRONG.'); }
      foreach($time_arr as $time_e) {
        if(!preg_match($time_regex, $time_e)) { exit('WRONG.'); }
      }
      try {
        $stmt = $pdo_ap->prepare("INSERT INTO `u_custom_timetable` (`user`, `learning`, `day`, `timerange`, `changed`) VALUES (:user, :learning1, :day1, :timerange1, CURRENT_TIMESTAMP()) ON DUPLICATE KEY UPDATE `learning` = :learning2, `day` = :day2, `timerange` = :timerange2, `changed` = CURRENT_TIMESTAMP()");
        $stmt->execute(Array(
          ':user' => $_SESSION['userid'],
          ':learning1' => $learning,
          ':day1' => $day,
          ':timerange1' => $time_str,
          ':learning2' => $learning,
          ':day2' => $day,
          ':timerange2' => $time_str
        ));
      }
      catch(Exception $e) {
        debuglog('ERROR IN FILE '.__FILE__.' AT LINE '.__LINE__.' ERROR EXCEPTION: ');
        debuglog($e);
        exit('ERROR.');
      }
    }
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['load_records'])) {
    $records = Array();
    try {
      $stmt = $pdo_ap->prepare("SELECT * FROM `u_custom_timetable` WHERE `user` LIKE ?");
      $stmt->execute([$_SESSION['userid']]);
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        $records[] = (object)Array(
          'learning' => $row['learning'],
          'day' => $row['day'],
          'timerange' => $row['timerange']
        );
      }
    }
    catch(Exception $e) {
      debuglog('ERROR IN FILE '.__FILE__.' AT LINE '.__LINE__.' ERROR EXCEPTION: ');
      debuglog($e);
      exit('ERROR.');
    }
    echo('OK.');
    echo(json_encode($records));
    exit();
  }

  // ===========================================================================

  if(isset($_POST['im_recorded'])) {
    $output = Array();
    try {
      $stmt = $pdo_ap->prepare("SELECT * FROM `timetable_groups` WHERE `user` LIKE ?");
      $stmt->execute([$_SESSION['userid']]);
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        if(mb_strlen($row['groups']) == 0 || empty($row['groups'])) continue;
        $learning = $row['learning'];
        $groups = explode(',', $row['groups']);
        $notify = !boolval($row['viewed']);
        if($notify) {
          $stmt2 = $pdo_ap->prepare("UPDATE `timetable_groups` SET `viewed` = 1 WHERE `user` = ?");
          $stmt2->execute([$_SESSION['userid']]);
        }
        $output[] = (object)Array(
          'learning' => $learning,
          'groups' => $groups,
          'notify' => $notify
        );
      }
      // add
      //$output = $row['groups_json'];
      // set viewed
      /*if(!boolval($row['viewed'])) {
        $notify = '1';
        $stmt2 = $pdo_ap->prepare("UPDATE `timetable_groups` SET `viewed` = 1 WHERE `user` = ?");
        $stmt2->execute([$_SESSION['userid']]);
      }*/
    }
    catch(Exception $e) {
      debuglog('ERROR IN FILE '.__FILE__.' AT LINE '.__LINE__.' ERROR EXCEPTION: ');
      debuglog($e);
      exit('ERROR.');
    }
    echo('OK.');
    //echo($notify.'.');
    echo(json_encode($output));
    //echo(json_encode($output));
    exit();
  }

  // ===========================================================================

  exit('EMPTY.');

?>
