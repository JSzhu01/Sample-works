<?php
include "templates/header.php";
require_once("connection.php");
require_once "../modules/models/course_group.php";
require_once "../common.php"; 

$sid = $_GET['GetId']; //course_section id
$query = "select group_id, group_name from course_group where section_id = '".$sid."'";
$result = mysqli_query($con,$query); 

//display current course section info
$query2 = "select course_num, section, semester, year from course_section where section_id = '".$sid."'";
$result2 = mysqli_query($con,$query2); 

//get info from enrolled group
$current_userId = $_SESSION["current_user"]-> id;
$query1 = "
SELECT 
    cg.group_name, ug.group_id, ug.user_group_id
FROM
    course_section cs,
    course_group cg,
    user_group ug
WHERE
    cs.section_id = cg.section_id
        AND cg.group_id = ug.group_id
        AND cs.section_id = '".$sid."'
        AND ug.user_id = '$current_userId' ";
$result1 = mysqli_query($con,$query1); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Form</title>
</head>
<body >

                    <div >
                        <div class="title">
                            <h2><?php //display course info
                            while($row = mysqli_fetch_assoc($result2))
                            {
                               echo strtoupper($row['course_num'].'  '.$row['section'].'  '.$row['semester'].'  '.$row['year']);
                            }?></h2>
                            <h2><?php //display group info
                            while($row = mysqli_fetch_assoc($result1))
                            {
                                $gid = $row['group_id'];
                                $del = $row['user_group_id'];
                                echo 'Joined group: ' .strtoupper($row['group_name']);
                                ?>                                         
                                <td><a href="QuitGroup.php?del=<?php echo $del ?>"> QUIT </a></td>
                                <?php 
                            }
                            ?></h2>
                            <h3> View Group Information</h3>
                        </div>
                        <table>
                                <thead>
                                        <tr>
                                        <th>Group Name</th>
                                        <th>Join</th>
                                        <th>View users</th>
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while($row = mysqli_fetch_assoc($result))
                                    {
                                        $gid = $row['group_id'];
                                        $name = $row['group_name'];
                                    ?>
                                <tr>
                                        <td><?php echo strtoupper($name) ?></td>
                                        <td><a href="enrollGroup.php?Id=<?php echo $gid ?>&sectionId=<?php echo $sid?>"> join </a></td>
                                        <td><a href="displayGroupUser.php?Id=<?php echo $gid ?>&name=<?php echo $name?>"> View </a></td>
                                   </tr>
                                    <?php } ?>
                                </tbody>
                        </table>
                        <br><br>       
                    </div>                    
</body>
</html>

<html>

<a href="joinCourse.php">Back to Last Page</a></html>