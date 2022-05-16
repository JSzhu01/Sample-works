<?php include "templates/header.php"; ?>

<?php
require_once "../modules/models/marked_entity.php";
require_once "../common.php";

ensure_logged_in();
?>

<?php
$isadmin = False;
$isold = False;
$is_instructor = False;
if(get_priv() == 0){
    // die("admin");
    $isadmin = True;
}
$database = getConnection();

if(!isset($_GET['id'])){
// if($isadmin){
//     $c_sql = 'SELECT * FROM course_section';
//     $get_me = 'SELECT * FROM me_info ORDER BY course_num';
// }
// else{
    echo '<a href ="index.php"> Back to home page </a><br></br>';
    die("No entity specified.");
    
// }
}
else{
$me_id = $_GET['id'];
if(!is_numeric($me_id)){
    echo '<a href ="index.php"> Back to home page </a><br></br>';
    die("Invalid marked entity ID!");
}
$me_sql = 'SELECT * FROM marked_entities WHERE id= '.$me_id;
$me = $database -> prepare($me_sql);
$me -> execute();
$marked_entity = $me -> fetch();
if(!$marked_entity){
    echo '<a href ="index.php"> Back to home page </a><br></br>';
    die("Non existent marked entity!");
}
if(get_priv() == 1){
    $sql = 'SELECT id FROM me_info WHERE id = '.$me_id.' AND instructor_id = '.$_SESSION['current_user_id'];
    $g_sql = 'SELECT DISTINCT group_id,group_name FROM student_me_info where me_id = '.$me_id;
    $is_instructor = True;
}
else if(get_priv() == 3){
    $sql = 'SELECT me_id FROM student_me_info WHERE me_id = '.$me_id.' AND user_id = '.$_SESSION['current_user_id'];
    $sql_check_past = 'SELECT * FROM leftMarkEntity WHERE left_time = (SELECT MAX(left_time) FROM leftMarkEntity WHERE me_id = '.$me_id.' AND user_id = '.$_SESSION['current_user_id'].' ) AND me_id = '.$me_id.' AND user_id = '.$_SESSION['current_user_id'];
    // echo $sql_check_past;
    $o = $database -> prepare($sql_check_past);
    $o -> execute();
    $old_data = $o -> fetchAll();
    if($old_data){
        $isold= True;
    }
    $g_sql = 'SELECT DISTINCT group_id, group_name FROM student_me_info where me_id = '.$me_id.' AND user_id = '.$_SESSION['current_user_id'] ;
}
else if(get_priv() == 0){
    $g_sql = 'SELECT DISTINCT group_id,group_name FROM student_me_info where me_id = '.$me_id;
    // pass...
}
else{
    echo '<a href ="index.php"> Back to home page </a><br></br>';
    die("You have no access to this page!");
}

if(!$isadmin && !$isold){
    $q = $database -> prepare($sql);
    $q-> execute();
    $valid = $q -> fetchAll();
    // echo ($valid);
    if(!$valid){
        echo '<a href ="index.php"> Back to home page </a><br></br>';
        die("You have no access to this marked entity!");
    }
    if($is_instructor){
        $_SESSION['me_instructor_'.$me_id] = $_SESSION['current_user_id'];
    }
}
$g = $database -> prepare($g_sql);
$g-> execute();
$group_info = $g -> fetchAll();
if($isold){
    foreach($group_info as $group){
        foreach($old_data as $old_group){
            if($group['group_id'] == $old_group['group_id']){
                $isold = False;
                break 2;
            }
        }
    }
}
}


if (($me_id) AND ($marked_entity)) {
    $f_sql = 'SELECT * FROM attachments WHERE attachable_id = '.$me_id.' AND attachable_type = "InstructorFile" LIMIT 1';
    $f = $database -> prepare($f_sql);
    $f -> execute();
    $files = $f -> fetchAll();
    ?>
    <div class="container">
        <h4>Marked Entity - <?php echo $marked_entity['title'] ?></h4>
        <p><?php echo $marked_entity['description'] ?></p>

        <h5>Uploaded File(Instructions):</h5>

        <?php
        if (!empty($files) ){
            foreach ($files as $file) { ?>
                <p><a href='<?php echo 'download.php?file_id='.$file['file_id'] ?>'><?php echo $file['file_filename']; ?></a></p>
            <?php }
        } else {
            echo "<p> No files.</p>";
        } ?>

        <h5>Due at: <?php echo $marked_entity['due_at']; 
        $expiry_date = new DateTime($marked_entity['due_at']);
        if(new DateTime() > $expiry_date ){
            $_SESSION['me_expired_'.$marked_entity['id']] = True;
        }
        else{
            $_SESSION['me_expired_'.$marked_entity['id']] = False;
        }
        ?> </h5>
        <?php if($isold){?>
            <b> You have left this Group ! </b>
            <?php foreach($old_data as $group) {?>
        <blockquote><a href="marked_entity_files.php?marked_entity_id=<?php echo $marked_entity['id'] ?>&group_id=<?php echo $group['group_id'] ?>">See Your Previous Works on this Group  </a></blockquote>
        <?php 
              //Grant access to everything in the future, specify old case
                        $_SESSION['me_access_'.$marked_entity['id']] = True;
                        $_SESSION['me_'.$marked_entity['id'].'_group_access_'.$group['group_id']] = True;
                        $_SESSION['is_old_group_'.$group['group_id']] = True;
                        $_SESSION['old_since'.$group['group_id']] = $group['left_time'] ;?>
    <?php }?>
        <?php } else{ ?>
        <h3>Uploaded Courseworks:</h3>
        <?php foreach($group_info as $group) {?>
        <blockquote><a href="marked_entity_files.php?marked_entity_id=<?php echo $marked_entity['id'] ?>&group_id=<?php echo $temp= $group['group_id'] ?? '-1' ?>">See Uploaded Works for Group <?php echo $group['group_name'] ?> </a></blockquote>
        <?php
        //Grant access to everything in the future
            $_SESSION['me_access_'.$marked_entity['id']] = True;
            //echo $_SESSION['me_access_'.$marked_entity['id']] 
            $_SESSION['me_'.$marked_entity['id'].'_group_access_'.$temp] = True;
            $_SESSION['is_old_group_'.$temp] = False;  
    }?>
        <?php }?>
        <h3>Discussion:</h3>
        <?php if($isold){?>
            <?php foreach($old_data as $group) {?>
        <blockquote><a href="discussions.php?me=<?php echo $marked_entity['id'] ?>&gid=<?php echo $group['group_id'] ?>">See Previously Related discussions</a></blockquote>
        <?php }?>
        <?php } else{
                foreach($group_info as $group){ ?>

                <blockquote><a href="discussions.php?me=<?php echo $marked_entity['id'] ?>&gid=<?php echo $group['group_id'] ?? '-1'?>">See Discussions Related to Group <?php echo $group['group_name'] ?></a></blockquote>
<?php
                }

        } ?>
        
    </div>
<?php
}
?>