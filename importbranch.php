<?php
require_once __DIR__.'/SimpleXLSX.php';
require_once(dirname(__FILE__)."/simpleusers/su.inc.php");

	// Produce array keys from the array values of 1st array element
	echo '<h1>Parse books.xslx</h1><pre>';
if ( $xlsx = SimpleXLSX::parse('VVVVVVVVVVVVVVVVVVV.xlsx') ) {
	
		$header_values = $rows = [];

	foreach ( $xlsx->rows() as $k => $r ) {
		if ( $k === 0 ) {
			$header_values = $r;
			continue;
		}
		$rows[] = array_combine( $header_values, $r );
	}
	$customerid = array_column($rows, 'Sl No');
		
	// assign customer id to a branch
	foreach($customerid as $id){
		echo $id;
		echo "/n";
		
		$result = mysqli_query($mysqli, "UPDATE clientinfo SET branch_assigned=2 WHERE cid=$id");
		
	}
	 
	
	
	
	
	//print_r ($distinct_area ) ;
} else {
	echo SimpleXLSX::parseError();
}
echo '<pre>';
/*
Array
(
    [0] => Array
        (
            [ISBN] => 618260307
            [title] => The Hobbit
            [author] => J. R. R. Tolkien
            [publisher] => Houghton Mifflin
            [ctry] => USA
        )
    [1] => Array
        (
            [ISBN] => 908606664
            [title] => Slinky Malinki
            [author] => Lynley Dodd
            [publisher] => Mallinson Rendel
            [ctry] => NZ
        )
)
 */

?>