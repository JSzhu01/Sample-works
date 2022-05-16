<?php

require_once(dirname(__FILE__)."/record.php");

class Mail extends Record
{
    protected static $table_name = "mails";

    public $id;
    public $receiver_id;
    public $sender_id;
    public $title;
    public $content;
    public $delete_flag;
    public $read_flag;
    public $created_at;
    public $updated_at;
}
