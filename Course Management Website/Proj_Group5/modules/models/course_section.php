<?php

require_once(dirname(__FILE__)."/record.php");

class CourseSection extends Record
{
    protected static $table_name = "course_section";

    public $section_id;
    public $course_num;
    public $user_id;
    public $section;
    public $semester;
    public $year;
    public $created_at;
    public $updated_at;
    
}