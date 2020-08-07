<?php
/**
 * Shortcodes
 *
 * @package toptal.test
 */

/**
 * Shortcode rendering content for breaking news
 *
 * @return string
 */
function toptalbn_shortcode() {
	$breaking_news = toptalbn_get_breaking_news();

	$content = '';

	if ( false !== $breaking_news ) {
		$options = toptalbn_options();

		ob_start();

		?>
		<a id="toptalbn" class="toptalbn" href="<?php echo esc_url( $breaking_news['url'] ); ?>">
			<span class="toptalbn-prefix"><?php echo esc_html( $options['prefix'] ); ?></span>
			<?php echo esc_html( $breaking_news['title'] ); ?>
		</a>
		<?php

		$content = ob_get_clean();

	}

	return $content;
}
add_shortcode( 'toptal_breaking_news', 'toptalbn_shortcode' );
