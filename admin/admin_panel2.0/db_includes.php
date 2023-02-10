<?php

  /*
   *  Swiftly Admin Panel v1.12 alpha
   *  (c) 2019-2020 INSOweb  <http://insoweb.ru>
   *  All rights reserved.
   */

  /*
     SESSION VARIABLES
        auth
        username
        userid
        userlvl
        act_token
        version

     LEVELS
        default
        redactor
        moderator
        administrator
        superuser
  */

  // == setup ==================================================================

  $sess_name                   = 'INSOAdminPanel';
  $sess_v                      = '4';
  $program_version             = '<span title="1.21 Beta">1.21 <small>β</small></span>';

  // == mobile detect ==========================================================
  require_once('php/lib_php/Mobile_Detect.php');
  $detect = new Mobile_Detect;

  // == parameters =============================================================

  $development_state           =  true;
  $typeLearning                =  ['Групповое обучение','Онлайн обучение'];


  if(!file_exists('php/configPHP/config.php')){
    include_once('php/configPHP/config_default.php');
  } else{
    include_once('php/configPHP/config.php');
  }

  date_default_timezone_set($timezone);


  // == config =================================================================

  $statisticsPanel             =  false;
  $individualMsgPanel          =  false;
  $chatPanel                   =  true;
  $finderPanel                 =  true;
  $newsPanel                   =  true;
  $timeTablePanel              =  true;
  $contactsPanel               =  true;
  $reviewsPanel                =  false;
  $aboutCompanyPanel           =  true;
  $usersPanel                  =  false;
  $employeesPanel              =  false;
  $newYearPanel                =  false;


  // == documents ==============================================================

  $pdppLocal                   =  'doc/Privacy policy.php';
  $TOFLocal                    =  'doc/Terms of use.php';

  // == https check ============================================================

  $isHttps = !empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS']);
  $isHttps = (iconv_strlen($isHttps) == 0) ? false : true;

  // == finder =================================================================

  $bad_filenames_array         =  Array('con', 'nul', 'prn', 'aux','com1','com2','com3','com4','com5','com6','com7','com8','com9','lpt1','lpt2','lpt3','lpt4','lpt5','lpt6','lpt7','lpt8','lpt9');

  // == regex ==================================================================

  $login_regex                 =  '/^([a-z0-9]){4,32}$/';
  $password_regex              =  '/^([a-zA-Z0-9-.,_!а-яА-ЯёЁ]){8,64}$/';
  $email_regex                 =  '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/';
  $phone_regex                 =  '/^([0-9]){11}$/';
  $name_regex                  =  '/^([A-Za-zА-ЯЁа-яё]){2,32}$/u';
  $city_regex                  =  '/^([A-Za-zА-ЯЁа-яё ,.]){2,32}$/u';
  $recovery_code_regex         =  '/^([0-9]){7}$/';
  $datetime_regex              =  '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
  $dateonly_regex              =  '/(\d{4})-(\d{2})-(\d{2})/';

  // == file formats ===========================================================

  $video_extensions = Array(
    '3g2',
    '3gp',
    'avi',
    'flv',
    'h264',
    'm4v',
    'mkv',
    'mov',
    'mp4',
    'mpg',
    'mpeg',
    'rm',
    'swf',
    'vob',
    'wmv'
  );
  $audio_extensions = Array(
    'aif',
    'cda',
    'mid',
    'midi',
    'mp3',
    'mpa',
    'ogg',
    'wav',
    'wma',
    'wpl'
  );
  $compressed_extensions = Array(
    '7z',
    'arj',
    'deb',
    'pkg',
    'rar',
    'rpm',
    'gz',
    'z',
    'zip'
  );
  $executable_extensions = Array(
    'apk',
    'bat',
    'bin',
    'cgi',
    'pl',
    'com',
    'exe',
    'gadget',
    'jar',
    'msi',
    'py',
    'wsf'
  );
  $document_extensions = Array(
    'key',
    'odp',
    'pps',
    'ppt',
    'pptx',
    'doc',
    'docx',
    'odt',
    'pdf',
    'rtf',
    'tex',
    'txt',
    'wpd',
    'xlsx',
    'xlsm',
    'xlsb',
    'xltx',
    'xltm',
    'xls',
    'xlt',
    'xml'
  );
  $image_extensions = Array(
    'jpeg',
    'jpg',
    'gif',
    'png',
    'webp',
    'svg'
  );

  // == location ===============================================================

  require_once('php/sxgeo/SxGeo.php');
  $sx_geo_city_db_path = 'php/sxgeo/SxGeoCity.dat';
  $sx_geo_country_db_path = 'php/sxgeo/SxGeoCountry.dat';

  $_ISO_RU = Array(
    'AD' => 'Андорра',
    'AE' => 'Объединенные Арабские Эмираты',
    'AF' => 'Афганистан',
    'AG' => 'Антигуа и Депс',
    'AI' => 'Ангилья',
    'AL' => 'Албания',
    'AM' => 'Армения',
    'AO' => 'Ангола',
    'AQ' => 'Антарктика',
    'AR' => 'Аргентина',
    'AS' => 'Американское Самоа',
    'AT' => 'Австрия',
    'AU' => 'Австралия',
    'AW' => 'Аруба',
    'AX' => 'Аландские острова',
    'AZ' => 'Азербайджан',
    'BA' => 'Босния и Герцеговина',
    'BB' => 'Барбадос',
    'BD' => 'Бангладеш',
    'BE' => 'Бельгия',
    'BG' => 'Болгария',
    'BH' => 'Бахрейн',
    'BO' => 'Боливия',
    'BR' => 'Бразилия',
    'BS' => 'Багамы',
    'BW' => 'Ботсвана',
    'BY' => 'Беларусь',
    'CA' => 'Канада',
    'CH' => 'Швейцария',
    'CL' => 'Чили',
    'CN' => 'Китай',
    'CU' => 'Куба',
    'CY' => 'Кипр',
    'DE' => 'Германия',
    'EE' => 'Эстония',
    'EG' => 'Египт',
    'ES' => 'Испания',
    'EU' => 'Европа',
    'FI' => 'Финляндия',
    'FR' => 'Франция',
    'GB' => 'Великобритания',
    'GR' => 'Греция',
    'GT' => 'Гватемала',
    'ID' => 'Индонезия',
    'IE' => 'Ирландия',
    'IL' => 'Израиль',
    'IN' => 'Индия',
    'IQ' => 'Ирак',
    'IR' => 'Иран',
    'IS' => 'Исландия',
    'IT' => 'Италия',
    'JP' => 'Япония',
    'KZ' => 'Казахстан',
    'MC' => 'Монако',
    'NL' => 'Нидерланды',
    'NO' => 'Норвегия',
    'PL' => 'Польша',
    'RU' => 'Россия',
    'SD' => 'Судан',
    'SE' => 'Швеция',
    'SG' => 'Сингапур',
    'TH' => 'Тайланд',
    'UA' => 'Украина',
    'UG' => 'Уганда',
    'US' => 'США',
    'UZ' => 'Узбекистан',
    'VN' => 'Вьетнам',
    'ZA' => 'Южная Африка',
    'ZM' => 'Замбия',
    'ZW' => 'Зимбабве'
  );

  $_ISO_EN = Array(
    'AD' => 'Andorra',
    'AE' => 'United Arab Emirates',
    'AF' => 'Afghanistan',
    'AG' => 'Antigua and Barbuda',
    'AI' => 'Anguilla',
    'AL' => 'Albania',
    'AM' => 'Armenia',
    'AO' => 'Angola',
    'AP' => 'Asia/Pacific Region',
    'AQ' => 'Antarctica',
    'AR' => 'Argentina',
    'AS' => 'American Samoa',
    'AT' => 'Austria',
    'AU' => 'Australia',
    'AW' => 'Aruba',
    'AX' => 'Aland Islands',
    'AZ' => 'Azerbaijan',
    'BA' => 'Bosnia and Herzegovina',
    'BB' => 'Barbados',
    'BD' => 'Bangladesh',
    'BE' => 'Belgium',
    'BF' => 'Burkina Faso',
    'BG' => 'Bulgaria',
    'BH' => 'Bahrain',
    'BI' => 'Burundi',
    'BJ' => 'Benin',
    'BL' => 'Saint Barthelemy',
    'BM' => 'Bermuda',
    'BN' => 'Brunei Darussalam',
    'BO' => 'Bolivia',
    'BQ' => 'Bonaire. Saint Eustatius and Saba',
    'BR' => 'Brazil',
    'BS' => 'Bahamas',
    'BT' => 'Bhutan',
    'BV' => 'Bouvet Island',
    'BW' => 'Botswana',
    'BY' => 'Belarus',
    'BZ' => 'Belize',
    'CA' => 'Canada',
    'CC' => 'Cocos (Keeling) Islands',
    'CD' => 'Congo. The Democratic Republic of the',
    'CF' => 'Central African Republic',
    'CG' => 'Congo',
    'CH' => 'Switzerland',
    'CI' => 'Cote d Ivoire',
    'CK' => 'Cook Islands',
    'CL' => 'Chile',
    'CM' => 'Cameroon',
    'CN' => 'China',
    'CO' => 'Colombia',
    'CR' => 'Costa Rica',
    'CU' => 'Cuba',
    'CV' => 'Cape Verde',
    'CW' => 'Curacao',
    'CX' => 'Christmas Island',
    'CY' => 'Cyprus',
    'CZ' => 'Czech Republic',
    'DE' => 'Germany',
    'DJ' => 'Djibouti',
    'DK' => 'Denmark',
    'DM' => 'Dominica',
    'DO' => 'Dominican Republic',
    'DZ' => 'Algeria',
    'EC' => 'Ecuador',
    'EE' => 'Estonia',
    'EG' => 'Egypt',
    'EH' => 'Western Sahara',
    'ER' => 'Eritrea',
    'ES' => 'Spain',
    'ET' => 'Ethiopia',
    'EU' => 'Europe',
    'FI' => 'Finland',
    'FJ' => 'Fiji',
    'FK' => 'Falkland Islands (Malvinas)',
    'FM' => 'Micronesia. Federated States of',
    'FO' => 'Faroe Islands',
    'FR' => 'France',
    'GA' => 'Gabon',
    'GB' => 'United Kingdom',
    'GD' => 'Grenada',
    'GE' => 'Georgia',
    'GF' => 'French Guiana',
    'GG' => 'Guernsey',
    'GH' => 'Ghana',
    'GI' => 'Gibraltar',
    'GL' => 'Greenland',
    'GM' => 'Gambia',
    'GN' => 'Guinea',
    'GP' => 'Guadeloupe',
    'GQ' => 'Equatorial Guinea',
    'GR' => 'Greece',
    'GS' => 'South Georgia and the South Sandwich Islands',
    'GT' => 'Guatemala',
    'GU' => 'Guam',
    'GW' => 'Guinea-Bissau',
    'GY' => 'Guyana',
    'HK' => 'Hong Kong',
    'HM' => 'Heard Island and McDonald Islands',
    'HN' => 'Honduras',
    'HR' => 'Croatia',
    'HT' => 'Haiti',
    'HU' => 'Hungary',
    'ID' => 'Indonesia',
    'IE' => 'Ireland',
    'IL' => 'Israel',
    'IM' => 'Isle of Man',
    'IN' => 'India',
    'IO' => 'British Indian Ocean Territory',
    'IQ' => 'Iraq',
    'IR' => 'Iran. Islamic Republic of',
    'IS' => 'Iceland',
    'IT' => 'Italy',
    'JE' => 'Jersey',
    'JM' => 'Jamaica',
    'JO' => 'Jordan',
    'JP' => 'Japan',
    'KE' => 'Kenya',
    'KG' => 'Kyrgyzstan',
    'KH' => 'Cambodia',
    'KI' => 'Kiribati',
    'KM' => 'Comoros',
    'KN' => 'Saint Kitts and Nevis',
    'KP' => 'Korea. Democratic People Republic of',
    'KR' => 'Korea. Republic of',
    'KW' => 'Kuwait',
    'KY' => 'Cayman Islands',
    'KZ' => 'Kazakhstan',
    'LA' => 'Lao People Democratic Republic',
    'LB' => 'Lebanon',
    'LC' => 'Saint Lucia',
    'LI' => 'Liechtenstein',
    'LK' => 'Sri Lanka',
    'LR' => 'Liberia',
    'LS' => 'Lesotho',
    'LT' => 'Lithuania',
    'LU' => 'Luxembourg',
    'LV' => 'Latvia',
    'LY' => 'Libyan Arab Jamahiriya',
    'MA' => 'Morocco',
    'MC' => 'Monaco',
    'MD' => 'Moldova. Republic of',
    'ME' => 'Montenegro',
    'MF' => 'Saint Martin',
    'MG' => 'Madagascar',
    'MH' => 'Marshall Islands',
    'MK' => 'Macedonia',
    'ML' => 'Mali',
    'MM' => 'Myanmar',
    'MN' => 'Mongolia',
    'MO' => 'Macao',
    'MP' => 'Northern Mariana Islands',
    'MQ' => 'Martinique',
    'MR' => 'Mauritania',
    'MS' => 'Montserrat',
    'MT' => 'Malta',
    'MU' => 'Mauritius',
    'MV' => 'Maldives',
    'MW' => 'Malawi',
    'MX' => 'Mexico',
    'MY' => 'Malaysia',
    'MZ' => 'Mozambique',
    'NA' => 'Namibia',
    'NC' => 'New Caledonia',
    'NE' => 'Niger',
    'NF' => 'Norfolk Island',
    'NG' => 'Nigeria',
    'NI' => 'Nicaragua',
    'NL' => 'Netherlands',
    'NO' => 'Norway',
    'NP' => 'Nepal',
    'NR' => 'Nauru',
    'NU' => 'Niue',
    'NZ' => 'New Zealand',
    'OM' => 'Oman',
    'PA' => 'Panama',
    'PE' => 'Peru',
    'PF' => 'French Polynesia',
    'PG' => 'Papua New Guinea',
    'PH' => 'Philippines',
    'PK' => 'Pakistan',
    'PL' => 'Poland',
    'PM' => 'Saint Pierre and Miquelon',
    'PN' => 'Pitcairn',
    'PR' => 'Puerto Rico',
    'PS' => 'Palestinian Territory',
    'PT' => 'Portugal',
    'PW' => 'Palau',
    'PY' => 'Paraguay',
    'QA' => 'Qatar',
    'RE' => 'Reunion',
    'RO' => 'Romania',
    'RS' => 'Serbia',
    'RU' => 'Russian Federation',
    'RW' => 'Rwanda',
    'SA' => 'Saudi Arabia',
    'SB' => 'Solomon Islands',
    'SC' => 'Seychelles',
    'SD' => 'Sudan',
    'SE' => 'Sweden',
    'SG' => 'Singapore',
    'SH' => 'Saint Helena',
    'SI' => 'Slovenia',
    'SJ' => 'Svalbard and Jan Mayen',
    'SK' => 'Slovakia',
    'SL' => 'Sierra Leone',
    'SM' => 'San Marino',
    'SN' => 'Senegal',
    'SO' => 'Somalia',
    'SR' => 'Suriname',
    'SS' => 'South Sudan',
    'ST' => 'Sao Tome and Principe',
    'SV' => 'El Salvador',
    'SX' => 'Sint Maarten',
    'SY' => 'Syrian Arab Republic',
    'SZ' => 'Swaziland',
    'TC' => 'Turks and Caicos Islands',
    'TD' => 'Chad',
    'TF' => 'French Southern Territories',
    'TG' => 'Togo',
    'TH' => 'Thailand',
    'TJ' => 'Tajikistan',
    'TK' => 'Tokelau',
    'TL' => 'Timor-Leste',
    'TM' => 'Turkmenistan',
    'TN' => 'Tunisia',
    'TO' => 'Tonga',
    'TR' => 'Turkey',
    'TT' => 'Trinidad and Tobago',
    'TV' => 'Tuvalu',
    'TW' => 'Taiwan',
    'TZ' => 'Tanzania. United Republic of',
    'UA' => 'Ukraine',
    'UG' => 'Uganda',
    'UM' => 'United States Minor Outlying Islands',
    'US' => 'United States',
    'UY' => 'Uruguay',
    'UZ' => 'Uzbekistan',
    'VA' => 'Holy See',
    'VC' => 'Saint Vincent and the Grenadines',
    'VE' => 'Venezuela',
    'VG' => 'Virgin Islands. British',
    'VI' => 'Virgin Islands. U.S.',
    'VN' => 'Vietnam',
    'VU' => 'Vanuatu',
    'WF' => 'Wallis and Futuna',
    'WS' => 'Samoa',
    'YE' => 'Yemen',
    'YT' => 'Mayotte',
    'ZA' => 'South Africa',
    'ZM' => 'Zambia',
    'ZW' => 'Zimbabwe'
  );

  function get_country_by_ip($ip, $lang) {
    global $sx_geo_country_db_path;
    global $_ISO_EN;
    global $_ISO_RU;
    $sx_geo = new SxGeo($sx_geo_country_db_path, SXGEO_BATCH);
    $code = $sx_geo->get($ip);
    $location = false;
    if($lang == 'ru') {
      if(array_key_exists($code, $_ISO_RU)) {
        $location = $_ISO_RU[$code];
      }
    }
    if(!$location) {
      if(array_key_exists($code, $_ISO_EN)) {
        $location = $_ISO_EN[$code];
      }
      else if(!empty($code)) {
        $location = $code;
      }
      else {
        $location = 'Не определена';
      }
    }
    return $location;
  }

  function get_city_by_ip($ip, $lang) {
    global $sx_geo_city_db_path;
    $sx_geo = new SxGeo($sx_geo_city_db_path, SXGEO_BATCH);
    $location_data = $sx_geo->getCityFull($ip);
    $location = 'Не определен';
    if($lang == 'ru') {
      if(is_array($location_data) && array_key_exists('city', $location_data) && is_array($location_data['city']) && array_key_exists('name_ru', $location_data['city'])) {
        $location = $location_data['city']['name_ru'];
      }
    }
    if(empty($location)) {
      $location = 'Не определен';
    }
    return $location;
  }

  // == months =================================================================

  $months1 = Array(
    '01' => 'января',
    '02' => 'февраля',
    '03' => 'марта',
    '04' => 'апреля',
    '05' => 'мая',
    '06' => 'июня',
    '07' => 'июля',
    '08' => 'августа',
    '09' => 'сентября',
    '10' => 'октября',
    '11' => 'ноября',
    '12' => 'декабря'
  );

  function form_birthday($the_date) {
    global $months1;
    $l_a = explode('.', $the_date);
    if(count($l_a) != 3) {
      $l_a = explode('-', $the_date);
    }
    return intval($l_a[2]).' '.$months1[$l_a[1]].' '.$l_a[0].' г.';
  }


  // == days of week ===========================================================

  $days_of_week = Array(
    0 => 'monday',
    1 => 'tuesday',
    2 => 'wednesday',
    3 => 'thursday',
    4 => 'friday',
    5 => 'saturday',
    6 => 'sunday'
  );

  $days_of_week_rus = Array(
    0 => 'Понедельник',
    1 => 'Вторник',
    2 => 'Среда',
    3 => 'Четверг',
    4 => 'Пятница',
    5 => 'Суббота',
    6 => 'Воскресенье'
  );

  function is_day_of_week($day_str) {
    global $days_of_week;
    $day_of_week = strtolower($day_str);
    if(in_array($day_of_week, $days_of_week)) {
      return $day_of_week;
    }
    else {
      return false;
    }
  }

  function convert_day($day) {
    $day = $day - 1;
    if($day < 0) $day = $day + 7;
    if($day > 6) $day = $day - 7;
    return $day;
  }

  function get_days_to_next_day_of_week($needle) {
    $today = convert_day(intval(date('w')));
    $diff = $today - $needle;
    if($diff < 0) {
      // this week
      return ($needle - $today);
    }
    else if($diff > 0) {
      // next week
      return (7 - $diff);
    }
    else {
      return 0;
    }
  }

  // == logging ================================================================

  function collect_client_data($type) {
    if($type == 'ip') {
      return $_SERVER['REMOTE_ADDR'];
    }
    if($type == 'location') {
      // API
      return 'Undefined';
    }
    else if($type == 'browser_raw') {
      return $_SERVER['HTTP_USER_AGENT'];
    }
    else if($type == 'country') {
      return get_country_by_ip($_SERVER['REMOTE_ADDR'], 'ru');
    }
    else if($type == 'city') {
      return get_city_by_ip($_SERVER['REMOTE_ADDR'], 'ru');
    }
    else if($type == 'browser_agent') {
      /*$browser = get_browser(null, true);
      return $browser['browser'];*/
      return 'Undefined';
    }
    else if($type == 'browser_version') {
      /*$browser = get_browser(null, true);
      return $browser['version'];*/
      return 'Undefined';
    }
    else if($type == 'platform') {
      /*$browser = get_browser(null, true);
      return $browser['platform'];*/
      return 'Undefined';
    }
    else {
      return 'undefined';
    }
  }

  function prepare_client_data() {
    /*return Array(
      'ip' => collect_client_data('ip'),
      'agent' => collect_client_data('browser_agent'),
      'agent_version' => collect_client_data('browser_version'),
      'platform' => collect_client_data('platform')
    );*/
    return Array(
      'ip' => collect_client_data('ip'),
      'city' => collect_client_data('city'),
      'country' => collect_client_data('country'),
      'agent_raw' => collect_client_data('browser_raw')
    );
  }

  function send_log($id, $action, $details) {
    global $pdo;
    $details = serialize($details);
    $details = htmlentities($details, ENT_HTML5);
    $stmt = $pdo->prepare('INSERT INTO `logs` (`account_id`, `action`, `details`) VALUES (:account_id, :action, :details)');
    if(!$stmt) {
      return false;
    }
    $exec_status = $stmt->execute(Array(
      ':account_id' => $id,
      ':action' => $action,
      ':details' => $details
    ));
    if(!$exec_status) {
      return false;
    }
    return true;
  }

  function log_error_to_file($text, $auto = null) {
    if(!is_null($auto)) {
      $text = date("[d.m.Y H:i:s]").' ERROR IN LINE '.__LINE__.' IN FILE '.__FILE__;
    }
    else {
      $text = date("[d.m.Y H:i:s] ").$text;
    }
    file_put_contents('errorlog.txt', $text."\n", FILE_APPEND);
  }

  // == PDO ====================================================================

  // establish connection
  $pdo_options = Array();
  if($development_state) {
    // development parameters
    $pdo_options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];
  }
  else {
    // release parameters
    $pdo_options = [
      PDO::ATTR_ERRMODE            => PDO::ERRMODE_SILENT,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES   => false,
    ];
  }

  $pdo_dsn = "mysql:host=$sql_host;dbname=$sql_db;charset=$sql_charset";
  $pdo = new PDO($pdo_dsn, $sql_user, $sql_password, $pdo_options);
  $pdo_site_dsn = "mysql:host=$sql_site_host;dbname=$sql_site_db;charset=$sql_site_charset";
  $pdo_site = new PDO($pdo_site_dsn, $sql_site_user, $sql_site_password, $pdo_options);

  // == timers =================================================================

  function timers_check($timer_id, $the_time_limit) {
    global $pdo;
    // check timer
    $timer_exists = false;
    $db_timer = new DateTime();
    $db_timer->format('U = Y-m-d H:i:s');
    $now_timer = new DateTime('now');
    try {
      $stmt = $pdo->prepare('SELECT the_time FROM timers WHERE timer = ?');
      $stmt->execute([$timer_id]);
    }
    catch(Exception $e) {
      return 'ERROR.LINE.'.__LINE__;
    }
    $is_empty = true;
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      $is_empty = false;
      if(isset($row['the_time'])) {
        $timer_exists = true;
        $db_timer->setTimestamp(strtotime($row['the_time']));
      }
      else {
        return 'ERROR.LINE.'.__LINE__;
      }
    }
    if($is_empty) {
      $timer_exists = false;
    }
    if($timer_exists) {
      $difference = $now_timer->getTimestamp() - $db_timer->getTimestamp();
      if($difference < $the_time_limit) {
        return false;
      }
    }

    // add timer
    try {
      $stmt = $pdo->prepare('INSERT INTO `timers` (`timer`) VALUES (:timername) ON DUPLICATE KEY UPDATE `the_time`=CURRENT_TIMESTAMP()');
      $stmt->execute(Array(
        ':timername' => $timer_id
      ));
    }
    catch(Exception $e) {
      return 'ERROR.LINE.'.__LINE__;
    }

    return true;
  }

  // == timers, counters and BAN HAMMER ========================================

  function check_counter($counter_name, $count_limit) {
    global $pdo;
    //
    $counter_exists = false;
    $current_count = 0;
    try {
      $stmt = $pdo->prepare("SELECT the_count FROM counters WHERE counter = ?");
      $stmt->execute([$counter_name]);
    }
    catch(Exception $e) {
      return false;
    }
    $is_empty = true;
    while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
      $is_empty = false;
      if(isset($row['the_count'])) {
        $counter_exists = true;
        $current_count = intval($row['the_count']);
      }
      else {
        return false;
      }
    }
    if($is_empty) {
      $counter_exists = false;
    }
    $current_count++;
    if($counter_exists) {
      if($current_count > $count_limit) {
        return false;
      }
    }
    // add counter
    try {
      $stmt = $pdo->prepare("INSERT INTO `counters` (`counter`) VALUES (:countername) ON DUPLICATE KEY UPDATE `the_count` = :thecount");
      $stmt->execute(Array(
        ':countername' => $counter_name,
        ':thecount' => $current_count
      ));
    }
    catch(Exception $e) {
      return false;
    }
    return true;
  }

  function set_counter($counter_name, $value) {
    global $pdo;
    // set
    try {
      $stmt = $pdo->prepare("INSERT INTO `counters` (`counter`) VALUES (:countername) ON DUPLICATE KEY UPDATE `the_count` = :thecount");
      $stmt->execute(Array(
        ':countername' => $counter_name,
        ':thecount' => $value
      ));
    }
    catch(Exception $e) {
      return false;
    }
    return true;
  }

  function ban_ip($ip, $seconds) {
    global $pdo;
    // add timer
    try {
      $stmt = $pdo->prepare("INSERT INTO `banned_ip` (`ip`, `banned`, `ban_time`) VALUES (:ip, CURRENT_TIMESTAMP(), :ban_time1) ON DUPLICATE KEY UPDATE `banned` = CURRENT_TIMESTAMP(), `ban_time` = :ban_time2");
      $stmt->execute(Array(
        ':ip' => $ip,
        ':ban_time1' => $seconds,
        ':ban_time2' => $seconds
      ));
    }
    catch(Exception $e) {
      return false;
    }
  }

  function is_banned($ip) {
    global $pdo;
    // check record
    try {
      $stmt = $pdo->prepare("SELECT * FROM `banned_ip` WHERE `ip` LIKE ?");
      $stmt->execute([$ip]);
      $row = $stmt->fetch(PDO::FETCH_LAZY);
      if(empty($row)) return false;
    }
    catch(Exception $e) {
      return false;
    }
    // check time
    if((strtotime($row['banned']) + intval($row['ban_time'])) > time()) return true;
    else return false;
  }

  // == server used memory =====================================================

  function finder_how_much_memory_used() {
    global $pdo;
    try {
      $stmt = $pdo->prepare("SELECT `size`, `count` FROM `finder_files` WHERE `path` = '/'");
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_LAZY);
      if(!empty($row)) {
        $size = intval($row['size']);
        $count = intval($row['count']);
        $total_size = $size + ($count * 1631);
        return $total_size;
      }
      else {
        log_error_to_file('<db_includes.php : finder_how_much_memory_used> ERROR IN LINE '.__LINE__);
        return 0;
      }
    }
    catch(Exception $e) {
      log_error_to_file('<db_includes.php : finder_how_much_memory_used> ERROR IN LINE '.__LINE__);
      return 0;
    }
  }

  // ===========================================================================

  function site_Link($page = null){
    global $isHttps;
    if(is_null($page)) $page = '';
    $output = '';
    if($isHttps){
      $output .= 'https://';
    } else{
      $output .= 'http://';
    }
    $output .= $_SERVER['SERVER_NAME'];
    if($_SERVER['SERVER_PORT'] != '80'){
      $output .= ":".$_SERVER['SERVER_PORT'];
    }
    return $output.'/'.$page;
  }

  function num_decline( $number, $titles, $param2 = '', $param3 = '' ){

  	if( $param2 )
  		$titles = [ $titles, $param2, $param3 ];

  	if( is_string($titles) )
  		$titles = preg_split( '/, */', $titles );

  	if( empty($titles[2]) )
  		$titles[2] = $titles[1]; // когда указано 2 элемента

  	$cases = [ 2, 0, 1, 1, 1, 2 ];

  	$intnum = abs( intval( strip_tags( $number ) ) );

  	return $titles[ ($intnum % 100 > 4 && $intnum % 100 < 20) ? 2 : $cases[min($intnum % 10, 5)] ];
  }
?>
