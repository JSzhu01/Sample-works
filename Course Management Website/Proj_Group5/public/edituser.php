<?php 
    require "../modules/models/user.php";
    require_once "../common.php";
    require_once "../modules/models/record.php" ;
    maybe_session_start();

	
	//only allow admins to access this page

	if($_SESSION["current_user"] -> privilege != 0) {

		die($_SESSION["current_user"] -> privilege);

        redirect('index.php?');
    }
	
	function redirect($url) {
		ob_start();
		header('Location: '.$url);
        ob_end_flush();
        die();
	}
	
	$id = 0;
	if ( !empty($_GET['id'])) {
		$id = $_REQUEST['id'];
	}
	
	if ( !empty($_POST)) {
	
		// keep track validation errors
		$fnameError = null;
        $lnameError = null;
        $unameError = null;
        $passError = null;
        $typeError = null;
		
        // keep track post values
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $uname = $_POST['uname'];
        $student_id = $_POST['student_id'];
        $id = $_POST['id'];
        $type = $_POST['type'];
		
        // validate input
        $valid = true;
        if (empty($fname)) {
            $fnameError = 'Please enter First Name';
            $valid = false;
        }
        if (empty($lname)) {
            $lnameError = 'Please enter Last Name';
            $valid = false;
        }
        if (empty($uname)) {
            $unameError = 'Please enter Username(e-mail)';
            $valid = false;
        }
        if (empty($student_id)) {
            $passError = 'Please enter Student ID';
            $valid = false;
        }
		if (empty($type)) {
            $typeError = 'Please enter Type';
            $valid = false;
        }
		
		global $duplicate;
		
		// edit user data and only allow such action if username does not exist in the database
		if ($valid) {
            $a_value = 0;
            $i_value = 0;
            if($type == 1){
                $a_value = 1;
            }
            if($type == 2 or $type == 3){
                $i_value = 1 ;
            }

            $user_records = getConnection();
			$sql = "UPDATE users set first_name = ?, last_name = ?, email = ?, student_id=?,privilege = ? WHERE id = ?;";
			$q = $user_records->prepare($sql);
			$sql2 = "SELECT * From users WHERE email = '".$uname."' AND id != '".$id."';";
            $q2 = $user_records -> prepare($sql2);
            $q2 -> execute();
			
			// only allow editing if username does not exist
			if ($q2 -> fetchColumn() == 0){
				$duplicate = 0;
				$q->execute(array($fname,$lname,$uname,$student_id,$type,$id));
				header("Location: view_users.php");
			}
			
			//if duplicate, do not allow
			else{
				$duplicate = 1;
			}
		}
	} 
?>

<?php include "templates/header.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
   <title>Edit User</title>
   <link rel='shortcut icon' type='image/x-icon' href='images/favicon.ico' />
		<meta charset="utf-8">
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>

<body>
    <!-- <div class="container">
		<div class="span10 offset1">
			<div class="page-header">
			<div class='btn-toolbar pull-right'>
			<a href="logout.php" class="btn btn-danger btn-lg">
			  <span class="glyphicon glyphicon-log-out"></span> Log out
			</a>
		</div> -->
			<h2>Edit User</h2>
			
			<div class="row">
			<div class="panel panel-default">
			<div class="panel-body">
			<form class="form-horizontal" action="edituser.php" method="post">
				<input type="hidden" name="id" value="<?php echo $id ;?>">
				<div class="control-group <?php echo !empty($fnameError)?'error':'';?>">
                <table border="1" cellpadding="10" cellspacing="1" width="500" align="center" class="tblLogin">
				<tr class = "tablerow"> <th bgcolor=pink> <label class="control-label">First Name:</label> </th>
				<td><div class="controls">
					<input name="fname" type="text" class="form-control" placeholder="First Name" value="<?php echo !empty($fname)?$fname:'';?>">
					<?php if (!empty($fnameError)): ?>
						<span class="help-inline"><?php echo $fnameError;?></span>
					<?php endif; ?>
                    </td>
                    </tr>
				</div>
			</div>
			<div class="control-group <?php echo !empty($lnameError)?'error':'';?>">
            <tr class = "tablerow"> <th bgcolor=pink> <label class="control-label">Last Name:</label></th>
				<td><div class="controls">
					<input name="lname" type="text" class="form-control" placeholder="Last Name" value="<?php echo !empty($lname)?$lname:'';?>">
					<?php if (!empty($lnameError)): ?>
						<span class="help-inline"><?php echo $lnameError;?></span>
					<?php endif; ?>
                    </td>
                    </tr>
				</div>
			</div>
			<div class="control-group <?php echo !empty($unameError)?'error':'';?>">
            <tr class = "tablerow"> <th bgcolor=pink><label class="control-label">Username:</label></th>
				<td><div class="controls">
					<input name="uname" type="text" class="form-control" placeholder="Username" value="<?php echo !empty($uname)?$uname:'';?>">
					<?php if (!empty($unameError)): ?>
						<span class="help-inline"><?php echo $unameError;?></span>
					<?php endif; ?>
                    </td>
                    </tr>
				</div>
			</div>
			<div class="control-group <?php echo !empty($passError)?'error':'';?>">
            <tr class = "tablerow"> <th bgcolor=pink><label class="control-label">Student ID:</label></th>
				<td><div class="controls">
					<input name="student_id" type="student_id" class="form-control" placeholder="Student_ID" value="<?php echo !empty($student_id)?$student_id:'';?>">
					<?php if (!empty($passError)): ?>
						<span class="help-inline"><?php echo $passError;?></span>
					<?php endif;?>
                    </td>
                    </tr>
				</div>
			</div>
			<br/>
			<div class="span10 offset1">
            <tr class = "tablerow" >
				<td colspan ="2" align = "center" ><form action = "edituser.php" method = "post">
				  <input type="radio" name="type" value=3 checked> Student<br>
				  <input type="radio" name="type" value=2> Teaching Assistant<br>
				  <input type="radio" name="type" value=1> Teacher<br>
				  <input type="radio" name="type" value=0> Administrator<br>
                    </td>
                    </tr>
				  <br/>
                    </table>
                    <table border =0 align = "center">
		<tr class="tableheader">
        <td align="center" colspan="4"><input type="submit" name="submit" class= "btn btn-default" value="Update">
				</form>
                    </tr>
                    <!-- new -->
                    <tr class="tableheader">
        <td align="center" colspan="4"><a href="reset_password.php?id=<?php echo $id; ?>"> Reset Password </a>
                    </td>
                    </tr>
                    <!-- new -->
                    </table>
                    <a class="btn btn-default" href="view_users.php"> view user page</a></td>
			</div>
			</div>
			</div>
			<br>
			</form>
			</div>
			</div>
			</div>
		</div>
    </div> <!-- /container -->
  </body>
</html>

<script>
	<?php 
	 if($duplicate === 1){
		echo "toastr.error('duplicate User!!');";
	 }
	?>
</script>

<?php include "templates/footer.php"; ?>