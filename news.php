<?php

	include('php/standartScriptPHP.php');

	// registration referer
	$_SESSION['referer'] = pathinfo(__FILE__)['filename'];

?>

<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Новости :: <?=$siteData['title']?></title>
    <link rel="stylesheet" href="style/bootstrap.min.css" >
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/footer.css">
    <link rel="stylesheet" href="style/nav.css">
    <link rel="stylesheet" href="style/window.css">
    <link rel="stylesheet" href="style/notification.css">

    <link rel="stylesheet" href="style/news.css">

		<script>var GET_ARGS = <?php echo json_encode($_GET ?? null) ?>;</script>

    <?php include('standartScriptJS.php'); ?>
		<script type="application/javascript" src="js/news.js"></script>

  </head>
  <body>

    <!-- Window (start) -->
    <?php include('windows.php'); ?>
    <!-- Window (end) -->

    <!-- Nav (start) -->
    <?php include('nav.php'); ?>
    <!-- Nav (end) -->

    <!-- Cookies policy notification -->
    <?php
      if(!isset($_COOKIE['cookies_accepted'])) {
        //include('cookies.php');
        echo(file_get_contents('cookies.html'));
      }
    ?>

    <notifications></notifications>

    <div class='container-BigElem'>
      <div class='container-BigElem-2'></div>
      <div class='container background-main'>
        <div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12' style='margin-top: 100px; margin-bottom: 100px;'>
          <h1 class='Acquaintance-h1' style='text-align: center;'>Новости</h1>
          <div class='Acquaintance-title' style='text-align: center;'>
            <div class='back-plus3 hidden-xs hidden-sm hidden-md'></div>
            <div class='back-triangle2'></div>
            <div class='Acquaintance-title-texth4'>Узнай, чем живет наша школа английского!</div>
						<div class='Acquaintance-title-texth5' style='' id="news-best-title">Наши самые интересные статьи</div>
          </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12" id="news-best-container">
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" style='text-align: center;'>
            <a href='#' class='news-elem'>
              <div class='news-elem-photo' style='background-image: url("https://lifeglobe.net/x/entry/0/1-51.JPG");'>
              	<div title='2 место' data-place='2' class='news-elem-photo-place icons-place-bolt' style='color: #c7c7c7;'></div>
              </div>
              <div class='news-elem-text'>
                <div class='news-elem-text-title'>10 признаков того, что пора начинать учить английский язык</div>
                <div class='news-elem-text-author'>Никита Федотов</div>
                <div class='news-elem-text-article'>
                  Идейные соображения высшего порядка, а также укрепление и развитие структуры в значительной степени обуславливает создание дальнейших направлений развития. Значимость этих проблем настолько очевидна, что начало повседневной работы по формированию позиции обеспечивает широкому кругу (специалистов) участие в формировании существенных финансовых и административных условий. Повседневная практика показывает, что укрепление и развитие структуры способствует подготовки и реализации дальнейших направлений развития.
                </div>
              </div>
            </a>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" style='text-align: center;'>
            <a href='#' class='news-elem'>
              <div class='news-elem-photo' style='background-image: url("https://lifeglobe.net/x/entry/0/1-51.JPG");'>
              	<div title='1 место' data-place='1' class='news-elem-photo-place icons-place-bolt' style='color: #ffeb00;'></div>
              </div>
              <div class='news-elem-text' style='background-color: var(--colorLogo);'>
                <div class='news-elem-text-title' style='color: #fff;'>10 признаков того, что пора начинать учить английский язык</div>
                <div class='news-elem-text-author' style='color: #fff;'>Никита Федотов</div>
                <div class='news-elem-text-article' style='color: #fff;'>
                  Идейные соображения высшего порядка, а также укрепление и развитие структуры в значительной степени обуславливает создание дальнейших направлений развития. Значимость этих проблем настолько очевидна, что начало повседневной работы по формированию позиции обеспечивает широкому кругу (специалистов) участие в формировании существенных финансовых и административных условий. Повседневная практика показывает, что укрепление и развитие структуры способствует подготовки и реализации дальнейших направлений развития.
                </div>
              </div>
            </a>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" style='text-align: center;'>
            <a href='#' class='news-elem'>
              <div class='news-elem-photo' style='background-image: url("https://lifeglobe.net/x/entry/0/1-51.JPG");'>
              	<div title='3 место' data-place='3' class='news-elem-photo-place icons-place-bolt' style='color: #a05b15;'></div>
              </div>
              <div class='news-elem-text'>
                <div class='news-elem-text-title'>10 признаков того, что пора начинать учить английский язык</div>
                <div class='news-elem-text-author'>Никита Федотов</div>
                <div class='news-elem-text-article'>
                  Идейные соображения высшего порядка, а также укрепление и развитие структуры в значительной степени обуславливает создание дальнейших направлений развития. Значимость этих проблем настолько очевидна, что начало повседневной работы по формированию позиции обеспечивает широкому кругу (специалистов) участие в формировании существенных финансовых и административных условий. Повседневная практика показывает, что укрепление и развитие структуры способствует подготовки и реализации дальнейших направлений развития.
                </div>
              </div>
            </a>
          </div>


        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
          <div style='width: 100%; text-align: center; font-family: pfb; font-size: 25px; margin-top: 50px; margin-bottom: 50px;'>Другие новости</div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

          <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4" style='text-align: center;'>
            <div class='filter-news'>
              <div class='filter-news-title'>Поиск</div>
              <input type="text" class='filter-news-input' id='news-filter-search' placeholder="Поиск">
              <div class='filter-news-title-2'>Сортировка</div>
              <div>
                <input id='dZdeB-aBjL-rNsI-FQRc' type="radio" name="news-sort" style='display: none;'>
                <input id='dXsPs-IEz0-SAhP-XHVf' type="radio" name="news-sort" style='display: none;'>
                <label class='news-sort-label' for='dZdeB-aBjL-rNsI-FQRc' id='dZdeB-aBjL-rNsI-FQRc-label' onclick="newsFilterSortBy('views');">
                  <label for='dZdeB-aBjL-rNsI-FQRc' class='news-sort-label-2'>По просмотрам</label>
                </label>
                <label class='news-sort-label' for='dXsPs-IEz0-SAhP-XHVf' id='dXsPs-IEz0-SAhP-XHVf-label' onclick="newsFilterSortBy('date');">
                  <label for='dXsPs-IEz0-SAhP-XHVf' class='news-sort-label-2'>По дате</label>
                </label>
              </div>
              <input type="button" value="Найти" class='filter-news-btn' onclick="newsFilterSearch();">
            </div>
            <div class='pages-news' id="news-pagination">
              <a style='width: calc((100% / 6) - 4px);' class='pages-news-elem icons-left' title='Предыдущая' onclick="prevPage();"></a>
              <a style='width: calc((100% / 6) - 4px);' class='pages-news-elem' id='pagination-p1' onclick="setPage('set', $(this).text(), true);">1</a>
              <a style='width: calc((100% / 6) - 4px);' class='pages-news-elem' id='pagination-p2' onclick="setPage('set', $(this).text(), true);">3</a>
              <a style='width: calc((100% / 6) - 4px);' class='pages-news-elem' id='pagination-p3' onclick="setPage('set', $(this).text(), true);">4</a>
              <a style='width: calc((100% / 6) - 4px);' class='pages-news-elem' id='pagination-p4' onclick="setPage('set', $(this).text(), true);">5</a>
              <a style='width: calc((100% / 6) - 4px);' class='pages-news-elem icons-right' title='Следующая' onclick="nextPage();"></a>
            </div>
          </div>
          <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8" style='text-align: center;' id='news-listing'>
            <div class='news-filter-elem'>
              <div class='news-filter-elem-photo'>
                <div class='news-filter-elem-photo-ico'>📖</div>
              </div>
              <div class='news-filter-elem-text'>
                <div class='news-filter-elem-text-title'>10 признаков того, что пора начинать учить английский язык</div>
                <div class='news-filter-elem-text-date'>
                  <div class='news-filter-elem-text-date-ico icons-date'></div>
                  <div class='news-filter-elem-text-date-line'></div>
                  <div class='news-filter-elem-text-date-text'>21.02.2020</div>
                </div>
                <div class='news-filter-elem-text-btn'>
                  <a href='#' class='news-filter-elem-text-btn-block'>Читать</a>
                </div>
              </div>
            </div>
            <div class='news-filter-elem'>
              <div class='news-filter-elem-photo'>
                <div class='news-filter-elem-photo-ico'>📖</div>
              </div>
              <div class='news-filter-elem-text'>
                <div class='news-filter-elem-text-title'>10 признаков того, что пора начинать учить английский язык</div>
                <div class='news-filter-elem-text-date'>
                  <div class='news-filter-elem-text-date-ico icons-date'></div>
                  <div class='news-filter-elem-text-date-line'></div>
                  <div class='news-filter-elem-text-date-text'>21.02.2020</div>
                </div>
                <div class='news-filter-elem-text-btn'>
                  <a href='#' class='news-filter-elem-text-btn-block'>Читать</a>
                </div>
              </div>
            </div>
            <div class='news-filter-elem'>
              <div class='news-filter-elem-photo' style='background-image: url("https://lifeglobe.net/x/entry/0/1-51.JPG");'></div>
              <div class='news-filter-elem-text'>
                <div class='news-filter-elem-text-title'>10 признаков того, что пора начинать учить английский язык</div>
                <div class='news-filter-elem-text-date'>
                  <div class='news-filter-elem-text-date-ico icons-date'></div>
                  <div class='news-filter-elem-text-date-line'></div>
                  <div class='news-filter-elem-text-date-text'>21.02.2020</div>
                </div>
                <div class='news-filter-elem-text-btn'>
                  <a href='#' class='news-filter-elem-text-btn-block'>Читать</a>
                </div>
              </div>
            </div>
            <div class='news-filter-elem'>
              <div class='news-filter-elem-photo'>
                <div class='news-filter-elem-photo-ico'>📖</div>
              </div>
              <div class='news-filter-elem-text'>
                <div class='news-filter-elem-text-title'>10 признаков того, что пора начинать учить английский язык</div>
                <div class='news-filter-elem-text-date'>
                  <div class='news-filter-elem-text-date-ico icons-date'></div>
                  <div class='news-filter-elem-text-date-line'></div>
                  <div class='news-filter-elem-text-date-text'>21.02.2020</div>
                </div>
                <div class='news-filter-elem-text-btn'>
                  <a href='#' class='news-filter-elem-text-btn-block'>Читать</a>
                </div>
              </div>
            </div>
            <div class='news-filter-elem'>
              <div class='news-filter-elem-photo'>
                <div class='news-filter-elem-photo-ico'>📖</div>
              </div>
              <div class='news-filter-elem-text'>
                <div class='news-filter-elem-text-title'>10 признаков того, что пора начинать учить английский язык</div>
                <div class='news-filter-elem-text-date'>
                  <div class='news-filter-elem-text-date-ico icons-date'></div>
                  <div class='news-filter-elem-text-date-line'></div>
                  <div class='news-filter-elem-text-date-text'>21.02.2020</div>
                </div>
                <div class='news-filter-elem-text-btn'>
                  <a href='#' class='news-filter-elem-text-btn-block'>Читать</a>
                </div>
              </div>
            </div>
            <div class='news-filter-elem'>
              <div class='news-filter-elem-photo'>
                <div class='news-filter-elem-photo-ico'>📖</div>
              </div>
              <div class='news-filter-elem-text'>
                <div class='news-filter-elem-text-title'>10 признаков того, что пора начинать учить английский язык</div>
                <div class='news-filter-elem-text-date'>
                  <div class='news-filter-elem-text-date-ico icons-date'></div>
                  <div class='news-filter-elem-text-date-line'></div>
                  <div class='news-filter-elem-text-date-text'>21.02.2020</div>
                </div>
                <div class='news-filter-elem-text-btn'>
                  <a href='#' class='news-filter-elem-text-btn-block'>Читать</a>
                </div>
              </div>
            </div>
            <div class='news-filter-elem'>
              <div class='news-filter-elem-photo'>
                <div class='news-filter-elem-photo-ico'>📖</div>
              </div>
              <div class='news-filter-elem-text'>
                <div class='news-filter-elem-text-title'>10 признаков того, что пора начинать учить английский язык</div>
                <div class='news-filter-elem-text-date'>
                  <div class='news-filter-elem-text-date-ico icons-date'></div>
                  <div class='news-filter-elem-text-date-line'></div>
                  <div class='news-filter-elem-text-date-text'>21.02.2020</div>
                </div>
                <div class='news-filter-elem-text-btn'>
                  <a href='#' class='news-filter-elem-text-btn-block'>Читать</a>
                </div>
              </div>
            </div>
            <div class='news-filter-elem'>
              <div class='news-filter-elem-photo'>
                <div class='news-filter-elem-photo-ico'>📖</div>
              </div>
              <div class='news-filter-elem-text'>
                <div class='news-filter-elem-text-title'>10 признаков того, что пора начинать учить английский язык</div>
                <div class='news-filter-elem-text-date'>
                  <div class='news-filter-elem-text-date-ico icons-date'></div>
                  <div class='news-filter-elem-text-date-line'></div>
                  <div class='news-filter-elem-text-date-text'>21.02.2020</div>
                </div>
                <div class='news-filter-elem-text-btn'>
                  <a href='#' class='news-filter-elem-text-btn-block'>Читать</a>
                </div>
              </div>
            </div>
            <div class='news-filter-elem'>
              <div class='news-filter-elem-photo'>
                <div class='news-filter-elem-photo-ico'>📖</div>
              </div>
              <div class='news-filter-elem-text'>
                <div class='news-filter-elem-text-title'>10 признаков того, что пора начинать учить английский язык</div>
                <div class='news-filter-elem-text-date'>
                  <div class='news-filter-elem-text-date-ico icons-date'></div>
                  <div class='news-filter-elem-text-date-line'></div>
                  <div class='news-filter-elem-text-date-text'>21.02.2020</div>
                </div>
                <div class='news-filter-elem-text-btn'>
                  <a href='#' class='news-filter-elem-text-btn-block'>Читать</a>
                </div>
              </div>
            </div>
            <div class='news-filter-elem'>
              <div class='news-filter-elem-photo'>
                <div class='news-filter-elem-photo-ico'>📖</div>
              </div>
              <div class='news-filter-elem-text'>
                <div class='news-filter-elem-text-title'>10 признаков того, что пора начинать учить английский язык</div>
                <div class='news-filter-elem-text-date'>
                  <div class='news-filter-elem-text-date-ico icons-date'></div>
                  <div class='news-filter-elem-text-date-line'></div>
                  <div class='news-filter-elem-text-date-text'>21.02.2020</div>
                </div>
                <div class='news-filter-elem-text-btn'>
                  <a href='#' class='news-filter-elem-text-btn-block'>Читать</a>
                </div>
              </div>
            </div>
            <div class='news-filter-elem' style='height: 80px;'>
              <div class='news-filter-elem-photo' style='filter: contrast(1) saturate(1) sepia(0); min-height: 80px; width: 80px; background-color: var(--red)'>
                <div class='news-filter-elem-photo-ico icons-error-bolt' style='transform: scale(0.85);'>
                  <div class='news-filter-elem-photo-ico-helper'></div>
                </div>
              </div>
              <div class='news-filter-elem-text'>
                <div class='news-filter-elem-text-empty'>По вашему запросу ничего не найдено!</div>
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
