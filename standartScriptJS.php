<meta name="viewport" content="width=device-width, initial-scale=1">

<?php
  if(isset($siteData) && isset($siteData['tags'])) {
    echo('<meta name="keywords" content="'.$siteData['tags'].'">'."\n");
  }
  if(isset($siteData) && isset($siteData['description'])) {
    echo('<meta name="description" content="'.$siteData['description'].'">'."\n");
  }
?>

<!-- Favicon (Start) -->
<link rel='shortcut icon' href='media/icons/favicon.ico' type='image/x-icon'/>
<!-- Favicon (End) -->

<!-- Jquery (Start) -->
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<!-- Jquery (End) -->

<script type="text/javascript" src="js/bootstrap.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/config.js"></script>
<script type="text/javascript" src="js/standart.js"></script>
<script type="text/javascript" src="js/style.js"></script>

<!-- Statistics (Start) -->
<script type="application/javascript" src="js/evercookie/evercookie.js"></script>
<script type="application/javascript" src="js/fingerprintjs2/fingerprint2.js"></script>
<script type="text/javascript" src="js/statistics.js"></script>
<!-- Statistics (End) -->

<script type="text/javascript" src="js/learning.js"></script>

<?php
  if(isset($userData)) {
    // set PHP var as JS var
    echo("<script>var userData = new Object();\n");
    foreach($userData as $key => $val) {
      echo("userData['".$key."'] = ".var_export($val, true).";\n");
    }
    echo('</script>');
  }
?>
