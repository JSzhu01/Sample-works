<?php

require_once(dirname(__FILE__)."/record.php");

class UserGroup extends Record
{
    protected static $table_name = "user_group";

    public $user_group_id;
    public $user_id;
    public $group_id;
    public $created_at;
    public $updated_at;
    
}