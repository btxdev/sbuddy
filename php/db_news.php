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

  $path_to_users = './admin/admin_panel2.0/media/users/';    // FROM ROOT OF SITE !!!
  $path_to_public = $path_to_users.'public/';                                           // FROM ROOT OF SITE !!!
  $path_to_private = $path_to_users.'private/';                                         // FROM ROOT OF SITE !!!

  // === db information ========================================================

  $sql_news = Array(
  'host' => '127.0.0.1',
  'db' => 'u1878365_sbinsoap',
  'user' => 'u1878365_sbinsoa',
  'password' => '9Y54WG911B',
    'charset' => 'utf8'
  );

  // === PDO ===================================================================

  // establish connection
  $pdo_dsn = "mysql:host=".$sql_news['host'].";dbname=".$sql_news['db'].";charset=".$sql_news['charset'];
  $pdo_news = new PDO($pdo_dsn, $sql_news['user'], $sql_news['password'], $pdo_options);

  // === news functions ========================================================

  // === news requests =========================================================

  if(isset($_POST['news_listing'])) {
    // pagination
    $records_limit = 10;
    $pages_limit = 65535;
    $page = 1;
    if(isset($_POST['page'])) {
      $prep = intval($_POST['page']);
      if(($prep > 0) && ($prep < $pages_limit)) {
        $page = $prep;
      }
    }
    // prepare search filter
    $need_search = false;
    $needle = '';
    if(isset($_POST['needle'])) {
      $needle = htmlspecialchars($_POST['needle'], ENT_QUOTES);
      $need_search = true;
    }
    // prepare sorting
    $sort_type = 'date';
    $sort_order = 'DESC';
    if(isset($_POST['sortby'])) {
      $sortby = htmlspecialchars($_POST['sortby'], ENT_QUOTES);
      if($sortby == 'views') {
        $sort_type = 'views';
      }
      if($sortby == 'date') {
        $sort_type = 'date';
      }
    }
    // records count
    $stmt = $pdo_news->prepare("SELECT COUNT(*) FROM `news` WHERE `deleted` = 0 AND `publicated` = 1");
    $stmt->execute();
    $row = $stmt->fetchColumn();
    if(empty($row)) {
      exit('EMPTY.');
    }
    $total_records_count = intval($row);
    // compose request
    // default
    $query = "SELECT `id`, `title`, `attachments`, `account_id`, `publication_date` FROM `news` WHERE `deleted` = 0 AND `publicated` = 1 ";
    $stmt_array = Array();
    // search filter
    if($need_search) {
      $query = $query."AND (`title` LIKE concat('%',:needle1,'%') OR `data` LIKE concat('%',:needle2,'%')) ";
      $stmt_array['needle1'] = $needle;
      $stmt_array['needle2'] = $needle;
    }
    // sorting
    if($sort_type == 'views') {
      $query = $query."ORDER BY `views_total` ".$sort_order." ";
    }
    else {
      $query = $query."ORDER BY `publication_date` ".$sort_order." ";
    }
    // finally
    $query = $query."LIMIT :a,:b";
    $stmt_array['a'] = ($page - 1) * $records_limit;
    $stmt_array['b'] = $records_limit;
    // send request
    try {
      $stmt = $pdo_news->prepare($query);
      $success = $stmt->execute($stmt_array);
      if(!$success) {
        debuglog('', 'news_listing');
        exit('ERROR.');
      }
    }
    catch(Exception $e) {
      debuglog('sql request fail', 'news_listing');
      exit('ERROR.');
    }

    // finally
    $founded = Array();
    // count
    $founded[] = Array($total_records_count, $records_limit);
    // listing
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      // find image in attachments
      $image = 'none';
      $fname = false;
      $accid = $row['account_id'];
      //
      if(isset($row['attachments'])) {
        $attachments = json_decode(html_entity_decode($row['attachments'], ENT_QUOTES));
        if(is_array($attachments) && !empty($attachments)) {
          foreach($attachments as $attachment) {
            if(is_object($attachment) && ($attachment->mime == 'image')) {
              $fname = $attachment->hash;
              break;
            }
            if(is_array($attachment) && ($attachment['mime'] == 'image')) {
              $fname = $attachment['hash'];
              break;
            }
          }
        }
      }
      // get user data
      if($fname !== false) {
        // username
        $username = false;
        try {
          $stmt2 = $pdo_news->prepare("SELECT `account` FROM `accounts` WHERE `account_id` = ?");
          $stmt2->execute([$accid]);
          $username = $stmt2->fetchColumn();
          if(empty($username)) {
            debuglog('user not found', 'news_listing');
          }
        }
        catch(Exception $exc) {
          debuglog('sql request fail', 'news_listing');
        }
        // user folder
        if($username !== false) {
          $image = $path_to_public.$username.'/attachments/record'.$row['id'].'/'.$fname;
        }
      }
      // put data
      $founded[] = Array(
        'id' => $row['id'],
        'title' => $row['title'],
        'image' => $image,
        'date' => $row['publication_date']
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

  if(isset($_POST['news_best'])) {
    // records count
    $stmt = $pdo_news->prepare("SELECT COUNT(*) FROM `news` WHERE `deleted` = 0 AND `publicated` = 1");
    $stmt->execute();
    $row = $stmt->fetchColumn();
    if(empty($row)) {
      exit('EMPTY.');
    }
    $records_count = intval($row);
    if($records_count < 3) {
      exit('FEW.');
    }
    // send request
    try {
      $stmt = $pdo_news->prepare("SELECT `id`, `title`, `data`, `attachments`, `account_id` FROM `news` WHERE `deleted` = 0 AND `publicated` = 1 ORDER BY `views_total` DESC LIMIT 3");
      $success = $stmt->execute();
      if(!$success) {
        debuglog('', 'news_best');
        exit('ERROR.');
      }
    }
    catch(Exception $e) {
      debuglog('sql request fail', 'news_best');
      exit('ERROR.');
    }
    // finally
    $founded = Array();
    // listing
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      // find image in attachments
      $image = 'none';
      $fname = false;
      $accid = $row['account_id'];
      $username = false;
      $first = 'undefined';
      $second = 'undefined';
      $data = $row['data'];
      //
      if(isset($row['attachments'])) {
        $attachments = json_decode(html_entity_decode($row['attachments'], ENT_QUOTES));
        if(is_array($attachments) && !empty($attachments)) {
          foreach($attachments as $attachment) {
            if(is_object($attachment) && ($attachment->mime == 'image')) {
              $fname = $attachment->hash;
              break;
            }
            if(is_array($attachment) && ($attachment['mime'] == 'image')) {
              $fname = $attachment['hash'];
              break;
            }
          }
        }
      }
      // get user data
      // username
      try {
        $stmt2 = $pdo_news->prepare("SELECT `account`, `first_name`, `second_name` FROM `accounts` WHERE `account_id` = ?");
        $stmt2->execute([$accid]);
        $row2 = $stmt2->fetch(PDO::FETCH_LAZY);
        if(empty($row2)) {
          debuglog('user not found', 'news_best');
        }
        else {
          $first = $row2['first_name'];
          $second = $row2['second_name'];
          $username = $row2['account'];
        }
      }
      catch(Exception $exc) {
        debuglog('sql request fail', 'news_best');
      }
      if($username === false) {
        exit('ERROR.');
      }
      // user folder
      if($fname !== false) {
        $image = $path_to_public.$username.'/attachments/record'.$row['id'].'/'.$fname;
      }
      // put data
      $founded[] = Array(
        'id' => $row['id'],
        'title' => $row['title'],
        'first' => $first,
        'second' => $second,
        'data' => mb_substr($data, 0, 2000),
        'image' => $image
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

  exit('EMPTY.');

?>