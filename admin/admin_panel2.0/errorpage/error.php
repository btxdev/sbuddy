<?php

	@$GETlang = $_GET['lang']; //Определяем язык
	$customer_color = '#5d78ff';
  $customer_color2 = '#abbaff';

	if(!isset($GETlang)){
    $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
  } else{
    $lang = $GETlang;
  }


	$errorCode = 'error';
	if(isset($_GET['error'])){
		$errorCode = $_GET['error'];
	}

	if(!(isset($_COOKIE["theme"]))){
		$time = mktime(0,0,0,1,1,2120);
		setcookie('theme', 'white', $time);
	}


	if(@$_COOKIE["theme"] == 'white'){
		$main_color_bg = '#5d78ff';
		$main_bg = '#f2f3f8';
		$color = '#303036';
		$white = '#fff';
		$main_color_bg_2 = '#bbc6ff';
	} else if (@$_COOKIE["theme"] == 'black') {
		$main_color_bg = '#5d78ff';
		$main_bg = '#1a1a1a';
		$color = '#fff';
		$white = '#292929';
		$main_color_bg_2 = '#323a63';
	}

	if($lang == 'ru'){
		$lang = 'ru';
	} else if($lang == 'kz'){
		$lang = 'kz';
	} else if($lang == 'ua'){
		$lang = 'ua';
	} else{
		$lang = 'en';
	}

	$Array_lang = [];

	if(is_dir('../leng/'.$errorCode.'/')){
		$Array_lang =  parse_ini_file(htmlspecialchars('../leng/'.$errorCode.'/'.$lang.'.ini'));
	} else{
		$Array_lang =  parse_ini_file(htmlspecialchars('../leng/error/'.$lang.'.ini'));
	}


?>
<!DOCTYPE html>
<html lang="ru" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Error-<?=$errorCode;?> :: Admin panel</title>
    <link rel="shortcut icon" href="media/img/logo.png" type="image/png">
    <link rel="stylesheet" href="style/error.css">
		<link rel="stylesheet" href="style/preloader.css">
    <link rel="stylesheet" href="media/fonts/fonts.css">
    <script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
    <script type="text/javascript" src="js/error.js"></script>
		<script type="text/javascript" src="js/preloader.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
		<script>
			$('html').get(0).style.setProperty('--main-color-bg','<?php echo($main_color_bg)?>')
			$('html').get(0).style.setProperty('--main-bg','<?php echo($main_bg)?>')
			$('html').get(0).style.setProperty('--color','<?php echo($color)?>')
			$('html').get(0).style.setProperty('--white','<?php echo($white)?>')
			$('html').get(0).style.setProperty('--main-color-bg-2','<?php echo($main_color_bg_2)?>')
		</script>
  </head>
  <body>
		<svg style='position: absolute; visibility: hidden;'>
      <defs>
        <linearGradient
           id="linearGradient2344">
          <stop
             style="stop-color:#0021ca;stop-opacity:1"
             offset="0"
             id="stop2340" />
          <stop
             style="stop-color:#6c82ff;stop-opacity:1"
             offset="1"
             id="stop2342" />
        </linearGradient>
        <linearGradient
           id="linearGradient2321">
          <stop
             id="stop2317"
             offset="0"
             style="stop-color:#0020be;stop-opacity:1" />
          <stop
             id="stop2319"
             offset="1"
             style="stop-color:#5d78ff;stop-opacity:1" />
        </linearGradient>
        <marker>
          <path />
        </marker>
        <marker>
          <path />
        </marker>
        <marker
           style="overflow:visible"
           id="marker1574"
           refX="0"
           refY="0"
           orient="auto">
          <path
             transform="matrix(0.8,0,0,0.8,10,0)"
             style="fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:1.00000003pt;stroke-opacity:1"
             d="M 0,0 5,-5 -12.5,0 5,5 Z"
             id="path1572" />
        </marker>
        <marker
           style="overflow:visible"
           id="marker1435"
           refX="0"
           refY="0"
           orient="auto">
          <path
             transform="matrix(0.2,0,0,0.2,1.2,0)"
             style="fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:1.00000003pt;stroke-opacity:1"
             d="M 0,0 5,-5 -12.5,0 5,5 Z"
             id="path1433" />
        </marker>
        <marker
           style="overflow:visible"
           id="Arrow1Sstart"
           refX="0"
           refY="0"
           orient="auto">
          <path
             transform="matrix(0.2,0,0,0.2,1.2,0)"
             style="fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:1.00000003pt;stroke-opacity:1"
             d="M 0,0 5,-5 -12.5,0 5,5 Z"
             id="path1012" />
        </marker>
        <marker
           orient="auto"
           refY="0"
           refX="0"
           id="DistanceStart"
           style="overflow:visible">
          <g
             style="fill:#000000;fill-opacity:1;stroke:#000000;stroke-opacity:1"
             id="g2300">
            <path
               id="path2306"
               d="M 0,0 H 2"
               style="fill:#000000;fill-opacity:1;stroke:#000000;stroke-width:1.14999998;stroke-linecap:square;stroke-opacity:1" />
            <path
               id="path2302"
               d="M 0,0 13,4 9,0 13,-4 Z"
               style="fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-opacity:1" />
            <path
               id="path2304"
               d="M 0,-4 V 40"
               style="fill:#000000;fill-opacity:1;stroke:#000000;stroke-width:1;stroke-linecap:square;stroke-opacity:1" />
          </g>
        </marker>
        <marker
           style="overflow:visible"
           id="Arrow1Lstart"
           refX="0"
           refY="0"
           orient="auto">
          <path
             transform="matrix(0.8,0,0,0.8,10,0)"
             style="fill:#000000;fill-opacity:1;fill-rule:evenodd;stroke:#000000;stroke-width:1.00000003pt;stroke-opacity:1"
             d="M 0,0 5,-5 -12.5,0 5,5 Z"
             id="path1000" />
        </marker>
        <linearGradient
           gradientUnits="userSpaceOnUse"
           y2="185.28906"
           x2="443.60504"
           y1="284.28906"
           x1="342.60547"
           id="linearGradient2323"
           xlink:href="#linearGradient2321" />
        <linearGradient
           gradientUnits="userSpaceOnUse"
           y2="591.8457"
           x2="339.54691"
           y1="490.8457"
           x1="440.59131"
           id="linearGradient2331"
           xlink:href="#linearGradient2344" />
        <linearGradient
           y2="591.8457"
           x2="339.54691"
           y1="490.8457"
           x1="440.59131"
           gradientUnits="userSpaceOnUse"
           id="linearGradient2352"
           xlink:href="#linearGradient2344" />
        <linearGradient
           y2="185.28906"
           x2="443.60504"
           y1="284.28906"
           x1="342.60547"
           gradientUnits="userSpaceOnUse"
           id="linearGradient2354"
           xlink:href="#linearGradient2321" />
      </defs>
    </svg>
		<div class='preloader'>
      <div class='preloader-block'>
				<div class='preloader-block-ico'>
					<svg style='opacity: 0; transition: 0.25s all; transition-delay: 0.4s; animation: animationPreloader0 1.1s ease-in-out; animation-delay: 0.4s; transform-origin: 35px 29px;'
             xmlns:dc="http://purl.org/dc/elements/1.1/"
             xmlns:cc="http://creativecommons.org/ns#"
             xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
             xmlns:svg="http://www.w3.org/2000/svg"
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             version="0.0"
             viewBox="0 0 124.10456 124.10457"
             height="124.10457mm"
             width="124.10457mm">
            <g
               transform="translate(-61.077338,-72.896314)"
               id="layer1">
              <g
                 transform="translate(2.1166666)"
                 id="g2350">
                 <g id="g-block1-lvl1">
                   <g id="g-block1-lvl2">
                     <g id="g-block1-lvl3">
                       <g id="g-block1-lvl4-elem1"> <!-- Верхний треугольник -->
                         <path
                            id='preloader-logo-4'
                            style="opacity:1;fill:url(#linearGradient2354);fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:58.33576965;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:normal"
                            d="m 309.60547,156.03906 158.35156,158.5 140.70313,-144.33008 c 0.60419,-0.69397 1.15889,-1.41551 1.65429,-2.16406 0.75311,-1.13815 1.38071,-2.33252 1.88282,-3.5664 0.55665,-1.44016 0.53378,-2.54264 -0.01,-3.77344 -0.61295,-1.38797 -1.51387,-2.05462 -2.73828,-2.57617 -1.22748,-0.51746 -2.51425,-0.91917 -3.85156,-1.19141 -1.22813,-0.25002 -2.49978,-0.3831 -3.79883,-0.4043 z"
                            transform="matrix(0.26458333,0,0,0.26458333,17.150846,31.610984)"
                            id="path2177-8" />
                       </g>
                       <g id="g-block1-lvl1-elem2"> <!-- Верхний квадрат -->
                         <path
                            id='preloader-logo-1'
                            style="opacity:1;fill:#5d78ff;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:36.84435654;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:normal"
                            d="M 309.60547,156.03906 172.04688,293.5957 c -10e-4,10e-4 -0.003,0.003 -0.004,0.004 -1.00247,1.00304 -1.88069,2.08406 -2.63281,3.2207 -0.75307,1.13817 -1.38074,2.33254 -1.88282,3.56641 -0.50203,1.23383 -0.87794,2.5056 -1.1289,3.79687 -0.25096,1.29132 -0.37696,2.60168 -0.37696,3.91211 0,1.31044 0.126,2.62084 0.37696,3.91211 0.25103,1.29132 0.62687,2.56497 1.1289,3.79883 0.50208,1.23387 1.12975,2.42828 1.88282,3.56641 0.7531,1.13813 1.63261,2.21855 2.63672,3.22265 l 144,144 152.05859,-152.05664 z"
                            transform="matrix(0.26458333,0,0,0.26458333,17.150846,31.610984)"
                            id="rect2160" />
                       </g>
                     </g>
                   </g>
                 </g>
                 <g id="g-block2-lvl1">
                   <g id="g-block2-lvl2">
                     <g id="g-block2-lvl3">
                       <g id="g-block2-lvl4-elem1"> <!-- Нижний треугольник -->
                         <path
                            id='preloader-logo-3'
                            style="opacity:1;fill:url(#linearGradient2352);fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:58.33576965;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:normal"
                            d="M 316.19531,466.5957 175.70312,610.70898 c -0.68324,0.76225 -1.30013,1.56235 -1.8496,2.39258 -0.75311,1.13817 -1.38071,2.33254 -1.88282,3.56641 -0.55724,1.44139 -0.47308,2.715 0.0723,3.94726 0.61393,1.38721 1.45142,1.88081 2.67578,2.40235 1.22748,0.51745 2.51425,0.91928 3.85156,1.1914 1.05357,0.21457 2.13895,0.34559 3.2461,0.39258 l 292.73047,0.49414 z"
                            transform="matrix(0.26458333,0,0,0.26458333,17.150846,31.610984)"
                            id="path2177" />

                       </g>
                       <g id="g-block2-lvl4-elem2"> <!-- Нижний квадрат -->
                         <path
                            id='preloader-logo-2'
                            style="opacity:1;fill:#6c84ff;fill-opacity:1;fill-rule:nonzero;stroke:none;stroke-width:36.84435654;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;paint-order:normal"
                            d="m 468.10547,314.53906 -14.5,14.5 -123.05859,123.0586 -14.5,14.49804 14.5,14.5 129.5,129.5 14.5,14.5 14.5,-14.5 123.05859,-123.05859 c 1.00367,-1.00382 1.88194,-2.08298 2.63476,-3.2207 0.7531,-1.13814 1.38075,-2.3345 1.88282,-3.56836 0.50206,-1.23386 0.87982,-2.50559 1.13086,-3.79688 0.25103,-1.29129 0.375,-2.60167 0.375,-3.91211 0,-1.31044 -0.12397,-2.62081 -0.375,-3.91211 -0.25104,-1.29129 -0.6288,-2.56301 -1.13086,-3.79687 -0.50207,-1.23386 -1.12972,-2.43022 -1.88282,-3.56836 -0.75309,-1.13814 -1.63064,-2.21853 -2.63476,-3.22266 l -129.5,-129.5 z"
                            transform="matrix(0.26458333,0,0,0.26458333,17.150846,31.610984)"
                            id="path2236" />
                       </g>
                     </g>
                   </g>
                 </g>
              </g>
            </g>
          </svg>
				</div>

			</div>
    </div>
		<div class='notification'>
			<div class='notification-block-standart' style='max-width: 320px;' id='$system_@id@_js$'><!-- standart -->
				<div class='arrow-i' title='<?php echo $Array_lang["Close"];?>' onclick="close_noti(this)">
          <div class='arrow-1'></div>
          <div class='arrow-2'></div>
        </div>
				<div class='notification-block-standart-text'>$system_@text@_js$</div>
			</div>
		</div>
    <div class='main'>
			<div class='edge1'>
	      <div class='main-title'><?php echo $Array_lang["code"];?></div>
	      <div class='main-title-a'><?php echo $Array_lang["code"];?></div>
	      <div class='main-text-1'>
	        <?php echo $Array_lang["oops"];?>
	      </div>
	      <div class='main-text-1-a'><?php echo $Array_lang["oops"];?></div>
	      <div class='main-text-2'><?php echo $Array_lang["The requested URL"];?></div>
	      <div onclick="redirect('./')" class='main-btn'><?php echo $Array_lang["Back to homepage"];?></div>

			</div>
      <div class='main-search edge1' onclick="open_window()"><?php echo $Array_lang["Tech support"];?></div>
      <div class='main-search-none'>
				<div class='main-search-none-preloader'>
				</div>
        <div class='arrow' id='close' onclick="close_window(this)" title='<?php echo $Array_lang["Close"];?>'>
          <div class='arrow-1'></div>
          <div class='arrow-2'></div>
        </div>
        <div class='main-search-none-title'><?php echo $Array_lang["Tech support"];?></div>
        <input placeholder="<?php echo $Array_lang['name'];?>" type='text' id='name'>
        <input placeholder="<?php echo $Array_lang['email'];?>" type='email' id='email'>
        <input placeholder="<?php echo $Array_lang['tel'];?>" type='tel' id='tel'>
        <textarea placeholder="<?php echo $Array_lang['msg'];?>" type='text' id='text'></textarea>
        <div class='main-search-none-btn' onclick="msg_to_send()">Отправить</div>

      </div>
      <div id='user-select' class='edge1'>
        <div class='circle'style='top: 91px; left: 155px; opacity: 0.25;'></div>
        <div id='circle2' class='circle2'style='top: 192px; left: 181px; opacity: 0.38;'></div>
        <div class='circle'style='top: 24px; left: 400px; opacity: 0.45;'></div>
        <div class='circle2'style='top: 105px; left: 545px; opacity: 0.34;'></div>
        <div class='circle2'style='top: 449px; left: 503px; opacity: 0.51;'></div>
        <div class='circle'style='top: 202px; left: 663px; opacity: 0.47;'></div>
        <div class='circle'style='top: 453px; left: 102px; opacity: 0.47;'></div>
        <div class='circle'style='top: 302px; left: 600px; opacity: 0.42;'></div>
        <div class='square2'style='top: 215px; left: 468px; opacity: 0.5; transform: rotate(45deg)'></div>
        <div class='square2'style='top: 368px; left: 148px; opacity: 0.5; transform: rotate(45deg)'></div>
        <div class='square'style='top: 10px; left: 600px; opacity: 0.5; transform: rotate(45deg)'></div>
        <div class='square'style='top: 161px; left: 81px; opacity: 0.5; transform: rotate(45deg)'></div>
        <div class='square2'style='top: -26px; left: 272px; opacity: 0.5; transform: rotate(45deg)'></div>
        <div id='line1' style='position: absolute; top: 16px; left: 71px;'>
          <div class='line' style='transform: rotate(-45deg);'></div>
          <div class='line' style='transform: rotate(45deg);'></div>
          <div class='line' style='transform: rotate(-45deg);'></div>
          <div class='line' style='transform: rotate(45deg);'></div>
          <div class='line' style='transform: rotate(-45deg);'></div>
          <div class='line' style='transform: rotate(45deg);'></div>
          <div class='line' style='transform: rotate(-45deg);'></div>
          <div class='line' style='transform: rotate(45deg);'></div>
        </div>
        <div id='line2' style='position: absolute; top: 416px; left: 621px;'>
          <div class='line' style='transform: rotate(-45deg);'></div>
          <div class='line' style='transform: rotate(45deg);'></div>
          <div class='line' style='transform: rotate(-45deg);'></div>
          <div class='line' style='transform: rotate(45deg);'></div>
          <div class='line' style='transform: rotate(-45deg);'></div>
          <div class='line' style='transform: rotate(45deg);'></div>
          <div class='line' style='transform: rotate(-45deg);'></div>
          <div class='line' style='transform: rotate(45deg);'></div>
        </div>
        <div id='line3' style='position: absolute; top: 300px; left: 100px;'>
          <div class='line' style='transform: rotate(-45deg);'></div>
          <div class='line' style='transform: rotate(45deg);'></div>
          <div class='line' style='transform: rotate(-45deg);'></div>
          <div class='line' style='transform: rotate(45deg);'></div>
          <div class='line' style='transform: rotate(-45deg);'></div>
          <div class='line' style='transform: rotate(45deg);'></div>
          <div class='line' style='transform: rotate(-45deg);'></div>
          <div class='line' style='transform: rotate(45deg);'></div>
        </div>
      </div>
    </div>
  </body>
</html>
