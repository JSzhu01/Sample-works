<?php
include "templates/header.php";

require_once("connection.php");
require_once "../modules/models/course_group.php";
require_once "../modules/models/enrollGroup.php";
require_once "../common.php"; 

//group info
$sid = $_GET['sectionId'];
$course = $_GET['course'];
$query = "select group_id,  group_name from course_group where section_id = '".$sid."'";
$result = mysqli_query($con,$query); 
$result4 = mysqli_query($con,$query); 

//users in group info
$query1 = "
SELECT 
    cgi.first_name, cgi.last_name, cgi.group_name, student_id
FROM
    student_group_info cgi,
    users
WHERE
    cgi.id = users.id AND section_id =  '".$sid."' ";
$result2 = mysqli_query($con,$query1); 

//display course section 
$query3 ="select course_num, section from course_section where section_id = '".$sid."'";
$result3 = mysqli_query($con,$query3); 
while ($row = mysqli_fetch_assoc($result3)) {
    $crs_num = $row['course_num'];
    $crs_sec = $row['section']; 
}

echo "<br>";

//add course group
try {
    $result1 = CourseGroup::getAll();
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

if (isset($_POST['submitGroup'])) {
    $course_group = new CourseGroup();
    $course_group->section_id = $sid;
    $course_group->group_name = trim(strtolower($_POST['group_name']));
     
    //check duplicate value
    $name = trim($_POST['group_name']);
    $dup = mysqli_query($con,"select * from course_group 
        where (section_id = '$sid' and group_name = '$name')");
   
    if(mysqli_num_rows($dup)>0){
        $create_success = false;
        echo "<center>Group " .$_POST['group_name']. " already exist.</center>";
    }else{
        try {
            $course_group->save();
            $create_success = true;
            echo "<center>Group " .$_POST['group_name']. " Added.</center>";
        } catch (PDOException $error) {
            echo '<center>Invaild group name</center>';
            //echo "<br>" . $error->getMessage();
        }
    }
    header("Refresh:1");

}


//assign user into group
if (isset($_POST['submit'])) {

    //get student id via user id
    $uid = null;
    $query5 ="select id from users where student_id = '".trim($_POST['stdId'])."'";
    $result5 = mysqli_query($con,$query5);     
    while ($row = mysqli_fetch_assoc($result5)) {
        $uid = $row['id']; 
        //echo $uid;
    }

    //check if student in current section
    $dup0 = mysqli_query($con,"
    SELECT 
        *
    FROM
        enrollment
    WHERE
        section_id = '$sid' AND user_id = '$uid';
    ");    

    //check duplicate before assign
    $dup1 = mysqli_query($con,"
    SELECT 
        *
    FROM
        course_group cg,
        user_group ug
    WHERE
        cg.group_id = ug.group_id
            AND ug.user_id = '$uid'
            AND cg.section_id = '$sid'");

    if(mysqli_num_rows($dup0)<1){
        echo '<center>Student is not enrolled in this section.</center>'; 
    }else{
        if(mysqli_num_rows($dup1)>0){
            echo '<center>You can only assign one student into one group.</center>'; 
        }else{
            $query3 ="select group_id from course_group where section_id = '$sid' and group_name = '".trim($_POST['group_name'])."'";
            $result3 = mysqli_query($con,$query3); 
            while ($row = mysqli_fetch_assoc($result3)) {
                $gid = $row['group_id']; 
                //echo $sid;
            }
            
            $enrollment = new EnrollGroup();
            $enrollment->user_id = $uid;
            $enrollment->group_id = $gid;
        
            try {
                $enrollment->save();
                $create_success = true;
                echo '<center>Assigned</center>';
            } catch (PDOException $error) {
                echo '<center>Student id doesnt exist.</center>';
                //echo "<br>" . $error->getMessage();
            }
            header("Refresh:1");
        }
    }    
}

//only admin can add group
if($_SESSION["current_user"] -> privilege == 0 || $_SESSION["current_user"] -> privilege == 1){      
?>

<h2 > <?php echo strtoupper($crs_num).'  '.strtoupper($crs_sec) ?></h2>
<form method="post">
<table border="1" cellpadding="10" cellspacing="1" width="500" align="center" class="tblLogin">
</select>
        </tr>
        <tr class="tablerow">
		<th bgcolor=pink> Group Name</th>
		<td> <input type="text" name="group_name" pattern="[A-Za-z0-9]{1,20}" id="group_name" maxlength="10" title="Ex: Group, Maximum length 10"required></td>
		</tr>
    </table>
    <table border =0 align = "center">
		<tr class="tableheader">
		<td align="center" colspan="4"><input type="submit" name="submitGroup" value="Add" class="btnSubmit"></td>
		</tr>
	</table>
</form>
<?php } ?>

<h2 > Assign Student</h2>
<form method="post">
<table border="1" cellpadding="10" cellspacing="1" width="500" align="center" class="tblLogin">
</select>
        </tr>
        <tr class="tablerow">
		<th bgcolor=pink> Group Name</th>
		<td> <select name="group_name" id="group_name">
            <?php 
            while($row = mysqli_fetch_assoc($result4)){   
                $gname = strtoupper($row['group_name']);
                echo "<option value='$gname'>$gname</option>";
            }?>
            </select></td>
		</tr>
        <tr class="tablerow">
		<th bgcolor=pink> Student ID</th>
		<td> <input type="text" name="stdId" pattern="[0-9]{1,}" title="*Must be a number*" id="stdId" required></td>
		</tr>
    </table>
    <table border =0 align = "center">
		<tr class="tableheader">
		<td align="center" colspan="4"><input type="submit" name="submit" value="Assign" class="btnSubmit"></td>
		</tr>
	</table>
</form>

<!DOCTYPE html>
<html lang="en">
<head>
   
    <title>Edit Group</title>
</head>
<body >

                    <div >
                        <div class="title">
                            <h2 > View Group Information</h2>
                        </div>
                        <table>
                                <thead>
                                        <tr>
                                        <th>Group Name</th>
                                        <th>View users</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
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
                                        <td><a href="viewGroupUsers.php?Gid=<?php echo $gid ?>&course=<?php echo $course ?>&Sid=<?php echo $sid ?>"> View </a></td>
                                        <td><a href="editGroupName.php?course=<?php echo $course ?>&Sid=<?php echo $sid ?>&Gid=<?php echo $gid ?>"> Edit </a></td>
                                        <td><a href="deleteGroup.php?del=<?php echo $gid ?>
                                                        "> Delete </a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody> 
                                <table> 
                    </div>
                    <div >
                            <h2 > View Registered Students</h2>
                        <table>                          
                                <thead>
                                        <tr>
                                        <th>Student ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Group</th>
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while($row = mysqli_fetch_assoc($result2))
                                    {
                                        $stdid = $row['student_id'];
                                        $fn = $row['first_name'];
                                        $ln = $row['last_name'];   
                                        $gname = $row['group_name'];
                                    ?>
                                <tr>
                                        <td><?php echo $stdid ?></td>
                                        <td><?php echo $fn ?></td>
                                        <td><?php echo $ln ?></td>
                                        <td><?php echo strtoupper($gname) ?></td>
                                    </tr>
                                    <?php } ?>
                                </tbody> 
                        <table> 
                     </div>
                                                      
</body>

</html>
<br>
<?php if($_SESSION["current_user"] -> privilege == 1){?>
    <a href="modifyGroup.php">Back to Last Page</a>
<?php } else {?> 
    <a href="editSection.php?GetId=<?php echo $course ?>">Back to Last Page</a>
<?php } ?>

