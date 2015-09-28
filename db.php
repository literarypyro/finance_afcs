<?php
function retrieveDb(){

	$db=new mysqli("localhost","root","Colossus0611","finance_afcs");

	return $db;
}
function retrieveRecordDb(){


	$record_db=new mysqli("localhost","root","Colossus0611","transaction_history");
	return $record_db;
}

?>

