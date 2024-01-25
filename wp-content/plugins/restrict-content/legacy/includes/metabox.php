<?php
/**
 * Meta Box
 *
 * @package     Restrict Content
 * @subpackage  Meta Box
 * @copyright   Copyright (c) 2017, Restrict Content Pro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

/**
 * Get meta box settings and fields
 *
 * @return array
 */
function rc_get_metabox() {
	$fields = array(
		'id'       => 'rcMetaBox',
		'title'    => __( 'Restrict this content', 'restrict-content' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name'    => __( 'User Level', 'restrict-content' ),
				'id'      => 'rcp_user_level',
				'type'    => 'select',
				'desc'    => __( 'Choose the user level that can see this page / post', 'restrict-content' ),
				'options' => array(
					'None'          => __( 'None', 'restrict-content' ),
					'Administrator' => __( 'Administrator', 'restrict-content' ),
					'Editor'        => __( 'Editor', 'restrict-content' ),
					'Author'        => __( 'Author', 'restrict-content' ),
					'Contributor'   => __( 'Contributor', 'restrict-content' ),
					'Subscriber'    => __( 'Subscriber', 'restrict-content' )
				),
				'std'     => 'None'
			),
			array(
				'name' => __( 'Hide from Feed?', 'restrict-content' ),
				'id'   => 'rcFeedHide',
				'type' => 'checkbox',
				'desc' => __( 'Hide the excerpt of this post / page from the Feed?', 'restrict-content' ),
				'std'  => ''
			)
		)
	);

	return apply_filters( 'rc_metabox_fields', $fields );

}

/**
 * Add meta box to supported post types
 *
 * @return void
 */
function rcAddMetaBoxes() {

	$metabox = rc_get_metabox();

	$post_types = get_post_types( array( 'public' => true, 'show_ui' => true ), 'objects' );
	foreach ( $post_types as $page ) {

		$exclude = apply_filters( 'rcp_metabox_excluded_post_types', array(
			'forum',
			'topic',
			'reply',
			'product',
			'attachment'
		) );

		if ( ! in_array( $page->name, $exclude ) ) {
			add_meta_box( $metabox['id'], $metabox['title'], 'rcShowMetaBox', $page->name, $metabox['context'], $metabox['priority'] );
		}
	}
}

add_action( 'add_meta_boxes', 'rcAddMetaBoxes' );


/**
 * Render meta box
 *
 * @return void
 */
function rcShowMetaBox() {

	global $post;

	$metabox             = rc_get_metabox();

	// Use nonce for verification
	echo '<input type="hidden" name="rcMetaNonce" value="' . esc_attr( wp_create_nonce( basename( __FILE__ ) ) ) . '" />';

	echo '<table class="form-table">';


	echo '<tr><td colspan="3">';
	printf(
		/* translators: %s: shortcode tag */
		__( 'Use these options to restrict this entire entry, or the %1$s shortcode to restrict partial content.', 'restrict-content' ),
		'<code>[restrict ...] ... [/restrict]</code>'
	);
	echo '</td></tr>';

	foreach ( $metabox['fields'] as $field ) {

		// get current post meta data
		$meta = get_post_meta( $post->ID, $field['id'], true );

		echo '<tr>';
		echo '<th style="width:20%"><label for="' . esc_attr( $field['id'] ) . '">' . $field['name'] . '</label></th>';
		echo '<td>';
		switch ( $field['type'] ) {
			case 'select':
				echo '<select name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '">';
				foreach ( $field['options'] as $option => $label ) {
					echo '<option' . selected( $meta, $option, false ) . ' value="' . esc_attr( $option ) . '">' . $label . '</option>';
				}
				echo '</select>';
				break;
			case 'checkbox':
				echo '<input type="checkbox" name="' . esc_attr( $field['id'] ), '" id="' . esc_attr( $field['id'] ) . '"' . checked( 'on', $meta, false ) . ' />';
				break;
		}
		echo '<td>' . $field['desc'] . '</td><td>';
		echo '</tr>';
	}

	echo '</table>';

	echo '<hr>';

	$utm_args = array(
		'utm_source'   => 'integration',
		'utm_medium'   => 'admin',
		'utm_campaign' => 'restrict-content',
		'utm_content'  => urlencode( $post->post_type ),
	);
	$url      = add_query_arg( $utm_args, 'https://restrictcontentpro.com/' );

	echo '<div class="rcp-go-pro-wrapper-metabox"><h4>';
	printf(
		/* translators: %s: href restrict content pro */
		__( 'Unlock more restriction options with %1$s', 'restrict-content' ),
		'<a href="https://restrictcontentpro.com/pricing" target="_blank">Restrict Content Pro</a>'
	);
	echo '</h4>';
	echo '<p>' .
		sprintf(
			__( 'Need more flexibility with restrictions? Restrict Content Pro enables you to restrict content based on subscription levels, user levels, custom roles, and more! <a href="%s" target="_blank" rel="noopener noreferrer">Learn more...</a>', 'restrict-content' ),
			esc_url( $url )
		) .
		'</p>';
	echo '</div>';

}

/**
 * Save meta box data
 *
 * @param int $post_id
 *
 * @return void
 */
function rcSaveData( $post_id ) {

	if ( empty( $_POST['rcMetaNonce'] ) ) {
		return;
	}

	// verify nonce
	if ( ! wp_verify_nonce( $_POST['rcMetaNonce'], basename( __FILE__ ) ) ) {
		return;
	}

	// check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// check permissions
	if ( 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {

		return;

	}

	$metabox = rc_get_metabox();

	foreach ( $metabox['fields'] as $field ) {

		$old = get_post_meta( $post_id, $field['id'], true );
		$new = isset( $_POST[ $field['id'] ] ) ? sanitize_text_field( $_POST[ $field['id'] ] ) : '';

		if ( $new && $new != $old ) {

			update_post_meta( $post_id, $field['id'], $new );

		} elseif ( '' == $new && $old ) {

			delete_post_meta( $post_id, $field['id'], $old );

		}
	}
}

add_action( 'save_post', 'rcSaveData' );