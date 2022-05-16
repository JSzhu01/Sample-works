<?php

require_once(dirname(__FILE__)."/record.php");

class Course extends Record
{
    protected static $table_name = "courses";

    public $course_num;
    public $course_name;
    public $semester;
    public $year;
    public $credit_hours;
    public $room;
    public $offering_dept;
    public $created_at;
    public $updated_at;
    
}
