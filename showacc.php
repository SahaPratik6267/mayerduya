<?php

	/**
	* Make sure you started your'e sessions!
	* You need to include su.inc.php to make SimpleUsers Work
	* After that, create an instance of SimpleUsers and your'e all set!
	*/

	session_start();
	require_once(dirname(__FILE__)."/simpleusers/su.inc.php");

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

		

.dropbtn {
    background-color: #4CAF50;
    color: white;
    padding: 16px;
    font-size: 16px;
    border: none;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f1f1f1;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

.dropdown-content a {
    color: black;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
}

.dropdown-content a:hover {background-color: #ddd}

.dropdown:hover .dropdown-content {
    display: block;
}

.dropdown:hover .dropbtn {
    background-color: #3e8e41;
}




	  </style>

	</head>
	<body>
	
<div class="dropdown">
  <button class="dropbtn">Users</button>
  <div class="dropdown-content">
    <a href="users.php">Uses List</a>
   <a href="newuser.php">Add New</a>
  </div>
</div>

<div class="dropdown">
  <button class="dropbtn">Customer</button>
  <div class="dropdown-content">
    <a href="customer.php">Customer List</a>
    <a href="addcus.php">Add Customer</a>
	
    
  </div>
</div>
<div class="dropdown">
  <button class="dropbtn">Ledger</button>
  <div class="dropdown-content">
    <a href="customerledger.php">Customer Ledger</a>
	<a href="createinvoice.php">Create Invoice</a>
    <a href="manual.php">Add Manual Payment</a>
    <a href="manualhis.php">Manual Payment History</a>
  </div>
</div>
<div class="dropdown">
  <button class="dropbtn">Back Account</button>
  <div class="dropdown-content">
    <a href="listacc.php">Account List</a>
    <a href="showacc.php">Transactions</a>
    <a href="addtrans.php">Add Transactions</a>
	<a href="addacc.php">Add Account</a>
  </div>
</div>




		<h1>User administration</h1>
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
					<td colspan="4" class="right">
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