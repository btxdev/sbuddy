<?php

	// === setup =================================================================

	include('php/standartScriptPHP.php');

	function short_bytes_str($bytes) {
		if($bytes < 0) $bytes = 2147483648;
		$map = Array(' б', ' Кб', ' Мб', 'Гб');
		$i = 0;
		$word = $map[$i];
		while($bytes > 500) {
			$bytes = $bytes / 500;
			$i++;
			$word = $map[$i];
			if($i > 3) break;
		}
		return strval(round($bytes, 2)).$word;
	}

	// db parameters
	$sql_news = $sql_site;

  $path_to_users_from_root = './admin/admin_panel2.0/media/users/';    // FROM ROOT OF SITE

	// establish connection
  $pdo_dsn = "mysql:host=".$sql_news['host'].";dbname=".$sql_news['db'].";charset=".$sql_news['charset'];
  $pdo_news = new PDO($pdo_dsn, $sql_news['user'], $sql_news['password'], $pdo_options);

	// ===========================================================================

	function not_found() {
		header('Location: errorpages/error.php?number=404');
		exit();
	}

	// ===========================================================================

	$article_data = false;
	// prepare record id
	if(isset($_GET['id'])) {
		$id = intval($_GET['id']);
		if(($id > 0) || ($id < 9999999)) {
			// get record data
			try {
				$stmt1 = $pdo_news->prepare("SELECT * FROM `news` WHERE `id` = ? AND `deleted` = 0 AND `publicated` = 1 LIMIT 1");
				$success = $stmt1->execute([$id]);
				$record_data = $stmt1->fetch(PDO::FETCH_LAZY);
				if(!empty($record_data)) {
					// get user data
					$stmt2 = $pdo_news->prepare("SELECT `account`, `first_name`, `second_name` FROM `accounts` WHERE `account_id` = ?");
					$stmt2->execute([$record_data['account_id']]);
					$user_data = $stmt2->fetch(PDO::FETCH_LAZY);
					if(!empty($user_data)) {
						$article_data = (object)Array(
							'username' => $user_data['account'],
							'record_id' => $id,
							'title' => $record_data['title'],
							'text' => $record_data['data'],
							'attachments' => $record_data['attachments'],
							'author' => $user_data['first_name'].' '.$user_data['second_name'],
							'views' => $record_data['visitors_total'],
							'date' => $record_data['publication_date'],
							'likes' => $record_data['marks_likes'],
							'dislikes' => intval($record_data['marks_count'] - $record_data['marks_likes'])
						);
					}
				}
				else {
					not_found();
				}
			}
			catch(Exception $e) {
				not_found();
			}
		}
		else {
			not_found();
		}
	}

	// ===========================================================================

	// registration referer
	$_SESSION['referer'] = 'article?id='.$id;

?>

<!DOCTYPE html>
<html lang="ru" dir="ltr">

<head>
  <meta charset="utf-8">
  <title><?php if($article_data !== false) { echo($article_data->title); } ?> :: <?=$siteData['title']?></title>
  <link rel="stylesheet" href="style/bootstrap.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="style/main.css">
  <link rel="stylesheet" href="style/footer.css">
  <link rel="stylesheet" href="style/nav.css">
  <link rel="stylesheet" href="style/window.css">
  <link rel="stylesheet" href="style/notification.css">

  <link rel="stylesheet" href="style/article.css">
  <link rel="stylesheet" href="style/news.css">

  <?php include('standartScriptJS.php'); ?>

  <script type="application/javascript" src="js/article.js"></script>

  <?php include('php/fingerprint.php'); ?>

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
        <!--<h2 class='Acquaintance-h2' style='text-align: center;'>10 признаков того, что пора начинать учить английский язык</h2>-->
        <h2 class='Acquaintance-h2' style='text-align: center;'>
          <?php if($article_data !== false) { echo($article_data->title); } ?></h2>
        <div class='Acquaintance-title' style='text-align: center;'>
          <div class='back-triangle2'></div>
          <div class='Acquaintance-title-texth4'></div>
        </div>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div class='article-text'><?php if($article_data !== false) { echo($article_data->text); } ?></div>

        <!-- Слайдер -->
        <div class='article-slider'>
          <div class='col-xs-12 col-sm-12 col-md-12 col-lg-10 col-xl-10' style='height: 100%;'>
            <div class='row' style='height: 100%;'>
              <div class='article-slider-main' id='slider-big'>
                <div class='article-slider-main-left' title='Предыдущая' onclick="sliderArrow('prev')">
                  <div class='article-slider-main-left-ico icons-left' style='left: 15px;'></div>
                </div>
                <div class='article-slider-main-right' title='Следующая' onclick="sliderArrow('next')">
                  <div class='article-slider-main-left-ico icons-right' style='right: 15px;'></div>
                </div>
                <div class='article-slider-main-full'>
                  <div class='article-slider-main-full-ico icons-full' title='На весь экран' onclick="sliderFull(this)">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class='col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 hidden-xs hidden-sm hidden-md'
            style='height: 100%;'>
            <div class='row' style='height: 100%;'>
              <div class='article-slider-photo'>
                <div class='article-slider-photo-previous icons-top'></div>
                <div class='article-slider-photo-main' id='slider-mini'>
                  <!--<div class='article-slider-photo-main-elem' style='background-image: url("sample");'></div>-->
                </div>
                <div class='article-slider-photo-next icons-bottom'></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Файлы -->

        <div class="row">
          <div class='col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-4'>
            <?php
								if($article_data !== false) {
									$attachments = json_decode(html_entity_decode($article_data->attachments, ENT_QUOTES));
									if(is_array($attachments) && !empty($attachments)) {
										foreach($attachments as $attachment) {
											$mime = false; $filename = false; $hash = false;
											if(is_object($attachment)) { $mime = $attachment->mime; $filename = $attachment->filename; $hash = $attachment->hash; }
											if(is_array($attachment)) { $mime = $attachment['mime']; $filename = $attachment['filename']; $hash = $attachment['hash']; }
											if(($mime !== false) && ($filename !== false) && ($hash !== false)) {
												$icon = '📌'; // 📎
												if($mime == 'video') $icon = '🎬';
												if($mime == 'audio') $icon = '🎻';
												if($mime == 'compressed') $icon = '📦';
												if($mime == 'executable') $icon = '⚡';
												if($mime == 'document') $icon = '📄';
												$path_to_file_root = $path_to_users_from_root.'public/'.$article_data->username.'/attachments/record'.$article_data->record_id.'/'.$hash;
												if($mime == 'image') {
													echo("<script>sliderAddPhoto('$path_to_file_root');</script>");
													continue;
												}
												$filesize = short_bytes_str(filesize($path_to_file_root));
												echo("<div class='article-file'>");
												echo("<div class='article-file-ico'>$icon</div>");
												echo("<div class='article-file-text'>");
												echo("<div class='article-file-text-name'>$filename</div>");
												echo("<div class='article-file-text-size'>");
												echo("<div class='article-file-text-size-text'>$filesize</div>");
												echo("</div>");
												echo("</div>");
												echo("<a target='_blank' href='".$path_to_file_root."'><div class='article-file-download icons-download' title='Скачать'></div></a>");
												echo("</div>");
											}
										}
									}
								}
							?>
            <!--<div class='article-file'>
                <div class='article-file-ico'>📄</div>
                <div class='article-file-text'>
                  <div class='article-file-text-name'>Имя файла.docx</div>
                  <div class='article-file-text-size'>
                    <div class='article-file-text-size-text'>13.2 Мб</div>
                  </div>
                </div>
                <div class='article-file-download icons-download' title='Скачать'></div>
              </div>
              <div class='article-file'>
                <div class='article-file-ico'>🎻</div>
                <div class='article-file-text'>
                  <div class='article-file-text-name'>Имя файла.mp3</div>
                  <div class='article-file-text-size'>
                    <div class='article-file-text-size-text'>13.2 Мб</div>
                  </div>
                </div>
                <div class='article-file-download icons-download' title='Скачать'></div>
              </div>
              <div class='article-file'>
                <div class='article-file-ico'>📦</div>
                <div class='article-file-text'>
                  <div class='article-file-text-name'>Имя файла.rar</div>
                  <div class='article-file-text-size'>
                    <div class='article-file-text-size-text'>13.2 Мб</div>
                  </div>
                </div>
                <div class='article-file-download icons-download' title='Скачать'></div>
              </div>
              <div class='article-file'>
                <div class='article-file-ico'>🎬</div>
                <div class='article-file-text'>
                  <div class='article-file-text-name'>Имя файла.mp4</div>
                  <div class='article-file-text-size'>
                    <div class='article-file-text-size-text'>13.2 Мб</div>
                  </div>
                </div>
                <div class='article-file-download icons-download' title='Скачать'></div>
              </div>-->
          </div>
        </div>


        <!-- Автор -->
        <div class='article-author'>
          <span style='margin-top: 5px; margin-right: 15px; display: inline-block; white-space: nowrap;'>
            <div class='article-author-ico icons-user'></div>
            <div class='article-author-line'></div>
            <!--<div class='article-author-name'>Иван Федотов</div>-->
            <div class='article-author-name'><?php if($article_data !== false) { echo($article_data->author); } ?></div>
          </span>
          <span style='margin-top: 5px; margin-right: 15px; display: inline-block; white-space: nowrap;'>
            <div class='article-author-ico2 icons-eyeOpen'></div>
            <div class='article-author-line'></div>
            <?php if($article_data !== false) { echo('<script>$(document).ready(function(){articleViews('.$article_data->views.');});</script>'); } ?>
            <div class='article-author-name' id='views-count'></div>
          </span>
          <span style='margin-top: 5px; display: inline-block; white-space: nowrap;'>
            <div class='article-author-ico2 icons-date'></div>
            <div class='article-author-line'></div>
            <?php if($article_data !== false) { echo('<script>$(document).ready(function(){articleDate("'.$article_data->date.'");});</script>'); } ?>
            <div class='article-author-name' id='publication-date'></div>
          </span>

        </div>

        <!-- Оценка -->
        <?php if(isset($_SESSION['userid'])): ?>
        <script>
        $(document).ready(function() {
          Article.recordId = <?= $article_data->record_id ?>;
          Article.auth = true;
          articleDBGetMark();
        });
        </script>
        <?php endif; ?>

        <div class='row' style='margin-bottom: 55px;'>
          <div class='col-xs-0 col-sm-6 col-md-8 col-lg-9 col-xl-9'></div>
          <div class='col-xs-12 col-sm-6 col-md-4 col-lg-3 col-xl-3'>
            <div class='assessment'>
              <div class='assessment-elem' onclick='setMark("like", true);'>
                <div class='assessment-elem-ico icons-like'></div>
                <?php if($article_data !== false) { echo('<script>$(document).ready(function(){articleLikes('.$article_data->likes.', '.$article_data->dislikes.');});</script>'); } ?>
                <div class='assessment-elem-text' id='likes-count'>0</div>
              </div>
              <div class='assessment-elem' style='margin-right: 0px; margin-left: -2px;'
                onclick='setMark("dislike", true);'>
                <div class='assessment-elem-ico icons-dislike'></div>
                <div class='assessment-elem-text' id='dislikes-count'>0</div>
              </div>
              <div class='assessment-bar'>
                <div class='assessment-bar-line' style='width: 85%' id='likes-bar'></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Рекомендации -->
        <div class='row'>
          <?php
							// add id of current page to list
							if(!isset($_SESSION['news_history'])) $_SESSION['news_history'] = Array();
							if(!in_array($article_data->record_id, $_SESSION['news_history'])) $_SESSION['news_history'][] = $article_data->record_id;
							for($j = 0; $j < 2; $j++) {
								$repeat = false;
								// compose request
								$status = true;
								$query = "SELECT `id`, `title`, `attachments`, `account_id`, `publication_date` FROM `news` WHERE `deleted` = 0 AND `publicated` = 1 ";
								// viewed records
								if(is_array($_SESSION['news_history']) && !empty($_SESSION['news_history'])) {
									// exception: WHERE id != record_id
									$counter = 0;
									foreach($_SESSION['news_history'] as $hr_id) {
										if($counter > 60) break;
										$query .= " AND `id` != ".$hr_id." ";
										$counter++;
									}
								}
								$query .= "ORDER BY `views_total` DESC LIMIT 2";
								try {
									$stmt = $pdo_news->prepare($query);
									$stmt->execute();
								}
								catch(Exception $e) {
									debuglog('sql request fail', 'news_listing');
									$status = false;
								}
								// check rows count
								if($status) {
									$rows = $stmt->fetchAll();
									if(empty($rows)) $rows = Array();
									if(count($rows) < 2) {
										// reset history
										$_SESSION['news_history'] = Array();
										$status = false;
										$repeat = true;
									}
								}
								if($repeat === false) {
									break;
								}
							}
							// fetch
							if($status) {
								echo("<h4 class='col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 recommendation-glTitle'>\n");
								echo("Рекомендуем вам прочитать другие статьи\n");
								echo("</h4>\n");
								$counter2 = 0;
								foreach($rows as $row) {
									$status2 = true;
									if($counter2 > 1) break;
									// find image in attachments
									$image = 'none';
									$fname = false;
									$accid = $row['account_id'];
									//
									if(isset($row['attachments'])) {
										$attachments = json_decode(html_entity_decode($row['attachments'], ENT_QUOTES));
										if(is_array($attachments) && !empty($attachments)) {
											foreach($attachments as $attachment) {
												if(is_object($attachment) && ($attachment->mime == 'image')) {
													$fname = $attachment->hash;
													break;
												}
												if(is_array($attachment) && ($attachment['mime'] == 'image')) {
													$fname = $attachment['hash'];
													break;
												}
											}
										}
									}
									// get user data
									if($fname !== false) {
										// username
										$username = false;
										try {
											$stmt2 = $pdo_news->prepare("SELECT `account` FROM `accounts` WHERE `account_id` = ?");
											$stmt2->execute([$accid]);
											$username = $stmt2->fetchColumn();
											if(empty($username)) {
												debuglog('user not found', 'article.php');
												$status2 = false;
											}
										}
										catch(Exception $exc) {
											debuglog('sql request fail', 'article.php');
											$status2 = false;
										}
										// user folder
										if($username !== false) {
											$image = $path_to_users_from_root.'public/'.$username.'/attachments/record'.$row['id'].'/'.$fname;
										}
									}
									if($status2) {
										// prepare data
										$recid = $row['id'];
										$rectitle = $row['title'];
										$recdate = date('d.m.Y', strtotime($row['publication_date']));
										// put data

										if($counter2 == 1){
											echo("<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6' style='text-align: right;'>\n");
										} else{
											echo("<div class='col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6'>\n");
										}
										echo("<div class='news-filter-elem' style='width: 100%;'>\n");
										echo("<div class='news-filter-elem-photo'>\n");
										if($image == 'none') {
											echo("<div class='news-filter-elem-photo-ico'>📖</div>\n");
										}
										else {
											echo("<div class='news-filter-elem-photo' style='background-image: url(&quot;$image&quot;);'></div>\n");
										}
										echo("</div>\n");
										echo("<div class='news-filter-elem-text'>\n");
										echo("<div class='news-filter-elem-text-title'>$rectitle</div>\n");
										echo("<div class='news-filter-elem-text-date'>\n");
										echo("<div class='news-filter-elem-text-date-ico icons-date'></div>\n");
										echo("<div class='news-filter-elem-text-date-line'></div>\n");
										echo("<div class='news-filter-elem-text-date-text'>$recdate</div>\n");
										echo("</div>\n");
										echo("<div class='news-filter-elem-text-btn'>\n");
										echo("<a href='article?id=$recid' class='news-filter-elem-text-btn-block'>Читать</a>\n");
										echo("</div>\n");
										echo("</div>\n");
										echo("</div>\n");
										echo("</div>\n");
									}
									$counter2++;
								}
							}
						?>
        </div>
      </div>
    </div>
  </div>


  <!-- Footer (start) -->
  <?php include('footer.php'); ?>
  <!-- Footer (end) -->

</body>

</html>