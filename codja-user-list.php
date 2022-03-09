<?php

/**
 * Codja user list
 *
 * @package     UserList Plugin Demo
 * @author      Egenslab
 * @license     GPL-1.0+
 *
 * @wordpress-plugin
 * Plugin Name: Codja User List
 * Plugin URI:
 * Description: A simple user list plugin using ajax
 * Version:     0.0.1
 * Author:      Egenslab
 * Author URI:  https://www.egenslab.com/
 * Text Domain: codja-user-list
 */

ob_start();
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

final class CodJaUserList
{
    /**
     * Plugin version
     *
     * @var string
     */
    const codja_plugin_versions = '1.0.0';

    /**
     * Class constructor
     */
    private function __construct() {
        self::define_constants();
        require_once (EGENS_WPS_DIRECTORY . '/include/codja-user-list-class.php');
    }

    /**
     * Define the required plugin constants
     *
     * @return void
     */
    public function define_constants() {
        define("EGENS_WPS_FILE", __FILE__);
        define("EGENS_WPS_DIRECTORY", dirname(__FILE__));
        define("EGENS_WPS_TEXT_DOMAIN", dirname(__FILE__));
        define("EGENS_WPS_DIRECTORY_BASENAME", plugin_basename(EGENS_WPS_FILE));
        define("EGENS_WPS_DIRECTORY_PATH", plugin_dir_path(EGENS_WPS_FILE));
        define("EGENS_WPS_DIRECTORY_URL", plugins_url(null, EGENS_WPS_FILE));
    }

    /**
     * Initializes Instance
     *
     * @return \CodJaUserList
     */
    public static function condja_init() {
        static $instance = false;
        if ( ! $instance ) {
            $instance = new self();
            require_once (dirname(__FILE__) . '/include/codja-user-list-class.php');
            $objectMainClass    =new Egens_Userlist();
            add_action( 'wp_ajax_pagination_user_list', array( $objectMainClass, 'ajax_user_list_data') );
            add_action( 'wp_ajax_nopriv_pagination_user_list', array( $objectMainClass, 'ajax_user_list_data') );
        }

        return $instance;
    }
}

/**
 * Initializes the main plugin and ajax calling hook
 * @return \CodJaUserList
 * @ Version : 0.0.1
 */

function condja_userlist() {
    return CodJaUserList::condja_init();
}

condja_userlist();
