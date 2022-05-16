<?php
/*get connection*/
require "../modules/models/user.php";
require_once "../modules/models/course.php";
require_once "../modules/models/course_group.php";
require_once "../common.php";
ensure_logged_in();

echo "<br>";
//add/delete course
$course_success = false;
try {
    $result = Course::getAll();
} catch (PDOException $error) {
    echo $sql . "<br>" . $error->getMessage();
}

if (isset($_POST['submitAdd'])) {
    $course = new Course();
    $course->course_num = $_POST['course_num']; //primary key
    $course->course_name = $_POST['course_name'];
    $course->semester = $_POST['semester'];
    $course->year = $_POST['year'];
    $course->credit_hours = $_POST['credit_hours'];
    $course->room = $_POST['room'];
    $course->offering_dept= $_POST['offering_dept'];

    try {
        $course->save();
        $course_success = true;
    } catch (PDOException $error) {
        //echo "<br>" . $error->getMessage();
    }

    header("Refresh:1");
}

?>

<?php include "templates/header.php"; 
    require_once ("connection.php");
?>

<?php //course
    if (isset($_POST['submitAdd']) && $course_success) { ?>
    <blockquote><?php echo $_POST['course_num']; ?> was added successfully!</blockquote>
<?php }
    else if (isset($_POST['submitAdd']) && !$course_success) { ?>
    <blockquote>Please check the input.</blockquote>
<?php } 
?>

<?php 
if (isset($_SESSION["current_user"])) {
    $user = $_SESSION["current_user"];
    // echo $user -> id;    ---get user id
    if($_SESSION["current_user"] -> privilege == 0){  
?>

<!--add course-->
<h2>Add a course</h2>
<form method="post">
<table border="1" cellpadding="10" cellspacing="1" width="500" align="center" class="tblLogin">
</select>
        </tr>
        <tr class="tablerow">
		<th bgcolor=pink> Course Name</th>
		<td> <input type="text" name="course_name" pattern="[A-Za-z ]{1,}" title="Ex: Databases" maxlength="20" id="course_name" required></td>
		</tr>
        <tr class="tablerow">
		<th bgcolor=pink> Course Num</th>
		<td> <input type="text" name="course_num" pattern="[A-Za-z0-9]{1,}" title="Ex: COMP5531" maxlength="8"id="course_num" required></td>
		</tr>
        <tr class="tablerow">
		<th bgcolor=pink> Semester</th>
		<td> 
            <select name="semester" id="semester">  
            <option value="WNTR">Winter</option>
            <option value="SUMR">Summer</option>  
            <option value="FALL">Fall</option>  
            </select></td>
		</tr>
        <tr class="tablerow">
		<th bgcolor=pink> Year</th>
		<td> <input type="number" name="year" min="1974" max= "2030" title="Ex: 1974-2030" id="year" required></td>
		</tr>
        <tr class="tablerow">
		<th bgcolor=pink> Credits</th>
		<td> <input type="number" min="1" name="credit_hours" max= "10" title="Ex: 1-10" id="credit_hours" required></td>
		</tr>
        <tr class="tablerow">
		<th bgcolor=pink> Room</th>
		<td> <input type="text" name="room" pattern="[A-Za-z0-9 ]{1,}" maxlength="8" id="room"></td>
		</tr>
        <tr class="tablerow">
		<th bgcolor=pink> Department</th>
		<td> <input type="text" name="offering_dept" pattern="[A-Za-z0-9 ]{1,}" maxlength="20" id="offering_dept" required></td>
		</tr>
    </table>
    <table border =0 align = "center">
		<tr class="tableheader">
		<td align="center" colspan="4"><input type="submit" name="submitAdd" value="Add" class="btnSubmit"></td>
		</tr>
	</table>
</form>
    <a href="modifyCourse.php">Manage Course and Group</a><br>
<?php }
    else if($_SESSION["current_user"] -> privilege == 1){  
?>
    <a href="modifyGroup.php">Manage Groups</a><br>
<?php
    }else { ?>
    <h5>You need authority to change the course list.</h1>
<?php } 
}

if($_SESSION["current_user"] -> privilege == 3){
?>
    <a href="joinCourse.php">JOIN/DROP Course</a>
<?php } ?>

<h2>Course list</h2>
<?php
//display course list
if ($result && count($result)) { ?>
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
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($result as $row) { ?>
            <tr>
                <td><?php echo strtoupper(escape($row->course_num)); ?></td>
                <td><?php echo escape($row->course_name); ?></td>
                <td><?php echo strtoupper(escape($row->semester)); ?></td>
                <td><?php echo escape($row->year); ?></td>
                <td><?php echo escape($row->credit_hours); ?></td>
                <td><?php echo strtoupper(escape($row->room)); ?></td>
                <td><?php echo strtoupper(escape($row->offering_dept)); ?></td>
                <td><?php echo escape($row->created_at);  ?> </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
        <blockquote>No courses found.</blockquote>
    <?php }
?> 

<br>
<a href="index.php">Back to Home</a>

<?php include "templates/footer.php"; ?>