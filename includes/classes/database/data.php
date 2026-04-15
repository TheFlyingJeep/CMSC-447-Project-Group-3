<?php
namespace Plugin_Template\Includes\Database;
use Plugin_Template\Includes\Database;

require_once PLUGIN_TEMPLATE_PLUGIN_DIR . 'includes/classes/database/db-tables.php';

Class Data{
    Public $current_wpdb;
    Public $unknown_table;

    public function __construct() {
		global $wpdb;
		$this->current_wpdb = $wpdb;
        $this->unknown_table = $this->current_wpdb->prefix . "oymuap_user_application";
    }

    public function db_install(): void{
        $db_tables = new Database\Db_Tables($this->current_wpdb);
        $db_tables->create_db_tables();
    }

    public function db_uninstall(): void{
        $db_tables = new Database\Db_Tables($this->current_wpdb);
        $db_tables->delete_db_tables();
    }


    public function dbGet_user_applications_byUser($selected_user_id){
        $sql =  " SELECT UA.id, UA.application_date, UA.application_year_id, U.name,  UA.user_application_guid  
        FROM {$this->current_wpdb->prefix}oymuap_user_application UA
        INNER JOIN {$this->current_wpdb->prefix}oymuap_user U ON U.ID = UA.registered_user_id
        WHERE UA.registered_user_id = " . $selected_user_id;

        $user_applications = $this->current_wpdb->get_results($sql, OBJECT );
        return $user_applications;
    }

    public function dbCreate_user_application($user_application){
        $data = array(
            "user_application_guid" => $user_application->user_application_guid,
            "application_pdf_name" => $user_application->application_pdf_name,
            "registered_user_id" => $user_application->registered_user_id,
            "application_date" => $user_application->application_date,
            "application_year_id" => $user_application->application_year_id,
            "current_app_user" => $user_application->current_app_user,
            "application_json" => $user_application->application_json,
            "hear_about_cac" => $user_application->hear_about_cac,
            "application_certification" => $user_application->application_certification,
            "signature_certification" => $user_application->signature_certification,
            "typed_name" => $user_application->typed_name,
            "signature_url" => $user_application->signature_url,
            "status" => $user_application->status,
            "household_json" => $user_application->household_obj,
            "housing_json" => $user_application->housing_obj,
            "energy_json" => $user_application->energy_obj
        );

        $format = array('%s','%s','%d','%s','%d','%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s');
        $this->current_wpdb->insert($this->unknown_table,$data,$format);
        $insert_id = $this->current_wpdb->insert_id;
        return $insert_id;
    }

    public function dbUpdate_user_application($data, $where){
        $updated = $this->current_wpdb->update( $this->unknown_table, $data, $where ); 
        return $updated;
    }

}
