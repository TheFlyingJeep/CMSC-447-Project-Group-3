<?php

namespace Plugin_Template\Includes;
use Plugin_Template\Includes;

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
        <label for='<?php echo $field->name?>'><?php echo $field->label?>: </label>
         <?php

        $created_field = match($field->type) {
            'text' => $this->create_text_field($field, $value, $html_required),
            'integer' => $this->create_integer_field($field, $value, $html_required),
            'boolean_radio' => $this->create_boolean_radio_field($field, $value, $html_required),
            'dropdown' => $this->create_dropdown_field($field, $value, $html_required),
            'timezone' => $this->create_timezone_field($field, $value, $html_required),
            default => $this->create_text_field($field, $value, $html_required),
        };

        ?>
        </div>
         <?php
    }

    private function create_text_field($field, $value, $html_required){
        ?>

        <input type='text' <?php echo $html_required?> size='<?php echo $field->size?>' id='<?php echo $field->name?>' name='<?php echo $field->name?>' value='<?php echo $value; ?>'>
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

    private function create_timezone_field($field, $value, $html_required){
        $selected_timezone = $value == '' ? 'UTC' : $value;
        $html = '<td class="hoo">';
        $html .= '<select id="' . $field->name .  '" name="' . $field->name .  '" aria-describedby="timezone-description">';
        $html .= wp_timezone_choice( $selected_timezone );
        $html .= '</select>';
        $html .= '</td>';

        echo $html;
    }
    #endregion

}

$tutor_session_post = new Tutor_Session_Post();