<?php
namespace Plugin_Template\Includes\Database;

Class Db_Tables{
    Public $current_wpdb;
    Private $charset_collate;
    Private $department_table_name;
    Private $course_table_name;
    Private $tutor_table_name;
    Private $tutor_session_table_name;

    function __construct($current_wpdb) {
        $this->current_wpdb = $current_wpdb;
        $this->department_table_name = $this->current_wpdb->prefix . DEPARTMENT_TABLE;
        $this->course_table_name = $this->current_wpdb->prefix . COURSE_TABLE;
        $this->tutor_table_name = $this->current_wpdb->prefix . TUTOR_TABLE;
        $this->tutor_session_table_name = $this->current_wpdb->prefix . TUTOR_SESSION_TABLE;
        $this->charset_collate = $this->current_wpdb->get_charset_collate();
    }

    public function create_db_tables(): void{
        $this->create_department_table();
        $this->create_course_table();
        $this->create_tutor_table();
        $this->create_tutor_session_table();
    }

    public function delete_db_tables(){
        $this->current_wpdb->query("DROP TABLE IF EXISTS $this->department_table_name");
        $this->current_wpdb->query("DROP TABLE IF EXISTS $this->course_table_name");
        $this->current_wpdb->query("DROP TABLE IF EXISTS $this->tutor_table_name");
        $this->current_wpdb->query("DROP TABLE IF EXISTS $this->tutor_session_table_name");
    }

    private function create_department_table(): void {
		//require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		$sql = "CREATE TABLE {$this->department_table_name} (
            `id` VARCHAR(10) NOT NULL,
            `name` VARCHAR(100) NULL,
		    PRIMARY KEY (`id`),
            UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE) 
            $this->charset_collate";

        dbDelta( $sql );
	}

    private function create_course_table(): void {
		$sql = "CREATE TABLE {$this->course_table_name} (
            `id` INT NOT NULL AUTO_INCREMENT,
            `id_department` VARCHAR(10) NOT NULL,
            `course_id` INT NOT NULL,
            `name` VARCHAR(100) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE);
            $this->charset_collate";

		dbDelta( $sql );
	}

    private function create_tutor_table(): void {
		$sql = "CREATE TABLE {$this->tutor_table_name} (
            `id` VARCHAR(10) NOT NULL,
            `name` VARCHAR(100) NULL,
            PRIMARY KEY (`id`),
            UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE);
            $this->charset_collate";

		dbDelta( $sql );
	}

    private function create_tutor_session_table(): void {
		$sql = "CREATE TABLE {$this->tutor_session_table_name} (
            `id` INT NOT NULL AUTO_INCREMENT,
            `id_tutor` VARCHAR(10) NOT NULL,
            `id_course` INT NOT NULL,
            `day` VARCHAR(10) NOT NULL,
            `start` TIME NOT NULL,
            `end` TIME NOT NULL,
            `status` VARCHAR(15) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE INDEX `id_UNIQUE` (`id` ASC) VISIBLE);
            $this->charset_collate";

		dbDelta( $sql );
	}


}