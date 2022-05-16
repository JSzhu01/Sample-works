<?php

require_once(dirname(__FILE__)."/record.php");

class Enrollment extends Record
{
    protected static $table_name = "enrollment";

    public $section_id;
    public $user_id;
    public $grade;
    public $created_at;
    public $updated_at;
    
}