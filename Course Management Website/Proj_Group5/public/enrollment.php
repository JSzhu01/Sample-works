<?php include "templates/header.php"; 

require_once("connection.php");
echo "<br>";
if(isset($_GET['sectionId']))
{
    $sid = $_GET['sectionId'];
    $cid = $_GET['course']; 
    $id = $_SESSION["current_user"]-> id;   

    $dup = mysqli_query($con,"select *from course_section cs, enrollment e 
    where cs.section_id = e.section_id and cs.course_num = '$cid' and e.user_id = '$id'");

    if(mysqli_num_rows($dup)>0){
        echo "You are already enrolled in a section for this course.";

    }else{
        $query = "insert into enrollment (user_id, section_id) values ('$id', '$sid')";
        $result = mysqli_query($con,$query);
    
        if ($result)
        {
            echo 'enrolled';
        }
        else
        {
            echo 'You are already in this course';
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
