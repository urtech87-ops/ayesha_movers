<?php
/**
 * Settings > Business Info.
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

const AYESHA_CORE_OPTION = 'ayesha_business';

/**
 * Default values: the verified client facts.
 *
 * @return array<string, mixed>
 */
function ayesha_core_defaults() {
	return array(
		'phone_primary'    => '+973 3444 8236',
		'phone_secondary'  => '+973 3642 9850',
		'phone_office'     => '+973 7736 0292',
		'whatsapp'         => '97334448236',
		'email'            => 'ayeshamoversbh786@gmail.com',
		'whatsapp_message' => "Hi AYESHA Movers & Packers, I'd like a price for my move.",
		'hours'            => 'Open 24 hours, every day',
		'service_areas'    => implode(
			"\n",
			array(
				'Manama and every city in Bahrain',
				'Mina Salman Port',
				'Khalifa Bin Salman Port',
				'Airport cargo',
				'Saudi Arabia',
				'All GCC countries',
				'UK, USA, Canada and worldwide',
			)
		),
		'instagram_url'    => 'https://www.instagram.com/ayesha_movers_packers/',
		'address'          => '',
		'show_reviews'     => 0,
	);
}

/**
 * Saved settings merged over the defaults.
 *
 * @return array<string, mixed>
 */
function ayesha_core_settings() {
	$saved = get_option( AYESHA_CORE_OPTION, array() );
	return wp_parse_args( is_array( $saved ) ? $saved : array(), ayesha_core_defaults() );
}

/**
 * One setting.
 *
 * @param string $key Setting key.
 * @return mixed
 */
function ayesha_core_setting( $key ) {
	$settings = ayesha_core_settings();
	return $settings[ $key ] ?? '';
}

/**
 * Field definitions for the settings page.
 *
 * @return array<string, array<string, string>>
 */
function ayesha_core_fields() {
	return array(
		'phone_primary'    => array(
			'label' => __( 'Main phone number', 'ayesha-core' ),
			'type'  => 'tel',
			'help'  => __( 'Shown in the header, the hero, the mobile Call button and the footer. Write it the way people should read it, e.g. +973 3444 8236.', 'ayesha-core' ),
		),
		'phone_secondary'  => array(
			'label' => __( 'Second mobile number', 'ayesha-core' ),
			'type'  => 'tel',
			'help'  => '',
		),
		'phone_office'     => array(
			'label' => __( 'Office number', 'ayesha-core' ),
			'type'  => 'tel',
			'help'  => '',
		),
		'whatsapp'         => array(
			'label' => __( 'WhatsApp number', 'ayesha-core' ),
			'type'  => 'whatsapp',
			'help'  => __( 'Digits only, with the country code and no + or spaces, e.g. 97334448236.', 'ayesha-core' ),
		),
		'whatsapp_message' => array(
			'label' => __( 'WhatsApp opening message', 'ayesha-core' ),
			'type'  => 'textarea',
			'help'  => __( 'Typed into the chat for the visitor when they tap a WhatsApp button. They can change it before sending.', 'ayesha-core' ),
		),
		'email'            => array(
			'label' => __( 'Email address', 'ayesha-core' ),
			'type'  => 'email',
			'help'  => __( 'Shown on the site, and used as the sender address of emails the website sends.', 'ayesha-core' ),
		),
		'hours'            => array(
			'label' => __( 'Opening hours', 'ayesha-core' ),
			'type'  => 'text',
			'help'  => '',
		),
		'service_areas'    => array(
			'label' => __( 'Service areas', 'ayesha-core' ),
			'type'  => 'textarea',
			'help'  => __( 'One place per line.', 'ayesha-core' ),
		),
		'instagram_url'    => array(
			'label' => __( 'Instagram page', 'ayesha-core' ),
			'type'  => 'url',
			'help'  => __( 'The full link, starting with https://', 'ayesha-core' ),
		),
		'address'          => array(
			'label' => __( 'Business address', 'ayesha-core' ),
			'type'  => 'textarea',
			'help'  => __( 'Leave empty to keep the address off the website. When filled in, it is also given to search engines.', 'ayesha-core' ),
		),
		'show_reviews'     => array(
			'label' => __( 'Show reviews section', 'ayesha-core' ),
			'type'  => 'checkbox',
			'help'  => __( 'Only switch this on after the example reviews have been replaced with real customer reviews.', 'ayesha-core' ),
		),
	);
}

/**
 * Sanitize the whole settings array.
 *
 * @param mixed $input Raw input.
 * @return array<string, mixed>
 */
function ayesha_core_sanitize( $input ) {
	$input    = is_array( $input ) ? wp_unslash( $input ) : array();
	$old      = ayesha_core_settings();
	$clean    = array();
	$defaults = ayesha_core_defaults();

	foreach ( array( 'phone_primary', 'phone_secondary', 'phone_office' ) as $key ) {
		$value         = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : '';
		$clean[ $key ] = trim( preg_replace( '/[^0-9+()\-\s]/', '', $value ) );
	}

	$whatsapp          = isset( $input['whatsapp'] ) ? preg_replace( '/\D/', '', (string) $input['whatsapp'] ) : '';
	$clean['whatsapp'] = $whatsapp;
	if ( '' !== $whatsapp && ( strlen( $whatsapp ) < 8 || strlen( $whatsapp ) > 15 ) ) {
		add_settings_error( AYESHA_CORE_OPTION, 'whatsapp', __( 'The WhatsApp number must be 8 to 15 digits, including the country code. The previous number was kept.', 'ayesha-core' ) );
		$clean['whatsapp'] = $old['whatsapp'];
	}

	$email = isset( $input['email'] ) ? sanitize_email( $input['email'] ) : '';
	if ( '' !== $email && ! is_email( $email ) ) {
		add_settings_error( AYESHA_CORE_OPTION, 'email', __( 'That email address is not valid. The previous address was kept.', 'ayesha-core' ) );
		$email = $old['email'];
	}
	$clean['email'] = $email;

	$clean['whatsapp_message'] = isset( $input['whatsapp_message'] ) ? sanitize_textarea_field( $input['whatsapp_message'] ) : '';
	$clean['hours']            = isset( $input['hours'] ) ? sanitize_text_field( $input['hours'] ) : '';
	$clean['address']          = isset( $input['address'] ) ? sanitize_textarea_field( $input['address'] ) : '';

	$areas                  = isset( $input['service_areas'] ) ? sanitize_textarea_field( $input['service_areas'] ) : '';
	$areas                  = array_filter( array_map( 'trim', preg_split( '/\R/', $areas ) ) );
	$clean['service_areas'] = implode( "\n", $areas );

	$instagram = isset( $input['instagram_url'] ) ? esc_url_raw( trim( $input['instagram_url'] ), array( 'https' ) ) : '';
	if ( '' !== trim( $input['instagram_url'] ?? '' ) && '' === $instagram ) {
		add_settings_error( AYESHA_CORE_OPTION, 'instagram_url', __( 'The Instagram link must start with https://. The previous link was kept.', 'ayesha-core' ) );
		$instagram = $old['instagram_url'];
	}
	$clean['instagram_url'] = $instagram;

	$clean['show_reviews'] = empty( $input['show_reviews'] ) ? 0 : 1;

	return array_intersect_key( $clean, $defaults );
}

add_action( 'admin_init', 'ayesha_core_register_settings' );

/**
 * Register the setting, section and fields.
 */
function ayesha_core_register_settings() {
	register_setting(
		'ayesha_business_group',
		AYESHA_CORE_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'ayesha_core_sanitize',
			'default'           => ayesha_core_defaults(),
			'show_in_rest'      => false,
		)
	);

	add_settings_section(
		'ayesha_business_main',
		'',
		static function () {
			echo '<p>' . esc_html__( 'These details appear across the whole website: header, footer, buttons and contact page. Change a number here and it changes everywhere.', 'ayesha-core' ) . '</p>';
		},
		'ayesha-business-info'
	);

	foreach ( ayesha_core_fields() as $key => $field ) {
		add_settings_field(
			$key,
			esc_html( $field['label'] ),
			'ayesha_core_render_field',
			'ayesha-business-info',
			'ayesha_business_main',
			array(
				'key'       => $key,
				'field'     => $field,
				'label_for' => 'checkbox' === $field['type'] ? null : 'ayesha-' . $key,
			)
		);
	}
}

/**
 * Render one field.
 *
 * @param array $args Field args.
 */
function ayesha_core_render_field( $args ) {
	$key   = $args['key'];
	$field = $args['field'];
	$value = ayesha_core_setting( $key );
	$id    = 'ayesha-' . $key;
	$name  = AYESHA_CORE_OPTION . '[' . $key . ']';
	$desc  = $field['help'] ? ' aria-describedby="' . esc_attr( $id . '-help' ) . '"' : '';

	switch ( $field['type'] ) {
		case 'textarea':
			printf(
				'<textarea id="%1$s" name="%2$s" rows="%3$d" class="large-text"%5$s>%4$s</textarea>',
				esc_attr( $id ),
				esc_attr( $name ),
				'service_areas' === $key ? 8 : 3,
				esc_textarea( $value ),
				$desc // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from esc_attr above.
			);
			break;
		case 'checkbox':
			printf(
				'<label for="%1$s"><input type="checkbox" id="%1$s" name="%2$s" value="1"%3$s%5$s> %4$s</label>',
				esc_attr( $id ),
				esc_attr( $name ),
				checked( 1, (int) $value, false ),
				esc_html__( 'Show the reviews section on the website', 'ayesha-core' ),
				$desc // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			break;
		default:
			$type  = array(
				'tel'      => 'tel',
				'whatsapp' => 'text',
				'email'    => 'email',
				'url'      => 'url',
			)[ $field['type'] ] ?? 'text';
			$extra = 'whatsapp' === $field['type'] ? ' inputmode="numeric" pattern="[0-9]{8,15}"' : '';
			printf(
				'<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="regular-text"%5$s%6$s>',
				esc_attr( $type ),
				esc_attr( $id ),
				esc_attr( $name ),
				esc_attr( $value ),
				$extra, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static string.
				$desc // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
	}

	if ( $field['help'] ) {
		printf( '<p class="description" id="%1$s">%2$s</p>', esc_attr( $id . '-help' ), esc_html( $field['help'] ) );
	}
}

add_action( 'admin_menu', 'ayesha_core_admin_menu' );

/**
 * Add Settings > Business Info.
 */
function ayesha_core_admin_menu() {
	add_options_page(
		__( 'Business Info', 'ayesha-core' ),
		__( 'Business Info', 'ayesha-core' ),
		'manage_options',
		'ayesha-business-info',
		'ayesha_core_render_page'
	);
}

/**
 * Render the settings page.
 */
function ayesha_core_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to change these settings.', 'ayesha-core' ) );
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Business Info', 'ayesha-core' ); ?></h1>
		<?php settings_errors( AYESHA_CORE_OPTION ); ?>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'ayesha_business_group' );
			do_settings_sections( 'ayesha-business-info' );
			submit_button( __( 'Save business info', 'ayesha-core' ) );
			?>
		</form>
	</div>
	<?php
}

add_filter( 'plugin_action_links_' . plugin_basename( AYESHA_CORE_FILE ), 'ayesha_core_action_links' );

/**
 * Link to the settings page from the Plugins screen.
 *
 * @param string[] $links Links.
 * @return string[]
 */
function ayesha_core_action_links( $links ) {
	$url = admin_url( 'options-general.php?page=ayesha-business-info' );
	array_unshift( $links, '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Business Info', 'ayesha-core' ) . '</a>' );
	return $links;
}
