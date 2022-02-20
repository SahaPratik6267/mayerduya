<!doctype html>
<html>
<head>
<link rel="stylesheet" href="style.css">
    <meta charset="utf-8">
    <title>A simple, clean, and responsive HTML invoice template</title>
    
	
	
	
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
	
	
$userId=0;
$user = $SimpleUsers->getSingleUser($userId);

//getting id from url
$id = $_GET['id'];
//selecting data associated with this particular id	

$resled = mysqli_query($mysqli, "SELECT * FROM ledger WHERE invoice_no=$id");

$re = mysqli_fetch_array($resled); 
$getorderid=$re['order_id'];
$client=$re['client_id'];
$prevdue=$re['due'];


$getdate= $re['date'];
$time = strtotime($getdate);
$myFormatForView = date("Y-m-d\TH:i", $time);
$date = date('Y-m-d\TH:i');
$datev = $myFormatForView;

 //echo $getdate;
 //echo '<br />';
 //echo $date;
 
 $getclient= $re['client_id'];
 $getpaid=$re['paid'];
 
 
 $getcduefromman=mysqli_query($mysqli, "SELECT date,cdue FROM manualp WHERE client_id='$client' AND date<'$getdate' order by date DESC LIMIT 1");
 $gettduemanual=mysqli_fetch_array($getcduefromman);
 $getdue=$gettduemanual['cdue'];
 $getdatemanual=$gettduemanual['date'];
 
 $getcorrectdue= mysqli_query($mysqli, "SELECT date,due FROM ledger WHERE client_id='$client' AND order_id<'$getorderid' order by date DESC LIMIT 1");
 $gettdue=mysqli_fetch_array($getcorrectdue);
 $getledgerdate=$gettdue['date'];
 if($getledgerdate>$getdatemanual){
 $getdue=$gettdue['due'];}
 //echo $getdue;
 
 //$getdue=$re['due'];
 $getshipment=$re['shipment'];
 $getlotno=$re['lot_no'];
 $getlnote=$re['lnote'];
 $client=mysqli_query($mysqli, "SELECT name,company FROM clientinfo WHERE cid='$getclient'");
 $clientnme = mysqli_fetch_array($client); 
 $getclientname=$clientnme['name'];
 $getclconmpany= $clientnme['company'];
 $cname = $clientnme['name'] . ' ' . $clientnme['company'];


$resitem = mysqli_query($mysqli, "SELECT * FROM items WHERE invoice_no=$id");
 $i=0;
 while ($getitems = mysqli_fetch_array($resitem)){
 $item[$i]=$getitems['item'];
 $cartonno[$i]=$getitems['carton'];
 $weight[$i]=$getitems['weight_pcs'];
 $rate[$i]=$getitems['rate'];
 $i++;
 }

	
	
?>
<?php
if(isset($_POST['chooseclient'])){
	
	
	$duequery= mysqli_query($mysqli, "SELECT due FROM ledger WHERE client_id='$clientid' order by order_id desc limit 1");
	$gedue = mysqli_fetch_array($duequery); 
    $lastdue=$getdue['due'];
	if($lastdue== null){$lastdue=0;}
	
	$date = mysqli_real_escape_string($mysqli, $_POST['date']);	
	$invoiceno = mysqli_real_escape_string($mysqli, $_POST['invoice_no']);
	$item =$_POST['item'];
	$cartonno = $_POST['carton_no'];
	$weight = $_POST['weight_pcs'];
	$rate = $_POST['rate'];
	$shipment = mysqli_real_escape_string($mysqli, $_POST['shipment']);
	$lot = mysqli_real_escape_string($mysqli, $_POST['lot_no']);

	$lnote = mysqli_real_escape_string($mysqli, $_POST['lnote']);
	$getpaid = mysqli_real_escape_string($mysqli, $_POST['paid']);}
	
	
	
	
	if(isset($_POST['Submit'])) {	
	
	
	$date = mysqli_real_escape_string($mysqli, $_POST['date']);	
	$getorderid=mysqli_real_escape_string($mysqli, $_POST['orderid']);	
	$client=mysqli_real_escape_string($mysqli, $_POST['clientid']);
	
	$invoiceno = mysqli_real_escape_string($mysqli, $_POST['invoice_no']);
	
	
	$item =$_POST['item'];

	
	$cartonno = $_POST['carton_no'];
	$weight = $_POST['weight_pcs'];
	$rate = $_POST['rate'];

	$paid = mysqli_real_escape_string($mysqli, $_POST['paid']);
	

	$shipment = mysqli_real_escape_string($mysqli, $_POST['shipment']);
	
	$lot = mysqli_real_escape_string($mysqli, $_POST['lot_no']);
	

	$lnote = mysqli_real_escape_string($mysqli, $_POST['lnote']);
	
	$lastedit = mysqli_real_escape_string($mysqli, $_POST['lastedit']);
	
	$dued = mysqli_real_escape_string($mysqli, $_POST['dued']);
	
	$id = mysqli_real_escape_string($mysqli, $_POST['invid']);
	
	
	
	if(empty($date) || empty($invoiceno) || empty($item) || empty($cartonno) || empty($weight) || empty($rate) || empty($bill) || empty($due) || empty($shipment) || empty($lot)) {
				
		if(empty($date)) {
			echo "<font color='red'>Date field is empty.</font><br/>";
		}
		
		if(empty($invoiceno)) {
			echo "<font color='red'>invoice field is empty.</font><br/>";
		}
	
		
		
		
			if(empty($shipment)) {
			echo "<font color='red'>shipment field is empty.</font><br/>";
		}
		
			if(empty($lot)) {
			echo "<font color='red'>lot field is empty.</font><br/>";
		}
		
		//link to the previous page
	
	

		}  
$deleteinvoice=0;
$p=0;
while($p<15){
if(empty($item[$p]) && empty($cartonno[$p]) && empty($weight[$p]) && empty($rate[$p]))
{$deleteinvoice=1;}
else {$deleteinvoice=0;}
if($deleteinvoice==0){break;}
$p++;
}	

	
if($deleteinvoice==1){
		$deleteinv= (mysqli_query($mysqli, "DELETE FROM `ledger` WHERE invoice_no='$id'"));

}		
			
			//Update data to database
$ledupt = "UPDATE ledger SET `date`='$date',`invoice_no`='$invoiceno',`paid`='$paid',`due`='$dued',`shipment`='$shipment',`lot_no`='$lot',`lastedit`='$lastedit',`lnote`='$lnote' WHERE `invoice_no`='$id'";			
			$updateled=(mysqli_query($mysqli, $ledupt));
			
		$deleteitems= (mysqli_query($mysqli, "DELETE FROM `items` WHERE invoice_no='$id'"));
		$i=0; 
	while($i<15){
	if($item[$i]!=null){	
	$result = mysqli_query($mysqli, "INSERT INTO items(invoice_no,item,carton,weight_pcs,rate,indx) VALUES('$invoiceno','$item[$i]','$cartonno[$i]','$weight[$i]','$rate[$i]','$i')");}
	$i++;
	}
	
	
	
	$dif=$dued-$prevdue;
	

	
	$cnt = 	mysqli_query($mysqli, "SELECT * FROM ledger WHERE client_id='$client' AND order_id>'$getorderid'");
	
	

	
	while($ccc = mysqli_fetch_array($cnt)){
	$newdue=0;	
		$x = $ccc['invoice_no'];
		
		
	
	$fetchdue = mysqli_query($mysqli, "SELECT due FROM ledger WHERE invoice_no='$x'");
	$fdue=mysqli_fetch_array($fetchdue);  
	 //echo $fdue['due'];
	// echo "</br>";
	$newdue=($fdue['due']+$dif); 
//	echo $newdue;
//	echo "</br>";
	
	$updatedue=mysqli_query($mysqli,"UPDATE ledger SET due='$newdue' WHERE invoice_no='$x'");
	} 
//update code for manual payment table	
	$cnt = 	mysqli_query($mysqli, "SELECT * FROM manualp WHERE client_id='$client' AND invoice>='$invoiceno'");
	while($ccc = mysqli_fetch_array($cnt)){
	$newdue=0;	
    $x = $ccc['mid'];
	$uplastdue=$ccc['lastdue']+$dif;
	$upcdue=$ccc['cdue']+$dif;
	
	
	$cnt = 	mysqli_query($mysqli, "UPDATE manualp SET lastdue='$uplastdue',cdue='$upcdue' WHERE mid='$x'");
	
	}	
	
	//update current due in customer table
	
	$duefrmcus = mysqli_query($mysqli, "SELECT current_due FROM clientinfo WHERE cid='$client'");
	$duetoupdateincust = mysqli_fetch_array($duefrmcus);
	$updatedduefircust=$duetoupdateincust['current_due']+$dif;

	
	$updatedueincustomer = 	mysqli_query($mysqli, "UPDATE clientinfo SET current_due='$updatedduefircust' WHERE cid='$client'");

			
	
		
		//display success message
		echo "<center><div class='alert'>Data added successfully.<a href='customer.php'>View Result</a></div></center>";
		echo "</br>";

	
}

//updated 17 april//
	
	?>



    <style>
	
	.alert {
    padding: 10px;
    background-color: #f44336;
    color: white;
	line-height: 20px;
    font-weight: bold;
}
   .invoice-box {
        max-width: 1100px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
 
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
    .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
	
	
	   .invoice-box table tr.item1 {
        border-bottom: 1px solid #eee;
		padding-left: 100px;
    }
	
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
	
	
	
	
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

#red {
   
   margin-left: 500px;
  
}
	
    </style>
	

</head>


<div class="dropdown">
  <button class="dropbtn">Users</button>
  <div class="dropdown-content">
    <a href="users.php">Uses List</a>
   <a href="newuser.php">Add New</a>
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



<body>
    <div class="invoice-box">
	
		<form oninput="tp1.value = (+w1.value)*(+r1.value);tp2.value = (+w2.value)*(+r2.value);tp3.value = (+w3.value)*(+r3.value);tp4.value = (+w4.value)*(+r4.value);tp5.value = (+w5.value)*(+r5.value);
		tp6.value = (+w6.value)*(+r6.value);tp7.value = (+w7.value)*(+r7.value);tp8.value = (+w8.value)*(+r8.value);tp9.value = (+w9.value)*(+r9.value);tp10.value = (+w10.value)*(+r10.value);
		tp11.value = (+w11.value)*(+r11.value);tp12.value = (+w12.value)*(+r12.value);tp13.value = (+w13.value)*(+r13.value);tp14.value = (+w14.value)*(+r14.value);tp15.value = (+w15.value)*(+r15.value);
		subtot.value = (+o1.value)+(+o2.value)+(+o3.value)+(+o4.value)+(+o5.value)+(+o6.value)+(+o7.value)+(+o8.value)+(+o9.value)+(+o10.value)+(+o11.value)+(+o12.value)+(+o13.value)+(+o14.value)+(+o15.value);
		tot.value = (+subtot.value)+(+lasdue.value);duyd.value = (+tot.value)-(+paid.value);
		" action="editinvoice.php?id=<?PHP echo $id;?>" method="post" name="my-form">
		
		
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="6">
				
                    <table>
                        <tr>
                            <td class="title">
                                <img src="https://i.imgur.com/QbdBJDz.png" style="width:100%; max-width:300px;">
                            </td>
                            
                            <td>
                                Invoice #: <input type="text" name="invoice_no" value="<?php echo $id; ?>"></td>
                                <td> Shipment:<?php echo  $getshipment;?> <select name="shipment" value="<?php echo $shipment; ?>">
<option value="AIR">AIR</option>
<option value="SHIP">SHIP</option>
</select></td>
                                <td> Lot: <input type="text" name="lot_no" value="<?php echo  $getlotno; ?>">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="6">
                    <table>
                        <tr>
                            <td>
Mayer Duya Electronics <br />
All Kinds of Mobile <br />
Shop#204,4th floor, <br />
Gulistan Dhaka-1000<br />
Phone: 01758425235,

                            </td>
                            
                           
								<td>Client Name :
								<?php echo $getclientname;
//								$resultt = mysqli_query($mysqli, "SELECT * FROM clientinfo order by cid ");
				
//				echo "<select id='mySelect' name='client_id' >";
//while ($rest = mysqli_fetch_array($resultt)) {
	
	
	   //if($rest['cid'] == $getclient){
         // $isSelected = ' selected="selected"'; // if the option submited in form is as same as this row we add the selected tag
   //  } else {
        //  $isSelected = ''; // else we remove any tag
 //    }
	 
	 
  //  echo "<option value='" . $rest['cid']."'".$isSelected.">" . $rest['name']."-".$rest['company']  ."</option>";
//}
//echo "</select>";
				
				
				?>
			
				</td> 
				
				
				<td>Date
		
		<td><input type="datetime-local" name="date" value="<?php echo $datev; ?>"readonly></td>
                        </tr>
                    </table>
                </td>
            </tr>
            </table>
        
            
         
           <table class="blueTable"> 
            <tr class="heading">
                <td>
                    Item
                </td>
                 <td>
                    No of Cartoon
                </td>
                <td>
                   Weight/PCS
                </td>
				
				 <td>
                    Unit Price 
                </td>
				   <td>
                   Total Price  
                </td>
            </tr>
            
             <tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[0]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[0]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]" id="w1" value="<?php echo $weight[0];  ?>">
                </td>
                <td>
                  <input type="text" name="rate[]" id="r1" value="<?php echo $rate[0]; ?>">
                </td>
				  <td>
                <output name="tp1" for="w1 r1" id="o1" ></output>
                </td>
            </tr>
			<tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[1]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[1]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]" id="w2" value="<?php echo $weight[1]; ?>">
                </td>
                <td>
                  <input type="text" name="rate[]" id="r2" value="<?php echo $rate[1]; ?>">
                </td>
				  <td>
                  <output name="tp2" for="w2 r2" id="o2" ></output>
                </td>
            </tr>
			<tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[2]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[2]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]" id="w3" value="<?php echo $weight[2]; ?>">
                </td>
                <td>
                  <input type="text" name="rate[]" id="r3" value="<?php echo $rate[2]; ?>">
                </td>
				  <td>
             
	         <output name="tp3" for="w3 r3" id="o3" ></output>
                </td>
            </tr>
			<tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[3]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[3]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]" id="w4" value="<?php echo $weight[3]; ?>">
                </td>
                <td>
                  <input type="text" name="rate[]" id="r4" value="<?php echo $rate[3]; ?>">
                </td>
				  <td>
 
	         <output name="tp4" for="w4 r4" id="o4" ></output>
                </td>
            </tr>
			<tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[4]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[4]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]" id="w5" value="<?php echo $weight[4]; ?>">
                </td>
                <td>
                  <input type="text" name="rate[]" id="r5" value="<?php echo $rate[4]; ?>">
                </td>
				  <td>
                  <output name="tp5" for="w5 r5" id="o5" ></output>
                </td>
            </tr>
			<tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[5]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[5]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]" id="w6" value="<?php echo $weight[5]; ?>">
                </td>
                <td>
                  <input type="text" name="rate[]" id="r6" value="<?php echo $rate[5]; ?>">
                </td>
				  <td>
             <output name="tp6" for="w6 r6" id="o6" ></output>
                </td>
            </tr>
			<tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[6]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[6]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]" id="w7" value="<?php echo $weight[6]; ?>">
                </td>
                <td>
                  <input type="text" name="rate[]" id="r7" value="<?php echo $rate[6]; ?>">
                </td>
				  <td>
                 
	         <output name="tp7" for="w7 r7" id="o7" ></output>
                </td>
            </tr>
			<tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[7]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[7]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]" id="w8" value="<?php echo $weight[7]; ?>">
                </td>
                <td>
                  <input type="text" name="rate[]" id="r8" value="<?php echo $rate[7]; ?>">
                </td>
				  <td>
                <output name="tp8" for="w8 r8" id="o8" ></output>
                </td>
            </tr>
			<tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[8]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[8]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]" id="w9" value="<?php echo $weight[8]; ?>">
                </td>
                <td>
                  <input type="text" name="rate[]" id="r9" value="<?php echo $rate[8]; ?>">
                </td>
				  <td>
               <output name="tp9" for="w9 r9" id="o9" ></output>
                </td>
            </tr>
			<tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[9]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[9]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]" id="w10" value="<?php echo $weight[9]; ?>">
                </td>
                <td>
                  <input type="text" name="rate[]" id="r10" value="<?php echo $rate[9]; ?>">
                </td>
				  <td>
                       <output name="tp10" for="w10 r10" id="o10" ></output>
                </td>
            </tr>
			<tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[10]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[10]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]"  id="w11" value="<?php echo $weight[10]; ?>">
                </td>
                <td>
                  <input type="text" name="rate[]"  id="r11" value="<?php echo $rate[10]; ?>">
                </td>
				  <td>
              <output name="tp11" for="w11 r11" id="o11" ></output>
                </td>
            </tr>
			<tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[11]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[11]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]" id="w12" value="<?php echo $weight[11]; ?>">
                </td>
                <td>
                  <input type="text" name="rate[]" id="r12" value="<?php echo $rate[11]; ?>">
                </td>
				  <td>
                    <output name="tp12" for="w12 r12" id="o12" ></output>
                </td>
            </tr>
			<tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[12]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[12]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]" id="w13" value="<?php echo $weight[12]; ?>">
                </td>
                <td>
                  <input type="text" name="rate[]" id="r13" value="<?php echo $rate[12]; ?>">
                </td>
				  <td>
               <output name="tp13" for="w13 r13" id="o13" ></output>
                </td>
            </tr>
			<tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[13]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[13]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]" id="w14" value="<?php echo $weight[13]; ?>">
                </td>
                <td>
                  <input type="text" name="rate[]" id="r14" value="<?php echo $rate[13]; ?>">
                </td>
				  <td>
                 <output name="tp14" for="w14 r14" id="o14" ></output>
                </td>
            </tr>
			<tr class="item">
               
                 <td>
                  <input type="text" name="item[]" value="<?php echo $item[14]; ?>">
                </td>
                <td>
                   <input type="text" name="carton_no[]" value="<?php echo $cartonno[14]; ?>">
				     </td>
					 <td>
                 <input type="text" name="weight_pcs[]" id="w15" value="<?php echo $weight[14]; ?>">
                </td>
                <td>
                  <input type="text" name="rate[]" id="r15" value="<?php echo $rate[14]; ?>">
                </td>
				  <td>
              <output name="tp15" for="w15 r15" id="o15" ></output>
		      
                </td>
            </tr>
            
            
            
        <table id="red">
            		   <tr class="item1">
                <td>
                  Sub Total
                </td>
                
                <td>
                 <?php 
				 $i=0;
				 $subtot=0;
				 while($i<15)
				 { $subtot=$subtot+$tot[$i];
				 $i++;}
				 //echo $subtot;				 
				 ?>
				 
						    <output name="subtot" id="subtot" for="o1 o2 o4 o5 o6 o7 o8 o9 o10 o11 o12 o13 o14 o15" id="stot" ></output>	 
                </td>
            </tr>
			
			
            		   <tr class="item1">
                <td>
                 B/F
                </td>
                
                <td>
               <input type="text" name="lasdue" id="lasdue" value="  <?php echo $getdue; ?>" readonly>
                </td>
            </tr>
			
			
			        		   <tr class="item1">
                <td>
               Total 
                </td>
                
                <td>
                 <?php 
				 $total =$getdue+$subtot; 
				 ?>
				  <output name="tot" for="subtot lasdue" id="tot" ></output>
                </td>
            </tr>
				   <tr class="item1">
                <td>
                   Paid Amount 
                </td>
                
                <td>
                 <input type="text" name="paid" value="<?php echo $getpaid; ?>">
                </td>
            </tr>
			
				   <tr class="item1">
                <td>
                   Due
                </td>
                
                <td>
            <input type="text" id="duyd" name="dued" for="tot paid">
                </td>
            </tr>
			
				   <tr class="item last">
                <td>
                   Note 
                </td>
                
                <td>
                 <input type="text" name="lnote" value="<?php echo $getlnote; ?>">
                </td>
				<td><input type="hidden" name="orderid" value="<?php echo $getorderid; ?>"></td>
				<td><input type="hidden" name="clientid" value="<?php echo $getclient; ?>"></td>
				<td>
				
                 
				 <input type="hidden" name="lastedit" value="<?php echo $user["uUsername"]; ?>"> </td>
				 <td><input type="hidden" name="invid" value="<?php echo $id; ?>"> </td>
            </tr>
			</table><br /><br />
			<center><tr> 
				<td><input type="submit" name="Submit" value="Edit"></td>
			
			</tr></center>
        </table>
		
		</form>
		
		
    </div>
	
	

</body>
</html>