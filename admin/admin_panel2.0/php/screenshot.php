<?php

  include_once('../db_includes.php');

  session_name($sess_name);
  session_start();


  if(!isset($_SESSION) || !isset($_SESSION['auth']) || !$_SESSION['auth']) {
    echo '<c-error>Ошибка авторизации!</c-error>';
    exit();
  }


  $gener = generateText(25);
  $way = '../system_dump/'.$gener.'/';
  $name = 'Screenshot.png';

  mkdir('../system_dump/'.$gener.'/', 0777);

  file_put_contents($way.$name, base64_decode($_POST['data'] ));

  if (!file_exists( $way.'info.ini' ) ) { // если файл НЕ существует
    $fp = fopen ($way.'info.ini', "w");
    fwrite($fp,"Login = '".$_POST['login']."'\nUser ip = ".$_SERVER['REMOTE_ADDR']."\nDate = ".date('d.m.Y')."\nTime = ".date('H:i:s')."\n");
    fclose($fp);
  } else {
    echo 'Увы, файл уже существует. Перезаписали его';
  }

  echo($gener.'/' );


  function generateText($length){
    $chars = 'abdefhiknrstyzABDEFGHKNQRSTYZ23456789';
    $numChars = strlen($chars);
    $string = '';

    for ($i = 0; $i < $length; $i++) {
      $string .= substr($chars, rand(1, $numChars) - 1, 1);
    }
    return $string;
  }

?>
