<?php

	// === setup =================================================================

	include('php/standartScriptPHP.php');

	if(!isset($_SESSION) || !isset($_SESSION['userid'])) {
		header('Location: index.php');
		exit();
	}

	if(!(isset($_COOKIE["sound_msg_site"]))){
    $time = mktime(0,0,0,1,1,2120);
    setcookie('sound_msg_site', 'true', $time);
  }

  $pageTitle = 'Общий чат';

	// ===========================================================================

	$dDRQ6KxpVafu2nPusifS = idGenerator(20,5); // chb1
	$dXYQ2ZVNfAalB8wRuaMe = idGenerator(20,5); // chb2
	$dXYQ2ZVNfAalB8wRsdTr = idGenerator(20,5); // chb3

?>

<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?=$pageTitle;?> :: <?=$siteData['title']?></title>
    <link rel="stylesheet" href="style/bootstrap.min.css" >
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/window.css">
    <link rel="stylesheet" href="style/notification.css">

		<link rel="stylesheet" href="style/chat.css">

		<?php include('standartScriptJS.php'); ?>

		<?php if($userData['account'] != 'poma098'): ?>
			<!-- <script type="text/javascript" src='js/chat.js'></script> -->
		<?php endif; ?>

		<script type="text/javascript" src='js/chat.js'></script>

  </head>
  <body>

		<!-- Window (start) -->
		<?php include('windows.php'); ?>
		<!-- Window (end) -->

		<!-- Nav (start) -->
		<?php include('nav.php'); ?>
		<!-- Nav (end) -->

		<?php
			if(!isset($_COOKIE['cookies_accepted'])) {
				//include('cookies.php');а
				echo(file_get_contents('cookies.html'));
			}
		?>

    <notifications></notifications>


    <div class='container-BigElem'>
      <div class='container-BigElem-2'></div>
      <div class='container background-main'>
        <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12' style='margin-top: 100px; margin-bottom: 100px;'>
          <h2 class='Acquaintance-h2' style='text-align: center;'><?=$pageTitle;?></h2>
          <div class='Acquaintance-title' style='text-align: center;'>
            <div class='back-plus3 hidden-xs hidden-sm hidden-md'></div>
            <div class='back-triangle2'></div>
            <!-- <div class='Acquaintance-title-texth4'>Заголовок 2 уровня</div> -->
          </div>
        </div>
				<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12'>
					<div class="row">
						<div class='chat'>
							<div class='chat-info'>
								<div class='chat-info-container'>
									<div class='chat-info-container-title'>
										<span class='chat-info-container-title-ico'>💬</span>
										<span class='chat-info-container-title-text'>Общий чат</span>
									</div>
									<div class='chat-info-container-exit icons-close' onclick="chatInfo('close')"></div>
									<div class='chat-info-container-main'>
										<div class='chat-info-container-main-title'>Настройки</div>
										<div class='chat-info-container-main-elem'>
											<div class='chat-info-container-main-elem-text'>Звук сообщений</div>
											<div class='chat-info-container-main-elem-ch'>
												<label class='checkbox' for='<?=$dDRQ6KxpVafu2nPusifS;?>'>
													<input type="checkbox" class='checkbox-checked' checked id='<?=$dDRQ6KxpVafu2nPusifS;?>' style='display: none;'>
													<label class='checkbox-checkbox' style='min-height: initial; padding-left: initial;' for='<?=$dDRQ6KxpVafu2nPusifS;?>'>
														<label class='checkbox-checkbox-line icons-checked' style='min-height: initial; padding-left: initial;' for='<?=$dDRQ6KxpVafu2nPusifS;?>'></label>
													</label>
												</label>
											</div>
										</div>
										<div class='chat-info-container-main-elem'>
											<div class='chat-info-container-main-elem-text'>Звук уведомлений</div>
											<div class='chat-info-container-main-elem-ch'>
												<label class='checkbox' for='<?=$dXYQ2ZVNfAalB8wRuaMe;?>'>
													<input type="checkbox" class='checkbox-checked' checked id='<?=$dXYQ2ZVNfAalB8wRuaMe;?>' style='display: none;'>
													<label class='checkbox-checkbox' style='min-height: initial; padding-left: initial;' for='<?=$dXYQ2ZVNfAalB8wRuaMe;?>'>
														<label class='checkbox-checkbox-line icons-checked' style='min-height: initial; padding-left: initial;' for='<?=$dXYQ2ZVNfAalB8wRuaMe;?>'></label>
													</label>
												</label>
											</div>
										</div>
										<div class='chat-info-container-main-elem hidden-sm hidden-md hidden-lg hidden-xl'>
											<div class='chat-info-container-main-elem-text'>Полный экран</div>
											<div class='chat-info-container-main-elem-ch'>
												<label class='checkbox' for='<?=$dXYQ2ZVNfAalB8wRsdTr;?>'>
													<input type="checkbox" class='checkbox-checked' id='<?=$dXYQ2ZVNfAalB8wRsdTr;?>' style='display: none;'>
													<label class='checkbox-checkbox' style='min-height: initial; padding-left: initial;' for='<?=$dXYQ2ZVNfAalB8wRsdTr;?>'>
														<label class='checkbox-checkbox-line icons-checked' style='min-height: initial; padding-left: initial;' for='<?=$dXYQ2ZVNfAalB8wRsdTr;?>'></label>
													</label>
												</label>
												<script>

													var fullBlockChId = '#' + '<?=$dXYQ2ZVNfAalB8wRsdTr?>';

												</script>
											</div>
										</div>
										<div class='chat-info-container-main-title' style='margin-top: 20px;' id='chat-ulist-title' data-peoples='10' title='10 человек'>Участники</div>
										<div id='chat-ulist-container'>
											<div class='chat-info-container-main-elem' style='margin-bottom: 10px;'>
												<div class='chat-info-container-main-elem-photo' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg")'></div>
												<div class='chat-info-container-main-elem-name'>Имя фамилия</div>
											</div>
											<div class='chat-info-container-main-elem' style='margin-bottom: 10px;'>
												<div class='chat-info-container-main-elem-photo' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg")'></div>
												<div class='chat-info-container-main-elem-name'>Имя фамилия</div>
											</div>
											<div class='chat-info-container-main-elem' style='margin-bottom: 10px;'>
												<div class='chat-info-container-main-elem-photo' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg")'></div>
												<div class='chat-info-container-main-elem-name'>Имя фамилия</div>
											</div>
											<div class='chat-info-container-main-elem' style='margin-bottom: 10px;'>
												<div class='chat-info-container-main-elem-photo' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg")'></div>
												<div class='chat-info-container-main-elem-name'>Имя фамилия</div>
											</div>
											<div class='chat-info-container-main-elem' style='margin-bottom: 10px;'>
												<div class='chat-info-container-main-elem-photo' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg")'></div>
												<div class='chat-info-container-main-elem-name'>Имя фамилия</div>
											</div>
											<div class='chat-info-container-main-elem' style='margin-bottom: 10px;'>
												<div class='chat-info-container-main-elem-photo' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg")'></div>
												<div class='chat-info-container-main-elem-name'>Имя фамилия</div>
											</div>
											<div class='chat-info-container-main-elem' style='margin-bottom: 10px;'>
												<div class='chat-info-container-main-elem-photo' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg")'></div>
												<div class='chat-info-container-main-elem-name'>Имя фамилия</div>
											</div>
											<div class='chat-info-container-main-elem' style='margin-bottom: 10px;'>
												<div class='chat-info-container-main-elem-photo' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg")'></div>
												<div class='chat-info-container-main-elem-name'>Имя фамилия</div>
											</div>
											<div class='chat-info-container-main-elem' style='margin-bottom: 10px;'>
												<div class='chat-info-container-main-elem-photo' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg")'></div>
												<div class='chat-info-container-main-elem-name'>Имя фамилия</div>
											</div>
											<div class='chat-info-container-main-elem' style='margin-bottom: 10px;'>
												<div class='chat-info-container-main-elem-photo' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg")'></div>
												<div class='chat-info-container-main-elem-name'>Имя фамилия</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class='chat-main'>
								<div class='chat-main-msg'>
									<div class='chat-main-msg-msgs'>
										<div class='chat-main-msg-msgs-title'>
											<div class='chat-main-msg-msgs-title-text' id='chat-title-count' data-peoples='10 человек' title='10 человек'>Общий чат</div>
											<div class='chat-main-msg-msgs-title-info icons-info' onclick="chatInfo()"></div>
										</div>
										<div class='chat-main-msg-msgs-text'>
											<div class='chat-main-msg-msgs-text-main-hello'>
												<div class='chat-main-msg-msgs-text-main-hello-img'>
													<div class='chat-main-msg-msgs-text-main-hello-img-ico'></div>
													<div class='chat-main-msg-msgs-text-main-hello-img-text'>Начните диалог первым, напишите: "Всем привет!"</div>
												</div>
											</div>
											<div class='chat-main-msg-msgs-text-main-preloader'>
												<div class='chat-main-msg-msgs-text-main-preloader-ico'>
													<div class="loader" style="">
									          <svg class="circular" viewBox="25 25 50 50">
									            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="5" stroke-miterlimit="10"></circle>
									          </svg>
									        </div>
												</div>
												<div class='chat-main-msg-msgs-text-main-preloader-text'>Загрузка...</div>
											</div>
											<div class='chat-main-msg-msgs-text-main' id='chat-msg-container'>



												 <!--<div class='chat-main-msg-msgs-text-main-delimiter' data-delimiter='2 мая 2020г.'></div>

												<div class='chat-main-msg-msgs-text-main-elem'>
													<div class='chat-main-msg-msgs-text-main-elem-photo' title='login' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg");'></div>
													<div class='chat-main-msg-msgs-text-main-elem-msg'>
														<div class='chat-main-msg-msgs-text-main-elem-msg-name'>Имя фамилия</div>
														<div class='chat-main-msg-msgs-text-main-elem-msg-text'>Lorem ipsum dolor sit amet, 🧡😀🤣😂 consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</div>
													</div>
													<div class='chat-main-msg-msgs-text-main-elem-info'>
														<div class='chat-main-msg-msgs-text-main-elem-info-ico icons-error-bolt' style='color: var(--red); cursor: pointer;' onclick="notification_add('error','Ошибка в сообщении','Текст ошибки', 30)"></div>
														<div class='chat-main-msg-msgs-text-main-elem-info-date' title='12:05:51'>12:05</div>
													</div>
												</div>

												<div class='chat-main-msg-msgs-text-main-elem'>
													<div class='chat-main-msg-msgs-text-main-elem-photo' title='login' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg");'></div>
													<div class='chat-main-msg-msgs-text-main-elem-msg'>
														<div class='chat-main-msg-msgs-text-main-elem-msg-name1' data-rights='&#128081;' style='color: #da9700' title='Администратор'>Имя фамилия</div>
														<div class='chat-main-msg-msgs-text-main-elem-msg-text'>Привет всем! Я администратор и пушу из админки!</div>
													</div>
													<div class='chat-main-msg-msgs-text-main-elem-info'>
														<div class='chat-main-msg-msgs-text-main-elem-info-ico icons-error-bolt' style='color: var(--red); cursor: pointer;' onclick="notification_add('error','Ошибка в сообщении','Текст ошибки', 30)"></div>
														<div class='chat-main-msg-msgs-text-main-elem-info-date' title='12:05:51'>12:05</div>
													</div>
												</div>

												<div class='chat-main-msg-msgs-text-main-elem'>
													<div class='chat-main-msg-msgs-text-main-elem-photo' title='login' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg");'></div>
													<div class='chat-main-msg-msgs-text-main-elem-msg'>
														<div class='chat-main-msg-msgs-text-main-elem-msg-name1' data-rights='🥋' style='color: #2a9fd0' title='Модератор'>Имя фамилия</div>
														<div class='chat-main-msg-msgs-text-main-elem-msg-text'>Привет всем! Я администратор и пушу из админки!</div>
													</div>
													<div class='chat-main-msg-msgs-text-main-elem-info'>
														<div class='chat-main-msg-msgs-text-main-elem-info-ico icons-error-bolt' style='color: var(--red); cursor: pointer;' onclick="notification_add('error','Ошибка в сообщении','Текст ошибки', 30)"></div>
														<div class='chat-main-msg-msgs-text-main-elem-info-date' title='12:05:51'>12:05</div>
													</div>
												</div>

												<div class='chat-main-msg-msgs-text-main-elem'>
													<div class='chat-main-msg-msgs-text-main-elem-msg2'>
														<div class='chat-main-msg-msgs-text-main-elem-msg-text'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</div>
													</div>
													<div class='chat-main-msg-msgs-text-main-elem-info'>
														<div class='chat-main-msg-msgs-text-main-elem-info-ico icons-warning-bolt' style='color: var(--orange); cursor: pointer;' onclick="notification_add('warning','Предупреждение','Текст ошибки', 30)"></div>
														<div class='chat-main-msg-msgs-text-main-elem-info-date' title='12:05:51'>12:05</div>
													</div>
												</div>

												<div class='chat-main-msg-msgs-text-main-elem'>
													<div class='chat-main-msg-msgs-text-main-elem-msg2'>
														<div class='chat-main-msg-msgs-text-main-elem-msg-text'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</div>
													</div>
													<div class='chat-main-msg-msgs-text-main-elem-info'>
														<div class='chat-main-msg-msgs-text-main-elem-info-ico icons-good' title='Отправлено' style='color: var(--green);'></div>
														<div class='chat-main-msg-msgs-text-main-elem-info-date' title='12:05:51'>12:05</div>
													</div>
												</div>

												<div class='chat-main-msg-msgs-text-main-elem'>
													<div class='chat-main-msg-msgs-text-main-elem-msg2'>
														<div class='chat-main-msg-msgs-text-main-elem-msg-text'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</div>
													</div>
													<div class='chat-main-msg-msgs-text-main-elem-info'>
														<div class='chat-main-msg-msgs-text-main-elem-info-ico icons-good-bolt' title='Прочитано' style='color: var(--green);'></div>
														<div class='chat-main-msg-msgs-text-main-elem-info-date' title='12:05:51'>12:05</div>
													</div>
												</div>

												<div class='chat-main-msg-msgs-text-main-elem'>
													<div class='chat-main-msg-msgs-text-main-elem-msg2'>
														<div class='chat-main-msg-msgs-text-main-elem-msg-text'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</div>
													</div>
													<div class='chat-main-msg-msgs-text-main-elem-info'>
														<div class='chat-main-msg-msgs-text-main-elem-info-ico icons-time-bolt' style='color: var(--yellow); cursor: pointer;' onclick="notification_add('info','Информация','Операция выполняется')"></div>
														<div class='chat-main-msg-msgs-text-main-elem-info-date' title='12:05:51'>12:05</div>
													</div>
												</div>

												<div class='chat-main-msg-msgs-text-main-delimiter' data-delimiter='5 мая 2020г.'></div>

												<div class='chat-main-msg-msgs-text-main-elemI'>
													<div class='chat-main-msg-msgs-text-main-elemI-msg'>
														<div class='chat-main-msg-msgs-text-main-elem-msg-text'>Lorem ipsum dolor sit amet, 🧡😀🤣😂 consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</div>
													</div>
													<div class='chat-main-msg-msgs-text-main-elemI-photo' title='login' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg");'></div>
													<div class='chat-main-msg-msgs-text-main-elemI-info'>
														<div class='chat-main-msg-msgs-text-main-elem-info-ico icons-good-bolt' title='Прочитано' style='color: var(--green);'></div>
														<div class='chat-main-msg-msgs-text-main-elem-info-date' title='12:05:51'>12:05</div>
													</div>
												</div>

												<div class='chat-main-msg-msgs-text-main-elemI'>
													<div class='chat-main-msg-msgs-text-main-elemI-msg2'>
														<div class='chat-main-msg-msgs-text-main-elem-msg-text'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</div>
														<div class='chat-main-msg-msgs-text-main-elem-msg-file'>
															<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem' title='Нажмите для открытия'>
																<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem-ico icons-files'></div>
																<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem-name' title='name.pdf'>name.pdf</div>
															</div>
														</div>
													</div>
													<div class='chat-main-msg-msgs-text-main-elemI-info'>
														<div class='chat-main-msg-msgs-text-main-elem-info-ico icons-good-bolt' title='Прочитано' style='color: var(--green);'></div>
														<div class='chat-main-msg-msgs-text-main-elem-info-date' title='12:05:51'>12:05</div>
													</div>
												</div>

												<div class='chat-main-msg-msgs-text-main-delimiter' data-delimiter='Сегодня'></div>

												<div class='chat-main-msg-msgs-text-main-elem'>
													<div class='chat-main-msg-msgs-text-main-elem-photo' title='login' style='background-image: url("https://st.depositphotos.com/1008939/1880/i/450/depositphotos_18807295-stock-photo-portrait-of-handsome-man.jpg");'></div>
													<div class='chat-main-msg-msgs-text-main-elem-msg'>
														<div class='chat-main-msg-msgs-text-main-elem-msg-name'>Имя фамилия</div>
														<div class='chat-main-msg-msgs-text-main-elem-msg-text'>Lorem ipsum dolor sit amet, 🧡😀🤣😂 consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</div>
														<div class='chat-main-msg-msgs-text-main-elem-msg-file'>
															<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem' title='Нажмите для открытия'>
																<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem-ico icons-files'></div>
																<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem-name' title='name.pdf'>name.pdf</div>
															</div>
															<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem' title='Нажмите для открытия'>
																<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem-ico icons-files'></div>
																<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem-name' title='name.pdf'>name.pdf</div>
															</div>
															<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem' title='Нажмите для открытия'>
																<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem-ico icons-files'></div>
																<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem-name' title='name.pdf'>name.pdf</div>
															</div>
															<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem' title='Нажмите для открытия'>
																<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem-ico icons-files'></div>
																<div class='chat-main-msg-msgs-text-main-elem-msg-file-elem-name' title='name.pdf'>name.pdf</div>
															</div>
														</div>
													</div>
													<div class='chat-main-msg-msgs-text-main-elem-info'>
														<div class='chat-main-msg-msgs-text-main-elem-info-ico icons-good-bolt' title='Прочитано' style='color: var(--green);'></div>
														<div class='chat-main-msg-msgs-text-main-elem-info-date' title='12:05:51'>12:05</div>
													</div>
												</div>-->


											</div>
										</div>
									</div>
									<div class='chat-main-msg-text'>
										<script type="text/javascript">
											/* если нет файлов то блок .chat-main-msg-text имеет высоту 74px,
											 	 а блок .chat-main-msg-msgs высоту calc(100% - 75px)
											   давай ограничим до 4 файлов за 1 сообщение

												 Информация о девайсе в переменной device: (phone|tablet|pc)

											   Если чел прикрепляет с пк то блоки будут такими
												 1-2 файла: .chat-main-msg-msgs -> height: calc(100% - 127px);

												 2-4 файла: .chat-main-msg-msgs -> height: calc(100% - 184px);

												 Если Телефон, то такие блоки

												 1 файл: .chat-main-msg-msgs -> height: calc(100% - 129px);
												 2 файла: .chat-main-msg-msgs -> height: calc(100% - 184px);
												 3 файла: .chat-main-msg-msgs -> height: calc(100% - 240px);
												 4 файла: .chat-main-msg-msgs -> height: calc(100% - 295px);

											*/

											$('.chat-main-msg-msgs').css({
												'height':'calc(100% - 184px)'
											})
										</script>
										<div class='chat-main-msg-text-main'>
											<div class='chat-main-msg-text-file' id='attachments-container'>
												<div class='chat-main-msg-text-file-elem' title='Название файла.pdf'>
													<div class='chat-main-msg-text-file-elem-del icons-plus' title='Удалить'></div>
													<div class='chat-main-msg-text-file-elem-ico icons-files'></div>
													<div class='chat-main-msg-text-file-elem-name'>loaderRegister.pdf</div>
												</div>
												<div class='chat-main-msg-text-file-elem' title='Название файла.pdf'>
													<div class='chat-main-msg-text-file-elem-del icons-plus' title='Удалить'></div>
													<div class='chat-main-msg-text-file-elem-ico icons-files'></div>
													<div class='chat-main-msg-text-file-elem-name'>loaderRegister.pdf</div>
												</div>
												<div class='chat-main-msg-text-file-elem' title='Название файла.pdf'>
													<div class='chat-main-msg-text-file-elem-del icons-plus' title='Удалить'></div>
													<div class='chat-main-msg-text-file-elem-ico icons-files'></div>
													<div class='chat-main-msg-text-file-elem-name'>loaderRegister.pdf</div>
												</div>
												<div class='chat-main-msg-text-file-elem' title='Название файла.pdf'>
													<div class='chat-main-msg-text-file-elem-del icons-plus' title='Удалить'></div>
													<div class='chat-main-msg-text-file-elem-ico icons-files'></div>
													<div class='chat-main-msg-text-file-elem-name'>loaderRegister.pdf</div>
												</div>
											</div>
											<div class='emoji-block'>
												<div class='emoji-block-title'>Emoji</div>
												<div class='emoji-block-group'>
													<div class='emoji-block-group-title'>Эмоции</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😀</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😁</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😂</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤣</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😃</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😄</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😅</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😆</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😉</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😊</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😋</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😎</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😍</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😘</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😗</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😙</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😚</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🙂</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤗</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤩</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤔</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤨</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😐</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😑</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😶</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🙄</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😏</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😣</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😥</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😮</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤐</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😯</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😪</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😫</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😴</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😌</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😛</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😜</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😝</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤤</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😒</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😓</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😔</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😕</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🙃</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤑</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😲</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🙁</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😖</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😞</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😤</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😢</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😭</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😨</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤯</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😬</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😩</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😰</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😱</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😳</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤪</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😵</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😠</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😡</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤬</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😷</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤒</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤕</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤢</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤮</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">😇</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤠</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤡</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤥</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤫</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤭</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🧐</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤓</div>
												</div>
												<div class='emoji-block-group'>
													<div class='emoji-block-group-title'>Люди</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👩🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👨🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🧑🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👧🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👦🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👶🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👵🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👴🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🧓🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👨🏼🦰🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👨🏼🦱🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👩🏼🦲🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👩🏼🦳🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👱🏼♀️🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👱🏼♂️🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👸🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤴🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👳🏼‍♀️</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👳🏼‍♂️</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👲🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🧔🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👼🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤶🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🎅🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👮🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👮🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🕵🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🕵🏼‍♂️</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">💂🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">💂🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👷🏼‍♀️</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👷🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👩🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👨🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👩🏼🎓</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👨🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👩🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👨🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤵🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👩🏼🚀</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👨🏼‍🚀</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👩🏼‍🚒</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👨🏼‍🚒</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🧛🏼‍♀️</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🧛🏼‍♂️</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🧜🏼‍♀️</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🧜🏼‍♂️</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🚴🏼‍♂️</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🧙🏼‍♂️</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">💪🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👂🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👃🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👈🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👉🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">☝🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👆🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👇🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">✌🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤞🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🖖🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🖖🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤘🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤙🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👍🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">👎🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🖐🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🙏🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤝🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">💅🏼</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">✍🏼</div>
												</div>
												<div class='emoji-block-group'>
													<div class='emoji-block-group-title'>Сердечки</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🧡</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">💛</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">💚</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">💙</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">💜</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤎</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🖤</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">🤍</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">💔</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">💕</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">💗</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">💖</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">💘</div>
													<div class='emoji-block-group-elem' onclick="addEmoji(this);">💝</div>
												</div>
											</div>
											<input type='file' style='display: none;' id='attachments-input' multiple />
											<label for='attachments-input' class='chat-main-msg-text-main-file icons-file'></label>
											<div class='chat-main-msg-text-main-text'>
												<textarea placeholder="Ваше сообщение" id='chat-textarea'></textarea>
												<div class='chat-main-msg-text-main-text-emoji' onclick="openEmojiBlock()" title='emoji'></div>
											</div>
											<div class='chat-main-msg-text-main-btn icons-send' title='Отправить' onclick="Chat.form.send();"></div>
										</div>
									</div>
								</div>
							</div>
						</div>
	        </div>
				</div>


      </div>
    </div>


    <!-- Footer (start) -->
    <?php include('footer.php'); ?>
    <!-- Footer (end) -->


  </body>
</html>
