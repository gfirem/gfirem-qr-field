<?php
/**
 * @package    WordPress
 * @subpackage Formidable, gfirem
 * @author     GFireM
 * @copyright  2017
 * @link       http://www.gfirem.com
 * @license    http://www.apache.org/licenses/
 *
 */
if ( ! defined( 'WPINC' ) ) {
	die;
}

class GFireM_QrField_Fs {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	public function __construct() {
		if ( $this->gfirem_qf_fs_is_parent_active_and_loaded() ) {
			// If parent already included, init add-on.
			return $this->gfirem_qf_fs_init();
		} else if ( $this->gfirem_qf_fs_is_parent_active() ) {
			// Init add-on only after the parent is loaded.
			add_action( 'gfirem_fs_loaded', array( $this, 'gfirem_qf_fs_init' ) );
		} else {
			// Even though the parent is not activated, execute add-on for activation / uninstall hooks.
			return $this->gfirem_qf_fs_init();
		}

		return false;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	function gfirem_qf_fs_is_parent_active_and_loaded() {
		// Check if the parent's init SDK method exists.
		return class_exists( 'gfirem' );
	}

	function gfirem_qf_fs_is_parent_active() {
		$active_plugins_basenames = get_option( 'active_plugins' );

		foreach ( $active_plugins_basenames as $plugin_basename ) {
			if ( 0 === strpos( $plugin_basename, 'gfirem/' ) ||
			     0 === strpos( $plugin_basename, 'gfirem-premium/' )
			) {
				return true;
			}
		}

		return false;
	}

	function gfirem_qf_fs_init() {
		if ( $this->gfirem_qf_fs_is_parent_active_and_loaded() ) {
			// Init Freemius.
			$result = $this->load_freemius();

			return $result;
		} else {
			return false;
		}
	}

	private function load_freemius() {
		global $gfirem_qf_fs;

		if ( ! isset( $gfirem_qf_fs ) ) {
			// Include Freemius SDK.
			$classes_path = gfirem_fs::$classes;
			if ( file_exists( $classes_path . 'include/freemius/start.php' ) ) {
				// Try to load SDK from parent plugin folder.
				require_once $classes_path . 'include/freemius/start.php';
				$gfirem_qf_fs = fs_dynamic_init( array(
					'id'               => '1525',
					'slug'             => 'qr-field',
					'type'             => 'plugin',
					'public_key'       => 'pk_a5ff0f40c32174e33a129a9f65c46',
					'is_premium'       => true,
					'has_paid_plans'   => true,
					'is_org_compliant' => false,
					'parent'           => array(
						'id'         => '848',
						'slug'       => 'gfirem',
						'public_key' => 'pk_47201a0d3289152f576cfa93e7159',
						'name'       => 'GFireM Fields',
					),
					'menu'                => array(
						'slug'           => 'qr-field',
						'first-path'     => 'admin.php?page=gfirem',
						'support'        => false,
						'parent'         => array(
							'slug' => 'gfirem',
						),
					),
				) );
			} else {
				return false;
			}
		}

		return $gfirem_qf_fs;
	}

}