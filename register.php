<?php

	include('php/standartScriptPHP.php');

	if(isset($_SESSION['login'])) {
		$referer = 'index';
		if(isset($_SESSION['referer'])) {
			$referer = $_SESSION['referer'];
			if($_SESSION['referer'] == 'none') {
				$referer = 'index';
			}
		}
		header("Location: $referer");
		exit();
	}

	$dt38RrYA1o06bSvLhVvL = idGenerator(20,5); // login
	$dxz3xh8JzW53jWeAGPu2 = idGenerator(20,5); // Name
	$drEeskRsvXgLxkPdjojv = idGenerator(20,5); // Second name
	$dZt18Z0p02okNhAWe6P5 = idGenerator(20,5); // Middle name
	$dLoPqjAeV4zfHxSBFkOv = idGenerator(20,5); // Email
	$dmZue1oVpLnsgmM3ViHL = idGenerator(20,5); // chb1
	$dxNsVQKBWkwS0UDojbpZ = idGenerator(20,5); // chb2

?>

<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Регистрация :: <?=$siteData['title']?></title>
    <link rel="stylesheet" href="style/bootstrap.min.css" >
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/window.css">
    <link rel="stylesheet" href="style/notification.css">

		<!-- re-captcha v3 -->
		<script src="https://www.google.com/recaptcha/api.js?render=6LdKcfQUAAAAAMtb7qMKV1fs2rAIzLEeJp5UdFX9"></script>
		<script>
			var captcha3Token = undefined;
			var theCaptchaToken = undefined; // copy of captcha3Token
			var captcha3Interval = undefined;
			var captcha2Rendered = false;
			function captcha3GenToken(reload) {
				grecaptcha.execute('6LdKcfQUAAAAAMtb7qMKV1fs2rAIzLEeJp5UdFX9', {action: 'login'}).then(function(token) {
					 captcha3Token = token;
					 theCaptchaToken = token;
				});
				if(typeof(reload) != 'undefined') {
					if(typeof(captcha3Interval) != 'undefined') {
						clearInterval(captcha3Interval);
					}
					captcha3Interval = setInterval(captcha3GenToken, 110000);
				}
			}
			grecaptcha.ready(function() {
				captcha3GenToken(true);
			});
		</script>

		<!-- re-captcha v2 -->
		<script>
			var captcha2Token = undefined;
			var captcha2Tries = 0;
			function captcha2Wait() {
				var token = grecaptcha.getResponse();
				if(typeof(token) == 'string' && token != '') {
					captcha2Token = token;
					register2();
					windowClose('.window-shadow', true);
					$('window').css({'z-index':'99999999999'});
					return;
				}
				if(captcha2Tries < 120) {
					setTimeout(captcha2Wait, 1000);
				}
				captcha2Tries++;
			}
			function captcha2GenToken() {
				captcha2Tries = 0;
				$('window').css({'z-index':'9'});
				windowOpen('#reCaptchaWindow');
				if(captcha2Rendered) {
					grecaptcha.reset();
				}
				else {
					grecaptcha.render('captcha-v2-div', {
						'sitekey': '6Lfwa_QUAAAAANdT5nXaGinIjZzUb3W1Ok3RD_j1'
					});
					captcha2Rendered = true;
				}
				captcha2Wait();
			}
		</script>

		<?php include('standartScriptJS.php'); ?>

		<script type="text/javascript">
			var Register;
			$(document).ready(function(){
				Register = {
					login: $('#<?=$dt38RrYA1o06bSvLhVvL;?>'),
					name: $('#<?=$dxz3xh8JzW53jWeAGPu2;?>'),
					secondName: $('#<?=$drEeskRsvXgLxkPdjojv;?>'),
					middleName: $('#<?=$dZt18Z0p02okNhAWe6P5;?>'),
					email: $('#<?=$dLoPqjAeV4zfHxSBFkOv;?>'),
					chb1: $('#<?=$dmZue1oVpLnsgmM3ViHL;?>'),
					chb2: $('#<?=$dxNsVQKBWkwS0UDojbpZ;?>'),
					sent: false
				}
			});
		</script>

		<script type="text/javascript" src="js/register.js"></script>

		<script><?php /*foreach($_SESSION as $key => $value) { echo("console.log('$key: $value'); "); }*/ ?></script>

  </head>
  <body class='register'>

    <!-- Window (start) -->
    <?php include('windows.php'); ?>
    <!-- Window (end) -->


    <?php
      if(!isset($_COOKIE['cookies_accepted'])) {
        //include('cookies.php');а
        echo(file_get_contents('cookies.html'));
      }
    ?>

    <notifications></notifications>

    <div class='register-block'>
			<div class="window-container-preloader"></div>
			<div class='window-container'>
				<div class='window-container-title' style='text-align: center; max-width: 100%;'>Регистрация</div>

				<label class='input' for="<?=$dt38RrYA1o06bSvLhVvL;?>">
          <div class='input-div'>
            <label for="<?=$dt38RrYA1o06bSvLhVvL;?>" class='input-icons icons-user'></label>
            <input type="text" class='input-input' required id='<?=$dt38RrYA1o06bSvLhVvL;?>'>
            <label for="<?=$dt38RrYA1o06bSvLhVvL;?>" class='input-placeholder'>Логин</label>
            <label for="<?=$dt38RrYA1o06bSvLhVvL;?>" class='input-line'>
              <label for="<?=$dt38RrYA1o06bSvLhVvL;?>" class='input-line-main0'></label>
              <label for="<?=$dt38RrYA1o06bSvLhVvL;?>" class='input-line-main'></label>
              <label for="<?=$dt38RrYA1o06bSvLhVvL;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>

				<label class='input' for="<?=$dxz3xh8JzW53jWeAGPu2;?>">
          <div class='input-div'>
            <label for="<?=$dxz3xh8JzW53jWeAGPu2;?>" class='input-icons icons-user-checked'></label>
            <input type="text" class='input-input' required id='<?=$dxz3xh8JzW53jWeAGPu2;?>'>
            <label for="<?=$dxz3xh8JzW53jWeAGPu2;?>" class='input-placeholder'>Имя</label>
            <label for="<?=$dxz3xh8JzW53jWeAGPu2;?>" class='input-line'>
              <label for="<?=$dxz3xh8JzW53jWeAGPu2;?>" class='input-line-main0'></label>
              <label for="<?=$dxz3xh8JzW53jWeAGPu2;?>" class='input-line-main'></label>
              <label for="<?=$dxz3xh8JzW53jWeAGPu2;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>

				<label class='input' for="<?=$drEeskRsvXgLxkPdjojv;?>">
          <div class='input-div'>
            <label for="<?=$drEeskRsvXgLxkPdjojv;?>" class='input-icons icons-user-checked'></label>
            <input type="text" class='input-input' required id='<?=$drEeskRsvXgLxkPdjojv;?>'>
            <label for="<?=$drEeskRsvXgLxkPdjojv;?>" class='input-placeholder'>Фамилия</label>
            <label for="<?=$drEeskRsvXgLxkPdjojv;?>" class='input-line'>
              <label for="<?=$drEeskRsvXgLxkPdjojv;?>" class='input-line-main0'></label>
              <label for="<?=$drEeskRsvXgLxkPdjojv;?>" class='input-line-main'></label>
              <label for="<?=$drEeskRsvXgLxkPdjojv;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>

				<label class='input' for="<?=$dZt18Z0p02okNhAWe6P5;?>">
          <div class='input-div'>
            <label for="<?=$dZt18Z0p02okNhAWe6P5;?>" class='input-icons icons-user-checked'></label>
            <input type="text" class='input-input' required id='<?=$dZt18Z0p02okNhAWe6P5;?>'>
            <label for="<?=$dZt18Z0p02okNhAWe6P5;?>" class='input-placeholder'>Отчество</label>
            <label for="<?=$dZt18Z0p02okNhAWe6P5;?>" class='input-line'>
              <label for="<?=$dZt18Z0p02okNhAWe6P5;?>" class='input-line-main0'></label>
              <label for="<?=$dZt18Z0p02okNhAWe6P5;?>" class='input-line-main'></label>
              <label for="<?=$dZt18Z0p02okNhAWe6P5;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>

				<label class='input' for="<?=$dLoPqjAeV4zfHxSBFkOv;?>">
          <div class='input-div'>
            <label for="<?=$dLoPqjAeV4zfHxSBFkOv;?>" class='input-icons icons-email'></label>
            <input type="text" class='input-input' required id='<?=$dLoPqjAeV4zfHxSBFkOv;?>'>
            <label for="<?=$dLoPqjAeV4zfHxSBFkOv;?>" class='input-placeholder'>Почта</label>
            <label for="<?=$dLoPqjAeV4zfHxSBFkOv;?>" class='input-line'>
              <label for="<?=$dLoPqjAeV4zfHxSBFkOv;?>" class='input-line-main0'></label>
              <label for="<?=$dLoPqjAeV4zfHxSBFkOv;?>" class='input-line-main'></label>
              <label for="<?=$dLoPqjAeV4zfHxSBFkOv;?>" class='input-line-main2'></label>
            </label>
          </div>
        </label>

				<label class='checkbox' for='<?=$dmZue1oVpLnsgmM3ViHL;?>'>
					<input type="checkbox" class='checkbox-checked' id='<?=$dmZue1oVpLnsgmM3ViHL;?>' style='display: none;'>
					<label class='checkbox-checkbox' style='min-height: initial; padding-left: initial;' for='<?=$dmZue1oVpLnsgmM3ViHL;?>'>
						<label class='checkbox-checkbox-line icons-checked' style='min-height: initial; padding-left: initial;' for='<?=$dmZue1oVpLnsgmM3ViHL;?>'></label>
					</label>
					<label class='checkbox-text' style='min-height: initial; padding-left: initial;' for='<?=$dmZue1oVpLnsgmM3ViHL;?>'>Согласен с пользовательским соглашением и политикой конфиденциальности</label>
				</label>

				<label class='checkbox' for='<?=$dxNsVQKBWkwS0UDojbpZ;?>'>
					<input type="checkbox" class='checkbox-checked' checked id='<?=$dxNsVQKBWkwS0UDojbpZ;?>' style='display: none;'>
					<label class='checkbox-checkbox' style='min-height: initial; padding-left: initial;' for='<?=$dxNsVQKBWkwS0UDojbpZ;?>'>
						<label class='checkbox-checkbox-line icons-checked' style='min-height: initial; padding-left: initial;' for='<?=$dxNsVQKBWkwS0UDojbpZ;?>'></label>
					</label>
					<label class='checkbox-text' style='min-height: initial; padding-left: initial;' for='<?=$dxNsVQKBWkwS0UDojbpZ;?>'>Получать новостную рассылку</label>
				</label>

				<div class='login-btn-login'>
					<input type='button' onclick="register()" class='window-btn' style='z-index: 0; position: relative; width: 100%; padding-top: 6px; padding-bottom: 6px; margin-top: 20px; margin-bottom: 0px;' value='Зарегистрироваться'>
					<div class='login-btn-register' style='margin-bottom: -20px; margin-top: 15px;'>
	          <div>Уже есть аккаунт?</div>
	          <a onclick="windowOpen('#login')">Войти</a>
	        </div>
				</div>

			</div>
			<div class='register-preloader'>
				<div class='login-preloader-elem1'></div>
				<div class='login-preloader-elem2'></div>
				<div class='login-preloader-elem3'></div>
				<div class='login-preloader-elem11'></div>
				<div class='login-preloader-elem22'></div>
				<div class='login-preloader-elem33'></div>
				<div class='login-preloader-elem4'></div>
			</div>
			<div class='register-logo'></div>
    </div>
  </body>
</html>
