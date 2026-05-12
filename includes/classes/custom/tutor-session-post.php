<?php

namespace Plugin_Template\Includes;
use Plugin_Template\Includes;

class CPT_Sessions {
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

    public function remove_cpt_sessions() {
        if (post_type_exists("session")) {
            $post_ids = get_posts(array(
                'post_type' => 'session',
                'numberposts' => -1,
                'fields' => 'ids'
            ));
            foreach ($post_ids as $post_id) {
                wp_delete_post($post_id, true);
            }
            unregister_post_type("session");
        }
    }
}

Class Tutor_Session_Post{
    Public $current_post;
    Public $tutor_session;


    function __construct() {
        $this->tutor_session = new Includes\Tutor_Session();
      //  add_action( 'init', array( $this, 'construct_cpt_sessions' ) );
		add_action( 'add_meta_boxes', array( $this,'create_tutor_metabox' ));
    }
    

	public function create_tutor_metabox() {
 		add_meta_box(
			'tutor_session_meta',       // Unique ID
			'Tutor Session Fields',       // Box title
			array($this, 'display'),  // Callback function to render HTML
			'session',        // The Custom Post Type slug
			'normal',               // Context (normal, side, or advanced)
			'high'                  // Priority (high, low, or default)
		);
	}

    #region DISPLAY
    public function display($post){
        $this->current_post = $post;
        ?>
        <style>

            .inside {
                width: 100% !important;
                margin: 0px !important;
                padding: 0px !important;
            }

            .ts-field-container {
                width: 100%;
                border-top: #eaecf0 solid 1px;
                padding-bottom: 10px;
            }

            .label-container {
                padding-top: 10px;
                margin-left: 10px;
                margin-bottom: 10px;
            }

            .field-label {
                font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
                font-weight: 500;
            }

            .field-container {
                margin-left: 10px;
            }
        </style>
        <?php
        foreach ($this->tutor_session->fields as $field) {
            $this->create_field($field);
        }
    }

    #endregion

    #region FIELDS
    public function create_field($field){
        $value = get_post_meta( $this->current_post->ID, $field->meta_key, true );
        $html_required = ($field->required) ? "required" : "";

        ?>
        <div class="ts-field-container">
        <div class="label-container">
            <label class="field-label" for='<?php echo $field->name?>'><?php echo $field->label?>: </label><br>
        </div>
        <div class="field-container">
         <?php

        $created_field = match($field->type) {
            'text' => $this->create_text_field($field, $value, $html_required),
            'integer' => $this->create_integer_field($field, $value, $html_required),
            //'boolean_radio' => $this->create_boolean_radio_field($field, $value, $html_required),
            'dropdown' => $this->create_dropdown_field($field, $value, $html_required),
            'timezone' => $this->create_timezone_field($field, $value, $html_required),
            'checkbox' => $this->create_checkbox_field($field, $value, $html_required),
            'time_picker' => $this->create_timepicker_field($field, $value, $html_required),
            'date_time_picker' => $this->create_datetime_field($field, $value, $html_required),
            default => $this->create_text_field($field, $value, $html_required),
        };

        ?>
        </div>
        </div>
         <?php
    }

    private function create_text_field($field, $value, $html_required){
        if ($field->high) {
            $this->create_textarea_field($field, $value, $html_required);
        } else {
        ?>

        <input type='text' <?php echo $html_required?> size='<?php echo $field->size?>' id='<?php echo $field->name?>' name='<?php echo $field->name?>' value='<?php echo $value; ?>'>
        <?php
        }
    }

    private function create_textarea_field($field, $value, $html_required) {
        ?>
        <textarea id='<?php echo $field->name;?>' name='<?php echo $field->name;?>' rows="5" cols="100"></textarea>
        <?php
    }

    private function create_integer_field($field, $value, $html_required){
        ?>
        <input type='number' step=1 <?php echo $html_required?> size='<?php echo $field->size?>' id='<?php echo $field->name?>' name='<?php echo $field->name?>' value='<?php echo $value; ?>'>
        <?php
    }

    private function create_dropdown_field($field, $value, $html_required){
        $html = '<select id="' . $field->name .  '" name="' . $field->name .  '" >';
        $html .= '<option value="--SELECT--">--SELECT--</option>';
        foreach ( $field->field_values as $field_value) {
            $selected = $field_value == $value ? 'selected' : ($field->default_value == $field_value ? 'selected' : '') ;
            $html .= '<option ' . $selected .  ' value="' .  $field_value  . '">';
            $html .= $field_value;
            $html .= '</option>';
        }
        $html .= '</select>';

        echo $html;
    }

    private function create_checkbox_field($field, $value, $html_required) {
        $html = '';
        foreach($field->field_values as $field_value) {
            $checked = in_array($field_value, $value) ? 'checked': '';
            $html .= '<label><input ' . $checked . ' type="checkbox" name="session_days[]" value="' . $field_value . '" >' . $field_value . '</label><br>';
        }
        echo $html;
    }

    private function create_timezone_field($field, $value, $html_required){
        $selected_timezone = $value == '' ? 'UTC' : $value;
        $html = '<td class="hoo">';
        $html .= '<select id="' . $field->name .  '" name="' . $field->name .  '" aria-describedby="timezone-description">';
        $html .= wp_timezone_choice( $selected_timezone );
        $html .= '</select>';
        $html .= '</td>';

        echo $html;
    }

    private function create_timepicker_field( $field, $value, $html_required) {
        ?>
        <input <?php echo $html_required;?> type="time" id='<?php echo $field->name;?>' name='<?php echo $field->name;?>' value='<?php echo $value; ?>'> 
        <?php
    }

    private function create_datetime_field($field, $value, $html_required) {
        ?>
        <input <?php echo $html_required;?> type="datetime-local" id='<?php echo $field->name;?>' name='<?php echo $field->name;?>' value='<?php echo $value; ?>'> 
        <?php
    }
    #endregion

}

$cpt_session = new CPT_Sessions();
$tutor_session_post = new Tutor_Session_Post();