<?php
include "templates/header.php";

require_once("connection.php");  //view user
require_once "../modules/models/course_group.php";
require_once "../common.php"; 

$gid = $_GET['Gid'];
$course = $_GET['course'];
$sid = $_GET['Sid'];

$firstName ='';
$lastName ='';

$query1 = "select u.first_name, u.last_name, u.id, ug.user_group_id, ug.group_id from users u, user_group ug
where u.id = ug.user_id and ug.group_id = '".$gid."' ";
$result1 = mysqli_query($con,$query1); 

//get group leader
$query2 = "
SELECT 
u.first_name, u.last_name
FROM
course_group cg,
users u
WHERE
cg.leader_id = u.id AND cg.group_id = '$gid' ";
$result2 = mysqli_query($con,$query2); 
while($row = mysqli_fetch_assoc($result2))
{
    $firstName = $row['first_name'];
    $lastName = $row['last_name'];
}

//assign group leader
if(isset($_POST['assignLeader'])){
    $leaderId = $_POST['uid'];
    $query4 = "update course_group set leader_id = '$leaderId' where group_id = '$gid' ";
    $result4 = mysqli_query($con,$query4);
    header("Refresh:0"); 
}

//delete group leader
if(isset($_POST['del'])){
    $query3 = "update course_group set leader_id = null where group_id = '$gid' ";
    $result3 = mysqli_query($con,$query3); 
    header("Refresh:0"); 
}

//delete Memember (check leader)
if(isset($_POST['delMemember'])){
    //check if leader
    $usergid = $_POST['usergid'];
    $uid = $_POST['uid'];
    $gid = $_POST['gid'];
    //echo $uid;

    $dup = mysqli_query($con,"
    SELECT 
    *
    FROM
    course_group cg,
    user_group ug
    WHERE
    cg.group_id = ug.group_id
    and cg.leader_id = ug.user_id
    AND ug.user_id = '$uid' ");
    
    if(mysqli_num_rows($dup)>0){
        $deleteLeader = mysqli_query($con, "update course_group set leader_id = null where group_id = '$gid' ");
        header("Refresh:0"); 

    }

    $query5 = "delete from user_group where user_group_id = '".$usergid."' ";    
    $result5 = mysqli_query($con,$query5);
    header("Refresh:0"); 
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title> View Group Information</title>
</head>
<body >

                    <div >
                        <div class="title">
                            <h2> View Group Information</h2>
                            <h2> Group Leader: <?php echo $firstName. '  '.$lastName ?>
                            <form method="POST">
                                            <input type="submit" name="del" value="delete"/></form></h2>
                        </div>
                        <table>
                                <thead>
                                        <tr>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Assign leader</th>
                                        <th>Delete</th>
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while($row = mysqli_fetch_assoc($result1))
                                    {
                                        $fn = $row['first_name'];
                                        $ln = $row['last_name'];
                                        $uid = $row['id']; 
                                        $ugid = $row['user_group_id'];  //reserve for delete user in the group
                                        $gid = $row['group_id'];
                                    ?>
                                <tr>
                                        <td><?php echo $fn ?></td>
                                        <td><?php echo $ln ?></td>
                                        <td><form method="POST">
                                            <input type="hidden" name="uid" id="uid" value = "<?php echo $uid ?>">
                                            <input type="submit" name="assignLeader" value="assign"/></form></td>
                                        <td><form method="POST">                                            
                                            <input type="hidden" name="usergid" id="usergid" value = "<?php echo $ugid ?>">
                                            <input type="hidden" name="uid" id="uid" value = "<?php echo $uid ?>">
                                            <input type="hidden" name="gid" id="gid" value = "<?php echo $gid ?>">
                                            <input type="submit" name="delMemember" value="Delete"/></td> 
                                     </tr>
                                    <?php } ?>
                                </tbody>
                        </table> 
                    </div>
    
</body>
</html>

<html>

<br>
<a href="editGroup.php?sectionId=<?php echo $sid ?>&course=<?php echo $course ?>">Back to Last Page</a>
