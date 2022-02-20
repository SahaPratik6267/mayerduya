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
    width: 200%;
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
	$bid = mysqli_real_escape_string($mysqli, $_POST['bank_id']);
	
	
	$name = mysqli_real_escape_string($mysqli, $_POST['date']);
	$company = mysqli_real_escape_string($mysqli, $_POST['details']);
	$address = mysqli_real_escape_string($mysqli, $_POST['with']);
	$mobile = mysqli_real_escape_string($mysqli, $_POST['depo']);
	$email = mysqli_real_escape_string($mysqli, $_POST['tnote']);
	
	$bal = $cbalance-$address+$mobile; 

	
	
		
	$result = mysqli_query($mysqli, "UPDATE trans SET date='$name',bid='$bid',details='$company',withdraw='$address',deposit='$mobile',cbalance='$bal',tnote='$email' WHERE tid=$id");
		
		//redirectig to the display page. In our case, it is index.php
		header("Location: showtrans.php");
	
}
?>
<?php
//getting id from url
$id = $_GET['id'];

//selecting data associated with this particular id
$result = mysqli_query($mysqli, "SELECT * FROM trans WHERE tid=$id");

while($res = mysqli_fetch_array($result))
{
	$date = $res['date'];
	$bid = $res['bid'];
	$details = $res['details'];
	$with = $res['withdraw'];
	$depo = $res['deposit'];
	$cbalance = $res['cbalance'];
	$tnote = $res['tnote'];

}



include 'header.php';
?>


</head>

<body>


	<br/><br/>
	<div class="center">
	<form name="form1" method="post" action="edittrans.php">
	<table width="25%" border="0">
			<tr> 
				<td>Account</td>
				<td>
<?php 
$getaname = mysqli_query($mysqli, "SELECT * FROM bankacc");
echo "<select name='bank_id' data-placeholder='Choose a Account...' class='chosen-select' tabindex='2'>";
echo "<option value=''></option>";
while ($rest = mysqli_fetch_array($getaname)) {
 if($rest['bid'] == $bid){
          $isSelected = ' selected="selected"'; // if the option submited in form is as same as this row we add the selected tag
     } else {
          $isSelected = ''; // else we remove any tag
     }
	 
	

	    echo "<option value='" . $rest['bid']."'".$isSelected.">" . $rest['accname']."-".$rest['accno']  ."</option>";
}

echo "</select>";
				

				?>
				
				</td>
			</tr>
			<tr> 
				<td>Date</td>
				<td><input type="date" name="date" value="<?php echo $date;?>"></td>
			</tr>
			<tr> 
				<td>Details</td>
				<td><input type="text" name="details" value="<?php echo $details;?>"></td>
			</tr>
			<tr> 
				<td>Withdraw</td>
				<td><input type="text" name="with" value="<?php echo $with;?>"></td>
			</tr>
			<tr> 
				<td>Deposit</td>
				<td><input type="text" name="depo" value="<?php echo $depo;?>"></td>
			</tr>
			
			<tr> 
				<td>Note</td>
				<td><input type="text" name="tnote" value="<?php echo $tnote;?>"></td>
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