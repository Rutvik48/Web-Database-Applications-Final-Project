<?php
session_start();

$current_user = $_SESSION['valid_user'];

echo "<h1>Add Comment</h1>";

if(!empty($current_user)){
	
	echo '<div style="text-align: right;"><a href="logout1.php">Log out</a></div>';
	
	echo '<a href="index.php">Click here </a>
			to go back to home Page.<br/><br/>';

	$post_id = $_GET['postID'];
	
	if(!isset($_POST['submit'])){
		showForm($post_id);
			
	} elseif(sanitizeBody($_POST['comment']))
		
		addComment($_POST['comment'], $post_id, $current_user);
	
} else
	echo "<a href='login1.php'>Log in here</a>";

function showForm($post_id){
	echo "<form action = 'addComment.php?postID=$post_id' method='post'> 
		
		<table> <tr>
			
		<td><textarea name = 'comment' rows = '5' cols = '20' placeholder = 'Write your comment here' required></textarea></td></tr><br/>
			
		<td colspan='2' align='center'>
		<input type='submit' name = 'submit' value='Submit'></td>
			
		</tr></table></form>";	
}

function addComment($comment_body,$post_id, $username){
	
	$count = getCount();
	
	include 'loginToDatabase.php';
	
	
	$query2 = "INSERT INTO Comment_Table(comment_id, username, comment_body, post_id, date_time) 
				VALUES ( ?, ?, ?, ?, CURDATE())";

	$stmt = $db_conn -> prepare($query2);
	$stmt->bind_param('ssss', $count, $username, $comment_body, $post_id);
	$stmt->execute();
	$stmt->store_result();
	
	$stmt->free_result();
 
	$db_conn->close();
	echo "$comment_body, $post_id, $username, $count";
	echo "Your comment has been added!";
}


function getCount(){
	
	include 'loginToDatabase.php';
	
	$query = 'SELECT count(*) FROM Comment_Table';
		
	$stmt = $db_conn -> prepare($query);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($count);
	$stmt -> fetch();
	$count = $count + 1;
	
	
	$stmt->free_result();
 
	return $count;
}

function sanitizeBody($text){
	
	if($text != ""){
		$text = filter_var($text, FILTER_SANITIZE_STRING);
		
		if($text == ""){
			echo "Wrong input!<br/>
				go back to home page and try again!";
			return false;
			
		}else
			return true;
	
	}else{echo "You can't post empty comment.<br/>
				go back to home page and try again!";
		return false;
	}
}
?>