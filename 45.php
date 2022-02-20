<div id="SCREEN_VIEW_CONTAINER">
<?php

	/**
	* Make sure you started your'e sessions!
	* You need to include su.inc.php to make SimpleUsers Work
	* After that, create an instance of SimpleUsers and your'e all set!
	*/
error_reporting(0);
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
	
	$resultt = mysqli_query($mysqli, "SELECT * FROM clientinfo ORDER BY name ");

	$id = $_GET['id'];
	if(!empty($id)){
	$gdue = mysqli_query($mysqli, "SELECT * FROM manualp where mid='$id'");	
	$getduetoupdate=mysqli_fetch_array($gdue);
	$duetoupdate=$getduetoupdate['lastdue'];
	$paymentamount=$getduetoupdate['mpayment'];
	$paymentdate=$getduetoupdate['date'];
	$deletedvalue=$duetoupdate+$paymentamount;
	$invoiceid=$getduetoupdate['invoice_no'];
	$client=$getduetoupdate['client_id'];
	
	//update all invoice above that date
	
	$upledger= mysqli_query($mysqli, "SELECT invoice_no,due FROM ledger where client_id='$client' AND date >'$paymentdate'");
	while($updaledger = mysqli_fetch_array($upledger)){
	$invoice= $updaledger['invoice_no'];
	$updateddue= $updaledger['due']+$paymentamount;
	
	$updatedue=mysqli_query($mysqli,"UPDATE ledger SET due='$updateddue' WHERE invoice_no='$invoice'");
	
    }
	
	//update all payments
	
	$upmanual= mysqli_query($mysqli, "SELECT mid,lastdue,cdue FROM ledger where client_id='$client' AND date >'$paymentdate'");
	while($updmanual = mysqli_fetch_array($upledger)){
	$manualpid= $updmanual['mid'];
	$updatelastdue= $updmanual['lastdue']+$paymentamount;
	$updatecdue= $updmanual['cdue']+$paymentamount;
	
	$updatedue=mysqli_query($mysqli,"UPDATE manualp SET lastdue='$updatelastdue',cdue='$updatecdue' WHERE mid='$manualpid'");
	
	
	$delete = mysqli_query($mysqli, "delete FROM manualp WHERE mid='$id'");
	
	}
	
	//$client =1 ;
	

	
	
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
				font-size: 20px;
			}
			table
			{
				width: 1200px;
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

<!-- CSS for MP-->


	  </style>
<style type="text/css" media="print">

#SCREEN_VIEW_CONTAINER{
        display: none;
    }
.other_print_layout{
        background-color:#FFF;
    }
</style>
	</head>
	<body>
<h1>Manual Payment History</h1>
	
<a href="manualhis.php">Add New Customer</a><br/><br/>
</div>

<form action="manualhis.php" method="post" name="form1">
	
<div id="table-container">
	<table width="20%" border="0">
			
			
		<table class="data" id="maintable" width='70%' border=0>

		
<div id="PRINT_VIEW">	
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

//echo "<div class='cent'>";
//echo "Current Due : ";echo $ree['due'];
//echo "</div>";
//echo "<br />";echo "<br />";


$result = mysqli_query($mysqli, "SELECT * FROM manualp WHERE client_id='$client' ORDER BY date DESC");
	
		echo "<thead>";
	    echo "<tr bgcolor='#CCCCCC'>";
		echo "<td>Date</td>";
		echo "<td>Client Name</td>";
		echo "<td>Last Due</td>";
		echo "<td>Manual Payment</td>";
		echo "<td>discount</td>";
		echo "<td>Current Due</td>";
		echo "<td>Note</td>";
		echo "<td>Added By</td>";
		echo "</tr>";
		echo "</thead>";
	
	



		while($res = mysqli_fetch_array($result)) { 	
		echo "<tbody>";		
		echo "<tr bgcolor='#babdc1'>";
		echo "<td>".$res['date']."</td>";
		echo "<td>".$res['cname']."</td>";	
		echo "<td>".$res['lastdue']."</td>";
		echo "<td>".$res['mpayment']."</td>";
		echo "<td>".$res['discount']."</td>";
		echo "<td>".$res['cdue']."</td>"; 
        echo "<td>".$res['mnote']."</td>";		
		echo "<td>".$res['addedby']."</td>";
		echo "<td> <a href='manualhis.php?id=$res[mid]' onClick=\"return confirm('are you sure you want to delete payment for ".$res['cname']." on ".$res['date']." ??');\"><center>Delete</center></a>";		
		echo "</tr>";
		echo "</tbody>";
		
		} }
	
	?></div>
</table></table>


<div id="bottom_anchor"></div>
</div><div id="SCREEN_VIEW_CONTAINER">
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
				 right: 72,
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
</div>