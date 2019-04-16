<?php
session_start();

if (isset($_POST['username'])) {

	$errors = "";

	if (($_POST['username'] != "")) {

		$_POST['username'] = filter_var($_POST['username'], FILTER_SANITIZE_STRING);


		if ($_POST['username'] == "") {
			$errors .= 'Please enter a valid Username and Password.<br/><br/>';
		}

    } else {
         $errors .= 'Please enter your Username and Password.<br/>';
    }

	if (!$errors) {

	// if the user has just tried to log in
	$username = $_POST['username'];
	$password = $_POST['password'];

	include 'loginToDatabase.php';

	$query = "select username from User_Table where username = ? and password = sha1(?)";

	$stmt = $db_conn -> prepare($query);
	$stmt->bind_param('ss',$username,$password);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($username);

	if ($stmt->num_rows >0 ){

		// if they are in the database register the user id
		$_SESSION['valid_user'] = $username;
	}

	$stmt->free_result();

	$db_conn->close();

	} else {

		echo '<div style="color: red">' . $errors . '<br/></div>';
		unset($_POST['Submit']);
	}
}


  if (isset($_SESSION['valid_user'])){

	   echo '<br/><div style="text-align: right;">
				If you are not '. $_SESSION["valid_user"].
					'<a href="logout1.php"> Log out here.</a><br/></div><br/>';

	  echo '<br/><br/><h1>Hello '.$_SESSION['valid_user'].'.</h1><br />';

	  echo '<a href="index.php"><h1>Click here to view recent posts.</h1></a><br/><br/>';

	} else {

    if (isset($username)){

      // if they've tried and failed to log in
      echo 'Wrong Username or Password.<br />';

	}else {

      // they have not tried to log in yet or have logged out
      echo 'You are not logged in.<br />';
    }

	 echo "<h1>Login Page. <h1/>";
?>
<!-- provide form to log in -->

<form method="post" action="login1.php">

<table><tr>

<td>Username:</td>
<td><input type="text" name="username" required/></td></tr>

<tr><td>Password:</td>
<td><input type="password" name="password" required/></td></tr>

<tr><td colspan="2" align="center">

<input type="submit" value="Log in"></td>

</tr></table>
</form>
</body>
</html>
<?php
  }
?>