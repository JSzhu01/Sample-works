<?php

require_once "../common.php";

if( get_priv() != 0) {
    redirect('index.php?');
}

if(isset($_GET['id'])) {
    $user_records = getConnection();
    $sql = "DELETE FROM users WHERE id=".$_GET['id'];
    $q = $user_records->prepare($sql);
    $q -> execute();
    echo 'Record deleted successfully.';
}