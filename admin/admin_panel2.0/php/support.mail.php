<?php

  // require_once('lib_php/phpmailerNew/PHPMailer.php');
  require_once('lib_php/phpmailerNew/PHPMailerAutoload.php');
  require("lib_php/phpmailerNew/class.phpmailer.php");
  require("lib_php/phpmailerNew/class.smtp.php");

  $mail     =   new PHPMailer;
  $name     =   $_POST['name'];
  $tel      =   $_POST['tel'];
  $email    =   $_POST['email'];
  $text     =   $_POST['text'];
  $date     =   $_POST['date'];
  $ip       =   $_SERVER['REMOTE_ADDR'];
  $request  =   file_get_contents("http://api.sypexgeo.net/json/".$_SERVER['REMOTE_ADDR']);
  $array    =   json_decode($request);
  $city     =   $array->city->name_ru;
  $country  =   $array->country->name_ru;

  $nameRegular    =   '/[A-zА-яЁё]/';
  $telRegular     =   '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/';
  $emailRegular   =   '/^\w.+@[a-zA-Z_0-9]+?\.[a-zA-Z]{2,5}$/gm';

  if(isset($name) && isset($tel) && isset($email) && isset($text)){
    if(preg_match($nameRegular, $name) && mb_strlen($name) < 50){
      if(preg_match($telRegular, $tel)){
        if(preg_match($nameRegular, $name)){
          if(mb_strlen($text) > 5 && mb_strlen($text) < 9999){
            // все хорошо, отправляем письмо на почту

            //$mail->SMTPDebug = 3;   // Для отладки

            $mail->CharSet = 'utf-8';
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'inso.web59@gmail.com';
            $mail->Password = 'poma098123';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('inso.web59@gmail.com');
            $mail->addAddress('support@insoweb.ru');     // Кому будет уходить письмо
            $mail->isHTML(true);

            $mail->Subject = 'Служба поддержки!';
            $mail->Body    = "<!DOCTYPE html><html lang='ru' dir='ltr'><head><meta charset='utf-8'><style>a{text-decoration: none;color: #303036;}::selection{background-color: #ff970840;}.tel{border-bottom: 1px solid transparent;transition: 0.25s border-bottom;color: #303036;}.tel:hover{color: #303036;border-bottom: 1px dashed #303036;}.fotter-a{border-bottom: 1px solid transparent;transition: 0.25s border-bottom;color: #303036;}.fotter-a:hover{border-bottom: 1px dashed #303036;color: #303036;}.footera:hover{color: #303036;border-bottom: 1px dashed #303036;}</style><link href='https://fonts.googleapis.com/css?family=Quicksand:300,400,500,600,700|Roboto:300,400,500,700,900&display=swap&subset=latin-ext' rel='stylesheet'></head><body style='padding: 0;margin: 0;font-family: Quicksand, sans-serif;color: #303036;'><div class='nav' style='height: 75px; width: 120px; margin-top: 30px; position: relative; margin-left: 30px; background-position: center; background-repeat: no-repeat; background-size: contain; white-space: nowrap; transform: scale(1); user-select: none;'><div style='background-image: url(http://insoweb.ru/mail/logo/cloudlyAPLogo.png); background-repeat: no-repeat; width: 80px; height: 69px; background-size: contain; display: inline-block; vertical-align: middle;'></div><div class='nav-text' style='display: inline-block; vertical-align: middle; font-size: 40px; font-weight: 700; color: #303036; line-height: 25px;'><hb><a style='color: #303036;' href='http://cloudly.insoweb.ru' target='_blank'>cloudly</a></hb><br><div class='logo-title-preloader-2' style='font-size: 22.8px; line-height: 35px;'>admin panel</div></div></div><div class='msg' style='padding-left: 30px; padding-right: 30px; padding-top: 10px; border: 1px solid #303036; padding-bottom: 50px; background-color: #fff; border-radius: 15px; margin-left: 35px; margin-top: 35px; margin-right: 35px; margin-bottom: 25px; box-shadow: 0 0 13px 0 rgba(82, 63, 105, 0.15);'><div class='title' style='font-family: Roboto ,sans-serif; margin-left: 35px; font-size: 25px; margin-top: 30px; font-weight: 700;'>Техническая поддержка</div><div class='title2' style='font-family: Roboto,sans-serif; margin-left: 35px; font-weight: 300;'>Сила появляется благодаря поддержке.</div><div class='user' style='margin-left: 50px; margin-top: 60px; font-family: Roboto,sans-serif;'><b>".$name."</b>, оставил заявку, его телефон <b class='Qw' style='font-family: Quicksand, sans-serif;'><a class='tel' style='color: #303036;' href='tel:".$tel."' title='Позвонить'>".$tel."</a></b><br>Почта этого пользователя: <b class='Qw' style='font-family: Quicksand, sans-serif;'><a class='tel' style='color: #303036;' href='mailto:".$email."' title='Написать'>".$email."</a></b><br><br>IP пользователя: <b class='Qw' style='font-family: Quicksand, sans-serif;'>".$ip."</b><br>Вероятнее всего пользователь из <b>".$country.",</b> город<b> ".$city."</b>.<br><br><br><div class='msg-user-title' style='font-weight: 700;'>Сообщение:</div><div class='msg-user-text' style='margin-top: 10px; text-align: justify; padding-right: 50px; white-space: pre-wrap;'>".$text."</div><br><br><div>Дата составления записи: <b class='Qw' style='font-family: Quicksand, sans-serif;'>".$date."</b></div></div></div><div class='footer' style='font-weight: 700; margin-left: 65px; opacity: 0.5; line-height: 25px; display: block; margin-top: 40px;'>Автоматическое сообщение</div><div class='footer2' style='margin-top: 15px; margin-left: 65px; opacity: 0.5; line-height: 15px;'>С условием обработки персональных<br>данных можно ознакомиться <a class='fotter-a' href='#'>здесь</a>.</div><a href='http://insoweb.ru/' target='_blank' class='footera' style='color: #303036; font-weight: 500; font-family: Roboto,sans-serif; margin-left: 65px; margin-top: 35px; font-size: 16px; margin-bottom: 35px; opacity: 0.5; display: inline-block; transition: 0.25s border-bottom; border-bottom: 1px solid transparent;'>© INSOweb</a></body></html>";
            $mail->AltBody = '';

            if(!$mail->send()) {
                echo 'Сообщение не отправлено: '. $mail->ErrorInfo;
            } else {
                echo 'Сообщение отправлено!';
            }

          } else{
            // msg
            echo 'Сообщение от 6 до 9999 символов!';
          }
        } else{
          // почта
          echo 'Неверно указана почта!';
        }
      } else{
        // телефон
        echo 'Неверно указан номер телефона!';
      }
    } else{
      // имя
      echo 'Ошибка в имени!';
    }
  }



?>
