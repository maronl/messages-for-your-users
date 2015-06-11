<?php
/**
 * The file responsible for starting the plugin
 *
 * Description ...
 *
 * *
 * @wordpress-plugin
 * Plugin Name: Messages for Your Users
 * Plugin URI: https://github.com/maronl/messages-for-your-users
 * Description: Plugin to enable administrator menage messages to be showed to the users once they are logged into the website.
 * Version: 1.0.0
 * Author: Luca Maroni
 * Author URI: http://maronl.it
 * Text Domain: messages-for-your-users
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /langs
 */

// If this file is called directly, then abort execution.
if (!defined('WPINC')) {
    die;
}

/**
 * Include the core class responsible for loading all necessary components of the plugin.
 */
require_once plugin_dir_path(__FILE__) . 'includes/class-messages-for-your-users-manager.php';

/**
 * Instantiates the Manager class and then
 * calls its run method officially starting up the plugin.
 */
function run_messages_for_your_users_manager()
{

    $onlimag = new Messages_For_Your_Users_Manager();
    $onlimag->run();

}

// Call the above function to begin execution of the plugin.
run_messages_for_your_users_manager();