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

	
	
		
	$result = mysqli_query($mysqli, "UPDATE bankacc SET accname='$name',accno='$company',balance='$address',branch='$mobile',bnote='$email' WHERE bid=$id");
		
		//redirectig to the display page. In our case, it is index.php
		header("Location: listacc.php");
	
}
?>
<?php
//getting id from url
$id = $_GET['id'];

//selecting data associated with this particular id
$result = mysqli_query($mysqli, "SELECT * FROM bankacc WHERE bid=$id");

while($res = mysqli_fetch_array($result))
{
	$name = $res['accname'];
	$company = $res['accno'];
	$address = $res['balance'];
	$mobile = $res['branch'];
	$email = $res['bnote'];

}

include 'header.php';
?>
<html>
<head>	
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
	<form name="form1" method="post" action="editacc.php">
		<table border="0">
			<tr> 
				<td>Account Name</td>
				<td><input type="text" name="name" value="<?php echo $name;?>"></td>
			</tr>
			<tr> 
				<td>Account No</td>
				<td><input type="text" name="company" value="<?php echo $company;?>"></td>
			</tr>
			<tr> 
				<td>Balance</td>
				<td><input type="text" name="address" value="<?php echo $address;?>"></td>
			</tr>
				<tr> 
				<td>Branch</td>
				<td><input type="text" name="mobile" value="<?php echo $mobile;?>"></td>
			</tr>
			<tr> 
				<td>Note</td>
				<td><input type="text" name="email" value="<?php echo $email;?>"></td>
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