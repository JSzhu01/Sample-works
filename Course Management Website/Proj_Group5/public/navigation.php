<?php 
// Include the database config file 
require_once "../common.php"; 
ensure_logged_in();
include "templates/header.php"; 

$database = getConnection();

$sid = '';
$me_id ='';
$gid = '';
$type ='';
$go_sid = False;
$go_me = False;
$go_gid = False;
$go_type = False;
$is_expired = False;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!empty($_POST['course_section'])){
        $sid = $_POST['course_section'];
        $go_sid = True;
    }
    if(!empty($_POST['marked_entities'])){
        list($me_id,$due_date) = explode(',',$_POST['marked_entities']);
        $go_me = True;
        if(new DateTime > new DateTime($due_date)){
            $_SESSION['me_expired_'.$me_id] = True;
        }
        else{
            $_SESSION['me_expired_'.$me_id] = False;
        }

    }
    if(!empty($_POST['course_group'])){
        $gid = $_POST['course_group'];
        $go_gid = True;
    }
    if(!empty($_POST['page_type'])){
        $type = $_POST['page_type'];
        $go_type = True;
    }



    if($go_sid && $go_me && $go_gid && !$go_type){
        echo '<a href="main.php">Back to Home</a><br></br>';
        die("Page type not specified!");
    }
    if($go_sid && !$go_me && !$go_gid){
        header("Location:marked_entities.php?sid=".$sid);
    }
    if($go_sid && $go_me && !$go_gid && !$go_type){
        header("Location:marked_entity.php?id=".$me_id);
    }
    if($go_sid && $go_me && $go_gid && $go_type){
        if(strcmp($type,'Discussion') == 0){
                    //Grant access to everything in the future
                    $_SESSION['me_access_'.$me_id] = True;
                    //echo $_SESSION['me_access_'.$marked_entity['id']] 
                    $_SESSION['me_'.$me_id.'_group_access_'.$gid] = True;
                    $_SESSION['is_old_group_'.$gid] = False;  
            header("Location:discussions.php?me=".$me_id."&gid=".$gid);
        }
        if(strcmp($type,'File') == 0){
            $_SESSION['me_access_'.$me_id] = True;
            //echo $_SESSION['me_access_'.$marked_entity['id']] 
            $_SESSION['me_'.$me_id.'_group_access_'.$gid] = True;
            $_SESSION['is_old_group_'.$gid] = False; 
            header("Location: marked_entity_files.php?marked_entity_id=".$me_id."&group_id=".$gid);
        }
        
    }



}
else{
    die("Likely an illegal access!");
}




?>