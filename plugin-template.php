<?php
/**
 * Plugin Template plugin.
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             0.1
 * @package           Plugin_Template
 *
 * @wordpress-plugin
 * Plugin Name:       Plugin Template
 * Description:       Description of your plugin.
 * Version:           1.26.2.3
 * Author:            Author3 & Author2 & Author3
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-template
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/* Currently plugin version. */
#region plugin info
define( 'PLUGIN_TEMPLATE_VERSION', '0.5.7' );
define( 'PLUGIN_TEMPLATE_MINIMUM_WP_VERSION', '5.8' );
#endregion

#region default urls and paths
define( 'PLUGIN_TEMPLATE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'PLUGIN_TEMPLATE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'PLUGIN_TEMPLATE_PLUGINS_DIR', plugin_dir_path( __DIR__ ) );
define( 'PLUGIN_TEMPLATE_SITE_URL', get_site_url());
define( 'PLUGIN_TEMPLATE_BASE_URL', $_SERVER['HTTP_HOST']);
#endregion

#region database table names
//define( 'DEPARTMENT_TABLE', "rim_department" );
//define( 'COURSE_TABLE', "rim_course" );
//define( 'TUTOR_TABLE', "rim_tutor" );
//define( 'TUTOR_SESSION_TABLE', "rim_tutor_session" );
#endregion


/* The code that runs during plugin activation. This action is documented in includes/classes/activator.php */
 function activate_plugin_template() {
	require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/classes/activator.php';
	Activator::activate();
}

/* The code that runs during plugin deactivation. This action is documented in includes/classes/deactivator.php */
function deactivate_plugin_template() {
	require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/classes/deactivator.php';
	Deactivator::deactivate();
}

function create_front_page() {
	ob_start();
	include PLUGIN_TEMPLATE_PLUGIN_DIR . "public/partials/front_page.phtml";
	return ob_get_clean();
}
function create_dynamic_frontpage() {
	ob_start();
	include PLUGIN_TEMPLATE_PLUGIN_DIR . "public/partials/dynamic_front.phtml";
	return ob_get_clean();
}
add_shortcode('render_dynamic_frontpage', 'create_dynamic_frontpage');
add_shortcode('render_front_page', 'create_front_page');

function create_login_page() {
	ob_start();
	include PLUGIN_TEMPLATE_PLUGIN_DIR . "public/partials/login_page.phtml";
    return ob_get_clean();
}
add_shortcode('render_login', 'create_login_page');

function create_admin_page() {
	ob_start();
	include PLUGIN_TEMPLATE_PLUGIN_DIR . "public/partials/admin_page.phtml";
	return ob_get_clean();
}
function create_dynamic_adminpage() {
	ob_start();
	include PLUGIN_TEMPLATE_PLUGIN_DIR . "public/partials/dynamic_admin.phtml";
	return ob_get_clean();
}
add_shortcode('render_dynamic_adminpage', 'create_dynamic_adminpage');
add_shortcode('render_admin_page', 'create_admin_page');

register_activation_hook( __FILE__, 'activate_plugin_template' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_template' );


/**
 * The core plugin class that is used to define internationalization, admin-specific hooks, and public-facing site hooks.
 */
require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/classes/includes.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.1.1
 */
function run_plugin_template() {
	$plugin = new Plugin_Template();
	$plugin->run();

}
run_plugin_template();
