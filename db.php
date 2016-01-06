<?php
function retrieveDb(){

//$db=new mysqli("localhost","root","","finance_afcs");
	$db=new mysqli("localhost","root","","cubao");

	return $db;
}
function retrieveRecordDb(){


	$record_db=new mysqli("localhost","root","","transaction_history");
	return $record_db;
}

?>

