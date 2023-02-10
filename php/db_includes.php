<?php
/*
 *  Study Buddy v1.01
 *  (c) 2019-2020 INSOweb  <http://insoweb.ru>
 *  All rights reserved.
 */

  // === setup =================================================================

  $sess_name = 'StudyBuddy';
  $sess_v = '1';

  date_default_timezone_set('Asia/Tashkent');

  // === parameters ============================================================

  $development_state = true;

  // == finder =================================================================

  $bad_filenames_array = Array('con', 'nul', 'prn', 'aux','com1','com2','com3','com4','com5','com6','com7','com8','com9','lpt1','lpt2','lpt3','lpt4','lpt5','lpt6','lpt7','lpt8','lpt9');

  // === basic =================================================================

  // destroy session
  function remove_user_session() {
    $token = false;
    if(isset($_SESSION['act_token'])) $token = $_SESSION['act_token'];
    $_SESSION = Array();
    session_destroy();
    return $token;
  }

  function gen_token($length = null) {
    if(is_null($length)) $length = 32;
    if(function_exists('random_bytes')) {
      return bin2hex(random_bytes($length));
    }
    if(function_exists('mcrypt_create_iv')) {
      return bin2hex(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
    }
    if(function_exists('openssl_random_pseudo_bytes')) {
      return bin2hex(openssl_random_pseudo_bytes($length));
    }
  }

  function create_default_session() {
    global $sess_name;
    session_name($sess_name);
    if(session_status()!=PHP_SESSION_ACTIVE) @session_start();
    if(!isset($_SESSION) || !isset($_SESSION['act_token'])) {
      $_SESSION['act_token'] = gen_token(32);
    }
  }

  // === logging ===============================================================

  function debuglog($text = null, $custom_f = null, $need_args = null) {
    if($text === true || is_null($text)) {
      foreach(debug_backtrace() as $backtrace) {
        $b_file = $backtrace['file'];
        $b_line = $backtrace['line'];
        $b_func = is_null($custom_f) ? $backtrace['function'] : $custom_f;
        $b_args = is_null($need_args) ? '' : '('.strval(implode(', ', $backtrace['args'])).')';
        $text = date("[d.m.Y H:i:s] <$b_file : $b_func> ERROR IN LINE $b_line $b_args \n");
      }
    }
    else {
      $text = date("[d.m.Y H:i:s] ").$text."\n";
    }
    file_put_contents('debug.txt', $text, FILE_APPEND);
  }

  function debuglog2($text = null, $mode = null, $custom_f = null) {
    if(is_null($text)) {
      $text = '';
    }
    if(!is_null($mode)) {
      $backtrace = debug_backtrace()[0];
      if(is_null($custom_f)) {
        $custom_f = $backtrace['function'];
      }
      $b_file = $backtrace['file'];
      $b_line = $backtrace['line'];
      $text = date("[d.m.Y H:i:s] <$b_file : $b_func> IN LINE $b_line >>> $text \n");
    }
    else {
      $text = date("[d.m.Y H:i:s] ").$text."\n";
    }
    file_put_contents('debug.txt', $text, FILE_APPEND);
  }

  function debuglog3($text = null) {
    $data = debug_backtrace();
    print_r($data);
  }

  // === PDO ===================================================================

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

  // == location ===============================================================

  require_once('sxgeo/SxGeo.php');
  $sx_geo_city_db_path = 'sxgeo/SxGeoCity.dat';
  $sx_geo_country_db_path = 'sxgeo/SxGeoCountry.dat';

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
    'RU' => 'Российская Федерация',
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
    if($lang == 'en') {
      if(array_key_exists($code, $_ISO_EN)) {
        $location = $_ISO_EN[$code];
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
    if($lang == 'en') {
      if(is_array($location_data) && array_key_exists('city', $location_data) && is_array($location_data['city']) && array_key_exists('name_en', $location_data['city'])) {
        $location = $location_data['city']['name_en'];
      }
    }
    if(empty($location)) {
      $location = 'Не определен';
    }
    return $location;
  }

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

  // === gender by name ========================================================

  $names1_male_array = Array('аарон', 'аббас', 'абдуллах', 'абид', 'аботур', 'аввакум', 'август', 'авдей', 'авель', 'авигдор', 'авирмэд', 'авксентий', 'авл', 'авнер', 'аврелий', 'автандил', 'автоном', 'агапит', 'агафангел', 'агафодор', 'агафон', 'аги', 'агриппа', 'адам', 'адам', 'адар', 'адиль', 'адольф', 'адонирам', 'адриан', 'азамат', 'азат', 'азиз', 'азим', 'айварс', 'айдар', 'акакий', 'аквилий', 'акиф', 'акоп', 'аксель', 'алан', 'алан', 'аланус', 'александр', 'алексей', 'алемдар', 'алик', 'алим', 'алипий', 'алишер', 'алоиз', 'альберик', 'альберт', 'альбин', 'альваро', 'альвиан', 'альвизе', 'альфонс', 'альфред', 'амадис', 'амвросий', 'амедей', 'амин', 'амир', 'амр', 'анания', 'анас', 'анастасий', 'анатолий', 'андокид', 'андрей', 'андроник', 'аникита', 'аннерс', 'анри', 'ансельм', 'антипа', 'антон', 'антоний', 'антонин', 'антуан', 'арам', 'арефа', 'арзуман', 'аристарх', 'ариф', 'аркадий', 'арсен', 'арсений', 'артем', 'артемий', 'артур', 'арфаксад', 'архипп', 'атанасий', 'аттик', 'афанасий', 'афинагор', 'афиней', 'африкан', 'ахилл', 'ахмад', 'ахтям', 'ашот', 'бадр', 'барни', 'бартоломео', 'басир', 'бахтияр', 'бен', 'бехруз', 'билял', 'богдан', 'болеслав', 'болот', 'бонавентура', 'борис', 'борислав', 'боян', 'бронислав', 'брячислав', 'булат', 'бурхан', 'бямбасурэн', 'вадим', 'валентин', 'валерий', 'валерьян', 'вальдемар', 'вангьял', 'варлам', 'варнава', 'варфоломей', 'василий', 'вася', 'вахтанг', 'велвел', 'венансио', 'вениамин', 'венцеслав', 'верослав', 'викентий', 'виктор', 'викторин', 'вильгельм', 'винцас', 'виссарион', 'виталий', 'витаутас', 'вито', 'владимир', 'владислав', 'владлен', 'влас', 'волк', 'володарь', 'вольфганг', 'вописк', 'всеволод', 'всеслав', 'вук', 'вукол', 'вышеслав', 'вячеслав', 'габриеле', 'гавриил', 'гай', 'галактион', 'гарет', 'гаспар', 'гафур', 'гвидо', 'гейдар', 'геласий', 'гельмут', 'геннадий', 'генри', 'генрих', 'георге', 'георгий', 'гераклид', 'герберт', 'герман', 'германн', 'геронтий', 'герхард', 'гильем', 'гинкмар', 'глеб', 'гней', 'горацио', 'гордей', 'гостомысл', 'градислав', 'григорий', 'гримоальд', 'гуго', 'гьялцен', 'давид', 'далер', 'дамдинсурэн', 'дамир', 'данакт', 'даниил', 'дарий', 'демид', 'демьян', 'денис', 'децим', 'джаббар', 'джамиль', 'джанер', 'джанфранко', 'джаррах', 'джафар', 'джейкоб', 'джихангир', 'джованни', 'джон', 'джулиус', 'диодор', 'дмитрий', 'доминик', 'дональд', 'донат', 'дорофей', 'досифей', 'еварест', 'евгений', 'евдоким', 'евсей', 'евстафий', 'евтихиан', 'евтихий', 'евферий', 'егор', 'елеазар', 'елисей', 'емельян', 'ерванд', 'еремей', 'ермак', 'ермолай', 'ернар', 'ерофей', 'ефим', 'ефрем', 'жан', 'ждан', 'жером', 'жоан', 'жюль', 'зайнуддин', 'захар', 'захария', 'збигнев', 'зденек', 'зейналабдин', 'зеэв', 'зигмунд', 'зия', 'золтан', 'зосима', 'иан', 'ибрагим', 'ибрахим', 'иван', 'ивар', 'игнатий', 'игорь', 'иероним', 'иерофей', 'израиль', 'икрима', 'иларий', 'илларион', 'ильфат', 'илья', 'иоаким', 'иоанн', 'иоанникий', 'иоахим', 'иов', 'иоганнес', 'ионафан', 'иосафат', 'ираклий', 'иржи', 'иринарх', 'ириней', 'иродион', 'иса', 'иса', 'исаак', 'исаия', 'ислам', 'исмаил', 'истислав', 'истома', 'истукарий', 'иуда', 'иулиан', 'иштван', 'кадваллон', 'кадир', 'казимир', 'каликст', 'калин', 'каллистрат', 'кальман', 'камран', 'карен', 'картерий', 'касим', 'кассиан', 'кассий', 'касторий', 'квинт', 'кехлер', 'киллиан', 'ким', 'кир', 'кириак', 'кирилл', 'клаас', 'клавдиан', 'клеоник', 'климент', 'кондрат', 'конон', 'конрад', 'константин', 'корнелиус', 'корнилий', 'коррадо', 'косьма', 'кратипп', 'криспин', 'кристиан', 'кронид', 'кузьма', 'куприян', 'курбан', 'курт', 'кутлуг-буга', 'кэлин', 'лаврентий', 'лаврентий', 'лавс', 'ладислав', 'лазарь', 'лайл', 'лампрехт', 'ландульф', 'лев', 'левенте', 'леви', 'ленни', 'леонид', 'леонтий', 'леонхард', 'лиам', 'линкей', 'логгин', 'лоренц', 'лоренцо', 'луи', 'луитпольд', 'лука', 'лукий', 'лукьян', 'луций', 'людовик', 'люцифер', 'майнхард', 'макар', 'макарий', 'максим', 'максимиан', 'максимилиан', 'малик', 'малх', 'мамбет', 'маний', 'маноле', 'мануил', 'мануэль', 'мариан', 'марк', 'маркел', 'мартын', 'марчелло', 'матвей', 'матео', 'матиас', 'матфей', 'матфий', 'махмуд', 'меир', 'мелентий', 'мелитон', 'менахем', 'мендель', 'месроп', 'мефодий', 'мечислав', 'мика', 'микулаш', 'милорад', 'милутин', 'мина', 'мирко', 'мирон', 'митрофан', 'михаил', 'михей', 'младан', 'модест', 'моисей', 'мордехай', 'мстислав', 'мурад', 'мухаммед', 'мэдисон', 'мэлс', 'назар', 'наиль', 'насиф', 'натан', 'натаниэль', 'наум', 'нафанаил', 'нацагдорж', 'нестор', 'никандр', 'никанор', 'никита', 'никифор', 'никодим', 'николай', 'нил', 'нильс', 'ноа', 'ной', 'норд', 'оге', 'одинец', 'октавий', 'олаф', 'оле', 'олег', 'оливер', 'ольгерд', 'онисим', 'онуфрий', 'орест', 'осип', 'оскар', 'осман', 'оттон', 'очирбат', 'пабло', 'павел', 'павлин', 'павсикакий', 'паисий', 'палладий', 'панкратий', 'пантелеймон', 'папа', 'паруйр', 'парфений', 'патрик', 'пафнутий', 'пахомий', 'педро', 'петр', 'пимен', 'пинхас', 'пипин', 'питирим', 'платон', 'пол', 'полидор', 'полиевкт', 'поликарп', 'поликрат', 'порфирий', 'потап', 'предраг', 'премысл', 'пров', 'прокл', 'прокул', 'протасий', 'прохор', 'публий', 'рагнар', 'рагуил', 'радмир', 'радослав', 'раймонд', 'рамадан', 'рамазан', 'рамиль', 'ратмир', 'рахман', 'рашад', 'рашид', 'рейнхард', 'ренат', 'реститут', 'ричард', 'роберт', 'родерик', 'родион', 'рожер', 'розарио', 'роман', 'рон', 'ронан', 'ростислав', 'рудольф', 'руслан', 'руф', 'руфин', 'рушан', 'рюрик', 'сабит', 'сабриэль', 'савва', 'савватий', 'савелий', 'савин', 'саддам', 'садик', 'саид', 'салават', 'салих', 'саллюстий', 'салман', 'самуил', 'сармат', 'сасоний', 'святослав', 'северин', 'секст', 'секунд', 'семен', 'септимий', 'серапион', 'сергей', 'серж', 'сигеберт', 'сила', 'сильвестр', 'симеон', 'симон', 'созон', 'соломон', 'сонам', 'софрон', 'спиридон', 'срджан', 'станислав', 'степан', 'стефано', 'стивен', 'сулейман', 'сфенел', 'таврион', 'тавус', 'тагир', 'тадеуш', 'тарас', 'тарасий', 'теймураз', 'тейс', 'тендзин', 'терентий', 'терри', 'тиберий', 'тигран', 'тимофей', 'тимур', 'тимур', 'тихомир', 'тихон', 'томоми', 'торос', 'тофик', 'трифон', 'тудхалия', 'тутмос', 'тьерри', 'уве', 'уильям', 'улдис', 'ульрих', 'ульф', 'умар', 'урбан', 'урызмаг', 'усама', 'усман', 'фавст', 'фаддей', 'фадлалла', 'фарид', 'фахраддин', 'федериго', 'федор', 'федосей', 'федот', 'фейсал', 'феликс', 'феогност', 'феоктист', 'феофан', 'феофил', 'феофилакт', 'фердинанд', 'ференц', 'фидель', 'филарет', 'филип', 'филипп', 'философ', 'филострат', 'фока', 'фома', 'фотий', 'франц', 'франческо', 'фредерик', 'фридрих', 'фродо', 'фрол', 'фульк', 'хайме', 'ханс', 'харальд', 'харитон', 'хасан', 'хетаг', 'хильдерик', 'хирам', 'хлодвиг', 'хокон', 'хорив', 'хоселито', 'хосрой', 'хотимир', 'хрисанф', 'христофор', 'цэрэндорж', 'чеслав', 'шалом', 'шамиль', 'шамсуддин', 'шапур', 'шарль', 'шейх-хайдар', 'шон', 'эберхард', 'эвандр', 'эдмунд', 'эдна', 'эдуард', 'элбэгдорж', 'элджернон', 'элиас', 'эллиот', 'эмиль', 'энрик', 'энрико', 'энтони', 'эразм', 'эрик', 'эрик', 'эрнст', 'эстебан', 'этьен', 'ювеналий', 'юлиан', 'юлий', 'юлиус', 'юрген', 'юрий', 'юстин', 'юхан', 'яков', 'якуб', 'ян', 'яни', 'януарий', 'яромир', 'ярополк', 'ярослав');

  $names1_female_array = Array('ава', 'августа', 'агата', 'агафья', 'агнес', 'агнесса', 'агния', 'аделаида', 'аделина', 'адриенна', 'азиза', 'аида', 'алдона', 'алевтина', 'александра', 'алима', 'алина', 'алиса', 'алия', 'алла', 'альбина', 'аманда', 'амина', 'амира', 'анаис', 'анастасия', 'ангелина', 'анжела', 'анжелика', 'анисия', 'анна', 'антонина', 'анфиса', 'аполлинария', 'аполлония', 'ассоль', 'аурелия', 'бажена', 'беата', 'беатриса', 'белла', 'божена', 'валентина', 'валерия', 'ванда', 'варвара', 'василиса', 'вася', 'венди', 'вера', 'вероника', 'виктория', 'виолетта', 'галатея', 'галина', 'глафира', 'гликерия', 'гоар', 'говхар', 'горица', 'гульмира', 'гульнара', 'гуннхильда', 'гюльджан', 'дана', 'дарерка', 'дарья', 'дебора', 'джанет', 'дженифер', 'дженна', 'джоан', 'джулия', 'диана', 'дина', 'динора', 'дита', 'домна', 'дора', 'доротея', 'ева', 'евгения', 'евдокия', 'евдоксия', 'евлалия', 'евлампия', 'евпраксия', 'екатерина', 'елена', 'елизавета', 'епистима', 'ермиония', 'женевьева', 'забава', 'зинаида', 'зоя', 'зульфия', 'ивета', 'илона', 'ильзе', 'инга', 'инес', 'инна', 'иоанна', 'ираида', 'ирина', 'ирма', 'иулия', 'ия', 'йенни', 'камилла', 'камиля', 'карен', 'карина', 'каролина', 'ким', 'кира', 'клавдия', 'клара', 'клементина', 'констанция', 'консуэло', 'корнелия', 'кристина', 'ксения', 'лада', 'лана', 'лаодика', 'лариса', 'лаура', 'лейла', 'леля', 'лидия', 'лина', 'линнея', 'лия', 'лора', 'лукия', 'любовь', 'людмила', 'людовика', 'магали', 'магдалина', 'мадина', 'майя', 'мальвина', 'маргарет', 'маргарита', 'марианна', 'марина', 'мариса', 'марисоль', 'мария', 'марлен', 'марфа', 'мастридия', 'матрена', 'мафтуха', 'мелания', 'мелисса', 'меропа', 'мерседес', 'милица', 'миранда', 'мирей', 'миропия', 'мирослава', 'михримах', 'мэдисон', 'мэри', 'мю', 'надежда', 'наджия', 'надия', 'назгуль', 'наиля', 'наоми', 'наталья', 'невена', 'нелли', 'неонилла', 'ника', 'николетта', 'нилуфар', 'нинель', 'ноа', 'нонна', 'нора', 'нэнси', 'одетта', 'октябрина', 'олимпиада', 'ольга', 'павла', 'павлина', 'параскева', 'пинна', 'полина', 'прасковья', 'прити', 'рада', 'раиса', 'рамина', 'раминта', 'рамона', 'ребекка', 'ревекка', 'римма', 'роза', 'розалия', 'розалия', 'рос', 'росарио', 'рукайя', 'руфина', 'рушан', 'сабина', 'саида', 'салиха', 'саманта', 'сандра', 'сара', 'светлана', 'серафима', 'сибилла', 'сильвия', 'синклитикия', 'синтия', 'смиляна', 'снежана', 'сона-ханум', 'соня', 'софия', 'стелла', 'степанида', 'стефания', 'тавус', 'тамара', 'танзиля', 'тарья', 'татьяна', 'тахмина', 'томоми', 'ульяна', 'урсула', 'урсула', 'фаина', 'фарангис', 'фатима', 'феба', 'фейт', 'фекла', 'фелисити', 'феодосия', 'феофания', 'фива', 'фила', 'филлида', 'фотина', 'франческа', 'фрида', 'ханнелора', 'хатидже', 'хафса', 'хильдегарда', 'хильдур', 'цветана', 'целестина', 'цецилия', 'чулпан', 'шарлотта', 'шейла', 'шерил', 'шорена', 'эдита', 'эдна', 'элеонора', 'элла', 'эллен', 'эльвира', 'эльмира', 'эми', 'эмилия', 'эмма', 'эрвина', 'эрика', 'эрин', 'эрна', 'этель', 'юлия', 'юния', 'яна', 'ярослава');

  function get_gender_by_name($name1, $name2, $name3) {
    global $names1_male_array;
    global $names1_female_array;
    $name1 = mb_strtolower($name1);
    $name2 = mb_strtolower($name2);
    $name3 = mb_strtolower($name3);
    $gender = 0;
    // by name1
    if(mb_strlen($name1) > 0) {
      if(in_array($name1, $names1_male_array)) $gender++;
      if(in_array($name1, $names1_female_array)) $gender--;
    }
    // by name2
    if(mb_strlen($name2) > 0) {
      if(mb_substr($name2, -1) == 'а') {
        $gender--;
      }
      else {
        $gender++;
      }
    }
    // by name3
    if(mb_strlen($name3) > 0) {
      $l1 = mb_substr($name3, -1);
      $l2 = mb_substr($name3, -2);
      if($l1 == 'ч' || $l2 == 'лы') {
        $gender++;
      }
      if($l1 == 'а' || $l2 == 'зы') {
        $gender--;
      }
    }
    if($gender >= 0) {
      return 'male';
    }
    else {
      return 'female';
    }
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

?>
