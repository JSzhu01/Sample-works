<?php

	function my_each(&$arr) {
		$key = key($arr);
		$result = ($key === null) ? false : [$key, current($arr), 'key' => $key, 'value' => current($arr)];
		next($arr);
		return $result;
	}

	function format_comments($comments,$is_admin) {
		$html = array();
		$root_id = 0;
		foreach ($comments as $comment)
			$children[$comment['parent_id']][] = $comment;

		// loop will be false if the root has no children (i.e., an empty comment!)
		$loop = !empty($children[$root_id]);

		// initializing $parent as the root
		$parent = $root_id;
		$parent_stack = array();

		// HTML wrapper for the menu (open)
		$html[] = '<ul class="comment">';

		//while ($loop && ( ( $option = each($children[$parent]) ) || ( $parent > $root_id ) )) { //below PHP 7.2
		while ($loop && ( ( $option = my_each($children[$parent]) ) || ( $parent > $root_id ) )) { //PHP 7.2+
			if ($option === false) {
				$parent = array_pop($parent_stack);

				// HTML for comment item containing childrens (close)
				$html[] = str_repeat("\t", ( count($parent_stack) + 1 ) * 2) . '</ul>';
				$html[] = str_repeat("\t", ( count($parent_stack) + 1 ) * 2 - 1) . '</li>';
			} elseif (!empty($children[$option['value']['comment_id']])) {
				$tab = str_repeat("\t", ( count($parent_stack) + 1 ) * 2 - 1);
				$keep_track_depth = count($parent_stack);
				if ($keep_track_depth <= 3) {
					$reply_link = '%1$s%1$s<a href="#" class="reply_button" id="%2$s">reply</a><br/>%1$s';
				} else {
					$reply_link = '';
				}

				if($option['value']['file_index'] > 0){
					if($is_admin){
					$file_link = '%1$s%1$s<div><span class="comment_file" file_index = '.$option['value']['file_index'].' file_id = "'.$option['value']['file_id'].'"> File:'.$option['value']['file_filename'].'<a href="../download.php?file_id='.$option['value']['file_id'].'" class="downloadbutton" >Download</a><a href="#" class = "delete downloadbutton" >Delete</a></span></div><br></br>';
					}
					else{
					$file_link = '%1$s%1$s<div><span class="comment_file" file_index = '.$option['value']['file_index'].' file_id = "'.$option['value']['file_id'].'"> File:'.$option['value']['file_filename'].'<a href="../download.php?file_id='.$option['value']['file_id'].'" class="downloadbutton" >Download</a></span></div><br></br>';
					}
				}
				else{
					$file_link ='';
				}
				
				//$reply_link = '%1$s%1$s<a href="#" class="reply_button" id="%2$s">reply</a><br/>';
				// HTML for comment item containing childrens (open)
				$html[] = sprintf(
						'%1$s<li id="li_comment_%2$s" comment_id=%2$s data-depth-level="' . $keep_track_depth . '">' .
						'%1$s%1$s<div><span class="author">Author:<b>%5$s</b></span></div>' .
						'%1$s%1$s<div><span class="comment_date"> %4$s</span></div>' .
						'%1$s%1$s<div style="margin-top:4px;">%3$s</div><br></br>' .
						$file_link.
						$reply_link . '</li>', $tab, // %1$s = tabulation
						$option['value']['comment_id'], //%2$s id
						$option['value']['comment_text'], // %3$s = comment
						$option['value']['comment_date'], // %4$s = comment created_date
						$option['value']['name'] //%5$s author
				);
				//$check_status = "";
				$html[] = $tab . "\t" . '<ul class="comment">';

				array_push($parent_stack, $option['value']['parent_id']);
				$parent = $option['value']['comment_id'];
			} else {
				$keep_track_depth = count($parent_stack);
				if ($keep_track_depth <= 3) {
					$reply_link = '%1$s%1$s<a href="#" class="reply_button" id="%2$s">reply</a><br/>%1$s';
					
				} else {
					$reply_link = '';
				}
				if($option['value']['file_index'] > 0){
					if($is_admin){
					$file_link = '%1$s%1$s<div><span class="comment_file" file_index = '.$option['value']['file_index'].' file_id = "'.$option['value']['file_id'].'"> File:'.$option['value']['file_filename'].'<a href="../download.php?file_id='.$option['value']['file_id'].'" class="downloadbutton" >Download</a><a href="#" class = "delete downloadbutton">Delete</a></span></div><br></br>';
					}
					else{
					$file_link = '%1$s%1$s<div><span class="comment_file" file_index = '.$option['value']['file_index'].' file_id = "'.$option['value']['file_id'].'"> File:'.$option['value']['file_filename'].'<a href="../download.php?file_id='.$option['value']['file_id'].'" class="downloadbutton" >Download</a></span></div><br></br>';
					}
				}
				else{
					$file_link ='';
				}
				//$reply_link = '%1$s%1$s<a href="#" class="reply_button" id="%2$s">reply</a><br/>%1$s</li>';
				// HTML for comment item with no children (aka "leaf")
				$html[] = sprintf(
						'%1$s<li id="li_comment_%2$s" comment_id=%2$s data-depth-level="' . $keep_track_depth . '">' .
						'%1$s%1$s<div><span class="author">Author:<b>%5$s</b></span></div>' .
						'%1$s%1$s<div><span class="comment_date">%4$s</span></div>' .
						'%1$s%1$s<div style="margin-top:4px;">%3$s</div><br></br>' .
						$file_link.
						$reply_link . '</li>', str_repeat("\t", ( count($parent_stack) + 1 ) * 2 - 1), // %1$s = tabulation
						$option['value']['comment_id'], //%2$s id
						$option['value']['comment_text'], // %3$s = comment
						$option['value']['comment_date'], // %4$s = comment created_date
						$option['value']['name'] //%5$s author
				);	
			}
		}

		// HTML wrapper for the comment (close)
		$html[] = '</ul>';
		return implode("\r\n", $html);
	}
?>