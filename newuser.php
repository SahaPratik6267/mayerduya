<?php

	/**
	* Make sure you started your'e sessions!
	* You need to include su.inc.php to make SimpleUsers Work
	* After that, create an instance of SimpleUsers and your'e all set!
	*/

	session_start();
	require_once(dirname(__FILE__)."/simpleusers/su.inc.php");

	$SimpleUsers = new SimpleUsers();

	// Validation of input
	if( isset($_POST["username"]) )
	{
		if( empty($_POST["username"]) || empty($_POST["password"]) )
			$error = "You have to choose a username and a password";
    else
    {
    	// Both fields have input - now try to create the user.
    	// If $res is (bool)false, the username is already taken.
    	// Otherwise, the user has been added, and we can redirect to some other page.
			$res = $SimpleUsers->createUser($_POST["username"], $_POST["password"]);

			if(!$res)
				$error = "Username already taken.";
			else
			{
					header("Location: users.php");
					exit;
			}
		}

	} // Validation end
include 'header.php'; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title></title>
	  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	  <style type="text/css">

			* {	margin: 0px; padding: 0px; }
			body
			{
				padding: 30px;
				font-family: Calibri, Verdana, "Sans Serif";
				font-size: 12px;
			}
			table
			{
				width: 800px;
				margin: 0px auto;
			}

			th, td
			{
				padding: 3px;
			}

			.right
			{
				text-align: right;
			}

	  	h1
	  	{
	  		color: #FF0000;
	  		border-bottom: 2px solid #000000;
	  		margin-bottom: 15px;
	  	}

	  	p { margin: 10px 0px; }
	  	p.faded { color: #A0A0A0; }
.center {
    margin: auto;
    width: 60%;
    border: 3px solid #73AD21;
    padding: 10px;
	text-align: center;
	font-size: 30px;
}
input[type=text], select {
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

input[type=submit] {
    width: 100%;
    background-color: #4CAF50;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type=submit]:hover {
    background-color: #45a049;
}
h2 { color: #111; font-family: 'Helvetica Neue', sans-serif; font-size: 50px; font-weight: bold; letter-spacing: -1px; line-height: 1; text-align: center; }
	  </style>
	</head>
	<body>

		<h2>Register new user</h2>
		<br />

		<?php if( isset($error) ): ?>
		<p>
			<?php echo $error; ?>
		</p>
		<?php endif; ?>
<div class="center">
		<form method="post" action="">
			<p>
				<label for="username">Username:</label><br />
				<input type="text" name="username" id="username" />
			</p>

			<p>
				<label for="password">Password:</label><br />
				<input type="text" name="password" id="password" />
			</p>

			<p>
				<input type="submit" name="submit" value="Register" />
			</p>

		</form> </div>

	</body>
</html>
<br /><br />
<?php include 'footer.php'; ?>