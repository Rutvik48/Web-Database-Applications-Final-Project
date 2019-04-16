<?php
	$db_conn = new mysqli('localhost', 'rajan', '********', 'rajan_FinalProject');
	
	if (mysqli_connect_errno())
		exit("Connection to database failed:".mysqli_connect_error());
	
?>