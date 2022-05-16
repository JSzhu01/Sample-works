<?php
include "templates/header.php";

require_once("connection.php");
require_once "../modules/models/course_section.php";
require_once "../modules/models/enrollment.php";
require_once "../common.php"; 

//section info
$id = $_GET['GetId'];
$Cid = $_GET['GetId'];
$query = "select section_id, course_num, user_id, section, semester, year from course_section where course_num = '".$id."'";
$result = mysqli_query($con,$query); 
$result4 = mysqli_query($con,$query); 

//users in course info
$query1 = "
SELECT 
	u.first_name, u.last_name, cs.section, cs.section_id, u.id, u.student_id
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

//add/delete course section
try {
    $result1 = CourseSection::getAll();
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

if (isset($_POST['submitSection'])) {
    $course_section = new CourseSection();
    $course_section->course_num = $id;
    $course_section->section = trim(strtoupper($_POST['section']));
    $course_section->semester = trim(strtoupper($_POST['semester']));
    $course_section->year = $_POST['year'];
    
    $create_success = false;
    try {
        $course_section->save();
        $create_success = true;
        echo '<br>';
        echo "<center>".trim(strtoupper($_POST['section'])) .' Added'."</center>";
    } catch (PDOException $error) {
        echo "<center>Something went wrong! Course section was not added successfully, it might already exist. Please try again.</center>";
    }
}

//add course section
if (isset($_POST['submitSection']) && $create_success) { 
    header("Refresh:1");
}

//assign student
if (isset($_POST['submit'])) {
    $query3 ="select section_id from course_section where course_num = '$id' and section = '".trim($_POST['section'])."'";
    $result3 = mysqli_query($con,$query3); 
    while ($row = mysqli_fetch_assoc($result3)) {
        $sid = $row['section_id']; 
        //echo $sid;
    }

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

    //check if student aleady in some section
    $dup1 = mysqli_query($con,"
    SELECT 
    *
    FROM
    enrollment e,
    course_section cs
    WHERE
        cs.course_num = '$id'
        AND cs.section_id = e.section_id
        AND e.user_id = '$uid';
    ");
    
    if(mysqli_num_rows($dup0)>0){
        echo '<center>Student already in this section.</center>';
    }else if(mysqli_num_rows($dup1)>0){
        echo '<center>Student already in other section.</center>';
    }else{      
        $enrollment = new Enrollment();
        $enrollment->user_id = $uid;
        $enrollment->section_id = $sid;
    
        try {
            $enrollment->save();
            $create_success = true;
            echo '<center>Assigned</center>';
        } catch (PDOException $error) {
            echo '<center>User doesnt exist or already in this section</center>';
            //echo "<br>" . $error->getMessage();
        }
    }
    header("Refresh:2");
}

//only admin can add section
if($_SESSION["current_user"] -> privilege == 0){      
?>
    
<h2> <?php echo 'Course Num: ' .strtoupper($id);?></h2>
<form method="post">
<table border="1" cellpadding="10" cellspacing="1" width="500" align="center" class="tblLogin">
</select>
        </tr>
        <tr class="tablerow">
		<th bgcolor=pink> Section</th>
		<td> <input type="text" name="section" id="section" pattern="[A-Za-z]{1,4}" maxlength="4" title="Ex: NN, Maximum length 4" required></td>
		</tr>
        <tr class="tablerow">
		<th bgcolor=pink> Semester</th>
		<td> <select name="semester" id="semester">  
            <option value="WNTR">Winter</option>
            <option value="SUMR">Summer</option>  
            <option value="FALL">Fall</option>  
            </select></td>
		</tr>
        <tr class="tablerow">
		<th bgcolor=pink> Year</th>
		<td> <input type="number" min="1974" name="year" id="year"  min="1974" max= "2030" title="Ex: 1974-2030" required></td>
		</tr>
    </table>
    <table border =0 align = "center">
		<tr class="tableheader">
		<td align="center" colspan="4"><input type="submit" name="submitSection" value="Add" class="btnSubmit"></td>
		</tr>
	</table>
</form>
<?php } ?>

<h2> Assign Student</h2>
<form method="post">
<table border="1" cellpadding="10" cellspacing="1" width="500" align="center" class="tblLogin">
</select>
        </tr>
        <tr class="tablerow">
		<th bgcolor=pink> Section</th>
		<td> <select name="section" id="section">
            <?php 
            while($row = mysqli_fetch_assoc($result4)){   
                $section = $row['section'];
                echo "<option value='$section'>$section</option>";
            }?></select></td>
		</tr>
        <tr class="tablerow">
		<th bgcolor=pink> Student ID</th>
		<td> <input type="text" name="stdId" pattern="[0-9]{1,}" title="*Must be a number*" id="stdIid" required></td>
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
   
    <title>Edit Section</title>
</head>
<body >

                    <div >
                        <div class="title">
                            <h2 > View Section Information </h2>
                        </div>
                        <table>
                                <thead>
                                        <tr>
                                        <th>Section</th>
                                        <th>Semester</th>
                                        <th>Year</th>
                                        <th>Group</th>
                                        <th>Delete</th>
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
                                        <td><a href = "marked_entities.php?sid=<?php echo $sid ?>"><?php echo $section ?></a></td>
                                        <td><?php echo strtoupper($semester) ?></td>
                                        <td><?php echo $year ?></td>
                                        <td><a href="editGroup.php?sectionId=<?php echo $sid ?>&course=<?php echo $id ?>"> View </a></td>
                                        <td><a href="deleteSection.php?del=<?php echo $sid ?>
                                                        &name=<?php echo $id ?>"> Delete </a></td>
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
                                        <th>Section</th>
                                        <th>Delete</th>
                                        </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while($row = mysqli_fetch_assoc($result2))
                                    {
                                        $stdid = $row['student_id'];
                                        $fn = $row['first_name'];
                                        $ln = $row['last_name'];   
                                        $section = $row['section'];
                                        $uid = $row['id'];
                                        $sid = $row['section_id'];
                                    ?>
                                <tr>
                                        <td><?php echo $stdid ?></td>
                                        <td><?php echo $fn ?></td>
                                        <td><?php echo $ln ?></td>
                                        <td><?php echo $section ?></td>
                                        <td><a href="deleteSectionUser.php?sectionId=<?php echo $sid ?>&userId=<?php echo $uid ?>"> Delete </a></td>
                                    </tr>
                                    <?php } ?>
                                </tbody> 
                        <table> 
                     </div>
                                                      
</body>

</html>

<br>
<a href="modifyCourse.php">Back to Last Page</a>


