<?php

  /*
  *  Cloudly Admin Panel v1.12 alpha
  *  (c) 2019-2020 INSOweb  <http://insoweb.ru>
  *  All rights reserved.
  */

  if(isset($_GET['file_version'])) {
  exit('db_r_scanner VERSION: 1');
  }

  if(empty($_POST) && empty($_GET)) {
  exit('EMPTY.');
  }

  // == setup ==================================================================

  include_once('db_includes.php');

  session_name($sess_name);
  session_start();

  // == parameters =============================================================

  $dir_info_filename = '.htdirinfo';
  $root_relative_path = '..';
  $trash_path = '../TRASH_CAN/';                                                // CHANGE IN db_finder.php
  $users_files_path = '../USERS_FILES/';                                        // CHANGE IN db_finder.php AND IN index.php
  $docs_files_path = '../DOCS_FILES/';                                          // CHANGE IN db_finder.php
  $books_files_path = '../BOOKS_FILES/';                                        // CHANGE IN db_finder.php
  $tmp_files_path = '../TMP_FILES/';                                            // CHANGE IN db_finder.php

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

  // === external methods ======================================================

  function filesize64($file) {
    static $iswin;
    if(!isset($iswin)) {
      $iswin = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
    }
    static $exec_works;
    if(!isset($exec_works)) {
      $exec_works = (function_exists('exec') && !ini_get('safe_mode') && @exec('echo EXEC') == 'EXEC');
    }
    // try a shell command
    if($exec_works) {
      $cmd = ($iswin) ? "for %F in (\"$file\") do @echo %~zF" : "stat -c%s \"$file\"";
      @exec($cmd, $output);
      if(is_array($output) && ctype_digit($size = trim(implode("\n", $output)))) {
        return $size;
      }
    }
    // try the Windows COM interface
    if($iswin && class_exists("COM")) {
      try {
        $fsobj = new COM('Scripting.FileSystemObject');
        $f = $fsobj->GetFile(realpath($file));
        $size = $f->Size;
      }
      catch(Exception $e) {
        $size = null;
      }
      if(ctype_digit($size)) {
        return $size;
      }
    }
    // if all else fails
    $the_filesize = 0;
    try {
      $the_filesize = filesize($file);
    }
    catch(Exception $e) {
      $the_filesize = 0;
    }
    return $the_filesize;
  }

  // === finder functions ======================================================

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

  function prepare_path($path, $mode = null) {
    global $root_relative_path;
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
        return false;
      }
      return $path;
    }
    else {
      return false;
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

  // === files scanner =========================================================

  $finder_routine_scanner_array = Array();

  function finder_routine_scanner($path) {
    global $finder_routine_scanner_array;
    global $trash_path;
    global $users_files_path;
    global $docs_files_path;
    global $books_files_path;
    global $tmp_files_path;
    $realpath = finder_real($path);
    if($realpath === false) {
     log_error_to_file('<finder_routine_scanner> ERROR IN LINE '.__LINE__);
     return false;
    }
    // ignore forbidden folders
    /*if(($realpath == $trash_path) || ($realpath == $users_files_path) || ($realpath == $docs_files_path) || ($realpath == $books_files_path) || ($realpath == $tmp_files_path)) {
      return false;
    }*/
    if(($realpath == $trash_path) || ($realpath == $tmp_files_path)) {
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
       // write
       $file_size = filesize64($full_realpath);
       $file_date = date("d.m.Y", filemtime($full_realpath));
       $finder_routine_scanner_array[] = Array(
         'path' => $full_vpath,
         'type' => 'file',
         'size' => $file_size,
         'date' => $file_date,
         'count' => 0
       );
     }
     // is a directory
     if(is_dir($full_realpath)) {
       // ignore forbidden folders
       /*if((($full_realpath.'/') == $trash_path) || (($full_realpath.'/') == $users_files_path) || (($full_realpath.'/') == $docs_files_path) || (($full_realpath.'/') == $books_files_path) || (($full_realpath.'/') == $tmp_files_path)) {
         continue;
       }*/
       if((($full_realpath.'/') == $trash_path) || (($full_realpath.'/') == $tmp_files_path)) {
         continue;
       }
       // write
       $dir_date = date("d.m.Y", filemtime($full_realpath.'/.'));
       $finder_routine_scanner_array[] = Array(
         'path' => $full_vpath.'/',
         'type' => 'directory',
         'size' => 0,
         'date' => $dir_date,
         'count' => 0
       );
       // run next
       finder_routine_scanner($full_vpath.'/');
     }
    }
    // close directory
    closedir($dir);
  }

  function finder_routine_scanner_run() {
    global $finder_routine_scanner_array;
    global $pdo;
    // array for scanned files or directories
    $finder_routine_scanner_array = Array(
     0 => Array(
       'path' => '/',
       'type' => 'directory',
       'size' => 0,
       'count' => 0
       )
    );
    // run scanner
    finder_routine_scanner('/');
    // run tree
    for($i = count($finder_routine_scanner_array) - 1; $i > -1; $i--) {
     // get values
     $current_path = $finder_routine_scanner_array[$i]['path'];
     $current_type = $finder_routine_scanner_array[$i]['type'];
     $current_size = $finder_routine_scanner_array[$i]['size'];
     $current_count = $finder_routine_scanner_array[$i]['count'];
     // find id of parent catalog
     $id = $i;
     $parent_path = finder_get_parent_dir($current_path);
     for($j = $i; $j > -1; $j--) {
       $needle_path = $finder_routine_scanner_array[$j]['path'];
       $needle_type = $finder_routine_scanner_array[$j]['type'];
       if($needle_type == 'directory') {
         if($needle_path == $parent_path) {
           $id = $j;
           break;
         }
       }
     }
     // +size and +count to parent catalog
     if($id != $i) {
       if($current_type == 'directory') {
         $finder_routine_scanner_array[$id]['count'] = $finder_routine_scanner_array[$id]['count'] + $current_count;
       }
       else {
         $finder_routine_scanner_array[$id]['count'] = $finder_routine_scanner_array[$id]['count'] + 1;
       }
       $finder_routine_scanner_array[$id]['size'] = $finder_routine_scanner_array[$id]['size'] + $current_size;
     }
    }
    // clear db
    try {
     $stmt = $pdo->prepare("TRUNCATE TABLE `finder_files`");
     $stmt->execute();
    }
    catch(Exception $e) {
     log_error_to_file('<finder_routine_scanner_run> ERROR '.$e.' IN LINE '.__LINE__);
     return 'ERROR: '.$e;
    }
    // save to db
    foreach($finder_routine_scanner_array as $i => $file) {
     try {
       $stmt = $pdo->prepare('INSERT INTO `finder_files` (`path`, `type`, `size`, `count`) VALUES (:path1, :type1, :size1, :count1) ON DUPLICATE KEY UPDATE `path` = :path2, `type` = :type2, `size` = :size2, `count` = :count2');
       $stmt->execute(Array(
         ':path1' => $file['path'],
         ':type1' => $file['type'],
         ':size1' => $file['size'],
         ':count1' => $file['count'],
         ':path2' => $file['path'],
         ':type2' => $file['type'],
         ':size2' => $file['size'],
         ':count2' => $file['count']
       ));
     }
     catch(Exception $errorException) {
       log_error_to_file('<finder_routine_scanner_run> ERROR '.$errorException.' IN LINE '.__LINE__);
       return 'ERROR: '.$errorException;
     }
    }
    return true;
  }

  $routine_was_running = false;

  if(true) {
    $status = timers_check('ROUTINE_FNDRSCNNR', 43200);
    if($status === true) {
      echo('RUNNING.');
      // run
      $routine_was_running = true;
      log_error_to_file('<routine> running...');
      $scanner_status = finder_routine_scanner_run();
      if($scanner_status !== true) {
        // log
        log_error_to_file('<routine> ERROR: '.$scanner_status.' IN LINE '.__LINE__);
      }
    }
    else if($status === false) {
      // ignore
    }
    else {
      // log
      log_error_to_file('<routine> ERROR: '.$status.' IN LINE '.__LINE__);
    }

    if($routine_was_running) {
      log_error_to_file('<routine> end');
    }
  }

  echo('OK.');

?>
