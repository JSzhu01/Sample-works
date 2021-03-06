<?php

require_once(dirname(__FILE__)."/record.php");

class Attachment extends Record
{
    protected static $table_name = "attachments";

    public $id;
    public $file_id;
    public $file_filename;
    public $file_size;
    public $attachable_id;
    public $attachable_type;
    public $created_at;
    public $updated_at;
}
