<?php
session_start();
?>
<?php
require("db.php");
?>
<?php
$db=retrieveDb();
?>
<?php
if(isset($_GET['deleteRemittance'])){
	$update="delete from remittance where id='".$_GET['deleteRemittance']."'";
	$updateRS=$db->query($update);
	
	echo "Data deleted.";
}
if(isset($_GET['removeLogbook'])){
	$log_id=$_GET['removeLogbook'];
	$control_id=$_GET['control'];
	
	$update="delete from control_tracking where control_id='".$control_id."' and log_id='".$log_id."'";
	$updateRS=$db->query($update);
	
}

if(isset($_GET['track_date'])){
	$track_date=date("Y-m-d",strtotime($_GET['track_date']));
	$station=$_GET['station'];
	$revenue=$_GET['revenue'];
	$shift=$_GET['shift'];
	
	$control_id=$_GET['control'];
	
	$search="select * from logbook where date='".$track_date."' and station='".$station."' and revenue='".$revenue."' and shift='".$shift."' limit 1";
	$searchRS=$db->query($search);
	
	$searchNM=$searchRS->num_rows;
	
	if($searchNM>0){
		$searchRow=$searchRS->fetch_assoc();
		
		$count="select * from control_tracking where control_id='".$control_id."' and log_id='".$searchRow['id']."' limit 1";
		$countRS=$db->query($count);
		$countNM=$countRS->num_rows;
		
		if($countNM>0){
			echo "existing";
			
		}
		else {
			$update="insert into control_tracking(control_id,log_id) values ('".$control_id."','".$searchRow['id']."')";
			$updateRS=$db->query($update);
			
			echo "added";
		
		}
	
	
	}
	else {
		echo "none";
	
	}
	
	
}



?>
