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

register_activation_hook( __FILE__, 'activate_plugin_template' );
register_deactivation_hook( __FILE__, 'deactivate_plugin_template' );

function render_login_phtml() {
	ob_start();
	include PLUGIN_TEMPLATE_PLUGIN_DIR . "admin/partials/login.phtml";
	return ob_get_clean();
}

function render_protected_phtml() {
	if ($_SERVER["REQUEST_METHOD"] === "POST") {
		if (!isset($_POST["username"]) || !isset($_POST["password"])) {
			header("Location: login-test-page", true);
			exit();
		} else {
			$username = $_POST["username"];
			$password = $_POST["password"];
			if (empty($username) || empty($password)) {
				header("Location: login-test-page", true);
				exit();
			}
			$password_file = fopen(PLUGIN_TEMPLATE_PLUGIN_DIR . "/admin/user_password.ini", "r") or die("Password file does not exist");
			$password_hash = fgets($password_file);
			fclose($password_file);
			if (!$username == "test" || !password_verify($password, $password_hash)) {
				header("Location: login-test-page", true);
				exit();
			}
		}
		header("Location: administrator");
		// ob_start();
		// echo "<h1>Dynamic content on " . date("Y-m-d H:i:s") . "</h1>";
		// return ob_get_clean();
	}
}

add_shortcode("render_login", "render_login_phtml");
add_shortcode("render_protected", "render_protected_phtml");

function create_login_protected_page() {
	if (!get_page_by_path("login-test-page")) {
		wp_insert_post([
			"id" => 999,
			"import_id" => 999,
			"post_title" => "Login Test Page",
			"post_content" => "[render_login]",
			"post_status" => "publish",
			"post_type" => "page",
			"post_name" => "login-test-page",
		]);
	}
	if (!get_page_by_path("protected-page")) {
		wp_insert_post([
			"id" => 1000,
			"import_id" => 1000,
			"post_title" => "Protected Page",
			"post_content" => "[render_protected]",
			"post_status" => "publish",
			"post_type" => "page",
			"post_name" => "protected-page",
		]);
	}
}

function remove_login_protected_page() {
	if (get_page_by_path("login-test-page")) {
		wp_delete_post(999, true);
	}
	if (get_page_by_path("protected-page")) {
		wp_delete_post(1000, true);
	}
}

register_activation_hook(__FILE__, "create_login_protected_page");
register_deactivation_hook(__FILE__, "remove_login_protected_page");


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
