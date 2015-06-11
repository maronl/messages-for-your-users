<?php

/**
 * The Manager is the core plugin responsible for including and
 * instantiating all of the code that composes the plugin
 */

/**
 * The Manager is the core plugin responsible for including and
 * instantiating all of the code that composes the plugin.
 *
 * The Manager includes an instance to the Loader which is 
 * responsible for coordinating the hooks that exist within the plugin.
 *
 * It also maintains a reference to the plugin slug which can be used in
 * internationalization, and a reference to the current version of the plugin
 * so that we can easily update the version in a single place to provide
 * cache busting functionality when including scripts and styles.
 *
 * @since 1.0.0
 */
class Messages_For_Your_Users_Manager {

    /**
     * A reference to the loader class that coordinates the hooks and callbacks
     * throughout the plugin.
     *
     * @access protected
     * @var Messages_For_Your_Users_Loader $loader Manages hooks between the WordPress hooks and the callback functions.
     */
    protected $loader;

    /**
     * Represents the slug of the plugin that can be used throughout the plugin
     * for internationalization and other purposes.
     *
     * @access protected
     * @var string $plugin_slug The single, hyphenated string used to identify this plugin.
     */
    protected $plugin_slug;

    /**
     * Maintains the current version of the plugin so that we can use it throughout
     * the plugin.
     *
     * @access protected
     * @var string $version The current version of the plugin.
     */
    protected $version;

    /**
     * The options defined for the plugin.
     *
     * @access protected
     * @var array $options The options defined for the plugin.
     */
    protected $options;

    /**
     * Instantiates the plugin by setting up the core properties and loading
     * all necessary dependencies and defining the hooks.
     *
     * The constructor will define both the plugin slug and the verison
     * attributes, but will also use internal functions to import all the
     * plugin dependencies, and will leverage the Single_Post_Meta_Loader for
     * registering the hooks and the callback functions used throughout the
     * plugin.
     */
    public function __construct() {

        $this->plugin_slug = 'messages-for-your-users';
        $this->version = '1.0.0';
        $this->options = array(); // now fixed

        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->define_register_activation_hook();

    }



    /**
     * Imports the Classes needed to make the plugin working.
     *
     * The Manager administration class defines all unique functionality for
     * introducing custom functionality into the WordPress dashboard.
     *
     * The Manager public class defines all unique functionality for
     * introducing custom functionality into the public side.
	 *	
     * The Loader is the class that will coordinate the hooks and callbacks
     * from WordPress and the plugin. This function instantiates and sets the reference to the
     * $loader class property.
     *
     * @access private
     */
    private function load_dependencies() {
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-messages-for-your-users-model.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-messages-for-your-users-manager-admin.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-messages-for-your-users-manager-options.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-messages-for-your-users-manager-public.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-messages-for-your-users-theme-functions.php';

        require_once plugin_dir_path( __FILE__ ) . 'class-messages-for-your-users-loader.php';
        $this->loader = new Messages_For_Your_Users_Loader();

    }

    /**
     * Defines the hooks and callback functions that are used for setting up the plugin stylesheets, scripts, logic
     * and the plugin's meta box.
     *
     * @access private
     */
    private function define_admin_hooks() {

        $data_model = Messages_For_Your_Users_Model::getInstance();
        $admin = new Messages_For_Your_Users_Manager_Admin( $this->get_version(), $this->options, $data_model );

        $this->loader->add_action( 'init', $admin, 'load_textdomain' );
        $this->loader->add_action( 'init', $admin, 'register_message_for_your_user_post_type' );
        $this->loader->add_action( 'wp_ajax_set_message_as_read', $admin, 'ajax_set_message_as_read' );
        $this->loader->add_action( 'wp_ajax_nopriv_set_message_as_read', $admin, 'ajax_set_message_as_read' );

    }

    /**
     * Defines the hooks and callback functions that are used for rendering information on the front
     * end of the site.
     *
     * @access private
     */
    private function define_public_hooks() {
        $data_model = Messages_For_Your_Users_Model::getInstance();
        $public = new Messages_For_Your_Users_Manager_Public( $this->get_version(), $this->options, $data_model);
        $this->loader->add_action( 'init', $public, 'register_scripts' );
        $this->loader->add_action( 'wp_enqueue_scripts', $public, 'enqueue_scripts' );
        $this->loader->add_action( 'init', $public, 'create_shortcode_m4yuphp' );
        $this->loader->add_action( 'wp_footer', $public, 'print_message_to_the_user' );
        Messages_For_Your_Users_Theme_Functions::define_theme_functions();
    }

    private function define_register_activation_hook() {
        $data_model = Messages_For_Your_Users_Model::getInstance();
        $admin = new Messages_For_Your_Users_Manager_Admin( $this->version, $this->options, $data_model);
        register_activation_hook( dirname( dirname( __FILE__ ) ) . '\messages-for-your-users.php' , array( $admin, 'init_db_schema' ) );
    }

    /**
     * Sets this class into motion.
     *
     * Executes the plugin by calling the run method of the loader class which will
     * register all of the hooks and callback functions used throughout the plugin
     * with WordPress.
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * Returns the current version of the plugin to the caller.
     *
     * @return string $this->version The current version of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}