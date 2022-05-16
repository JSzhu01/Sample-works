<?php

require_once(dirname(__FILE__)."/record.php");

class Feedback extends Record
{
    protected static $table_name = "feedbacks";

    protected static $belongs_to = array(
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        )
    );
  
    public $feedback_id;
    public $user_id;
    public $content;
    public $identity_id;
    public $identity_type;
    public $created_at;
    public $updated_at;
}
