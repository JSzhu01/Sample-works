<?php

require_once(dirname(__FILE__)."/record.php");

class User extends Record
{
    protected static $table_name = "users";

    public $id;
    public $student_id;
    public $first_name;
    public $privilege;
    public $last_name;
    public $email;
    public $password_digest;
    public $created_at;
    public $updated_at;
}
