<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class GFireMQRManager {

    /*
     * Me quede por:
     * hay que hacer que enlazar los ficheros para que carge all en su lugar.
     * El plugins es de pago y no tiene contenido protejido.
     * El campo tiene que tener el mismo slug para que siga funcionando.
     * Hay que hacer que el plugin sea como cascaron para todos los campos.
     * Hay campos que son free que van a ir directo a wp.org
     * Hacer el campo lo mas ligero posible
     * Ver si se puede implementar cache.
     *
     */
    public function __construct() {

        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action( 'fs_is_submenu_visible_gfirem-qr', array( $this, 'handle_sub_menu' ), 10, 2 );

        require_once 'class-gfirem-qr-logs.php';
        new GFireMQRLogs();

        try {
            //Check formidable pro
            if ( self::is_formidable_active() ) {
                if ( GFireM_QrField::getFreemius()->is_paying() ) {

                    require_once 'class-gfirem-qr-fieldbase.php';
                    require_once 'class-qr_field.php';
                    new GFireMQrField();
                }
            }
        } catch ( Exception $ex ) {
            GFireMQRLogs::log( array(
                'action'         => get_class( $this ),
                'object_type'    => GFireM_QrField_Fs::getSlug(),
                'object_subtype' => 'loading_dependency',
                'object_name'    => $ex->getMessage(),
            ) );
        }
    }

    public static function translate( $str ) {
        return __( $str, 'gfirem_qr-locale' );
    }

    public static function load_plugins_dependency() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
    }

    public static function is_formidable_active() {
        self::load_plugins_dependency();

        return is_plugin_active( 'formidable/formidable.php' );
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
        add_menu_page( __( "QrField", "gfirem_qr-locale" ), __( "QrField", "gfirem_qr-locale" ), 'manage_options', GFireM_QrField::getSlug(), array( $this, 'screen' ), 'dashicons-screenoptions' );
    }

    /**
     * Screen to admin page
     */
    public function screen() {
        GFireM_QrField::getFreemius()->get_logger()->entrance();
        GFireM_QrField::getFreemius()->_account_page_load();
        GFireM_QrField::getFreemius()->_account_page_render();
    }
}