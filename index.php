<?php
/*
 * Plugin Name: PB Newsletter Generator
 * Description: A plugin using Active Campaign to generate Newsletters
 * Author: By PebbleBed - Titans
 * Version: 1.0.0
 */

// Validate that the Nextgen Gallery Plugin is active in the site or DIE
include_once (ABSPATH . 'wp-admin/includes/plugin.php');
if (! is_plugin_active ( 'activecampaign-subscription-forms/activecampaign.php' )) {
    wp_die ( 'PB Newsletter Generator requires Active Campaign, please install or activate it. <br /> <a href="/wp-admin/plugins.php">Go back to Plugins</a>' );
}

defined( 'PB_NEWSLETTER_GEN_PATH' ) || define( 'PB_NEWSLETTER_GEN_PATH', __DIR__ );
defined( 'PB_NEWSLETTER_GEN_URL' ) || define( 'PB_NEWSLETTER_GEN_URL', plugin_dir_url( __FILE__ ) );

if ( is_admin() ) {

	require PB_NEWSLETTER_GEN_PATH . '/lib/Templates/Templates.php';
	require PB_NEWSLETTER_GEN_PATH . '/lib/Utils/Utils.php';
	require PB_NEWSLETTER_GEN_PATH . '/lib/Ajax.php';
	require PB_NEWSLETTER_GEN_PATH . '/lib/Admin/Admin.php';

	$pb_newsletter_gen_admin_ajax  = new PB_Newsletter_Gen_Admin_Ajax();
	$pb_newsletter_gen_admin = new PB_Newsletter_Gen_Admin();

	register_activation_hook( __FILE__, array( $pb_newsletter_gen_admin, 'install' ) );
	register_deactivation_hook( __FILE__, array( $pb_newsletter_gen_admin, 'uninstall' ) );

}