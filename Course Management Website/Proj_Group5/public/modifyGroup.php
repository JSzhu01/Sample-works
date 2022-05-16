<?php
require_once("connection.php");
include "templates/header.php";
require_once "../modules/models/course_section.php";
require_once "../modules/models/enrollment.php";
require_once "../common.php"; 

//section info
$query = "select * from course_section where user_id = '".$_SESSION["current_user"] -> id."'";
$result = mysqli_query($con,$query); 

/*
//users in course section info
$query1 = "
SELECT 
	u.first_name, u.last_name, cs.section
FROM
	users u,
	enrollment e,
	courses c,
	course_section cs
WHERE u.id = e.user_id 
	AND e.section_id = cs.section_id 
    and c.course_num = cs.course_num
    AND c.course_num = '".$id."' ";   
$result2 = mysqli_query($con,$query1); 
*/

echo "<br>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
   
    <title>Edit Section</title>
</head>
<body >

                    <div >
                        <div class="title">
                            <h2 > Your Course Sections</h2>
                        </div>
                        <table>
                                <thead>
                                        <tr>
                                        <th>Course num</th>                                            
                                        <th>Section</th>
                                        <th>Semester</th>
                                        <th>Year</th>
                                        <th>Group</th>
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while($row = mysqli_fetch_assoc($result))
                                    {
                                        $id = $row['course_num'];   
                                        $section = $row['section'];
                                        $semester = $row['semester'];
                                        $year = $row['year'];
                                        $sid = $row['section_id'];
                                    ?>
                                <tr>
                                        <td><a href= "marked_entities.php?sid=<?php echo $sid ?>"><?php echo $id ?></a></td>
                                        <td><?php echo $section ?></td>
                                        <td><?php echo $semester ?></td>
                                        <td><?php echo $year ?></td>
                                        <td><a href="editGroup.php?sectionId=<?php echo $sid ?>&course=<?php echo $id ?>"> View </a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody> 
                                <table> 
                    </div>
                                                      
</body>

</html>

<?php if ($_SESSION["current_user"]->privilege == 1) {
 echo '<a href="main.php">Back to Home</a>';
}else{
 echo '<a href="course_list.php">Back to Last Page</a> ';
} ?>
