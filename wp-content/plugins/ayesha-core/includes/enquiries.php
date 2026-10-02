<?php
/**
 * Enquiries: the private post type that stores every quote request, with reference numbers
 * (AYM-YYMMDD-NNN), a status (New / Contacted / Done) and the admin list.
 *
 * Only users who can edit posts see the menu. Enquiries can't be created by hand
 * (only the quote form makes them) and are never public.
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

const AYESHA_CORE_ENQUIRY = 'ayesha_enquiry';

add_action( 'init', 'ayesha_core_register_enquiries' );

/**
 * Register the post type.
 */
function ayesha_core_register_enquiries() {
	register_post_type(
		AYESHA_CORE_ENQUIRY,
		array(
			'labels'              => array(
				'name'               => __( 'Enquiries', 'ayesha-core' ),
				'singular_name'      => __( 'Enquiry', 'ayesha-core' ),
				'menu_name'          => __( 'Enquiries', 'ayesha-core' ),
				'all_items'          => __( 'All enquiries', 'ayesha-core' ),
				'edit_item'          => __( 'Enquiry', 'ayesha-core' ),
				'view_item'          => __( 'View enquiry', 'ayesha-core' ),
				'search_items'       => __( 'Search enquiries', 'ayesha-core' ),
				'not_found'          => __( 'No enquiries yet. Quote requests sent from the Contact Us page appear here.', 'ayesha-core' ),
				'not_found_in_trash' => __( 'No enquiries in the Trash.', 'ayesha-core' ),
				'item_updated'       => __( 'Enquiry updated.', 'ayesha-core' ),
			),
			'public'              => false,
			'publicly_queryable'  => false,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'show_in_rest'        => false,
			'menu_position'       => 26,
			'menu_icon'           => 'dashicons-email-alt',
			'capability_type'     => 'post',
			'capabilities'        => array( 'create_posts' => 'do_not_allow' ),
			'map_meta_cap'        => true,
			'supports'            => false,
			'rewrite'             => false,
			'query_var'           => false,
			'can_export'          => true,
			'delete_with_user'    => false,
		)
	);
}

/**
 * The three statuses.
 *
 * @return array<string, string> Key => label.
 */
function ayesha_core_enquiry_statuses() {
	return array(
		'new'       => __( 'New', 'ayesha-core' ),
		'contacted' => __( 'Contacted', 'ayesha-core' ),
		'done'      => __( 'Done', 'ayesha-core' ),
	);
}

/**
 * An enquiry's status key.
 *
 * @param int $post_id Enquiry ID.
 * @return string
 */
function ayesha_core_enquiry_status( $post_id ) {
	$status = (string) get_post_meta( $post_id, '_ayesha_status', true );
	return isset( ayesha_core_enquiry_statuses()[ $status ] ) ? $status : 'new';
}

/**
 * Next reference number for today (site time zone): AYM-YYMMDD-NNN.
 *
 * @return string
 */
function ayesha_core_enquiry_next_reference() {
	$day     = wp_date( 'ymd' );
	$counter = get_option( 'ayesha_enquiry_counter', array() );
	$number  = ( is_array( $counter ) && ( $counter['day'] ?? '' ) === $day ) ? (int) $counter['n'] + 1 : 1;
	update_option(
		'ayesha_enquiry_counter',
		array(
			'day' => $day,
			'n'   => $number,
		),
		false
	);
	return sprintf( 'AYM-%s-%03d', $day, $number );
}

/**
 * Save a quote request.
 *
 * @param array  $data      Clean values (see ayesha_core_quote_validate()).
 * @param string $reference Reference number.
 * @return int|WP_Error Post ID.
 */
function ayesha_core_enquiry_save( array $data, $reference ) {
	$post_id = wp_insert_post(
		array(
			'post_type'   => AYESHA_CORE_ENQUIRY,
			'post_status' => 'private',
			'post_title'  => $reference,
			'post_author' => 0,
		),
		true
	);
	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}
	// update_post_meta() unslashes, so slash first: the values keep any backslashes the customer typed.
	update_post_meta( $post_id, '_ayesha_ref', $reference );
	update_post_meta( $post_id, '_ayesha_status', 'new' );
	update_post_meta( $post_id, '_ayesha_enquiry', wp_slash( $data ) );
	return (int) $post_id;
}

/**
 * The saved values of an enquiry.
 *
 * @param int $post_id Enquiry ID.
 * @return array
 */
function ayesha_core_enquiry_data( $post_id ) {
	$data = get_post_meta( $post_id, '_ayesha_enquiry', true );
	return is_array( $data ) ? $data : array();
}

/*
 * ---------------------------------------------------------------------------
 * Admin: list table
 * ---------------------------------------------------------------------------
 */

add_filter( 'manage_' . AYESHA_CORE_ENQUIRY . '_posts_columns', 'ayesha_core_enquiry_columns' );

/**
 * Columns: date, reference, name, phone, move type, from → to, preferred date, status.
 *
 * @param array<string, string> $columns Default columns.
 * @return array<string, string>
 */
function ayesha_core_enquiry_columns( $columns ) {
	return array(
		'cb'          => $columns['cb'] ?? '<input type="checkbox" />',
		'date'        => __( 'Received', 'ayesha-core' ),
		'title'       => __( 'Reference', 'ayesha-core' ),
		'aq_name'     => __( 'Name', 'ayesha-core' ),
		'aq_phone'    => __( 'Phone', 'ayesha-core' ),
		'aq_move'     => __( 'Move type', 'ayesha-core' ),
		'aq_route'    => __( 'From → to', 'ayesha-core' ),
		'aq_when'     => __( 'Preferred date', 'ayesha-core' ),
		'aq_status'   => __( 'Status', 'ayesha-core' ),
	);
}

add_action( 'manage_' . AYESHA_CORE_ENQUIRY . '_posts_custom_column', 'ayesha_core_enquiry_column', 10, 2 );

/**
 * Column values. Everything the customer typed is escaped here.
 *
 * @param string $column  Column key.
 * @param int    $post_id Enquiry ID.
 */
function ayesha_core_enquiry_column( $column, $post_id ) {
	$data = ayesha_core_enquiry_data( $post_id );
	switch ( $column ) {
		case 'aq_name':
			echo esc_html( $data['name'] ?? '' );
			break;
		case 'aq_phone':
			$e164 = $data['phone_e164'] ?? '';
			echo $e164 ? ayesha_core_link( 'tel:' . $e164, (string) ( $data['phone'] ?? $e164 ) ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper.
			break;
		case 'aq_move':
			echo esc_html( ayesha_core_quote_label( 'move_type', $data['move_type'] ?? '' ) );
			break;
		case 'aq_route':
			echo esc_html( ( $data['from'] ?? '' ) . ' → ' . ( $data['to'] ?? '' ) );
			break;
		case 'aq_when':
			echo esc_html( ayesha_core_quote_date_text( $data ) );
			break;
		case 'aq_status':
			$status = ayesha_core_enquiry_status( $post_id );
			printf(
				'<span class="ayesha-status ayesha-status--%1$s">%2$s</span>',
				esc_attr( $status ),
				esc_html( ayesha_core_enquiry_statuses()[ $status ] )
			);
			break;
	}
}

add_filter( 'post_date_column_status', 'ayesha_core_enquiry_date_status', 10, 2 );

/**
 * The date column says "Received" (WordPress would say "Last Modified" for a private post).
 *
 * @param string  $status Label.
 * @param WP_Post $post   Post.
 * @return string
 */
function ayesha_core_enquiry_date_status( $status, $post ) {
	return AYESHA_CORE_ENQUIRY === $post->post_type ? __( 'Received', 'ayesha-core' ) : $status;
}

add_filter( 'list_table_primary_column','ayesha_core_enquiry_primary_column', 10, 2 );

/**
 * The reference is the main column (row actions sit under it).
 *
 * @param string $default Default column.
 * @param string $screen  Screen ID.
 * @return string
 */
function ayesha_core_enquiry_primary_column( $default, $screen ) {
	return 'edit-' . AYESHA_CORE_ENQUIRY === $screen ? 'title' : $default;
}

add_filter( 'post_row_actions', 'ayesha_core_enquiry_row_actions', 10, 2 );

/**
 * Row actions: Open and Trash only (no Quick Edit, no Preview).
 *
 * @param array<string, string> $actions Actions.
 * @param WP_Post               $post    Post.
 * @return array<string, string>
 */
function ayesha_core_enquiry_row_actions( $actions, $post ) {
	if ( AYESHA_CORE_ENQUIRY !== $post->post_type ) {
		return $actions;
	}
	unset( $actions['inline hide-if-no-js'], $actions['view'], $actions['preview'] );
	if ( isset( $actions['edit'] ) ) {
		$actions['edit'] = sprintf( '<a href="%s">%s</a>', esc_url( get_edit_post_link( $post->ID ) ), esc_html__( 'Open', 'ayesha-core' ) );
	}
	return $actions;
}

add_action( 'restrict_manage_posts', 'ayesha_core_enquiry_status_filter' );

/**
 * "All statuses / New / Contacted / Done" filter above the list.
 *
 * @param string $post_type Post type of the list.
 */
function ayesha_core_enquiry_status_filter( $post_type ) {
	if ( AYESHA_CORE_ENQUIRY !== $post_type ) {
		return;
	}
	$current = isset( $_GET['aq_status'] ) ? sanitize_key( wp_unslash( $_GET['aq_status'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only filter.
	echo '<label for="aq-status-filter" class="screen-reader-text">' . esc_html__( 'Filter by status', 'ayesha-core' ) . '</label>';
	echo '<select name="aq_status" id="aq-status-filter"><option value="">' . esc_html__( 'All statuses', 'ayesha-core' ) . '</option>';
	foreach ( ayesha_core_enquiry_statuses() as $key => $label ) {
		printf( '<option value="%1$s"%2$s>%3$s</option>', esc_attr( $key ), selected( $current, $key, false ), esc_html( $label ) );
	}
	echo '</select>';
}

add_action( 'pre_get_posts', 'ayesha_core_enquiry_filter_query' );

/**
 * Apply the status filter; newest first by default.
 *
 * @param WP_Query $query Query.
 */
function ayesha_core_enquiry_filter_query( $query ) {
	if ( ! is_admin() || ! $query->is_main_query() || AYESHA_CORE_ENQUIRY !== $query->get( 'post_type' ) ) {
		return;
	}
	if ( ! $query->get( 'orderby' ) ) {
		$query->set( 'orderby', 'date' );
		$query->set( 'order', 'DESC' );
	}
	$status = isset( $_GET['aq_status'] ) ? sanitize_key( wp_unslash( $_GET['aq_status'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only filter.
	if ( '' === $status || ! isset( ayesha_core_enquiry_statuses()[ $status ] ) ) {
		return;
	}
	$query->set(
		'meta_query',
		array(
			array(
				'key'   => '_ayesha_status',
				'value' => $status,
			),
		)
	);
}

add_filter( 'bulk_actions-edit-' . AYESHA_CORE_ENQUIRY, 'ayesha_core_enquiry_bulk_actions' );

/**
 * Bulk actions: mark as New / Contacted / Done. (No bulk Edit.)
 *
 * @param array<string, string> $actions Actions.
 * @return array<string, string>
 */
function ayesha_core_enquiry_bulk_actions( $actions ) {
	unset( $actions['edit'] );
	foreach ( ayesha_core_enquiry_statuses() as $key => $label ) {
		/* translators: %s: status name. */
		$actions[ 'aq_mark_' . $key ] = sprintf( __( 'Mark as %s', 'ayesha-core' ), $label );
	}
	return $actions;
}

add_filter( 'handle_bulk_actions-edit-' . AYESHA_CORE_ENQUIRY, 'ayesha_core_enquiry_handle_bulk', 10, 3 );

/**
 * Change the status of the ticked enquiries. WordPress has already checked the list's nonce.
 *
 * @param string $redirect Redirect URL.
 * @param string $action   Action.
 * @param int[]  $ids      Post IDs.
 * @return string
 */
function ayesha_core_enquiry_handle_bulk( $redirect, $action, $ids ) {
	if ( ! str_starts_with( $action, 'aq_mark_' ) ) {
		return $redirect;
	}
	$status = substr( $action, 8 );
	if ( ! isset( ayesha_core_enquiry_statuses()[ $status ] ) ) {
		return $redirect;
	}
	$changed = 0;
	foreach ( (array) $ids as $id ) {
		$id = (int) $id;
		if ( AYESHA_CORE_ENQUIRY === get_post_type( $id ) && current_user_can( 'edit_post', $id ) ) {
			update_post_meta( $id, '_ayesha_status', $status );
			++$changed;
		}
	}
	return add_query_arg( 'aq_marked', $changed, $redirect );
}

add_action( 'admin_notices', 'ayesha_core_enquiry_bulk_notice' );

/**
 * "2 enquiries updated."
 */
function ayesha_core_enquiry_bulk_notice() {
	$screen = get_current_screen();
	if ( ! $screen || 'edit-' . AYESHA_CORE_ENQUIRY !== $screen->id || ! isset( $_GET['aq_marked'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	$count = absint( $_GET['aq_marked'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	/* translators: %d: number of enquiries. */
	printf( '<div class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( sprintf( _n( '%d enquiry updated.', '%d enquiries updated.', $count, 'ayesha-core' ), $count ) ) );
}

add_action( 'admin_menu', 'ayesha_core_enquiry_menu_count', 20 );

/**
 * Number of New enquiries next to the menu item.
 */
function ayesha_core_enquiry_menu_count() {
	global $menu;
	if ( ! current_user_can( 'edit_posts' ) || ! is_array( $menu ) ) {
		return;
	}
	$new = count(
		get_posts(
			array(
				'post_type'      => AYESHA_CORE_ENQUIRY,
				'post_status'    => 'private',
				'fields'         => 'ids',
				'posts_per_page' => 100,
				'meta_key'       => '_ayesha_status', // phpcs:ignore WordPress.DB.SlowDBQuery
				'meta_value'     => 'new', // phpcs:ignore WordPress.DB.SlowDBQuery
			)
		)
	);
	if ( ! $new ) {
		return;
	}
	foreach ( $menu as $i => $item ) {
		if ( 'edit.php?post_type=' . AYESHA_CORE_ENQUIRY === ( $item[2] ?? '' ) ) {
			/* translators: %d: number of new enquiries. */
			$menu[ $i ][0] .= sprintf( ' <span class="awaiting-mod"><span aria-hidden="true">%1$d</span><span class="screen-reader-text">%2$s</span></span>', $new, esc_html( sprintf( _n( '%d new enquiry', '%d new enquiries', $new, 'ayesha-core' ), $new ) ) ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride
		}
	}
}

/*
 * ---------------------------------------------------------------------------
 * Admin: the enquiry screen (details + status)
 * ---------------------------------------------------------------------------
 */

add_action( 'add_meta_boxes_' . AYESHA_CORE_ENQUIRY, 'ayesha_core_enquiry_meta_boxes' );

/**
 * Details (read-only) and Status boxes.
 */
function ayesha_core_enquiry_meta_boxes() {
	add_meta_box( 'ayesha-enquiry-details', __( 'Quote request', 'ayesha-core' ), 'ayesha_core_enquiry_details_box', null, 'normal', 'high' );
	add_meta_box( 'ayesha-enquiry-status', __( 'Status', 'ayesha-core' ), 'ayesha_core_enquiry_status_box', null, 'side', 'high' );
}

/**
 * Details box: every field, with tap-to-call / WhatsApp / email links to the customer.
 *
 * @param WP_Post $post Enquiry.
 */
function ayesha_core_enquiry_details_box( $post ) {
	$data = ayesha_core_enquiry_data( $post->ID );
	echo '<p style="font-size:14px"><strong>' . esc_html( get_post_meta( $post->ID, '_ayesha_ref', true ) ) . '</strong>, ' . esc_html( get_the_date( 'j F Y, H:i', $post ) ) . '</p>';
	echo '<p>' . ayesha_core_quote_contact_links_html( $data ) . '</p>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped in the helper.
	echo '<table class="widefat striped" style="max-width:48rem"><tbody>';
	foreach ( ayesha_core_quote_rows( $data ) as $row ) {
		printf( '<tr><th scope="row" style="width:14rem">%1$s</th><td>%2$s</td></tr>', esc_html( $row[0] ), nl2br( esc_html( $row[1] ), false ) );
	}
	echo '</tbody></table>';
	$mail = get_post_meta( $post->ID, '_ayesha_mail', true );
	if ( is_array( $mail ) ) {
		echo '<p class="description">' . esc_html(
			sprintf(
				/* translators: 1: yes/no, 2: yes/no/not asked. */
				__( 'Email to you sent: %1$s. Confirmation to the customer sent: %2$s.', 'ayesha-core' ),
				! empty( $mail['admin'] ) ? __( 'yes', 'ayesha-core' ) : __( 'no (check the email settings)', 'ayesha-core' ),
				null === ( $mail['customer'] ?? null ) ? __( 'no email address given', 'ayesha-core' ) : ( $mail['customer'] ? __( 'yes', 'ayesha-core' ) : __( 'no', 'ayesha-core' ) )
			)
		) . '</p>';
	}
}

/**
 * Status box.
 *
 * @param WP_Post $post Enquiry.
 */
function ayesha_core_enquiry_status_box( $post ) {
	wp_nonce_field( 'ayesha_enquiry_status', '_ayesha_status_nonce' );
	$current = ayesha_core_enquiry_status( $post->ID );
	echo '<fieldset><legend class="screen-reader-text">' . esc_html__( 'Status', 'ayesha-core' ) . '</legend>';
	foreach ( ayesha_core_enquiry_statuses() as $key => $label ) {
		printf(
			'<p><label><input type="radio" name="ayesha_status" value="%1$s"%2$s> %3$s</label></p>',
			esc_attr( $key ),
			checked( $current, $key, false ),
			esc_html( $label )
		);
	}
	echo '</fieldset><p class="description">' . esc_html__( 'Pick one, then click Update.', 'ayesha-core' ) . '</p>';
}

add_action( 'save_post_' . AYESHA_CORE_ENQUIRY, 'ayesha_core_enquiry_save_status' );

/**
 * Save the status from the enquiry screen (nonce + capability checked).
 *
 * @param int $post_id Enquiry ID.
 */
function ayesha_core_enquiry_save_status( $post_id ) {
	if ( ! isset( $_POST['_ayesha_status_nonce'] ) || ! wp_verify_nonce( sanitize_key( wp_unslash( $_POST['_ayesha_status_nonce'] ) ), 'ayesha_enquiry_status' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	$status = isset( $_POST['ayesha_status'] ) ? sanitize_key( wp_unslash( $_POST['ayesha_status'] ) ) : '';
	if ( isset( ayesha_core_enquiry_statuses()[ $status ] ) ) {
		update_post_meta( $post_id, '_ayesha_status', $status );
	}
}

add_filter( 'wp_insert_post_data', 'ayesha_core_enquiry_keep_private', 10, 2 );

/**
 * An enquiry is always private, whatever is picked in the Publish box.
 *
 * @param array $data    Post data about to be saved.
 * @param array $postarr Raw post data.
 * @return array
 */
function ayesha_core_enquiry_keep_private( $data, $postarr ) {
	if ( AYESHA_CORE_ENQUIRY === $data['post_type'] && ! in_array( $data['post_status'], array( 'trash', 'auto-draft' ), true ) ) {
		$data['post_status']   = 'private';
		$data['post_password'] = '';
	}
	return $data;
}

add_action( 'admin_head', 'ayesha_core_enquiry_admin_css' );

/**
 * Small styles for the status labels and the enquiry screen.
 */
function ayesha_core_enquiry_admin_css() {
	$screen = get_current_screen();
	if ( ! $screen || AYESHA_CORE_ENQUIRY !== $screen->post_type ) {
		return;
	}
	echo '<style>
.ayesha-status{display:inline-block;padding:2px 8px;border-radius:3px;font-weight:600;background:#e8ebe9;color:#0f4d4a}
.ayesha-status--new{background:#f2b705;color:#0f4d4a}
.ayesha-status--done{background:#fff;border:1px solid #3f6f6b;color:#3f6f6b}
.post-type-ayesha_enquiry #misc-publishing-actions,.post-type-ayesha_enquiry #minor-publishing-actions{display:none}
</style>';
}
