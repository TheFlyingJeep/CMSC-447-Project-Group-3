<?php
namespace Plugin_Template\Public\Asc_Util;
use DateTime;
if (!defined('ASC_DAILY_RESET_HOUR')) {
	define('ASC_DAILY_RESET_HOUR', 15);
}

if (!defined('ASC_DAILY_RESET_MINUTE')) {
	define('ASC_DAILY_RESET_MINUTE', 0);
}
function asc_get_session_field($field_name, $post_id) {
	$value = function_exists('get_field')
		? get_field($field_name, $post_id)
		: get_post_meta($post_id, $field_name, true);

	return maybe_unserialize($value);
}

function asc_normalize_checkbox_values($value) {
	$value = maybe_unserialize($value);

	if ($value === null || $value === '' || $value === false) {
		return array();
	}

	$items = is_array($value) ? $value : array($value);
	$normalized = array();

	foreach ($items as $item) {
		if (is_array($item)) {
			if (isset($item['label']) && $item['label'] !== '') {
				$normalized[] = sanitize_text_field($item['label']);
			} elseif (isset($item['value']) && $item['value'] !== '') {
				$normalized[] = sanitize_text_field($item['value']);
			}
		} elseif (is_string($item) || is_numeric($item)) {
			$normalized[] = sanitize_text_field((string) $item);
		}
	}

	return array_values(array_unique(array_filter($normalized)));
}

function asc_format_day_values($value) {
	$days = asc_normalize_checkbox_values($value);
	return empty($days) ? '' : implode(', ', $days);
}

function asc_get_day_sort_value($value, $day_order) {
	$days = asc_normalize_checkbox_values($value);

	if (empty($days)) {
		return 99;
	}

	$orders = array();

	foreach ($days as $day) {
		$orders[] = isset($day_order[$day]) ? $day_order[$day] : 99;
	}

	return min($orders);
}

function asc_get_status_display_text($status, $left_early_time = '') {
	if ($status === 'Left Early') {
		return !empty($left_early_time)
			? 'Left/Leaves Early at ' . $left_early_time
			: 'Left/Leaves Early';
	}

	return $status;
}

function asc_get_now_mysql() {
	return current_datetime()->format('Y-m-d H:i:s');
}

function asc_get_now_timestamp() {
	return current_datetime()->getTimestamp();
}

function asc_get_subject_code_from_course_code($course_code) {
	$course_code = trim((string) $course_code);

	if (preg_match('/^[A-Za-z]+/', $course_code, $matches)) {
		return strtoupper($matches[0]);
	}

	return strtoupper($course_code);
}

function asc_get_course_key($course_code, $course_title) {
	return strtoupper(trim((string) $course_code)) . '|' . strtolower(trim((string) $course_title));
}

function asc_get_course_label($course_code, $course_title) {
	return trim((string) $course_code) . ' – ' . trim((string) $course_title);
}

function asc_sanitize_shared_shift_id($value) {
	$value = strtolower(trim((string) $value));
	$value = preg_replace('/\s+/', '_', $value);
	$value = preg_replace('/[^a-z0-9_\-]/', '', $value);
	return $value;
}

function asc_get_all_session_rows() {
	$ids = get_posts(array(
		'post_type'   => 'session',
		'post_status' => 'publish',
		'numberposts' => -1,
		'fields'      => 'ids',
		'orderby'     => 'title',
		'order'       => 'ASC',
	));

	$rows = array();

	foreach ($ids as $post_id) {
		$course_code = asc_get_session_field('ts_course_code', $post_id);
		$course_title = asc_get_session_field('ts_course_title', $post_id);
		$tutor_name = asc_get_session_field('ts_tutor_name', $post_id);
		$day_values = asc_get_session_field('ts_session_days', $post_id);
		$start_time = asc_get_session_field('ts_start_time', $post_id);
		$end_time = asc_get_session_field('ts_end_time', $post_id);

		$rows[] = array(
			'post_id'           => $post_id,
			'shared_shift_id'   => asc_get_session_field('ts_shared_shift_id', $post_id),
			'subject_code'      => asc_get_subject_code_from_course_code($course_code),
			'course_code'       => $course_code,
			'course_title'      => $course_title,
			'course_key'        => asc_get_course_key($course_code, $course_title),
			'course_label'      => asc_get_course_label($course_code, $course_title),
			'tutor_name'        => $tutor_name,
			'day_values'        => asc_normalize_checkbox_values($day_values),
			'day_display'       => asc_format_day_values($day_values),
			'start_time'        => $start_time,
			'end_time'          => $end_time,
			'status'            => asc_get_session_field('ts_session_status', $post_id),
			'capacity'          => asc_get_session_field('ts_session_capacity', $post_id),
			'notes'             => asc_get_session_field('ts_staff_notes', $post_id),
			'left_early_time'   => asc_get_session_field('ts_left_early_time', $post_id),
			'last_updated'      => asc_get_session_field('ts_last_updated', $post_id),
		);
	}

	return $rows;
}

function asc_get_filter_options_from_rows($rows) {
	$subjects = array();
	$courses_by_subject = array();
	$tutors = array();

	foreach ($rows as $row) {
		$subject = $row['subject_code'];
		$course_code = $row['course_code'];
		$course_title = $row['course_title'];
		$tutor_name = $row['tutor_name'];

		if ($subject !== '') {
			$subjects[$subject] = $subject;
		}

		if (!isset($courses_by_subject[$subject])) {
			$courses_by_subject[$subject] = array();
		}

		if ($course_code !== '') {
			$courses_by_subject[$subject][$course_code] = $course_title;
		}

		if ($tutor_name !== '') {
			$tutors[$tutor_name] = $tutor_name;
		}
	}

	ksort($subjects);

	foreach ($courses_by_subject as $subject => $courses) {
		ksort($courses);
		$courses_by_subject[$subject] = $courses;
	}

	ksort($tutors);

	return array($subjects, $courses_by_subject, $tutors);
}

function asc_get_course_options_for_subject($courses_by_subject, $selected_subject = '') {
	if ($selected_subject !== '' && isset($courses_by_subject[$selected_subject])) {
		return $courses_by_subject[$selected_subject];
	}

	$all_courses = array();

	foreach ($courses_by_subject as $subject => $courses) {
		foreach ($courses as $course_code => $course_title) {
			$all_courses[$course_code] = $course_title;
		}
	}

	ksort($all_courses);

	return $all_courses;
}

function asc_filter_session_rows($rows, $filters) {
	$selected_subject = isset($filters['subject']) ? (string) $filters['subject'] : '';
	$selected_course = isset($filters['course']) ? (string) $filters['course'] : '';
	$selected_tutor = isset($filters['tutor']) ? (string) $filters['tutor'] : '';
	$selected_days = isset($filters['days']) ? (array) $filters['days'] : array();

	$filtered = array();

	foreach ($rows as $row) {
		if ($selected_subject !== '' && $row['subject_code'] !== $selected_subject) {
			continue;
		}

		if ($selected_course !== '' && $row['course_code'] !== $selected_course) {
			continue;
		}

		if ($selected_tutor !== '' && $row['tutor_name'] !== $selected_tutor) {
			continue;
		}

		if (!empty($selected_days)) {
			$intersection = array_intersect($selected_days, $row['day_values']);
			if (empty($intersection)) {
				continue;
			}
		}

		$filtered[] = $row;
	}

	return $filtered;
}

function asc_group_session_rows($rows) {
	$grouped = array();

	foreach ($rows as $row) {
		$subject = $row['subject_code'];
		$course_key = $row['course_key'];

		if (!isset($grouped[$subject])) {
			$grouped[$subject] = array(
				'subject_code' => $subject,
				'courses'      => array(),
			);
		}

		if (!isset($grouped[$subject]['courses'][$course_key])) {
			$grouped[$subject]['courses'][$course_key] = array(
				'course_code'  => $row['course_code'],
				'course_title' => $row['course_title'],
				'course_label' => $row['course_label'],
				'sessions'     => array(),
			);
		}

		$grouped[$subject]['courses'][$course_key]['sessions'][] = $row;
	}

	ksort($grouped);

	$day_order = array(
		'Monday'    => 1,
		'Tuesday'   => 2,
		'Wednesday' => 3,
		'Thursday'  => 4,
		'Friday'    => 5,
	);

	foreach ($grouped as &$subject_group) {
		uasort($subject_group['courses'], function ($a, $b) {
			return strcasecmp($a['course_code'], $b['course_code']);
		});

		foreach ($subject_group['courses'] as &$course_group) {
			usort($course_group['sessions'], function ($a, $b) use ($day_order) {
				$day_a = asc_get_day_sort_value($a['day_values'], $day_order);
				$day_b = asc_get_day_sort_value($b['day_values'], $day_order);

				if ($day_a !== $day_b) {
					return $day_a - $day_b;
				}

				$time_a = strtotime($a['start_time']);
				$time_b = strtotime($b['start_time']);

				if ($time_a !== $time_b) {
					return $time_a <=> $time_b;
				}

				return strcasecmp($a['tutor_name'], $b['tutor_name']);
			});
		}
		unset($course_group);
	}
	unset($subject_group);

	return $grouped;
}

/**
 * Shared shift sync helpers
 */
function asc_get_session_ids_by_shared_shift($shared_shift_id) {
	$shared_shift_id = asc_sanitize_shared_shift_id($shared_shift_id);

	if ($shared_shift_id === '') {
		return array();
	}

	return get_posts(array(
		'post_type'   => 'session',
		'post_status' => 'publish',
		'numberposts' => -1,
		'fields'      => 'ids',
		'meta_query'  => array(
			array(
				'key'     => 'shared_shift_id',
				'value'   => $shared_shift_id,
				'compare' => '=',
			),
		),
	));
}

function asc_update_live_session_fields($post_id, $status, $capacity, $notes, $left_early_time, $updated_by = '') {
	if ($status !== 'Left Early') {
		$left_early_time = '';
	}

	$allowed_statuses = array('Not Checked In', 'Checked In', 'Cancelled', 'Left Early');
	$allowed_capacity = array('Normal', 'Full');

	if (!in_array($status, $allowed_statuses, true)) {
		$status = 'Not Checked In';
	}

	if (!in_array($capacity, $allowed_capacity, true)) {
		$capacity = 'Normal';
	}

	$last_updated = asc_get_now_mysql();

	update_post_meta($post_id, 'ts_session_status', $status);
	update_post_meta($post_id, 'ts_session_capacity', $capacity);
	update_post_meta($post_id, 'ts_staff_notes', $notes);
	update_post_meta($post_id, 'ts_left_early_time', $left_early_time);
	update_post_meta($post_id, 'ts_last_updated', $last_updated);

	clean_post_cache($post_id);
}

/**
 * Reset one session
 */
function asc_reset_single_session($post_id, $updated_by = 'Daily Auto Reset') {
	asc_update_live_session_fields(
		$post_id,
		'Not Checked In',
		'Normal',
		'',
		'',
		$updated_by
	);
}

/**
 * Schedule daily reset
 */
add_action('init', 'asc_schedule_daily_reset');

function asc_get_next_reset_timestamp() {
	$tz = wp_timezone();
	$now = new DateTime('now', $tz);
	$next = new DateTime('now', $tz);
	$next->setTime(ASC_DAILY_RESET_HOUR, ASC_DAILY_RESET_MINUTE, 0);

	if ($next <= $now) {
		$next->modify('+1 day');
	}

	return $next->getTimestamp();
}

function asc_schedule_daily_reset() {
	$schedule_key = ASC_DAILY_RESET_HOUR . ':' . ASC_DAILY_RESET_MINUTE;
	$stored_key = get_option('asc_daily_reset_schedule_key', '');
	$next_event = wp_next_scheduled('asc_daily_reset_sessions');

	if ($next_event && $stored_key !== $schedule_key) {
		wp_unschedule_event($next_event, 'asc_daily_reset_sessions');
		$next_event = false;
	}

	if (!$next_event) {
		wp_schedule_event(asc_get_next_reset_timestamp(), 'daily', 'asc_daily_reset_sessions');
		update_option('asc_daily_reset_schedule_key', $schedule_key, false);
	}
}

add_action('asc_daily_reset_sessions', 'asc_run_daily_reset_sessions');

function asc_run_daily_reset_sessions() {
	$ids = get_posts(array(
		'post_type'   => 'session',
		'post_status' => 'publish',
		'numberposts' => -1,
		'fields'      => 'ids',
	));

	foreach ($ids as $id) {
		asc_reset_single_session($id, 'Daily Auto Reset');
	}

	update_option('asc_daily_reset_last_run_date', wp_date('Y-m-d', null, wp_timezone()), false);
}

/**
 * Failsafe reset
 */
add_action('init', 'asc_daily_reset_failsafe', 20);

function asc_daily_reset_failsafe() {
	$tz = wp_timezone();
	$now = new DateTime('now', $tz);

	$target_today = new DateTime('today', $tz);
	$target_today->setTime(ASC_DAILY_RESET_HOUR, ASC_DAILY_RESET_MINUTE, 0);

	$today_key = $now->format('Y-m-d');
	$last_run = get_option('asc_daily_reset_last_run_date', '');

	if ($now >= $target_today && $last_run !== $today_key) {
		asc_run_daily_reset_sessions();
	}
}

/**
 * Save handler for Tutor Session updates
 * Syncs all cards with the same shared_shift_id
 */

function asc_save_tutor_session() {
// 	if (!is_user_logged_in()) {
// 		wp_die('You must be logged in.');
// 	}

	$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

	if (!$post_id || get_post_type($post_id) !== 'session') {
		wp_die('Invalid Tutor Session.');
	}

// 	if (!current_user_can('edit_post', $post_id)) {
// 		wp_die('You do not have permission to edit this Tutor Session.');
// 	}

	$status = isset($_POST['status']) ? sanitize_text_field(wp_unslash($_POST['status'])) : '';
	$capacity = isset($_POST['capacity']) ? sanitize_text_field(wp_unslash($_POST['capacity'])) : '';
	$notes = isset($_POST['notes']) ? sanitize_textarea_field(wp_unslash($_POST['notes'])) : '';
	$left_early_time = isset($_POST['left_early_time']) ? sanitize_text_field(wp_unslash($_POST['left_early_time'])) : '';

	//$current_user = wp_get_current_user();
	//$updated_by = $current_user ? $current_user->display_name : '';

	$shared_shift_id = asc_get_session_field('ts_shared_shift_id', $post_id);

	$target_post_ids = array($post_id);

	if (!empty($shared_shift_id)) {
		$linked_ids = asc_get_session_ids_by_shared_shift($shared_shift_id);
		if (!empty($linked_ids)) {
			$target_post_ids = $linked_ids;
		}
	}

	$target_post_ids = array_values(array_unique(array_map('intval', $target_post_ids)));

	foreach ($target_post_ids as $target_post_id) {
		asc_update_live_session_fields(
			$target_post_id,
			$status,
			$capacity,
			$notes,
			$left_early_time,
			//$updated_by
		);
	}
}

/**
 * Shared UI helpers
 */
function asc_render_subject_course_filter_js() {
	?>
	<script>
	(function () {
		function wireDependentCourseDropdown(scope) {
			const subjectSelect = scope.querySelector('.asc-subject-select');
			const courseSelect = scope.querySelector('.asc-course-select');

			if (!subjectSelect || !courseSelect) {
				return;
			}

			function refreshCourseOptions() {
				const selectedSubject = subjectSelect.value;
				let currentCourseStillValid = false;

				Array.from(courseSelect.options).forEach(function(option, index) {
					if (index === 0) {
						option.hidden = false;
						option.disabled = false;
						return;
					}

					const optionSubject = option.getAttribute('data-subject') || '';
					const visible = !selectedSubject || optionSubject === selectedSubject;

					option.hidden = !visible;
					option.disabled = !visible;

					if (visible && option.value === courseSelect.value) {
						currentCourseStillValid = true;
					}
				});

				if (courseSelect.value && !currentCourseStillValid) {
					courseSelect.value = '';
				}
			}

			subjectSelect.addEventListener('change', refreshCourseOptions);
			refreshCourseOptions();
		}

		document.querySelectorAll('.asc-filter-shell').forEach(function(scope) {
			wireDependentCourseDropdown(scope);
		});
	})();
	</script>
	<?php
}

function asc_render_shared_accordion_styles() {
?>
<style>
.asc-filter-shell,
#asc-admin-view,
#asc-public-view {
font-family: Arial, Helvetica, sans-serif;
color: #111827;
}

#asc-admin-view,
#asc-public-view {
--asc-gold: #f2b31a;
--asc-gold-dark: #b98700;
--asc-border: #d1d5db;
--asc-subject-bg: #f4e6bf;
--asc-radius-lg: 14px;
--asc-radius-md: 10px;
--asc-radius-sm: 8px;
}

.asc-filter-bar {
display: grid;
grid-template-columns: 1.1fr 1.3fr 1.1fr auto;
gap: 14px;
align-items: center;
margin: 0 0 24px;
padding: 18px;
border-radius: var(--asc-radius-lg);
background: #ffffff;
border: 1px solid var(--asc-border);
box-shadow: none;
}

.asc-filter-bar.asc-filter-bar-public {
grid-template-columns: 1fr 1.2fr minmax(320px, 1.05fr) auto;
}

.asc-filter-bar label,
.asc-filter-bar .asc-day-filter-wrap {
display: block;
font-weight: 700;
}

.asc-filter-bar label span,
.asc-filter-label {
display: block;
margin-bottom: 6px;
font-weight: 800;
letter-spacing: 0.01em;
}

.asc-filter-select,
.asc-capacity-note,
.asc-left-early-time {
width: 100%;
min-height: 44px;
padding: 9px 12px;
border: 1px solid #9ca3af;
background: #ffffff;
box-sizing: border-box;
font-size: 16px;
border-radius: var(--asc-radius-sm);
box-shadow: none;
}

.asc-filter-select:focus,
.asc-capacity-note:focus,
.asc-left-early-time:focus {
outline: 2px solid var(--asc-gold);
outline-offset: 1px;
border-color: var(--asc-gold-dark);
}

.asc-day-filter-wrap {
grid-column: 1 / -1;
}

.asc-day-filter-list {
display: grid;
grid-template-columns: repeat(5, minmax(150px, 1fr));
gap: 12px 16px;
padding: 16px 18px;
border: 1px solid var(--asc-border);
background: #ffffff;
min-height: 42px;
box-sizing: border-box;
border-radius: var(--asc-radius-md);
}

.asc-day-check {
display: flex !important;
align-items: center;
gap: 8px;
font-weight: 600 !important;
margin: 0;
min-height: 54px;
padding: 12px 14px;
border: 1px solid var(--asc-border);
border-radius: var(--asc-radius-md);
background: #ffffff;
cursor: pointer;
}

.asc-day-check input {
margin: 0;
accent-color: var(--asc-gold);
}

.asc-day-check input:checked + span {
color: #8a5a00;
font-weight: 800;
}

.asc-toolbar-button-wrap {
display: flex;
align-items: end;
gap: 10px;
flex-wrap: wrap;
}

.asc-gold-btn {
display: inline-flex;
align-items: center;
justify-content: center;
min-height: 44px;
padding: 10px 16px;
border: 1px solid var(--asc-gold-dark);
background: #f2b31a;
color: #111827;
font-weight: 700;
font-size: 15px;
line-height: 1.2;
border-radius: var(--asc-radius-sm);
text-decoration: none;
cursor: pointer;
box-shadow: none;
}

.asc-secondary-btn {
background: #f3f4f6;
border-color: #9ca3af;
}

.asc-notice-success {
margin: 0 0 20px;
padding: 14px 16px;
background: #eaf8ee;
border: 1px solid #8bc79a;
border-radius: var(--asc-radius-md);
font-weight: 700;
box-shadow: none;
}

.asc-empty-state,
.asc-filter-summary {
margin: 0 0 18px;
padding: 14px 16px;
background: #ffffff;
border: 1px solid var(--asc-border);
border-radius: var(--asc-radius-md);
box-shadow: none;
}

.asc-accordion-stack {
display: grid;
gap: 18px;
}

.asc-subject-accordion,
.asc-course-accordion {
border: 1px solid var(--asc-border);
border-radius: var(--asc-radius-lg);
background: #ffffff;
overflow: hidden;
box-shadow: none;
}

.asc-subject-accordion > summary,
.asc-course-accordion > summary {
list-style: none;
cursor: pointer;
padding: 18px 20px;
display: flex;
align-items: center;
justify-content: space-between;
gap: 14px;
font-weight: 800;
position: relative;
}

.asc-subject-accordion > summary::-webkit-details-marker,
.asc-course-accordion > summary::-webkit-details-marker {
display: none;
}

.asc-subject-accordion > summary::after,
.asc-course-accordion > summary::after {
content: "▸";
font-size: 1rem;
flex-shrink: 0;
color: #4b5563;
}

.asc-subject-accordion[open] > summary::after,
.asc-course-accordion[open] > summary::after {
content: "▾";
}

.asc-subject-accordion > summary {
background: var(--asc-subject-bg);
font-size: 1.28rem;
border-bottom: 1px solid var(--asc-border);
}

.asc-course-accordion > summary {
background: #ffffff;
font-size: 1.06rem;
border-bottom: 1px solid var(--asc-border);
}

.asc-summary-title {
display: inline-flex;
align-items: center;
gap: 10px;
flex-wrap: wrap;
}

.asc-summary-meta {
font-size: 0.92rem;
font-weight: 700;
color: #4b5563;
padding: 6px 10px;
border-radius: 999px;
background: #f3f4f6;
border: 1px solid var(--asc-border);
box-shadow: none;
}

.asc-accordion-body {
padding: 16px;
background: #ffffff;
}

.asc-course-inner-stack,
.asc-session-list-wrap {
display: grid;
gap: 14px;
}

.asc-pill {
display: inline-flex;
align-items: center;
justify-content: center;
padding: 8px 16px;
border-radius: 999px;
border: 1px solid #bdbdbd;
font-size: 0.95rem;
font-weight: 700;
background: #f3f4f6;
white-space: nowrap;
box-shadow: none;
}

.asc-pill-green {
background: #e6f4ea;
border-color: #7ab889;
}

.asc-pill-gray {
background: #f3f4f6;
border-color: #9ca3af;
}

.asc-pill-red {
background: #fde2e2;
border-color: #d27c7c;
}

.asc-pill-gold {
background: #fff3cd;
border-color: #d9a85f;
}

.asc-capacity-normal {
background: #ffffff;
border-color: #7ab889;
}

.asc-capacity-full {
background: #ffffff;
border-color: #cc7a7a;
}

.asc-today-toggle {
display: block;
}

.asc-today-box {
display: flex;
align-items: center;
gap: 12px;
min-height: 64px;
padding: 14px 16px;
border: 1px solid var(--asc-border);
border-radius: var(--asc-radius-md);
background: #ffffff;
box-shadow: none;
cursor: pointer;
}

.asc-today-box.is-active {
border-color: var(--asc-gold-dark);
background: #fff8ea;
}

.asc-today-box input {
width: 20px;
height: 20px;
margin: 0;
accent-color: var(--asc-gold);
flex-shrink: 0;
}

.asc-today-copy {
display: flex;
flex-direction: column;
line-height: 1.2;
}

.asc-today-copy strong {
font-size: 1rem;
font-weight: 900;
}

.asc-today-copy small {
margin-top: 4px;
color: #6b7280;
font-size: .86rem;
font-weight: 700;
}
</style>
<?php
}

