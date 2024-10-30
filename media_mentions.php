<?php
/*
Plugin Name: Media Mentions
Plugin URI: http://mediamentionsplugin.com
Description: Show off your blog's media mentions and display a collage of logos from those websites.
Version: 1.1
Author: Kyle Johnson
Author URI: http://mediamentionsplugin.com
License: GPL2
*/

/*  Copyright 2011  Kyle Johnson  (email : admin@mediamentionsplugin.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'DS' ) )
    define( 'DS', '/' );
if ( ! defined( 'WP_CONTENT_URL' ) )
    define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
    define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
    define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
    define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

define( 'MM_WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins/media-mentions' );
define( 'WP_MEDIA_MENTIONS_DIR', WP_CONTENT_URL . '/plugins/media-mentions' );

//require_once( WP_PLUGIN_DIR . '/media-mentions/media_mentions.class.php');

global $media_mentions_db_version;
$media_mentions_db_version = "1.0";
global $media_mentions_display_text;
$media_mentions_display_text = "<h3>As seen on:</h3>";

global $media_mentions_options;
$media_mentions_options = get_option('media_mentions_options');
//$media_mentions_options['media_mentions_db_version'] = get_option('media_mentions_db_version');
//$media_mentions_options['media_mentions_enabled'] = get_option('media_mentions_enabled');
//$media_mentions_options['media_mentions_display_text'] = get_option('media_mentions_display_text');

register_activation_hook(__FILE__,'media_mentions_install');
///register_activation_hook(__FILE__,'media_mentions_install_data');




///BEGIN//////////////////////////////////////////////////////////////////////////////////////////////////
///////// only activate the virtual /media-mentions page if the activated radio button is on /////////////


add_action( 'init', 'mmv1redirect_init_internal' );
function mmv1redirect_init_internal()
{
       global $wp;
       global $media_mentions_options;

	if ($media_mentions_options['media_mentions_enabled']) {
	       $wp->add_query_var('media_mentions_detail');
	       add_rewrite_rule('media-mentions/?$', get_option( 'siteurl' ) . 'index.php?media_mentions_detail=1', 'top' );
	}

	//if ( !current_user_can('level_10') ) {
	if ( current_user_can('manage_options') ) {
	//Only administrators are able to configure and use Media Mentions functions
		if ($_GET['create_collage'] == '1') {
			include('create_collage.php');
			create_collage();
			exit;
		}

		if ($_GET['get_resources'] == '1') {
			include('get_resources.php');
			get_resources();
			exit;
		}

		if ($_GET['get_screenshot'] == '1') {
			include('get_screenshot.php');
			get_screenshot();
			exit;
		}
	}


}


if ($media_mentions_options['media_mentions_enabled']) {


add_action ('wp', 'mm_make_page');

function mm_make_page() {
	global $wp_query, $post_type;
	if (get_query_var('media_mentions_detail') == 1) {
		$wp_query->is_page = true;
		$wp_query->is_singular = true;
		$wp_query->is_home = false;
		$wp_query->is_single = false;
		$wp_query->post_count = 1;
		$wp_query->found_posts = 0;
		$wp_query->max_num_pages = 0;
		$wp_query->query = array("page"=>"", "pagename"=>"media-mentions");

		$wp_query->post->ID = "1";
		$wp_query->post->post_author = 1;
		$wp_query->post->post_date = "2011-02-23 05:38:21";
		$wp_query->post->post_date_gmt = "2011-02-23 05:38:21";
		$wp_query->post->post_content = "Some mentions...";
		$wp_query->post->post_title = "Media Mentions";
		$wp_query->post->post_excerpt = "";
		$wp_query->post->post_status = "publish";
		$wp_query->post->comment_status = "closed";
		$wp_query->post->ping_status = "closed";
		$wp_query->post->post_password = "";
		$wp_query->post->post_name = "media-mentions";
		$wp_query->post->to_ping = "";
		$wp_query->post->pinged = "";
		$wp_query->post->post_modified = "2011-02-26 21:50:21";
		$wp_query->post->post_modified_gmt = "2011-02-26 21:50:21";
		$wp_query->post->post_content_filtered = "";
		$wp_query->post->post_parent = 0;
		$wp_query->post->guid = "";
		$wp_query->post->menu_order = 0;
		$wp_query->post->post_type = "page";
		$wp_query->post->post_mime_type = "";
		$wp_query->post->comment_count = 0;
		$wp_query->post->ancestors = array();
		$wp_query->post->filter = "raw";


/*
 [post] => stdClass Object
        (
            [ID] => 21
            [post_author] => 1
            [post_date] => 2011-02-23 05:38:21
            [post_date_gmt] => 2011-02-23 05:38:21
            [post_content] => post content
            [post_title] => Post Title
            [post_excerpt] => 
            [post_status] => publish
            [comment_status] => open
            [ping_status] => open
            [post_password] => 
            [post_name] => post-title
            [to_ping] => 
            [pinged] => 
            [post_modified] => 2011-02-26 21:50:21
            [post_modified_gmt] => 2011-02-26 21:50:21
            [post_content_filtered] => 
            [post_parent] => 0
            [guid] => http://mediamentionsplugin.com/?page_id=21
            [menu_order] => 0
            [post_type] => page
            [post_mime_type] => 
            [comment_count] => 0
            [ancestors] => Array
                (
                )

            [filter] => raw
        )
*/

	}
}



add_action( 'template_redirect', 'mmv1redirect_parse_request' );
function mmv1redirect_parse_request( )
{
       if (get_query_var('media_mentions_detail') == 1) {


     add_filter('the_content', 'replace_content');
//     add_filter('the_title', 'replace_title');
//     add_filter('post_limits', 'limit_1_post' );
     add_action('template_redirect', 'replace_template');

    }
    return;
}


function replace_content($text) {
  global $media_mentions_options;
  $mycontent = "";

  $pcount = 1;
  for ($i = 1; $i <= 9; $i++) {
      if ($media_mentions_options['media_mentions_url'.$i] && $media_mentions_options['media_mentions_resources_selection'.$i]) {
        if ($pcount%2) $float = 'left'; else $float = 'right';
	$bwfile = substr(strrchr($media_mentions_options['media_mentions_resources_selection'.$i], '|'), 1);
	if (!preg_match("/_iv/i", $bwfile)) $bwfile = str_replace(".", "_bw.", $bwfile);
	$bwdimensions = getimagesize(MM_WP_PLUGIN_DIR.'/images/'.$i.'/'.$bwfile);
	if (stripslashes($media_mentions_options['media_mentions_citation'.$i]))
		$citation_and_link = '<i>"<a href="'.$media_mentions_options['media_mentions_url'.$i].'">'.stripslashes($media_mentions_options['media_mentions_citation'.$i]).'</a>"</i>';
	else
		$citation_and_link = '';
        if ($pcount%2) $mycontent .= '<div style="width:510px;overflow:hidden;">'; //start div row
        $mycontent .= '<div style="float:'.$float.';width:250px;text-align:center;margin:0 0 50px 0;">
<div>
';
//$mycontent .= "HELLO".($bwdimensions[0]/$bwdimensions[1]).'images/'.$i.'/'.$bwfile.$bwdimensions;
   if (($bwdimensions[0]/$bwdimensions[1]) > (180/75)) {
	$mycontent .= '<a href="'.$media_mentions_options['media_mentions_url'.$i].'"><img src="'.WP_MEDIA_MENTIONS_DIR.'/images/'.$i.'/'.$bwfile.'?'.rand().'" width="180" border="0"/></a>';
   } else {
	$mycontent .= '<img src="'.WP_MEDIA_MENTIONS_DIR.'/images/'.$i.'/'.$bwfile.'?'.rand().'" height="75" border="0"/>';
   }
$mycontent .= '</div>
<div style="margin:20px 0 10px 0;text-align:center;">
<a href="'.WP_MEDIA_MENTIONS_DIR.'/images/screenshot'.$i.'.png?'.rand().'"><img src="'.WP_MEDIA_MENTIONS_DIR.'/images/screenshot'.$i.'t.png?'.rand().'" width="200" border="0"/></a>
</div>
<div style="margin:0px 0 0px 0;text-align:left;">
'.$citation_and_link.'
</div>
            </div>
        ';
        if ($pcount%2) ; else $mycontent .= '</div>'; //close out div row
	$pcount++;
      }
  }
  if ($pcount%2) ; else $mycontent .= '</div>'; //close out div row
  $mycontent .= '<div style="text-align:center">Page generated by <a href="http://mediamentionsplugin.com" target="_blank">Media Mentions Plugin</a></div>';
  return $mycontent;
} 

function limit_1_post($limit) {
  return 'LIMIT 1';
}

function replace_template() {
  include(TEMPLATEPATH . '/page.php');
  exit;
}

}
////END - only activate the virtual /media-mentions page if the activated radio button is on /////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////


function media_mentions_install() {
   global $wpdb;
   global $media_mentions_db_version;
   global $media_mentions_display_text;

   $table_name = $wpdb->prefix . "media_mentions";
      

   $sql = "CREATE TABLE " . $table_name . " (
	  id mediumint(9) NOT NULL AUTO_INCREMENT,
	  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
	  url VARCHAR(255) DEFAULT '' NOT NULL,
	  title VARCHAR(255) DEFAULT '' NOT NULL,
	  description text NOT NULL,
	  logo_filename VARCHAR(55) DEFAULT '' NOT NULL,
	  screenshot_filename VARCHAR(55) DEFAULT '' NOT NULL,
	  UNIQUE KEY id (id)
    );";

   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//   dbDelta($sql);

//create data directories

   $dirstocreate = array(
     MM_WP_PLUGIN_DIR."/images",
     MM_WP_PLUGIN_DIR."/images/1",
     MM_WP_PLUGIN_DIR."/images/2",
     MM_WP_PLUGIN_DIR."/images/3",
     MM_WP_PLUGIN_DIR."/images/4",
     MM_WP_PLUGIN_DIR."/images/5",
     MM_WP_PLUGIN_DIR."/images/6",
     MM_WP_PLUGIN_DIR."/images/7",
     MM_WP_PLUGIN_DIR."/images/8",
     MM_WP_PLUGIN_DIR."/images/9"
   );
   foreach ($dirstocreate as $dirtocreate) {
     if (!file_exists($dirtocreate)) {
       @mkdir($dirtocreate);
       @chmod($dirtocreate, 0755);
     }
   }

   $default_options = array();
   $default_options['media_mentions_db_version'] = $media_mentions_db_version;
   $default_options['media_mentions_enabled'] = '0';
   $default_options['media_mentions_display_text'] = $media_mentions_display_text;
   add_option("media_mentions_options", $default_options);
}

function media_mentions_install_data() {
   global $wpdb;
   $mm_url = "http://test";
   $mm_title = "FTF in MediaPost";
   $mm_description = "FTF is a website, etc";
   $mm_logo_filename = "logo1.jpg";
   $mm_screenshot_filename = "screenshot1.jpg";

   $table_name = $wpdb->prefix . "media_mentions";

   $rows_affected = $wpdb->insert( $table_name, array( 
	'time' => current_time('mysql'),
	'url' => $mm_url,
	'title' => $mm_title,
	'description' => $mm_description,
	'logo_filename' => $mm_logo_filename,
	'screenshot_filename' => $mm_screenshot_filename
   ) );
}


add_action('wp_head', 'media_mentions_head_scripts');
function media_mentions_head_scripts() {
        wp_print_scripts('jquery');
}



add_action('admin_menu', 'media_mentions_menu');

if ($media_mentions_options['media_mentions_enabled'] != '1') {
	add_action('admin_notices', 'media_mentions_admin_notices' );
}

function media_mentions_menu() {
	$hook_suffix = add_options_page('Media Mentions Options', 'Media Mentions', 'manage_options', 'media-mentions', 'media_mentions_options');
        add_action( 'load-' . $hook_suffix , 'media_mentions_load_function' );
}


class MediaMentions_Widget extends WP_Widget {
    var $max_rpp = 10;
    var $cache_lifetime = 300;
    var $cache_fail_lifetime = 300;
    var $default_rpp = 7;

    function MediaMentions_Widget() {
        $widget_ops = array(
            'classname' => 'mediamentions',
            'description' => __( "Media Mentions plugin")
        );
        $this->WP_Widget('media-mentions-widget', __('Media Mentions'), $widget_ops);
    }

    function widget( $args, $instance ) {
		global $media_mentions_options;
//print_r($instance['display_text']);
                extract( $args );

		if ($media_mentions_options['media_mentions_enabled']) {
			$display_text = $instance['display_text'];
			if ($display_text == "0") {
				echo '<div class="media_mentions_image"><a href="' . get_option( 'siteurl' ) . '/?media_mentions_detail=1"><img src="'.WP_MEDIA_MENTIONS_DIR.'/images/collage.png" border="0" /></a></div>';
				echo '<div style="clear: both;"></div>';
			} else {
				echo '<div class="media_mentions_header">'.$media_mentions_options['media_mentions_display_text'].'</div><div class="media_mentions_image"><a href="' . get_option( 'siteurl' ) . '/?media_mentions_detail=1"><img src="'.WP_MEDIA_MENTIONS_DIR.'/images/collage.png" border="0" /></a></div>';
				echo '<div style="clear: both;"></div>';
			}
		}


    }

}


add_action("widgets_init", "media_mentions_widget_init");
function media_mentions_widget_init() {
    register_widget('MediaMentions_Widget');
}


function media_mentions_get_mentions($display_text) {
	global $media_mentions_options;

    $args = array(
        'before_title' => '<h2 class="media_mentions_widgettitle">',
        'after_title' => '</h2>',
        'widget_id' => 'media-mentions-widget-0'
    );
    $instance = array(
        'display_text' => $display_text
    );
    $widget = new MediaMentions_Widget;
    $widget->widget($args, $instance);


}


function media_mentions_func($atts) {
	return media_mentions_get_mentions($atts['display_text']);
}
add_shortcode('media_mentions', 'media_mentions_func');


///////////////// BEGIN  -  Utility PHP functions ////////////////////////////////

function delete_folder_files($tmp_path){ 
  if(!is_writeable($tmp_path) && is_dir($tmp_path)){chmod($tmp_path,0777);} 
    $handle = opendir($tmp_path); 
  while($tmp=readdir($handle)){ 
    if($tmp!='..' && $tmp!='.' && $tmp!=''){ 
        unlink($tmp_path.DS.$tmp); 
    } 
  } 
  closedir($handle); 
//  rmdir($tmp_path); 
  if(!is_dir($tmp_path)){return true;} 
  else{return false;} 
} 


function cleanUrlSettings($url_num) {

	$dirrefresh = MM_WP_PLUGIN_DIR."/images/".$url_num;
	delete_folder_files($dirrefresh);

	//delete screenshots
	$screenshotdelete = MM_WP_PLUGIN_DIR."/images/screenshot".$url_num."t.png";
	@unlink($screenshotdelete);
	$screenshotdelete = MM_WP_PLUGIN_DIR."/images/screenshot".$url_num.".png";
	@unlink($screenshotdelete);
	$screenshotdelete = MM_WP_PLUGIN_DIR."/images/collage.png";
	@unlink($screenshotdelete);

}

///////////////// END  -  Utility PHP functions ////////////////////////////////


function media_mentions_options() {
	global $media_mentions_options;

	$urls_updated = array();
	$refresh_collage = 0;

	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

//process form submits

	if(isset($_POST['action'])){
		if ($_POST['action'] && $_POST['action'] == 'media_mentions_options_update' && $_POST['Submit']!='') {
			$media_mentions_options['media_mentions_enabled'] = $_POST['media_mentions_enabled'];
			$media_mentions_options['media_mentions_display_text'] = $_POST['media_mentions_display_text'];

			for ($i = 1; $i <= 9; $i++) {
				if ($media_mentions_options['media_mentions_url'.$i] != $_POST['media_mentions_url'.$i]) {
					show_message("Url #$i updated..");
					cleanUrlSettings($i);
					$media_mentions_options['media_mentions_url'.$i] = $_POST['media_mentions_url'.$i];
					$urls_updated[] = $i;
					$media_mentions_options['media_mentions_check'.$i] = "";
					$media_mentions_options['media_mentions_resources_selection'.$i] = "";
					$media_mentions_options['media_mentions_citation'.$i] = "";
				} else {
					if ($media_mentions_options['media_mentions_citation'.$i] != $_POST['media_mentions_citation'.$i]) {
						show_message("Url #$i citation text saved..");
						$media_mentions_options['media_mentions_citation'.$i] = $_POST['media_mentions_citation'.$i];
					}
					if ($media_mentions_options['media_mentions_check'.$i] != $_POST['media_mentions_check'.$i]) {
						show_message("Url #$i now included in collage..");
						$media_mentions_options['media_mentions_check'.$i] = $_POST['media_mentions_check'.$i];
						$refresh_collage = 1;
					}
					if ($media_mentions_options['media_mentions_resources_selection'.$i] != $_POST['media_mentions_resources_selection'.$i]) {
						show_message("Url #$i resource selection saved..");
						$media_mentions_options['media_mentions_resources_selection'.$i] = $_POST['media_mentions_resources_selection'.$i];
						$refresh_collage = 1;
					}
				}
			}
//print_r($_POST);
			update_option('media_mentions_options', $media_mentions_options);
		}
	}




//display options page

//print_r($media_mentions_options);

echo '
<div style="background-color:white;width:500px;padding: 10px 10px 10px 10px;margin-right:15px;border: 1px solid #ddd;height:200px;">
		<div style="width:423px;height:130px;">
			<h3>Donate</h3>
			<em>If you like this plugin and find it useful, help keep this plugin free and actively developed by clicking the <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=foodtruckfiesta%40gmail%2ecom&amp;item_name=Media%20Mentions&amp;item_number=Support%20Open%20Source&amp;no_shipping=0&amp;no_note=1&amp;tax=0&amp;currency_code=USD&amp;lc=US&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8" target="_blank"><strong>donate</strong></a> button or send me a gift from my <a href="http://amzn.com/w/18H3WEX05AAJ5" target="_blank"><strong>Amazon wishlist</strong></a>.  </em>
		</div>
		<a target="_blank" title="Donate" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=foodtruckfiesta%40gmail%2ecom&amp;item_name=Media%20Mentions&amp;item_number=Support%20Open%20Source&amp;no_shipping=0&amp;no_note=1&amp;tax=0&amp;currency_code=USD&amp;lc=US&amp;bn=PP%2dDonationsBF&amp;charset=UTF%2d8">
		<img src="'.WP_MEDIA_MENTIONS_DIR.'/images/donate.jpg" alt="Donate with Paypal">	</a>
		<a target="_blank" title="Amazon Wish List" href="http://amzn.com/w/18H3WEX05AAJ5">
		<img src="'.WP_MEDIA_MENTIONS_DIR.'/images/amazon.jpg" alt="My Amazon Wish List"> </a>
	</div>
		<div style="margin:20px 0 0 0;">Help info: <a target="_blank" title="instructions" href="http://mediamentionsplugin.com/usage/">
		Instructions on how to use Media Mentions Plugin</a> &nbsp;|&nbsp; <a target="_blank" title="support" href="http://mediamentionsplugin.com/forum/">Support forum</a></div>
';



?>


<script type="text/javascript"><!--

jQuery(function(){
	readyFrames();
});

function readyFrames(is_refresh) {
<?php
	for ($i = 1; $i <= 9; $i++) {
?>



jQuery("#iframe_resources_url<?php echo $i; ?>").load(function() {
	if (!is_refresh) setJSResourcesSelections(<?php echo $i; ?>);
	if (jQuery('#iframe_resources_url<?php echo $i; ?>').contents().find("body").find(".citation").length != 0) {
		setCitationBox(<?php echo $i; ?>);
	}
			jQuery('#media_mentions_citation_select<?php echo $i; ?>').change(function(){
				var selected = jQuery("#media_mentions_citation_select<?php echo $i; ?> option:selected");
				if(selected.val() != 0 ){
					jQuery('#media_mentions_citation<?php echo $i; ?>').val(selected.html());
				}
			});
jQuery('#iframe_resources_url<?php echo $i; ?>').contents().find("body").find(".image-container").click(function() {
//alert('Handler for .click() called: '+jQuery(this).attr("name"));
  jQuery(this).parent().parent().find(".image-container").contents().css('background-color', 'white');
  jQuery(this).parent().parent().find(".image-container").removeClass('tagged');
  jQuery(this).contents().css('background-color', 'red');
  jQuery(this).addClass('tagged');
  setFormResourcesSelections();
});
});
<?php
	}
?>
}

function setCitationBox(num_frame) {
<?php
	for ($i = 1; $i <= 9; $i++) {
		if ($media_mentions_options['media_mentions_url'.$i]) {
?>
			if (num_frame == <?php echo $i; ?>) {
				//add drop-down suggestions & activate drop-down
				jQuery('#media_mentions_citation_select<?php echo $i; ?>').find('option').remove().end();
				jQuery('#media_mentions_citation_box<?php echo $i; ?>').css('display', 'block');
				//copy data from iframe
				jQuery('#media_mentions_citation_select<?php echo $i; ?>').append(jQuery("<option></option>").attr("value", "0").text('Copy citation from one of these suggestions..'));
				if (jQuery('#iframe_resources_url<?php echo $i; ?>').contents().find("body").find(".citation").length != 0) {
					jQuery('#iframe_resources_url<?php echo $i; ?>').contents().find("body").find(".citation").each(function(i, obj){
						jQuery('#media_mentions_citation_select<?php echo $i; ?>').append(jQuery("<option></option>").attr("value", "<?php echo $i; ?>").text(jQuery('#iframe_resources_url<?php echo $i; ?>').contents().find("body").find(".citation").html()));
					});

				}

			}


<?php
		}
	}
?>
}


function setJSResourcesSelections(num_frame) {
<?php
	for ($i = 1; $i <= 9; $i++) {
		if ($media_mentions_options['media_mentions_resources_selection'.$i]) {
?>
			if (num_frame == <?php echo $i; ?>) {
				if (jQuery('#iframe_resources_url<?php echo $i; ?>').contents().find("body").find(".image-container[name='<?php echo $media_mentions_options['media_mentions_resources_selection'.$i]; ?>']").length != 0) {
					jQuery('#iframe_resources_url<?php echo $i; ?>').contents().find("body").find(".image-container[name='<?php echo $media_mentions_options['media_mentions_resources_selection'.$i]; ?>']").css('background-color', 'red');
					jQuery('#iframe_resources_url<?php echo $i; ?>').contents().find("body").find(".image-container[name='<?php echo $media_mentions_options['media_mentions_resources_selection'.$i]; ?>']").addClass('tagged');
					jQuery('#iframe_resources_url<?php echo $i; ?>').contents().find('body').animate({scrollTop: jQuery('#iframe_resources_url<?php echo $i; ?>').contents().find('a[href="#<?php echo substr(strrchr($media_mentions_options['media_mentions_resources_selection'.$i], '|'), 1); ?>"]').offset().top}, 'slow');
				} else {
					jQuery('#media_mentions_resources_selection'+num_frame).val('');
				}

			}


<?php
		}
	}
?>
}


function setFormResourcesSelections() {
	for (var i=1; i <= 9; i++) {
		jQuery('#media_mentions_resources_selection'+i).attr('value', jQuery('#iframe_resources_url'+i).contents().find('body').find('.tagged').attr('name'));
	}
}

function getLogoList() {

	var strList = "options-general.php?page=media-mentions&create_collage=1&r=1&q=";
	for (var i=1; i <= 9; i++) {
		if (jQuery('#media_mentions_check'+i).attr('checked') == 'checked') strList += jQuery('#iframe_resources_url'+i).contents().find('body').find('.tagged').attr('name')+',';
	}
	return strList;
}

//-->
</script>

<div class="wrap">
<p>
Media Mentions plugin options:
</p>
<p>
<form name="dofollow" action="" method="post">
<table class="form-table">

<tr>
<td scope="row" style="text-align:right; vertical-align:top; width:150px;">
Enable Media Mentions:
</a>
</td>
<td style="width:350px;" colspan="2">
<input type="radio" name="media_mentions_enabled" value="1" <?php if ($media_mentions_options['media_mentions_enabled'] == '1') echo "checked=\"checked\"";?> /> Yes 
<input type="radio" name="media_mentions_enabled" value="0" <?php if ($media_mentions_options['media_mentions_enabled'] == '0') echo "checked=\"checked\"";?> /> No 
<?php if ($media_mentions_options['media_mentions_enabled'] == '1') { ?>
<p>Virtual <a href="<?php echo get_option( 'siteurl' ); ?>/?media_mentions_detail=1" target="_blank">/?media_mentions_detail=1</a> page is now active. (link opens in a new window)</p>
<?php } ?>
</td>
</tr>
<tr>
<td scope="row" style="text-align:right; vertical-align:top;">
Text to display above the collage of logos:
</a>
</td>
<td>
<input size="59" name="media_mentions_display_text" value="<?php echo stripcslashes($media_mentions_options['media_mentions_display_text']); ?>"/>
</td>
</tr>

<?php

$iframe_collage_src_inline = '<iframe id="iframe_collage" src="options-general.php?page=media-mentions&create_collage=1" frameborder="0" width="320" height="200" scrolling="no"></iframe>';
$iframe_collage_src = '<iframe id=&quot;iframe_collage&quot; src=&quot;options-general.php?page=media-mentions&create_collage=1&r=1&q=&quot; frameborder=&quot;0&quot; width=&quot;320&quot; height=&quot;200&quot; scrolling=&quot;no&quot;></iframe>';

?>

<tr>
<td scope="row" style="text-align:right; vertical-align:top;">
Collage image:
</td>
<td>
<?php echo $iframe_collage_src_inline; ?>
</td>
<td>
<input type="button" value="Refresh Collage" onclick="jQuery('#iframe_collage').attr('src', getLogoList());" class="button">
</td>
</tr>

<?php for ($i = 1; $i <= 9; $i++) { 

$iframe_src_inline_firstload = '<iframe id="iframe_screenshot_url'.$i.'" src="options-general.php?page=media-mentions&get_screenshot=1&r=1&n='.$i.'&q='.$media_mentions_options['media_mentions_url'.$i].'" frameborder="0" width="200" height="200" scrolling="no"></iframe>';
$iframe_src_inline = '<iframe id="iframe_screenshot_url'.$i.'" src="options-general.php?page=media-mentions&get_screenshot=1&n='.$i.'&q='.$media_mentions_options['media_mentions_url'.$i].'" frameborder="0" width="200" height="200" scrolling="no"></iframe>';
$iframe_src = '<iframe id=&quot;iframe_screenshot_url'.$i.'&quot; src=&quot;options-general.php?page=media-mentions&get_screenshot=1&r=1&n='.$i.'&q='.$media_mentions_options['media_mentions_url'.$i].'&quot; frameborder=&quot;0&quot; width=&quot;200&quot; height=&quot;200&quot; scrolling=&quot;no&quot;></iframe>';

$iframe_resources_src_inline_firstload = '<iframe id="iframe_resources_url'.$i.'" src="options-general.php?page=media-mentions&get_resources=1&r=1&n='.$i.'&q='.$media_mentions_options['media_mentions_url'.$i].'&gr1='.str_replace('"', "", get_bloginfo("name")).'&gr2='.home_url().'" frameborder="0" width="220" height="200" scrolling="yes"></iframe>';
$iframe_resources_src_inline = '<iframe id="iframe_resources_url'.$i.'" src="options-general.php?page=media-mentions&get_resources=1&n='.$i.'&q='.$media_mentions_options['media_mentions_url'.$i].'&gr1='.str_replace("'", "", get_bloginfo("name")).'&gr2='.home_url().'" frameborder="0" width="220" height="200" scrolling="yes"></iframe>';
$iframe_resources_src = '<iframe id=&quot;iframe_resources_url'.$i.'&quot; src=&quot;options-general.php?page=media-mentions&get_resources=1&r=1&n='.$i.'&q='.$media_mentions_options['media_mentions_url'.$i].'&gr1='.str_replace("'", "", get_bloginfo("name")).'&gr2='.home_url().'&quot; frameborder=&quot;0&quot; width=&quot;220&quot; height=&quot;200&quot; scrolling=&quot;yes&quot;></iframe>';

?>
<tr>
<td scope="row" style="text-align:right; vertical-align:top;">
Mention URL #<?php echo $i; ?>:
</td>
<td>
<input size="59" name="media_mentions_url<?php echo $i; ?>" value="<?php echo stripcslashes($media_mentions_options['media_mentions_url'.$i]); ?>"/>
</td>
<td align="center">
<?php if ($media_mentions_options['media_mentions_url'.$i]) { ?>
<input type="button" value="Refresh Screenshot" onclick="jQuery('#screenshot_url<?php echo $i; ?>').html('<?php echo $iframe_src; ?>');jQuery('#screenshot_url<?php echo $i; ?>').attr('src', jQuery('#screenshot_url<?php echo $i; ?>').attr('src'));" class="button">
<?php } ?>
</td>
<td align="center">
<?php if ($media_mentions_options['media_mentions_url'.$i]) { ?>
<input type="button" value="Refresh Logo Selections" onclick="jQuery('#resources_url<?php echo $i; ?>').html('<?php echo $iframe_resources_src; ?>');jQuery('#resources_url<?php echo $i; ?>').attr('src', jQuery('#resources_url<?php echo $i; ?>').attr('src'));readyFrames(1);" class="button">
<input type="hidden" name="media_mentions_resources_selection<?php echo $i; ?>" id="media_mentions_resources_selection<?php echo $i; ?>" value="<?php echo $media_mentions_options['media_mentions_resources_selection'.$i]; ?>"/>
<?php } ?>
</td>
</tr>

<tr>
<td></td>
<td>
<?php if ($media_mentions_options['media_mentions_url'.$i]) { ?>
<p><input name="media_mentions_check<?php echo $i; ?>" type="checkbox" value="1" <?php if ($media_mentions_options['media_mentions_check'.$i] == '1') echo "checked=\"checked\"";?> id="media_mentions_check<?php echo $i; ?>">
<label for="media_mentions_check<?php echo $i; ?>">Include URL #<?php echo $i; ?>'s logo in collage?</label></p>
<br/>
Citation:<br/>
<div id="media_mentions_citation_box<?php echo $i; ?>" style="display:none;">
<select id="media_mentions_citation_select<?php echo $i; ?>" name="media_mentions_citation_select<?php echo $i; ?>" style="width:300px;">
<option value="volvo">Volvo VolvoVolvo Volvo Volvo Volvo Volvo Volvo Volvo Volvo Volvo Volvo </option>
<option value="saab">Saab</option>
<option value="audi">Audi</option>
</select>
</div>
<p><input size="59" id="media_mentions_citation<?php echo $i; ?>" name="media_mentions_citation<?php echo $i; ?>" value="<?php echo stripcslashes($media_mentions_options['media_mentions_citation'.$i]); ?>"/></p>
<?php } ?>
</td>
<td>
<?php if ($media_mentions_options['media_mentions_url'.$i]) { ?>
<div style="width:200px;height:200px;" id="screenshot_url<?php echo $i; ?>">
<?php 
    $image_file_local = MM_WP_PLUGIN_DIR."/images/screenshot".$i.".png";
    $image_file = WP_MEDIA_MENTIONS_DIR."/images/screenshot".$i.".png";

    if (!file_exists($image_file_local)) {
        echo $iframe_src_inline_firstload;
    } else {
        echo $iframe_src_inline;
    }

?>
</div>
<?php } ?>
</td>
<td>
<?php if ($media_mentions_options['media_mentions_url'.$i]) { ?>
<div style="width:220px;height:200px;" id="resources_url<?php echo $i; ?>">
<?php

    $resources_dir_local = MM_WP_PLUGIN_DIR."/images/".$i."/";
    $resources_dir = WP_MEDIA_MENTIONS_DIR."/images/".$i."/";

    if (fileCounter($resources_dir_local) <= 0) {
	echo $iframe_resources_src_inline_firstload;
    } else {
        echo $iframe_resources_src_inline;
    }

?>
</div>
<?php } ?>
</td>
</tr>
<?php } //end loop ?>

</table>
<p>
<input type="hidden" name="action" value="media_mentions_options_update" />
<input type="submit" class='button-primary' name="Submit" value="<?php _e('Update Options', 'media_mentions')?> &raquo;" />
</p>
</form>
</p>

</div>
<?php

}


function media_mentions_load_function() {
	// Current page is options page for our plugin, so do not display notice
	// (remove hook responsible for this)
	remove_action( 'admin_notices', 'media_mentions_admin_notices' );
}

function media_mentions_admin_notices() {
	echo "<div id='notice' class='updated fade'><p>Media Mentions plugin is not enabled yet. <a href=\"/wp-admin/options-general.php?page=media-mentions\">Please set it up now</a>.</p></div>\n";
}



function fileCounter($dir){
    $counter = 0;
    if ($handle = opendir($dir)) {
      //echo "Directory handle: $handle\n";
      //echo "Files:\n";
      /* This is the correct way to loop over the directory. */
      while (false !== ($file = readdir($handle))) {
          //echo "<BR>".$counter." - $file";
          $counter++;
      }
      closedir($handle);
    }
    $counter -= 2; // in order to exclude '.' and '..', as well as start the counter on 1
    return $counter;
}




