<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class GFireMQRManager {
	
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'fs_is_submenu_visible_gfirem-qr', array( $this, 'handle_sub_menu' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_style' ) );
		
		require_once 'class-gfirem-qr-logs.php';
		new GFireMQRLogs();
		
		try {
			//Check formidable pro
			if ( class_exists( 'FrmAppHelper' ) && method_exists( 'FrmAppHelper', 'pro_is_installed' ) && FrmAppHelper::pro_is_installed() ) {
				if ( GFireM_Qr::getFreemius()->is_paying() ) {
					require_once 'class-gfirem-fieldbase.php';
					require_once 'class-gfirem-qr-field.php';
					new GFireMQrField();
				}
			} else {
				add_action( 'admin_notices', array( $this, 'required_formidable_pro' ) );
			}
		}
		catch ( Exception $ex ) {
			GFireMQRLogs::log( array(
				'action'         => 'loading_dependency',
				'object_type'    => GFireM_Qr::getSlug(),
				'object_subtype' => get_class( $this ),
				'object_name'    => $ex->getMessage(),
			) );
		}
	}
	
	public function admin_enqueue_style() {
		$current_screen = get_current_screen();
		if ( 'toplevel_page_formidable' === $current_screen->id ) {
			wp_enqueue_style( 'formidable_key_field', GFireM_Qr::$assets . 'css/admin_qr.css' );
		}
	}
	
	public function required_formidable_pro() {
		require GFireM_Qr::$view . 'formidable_notice.php';
	}
	
	public static function translate( $str ) {
		return __( $str, 'gfirem_qr-locale' );
	}
	
	/**
	 * Handle freemius menus visibility
	 *
	 * @param $is_visible
	 * @param $menu_id
	 *
	 * @return bool
	 */
	public function handle_sub_menu( $is_visible, $menu_id ) {
		if ( $menu_id == 'account' ) {
			$is_visible = false;
		}
		
		return $is_visible;
	}
	
	/**
	 * Adding the Admin Page
	 */
	public function admin_menu() {
		add_menu_page( __( "QrField", "gfirem_qr-locale" ), __( "QrField", "gfirem_qr-locale" ), 'manage_options', GFireM_Qr::getSlug(), array( $this, 'screen' ), 'dashicons-screenoptions' );
	}
	
	/**
	 * Screen to admin page
	 */
	public function screen() {
		GFireM_Qr::getFreemius()->get_logger()->entrance();
		GFireM_Qr::getFreemius()->_account_page_load();
		GFireM_Qr::getFreemius()->_account_page_render();
	}
}
