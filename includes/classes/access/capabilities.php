<?php

namespace Plugin_Template\Includes\Access;

class Capabilities {

	public function __construct() {

	}

	/* Get a list of all capabilities. */
	public function get_caps() {

		$capabilities = [
			'plugin_template_access'                => __( 'Access Application', 'plugin-template' ),
			'plugin_template_view_settings'         => __( 'View Settings', 'plugin-template' ),
			'plugin_template_edit_settings'         => __( 'Edit Settings', 'plugin-template' ),
		];

		return \apply_filters( 'plugin_template_access_capabilities_get_caps', $capabilities );
	}

    public function grant_capabilities_to_admin() {
        $all_roles = \get_editable_roles();

		foreach ($all_roles as $role_name => $role_info){
            if (array_key_exists("manage_options", $role_info['capabilities'])){
                $role = get_role( $role_name );  
				/*
				$role->remove_cap('plugin_template_access');
				$role->remove_cap('plugin_template_view_settings');
				$role->remove_cap('plugin_template_edit_settings');
				*/
				if ( $role && ! $role->has_cap( 'plugin_template_access' ) ) {
					$role->add_cap( 'plugin_template_access' );
				}
				if ( $role && ! $role->has_cap( 'plugin_template_view_settings' ) ) {
					$role->add_cap( 'plugin_template_view_settings' );
				}
				if ( $role && ! $role->has_cap( 'plugin_template_edit_settings' ) ) {
					$role->add_cap( 'plugin_template_edit_settings' );
				}

            }
        }
    }

}
$capabilities = new Capabilities();