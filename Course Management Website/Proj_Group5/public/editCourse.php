<?php
include "templates/header.php"; 
require_once("connection.php");

$id = $_GET['GetId'];
$query = "select course_num, course_name, semester, year, credit_hours, room, offering_dept from courses where course_num = '".$id."'";
$result = mysqli_query($con,$query); 

echo"</br>";
while($row = mysqli_fetch_assoc($result))
{
    $id = $row['course_num'];   
    $name = $row['course_name'];
    $semester = $row['semester'];
    $year = $row['year'];
    $credit_hours = $row['credit_hours'];
    $room = $row['room'];
    $offering_dept = $row['offering_dept'];   
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!--
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">-->
    <title>Edit Course Information</title>
</head>
<body >

                    <div >
                        <div class="title">
                            <h2><?php 
                                 echo "Course Number: " .strtoupper($id). "<br>";
                                 echo "Course Name: " .strtoupper($name). "<br>";?></h2>
                            <h2> Edit Course Information</h2>
                        </div>
                        <div class="form-body">
                            <form action="updateCourse.php?Id=<?php echo $id ?>" method="post">
                                <Label>Course Number</Label>
                                <input type="text"  placeholder=" Course Number " pattern="[A-Za-z0-9]{1,}" title="Ex: COMP5531" maxlength="8" name="course_num" value ="<?php echo $id ?>" required> 
                                <br><br>
                                <Label>Enter Course name</Label>
                                <input type="text"  placeholder=" Course Name " pattern="[A-Za-z ]{1,}" title="Ex: Databases" maxlength="20" name="course_name" value ="<?php echo $name ?> ">
                                <br><br>
                                <Label>Enter semester</Label>
                                <select name="semester" id="semester">  
                                <option value="WNTR">Winter</option>
                                <option value="SUMR">Summer</option>  
                                <option value="FALL">Fall</option> </select>                                
                                <br><br>
                                <Label>Enter year</Label>
                                <input type="number"  placeholder=" Year " name="year" value ="<?php echo $year ?>" min="1974" max="2030" required > 
                                <br><br>
                                <Label>Enter credit hours</Label>
                                <input type="number"  placeholder=" Credit Hours " name="credit_hours" value ="<?php echo $credit_hours ?> " min="1" max="10" required> 
                                <br><br>
                                <Label>Enter room</Label>
                                <input type="text"  placeholder=" Room " name="room" maxlength="8" value ="<?php echo $room ?> " pattern="[A-Za-z0-9 ]{1,}" > 
                                <br><br>
                                <Label>Enter department</Label>
                                <input type="text"  placeholder=" Offering department " name="offering_dept" maxlength="20" value ="<?php echo $offering_dept ?> " pattern="[A-Za-z0-9 ]{1,}" required> 
                                <br><br>
                               <!--<input type="submit" name="submit" value="Submit">--> 
                               <button name="update">Update</button>
                             
                            </form>

                        </div>
                    </div>
    
</body>
</html>
