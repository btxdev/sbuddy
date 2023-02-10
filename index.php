<!--
 *
 *  Study Buddy
 *  (c) 2020 Study Buddy
 *  All rights reserved.
 *
 *  Developed by INSOweb
 *  <http://insoweb.ru>
 *
 *
-->

<?php

	include('php/standartScriptPHP.php');

	// registration referer
	$_SESSION['referer'] = pathinfo(__FILE__)['filename'];

	$use_db_slider = false;
	$path_to_slider_photos = './admin/DOCS_FILES/Фотографии школы/';

?>



<!DOCTYPE html>
<html lang="ru" dir="ltr">

<head>
  <meta charset="utf-8">
  <title><?=$siteData['title']?></title>
  <link rel="stylesheet" href="style/bootstrap.min.css">
  <link rel="stylesheet" href="style/main.css">
  <link rel="stylesheet" href="style/index.css">
  <link rel="stylesheet" href="style/footer.css">
  <link rel="stylesheet" href="style/nav.css">
  <link rel="stylesheet" href="style/window.css">
  <link rel="stylesheet" href="style/notification.css">

  <?php include('standartScriptJS.php'); ?>

  <script type="text/javascript" src="js/index.js"></script>
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

  <div class='scrollElem'>
    <div class='scrollElem-mouse'>
      <div class='scrollElem-mouse-stik'></div>
    </div>
  </div>

  <div class='container background-main'>
    <!-- Small space -->
    <div id='smallSpace' class='row hidden-xs hidden-sm hidden-md'>
      <span style=''></span>
    </div>

    <!-- Welcome -->
    <div class='row' style='margin-top: 50px; min-height: calc(100vh - 75px - 300px);'>
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6'>
        <div class='row'>
          <div class='back'>
            <div class='back1'></div>
            <div class='back2'></div>
            <div class='back3'>С каждым новым языком вы проживаете новую жизнь. Если вы знаете только один язык, вы
              живете всего один раз</div>
          </div>
        </div>
      </div>
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6'>
        <div class='row' id='main-title-1'>
          <div class='back-circle hidden-xs hidden-sm'></div>
          <div class='back-circle-line'></div>
          <p class='back-text'>  </p>
          <h1 class='back-h1'>
            Учись
            <span style='font-family: pb;'>></span>
            Ошибайся
            <span style='font-family: pb;'>></span>
            Пробуй
            <span style='font-family: pb;'>></span>
            Экспериментируй
            <span style='font-family: pb;'>></span>
            <span class='back-h1-hover'>побеждай</span>
          </h1>
          <input type='button' value='Пройти тест' onclick="windowOpen('#control-test');" class='btn2-main'><br>
          <div class='back-plus'></div>
          <div class='back-plus1 hidden-xs hidden-sm'></div>
        </div>
      </div>
    </div>

    <!-- Teamworks -->
    <div class='row' style='margin-top: 150px;'>
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12'>
        <div style='height: 100%;' class='col-xs-1 col-sm-1 col-md-1 col-lg-1 col-xl-1'>
          <div class='Acquaintance-svg hidden-xs hidden-sm' id='Acquaintance-svg1'></div>
        </div>
        <div class='col-xs-11 col-sm-11 col-md-11 col-lg-11 col-xl-11'>
          <h4 class='Acquaintance-h4' id='Acquaintance-h41'>Знакомство</h4>
          <div class='Acquaintance-title'>
            <div class='back-triangle'></div>
            <div class='Acquaintance-title-text' id='Acquaintance-title-text1'>У нас творческая команда,<br>и большой
              опыт</div>
          </div>
        </div>
      </div>
      <div id='news_parallax1'>
        <script>
        var empBlocks = [];

        function empBlocksShow(i) {
          if (typeof(i) == 'undefined') i = 0;
          if (i == 0) $('#emp-more-btn').fadeOut(50);
          if (i >= empBlocks.length) {
            return;
          } else {
            let t = (i + 1) * 100 + 30;
            $('#id-emp-block1-' + empBlocks[i]).fadeIn(t);
            $('#id-emp-block2-' + empBlocks[i]).fadeIn(t + 10);
            $('#id-emp-block3-' + empBlocks[i]).fadeIn(t + 20);
            setTimeout(empBlocksShow, 50, ++i);
          }
        }
        </script>
        <?php
						$moreButton = false;
						try {
							$stmt = $pdo_site->prepare("SELECT * FROM `our_team`");
							$stmt->execute();
							$arr_c = 0;
							$row_c = 0;
							$col_c = 0;
							$cols = 2;
							$rows = 2;
							$opened = false;
							while($elem = $stmt->fetch(PDO::FETCH_LAZY)) {
								// is new row? -> draw row
								// open div
								if($col_c == 0) {
									if($row_c >= $rows) {
										echo("<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12' id='id-emp-block1-$arr_c' style='display: none;'>\n");
									}
									else {
										echo("<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12'>\n");
									}
									$opened = true;
								}
								// check icon
								$icon = "media/employee/icon-std-id{$elem['id']}.jpg";
								if(!file_exists($icon)) $icon = "media/svg/male_avatar.svg";
								// draw column
								if($row_c >= $rows) {
									echo("<script>empBlocks[empBlocks.length] = $arr_c;</script>");
									echo("<div class='col-xs-12 col-sm-12 col-md-1 col-lg-1 col-xl-1' id='id-emp-block2-$arr_c' style='display: none;'></div>\n");
									echo("<div class='col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5' id='id-emp-block3-$arr_c' style='display: none;'>\n");
								}
								else {
									echo("<div class='col-xs-12 col-sm-12 col-md-1 col-lg-1 col-xl-1'></div>\n");
									echo("<div class='col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5'>\n");
								}
								echo("<div class='Acquaintance-elem'>\n");
								echo("<div class='Acquaintance-elem-photo' style='background-image: url(\"$icon\");'></div>\n");
								echo("<div class='Acquaintance-elem-text'>\n");
								echo("<div class='Acquaintance-elem-text-name'>{$elem['name1']} {$elem['name2']}</div>\n");
								echo("<div class='Acquaintance-elem-text-description'>{$elem['description']}</div>\n");
								echo("<div class='Acquaintance-elem-text-social'>\n");
								if(!empty($elem['link_vk'])) echo("<a target='_blank' href='{$elem['link_vk']}' class='Acquaintance-elem-text-social-vk icons-vk' title='Вконтакте'></a>\n");
								if(!empty($elem['link_fb'])) echo("<a target='_blank' href='{$elem['link_fb']}' class='Acquaintance-elem-text-social-vk icons-fb' title='Facebook'></a>\n");
								if(!empty($elem['link_tw'])) echo("<a target='_blank' href='{$elem['link_tw']}' class='Acquaintance-elem-text-social-vk icons-tw' title='Twitter'></a>\n");
								if(!empty($elem['link_inst'])) echo("<a target='_blank' href='{$elem['link_inst']}' class='Acquaintance-elem-text-social-vk icons-instagram' title='Instagram'></a>\n");
								if(!empty($elem['link_wa'])) echo("<a target='_blank' href='{$elem['link_wa']}' class='Acquaintance-elem-text-social-vk icons-whatsapp' title='Whatsapp'></a>\n");
								echo("</div>\n");
								echo("</div>\n");
								echo("</div>\n");
								echo("</div>\n");
								// is new row? -> draw row
								// close div
								if($col_c == 1) {
									echo("</div>\n");
									$opened = false;
								}
								// update col counter
								$col_c++;
								// update row counter
								if($col_c >= $cols) {
									$row_c++;
									$col_c = 0;
								}
								// show 'more' btn
								if($row_c >= $rows) {
									$moreButton = true;
								}
								// emp blocks id
								$arr_c++;
							}
							// close div
							if($opened) {
								echo("</div>\n");
								$opened = false;
							}
						}
						catch(Exception $e) {
							debuglog('critical error '.__FILE__.' :: '.__LINE__);
              debuglog($e);
						}
					?>
        <!--<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12'>
            <div class='col-xs-12 col-sm-12 col-md-1 col-lg-1 col-xl-1'></div>
            <div class='col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5'>
              <div class='Acquaintance-elem'>
                <div class='Acquaintance-elem-photo'></div>
                <div class='Acquaintance-elem-text'>
                  <div class='Acquaintance-elem-text-name'>Василий Новиков</div>
                  <div class='Acquaintance-elem-text-description'>Преподаватель английского языка</div>
                  <div class='Acquaintance-elem-text-social'>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-vk' title='Вконтакте'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-fb' title='Facebook'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-tw' title='Twitter'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-instagram' title='Instagram'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-whatsapp' title='Whatsapp'></a>
                  </div>
                </div>
              </div>
            </div>
            <div class='col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5'>
              <div class='Acquaintance-elem'>
                <div class='Acquaintance-elem-photo'></div>
                <div class='Acquaintance-elem-text'>
                  <div class='Acquaintance-elem-text-name'>Василий Новиков</div>
                  <div class='Acquaintance-elem-text-description'>Носитель английского языка</div>
                  <div class='Acquaintance-elem-text-social'>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-vk' title='Вконтакте'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-fb' title='Facebook'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-tw' title='Twitter'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-instagram' title='Instagram'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-whatsapp' title='Whatsapp'></a>
                  </div>
                </div>
              </div>
            </div>
            <div class='col-xs-12 col-sm-12 col-md-1 col-lg-1 col-xl-1'></div>
          </div>
          <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12'>
            <div class='col-xs-12 col-sm-12 col-md-1 col-lg-1 col-xl-1'></div>
            <div class='col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5'>
              <div class='Acquaintance-elem'>
                <div class='Acquaintance-elem-photo'></div>
                <div class='Acquaintance-elem-text'>
                  <div class='Acquaintance-elem-text-name'>Василий Новиков</div>
                  <div class='Acquaintance-elem-text-description'>Преподаватель английского языка</div>
                  <div class='Acquaintance-elem-text-social'>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-vk' title='Вконтакте'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-fb' title='Facebook'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-tw' title='Twitter'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-instagram' title='Instagram'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-whatsapp' title='Whatsapp'></a>
                  </div>
                </div>
              </div>
            </div>
            <div class='col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5'>
              <div class='Acquaintance-elem'>
                <div class='Acquaintance-elem-photo'></div>
                <div class='Acquaintance-elem-text'>
                  <div class='Acquaintance-elem-text-name'>Василий Новиков</div>
                  <div class='Acquaintance-elem-text-description'>Носитель английского языка</div>
                  <div class='Acquaintance-elem-text-social'>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-vk' title='Вконтакте'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-fb' title='Facebook'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-tw' title='Twitter'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-instagram' title='Instagram'></a>
                    <a href='#' class='Acquaintance-elem-text-social-vk icons-whatsapp' title='Whatsapp'></a>
                  </div>
                </div>
              </div>
            </div>
            <div class='col-xs-12 col-sm-12 col-md-1 col-lg-1 col-xl-1'></div>
          </div>-->
      </div>
      <?php if($moreButton): ?>
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12' id='emp-more-btn'>
        <div class='col-xs-12 col-sm-12 col-md-1 col-lg-1 col-xl-1'></div>
        <div class='col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5' onclick="empBlocksShow();">
          <a class='Acquaintance-more'>Больше...</a>
        </div>
        <div class='col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7'></div>
      </div>
      <?php endif; ?>
    </div>
    <!-- Photo -->
    <script>
    $(document).ready(function() {
      <?php
						if($use_db_slider) {
							try {
								$stmt = $pdo_site->prepare("SELECT * FROM `index_slider`");
								$stmt->execute();
								$photos = $stmt->fetchAll();
								for($i = 0; $i < count($photos); $i++) {
									$elem = $photos[$i];
									$id = $elem['id'];
									$link = $path_to_slider_photos.$elem['link'];
									echo("IndexSlider.photos[$i] = {id: $id, link: '$link'};\n");
								}
							}
							catch(Exception $e) {
								debuglog('exception in '.__FILE__.' at line '.__LINE__.' details: ');
								debuglog($e);
							}
						}
						else {
							try {
								$slider_files = scandir($path_to_slider_photos);
								$i = 0;
								foreach($slider_files as $filename) {
									$path = $path_to_slider_photos.$filename;
									if($filename == '.' || $filename == '..' || !is_file($path) || !in_array(pathinfo($path, PATHINFO_EXTENSION), Array('jpg', 'jpeg', 'png'))) continue;
									echo("IndexSlider.photos[$i] = {id: $i, link: '$path'};\n");
									$i++;
								}
							}
							catch(Exception $e) {
								debuglog('exception in '.__FILE__.' at line '.__LINE__.' details: ');
								debuglog($e);
							}
						}
					?>
    });
    </script>
    <div class='row' style='margin-top: 150px;'>
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12'>
        <h4 class='Acquaintance-h4' style='text-align: center;'>Фотографии школы</h4>
        <div class='Acquaintance-title' style='text-align: center;'>
          <div class='back-circle22 hidden-xs hidden-sm'></div>
          <div class='back-triangle22 hidden-xs hidden-sm'></div>
          <div class='Acquaintance-title-text'>Наша школа английского языка</div>
        </div>
      </div>
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12' style='margin-top: 50px;'>
        <div class='col-xs-3 col-sm-3 col-md-1 col-lg-1 col-xl-1' id='slider-school-photo-1'>
          <div class='news-arrow' style='height: 400px;'>
            <div class='news-arrow-btn icons-left' id='index-slider-arrow-l' onclick="IndexSlider.move(false, true);">
            </div>
          </div>
        </div>
        <div class='col-xs-12 col-sm-12 col-md-12 col-lg-10 col-xl-10'>
          <div class='slider-photo'>
            <div class='slider-photo-elem'>
              <div class='slider-photo-elem-img' id='index-slider-img'
                style='background-image: url("https://lifeglobe.net/x/entry/0/1-51.JPG");'></div>
            </div>
          </div>
        </div>
        <div class='col-xs-3 col-sm-3 col-md-1 col-lg-1 col-xl-1' id='slider-school-photo-2'>
          <div class='news-arrow' style='height: 400px;'>
            <div class='news-arrow-btn icons-right' id='index-slider-arrow-r' onclick="IndexSlider.move(true, true);">
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- News -->
    <div class='row' style='margin-top: 150px;'>
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12'>
        <h4 class='Acquaintance-h4' style='text-align: center;'>Новости</h4>
        <div class='Acquaintance-title' style='text-align: center;'>
          <div class='back-plus3 hidden-xs hidden-sm'></div>
          <div class='back-triangle2 hidden-xs hidden-sm'></div>
          <div class='Acquaintance-title-text'>Наши праздники и будни</div>
        </div>
      </div>
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12' style='margin-top: 50px;'>
        <div class='col-xs-2 col-sm-2 col-md-1 col-lg-1 col-xl-1'>
          <div class='news-arrow' style='display: none;'>
            <div class='news-arrow-btn icons-left'></div>
          </div>
        </div>
        <div class='col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10'>
          <div class='news-slider' id='id-news-slider'>
            <a href='#' class='news-slider-elem'
              style='background-image: url("https://lifeglobe.net/x/entry/0/1-51.JPG");'>
              <div class='news-slider-elem-hover'>
                <div class='news-slider-elem-hover-title'>Не следует, однако забывать важные вещи!</div>
                <div class='news-slider-elem-hover-author'>Никита Федотов</div>
              </div>
            </a>
            <a href='#' class='news-slider-elem'>
              <div class='news-slider-elem-hover'>
                <div class='news-slider-elem-hover-title'>Не следует, однако забывать важные вещи!</div>
                <div class='news-slider-elem-hover-author'>Никита Федотов</div>
              </div>
            </a>
            <a href='#' class='news-slider-elem'
              style='background-image: url("https://ic.pics.livejournal.com/kabzon/7182730/2824062/2824062_800.jpg");'>
              <div class='news-slider-elem-hover'>
                <div class='news-slider-elem-hover-title'>Не следует, однако забывать важные вещи!</div>
                <div class='news-slider-elem-hover-author'>Никита Федотов</div>
              </div>
            </a>
          </div>
        </div>
        <div class='col-xs-2 col-sm-2 col-md-1 col-lg-1 col-xl-1'>
          <div class='news-arrow' style='display: none;'>
            <div class='news-arrow-btn icons-right'></div>
          </div>
        </div>
      </div>
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12' style='margin-top: 50px; text-align: center;'>
        <a href='news.php' class='news-btn'>Все новости</a>
      </div>
    </div>

    <!-- Why are we -->
    <div class='row' style='margin-top: 100px;'>
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12'>
        <h4 class='Acquaintance-h4' style='text-align: center;'>НЕСКОЛЬКО ПРИЧИН</h4>
        <div class='Acquaintance-title' style='text-align: center;'>
          <div class='back-circle222 hidden-xs hidden-sm'></div>
          <div class='back-triangle222 hidden-xs hidden-sm'></div>
          <div class='Acquaintance-title-text'>Почему именно мы</div>
        </div>
      </div>
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12' style='margin-top: 70px;'>

        <div class='col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4'>
          <div class='plus-index'>
            <div class='plus-index-ico' style='background-image: url("media/svg/teacher.svg")'></div>
            <div class='plus-index-text'>Творческие и отзывчивые преподаватели</div>
          </div>

          <div class='plus-index'>
            <div class='plus-index-ico' style='background-image: url("media/svg/group.svg")'></div>
            <div class='plus-index-text'>Занятия в мини-группах по 5-6 человек</div>
          </div>

        </div>

        <div class='col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4'>
          <div class='plus-index'>
            <div class='plus-index-ico' style='background-image: url("media/svg/abstract.svg")'></div>
            <div class='plus-index-text'>Сочетание классической и коммуникативной методики</div>
          </div>

          <div class='plus-index'>
            <div class='plus-index-ico' style='background-image: url("media/svg/percentages.svg")'></div>
            <div class='plus-index-text'>Мы внимательно отслеживаем прогресс своих учеников и предлагаем наилучшие
              способы его достижения</div>
          </div>
        </div>

        <div class='col-xs-12 col-sm-12 col-md-6 col-lg-4 col-xl-4'>
          <div class='plus-index'>
            <div class='plus-index-ico' style='background-image: url("media/svg/prototyping.svg")'></div>
            <div class='plus-index-text'>Индивидуальный подход к каждому учащемуся</div>
          </div>

          <div class='plus-index'>
            <div class='plus-index-ico' style='background-image: url("media/svg/result.svg")'></div>
            <div class='plus-index-text'>Видимый результат по окончанию курса</div>
          </div>

        </div>




      </div>
    </div>

    <!-- Msg -->
    <div class='row' style='margin-top: 150px;'>
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12'>
        <div style='height: 100%;' class='col-xs-1 col-sm-1 col-md-1 col-lg-1 col-xl-1'>
          <div class='Acquaintance-svg-triangle hidden-xs hidden-sm'></div>
        </div>
        <div class='col-xs-11 col-sm-11 col-md-11 col-lg-11 col-xl-11'>
          <h4 class='Acquaintance-h4'>Мы всегда на связи</h4>
          <div class='Acquaintance-title'>
            <div class='back-circle-msg hidden-xs hidden-sm'></div>
            <div class='back-plus-msg hidden-xs hidden-sm'></div>
            <div class='Acquaintance-title-text'>Напиши и задай нам вопрос</div>
          </div>
        </div>
      </div>
      <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12' style='margin-top: 50px; margin-bottom: 50px;'>
        <div class='col-xs-12 col-sm-12 col-md-1 col-lg-1 col-xl-1'></div>
        <div class='col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9'>
          <div class='questions'>
            <div class='questions-1'>
              <input type="text" placeholder="Имя" class='input-main' id='mail-name1'>
              <input type="text" placeholder="Фамилия" class='input-main' id='mail-name2'>
              <input type="tel" placeholder="Телефон" class='input-main' id='mail-phone'>
              <input type="email" placeholder="Почта" class='input-main' id='mail-email'>
              <input type="button" value="Отправить сообщение" class='hidden-xs hidden-sm hidden-md btn-main'
                onclick='IndexMail.send();'>
            </div>
            <div class='questions-2'>
              <textarea type="text" placeholder="Ваше сообщение" class='input-2-main' id='mail-msg'></textarea>
            </div>
            <div class='questions-1 hidden-xl hidden-lg'>
              <input type="button" value="Отправить сообщение" class='btn-main' onclick='IndexMail.send();'>
            </div>
          </div>
        </div>
        <div class='col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2'></div>
      </div>
    </div>
  </div>

  <!-- Footer (start) -->
  <?php include('footer.php'); ?>
  <!-- Footer (end) -->

</body>

</html>