<?php

  include_once('../db_includes.php');

  $function_regex = "/(^([a-zA-Z._$]{1})([0-9a-zA-Z_$])+)((\({1})([A-zА-яЁё0-9_,.\/\?=+\'!@#$%^&*№;:()\s-]*)(\)$))/ui";
  $function_replace_regex = "/((\({1})([A-zА-яЁё0-9_,.\/\?=+\'!@#$%^&*№;:()\s-]*)(\)$))/ui";
  $function_exceptions_regex = "/(session_destroy|eval)/ui";

  $msg = '';
  $type = '';
  $screenshot = '';

  session_name($sess_name);
  session_start();

  // get user information
  $userData['id'] = $_SESSION['userid'];
  $userData['login'] = $_SESSION['username'];
  $userData['statistics'] = true;
  $userData['errorlog'] = true;
  $stmt = $pdo->prepare('SELECT * FROM accounts WHERE account = ?');
  $stmt->execute([$_SESSION['username']]);
  while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
    $userData['access'] = isset($row['access_type']) ? $row['access_type'] : 'undefined';
    $userData['name1'] = isset($row['first_name']) ? $row['first_name'] : '';
    $userData['name2'] = isset($row['second_name']) ? $row['second_name'] : '';
    $userData['gender'] = isset($row['gender']) ? $row['gender'] : 'male';
    $userData['birthday'] = isset($row['birthday']) ? $row['birthday'] : '01.01.1970';
    $userData['country'] = isset($row['country']) ? $row['country'] : '';
    $userData['city'] = isset($row['city']) ? $row['city'] : '';
    $userData['phone'] = isset($row['phonenumber']) ? ("+".$row['phonenumber']) : '';
    $userData['email'] = isset($row['email']) ? $row['email'] : '';
    $userData['email2'] = isset($row['email2']) ? $row['email2'] : '';
    $userData['phone_verify'] = isset($row['phone_verify']) ? boolval($row['phone_verify']) : false;
    $userData['email_verify'] = isset($row['email_verify']) ? boolval($row['email_verify']) : false;
    $userData['email2_verify'] = isset($row['email2_verify']) ? boolval($row['email2_verify']) : false;
    $userData['new'] = isset($row['is_new']) ? boolval($row['is_new']) : true;
    $userData['mailing'] = isset($row['mailing']) ? boolval($row['mailing']) : false;
    if(isset($row['profile_icon'])) {
      if(substr($row['profile_icon'], 0, 8) == 'DEFAULT_') {
        $theIcon = substr($row['profile_icon'], 8);
        $userData['icon'] = "media/users/".$theIcon.'.jpg';
      }
      else if($row['profile_icon'] == 'DEF_ADMIN') {
        $userData['icon'] = 'media/users/admin.jpg';
      }
      else {
        $userData['icon'] = "media/users/public/".$_SESSION['username']."/profile.jpg";
      }
    }
    else {
      $userData['icon'] = "media/users/0.jpg";
    }
  }
  $stmt = $pdo->prepare('SELECT `send_statistics`, `send_logs` FROM `account_settings` WHERE `account_id` = ?');
  $stmt->execute([$_SESSION['userid']]);
  while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
    $userData['statistics'] = isset($row['send_statistics']) ? boolval($row['send_statistics']) : true;
    $userData['errorlog'] = isset($row['send_logs']) ? boolval($row['send_logs']) : true;
  }

  // get full site information
  $siteData = Array('title' => '');
  $data = $pdo->query('SELECT * FROM `site_settings`')->fetchAll(PDO::FETCH_UNIQUE);
  foreach($data as $row => $arr) {
    $siteData[strval($row)] = htmlspecialchars($arr['value'], ENT_QUOTES);
  }


  if(!isset($_SESSION) || !isset($_SESSION['auth']) || !$_SESSION['auth']) {
    echo '<c-error>Ошибка авторизации!</c-error>';
    exit();
  }

  if($userData['access'] == 'default') {
    echo '<c-error>Ошибка уровня доступа!</c-error>';
    exit();
  }


  if(isset($_POST['send'])){
    $msg = $_POST['send'];
  }
  if(isset($_POST['type'])){
    $type = $_POST['type'];
  }
  if(isset($_POST['screenshot'])){
    $screenshot = $_POST['screenshot'];
  }


  // function
  if(preg_match($function_regex, $msg) && $type == 'func'){
    $tmpFuncString = preg_replace($function_replace_regex, '', $msg);
    if(function_exists($tmpFuncString)){
      if(!preg_match($function_exceptions_regex, $msg)){
        eval('var_Dump('.$msg.');');
      } else{
        echo("<c-error>Вы используете запрещенные функции в консоли!</c-error>");
      }
    } else{
      echo("<c-error>Данной функции не существует!</c-error>");
    }
  }

  // session
  else if($type == 'session'){
    echo("<c-good>Ваши данные из сессии:</c-good><br><c-default>");
    print_r($_SESSION);
    echo("</c-default>");
  }

  // cookies
  else if($type == 'cookie'){
    echo("<c-good>Ваши данные из cookie:</c-good><br><c-default>");
    print_r($_COOKIE);
    echo("</c-default>");
  }

  // cookies
  else if($type == 'serial_key'){
    echo("<c-good>Ваш серийный номер:</c-good><br>".$serialNumber);
  }

  // lines
  else if($type == 'lines'){

    $dirname = "../";
    $extentions = array("#\.php#i","#\.json#i","#\.css#i","#\.html#i","#\.ini#i","#\.ht#i");
    $count = 0;
    scan_dir($dirname);
    echo($count);

  }

  // hash_key
  else if($type == 'hash_key'){

    $dirname = "..";
    $hash = [];
    hash_Key_2($dirname);
    echo("<c-good>Контрольная хеш-сумма:</c-good><br>".$hash[count($hash) - 1]);

  }

  // hash_key_window
  else if($type == 'hash_key_window'){

    $dirname = "..";
    $hash = [];
    hash_Key_2($dirname);
    echo($hash[count($hash) - 1]);

  }

  // list_dir
  else if($type == 'list_dir'){

    $dirname = "../..";
    $filesArray = [];
    list_dir($dirname);
    echo('<c-good>Количество файлов в системе: </c-good>'.count($filesArray));
  }

  // help
  else if($type == 'help'){
    print_r(help());
  }

  // system dump
  else if($type == 'dump'){
    echo '<c-good>Дамп системы успешно создан!</c-good>';
  }

  // other
  else{
    if($development_state){
      if(!preg_match($function_exceptions_regex, $msg)){
        eval($msg);
      } else{
        echo("<c-error>Вы используете запрещенные функции в консоли!</c-error>");
      }
    } else{
      echo("<c-error>Режим разработчика отключен!</c-error>");
    }
  }

  function list_dir($dirname){
    $regexExtHash = '/^(php|html|css|js|json|htaccess)$/ui';

    // Объявляем переменные замены глобальными
    GLOBAL $filesArray;
    // Открываем текущую директорию
    $dir = opendir($dirname);
    // Читаем в цикле директорию
    while (($file = readdir($dir)) !== false){

      // Если файл обрабатываем его содержимое
      if($file != "." && $file != ".."){
        // Если имеем дело с файлом - производим в нём замену
        if(is_file($dirname."/".$file)){
          array_push($filesArray, $dirname."/".$file);
          echo($dirname."/".$file.'<br>');
        }
        // Если перед нами директория, вызываем рекурсивно
        // функцию scan_dir
        if(is_dir($dirname."/".$file)){
          list_dir($dirname."/".$file);
        }
      }
    }
    // Закрываем директорию
    closedir($dir);
  }

  function hash_Key_2($dirname){

    $regexExtHash = '/^(php|html|css|js|json|htaccess)$/ui';

    // Объявляем переменные замены глобальными
    GLOBAL $hash;
    // Открываем текущую директорию
    $dir = opendir($dirname);
    // Читаем в цикле директорию
    while (($file = readdir($dir)) !== false){

      // Если файл обрабатываем его содержимое
      if($file != "." && $file != ".."){
        // Если имеем дело с файлом - производим в нём замену
        if(is_file($dirname."/".$file)){
          $ext = getExtension($dirname."/".$file);
          if(preg_match($regexExtHash, $ext)){

            $content = file_get_contents($dirname."/".$file);
            array_push($hash, hash_file('md5', $dirname."/".$file));
            if(count($hash) > 1){
              $hashLine = md5($hash[count($hash) - 2] . $hash[count($hash) - 1]);
              array_push($hash, $hashLine);
            }
          }

        }
        // Если перед нами директория, вызываем рекурсивно
        // функцию scan_dir
        if(is_dir($dirname."/".$file)){
          hash_Key_2($dirname."/".$file);
        }
      }
    }
    // Закрываем директорию
    closedir($dir);
  }

  function getExtension($filename) {
     return @array_pop(explode(".", $filename));
   }

  function scan_dir($dirname){
    // Объявляем переменные замены глобальными
    GLOBAL $extentions, $count;
    // Открываем текущую директорию
    $dir = opendir($dirname);
    // Читаем в цикле директорию
    while (($file = readdir($dir)) !== false){
      // Если файл обрабатываем его содержимое
      if($file != "." && $file != ".." && $file != "lib_php" && $file != "RSA" && $file != "sxgeo" && $file != "key" && $file != "vendor"){
        // Если имеем дело с файлом - производим в нём замену
        if(is_file($dirname."/".$file)){
          // Извлекаем из имени файла расширение
          $ext = strrchr($dirname."/".$file, ".");
          foreach($extentions as $exten)
          if(preg_match($exten, $ext)){
            // Читаем содержимое файла
            $content = file($dirname."/".$file);
            // Подсчтываем число файлов
            $count += count($content);
            // Удаляем массив
            unset($content);
          }
        }
        // Если перед нами директория, вызываем рекурсивно
        // функцию scan_dir
        if(is_dir($dirname."/".$file)){
          scan_dir($dirname."/".$file);
        }
      }
    }
    // Закрываем директорию
    closedir($dir);
  }

  function help(){

    $tmpHelp = parse_ini_file("help.ini", true);
    return $tmpHelp;

  }


?>
