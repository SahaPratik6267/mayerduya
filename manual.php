<?php

/**
 * Make sure you started your'e sessions!
 * You need to include su.inc.php to make SimpleUsers Work
 * After that, create an instance of SimpleUsers and your'e all set!
 */

session_start();
require_once(dirname(__FILE__) . "/simpleusers/su.inc.php");
error_reporting(0);
$SimpleUsers = new SimpleUsers();

// This is a simple way of validating if a user is logged in or not.
// If the user is logged in, the value is (bool)true - otherwise (bool)false.
if (!$SimpleUsers->logged_in) {
    header("Location: login.php");
    exit;
}

// If the user is logged in, we can safely proceed.
$users = $SimpleUsers->getUsers();

$resultt = mysqli_query($mysqli, "SELECT * FROM clientinfo order by name ");
$userId = 0;
$user = $SimpleUsers->getSingleUser($userId);

date_default_timezone_set("Asia/Dhaka");

//$client =1 ;

if (isset($_POST['chooseclient'])) {

    $clientid = mysqli_real_escape_string($mysqli, $_POST['cli']);
    $client = mysqli_real_escape_string($mysqli, $_POST['cli']);
}
include 'header.php';
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title></title>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" href="docsupport/style.css">
        <link rel="stylesheet" href="docsupport/prism.css">
        <link rel="stylesheet" href="chosen.css">
        <style type="text/css">

            * {
                margin: 0px;
                padding: 0px;
            }

            body {
                padding: 30px;
                font-family: 'Lato', sans-serif;
                font-size: 20px;
                font-weight: 300;
            }

            table {
                width: 800px;
                margin: 0px auto;
            }

            th, td {
                padding: 3px;
            }

            .right {
                text-align: right;
            }

            h1 {
                color: #FF0000;
                border-bottom: 2px solid #000000;
                margin-bottom: 15px;
            }

            p {
                margin: 10px 0px;
            }

            p.faded {
                color: #A0A0A0;
            }


        </style>

    </head>
    <body>

    <h1>Manual Payment</h1>

    <p>Select Client</p>
    <form action="manual.php" method="post" name="form1">
        <table width="25%" border="0">
            <tr>
                <td>Client Name :</td>
                <td><?php
                    echo "<select name='cli' data-placeholder='Choose a Client...' class='chosen-select' tabindex='2'>";
                    echo "<option value=''></option>";
                    while ($rest = mysqli_fetch_array($resultt)) {


                        if ($rest['cid'] == $client) {
                            $isSelected = ' selected="selected"'; // if the option submited in form is as same as this row we add the selected tag
                        } else {
                            $isSelected = ''; // else we remove any tag
                        }


                        echo "<option value='" . $rest['cid'] . "'" . $isSelected . ">" . $rest['name'] . "-" . $rest['company'] . "</option>";
                    }
                    echo "</select>"; ?></td>
                <td><input type="submit" name="chooseclient" value="Select Client"></td>
            </tr>

        </table>
        <script src="docsupport/jquery-3.2.1.min.js" type="text/javascript"></script>
        <script src="chosen.jquery.js" type="text/javascript"></script>
        <script src="docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
        <script src="docsupport/init.js" type="text/javascript" charset="utf-8"></script>
    </form>
    <?php
    if (isset($_POST['chooseclient'])) {

        $clientid = mysqli_real_escape_string($mysqli, $_POST['cli']);
        $client = mysqli_real_escape_string($mysqli, $_POST['cli']);
//Code for Last Due from 1st Customer 

        $lastdd = mysqli_query($mysqli, "SELECT current_due FROM clientinfo WHERE cid='$clientid'");
        while ($ree = mysqli_fetch_array($lastdd)) {
            $lastduee = $ree['current_due'];
        }


//Code for Last Due from 1st Customer

        $userId = 0;
        $user = $SimpleUsers->getSingleUser($userId);
        $userx = $user["uUsername"];

        $datev = date('Y-m-d H:i s');
        $date = date('Y-m-d\TH:i');
        echo '</br>';
        echo '<center>';
        echo 'Current Date and Time : ';
        echo $datev;
        echo '</center>';
        echo '</br>';
        echo '<form action="manual.php" method="post" name="form1">
		<table width="25%" border="0">
	
		    <tr> 
				<td>Date :</td>
				<td> <input type="datetime-local" name="date" value="' . $date . '"></td>
			</tr>
			<tr> 
				<td>Lastdue</td>
				<td> <input type="text" name="lastdue" value="' . $lastduee . '"></td>
			</tr>
			<tr> 
				<td>Manual Payment Amount</td>
				<td><input type="text" name="mpay"></td>
			</tr>
			<tr> 
				<td>Discount Amount</td>
				<td><input type="text" name="discount"></td>
			</tr>
<tr> 
				<td>Note</td>
				<td><input type="text" name="note"></td>
			</tr>
			<tr> 
				<td></td>
				<td><input type="hidden" name="lastpa" value="' . $lastpa . '"></td>
				<td><input type="hidden" name="clientid" value="' . $clientid . '"></td>
				<td><input type="submit" name="Submit" value="Add"></td>
				
		</table>
		

  
	</form>';


    }


    if (isset($_POST['Submit'])) {

        $clientid = mysqli_real_escape_string($mysqli, $_POST['clientid']);
        $date = mysqli_real_escape_string($mysqli, $_POST['date']);
//Code for Last Payment from 1st Customer
//$lastp =  mysqli_query($mysqli, "SELECT paid FROM ledger WHERE client_id='$clientid' order by date DESC, order_id desc limit 1");
//while ($pee = mysqli_fetch_array($lastp)) {
//$lastpa=$pee['paid'];}
//Code for Last Payment from 1st Customer
        $getinvoice = mysqli_query($mysqli, "SELECT invoice_no FROM ledger WHERE client_id='$clientid' order by invoice_no desc LIMIT 1");
        $getinv = mysqli_fetch_array($getinvoice);
        $invoiceid = $getinv['invoice_no'];
//echo $invoiceid;
        $lastdue = mysqli_real_escape_string($mysqli, $_POST['lastdue']);
        $mpay = mysqli_real_escape_string($mysqli, $_POST['mpay']);
        $discount = mysqli_real_escape_string($mysqli, $_POST['discount']);
        if (empty($discount)) {
            $discount = 0;
        }
        //echo $mpay."and".$discount;
        $totpaid = $mpay + $discount;
        //$lastpa = mysqli_real_escape_string($mysqli, $_POST['lastpa']);
        //echo $lastpa."---";

        $mnote = mysqli_real_escape_string($mysqli, $_POST['note']);

        $lastedt = $user["uUsername"];


        $cupay = ($lastpa + $mpay);
        //echo $cupay;
        $curdue = ($lastdue - $totpaid);

// Getting Client Name 
        $ccname = mysqli_query($mysqli, "SELECT name FROM clientinfo WHERE cid='$clientid' ");
        while ($re = mysqli_fetch_array($ccname)) {
            $cname = $re['name'];
        }


        if (empty($mpay)) {
            echo "<font color='red'>Manual Payment is empty.</font><br/>";


            //link to the previous page
            echo "<br/><a href='javascript:self.history.back();'>Go Back</a>";

        } else {

            //insert data to database
            $result = mysqli_query($mysqli, "UPDATE clientinfo SET current_due='$curdue' where cid='$clientid'");
            $result = mysqli_query($mysqli, "INSERT INTO manualp(date,client_id,lastdue,lastp,mpayment,cdue,cpayment,discount,addedby,cname,mnote,invoice) VALUES('$date','$clientid','$lastdue','$lastpa','$mpay','$curdue','$cupay','$discount','$lastedt','$cname','$mnote','$invoiceid')");
//$update = mysqli_query($mysqli, "UPDATE ledger SET paid='$cupay',due='$curdue' WHERE client_id='$clientid' order by order_id desc limit 1 ");

            $lastdd = mysqli_query($mysqli, "SELECT current_due FROM clientinfo WHERE cid='$clientid'");
            while ($ree = mysqli_fetch_array($lastdd)) {
                $currentdue = $ree['current_due'];
            }

            $resultt = mysqli_query($mysqli, "SELECT * FROM clientinfo where cid='$clientid'");
            $re = mysqli_fetch_array($resultt);
            $name = $re['name'];
            echo "<br/><br/>";
            echo "<font color='red' font size='100px'>Current DUE for " . $name . " is =" . $currentdue . "</font>";
            //display success message
            echo "<br/><br/><font color='green'>Manual Payment added successfully.";
            echo "<br/><br/><a href='manualhis.php'>View Result</a>";
        }
    }
    ?>


    </body>
    </html>

<?php include 'footer.php'; ?>