<?php
session_start();
?>
<?php
require("db.php");
?>
<?php
$log_id=$_SESSION['log_id'];
?>
<?php
$db=retrieveDb();
//$record_db=retrieveRecordDb();

if(isset($_GET['tID'])){
	

	
	
	
	$transaction_id=$_GET['tID'];
	$type=$_GET['type'];

	$sql="select * from transaction where transaction_id='".$transaction_id."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	
	$tID=$row['id'];
	
	if($type=="cash"){
		$transaction_type=$row['transaction_type'];
		$log_type=$row['log_type'];
		if($transaction_type=="deposit"){
			$deposit_sql="select * from pnb_deposit where transaction_id='".$transaction_id."'";
			$deposit_rs=$db->query($deposit_sql);	
			$deposit_nm=$deposit_rs->num_rows;
			
			for($i=0;$i<$deposit_nm;$i++){
				$deposit_row=$deposit_rs->fetch_assoc();
				
				$update="delete from pnb_deposit where id='".$deposit_row['id']."'";
				$updateRS=$db->query($update);
				
			}
			
		}
		else {
			$cash_sql="select * from cash_transfer where transaction_id='".$transaction_id."'";
			$cash_rs=$db->query($cash_sql);	
			$cash_row=$cash_rs->fetch_assoc();
			
			$cash_transfer_id=$cash_row['id'];
			
			$update="delete from denomination where cash_transfer_id='".$cash_transfer_id."'";
			$updateRS=$db->query($update);
			
			
			$update="delete from cash_transfer where id='".$cash_transfer_id."'";
			$updateRS=$db->query($update);
			
			
			$discrepancy_sql="select * from discrepancy where transaction_id='".$transaction_id."'";
			$discrepancy_rs=$db->query($discrepancy_sql);
			$discrepancy_nm=$discrepancy_rs->num_rows;
			
			if($discrepancy_nm>0){
				$update="delete from discrepancy where transaction_id='".$transaction_id."'";
				$updateRS=$db->query($update);
			
			}
			
			
			$record_date=date("Y-m-d H:i:s");
			
			$record_sql="insert into history(action,element,transaction_time,reference_id,log_id) ";
			$record_sql.=" values ";
			$record_sql.="('delete','cash transfer','".$record_date."','".$transaction_id."','".$log_id."')";
		//	$record_rs=$record_db->query($record_sql);
				
			$record_date=date("Y-m-d H:i:s");
			
			$record_sql="insert into history(action,element,transaction_time,reference_id,log_id) ";
			$record_sql.=" values ";
			$record_sql.="('delete','discrepancy','".$record_date."','".$transaction_id."','".$log_id."')";
		//	$record_rs=$record_db->query($record_sql);			
			
		}
		
		
		
		
		
		
		
		$update="delete from transaction where id='".$tID."'";
		$updateRS=$db->query($update);
		
	}
	else if($type=="ticket"){
		$transaction_type=$row['transaction_type'];
		$log_type=$row['log_type'];		
		
		if($log_type=="initial"){
			if($transaction_type=="allocation"){
				$remitSQL="select * from allocation where transaction_id='".$transaction_id."'";
				$remitRS=$db->query($remitSQL);
				$remitRow=$remitRS->fetch_assoc();

				$update="delete from remittance where control_id='".$remitRow['control_id']."'";
				$updateRS=$db->query($update);
				
				
				$update="delete from allocation where transaction_id='".$transaction_id."'";
				$updateRS=$db->query($update);
			
				$record_date=date("Y-m-d H:i:s");
				
				$record_sql="insert into history(action,element,transaction_time,reference_id,log_id) ";
				$record_sql.=" values ";
				$record_sql.="('delete','allocation - c. slip','".$record_date."','".$remitRow['control_id']."','".$log_id."')";
			//	$record_rs=$record_db->query($record_sql);			
			
			
			
			}
			else if($transaction_type=="remittance"){
				$remitSQL="select * from control_unsold where transaction_id='".$transaction_id."'";
				$remitRS=$db->query($remitSQL);
				$remitRow=$remitRS->fetch_assoc();


				$update="delete from remittance where control_id='".$remitRow['control_id']."'";
				$updateRS=$db->query($update);

				$update="delete from control_unsold where transaction_id='".$transaction_id."'";
				$updateRS=$db->query($update);
				
				$record_date=date("Y-m-d H:i:s");
				
				$record_sql="insert into history(action,element,transaction_time,reference_id,log_id) ";
				$record_sql.=" values ";
				$record_sql.="('delete','remittance - c. slip','".$record_date."','".$remitRow['control_id']."','".$log_id."')";
			//	$record_rs=$record_db->query($record_sql);			

			
			}

		}
		else {
			$ticket_sql="select * from ticket_order where transaction_id='".$transaction_id."'";
			$ticket_rs=$db->query($ticket_sql);
			
			$ticket_row=$ticket_rs->fetch_assoc();
			
			
			$update="delete from ticket_order where id='".$ticket_row['id']."'";
			$updateRS=$db->query($update);


			$discrepancy_sql="select * from discrepancy_ticket where transaction_id='".$transaction_id."'";
			$discrepancy_rs=$db->query($discrepancy_sql);
			$discrepancy_nm=$discrepancy_rs->num_rows;
			
			if($discrepancy_nm>0){
				$update="delete from discrepancy_ticket where transaction_id='".$transaction_id."'";
				$updateRS=$db->query($update);
			
			}
			$record_date=date("Y-m-d H:i:s");
				
			$record_sql="insert into history(action,element,transaction_time,reference_id,log_id) ";
			$record_sql.=" values ";
			$record_sql.="('delete','ticket order','".$record_date."','".$cash_transfer."','".$log_id."')";
//$record_rs=$record_db->query($record_sql);			


			
			
		}
		$update="delete from transaction where id='".$tID."'";
		$updateRS=$db->query($update);
		
	}	
	else if($type=="defective"){
		$defective_id=$transaction_id;
		$update="delete from physically_defective where id='".$defective_id."'";
		$updateRS=$db->query($update);

	}
}		
?>
<script language='javascript'>
window.opener.location.reload();
alert("Transaction deleted");

self.close();

</script>
