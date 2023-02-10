<?php

	// === setup =================================================================

	include('php/standartScriptPHP.php');

	if(!isset($_SESSION['login'])) {
		header('Location: index');
		exit();
	}

  $pageTitle = 'Хранилище';

	// ===========================================================================

	$dDRQ6KxpVafu2nPusifS = idGenerator(20,5); // chb1
	$dXYQ2ZVNfAalB8wRuaMe = idGenerator(20,5); // chb2

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

		<link rel="stylesheet" href="style/drive.css">

		<?php include('standartScriptJS.php'); ?>

		<!-- <script type="text/javascript" src='js/chat.js'></script> -->
		<script type="text/javascript" src='js/finder.js'></script>
		<script type="text/javascript" src='js/draganddrop.js'></script>

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
						<div class='drive'>
							<div class='drive-draganddrop'>
								<div class='drive-draganddrop-ab'></div>
								<div class='drive-draganddrop-text'>
									<div class='drive-draganddrop-text-ico icons-upload'></div>
									<div class='drive-draganddrop-text-elem'><?=$userData['name1']?>, перетащите файлы сюда</div>
								</div>
							</div>
							<div class='drive-info'>
								<div class="drive-info-container">
									<div class="drive-info-container-title">
										<span class="drive-info-container-title-ico">📁</span>
										<span class="drive-info-container-title-text"><?=$pageTitle;?></span>
									</div>
									<div class='drive-info-container-btn2' id='drive-menu-prev' onclick='Finder.history.prev();'>
										<div class='drive-info-container-btn2-ico icons-left' style='color: #303036;'></div>
										<div class='drive-info-container-btn-text'>Назад</div>
									</div>
									<div class='drive-info-container-btn3' onclick='Finder.listing();'>
										<div class='drive-info-container-btn3-ico icons-reload'></div>
										<div class='drive-info-container-btn-text'>Обновить</div>
									</div>
									<div class='drive-info-container-btn1' onclick='Finder.createCatalog();'>
										<div class='drive-info-container-btn1-ico icons-folderNew'></div>
										<div class='drive-info-container-btn-text'>Создать папку</div>
									</div>
									<input type='file' id='drive-menu-upload-input' style='display: none;' multiple />
									<label for='drive-menu-upload-input' class='drive-info-container-btn2'>
										<div class='drive-info-container-btn2-ico icons-uploadFile' style='color: #303036;'></div>
										<div class='drive-info-container-btn-text'>Загрузить файлы</div>
									</label>
									<input type='file' id='drive-menu-upload-folder' style='display: none;' webkitdirectory multiple />
									<label for='drive-menu-upload-folder' class='drive-info-container-btn3'>
										<div class='drive-info-container-btn3-ico icons-uploadFolder'></div>
										<div class='drive-info-container-btn-text'>Загрузить папку</div>
									</label>
									<div class='drive-info-container-btn1 drive-info-container-btn-none' onclick='Finder.rename.window();' id='drive-menu-rename'>
										<div class='drive-info-container-btn1-ico icons-rename'></div>
										<div class='drive-info-container-btn-text'>Переименовать</div>
									</div>
									<div class='drive-info-container-btn2' onclick='Finder.copycut.selected("cut", true);'>
										<div class='drive-info-container-btn2-ico icons-cut' style='color: #303036;'></div>
										<div class='drive-info-container-btn-text'>Вырезать</div>
									</div>
									<div class='drive-info-container-btn3' onclick='Finder.copycut.pasteTo();'>
										<div class='drive-info-container-btn3-ico icons-past'></div>
										<div class='drive-info-container-btn-text'>Вставить</div>
									</div>
									<div class='drive-info-container-btn1' onclick='Finder.elements.selectAll();'>
										<div class='drive-info-container-btn1-ico icons-selectAll'></div>
										<div class='drive-info-container-btn-text'>Выделить все</div>
									</div>
									<div class='drive-info-container-btn2 drive-info-container-btn-none' id='drive-menu-remove' onclick='Finder.remove.selected();'>
										<div class='drive-info-container-btn2-ico icons-remove' style='color: #303036;'></div>
										<div class='drive-info-container-btn-text'>Удалить</div>
									</div>
									<div class='drive-info-container-btn3 drive-info-container-btn-none' id='drive-menu-download' onclick='Finder.download.selected();'>
										<div class='drive-info-container-btn3-ico icons-download'></div>
										<div class='drive-info-container-btn-text'>Скачать</div>
									</div>
									<div class='drive-info-container-size'>
										<div class='drive-info-container-size-title'>Пространство</div>
										<div class='drive-info-container-size-text-2' id='drive-memory-percent'>
											Занято 50%
										</div>
										<div class='drive-info-container-size-line'>
											<div class='drive-info-container-size-line-p' style='width: 50%;' id='drive-memory-bar'></div>
										</div>

										<div class='drive-info-container-size-text'>
											<div class='drive-info-container-size-text1' id='drive-memory-used'>250 Мб</div>
											<div class='drive-info-container-size-text2' id='drive-memory-max'>500 Мб</div>
										</div>
									</div>
								</div>
							</div>
							<div class='drive-main'>
								<div class='drive-main-header'>
									<div class='drive-main-header-ico' id='drive-files-icon' style='background-image: url("media/svg/file/folderColor.svg")'></div>
									<div class='drive-main-header-text' id='drive-files-title' title='Объем папки: 100 Мб' data-size='100 Мб'>Название папки</div>
								</div>
								<div class='drive-main-main'>
									<div class='drive-main-main-sort'>
										<div class='drive-main-main-sort-elem' style='width: calc(37% + 10px); margin-left: 79px;'>Имя</div>
										<div class='drive-main-main-sort-elem' style='width: calc(20% + 20px); text-align: right;'>Даты</div>
										<div class='drive-main-main-sort-elem' style='width: calc(20% + 10px); text-align: right;'>Размер</div>
									</div>
									<div id='drive-files-container'>
										<div class='drive-main-main-elem'>
											<div class='drive-main-main-elem-ch'>
												<label class="checkbox" style='margin-top: 0px; margin-bottom: 0px; margin-left: 2px;' for="ttr-red-y43">
								  				<input type="checkbox" class="checkbox-checked" id="ttr-red-y43" style="display: none;">
								  				<label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="ttr-red-y43">
								  					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="ttr-red-y43"></label>
								  				</label>
								  			</label>
											</div>
											<div class='drive-main-main-elem-ico' style='background-image: url("media/svg/file/folderColor.svg")'></div>
											<div class='drive-main-main-elem-name'>Lorem ipsum dolor sit amet</div>
											<div class='drive-main-main-elem-date'>01.10.2020</div>
											<div class='drive-main-main-elem-size'>13 Мб</div>
										</div>
										<div class='drive-main-main-elem'>
											<div class='drive-main-main-elem-ch'>
												<label class="checkbox" style='margin-top: 0px; margin-bottom: 0px; margin-left: 2px;' for="ttr-red-y65">
								  				<input type="checkbox" class="checkbox-checked" id="ttr-red-y65" style="display: none;">
								  				<label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="ttr-red-y65">
								  					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="ttr-red-y65"></label>
								  				</label>
								  			</label>
											</div>
											<div class='drive-main-main-elem-ico' style='background-image: url("media/svg/file/folderColor.svg")'></div>
											<div class='drive-main-main-elem-name'>Lorem ipsum dolor sit amet</div>
											<div class='drive-main-main-elem-date'>01.10.2020</div>
											<div class='drive-main-main-elem-size'>13 Мб</div>
										</div>
										<div class='drive-main-main-elem'>
											<div class='drive-main-main-elem-ch'>
												<label class="checkbox" style='margin-top: 0px; margin-bottom: 0px; margin-left: 2px;' for="ttr-red-e43">
								  				<input type="checkbox" class="checkbox-checked" id="ttr-red-e43" style="display: none;">
								  				<label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="ttr-red-e43">
								  					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="ttr-red-e43"></label>
								  				</label>
								  			</label>
											</div>
											<div class='drive-main-main-elem-ico' style='background-image: url("media/svg/file/folderColor.svg")'></div>
											<div class='drive-main-main-elem-name'>Lorem ipsum dolor sit amet</div>
											<div class='drive-main-main-elem-date'>01.10.2020</div>
											<div class='drive-main-main-elem-size'>13 Мб</div>
										</div>
										<div class='drive-main-main-elem'>
											<div class='drive-main-main-elem-ch'>
												<label class="checkbox" style='margin-top: 0px; margin-bottom: 0px; margin-left: 2px;' for="ttr-red-h43">
								  				<input type="checkbox" class="checkbox-checked" id="ttr-red-h43" style="display: none;">
								  				<label class="checkbox-checkbox" style="min-height: initial; padding-left: initial;" for="ttr-red-h43">
								  					<label class="checkbox-checkbox-line icons-checked" style="min-height: initial; padding-left: initial;" for="ttr-red-h43"></label>
								  				</label>
								  			</label>
											</div>
											<div class='drive-main-main-elem-ico' style='background-image: url("media/svg/file/7z.svg")'></div>
											<div class='drive-main-main-elem-name'>Lorem ipsum dolor sit amet</div>
											<div class='drive-main-main-elem-date'>01.10.2020</div>
											<div class='drive-main-main-elem-size'>13 Мб</div>
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
