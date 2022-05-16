<!DOCTYPE html>
<html lang="en">
<head>
    <?php include "templates/header.php"; 
        require_once("connection.php");
        //get info from courses
        $query = "select course_num, course_name, semester, year, credit_hours, room, offering_dept from courses";
        $result = mysqli_query($con,$query); 

        //get info from enrollment
        $current_userId = $_SESSION["current_user"]-> id;
        $query1 = "
        SELECT 
            cs.course_num, cs.section, cs.semester, cs.year, cs.section_id
        FROM
            course_section cs,
            enrollment e
        WHERE
            e.section_id = cs.section_id
        AND e.user_id = '$current_userId'";
        $result1 = mysqli_query($con,$query1); 

    ?>
    <title>View Course List</title>
</head>
<body >

                    <div >
                        <div class="title">
                            <h1 > View Course List </h3>
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
                                        <td><?php echo $id ?></td>
                                        <td><?php echo $name ?></td>
                                        <td><?php echo $semester ?></td>
                                        <td><?php echo $year ?></td>
                                        <td><?php echo $credit_hours ?></td>
                                        <td><?php echo $room ?></td>
                                        <td><?php echo $offering_dept ?></td>
                                        <td><a href="joinSection.php?Id=<?php echo $id ?>"> more info </a></td>
                                    </tr>
                                <?php
                                        }
                                ?>

                                        </tbody>
                                
                        </table>

                        <h1 > Enrolled Courses Section</h3>
                        <table>                        
                            <thead>
                                <th>Course Number</th>
                                <th>Section</th>
                                <th>Semester</th>
                                <th>Year</th>
                                <th>Join Group</th>
                                <th>Drop</th>
                            </thead>
                            <tbody>
                                <?php
                                        while($row = mysqli_fetch_assoc($result1))
                                        {
                                             $name = $row['course_num']; 
                                             $section = $row['section'];
                                             $semester = $row['semester'];
                                             $year = $row['year'];
                                             $sid = $row['section_id'];  
                                        
                                ?>
                                    <tr>

                                        <td><a href = "marked_entities.php?sid=<?php echo $sid ?>"><?php echo $name ?></a></td>
                                        <td><?php echo $section ?></td>
                                        <td><?php echo $semester ?></td>
                                        <td><?php echo $year ?></td>
                                        <td><a href="joinGroup.php?GetId=<?php echo $sid ?>"> More info </a></td>
                                        <td><a href="dropSection.php?del=<?php echo $sid ?>"> Drop </a></td>
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

<a href="course_list.php">Back to Last Page</a>

<?php   //$user = $_SESSION["current_user"];
        //echo $user -> id; 
        //echo $id?>