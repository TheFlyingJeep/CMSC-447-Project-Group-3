<?php
/** Fired during plugin deactivation 
 *
 */
class Deactivator {

	public static function deactivate() {
		$front_page_check = get_page_by_path("front-page");
		if ($front_page_check) {
			wp_delete_post($front_page_check->ID);
		}
		update_option('show_on_front', 'posts');
		$login_page_check = get_page_by_path("login-page");
		if ($login_page_check) {
			wp_delete_post($login_page_check->ID);
		}
		$admin_page_check = get_page_by_path("admin-page");
		if ($admin_page_check) {
			wp_delete_post($admin_page_check->ID);
		}
	}

}
