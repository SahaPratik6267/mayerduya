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
	  </style>

	</head>
	<body>



		<h1>ADD Customer</h1>

		
		<body>
	<a href="index.php">Home</a>
	<br/><br/>
<div class="center">
	<form action="addcus.php" method="post" name="form1">
		<table width="25%" border="0">
			<tr> 
				<td>Name</td>
				<td><input type="text" name="name"></td>
			</tr>
			<tr> 
				<td>Company</td>
				<td><input type="text" name="company"></td>
			</tr>
			<tr> 
				<td>Address</td>
				<td><input type="text" name="address"></td>
			</tr>
			<tr> 
				<td>Mobile No</td>
				<td><input type="text" name="mobile"></td>
			</tr>
			<tr> 
				<td>Email</td>
				<td><input type="text" name="email"></td>
			</tr>
			<tr> 
				<td>Area</td>
				<td><input type="text" name="area"></td>
			</tr>
			<tr> 
				<td>Note</td>
				<td><input type="text" name="cnote"></td>
			</tr>
			<tr> 
				<td></td>
				<td><input type="submit" name="Submit" value="Add"></td>
			</tr>
		</table>
	</form></div>
	
	<br /><br />
	
	<?php
	
	if(isset($_POST['Submit'])) {	
	$name = mysqli_real_escape_string($mysqli, $_POST['name']);
	$company = mysqli_real_escape_string($mysqli, $_POST['company']);
	$address = mysqli_real_escape_string($mysqli, $_POST['address']);
	$mobile = mysqli_real_escape_string($mysqli, $_POST['mobile']);
	$email = mysqli_real_escape_string($mysqli, $_POST['email']);
	$area = mysqli_real_escape_string($mysqli, $_POST['area']);
	$cnote = mysqli_real_escape_string($mysqli, $_POST['cnote']);

		if(empty($name) || empty($company) || empty($address) || empty($mobile) || empty($email) || empty($area) || empty($cnote)) {
				
		if(empty($name)) {
			echo "<font color='red'>Name field is empty.</font><br/>";
		}
		
		if(empty($company)) {
			echo "<font color='red'>Company field is empty.</font><br/>";
		}
		
		if(empty($address)) {
			echo "<font color='red'>Address field is empty.</font><br/>";
		}
		
			if(empty($mobile)) {
			echo "<font color='red'>Mobile field is empty.</font><br/>";
		}
		
			if(empty($email)) {
			echo "<font color='red'>Email field is empty.</font><br/>";
		}
		
			if(empty($area)) {
			echo "<font color='red'>Area field is empty.</font><br/>";
		}
		
			if(empty($cnote)) {
			echo "<font color='red'>Note field is empty.</font><br/>";
		}
		
	
		
		//link to the previous page
		echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";

			} else { 
			
		//insert data to database	
		$result = mysqli_query($mysqli, "INSERT INTO clientinfo(name,company,address,mobile,email,area,cnote) VALUES('$name','$company','$address','$mobile','$email','$area','$cnote')");
		
		//display success message
		echo "<font color='green'>Customer added successfully.";
		echo "<br/><a href='customer.php'>View Result</a>";
			}
}

	?>
</body>



	
</html>
<?php include 'footer.php'; ?>