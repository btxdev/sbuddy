<?php

  /*
   *  Study Buddy v1.01
   *  (c) 2019-2020 INSOweb  <http://insoweb.ru>
   *  All rights reserved.
   */

   if(empty($_POST) && empty($_GET)) {
     exit('EMPTY.');
   }

  // === setup =================================================================

  require_once('db_includes.php');
  require_once('mobiledetect/Mobile_Detect.php');
  create_default_session();

  // === parameters ============================================================

  // ...

  // === db information ========================================================

  $sql_site = Array(
  'host' => '127.0.0.1',
  'db' => 'u1878365_sbinsoap',
  'user' => 'u1878365_sbinsoa',
  'password' => '9Y54WG911B',
    'charset' => 'utf8'
  );

  // === PDO ===================================================================

  $pdo_dsn = "mysql:host=".$sql_site['host'].";dbname=".$sql_site['db'].";charset=".$sql_site['charset'];
  $pdo_site = new PDO($pdo_dsn, $sql_site['user'], $sql_site['password'], $pdo_options);

  // === statistics functions ==================================================

  function get_user_agent() {
    $md = new Mobile_Detect;
    if($md->isChrome()) { return 'CHROME'; }
    else if($md->isDolfin()) { return 'DOLFIN'; }
    else if($md->isOpera()) { return 'OPERA'; }
    else if($md->isSkyfire()) { return 'SKYFIRE'; }
    else if($md->isEdge()) { return 'EDGE'; }
    else if($md->isIE()) { return 'IE'; }
    else if($md->isFirefox()) { return 'FIREFOX'; }
    else if($md->isBolt()) { return 'BOLT'; }
    else if($md->isTeaShark()) { return 'SHARK'; }
    else if($md->isBlazer()) { return 'BLAZER'; }
    else if($md->isSafari()) { return 'SAFARI'; }
    else if($md->isWeChat()) { return 'WECHAT'; }
    else if($md->isUCBrowser()) { return 'UCBROWSER'; }
    else if($md->isbaiduboxapp()) { return 'BAIDUBOXAPP'; }
    else if($md->isbaidubrowser()) { return 'BAIDUBROWSER'; }
    else if($md->isDiigoBrowser()) { return 'DIIGOBROWSER'; }
    else if($md->isMercury()) { return 'MERCURY'; }
    else if($md->isObigoBrowser()) { return 'OBIGABROWSER'; }
    else if($md->isNetFront()) { return 'NETFRONT'; }
    else if($md->isGenericBrowser()) { return 'GENERICBROWSER'; }
    else if($md->isPaleMoon()) { return 'PALEMOON'; }
    elseif (strpos($_SERVER["HTTP_USER_AGENT"], "Firefox") !== false) { return 'FIREFOX'; }
    elseif (strpos($_SERVER["HTTP_USER_AGENT"], "Opera") !== false) { return 'OPERA'; }
    elseif (strpos($_SERVER["HTTP_USER_AGENT"], "Chrome") !== false) { return 'CHROME'; }
    elseif (strpos($_SERVER["HTTP_USER_AGENT"], "MSIE") !== false) { return 'IE'; }
    elseif (strpos($_SERVER["HTTP_USER_AGENT"], "Safari") !== false) { return 'SAFARI'; }
    else { return 'OTHER'; }
  }

  function get_os() {
    $md = new Mobile_Detect;
    if($md->isAndroidOS()) { return 'ANDROID'; }
    else if($md->isBlackBerryOS()) { return 'BLACKBERRY'; }
    else if($md->isPalmOS()) { return 'PALMOS'; }
    else if($md->isSymbianOS()) { return 'SYMBIAN'; }
    else if($md->isWindowsMobileOS()) { return 'WINDOWSMOBILE'; }
    else if($md->isWindowsPhoneOS()) { return 'WINDOWSPHONE'; }
    else if($md->isiOS()) { return 'IOS'; }
    else if($md->isiPadOS()) { return 'IPADOS'; }
    else if($md->isMeeGoOS()) { return 'MEEGO'; }
    else if($md->isMaemoOS()) { return 'MAEMO'; }
    else if($md->isJavaOS()) { return 'JAVAOS'; }
    else if($md->iswebOS()) { return 'WEBOS'; }
    else if($md->isbadaOS()) { return 'BADA'; }
    else if($md->isBREWOS()) { return 'BREW'; }
    else { return 'WINDOWS'; }
  }

  function get_platform() {
    $md = new Mobile_Detect;
    if($md->isiPhone()) { return 'iPhone'; }
    else if($md->isBlackBerry()) { return 'BlackBerry'; }
    else if($md->isHTC()) { return 'HTC'; }
    else if($md->isNexus()) { return 'Nexus'; }
    else if($md->isDell()) { return 'Dell'; }
    else if($md->isMotorola()) { return 'Motorola'; }
    else if($md->isSamsung()) { return 'Samsung'; }
    else if($md->isLG()) { return 'LG'; }
    else if($md->isSony()) { return 'Sony'; }
    else if($md->isAsus()) { return 'Asus'; }
    else if($md->isNokiaLumia()) { return 'NokiaLumia'; }
    else if($md->isMicromax()) { return 'Micromax'; }
    else if($md->isPalm()) { return 'Palm'; }
    else if($md->isVertu()) { return 'Vertu'; }
    else if($md->isPantech()) { return 'Pantech'; }
    else if($md->isFly()) { return 'Fly'; }
    else if($md->isWiko()) { return 'Wiko'; }
    else if($md->isiMobile()) { return 'iMobile'; }
    else if($md->isSimValley()) { return 'SimValley'; }
    else if($md->isWolfgang()) { return 'Wolfgang'; }
    else if($md->isAlcatel()) { return 'Alcatel'; }
    else if($md->isNintendo()) { return 'Nintendo'; }
    else if($md->isAmoi()) { return 'Amoi'; }
    else if($md->isINQ()) { return 'INQ'; }
    else if($md->isOnePlus()) { return 'OnePlus'; }
    else if($md->isGenericPhone()) { return 'GenericPhone'; }
    else if($md->isiPad()) { return 'iPad'; }
    else if($md->isNexusTablet()) { return 'NexusTablet'; }
    else if($md->isGoogleTablet()) { return 'GoogleTablet'; }
    else if($md->isSamsungTablet()) { return 'SamsungTablet'; }
    else if($md->isKindle()) { return 'Kindle'; }
    else if($md->isSurfaceTablet()) { return 'SurfaceTablet'; }
    else if($md->isHPTablet()) { return 'HPTablet'; }
    else if($md->isAsusTablet()) { return 'AsusTablet'; }
    else if($md->isBlackBerryTablet()) { return 'BlackBerryTablet'; }
    else if($md->isHTCtablet()) { return 'HTCtablet'; }
    else if($md->isMotorolaTablet()) { return 'MotorolaTablet'; }
    else if($md->isNookTablet()) { return 'NookTablet'; }
    else if($md->isAcerTablet()) { return 'AcerTablet'; }
    else if($md->isToshibaTablet()) { return 'ToshibaTablet'; }
    else if($md->isLGTablet()) { return 'LGTablet'; }
    else if($md->isFujitsuTablet()) { return 'FujitsuTablet'; }
    else if($md->isPrestigioTablet()) { return 'PrestigioTablet'; }
    else if($md->isLenovoTablet()) { return 'LenovoTablet'; }
    else if($md->isDellTablet()) { return 'DellTablet'; }
    else if($md->isYarvikTablet()) { return 'YarvikTablet'; }
    else if($md->isMedionTablet()) { return 'MedionTablet'; }
    else if($md->isArnovaTablet()) { return 'ArnovaTablet'; }
    else if($md->isIntensoTablet()) { return 'IntensoTablet'; }
    else if($md->isIRUTablet()) { return 'IRUTablet'; }
    else if($md->isMegafonTablet()) { return 'MegafonTablet'; }
    else if($md->isEbodaTablet()) { return 'EbodaTablet'; }
    else if($md->isAllViewTablet()) { return 'AllViewTablet'; }
    else if($md->isArchosTablet()) { return 'ArchosTablet'; }
    else if($md->isAinolTablet()) { return 'AinolTablet'; }
    else if($md->isNokiaLumiaTablet()) { return 'NokiaLumiaTablet'; }
    else if($md->isSonyTablet()) { return 'SonyTablet'; }
    else if($md->isPhilipsTablet()) { return 'PhilipsTablet'; }
    else if($md->isCubeTablet()) { return 'CubeTablet'; }
    else if($md->isCobyTablet()) { return 'CobyTablet'; }
    else if($md->isMIDTablet()) { return 'MIDTablet'; }
    else if($md->isMSITablet()) { return 'MSITablet'; }
    else if($md->isSMiTTablet()) { return 'SMiTTablet'; }
    else if($md->isRockChipTablet()) { return 'RockChipTablet'; }
    else if($md->isFlyTablet()) { return 'FlyTablet'; }
    else if($md->isbqTablet()) { return 'bqTablet'; }
    else if($md->isHuaweiTablet()) { return 'HuaweiTablet'; }
    else if($md->isNecTablet()) { return 'NecTablet'; }
    else if($md->isPantechTablet()) { return 'PantechTablet'; }
    else if($md->isBronchoTablet()) { return 'BronchoTablet'; }
    else if($md->isVersusTablet()) { return 'VersusTablet'; }
    else if($md->isZyncTablet()) { return 'ZyncTablet'; }
    else if($md->isPositivoTablet()) { return 'PositivoTablet'; }
    else if($md->isNabiTablet()) { return 'NabiTablet'; }
    else if($md->isKoboTablet()) { return 'KoboTablet'; }
    else if($md->isDanewTablet()) { return 'DanewTablet'; }
    else if($md->isTexetTablet()) { return 'TexetTablet'; }
    else if($md->isPlaystationTablet()) { return 'PlaystationTablet'; }
    else if($md->isTrekstorTablet()) { return 'TrekstorTablet'; }
    else if($md->isPyleAudioTablet()) { return 'PyleAudioTablet'; }
    else if($md->isAdvanTablet()) { return 'AdvanTablet'; }
    else if($md->isDanyTechTablet()) { return 'DanyTechTablet'; }
    else if($md->isGalapadTablet()) { return 'GalapadTablet'; }
    else if($md->isMicromaxTablet()) { return 'MicromaxTablet'; }
    else if($md->isKarbonnTablet()) { return 'KarbonnTablet'; }
    else if($md->isAllFineTablet()) { return 'AllFineTablet'; }
    else if($md->isPROSCANTablet()) { return 'PROSCANTablet'; }
    else if($md->isYONESTablet()) { return 'YONESTablet'; }
    else if($md->isChangJiaTablet()) { return 'ChangJiaTablet'; }
    else if($md->isGUTablet()) { return 'GUTablet'; }
    else if($md->isPointOfViewTablet()) { return 'PointOfViewTablet'; }
    else if($md->isOvermaxTablet()) { return 'OvermaxTablet'; }
    else if($md->isHCLTablet()) { return 'HCLTablet'; }
    else if($md->isDPSTablet()) { return 'DPSTablet'; }
    else if($md->isVistureTablet()) { return 'VistureTablet'; }
    else if($md->isCrestaTablet()) { return 'CrestaTablet'; }
    else if($md->isMediatekTablet()) { return 'MediatekTablet'; }
    else if($md->isConcordeTablet()) { return 'ConcordeTablet'; }
    else if($md->isGoCleverTablet()) { return 'GoCleverTablet'; }
    else if($md->isModecomTablet()) { return 'ModecomTablet'; }
    else if($md->isVoninoTablet()) { return 'VoninoTablet'; }
    else if($md->isECSTablet()) { return 'ECSTablet'; }
    else if($md->isStorexTablet()) { return 'StorexTablet'; }
    else if($md->isVodafoneTablet()) { return 'VodafoneTablet'; }
    else if($md->isEssentielBTablet()) { return 'EssentielBTablet'; }
    else if($md->isRossMoorTablet()) { return 'RossMoorTablet'; }
    else if($md->isiMobileTablet()) { return 'iMobileTablet'; }
    else if($md->isTolinoTablet()) { return 'TolinoTablet'; }
    else if($md->isAudioSonicTablet()) { return 'AudioSonicTablet'; }
    else if($md->isAMPETablet()) { return 'AMPETablet'; }
    else if($md->isSkkTablet()) { return 'SkkTablet'; }
    else if($md->isTecnoTablet()) { return 'TecnoTablet'; }
    else if($md->isJXDTablet()) { return 'JXDTablet'; }
    else if($md->isiJoyTablet()) { return 'iJoyTablet'; }
    else if($md->isFX2Tablet()) { return 'FX2Tablet'; }
    else if($md->isXoroTablet()) { return 'XoroTablet'; }
    else if($md->isViewsonicTablet()) { return 'ViewsonicTablet'; }
    else if($md->isVerizonTablet()) { return 'VerizonTablet'; }
    else if($md->isOdysTablet()) { return 'OdysTablet'; }
    else if($md->isCaptivaTablet()) { return 'CaptivaTablet'; }
    else if($md->isIconbitTablet()) { return 'IconbitTablet'; }
    else if($md->isTeclastTablet()) { return 'TeclastTablet'; }
    else if($md->isOndaTablet()) { return 'OndaTablet'; }
    else if($md->isJaytechTablet()) { return 'JaytechTablet'; }
    else if($md->isBlaupunktTablet()) { return 'BlaupunktTablet'; }
    else if($md->isDigmaTablet()) { return 'DigmaTablet'; }
    else if($md->isEvolioTablet()) { return 'EvolioTablet'; }
    else if($md->isLavaTablet()) { return 'LavaTablet'; }
    else if($md->isAocTablet()) { return 'AocTablet'; }
    else if($md->isMpmanTablet()) { return 'MpmanTablet'; }
    else if($md->isCelkonTablet()) { return 'CelkonTablet'; }
    else if($md->isWolderTablet()) { return 'WolderTablet'; }
    else if($md->isMediacomTablet()) { return 'MediacomTablet'; }
    else if($md->isMiTablet()) { return 'MiTablet'; }
    else if($md->isNibiruTablet()) { return 'NibiruTablet'; }
    else if($md->isNexoTablet()) { return 'NexoTablet'; }
    else if($md->isLeaderTablet()) { return 'LeaderTablet'; }
    else if($md->isUbislateTablet()) { return 'UbislateTablet'; }
    else if($md->isPocketBookTablet()) { return 'PocketBookTablet'; }
    else if($md->isKocasoTablet()) { return 'KocasoTablet'; }
    else if($md->isHisenseTablet()) { return 'HisenseTablet'; }
    else if($md->isHudl()) { return 'Hudl'; }
    else if($md->isTelstraTablet()) { return 'TelstraTablet'; }
    else if($md->isGenericTablet()) { return 'GenericTablet'; }
    else { return 'OTHER'; }
  }

  function get_device() {
    $md = new Mobile_Detect;
    if($md->isMobile() && !$md->isTablet()) {
      return 'MOBILE';
    }
    else if($md->isTablet()) {
      return 'TABLET';
    }
    else {
      return 'PC';
    }
  }

  // === statistics requests ===================================================

  if(isset($_POST['reg_view'])) {
    // prepare
    // key
    if(!isset($_POST['key'])) { exit(); }
    $key = htmlspecialchars($_POST['key'], ENT_QUOTES);
    $len = mb_strlen($key);
    if(($len < 8) || ($len > 64)) { exit(); }
    // login
    $login = '???';
    if(isset($_SESSION['login'])) {
      $login = $_SESSION['login'];
    }
    // page
    $page = htmlspecialchars($_POST['page'], ENT_QUOTES);
    $len = $len = mb_strlen($page);
    if(($len < 1) || ($len > 100)) { exit(); }
    if(preg_match('/.*,.*/uim', $page)) { exit(); }
    // percent
    if(!isset($_POST['percent'])) { exit(); }
    $percent = intval($_POST['percent']);
    if($percent < 0 || $percent > 100) { exit(); }
    // time
    if(!isset($_POST['time'])) { exit(); }
    $time = intval($_POST['time']);
    if($time < 0 || $time > 16777214) { exit(); }
    // SQL
    // check record exists
    try {
      $stmt = $pdo_site->prepare("SELECT * FROM `view_stat` WHERE `visitor` LIKE :visitor AND `session` LIKE :session LIMIT 1");
      $stmt->execute(Array(
        ':visitor' => $key,
        ':session' => $_SESSION['act_token']
      ));
      $column = $stmt->fetch();
      // === record exists ===
      if(isset($column['visitor'])) {
        // get old login and update (de-anon)
        $update_login = $column['login'];
        if($update_login == '???') $update_login = $login;
        // add new page to page history
        $pages = $column['page'];
        $pages_new = $pages.','.$page;
        if((mb_strlen($pages_new) < 254) && (mb_substr($pages, mb_strripos($pages, ',') + 1) != $page) && ($page != $pages)) {
          $page = $pages_new;
        }
        else {
          $page = $pages;
        }
        // update view_time
        $view_time = intval($column['view_time']) + intval($time);
        // update view_percent
        $percent_arr = explode(',', $column['view_percent']);
        $p_count = 0; if(isset($percent_arr[0])) $p_count = $percent_arr[0];
        $p_summ = 0; if(isset($percent_arr[1])) $p_summ = $percent_arr[1];
        $p_count++;
        $p_summ += intval($percent);
        $percent = "$p_count,$p_summ";
        // === update record ===
        $stmt2 = $pdo_site->prepare("UPDATE `view_stat` SET `login` = :login, `ip` = :ip, `page` = :page, `view_time` = :view_time, `view_percent` = :view_percent WHERE `visitor` LIKE :visitor AND `session` LIKE :session");
        $stmt2->execute(Array(
          ':visitor' => $key,
          ':session' => $_SESSION['act_token'],
          ':login' => $update_login,
          ':ip' => $_SERVER['REMOTE_ADDR'],
          ':page' => $page,
          ':view_time' => $view_time,
          ':view_percent' => $percent
        ));
      }
      // === record not exists ===
      else {
        // get data
        $country = get_country_by_ip($_SERVER['REMOTE_ADDR'], 'en');
        $city = get_city_by_ip($_SERVER['REMOTE_ADDR'], 'en');
        $device = get_device();
        $platform = get_platform();
        $os = get_os();
        $browser = get_user_agent();
        // === add new record ===
        $stmt2 = $pdo_site->prepare("INSERT INTO `view_stat` (`visitor`, `session`, `login`, `ip`, `country`, `city`, `page`, `view_time`, `view_percent`, `view_date`, `device`, `platform`, `os`, `browser`) VALUES (:visitor, :session, :login, :ip, :country, :city, :page, :view_time, :view_percent, CURRENT_TIMESTAMP(), :device, :platform, :os, :browser)");
        $status = $stmt2->execute(Array(
          ':visitor' => $key,
          ':session' => $_SESSION['act_token'],
          ':login' => $login,
          ':ip' => $_SERVER['REMOTE_ADDR'],
          ':country' => $country,
          ':city' => $city,
          ':page' => "$page",
          ':view_time' => "$time",
          ':view_percent' => "1,$percent",
          ':device' => $device,
          ':platform' => $platform,
          ':os' => $os,
          ':browser' => $browser
        ));
      }
    }
    catch(Exception $e) {
      debuglog('FILE '.__FILE__.' LINE '.__LINE__.' ERROR: ');
      debuglog($e);
      exit();
    }
    exit();
  }

  // ===========================================================================

  // НАКРУТКА
  /*if(isset($_GET['randomize'])) {
    $t_max = time() - 950400;
    $t_min = time() - 1468800;
    for($i = 0; $i < 200; $i++) {
      try {
        $stmt = $pdo_site->prepare("INSERT INTO `view_stat` (`visitor`, `session`, `login`, `ip`, `page`, `view_time`, `view_percent`, `view_date`) VALUES (:visitor, :session, :login, :ip, :page, :view_time, :view_percent, :timestamp_lol)");
        $status = $stmt->execute(Array(
          ':visitor' => gen_token(16),
          ':session' => gen_token(32),
          ':login' => 'anon'.gen_token(8).$i.'?',
          ':ip' => implode('.', Array(rand(20, 180), rand(20, 255), rand(10, 200), rand(13, 253))),
          ':page' => 'index',
          ':view_time' => strval(rand(1, 360)),
          ':view_percent' => '1,'.strval(rand(10, 82)),
          ':timestamp_lol' => date('Y-m-d H:i:s', rand($t_min, $t_max))
        ));
      }
      catch(Exception $e) {
        debuglog('FILE '.__FILE__.' LINE '.__LINE__.' ERROR: ');
        debuglog($e);
        exit();
      }
    }
    exit();
  }*/

?>
