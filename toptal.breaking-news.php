<?php
/**
 * Toptal Breaking News Plugin
 *
 * Plugin Name:  Toptal Breaking News
 * Description:  Breaking news feature for Toptal technical screening
 * Version:      1.0
 * Author:       Max Lyuchin
 * Text Domain:  toptalbn
 *
 * @package toptal.test
 */

/*
The goal is to feature an individual post as “breaking news.”
Anything that is a “breaking news” story has to be visible on every page and always shown in the same way.
The displayed “breaking news” item also needs to have a link to the actual post.

The plugin options page must include the following configurable items:
[x] a text input field for the title of the breaking news area (e.g. “BREAKING NEWS:”)
[x] a color picker for the background color
[x] a color picker for the text color
[x] a display of the active breaking news post title and a link to edit that post

In the post editor, add a metabox with the following fields:
[x] A checkbox with the legend “Make this post breaking news” that activates this post as “breaking news” when checked
[x] A text field containing a custom title that will be shown instead of the post title (if empty, display the post title)
[x] A checkbox to set an expiration date and time. If checked, add an option to select the date and time. When the required time expires, the post should not be marked as “breaking news” anymore.

Front-end instructions:
[x] If there is a post set as “breaking news,” display a full width div at the bottom of the site’s header with this format:
	[Breaking news title from backend]: [post title \| custom title]
[x] There can be only one active breaking news post at a time, which should be the post that was activated last.

Extra features:
[x] Add setting to specify custom selector for themes.
*/

define( 'TOPTALBN_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'TOPTALBN_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'TOPTALBN_PLUGIN_VERSION', '1.0' );

require_once TOPTALBN_PLUGIN_DIR . '/includes/core.php';
require_once TOPTALBN_PLUGIN_DIR . '/includes/settings.php';
require_once TOPTALBN_PLUGIN_DIR . '/includes/metaboxes.php';
require_once TOPTALBN_PLUGIN_DIR . '/includes/shortcodes.php';
require_once TOPTALBN_PLUGIN_DIR . '/includes/cron.php';

/**
 * Create cron job to disable expired breaking news
 *
 * @return void
 */
function toptalbn_register_cron_jobs() {
	if ( ! wp_next_scheduled( 'toptalbn_cron' ) ) {
		wp_schedule_event( time(), 'hourly', 'toptalbn_cron' );
	}
}
register_activation_hook( __FILE__, 'toptalbn_register_cron_jobs' );

/**
 * Plugin scripts and styles
 *
 * @return void
 */
function toptalbn_scripts() {
	wp_register_script(
		'toptalbn',
		TOPTALBN_PLUGIN_URL . '/assets/app.js',
		array( 'jquery' ),
		TOPTALBN_PLUGIN_VERSION,
		true
	);

	$options = toptalbn_options();
	wp_localize_script( 'toptalbn', 'TOPTALBN', $options );

	wp_enqueue_script( 'toptalbn' );

	wp_enqueue_style(
		'toptalbn',
		TOPTALBN_PLUGIN_URL . '/assets/style.css',
		array(),
		TOPTALBN_PLUGIN_VERSION
	);

	$options = toptalbn_options();

	$custom_css = "#toptalbn {
		color: {$options['color']};
		background: {$options['background_color']};
	}";

	wp_add_inline_style( 'toptalbn', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'toptalbn_scripts' );

/**
 * Admin scripts
 *
 * @return void
 */
function toptalbn_admin_scripts() {

	if ( is_admin() ) {
		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_style(
			'toptalbn-datetimepicker',
			TOPTALBN_PLUGIN_URL . '/assets/vendor/jquery.datetimepicker.css',
			array(),
			TOPTALBN_PLUGIN_VERSION
		);

		wp_enqueue_script(
			'toptalbn-moment',
			TOPTALBN_PLUGIN_URL . '/assets/vendor/moment.js',
			array(),
			TOPTALBN_PLUGIN_VERSION,
			true
		);

		wp_enqueue_script(
			'toptalbn-datetimepicker',
			TOPTALBN_PLUGIN_URL . '/assets/vendor/jquery.datetimepicker.js',
			array( 'jquery', 'toptalbn-moment' ),
			TOPTALBN_PLUGIN_VERSION,
			true
		);

		wp_enqueue_script(
			'toptalbn-admin',
			TOPTALBN_PLUGIN_URL . '/assets/admin.js',
			array( 'wp-color-picker', 'toptalbn-datetimepicker' ),
			TOPTALBN_PLUGIN_VERSION,
			true
		);
	}
}
add_action( 'admin_enqueue_scripts', 'toptalbn_admin_scripts' );


/**
 * Output Breaking News to the page footer. Will be moved to page header with jQuery.
 *
 * @return void
 */
function toptalbn_display_breaking_news() {
	echo do_shortcode( '[toptal_breaking_news]' );
}
add_action( 'wp_footer', 'toptalbn_display_breaking_news' );
