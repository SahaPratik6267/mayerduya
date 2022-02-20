<?php

	/**
	* Make sure you started your'e sessions!
	* You need to include su.inc.php to make SimpleUsers Work
	* After that, create an instance of SimpleUsers and your'e all set!
	*/

	session_start();
	require_once(dirname(__FILE__)."/simpleusers/su.inc.php");
error_reporting(0);
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
	
	$resultt = mysqli_query($mysqli, "SELECT * FROM clientinfo order by cid ");
	$userId=0;
	$user = $SimpleUsers->getSingleUser($userId);

	
	
	$client =1 ;
	$sql="SELECT DISTINCT date,lot_no from ledger";

if(isset($_POST['chooselot'])){ 

$totalbill=0;

$lotno = mysqli_real_escape_string($mysqli, $_POST['lotno']);
$date = mysqli_real_escape_string($mysqli, $_POST['date']); 

if(empty($lotno) || empty($date)){

if(empty($lotno)){echo "";}
if(empty($date)){ echo" date field is empty";}
}
else{
$sql=$sql." where date='$date' AND lot_no='$lotno'";

}
}
include 'header.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		
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


	  </style>

	</head>
	<body>

		<h1>Manual Payment</h1>
		
		<p>Select Client</p>
				<form action="lotbill.php" method="post" name="form1">
		<table width="25%" border="0">
				<tr> 
				<td>LOT NUMBER : </td>
				<td>
                 <input type="text" name="lotno" value="<?php echo $lotno; ?>">
                </td>
				<td>Date : </td>
				<td>
                 <input type="date" name="date" value="<?php echo $date; ?>">
                </td>
			<td><input type="submit" name="chooselot" value="Select lot"></td>
			</tr>
			
			

			</table>
			
			<table class="data" width='100%' border=0>
			<?PHP
			
			echo "<tr bgcolor='#CCCCCC'>";
	    echo "<td>Date</td>";
		echo "<td>LOT NUMBER</td>";
		echo "<td>BILL</td>";
			
$getalllot = mysqli_query($mysqli, $sql);
while ($getlot = mysqli_fetch_array($getalllot)) {
$lotno=$getlot['lot_no'];
$date=$getlot['date'];
$totalbill=0;			
$query =  mysqli_query($mysqli, "SELECT invoice_no FROM ledger WHERE lot_no='$lotno' AND date='$date'");
while ($res = mysqli_fetch_array($query)) {
$invoice =$res['invoice_no'];
$getitem = mysqli_query($mysqli, "SELECT * FROM items WHERE invoice_no='$invoice'");
	while($r = mysqli_fetch_array($getitem)) {
	    
	 	$totalbill=$totalbill+($r['weight_pcs']*$r['rate']);


}	
}			
			
			
			
	echo "<tr>";
	    echo "<td>$date</td>";
		echo "<td>$lotno</td>";
		echo "<td>$totalbill</td>";		
			
			
			
			
			
}			
			
			
			
			
			
			?>
			
			</table>
					  <script src="docsupport/jquery-3.2.1.min.js" type="text/javascript"></script>
  <script src="chosen.jquery.js" type="text/javascript"></script>
  <script src="docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
  <script src="docsupport/init.js" type="text/javascript" charset="utf-8"></script>
				</form>
<?php
if(isset($_POST['chooseclient'])){ 

$clientid = mysqli_real_escape_string($mysqli, $_POST['cli']);
$client = mysqli_real_escape_string($mysqli, $_POST['cli']);	
//Code for Last Due from 1st Customer 

$lastdd =  mysqli_query($mysqli, "SELECT due FROM ledger WHERE client_id='$clientid' order by date desc limit 1");
while ($ree = mysqli_fetch_array($lastdd)) {
$lastduee=$ree['due'];}	


//Code for Last Due from 1st Customer

//Code for Last Payment from 1st Customer
$lastp =  mysqli_query($mysqli, "SELECT paid FROM ledger WHERE client_id='$clientid' order by date desc limit 1");
while ($pee = mysqli_fetch_array($lastp)) {
$lastpa=$pee['paid'];}
//Code for Last Payment from 1st Customer


	echo '<form action="manual.php" method="post" name="form1">
		<table width="25%" border="0">
	
		
			<tr> 
				<td>Lastdue</td>
				<td> <input type="text" name="lastdue" value="' .$lastduee. '"></td>
			</tr>
			<tr> 
				<td>Manual Payment Amount</td>
				<td><input type="text" name="mpay"></td>
			</tr>

			<tr> 
				<td></td>
				<td><input type="hidden" name="lastpa" value="' .$lastpa. '"></td>
				<td><input type="hidden" name="clientid" value="' .$clientid. '"></td>
				<td><input type="submit" name="Submit" value="Add"></td>
			</tr>
		</table>
		

  
	</form>' ;
	
	
} 
	


	if(isset($_POST['Submit'])) {
		



	$lastdue = mysqli_real_escape_string($mysqli, $_POST['lastdue']);
	$mpay = mysqli_real_escape_string($mysqli, $_POST['mpay']);
	$lastpa = mysqli_real_escape_string($mysqli, $_POST['lastpa']);
	$clientid = mysqli_real_escape_string($mysqli, $_POST['clientid']);
	
	$lastedt = $user["uUsername"];
	$tdate = date('y-m-d');
	
	$cupay = ($lastpa+$mpay);
	$curdue = ($lastdue-$mpay);
	
// Getting Client Name 
$ccname = mysqli_query($mysqli, "SELECT name FROM clientinfo WHERE cid='$clientid' ");	
while ($re = mysqli_fetch_array($ccname)) {
$cname=$re['name'];}

	
		if(empty($mpay)) {
			echo "<font color='red'>Manual Payment is empty.</font><br/>";
		
	
		//link to the previous page
		echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";

			} else { 
			
		//insert data to database	
$result = mysqli_query($mysqli, "INSERT INTO manualp(date,client_id,lastdue,lastp,mpayment,cdue,cpayment,addedby,cname) VALUES('$tdate','$clientid','$lastdue','$lastpa','$mpay','$curdue','$cupay','$lastedt','$cname')");
$update = mysqli_query($mysqli, "UPDATE ledger SET paid='$cupay',due='$curdue' WHERE client_id='$clientid' order by date desc limit 1 ");
		
		//display success message
		echo "<font color='green'>Manual Payment added successfully.";
		echo "<br/><a href='manualhis.php'>View Result</a>";
	} }


	?>
	
	


	</body>
</html>
<?php include 'footer.php'; ?>