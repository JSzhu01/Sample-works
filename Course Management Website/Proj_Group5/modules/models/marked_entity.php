<?php

require_once(dirname(__FILE__)."/record.php");

class MarkedEntity extends Record
{
    protected static $table_name = "marked_entities";
    // Files that the instructor adds to the marked entity
    protected static $has_many = array(
        "files" => array(
            "class_name" => "Attachment",
            "foreign_key" => "attachable_id",
        ),
        "Works" => array(
            "class_name" => "MarkedEntityFile",
            "foreign_key" => "entity_id",
        ),
        "DiscussionBoard" => array(
            "class_name" => "Discussion",
            "foreign_key" => "user_id",
        )

    );

    public int $id;
    public string $title;
    public string $description;
    public int $sid;
    public bool $is_group_work;
    public string $due_at;
    public string $created_at;
    public string $updated_at;

    public function expired():bool{
        return new DateTime() > new DateTime($this -> due_at);
    }
}
