<?php
namespace Plugin_Template\Public\Api;

Class Api_Ajax{

    public function __construct() {

    }

    public function check_security(){
        if ( ! check_ajax_referer( 'plugin-template-security-nonce', 'security', false ) ) {
            wp_send_json_error( 'Invalid security token sent.' );
            wp_die();
        }
        return;
    }


}
$plugin_template_api_ajax = new Api_Ajax();