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
	}

	public function register_csv_page() {
		add_menu_page(
			esc_html__('CSV Page', 'csv-page'),
			esc_html__('CSV Page', 'csv-page'),
			'manage_options',
			'csv-page',
			[ $this,'render_csv'],
			'dashicons-format-gallery'
		);
	}

	public function display_main() {
		include_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'admin/partials/main.phtml';
	}

	public function render_csv() {
		include_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'admin/partials/csv-page.phtml';
	}
}

new AdminMenu();
