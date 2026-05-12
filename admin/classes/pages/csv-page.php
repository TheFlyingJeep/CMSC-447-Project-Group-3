<?php
namespace Plugin_Template\Admin\Pages;


use DateTime;

Class CSV_Page{

    public function parse_csv($file, $overwrite) {
        $day_opts = ["monday", "tuesday", "wednesday", "thursday", "friday"];
        if (($file_open = fopen($file["tmp_name"], "r")) !== false) {
            while (($data = fgetcsv($file_open, 1000, ",")) !== false) {
                $csv_data[] = $data;
            }
            fclose($file_open);
            for ($c = 0; $c < count($csv_data); $c++) {
                if (count($csv_data[$c]) != 7) {
                    return [2,$c];
                }
                for ($i = 0; $i < count($csv_data[$c]); $i++) {
                    if ($i == 2) {
                        $vals = explode(" ", $csv_data[$c][$i]);
                        if (!filter_var($vals[1], FILTER_VALIDATE_INT)) {
                            return [3,$c];
                        }
                    }
                    if ($i == 4) {
                        $days = explode(";",$csv_data[$c][$i]);
                        foreach ($days as $day) {
                            if (!in_array(strtolower($day), $day_opts, true)) {
                                return [4,$c];
                            }
                        }
                    } elseif ($i == 5 || $i == 6) {
                        if (!$this->test_valid_time($csv_data[$c][$i])) {
                            return [5,$c];
                        }
                    }
                }
                if (new DateTime($csv_data[$c][5]) >= new DateTime($csv_data[$c][6])) {
                    return [6, $c];
                }
            }
            $overwrite_bool = false;
            if ($overwrite == "overwrite") {
                $overwrite_bool = true;
            }
            $this->upload_data($csv_data, $overwrite_bool);
            return [0,0];
        } else {
            return [1,0];
        }
    }

    private function parse_days($days) {
        $possible_days = array(
            "monday" => "Monday",
            "tuesday" => "Tuesday",
            "wednesday" => "Wednesday",
            "thursday" => "Thursday",
            "friday" => "Friday"
        );
        $out = array();
        foreach ($days as $day) {
            if (key_exists($day, $possible_days)) {
                $out[] = $possible_days[$day];
            }
        }
        return $out;
    }

    private function upload_data($data, $overwrite = false) {
        if ($overwrite) {
            $post_ids = get_posts(array(
                'post_type' => 'session',
                'numberposts' => -1,
                'fields' => 'ids'
            ));
            foreach ($post_ids as $post_id) {
                wp_delete_post($post_id, true);
            }
        }
        foreach ($data as $k => $v) {
            $post_id = wp_insert_post(array(
                'post_type' => 'session',
                'post_status' => 'publish',
                'post_title' => $v[2] . " - " . $v[1] . " - " . date("h:i A", strtotime($v[5]))
            ), true);

            $temp_days = explode(';',strtolower($v[4]));
            $days = $this->parse_days($temp_days);
            $start = new DateTime($v[5]);
            $end = new DateTime($v[6]);
            $now = current_datetime()->format("Y-m-d H:i:s");
            update_post_meta($post_id, 'ts_shared_shift_id', $v[0]);
            update_post_meta($post_id, 'ts_tutor_name', $v[1]);
            update_post_meta($post_id, 'ts_course_code', $v[2]);
            update_post_meta($post_id, 'ts_course_title', $v[3]);
            update_post_meta($post_id, 'ts_session_days', $days);
            update_post_meta($post_id, 'ts_start_time', $start->format("H:i"));
            update_post_meta($post_id, 'ts_end_time', $end->format("H:i"));
            update_post_meta($post_id, 'ts_session_status', "Not Checked In");
            update_post_meta($post_id, 'ts_session_capacity', "Normal");
            update_post_meta($post_id, 'ts_staff_notes', "");
            update_post_meta($post_id, 'ts_left_early_time', "");
            update_post_meta($post_id, 'ts_last_updated', sanitize_text_field($now));

            clean_post_cache($post_id);
        }
    }

    public function clean_sessions() {
        $post_ids = get_posts(array(
            'post_type' => 'session',
            'numberposts' => -1,
            'fields' => 'ids'
        ));
        foreach ($post_ids as $post_id) {
            wp_delete_post($post_id, true);
        }
    }

    private function test_valid_time($time, $format = "H:i") {
        $d = DateTime::createFromFormat($format, $time);
        return $d;
    }

}

?>