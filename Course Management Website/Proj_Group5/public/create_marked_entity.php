<?php

require_once "../modules/models/marked_entity.php";
require_once "../common.php";

ensure_logged_in();

if(!isset($_POST["sid"])){
    die ("Non existent course.");
}

$course_id = intval($_POST["sid"]);
$current_course = CourseSection::find_by_section_id($course_id);

if(get_priv() == 0){
    //pass...   
}
else if(get_priv() == 1){
    if(is_null($current_course)){
        die("Non existent course.");
    }
    else{
        if($current_course -> user_id != $_SESSION["current_user_id"]){
            die("You are not the instructor for this course!");
        }
    }
}
else{
    die("You can only create marked entity as an admin or instructor for the course.");
}




$marked_entity = new MarkedEntity();
$marked_entity->title = $_POST["title"];
$marked_entity->description = $_POST["description"];
$marked_entity->is_group_work = $_POST["is_group_work"];
// TODO: Fix this when courses are fully implemented
$marked_entity->sid = $course_id ?? 1;
$marked_entity->due_at = $_POST["due_at"];

if (isset($_FILES['marked_entity_file'])) {
    echo "Handling file";
    // Uploads folder needs to be created in the public/ directory
    // TODO: Make this more convenient
    $target_dir = "uploads/";
    // TODO: Improve the file ID
    $file_id = uniqid().basename($_FILES["marked_entity_file"]["name"]);
    $target_file = $target_dir . $file_id;

    $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $file_size = $_FILES["marked_entity_file"]["size"];

    $attachment = new Attachment();
    $attachment->file_size = $_FILES["marked_entity_file"]["size"];
    $attachment->file_filename = basename($_FILES["marked_entity_file"]["name"]);
    $attachment->file_id = $file_id;
    $attachment->$attachable_type = "InstructorFile";

    if (!move_uploaded_file($_FILES["marked_entity_file"]["tmp_name"], $target_file)) {
        echo "Sorry, there was an error uploading your file.";
    }

    $marked_entity->files = array($attachment);
}

try {
    $marked_entity->save();

    header("Location: marked_entity.php?id={$marked_entity->id}");
} catch (PDOException $error) {
    echo "<br>" . $error->getMessage();
}
?>  