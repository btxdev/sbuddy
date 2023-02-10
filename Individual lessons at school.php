<?php

	// === setup =================================================================

	include('php/standartScriptPHP.php');

	// registration referer
	$_SESSION['referer'] = pathinfo(__FILE__)['filename'];

  $pageTitle = 'Название страницы';

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

		<?php include('standartScriptJS.php'); ?>

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
            <div class='Acquaintance-title-texth4'>Заголовок 2 уровня</div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

        </div>

      </div>
    </div>


    <!-- Footer (start) -->
    <?php include('footer.php'); ?>
    <!-- Footer (end) -->


  </body>
</html>
