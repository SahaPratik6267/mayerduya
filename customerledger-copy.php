<?php
	/**
	* Make sure you started your'e sessions!
	* You need to include su.inc.php to make SimpleUsers Work
	* After that, create an instance of SimpleUsers and your'e all set!
	*/

	session_start();
	require_once(dirname(__FILE__)."/simpleusers/su.inc.php");

	$SimpleUsers = new SimpleUsers();
error_reporting(0);
	// This is a simple way of validating if a user is logged in or not.
	// If the user is logged in, the value is (bool)true - otherwise (bool)false.
	if( !$SimpleUsers->logged_in )
	{
		header("Location: login.php");
		exit;
	}

	// If the user is logged in, we can safely proceed.
	$users = $SimpleUsers->getUsers();
	
	$resultt = mysqli_query($mysqli, "SELECT * FROM clientinfo ORDER BY name ");

	
	//$client =1 ;
	
	
	
include 'header.php';

?>
<?php if(isset($_POST['Submit'])) {
$client = mysqli_real_escape_string($mysqli, $_POST['client_id']);}
$ex = 0; // Initializing for not showing double
//echo $client;
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
				font-size: 20px;
			}
			table
			{
				width: 1100px;
				margin: 0px auto;
			}



.data thead{right: 400px; font-size: 20px; color: #FF0000;}
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

		
.center {
    margin: auto;
    width: 60%;
font-weight: bold; letter-spacing: -1px; line-height: 1; 
  font-family: 'Helvetica Neue', sans-serif;
	text-align: center;
	font-size: 30px;
	border : 1px solid;
}

.cent {
    margin: auto;
    width: 60%;
font-weight: bold; letter-spacing: -1px; line-height: 1; 
  font-family: 'Helvetica Neue', sans-serif;
	text-align: center;
	font-size: 30px;
	
}
	  </style>

	</head>
	<body>




		<h1>Customer Ledger</h1>
<?PHP		
//echo "<a href=createinvoice.php?id=".$client.">Create Invoice for Customer</a><br/><br/>";
?>

<form action="customerledger.php" method="post" name="form1">
		<div id="table-container">
		<table width="20%" border="0">
		
		<table class="data" id="maintable" width='70%' border=0>

		
		
		<?php if(isset($_POST['Submit'])) {	
		
$client = mysqli_real_escape_string($mysqli, $_POST['client_id']);	

$sres = mysqli_query($mysqli, "SELECT * FROM clientinfo WHERE cid='$client'");
$sq = mysqli_fetch_array($sres); 

$snam = $sq['name'];
$sname = $sq['address'];
$smobile = $sq['mobile'];

$xyz = $client;		
$lastd =  mysqli_query($mysqli, "SELECT due FROM ledger WHERE client_id='$xyz' order by order_id desc limit 1 ");
$ree = mysqli_fetch_array($lastd);
if($ree['due']==null){$ree['due']=0;}


echo "<div class='center'>";
echo "<br />";
echo "Customer Name : "; echo $snam;
echo "<br />";
echo "Mobile No : "; echo $smobile;
echo "<br />";
echo "Address : ";echo $sname;
echo "<br />";echo "<br />";
echo "</div>";
echo "<br />";

echo "<div class='cent'>";
echo "Current Due : ";echo $ree['due'];
echo "</div>";
echo "<br />";echo "<br />";


$getcname = mysqli_query($mysqli, "SELECT * FROM clientinfo WHERE cid='$client'");
	
	
		
		echo "<thead>";
	    echo "<tr bgcolor='#CCCCCC'>";
	    echo "<td>Date</td>";
		echo "<td>Invoice No</td>";
		echo "<td>Item</td>";
		echo "<td>Carton No</td>";
		echo "<td>Weight</td>";
		echo "<td>Rate</td>";
		echo "<td>Bill</td>";
		echo "<td>Paid</td>";
		echo "<td>Due</td>";
		echo "<td>Shipment</td>";
		echo "<td>Lot</td>";
		echo "<td>Last Edit</td>";
		echo "<td>Note</td>";
		echo "<td>Edit</td>";
		echo "<td>View</td>";
		echo "</tr>";
		echo "</thead>";
	
	
	$getledger = mysqli_query($mysqli, "SELECT * FROM ledger WHERE client_id='$client' order by date DESC ,order_id DESC");
	//$getledger = mysqli_query($mysqli, "SELECT * FROM ledger WHERE client_id='$client' order by order_id DESC");
	while($resu = mysqli_fetch_array($getledger)) {
	$subtotal=0;
	$cartontotal=0;
	$invoice = $resu['invoice_no'];
	$getitem = mysqli_query($mysqli, "SELECT * FROM items WHERE invoice_no='$invoice'");
	while($r = mysqli_fetch_array($getitem)) {
	    
	 	$subtotal=$subtotal+($r['weight_pcs']*$r['rate']);
		$cartontotal=$cartontotal+$r['carton'];



/*
Code for Showing Manual Payment Inside of Ledger 
*******
*END*
*******
*/


$currentdate=$resu['date'];

if (empty($prevdate)) {

$prevdate = "2050-01-01";  // As sometime manual Payment is after last lager payment date . Please update it after 2050 :P if alive :P 
} 

// Code to test out the dates in pair
/*
echo "Cur Date :";
echo $currentdate; 
echo "<br />";
echo "After Date :";
echo $prevdate;
echo "<br />";echo "<br />";


$getpayment = mysqli_query($mysqli, "SELECT * FROM manualp WHERE client_id='$client' AND date between '$currentdate' and '$prevdate' order by date DESC ");
while($payment = mysqli_fetch_array($getpayment)) {

echo "<tr>";
echo "<td bgcolor='#429bf4' >".$payment['date']."</td>";
echo "<td bgcolor='#429bf4'>Manual </td>";
echo "<td bgcolor='#429bf4'>Payment</td>";
echo "<td bgcolor='#429bf4'>Amount : </td>";
echo "<td>------</td>";
echo "<td>------</td>";
echo "<td>------</td>";
echo "<td bgcolor='#429bf4'>".$payment['mpayment']."</td>";
echo "<td>------</td>";
echo "<td bgcolor='#429bf4'>Discount: </td>";
echo "<td bgcolor='#429bf4'>".$payment['discount']."</td>";
echo "<td>------</td>";
echo "<td bgcolor='#429bf4'>".$payment['mnote']."</td>";
echo "</tr>";
	} 
	$prevdate = $currentdate;*/
/*
Code for Showing Manual Payment Inside of Ledger 
*******
*END*
*******
*/	



//
	 	echo "<tbody>";
		echo "<tr>";
		echo "<td>"."---"."</td>";
	    echo "<td>"."---"."</td>";
		echo "<td>".$r['item']."</td>";
		echo "<td>".$r['carton']."</td>";	
		echo "<td>".$r['weight_pcs']."</td>";	
		echo "<td>".$r['rate']."</td>";	
		echo "<td>".$r['weight_pcs']*$r['rate']."</td>";	
		echo "<td>"."---"."</td>";	
		echo "<td>"."---"."</td>";	
		echo "<td>"."---"."</td>";
		echo "<td>"."---"."</td>";	
		echo "<td>"."---"."</td>";	
		echo "<td>"."---"."</td>";	
		echo "</tr>";
	
	}
	echo "<tr  bgcolor='#CCCCCC'>";
		echo "<td>".$resu['date']."</td>";
		echo "<td>".$resu['invoice_no']."</td>";
		echo "<td>"."---"."</td>";
		echo "<td>".$cartontotal."</td>";	
		echo "<td>"."---"."</td>";	
		echo "<td>"."---"."</td>";	
		echo "<td>".$subtotal."</td>";	
		echo "<td>".$resu['paid']."</td>";	
		echo "<td>".$resu['due']."</td>";	
		echo "<td>".$resu['shipment']."</td>";
		echo "<td>".$resu['lot_no']."</td>";
		echo "<td>".$resu['lastedit']."</td>";
		echo "<td>".$resu['lnote']."</td>";
		echo "<td><a href=\"editinvoice.php?id=$resu[invoice_no]\">Edit</a></td>";
		echo "<td><a href=\"viewinvoice.php?id=$resu[invoice_no]\">View</a></td>";
		echo "</tr>";
		

echo "</tbody>";
	}
	
}
?>
</table>
			


<div id="bottom_anchor"></div>
</div>
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
			
				<td><input type="submit" name="Submit" value="Select"></td>
			</tr></center>
		</table>
		
		
  <script src="docsupport/jquery-3.2.1.min.js" type="text/javascript"></script>
  <script src="chosen.jquery.js" type="text/javascript"></script>
  <script src="docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
  <script src="docsupport/init.js" type="text/javascript" charset="utf-8"></script>
    <script>function moveScroll(){
    var scroll = $(window).scrollTop();
    var anchor_top = $("#maintable").offset().top;
    var anchor_bottom = $("#bottom_anchor").offset().top;
    if (scroll>anchor_top && scroll<anchor_bottom) {
    clone_table = $("#clone");
    if(clone_table.length == 0){
        clone_table = $("#maintable").clone();
        clone_table.attr('id', 'clone');
        clone_table.css({position:'fixed',
                 'pointer-events': 'none',
				 right: 124,
                 top:0});
        clone_table.width($("#maintable").width());
        $("#table-container").append(clone_table);
        $("#clone").css({visibility:'hidden'});
        $("#clone thead").css({visibility:'visible'});
    }
    } else {
    $("#clone").remove();
    }
}
$(window).scroll(moveScroll);</script>
	</form>
		
		

	</body>
</html>
<?php include 'footer.php'; ?>