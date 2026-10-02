<?php
/**
 * Plugin Name:       AYESHA Core
 * Description:       Business Info settings, Block Bindings, the quote form and Enquiries, shortcodes, structured data, search-engine titles, WebP image sizes and mail settings for the AYESHA Movers & Packers website.
 * Version:           0.6.0
 * Requires at least: 6.9
 * Requires PHP:      8.0
 * Author:            AYESHA Movers & Packers
 * License:           GPL-2.0-or-later
 * Text Domain:       ayesha-core
 *
 * @package AyeshaCore
 */

defined( 'ABSPATH' ) || exit;

define( 'AYESHA_CORE_VERSION', '0.6.0' );
define( 'AYESHA_CORE_FILE', __FILE__ );
define( 'AYESHA_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'AYESHA_CORE_URL', plugin_dir_url( __FILE__ ) );

/** Fixed business facts that are not edited on the settings page. */
define( 'AYESHA_CORE_BUSINESS_NAME', 'AYESHA Movers & Packers' );
define( 'AYESHA_CORE_ALTERNATE_NAME', 'AYESHA Cargo Handling' );
define( 'AYESHA_CORE_MANAGER_NAME', 'Mohammad Ayub Khokhear' );

require_once AYESHA_CORE_DIR . 'includes/settings.php';
require_once AYESHA_CORE_DIR . 'includes/helpers.php';
require_once AYESHA_CORE_DIR . 'includes/bindings.php';
require_once AYESHA_CORE_DIR . 'includes/shortcodes.php';
require_once AYESHA_CORE_DIR . 'includes/schema.php';
require_once AYESHA_CORE_DIR . 'includes/mail.php';
require_once AYESHA_CORE_DIR . 'includes/frontend.php';
require_once AYESHA_CORE_DIR . 'includes/seo.php';
require_once AYESHA_CORE_DIR . 'includes/media.php';
require_once AYESHA_CORE_DIR . 'includes/enquiries.php';
require_once AYESHA_CORE_DIR . 'includes/quote-form.php';
require_once AYESHA_CORE_DIR . 'includes/quote-render.php';
require_once AYESHA_CORE_DIR . 'includes/quote-mail.php';

register_activation_hook( __FILE__, 'ayesha_core_activate' );

/**
 * Store the default Business Info on first activation (never overwrites saved values).
 */
function ayesha_core_activate() {
	add_option( AYESHA_CORE_OPTION, ayesha_core_defaults() );
}
