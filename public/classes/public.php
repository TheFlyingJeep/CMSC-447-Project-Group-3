<?php
namespace Plugin_Template\Public;
use Plugin_Template\Includes\Utility;

class My_Public {
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
			'security'  => $this->ajax_security_nonce,
		);
	}


	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, PLUGIN_TEMPLATE_PLUGIN_URL . 'public/css/public.css', array(), $this->utilities->unique_id(), 'all' );
	}

	public function enqueue_scripts() {
		if ($this->is_this_plugin){
			$this->set_localization_data();
		   	wp_enqueue_script($this->plugin_name, PLUGIN_TEMPLATE_PLUGIN_URL . 'public/js/public.js', array( 'jquery' ), $this->utilities->unique_id(), false );
			wp_localize_script($this->plugin_name, 'plugin_template_js_defaults', $this->localization_data);
		}

	}

}
