<?php

require_once(dirname(__FILE__)."/record.php");

class Discussion extends Record
{
    protected static $table_name = "discussions";
    protected static $belongs_to = array(
        "user" => array(
            "class_name" => "User",
            "foreign_key" => "user_id",
        )
    );

    public $id;
    public $user_id;
    public $me_id;
    public $instructor_post;
    public $title;
    public $topic_content;
    public $created_at;
    public $updated_at;
}
