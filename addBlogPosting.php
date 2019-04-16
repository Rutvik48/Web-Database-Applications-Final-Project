<?php
session_start();

$current_user = $_SESSION['valid_user'];


if(!empty($current_user)){
	
echo '<div style="text-align: right;"><a href="logout1.php">Log out</a></div>';

echo '<a href="index.php">Front Page.</a><br/><br/>';

echo "<h1>New Blog Post</h1>";

	if(!isset($_POST['submit'])){

		showForm();

	}else{
		$title =  sanitizeText($_POST['title']);
		
		$post = sanitizeText($_POST['post']);
		
		if($title){
			if($post){
				addBlog($_POST['title'],$_POST['post'], $current_user);
			}else
				echo "Wrong input inside post body field.";
		}else
			echo "Wrong input inside title field.";
		
	}
	
} else
	echo "<a href='login1.php'>Log in here</a>";


function showForm(){
	
	echo "<form action='addBlogPosting.php' method='post'> 
			<table>
			
			<tr><td>Post Title: </td> 
			<td><input type = 'text' name = 'title' placeholder = 'enter post title here' required></td></tr><br/>
			
			<tr><td>Post Body: </td>
			<td><textarea rows = '10' cols = '50' name = 'post' placeholder = 'write your post here'  required></textarea></td></tr><br/>
			
			<td colspan='2' align='center'>
			<input type='submit' name='submit' value='submit'></td>
			
			</tr></table></form>";
}

function addBlog($title, $post_body, $username){
	
	$post_id = getCount();
	
	include 'loginToDatabase.php';
	
	$query = "INSERT INTO Post_Table(id, title, post_body, username, date_time)
				VALUES ( ?, ?, ?, ?, CURDATE())";
				
	$stmt = $db_conn -> prepare($query);
	$stmt->bind_param('ssss', $post_id, $title, $post_body, $username);
	$stmt->execute();
	$stmt->store_result();
	$stmt->free_result();
	
	$db_conn->close();
	
	echo "Your post has been added!";
	
	echo '<a href="addBlogPosting.php">Add New Blog Post.</a><br/><br/>';
}

function getCount(){
	
	include 'loginToDatabase.php';
	
	$query = 'SELECT count(*) FROM Post_Table';
		
	$stmt = $db_conn -> prepare($query);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($count);
	$stmt -> fetch();
	$stmt->free_result();
 
	$db_conn->close();
	
	return $count+1;
}

function sanitizeText($text){
	
	if($text != ""){
		$text = filter_var($text, FILTER_SANITIZE_STRING);
		
		if($text == ""){
			return false;
			
		}else
			return true;
	
	}else
		return false;
	
}

?>