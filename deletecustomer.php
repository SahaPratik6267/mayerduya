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
	
	$resultt = mysqli_query($mysqli, "SELECT * FROM clientinfo ");

	
	$client =1 ;
	error_reporting(0);
	
	


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title></title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <link rel="stylesheet" href="docsupport/style.css">
  <link rel="stylesheet" href="docsupport/prism.css">
  <link rel="stylesheet" href="chosen.css">

  
  
  
	  <style type="text/css">

			* {	margin: 0px; padding: 0px; }
			body
			{
				padding: 30px;
				font-family: Calibri, Verdana, "Sans Serif";
				font-size: 20px;
			}
			table
			{
				width: 1500px;
				margin: 0px auto;
			}

.data th{
				padding: 4px;
				 border: 1px solid #dddddd;
			}

			.data td{
				padding: 4px;
				 border: 1px solid #dddddd;
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
   <a href="createinvoice.php">Create Invoice</a>
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


		<h1>Customer Ledger</h1>
		
<a href="createinvoice.php">Add New Customer</a><br/><br/>

<form action="deletecustomer.php" method="post" name="form1">
		<table width="25%" border="0">
		
	
		
			
			
				<table class="data" width='100%' border=0>
				

		
		
		<?php if(isset($_POST['Submit'])) {	
		

		
	$client = mysqli_real_escape_string($mysqli, $_POST['client_id']);

   $deletecust= (mysqli_query($mysqli, "DELETE FROM `clientinfo` WHERE cid='$client'"));	
	
	$getinvoiceid=(mysqli_query($mysqli, "SELECT invoice_no FROM `ledger` WHERE client_id='$client'"));
	while($getinvoicebycid = mysqli_fetch_array($getinvoiceid)){
	$invoiceno=$getinvoicebycid['invoice_no'];
	$deleteitems=(mysqli_query($mysqli, "DELETE FROM `items` WHERE invoice_no='$invoiceno'"));
	$deleteledger==(mysqli_query($mysqli, "DELETE FROM `ledger` WHERE client_id='$client'"));
	$deletemanualp==(mysqli_query($mysqli, "DELETE FROM `manualp` WHERE client_id='$client'"));
	}
	
	
	} ?>
</table>


<br />

		<center>	<tr> 
				<td>Select Client : </td>
			
				<td><?php 
				
				echo "<select name='client_id' data-placeholder='Choose a Client...' class='chosen-select' tabindex='2'>";
				echo "<option value=''></option>";
while ($rest = mysqli_fetch_array($resultt)) {
	
	
	   if($rest['cid'] == $client){
          $isSelected = ' selected="selected"'; // if the option submited in form is as same as this row we add the selected tag
     } else {
          $isSelected = ''; // else we remove any tag
     }
	 
	
    echo "<option value='" . $rest['cid']."'".$isSelected.">" . $rest['name']."-".$rest['company']  ."</option>";
}

echo "</select>";
				
				
		
				
				
				?></td>
			</tr> 
			
		
			<tr> 
				<td></td>
				<td><input type="submit" name="Submit" value="Select"></td>
			</tr></center>
		</table>
		
		 <script src="docsupport/jquery-3.2.1.min.js" type="text/javascript"></script>
  <script src="chosen.jquery.js" type="text/javascript"></script>
  <script src="docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
  <script src="docsupport/init.js" type="text/javascript" charset="utf-8"></script>
	</form>
		
		

	</body>
</html>