<?php
namespace Plugin_Template\Admin;
use Plugin_Template\Includes\Utility;


class Admin {
	private $plugin_name;
	private $version;
	public $utilities;
	public $localization_data;
	public $ajax_security_nonce;
	public $is_this_plugin;

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->utilities = new Utility\Utilities();
		$this->set_variables();
	}

	public function set_variables(){
		$this->is_this_plugin = $this->utilities->is_current_plugin(
			[
				'page'=>'plugin-template-',
				'posttype'=>'plugin-template-',
			]
		);
	}

	public function set_localization_data(){
		$this->ajax_security_nonce = wp_create_nonce( 'plugin-template-security-nonce' );
		$this->localization_data = array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'admin_url' => admin_url(),
			'plugin_url' => PLUGIN_TEMPLATE_PLUGIN_URL,
			'site_url' => PLUGIN_TEMPLATE_SITE_URL,
			'security'  => $this->ajax_security_nonce,
		);
	}


	public function enqueue_styles() {
		//if ($this->is_this_plugin){
			wp_enqueue_style( 'plugin-template-admin-css', PLUGIN_TEMPLATE_PLUGIN_URL . 'admin/css/admin.css', array(), $this->utilities->unique_id(), 'all' );
		//}
	}

	public function enqueue_scripts() {
		$this->set_localization_data();
		if ($this->is_this_plugin){
			wp_enqueue_script( 'plugin-template-utility-js', PLUGIN_TEMPLATE_PLUGIN_URL . 'includes/js/utility.js', array( 'jquery' ), $this->utilities->unique_id(), false );
			wp_localize_script( 'plugin-template-utility-js', 'plugin_template_js_defaults', $this->localization_data);
		}
	}

}
