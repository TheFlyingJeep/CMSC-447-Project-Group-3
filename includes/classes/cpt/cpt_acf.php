<?php
namespace Plugin_Template\Includes\cpt;
use Plugin_Template\Includes;

class CPT_ACF {

	function __construct() {
		add_action( 'init', array( $this, 'construct_cpt_sessions' ) );
		//add_action( 'add_meta_boxes', array( $this,'my_custom_add_meta_box' ));
		//add_action( 'acf/init', array( $this, 'construct_acf_sessions' ) );
	}

	public function my_custom_add_meta_box() {
		add_meta_box(
			'my_meta_box_id_q',       // Unique ID
			'Custom Details',       // Box title
			array($this, 'my_display_callback'),  // Callback function to render HTML
			'session',        // The Custom Post Type slug
			'normal',               // Context (normal, side, or advanced)
			'high'                  // Priority (high, low, or default)
		);
	}

	public function my_display_callback( $post ) {
		// Add nonce for security
		wp_nonce_field( 'my_meta_box_nonce_action', 'my_meta_box_nonce_name' );
	
		// Retrieve existing value from database
		$value = get_post_meta( $post->ID, '_my_custom_key', true );
	
		echo '<label for="my_custom_field">Enter Data: </label>';
		echo '<input type="text" id="my_custom_field" name="my_custom_field" value="' . esc_attr( $value ) . '" />';
	}

	public function construct_acf_sessions() {

		acf_add_local_field_group( array(
			'key' => 'group_69d2539ef0d16',
			'title' => 'Session Fields',
			'fields' => array(
				array(
					'key' => 'field_69d253765879d',
					'label' => 'Tutor Name',
					'name' => 'tutor_name',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'maxlength' => '',
					'allow_in_bindings' => 0,
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
				),
				array(
					'key' => 'field_69d273e15879e',
					'label' => 'Course Code',
					'name' => 'course_code',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'maxlength' => '',
					'allow_in_bindings' => 0,
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
				),
				array(
					'key' => 'field_69d253fa5779f',
					'label' => 'Course Title',
					'name' => 'course_title',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'maxlength' => '',
					'allow_in_bindings' => 0,
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
				),
				array(		
					'key' => 'field_69d2740a587a1',
					'label' => 'Day',
					'name' => 'session_day',
					'aria-label' => '',
					'type' => 'select',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'Monday' => 'Monday',
						'Tuesday' => 'Tuesday',
						'Wednesday' => 'Wednesday',
						'Thursday' => 'Thursday',
						'Friday' => 'Friday',
					),
					'default_value' => false,
					'return_format' => 'value',
					'multiple' => 0,
					'allow_null' => 0,
					'allow_in_bindings' => 0,
					'ui' => 0,
					'ajax' => 0,
					'placeholder' => '',
					'create_options' => 0,
					'save_options' => 0,
				),
				array(
					'key' => 'field_69d25478787a2',
					'label' => 'Start Time',
					'name' => 'start_time',
					'aria-label' => '',
					'type' => 'time_picker',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'g:i a',
					'return_format' => 'g:i a',
					'allow_in_bindings' => 0,
				),
				array(
					'key' => 'field_67d254b9587a3',
					'label' => 'End Time',
					'name' => 'end_time',
					'aria-label' => '',
					'type' => 'time_picker',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'g:i a',
					'return_format' => 'g:i a',
					'allow_in_bindings' => 0,
				),
				array(
					'key' => 'field_69d254ce577a4',
				'label' => 'Status',
				'name' => 'session_status',
				'aria-label' => '',
					'type' => 'select',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
				'id' => '',
					),
					'choices' => array(
						'Not Checked In' => 'Not Checked In',
						'Checked In' => 'Checked In',
						'Cancelled' => 'Cancelled',
						'Left Early' => 'Left Early',
					),
					'default_value' => false,
					'return_format' => 'value',
					'multiple' => 0,
					'allow_null' => 0,
					'allow_in_bindings' => 0,
					'ui' => 0,
					'ajax' => 0,
					'placeholder' => '',
					'create_options' => 0,
					'save_options' => 0,
				),
				array(
					'key' => 'field_69d274f7587a5',
					'label' => 'Capacity',
					'name' => 'session_capacity',
					'aria-label' => '',
					'type' => 'select',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'choices' => array(
						'Normal' => 'Normal',
						'Busy' => 'Busy',
						'Full' => 'Full',
						'No Students' => 'No Students',
					),
					'default_value' => false,
					'return_format' => 'value',
					'multiple' => 0,
					'allow_null' => 0,
					'allow_in_bindings' => 0,
					'ui' => 0,
					'ajax' => 0,
					'placeholder' => '',
					'create_options' => 0,
					'save_options' => 0,
				),
				array(
					'key' => 'field_69d2572f587a6',
					'label' => 'Left Early Time',
					'name' => 'left_early_time',
					'aria-label' => '',
					'type' => 'time_picker',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'g:i a',
					'return_format' => 'g:i a',
					'allow_in_bindings' => 0,
				),
				array(
					'key' => 'field_69d2555a787a7',
					'label' => 'Students Waiting',
					'name' => 'students_waiting',
					'aria-label' => '',
					'type' => 'number',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'min' => '',
					'max' => '',
					'allow_in_bindings' => 0,
					'placeholder' => '',
					'step' => '',
					'prepend' => '',
					'append' => '',
				),
				array(
					'key' => 'field_63d25570587a8',
					'label' => 'Staff Notes',
					'name' => 'staff_notes',
					'aria-label' => '',
					'type' => 'textarea',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'maxlength' => '',
					'allow_in_bindings' => 0,
					'rows' => '',
					'placeholder' => '',
					'new_lines' => '',
				),
				array(
					'key' => 'field_69d25539587a9',
					'label' => 'Last Updated',
					'name' => 'last_updated',
					'aria-label' => '',
					'type' => 'date_time_picker',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'F j, Y g:i a',
					'return_format' => 'F j, Y g:i a',
					'first_day' => 1,
					'default_to_current_date' => 0,
					'allow_in_bindings' => 0,
				),
				array(
					'key' => 'field_69d235c5587aa',
					'label' => 'Updated By',
					'name' => 'updated_by',
					'aria-label' => '',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'maxlength' => '',
					'allow_in_bindings' => 0,
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'session',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 0,
			'display_title' => '',
			'allow_ai_access' => false,
			'ai_description' => '',
		));
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
