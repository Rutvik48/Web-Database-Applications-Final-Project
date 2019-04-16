<?php
session_start();

$current_user = $_SESSION['valid_user'];

echo "<h1>Comments on Post: $_GET[postID]</h1>";

if(!empty($current_user)){
	
	echo '<div style="text-align: right;"><a href="logout1.php">Log out</a></div>';
	
	echo '<a href="index.php">HomePage.</a><br/><br/>';
	
	viewComment($_GET['postID']);
	
} else
	echo "<a href='login1.php'>Log in here</a>";


function viewComment($postID){
	
	include 'loginToDatabase.php';
	
	
	$query = "SELECT * FROM Comment_Table
				WHERE post_id = ?";
				
				
	$stmt = $db_conn -> prepare($query);
	$stmt->bind_param('s', $postID);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($comment_id, $username, $comment_body, $post_id, $date);
	
	$count = 0;
	while($stmt -> fetch()){
		
		displayDetails($comment_id, $username, $date, $comment_body);
		$count++;
		
	}
	if($count == 0){
		echo"<h3>There is no comment to show.</h3>";
	}else
		echo"<br/><br/><div style='color: red'><h3>Comment Total: $count</h3></div>";
	
	$stmt->free_result();
 
	$db_conn->close();
}


function displayDetails($comment_id, $username, $date, $comment_body){
	
	echo "<strong><br/>Comment id:</strong> $comment_id<br/>";
	
	echo "<strong>Comment by</strong> $username<br/>";
	
	echo "<strong>Comment Date:</strong> $date  <br/><br/><br/>";
	
	echo "$comment_body<br/>____________________________________________________________________";
}

?>