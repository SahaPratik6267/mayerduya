<?php

	/**
	* Make sure you started your'e sessions!
	* You need to include su.inc.php to make SimpleUsers Work
	* After that, create an instance of SimpleUsers and your'e all set!
	*/

	session_start();
	require_once(dirname(__FILE__)."/simpleusers/su.inc.php");
include 'header.php';
	$SimpleUsers = new SimpleUsers();

	// This is a simple way of validating if a user is logged in or not.
	// If the user is logged in, the value is (bool)true - otherwise (bool)false.
	if( !$SimpleUsers->logged_in )
	{
		header("Location: login.php");
		exit;
	}

	// If the user is logged in, we can safely proceed.
	$users = $SimpleUsers->getUsers();
	
	
	$userId=0;
	$user = $SimpleUsers->getSingleUser($userId);
	//echo $user["uUsername"];
;


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
			
				
				font-family: 'Lato', sans-serif; font-size: 20px; font-weight: 300;
			}
			table
			{
				width: 1000px;
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
	  	{font-size: 22px;
	  		color: #FF0000;
	  		border-bottom: 2px solid #000000;
	  		margin-bottom: 15px;
	  	}
	  	p { margin: 10px 0px; }
	  	p.faded { color: #A0A0A0; }

	  </style>

	</head>
	<body>
<br /><br />
			<h1>User Administration</h1>	
		<table cellpadding="0" cellspacing="0" border="1">
			<thead>
				<tr>
					<th>Username</th>
					<th>Last activity</th>
					<th>Created</th>
					<th></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="8" class="right">
						<a href="newuser.php">Create new user</a> | <a href="logout.php">Logout</a>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php foreach( $users as $user ): ?>
				<tr>
					<td><?php echo $user["uUsername"]; ?></td>
					<td class="right"><?php echo $user["uActivity"]; ?></td>
					<td class="right"><?php echo $user["uCreated"]; ?></td>
					<td class="right"><a href="deleteuser.php?userId=<?php echo $user["userId"]; ?>">Delete</a> | <a href="userinfo.php?userId=<?php echo $user["userId"]; ?>">User info</a> | <a href="changepassword.php?userId=<?php echo $user["userId"]; ?>">Change password</a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

	</body>
</html>
<?php include 'footer.php'; ?>