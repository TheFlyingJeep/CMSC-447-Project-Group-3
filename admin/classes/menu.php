<?php
namespace Plugin_Template\Admin;

class AdminMenu {
	public function __construct() {

	}

	public function register_menus() {
		// Default top level menu item.
		add_menu_page(
			esc_html__( 'Plugin Template', 'plugin-template' ),
			esc_html__( 'Plugin Template', 'plugin-template' ),
			'manage_options', 
			'plugin-template-main',
			[ $this, 'display_main' ],
			'dashicons-format-gallery'
		);

		// add_submenu_page(
		// 	'plugin-template-main',
		// 	esc_html__( 'Second Page', 'plugin-template' ),
		// 	esc_html__( 'Second Page', 'plugin-template' ),
		// 	'manage_options', 
		// 	'plugin-template-second',
		// 	[ $this, 'display_second' ]
		// );

	// 	add_submenu_page(
	// 		'plugin-template-main',
	// 		esc_html__('Login Page', 'plugin-template'),
	// 		esc_html__('Login Page', 'plugin-template'),
	// 		'manage_options',
	// 		'plugin-template-login',
	// 		[ $this, 'display_login']
	// 	);

	// 	add_submenu_page(
	// 		'plugin-template-main',
	// 		esc_html__('Protected Page', 'plugin-template'),
	// 		esc_html__('Protected Page', 'plugin-template'),
	// 		'manage_options',
	// 		'plugin-template-protected',
	// 		[ $this, 'display_protected']
	// 	);
	}

	public function display_main() {
		include_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'admin/partials/main.phtml';
	}

	// public function display_second() {
	// 	include_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'admin/partials/second.phtml';
	// }

	// public function display_login() {
	// 	include_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'admin/partials/login.phtml';
	// }

	// public function display_protected() {
	// 	include_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'admin/partials/protected.phtml';
	// }
}

new AdminMenu();
