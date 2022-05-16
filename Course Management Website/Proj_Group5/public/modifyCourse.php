<?php 
include "templates/header.php"; 
require_once("connection.php");

$query = "select course_num, course_name, semester, year, credit_hours, room, offering_dept from courses";
$result = mysqli_query($con,$query); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>View Records</title>
</head>
<body >

                    <div >
                        <div class="title">
                            <h1>View Course Records </h1>
                        </div>
                        <div class="table-body">
                        <table>
                                <thead>
                                        <tr>
                                        <th>Course Num</th>
                                        <th>Course Name</th>
                                        <th>Semester</th>
                                        <th>Year</th>
                                        <th>Credit</th>
                                        <th>Room</th>
                                        <th>Department</th>
                                        <th>Section</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                        </tr>
                                </thead>
                                <tbody>

                                <?php
                                        while($row = mysqli_fetch_assoc($result))
                                        {
                                             $id = $row['course_num'];   
                                             $name = $row['course_name'];
                                             $semester = $row['semester'];
                                             $year = $row['year'];
                                             $credit_hours = $row['credit_hours'];
                                             $room = $row['room'];
                                             $offering_dept = $row['offering_dept'];                                        
                                ?>
                                    <tr>
                                        <td><?php echo strtoupper($id) ?></td>
                                        <td><?php echo $name ?></td>
                                        <td><?php echo strtoupper($semester) ?></td>
                                        <td><?php echo $year ?></td>
                                        <td><?php echo $credit_hours ?></td>
                                        <td><?php echo strtoupper($room) ?></td>
                                        <td><?php echo strtoupper($offering_dept) ?></td>
                                        <td><a href="editSection.php?GetId=<?php echo $id ?>"> More info </a></td>
                                        <td><a href="editCourse.php?GetId=<?php echo $id ?>"> Edit </a></td>
                                        <td><a href="deleteCourse.php?del=<?php echo $id ?>"> Delete </a></td>
                                    </tr>
                                <?php
                                        }
                                ?>

                                        </tbody>
                        </table>
                
                        </div>
                    </div>
    
</body>
</html>
<br>
<a href="course_list.php">Back to Last Page</a>