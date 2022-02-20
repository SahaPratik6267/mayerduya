<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
<title>MayerDuya Shipment Management</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />


<style type="text/css">
	  
	  
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

	    </style>

		<div class="dropdown">
  <button class="dropbtn">Users</button>
  <div class="dropdown-content">
    <a href="users.php">Uses List</a>
   <a href="newuser.php">Add New</a>
   <a href="createinvoice.php">Create Invoice</a>
  </div>
</div>

<div class="dropdown">
  <button class="dropbtn">Customer</button>
  <div class="dropdown-content">
    <a href="gulistancustomer.php">Customer List(Gulistan)</a>
	<a href="motalibcustomer.php">Customer List(motalib)</a>
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
    <a href="lotbill.php">LOT DETAILS</a>
  </div>
</div>
<div class="dropdown">
  <button class="dropbtn">Back Account</button>
  <div class="dropdown-content">
    <a href="listacc.php">Account List</a>
    <a href="showtrans.php">Transactions</a>
    <a href="addtrans.php">Add Transactions</a>
	<a href="addacc.php">Add Account</a>
  </div>
</div>

