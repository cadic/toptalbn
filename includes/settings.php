<?php
/**
 * Plugin Settings
 *
 * @package toptal.test
 */

/**
 * Get Breaking News options
 *
 * @return array
 */
function toptalbn_options() {
	return get_option(
		'toptalbn_options',
		array(
			'prefix'           => 'Breaking News:',
			'color'            => '#ffffff',
			'background_color' => '#000000',
		)
	);
}

/**
 * Plugin Options
 *
 * @return void
 */
function toptalbn_add_plugin_options() {
	add_options_page(
		'Breaking News',
		'Breaking News',
		'manage_options',
		'breaking-news',
		'toptalbn_create_admin_page'
	);
}
add_action( 'admin_menu', 'toptalbn_add_plugin_options' );

/**
 * Render Admin Page
 *
 * @return void
 */
function toptalbn_create_admin_page() {
	$toptalbn_options = get_option( 'toptalbn_options' );
	?>

		<div class="wrap">
			<h1>Breaking News</h1>

			<form method="post" action="options.php">
				<?php
				settings_fields( 'toptalbn_option_group' );
				do_settings_sections( 'breaking-news-admin' );
				submit_button();
				?>
			</form>
		</div>

	<?php
}

/**
 * Initialize options
 *
 * @return void
 */
function toptalbn_options_init() {
	register_setting(
		'toptalbn_option_group',
		'toptalbn_options',
		'toptalbn_sanitize'
	);

	add_settings_section(
		'toptalbn_setting_section',
		__( 'Settings', 'toptalbn' ),
		'toptalbn_section_info',
		'breaking-news-admin'
	);

	add_settings_field(
		'prefix',
		__( 'Prefix', 'toptalbn' ),
		'toptalbn_prefix_callback',
		'breaking-news-admin',
		'toptalbn_setting_section'
	);

	add_settings_field(
		'color',
		__( 'Color', 'toptalbn' ),
		'toptalbn_color_callback',
		'breaking-news-admin',
		'toptalbn_setting_section',
		array( 'class' => 'toptalbn-color-wrap' ),
	);

	add_settings_field(
		'background_color',
		__( 'Background Color', 'toptalbn' ),
		'toptalbn_background_color_callback',
		'breaking-news-admin',
		'toptalbn_setting_section',
		array( 'class' => 'toptalbn-background-wrap' ),
	);

	add_settings_field(
		'current',
		__( 'Current', 'toptalbn' ),
		'toptalbn_current_callback',
		'breaking-news-admin',
		'toptalbn_setting_section',
		array( 'class' => 'toptalbn-current-wrap' ),
	);

	add_settings_field(
		'selector',
		__( 'Selector', 'toptalbn' ),
		'toptalbn_selector_callback',
		'breaking-news-admin',
		'toptalbn_setting_section'
	);

}
add_action( 'admin_init', 'toptalbn_options_init' );

/**
 * Sanitize options
 *
 * @param array $input Options to sanitize.
 * @return array
 */
function toptalbn_sanitize( $input ) {
	$sanitary_values = array();
	if ( isset( $input['prefix'] ) ) {
		$sanitary_values['prefix'] = sanitize_text_field( $input['prefix'] );
	}

	if ( isset( $input['color'] ) ) {
		$sanitary_values['color'] = sanitize_hex_color( $input['color'] );
	}

	if ( isset( $input['background_color'] ) ) {
		$sanitary_values['background_color'] = sanitize_hex_color( $input['background_color'] );
	}

	if ( isset( $input['selector'] ) ) {
		$sanitary_values['selector'] = sanitize_text_field( $input['selector'] );
	}

	return $sanitary_values;
}

/**
 * Section Info
 *
 * @return void
 */
function toptalbn_section_info() {
}

/**
 * Render Prefix Field
 *
 * @return void
 */
function toptalbn_prefix_callback() {
	$toptalbn_options = get_option( 'toptalbn_options' );

	printf(
		'<input class="regular-text" type="text" name="toptalbn_options[prefix]" id="prefix" value="%s">',
		isset( $toptalbn_options['prefix'] ) ? esc_attr( $toptalbn_options['prefix'] ) : 'Breaking News:'
	);
}

/**
 * Render Color Field
 *
 * @return void
 */
function toptalbn_color_callback() {
	$toptalbn_options = get_option( 'toptalbn_options' );

	printf(
		'<input class="color-picker" type="text" name="toptalbn_options[color]" id="color" value="%s">',
		isset( $toptalbn_options['color'] ) ? esc_attr( $toptalbn_options['color'] ) : '#ffffff'
	);
}

/**
 * Render Background Field
 *
 * @return void
 */
function toptalbn_background_color_callback() {
	$toptalbn_options = get_option( 'toptalbn_options' );

	printf(
		'<input class="color-picker" type="text" name="toptalbn_options[background_color]" id="background_color" value="%s">',
		isset( $toptalbn_options['background_color'] ) ? esc_attr( $toptalbn_options['background_color'] ) : '#000000'
	);
}

/**
 * Render Current Breaking News
 *
 * @return void
 */
function toptalbn_current_callback() {
	$breaking_news = toptalbn_get_breaking_news();

	if ( $breaking_news ) {
		?>
		<p>
			<a href="<?php echo esc_url( $breaking_news['url'] ); ?>"><?php echo esc_html( $breaking_news['title'] ); ?></a>
			<?php edit_post_link( __( '(edit)', 'toptalbn' ), '', '', $breaking_news['ID'] ); ?>
		</p>
		<?php
	} else {
		?>
		<p><?php esc_html_e( 'No Breaking News to display', 'toptalbn' ); ?> <a href="edit.php"></a></p>
		<?php
	}
}

/**
 * Custom selector to search correct header DOM object
 *
 * @return void
 */
function toptalbn_selector_callback() {
	$toptalbn_options = get_option( 'toptalbn_options' );

	printf(
		'<input class="regular-text" type="text" name="toptalbn_options[selector]" id="selector" value="%s">',
		isset( $toptalbn_options['selector'] ) ? esc_attr( $toptalbn_options['selector'] ) : ''
	);

	printf(
		'<p class="description">%s</p>',
		esc_html__( 'You can specify custom jQuery selector for your theme header if plugin is unable to detect your header element.', 'toptalbn' )
	);

}
