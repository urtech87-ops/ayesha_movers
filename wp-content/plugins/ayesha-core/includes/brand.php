<?php
/**
 * Share image (Open Graph) and the SVG browser-tab icon.
 *
 * The share image is a 1200 x 630 PNG: the logo on navy with the main phone number
 * from Business Info, drawn with GD. It is built when the main number changes in
 * Business Info, or with the "Rebuild share image" button there. A client's own
 * image can be used instead (Business Info → Share image).
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

const AYESHA_CORE_SHARE_OPTION = 'ayesha_share_image';
const AYESHA_CORE_SVG_ICON_META = '_ayesha_svg_icon';

/**
 * Draw the share image for a phone number and save it in uploads/ayesha/.
 *
 * @param string $phone Number as people read it, e.g. "+973 3444 8236".
 * @return array{file:string,phone:string,built:int}|WP_Error
 */
function ayesha_core_build_share_image( $phone ) {
	if ( ! function_exists( 'imagecreatetruecolor' ) || ! function_exists( 'imagettftext' ) ) {
		return new WP_Error( 'ayesha_no_gd', __( 'This server cannot draw images (the PHP GD extension with FreeType is missing).', 'ayesha-core' ) );
	}

	$w    = 1200;
	$h    = 630;
	$img  = imagecreatetruecolor( $w, $h );
	$navy = imagecolorallocate( $img, 0x0C, 0x12, 0x39 );
	$gold = imagecolorallocate( $img, 0xF2, 0xB7, 0x05 );
	imagefilledrectangle( $img, 0, 0, $w - 1, $h - 1, $navy );
	imagefilledrectangle( $img, 0, 0, $w - 1, 7, $gold ); // The site's gold rule, along the top.

	// Logo: the footer version (white and gold), drawn at 132px tall.
	$logo = imagecreatefrompng( AYESHA_CORE_DIR . 'assets/img/og-logo.png' );
	if ( ! $logo ) {
		imagedestroy( $img );
		return new WP_Error( 'ayesha_no_logo', __( 'The logo file for the share image is missing.', 'ayesha-core' ) );
	}
	$left = 96;
	imagealphablending( $img, true );
	imagecopy( $img, $logo, $left, 150, 0, 0, imagesx( $logo ), imagesy( $logo ) );
	imagedestroy( $logo );

	// The main number in gold, Roboto Bold, shrunk if a long number would not fit.
	$font = AYESHA_CORE_DIR . 'assets/fonts/roboto-latin-700.ttf';
	$size = 72; // GD sizes are in points: 72pt = 96px.
	$max  = $w - $left * 2 - 8;
	do {
		$box   = imagettfbbox( $size, 0, $font, $phone );
		$width = $box[2] - $box[0];
		if ( $width <= $max ) {
			break;
		}
		$size -= 2;
	} while ( $size > 24 );
	if ( '' !== $phone ) {
		// Lined up with the carton's left edge (8px inside the logo file).
		imagettftext( $img, $size, 0, $left + 8 - $box[0], 470, $gold, $font, $phone );
	}

	$uploads = wp_upload_dir();
	if ( ! empty( $uploads['error'] ) ) {
		imagedestroy( $img );
		return new WP_Error( 'ayesha_uploads', $uploads['error'] );
	}
	$dir = trailingslashit( $uploads['basedir'] ) . 'ayesha';
	if ( ! wp_mkdir_p( $dir ) ) {
		imagedestroy( $img );
		return new WP_Error( 'ayesha_mkdir', __( 'Could not create the uploads/ayesha folder.', 'ayesha-core' ) );
	}

	// The number is in the file name, so sites that cached the old image fetch the new one.
	$digits = preg_replace( '/\D/', '', $phone );
	$name   = 'share-image' . ( '' !== $digits ? '-' . $digits : '' ) . '.png';
	$ok     = imagepng( $img, $dir . '/' . $name, 9 );
	imagedestroy( $img );
	if ( ! $ok ) {
		return new WP_Error( 'ayesha_write', __( 'Could not save the share image.', 'ayesha-core' ) );
	}

	// Remove the previous file when its name changed.
	$old = get_option( AYESHA_CORE_SHARE_OPTION );
	if ( is_array( $old ) && ! empty( $old['file'] ) && 'ayesha/' . $name !== $old['file'] ) {
		$old_path = trailingslashit( $uploads['basedir'] ) . $old['file'];
		if ( 0 === strpos( wp_normalize_path( $old_path ), wp_normalize_path( $dir ) . '/share-image' ) && is_file( $old_path ) ) {
			wp_delete_file( $old_path );
		}
	}

	$data = array(
		'file'  => 'ayesha/' . $name,
		'phone' => $phone,
		'built' => time(),
	);
	update_option( AYESHA_CORE_SHARE_OPTION, $data, false );
	return $data;
}

add_action( 'update_option_' . AYESHA_CORE_OPTION, 'ayesha_core_share_image_on_save', 10, 2 );
add_action( 'add_option_' . AYESHA_CORE_OPTION, 'ayesha_core_share_image_on_add', 10, 2 );

/**
 * Rebuild the share image when the main number changes in Business Info.
 *
 * @param mixed $old   Previous value.
 * @param mixed $value New value.
 */
function ayesha_core_share_image_on_save( $old, $value ) {
	$before = is_array( $old ) ? (string) ( $old['phone_primary'] ?? '' ) : '';
	$after  = is_array( $value ) ? (string) ( $value['phone_primary'] ?? '' ) : '';
	$built  = get_option( AYESHA_CORE_SHARE_OPTION );
	if ( $before !== $after || ! is_array( $built ) || ( $built['phone'] ?? null ) !== $after ) {
		ayesha_core_build_share_image( $after );
	}
}

/**
 * First save of Business Info.
 *
 * @param string $option Option name.
 * @param mixed  $value  Value.
 */
function ayesha_core_share_image_on_add( $option, $value ) {
	ayesha_core_share_image_on_save( array(), $value );
}

/**
 * The share image to use: the client's own (Business Info) or the built one.
 *
 * @return array{url:string,width:int,height:int,alt:string}|null
 */
function ayesha_core_share_image() {
	$custom = (string) ayesha_core_setting( 'share_image_url' );
	if ( '' !== $custom ) {
		return array(
			'url'    => $custom,
			'width'  => 0,
			'height' => 0,
			'alt'    => AYESHA_CORE_BUSINESS_NAME,
		);
	}
	$built = get_option( AYESHA_CORE_SHARE_OPTION );
	if ( ! is_array( $built ) || empty( $built['file'] ) ) {
		return null;
	}
	$uploads = wp_upload_dir( null, false );
	if ( ! is_file( trailingslashit( $uploads['basedir'] ) . $built['file'] ) ) {
		return null;
	}
	return array(
		'url'    => trailingslashit( $uploads['baseurl'] ) . $built['file'],
		'width'  => 1200,
		'height' => 630,
		/* translators: %s: main phone number. */
		'alt'    => AYESHA_CORE_BUSINESS_NAME . ( '' !== $built['phone'] ? ', ' . sprintf( __( 'call %s', 'ayesha-core' ), $built['phone'] ) : '' ),
	);
}

add_action( 'wp_head', 'ayesha_core_og_image_tags', 3 );

/**
 * og:image tags on every front-end page.
 */
function ayesha_core_og_image_tags() {
	$image = ayesha_core_share_image();
	if ( ! $image ) {
		return;
	}
	printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $image['url'] ) );
	if ( $image['width'] ) {
		printf( '<meta property="og:image:width" content="%d" />' . "\n", (int) $image['width'] );
		printf( '<meta property="og:image:height" content="%d" />' . "\n", (int) $image['height'] );
	}
	printf( '<meta property="og:image:alt" content="%s" />' . "\n", esc_attr( $image['alt'] ) );
}

add_action( 'admin_post_ayesha_rebuild_share_image', 'ayesha_core_rebuild_share_image_action' );

/**
 * "Rebuild share image" button on Business Info.
 */
function ayesha_core_rebuild_share_image_action() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to change these settings.', 'ayesha-core' ), 403 );
	}
	check_admin_referer( 'ayesha_rebuild_share_image' );
	$result = ayesha_core_build_share_image( (string) ayesha_core_setting( 'phone_primary' ) );
	$status = is_wp_error( $result ) ? 'error' : 'rebuilt';
	wp_safe_redirect( add_query_arg( 'ayesha_share', $status, admin_url( 'options-general.php?page=ayesha-business-info' ) ) );
	exit;
}

/**
 * The "Share image" box under the Business Info form.
 */
function ayesha_core_render_share_image_box() {
	$built = get_option( AYESHA_CORE_SHARE_OPTION );
	$image = ayesha_core_share_image();
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display only.
	$status = isset( $_GET['ayesha_share'] ) ? sanitize_key( $_GET['ayesha_share'] ) : '';
	?>
	<h2 id="share-image"><?php esc_html_e( 'Share image', 'ayesha-core' ); ?></h2>
	<p><?php esc_html_e( 'The picture shown when someone shares a link to the website on WhatsApp, Facebook and similar apps: the logo on navy with the main phone number. It is rebuilt automatically when you change the main number above.', 'ayesha-core' ); ?></p>
	<?php if ( 'rebuilt' === $status ) : ?>
		<div class="notice notice-success inline"><p><?php esc_html_e( 'Share image rebuilt.', 'ayesha-core' ); ?></p></div>
	<?php elseif ( 'error' === $status ) : ?>
		<div class="notice notice-error inline"><p><?php esc_html_e( 'The share image could not be built on this server.', 'ayesha-core' ); ?></p></div>
	<?php endif; ?>
	<?php if ( $image ) : ?>
		<p><img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" width="480" style="max-width:100%;height:auto;border:1px solid #dcdcde"></p>
	<?php endif; ?>
	<?php if ( '' !== (string) ayesha_core_setting( 'share_image_url' ) ) : ?>
		<p><?php esc_html_e( 'Your own image (the "Own share image" link above) is used instead of the built one.', 'ayesha-core' ); ?></p>
	<?php elseif ( is_array( $built ) ) : ?>
		<?php /* translators: %s: phone number. */ ?>
		<p><?php echo esc_html( sprintf( __( 'Built with the number %s.', 'ayesha-core' ), $built['phone'] ) ); ?></p>
	<?php endif; ?>
	<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
		<input type="hidden" name="action" value="ayesha_rebuild_share_image">
		<?php wp_nonce_field( 'ayesha_rebuild_share_image' ); ?>
		<?php submit_button( __( 'Rebuild share image', 'ayesha-core' ), 'secondary', 'submit', false ); ?>
	</form>
	<?php
}

add_action( 'wp_head', 'ayesha_core_svg_site_icon', 100 );

/**
 * The SVG version of the browser-tab icon, after WordPress's PNG icons (wp_site_icon, priority 99).
 * Printed only while the Site Icon is the AYESHA carton icon, so a new icon chosen in
 * Site Identity is never overridden by the old SVG.
 */
function ayesha_core_svg_site_icon() {
	$icon = (int) get_option( 'site_icon' );
	if ( ! $icon || ! get_post_meta( $icon, AYESHA_CORE_SVG_ICON_META, true ) ) {
		return;
	}
	printf(
		'<link rel="icon" href="%s" type="image/svg+xml" />' . "\n",
		esc_url( AYESHA_CORE_URL . 'assets/img/site-icon.svg?ver=' . AYESHA_CORE_VERSION )
	);
}
