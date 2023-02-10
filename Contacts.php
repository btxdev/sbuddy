<?php

	// === setup =================================================================

	include('php/standartScriptPHP.php');

	// registration referer
	$_SESSION['referer'] = pathinfo(__FILE__)['filename'];

  $pageTitle = 'Контакты';

	// === location ==============================================================
	$contacts_str = '';
	if(isset($siteData['contacts_postcode']) && !empty($siteData['contacts_postcode'])) {
		$contacts_str .= $siteData['contacts_postcode'];
	}
	if(isset($siteData['contacts_city']) && !empty($siteData['contacts_city'])) {
		if($contacts_str != '') { $contacts_str .= ', '; }
		$contacts_str .= 'г. '.$siteData['contacts_city'];
	}
	if(isset($siteData['contacts_street']) && !empty($siteData['contacts_street'])) {
		if($contacts_str != '') { $contacts_str .= ', '; }
		$contacts_str .= 'ул. '.$siteData['contacts_street'];
	}
	if(isset($siteData['contacts_building']) && !empty($siteData['contacts_building'])) {
		if($contacts_str != '') { $contacts_str .= ', '; }
		$contacts_str .= 'дом '.$siteData['contacts_building'];
	}
	if(isset($siteData['contacts_level']) && !empty($siteData['contacts_level'])) {
		if($contacts_str != '') { $contacts_str .= ', '; }
		$contacts_str .= 'этаж '.$siteData['contacts_level'];
	}
	if(isset($siteData['contacts_office']) && !empty($siteData['contacts_office'])) {
		if($contacts_str != '') { $contacts_str .= ', '; }
		$contacts_str .= 'офис '.$siteData['contacts_office'];
	}
	// === worktime ==============================================================
	$worktime_str = '';
	if(isset($siteData['contacts_wt_start'])) {
		$worktime_str .= 'с '.$siteData['contacts_wt_start'].' ';
	}
	if(isset($siteData['contacts_wt_end'])) {
		$worktime_str .= 'до '.$siteData['contacts_wt_end'];
	}
	// === TIN / COR =============================================================
	$tincor_title = '';
	$tincor_str = '';
	if(isset($siteData['contacts_TIN'])) {
		$tincor_title .= 'ИНН';
		$tincor_str .= $siteData['contacts_TIN'];
	}
	if(isset($siteData['contacts_COR'])) {
		if($tincor_title != '') {
			$tincor_title .= '/';
			$tincor_str .= ' / ';
		}
		$tincor_title .= 'КПП';
		$tincor_str .= $siteData['contacts_COR'];
	}

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

		<link rel="stylesheet" href="style/contacts.css">

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
      <div class='background-main'>
				<div class='container-BigElem-2'></div>
				<div class='container'>
					<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12' style='margin-top: 100px; margin-bottom: 100px;'>
						<h2 class='Acquaintance-h2' style='text-align: center;'><?=$pageTitle;?></h2>
						<div class='Acquaintance-title' style='text-align: center;'>
							<div class='back-plus3 hidden-xs hidden-sm hidden-md'></div>
							<div class='back-triangle2'></div>
							<div class='Acquaintance-title-texth4'>Связаться с нами просто</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
						<div class='contacts-block' id='contacts-block-id'>
							<h4 class='contacts-block-title'>Общая информация</h4>

							<?php if($contacts_str != ''): ?>
								<span>
									<h5 class='contacts-block-title2'>Адрес</h5>
									<div class='contacts-block-text'>
										<div class='contacts-block-text-ico icons-point'></div>
										<?php echo("<div class='contacts-block-text-text'>$contacts_str</div>"); ?>
									</div>
								</span>
							<?php endif; ?>

							<?php if($worktime_str != ''): ?>
								<span>
									<h5 class='contacts-block-title2'>Время работы</h5>
									<div class='contacts-block-text'>
										<div class='contacts-block-text-ico icons-time'></div>
										<div class='contacts-block-text-text'><?= $worktime_str ?></div>
									</div>
								</span>
							<?php endif; ?>

							<?php
								// === phonenumbers ============================================
				        if(isset($siteData['contacts_phonenumbers']) && !empty($siteData['contacts_phonenumbers'])) {
				          $phonenumbers = explode(',', $siteData['contacts_phonenumbers']);
									$phonetitle = 'Телефон';
									if(count($phonenumbers) > 1) $phonetitle = 'Телефоны';
									echo("<span>");
									echo("<h5 class='contacts-block-title2'>$phonetitle</h5>");
									echo("<div class='contacts-block-text'>");
									echo("<div class='contacts-block-text-ico icons-tel'></div>");
									echo("<div class='contacts-block-text-text'>");
				          foreach($phonenumbers as $phone) {
				            $formatted = preg_replace('/([^0-9+])+/u', '', $phone);
				            echo("<a href='tel:$formatted'>".formatPhone($formatted)."</a><br>");
				          }
									echo("</div>");
									echo("</div>");
									echo("</span>");
				        }

								// === emails ============================================
				        if(isset($siteData['contacts_emails']) && !empty($siteData['contacts_emails'])) {
				          $emails = explode(',', $siteData['contacts_emails']);
									echo("<span>");
									echo("<h5 class='contacts-block-title2'>E-mail</h5>");
									echo("<div class='contacts-block-text'>");
									echo("<div class='contacts-block-text-ico icons-email'></div>");
									echo("<div class='contacts-block-text-text'>");
				          foreach($emails as $email) {
										echo("<a href='mailto:$email'>$email</a><br>");
				          }
									echo("</div>");
									echo("</div>");
									echo("</span>");
				        }
							?>

						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
						<div class='contacts-block' style='border-right: 0px dashed #30303663;'>
							<h4 class='contacts-block-title'>Реквизиты</h4>
							<?php if(isset($siteData['contacts_LA'])): ?>
								<span>
									<h5 class='contacts-block-title2'>Юридический адрес</h5>
									<div class='contacts-block-text'>
										<div class='contacts-block-text-ico icons-point'></div>
										<div class='contacts-block-text-text'><?= $siteData['contacts_LA'] ?></div>
									</div>
								</span>
							<?php endif; ?>

							<?php if($tincor_title != ''): ?>
								<span>
									<h5 class='contacts-block-title2'><?= $tincor_title ?></h5>
									<div class='contacts-block-text'>
										<div class='contacts-block-text-ico icons-license'></div>
										<div class='contacts-block-text-text'><?= $tincor_str ?></div>
									</div>
								</span>
							<?php endif; ?>

							<?php if(isset($siteData['contacts_PSRN'])): ?>
								<span>
									<h5 class='contacts-block-title2'>ОГРН</h5>
									<div class='contacts-block-text'>
										<div class='contacts-block-text-ico icons-license'></div>
										<div class='contacts-block-text-text'><?= $siteData['contacts_PSRN'] ?></div>
									</div>
								</span>
						<?php endif; ?>
						</div>
					</div>
				</div>

				<?php if(isset($siteData['contacts_maplink'])): ?>
					<div class="info-map-block">
						<iframe src="<?= $siteData['contacts_maplink'] ?>" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
					</div>
				<?php endif; ?>

				<?php if((isset($siteData['contacts_card'])) && ($siteData['contacts_card'] != '')): ?>
					<div class='container'>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
							<div class='contacts-block' style='margin-top: 25px; margin-bottom: 25px; border-right: 0px dashed #30303663;'>
								<h4 class='contacts-block-title'>Реквизиты</h4>
								<?php $card_path = '../../../../Plugins/admin_panel2.0/DOCS_FILES/'.$siteData['contacts_card']; ?>
								<a href="<?= $card_path ?>" target="_blank">
									<div class='contacts-block-text-text-a' style='margin-left: 0px;'>Скачать карточку <?=$siteData['title']?></div>
								</a>
							</div>
						</div>
					</div>
				<?php endif; ?>

      </div>
    </div>


    <!-- Footer (start) -->
    <?php include('footer.php'); ?>
    <!-- Footer (end) -->


  </body>
</html>
