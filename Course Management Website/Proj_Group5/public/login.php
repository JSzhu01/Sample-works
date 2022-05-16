<?php
require_once "../common.php";
maybe_session_start();
?>

<?php include "templates/header_nomenu.php"; ?>

<!-- <form name="CreateUser" action="" method=post>
<input type=hidden name=submitted_op value=true>
<br>

<br><br>

<b><font size=4><center>Create a new user</font></b>
<br><br>
<table border=1>

        <tr>
		<th bgcolor=pink>Current Email
		</th>
		<td>x_zhu202@encs.concordia.ca</td>
	</tr>
	
	<tr>
		 <th bgcolor=pink> New Email
		</th>
		<td><input type=text name=new_email size=40>
            	</td>
	</tr>
	<tr>
		 <th bgcolor=pink> Confirm New Email
		</th>
		<td><input type=text name=new_email_retyped size=40>
            	</td>
	</tr>
</table><br>
<table border=0>
	<tr>
	  <td align = "center"><input type = "button" value = "   Change   " onclick=submitInfo();>
	                                     <input type = "reset" value = "    Clear     " >
	  </td>
	</tr>
</table>


<br>


</form> -->

<div style= "padding-top: 30px">
	<form name="CreateUser" method="post" action="login_submit.php">
		<table border="1" cellpadding="10" cellspacing="1" width="500" align="center" class="tblLogin">
			<tr class="tableheader">
			<td align="center" colspan="2"><font size = 4><b>Enter Login Details</b></font></td>
			</tr>
			<tr class="tablerow">
			<th bgcolor=pink> Username (Email): </th>
			<td><input type="text" name="email" placeholder="Email" class="login-input"></td>
			</tr>
			<tr class="tablerow">
			<th bgcolor=pink> Password: </th>
			<td><input type="password" name="password" placeholder="Password" class="login-input"></td>
			</tr>
		</table>
		<table border =0 align = "center">
			<tr class="tableheader">
			<td align="center" colspan="4"><input type="submit" name="submit" value="Confirm" class="btnSubmit"></td>
			</tr>
		</table>
	</form>
</div>

<?php include "templates/footer.php"; ?>