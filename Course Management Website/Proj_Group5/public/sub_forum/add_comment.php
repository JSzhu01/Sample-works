<?php

	require("config.php");
	require("helper.php");
	require_once "../../common.php";


	if (isset($_POST)) {
		$parent_id = ($_POST['reply_id'] == NULL || $_POST['reply_id'] == '') ? 0 : $_POST['reply_id'];
		$page_id = $_POST['page_id'];
		$group_id = $_POST['group_id'];
		// echo $group_id;
		$course_id = $_POST['course_id'];
		$name = $_POST['reply_author'];
		$comment_text = $_POST['comment_text'];
		$depth_level = ($_POST['depth_level'] == NULL || $_POST['depth_level'] == '') ? 0 : $_POST['depth_level'];;
		$target_dir = "../uploads/";

		$sql = "INSERT INTO comment(page_id,name,comment_text, parent_id,group_id,course_id) VALUES('$page_id','$name','$comment_text', '$parent_id','$group_id','$course_id')";
		// echo $sql;
		$query = dbQuery($sql);
		$inserted_id = dbInsertId();
		$if_file_attached = False;
		//Handle file if exists
		if(isset($_FILES['file'])){
		if(is_uploaded_file($_FILES['file']['tmp_name'])){
			$file_id = uniqid().basename($_FILES["file"]["name"]);
			$target_file = $target_dir . $file_id;
			$file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
			$file_size = $_FILES["file"]["size"];
			$file_filename = basename($_FILES["file"]["name"]);
			$attachable_id = $inserted_id;
			$attachable_type = 'Comment';
			if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
				echo "The file ". htmlspecialchars(basename($_FILES["file"]["name"])). " has been uploaded.";
				$file_sql = "INSERT INTO attachments(file_id,file_filename,attachable_id,attachable_type) VALUES('$file_id','$file_filename','$attachable_id', '$attachable_type')";
				$file_query = dbQuery($file_sql);
				$file_inserted_id = dbInsertId();
				$sql_update = "UPDATE comment SET file_index=".$file_inserted_id." WHERE comment_id=".$inserted_id;
				$update_query = dbQuery($sql_update);
				$if_file_attached = True;
			} else {
				echo "Sorry, there was an error uploading your file.";
			}
		}
		else{
			echo "File ". $_FILES['file']['tmp_name'] .
			" not uploaded successfully.\n";
		}
	}
	else{
		// File is not selected.
	}
		$sql = "SELECT * FROM comment WHERE comment_id=" . $inserted_id;
		$results = dbQuery($sql);
		if ($results) {
			while ($row = dbFetchAssoc($results)) {
				if ($depth_level < 3) {
					$reply_link = "<a href=\"#\" class=\"reply_button\" id=\"{$row['comment_id']}\">reply</a><br/>";
				} else {
					$reply_link = '';
				}
				$depth = $depth_level + 1;
				echo "<li id=\"li_comment_{$row['comment_id']}\" data-depth-level=\"{$depth}\">" .
				"<span class=\"comment_date\">{$row['comment_date']}</span></div>" .
				"<div style=\"margin-top:4px;\">{$row['comment_text']}</div>" .
				$reply_link . "</li>";
			}
			if($if_file_attached){
			echo '<div class="success">Comment successfully posted with file attached.</div>';
			}
			else{
				echo '<div class="success">Comment successfully posted without a file attached.</div>';
			}
		} else {
			echo '<div class="error">Error in adding comment</div>';
		}
	} else {
		echo '<div class="error">Please enter required fields</div>';
	}
?>