<?php

function get_resources() {

if ( ! defined( 'DS' ) )
    define( 'DS', '/' );

$url = $_GET['q'];
$url_num = $_GET['n'];
$reload = $_GET['r'];
$gropt1 = $_GET['gr1']; //blog name
$gropt2 = $_GET['gr2']; //blog url w/ http:// on the front

$gropt1 = preg_replace('/[^a-zA-Z0-9\s]/', '', $gropt1);

$url = filter_var($url, FILTER_SANITIZE_URL);
$url = filter_var($url, FILTER_VALIDATE_URL);
if (!$url) { echo 'invalid URL - prefix with http://'; exit; }

$gropt2 = filter_var($gropt2, FILTER_SANITIZE_URL);
$gropt2 = filter_var($gropt2, FILTER_VALIDATE_URL);
if (!$gropt2) { echo 'invalid blog URL'; exit; }

//$gropt2_parts = parse_url($gropt2);
//$gropt2 = $gropt2_parts['host'];

$mmurl = "http://mm2.mediamentionsplugin.com/mm/get_resources.php?q=".$url."&gr1=".$gropt1."&gr2=".$gropt2;

$url_num = filter_var($url_num, FILTER_SANITIZE_NUMBER_INT);
$url_num = filter_var($url_num, FILTER_VALIDATE_INT, array("defulat"=>1, "min_range"=>1, "max_range"=>9));
if (!$url_num) { echo 'invalid URL num'; exit; }

$reload = filter_var($reload, FILTER_SANITIZE_NUMBER_INT);
$reload = filter_var($reload, FILTER_VALIDATE_INT, array("default"=>0, "min_range"=>0, "max_range"=>1));


$resources_dir = dirname ( realpath ( __FILE__ ) ) . "/images/$url_num/";
$resources_dir_web = WP_MEDIA_MENTIONS_DIR . "/images/$url_num/";


if ($reload) {

//delete old resources

delete_folder_files($resources_dir);

//get new resources
$resources = @file_get_contents($mmurl);
if($resources === false){
        $err = error_get_last();
        if(is_array($err) && $err['message']){
                $lastURLError = $err['message'];
        } else {
                $lastURLError = $err;
        }
        exit;
}

$resourcedata = json_decode($resources);
$resources = $resourcedata->resourcedata->resources;
$citations = $resourcedata->resourcedata->citations;
//print_r($resources);
//exit;

$ccount = 1;
foreach ($citations as $citation) {
	if (strip_tags($citation)) {
?>
<div id="citation<?php echo $ccount; ?>" class="citation" style="display:none;"><?php echo strip_tags($citation); ?></div>

<?php
		$ccount++;
	}
}

$rcount = 1;
foreach ($resources as $resource) {


  $user_agent = 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.202 Safari/535.1';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $resource);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
  curl_setopt($ch, CURLOPT_REFERER, $url);
  $current_resource = curl_exec($ch);

  //check to make sure it's an image (jpg, png, gif)
  switch(curl_getinfo($ch, CURLINFO_CONTENT_TYPE)) {
    case 'image/gif': 
      $ext = '.gif';
      break;
    case 'image/png': 
      $ext = '.png';
      break;
    case 'image/jpeg': 
      $ext = '.jpg';
      break;
    default:
      $ext = '';
      break;
  }

  if ($ext != "") {
    $image_file = $resources_dir.$rcount.$ext;
    if (!file_put_contents($image_file, $current_resource)){
          print "Could not write to file: $rcount";
    }

    $imginfo = getimagesize($image_file);

    //write b/w version
    $image_file = $resources_dir.$rcount."_bw".$ext;
    $imgbw = imagecreatefromstring($current_resource);
    imagefilter($imgbw, IMG_FILTER_GRAYSCALE);

    $imgtoutput = imagecreatetruecolor($imginfo[0], $imginfo[1]);
    $white = imagecolorallocate($imgtoutput, 255, 255, 255);
    imagefill($imgtoutput, 0, 0, $white);
    imagecopyresampled($imgtoutput, $imgbw, 0, 0, 0, 0, $imginfo[0], $imginfo[1], $imginfo[0], $imginfo[1]);
    imagefilter($imgtoutput, IMG_FILTER_GRAYSCALE);


    //write inverted b/w version
    $image_file_iv = $resources_dir.$rcount."_iv".$ext;
    $imgiv = imagecreatefromstring($current_resource);
    imagefilter($imgiv, IMG_FILTER_GRAYSCALE);
    imagefilter($imgiv, IMG_FILTER_NEGATE);

    $imgtoutputiv = imagecreatetruecolor($imginfo[0], $imginfo[1]);
    $white = imagecolorallocate($imgtoutputiv, 255, 255, 255);
    imagefill($imgtoutputiv, 0, 0, $white);
    imagecopyresampled($imgtoutputiv, $imgiv, 0, 0, 0, 0, $imginfo[0], $imginfo[1], $imginfo[0], $imginfo[1]);
    imagefilter($imgtoutputiv, IMG_FILTER_GRAYSCALE);


    switch($ext) {
      case '.gif': 
        imagegif($imgtoutput, $image_file);
        imagegif($imgtoutputiv, $image_file_iv);
        break;
      case '.png': 
        imagepng($imgtoutput, $image_file);
        imagepng($imgtoutputiv, $image_file_iv);
        break;
      case '.jpeg': 
        imagejpeg($imgtoutput, $image_file);
        imagejpeg($imgtoutputiv, $image_file_iv);
        break;
      default:
        imagejpeg($imgtoutput, $image_file);
        imagejpeg($imgtoutputiv, $image_file_iv);
        break;
    }


  $rcount++;
  }

}
} //end $refresh

if ($handle = opendir($resources_dir)) {
?>
<style type="text/css">
.image-container img {
    display:block;
    max-width:175px;
    max-height:175px;
    padding:5px;
    border:2px double #000;
    margin:auto;
 }
</style>
<?php
    while (false !== ($file = readdir($handle))) {
      if (preg_match("/(png|jpg|gif)/i", $file) && !preg_match("/_bw/i", $file)) {
        //echo "$file\n";
?>

<a href="#<?php echo $file; ?>"/>
<div class="image-container" name="<?php echo $url_num; ?>|<?php echo $file; ?>">
<div style="margin: 0 0 5px 0;">
<img src="<?php echo $resources_dir_web . $file; ?>?<?php echo rand(); ?>" />
</div>
</div>
<?php

      }
    }

}






/*
function delete_folder_files($tmp_path){
  if(!is_writeable($tmp_path) && is_dir($tmp_path)){chmod($tmp_path,0777);}
    $handle = opendir($tmp_path);
  while($tmp=readdir($handle)){
    if($tmp!='..' && $tmp!='.' && $tmp!=''){
        unlink($tmp_path.DS.$tmp);
    }
  }
  closedir($handle);
}
*/



}
