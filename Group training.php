<?php

	// === setup =================================================================

	include('php/standartScriptPHP.php');

	// registration referer
	$_SESSION['referer'] = pathinfo(__FILE__)['filename'];

  $pageTitle = 'Расписание';
	$d6BRu4Xw0rEs5YJ2Gpjr = 'd6BRu4Xw0rEs5YJ2Gpjr';

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
		<link rel="stylesheet" href="style/online learning.css">

		<?php include('standartScriptJS.php'); ?>

		<script type="application/javascript" src="js/timetable.js"></script>

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
            <div class='Acquaintance-title-texth4'>Групповое обучение</div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3 col-xl-3">
					<div class='onlinelearning-date'>
 						<div class='onlinelearning-date-elem' id='tt-group-list'>
							<div class='onlinelearning-date-elem-title'>Дни недели</div>
 							<div class='onlinelearning-date-elem-elem1'>
 								<div class='onlinelearning-date-elem-elem-ico' style=''>12</div>
								<div class='onlinelearning-date-elem-elem-text'>
									<div class='onlinelearning-date-elem-elem-text-title'>Понедельник</div>
									<div class='onlinelearning-date-elem-elem-text-date'>12.02.2020</div>
								</div>
							</div>
							<div class='onlinelearning-date-elem-elem2'>
 								<div class='onlinelearning-date-elem-elem-ico' style=''>
									13
									<div class='onlinelearning-date-elem-elem-ico-checked icons-checked' title='Сегодня'></div>
								</div>
								<div class='onlinelearning-date-elem-elem-text'>
									<div class='onlinelearning-date-elem-elem-text-title'>Вторник</div>
									<div class='onlinelearning-date-elem-elem-text-date'>13.02.2020</div>
								</div>
							</div>
							<div class='onlinelearning-date-elem-elem3'>
 								<div class='onlinelearning-date-elem-elem-ico' style=''>14</div>
								<div class='onlinelearning-date-elem-elem-text'>
									<div class='onlinelearning-date-elem-elem-text-title'>Среда</div>
									<div class='onlinelearning-date-elem-elem-text-date'>14.02.2020</div>
								</div>
							</div>
							<div class='onlinelearning-date-elem-elem1'>
 								<div class='onlinelearning-date-elem-elem-ico' style=''>15</div>
								<div class='onlinelearning-date-elem-elem-text'>
									<div class='onlinelearning-date-elem-elem-text-title'>Четверг</div>
									<div class='onlinelearning-date-elem-elem-text-date'>15.02.2020</div>
								</div>
							</div>
							<div class='onlinelearning-date-elem-elem2'>
 								<div class='onlinelearning-date-elem-elem-ico' style=''>16</div>
								<div class='onlinelearning-date-elem-elem-text'>
									<div class='onlinelearning-date-elem-elem-text-title'>Пятница</div>
									<div class='onlinelearning-date-elem-elem-text-date'>16.02.2020</div>
								</div>
							</div>
							<div class='onlinelearning-date-elem-elem3'>
 								<div class='onlinelearning-date-elem-elem-ico' style=''>
									17
 								</div>
								<div class='onlinelearning-date-elem-elem-text'>
									<div class='onlinelearning-date-elem-elem-text-title'>Суббота</div>
									<div class='onlinelearning-date-elem-elem-text-date'>17.02.2020</div>
								</div>
							</div>
							<div class='onlinelearning-date-elem-elem1'>
 								<div class='onlinelearning-date-elem-elem-ico' style=''>18</div>
								<div class='onlinelearning-date-elem-elem-text'>
									<div class='onlinelearning-date-elem-elem-text-title'>Воскресенье</div>
									<div class='onlinelearning-date-elem-elem-text-date'>18.02.2020</div>
								</div>
							</div>
 						</div>
						<div class='onlinelearning-date-elem'>
							<div class='onlinelearning-date-elem-title' style='margin-bottom: 0px;'>Конкретный день</div>
							<div class='onlinelearning-date-elem-elem'>
								<script>
									Timetable.group.field.dateSelect = '<?=$d6BRu4Xw0rEs5YJ2Gpjr;?>';
									$(document).ready(function() {
									  Timetable.group.list();
									  $('#' + Timetable.group.field.dateSelect).on('change', function() {
									    Timetable.group.dateSelect();
									  });
									});
								</script>
				        <label class='input' for="<?=$d6BRu4Xw0rEs5YJ2Gpjr;?>">
				          <div class='input-div'>
				            <label for="<?=$d6BRu4Xw0rEs5YJ2Gpjr;?>" class='input-icons icons-date'></label>
				            <input type="date" class='input-input' value="" required id='<?=$d6BRu4Xw0rEs5YJ2Gpjr;?>'>
				            <label for="<?=$d6BRu4Xw0rEs5YJ2Gpjr;?>" class='input-placeholder'>Дата</label>
				            <label for="<?=$d6BRu4Xw0rEs5YJ2Gpjr;?>" class='input-line'>
				              <label for="<?=$d6BRu4Xw0rEs5YJ2Gpjr;?>" class='input-line-main0'></label>
				              <label for="<?=$d6BRu4Xw0rEs5YJ2Gpjr;?>" class='input-line-main'></label>
				              <label for="<?=$d6BRu4Xw0rEs5YJ2Gpjr;?>" class='input-line-main2'></label>
				            </label>
				          </div>
				        </label>
							</div>
						</div>
 					</div>
        </div>

				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-9 col-xl-9">
 					<div class='onlinelearning-date'>
						<div class='onlinelearning-date-elem' style='overflow-y: auto;'>
							<div class='onlinelearning-date-elem-title' date='01.01.1970' id='tt-group-date'>Расписание на вторник</div>
							<div class='onlinelearning-date-elem-block' id='tt-group-table'>

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
