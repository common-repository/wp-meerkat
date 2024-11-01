<?php
/**
 * renderer.php - Part of the wp-meerkat wordpress plugin
 * Renders Javascript for Meerkat popups
 * @author Michael Lynn
 * @version $Id$
 * @copyright Michael Lynn, 25 November, 2010
 **/

include_once('../../../wp-config.php');
include_once('../../../wp-config.php');
include_once('../../../wp-load.php');
include_once('../../../wp-admin/includes/taxonomy.php');
include_once('../../../wp-includes/wp-db.php');
/* Create a quickie Template Class */
class Template {
   public $template;

   function load($filepath) {
      $this->template = file_get_contents($filepath);
   }

   function replace($var, $content) {
      $this->template = str_replace("#$var#", $content, $this->template);
   }

   function publish() {
     		eval("?>".$this->template."<?");
   }
}
/* Instansiate a quickie Template Class */
$template = new Template;
/* Pull in the template */
$template->load(WP_PLUGIN_DIR.'/wp-meerkat/js/meerkat_template.js');
$options = get_option('wpmk_settings');
if ($options['chk_enable_plugin']) {
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-core');
	wp_enqueue_script("wp-meerkat",WP_PLUGIN_URL.'/wp-meerkat/meerkat.js');
	wp_enqueue_style( "wp-meerkat", WP_PLUGIN_URL.'/wp-meerkat/meerkat.css' );
	$tmp = get_option( 'wpmk_settings' );
	$template->replace('background_style',$tmp['txt_background_style']);
	$template->replace('height',$tmp['txt_height']);
	$template->replace('width',$tmp['txt_width']);
	$template->replace('opacity',$tmp['txt_opacity']);
	$template->replace('position',$tmp['txt_position']);
	$template->replace('fadeout',$tmp['txt_fadeout']);
	$template->replace('animation_in',$tmp['txt_animation_in']);
	$template->replace('animation_out',$tmp['txt_animation_out']);
	$template->replace('animation_speed',$tmp['txt_animation_speed']);
	$template->replace('delay',$tmp['txt_delay']);
	$template->replace('content',$tmp['txt_content']);
	if ($tmp['chk_enable_close'] ) {
		$template->replace('close',"close: '.close-meerkat',");
	} else {
		$template->replace('close','');
	}
	$template->publish();
}
