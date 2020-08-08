<?php
/**
 * Cron functions
 *
 * @package toptal.test
 */

/**
 * Cron job to disable all expired breaking news
 *
 * @return void
 */
function toptalbn_cron() {
	$time = date_i18n( 'Y/m/d H:i' );

	$posts = get_posts(
		array(
			'post_type'  => 'post',
			'meta_query' => array( //phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
				'relation' => 'and',
				array(
					// Is breaking news.
					'key'   => 'toptalbn_is_breaking',
					'value' => 1,
				),
				array(
					// And expiration is enabled.
					'key'   => 'toptalbn_expire',
					'value' => 1,
				),
				array(
					// And expire time happen.
					'key'     => 'toptalbn_expire_at',
					'value'   => $time,
					'compare' => '<=',
				),
			),
		)
	);

	foreach ( $posts as $_post ) {
		update_post_meta( $_post->ID, 'toptalbn_is_breaking', 0 );
	}
}
add_action( 'toptalbn_cron', 'toptalbn_cron' );
