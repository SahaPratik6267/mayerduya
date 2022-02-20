<div id="SCREEN_VIEW_CONTAINER">
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
	
	$result = mysqli_query($mysqli, "SELECT * FROM bankacc ");
	
		
include 'header.php'; 
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>

	  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	  <style type="text/css">

			* {	margin: 0px; padding: 0px; }
			body
			{
				padding: 30px;
				font-family: Calibri, Verdana, "Sans Serif";
				font-size: 18px;
			}
			table
			{
				width: 1200px;
				margin: 0px auto;
			}

			th, td
			{
				padding: 5px;
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


.card {
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    transition: 0.3s;
    width: 40%;
}

.card:hover {
    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
}

.container {
    padding: 2px 16px;
}
h2 { color: #111; font-family: 'Helvetica Neue', sans-serif; font-size: 50px; font-weight: bold; letter-spacing: -1px; line-height: 1; text-align: center;	border-bottom: 2px solid #000000; }
	  </style>
<style type="text/css" media="print">
<!-- CSS for MP-->

#SCREEN_VIEW_CONTAINER{
        display: none;
    }
.other_print_layout{
        background-color:#FFF;
    }
</style>
	</head>
	<body>

		<h2>Bank Accounts</h2>
		
<a href="addacc.php">Add Bank Account</a><br/><br/>

<?php

// code for total Balance

$totalbalance=0;
$resultt = mysqli_query($mysqli, "SELECT * FROM bankacc ");
while($rest = mysqli_fetch_array($resultt)) {
$xy = $rest['bid'];		
$las =  mysqli_query($mysqli, "SELECT cbalance FROM trans WHERE bid='$xy' order by tid desc limit 1 ");
$re = mysqli_fetch_array($las);
if($re['cbalance']==null){$re['cbalance']=0;}
$totalbalance=$re['cbalance']+$totalbalance; }

?>
<center>
<div class="card">

  <div class="container">
    <h4><b><?php echo "Total Balance  :".$totalbalance."";?></b></h4> 
</div>
</div> </center> <br /><br />
</div>
<div id="PRINT_VIEW">
<div id="table-container">
	<table width='80%' border=0>
<table class="data" id="maintable" width='70%' border=0>
	<thead>
	<tr bgcolor='#7bbafb'>
	    <td>Sl No</td>
		<td>Account Name</td>
		<td>Account No</td>
		<td>Balance</td>
		<td>Branch</td>
		<td>Note</td>
		<td>Added By</td>

	</tr></thead>
	<?php 

	
while($res = mysqli_fetch_array($result)) {

$xyz = $res['bid'];		
$lastb =  mysqli_query($mysqli, "SELECT cbalance FROM trans WHERE bid='$xyz' order by tid desc limit 1 ");
$ree = mysqli_fetch_array($lastb);
if($ree['cbalance']==null){$ree['cbalance']=0;}
	
	
	 	echo "<tbody>";
		echo "<tr bgcolor='#b7d8c8'>";
		echo "<td>".$res['bid']."</td>";
		echo "<td>".$res['accname']."</td>";
		echo "<td>".$res['accno']."</td>";	
		echo "<td>".$ree['cbalance']."</td>";
		echo "<td>".$res['branch']."</td>";
		echo "<td>".$res['bnote']."</td>";	
		echo "<td>".$res['addby']."</td>";	
		echo "<td><a href=\"editacc.php?id=$res[bid]\">Edit</a></td>";
		echo "</tr>";
 	echo "</tbody>";		
	}
		
	
	?>
		</table></table>
<div id="bottom_anchor"></div>
</div></div>	
<div id="SCREEN_VIEW_CONTAINER">
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
				 right: -329,
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

	</body>
</html>
<?php include 'footer.php'; ?>
</div>