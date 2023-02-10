<?php

	// === setup =================================================================

	include('php/standartScriptPHP.php');

	// registration referer
	$_SESSION['referer'] = pathinfo(__FILE__)['filename'];

  $pageTitle = 'Отзывы';

	// ===========================================================================

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

		<link rel="stylesheet" href="style/reviews.css">

		<?php include('standartScriptJS.php'); ?>

		<script type="text/javascript" src="js/reviews.js"></script>

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
          </div>
        </div>

				<!-- Крутые отзывы прикрепленные самим заказачиком -->
				<!-- Изначально загружается 2 отзыва или сертификата, если надо больше нажимается кнопка "Загрузить еще" -->
        <div id='sertificates-container'>

					<div class='container'>
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-4 super-review">
							<div class='super-review-block'>
								<img src="media/tmp/original_55ff6426121a87dd6a8b4570_5ad8840c80298.jpg" class='super-review-block-image'></img>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 col-xl-8">
							<div class='super-review-title'>Название сертификата</div>
							<div class='super-review-description'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
							<div class='super-review-date'>
								<span class='super-review-date-text'>Выдано:</span>
								<span class='super-review-date-text2'>Oxford Official University</span>
							</div><br>
							<div class='super-review-date'>
								<span class='super-review-date-text'>Дата:</span>
								<span class='super-review-date-text2'>01.01.2020</span>
							</div><br>
							<div class='super-review-download'>
								<span class='super-review-date-text'>Скачать</span>
							</div>
						</div>
	        </div>

					<div class='container'>
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-4 super-review">
							<div class='super-review-block'>
								<img src="media/tmp/Dlya_chego_nuzhen_mezhdunarodnyy_sertifikat__3.jpg" class='super-review-block-image'></img>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-8 col-lg-8 col-xl-8">
							<div class='super-review-title'>Название сертификата</div>
							<div class='super-review-description'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</div>
							<div class='super-review-date'>
								<span class='super-review-date-text'>Выдано:</span>
								<span class='super-review-date-text2'>Oxford Official University</span>
							</div><br>
							<div class='super-review-date'>
								<span class='super-review-date-text'>Дата:</span>
								<span class='super-review-date-text2'>01.01.2020</span>
							</div><br>
							<div class='super-review-download'>
								<span class='super-review-date-text'>Скачать</span>
							</div>
						</div>
	        </div>

				</div>
				<div>

					<div class='container'>
						<div class='col-xs-0 col-sm-1 col-md-1 col-lg-1 col-xl-1'></div>
						<div class='col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 super-review-more'>
							<input type='button' value='Загрузить еще сертификаты' class='super-review-more-block' id='certificates-more-button' onclick='ReviewsForm.certificates.load();'></input>
						</div>
						<div class='col-xs-0 col-sm-1 col-md-1 col-lg-1 col-xl-1'></div>
					</div>

        </div>


        <div id='reviews-container'>



				</div>
				<div>

					<div class='container'>
						<div class='col-xs-0 col-sm-1 col-md-1 col-lg-1 col-xl-1'></div>
						<div class='col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10 super-review-more'>
							<input type='button' value='Загрузить еще отзывы' class='super-review-more-block' id='reviews-more-button' onclick='ReviewsForm.reviews.load();'></input>
						</div>
						<div class='col-xs-0 col-sm-1 col-md-1 col-lg-1 col-xl-1'></div>
					</div>

					<?php if(isset($userData['account'])): ?>
						<div class='container' style='margin-bottom: 75px;'>
							<div class='col-xs-0 col-sm-1 col-md-3 col-lg-3 col-xl-3'></div>
							<div class='col-xs-12 col-sm-10 col-md-6 col-lg-6 col-xl-6'>
								<div class='user-reviews2'>
									<div class='user-reviews-title'>Оставить отзыв</div>
									<div style='margin-bottom: 10px;'>
										<div class='user-reviews-title2'>Ваша оценка</div>
										<div class='user-reviews-stars'>
											<input type="radio" name='stars' id='dPSsL-gDfd-HHLt-AFUh' style='display: none;'>
											<input type="radio" name='stars' id='dI3Um-mvVN-kjUz-pmO5' style='display: none;'>
											<input type="radio" name='stars' id='d7Dnv-IqPz-msrt-5WkQ' style='display: none;'>
											<input type="radio" name='stars' id='dwWHy-vXKn-MmpN-NwpS' style='display: none;'>
											<input type="radio" name='stars' id='dhtKH-yljk-PUi0-wF86' style='display: none;'>
											<label class='user-reviews-stars-elem icons-star' id='dPSsL-gDfd-HHLt-AFUh-1' title='Ваша оценка: 1' for='dPSsL-gDfd-HHLt-AFUh' onclick='ReviewsForm.reviews.mark.set(1);'></label>
											<label class='user-reviews-stars-elem icons-star' id='dI3Um-mvVN-kjUz-pmO5-2' title='Ваша оценка: 2' for='dI3Um-mvVN-kjUz-pmO5' onclick='ReviewsForm.reviews.mark.set(2);'></label>
											<label class='user-reviews-stars-elem icons-star' id='d7Dnv-IqPz-msrt-5WkQ-3' title='Ваша оценка: 3' for='d7Dnv-IqPz-msrt-5WkQ' onclick='ReviewsForm.reviews.mark.set(3);'></label>
											<label class='user-reviews-stars-elem icons-star' id='dwWHy-vXKn-MmpN-NwpS-4' title='Ваша оценка: 4' for='dwWHy-vXKn-MmpN-NwpS' onclick='ReviewsForm.reviews.mark.set(4);'></label>
											<label class='user-reviews-stars-elem icons-star' id='dhtKH-yljk-PUi0-wF86-5' title='Ваша оценка: 5' for='dhtKH-yljk-PUi0-wF86' onclick='ReviewsForm.reviews.mark.set(5);'></label>
										</div>
									</div>
									<div style='margin-bottom: 10px;'>
										<div class='user-reviews-title2'>Ваш отзыв</div>
										<textarea class='user-reviews-textarea' id='user-reviews-textarea' placeholder="Напишите пару слов..."></textarea>
									</div>
									<div>
										<input type='button' value='Отправить' class='user-reviews-btn' onclick='ReviewsForm.reviews.send();'>
									</div>
								</div>
							</div>
							<div class='col-xs-0 col-sm-1 col-md-3 col-lg-3 col-xl-3'></div>
						</div>
				<?php endif; ?>

        </div>

      </div>
    </div>


    <!-- Footer (start) -->
    <?php include('footer.php'); ?>
    <!-- Footer (end) -->


  </body>
</html>
