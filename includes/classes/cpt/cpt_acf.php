<?php
namespace Plugin_Template\Includes\cpt;
use Plugin_Template\Includes;

class CPT_ACF {

	function __construct() {
		add_action( 'init', array( $this, 'construct_cpt_sessions' ) );
	}

	public function construct_cpt_sessions() {
		$labels = [
			"name" => esc_html__( "Sessions", "twentytwentyfive" ),
			"singular_name" => esc_html__( "Session", "twentytwentyfive" ),
		];

		$args = [
			"label" => esc_html__( "Sessions", "twentytwentyfive" ),
			"labels" => $labels,
			"description" => "",
			"public" => false,
			"publicly_queryable" => false,
			"show_ui" => true,
			"show_in_rest" => true,
			"rest_base" => "",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"rest_namespace" => "wp/v2",
			"has_archive" => false,
			"show_in_menu" => true,
			"show_in_nav_menus" => false,
			"delete_with_user" => false,
			"exclude_from_search" => true,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => false,
			"can_export" => false,
			"rewrite" => false,
			"query_var" => false,
			"supports" => [ "title" ],
			"show_in_graphql" => false,
		];
		register_post_type( "session", $args );
	}

}

$cpt_acf = new CPT_ACF();



?>
