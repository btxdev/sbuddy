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

  // auth
  if(!isset($_SESSION['login'])) {
    exit('AUTH.');
  }

  ini_set('memory_limit', '202M');
  ini_set('post_max_size', '201M');
  ini_set('upload_max_filesize', '200M');

  // === parameters ============================================================

  $users_dir = './admin/USERS_FILES';             // AP DOCS_USERS

  // == finder session properties ==============================================

  if(!isset($_SESSION['finder_history'])) {
    $_SESSION['finder_history'] = Array('/');
  }
  if(!isset($_SESSION['finder_history_p'])) {
    $_SESSION['finder_history_p'] = 0;
  }
  if(!isset($_SESSION['finder_copycut_mode'])) {
    $_SESSION['finder_copycut_mode'] = 'copy';
  }
  if(!isset($_SESSION['finder_copy_list'])) {
    $_SESSION['finder_copy_list'] = Array();
  }

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
  $pdo_dsn = "mysql:host=".$sql_site['host'].";dbname=".$sql_site['db'].";charset=".$sql_site['charset'];
  $pdo_site = new PDO($pdo_dsn, $sql_site['user'], $sql_site['password'], $pdo_options);
  $pdo_dsn = "mysql:host=".$sql_ap['host'].";dbname=".$sql_ap['db'].";charset=".$sql_ap['charset'];
  $pdo_ap = new PDO($pdo_dsn, $sql_ap['user'], $sql_ap['password'], $pdo_options);

  // === basic functions =======================================================

  function create_directory($path) {
    return is_dir($path) || mkdir($path, 0777, true);
  }

  function get_user_dir() {
    $username = $_SESSION['login'];
    return("$users_dir/$username");
  }

  function move_files($from_dir, $to_dir) {
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

  function move_file($file, $from_dir, $to_dir) {
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

  function get_file_extension($filename) {
    $pos = mb_strripos($filename, '.') + 1;
    return mb_substr($filename, $pos);
  }

  // get file type by extension
  function get_file_type($filename) {
    $extension = get_file_extension($filename);
    global $video_extensions;
    global $audio_extensions;
    global $compressed_extensions;
    global $executable_extensions;
    global $document_extensions;
    global $image_extensions;
    $type = 'undefined';
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

  function remove_directory_r($dir) {
    if(!file_exists($dir) || is_file($dir)) {
      return;
    }
    if(mb_substr($dir, -1, 1) != '/') {
      $dir = $dir.'/';
    }
    chmod($dir, 0777);
    $inner = scandir($dir);
    foreach($inner as $elem) {
      $path = $dir.$elem;
      if($elem == '.' || $elem == '..' || !file_exists($path)) continue;
      if(is_dir($path)) {
        remove_directory_r($path);
      }
      else {
        chmod($path, 0777);
        unlink($path);
      }
    }
    rmdir($dir);
  }

  // == finder functions =======================================================

  function prepare_path($path, $mode = null) {
    global $users_dir;
    if(preg_match('/^([^*|:"<>?\\\\])+$/ui', $path) && !preg_match('/([\/\/]{2,2})+/ui', $path)) {
      if(mb_substr($path, -1) != '/') $path = $path.'/';
      if(mb_substr($path, 0, 1) != '/') $path = '/'.$path;
      //if(mb_substr($path, 0, 1) == '/') $path = mb_substr($path, 1);
      $path_array = explode('/', mb_substr($path, 1, mb_strlen($path) - 2));
      $good = true;
      foreach($path_array as $str) {
        if($str == '..' || $str == '.') {
          $good = false;
          break;
        }
      }
      if(!$good) {
        return false;
      }
      return $path;
    }
    else {
      return false;
    }
  }

  function finder_next_catalog_available() {
    $pos = $_SESSION['finder_history_p'];
    $last = count($_SESSION['finder_history']) - 1;
    if($pos >= $last) {
      return false;
    }
    else {
      return true;
    }
  }

  function finder_prev_catalog_available() {
    $pos = $_SESSION['finder_history_p'];
    if($pos < 1) {
      return false;
    }
    else {
      return true;
    }
  }

  function history_get_pos() {
    $pos = $_SESSION['finder_history_p'];
    $last = count($_SESSION['finder_history']) - 1;
    if(!finder_next_catalog_available()) {
      $pos = $last;
      $_SESSION['finder_history_p'] = $pos;
    }
    if(!finder_prev_catalog_available()) {
      $pos = 0;
      $_SESSION['finder_history_p'] = $pos;
    }
    return $pos;
  }

  function finder_get_catalog() {
    if(empty($_SESSION['finder_history'])) {
      $_SESSION['finder_history'] = Array('/');
      $_SESSION['finder_history_p'] = 0;
      return '/';
    }
    else {
      $pos = history_get_pos();
      $catalog = $_SESSION['finder_history'][$pos];
      if(file_exists(finder_real($catalog))) {
        return $catalog;
      }
      else {
        finder_root_catalog();
        return '/';
      }
    }
  }

  function finder_next_catalog() {
    $_SESSION['finder_history_p']++;
    return history_get_pos();
  }

  function finder_prev_catalog() {
    $_SESSION['finder_history_p']--;
    return history_get_pos();
  }

  function finder_root_catalog() {
    $_SESSION['finder_history_p'] = 0;
    $_SESSION['finder_history'] = Array('/');
    $root_path = finder_real('/');
    if(!file_exists($root_path)) {
      create_directory($root_path);
    }
  }

  function finder_parent_catalog($set = false) {
    $path = finder_get_catalog();
    if($path == '/') {
      return '/';
    }
    else {
      $path_array = explode('/', mb_substr($path, 1, mb_strlen($path) - 2));
      if(count($path_array) < 2) {
        if($set) {
          finder_set_catalog('/');
        }
        return '/';
      }
      else {
        array_pop($path_array);
        $parent = prepare_path(implode('/', $path_array));
        if($set) {
        finder_set_catalog($parent);
        }
        return $parent;
      }
    }
  }

  function finder_how_much_memory_used() {
    global $pdo_site;
    if(!isset($_SESSION['userid'])) return 0;
    try {
      $stmt = $pdo_site->prepare("SELECT `used` FROM `drive` WHERE `account_id` = ?");
      $stmt->execute([$_SESSION['userid']]);
      $row = $stmt->fetch(PDO::FETCH_LAZY);
      if(!empty($row)) {
        $used = intval($row['used']);
        return $used;
      }
      else {
        $stmt2 = $pdo_site->prepare("INSERT INTO `drive` (`account_id`, `used`) VALUES (:account_id, :used)");
        $stmt2->execute(Array(
          ':account_id' => $_SESSION['userid'],
          ':used' => 0
        ));
        return 0;
      }
    }
    catch(Exception $e) {
      debuglog('<'.__FILE__.' : '.__FUNCTION__.'> ERROR IN LINE '.__LINE__);
      return 0;
    }
    return 0;
  }

  function finder_update_memory($bytes) {
    global $pdo_site;
    if(!isset($_SESSION['userid'])) return false;
    try {
      $stmt = $pdo_site->prepare("INSERT INTO `drive` (`account_id`, `used`) VALUES (:account_id, :used1) ON DUPLICATE KEY UPDATE `used` = :used2");
      $stmt->execute(Array(
        ':account_id' => $_SESSION['userid'],
        ':used1' => intval($bytes),
        ':used2' => intval($bytes)
      ));
      return true;
    }
    catch(Exception $e) {
      debuglog('<'.__FILE__.' : '.__FUNCTION__.'> ERROR IN LINE '.__LINE__);
      debuglog($e);
      return false;
    }
  }

  function finder_how_much_memory_available() {
    global $pdo_ap;
    try {
      $stmt = $pdo_ap->prepare("SELECT `value` FROM `site_settings` WHERE `param` = 'std_drive_limit'");
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_LAZY);
      if(!empty($row)) {
        $maximum = intval($row['value']);
        return $maximum;
      }
      else {
        debuglog('<'.__FILE__.' : '.__FUNCTION__.'> ERROR IN LINE '.__LINE__);
        return 104857600;
      }
    }
    catch(Exception $e) {
      debuglog('<'.__FILE__.' : '.__FUNCTION__.'> ERROR IN LINE '.__LINE__);
      return 0;
    }
    return 104857600;
  }

  function history_get_key($path) {
    $last = count($_SESSION['finder_history']) - 1;
    for($i = $last; $i > 0; $i--) {
      if($_SESSION['finder_history'][$i] == $path) {
        return $i;
      }
    }
    return false;
  }

  function finder_set_catalog($path_raw) {
    $new_path = prepare_path($path_raw);
    if($new_path === false) {
      return false;
    }
    $old_path = finder_get_catalog();
    if($new_path != $old_path) {
      $offset = ++$_SESSION['finder_history_p'];
      array_splice($_SESSION['finder_history'], $offset, count($_SESSION['finder_history']), $new_path);
    }
  }

  function finder_real($path_raw, $mode = null) {
    global $users_dir;
    $path = prepare_path($path_raw, $mode);
    if($path === false) {
      return false;
    }
    else {
      $the_path = $users_dir.'/'.$_SESSION['login'].'/'.$path;
      /*if(!file_exists($the_path)) {
        create_directory($the_path);
      }*/
      return($the_path);
    }
  }

  function file_is_forbidden($filename) {
    if(mb_substr($filename, 0, 3) == '.ht') return true;
    if(mb_substr($filename, -4) == '.ini') return true;
    if(mb_substr($filename, -4) == '.lnk') return true;
    return false;
  }

  function finder_is_dir($path) {
    if(mb_substr($path, -1) == '/') {
      return true;
    }
    else {
      return false;
    }
  }

  function finder_get_parent_dir($path) {
    if(finder_is_dir($path)) {
      $pos = mb_strripos(mb_substr($path, 0, -1), '/') + 1;
      return mb_substr($path, 0, $pos);
    }
    else {
      $pos = mb_strripos($path, '/') + 1;
      return mb_substr($path, 0, $pos);
    }
  }

  function finder_get_folder_size($path) {
    global $users_dir;
    $file = finder_real($path);
    if($file !== false) {
      return filesize($file);
    }
    else {
      return 0;
    }
  }

  function finder_listing($path = null) {
    global $users_dir;
    // prepare path
    if(is_null($path)) {
      $path = finder_get_catalog();
    }
    // create empty list (for listing)
    $listing = Array();
    // path to dir
    $path_to_dir = finder_real($path);
    // ignore forbidden folders
    /*if($path_to_dir == $trash_path || $path_to_dir == $tmp_files_path) {
      return false;
    }*/
    // check directory exists
    if(!file_exists($path_to_dir)) {
      // create folder
      /*if(($path_to_dir == $users_files_path) || ($path_to_dir == $books_files_path) || ($path_to_dir == $docs_files_path)) {
        create_directory($path_to_dir);
      }*/
      create_directory($path_to_dir);
      // exit
      /*else {
        return false;
      }*/
    }
    // files list in directory
    $real_files = scandir($path_to_dir);
    // clear list, prepare
    $listing = Array();
    // scan files in directory
    for($f = 0; $f < sizeof($real_files); $f++) {
      // file parameters
      $file_type = 'undefined';
      $file_date = ' ';
      $file_size = 0;
      // real file name
      $filename = $real_files[$f];
      // reserved
      if($filename == '.' || $filename == '..') {
        continue;
      }
      // full file path
      $filepath = $path_to_dir.$filename;
      // if file is forbidden
      if(file_is_forbidden($filename)) {
        continue;
      }
      // define file type
      if(is_dir($filepath)) {
        // check if is a trash can
        /*if(($filepath.'/' == $trash_path) || ($filepath.'/' == $users_files_path) || ($filepath.'/' == $tmp_files_path) || ($filepath.'/' == $docs_files_path) || ($filepath.'/' == $books_files_path)) {
          continue;
        }*/
        // is a directory
        $file_type = 'directory';
        $file_date = date("d.m.Y", filemtime($filepath.'/.'));
        $file_size = finder_get_folder_size(prepare_path($path.$filename, true));
      }
      else {
        // is a file
        $file_type = get_file_type($filename);
        $file_date = date("d.m.Y", filemtime($filepath));
        $file_size = filesize($filepath);
      }
      // add record to listing array
      $listing[] = Array(
        'filename' => strval($filename),
        'filetype' => strval($file_type),
        'date' => strval($file_date),
        'size' => intval($file_size)
      );
    }
    // output
    return $listing;
  }

  function finder_create_catalog($path = null) {
    if(is_null($path)) {
      $path = finder_get_catalog().'tmp'.rand(1000, 9999);
    }
    else {
      $path = prepare_path($path, true);
    }
    $realpath = finder_real($path, true);
    if($realpath === false) {
      return false;
    }
    if($path === false) {
      return false;
    }
    if(file_exists($realpath)) {
      return false;
    }
    // check memory
    $used = finder_how_much_memory_used() + 1024;
    $available = finder_how_much_memory_available();
    if($used >= $available) return false;
    finder_update_memory($used);
    // create directory
    try {
      mkdir($realpath, 0777, true);
    }
    catch(Exception $e) {
      debuglog('<db_finder.php : finder_create_catalog> ERROR IN LINE '.__LINE__);
      return false;
    }
    return true;
  }

  function finder_rename($dir, $from, $to) {
    global $bad_filenames_array;
    // check path
    $path = prepare_path($dir);
    if($path === false) {
      return false;
    }
    $realpath = finder_real($dir);
    if($realpath === false) {
      return false;
    }
    // equal
    if($from == $to) {
      return false;
    }
    // is catalog or file
    $to_is_file = true;
    $from_is_file = true;
    $to_replaced = mb_substr($to, 0, mb_strlen($to) - 1);
    $from_replaced = mb_substr($from, 0, mb_strlen($from) - 1);
    if($to_replaced.'/' == $to) {
      $to_is_file = false;
    }
    if($from_replaced.'/' == $from) {
      $from_is_file = false;
    }
    // file <-> directory replace
    if($to_is_file != $from_is_file) {
      return false;
    }
    // check name
    $file_regex = '/^([^*|:;"<>?\/\\\\])+$/ui';
    if($to_is_file) {
      $splitted = explode('.', $from);
      foreach($splitted as $str) { if(!preg_match($file_regex, $str) || (mb_strlen($str) == 0) || in_array(strtolower($str), $bad_filenames_array)) { return false; } }
      $splitted = explode('.', $to);
      foreach($splitted as $str) { if(!preg_match($file_regex, $str) || (mb_strlen($str) == 0) || in_array(strtolower($str), $bad_filenames_array)) { return false; } }
    }
    else {
      if($from_replaced == 'TRASH_CAN') { // LABEL348348
        return false;
      }
      $splitted = explode('.', $from_replaced);
      foreach($splitted as $str) { if(!preg_match($file_regex, $str) || (mb_strlen($str) == 0) || in_array(strtolower($str), $bad_filenames_array)) { return false; } }
      $splitted = explode('.', $to_replaced);
      foreach($splitted as $str) { if(!preg_match($file_regex, $str) || (mb_strlen($str) == 0) || in_array(strtolower($str), $bad_filenames_array)) { return false; } }
    }
    // check length
    if(mb_strlen($from) > 255 || mb_strlen($to) > 255 || mb_strlen($from) == 0 || mb_strlen($to) == 0) {
      return false;
    }
    // check permissions
    if(file_is_forbidden($from) || file_is_forbidden($to)) {
      return false;
    }
    // check if file/directory exists
    if(file_exists($realpath.$to) || !file_exists($realpath.$from)) {
      return false;
    }
    // rename
    try {
      $oldname = $realpath.$from;
      $newname = $realpath.$to;
      $status = rename($oldname, $newname);
      if($status === false) {
        return false;
      }
    }
    catch(Exception $e) {
      return false;
    }
    return true;
  }

  function finder_remove_file($where, $filename, $ignore_permissions = null) {
    global $bad_filenames_array;
    // check path
    $path = prepare_path($where);
    if($path === false) {
      return false;
    }
    $realpath = finder_real($where);
    if($realpath === false) {
      return false;
    }
    // check filename
    $file_regex = '/^([^*|:;"<>?\/\\\\])+$/ui';
    $splitted = explode('.', $filename);
    foreach($splitted as $str) { if(!preg_match($file_regex, $str) || (mb_strlen($str) == 0) || in_array(strtolower($str), $bad_filenames_array)) { return false; } }
    // check file exists
    if(!file_exists($realpath.$filename)) {
      return false;
    }
    // is dir ?
    if(is_dir($realpath.$filename)) {
      return false;
    }
    // check length
    if(mb_strlen($filename) > 255) {
      return false;
    }
    // check permissions
    if(file_is_forbidden($filename) && is_null($ignore_permissions)) {
      return false;
    }
    // get file size
    $file_size = 0;
    // remove
    try {
      $file4rem = $realpath.$filename;
      $file_size = filesize($file4rem);
      chmod($file4rem, 0777);
      unlink($file4rem);
    }
    catch(Exception $e) {
      return false;
    }
    // memory used
    $used = finder_how_much_memory_used() - $file_size;
    if($used < 0) $used = 0;
    finder_update_memory($used);
    return true;
  }

  function finder_remove_catalog($where) {
    global $bad_filenames_array;
    // check path
    $path = prepare_path($where);
    if($path === false) {
      debuglog('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__);
      return false;
    }
    $realpath = finder_real($where);
    if($realpath === false) {
      debuglog('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__);
      return false;
    }
    // check catalog exists
    if(!file_exists($realpath)) {
      debuglog('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__);
      return false;
    }
    // is dir ?
    if(!is_dir($realpath)) {
      debuglog('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__);
      return false;
    }
    // get directory name
    $dirname = mb_substr($path, mb_strripos(mb_substr($path, 0, -1), '/') + 1, -1);
    // check permissions
    if(file_is_forbidden($dirname)) {
      debuglog('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__);
      return false;
    }
    if(in_array($dirname, $bad_filenames_array)) {
      debuglog('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__);
      return false;
    }
    // open directory
    $dir = opendir($realpath);
    // scan directory
    while (($file = readdir($dir)) !== false) {
      if($file == '.' || $file == '..') {
        continue;
      }
      $full_realpath = $realpath.$file;
      $full_vpath = $path.$file;
      // is a file
      if(is_file($full_realpath)) {
        // remove file
        $status = finder_remove_file($path, $file, true);
        if($status === false) {
          debuglog('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__);
          return false;
        }
      }
      // is a directory
      if(is_dir($full_realpath)) {
        // remove next directory
        $status = finder_remove_catalog($full_vpath);
        if($status === false) {
          debuglog('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__);
          return false;
        }
      }
    }
    // close directory
    closedir($dir);
    // === remove current directory ===
    try {
      chmod($realpath, 0777); // 0755
      rmdir($realpath);
    }
    catch(Exception $e) {
      debuglog('<'.__FILE__.' : '.__FUNCTION__.'> ERROR IN LINE'.__LINE__);
    }
    // update memory
    $used = finder_how_much_memory_used() - 1024;
    if($used < 0) $used = 0;
    finder_update_memory($used);
    return true;
  }

  function finder_get_filename_from_path($fullpath) {
    $pos = mb_strripos($fullpath, '/');
    $path = mb_substr($fullpath, 0, $pos + 1);
    $file = mb_substr($fullpath, $pos + 1, mb_strlen($fullpath));
    $output = (object)Array(
      'path' => $path,
      'file' => $file
    );
    return $output;
  }

  function finder_move_file($from, $to, $filename, $ignore_permissions = null) {
    global $bad_filenames_array;
    // check path
    $vpath_from = prepare_path($from); if($vpath_from === false) { return false; }
    $rpath_from = finder_real($from); if($rpath_from === false) { return false; }
    $vpath_to = prepare_path($to); if($vpath_to === false) { return false; }
    $rpath_to = finder_real($to); if($rpath_to === false) { return false; }
    // check filename
    $file_regex = '/^([^*|:;"<>?\/\\\\])+$/ui';
    $splitted = explode('.', $filename);
    foreach($splitted as $str) { if(!preg_match($file_regex, $str) || (mb_strlen($str) == 0) || in_array(strtolower($str), $bad_filenames_array)) { return false; } }
    // check file exists
    if(!file_exists($rpath_from.$filename)) {
      return false;
    }
    // is dir ?
    if(is_dir($rpath_from.$filename)) {
      return false;
    }
    // check length
    if(mb_strlen($filename) > 255) {
      return false;
    }
    // check permissions
    if(file_is_forbidden($filename) && is_null($ignore_permissions)) {
      return false;
    }
    // create new name
    $not_free = true;
    $dot_pos = mb_strripos($filename, '.');
    $filename_extension = mb_substr($filename, $dot_pos + 1, mb_strlen($filename));
    if(mb_strlen($filename_extension) == 0) {
      $filename_extension = '.tmp';
    }
    $filename_name = mb_substr($filename, 0, $dot_pos);
    $new_filename = $filename;
    $counter = 0;
    while($not_free) {
      // create
      if($counter > 0) {
        $new_filename = $filename_name.' ('.$counter.').'.$filename_extension;
      }
      else {
        $new_filename = $filename;
      }
      // check
      if(!file_exists($rpath_to.$new_filename)) {
        $not_free = false;
      }
      // limiter
      if($counter > 200) {
        return false;
      }
      // counter
      $counter++;
    }
    // move
    try {
      $oldname = $rpath_from.$filename;
      $newname = $rpath_to.$new_filename;
      create_directory($rpath_to);
      $status = rename($oldname, $newname);
      if($status === false) {
        return false;
      }
    }
    catch(Exception $e) {
      return false;
    }
    return true;
  }

  function finder_copy_file($from, $to, $filename, $ignore_permissions = null) {
    global $bad_filenames_array;
    // check path
    $vpath_from = prepare_path($from); if($vpath_from === false) { return false; }
    $rpath_from = finder_real($from); if($rpath_from === false) { return false; }
    $vpath_to = prepare_path($to); if($vpath_to === false) { return false; }
    $rpath_to = finder_real($to); if($rpath_to === false) { return false; }
    // check filename
    $file_regex = '/^([^*|:;"<>?\/\\\\])+$/ui';
    $splitted = explode('.', $filename);
    foreach($splitted as $str) { if(!preg_match($file_regex, $str) || (mb_strlen($str) == 0) || in_array(strtolower($str), $bad_filenames_array)) { return false; } }
    // check file exists
    if(!file_exists($rpath_from.$filename)) {
      return false;
    }
    // is dir ?
    if(is_dir($rpath_from.$filename)) {
      return false;
    }
    // check length
    if(mb_strlen($filename) > 255) {
      return false;
    }
    // check permissions
    if(file_is_forbidden($filename) && is_null($ignore_permissions)) {
      return false;
    }
    // create new name
    $not_free = true;
    $dot_pos = mb_strripos($filename, '.');
    $filename_extension = mb_substr($filename, $dot_pos + 1, mb_strlen($filename));
    if(mb_strlen($filename_extension) == 0) {
      $filename_extension = '.tmp';
    }
    $filename_name = mb_substr($filename, 0, $dot_pos);
    $new_filename = $filename;
    $counter = 0;
    while($not_free) {
      // create
      if($counter > 0) {
        $new_filename = $filename_name.' ('.$counter.').'.$filename_extension;
      }
      else {
        $new_filename = $filename;
      }
      // check
      if(!file_exists($rpath_to.$new_filename)) {
        $not_free = false;
      }
      // limiter
      if($counter > 200) {
        return false;
      }
      // counter
      $counter++;
    }
    // copy
    try {
      $oldname = $rpath_from.$filename;
      $newname = $rpath_to.$new_filename;
      create_directory($rpath_to);
      $status = copy($oldname, $newname);
      if($status === false) {
        return false;
      }
    }
    catch(Exception $e) {
      return false;
    }
    return true;
  }

  function finder_copy_catalog($from, $to, $ignore_permissions = null) {
    global $bad_filenames_array;
    // check path
    $vpath_from = prepare_path($from); if($vpath_from === false) { return false; }
    $rpath_from = finder_real($from); if($rpath_from === false) { return false; }
    $vpath_to = prepare_path($to); if($vpath_to === false) { return false; }
    $rpath_to = finder_real($to); if($rpath_to === false) { return false; }
    // is file ?
    if(is_file($rpath_from)) {
      return false;
    }
    // get directory name
    $dirname = mb_substr($vpath_from, mb_strripos(mb_substr($vpath_from, 0, -1), '/') + 1, -1);
    // check permissions
    if(file_is_forbidden($dirname)) {
      debuglog('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__);
      return false;
    }
    if(in_array($dirname, $bad_filenames_array)) {
      debuglog('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__);
      return false;
    }
    // equal
    if(finder_get_parent_dir($vpath_from) == $vpath_to) {
      return false;
    }
    // check subfolders
    if(mb_strpos($vpath_to, $vpath_from) === false) {
      // good
    }
    else {
      // ban
      return false;
    }
    // add directory
    create_directory($rpath_to.$dirname.'/');
    // open directory
    $dir = opendir($rpath_from);
    // scan directory
    while (($file = readdir($dir)) !== false) {
      if($file == '.' || $file == '..') {
        continue;
      }
      $full_realpath = $rpath_from.$file;
      $full_vpath = $vpath_from.$file;
      // is a file
      if(is_file($full_realpath)) {
        // move file
        $status = finder_copy_file($vpath_from, $vpath_to.$dirname.'/', $file, $ignore_permissions);
        if($status === false) {
          debuglog('<db_finder.php : finder_copy_catalog> ERROR IN LINE '.__LINE__);
          return false;
        }
      }
      // is a directory
      if(is_dir($full_realpath)) {
        // move next directory
        $status = finder_copy_catalog($vpath_from.$file, $vpath_to.$dirname, $ignore_permissions);
        if($status === false) {
          debuglog('<db_finder.php : finder_copy_catalog> ERROR IN LINE '.__LINE__);
          return false;
        }
      }
    }
    // close directory
    closedir($dir);
    // end
    return true;
  }

  function finder_move_catalog($from, $to, $ignore_permissions = null) {
    global $bad_filenames_array;
    // check path
    $vpath_from = prepare_path($from); if($vpath_from === false) { return false; }
    $rpath_from = finder_real($from); if($rpath_from === false) { return false; }
    $vpath_to = prepare_path($to); if($vpath_to === false) { return false; }
    $rpath_to = finder_real($to); if($rpath_to === false) { return false; }
    // is file ?
    if(is_file($rpath_from)) {
      return false;
    }
    // get directory name
    $dirname = mb_substr($vpath_from, mb_strripos(mb_substr($vpath_from, 0, -1), '/') + 1, -1);
    // check permissions
    if(file_is_forbidden($dirname)) {
      debuglog('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__);
      return false;
    }
    if(in_array($dirname, $bad_filenames_array)) {
      debuglog('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__);
      return false;
    }
    // equal
    if(finder_get_parent_dir($vpath_from) == $vpath_to) {
      return false;
    }
    // check subfolders
    if(mb_strpos($vpath_to, $vpath_from) === false) {
      // good
    }
    else {
      // ban
      return false;
    }
    // add directory
    create_directory($rpath_to.$dirname.'/');
    // open directory
    $dir = opendir($rpath_from);
    // scan directory
    while (($file = readdir($dir)) !== false) {
      if($file == '.' || $file == '..') {
        continue;
      }
      $full_realpath = $rpath_from.$file;
      $full_vpath = $vpath_from.$file;
      // is a file
      if(is_file($full_realpath)) {
        // move file
        $status = finder_move_file($vpath_from, $vpath_to.$dirname.'/', $file, $ignore_permissions);
        if($status === false) {
          debuglog('<db_finder.php : finder_copy_catalog> ERROR IN LINE '.__LINE__);
          return false;
        }
      }
      // is a directory
      if(is_dir($full_realpath)) {
        // move next directory
        $status = finder_move_catalog($vpath_from.$file, $vpath_to.$dirname, $ignore_permissions);
        if($status === false) {
          debuglog('<db_finder.php : finder_copy_catalog> ERROR IN LINE '.__LINE__);
          return false;
        }
      }
    }
    // close directory
    closedir($dir);
    // remove old catalog
    finder_remove_catalog($vpath_from);
    // end
    return true;
  }

  function finder_copycut_from($what, $mode, $reset = null) {
    // set mode
    if($mode == 'copy') {
      $_SESSION['finder_copycut_mode'] = 'copy';
    }
    else if($mode == 'cut') {
      $_SESSION['finder_copycut_mode'] = 'cut';
    }
    else {
      debuglog('<db_finder.php : finder_copycut_from> mode = '.$mode);
      return false;
    }
    // prepare
    $is_dir = finder_is_dir($what);
    $path = '';
    $realpath = '';
    if($is_dir) {
      $path = prepare_path($what);
      if($path === false) {
        debuglog('<db_finder.php : finder_copycut_from> ERROR IN LINE '.__LINE__);
        return false;
      }
      $realpath = finder_real($what);
      if($realpath === false) {
        debuglog('<db_finder.php : finder_copycut_from> ERROR IN LINE '.__LINE__);
        return false;
      }
    }
    else {
      $what_obj = finder_get_filename_from_path($what);
      $directory_path = $what_obj->path;
      $file_path = $what_obj->file;
      $dir_prepared = prepare_path($directory_path);
      if($dir_prepared === false) {
        debuglog('<db_finder.php : finder_copycut_from> ERROR IN LINE '.__LINE__);
        return false;
      }
      $real_dir_prepared = finder_real($directory_path);
      if($real_dir_prepared === false) {
        debuglog('<db_finder.php : finder_copycut_from> ERROR IN LINE '.__LINE__);
        return false;
      }
      $path = $dir_prepared.$file_path;
      $realpath = $real_dir_prepared.$file_path;
    }
    // reset
    if(!is_null($reset)) {
      $_SESSION['finder_copy_list'] = Array();
    }
    // check exists
    if(!file_exists($realpath)) {
      debuglog('<db_finder.php : finder_copycut_from> ERROR IN LINE '.__LINE__);
      return false;
    }
    // add to list
    $_SESSION['finder_copy_list'][] = $path;
    // end
    return true;
  }

  function finder_paste_to($dir) {
    // list is empty
    if(empty($_SESSION['finder_copy_list'])) {
      return 'EMPTY';
    }
    // prepare
    $path = prepare_path($dir);
    if($path === false) {
      debuglog('<db_finder.php : finder_paste_to> ERROR IN LINE '.__LINE__);
      return false;
    }
    $realpath = finder_real($dir);
    if($realpath === false) {
      debuglog('<db_finder.php : finder_paste_to> ERROR IN LINE '.__LINE__);
      return false;
    }
    // check exists
    if(!file_exists($realpath)) {
      debuglog('<db_finder.php : finder_paste_to> ERROR IN LINE '.__LINE__);
      return false;
    }
    // is dir ?
    if(!is_dir($realpath)) {
      debuglog('<db_finder.php : finder_paste_to> ERROR IN LINE '.__LINE__);
      return false;
    }
    // mode
    $mode = $_SESSION['finder_copycut_mode'];
    // copy or cut files from
    foreach($_SESSION['finder_copy_list'] as $key => $file) {
      $is_dir = finder_is_dir($file);
      if($is_dir) {
        // get path
        $vpath_from = prepare_path($file);
        $rpath_from = finder_real($file);
        if(is_dir($rpath_from)) {
          // cut catalog
          if($mode == 'cut') {
            $status = finder_move_catalog($vpath_from, $path, true);
            if($status === false) {
              return false;
            }
          }
          // copy catalog
          if($mode == 'copy') {
            $status = finder_copy_catalog($vpath_from, $path, true);
            if($status === false) {
              return false;
            }
          }
        }
        else {
          debuglog('<db_finder.php : finder_paste_to> ERROR IN LINE '.__LINE__);
          return false;
        }
      }
      else {
        // get path
        $filepath_obj = finder_get_filename_from_path($file);
        $filepath_obj_dir = $filepath_obj->path;
        $filename = $filepath_obj->file;
        $vpath_from = prepare_path($filepath_obj_dir);
        $rpath_from = finder_real($filepath_obj_dir);
        // is file ?
        if(is_file($rpath_from.$filename)) {
          // cut file
          if($mode == 'cut') {
            // compare (current catalog ?)
            if($vpath_from == $path) {
              return false;
            }
            // move
            finder_move_file($vpath_from, $path, $filename);
            // remove from array
            unset($_SESSION['finder_copy_list'][$key]);
          }
          // copy file
          if($mode == 'copy') {
            // copy
            finder_copy_file($vpath_from, $path, $filename);
          }
        }
        else {
          debuglog('<db_finder.php : finder_paste_to> ERROR IN LINE '.__LINE__); //
          return false;
        }
      }
    }
    return true;
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

  // === file downloading functions ============================================

  function file_download_apache($file) {
    if(file_exists($file)) {
      header('X-SendFile: '.realpath($file));
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=' . basename($file));
      exit;
    }
  }

  function file_download_nginx($file) {
    if(file_exists($file)) {
      header('X-Accel-Redirect: '.$file);
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename=' . basename($file));
      exit;
    }
  }

  function file_download_force($file, $custom_filename = null, $no_exit = null) {
    if(file_exists($file)) {
      // refresh buffer
      if(ob_get_level()) {
        ob_end_clean();
      }
      // custom name
      $basename = basename($file);
      if(!is_null($custom_filename)) {
        $basename = $custom_filename;
      }
      $the_filesize = filesize($file);
      // save file
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.$basename);
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: '.$the_filesize);
      // read and send data
      $bytes_send = 0;
      if($fd = fopen($file, 'rb')) {
        while(!feof($fd) && !connection_aborted() && ($bytes_send < $the_filesize)) {
          print fread($fd, 1024);
          $bytes_send += 1024;
        }
        fclose($fd);
      }
      if(is_null($no_exit)) {
        exit;
      }
    }
  }

  // === finder zip ============================================================

  function finder_zip($source, $destination) {
    if(!extension_loaded('zip') || !file_exists($source)) {
      return false;
    }
    $zip = new ZipArchive();
    if(!$zip->open($destination, ZIPARCHIVE::CREATE)) {
      return false;
    }
    $source = str_replace('\\', DIRECTORY_SEPARATOR, realpath($source));
    $source = str_replace('/', DIRECTORY_SEPARATOR, $source);

    if(is_dir($source) === true) {
      $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

      foreach($files as $file) {
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
        $file = str_replace('/', DIRECTORY_SEPARATOR, $file);

        if($file == '.' || $file == '..' || empty($file) || $file == DIRECTORY_SEPARATOR) {
          continue;
        }
        // ignore
        if(in_array(substr($file, strrpos($file, DIRECTORY_SEPARATOR) + 1), array('.', '..'))) {
          continue;
        }
        $file = realpath($file);
        $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
        $file = str_replace('/', DIRECTORY_SEPARATOR, $file);

        if(is_dir($file) === true) {
          $d = str_replace($source.DIRECTORY_SEPARATOR, '', $file);
          if(empty($d)) {
            continue;
          }
          $zip->addEmptyDir($d);
        }
        else if(is_file($file) === true) {
          $zip->addFromString(str_replace($source . DIRECTORY_SEPARATOR, '', $file),
          file_get_contents($file));
        }
        else {
          // do shit
        }
      }
    }
    else if(is_file($source) === true) {
      $zip->addFromString(basename($source), file_get_contents($source));
    }
    return $zip->close();
  }

  // === finder requests =======================================================

  if(isset($_POST['set_catalog'])) {
    $catalog = prepare_path($_POST['set_catalog']);
    if($catalog === false) {
      exit('WRONG.');
    }
    else {
      finder_set_catalog($catalog);
      exit('OK.');
    }
  }

  // ===========================================================================

  if(isset($_POST['set_prev_catalog'])) {
    finder_prev_catalog();
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['set_next_catalog'])) {
    finder_next_catalog();
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['set_parent_catalog'])) {
    finder_parent_catalog();
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['set_root_catalog'])) {
    finder_root_catalog();
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['listing'])) {
    // output data
    $output = (object)Array();
    // path
    $current_path = finder_get_catalog();
    $output->path = $current_path;
    // buttons
    $output->history_data = (object)Array(
      'prev' => finder_prev_catalog_available(),
      'next' => finder_next_catalog_available()
    );
    // memory
    $output->memory = (object)Array(
      'val' => finder_how_much_memory_used(),
      'max' => finder_how_much_memory_available()
    );
    // files
    $output->listing = finder_listing();
    // json output
    echo('OK.');
    echo(json_encode($output));
    exit();
  }

  // ===========================================================================

  if(isset($_POST['memory'])) {
    $memory = finder_how_much_memory_used();
    exit('OK.'.$memory);
  }

  // ===========================================================================

  if(isset($_POST['create_catalog'])) {
    $path = htmlspecialchars($_POST['create_catalog'], ENT_QUOTES);
    $status = finder_create_catalog($path);
    if($status) {
      exit('OK.');
    }
    else {
      exit('ERROR.');
    }
  }

  // ===========================================================================

  if(isset($_POST['finder_rename'])) {
    if(!isset($_POST['rename_dir']) || !isset($_POST['rename_from']) || !isset($_POST['rename_to'])) {
      exit('WRONG.');
    }
    $dir = htmlspecialchars($_POST['rename_dir'], ENT_QUOTES);
    $from = htmlspecialchars($_POST['rename_from'], ENT_QUOTES);
    $to = htmlspecialchars($_POST['rename_to'], ENT_QUOTES);
    $status = finder_rename($dir, $from, $to);
    if($status) {
      exit('OK.');
    }
    else {
      exit('ERROR.');
    }
  }

  // ===========================================================================

  if(isset($_POST['remove_file'])) {
    if(!isset($_POST['remove_file_where']) || !isset($_POST['remove_file_name'])) {
      exit('WRONG.');
    }
    $where = htmlspecialchars($_POST['remove_file_where'], ENT_QUOTES);
    $file = htmlspecialchars($_POST['remove_file_name'], ENT_QUOTES);
    $status = finder_remove_file($where, $file);
    if($status === true) {
      exit('OK.');
    }
    else {
      exit('ERROR.');
    }
  }

  // ===========================================================================

  if(isset($_POST['remove_catalog'])) {
    $where = htmlspecialchars($_POST['remove_catalog'], ENT_QUOTES);
    $status = finder_remove_catalog($where);
    if($status === true) {
      exit('OK.');
    }
    else {
      exit('ERROR.');
    }
  }

  // ===========================================================================

  if(isset($_POST['finder_copycut'])) {
    if(!isset($_POST['copycut_mode']) || !isset($_POST['copycut_reset']) ) {
      exit('WRONG.');
    }
    $what = htmlspecialchars($_POST['finder_copycut'], ENT_QUOTES);
    $mode_str = htmlspecialchars($_POST['copycut_mode'], ENT_QUOTES);
    $reset_str = htmlspecialchars($_POST['copycut_reset'], ENT_QUOTES);
    /*$mode = 'copy';
    if($mode_str == 'cut') {
      $mode = 'cut';
    }*/
    $mode = 'cut';
    if($reset_str == 'true') {
      $status = finder_copycut_from($what, $mode, true);
    }
    else {
      $status = finder_copycut_from($what, $mode);
    }
    if($status === true) {
      exit('OK.');
    }
    else {
      exit('ERROR.');
    }
  }

  // ===========================================================================

  if(isset($_POST['finder_paste_to'])) {
    $path = htmlspecialchars($_POST['finder_paste_to'], ENT_QUOTES);
    $status = finder_paste_to($path);
    if($status === true) {
      exit('OK.');
    }
    else if($status == 'EMPTY') {
      exit('EMPTY.');
    }
    else {
      exit('ERROR.');
    }
  }

  // ===========================================================================

  if(isset($_POST['finder_upload_file'])) {
    // check files corruption
    if(!isset($_FILES) || (count($_FILES) <= 0)) exit('INVALID_PARAMETERS.');
    // check files count
    if(count($_FILES) > 20) exit('COUNT_LIM.');
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
      // change filename
      $filename = $_FILES[$f]['name'];
      $filename = finder_prepare_filename($filename);
      // check filename
      if(file_is_forbidden($filename) || in_array($filename, $bad_filenames_array)) {
        exit('WRONG_FILENAME.');
      }
      // check file exists
      $current_catalog = finder_real(finder_get_catalog());
      if($current_catalog === false) {
        exit('ERROR.');
      }
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
      // check file size
      if ($_FILES[$f]['size'] > 209715200) { // 200 MB
        exit('LIMIT.');
      }
      // check memory
      $used = finder_how_much_memory_used() + $_FILES[$f]['size'];
      $available = finder_how_much_memory_available();
      if($used > $available) exit('MEMORY_LIM.');
      finder_update_memory($used);
      // save file
      if(!move_uploaded_file($_FILES[$f]['tmp_name'], $current_catalog.$filename)) {
        exit('DOWNLOADING_ERROR.');
      }
    }
    // end
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['finder_upload_multiply'])) {
    // check files corruption
    if(!isset($_FILES) || (count($_FILES) <= 0)) exit('INVALID_PARAMETERS.');
    // check files count
    if(count($_FILES) > 20) exit('COUNT_LIM.');
    // prepare relative path data
    try {
      $files_data = json_decode($_POST['finder_upload_multiply']);
    }
    catch(Exception $e) {
      log_error_to_file('<db_finder.php : finder_upload_multiply> ERROR IN LINE '.__LINE__);
      exit('ERROR.');
    }
    // save files
    for($i = 0; $i < count($_FILES); $i++) {
      // check file corruption
      if(!isset($_FILES[$i]['error']) || is_array($_FILES[$i]['error'])) {
        exit('INVALID_PARAMETERS.');
      }
      switch($_FILES[$i]['error']) {
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
      // compare filenames
      $files_filepath = $_FILES[$i]['name'];
      $data_filepath = $files_data[$i];
      $s_pos = mb_strripos($data_filepath, '/');
      $filename = mb_substr($data_filepath, $s_pos + 1);
      $filepath = mb_substr($data_filepath, 0, $s_pos + 1);
      if($filename != $files_filepath) {
        exit('ERROR.');
      }
      // change filename
      $filename = finder_prepare_filename($filename);
      // check filename
      if(file_is_forbidden($filename) || in_array($filename, $bad_filenames_array)) {
        // ignore
        continue;
      }
      // check file exists
      $current_catalog = finder_real(finder_get_catalog());
      if($current_catalog === false) {
        exit('ERROR.');
      }
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
        if(!file_exists($current_catalog.$filepath.$new_filename)) {
          $not_free = false;
        }
        // limiter
        if($counter > 20) {
          exit('ERROR.');
        }
        // counter
        $counter++;
      }
      $filename = $new_filename;
      // memory available
      $available = finder_how_much_memory_available();
      // check file size
      if (($_FILES[$i]['size'] > 2147482625) || ($_FILES[$i]['size'] > $available)) { // > 1.99 GB or AVAILABLE
        exit('LIMIT.');
      }
      // check memory
      $used = finder_how_much_memory_used() + $_FILES[$i]['size'];
      if(!file_exists($current_catalog.$filepath)) {
        $used = $used + 1024;
      }
      if($used > $available) exit('MEMORY_LIM.');
      finder_update_memory($used);
      // create folder
      create_directory($current_catalog.$filepath);
      // save file
      if(!move_uploaded_file($_FILES[$i]['tmp_name'], $current_catalog.$filepath.$filename)) {
        exit('DOWNLOADING_ERROR.');
      }
    }
    // end
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['download_file'])) {
    // get
    $path = htmlspecialchars($_POST['download_file'], ENT_QUOTES);
    $obj = finder_get_filename_from_path($path);
    $dir = $obj->path;
    $file = $obj->file;
    $realdir = finder_real($dir);
    // check filename
    if(file_is_forbidden($file)) {
      exit('ACCESS.');
    }
    // check exists
    if($realdir === false) {
      log_error_to_file('<db_finder.php : download_file> file '.$path.' not founded');
      exit('EMPTY.');
    }
    // create hash
    if(!isset($_SESSION['finder_download_tokens'])) {
      $_SESSION['finder_download_tokens'] = Array();
    }
    $hash = sha1(time().random_int(1000, 9999));
    $_SESSION['finder_download_tokens'][$hash] = Array(
      'path' => $realdir.$file,
      'name' => 'none',
      'life' => 'infinity'
    );
    echo('OK.');
    echo($hash);
    exit();
  }

  // ===========================================================================

  if(isset($_POST['download_catalog'])) {
    $path = htmlspecialchars($_POST['download_catalog'], ENT_QUOTES);
    // check exists
    $realpath = finder_real($path);
    if($realpath === false) {
      exit('EMPTY.');
    }
    // create tmp folder
    /*if(!file_exists($tmp_files_path)) {
      create_directory($tmp_files_path);
    }*/
    // generate filename for archive
    $hash = 'hash';
    $tmp_name = 'download.zip';
    $tmp_real_path = $users_dir.'/'.$_SESSION['login'].'/'.$tmp_name;
    $not_free = true;
    while($not_free) {
      $hash = sha1(time().random_int(1000, 9999));
      $tmp_name = 'download-'.$hash.'.zip';
      $tmp_real_path = $users_dir.'/'.$_SESSION['login'].'/'.$tmp_name;
      if(!file_exists($tmp_real_path)) { $not_free = false; }
    }
    // memory available
    $used = finder_how_much_memory_used();
    $available = finder_how_much_memory_available();
    if(($used + 1024) > $available) exit('LIMIT.');
    // create archive
    try {
      finder_zip($realpath, $tmp_real_path);
    }
    catch(Exception $e) {
      log_error_to_file('<db_finder.php : download_catalog> ERROR IN LINE '.__LINE__);
      exit('ERROR.');
    }
    // get archive size
    $archive_filesize = filesize($tmp_real_path);
    $used = $used + $archive_filesize;
    finder_update_memory($used);
    // custom filename
    $dirname = $realpath;
    if(mb_substr($dirname, -1) == '/') {
      $dirname = mb_substr($dirname, 0, -1);
    }
    $dirname = mb_substr($dirname, mb_strripos($dirname, '/') + 1);
    $dirname = finder_prepare_filename($dirname);
    // download token
    if(!isset($_SESSION['finder_download_tokens'])) {
      $_SESSION['finder_download_tokens'] = Array();
    }
    $_SESSION['finder_download_tokens'][$hash] = Array(
      'path' => $tmp_real_path,
      'name' => $dirname,
      'life' => 'once'
    );
    echo('OK.');
    echo($hash);
    exit();
  }

  // ===========================================================================

  if(isset($_GET['dfut'])) {
    $hash = htmlspecialchars($_GET['dfut'], ENT_QUOTES);
    if(isset($_SESSION['finder_download_tokens'][$hash])) {
      // get data
      $realpath = $_SESSION['finder_download_tokens'][$hash]['path'];
      $use_custom_name = $_SESSION['finder_download_tokens'][$hash]['name'];
      $lifetime = $_SESSION['finder_download_tokens'][$hash]['life'];
      // clear
      unset($_SESSION['finder_download_tokens'][$hash]);
      try {
        // custom filename
        $custom_filename = null;
        if($use_custom_name != 'none') {
          $custom_filename = $use_custom_name.'.zip';
        }
        // download
        file_download_force($realpath, $custom_filename, true);
        // remove
        if($lifetime == 'once') {
          try {
            // get file size
            $file_size = filesize($realpath);
            // remove file
            unlink($realpath);
            // update memory
            $used = finder_how_much_memory_used() - $file_size;
            if($used < 0) $used = 0;
            finder_update_memory($used);
          }
          catch(Exception $e) {
            log_error_to_file('<db_finder.php : dfut> ERROR IN LINE '.__LINE__);
          }
        }
      }
      catch(Exception $e) {
        log_error_to_file('<db_finder.php : dfut> ERROR IN LINE '.__LINE__);
      }
    }
    exit();
  }

  // ===========================================================================

  exit('EMPTY.');

?>