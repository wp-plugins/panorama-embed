<?php
/*
Plugin Name: Panorama Embed 
Plugin URI: http://gpwrite.com/
Description: Allows you to embed panoramas or virtual tours in your blog. Either embedded directly within an iframe, or with a linked picture opening a new window. Works without problems for any type of panorama. (or anything you want to embed for that matter)
Version: 1.4
Date: Januari, 2013
Author: Gede Pasek
Author URI: http://gpwrite.com/
*/ 

function panoramaembedGP($content) {
   
	$serv = $_SERVER['DOCUMENT_ROOT'];
 
    $panoembed_pattern = '/\[panoembed\](.*?)\[\/panoembed\]/is'; 

    if (preg_match_all($panoembed_pattern, $content, $thePano)) {
	$panotags = array();
	$panorepl = array();
	for ($i=0; $i<count($thePano); $i++) {
		$panotags[] = $thePano[0][$i];
		$abspanoPath = $serv . $thePano[1][$i];
		print "<br>";
		if (file_exists($abspanoPath . "thumb.jpg")) {
		    $theThumb = $thePano[1][$i] . "thumb.jpg";
			$thePanoLink = $thePano[1][$i];
			$theImageLink = '<img src="' . $theThumb . '" alt="Panorama" title="Click to open Panorama" style="cursor:pointer;" onClick="window.open(\'' . $thePano[1][$i] . '\',\'_blank\');return false;" />';
			$panorepl[] = $theImageLink;
		} else {
		    $panorepl[] = '<iframe src="' . $thePano[1][$i] . '" seamless width="' . get_option("PanoramaEmbedGP_width") . 'px" height ="' . get_option("PanoramaEmbedGP_height") . 'px"></iframe>';

		}
	}
    $content = str_replace ($panotags, $panorepl, $content);
	}
   
    return $content;
}
add_filter('the_content', 'panoramaembedGP');

add_action( 'admin_menu', 'PanoramaEmbedMenuGP' );

function PanoramaEmbedMenuGP() {
	add_options_page( 'Panorama Embed', 'Panorama Embed', 'manage_options', 'PanoramaEmbedOptionsGP', 'PanoramaEmbedOptionsGP' );
}

function PanoramaEmbedOptionsGP() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	screen_icon();
	?>
	<h2>Panorama Embed Options</h2>
	<p>Panorama Embed is made by <a href="http://gpwrite.com/" target="_blank"">Gede Pasek</a></p>
	<p></p>
	<p>Use of the plugin:</p>
	<p>Add this to your post: <b>[panoembed]</b><i>/directory/of/your/panorama/</i><b>[/panoembed]</b></p>
	<p>The <i>directory of your panorama</i>, is a directory relative to the root of your domain.</p>
	<p>So, if your panorama is located at www.yourdomain.com/panoramas/pano/ , you enter <b>[panoembed]</b><i>/panoramas/pano/</i><b>[/panoembed]</b></p>
	<p>The plugin assumes that there is a default html file in that directory that will be served, such as index.htm or index.php</p>
	<p>The plugin loads an iframe in your post, with the content of the directory, <b>unless</b> there is a thumb.jpg in that directory. Then, the plugin will show that thumb.jpg, linking to the panorama (directory) in a new window.</p>
	<p></p>
	<p>The panorama plugin has the option to set the width and height of the embedded panorama iframe.</p>
	<p>For comments, remarks, demos, samples, go to <a href="http://gpwrite.com/" target="_blank"">my blog.</a>
	<h3>Settings:</h3>
	
<form method='POST' action='options.php'>
    <?php settings_fields('PanoramaEmbedGP_options'); ?>
    <table class='form-table'>
        <tr>
        <td><input type='text' name='PanoramaEmbedGP_width' value='<?php echo get_option('PanoramaEmbedGP_width'); ?>' /> Width</td>
		</tr>
		<tr>
        <td><input type='text' name='PanoramaEmbedGP_height' value='<?php echo get_option('PanoramaEmbedGP_height'); ?>' /> Height</td>
        </tr>
    </table>
    <p class='submit'>
        <input type='submit' class='button-primary' value='<?php _e('Save Changes') ?>' />
    </p>
</form>

<?php
echo '</div>';
}

add_action('admin_init', 'PanoramaEmbedGP_admin_init');

function PanoramaEmbedGP_admin_init(){
add_option("PanoramaEmbedGP_width", "468");
add_option("PanoramaEmbedGP_height", "360");
register_setting( 'PanoramaEmbedGP_options', 'PanoramaEmbedGP_width', 'intval' );
register_setting( 'PanoramaEmbedGP_options', 'PanoramaEmbedGP_height', 'intval' );
}

?>
