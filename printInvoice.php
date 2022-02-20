
<html>
<head>
    <link rel="stylesheet" href="style.css">
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



        #red {

            margin-left: 400px;

        }

    </style>
    <style type="text/css" media="print">

        #SCREEN_VIEW_CONTAINER{
            display: none;
        }
    </style>

    <div id="SCREEN_VIEW_CONTAINER">
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
        $id = $_GET['id'];
        if($id==null){echo "please choose an invoice";}
        else{
            $resled = mysqli_query($mysqli, "SELECT * FROM ledger WHERE invoice_no=$id");

            $re = mysqli_fetch_array($resled);
            $getclient= $re['client_id'];
            $getorderid=$re['order_id'];
            $invoiceno=$id;
            $date= $re['date'];
            $shipment=$re['shipment'];
            $lotno=$re['lot_no'];
            $paid=$re['paid'];
            $due=$re['due'];




            $client=mysqli_query($mysqli, "SELECT * FROM clientinfo WHERE cid='$getclient'");
            $clientnme = mysqli_fetch_array($client);
            $customername=$clientnme['name'];
            $customerno= $clientnme['mobile'];
            $customeradd = $clientnme['address'];
            $getlnote=$re['lnote'];


            $resitem = mysqli_query($mysqli, "SELECT * FROM items WHERE invoice_no=$id");
            $i=0;
            while ($getitems = mysqli_fetch_array($resitem)){
                $item[$i]=$getitems['item'];
                $cartonno[$i]=$getitems['carton'];
                $weight[$i]=$getitems['weight_pcs'];
                $rate[$i]=$getitems['rate'];
                $i++;
            }
            $getcorrectdue= mysqli_query($mysqli, "SELECT due FROM ledger WHERE client_id='$getclient' AND order_id<'$getorderid' order by order_id DESC LIMIT 1");
            $gettdue=mysqli_fetch_array($getcorrectdue);
            $getdue=$gettdue['due'];


        }
        //include 'header.php';
        ?>
    </div>
</head>

<br /><br />
<body>
<div id="PRINT_VIEW">
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>

                            <center> <img src="https://i.imgur.com/QbdBJDz.png"  height="50px";width="150px";></center>

                        </tr>
                        <tr>
                            <td align="left">
                                INVOICE NO:	<?PHP echo $invoiceno; ?>
                            </td>


                            <td align="center">
                                DATE:  <?PHP echo $date; ?>
                            </td>

                            <td align="right">
                                SHIPMENT: <?PHP echo $shipment."--".$lotno; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <h4 align="left">Customer Details</h4>
                            <td align="left">
                                <?PHP echo $customername; ?>.<br>
                                <?PHP echo $customerno; ?><br>
                                <?PHP echo $customeradd; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>



        <table class="blueTable">
            <tr class="heading">
                <td>
                    SL
                </td>
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
                    1
                </td>
                <td>
                    <?php echo $item[0];?>
                </td>
                <td>
                    <?php echo $cartonno[0]; ?>
                </td>
                <td>
                    <?php echo $weight[0]; ?>
                </td>
                <td>
                    <?php echo $rate[0]; ?>
                </td>
                <td>
                    <?php echo $tot[0]=$rate[0]*$weight[0]; ?>

                </td>
            </tr>
            <tr class="item">
                <td>
                    2
                </td>
                <td>
                    <?php echo $item[1];?>
                </td>
                <td>
                    <?php echo $cartonno[1]; ?>
                </td>
                <td>
                    <?php echo $weight[1]; ?>
                </td>
                <td>
                    <?php echo $rate[1]; ?>
                </td>
                <td>
                    <?php echo $tot[1]=$rate[1]*$weight[1]; ?>

                </td>
            </tr>
            <tr class="item">
                <td>
                    3
                </td>
                <td>
                    <?php echo $item[2];?>
                </td>
                <td>
                    <?php echo $cartonno[2]; ?>
                </td>
                <td>
                    <?php echo $weight[2]; ?>
                </td>
                <td>
                    <?php echo $rate[2]; ?>
                </td>
                <td>
                    <?php echo $tot[2]=$rate[2]*$weight[2]; ?>

                </td>
            </tr>
            <tr class="item">
                <td>
                    4
                </td>
                <td>
                    <?php echo $item[3];?>
                </td>
                <td>
                    <?php echo $cartonno[3]; ?>
                </td>
                <td>
                    <?php echo $weight[3]; ?>
                </td>
                <td>
                    <?php echo $rate[3]; ?>
                </td>
                <td>
                    <?php echo $tot[3]=$rate[3]*$weight[3]; ?>

                </td>
            </tr>
            <tr class="item">
                <td>
                    5
                </td>
                <td>
                    <?php echo $item[4];?>
                </td>
                <td>
                    <?php echo $cartonno[4]; ?>
                </td>
                <td>
                    <?php echo $weight[4]; ?>
                </td>
                <td>
                    <?php echo $rate[4]; ?>
                </td>
                <td>
                    <?php echo $tot[4]=$rate[4]*$weight[4]; ?>

                </td>
            </tr>
            <tr class="item">
                <td>
                    6
                </td>
                <td>
                    <?php echo $item[5];?>
                </td>
                <td>
                    <?php echo $cartonno[5]; ?>
                </td>
                <td>
                    <?php echo $weight[5]; ?>
                </td>
                <td>
                    <?php echo $rate[5]; ?>
                </td>
                <td>
                    <?php echo $tot[5]=$rate[5]*$weight[5]; ?>

                </td>
            </tr>
            <tr class="item">
                <td>
                    7
                </td>
                <td>
                    <?php echo $item[6];?>
                </td>
                <td>
                    <?php echo $cartonno[6]; ?>
                </td>
                <td>
                    <?php echo $weight[6]; ?>
                </td>
                <td>
                    <?php echo $rate[6]; ?>
                </td>
                <td>
                    <?php echo $tot[6]=$rate[6]*$weight[6]; ?>

                </td>
            </tr>
            <tr class="item">
                <td>
                    8
                </td>
                <td>
                    <?php echo $item[7];?>
                </td>
                <td>
                    <?php echo $cartonno[7]; ?>
                </td>
                <td>
                    <?php echo $weight[7]; ?>
                </td>
                <td>
                    <?php echo $rate[7]; ?>
                </td>
                <td>
                    <?php echo $tot[7]=$rate[7]*$weight[7]; ?>

                </td>
            </tr>
            <tr class="item">
                <td>
                    9
                </td>
                <td>
                    <?php echo $item[8];?>
                </td>
                <td>
                    <?php echo $cartonno[8]; ?>
                </td>
                <td>
                    <?php echo $weight[8]; ?>
                </td>
                <td>
                    <?php echo $rate[8]; ?>
                </td>
                <td>
                    <?php echo $tot[8]=$rate[8]*$weight[8]; ?>

                </td>
            </tr>
            <tr class="item">
                <td>
                    10
                </td>
                <td>
                    <?php echo $item[9];?>
                </td>
                <td>
                    <?php echo $cartonno[9]; ?>
                </td>
                <td>
                    <?php echo $weight[9]; ?>
                </td>
                <td>
                    <?php echo $rate[9]; ?>
                </td>
                <td>
                    <?php echo $tot[9]=$rate[9]*$weight[9]; ?>

                </td>
            </tr>
            <tr class="item">
                <td>
                    11
                </td>
                <td>
                    <?php echo $item[10];?>
                </td>
                <td>
                    <?php echo $cartonno[10]; ?>
                </td>
                <td>
                    <?php echo $weight[10]; ?>
                </td>
                <td>
                    <?php echo $rate[10]; ?>
                </td>
                <td>
                    <?php echo $tot[10]=$rate[10]*$weight[10]; ?>

                </td>
            </tr>
            <tr class="item">
                <td>
                    12
                </td>
                <td>
                    <?php echo $item[11];?>
                </td>
                <td>
                    <?php echo $cartonno[11]; ?>
                </td>
                <td>
                    <?php echo $weight[11]; ?>
                </td>
                <td>
                    <?php echo $rate[11]; ?>
                </td>
                <td>
                    <?php echo $tot[11]=$rate[11]*$weight[11]; ?>

                </td>
            </tr>
            <tr class="item">
                <td>
                    13
                </td>
                <td>
                    <?php echo $item[12];?>
                </td>
                <td>
                    <?php echo $cartonno[12]; ?>
                </td>
                <td>
                    <?php echo $weight[12]; ?>
                </td>
                <td>
                    <?php echo $rate[12]; ?>
                </td>
                <td>
                    <?php echo $tot[12]=$rate[12]*$weight[12]; ?>

                </td>
            </tr>
            <tr class="item">
                <td>
                    14
                </td>
                <td>
                    <?php echo $item[13];?>
                </td>
                <td>
                    <?php echo $cartonno[13]; ?>
                </td>
                <td>
                    <?php echo $weight[13]; ?>
                </td>
                <td>
                    <?php echo $rate[13]; ?>
                </td>
                <td>
                    <?php echo $tot[13]=$rate[13]*$weight[13]; ?>

                </td>
            </tr>
            <tr class="item">
                <td>
                    15
                </td>
                <td>
                    <?php echo $item[14];?>
                </td>
                <td>
                    <?php echo $cartonno[14]; ?>
                </td>
                <td>
                    <?php echo $weight[14]; ?>
                </td>
                <td>
                    <?php echo $rate[14]; ?>
                </td>
                <td>
                    <?php echo $tot[14]=$rate[14]*$weight[14]; ?>

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
                        echo $subtot." /=";
                        ?>

                    </td>
                </tr>


                <tr >
                    <td>
                        B/F
                    </td>

                    <td>
                        <?php echo $due-$subtot." /="; ?>
                    </td>
                </tr>


<!--                <tr >-->
<!--                    <td>-->
<!--                        Total-->
<!--                    </td>-->
<!---->
<!--                    <td>-->
<!--                        --><?php
//                        echo $total =$getdue+$subtot." /=";
//                        ?>
<!---->
<!--                    </td>-->
<!--                </tr>-->
                <tr>
                    <td>
                        Paid Amount
                    </td>
<!---->
<!--                    <td>-->
<!--                        --><?php //echo $paid." /="; ?>
<!--                    </td>-->
                </tr>

                <tr >
                    <td>
                        Due
                    </td>
<!---->
<!--                    <td>-->
<!--                        --><?php //echo $due. "/="; ?>
<!--                    </td>-->
                </tr>

                <tr>
                    <td>
                        Note
                    </td>

                    <td>
                        <?php echo $getlnote; ?>
                    </td>
                    <td>




                </tr>
            </table><br /><br />
            <center><tr>
                    <td>THANK YOU FOR YOUR BUSINESS WITH US. </td>

                </tr></center>
        </table>

    </div>
</div>


</body>
</html>