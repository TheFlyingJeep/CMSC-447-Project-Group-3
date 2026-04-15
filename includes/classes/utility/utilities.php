<?php
namespace Plugin_Template\Includes\Utility;
use Plugin_Template\Includes;

class Utilities {
    
	public function unique_id($bytes = 16) {
        $data = bin2hex(random_bytes($bytes));
        return $data;
    }

    Public function replace_line_breaks($str){
        $search = "\n";
        $replace = ",";
        return str_replace($search, $replace, $str);
    }

    Public function add_slashes_array($str){
        return addslashes($str);
    }

    public function is_current_plugin($checks_array){
        $page_info = new Page_Info();
        $is_current_plugin = false;
        foreach ($checks_array as $key => $value) {          
            $is_current_plugin = match($key){
                'page' => str_contains($page_info->page, $value) ? true : false,
                'posttype' => str_contains($page_info->current_posttype, $value) ? true : false,
                'uri' => str_contains($page_info->request_uri, $value) ? true : false,
                default => false,
            };
            if ($is_current_plugin){
                break;
            }
        }
        return $is_current_plugin;
    }

}

class Page_Info {
    public $current_posttype;
    public $is_post;
    public $request_uri;
    public $page;
	public function __construct() {
		$this->set_page_variables();
	}

    private function set_page_variables(){
		global $post, $pagenow;
		$this->page = $_GET['page'] ?? "";
		$this->request_uri = $_SERVER['REQUEST_URI'];
		if ( 'post.php' === $pagenow && isset($_GET['post']) ){
			$post_id = $_GET['post'];
			$post = get_post($post_id);
			$this->current_posttype = $post->post_type;
		} else {
			$this->current_posttype = $_GET['post_type'] ?? "";
		}
		$this->is_post = (in_array($pagenow, ['post.php', 'post-new.php'])) ? true : false;
	}

}