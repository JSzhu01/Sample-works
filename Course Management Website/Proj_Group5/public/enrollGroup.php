<?php include "templates/header.php";   
require_once("connection.php");
echo "<br>";
if(isset($_GET['Id']))
{
    $gid = $_GET['Id']; //group id
    $sid = $_GET['sectionId'];
    $id = $_SESSION["current_user"]-> id;   

    $dup = mysqli_query($con,"select * from user_group 
        where (user_id = '$id' and group_id = '$gid')"); //enroll in a group once

    $dup1 = mysqli_query($con,"
    SELECT 
        cg.group_name
    FROM
        course_section cs,
        course_group cg,
        user_group ug
    WHERE
        cs.section_id = cg.section_id
        AND cg.group_id = ug.group_id
        AND cs.section_id = '$sid'
        AND ug.user_id = '$id'"); //once enroll in once group
   
    if(mysqli_num_rows($dup)>0){
        echo "You are already in this group.";
    }else if(mysqli_num_rows($dup1)>0){
        echo "You can only enroll in one group.";
    }    
    else{
        $query = "insert into user_group (user_id, group_id) values ('$id', '$gid')";
        $result = mysqli_query($con,$query);
    
        if ($result)
        {
            header('Location: ' . $_SERVER["HTTP_REFERER"] );
            exit;
            echo 'enrolled';
        }
        else
        {
            echo 'Something went wrong! Please try again.';
        }
    }

  
}
else
{
    echo 'Something went wrong! Please try again.';
}

?>

<br>
<button onclick="history.go(-1);">Back </button>
