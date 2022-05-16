<?php
include "templates/header.php";
require_once("connection.php");
require_once "../modules/models/course_group.php";
require_once "../common.php"; 

$Cid = $_GET['Id'];//course num
$query = "select section_id, course_num, user_id, section, semester, year from course_section where course_num = '".$Cid."'";
$result = mysqli_query($con,$query); 

echo '<br>';
?>

<!DOCTYPE html>
<html lang="en">
<head>       
    <title>Edit Section</title>
</head>
<body >
                    <div >
                        <div class="title">
                            <h2><?php echo 'Course Number: '.strtoupper($Cid); ?></h2>
                            <h2 > View Section Information</h2>
                        </div>
                        <table>
                                <thead>
                                        <tr>
                                        <th>Section</th>
                                        <th>Semester</th>
                                        <th>Year</th>
                                        <th>Enroll</th>
                                    <!--    <th>Drop</th>  -->
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
                                        <td><?php echo $section ?></td>
                                        <td><?php echo $semester ?></td>
                                        <td><?php echo $year ?></td>
                                        <td><a href="enrollment.php?sectionId=<?php echo $sid ?>&course=<?php echo $Cid ?>"> enroll </a></td>
                                    <!--    <td><a href="dropSection.php?del=<?php echo $sid ?>"> drop </a></td>  -->
                                    </tr>
                                    <?php } ?>
                                </tbody> 
                                <table> 
                    </div>
                                                      
</body>

</html>

<a href="joinCourse.php">Back to Last Page</a></html>