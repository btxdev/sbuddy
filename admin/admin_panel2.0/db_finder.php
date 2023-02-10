<?php

  /*
   *  Swiftly Admin Panel v1.12 alpha
   *  (c) 2019-2020 INSOweb  <http://insoweb.ru>
   *  All rights reserved.
   */

  if(isset($_GET['file_version'])) {
    exit('db_finder VERSION: 1');
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

  // == parameters =============================================================

  $dir_info_filename = '.htdirinfo';
  $IS_WINDOWS = true;
  $USE_EXEC = false;

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

  // == old basic functions ====================================================

  function create_directory($path) {
    return is_dir($path) || mkdir($path, 0700, true);
  }

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

  //== new basic functions =====================================================

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

  // == external methods =======================================================

  function filesize64($file) {
    global $USE_EXEC;
    global $IS_WINDOWS;
    $size = 0;
    if($USE_EXEC) {
      if($IS_WINDOWS) {
        if(class_exists('COM')) {
          $fsobj = new COM('Scripting.FileSystemObject');
          $f = $fsobj->GetFile(realpath($file));
          $size = $f->Size;
        }
        else {
          $size = trim(exec("for %F in (\"".$file."\") do @echo %~zF"));
        }
      }
      else if(PHP_OS == 'Darwin') {
        $size = trim(shell_exec("stat -f %z ".escapeshellarg($file)));
      }
      else if((PHP_OS == 'Linux') || (PHP_OS == 'FreeBSD') || (PHP_OS == 'Unix') || (PHP_OS == 'SunOS')) {
        $size = trim(shell_exec("stat -c%s ".escapeshellarg($file)));
      }
      else {
        $size = filesize($file);
      }
    }
    else {
      $size = filesize($file);
    }
    return $size;
  }

  // == finder functions =======================================================

  function prepare_path($path, $mode = null) {
    global $root_relative_path;
    global $users_files_path;
    global $books_files_path;
    global $docs_files_path;
    if(preg_match('/^([^*|:"<>?\\\\])+$/ui', $path) && !preg_match('/([\/\/]{2,2})+/ui', $path)) {
      if(mb_substr($path, -1) != '/') $path = $path.'/';
      if(mb_substr($path, 0, 1) != '/') $path = '/'.$path;
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
      if(!file_exists($root_relative_path.$path) && is_null($mode)) {
        if($root_relative_path.$path == $users_files_path || $root_relative_path.$path == $books_files_path || $root_relative_path.$path == $docs_files_path) {
          create_directory($root_relative_path.$path);
        }
        else {
          return false;
        }
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
    global $root_relative_path;
    $path = prepare_path($path_raw, $mode);
    if($path === false) {
      return false;
    }
    else {
      return($root_relative_path.$path);
      //return realpath($root_relative_path.$path);
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
    global $pdo;
    // get from db
    try {
      $stmt = $pdo->prepare("SELECT `size` FROM `finder_files` WHERE `path` = ?");
      $stmt->execute([$path]);
      $row = $stmt->fetch(PDO::FETCH_LAZY);
      if(empty($row)) {
        return 0;
      }
      else {
        return intval($row['size']);
      }
    }
    catch(Exception $e) {
      log_error_to_file('<db_finder.php : finder_get_parent_dir> ERROR IN LINE '.__LINE__);
      return 0;
    }
    return 0;
  }

  function finder_listing($path = null) {
    global $dir_info_filename;
    global $trash_path;
    global $users_files_path;
    global $docs_files_path;
    global $books_files_path;
    global $tmp_files_path;
    // prepare path
    if(is_null($path)) {
      $path = finder_get_catalog();
    }
    // create empty list (for listing)
    $listing = Array();
    // path to dir
    $path_to_dir = finder_real($path);
    // ignore forbidden folders
    if($path_to_dir == $trash_path || $path_to_dir == $tmp_files_path) {
      return false;
    }
    // check directory exists
    if(!file_exists($path_to_dir)) {
      // create folder
      if(($path_to_dir == $users_files_path) || ($path_to_dir == $books_files_path) || ($path_to_dir == $docs_files_path)) {
        create_directory($path_to_dir);
      }
      // exit
      else {
        return false;
      }
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
        if(($filepath.'/' == $trash_path) || ($filepath.'/' == $users_files_path) || ($filepath.'/' == $tmp_files_path) || ($filepath.'/' == $docs_files_path) || ($filepath.'/' == $books_files_path)) {
          continue;
        }
        // is a directory
        $file_type = 'directory';
        $file_date = date("d.m.Y", filemtime($filepath.'/.'));
        $file_size = finder_get_folder_size(prepare_path($path.$filename, true));
      }
      else {
        // is a file
        $file_type = get_file_type($filename);
        $file_date = date("d.m.Y", filemtime($filepath));
        $file_size = filesize64($filepath);
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
    global $pdo;
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
    // create directory
    try {
      mkdir($realpath, 0777, true);
    }
    catch(Exception $e) {
      log_error_to_file('<db_finder.php : finder_create_catalog> ERROR IN LINE '.__LINE__);
      return false;
    }
    // add to db
    try {
      $stmt = $pdo->prepare("INSERT INTO `finder_files` (`path`, `type`, `size`, `count`) VALUES (:the_path, :the_type, :the_size, :the_count)");
      $stmt->execute(Array(
        ':the_path' => $path,
        ':the_type' => 'directory',
        ':the_size' => 0,
        ':the_count' => 0
      ));
    }
    catch(Exception $errorException) {
      log_error_to_file('<db_finder.php : finder_create_catalog> ERROR IN LINE '.__LINE__);
      return true;
    }
    return true;
  }

  function finder_rename($dir, $from, $to) {
    global $pdo;
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
    // rename in db
    try {
      $oldname = $path.$from;
      $newname = $path.$to;
      $stmt = $pdo->prepare("UPDATE `finder_files` SET `path` = :to1 WHERE `finder_files`.`path` = :from1");
      $stmt->execute(Array(
        ':from1' => $oldname,
        ':to1' => $newname
      ));
    }
    catch(Exception $errorException) {
      log_error_to_file('<db_finder.php : finder_rename> ERROR IN LINE '.__LINE__);
      return false;
    }
    return true;
  }

  function finder_remove_file($where, $filename, $ignore_permissions = null) {
    global $pdo;
    global $bad_filenames_array;
    global $trash_path;
    global $docs_files_path;
    global $books_files_path;
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
    // create tmp name
    $tmp_name = 'trash';
    $not_free = true;
    while($not_free) {
      // create
      $tmp_name = 'trash-'.sha1(time().random_int(1000, 9999)).'.tmp';
      // check
      if(!file_exists($trash_path.$tmp_name)) {
        $not_free = false;
      }
    }
    // check trash
    create_directory($trash_path);
    // move to trash
    try {
      $oldname = $realpath.$filename;
      $newname = $trash_path.$tmp_name;
      $status = rename($oldname, $newname);
      if($status === false) {
        return false;
      }
    }
    catch(Exception $e) {
      return false;
    }
    // remove from db finder_files
    try {
      $oldname = $path.$filename;
      $stmt = $pdo->prepare("DELETE FROM `finder_files` WHERE `path` = ?");
      $stmt->execute([$oldname]);
    }
    catch(Exception $errorexception) {
      log_error_to_file('<db_finder.php : finder_remove_file> ERROR IN LINE '.__LINE__);
      return false;
    }
    // add to db finder_trash
    try {
      $oldname = $path.$filename;
      $stmt = $pdo->prepare('INSERT INTO `finder_trash` (`hash`, `path`, `type`, `link`) VALUES (:hash1, :path1, :type1, :link1) ON DUPLICATE KEY UPDATE `hash` = :hash2, `path` = :path2, `type` = :type2, `link` = :link2');
      $stmt->execute(Array(
        ':hash1' => $tmp_name,
        ':path1' => $oldname,
        ':type1' => 'file',
        ':link1' => '',
        ':hash2' => $tmp_name,
        ':path2' => $oldname,
        ':type2' => 'file',
        ':link2' => ''
      ));
    }
    catch(Exception $errorException) {
      log_error_to_file('<db_finder.php : finder_remove_file> ERROR IN LINE '.__LINE__);
      return false;
    }
    return true;
  }

  function finder_remove_catalog($where) {
    global $pdo;
    global $bad_filenames_array;
    global $trash_path;
    global $users_files_path;
    global $docs_files_path;
    global $books_files_path;
    global $tmp_files_path;
    // check path
    $path = prepare_path($where);
    if($path === false) {
      log_error_to_file('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__); //
      return false;
    }
    $realpath = finder_real($where);
    if($realpath === false) {
      log_error_to_file('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__); //
      return false;
    }
    // check catalog exists
    if(!file_exists($realpath)) {
      log_error_to_file('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__); //
      return false;
    }
    // is dir ?
    if(!is_dir($realpath)) {
      log_error_to_file('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__); //
      return false;
    }
    // get directory name
    $dirname = mb_substr($path, mb_strripos(mb_substr($path, 0, -1), '/') + 1, -1);
    // check permissions
    if(($realpath == $trash_path) || ($realpath == $users_files_path) || ($realpath == $docs_files_path) || ($realpath == $books_files_path) || ($realpath == $tmp_files_path)) {
      log_error_to_file('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__); //
      return false;
    }
    if(file_is_forbidden($dirname)) {
      log_error_to_file('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__); //
      return false;
    }
    if(in_array($dirname, $bad_filenames_array)) {
      log_error_to_file('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__); //
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
          log_error_to_file('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__); //
          return false;
        }
      }
      // is a directory
      if(is_dir($full_realpath)) {
        // remove next directory
        $status = finder_remove_catalog($full_vpath);
        if($status === false) {
          log_error_to_file('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__); //
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
      log_error_to_file('<'.__FILE__.' : '.__FUNCTION__.'> ERROR IN LINE'.__LINE__);
    }
    // remove from db finder_files
    try {
      $oldname = $path;
      $stmt = $pdo->prepare("DELETE FROM `finder_files` WHERE `path` = ?");
      $stmt->execute([$oldname]);
    }
    catch(Exception $errorexception) {
      log_error_to_file('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__);
      return false;
    }
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
    global $pdo;
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
    // db
    try {
      $oldname = $vpath_from.$filename;
      $newname = $vpath_to.$new_filename;
      $stmt = $pdo->prepare("UPDATE `finder_files` SET `path` = :to1 WHERE `finder_files`.`path` = :from1");
      $stmt->execute(Array(
        ':from1' => $oldname,
        ':to1' => $newname
      ));
    }
    catch(Exception $e) {
      log_error_to_file('<db_finder.php : finder_move_file> ERROR IN LINE '.__LINE__);
    }
    return true;
  }

  function finder_copy_file($from, $to, $filename, $ignore_permissions = null) {
    global $pdo;
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
    // db
    try {
      $stmt = $pdo->prepare("INSERT INTO `finder_files` (`path`, `type`, `size`, `count`) VALUES (?, 'file', 0, 0)");
      $stmt->execute([$vpath_to.$new_filename]);
    }
    catch(Exception $errorexception) {
      log_error_to_file('<db_finder.php : finder_copy_file> ERROR IN LINE '.__LINE__);
    }
    return true;
  }

  function finder_copy_catalog($from, $to, $ignore_permissions = null) {
    global $pdo;
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
      log_error_to_file('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__); //
      return false;
    }
    if(in_array($dirname, $bad_filenames_array)) {
      log_error_to_file('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__); //
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
    // =========================================================================
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
          log_error_to_file('<db_finder.php : finder_copy_catalog> ERROR IN LINE '.__LINE__); //
          return false;
        }
      }
      // is a directory
      if(is_dir($full_realpath)) {
        // move next directory
        $status = finder_copy_catalog($vpath_from.$file, $vpath_to.$dirname, $ignore_permissions);
        if($status === false) {
          log_error_to_file('<db_finder.php : finder_copy_catalog> ERROR IN LINE '.__LINE__); //
          return false;
        }
      }
    }
    // close directory
    closedir($dir);
    // =========================================================================
    // db
    try {
      $stmt = $pdo->prepare("INSERT INTO `finder_files` (`path`, `type`, `size`, `count`) VALUES (?, 'directory', 0, 0)");
      $stmt->execute([$vpath_to]);
    }
    catch(Exception $errorexception) {
      log_error_to_file('<db_finder.php : finder_copy_catalog> ERROR IN LINE '.__LINE__);
    }
    // end
    return true;
  }

  function finder_move_catalog($from, $to, $ignore_permissions = null) {
    global $pdo;
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
      log_error_to_file('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__); //
      return false;
    }
    if(in_array($dirname, $bad_filenames_array)) {
      log_error_to_file('<db_finder.php : finder_remove_directory> ERROR IN LINE '.__LINE__); //
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
    // =========================================================================
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
          log_error_to_file('<db_finder.php : finder_copy_catalog> ERROR IN LINE '.__LINE__); //
          return false;
        }
      }
      // is a directory
      if(is_dir($full_realpath)) {
        // move next directory
        $status = finder_move_catalog($vpath_from.$file, $vpath_to.$dirname, $ignore_permissions);
        if($status === false) {
          log_error_to_file('<db_finder.php : finder_copy_catalog> ERROR IN LINE '.__LINE__); //
          return false;
        }
      }
    }
    // close directory
    closedir($dir);
    // =========================================================================
    // remove old catalog
    finder_remove_catalog($vpath_from);
    // db
    try {
      $stmt = $pdo->prepare("UPDATE `finder_files` SET `path` = :to1 WHERE `finder_files`.`path` = :from1");
      $stmt->execute(Array(
        ':from1' => $vpath_from,
        ':to1' => $vpath_to
      ));
    }
    catch(Exception $e) {
      log_error_to_file('<db_finder.php : finder_move_catalog> ERROR IN LINE '.__LINE__);
    }
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
      log_error_to_file('<db_finder.php : finder_copycut_from> mode = '.$mode); //
      return false;
    }
    // prepare
    $is_dir = finder_is_dir($what);
    $path = '';
    $realpath = '';
    if($is_dir) {
      $path = prepare_path($what);
      if($path === false) {
        log_error_to_file('<db_finder.php : finder_copycut_from> ERROR IN LINE '.__LINE__); //
        return false;
      }
      $realpath = finder_real($what);
      if($realpath === false) {
        log_error_to_file('<db_finder.php : finder_copycut_from> ERROR IN LINE '.__LINE__); //
        return false;
      }
    }
    else {
      $what_obj = finder_get_filename_from_path($what);
      $directory_path = $what_obj->path;
      $file_path = $what_obj->file;
      $dir_prepared = prepare_path($directory_path);
      if($dir_prepared === false) {
        log_error_to_file('<db_finder.php : finder_copycut_from> ERROR IN LINE '.__LINE__); //
        return false;
      }
      $real_dir_prepared = finder_real($directory_path);
      if($real_dir_prepared === false) {
        log_error_to_file('<db_finder.php : finder_copycut_from> ERROR IN LINE '.__LINE__); //
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
      log_error_to_file('<db_finder.php : finder_copycut_from> ERROR IN LINE '.__LINE__); //
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
      log_error_to_file('<db_finder.php : finder_paste_to> ERROR IN LINE '.__LINE__); //
      return false;
    }
    $realpath = finder_real($dir);
    if($realpath === false) {
      log_error_to_file('<db_finder.php : finder_paste_to> ERROR IN LINE '.__LINE__); //
      return false;
    }
    // check exists
    if(!file_exists($realpath)) {
      log_error_to_file('<db_finder.php : finder_paste_to> ERROR IN LINE '.__LINE__); //
      return false;
    }
    // is dir ?
    if(!is_dir($realpath)) {
      log_error_to_file('<db_finder.php : finder_paste_to> ERROR IN LINE '.__LINE__); //
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
          log_error_to_file('<db_finder.php : finder_paste_to> ERROR IN LINE '.__LINE__); //
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
          log_error_to_file('<db_finder.php : finder_paste_to> ERROR IN LINE '.__LINE__); //
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

  // === finder development functions ==========================================

  function finder_trash_remove($hash) {
    global $bad_filenames_array;
    global $trash_path;
    global $pdo;
    $realpath = $trash_path.$hash;
    // remove realpath
    try {
      if(file_exists($realpath)) {
        chmod($realpath, 0777);
        unlink($realpath);
      }
    }
    catch(Exception $e) {
      log_error_to_file('<'.__FILE__.' : '.__FUNCTION__.'> ERROR IN LINE'.__LINE__);
      return false;
    }
    // try to remove from db anyway
    try {
      $stmt = $pdo->prepare("DELETE FROM `finder_trash` WHERE `hash` = ?");
      $stmt->execute([$hash]);
    }
    catch(Exception $e) {
      log_error_to_file('<'.__FILE__.' : '.__FUNCTION__.'> ERROR IN LINE '.__LINE__);
    }
    return true;
  }

  function finder_trash_recovery($hash, $path) {
    global $trash_path;
    global $pdo;
    global $bad_filenames_array;
    $file_regex = '/^([^*|:;"<>?\/\\\\])+$/ui';
    // prepare
    $rpath_from = $trash_path.$hash;
    $vpath_obj = finder_get_filename_from_path($path);
    $vpath_dir = prepare_path($vpath_obj->path, true);
    $vpath_file = $vpath_obj->file;
    $vpath_to = '';
    $rpath_to = '';
    if($vpath_dir === false) {
      return false;
    }
    if(mb_strlen($vpath_file) == 0) {
      return false;
    }
    // check length
    $path_arr = explode('/', mb_substr($vpath_dir, 0, -1));
    if(count($path_arr) > 255) {
      return false;
    }
    // create folders
    foreach($path_arr as $level => $folder) {
      // check folder name
      if((mb_strlen($folder) > 255) || file_is_forbidden($folder) || in_array($folder, $bad_filenames_array)) {
        return false;
      }
      // prepare
      $vpath_to = $vpath_to.$folder.'/';
      $rpath_to = finder_real($vpath_to, true);
      // check if folder not exists
      if(!file_exists($rpath_to)) {
        create_directory($rpath_to);
      }
    }
    // check filename
    if((mb_strlen($vpath_file) > 255) || file_is_forbidden($vpath_file) || in_array($vpath_file, $bad_filenames_array) || !preg_match($file_regex, $vpath_file)) {
      return false;
    }
    // create new name
    $not_free = true;
    $dot_pos = mb_strripos($vpath_file, '.');
    $ext = mb_substr($vpath_file, $dot_pos + 1, mb_strlen($vpath_file));
    $filename_name = mb_substr($vpath_file, 0, $dot_pos);
    $new_filename = $vpath_file;
    $counter = 0;
    while($not_free) {
      // create
      if($counter > 0) {
        $new_filename = $filename_name.' ('.$counter.').'.$ext;
      }
      else {
        $new_filename = $vpath_file;
      }
      // check
      $rpath_to = finder_real($vpath_dir, true).$new_filename;
      if(!file_exists($rpath_to)) {
        $not_free = false;
      }
      // limiter
      if($counter > 100) {
        return false;
      }
      // counter
      $counter++;
    }
    // move file
    try {
      $status = rename($rpath_from, $rpath_to);
      if($status === false) {
        log_error_to_file('<'.__FILE__.' : '.__FUNCTION__.'> ERROR IN LINE '.__LINE__);
        return false;
      }
    }
    catch(Exception $e) {
      log_error_to_file('<'.__FILE__.' : '.__FUNCTION__.'> ERROR IN LINE '.__LINE__);
      return false;
    }
    // db : remove from finder_trash
    try {
      $stmt = $pdo->prepare("DELETE FROM `finder_trash` WHERE `hash` = ?");
      $stmt->execute([$hash]);
    }
    catch(Exception $e) {
      log_error_to_file('<'.__FILE__.' : '.__FUNCTION__.'> ERROR IN LINE '.__LINE__);
    }
    // db : add to finder_files
    try {
      $stmt = $pdo->prepare('INSERT INTO `finder_files` (`path`, `type`, `size`, `count`) VALUES (:path1, :type1, :size1, :count1) ON DUPLICATE KEY UPDATE `path` = :path2, `type` = :type2, `size` = :size2, `count` = :count2');
      $stmt->execute(Array(
        ':path1' => $vpath_dir.$new_filename,
        ':type1' => 'file',
        ':size1' => 0,
        ':count1' => 0,
        ':path2' => $vpath_dir.$new_filename,
        ':type2' => 'file',
        ':size2' => 0,
        ':count2' => 0
      ));
    }
    catch(Exception $errorException) {
      log_error_to_file('<'.__FILE__.' : '.__FUNCTION__.'> ERROR IN LINE '.__LINE__);
    }
    return true;
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

  // === finder routine ========================================================

  $routine_count_limit = 1;
  $routine_counter = 0;

  // --- remove old temporarily files ------------------------------------------
  if($routine_counter < $routine_count_limit) {
    $the_time_limit = 21600; // 6 hours
    $status = timers_check('ROUTINE_FNDRTMPFLS', $the_time_limit);
    if($status === true) {
      // counter
      $routine_counter++;
      // scan tmp files
      $cur_tmp = scandir($tmp_files_path);
      foreach($cur_tmp as $key => $tmp_file) {
        // blocked
        if($tmp_file == '.' || $tmp_file == '..') {
          continue;
        }
        // remove if is old
        $now_time = time();
        $file_time = filemtime($tmp_files_path.$tmp_file);
        if(($now_time - $file_time) > $the_time_limit) {
          try {
            chmod($tmp_files_path.$tmp_file, 0777);
            unlink($tmp_files_path.$tmp_file);
          }
          catch(Exception $e) {
            log_error_to_file('<routine> ERROR: '.$status.' IN LINE '.__LINE__);
          }
        }
      }
    }
    else if($status === false) {
      // ignore
    }
    else {
      log_error_to_file('<routine> ERROR: '.$status.' IN LINE '.__LINE__);
    }
  }

  // --- remove old files in trash ---------------------------------------------
  if($routine_counter < $routine_count_limit) {
    $the_time_limit = 604800; // 7 days
    $status = timers_check('ROUTINE_FNDRTROLD', $the_time_limit);
    if($status === true) {
      // counter
      $routine_counter++;
      // scan files in trash
      $files = scandir($trash_path);
      foreach($files as $key => $file) {
        // blocked
        if($file == '.' || $file == '..') {
          continue;
        }
        // remove if is old
        $now_time = time();
        $file_time = filemtime($trash_path.$file);
        if(($now_time - $file_time) > $the_time_limit) {
          try {
            chmod($trash_path.$file, 0777);
            unlink($trash_path.$file);
          }
          catch(Exception $e) {
            log_error_to_file('<routine> ERROR: '.$status.' IN LINE '.__LINE__);
          }
        }
      }

    }
    else if($status === false) {
      // ignore
    }
    else {
      log_error_to_file('<routine> ERROR: '.$status.' IN LINE '.__LINE__);
    }
  }

  // --- remove bad files in trash ---------------------------------------------
  if($routine_counter < $routine_count_limit) {
    $the_time_limit = 259200; // 3 days
    $status = timers_check('ROUTINE_FNDRTRBAD', $the_time_limit);
    if($status === true) {
      // counter
      $routine_counter++;
      // get files from db and remove record if file not exists
      $files_db = Array();
      $files_db_real = Array();
      try {
        $stmt = $pdo->prepare("SELECT * FROM `finder_trash`");
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
          if(file_exists($trash_path.$row['hash'])) {
            $files_db[] = $row['hash'];
            $files_db_real[$row['hash']] = $row['path'];
          }
          else {
            $stmt2 = $pdo->prepare("DELETE FROM `finder_trash` WHERE `hash` LIKE ?");
            $stmt2->execute([$row['hash']]);
            log_error_to_file('<db_finder.php : routine> deleted from DB: '.$row['hash']);
          }
        }
      }
      catch(Exception $e) {
        log_error_to_file('<db_finder.php : routine> ERROR IN LINE '.__LINE__);
      }
      // scan files in trash and remove file if record not exists
      $files_real = scandir($trash_path);
      foreach($files_real as $key => $file) {
        // blocked
        if($file == '.' || $file == '..') {
          continue;
        }
        if(!in_array($file, $files_db)) {
          try {
            chmod($trash_path.$file, 0777);
            if(is_file($trash_path.$file)) {
              unlink($trash_path.$file);
              log_error_to_file('<db_finder.php : routine> deleted file: '.$trash_path.$file);
            }
            else {
              remove_directory_r($trash_path.$file);
              log_error_to_file('<db_finder.php : routine> deleted directory: '.$trash_path.$file);
            }
          }
          catch(Exception $e) {
            log_error_to_file('<routine> ERROR: '.$status.' IN LINE '.__LINE__);
          }
        }
        else {
          if(isset($files_db_real[$file])) {
            $the_path = $files_db_real[$file];
            $the_filename = mb_substr($the_path, mb_strripos($the_path, '/') + 1, mb_strlen($the_path));
            if($the_filename == 'desktop.ini') {
              try {
                chmod($trash_path.$file, 0777);
                unlink($trash_path.$file);
                log_error_to_file('<db_finder.php : routine> deleted file: '.$trash_path.$file);
              }
              catch(Exception $e) {
                log_error_to_file('<routine> ERROR: '.$status.' IN LINE '.__LINE__);
              }
            }
          }
        }
      }
    }
    else if($status === false) {
      // ignore
    }
    else {
      log_error_to_file('<routine> ERROR: '.$status.' IN LINE '.__LINE__);
    }
  }

  // == server requests ========================================================

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
      'max' => $maximum_volume
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
    $mode = 'copy';
    if($mode_str == 'cut') {
      $mode = 'cut';
    }
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
    // change filename
    $filename = $_FILES[0]['name'];
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
    if ($_FILES[0]['size'] > 2147482625) { // 1.99 GB
      exit('LIMIT.');
    }
    // save file
    if(!move_uploaded_file($_FILES[0]['tmp_name'], $current_catalog.$filename)) {
      exit('DOWNLOADING_ERROR.');
    }
    // end
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['finder_upload_multiply'])) {
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
      // check file size
      if ($_FILES[$i]['size'] > 2147482625) { // 1.99 GB
        exit('LIMIT.');
      }
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
    if(!file_exists($tmp_files_path)) {
      create_directory($tmp_files_path);
    }
    // generate filename for archive
    $hash = 'hash';
    $tmp_name = 'download.zip';
    $not_free = true;
    while($not_free) {
      $hash = sha1(time().random_int(1000, 9999));
      $tmp_name = 'download-'.$hash.'.zip';
      if(!file_exists($tmp_files_path.$tmp_name)) { $not_free = false; }
    }
    // create archive
    try {
      finder_zip($realpath, $tmp_files_path.$tmp_name);
    }
    catch(Exception $e) {
      log_error_to_file('<db_finder.php : download_catalog> ERROR IN LINE '.__LINE__);
      exit('ERROR.');
    }
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
      'path' => $tmp_files_path.$tmp_name,
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
            // remove file
            unlink($realpath);
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

  if(isset($_POST['create_zip'])) {
    // prepare
    $path = htmlspecialchars($_POST['create_zip'], ENT_QUOTES);
    $realpath = finder_real($path);
    // check exists
    if($realpath === false) {
      exit('EMPTY.');
    }
    // parent catalog and archive name
    $s_pos = mb_strripos(mb_substr($realpath, 0, -1), '/');
    $parent = mb_substr($realpath, 0, $s_pos + 1);
    $current = mb_substr($realpath, $s_pos + 1, -1);
    // generate filename for archive
    $archive = $current.'.zip';
    $not_free = true;
    $counter = 0;
    while($not_free) {
      // generate tmp name
      if($counter > 0) {
        $archive = $current.' ('.$counter.').zip';
      }
      // check exists
      if(!file_exists($parent.$archive)) { $not_free = false; }
      // execution limit
      if($counter > 200) {
        exit('ERROR.');
      }
      $counter++;
    }
    // check archive name
    if($archive == '.zip') {
      exit('ERROR.');
    }
    // create archive
    try {
      finder_zip($realpath, $parent.$archive);
    }
    catch(Exception $e) {
      log_error_to_file('<db_finder.php : create_zip> ERROR IN LINE '.__LINE__);
      exit('ERROR.');
    }
    // add to db
    $s_pos = mb_strripos(mb_substr($path, 0, -1), '/');
    $parent = mb_substr($path, 0, $s_pos + 1);
    try {
      $stmt = $pdo->prepare("INSERT INTO `finder_files` (`path`, `type`, `size`, `count`) VALUES (:the_path, :the_type, :the_size, :the_count)");
      $stmt->execute(Array(
        ':the_path' => $parent.$archive,
        ':the_type' => 'archive',
        ':the_size' => 0,
        ':the_count' => 0
      ));
    }
    catch(Exception $errorException) {
      log_error_to_file('<db_finder.php : create_zip> ERROR IN LINE '.__LINE__);
      return true;
    }
    exit('OK.');
  }

  // ===========================================================================

  if(isset($_POST['finder_search'])) {
    // prepare
    $needle = htmlspecialchars($_POST['finder_search'], ENT_QUOTES);
    if(mb_strlen($needle) < 2) {
      exit('EMPTY.');
    }
    $founded_array = Array();
    // find files and directories
    try {
      $stmt = $pdo->prepare("SELECT * FROM `finder_files` WHERE `path` LIKE concat('%',:needle,'%')");
      $success = $stmt->execute(Array(':needle' => $needle));
      if(!$success) {
        log_error_to_file('<db_finder.php : finder_search> ERROR IN LINE '.__LINE__);
        exit('ERROR.');
      }
    }
    catch(Exception $exception) {
      log_error_to_file('<db_finder.php : finder_search> ERROR IN LINE '.__LINE__);
      exit('ERROR.');
    }
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      // get
      $path = $row['path'];
      $type = $row['type'];
      $size = $row['size'];
      // check forbidden folders LABEL39181
      if(($path == '/TRASH_CAN/') || ($path == '/USERS_FILES/') || ($path == '/DOCS_FILES/') || ($path == '/BOOKS_FILES/') || ($path == '/TMP_FILES/')) {
        continue;
      }
      // check if founded in filename or last-child directory
      $s_pos =  mb_strripos(mb_substr($path, 0, -1), '/');
      $is_founded = mb_strripos($path, $needle);
      if($is_founded === false || ($is_founded <= $s_pos)) continue;
      // exists
      $exists = file_exists($root_relative_path.$path);
      if($exists === false) {
        continue;
      }
      // get file date
      $file_date = '';
      if($type != 'directory') {
        $file_date = date("d.m.Y", filemtime($root_relative_path.$path));
      }
      // add record
      $founded_array[] = (object)Array(
        'path' => $path,
        'date' => $file_date,
        'type' => $type,
        'size' => $size
      );
    }
    if(empty($founded_array)) {
      exit('EMPTY.');
    }
    // done
    echo('OK.');
    echo(json_encode($founded_array));
    exit();
  }

  // ===========================================================================

  if(isset($_POST['finder_listing_trash'])) {
    // output data
    $listing = Array();
    // get from db
    try {
      $stmt = $pdo->prepare("SELECT * FROM `finder_trash`");
      $stmt->execute();
      while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
        if(file_exists($trash_path.$row['hash'])) {
          $file_date = date("d.m.Y", filemtime($trash_path.$row['hash']));
          $file_size = filesize64($trash_path.$row['hash']);
          $listing[] = (object)Array(
            'hash' => $row['hash'],
            'path' => $row['path'],
            'date' => $file_date,
            'type' => $row['type'],
            'size' => $file_size
          );
        }
        else {
          $stmt2 = $pdo->prepare("DELETE FROM `finder_trash` WHERE `hash` LIKE ?");
          $stmt2->execute([$row['hash']]);
          log_error_to_file('<db_finder.php : finder_listing_trash> deleted: '.$trash_path.$row['hash']);
        }
      }
    }
    catch(Exception $e) {
      log_error_to_file('<db_finder.php : finder_listing_trash> ERROR IN LINE '.__LINE__);
    }
    // json output
    echo('OK.');
    echo(json_encode($listing));
    exit();
  }

  // ===========================================================================

  if(isset($_POST['trash_remove_one'])) {
    $hash = htmlspecialchars($_POST['trash_remove_one'], ENT_QUOTES);
    $status = finder_trash_remove($hash);
    if($status === true) {
      exit('OK.');
    }
    else {
      exit('ERROR.');
    }
  }

  // ===========================================================================

  if(isset($_POST['trash_recovery_one'])) {
    $hash = htmlspecialchars($_POST['trash_recovery_one'], ENT_QUOTES);
    if(!isset($_POST['recovery_to'])) {
      exit('WRONG.');
    }
    $path = htmlspecialchars($_POST['recovery_to'], ENT_QUOTES);
    $status = finder_trash_recovery($hash, $path);
    if($status === true) {
      exit('OK.');
    }
    else {
      exit('ERROR.');
    }
  }

  // ===========================================================================

  if(isset($_POST['privilege'])) {

  }

  if(isset($_POST['about_file'])) {

  }

  // == testing ================================================================

  if(isset($_GET['trash_scan'])) {
    print_r(scandir($trash_path));
    exit();
  }

  // ===========================================================================

  exit('EMPTY.');

?>
