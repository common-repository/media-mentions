<?php

function get_screenshot() {

$url = $_GET['q'];
$url_num = $_GET['n'];
$reload = $_GET['r'];

$url = filter_var($url, FILTER_SANITIZE_URL);
$url = filter_var($url, FILTER_VALIDATE_URL);
if (!$url) { echo 'invalid URL - prefix with http://'; exit; }

$mmurl = "http://mm1.mediamentionsplugin.com/mm/get_screenshot.php?q=".$url;

$url_num = filter_var($url_num, FILTER_SANITIZE_NUMBER_INT);
$url_num = filter_var($url_num, FILTER_VALIDATE_INT, array("defulat"=>1, "min_range"=>1, "max_range"=>9));
if (!$url_num) { echo 'invalid URL num'; exit; }

$reload = filter_var($reload, FILTER_SANITIZE_NUMBER_INT);
$reload = filter_var($reload, FILTER_VALIDATE_INT, array("default"=>0, "min_range"=>0, "max_range"=>1));


$image_file = dirname ( realpath ( __FILE__ ) ) . "/images/screenshot".$url_num.".png";
$image_file_thumb = dirname ( realpath ( __FILE__ ) ) . "/images/screenshot".$url_num."t.png";
$image_file_thumb_web = WP_MEDIA_MENTIONS_DIR . "/images/screenshot".$url_num."t.png";

if ($reload) {
//if (!file_exists($image_file)) {
$img = @file_get_contents ($mmurl);
if($img === false){
        $err = error_get_last();
        if(is_array($err) && $err['message']){
                $lastURLError = $err['message'];
        } else {
                $lastURLError = $err;
        }
        exit;
}


if(! file_put_contents($image_file, $img)){
        print "Could not write to file.";
        exit;
}

//write smaller version too
$imgt = imagecreatefromstring($img);
$imgtoutput = imagecreatetruecolor(200, 164);
$white = imagecolorallocate($imgtoutput, 255, 255, 255);
imagefill($imgtoutput, 0, 0, $white);
imagecopyresampled($imgtoutput, $imgt, 0, 0, 0, 0, 200, 164, 1248, 1024);
imagepng($imgtoutput, $image_file_thumb);



//}
}

?>
<div>
<img src="<?php echo $image_file_thumb_web; ?>?<?php echo rand(); ?>" width="200"/>
</div>
<?php

}
