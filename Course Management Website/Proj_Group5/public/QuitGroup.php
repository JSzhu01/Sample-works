<?php
include "templates/header.php"; 
require_once("connection.php");
echo "<br>";

if( isset($_GET['del']))
{
    $ugid = $_GET['del'];
    $current_userId = $_SESSION["current_user"]-> id;
    $query = "delete from user_group where user_group_id = $ugid ";
    $result = mysqli_query($con,$query);

    if ($result){
        header('Location: ' . $_SERVER["HTTP_REFERER"] );
        exit;
        echo("dropped");
    }
    else
    {
        echo 'Please check your Query'; 
    }
}
else
{
    echo("Something is going wrong, please try it again");
}

?>
<br>
<button onclick="history.go(-1);">Back </button>