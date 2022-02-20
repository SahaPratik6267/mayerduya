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
	
	$result = mysqli_query($mysqli, "SELECT * FROM ledger ");


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

		
		
		
		ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #333;
}

li {
    float: left;
}

li a {
    display: inline-block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
}

li a:hover {
    background-color: #111;
}

.active {
    background-color: red;
}



	  </style>

	</head>
	<body>

		<ul>
  <li><a href="customer.php" >Customer List</a></li>
  <li><a href="addcus.php">Add Customer</a></li>
  <li><a href="ledger.php">Ledger List</a></li>
  <li><a href="createinvoice.php">Create Invoice</a></li>
</ul>

		<h1>Ledger</h1>
		
<a href="createinvoice.php">Add New Customer</a><br/><br/>

	<table width='80%' border=0>

	<tr bgcolor='#CCCCCC'>
	    <td>order id</td>
		<td>date</td>
		<td>clientid</td>
		<td>invoice no</td>
		<td>item</td>
		<td>carton no</td>
		<td>weight</td>
		<td>rate</td>
		<td>bill</td>
		<td>paid</td>
		<td>due</td>
		<td>shipment</td>
		<td>lot</td>
		
	</tr>
	<?php 
	//while($res = mysql_fetch_array($result)) { // mysql_fetch_array is deprecated, we need to use mysqli_fetch_array 
	while($res = mysqli_fetch_array($result)) { 		
		echo "<tr>";
		echo "<td>".$res['order_id']."</td>";
		echo "<td>".$res['date']."</td>";
		echo "<td>".$res['client_id']."</td>";	
		echo "<td>".$res['invoice_no']."</td>";
		echo "<td>".$res['item']."</td>";
		echo "<td>".$res['carton_no']."</td>";	
		echo "<td>".$res['weight_pcs']."</td>";	
		echo "<td>".$res['rate']."</td>";	
		echo "<td>".$res['bill']."</td>";	
		echo "<td>".$res['paid']."</td>";	
		echo "<td>".$res['due']."</td>";	
		echo "<td>".$res['shipment']."</td>";
		echo "<td>".$res['lot_no']."</td>";	
		echo "<td><a href=\"editinvoice.php?id=$res[order_id]\">Edit</a> | <a href=\"deleteinvoice.php?id=$res[order_id]\" onClick=\"return confirm('Are you sure you want to delete?')\">Delete</a></td>";		
	}
	?>
	</table>
		

	</body>
</html>