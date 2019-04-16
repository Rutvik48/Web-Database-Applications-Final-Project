<?php
session_start();

$current_user = $_SESSION['valid_user'];

echo "<h1>Home Page</h1>";

if(!empty($current_user)){
	
	echo '<div style="text-align: right;"><a href="logout1.php">Log out</a></div>';
	  
	if(isset($_GET['username'])){
		
		showPostBody($_GET['username'],$_GET['title'], $_GET['postBody'], $_GET['date']);
		
	}elseif ($_GET['next'] === 'true'){
		
		createQuery($_GET['page'] + 5); 
	
	}elseif($_GET['previous'] === 'true'){
		
		createQuery($_GET['page'] - 5);
		
	}else{
		$pageNum=0;
		createQuery($pageNum);
	}
	
} else
	echo "<a href='login1.php'>Log in here</a>";


function createQuery($pageNum){
	
	$pageLimit = 5;
	
	$query = "SELECT username, date_time, id, title, post_body FROM Post_Table 
									ORDER BY date_time DESC LIMIT $pageNum, $pageLimit";	
		
	runQuery($query, $pageNum);
	
}


function runQuery($query, $pageNum){

	$count = getCount();
	
	include 'loginToDatabase.php';
	
	$stmt = $db_conn -> prepare($query);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($username,$date, $postID, $title, $postBody);
	
	if($pageNum>1)
		echo "<a href='index.php?page=$pageNum&next=false&previous=true'>Previous |</a></div>";
	
	
	echo "<style> table, th, td {
				border: 1px solid red;
				padding: 10px;} </style>
				
	<table style = 'width: 100%; text-align: center; ' > 
			<tr> <th> Author </th> <th> Date </th> <th> Add Comment </td>";
	
	while($stmt -> fetch()){
		
		echo "<tr> 
		<td> 
		<a href='index.php?username=$username&title=$title&postBody=$postBody&date=$date'>$username</a>
		</td> 
		
		<td> $date </td> 
		
		<td>
		<a href='addComment.php?postID=$postID'>Comment on this post </a>
		</td>

		<td><a href='viewComment.php?postID=$postID'>View comments</a></td>
		</tr>
		";
	}
	echo "</table>";

	if($pageNum + 5 < $count)
		echo "<div style='text-align: right;'> <a href='index.php?page=$pageNum&next=true&previous=false'>| Next</a></div>";
			
	echo '<a href="addBlogPosting.php"><h3>Add New Blog Post.</h3></a><br/><br/>';
	
	$stmt->free_result();
 
	$db_conn->close();
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
 
	return $count;
}

function showPostBody($author, $title, $body, $date){
	
	echo '<div style="text-align: right;"><a href="index.php">Click here </a>
			to go back to home Page.<br/><br/></div>';
			
	echo "<h3>Title: $title</h3><br/>";
	echo "<h4>Author: $author <br/>";
	echo "Creation date: $date </h4><br/>";
	echo "<br/> $body";
	
	unset($_GET['username']);
	
}
?>