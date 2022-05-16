<?php
include "templates/header.php"; 
require_once("connection.php");

$gid = $_GET['Gid'];
$course = $_GET['course'];
$sid = $_GET['Sid'];
$query = "
SELECT 
    cs.course_num, cs.section, cg.group_name
FROM
    course_section cs,
    course_group cg
WHERE
    cs.section_id = cg.section_id
        AND cg.group_id ='".$gid."'";
$result = mysqli_query($con,$query); 

echo"</br>";
while($row = mysqli_fetch_assoc($result))
{
   echo $row['course_num'].'  '.$row['section'].'  '.strtoupper($row['group_name']);  
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Group Name</title>
</head>
<body >

                    <div >
                        <div class="title">
                            <h1 > Edit Group Information</h3>
                        </div>
                        <div class="form-body">
                            <form action="updateGroup.php?Gid=<?php echo $gid ?>" method="post">
                                <Label>New Group Name</Label>
                                <input type="text"  placeholder=" Group Name " name="group_name" pattern="[A-Za-z0-9]{1,20}" maxlength="10" title="Ex: Group, Maximum length 10" > 
                                <br><br>
                               <!--<input type="submit" name="submit" value="Submit">--> 
                               <button name="update">Update</button>
                             
                            </form>

                        </div>
                    </div>
    
</body>
</html>

<a href="editGroup.php?sectionId=<?php echo $sid ?>&course=<?php echo $course ?>">Back to Last Page</a>