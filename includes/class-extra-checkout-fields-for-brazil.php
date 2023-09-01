<?php
/**
 * Extra checkout fields main class.
 *
 * @package Extra_Checkout_Fields_For_Brazil
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Plugin main class.
 */
class Extra_Checkout_Fields_For_Brazil {

	/**
	* Plugin version.
	*
	* @var string
	*/
	const VERSION = '3.9.0';

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin.
	 */
	private function __construct() {
		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		if ( class_exists( 'WooCommerce' ) ) {
			if ( is_admin() ) {
				$this->admin_includes();
			}

			$this->includes();
			add_filter( 'plugin_action_links_' . plugin_basename( CSBMW_PLUGIN_FILE ), array( $this, 'plugin_action_links' ) );

			// Add HPOS compatibility
            add_action('before_woocommerce_init', array($this, 'hposCompatibility'));
		} else {
			add_action( 'admin_notices', array( $this, 'woocommerce_fallback_notice' ) );
		}
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Get assets url.
	 *
	 * @return string
	 */
	public static function get_assets_url() {
		return plugins_url( 'assets/', CSBMW_PLUGIN_FILE );
	}

	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'woocommerce-extra-checkout-fields-for-brazil', false, dirname( plugin_basename( CSBMW_PLUGIN_FILE ) ) . '/languages/' );
	}

	/**
	 * Includes.
	 */
	private function includes() {
		include_once dirname( CSBMW_PLUGIN_FILE ) . '/includes/class-extra-checkout-fields-for-brazil-formatting.php';
		include_once dirname( CSBMW_PLUGIN_FILE ) . '/includes/class-extra-checkout-fields-for-brazil-front-end.php';
		include_once dirname( CSBMW_PLUGIN_FILE ) . '/includes/class-extra-checkout-fields-for-brazil-integrations.php';
		include_once dirname( CSBMW_PLUGIN_FILE ) . '/includes/class-extra-checkout-fields-for-brazil-api.php';
	}

	/**
	 * Admin includes.
	 */
	private function admin_includes() {
		include_once dirname( CSBMW_PLUGIN_FILE ) . '/includes/admin/class-extra-checkout-fields-for-brazil-admin.php';
		include_once dirname( CSBMW_PLUGIN_FILE ) . '/includes/admin/class-extra-checkout-fields-for-brazil-settings.php';
		include_once dirname( CSBMW_PLUGIN_FILE ) . '/includes/admin/class-extra-checkout-fields-for-brazil-order.php';
		include_once dirname( CSBMW_PLUGIN_FILE ) . '/includes/admin/class-extra-checkout-fields-for-brazil-customer.php';
	}

	/**
	 * Action links.
	 *
	 * @param  array $links Default plugin links.
	 *
	 * @return array
	 */
	public function plugin_action_links( $links ) {
		$plugin_links   = array();
		$plugin_links[] = '<a href="' . esc_url( admin_url( 'admin.php?page=woocommerce-extra-checkout-fields-for-brazil' ) ) . '">' . __( 'Settings', 'woocommerce-extra-checkout-fields-for-brazil' ) . '</a>';
		$plugin_links[] = '<a href="https://apoia.se/claudiosanches" target="_blank" rel="noopener noreferrer">' . __( 'Premium Support', 'woocommerce-extra-checkout-fields-for-brazil' ) . '</a>';
		$plugin_links[] = '<a href="https://apoia.se/claudiosanches" target="_blank" rel="noopener noreferrer">' . __( 'Contribute', 'woocommerce-extra-checkout-fields-for-brazil' ) . '</a>';

		return array_merge( $plugin_links, $links );
	}

	/**
	 * WooCommerce fallback notice.
	 */
	public function woocommerce_fallback_notice() {
		echo '<div class="error"><p>' . wp_kses(
			sprintf(
				/* translators: %s: woocommerce link */
				__( 'Brazilian Market on WooCommerce depends on %s to work!', 'woocommerce-extra-checkout-fields-for-brazil' ),
				'<a href="http://wordpress.org/plugins/woocommerce/">' . __( 'WooCommerce', 'woocommerce-extra-checkout-fields-for-brazil' ) . '</a>'
			),
			array(
				'a' => array(
					'href' => array(),
				),
			)
		) . '</p></div>';
	}

	/**
	 * Mark the plugin as compatible with the HPOS feature
	 *
	 * @return void
	 */
	function hposCompatibility(): void {
		if(class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class))
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', 'woocommerce-extra-checkout-fields-for-brazil/woocommerce-extra-checkout-fields-for-brazil.php', true);
	}

}
