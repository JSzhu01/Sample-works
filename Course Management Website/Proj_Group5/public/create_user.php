<?php

require "../modules/models/user.php";

if (isset($_POST['submit'])) {
    $user = new User();
    $user->first_name = $_POST['first_name'];
    $user->last_name = $_POST['last_name'];
    $user->email = $_POST['email'];
    $user->student_id = $_POST['student_id'];
    if($_POST['types'] == "admin"){
        $user->privilege = 0;
    }

    if($_POST['types'] == "instructor"){
        $user->privilege = 1;
    }

    if($_POST['types'] == "ta"){
        $user->privilege = 2;
    }

    $user->password_digest = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $user->save();
        $create_success = true;
    } catch (PDOException $error) {
        $create_success = false;
        //echo "<br>" . $error->getMessage();
    }
}
?>

<?php include "templates/header.php"; ?>

<?php if (isset($_POST['submit'])) {
    if ($create_success) { ?>
        <blockquote><?php echo $_POST['first_name']; ?> was added successfully!</blockquote>
    <?php }
    else{
        echo 'The ID or Email you entered is invalid or already exists. Please try again.';
    }
} ?>


<h2>Add a user</h2>


<form method="post">
<table border="1" cellpadding="10" cellspacing="1" width="500" align="center" class="tblLogin">
        <tr class="tablerow">
        <th bgcolor=pink> User Identified as</th>   
        <td>   <select name="types" id="user_types">
             <option value="student">Student</option>
            <option value="instructor">Instructor</option>
            <option value="ta">TA</option>
            <option value="admin">Admin</option></td>
</select>
        </tr>
        <tr class="tablerow">
		<th bgcolor=pink> ID</th>
		<td> <input type="text" name="student_id" pattern="[0-9]{1,}" title="*Must be a number*" id="student_id" required></td>
		</tr>
		<tr class="tablerow">
		<th bgcolor=pink> First Name </th>
		<td> <input type="text" name="first_name" pattern="[A-Za-z]{1,}" id="first_name" required></td>
		</tr>
		<tr class="tablerow">
		<th bgcolor=pink> Last Name</th>
		<td> <input type="text" name="last_name" id="last_name" pattern="[A-Za-z]{1,}" required></td>
		</tr>
        <tr class="tablerow">
		<th bgcolor=pink> E-mail</th>
		<td><input type="email" name="email" id="email" required></td>
		</tr>
        <tr class="tablerow">
		<th bgcolor=pink> Password</th>
		<td> <input type="password" name="password" id="password" required></td>
		</tr>
    </table>
    <table border =0 align = "center">
		<tr class="tableheader">
		<td align="center" colspan="4"><input type="submit" name="submit" value="Confirm" class="btnSubmit"></td>
		</tr>
	</table>
</form>

<a href="index.php"> Back to Home</a>

<?php include "templates/footer.php"; ?>