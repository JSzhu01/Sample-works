<?php 
// Include the database config file 
require_once "../common.php"; 
ensure_logged_in();

$database = getConnection();
 
if(!empty($_POST["sid"])){ 
    // Fetch mark entities of specific course section
    $query = 'SELECT id,title,due_at FROM marked_entities WHERE sid = '.$_POST['sid'].' ORDER BY due_at DESC'; 
    $q= $database -> prepare($query);
    $q -> execute();
    $result = $q->fetchAll(); 
     
    // Generate HTML of state options list 
    if($result and count($result)){ 
        echo '<option value="">Select Marked Entities</option>'; 
        foreach($result as $row){  
            echo '<option value="'.$row['id'].','.$row['due_at'].'">'.$row['title'].' Due At:'.$row['due_at'].'</option>'; 
        } 
    }else{ 
        echo '<option value="">No marked entities available</option>'; 
    } 
}elseif(!empty($_POST["me_id"])){ 
    // Fetch group data based on the specific mark entities 
    $query = 'SELECT DISTINCT group_id,group_name FROM student_me_info WHERE me_id = '.$_POST['me_id']; 
    $q= $database -> prepare($query);
    $q -> execute();
    $result = $q->fetchAll(); 
     
    // Generate HTML of group options list 
    if($result and count($result)){ 
        echo '<option value="">Select Course Group </option>'; 
        foreach($result as $row){  
            $query = 'SELECT DISTINCT first_name FROM student_me_info WHERE group_id = '.$row['group_id']; 
            $q= $database -> prepare($query);
            $q -> execute();
            $group_members = $q->fetchAll(); 
            $str = '';
            foreach($group_members as $group_member){
                $str .= (' '.$group_member['first_name']);
            }
            echo '<option value="'.$row['group_id'].'"> Group Name: '.$row['group_name'].'|Group members : '.$str.'</option>'; 
        } 
    }else{ 
        echo '<option value="">No Course Group available</option>'; 
    } 
} 
?>