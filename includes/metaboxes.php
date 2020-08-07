<?php
/**
 * Metaboxes
 *
 * @package toptal.test
 */

/**
 * Register Breaking News metabox for Post
 *
 * @return void
 */
function toptalbn_add_metabox() {
	add_meta_box(
		'toptalbn',
		__( 'Breaking News', 'toptalbn' ),
		'toptalbn_render_metabox',
		'post'
	);
}
add_action( 'add_meta_boxes', 'toptalbn_add_metabox' );

/**
 * Render Metabox for single Post
 *
 * @param WP_Post $post Current Post.
 * @return void
 */
function toptalbn_render_metabox( $post = null ) {
	if ( ! $post ) {
		return;
	}

	$options = toptalbn_options();

	$toptalbn_is_breaking  = get_post_meta( $post->ID, 'toptalbn_is_breaking', true );
	$toptalbn_custom_title = get_post_meta( $post->ID, 'toptalbn_custom_title', true );
	$toptalbn_expire       = get_post_meta( $post->ID, 'toptalbn_expire', true );
	$toptalbn_expire_at    = get_post_meta( $post->ID, 'toptalbn_expire_at', true );

	$date_format = get_option( 'date_format' );
	$time_format = get_option( 'time_format' );

	?>
	<div class="wrap">
		<table class="form-table" role="presentation">
			<tr>
				<th><label for="toptalbn_is_breaking"><?php esc_html_e( 'Make this post breaking news', 'toptalbn' ); ?></label></th>
				<td>
					<input
						type="checkbox"
						name="toptalbn_is_breaking"
						id="toptalbn_is_breaking"
						<?php checked( $toptalbn_is_breaking ); ?> />
				</td>
			</tr>
			<tr>
				<th><label for="toptalbn_custom_title"><?php esc_html_e( 'Custom title', 'toptalbn' ); ?></label></th>
				<td>
					<input
						type="text"
						name="toptalbn_custom_title"
						id="toptalbn_custom_title"
						value="<?php echo esc_attr( $toptalbn_custom_title ); ?>" />
				</td>
			</tr>
			<tr>
				<th><label for="toptalbn_expire"><?php esc_html_e( 'Expire', 'toptalbn' ); ?></label></th>
				<td>
					<input
						type="checkbox"
						name="toptalbn_expire"
						id="toptalbn_expire"
						<?php checked( $toptalbn_expire ); ?> />

					<?php $display = $toptalbn_expire ? '' : 'style="display: none;"'; ?>

					<span id="toptalbn_expire_at_field" <?php echo $display; //phpcs:ignore WordPress.Security.EscapeOutput -- static string ?>>
						<input
							type="text"
							name="toptalbn_expire_at"
							id="toptalbn_expire_at"
							value="<?php echo esc_attr( $toptalbn_expire_at ); ?>" />
					</span>
				</td>
			</tr>
		</table>
	</div>
	<?php
}

/**
 * Save breaking news attributes with the post
 *
 * @param integer $post_id Saved Pot ID.
 * @return void
 */
function toptalbn_save_post( $post_id ) {

	//phpcs:disable WordPress.Security.NonceVerification.Missing

	if ( array_key_exists( 'toptalbn_is_breaking', $_POST ) ) {
		update_post_meta( $post_id, 'toptalbn_is_breaking', 1 );
	} else {
		update_post_meta( $post_id, 'toptalbn_is_breaking', 0 );
	}

	if ( array_key_exists( 'toptalbn_custom_title', $_POST ) ) {
		update_post_meta( $post_id, 'toptalbn_custom_title', sanitize_text_field( wp_unslash( $_POST['toptalbn_custom_title'] ) ) );
	}

	if ( array_key_exists( 'toptalbn_expire', $_POST ) ) {
		update_post_meta( $post_id, 'toptalbn_expire', 1 );
	} else {
		update_post_meta( $post_id, 'toptalbn_expire', 0 );
	}

	if ( array_key_exists( 'toptalbn_expire_at', $_POST ) ) {
		update_post_meta( $post_id, 'toptalbn_expire_at', sanitize_text_field( wp_unslash( $_POST['toptalbn_expire_at'] ) ) );
	}

	//phpcs:enable WordPress.Security.NonceVerification.Missing

}
add_action( 'save_post', 'toptalbn_save_post' );
