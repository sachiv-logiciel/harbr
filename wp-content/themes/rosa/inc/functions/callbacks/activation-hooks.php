<?php
/*  for PRO users! - *
 * Theme activation hook
 */
function wpgrade_callback_geting_active() {

	/*  for PRO users! - *
	 * Get the config from /config/activation.php
	 */
	$activation_settings = array();
	if ( file_exists( wpgrade::themepath() . 'config/activation' . EXT ) ) {
		$activation_settings = include wpgrade::themepath() . 'config/activation' . EXT;
	}

	/*  for PRO users! - *
	 * Make sure pixlikes has the right settings
	 */
	if ( isset( $activation_settings['pixlikes-settings'] ) ) {
		$pixlikes_settings = $activation_settings['pixlikes-settings'];
		update_option( 'pixlikes_settings', $pixlikes_settings );
	}


	/*  for PRO users! - *
	 * Create custom post types, taxonomies and metaboxes
	 * These will be taken by pixtypes plugin and converted in their own options
	 */

	if ( isset( $activation_settings['pixtypes-settings'] ) ) {

		$pixtypes_conf_settings = $activation_settings['pixtypes-settings'];

		$types_options = get_option( 'pixtypes_themes_settings' );
		if ( empty( $types_options ) ) {
			$types_options = array();
		}

		$theme_key                   = wpgrade::shortname() . '_pixtypes_theme';
		$types_options[ $theme_key ] = $pixtypes_conf_settings;

		update_option( 'pixtypes_themes_settings', $types_options );
	}

	/*  for PRO users! - *
	 * http://wordpress.stackexchange.com/questions/36152/flush-rewrite-rules-not-working-on-plugin-deactivation-invalid-urls-not-showing
	 */
	delete_option( 'rewrite_rules' );
}

add_action( 'after_switch_theme', 'wpgrade_callback_geting_active' );
