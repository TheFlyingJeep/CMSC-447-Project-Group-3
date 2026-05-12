<?php
use Plugin_Template\Admin;
use Plugin_Template\Public;
use Plugin_Template\Includes\Access;
/* The file that defines the core plugin class */

class Plugin_Template {

	/* The loader that's responsible for maintaining and registering all hooks that power the plugin */
	protected $loader;

	/* The unique identifier of this plugin. */
	protected $plugin_name;

	/* The current version of the plugin. */
	protected $version;

	/* Define the core functionality of the plugin. */

	public function __construct() {
		if ( defined( 'PLUGIN_TEMPLATE_VERSION' ) ) {
			$this->version = PLUGIN_TEMPLATE_VERSION;
		} else {
			$this->version = '1.1.1';
		}
		$this->plugin_name = 'plugin-template';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}


	/* Load the required dependencies for this plugin. */
	private function load_dependencies() {

		/* The class responsible for orchestrating the actions and filters of the core plugin. */
		require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/classes/loader.php';

		/* The class responsible for defining internationalization functionality of the plugin. */
		require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/classes/i18n.php';

		/* The class responsible for defining all actions that occur in the admin area. */
		require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'admin/classes/admin.php';

		/* The class responsible for defining all actions that occur in the public-facing side of the site. */
		require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'public/classes/public.php';

		/* The class responsible for the custom objects. */
		require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/classes/objects/tutor-session-objects.php';

		/* The class responsible for the custom objects. */
		require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/classes/custom/tutor-session-post.php';

		/* The class responsible for the custom CPT and ACF. */
		//require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/classes/cpt/cpt_acf.php';

		/* The class responsible for shared functions. */
		require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/classes/utility/utilities.php';

		/* The class responsible for defining custom permissions and access. */
		require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/classes/access/capabilities.php';

		/* The class responsible for building the plugin menu */
		require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'admin/classes/menu.php';

		/* The class responsible for exposing REST api calls */
		require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'public/api/api-ajax.php';

		$this->loader = new Loader();

	}

	/* Define the locale for this plugin for internationalization.
	 * Uses the Plugin_Template_i18n class in order to set the domain and to register the hook with WordPress.
	 */
	private function set_locale() {
		$plugin_i18n = new i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/* Register all of the hooks related to the admin area functionality of the plugin. */
	private function define_admin_hooks() {
		$plugin_admin = new Admin\Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$admin_menu = new Admin\AdminMenu();
		$this->loader->add_action( 'admin_menu', $admin_menu, 'register_csv_page', 9 );

		$capabilities = new Access\Capabilities();
		$this->loader->add_action( 'admin_init', $capabilities, 'get_caps', 10, 4);
		$this->loader->add_action( 'admin_init', $capabilities, 'grant_capabilities_to_admin', 10, 4);

	}

	/* Register all of the hooks related to the public-facing functionality of the plugin. */
	private function define_public_hooks() {
		$plugin_public = new Public\My_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
	}

	/* Run the loader to execute all of the hooks with WordPress. */
	public function run() {
		$this->loader->run();
	}

	/* The name of the plugin used to uniquely identify it within the context of WordPress and to define internationalization functionality. */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/* The reference to the class that orchestrates the hooks with the plugin. */
	public function get_loader() {
		return $this->loader;
	}

	/* Retrieve the version number of the plugin. */
	public function get_version() {
		return $this->version;
	}

}
