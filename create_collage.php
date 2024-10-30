<?php

function create_collage() {


$query = $_GET['q'];
$reload = $_GET['r'];

$query = preg_replace('/[^a-zA-Z0-9\s|\._,]/', '', $query); //remove all chars except ones we're expecting
$query = preg_replace('/\|/', '/', $query);

$logo_files = explode(",", $query);

//more sanitizing / avoid path busting character combinations
$sanitized_logo_files = array();
foreach ($logo_files as $logo_file) {
	if ($logo_file && preg_match('/^([0-9]+)\/([0-9]+)((_iv)*)\.(jpg|png|gif)$/i', $logo_file)) {
		$sanitized_logo_files[] = dirname ( realpath ( __FILE__ ) ) . "/images/".$logo_file;
	}
}


if (!function_exists('imagecreatetruecolor')) {
	print "Your server does not have the php-gd library/package installed.  Please ask your server administrator to install it.";
	exit;
}



$image_file = dirname ( realpath ( __FILE__ ) ) . "/images/collage.png";
$image_file_web = WP_MEDIA_MENTIONS_DIR . "/images/collage.png";

$collage_rows = floor((count($sanitized_logo_files)-1)/3)+1;
if ($reload || (!file_exists($image_file))) {
//setup master logo image
switch($collage_rows) {
	case '1':
		$masterlogoimg = imagecreatetruecolor("300", "50");
		break;
	case '2':
		$masterlogoimg = imagecreatetruecolor("300", "100");
		break;
	case '3':
		$masterlogoimg = imagecreatetruecolor("300", "150");
		break;
	default:
		$masterlogoimg = imagecreatetruecolor("300", "150");
		break;
}

$white = imagecolorallocate($masterlogoimg, 255, 255, 255);
imagefill($masterlogoimg, 0, 0, $white);

$imgcount = 0;
foreach ($sanitized_logo_files as $slogo_file) {

        $max_height = 48;
        $max_width = 94;
	$width_offset = 100-$max_width;
	$height_offset = 50-$max_height;

        $img_size = getimagesize($slogo_file);
        if (strcasecmp(substr($slogo_file, -3, 3), "png") == 0) {
                $img = imagecreatefrompng($slogo_file);
        } elseif (strcasecmp(substr($slogo_file, -3, 3), "gif") == 0) {
                $img = imagecreatefromgif($slogo_file);
        } else {
                $img = imagecreatefromjpeg($slogo_file);
        }
	imagefilter($img, IMG_FILTER_GRAYSCALE);

        $orig_width = $img_size[0];
        $orig_height = $img_size[1];

        if (($orig_width/$orig_height) <= 1.5) {
          $new_height = $max_height;
          $new_width = round(($orig_width*$max_height)/$orig_height);
        } else {
          $new_width = $max_width;
          $new_height = round(($orig_height*$max_width)/$orig_width);
        }

        $newsize_img = imagecreatetruecolor($max_width, $max_height);
        $white = imagecolorallocate($newsize_img, 255, 255, 255);
        imagefill($newsize_img, 0, 0, $white);

	imagecopyresampled($newsize_img, $img, (round(($max_width-$new_width)/2)), (round(($max_height-$new_height)/2)), 0, 0, $new_width, $new_height, $orig_width, $orig_height);

	imagefilter($newsize_img, IMG_FILTER_GRAYSCALE);

	//copy to place on master logo
	imagecopyresampled($masterlogoimg, $newsize_img, (($imgcount%3)*100)+(round($width_offset/2)), (floor($imgcount/3)*50)+(round($height_offset/2)), 0, 0, $max_width, $max_height, $max_width, $max_height);
//	imagepng($newsize_img, 'images/logo.png');
//	header('Content-Type: image/png');
//	imagepng($newsize_img);
//exit;

//print "$imgcount - y:".(floor($imgcount/3)*50)." x:".(($imgcount%3)*100)." \n";

	//print $slogo_file."\n";
	$imgcount++;

}


//write file to images/collage.png
imagepng($masterlogoimg, $image_file);


} // end if refresh == 1

?>
<div>
<img src="<?php echo $image_file_web; ?>?<?php echo rand(); ?>" width="300"/>
</div>
<?php

}
