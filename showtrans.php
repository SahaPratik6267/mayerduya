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




		<h1>List of Transactions</h1>
<?PHP		
//echo "<a href=createinvoice.php?id=".$client.">Create Invoice for Customer</a><br/><br/>";
?>

<form action="showtrans.php" method="post" name="form1">
		<div id="table-container">
		<table width="20%" border="0">
		
		<table class="data" id="maintable" width='70%' border=0>

		
		
		<?php if(isset($_POST['Submit'])) {	
		
$bid = mysqli_real_escape_string($mysqli, $_POST['bank_id']);
$result = mysqli_query($mysqli, "SELECT * FROM trans WHERE bid=$bid ORDER BY tid DESC ");


$sres = mysqli_query($mysqli, "SELECT * FROM bankacc WHERE bid=$bid");
$sq = mysqli_fetch_array($sres); 

$snam = $sq['accname'];
$sname = $sq['accno'];




echo "<div class='center'>";
echo "<br />";
echo "Account :  : "; echo $snam;
echo "<br />";
echo "Account No : "; echo $sname;
echo "<br />";echo "<br />";
echo "</div>";
echo "<br />";


	
	
		
		echo "<thead>";
	    echo "<tr bgcolor='#7bbafb'>";
		echo "<td>Sl No</td>";
		echo "<td>Date</td>";
		echo "<td>Account Name</td>";
		echo "<td>Details</td>";
		echo "<td>Withdrawal</td>";
		echo "<td>Deposit</td>";
		echo "<td>Balance</td>";
		echo "<td>Note</td>";
		echo "<td>Added By</td>";
		echo "</tr>";
		echo "</thead>";
	
while($res = mysqli_fetch_array($result)) {

$a = mysqli_query($mysqli, "SELECT * FROM bankacc WHERE bid=$bid");

while($x = mysqli_fetch_array($a)) {
$z = $x['accname'];
$b = $x['accno'];
	}

	 	echo "<tbody>";
		echo "<tr bgcolor='#b7d8c8'>";
		echo "<td>".$res['tid']."</td>";
		echo "<td>".$res['date']."</td>";
		echo "<td>". $z."-".$b."</td>";	
		echo "<td>".$res['details']."</td>";
		echo "<td>".$res['withdraw']."</td>";
		echo "<td>".$res['deposit']."</td>";	
		echo "<td>".$res['cbalance']."</td>";	
		echo "<td>".$res['tnote']."</td>";
		echo "<td>".$res['addby']."</td>";		
		echo "<td><a href=\"edittrans.php?id=$res[tid]\">Edit</a></td>";
		echo "</tr>";
 	echo "</tbody>";		
	}
		
	
}?>
</table>
			


<div id="bottom_anchor"></div>
</div>
<br />

		<center>
		
				<tr> 
				<td>Select Account</td>
				<td>
				
			<?php 
$resultt = mysqli_query($mysqli, "SELECT * FROM bankacc ");

echo "<select name='bank_id' data-placeholder='Choose a Account...' class='chosen-select' tabindex='2'>";
echo "<option value=''></option>";
while ($resst = mysqli_fetch_array($resultt)) {

	
    echo "<option value='" . $resst['bid']."'>" . $resst['accname']."-".$resst['accno']  ."</option>";
}

echo "</select>";
				
				
		
				
				
				?>
				
				</td>
								<td><input type="submit" name="Submit" value="Select"></td>
			</tr>	
		
		
		</center>
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