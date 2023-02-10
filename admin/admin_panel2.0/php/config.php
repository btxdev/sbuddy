<?php

  include_once('../db_includes.php');
  session_name($sess_name);
  session_start();

  if(!isset($_SESSION) || !isset($_SESSION['auth']) || !$_SESSION['auth']) {
    echo 'Ошибка авторизации!';
    exit();
  }

  $type = $_POST['type'];

  if($type == 'default'){
    if(file_exists('configPHP/config.php')){
      $file = unlink('configPHP/config.php');
      if($file){
        echo('200');
      } else{
        echo('500');
      }
    } else{
      echo('404');
    }



  }

  if($type == 'notDefault'){
    $config = json_decode($_POST['config']);
    $fileConfig = "<?php\n\n";
    $fileConfig .= "  \$timezone                    =  '".$config->{'timezone'}."';\n\n";
    $fileConfig .= "  //== db information ==========================================================\n\n";
    $fileConfig .= "  \$sql_host                    =  '".$config->{'sql_host'}."';\n";
    $fileConfig .= "  \$sql_db                      =  '".$config->{'sql_db'}."';\n";
    $fileConfig .= "  \$sql_user                    =  '".$config->{'sql_user'}."';\n";
    $fileConfig .= "  \$sql_password                =  '".$config->{'sql_password'}."';\n";
    $fileConfig .= "  \$sql_charset                 =  '".$config->{'sql_charset'}."';\n";
    $fileConfig .= "  \n";
    $fileConfig .= "  \$sql_site_host               =  '".$config->{'sql_site_host'}."';\n";
    $fileConfig .= "  \$sql_site_db                 =  '".$config->{'sql_site_db'}."';\n";
    $fileConfig .= "  \$sql_site_user               =  '".$config->{'sql_site_user'}."';\n";
    $fileConfig .= "  \$sql_site_password           =  '".$config->{'sql_site_password'}."';\n";
    $fileConfig .= "  \$sql_site_charset            =  '".$config->{'sql_site_charset'}."';\n";
    $fileConfig .= "  \n";
    $fileConfig .= "  //== parameters ==============================================================\n\n";
    $fileConfig .= "  \$profile_photos_count        =  ".$config->{'profile_photos_count'}.";\n";
    $fileConfig .= "  \$account_emails_limit        =  ".$config->{'account_emails_limit'}.";\n";
    $fileConfig .= "  \$account_phonenumbers_limit  =  ".$config->{'account_phonenumbers_limit'}.";\n";
    $fileConfig .= "  \$phone_service_works         =  ".$config->{'phone_service_works'}.";\n";
    $fileConfig .= "  \$serialNumber                =  '".$config->{'serial_Number'}."';\n";
    if($finderPanel){
      $fileConfig .= "  \n";
      $fileConfig .= "  //== finder ==================================================================\n\n";
      $fileConfig .= "  \$maximum_volume              =  ".$config->{'finder_maximum_volume'}.";\n";
      $fileConfig .= "  \$root_relative_path          =  '".$config->{'root_relative_path'}."';\n";
      $fileConfig .= "  \$trash_path                  =  '".$config->{'trash_path'}."';                              // CHANGE IN LABEL348348 AND IN db_r_scanner.php AND IN LABEL39181\n";
      $fileConfig .= "  \$users_files_path            =  '".$config->{'users_files_path'}."';                              // CHANGE IN db_r_scanner.php AND IN index.php AND IN LABEL39181\n";
      $fileConfig .= "  \$docs_files_path             =  '".$config->{'docs_files_path'}."';                               // CHANGE IN db_r_scanner.php AND IN index.php AND IN LABEL39181\n";
      $fileConfig .= "  \$books_files_path            =  '".$config->{'books_files_path'}."';                              // CHANGE IN db_r_scanner.php AND IN index.php AND IN LABEL39181\n";
      $fileConfig .= "  \$tmp_files_path              =  '".$config->{'tmp_files_path'}."';                              // CHANGE IN db_r_scanner.php AND IN LABEL39181\n";
    }

    $fileConfig .= "\n?>";

    $file = true;

    if(file_exists('configPHP/config.php')){
      $file = unlink('configPHP/config.php');
    }
    if($file){
      $fp = fopen('configPHP/config.php', "w");
      fwrite($fp, $fileConfig);
      fclose($fp);
      if($fp){
        echo('200');
      } else{
        echo('Не удалось создать новый конфиг!');
      }
    } else{
      echo('Не удалось удалить старый конфиг!');
    }


  }


?>
