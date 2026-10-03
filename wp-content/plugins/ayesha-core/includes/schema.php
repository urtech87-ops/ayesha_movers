<?php
/**
 * MovingCompany JSON-LD (design plan section 7).
 *
 * No aggregateRating/review (no real reviews yet), no foundingDate, no availableLanguage.
 * A PostalAddress is added only when the Business Info address is filled in.
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', 'ayesha_core_print_schema', 20 );

/**
 * The six services, from the client's own list.
 *
 * @return array<int, array{name: string, description: string}>
 */
function ayesha_core_schema_services() {
	return array(
		array(
			'name'        => 'House, villa, flat and office shifting',
			'description' => 'Packing, loading, unloading, setting up inside the new home and removing the packing debris.',
		),
		array(
			'name'        => 'Packing and unpacking',
			'description' => 'International-standard packing, with special packing for fragile items and crockery.',
		),
		array(
			'name'        => 'Furniture dismantling and refitting',
			'description' => 'Carpenters dismantle and refit furniture and curtains, and set up new furniture for homes and offices.',
		),
		array(
			'name'        => 'Appliance removal and fitting',
			'description' => 'Removal and fitting of split units and air conditioners, LCD and LED TVs, curtains and blinds.',
		),
		array(
			'name'        => 'Truck hire and transport',
			'description' => 'Dyna and 6-wheel trucks for 8 hours or a full day; runs to Mina Salman, Khalifa Bin Salman Port, the airport and courier depots (DHL, Aramex, GLS).',
		),
		array(
			'name'        => 'GCC cargo',
			'description' => '20ft and 40ft container loading and unloading, customs documents, and moves to Saudi Arabia, the UAE, Kuwait, Qatar and Oman.',
		),
	);
}

/**
 * Build the schema array from the settings.
 *
 * @return array<string, mixed>
 */
function ayesha_core_schema() {
	$s    = ayesha_core_settings();
	$home = home_url( '/' );

	$phones = array();
	foreach ( array( 'phone_primary', 'phone_secondary', 'phone_office' ) as $key ) {
		$e164 = ayesha_core_e164( $s[ $key ] );
		if ( '' !== $e164 && ! in_array( $e164, $phones, true ) ) {
			$phones[] = $e164;
		}
	}

	$schema = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'MovingCompany',
		'@id'           => $home . '#business',
		'name'          => AYESHA_CORE_BUSINESS_NAME,
		'alternateName' => AYESHA_CORE_ALTERNATE_NAME,
		'url'           => $home,
	);

	if ( $phones ) {
		$schema['telephone'] = $phones[0];
	}
	if ( '' !== $s['email'] ) {
		$schema['email'] = $s['email'];
	}

	/**
	 * Media Library ID of the image given to search engines.
	 * Off (0) until the client confirms the truck in photo 9 is his; then this default becomes 9.
	 *
	 * @param int $id Attachment ID; 0 to leave the image out.
	 */
	$image_id = (int) apply_filters( 'ayesha_core_schema_image_id', 0 );
	$image    = $image_id ? wp_get_attachment_image_url( $image_id, 'full' ) : false;
	if ( $image ) {
		$schema['image'] = $image;
	}

	$schema['employee'] = array(
		'@type'    => 'Person',
		'name'     => AYESHA_CORE_MANAGER_NAME,
		'jobTitle' => 'General Manager',
	);

	if ( $phones ) {
		$schema['contactPoint'] = array_map(
			static function ( $phone ) {
				return array(
					'@type'       => 'ContactPoint',
					'telephone'   => $phone,
					'contactType' => 'customer service',
				);
			},
			$phones
		);
	}

	$schema['openingHoursSpecification'] = array(
		'@type'     => 'OpeningHoursSpecification',
		'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday' ),
		'opens'     => '00:00',
		'closes'    => '23:59',
	);

	$area_served = array( array( '@type' => 'Country', 'name' => 'Bahrain' ), array( '@type' => 'City', 'name' => 'Manama' ) );
	foreach ( array( 'Saudi Arabia', 'United Arab Emirates', 'Kuwait', 'Qatar', 'Oman' ) as $country ) {
		$area_served[] = array( '@type' => 'Country', 'name' => $country );
	}
	$schema['areaServed'] = $area_served;

	if ( '' !== $s['address'] ) {
		$schema['address'] = array(
			'@type'         => 'PostalAddress',
			'streetAddress' => preg_replace( '/\s*\R\s*/', ', ', trim( $s['address'] ) ),
			'addressCountry' => 'BH',
		);
	}

	$same_as = array_values( array_filter( array( $s['instagram_url'], $s['facebook_url'] ) ) );
	if ( $same_as ) {
		$schema['sameAs'] = $same_as;
	}

	$schema['makesOffer'] = array_map(
		static function ( $service ) {
			return array(
				'@type'       => 'Offer',
				'itemOffered' => array(
					'@type'       => 'Service',
					'name'        => $service['name'],
					'description' => $service['description'],
				),
			);
		},
		ayesha_core_schema_services()
	);

	/**
	 * Filter the MovingCompany schema before output.
	 *
	 * @param array $schema Schema.
	 */
	return apply_filters( 'ayesha_core_schema', $schema );
}

/**
 * Print the JSON-LD.
 */
function ayesha_core_print_schema() {
	$json = wp_json_encode( ayesha_core_schema(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG );
	if ( $json ) {
		echo "<script type=\"application/ld+json\" id=\"ayesha-business-schema\">{$json}</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON with < and > hex-escaped.
	}
}
