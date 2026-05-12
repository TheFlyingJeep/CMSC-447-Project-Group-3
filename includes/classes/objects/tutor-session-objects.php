<?php
namespace Plugin_Template\Includes;

Class Tutor_Session{
    Public $fields = [];
 
    function __construct() {
        $this->fields[] = new Tutor_Session_Field("ts_tutor_name", "Tutor Name", "tutor_name", "text", 0);
        $this->fields[] = new Tutor_Session_Field("ts_course_code", "Course Code", "course_code", "text", 0);
        $this->fields[] = new Tutor_Session_Field("ts_course_title", "Course Title", "course_title", "text", 0);
        $this->fields[] = new Tutor_Session_Field("ts_session_days", "Days", "session_days", "checkbox", 0, field_values: array(
                'Monday' => 'Monday',
                'Tuesday' => 'Tuesday',
                'Wednesday' => 'Wednesday',
                'Thursday' => 'Thursday',
                'Friday' => 'Friday'
            )
        );

        $this->fields[] = new Tutor_Session_Field("ts_start_time", "Start Time", "start_time", "time_picker", 0);
        $this->fields[] = new Tutor_Session_Field("ts_end_time", "End Time", "end_time", "time_picker", 0);
        $this->fields[] = new Tutor_Session_Field("ts_session_status", "Status", "session_status", "dropdown", 1, default_value: 'Not Checked In', field_values: array(
                'Not Checked In',
                'Checked In',
                'Cancelled',
                'Left Early'
            )
        );

        $this->fields[] = new Tutor_Session_Field("ts_session_capacity", "Capacity", "session_capacity", "dropdown", 1, default_value: 'Normal', field_values: array('Normal','Busy','Full','No Students'));
        $this->fields[] = new Tutor_Session_Field("ts_left_early_time", "Left Early Time", "left_early_time", "time_picker", 0);
        $this->fields[] = new Tutor_Session_Field("ts_staff_notes", "Staff Notes", "staff_notes", "text_area", 0, high: true);
        $this->fields[] = new Tutor_Session_Field("ts_last_updated", "Last Updated", "last_updated", "date_time_picker", 0);
        $this->fields[] = new Tutor_Session_Field("ts_shared_shift_id", "Shared Shift ID", "shared_shift_id", "text", 0);
    }
}

Class Tutor_Session_Field{

    public function __construct(
        public string $meta_key,
        public string $label,
        public string $name,
        public string $type,
        public bool $required,
        public int $maxlength = 100,
        public array $field_values = [],
        public string $default_value = '',
        public int $size = 100,
        public bool $high = false
    ) {}
}
