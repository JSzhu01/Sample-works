<?php

require_once(dirname(__FILE__)."/record.php");

class CourseGroup extends Record
{
    protected static $table_name = "course_group";

    public $group_id;
    public $section_id;
    public $group_name;
    public $leader_id;
    public $created_at;
    public $updated_at;
    
}