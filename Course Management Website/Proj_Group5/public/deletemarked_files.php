<?php

require_once "../common.php";
ensure_logged_in();
if( get_priv() != 0) {
    redirect('index.php?');
}

if(isset($_GET['file_id'])){
    echo $_GET['file_id'];
}

if(isset($_GET['id']) && isset($_GET['file_id'])) {
    $user_records = getConnection();
    $pre_delete = 'SELECT * FROM student_file_info WHERE mef_id ='.$_GET['id'];
    $p = $user_records-> prepare($pre_delete);
    $p -> execute();
    $mef = $p -> fetch();
    $i_sql = 'INSERT INTO file_actions (user_id,me_id,fid,action,filename) VALUES (:user_id,:m_id,:fid,"DELETE",:f_name)';
    $dbo = $user_records -> prepare($i_sql);
    $dbo->bindValue(':user_id', $_SESSION["current_user_id"], PDO::PARAM_INT);
    $dbo->bindValue(':m_id', $mef['entity_id'], PDO::PARAM_INT);
    $dbo->bindValue(':fid', $mef['fid'], PDO::PARAM_INT);
    $dbo->bindValue(':f_name', $mef['filename'] , PDO::PARAM_STR);
    $dbo-> execute();
    $sql = "DELETE FROM marked_entity_files WHERE id=".$_GET['id'];
    $q = $user_records->prepare($sql);
    $q -> execute();
    $file_pointer = "uploads/".$_GET['file_id'];
    if (!unlink($file_pointer)) {
        echo ("$file_pointer cannot be deleted due to an error");
    }
    else {
        echo ("$file_pointer has been deleted");
    }
    echo 'Record deleted successfully.';
}