<?php
/**
 * Core functions
 *
 * @package toptal.test
 */

/**
 * Return breaking news if exist or false if nothing to display
 *
 * @return array|false
 */
function toptalbn_get_breaking_news() {

	// Current time for expiration.
	$time = date_i18n( 'Y/m/d H:i' );

	$breaking_news = get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_query'     => array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'relation' => 'and',
				array(
					'key'   => 'toptalbn_is_breaking',
					'value' => 1,
				),
				array(
					'relation' => 'or',
					array(
						// Check if expiration isn't set.
						'key'     => 'toptalbn_expire',
						'compare' => 'NOT EXISTS',
					),
					array(
						// Or expiration is disabled.
						'key'   => 'toptalbn_expire',
						'value' => 0,
					),
					array(
						// Or.
						'relation' => 'and',
						array(
							// Expiration is enabled.
							'key'   => 'toptalbn_expire',
							'value' => 1,
						),
						array(
							// And expire time did not happen.
							'key'     => 'toptalbn_expire_at',
							'value'   => $time,
							'compare' => '>',
						),
					),
				),
			),
		)
	);

	$result = false;

	if ( count( $breaking_news ) ) {
		$post_id = $breaking_news[0]->ID;

		// Override custom title.
		$title = get_post_meta( $post_id, 'toptalbn_custom_title', true );

		// Rollback original title if custom is empty.
		if ( empty( $title ) ) {
			$title = get_the_title( $post_id );
		}

		$result = array(
			'ID'    => $post_id,
			'url'   => get_permalink( $post_id ),
			'title' => $title,
		);
	}

	if ( false === $result ) {
		// Force cleanup cron job.
		do_action( 'toptalbn_cron' );
	}

	return $result;
}
