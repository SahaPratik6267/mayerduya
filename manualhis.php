<div id="SCREEN_VIEW_CONTAINER">
    <?php

    /**
     * Make sure you started your'e sessions!
     * You need to include su.inc.php to make SimpleUsers Work
     * After that, create an instance of SimpleUsers and your'e all set!
     */
    //error_reporting(0);
    session_start();
    require_once(dirname(__FILE__) . "/simpleusers/su.inc.php");

    $SimpleUsers = new SimpleUsers();

    // This is a simple way of validating if a user is logged in or not.
    // If the user is logged in, the value is (bool)true - otherwise (bool)false.
    if (!$SimpleUsers->logged_in) {
        header("Location: login.php");
        exit;
    }

    // If the user is logged in, we can safely proceed.
    $users = $SimpleUsers->getUsers();

    $resultt = mysqli_query($mysqli, "SELECT * FROM clientinfo ORDER BY name ");

    $id = $_GET['id'];
    if (!empty($id)) {
        $gdue = mysqli_query($mysqli, "SELECT * FROM manualp where mid='$id'");
        $getduetoupdate = mysqli_fetch_array($gdue);
        $duetoupdate = $getduetoupdate['lastdue'];
        $paymentamount = $getduetoupdate['mpayment'];
        $paymentdate = $getduetoupdate['date'];
        $deletedvalue = $duetoupdate + $paymentamount;
        $invoiceid = $getduetoupdate['invoice_no'];
        $client = $getduetoupdate['client_id'];

        //update due on customer table
        $upduecus = mysqli_query($mysqli, "SELECT current_due FROM clientinfo where cid='$client' ");
        $upduecustomer = mysqli_fetch_array($upduecus);
        $updatedcustomerdue = $upduecustomer['current_due'] + $paymentamount;

        $updatecustomerdue = mysqli_query($mysqli, "UPDATE clientinfo SET current_due='$updatedcustomerdue' WHERE cid='$client'");

        //update all invoice above that date

        $upledger = mysqli_query($mysqli, "SELECT invoice_no,due FROM ledger where client_id='$client' AND date >'$paymentdate'");
        while ($updaledger = mysqli_fetch_array($upledger)) {
            $invoice = $updaledger['invoice_no'];
            $updateddue = $updaledger['due'] + $paymentamount;

            $updatedue = mysqli_query($mysqli, "UPDATE ledger SET due='$updateddue' WHERE invoice_no='$invoice'");
            //echo '1';
        }

        //update all payments


        $updmanual = mysqli_query($mysqli, "SELECT * FROM manualp where client_id='$client' AND date >'$paymentdate'");
        while ($upmanual = mysqli_fetch_array($updmanual)) {

            $manualpid = $upmanual['mid'];
            $updatelastdue = $upmanual['lastdue'] + $paymentamount;
            $updatecdue = $upmanual['cdue'] + $paymentamount;

            $updatepayment = mysqli_query($mysqli, "UPDATE manualp SET lastdue='$updatelastdue',cdue='$updatecdue' WHERE mid='$manualpid'");
        }

        $delete = mysqli_query($mysqli, "delete FROM manualp WHERE mid='$id'");
        //echo '2';

    }

    //$client =1 ;


    include 'header.php';

    ?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>

<!--        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>-->
<!--        <link rel="stylesheet" href="docsupport/style.css">-->
        <link rel="stylesheet" href="docsupport/prism.css">
        <link rel="stylesheet" href="chosen.css">
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
        <link href="https://fonts.googleapis.com/css?family=Quicksand:400,500,600,700&display=swap" rel="stylesheet">
        <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/plugins.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/scrollspyNav.css" rel="stylesheet" type="text/css"/>
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL CUSTOM STYLES -->
        <link rel="stylesheet" type="text/css" href="plugins/table/datatable/datatables.css">
        <link rel="stylesheet" type="text/css" href="plugins/table/datatable/dt-global_style.css">
        <link href="plugins/loaders/custom-loader.css" rel="stylesheet" type="text/css"/>
        <!-- END PAGE LEVEL CUSTOM STYLES -->


        <style type="text/css">

            * {
                margin: 0px;
                padding: 0px;
            }

            body {
                padding: 30px;
                font-family: Calibri, Verdana, "Sans Serif";
                font-size: 20px;
            }

            table {
                width: 1200px;
                margin: 0px auto;
            }

            .data th {
                padding: 4px;
                border: 1px solid #dddddd;
            }

            .data td {
                padding: 4px;
                border: 1px solid #dddddd;
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


            .center {
                margin: auto;
                width: 60%;
                font-weight: bold;
                letter-spacing: -1px;
                line-height: 1;
                font-family: 'Helvetica Neue', sans-serif;
                text-align: center;
                font-size: 30px;
                border: 1px solid;
            }

            .cent {
                margin: auto;
                width: 60%;
                font-weight: bold;
                letter-spacing: -1px;
                line-height: 1;
                font-family: 'Helvetica Neue', sans-serif;
                text-align: center;
                font-size: 30px;

            }

            <!--
            CSS for MP-- >


        </style>
        <style type="text/css" media="print">

            #SCREEN_VIEW_CONTAINER {
                display: none;
            }

            .other_print_layout {
                background-color: #FFF;
            }
        </style>
    </head>
    <body>
    <div id="content" class="main-content">
        <div class="">
            <h1>Manual Payment History</h1>

            <a class="btn btn-outline-primary" href="manualhis.php">Add New Customer</a><br/><br/>
        </div>
        <div class="row" id="cancel-row">
            <div class="col-xl-12 col-lg-12 col-sm-12">
                <div class="widget-content widget-content-area br-6">
        <form action="manualhis.php" method="post" name="form1">
            <div id="SCREEN_VIEW_CONTAINER">
                <center>
                    <tr>
                        <td><b>Select Client :</b></td>
                        <td><?php

                            echo "<select name='client_id' data-placeholder='Choose a Client...' class='chosen-select' tabindex='2'>";
                            echo "<option value='all'>ALL</option>";
                            while ($rest = mysqli_fetch_array($resultt)) {


                                if ($rest['cid'] == $client) {
                                    $isSelected = ' selected="selected"'; // if the option submited in form is as same as this row we add the selected tag
                                } else {
                                    $isSelected = ''; // else we remove any tag
                                }


                                echo "<option value='" . $rest['cid'] . "'" . $isSelected . ">" . $rest['name'] . "-" . $rest['company'] . "</option>";
                            }

                            echo "</select>";


                            ?></td>
                        <br>
                        <td><br></td>
                        <td><input class="btn btn-outline-secondary" type="button" name="show_search" value="Search by Date" onclick="toggleSearch()">
                        </td>
                        <td><input class="btn btn-outline-secondary" type="button" name="show_search_single" value="Search by Single Date"
                                   onclick="toggleSearchSingle()"></td>
                        <td><input class="btn btn-outline-secondary" type="button" name="lot_search" value="Lot Search" onclick="toggleLotSearch()"></td>
                        <input type="hidden" id="search_flag" name="search_flag" value="0">
                    </tr>
                    <div class="widget-content widget-content-area br-6 mt-2" id="date_search" style="display: none;">
                        From : <input type="date" id="from_date" name="from_date">
                        To : <input type="date" id="to_date" name="to_date">
                    </div>
                    <div  class="widget-content widget-content-area br-6 mt-2" id="date_search_single" style="display: none;">
                        <input type="date" id="date_single" name="date_single">

                    </div>
                    <div  class="widget-content widget-content-area br-6 mt-2" id="lot_number_search_div" style="display: none;">
                        <label><b>Lot No. : </b></label>
                        <input type="number" id="lot_number" name="lot_number">

                    </div>

                    <tr>
                        <td><br></td>
                        <td><br><input class="btn btn-primary"type="submit" name="Submit" value="Select" onclick="test()"></td>
                    </tr>
                </center>



            </div>
            <div id="table-container">
                <table class="table table-hover" id="maintable" width='70%' border=0>
                    <div id="PRINT_VIEW">
                        <?php if (isset($_POST['Submit'])) {

                            $client = mysqli_real_escape_string($mysqli, $_POST['client_id']);
                            if ($_POST['search_flag'] == 1) {

                                if ($client != 'all') {
                                    $sres = mysqli_query($mysqli, "SELECT * FROM clientinfo WHERE cid='$client'");
                                    $sq = mysqli_fetch_array($sres);

                                    $snam = $sq['name'];
                                    $sname = $sq['address'];
                                    $smobile = $sq['mobile'];
                                    $xyz = $client;

                                    // Search with date
                                    $from_date = $_POST['from_date'];
                                    $to_date = $_POST['to_date'];
                                    $lastd = mysqli_query($mysqli, "SELECT due FROM ledger WHERE client_id='$xyz' AND (date between '$from_date' AND '$to_date')order by order_id desc limit 1 ");
                                    $ree = mysqli_fetch_array($lastd);
                                    if ($ree['due'] == null) {
                                        $ree['due'] = 0;
                                    }

                                    echo "<br>";
                                    echo "<div class='center'>";
                                    echo "<br />";
                                    echo "Customer Name : ";
                                    echo $snam;
                                    echo "<br />";
                                    echo "Mobile No : ";
                                    echo $smobile;
                                    echo "<br />";
                                    echo "Address : ";
                                    echo $sname;
                                    echo "<br />";
                                    echo "<br />";
                                    echo "</div>";
                                    echo "<br />";

//echo "<div class='cent'>";
//echo "Current Due : ";echo $ree['due'];
//echo "</div>";
//echo "<br />";echo "<br />";

                                    $query = "SELECT * FROM manualp WHERE client_id='$client' AND (date between '$from_date' AND '$to_date') ORDER BY date DESC";
                                    $result = mysqli_query($mysqli, $query);

                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th class='text-center'>Date</th>";
                                    echo "<th class='text-center'>Client Name</th>";
                                    echo "<th class='text-center'>Last Due</th>";
                                    echo "<th class='text-center'>Manual Payment</th>";
                                    echo "<th class='text-center'>discount</th>";
                                    echo "<th class='text-center'>Current Due</th>";
                                    echo "<th class='text-center'>Note</th>";
                                    echo "<th class='text-center'>Added By</th>";
                                    echo "<th></th>";
                                    echo "</tr>";
                                    echo "</thead>";

                                    echo "<tbody>";
                                    while ($res = mysqli_fetch_array($result)) {

                                        echo "<tr>";
                                        echo "<td class='text-center'>" . $res['date'] . "</td>";
                                        echo "<td class='text-center'>" . $res['cname'] . "</td>";
                                        echo "<td class='text-center'>" . $res['lastdue'] . "</td>";
                                        echo "<td class='text-center'>" . $res['mpayment'] . "</td>";
                                        echo "<td class='text-center'>" . $res['discount'] . "</td>";
                                        echo "<td class='text-center'>" . $res['cdue'] . "</td>";
                                        echo "<td class='text-center'>" . $res['mnote'] . "</td>";
                                        echo "<td class='text-center'>" . $res['addedby'] . "</td>";
                                        echo "<td class='text-center'> <a class='btn btn-outline-danger' href='manualhis.php?id=$res[mid]' onClick=\"return confirm('are you sure you want to delete payment for " . $res['cname'] . " on " . $res['date'] . " ??');\"><center>Delete</center></a>";
                                        echo "</tr>";


                                    }
                                    echo "</tbody>";
                                } else {


                                    // Search with date
                                    $from_date = $_POST['from_date'];
                                    $to_date = $_POST['to_date'];
                                    $lastd = mysqli_query($mysqli, "SELECT due FROM ledger WHERE (date between '$from_date' AND '$to_date')order by order_id desc limit 1 ");
                                    $ree = mysqli_fetch_array($lastd);
                                    if ($ree['due'] == null) {
                                        $ree['due'] = 0;
                                    }

                                    echo "<br>";
                                    echo "<div class='center'>";
                                    echo "<br />";
                                    echo "ALL Customer";
                                    echo "</div>";
                                    echo "<br/>";

//echo "<div class='cent'>";
//echo "Current Due : ";echo $ree['due'];
//echo "</div>";
//echo "<br />";echo "<br />";

                                    $query = "SELECT * FROM manualp WHERE (date between '$from_date' AND '$to_date') ORDER BY date DESC";
                                    $result = mysqli_query($mysqli, $query);

                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th class='text-center'>Date</th>";
                                    echo "<th class='text-center'>Client Name</th>";
                                    echo "<th class='text-center'>Last Due</th>";
                                    echo "<th class='text-center'>Manual Payment</th>";
                                    echo "<th class='text-center'>discount</th>";
                                    echo "<th class='text-center'>Current Due</th>";
                                    echo "<th class='text-center'>Note</th>";
                                    echo "<th class='text-center'>Added By</th>";
                                    echo "<th></th>";
                                    echo "</tr>";
                                    echo "</thead>";

                                    echo "<tbody>";
                                    while ($res = mysqli_fetch_array($result)) {

                                        echo "<tr>";
                                        echo "<td class='text-center'>" . strftime("%d-%m-%Y",strtotime($res['date'])) . "</td>";
                                        echo "<td class='text-center'>" . $res['cname'] . "</td>";
                                        echo "<td class='text-center'>" . $res['lastdue'] . "</td>";
                                        echo "<td class='text-center'>" . $res['mpayment'] . "</td>";
                                        echo "<td class='text-center'>" . $res['discount'] . "</td>";
                                        echo "<td class='text-center'>" . $res['cdue'] . "</td>";
                                        echo "<td class='text-center'>" . $res['mnote'] . "</td>";
                                        echo "<td class='text-center'>" . $res['addedby'] . "</td>";
                                        echo "<td class='text-center'> <a class='btn btn-outline-danger' href='manualhis.php?id=$res[mid]' onClick=\"return confirm('are you sure you want to delete payment for " . $res['cname'] . " on " . $res['date'] . " ??');\"><center>Delete</center></a>";
                                        echo "</tr>";


                                    }
                                    echo "</tbody>";
                                }


                            } else if ($_POST['search_flag'] == 2) {
                                if ($client != 'all') {
                                    $sres = mysqli_query($mysqli, "SELECT * FROM clientinfo WHERE cid='$client'");
                                    $sq = mysqli_fetch_array($sres);

                                    $snam = $sq['name'];
                                    $sname = $sq['address'];
                                    $smobile = $sq['mobile'];
                                    $xyz = $client;

                                    // Search with date
                                    $date_single = $_POST['date_single'];
                                    $query = "SELECT due FROM ledger WHERE client_id='$xyz' AND date LIKE '" . $date_single . "%' order by order_id desc limit 1 ";
                                    $lastd = mysqli_query($mysqli, $query);
                                    $ree = mysqli_fetch_array($lastd);
                                    if ($ree['due'] == null) {
                                        $ree['due'] = 0;
                                    }

                                    echo "<br>";
                                    echo "<div class='center'>";
                                    echo "<br />";
                                    echo "Customer Name : ";
                                    echo $snam;
                                    echo "<br />";
                                    echo "Mobile No : ";
                                    echo $smobile;
                                    echo "<br />";
                                    echo "Address : ";
                                    echo $sname;
                                    echo "<br />";
                                    echo "<br />";
                                    echo "</div>";
                                    echo "<br />";

//echo "<div class='cent'>";
//echo "Current Due : ";echo $ree['due'];
//echo "</div>";
//echo "<br />";echo "<br />";

                                    $query = "SELECT * FROM manualp WHERE client_id='$client' AND date LIKE '" . $date_single . "%' ORDER BY date DESC";
                                    $result = mysqli_query($mysqli, $query);

                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<thclass='text-center'>Date</th>";
                                    echo "<th class='text-center'>Client Name</th>";
                                    echo "<th class='text-center'>Last Due</th>";
                                    echo "<th class='text-center'>Manual Payment</th>";
                                    echo "<th class='text-center'>discount</th>";
                                    echo "<th class='text-center'>Current Due</th>";
                                    echo "<th class='text-center'>Note</th>";
                                    echo "<th class='text-center'>Added By</th>";
                                    echo "<th></th>";
                                    echo "</tr>";
                                    echo "</thead>";

                                    echo "<tbody>";
                                    while ($res = mysqli_fetch_array($result)) {

                                        echo "<tr>";
                                        echo "<td class='text-center'>" . strftime("%d-%m-%Y",strtotime($res['date'])) . "</td>";
                                        echo "<td class='text-center'>" . $res['cname'] . "</td>";
                                        echo "<td class='text-center'>" . $res['lastdue'] . "</td>";
                                        echo "<td class='text-center'>" . $res['mpayment'] . "</td>";
                                        echo "<td class='text-center'>" . $res['discount'] . "</td>";
                                        echo "<td class='text-center'>" . $res['cdue'] . "</td>";
                                        echo "<td class='text-center'>" . $res['mnote'] . "</td>";
                                        echo "<td class='text-center'>" . $res['addedby'] . "</td>";
                                        echo "<td class='text-center'> <a class='btn btn-outline-danger' href='manualhis.php?id=$res[mid]' onClick=\"return confirm('are you sure you want to delete payment for " . $res['cname'] . " on " . $res['date'] . " ??');\"><center>Delete</center></a>";
                                        echo "</tr>";


                                    }
                                    echo "</tbody>";
                                } else {


                                    // Search with date
                                    $date_single = $_POST['date_single'];
                                    $query = "SELECT due FROM ledger WHERE date LIKE '" . $date_single . "%' order by order_id desc limit 1 ";
                                    $lastd = mysqli_query($mysqli, $query);
                                    $ree = mysqli_fetch_array($lastd);
                                    if ($ree['due'] == null) {
                                        $ree['due'] = 0;
                                    }


                                    echo "<div class='center'>";
                                    echo "<br />";

                                    echo "ALL Customer";
                                    echo "</div>";
                                    echo "<br/>";

//echo "<div class='cent'>";
//echo "Current Due : ";echo $ree['due'];
//echo "</div>";
//echo "<br />";echo "<br />";

                                    $query = "SELECT * FROM manualp WHERE date LIKE '" . $date_single . "%' ORDER BY date DESC";
                                    $result = mysqli_query($mysqli, $query);

                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th class='text-center'>Date</th>";
                                    echo "<th class='text-center' >Client Name</th>";
                                    echo "<th class='text-center'>Last Due</th>";
                                    echo "<th class='text-center'>Manual Payment</th>";
                                    echo "<th class='text-center'>Discount</th>";
                                    echo "<th class='text-center'>Current Due</th>";
                                    echo "<th class='text-center'>Note</th>";
                                    echo "<th class='text-center'>Added By</th>";
                                    echo "<th></th>";
                                    echo "</tr>";
                                    echo "</thead>";

                                    echo "<tbody>";
                                    while ($res = mysqli_fetch_array($result)) {

                                        echo "<tr>";
                                        echo "<td class='text-center'>" . strftime("%d-%m-%Y",strtotime($res['date'])) . "</td>";
                                        echo "<td class='text-center'>" . $res['cname'] . "</td>";
                                        echo "<td class='text-center'>" . $res['lastdue'] . "</td>";
                                        echo "<td class='text-center'>" . $res['mpayment'] . "</td>";
                                        echo "<td class='text-center'>" . $res['discount'] . "</td>";
                                        echo "<td class='text-center'>" . $res['cdue'] . "</td>";
                                        echo "<td class='text-center'>" . $res['mnote'] . "</td>";
                                        echo "<td class='text-center'>" . $res['addedby'] . "</td>";
                                        echo "<td class='text-center'> <a class='btn btn-outline-danger' href='manualhis.php?id=$res[mid]' onClick=\"return confirm('are you sure you want to delete payment for " . $res['cname'] . " on " . $res['date'] . " ??');\"><center>Delete</center></a>";
                                        echo "</tr>";


                                    }
                                    echo "</tbody>";
                                }
                            } else if ($_POST['search_flag'] == 3) {
                                $query = "SELECT manualp.* FROM `ledger` INNER JOIN manualp ON ledger.client_id = manualp.client_id WHERE ledger.lot_no = " . $_POST['lot_number'];
                                $result = mysqli_query($mysqli, $query);
                                $query2 = "SELECT SUM(manualp.mpayment) FROM `ledger` INNER JOIN manualp ON ledger.client_id = manualp.client_id WHERE ledger.lot_no = " . $_POST['lot_number'];
                                $result2 = mysqli_query($mysqli, $query2);
                                $res = mysqli_fetch_array($result2);

                                $fmt = numfmt_create('en_US', NumberFormatter::CURRENCY);
                                echo "<br>";
                                echo '<div class="text-center"><h1>Total Manual Payment : ' . numfmt_format_currency($fmt, $res[0], "BDT") . '</h1></div>';
                                echo "<br>";
                                echo "<thead>";
                                echo "<tr>";
                                echo "<th class='text-center'>Date</th>";
                                echo "<th class='text-center'>Client Name</th>";
                                echo "<th class='text-center'>Last Due</th>";
                                echo "<th class='text-center'>Manual Payment</th>";
                                echo "<th class='text-center'>Discount</th>";
                                echo "<th class='text-center'>Current Due</th>";
                                echo "<th class='text-center'>Note</th>";
                                echo "<th class='text-center'>Added By</th>";
                                echo "</tr>";
                                echo "</thead>";
                                echo "<tbody>";
                                while ($res = mysqli_fetch_array($result)) {


                                    echo "<tr>";
                                    echo "<td class='text-center'>" . strftime("%d-%m-%Y",strtotime($res['date'])). "</td>";
                                    echo "<td class='text-center'>" . $res['cname'] . "</td>";
                                    echo "<td class='text-center'>" . $res['lastdue'] . "</td>";
                                    echo "<td class='text-center'>" . $res['mpayment'] . "</td>";
                                    echo "<td class='text-center'>" . $res['discount'] . "</td>";
                                    echo "<td class='text-center'>" . $res['cdue'] . "</td>";
                                    echo "<td class='text-center'>" . $res['mnote'] . "</td>";
                                    echo "<td class='text-center'>" . $res['addedby'] . "</td>";
//                                echo "<td> <a href='manualhis.php?id=$res[mid]' onClick=\"return confirm('are you sure you want to delete payment for " . $res['cname'] . " on " . $res['date'] . " ??');\"><center>Delete</center></a>";
                                    echo "</tr>";


                                }
                                echo "</tbody>";
                            }
                            else {

                                $sres = mysqli_query($mysqli, "SELECT * FROM clientinfo WHERE cid='$client'");
                                $sq = mysqli_fetch_array($sres);

                                $snam = $sq['name'];
                                $sname = $sq['address'];
                                $smobile = $sq['mobile'];
                                $xyz = $client;

                                $lastd = mysqli_query($mysqli, "SELECT due FROM ledger WHERE client_id='$xyz' order by order_id desc limit 1 ");
                                $ree = mysqli_fetch_array($lastd);
                                if ($ree['due'] == null) {
                                    $ree['due'] = 0;
                                }

                                echo '<br>';
                                echo "<div class='center'>";
                                echo "<br />";
                                echo "Customer Name : ";
                                echo $snam;
                                echo "<br />";
                                echo "Mobile No : ";
                                echo $smobile;
                                echo "<br />";
                                echo "Address : ";
                                echo $sname;
                                echo "<br />";
                                echo "<br />";
                                echo "</div>";
                                echo "<br />";

//echo "<div class='cent'>";
//echo "Current Due : ";echo $ree['due'];
//echo "</div>";
//echo "<br />";echo "<br />";


                                $result = mysqli_query($mysqli, "SELECT * FROM manualp WHERE client_id='$client' ORDER BY date DESC");

                                echo "<thead>";
                                echo "<tr>";
                                echo "<th class='text-center'>Date</th>";
                                echo "<th class='text-center'>Client Name</th>";
                                echo "<th class='text-center'>Last Due</th>";
                                echo "<th class='text-center'>Manual Payment</th>";
                                echo "<th class='text-center'>Discount</th>";
                                echo "<th class='text-center'>Current Due</th>";
                                echo "<th class='text-center'>Note</th>";
                                echo "<th class='text-center'>Added By</th>";
                                echo "<th></th>";
                                echo "</tr>";
                                echo "</thead>";

                                echo "<tbody>";
                                while ($res = mysqli_fetch_array($result)) {

                                    echo "<tr>";
                                    echo "<td class='text-center'>" . strftime("%d-%m-%Y",strtotime($res['date']))  . "</td>";
                                    echo "<td class='text-center'>" . $res['cname'] . "</td>";
                                    echo "<td class='text-center'>" . $res['lastdue'] . "</td>";
                                    echo "<td class='text-center'>" . $res['mpayment'] . "</td>";
                                    echo "<td class='text-center'>" . $res['discount'] . "</td>";
                                    echo "<td class='text-center'>" . $res['cdue'] . "</td>";
                                    echo "<td class='text-center'>" . $res['mnote'] . "</td>";
                                    echo "<td class='text-center'>" . $res['addedby'] . "</td>";
                                    echo "<td class='text-center'> <a class='btn btn-outline-danger' href='manualhis.php?id=$res[mid]' onClick=\"return confirm('are you sure you want to delete payment for " . $res['cname'] . " on " . $res['date'] . " ??');\"><center>Delete</center></a>";
                                    echo "</tr>";


                                }
                                echo "</tbody>";
                            }
                        }


                        ?></div>
                </table>


<!--                <div id="bottom_anchor"></div>-->
            </div>

        </form>

            </div>
        </div>
    </div>
</div>
</body>
<script src="docsupport/jquery-3.2.1.min.js" type="text/javascript"></script>
<script src="chosen.jquery.js" type="text/javascript"></script>
<script src="docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
<script src="docsupport/init.js" type="text/javascript" charset="utf-8"></script>

<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
<script src="assets/js/libs/jquery-3.1.1.min.js"></script>
<script src="bootstrap/js/popper.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<script src="plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="assets/js/app.js"></script>

<script>
    $(document).ready(function () {
        App.init();
    });
</script>
<script src="assets/js/custom.js"></script>
<!-- END GLOBAL MANDATORY SCRIPTS -->
<script src="plugins/table/datatable/datatables.js"></script>
<script>
    $(document).ready(function () {
        $('#maintable').DataTable({
            "oLanguage": {
                "oPaginate": {
                    "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                    "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
                "sLengthMenu": "Results :  _MENU_",
            },
            "order": [[3, "desc"]],
            "stripeClasses": [],
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 7,
            drawCallback: function () {
                $('.dataTables_paginate > .pagination').addClass(' pagination-style-13 pagination-bordered mb-5');
            },
            scrollX: true
        });
    });

    function moveScroll() {
        var scroll = $(window).scrollTop();
        var anchor_top = $("#maintable").offset().top;
        var anchor_bottom = $("#bottom_anchor").offset().top;
        if (scroll > anchor_top && scroll < anchor_bottom) {
            clone_table = $("#clone");
            if (clone_table.length == 0) {
                clone_table = $("#maintable").clone();
                clone_table.attr('id', 'clone');
                clone_table.css({
                    position: 'fixed',
                    'pointer-events': 'none',
                    right: 72,
                    top: 0
                });
                clone_table.width($("#maintable").width());
                $("#table-container").append(clone_table);
                $("#clone").css({visibility: 'hidden'});
                $("#clone thead").css({visibility: 'visible'});
            }
        } else {
            $("#clone").remove();
        }
    }

    function test() {
        console.log($('#from_date').val());
    }

    function toggleSearch() {
        var x = document.getElementById("date_search");
        var y = document.getElementById("date_search_single");
        y.style.display = "none";
        var z = document.getElementById("lot_number_search_div");
        z.style.display = "none";
        if (x.style.display === "none") {
            x.style.display = "block";
            $('#search_flag').val(1);
        } else {
            x.style.display = "none";
            $('#search_flag').val(0);
        }
    }

    function toggleSearchSingle() {
        var y = document.getElementById("date_search");
        y.style.display = "none";
        var z = document.getElementById("lot_number_search_div");
        z.style.display = "none";
        var x = document.getElementById("date_search_single");
        if (x.style.display === "none") {
            x.style.display = "block";
            $('#search_flag').val(2);
        } else {
            x.style.display = "none";
            $('#search_flag').val(0);
        }

    }

    function toggleLotSearch() {
        var y = document.getElementById("date_search");
        y.style.display = "none";
        var z = document.getElementById("date_search_single");
        z.style.display = "none";
        var x = document.getElementById("lot_number_search_div");
        if (x.style.display === "none") {
            x.style.display = "block";
            $('#search_flag').val(3);
        } else {
            x.style.display = "none";
            $('#search_flag').val(0);
        }
    }

    $(window).scroll(moveScroll);</script>
</html>


<?php include 'footer.php'; ?>
</div>