<?php

// Block direct access to file
defined( 'ABSPATH' ) or die( 'Not Authorized!' );

class Egens_Userlist{

    public function __construct() {

        // Plugin uninstall hook
        register_uninstall_hook( EGENS_WPS_FILE, array('EGENS_USERLIST', 'plugin_uninstall') );

        // Plugin activation/deactivation hooks
        register_activation_hook( EGENS_WPS_FILE, array($this, 'plugin_activate') );
        register_deactivation_hook( EGENS_WPS_FILE, array($this, 'plugin_deactivate') );

        // Plugin Actions
        add_action( 'plugins_loaded', array($this, 'plugin_init') );
        add_action( 'wp_enqueue_scripts', array($this, 'plugin_enqueue_scripts') );
        add_action( 'wp_enqueue_scripts', array($this, 'plugin_enqueue_admin_scripts') );

        self::egens_loaded_userlist();
    }

    public static function plugin_uninstall() { }

    /**
     * Plugin activation function
     * called when the plugin is activated
     * @method plugin_activate
     */
    public function plugin_activate() { }

    /**
     * Plugin deactivate function
     * is called during plugin deactivation
     * @method plugin_deactivate
     */
    public function plugin_deactivate() { }

    /**
     * Plugin init function
     * init the polugin textDomain
     * @method plugin_init
     */
    function plugin_init() {
        load_plugin_textDomain( EGENS_WPS_TEXT_DOMAIN, false, dirname(EGENS_WPS_DIRECTORY_BASENAME) . '/languages' );
    }

    function egens_loaded_userlist() {
        add_shortcode('codja-user-list', array($this, 'userlist_loaded'));
    }


    /**
     * Enqueue the main Plugin admin scripts and styles
     * @method plugin_enqueue_scripts
     */
    function plugin_enqueue_admin_scripts() {
        wp_register_style( 'wps-admin-style', EGENS_WPS_DIRECTORY_URL . '/assets/dist/css/admin-style.css', array(), null );
        wp_register_style( 'bootstrap-file', EGENS_WPS_DIRECTORY_URL . '/assets/dist/css/bootstrap.css', array(), true );
        wp_register_style( 'fontawesome-file', EGENS_WPS_DIRECTORY_URL . '/assets/dist/css/fontawesome.min.css', array(), true );
        wp_enqueue_script('jquery');
        wp_enqueue_style('wps-admin-style');
        wp_enqueue_style('bootstrap-file');
        wp_enqueue_style('fontawesome-file');
    }

    /**
     * Enqueue the main Plugin user scripts and styles
     * @method plugin_enqueue_scripts
     */
    function plugin_enqueue_scripts() {
        wp_register_script( 'wps-admin-script', EGENS_WPS_DIRECTORY_URL . '/assets/dist/js/admin-script.js', array('jquery'), null, true );
        wp_enqueue_script('jquery');
        wp_enqueue_script('wps-admin-script');
    }

    /**
     * Plugin support page
     * in this page there are listed some useful debug informations
     * and a quick link to write a mail to the plugin author
     * @method userlist_loaded
     */
    function userlist_loaded()
    {
        if(is_user_logged_in()){
            $number =$per_page= 10;
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
            $cur_page=$paged;

            $offset = ($paged - 1) * $number;
            $users = get_users();
            $total_users = count($users);
            $query = get_users('&offset='.$offset.'&number='.$number);
            $total_pages = intval($total_users / $number) + 1;

            //Count Total users
            $users = get_users( array( 'fields' => array( 'ID' ) ) );
            $totalUsers=count($users);
            ?>
            <div id="codja-user-lists" class="card">
                <?php include_once plugin_dir_path( __FILE__ ). '/partials/filter.php'; ?>
                <div class="card-body" id="cardLoadedContent">
                    <div id="mainContent">
                        <?php include_once plugin_dir_path( __FILE__ ). '/partials/userlist.php'; ?>
                    </div>
                </div>
                <input type="hidden" value="<?php echo admin_url('admin-ajax.php'); ?>" id="adminAjaxUrl">
            </div>
          <?php
        }else{
            echo "<h1>Please login as an admin</h1>";
        }

    }

    /**
     * During Ajax Call its calling from frontend
     * @method ajax_user_list_data
     */
    public function ajax_user_list_data(){
        if(is_user_logged_in()){
            $number = 10;

            $role       = sanitize_text_field($_POST['adminRole']);
            $order_by   = sanitize_text_field($_POST['orderBy']);
            $paged      = sanitize_text_field($_POST['page']);

            $cur_page=$paged;

            $offset = ($paged - 1) * $number;
            $users = get_users();
            $total_users = count($users);

            if ($order_by && $paged && $role) {
                $query = get_users('&offset=' . $offset . '&number=' . $number . '&order=' . $order_by . '&role__in=' . $role);
                $users = get_users( '&order='.$order_by.'&role__in='.$role);
            }elseif ($order_by && $paged){
                $query = get_users('&offset=' . $offset . '&number=' . $number . '&order=' . $order_by);
                $users = get_users( '&order='.$order_by);
            }elseif ($role && $paged){
                $query = get_users('&offset=' . $offset . '&number=' . $number . '&role__in=' . $role);
                $users = get_users( '&role__in='.$role);
            }else{
                $query = get_users('&offset=' . $offset . '&number=' . $number);
                $users = get_users( array( 'fields' => array( 'ID' ) ) );
            }

            $total_pages = intval($total_users / $number) + 1;
            $totalUsers=count($users);
            $pages = ceil($totalUsers / 10);

            include_once plugin_dir_path( __FILE__ ). '/partials/userlist.php';
            ?>
            <?php
        }else{
            echo "<h1>Please login as an admin</h1>";
        }
    }

}

new Egens_Userlist;
