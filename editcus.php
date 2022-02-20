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
	
	


if(isset($_POST['update']))
{	

	$id = mysqli_real_escape_string($mysqli, $_POST['id']);
	
	$name = mysqli_real_escape_string($mysqli, $_POST['name']);
	$company = mysqli_real_escape_string($mysqli, $_POST['company']);
	$address = mysqli_real_escape_string($mysqli, $_POST['address']);
	$mobile = mysqli_real_escape_string($mysqli, $_POST['mobile']);
	$email = mysqli_real_escape_string($mysqli, $_POST['email']);
	$area = mysqli_real_escape_string($mysqli, $_POST['area']);
	$assigned_branch= mysqli_real_escape_string($mysqli, $_POST['assigned_branch']);
	
	
		
	$result = mysqli_query($mysqli, "UPDATE clientinfo SET name='$name',company='$company',address='$address',mobile='$mobile',email='$email',area='$area',branch_assigned='$assigned_branch' WHERE cid=$id");
		
		//redirectig to the display page. In our case, it is index.php
		header("Location: customer.php");
	
}
?>
<?php
//getting id from url
$id = $_GET['id'];

//selecting data associated with this particular id
$result = mysqli_query($mysqli, "SELECT * FROM clientinfo WHERE cid=$id");

while($res = mysqli_fetch_array($result))
{
	$name = $res['name'];
	$company = $res['company'];
	$address = $res['address'];
	$mobile = $res['mobile'];
	$email = $res['email'];
	$area = $res['area'];
}

include 'header.php';
?>
<html>
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">	
<style>


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
h2 { color: #111; font-family: 'Helvetica Neue', sans-serif; font-size: 75px; font-weight: bold; letter-spacing: -1px; line-height: 1; text-align: center; }
</style>

</head>

<body>


	<br/><br/>
	<div class="center">
	<form name="form1" method="post" action="editcus.php">
		<table border="0">
			<tr> 
				<td>Name</td>
				<td><input type="text" name="name" value="<?php echo $name;?>"></td>
			</tr>
			<tr> 
				<td>Company</td>
				<td><input type="text" name="company" value="<?php echo $company;?>"></td>
			</tr>
			<tr> 
				<td>Address</td>
				<td><input type="text" name="address" value="<?php echo $address;?>"></td>
			</tr>
				<tr> 
				<td>Mobile</td>
				<td><input type="text" name="mobile" value="<?php echo $mobile;?>"></td>
			</tr>
			<tr> 
				<td>Email</td>
				<td><input type="text" name="email" value="<?php echo $email;?>"></td>
			</tr>
			<tr> 
				<td>Area</td>
				<td><select name="assigned_branch" value="<?php echo $assign_branched; ?>">
                     <option value="1">Gulistan</option>
                     <option value="2">Motalib</option>
                    </select>
				</td>
			</tr>
			<tr>
				<td><input type="hidden" name="id" value=<?php echo $_GET['id'];?>></td>
				<td><input type="submit" name="update" value="Update"></td>
			</tr>
		</table>
	</form></div>
</body>
</html>
<?php include 'footer.php'; ?>