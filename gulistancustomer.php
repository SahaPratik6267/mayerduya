<?php

/**
 * Make sure you started your'e sessions!
 * You need to include su.inc.php to make SimpleUsers Work
 * After that, create an instance of SimpleUsers and your'e all set!
 */

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

$result = mysqli_query($mysqli, "SELECT * FROM clientinfo where branch_assigned=1 order by name asc");


include 'header.php';
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title></title>

        <style type="text/css">

            * {
                margin: 0px;
                padding: 0px;
            }

            body {
                padding: 30px;
                font-family: Calibri, Verdana, "Sans Serif";
                font-size: 18px;
            }

            table {
                width: 1200px;
                margin: 0px auto;
            }

            th, td {
                padding: 5px;
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


            .card {
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
                transition: 0.3s;
                width: 40%;
            }

            .card:hover {
                box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
            }

            .container {
                padding: 2px 16px;
            }

            h2 {
                color: #111;
                font-family: 'Helvetica Neue', sans-serif;
                font-size: 50px;
                font-weight: bold;
                letter-spacing: -1px;
                line-height: 1;
                text-align: center;
                border-bottom: 2px solid #000000;
            }
        </style>

    </head>
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
    <body>
    <div id="content" class="main-content">
        <div class="layout-px-spacing">
            <h2>List of Customer</h2>

            <a class="btn btn-success" href="addcus.php">Add New Customer</a><br/><br/>

            <?php
            $wholtotaldue = 0;
            $resulto = mysqli_query($mysqli, "SELECT * FROM clientinfo where branch_assigned=1");
            while ($rest = mysqli_fetch_array($resulto)) {
                $xyzz = $rest['cid'];
//$las =  mysqli_query($mysqli, "SELECT current_due FROM clientinfo WHERE cid='$xyzz' order by cid desc limit 1 ");

                if ($rest['current_due'] == null) {
                    $rest['current_due'] = 0;
                }
                $wholtotaldue = $rest['current_due'] + $wholtotaldue;
            }


            ?>
            <center>
                <div class="card">

                    <div class="container">
                        <h4><b><?php echo "Total Due  :" . $wholtotaldue . ""; ?> BDT</b></h4>
                    </div>
                </div>
            </center>
            <br/><br/>
            <div class="row layout-top-spacing" id="cancel-row">
                <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                    <div class="widget-content widget-content-area br-6">
                        <div class="loader multi-loader mx-auto" id="loader"></div>
                        <div class="table-responsive mb-4 mt-4">
                            <div id="table-container" style="display: none;">
                                <table class="table table-hover" id="maintable" width='70%' border=0>
                                    <thead>
                                    <tr>
                                        <th class="text-center" style="width: 50px;">Sl No</th>
                                        <th class="text-center" style="width: 50px;">Customer Name</th>
                                        <th class="text-center" style="width: 50px;">Company</th>
                                        <th class="text-center" style="width: 50px;">Address</th>
                                        <th class="text-center" style="width: 50px;">Mobile</th>
                                        <th class="text-center" style="width: 50px;">Email</th>
                                        <th class="text-center" style="width: 50px;">Area</th>
                                        <th class="text-center" style="width: 50px;">Due amount</th>

                                        <th class="text-center" style="width: 50px;">Note on Customer</th>
                                        <th class="text-center" style="width: 50px;">Edit</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $wholetotaldue = 0;

                                    while ($res = mysqli_fetch_array($result)) {

                                        $xyz = $res['cid'];
                                        $lastd = mysqli_query($mysqli, "SELECT due FROM ledger WHERE client_id='$xyz' order by order_id desc limit 1 ");
                                        $ree = mysqli_fetch_array($lastd);
                                        if ($ree['due'] == null) {
                                            $ree['due'] = 0;
                                        }
                                        $wholetotaldue = $ree['due'] + $wholetotaldue;

                                        echo "<tr>";
                                        echo "<td>" . $res['cid'] . "</td>";
                                        echo "<td>" . $res['name'] . "</td>";
                                        echo "<td>" . $res['company'] . "</td>";
                                        echo "<td>" . $res['address'] . "</td>";
                                        echo "<td>" . $res['mobile'] . "</td>";
                                        echo "<td>" . $res['email'] . "</td>";
                                        echo "<td>" . $res['area'] . "</td>";
                                        echo "<td>" . $res['current_due'] . "</td>";
                                        //echo "<td>".$ree['due']."</td>";
                                        echo "<td>" . $res['cnote'] . "</td>";
                                        echo "<td><a class='btn btn-outline-info' href=\"editcus.php?id=$res[cid]\">Edit</a></td>";
                                        echo "</tr>";

                                    }


                                    ?>
                                    </tbody>

                                </table>

                                <div id="bottom_anchor"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


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


    <script src="docsupport/jquery-3.2.1.min.js" type="text/javascript"></script>
    <script src="chosen.jquery.js" type="text/javascript"></script>
    <script src="docsupport/prism.js" type="text/javascript" charset="utf-8"></script>
    <script src="docsupport/init.js" type="text/javascript" charset="utf-8"></script>
    <script src="plugins/table/datatable/datatables.js"></script>
    <script>
        $(document).ready(function () {
            $('#loader').hide();
            $('#table-container').show();
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
                        right: -329,
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

        $(window).scroll(moveScroll);</script>

    </body>
    </html>
<?php include 'footer.php'; ?>