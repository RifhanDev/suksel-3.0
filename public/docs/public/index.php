<?php
define('ROOT_DIR', realpath(dirname(__FILE__)) .'/');
define('CONTENT_DIR', ROOT_DIR .'content/'); //change this to change which folder you want your content to be stored in

// Change this to your strapdown.js location before using! Edit the theme tag below to use different Bootswatch themes.
// It is recommended that you serve strapdown.js locally, rather than from strapdownjs' website:
$strapdown_location = "strapdown.js";
// $strapdown_location = "http://strapdownjs.com/v/0.2/strapdown.js";
$bootswatch_theme = "cerulean"; //choose any bootstrap theme included in strapdown.js!
$file_format = ".md"; //change this to choose a file type, be sure to include the period
// Get request url and script url
$url = '';
$request_url = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';///myovr/public/docs/public/
$script_url  = (isset($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : '';///myovr/public/docs/public/index.php

// Get our url path and trim the / of the left and the right
if($request_url != $script_url) $url = trim(preg_replace('/'. str_replace('/', '\/', str_replace('index.php', '', $script_url)) .'/', '', $request_url, 1), '/');

// Get the file path
if($url) $file = strtolower(CONTENT_DIR . $url);
else $file = CONTENT_DIR .'register';

// Load the file
if(is_dir($file)) $file = CONTENT_DIR . $url .'/index' . $file_format;
else $file .=  $file_format;

// Show 404 if file cannot be found
if(file_exists($file)) $content = file_get_contents($file);
else $content = file_get_contents(CONTENT_DIR .'404' . $file_format);

?>
<!DOCTYPE html>
<html>
  <link rel="stylesheet" href="bootstrap-cerulean-flatly-slate.css">
  <script src="jquery-2.1.0.min.js"></script>
  <script src="bootstrap.min.js"></script>
<head>
<title>eTender Manual</title>
<nav id="autohide_navbar" class="navbar navbar-default navbar-fixed-top">
  <div class="navbar-header">
    <a class="navbar-brand" href="<?php echo 'register'; ?>">eTENDER SELANGOR</a>
  </div>
    <div class="navbar-collapse collapse navbar-responsive-collapse">
    <ul class="nav navbar-nav">    
        <li><a href="<?php echo 'register'; ?>">PENDAFTARAN SYARIKAT</a></li>
        <li><a href="<?php echo 'register_approval'; ?>">PENGESAHAN</a></li>
        <li><a href="<?php echo 'vendor_payment_register'; ?>">PEMBAYARAN PENDAFTARAN</a></li>
        </ul>
      </li>
    </ul>
  </div>
</nav>
</head>
<xmp theme="<?php echo $bootswatch_theme; ?>" style="display:none;">
<?php echo $content; ?>
</xmp>
<script src="<?php echo $strapdown_location; ?>"></script>
<body>

</body>
</html>