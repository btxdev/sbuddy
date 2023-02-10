<?php

  // === setup =================================================================
  require_once('php/db_includes.php');
  create_default_session();

  // === load Admin Panel data from DB =========================================
	// establish connection
  $pdo_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
  $pdo_ap = new PDO($pdo_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);
  // === load site data from DB ================================================
  // establish connection
  $pdo_dsn = "mysql:host=".$sql_site['host'].";dbname=".$sql_site['db'].";charset=".$sql_site['charset'];
  $pdo_site = new PDO($pdo_dsn, $sql_site['user'], $sql_site['password'], $pdo_options);
	// get data from Admin Panel
  $siteData = Array('title' => '');
  $data = $pdo_ap->query('SELECT * FROM `site_settings`')->fetchAll(PDO::FETCH_UNIQUE);
  foreach($data as $row => $arr) {
    $siteData[strval($row)] = htmlspecialchars($arr['value'], ENT_QUOTES);
  }
  $siteData['title'] = $siteData['title'] ?? 'Название сайта';
  $siteData['description'] = $siteData['description'] ?? '';
  $siteData['contacts_city'] = $siteData['contacts_city'] ?? 'Пермь';
  // === load user data from site DB ===========================================
  $userData = Array();
  if(isset($_SESSION['login'])) {

    // get
    $stmt = $pdo_site->prepare("SELECT * FROM `accounts` WHERE `account` = ?");
    $stmt->execute([$_SESSION['login']]);
    $data = $stmt->fetch(PDO::FETCH_LAZY);
    if(!empty($data)) {
      $userData['account_id'] = (!empty($data['account_id'])) ? $data['account_id'] : '';
      $userData['account'] = (!empty($data['account'])) ? $data['account'] : '';
      $userData['name1'] = (!empty($data['name1'])) ? $data['name1'] : '';
      $userData['name2'] = (!empty($data['name2'])) ? $data['name2'] : '';
      $userData['name3'] = (!empty($data['name3'])) ? $data['name3'] : '';
      $userData['email'] = (!empty($data['email'])) ? $data['email'] : '';
      $userData['country'] = (!empty($data['country'])) ? $data['country'] : '';
      $userData['city'] = (!empty($data['city'])) ? $data['city'] : '';
      $userData['phone'] = (!empty($data['phone'])) ? $data['phone'] : '';
      $userData['gender'] = (!empty($data['gender'])) ? $data['gender'] : '';
      $userData['mailing'] = (!empty($data['mailing'])) ? $data['mailing'] : '';
      if(empty($data['profile_icon'])) $userData['profile_icon'] = 'media/svg/'.$userData['gender'].'_avatar.svg';
      else $userData['profile_icon'] = 'users/public/'.$userData['account'].'/avatar.png';
    }
  }
  // ===========================================================================

  function idGenerator($count, $where) {
    $alphabet = 'QWERTYUIOPASDFGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789';
    $string = '';

    if(!isset($where)) {
      $where = 5;
    }

    if(!isset($count)) {
      $count = 20;
    }

    if($where > 0){
      for($i = 0; $i < $count; $i++) {
        if($i % $where == 0 && $i != $count && $i != 0) {
          $string .= '-';
        }
        else {
          if($i == 0){
            $string .= 'd';
          }
          else{
            $string .= $alphabet[mt_rand(0, strlen($alphabet) - 1)];
          }
        }
      }
    }
    else{
      for($i = 0; $i < $count; $i++){
        if($i == 0){
          $string .= 'd';
        } else{
          $string .= $alphabet[mt_rand(0, strlen($alphabet) - 1)];
        }
      }
    }
    if($where < 0){
      echo('Предупреждение: переменную where не рукомендуется писать меньше нуля');
    }
    return $string;
  }

  function formatPhone($phone = null) {
    if(is_null($phone)) $phone = '';

    $phone = preg_replace('/[^0-9]/', '', $phone);

    if (strlen($phone) != 11 and ($phone[0] != '7' or $phone[0] != '8')) {
      return FALSE;
    }

    $phone_number['dialcode'] = substr($phone, 0, 1);
    $phone_number['code']  = substr($phone, 1, 3);
    $phone_number['phone'] = substr($phone, -7);
    $phone_number['phone_arr'][] = substr($phone_number['phone'], 0, 3);
    $phone_number['phone_arr'][] = substr($phone_number['phone'], 3, 2);
    $phone_number['phone_arr'][] = substr($phone_number['phone'], 5, 2);

    $format_phone = '+' . $phone_number['dialcode'] . ' ('. $phone_number['code'] .') ' . implode('-', $phone_number['phone_arr']);

    return $format_phone;
  }

?>