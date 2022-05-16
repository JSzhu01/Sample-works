<?php include "templates/header.php"; ?>

<?php

require_once "../modules/models/marked_entity.php";
require_once "../common.php";

// TODO: Require ensure_logged_in.php instead
ensure_logged_in();
$isadmin = False;
$isold = False;
if(get_priv() == 0){
    // die("admin");
    $isadmin = True;
}
$database = getConnection();

if(!isset($_GET['sid'])){
if($isadmin){
    $c_sql = 'SELECT * FROM course_section';
    $get_me = 'SELECT * FROM me_info ORDER BY course_num,section';
}
else{
    echo '<a href ="index.php"> Back to home page </a><br></br>';
    die("No section specified.");
    
}
}
else{
$sid = $_GET['sid'];
$c_sql = 'SELECT * FROM course_section WHERE section_id = '.$sid;
$c = $database -> prepare($c_sql);
$c -> execute();
$course_sections = $c -> fetchAll();
if(!$course_sections){
    echo '<a href ="index.php"> Back to home page </a><br></br>';
    die("Non existent course section!");
}
if(get_priv() == 1){
    $sql = 'SELECT section_id FROM course_section WHERE section_id = '.$sid.' AND user_id = '.$_SESSION['current_user_id'];
    $get_me = 'SELECT * FROM me_info WHERE sid = '.$sid.' ORDER BY course_num,section';
}
else if(get_priv() == 3){
    $sql = 'SELECT user_id FROM student_course_info WHERE section_id = '.$sid.' AND user_id = '.$_SESSION['current_user_id'];
    $sql_check_past = 'SELECT DISTINCT me_id FROM leftMarkEntity WHERE section_id = '.$sid.' AND user_id = '.$_SESSION['current_user_id'];
    // echo $sql_check_past;
    $o = $database -> prepare($sql_check_past);
    $o -> execute();
    $old_data = $o -> fetchAll();
    if($old_data){
        $isold= True;
    }
    $get_me = 'SELECT * FROM me_info WHERE sid = '.$sid.' ORDER BY course_num,section';
}
else if(get_priv() == 0){
    $get_me = 'SELECT * FROM me_info WHERE sid = '.$sid.' ORDER BY course_num,section';
    // pass...
}
else{
    echo '<a href ="index.php"> Back to home page </a><br></br>';
    die("You have no access to this page!");
}

if(!$isadmin){
    $q = $database -> prepare($sql);
    $q-> execute();
    $valid = $q -> fetchAll();
    // echo ($valid);
    if(!$valid){
        if($isold){
            $get_me = 'SELECT * FROM me_info WHERE id IN ( SELECT DISTINCT me_id FROM leftMarkEntity AS lm WHERE lm.section_id = '.$sid.' AND lm.user_id = '.$_SESSION['current_user_id'].')';
        }
        else{
        echo '<a href ="index.php"> Back to home page </a><br></br>';
        die("You have no access to this course section!");
        }
    }
}
}

$q = $database -> prepare($get_me);
$q-> execute();
$marked_entities = $q -> fetchAll();
$c = $database -> prepare($c_sql);
$c -> execute();
$course_sections = $c -> fetchAll();


// else if(get_priv() == 1){
//     //pass...
//     $course_sections = CourseSection::where(array("user_id" => $_SESSION["current_user_id"]));
//  }


// try {
//     // TODO: Get different marked entities based on roles
//     $course_sections = CourseSection::getAll();
//     $result = MarkedEntity::getAll();
// } catch (PDOException $error) {
//     echo "<br>" . $error->getMessage();
// }
?>

<?php
if ($marked_entities && count($marked_entities)) { ?>
        <div class="container">
            <h2>Marked entities</h2>

            <table class="table table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Group work</th>
                            <th scope="col">Due at</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Course</th>
                        </tr>
                    </thead>
                    <tbody>
                <?php $num = 0 ; foreach ($marked_entities as $row) { ?>
                    <tr>
                        <td scope="row"><?php echo escape($num); ?></td>
                        <td><a href="marked_entity.php?id=<?php echo $row['id']; ?>"><?php echo escape($row['title']); ?></a></td>
                        <td><?php echo escape($row['is_group_work']); ?></td>
                        <td><?php echo escape($row['due_at']); ?></td>
                        <td><?php echo escape($row['created_at']);  ?> </td>
                        <td><?php echo escape($row['course_num']." ".$row['section']." ".$row['semester']." ".$row['year']);  ?> </td>
                    </tr>
                <?php $num ++; } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <blockquote>No marked entities found.</blockquote>
    <?php }
?>
<?php if (get_priv() == 0 or get_priv() == 1){ ?>
<div class="container">
    <h2>Create a new marked entity for students</h2>

    <form method="post" action="create_marked_entity.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" name="title" id="title" class="form-control">
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"  rows="3"></textarea>
        </div>
    
        <div class="form-group">
            <label for="sid">Choose the section</label>
            <select name="sid" id="sid">
            <option value="" disabled selected>Section...</option>
            <?php
                foreach ($course_sections as $csec) {?>
                <option value="<?php echo $csec['section_id'];?>"><?php echo $csec['course_num'] , ": Section ", $csec['section'] ;?></option> 
                <?php } ?>
    </select>
        </div>

        <div class="form-group">
            <label for="is_group_work">Group Work</label>
            <input name="is_group_work" type="checkbox" value="true" class="form-check-input" />
            <input name="is_group_work" type="hidden" value="false" />
        </div>

        <div class="form-group">
            <label for="marked_entity_file">File</label>
            <input type="file" class="form-control-file" id="marked_entity_file" name="marked_entity_file">
        </div>

        <div class="form-group">
            <label for="due_at">Due at</label>
            <input type="text" id="due_at" name="due_at">
            
            <script>
                
            $("#due_at").datepicker({
                dateFormat: "yy-mm-dd"
            });
            </script>
        </div>

        <button type="submit" class="btn btn-info">Submit</button>
    </form>
</div>
<?php }; ?>
