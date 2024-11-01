<?php
error_reporting(E_NONE); // debugging - show all errors
/*
Plugin Name: WP Meerkat
Plugin URI: http://www.mlynn./org/plugins/wp-meerkat
Tags: custom footer, footer, footer ads, popup footer, jquery footer, ajax, popup
Description: WP-Meerkat enables you to create popup border messages on the bottom, top, left or right portion of your Wordpress Blog by leveraging Jarod Taylor's jQuery plugin by the same name. Simply specify the style, size and animation effects using the settings below. Be sure to check out the fadeout setting if you want to have a message that appears and then fades away on your site.  For more information on this great jQuery plugin, check out Jarod Taylor's Meerkat page at <a href=http://meerkat.jarodtaylor.com>meerkat.jarodtaylor.com</a> | <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=J89ARHMKVMAAN">Donate</a> 
Version: 1.0.1
Author: Michael Lynn
Author URI: http://www.mlynn.org/
*/

/*
	This script cannot be called directly
*/
if ( ! defined( 'ABSPATH' ) )
        die( "Can't load this file directly" );

/*
	Register our activation and unistall hooks
*/
register_activation_hook( __FILE__, 'wpmk_defaults' );
register_uninstall_hook( __FILE__, 'wpmk_delete_plugin_settings' );

add_action( 'init', 'wpmk_init' );
add_action( 'admin_init', 'wpmk_admin_init' );
add_action( 'admin_menu', 'wpmk_settings_page' );
add_action('wp_footer', 'wpmk_footer',0);

function wpmk_footer() {
	$options = get_option('wpmk_settings');
	echo "<div class='meerkat pos-".$options['txt_position']."' style='display: block;'>".$options['txt_content'];
	if ($options['chk_enable_close']) {
?>
		<a href='#' class='close-meerkat'>close</a>
<?php
	}
	if ($options['chk_enable_dontshow']) {
?>
		<a href='#' class="dont-show" style="<?php echo $options[txt_dontshow_style];?>">Don't Show Again</a>
<?php
	}
	echo "</div>";
}

function wpmk_settings_page() {
	add_options_page('WP Meerkat Settings','WP Meerkat', 'manage_options', __FILE__, 'wpmk_settings');
}
function wpmk_settings() {
	$options = get_option('wpmk_settings');
	?>
	<div class=wrap><h2>WP Meerkat Options</h2></div><p>
<?php
    if ( !$options['chk_donated'] ) { 
?>  
<div style="text-align:center; background: #eeeeee; margins: 5px 5px 5px 5px;" id='wmkdonate'>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="J89ARHMKVMAAN">
    <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"><br>
    <small>Like this plugin?  Please <a href=http://wordpress.org/extend/plugins/wp-meerkat/>rate</a> it highly and maybe even consider donating to help keep this plugin alive and free.</small>
    <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
<br />
</div>
<?php 
}
?>
	<h3>About</h3>
WP-Meerkat enables you to create popup border messages on the bottom, top, left or right portion of your Wordpress Blog by leveraging <a href=http://meerkat.jarodtaylor.com>Jarod Taylor's jQuery plugin</a> by the same name.  Simply specify the style, size and animation effects using the settings below.  Be sure to check out the fadeout setting if you want to have a message that appears and then fades away on your site.	
<p>
	<form method='post' action='options.php'>
		<?php settings_fields('wpmk_settings_fields'); ?>
		<table class='widefat post fixed'>
			<thead>
				<tr>
					<th width=120>Setting</th>
					<th>Value</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th valign=top width=120>Donation</th>
					<td>
						<input name="wpmk_settings[chk_donated]" type="checkbox" <?php if ($options['chk_donated'] ) echo 'checked="checked"'; ?>
						<span style="color:#666666;margin-left:2px;">I have <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=J89ARHMKVMAAN">donated</a> to the author of this plugin</span>
					</td>
				</tr>
				<tr>
					<th valign=top width=120>Plugin Status</th>
					<td>
						<input name="wpmk_settings[chk_enable_plugin]" type="checkbox" <?php if ($options['chk_enable_plugin'] ) echo 'checked="checked"'; ?>
						<span style="color:#666666;margin-left:2px;">Enable or Disable the Plugin</span>
					</td>
				</tr>
				<tr>
					<th valign=top width=130>Background Style</th>
					<td>
						<input name="wpmk_settings[txt_background_style]" type="text" size=60 value='<?php echo $options['txt_background_style'];?>'>
						<span style="color:#666666;margin-left:2px;">CSS Style for Popup Background</span>
					</td>
				</tr>
				<tr>
					<th valign=top width=120>Width</th>
					<td>
						<input name="wpmk_settings[txt_width]" type="text" size=20 value='<?php echo $options['txt_width'];?>'>
						<span style="color:#666666;margin-left:2px;">Width valign=topof the Popup Message</span>
					</td>
				</tr>
				<tr>
					<th valign=top width=120>Height</th>
					<td>
						<input name="wpmk_settings[txt_height]" type="text" size=20 value='<?php echo $options['txt_height'];?>'>
						<span style="color:#666666;margin-left:2px;">Height of the Popup Message</span>
					</td>
				</tr>
				<tr>
					<th valign=top width=120>Position</th>
					<td>
						<select name="wpmk_settings[txt_position]">
							<option value="left" <?php if ($options["txt_position"]=="left") echo "selected";?>>Left</option>
							<option value="right" <?php if ($options["txt_position"]=="right") echo "selected";?>>Right</option>
							<option value="bottom" <?php if ($options["txt_position"]=="bottom") echo "selected";?>>Bottom</option>
							<option value="top" <?php if ($options["txt_position"]=="top") echo "selected";?>>Top</option>
						</select>
						<span style="color:#666666;margin-left:2px;">Where should the message be displayed?</span>
					</td>
				</tr>
				<tr>
					<th valign=top width=120>Opacity</th>
					<td>
						<input name="wpmk_settings[txt_opacity]" type="text" size=20 value='<?php echo $options['txt_opacity'];?>'>
						<span style="color:#666666;margin-left:2px;">How transparent (or non-transparent) the popup be - value of 1 is completely non-transparent</span>
					</td>
				</tr>
				<tr>
					<th valign=top width=120>Animation In</th>
					<td>
                        <select name="wpmk_settings[txt_animation_in]">
                            <option value="slide" <?php if ($options["txt_animation_in"]=="left") echo "selected";?>>Slide</option>
                            <option value="fade" <?php if ($options["txt_animation_in"]=="right") echo "selected";?>>Fade</option>
                            <option value="none" <?php if ($options["txt_animation_in"]=="bottom") echo "selected";?>>None</option>
                        </select>
						<span style="color:#666666;margin-left:2px;">What form of animation should the popup use when displayed?  Valid options are fade, slide or none.</span>
					</td>
				</tr>
				<tr>
					<th valign=top width=120>Animation Out</th>
					<td>
                        <select name="wpmk_settings[txt_animation_out]">
                            <option value="slide" <?php if ($options["txt_animation_out"]=="left") echo "selected";?>>Slide</option>
                            <option value="fade" <?php if ($options["txt_animation_out"]=="right") echo "selected";?>>Fade</option>
                            <option value="none" <?php if ($options["txt_animation_out"]=="bottom") echo "selected";?>>None</option>
                        </select>
						<span style="color:#666666;margin-left:2px;">What form of animation should the popup use when finished displaying?  Valid options are fade, slide or none.</span>
					</td>
				</tr>
				<tr>
					<th valign=top width=120>Animation Speed</th>
					<td>
                        <select name="wpmk_settings[txt_animation_speed]">
                            <option value="fast" <?php if ($options["txt_animation_speed"]=="left") echo "selected";?>>Fast</option>
                            <option value="normal" <?php if ($options["txt_animation_speed"]=="right") echo "selected";?>>Normal</option>
                            <option value="slow" <?php if ($options["txt_animation_speed"]=="bottom") echo "selected";?>>Slow</option>
                        </select>
						<span style="color:#666666;margin-left:2px;">How fast should the animation be?</span>

					</td>
				</tr>
				<tr>
					<th valign=top width=120>Delay</th>
					<td>
						<input name="wpmk_settings[txt_delay]" type="text" size=20 value='<?php echo $options['txt_delay'];?>'>
						<span style="color:#666666;margin-left:2px;">How long should it be in seconds before the popup appears?</span>
					</td>
				</tr>
				<tr>
					<th valign=top width=120>Fadeout Seconds</th>
					<td>
						<input name="wpmk_settings[txt_fadeout]" type="text" size=20 value='<?php echo $options['txt_fadeout'];?>'>
						<span style="color:#666666;margin-left:2px;">How many seconds should the popup remain on the screen?</span>
					</td>
				</tr>
				<tr>
					<th valign=top width=120>Show Close Button</th>
					<td class='content'>
						<input name="wpmk_settings[chk_enable_close]" type="checkbox" <?php if ($options['chk_enable_close'] ) echo 'checked="checked"'; ?>
						<span style="color:#666666;margin-left:2px;">Should we include a close button on the popup screen?</span>
					</td>
				</tr>
				<tr>
					<th valign=top width=120>Show 'Don't Show'</th>
					<td class='content'>
						<input name="wpmk_settings[chk_enable_dontshow]" type="checkbox" <?php if ($options['chk_enable_dontshow'] ) echo 'checked="checked"'; ?>>&nbsp; Style: <input type=text size=15 name="wpmk_settings[txt_dontshow_style]">
						<span style="color:#666666;margin-left:2px;">Should we include a "don't show" link on the popup screen? If so, what style?</span>
					</td>
				</tr>
				<tr>
					<th valign=top width=120>Popup Content</th>
					<td class='content'>
						<textarea id='content' name="wpmk_settings[txt_content]" rows=10 cols=80><?php echo $options['txt_content'];?></textarea>
						<span style="color:#666666;margin-left:2px;">What content would you like to display inside the popup?</span>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit" align=center>
		<input type="submit" class="button-primary" value="Save Changes" />
	</form>
<p align=center>
Follow me on <a href=http://www.twitter.com/mlynn>twitter</a>, or visit my <a href=http://www.mlynn.org/>weblog</a>.
<br>

<?php

}

function wpmk_init() {
	$tmp = get_option( 'wpmk_settings' );
	if ( $tmp['chk_enable_plugin'] ) {
		wp_enqueue_script( "jquery");
		wp_enqueue_style( "wp-meerkat", WP_PLUGIN_URL.'/wp-meerkat/css/meerkat.css' );
		wp_enqueue_script( "meerkat", WP_PLUGIN_URL.'/wp-meerkat/js/meerkat.js',array('jquery') );
		wp_enqueue_script( "wp-meerkat", WP_PLUGIN_URL.'/wp-meerkat/renderer.php' );
	}

}

function wpmk_admin_init() {
	register_setting( 'wpmk_settings_fields', 'wpmk_settings', 'wpmk_validate_settings' );
}

function wpmk_defaults() {
    $tmp = get_option('wpmk_settings');
    if(($tmp['chk_default_settings_db']=='1')||(!is_array($tmp))) {
                delete_option('wpmk_settings');
                $arr = array(
                        'txt_background_style' => '#000 url(/path/to/bgimage.jpg) no-repeat left top;',
                        'txt_width' => '100%',
                        'txt_height' => '120px',
                        'txt_position' => 'bottom',
                        'txt_opacity' => '0.75',
                        'txt_animation_in' => 'slide',
                        'txt_animation_out' => 'slide',
                        'txt_animation_speed' => 'normal',
                        'txt_delay' => '2',
                        'txt_content' => '<div style="text-align: center;">Welcome to my blog!</div>',
                        chk_enable_dontshow=>FALSE,
                        chk_enable_close=>FALSE,
                        chk_donate=>FALSE,
                        chk_enable_plugin=>TRUE
                );
                update_option('wpmk_settings', $arr);
        }
}

function wpmk_delete_plugin_settings() {
        delete_option('wpmk_settings');
}

function wpmk_validate_settings($input) {

        $input['txt_background_style'] =  wp_filter_nohtml_kses($input['txt_background_style']);
        $input['txt_width'] =  wp_filter_nohtml_kses($input['txt_width']);
        $input['txt_height'] =  wp_filter_nohtml_kses($input['txt_height']);
        $input['txt_position'] =  wp_filter_nohtml_kses($input['txt_position']);
        $input['txt_opacity'] =  wp_filter_nohtml_kses($input['txt_opacity']);
        $input['txt_animation_in'] =  wp_filter_nohtml_kses($input['txt_animation_in']);
        $input['txt_animation_out'] =  wp_filter_nohtml_kses($input['txt_animation_out']);
        $input['txt_animation_speed'] =  wp_filter_nohtml_kses($input['txt_animation_speed']);
        $input['txt_delay'] =  wp_filter_nohtml_kses($input['txt_delay']);
        return $input;

}
