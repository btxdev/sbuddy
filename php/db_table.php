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

  // === PDO ===================================================================

  // establish connection to Admin Panel
  $pdo_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
  $pdo_ap = new PDO($pdo_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);

  // establish connection to Site DB
  $pdo_dsn = "mysql:host=".$sql_site['host'].";dbname=".$sql_site['db'].";charset=".$sql_site['charset'];
  $pdo_site = new PDO($pdo_dsn, $sql_site['user'], $sql_site['password'], $pdo_options);

  // === timetable functions ===================================================

  function timetable_load($date) {
    global $pdo_ap;
    // format
    $tasks_date_regex = '/(\d{2}).(\d{2}).(\d{4})/';
    // check task date format
    $task_date = htmlspecialchars($date, ENT_QUOTES);
    $day_of_week = is_day_of_week($task_date);
    if($day_of_week !== false) {
      $task_type = 'regular';
      $task_date = $day_of_week;
    }
    else if(preg_match($tasks_date_regex, $task_date)) {
      $task_type = 'exception';
    }
    else {
      exit('WRONG.');
    }
    // check data
    try {
      $stmt = $pdo_ap->prepare("SELECT `task_json` FROM `tasks` WHERE `task_date` = ? LIMIT 1");
      $stmt->execute([$task_date]);
      $row = $stmt->fetch(PDO::FETCH_LAZY);
      if(empty($row) || !isset($row['task_json'])) {
        if($task_type == 'exception') {
          return 'empty';
        }
        else {
          exit('EMPTY.');
        }
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

  // === timetable requests ====================================================

  if(isset($_POST['timetable_list'])) {
    // empty fields
    $epmty_fields = Array(true, true, true, true, true, true, true);
    // get list
    $the_list = Array();
    try {
      $stmt = $pdo_ap->prepare("SELECT `task_date` FROM `tasks`");
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

  if(isset($_POST['timetable_load'])) {
    // check task date exists
    if(!isset($_POST['date'])) {
      exit('WRONG.');
    }
    $status = timetable_load($_POST['date']);
    if($status == 'empty') {
      $day_of_week = strtolower(date('l', strtotime($_POST['date'])));
      timetable_load($day_of_week);
    }
    exit('EMPTY.');
  }

  // ===========================================================================

  exit();

?>