<?php
use Plugin_Template\Includes;
use DateTime;
/** Fired during plugin activation 
 * Useful for things like creating database tables.
 */
class Activator {

	public static function activate() {
		// $post_ids = get_posts(array(
		// 	'post_type' => 'tutor_session',
		// 	'numberposts' => -1,
		// 	'fields' => 'ids'
		// ));
		// $file = WP_CONTENT_DIR . '/uploads/temp.csv';
		// foreach($post_ids as $post_id) {
		// 	$string = "";
		// 	$string .= get_post_meta($post_id, 'shared_shift_id', true) . ",";
		// 	$string .= get_post_meta($post_id, 'tutor_name', true) . ",";
		// 	$string .= get_post_meta($post_id, 'course_code', true) . ",";
		// 	$course_title = get_post_meta($post_id, 'course_title', true);
		// 	$course_title = str_replace(",", " -", get_post_meta($post_id, 'course_title', true));
		// 	$string .= $course_title . ",";
		// 	$days = get_post_meta($post_id, 'session_day')[0];
		// 	foreach ($days as $day) {
		// 		$string .= $day . ";";
		// 	}
		// 	$string = substr($string, 0, strlen($string)-1);
		// 	$string .= ",";
		// 	$start_time = get_post_meta($post_id, 'start_time', true);
		// 	$start_date = new DateTime($start_time);
		// 	$start_time_str = $start_date->format("H:i");
		// 	$string .= $start_time_str . ",";
		// 	$end_time = get_post_meta($post_id, 'end_time', true);
		// 	$end_date = new DateTime($end_time);
		// 	$end_time_str = $end_date->format("H:i");
		// 	$string .= $end_time_str;
		// 	file_put_contents($file, $string . "\n", FILE_APPEND);
		// }
		$front_page_check = get_page_by_path("front-page");
		if (!$front_page_check) {
			wp_insert_post(array(
				'post_title' => 'ASC Tutoring',
				'post_name' => 'front-page',
				'post_content' => '[render_front_page]',
				'post_status' => 'publish',
				'post_type' => 'page'
			));
		}
		$front_page = get_page_by_path("front-page");
		update_option('show_on_front', 'page');
		update_option('page_on_front', $front_page->ID);
		$login_page_check = get_page_by_path("login-page");
		if (!$login_page_check) {
			wp_insert_post(array(
				'post_title' => 'Login Page',
				'post_name' => 'login-page',
				'post_content' => '[render_login]',
				'post_status' => 'publish',
				'post_type' => 'page'
			));
		}
		$admin_page_check = get_page_by_path("admin-page");
		if (!$admin_page_check) {
			wp_insert_post(array(
				'post_title' => 'Admin Page',
				'post_name' => 'admin-page',
				'post_content' => '[render_admin_page]',
				'post_status' => 'publish',
				'post_type' => 'page'
			));
		}
	}

}
