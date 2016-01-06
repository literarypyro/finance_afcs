<?php
session_start();
?>
<?php
require("db.php");
?>
<?php
$log_id=$_SESSION['log_id'];
$start = microtime(true);

?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
if(isset($_GET['control_log'])){
	$log_id=$_GET['control_log'];


}
$db=retrieveDb();
//$record_db=new mysqli("localhost","root","","transaction_history");

if(isset($_POST['change_control_id'])){
	$ticket_seller=$_POST['ticket_seller_change'];
	$control_id=$_POST['change_control_id'];
	$station=$_POST['station'];
	$unit=$_POST['unit'];
	
	$update="update control_slip set ticket_seller='".$ticket_seller."', unit='".$unit."', station='".$station."' where id='".$control_id."'";

	$updateRS=$db->query($update);

	$record_date=date("Y-m-d H:i:s");
				
	$record_sql="insert into history(action,element,transaction_time,reference_id,log_id) ";
	$record_sql.=" values ";
	$record_sql.="('update','change user - c. slip','".$record_date."','".$control_id."','".$log_id."')";
	//$record_rs=$record_db->query($record_sql);		
	
	
$_SESSION['unit']=$unit;
$_SESSION['ticket_seller']=$ticket_seller;	
	
}

if(isset($_POST['amount_control_id_1'])){
	if($_POST['ticket_amount_type']=='svc'){
		$bpi_amount=$_POST['bpi_amount'];
		$concessionary_amount=$_POST['concessionary_amount'];
		$smart_amount=$_POST['smart_amount'];
		$globe_amount=$_POST['globe_amount'];

		$issuance_amount=$_POST['issuance_amount'];
		$add_value_amount=$_POST['add_value_amount'];	

		$control_id=$_POST['amount_control_id_1'];

		$update="";

		$sql="select * from control_sales_amount where control_id='".$control_id."' and ticket_type='svc' and value_type='issuance_fee'";
		$rs=$db->query($sql);

		$nm=$rs->num_rows;

		if($nm>0){

			$row=$rs->fetch_assoc();
			$update="update control_sales_amount set amount='".$issuance_amount."' where id='".$row['id']."'";
			$updateRS=$db->query($update);



		}
		else {
			$update="insert into control_sales_amount(control_id,source_type,ticket_type,value_type,amount) ";
			$update.="values ('".$control_id."','pos','svc','issuance_fee','".$issuance_amount."')";
			$updateRS=$db->query($update);


		}

		$sql="select * from control_sales_amount where control_id='".$control_id."' and ticket_type='svc' and value_type='bpi'";
		$rs=$db->query($sql);

		$nm=$rs->num_rows;

		if($nm>0){

			$row=$rs->fetch_assoc();
			$update="update control_sales_amount set amount='".$bpi_amount."' where id='".$row['id']."'";
			$updateRS=$db->query($update);



		}
		else {
			$update="insert into control_sales_amount(control_id,source_type,ticket_type,value_type,amount) ";
			$update.="values ('".$control_id."','pos','svc','bpi','".$bpi_amount."')";
			$updateRS=$db->query($update);


		}

		$sql="select * from control_sales_amount where control_id='".$control_id."' and ticket_type='svc' and value_type='smart'";
		$rs=$db->query($sql);

		$nm=$rs->num_rows;

		if($nm>0){

			$row=$rs->fetch_assoc();
			$update="update control_sales_amount set amount='".$smart_amount."' where id='".$row['id']."'";
			$updateRS=$db->query($update);



		}
		else {
			$update="insert into control_sales_amount(control_id,source_type,ticket_type,value_type,amount) ";
			$update.="values ('".$control_id."','pos','svc','smart','".$smart_amount."')";
			$updateRS=$db->query($update);


		}

		$sql="select * from control_sales_amount where control_id='".$control_id."' and ticket_type='svc' and value_type='svc_issuance_fee'";
		$rs=$db->query($sql);

		$nm=$rs->num_rows;

		if($nm>0){

			$row=$rs->fetch_assoc();
			$update="update control_sales_amount set amount='".$globe_amount."' where id='".$row['id']."'";
			$updateRS=$db->query($update);



		}
		else {
			$update="insert into control_sales_amount(control_id,source_type,ticket_type,value_type,amount) ";
			$update.="values ('".$control_id."','pos','svc','globe','".$globe_amount."')";
			$updateRS=$db->query($update);


		}

		$sql="select * from control_sales_amount where control_id='".$control_id."' and ticket_type='svc' and value_type='concessionary'";
		$rs=$db->query($sql);

		$nm=$rs->num_rows;

		if($nm>0){

			$row=$rs->fetch_assoc();
			$update="update control_sales_amount set amount='".$concessionary_amount."' where id='".$row['id']."'";
			$updateRS=$db->query($update);



		}
		else {
			$update="insert into control_sales_amount(control_id,source_type,ticket_type,value_type,amount) ";
			$update.="values ('".$control_id."','pos','svc','concessionary','".$concessionary_amount."')";
			$updateRS=$db->query($update);


		}

		$sql="select * from control_sales_amount where control_id='".$control_id."' and ticket_type='svc' and value_type='add_value'";
		$rs=$db->query($sql);

		$nm=$rs->num_rows;

		if($nm>0){

			$row=$rs->fetch_assoc();
			$update="update control_sales_amount set amount='".$add_value_amount."' where id='".$row['id']."'";
			$updateRS=$db->query($update);



		}
		else {
			$update="insert into control_sales_amount(control_id,source_type,ticket_type,value_type,amount) ";
			$update.="values ('".$control_id."','pos','svc','add_value','".$add_value_amount."')";
			$updateRS=$db->query($update);


		}


	}
	else if($_POST['ticket_amount_type']=='sjt'){
		$sjt_amount=$_POST['regular_amount'];
		$sjd_amount=$_POST['discounted_amount'];
		$pwd_amount=$_POST['pwd_amount'];

		$control_id=$_POST['amount_control_id_1'];
		
		$sql="select * from control_sales_amount where control_id='".$control_id."' and ticket_type='sjt' and value_type='reg'";
		$rs=$db->query($sql);

		$nm=$rs->num_rows;

		if($nm>0){

			$row=$rs->fetch_assoc();
			$update="update control_sales_amount set amount='".$sjt_amount."' where id='".$row['id']."'";
			$updateRS=$db->query($update);



		}
		else {
			$update="insert into control_sales_amount(control_id,source_type,ticket_type,value_type,amount) ";
			$update.="values ('".$control_id."','pos','sjt','reg','".$sjt_amount."')";
			$updateRS=$db->query($update);

		}


		$sql="select * from control_sales_amount where control_id='".$control_id."' and ticket_type='sjt' and value_type='disc'";
		$rs=$db->query($sql);

		$nm=$rs->num_rows;

		if($nm>0){

			$row=$rs->fetch_assoc();
			$update="update control_sales_amount set amount='".$sjd_amount."' where id='".$row['id']."'";
			$updateRS=$db->query($update);



		}
		else {
			$update="insert into control_sales_amount(control_id,source_type,ticket_type,value_type,amount) ";
			$update.="values ('".$control_id."','pos','sjt','disc','".$sjd_amount."')";
			$updateRS=$db->query($update);


		}


		$sql="select * from control_sales_amount where control_id='".$control_id."' and ticket_type='sjt' and value_type='pwd'";
		$rs=$db->query($sql);

		$nm=$rs->num_rows;

		if($nm>0){

			$row=$rs->fetch_assoc();
			$update="update control_sales_amount set amount='".$pwd_amount."' where id='".$row['id']."'";
			$updateRS=$db->query($update);



		}
		else {
			$update="insert into control_sales_amount(control_id,source_type,ticket_type,value_type,amount) ";
			$update.="values ('".$control_id."','pos','sjt','pwd','".$pwd_amount."')";
			$updateRS=$db->query($update);


		}



	}


}

if(isset($_POST['sold_control_id'])){
	


	if($_POST['ticket_sold_type']=='sjt'){


		$sjt_sold_1=$_POST['regular_sold'];
		$sjd_sold_1=$_POST['discounted_sold'];
		$pwd_sold_1=$_POST['pwd_sold'];

		$control_id=$_POST['sold_control_id'];

		$sql="select * from control_sold where control_id='".$control_id."' and ticket_type='sjt' and value_type='reg'";
		$rs=$db->query($sql);

		$nm=$rs->num_rows;

		if($nm>0){

			$row=$rs->fetch_assoc();
			$update="update control_sold set quantity='".$sjt_sold_1."' where id='".$row['id']."'";
			$updateRS=$db->query($update);



		}
		else {
			$update="insert into control_sold(control_id,source_type,ticket_type,value_type,quantity) ";
			$update.="values ('".$control_id."','pos','sjt','reg','".$sjt_sold_1."')";
			$updateRS=$db->query($update);


		}





		$sql="select * from control_sold where control_id='".$control_id."' and ticket_type='sjt' and value_type='disc'";
		$rs=$db->query($sql);

		$nm=$rs->num_rows;

		if($nm>0){

			$row=$rs->fetch_assoc();
			$update="update control_sold set quantity='".$sjd_sold_1."' where id='".$row['id']."'";
			$updateRS=$db->query($update);



		}
		else {
			$update="insert into control_sold(control_id,source_type,ticket_type,value_type,quantity) ";
			$update.="values ('".$control_id."','pos','sjt','disc','".$sjd_sold_1."')";
			$updateRS=$db->query($update);



		}




		$sql="select * from control_sold where control_id='".$control_id."' and ticket_type='sjt' and value_type='pwd'";

		$rs=$db->query($sql);

		$nm=$rs->num_rows;

		if($nm>0){

			$row=$rs->fetch_assoc();
			$update="update control_sold set quantity='".$pwd_sold_1."' where id='".$row['id']."'";
			$updateRS=$db->query($update);



		}
		else {
			$update="insert into control_sold(control_id,source_type,ticket_type,value_type,quantity) ";
			$update.="values ('".$control_id."','pos','sjt','pwd','".$pwd_sold_1."')";
			$updateRS=$db->query($update);



		}



	}
	
	else if($_POST['ticket_sold_type']=='svc'){
		$svc_sold_1=$_POST['regular_sold'];
		$control_id=$_POST['sold_control_id'];

		$sql="select * from control_sold where control_id='".$control_id."' and ticket_type='svc' and value_type='reg'";

		$rs=$db->query($sql);

		$nm=$rs->num_rows;

		if($nm>0){

			$row=$rs->fetch_assoc();
			$update="update control_sold set quantity='".$svc_sold_1."' where id='".$row['id']."'";
			$updateRS=$db->query($update);



		}
		else {
			$update="insert into control_sold(control_id,source_type,ticket_type,value_type,quantity) ";
			$update.="values ('".$control_id."','pos','svc','reg','".$svc_sold_1."')";
			$updateRS=$db->query($update);



		}


	}
}


if(isset($_POST['ticket_seller_control'])){

$ticket_seller=$_POST['ticket_seller_control'];
$unit=$_POST['unit'];
$station=$_POST['station'];

$_SESSION['unit']=$unit;
$_SESSION['ticket_seller']=$ticket_seller;

$sql="select * from control_slip where ticket_seller='".$ticket_seller."' and unit='".$unit."' and station='".$station."' and status='open' order by id desc";
$rs=$db->query($sql);
$nm=$rs->num_rows;

$control_id="";
	if($nm>0){
		$row=$rs->fetch_assoc();
		$control_id=$row['id'];
		$_SESSION['control_id']=$control_id;

	}

	else if($nm==0) {
		$insert="insert into control_slip(log_id,ticket_seller,unit,station,status) values ('".$log_id."','".$ticket_seller."','".$unit."','".$station."','open')";
		$rsInsert=$db->query($insert);
		$control_id=$db->insert_id;
		$_SESSION['control_id']=$control_id;
		
	}
	
	
$sql="select * from control_tracking where control_id='".$control_id."' and log_id='".$log_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm==0){
	$update="insert into control_tracking(control_id,log_id) values ('".$control_id."','".$log_id."')";
	$updateRS=$db->query($update);
}
	
	$record_date=date("Y-m-d H:i:s");
				
	$record_sql="insert into history(action,element,transaction_time,reference_id,log_id) ";
	$record_sql.=" values ";
	$record_sql.="('insert','c. slip','".$record_date."','".$control_id."','".$log_id."')";
//	$record_rs=$record_db->query($record_sql);		
	

}
if(isset($_GET['edit_control'])){
	$control_id=$_GET['edit_control'];
	$sql="select * from control_slip where id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	$row=$rs->fetch_assoc();
	$unit=$row['unit'];
	$ticket_seller=$row['ticket_seller'];
	
	$_SESSION['control_id']=$control_id;
	
	$_SESSION['unit']=$unit;
	$_SESSION['ticket_seller']=$ticket_seller;	
	
	$update="update control_slip set status='open' where id='".$control_id."'";
	$updateRS=$db->query($update);
	
	$sql="select * from control_tracking where control_id='".$control_id."' and log_id='".$log_id."'";
	
	$rs=$db->query($sql);
	$nm=$rs->num_rows;

	if($nm==0){
		$update="insert into control_tracking(control_id,log_id) values ('".$control_id."','".$log_id."')";
		$updateRS=$db->query($update);
	}	
	
	
	
	
}

if(isset($_POST['adjustment_control_id_a'])){
	$control_id=$_POST['adjustment_control_id_a'];

	$db=retrieveDb();
	
//	$fare_adjustment=$_POST['adjustment_1'];
	$sjt_adjustment=$_POST['adjustment_2'];
	$sjd_adjustment=$_POST['adjustment_3'];
	$pwd_adjustment=$_POST['adjustment_4'];
	$c_adjustment=$_POST['adjustment_5'];
	$ot_adjustment=$_POST['adjustment_6'];
	$mismatch_adjustment=$_POST['adjustment_7'];

	$sjt_adjustment_t=$_POST['adjustment_tickets_2'];
	$sjd_adjustment_t=$_POST['adjustment_tickets_3'];
	$pwd_adjustment_t=$_POST['adjustment_tickets_4'];
	$c_adjustment_t=$_POST['adjustment_tickets_5'];
	$ot_adjustment_t=$_POST['adjustment_tickets_6'];
	$mismatch_adjustment_t=$_POST['adjustment_tickets_7'];


	
	$sql="select * from fare_adjustment where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm==0){
		$sql="insert into fare_adjustment(control_id,sjt,sjd,pwd,mismatch,c,ot) values ";
		$sql.="('".$control_id."','".$sjt_adjustment."','".$sjd_adjustment."','".$pwd_adjustment."','".$mismatch_adjustment."','".$c_adjustment."','".$ot_adjustment."')";
		$rs=$db->query($sql);

	}	
	else {
		$sql="update fare_adjustment set c='".$c_adjustment."',ot='".$ot_adjustment."',mismatch='".$mismatch_adjustment."',sjt='".$sjt_adjustment."',sjd='".$sjd_adjustment."',pwd='".$pwd_adjustment."' where control_id='".$control_id."'";
		$rs=$db->query($sql);	
	
	
	}

	$sql="select * from fare_adjustment_tickets where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm==0){
		$sql="insert into fare_adjustment_tickets(control_id,sjt,sjd,pwd,mismatch,c,ot) values ";
		$sql.="('".$control_id."','".$sjt_adjustment_t."','".$sjd_adjustment_t."','".$pwd_adjustment_t."','".$mismatch_adjustment_t."','".$c_adjustment_t."','".$ot_adjustment_t."')";
		$rs=$db->query($sql);

	}	
	else {
		$sql="update fare_adjustment_tickets set c='".$c_adjustment_t."',ot='".$ot_adjustment_t."',sjt='".$sjt_adjustment_t."',sjd='".$sjd_adjustment_t."',pwd='".$pwd_adjustment_t."',mismatch='".$mismatch_adjustment_t."' where control_id='".$control_id."'";
		$rs=$db->query($sql);	
	}


	$_SESSION['control_id']=$control_id;

}
if(isset($_POST['adjustments_2_control_id'])){
	$control_id=$_POST['adjustments_2_control_id'];
	$db=retrieveDb();

	if($_POST['total_remittance']>0){
	
//	$add_others=$_POST['addition_3'];
//	$refund=$_POST['deduction_1'];
//	$discount=$_POST['deduction_3'];

	$cash_advance=$_POST['addition_1'];
	$overage=$_POST['addition_2'];
	$unpaid_shortage=$_POST['deduction_2'];
	
	$ot_amount=$_POST['addition_3'];
	
	$refund_sj=$_POST['refund_sj'];
	$refund_sv=$_POST['refund_sv'];	
	
	$refund_sj_amount=$_POST['refund_sj_amount'];
	$refund_sv_amount=$_POST['refund_sv_amount'];	

	$svc_cash_value=$_POST['svc_cash_value'];
	
	
	$unreg_sj=$_POST['unreg_sj'];
	$unreg_sv=$_POST['unreg_sv'];
	
	$discount_sj=$_POST['discount_sj'];
	$discount_sv=$_POST['discount_sv'];	

	
	$sql="select * from control_slip where id='".$control_id."'";
	$rs=$db->query($sql);
	$row=$rs->fetch_assoc();
	
	$ticket_seller=$row['ticket_seller'];
	
	$sql="select * from refund where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm==0){
		$update="insert into refund(control_id,sj,sv,sj_amount,sv_amount) values ('".$control_id."','".$refund_sj."','".$refund_sv."','".$refund_sj_amount."','".$refund_sv_amount."')";
		$updateRS=$db->query($update);

	}
	else {
		$row=$rs->fetch_assoc();
		$update="update refund set sj='".$refund_sj."',sv='".$refund_sv."',sj_amount='".$refund_sj_amount."',sv_amount='".$refund_sv_amount."' where id='".$row['id']."'";
		$updateRS=$db->query($update);
	}	
	
	
	$sql="select * from unreg_sale where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm==0){
		$update="insert into unreg_sale(control_id,sj,sv,issuance_fee) values ('".$control_id."','".$unreg_sj."','".$unreg_sv."','".$_POST['issuance_unreg']."')";
		$updateRS=$db->query($update);
	}
	else {
		$row=$rs->fetch_assoc();
		$update="update unreg_sale set sj='".$unreg_sj."',sv='".$unreg_sv."',issuance_fee='".$_POST['issuance_unreg']."' where id='".$row['id']."'";
		$updateRS=$db->query($update);
	}
	

	$sql="select * from discount where control_id='".$control_id."'";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm==0){
		$update="insert into discount(control_id,sj,sv) values ('".$control_id."','".$discount_sj."','".$discount_sv."')";
		$updateRS=$db->query($update);
	}
	else {
		$row=$rs->fetch_assoc();
		$update="update discount set sj='".$discount_sj."',sv='".$discount_sv."' where id='".$row['id']."'";
		$updateRS=$db->query($update);

	}

	$sql="select * from control_cash where control_id='".$control_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm==0){
		$sql="insert into control_cash(control_id,unpaid_shortage,overage,cash_advance,svc_value) values ";
		$sql.="('".$control_id."','".$unpaid_shortage."','".$overage."','".$cash_advance."','".$svc_cash_value."')";
		$rs=$db->query($sql);
	}	
	else {
		$sql="update control_cash set unpaid_shortage='".$unpaid_shortage."',overage='".$overage."',cash_advance='".$cash_advance."',svc_value='".$svc_cash_value."' where control_id='".$control_id."'";
		$rs=$db->query($sql);	
	}	

	$_SESSION['control_id']=$control_id;
	
	$referenceSQL="select * from control_slip where id='".$control_id."'";
	$referenceRS=$db->query($referenceSQL);
	$referenceRow=$referenceRS->fetch_assoc();
	$reference_id=$referenceRow['reference_id'];
	

	
	$sql="select * from cash_remittance where log_id='".$log_id."' and ticket_seller='".$ticket_seller."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	$total_remittance=$_POST['total_remittance'];
	
	if($nm>0){
		$row=$rs->fetch_assoc();
		$update="update cash_remittance set control_remittance='".$total_remittance."' where id='".$row['id']."'";
		$rs2=$db->query($update);
		
			
	}
	else {
		/*
		$date=date("Y-m-d H:i");
		$date_id=date("Ymd",strtotime($date));
		
		$update="insert into transaction(date,log_id,log_type,transaction_type,reference_id) ";
		$update.="values ('".$date."','".$log_id."','control','remittance','".$reference_id."')";
		$rs2=$db->query($update);
					
		$insert_id=$db->insert_id;
		
		$transaction_id=$date_id."_".$insert_id;		
		
		$update="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
		$rs2=$db->query($update);		
		*/
		/*
		$sql="insert into cash_transfer(log_id,time,ticket_seller,cash_assistant,type,";
		$sql.="transaction_id,total_in_words,total,net_revenue,station,reference_id) values ";
		$sql.="('".$log_id."','".$date."','".$ticket_seller."','".$_POST['cash_assistant']."','".$type."',";
		$sql.="'".$transaction_id."','".$totalWords."','".$revolving."','".$net."','".$station_entry."','".$reference_id."')";
	
		*/
	
		$update="insert into cash_remittance(log_id,ticket_seller,control_remittance) values ";
		$update.="('".$log_id."','".$ticket_seller."','".$total_remittance."')";
		$rs2=$db->query($update);

	}
//	$update="update control_slip set status='close' where id='".$control_id."'";
//	$updateRS=$db->query($update);		
		$date=date("Y-m-d H:i");
		//$ticket_seller=$_SESSION['ticket_seller'];

		$sql="select * from remittance where control_id='".$control_id."' and ticket_seller='".$ticket_seller."'";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		if($nm>0){
			$row=$rs->fetch_assoc();
			$update="update remittance set log_id='".$log_id."' where id='".$row['id']."'";
			$updateRS=$db->query($update);		
		}
		else {
			$update="insert into remittance(log_id,control_id,ticket_seller,date) values ";
			$update.=" ('".$log_id."','".$control_id."','".$ticket_seller."','".$date."')";
			$updateRS=$db->query($update);		
		}

	$record_date=date("Y-m-d H:i:s");
					
	$record_sql="insert into history(action,element,transaction_time,reference_id,log_id) ";
	$record_sql.=" values ";
	$record_sql.="('insert','c. slip','".$record_date."','".$control_id."','".$log_id."')";
	//$record_rs=$record_db->query($record_sql);	

	echo "Remittance has been made.  It is now advisable to close the Control Slip.";
	}
	
	
	
}	
if(isset($_POST['ticket_control_id'])){
	$control_id=$_POST['ticket_control_id'];

	$_SESSION['control_id']=$control_id;
	
	$log_id=$_SESSION['log_id'];
	



	$db=retrieveDb();

		$source[0]='pos';


		/*
		$source[1]='tim_a';
		$source[2]='tim_b';
		$source[3]='tim_c';
		$source[4]='tim_d';
*/
	for($k=0;$k<count($source);$k++){
		$sjt_amount[$source[$k]]=$_POST[$source[$k].'_sjt_regular_amount'];
		$svt_amount[$source[$k]]=$_POST[$source[$k].'_svt_amount'];
		$sjd_amount[$source[$k]]=$_POST[$source[$k].'_sjt_discounted_amount'];


		$sjt_total[$source[$k]]=$_POST[$source[$k].'_sjt_regular_total'];
		$svt_total[$source[$k]]=$_POST[$source[$k].'_svt_total'];
		$sjd_total[$source[$k]]=$_POST[$source[$k].'_sjt_discounted_total'];


	}	

	
	
	$controlSQL="select * from control_slip where id='".$control_id."'";
	$controlRS=$db->query($controlSQL);
	$controlRow=$controlRS->fetch_assoc();
	//$log_id=$controlRow['log_id'];


	
	if($_POST['type_transact']=="ticket_amount"){
		
		$source[0]='pos';
		/*
		$source[1]='tim_a';
		$source[2]='tim_b';
		$source[3]='tim_c';
		$source[4]='tim_d';

*/
		/*

		for($k=0;$k<count($source);$k++){

			$sql="select * from control_sales_amount where control_id='".$control_id."' and source_type='".$source[$k]."'";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			
			if($nm==0){
				$sql="insert into control_sales_amount(control_id,sjt,svt,sjd,source_type) values ";
				$sql.="('".$control_id."','".$sjt_amount[$source[$k]]."','".$svt_amount[$source[$k]]."','".$sjd_amount[$source[$k]]."','".$source[$k]."')";
				$rs=$db->query($sql);	


			}	
			else {
				$sql="update control_sales_amount set sjt='".$sjt_amount[$source[$k]]."',svt='".$svt_amount[$source[$k]]."',sjd='".$sjd_amount[$source[$k]]."' where control_id='".$control_id."' and source_type='".$source[$k]."'";
				$rs=$db->query($sql);	
			}

//			$discount_sj=number_format($sjd_amount*.20,2);
//			$discount_sv=number_format($svd_amount*.20,2);	

		}
		*/
		
		$sql="select * from discount where control_id='".$control_id."'";

		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		if($nm==0){
			$update="insert into discount(control_id,sj,sv) values ('".$control_id."','".$discount_sj."','".$discount_sv."')";
			$updateRS=$db->query($update);
		}
		else {
			$row=$rs->fetch_assoc();
			$update="update discount set sj='".$discount_sj."',sv='".$discount_sv."' where id='".$row['id']."'";
			$updateRS=$db->query($update);

		}



	}
	else {
		$source[0]='pos';

		/*

		$source[1]='tim_a';
		$source[2]='tim_b';
		$source[3]='tim_c';
		$source[4]='tim_d';
		*/


		/*
		for($k=0;$k<count($source);$k++){

			$sql="select * from control_sold where control_id='".$control_id."' and source_type='".$source[$k]."'";

			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			
			if($nm==0){
				$sql="insert into control_sold(control_id,sjt,svt,sjd,source_type) values ";
				$sql.="('".$control_id."','".$sjt_total[$source[$k]]."','".$svt_total[$source[$k]]."','".$sjd_total[$source[$k]]."','".$source[$k]."')";
				$rs=$db->query($sql);
			}	
			else {
				$sql="update control_sold set sjt='".$sjt_total[$source[$k]]."',svt='".$svt_total[$source[$k]]."',sjd='".$sjd_total[$source[$k]]."' where control_id='".$control_id."' and source_type='".$source[$k]."'";
				$rs=$db->query($sql);	
			}
		
	
		}
		*/	
	}
	
	
	
	$tickets[0]="sjt";
	$tickets[1]="sjd";
	$tickets[2]="svt";
	$tickets[3]="svd";	

	$source[0]="pos";
	
/*
	$source[1]="tim_a";
	$source[2]="tim_b";
	$source[3]="tim_c";
	$source[4]="tim_d";

*/	
	$initial_type=$_POST['type_transact'];
	
	if($initial_type=="allocation"){
		$sql="select * from allocation where control_id='".$control_id."'";

		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		if($nm>0){
			$row=$rs->fetch_assoc();
			$transaction_no=$row['id'];
			$transaction_id=$row['transaction_id'];			
		
		}
		else {
			$date=date("Y-m-d H:i");
			$date_id=date("Ymd");
			
			$transactionInsert="insert into transaction(date,log_id,log_type,transaction_type) values ('".$date."','".$log_id."','initial','".$initial_type."')";
			
			$rsInsert=$db->query($transactionInsert);
						
			$insert_id=$db->insert_id;
					
			$transaction_id=$date_id."_".$insert_id;
			$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
			$rs=$db->query($sql);					


			$transaction_no=$insert_id;		
		
		}
		

		for($k=0;$k<count($source);$k++){				

			for($i=0;$i<count($tickets);$i++){		
				if(($_POST[$source[$k]."_".$tickets[$i]."_allocation_a"]=="")&&($_POST[$source[$k]."_".$tickets[$i]."_allocation_b"]=="")){

				}
				else {
					$initial=$_POST[$source[$k]."_".$tickets[$i]."_allocation_a"];
					$additional=$_POST[$source[$k]."_".$tickets[$i]."_allocation_b"];
					$initial_loose=$_POST[$source[$k]."_".$tickets[$i]."_allocation_a_loose"];
					$additional_loose=$_POST[$source[$k]."_".$tickets[$i]."_allocation_b_loose"];
					
					
					$sql="select * from allocation where control_id='".$control_id."' and type='".$tickets[$i]."' and source_type='".$source[$k]."'";

					$rs=$db->query($sql);
					$nm=$rs->num_rows;
					
					if($nm==0){
						$sql="insert into allocation(control_id,type,initial,additional,initial_loose,additional_loose,transaction_id,source_type) values ";
						$sql.="('".$control_id."','".$tickets[$i]."','".$initial."','".$additional."','".$initial_loose."','".$additional_loose."','".$transaction_id."','".$source[$k]."')";
						$rs=$db->query($sql);

					}
					else {
						$sql="update allocation set initial='".$initial."',initial_loose='".$initial_loose."' where control_id='".$control_id."' and type='".$tickets[$i]."' and source_type='".$source[$k]."'";
						
						$rs=$db->query($sql);	

					}		
				}	
			}		
		
		}
							
	$record_sql="insert into history(action,element,transaction_time,reference_id,log_id) ";
	$record_sql.=" values ";
	$record_sql.="('insert','c. slip - allocation','".$record_date."','".$control_id."','".$log_id."')";
	//$record_rs=$record_db->query($record_sql);	
	}
	else if($initial_type=="remittance"){	
		$sql="select * from control_unsold where control_id='".$control_id."'";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		if($nm>0){
			$row=$rs->fetch_assoc();
			$transaction_no=$row['id'];
			$transaction_id=$row['transaction_id'];			
		
		}
		else {
			$date=date("Y-m-d H:i");
			$date_id=date("Ymd");
			
			$transactionInsert="insert into transaction(date,log_id,log_type,transaction_type) values ('".$date."','".$log_id."','initial','".$initial_type."')";

			$rsInsert=$db->query($transactionInsert);
						
			$insert_id=$db->insert_id;
					
			$transaction_id=$date_id."_".$insert_id;
			$sql="update transaction set transaction_id='".$transaction_id."' where id='".$insert_id."'";
			$rs=$db->query($sql);					


			$transaction_no=$insert_id;		
		
		}
		for($k=0;$k<count($source);$k++){

			for($i=0;$i<count($tickets);$i++){			
				if(($_POST[$source[$k]."_".$tickets[$i]."_unsold_a"]=="")&&($_POST[$source[$k]."_".$tickets[$i]."_unsold_b"]=="")&&($_POST[$source[$k]."_".$tickets[$i]."_unsold_c"]=="")){

				}
				else {
				
					$sealed=$_POST[$source[$k]."_".$tickets[$i]."_unsold_a"];
					$loose_good=$_POST[$source[$k]."_".$tickets[$i]."_unsold_b"];
					$loose_defective=$_POST[$source[$k]."_".$tickets[$i]."_unsold_c"];

					
					$sql="select * from control_unsold where control_id='".$control_id."' and type='".$tickets[$i]."' and source_type='".$source[$k]."'";
					$rs=$db->query($sql);
					$nm=$rs->num_rows;
					
					if($nm==0){
						$sql="insert into control_unsold(control_id,type,sealed,loose_good,loose_defective,transaction_id,source_type) values ";
						$sql.="('".$control_id."','".$tickets[$i]."','".$sealed."','".$loose_good."','".$loose_defective."','".$transaction_id."','".$source[$k]."')";
						$rs=$db->query($sql);

					}
					else {
						$sql="update control_unsold set sealed='".$sealed."', loose_good='".$loose_good."', loose_defective='".$loose_defective."' where control_id='".$control_id."' and type='".$tickets[$i]."' and source_type='".$source[$k]."'";
						$rs=$db->query($sql);	
					}
				
				}		
			}

			

		}



		
		
		$date=date("Y-m-d H:i");
//		$ticket_seller=$_SESSION['ticket_seller'];

		
		$sql="select * from control_slip where id='".$control_id."'";
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();
		
		$ticket_seller=$row['ticket_seller'];		
		
		$sql="select * from remittance where control_id='".$control_id."' and ticket_seller='".$ticket_seller."'";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		if($nm>0){
			$row=$rs->fetch_assoc();
			$update="update remittance set log_id='".$log_id."' where id='".$row['id']."'";
			$updateRS=$db->query($update);		
		}
		else {
			$update="insert into remittance(log_id,control_id,ticket_seller,date) values ";
			$update.=" ('".$log_id."','".$control_id."','".$ticket_seller."','".$date."')";
			$updateRS=$db->query($update);		
		}

			$record_date=date("Y-m-d H:i:s");
					
	$record_sql="insert into history(action,element,transaction_time,reference_id,log_id) ";
	$record_sql.=" values ";
	$record_sql.="('insert','c. slip - remittance','".$record_date."','".$control_id."','".$log_id."')";
	//$record_rs=$record_db->query($record_sql);	
		
	}
	else if($initial_type=="reference"){
		$sql="update control_slip set reference_id='".$_POST['reference_id']."' where id='".$_POST['reference_control']."'";
		$rs=$db->query($sql);	
	
	}
		
	
}

$statusSlip="select * from control_slip where id='".$control_id."'";

$statusRS=$db->query($statusSlip);
$statusNM=$statusRS->num_rows;
if($statusNM>0){
	$statusRow=$statusRS->fetch_assoc();

	if($statusRow['status']=="close"){
		$statusMessage="The Control Slip is closed";

	}
	else {
		$statusMessage="The Control Slip is open";

	}
}
else {
	$statusMessage="The Control Slip is open";

}
if(isset($_POST['status_control'])){
	$db=retrieveDb();
	$control_id=$_POST['status_control'];
	$sql="update control_slip set status='".$_POST['status']."' where id='".$control_id."'";
	$rs=$db->query($sql);
	if($_POST['status']=="close"){
		$statusMessage="The Control Slip is closed";

	}
	else {
		$statusMessage="The Control Slip is open";

	}
}
?>
<link rel="stylesheet" type="text/css" href="layout/control slip.css">
<link rel="stylesheet" type="text/css" href="jquery-ui/jquery-ui.min.css">

<script src="//code.jquery.com/jquery-1.11.3.min.js"></script>

<!--<script src='jquery-1.11.3.min.js'></script>-->
<script src='jquery-ui/jquery-ui.min.js'></script>

<script language=javascript>

$(function() {
    var dialog, form, dialog2,
 
      // From http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#e-mail-state-%28type=email%29
      emailRegex = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/,
      name = $( "#name" ),
      email = $( "#email" ),
      password = $( "#password" ),
      allFields = $( [] ).add( name ).add( email ).add( password ),
      tips = $( ".validateTips" );
 
    function updateTips( t ) {
      tips
        .text( t )
        .addClass( "ui-state-highlight" );
      setTimeout(function() {
        tips.removeClass( "ui-state-highlight", 1500 );
      }, 500 );
    }
 
    function checkLength( o, n, min, max ) {
      if ( o.val().length > max || o.val().length < min ) {
        o.addClass( "ui-state-error" );
        updateTips( "Length of " + n + " must be between " +
          min + " and " + max + "." );
        return false;
      } else {
        return true;
      }
    }
 
    function checkRegexp( o, regexp, n ) {
      if ( !( regexp.test( o.val() ) ) ) {
        o.addClass( "ui-state-error" );
        updateTips( n );
        return false;
      } else {
        return true;
      }
    }
 
    function addUser() {
      var valid = true;
      allFields.removeClass( "ui-state-error" );
 
      valid = valid && checkLength( name, "username", 3, 16 );
      valid = valid && checkLength( email, "email", 6, 80 );
      valid = valid && checkLength( password, "password", 5, 16 );
 
      valid = valid && checkRegexp( name, /^[a-z]([0-9a-z_\s])+$/i, "Username may consist of a-z, 0-9, underscores, spaces and must begin with a letter." );
      valid = valid && checkRegexp( email, emailRegex, "eg. ui@jquery.com" );
      valid = valid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
 
      if ( valid ) {
        $( "#users tbody" ).append( "<tr>" +
          "<td>" + name.val() + "</td>" +
          "<td>" + email.val() + "</td>" +
          "<td>" + password.val() + "</td>" +
        "</tr>" );
        dialog.dialog( "close" );
      }
      return valid;
    }
 
    dialog = $( "#dialog-form" ).dialog({
      autoOpen: false,
      height: 350,
      width: 350,
      modal: true,
      buttons: {
      	"Submit": function(){
      		dialog.find("form").submit();
      	},
        //"Create an account": addUser,
        Cancel: function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });

    dialog2 = $( "#dialog-form2" ).dialog({
      autoOpen: false,
      height: 400,
      width: 350,
      modal: true,
      buttons: {
      	"Submit": function(){
      		dialog2.find("form").submit();
      	},
        //"Create an account": addUser,
        Cancel: function() {
          dialog2.dialog( "close" );
        }
      },
      close: function() {
        form[ 0 ].reset();
        allFields.removeClass( "ui-state-error" );
      }
    });


 
    form = dialog.find( "form" ).on( "submit", function( event ) {
     // event.preventDefault();
     // addUser();
    });
 
    $( "#create-user" ).button().on( "click", function() {

        $( "#ticket_sold_table" ).html( "<tr>" +
        "<tr>"+
        	"<th colspan=2>Ticket Sold</th>"+
        "</tr>"+
        

        "<td>Regular</td>" +
          "<td><input type='text' name='regular_sold'></td>" +
        "</tr>"+
        "<tr>"+
        	"<td>Discounted</td>"+
        	"<td><input type='text' name='discounted_sold'></td>"+
        "</tr>"+
         "<tr>"+
        	"<td>PWD</td>"+
        	"<td><input type='text' name='pwd_sold'></td>"+
        "</tr>");
        $('#ticket_sold_type').val('sjt');


      dialog.dialog( "open" );
    });

    $( "#create-user4" ).button().on( "click", function() {

        $( "#ticket_sold_table" ).html( "<tr>" +
        "<tr>"+
        	"<th colspan=2>Ticket Sold</th>"+
        "</tr>"+
        

        "<td>SVC Regular</td>" +
          "<td><input type='text' name='regular_sold'></td>" +
        "</tr>");

        $('#ticket_sold_type').val('svc');

      dialog.dialog( "open" );
    });

    $( "#create-user2" ).button().on( "click", function() {

        $( "#ticket_amount_table" ).html( 
        "<tr>"+
        	"<th colspan=2>Ticket Amount </th>"+
        "</tr>"+

       	"<tr>" +
          "<td>Issuance Fee</td>" +
          "<td><input type='text' name='issuance_amount'></td>" +
        "</tr>"+
        "<tr>"+
        	"<td>Add Value</td>"+
        	"<td><input type='text' name='add_value_amount'></td>"+
        "</tr>"+
         "<tr>"+
        	"<td>Concessionary</td>"+
        	"<td><input type='text' name='concessionary_amount'></td>"+
        "</tr>"+
        "<tr>"+
        	"<td>Globe</td>"+
        	"<td><input type='text' name='globe_amount'></td>"+
        "</tr>"+
        "<tr>"+
        	"<td>Smart</td>"+
        	"<td><input type='text' name='smart_amount'></td>"+
        "</tr>"+
         "<tr>"+
        	"<td>BPI</td>"+
        	"<td><input type='text' name='bpi_amount'></td>"+
        "</tr>"                
        );

        $('#ticket_amount_type').val('svc');

      dialog2.dialog( "open" );
    });


  
    $( "#create-user3" ).button().on( "click", function() {

        $( "#ticket_amount_table" ).html( 
        "<tr>"+
        	"<th colspan=2>Ticket Amount </th>"+
        "</tr>"+

        "<td>Regular</td>" +
          "<td><input type='text' name='regular_amount'></td>" +
        "</tr>"+
        "<tr>"+
        	"<td>Discounted</td>"+
        	"<td><input type='text' name='discounted_amount'></td>"+
        "</tr>"+
         "<tr>"+
        	"<td>PWD</td>"+
        	"<td><input type='text' name='pwd_amount'></td>"+
        "</tr>"
                );

        $('#ticket_amount_type').val('sjt');
      dialog2.dialog( "open" );
    });


  });

function computeSequence(type,column,e,nextField,source){
	
	computeTotal(type,source);
	computeSubTotal(column);
	
	if(e.keyCode==13){
		document.getElementById(nextField).focus();
		if(nextField=="revolving_remittance"){
			window.scrollBy(0,100);
		}
	
	}	

}

function computeTotal(type,source){
	var allocationTotal;
	var excessTotal;

	alert($('#'+source+'_'+type+'_allocation_a').val());
	allocationTotal=document.getElementById(source+'_'+type+'_allocation_a').value*1+document.getElementById(source+'_'+type+'_allocation_a_loose').value*1+document.getElementById(source+'_'+type+'_allocation_b').value*1+document.getElementById(source+'_'+type+'_allocation_b_loose').value*1;
	excessTotal=document.getElementById(source+'_'+type+'_unsold_a').value*1+document.getElementById(source+'_'+type+'_unsold_b').value*1+document.getElementById(source+'_'+type+'_unsold_c').value*1;
	

	if(type=='svt'){

		document.getElementById(source+'_'+type+'_total').value=allocationTotal*1-excessTotal*1;


	}



}
function computeDiscount(type,amount){
	var ticketType=type;
	var ticketAmount=amount;
	
	
	if(type=='sjt'){
	var ticketA=document.getElementById("sjd_amount").value;
	document.getElementById('discount_sj').value=Math.round(ticketA*.20,2);
	}
	else if(type=='svt'){
	var ticketA=document.getElementById("svd_amount").value;
	document.getElementById('discount_sv').value=ticketA*.20;
	}
}

function searchTicketSeller(tName){
	var xmlHttp;
	var caHTML="";

	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlHttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlHttp.onreadystatechange=function()
	{
		if (xmlHttp.readyState==4 && xmlHttp.status==200)
		{
			caHTML=xmlHttp.responseText;


			if(caHTML=="None available"){
				//document.getElementById('searchResults').innerHTML="<td style='background-color:white; color:black;'></td><td style='background-color:white; color:black;'></td>";

			}
			else {

				var caTerms=caHTML.split("==>");
				
				var count=(caTerms.length)*1-1;

				var optionsGrid="";

			
				optionsGrid+="<select name='ticket_seller_control' id='ticket_seller_control'>";				
				

				for(var n=0;n<count;n++){
					var parts=caTerms[n].split(";");

					optionsGrid+="<option value='"+parts[0]+"' >";
					optionsGrid+=parts[2]+", "+parts[1];
					optionsGrid+="</option>";
					
				}
				optionsGrid+="</select>";
					
				document.getElementById('cafill').innerHTML=optionsGrid;
				//document.getElementById('programpageNumber').value="";
			}

		}
	} 
	

	xmlHttp.open("GET","process search.php?searchTS="+tName,true);
	xmlHttp.send();	
}
function enableSubmit(){
	document.getElementById('remittanceSubmit').style.visibility="visible";
	document.getElementById('okSubmit').style.visibility="hidden";

}
function computeSubTotal(column){
	var suffix="";
	suffix=column;
	var total=0;



	total+=document.getElementById('pos_sjt'+suffix).value*1;
	total+=document.getElementById('pos_svt'+suffix).value*1;


/*	total+=document.getElementById('tim_a_sjt'+suffix).value*1;
	total+=document.getElementById('tim_a_svt'+suffix).value*1;

	total+=document.getElementById('tim_b_sjt'+suffix).value*1;
	total+=document.getElementById('tim_b_svt'+suffix).value*1;

	total+=document.getElementById('tim_c_sjt'+suffix).value*1;
	total+=document.getElementById('tim_c_svt'+suffix).value*1;

	total+=document.getElementById('tim_d_sjt'+suffix).value*1;
	total+=document.getElementById('tim_d_svt'+suffix).value*1;
*/
	document.getElementById('total'+suffix).value=total*1;
	
	
	suffix="_total";
	
	total=0;
	total+=document.getElementById('sjt'+suffix).value*1;
	total+=document.getElementById('svt'+suffix).value*1;


	document.getElementById('sold'+suffix).value=total*1;
		
	
}


function computeAmount(e,nextField,source){
	var totalAmount;
	

	//computeDiscount("sjt",document.getElementById(source+'_sjt_amount').value*1);
	//computeDiscount("svt",document.getElementById(source+'_svt_amount').value*1);
	
	
	totalAmount=document.getElementById('pos_sjt_discounted_amount').value*1+document.getElementById('pos_sjt_regular_amount').value*1+document.getElementById('pos_svt_amount').value*1


	//+document.getElementById('tim_a_sjt_amount').value*1+document.getElementById('tim_a_svt_amount').value*1+document.getElementById('tim_b_sjt_amount').value*1+document.getElementById('tim_b_svt_amount').value*1+document.getElementById('tim_c_sjt_amount').value*1+document.getElementById('tim_c_svt_amount').value*1+document.getElementById('tim_d_sjt_amount').value*1+document.getElementById('tim_d_svt_amount').value*1;
	document.getElementById('total_amount').value=totalAmount;
	document.getElementById('total_amount_display').value=document.getElementById('total_amount').value;
	
	if(e.keyCode==13){
		document.getElementById(nextField).focus();
	}		
	computeCashRevenue();
}

function computeCashRevenue(){
	var totalRevenue=document.getElementById('total_amount').value*1;
	var subtotalRevenue=0;
	for(i=2;i<8;i++){
		subtotalRevenue+=(document.getElementById('adjustment_'+i).value*1);
		totalRevenue=totalRevenue+(document.getElementById('adjustment_'+i).value*1);
	}
	document.getElementById('cash_sub_total').value=subtotalRevenue;

	document.getElementById('cash_revenue_1').value=totalRevenue;
	document.getElementById('cash_revenue_2').value=totalRevenue;
	computeRemittance();
}
function computeTicketRevenue(){

	var subtotalAdjustment=0;
	for(i=2;i<8;i++){
		subtotalAdjustment+=(document.getElementById('adjustment_tickets_'+i).value*1);
	}
	document.getElementById('tickets_sub_total').value=subtotalAdjustment;
	
}

function computeRemittance(){
	
	var totalRemittance=document.getElementById('cash_revenue_1').value*1;
	var totalAdditions=0;
	var totalDeductions=0;
	for(i=1;i<3;i++){
		totalAdditions+=document.getElementById('addition_'+i).value*1;

	
	}
	totalAdditions+=document.getElementById('unreg_sj').value*1;
	totalAdditions+=document.getElementById('unreg_sv').value*1;
	totalAdditions+=document.getElementById('issuance_unreg').value*1;

	
	
	totalDeductions+=document.getElementById('deduction_2').value*1;

	totalDeductions+=document.getElementById('refund_sj_amount').value*1;
	totalDeductions+=document.getElementById('refund_sv_amount').value*1;

	totalDeductions+=document.getElementById('discount_sj').value*1;
	totalDeductions+=document.getElementById('discount_sv').value*1;
	

	totalRemittance=(totalRemittance+totalAdditions)-totalDeductions;
	document.getElementById('total_remittance').value=totalRemittance;
}
function submitForm(){
	document.forms['control_form'].submit();
	window.opener.location.reload();
}
function focusHeader(header_option){
	document.getElementById('type_transact').value=header_option;
	
	highlightHeader();


}
function highlightHeader(){
	var option=document.getElementById('type_transact').value;

	if(option=='allocation'){
	
		document.getElementById('allocation_header').className="highlight";
	

		document.getElementById('unsold_header').className="header";
		

		document.getElementById('amount_header').className="header";
		
		
		
		document.getElementById('reference_header').className="header";
		
		
	}
	else if(option=='remittance'){
		document.getElementById('allocation_header').className="header";
		document.getElementById('unsold_header').className="highlight";
		document.getElementById('amount_header').className="header";
		document.getElementById('reference_header').className="header";


	}
	else if(option=='ticket_amount'){
		document.getElementById('allocation_header').className="header";
		document.getElementById('unsold_header').className="header";
		document.getElementById('amount_header').className="highlight";
		document.getElementById('reference_header').className="header";

	
	}
	else if(option=='reference'){
		document.getElementById('allocation_header').className="header";
		document.getElementById('unsold_header').className="header";
		document.getElementById('amount_header').className="header";
		document.getElementById('reference_header').className="highlight";

	}
	
	


//	var totalExit=0;
//	document.getElementById('row_'+rowNo).style.backgroundColor="red";
//	document.getElementById('row_'+rowNo).style.color="white";
//	for(i=0;i<=23;i++){
//		totalExit+=document.getElementById('h'+i+'_exit').value*1;
//	}
//	document.getElementById('total_exit').value=totalExit;

}
function submitStatus(){
	var status=document.getElementById('status').value;
	if(status=="close"){
		var check=confirm("Close the Control Slip? Please verify that all your data is correct.");
		
		if(check){
			document.forms['status_form'].submit();
			window.opener.location.reload();
		}
	}
	else {
		document.forms['status_form'].submit();
	
	}
}

function remitControlSlip(cSlip){
	var check=confirm("Do you still want to open the CTF to encode the Cash Remittance?");
	if(check){
		window.open("cash_transfer.php?cID="+cSlip,"transfer","height=800, width=500, scrollbars=yes");
	}
	document.forms['remittance_form'].submit();
}
</script>
<?php
$sql="select * from cash_remittance where log_id='".$log_id."' and ticket_seller='".$ticket_seller."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
}
else {

	$sql2="insert into cash_remittance(log_id,ticket_seller) values ('".$log_id."','".$ticket_seller."')";
	$rs2=$db->query($sql2);
}

?>
<?php
$unit=$_SESSION['unit'];
$ticket_seller=$_SESSION['ticket_seller'];
$control_id=$_SESSION['control_id'];

$stationSQL="select * from control_slip inner join station on station.id=control_slip.station where control_slip.id='".$control_id."'";
$stationRS=$db->query($stationSQL);
$stationRow=$stationRS->fetch_assoc();
$stationName=$stationRow['station_name'];

$ticketSellerName=$stationRow['ticket_seller'];

$sql="select * from ticket_seller where id='".$ticket_seller."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();

echo "<font color=red>".$statusMessage."</font><br>";
echo "<form id='status_form' name='status_form' action='control_slip.php' method='post'>";
echo "<table  class='controlTable3' width=100%>";
echo "<tr>";
echo "<td><b>".strtoupper($row['first_name']." ".$row['last_name'])." - ".$unit." (".$stationName.")</b></td>";
echo "<td align=right>Control Slip Status:";

echo "<select name='status' id='status'>";
echo "<option value='open'>Open</option>";
echo "<option value='close'>Close</option>";
echo "</select>";
echo "<input type=hidden name='status_control' id='status_control' value='".$control_id."' />";
echo "<input type=button value='Submit' onclick='submitStatus()' />";

echo "</td>";

echo "</tr>";
echo "</table>";

echo "</form>";
?>
<form id='control_form' name='control_form' action='control_slip.php?control_log=<?php echo $log_id; ?>' method='post' >
<table width=100% class='controlTable2'>
<tr>
<td width=40%>
Present Transaction: 
<select name='type_transact' id='type_transact' onchange='highlightHeader()' >
<option value='reference'>Reference ID</option>
<option <?php if($_POST['type_transact']=="reference"){ echo "selected"; } ?> value='allocation'>Allocation</option>
<option <?php if($_POST['type_transact']=="allocation"){ echo "selected"; } ?> value='remittance'>Remittance</option>
<option <?php if($_POST['type_transact']=="remittance"){ echo "selected"; } ?> value='ticket_amount'>Ticket Amount</option>

</select>
</td>
<?php
$control_id=$_SESSION['control_id'];

$sql="select * from control_slip where id='".$control_id."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();

?>
<td><span <?php if(isset($_POST['type_transact'])){ } else { echo "class='highlight'"; } ?> id='reference_header'  name='reference_header'><b> Reference Id (Control Slip No.)</b> </span> <input type='text' name='reference_id' id='reference_id'  onfocus='focusHeader("reference")'  value='<?php echo $row['reference_id']; ?>' /><input type=hidden name='reference_control' id='reference_control' value='<?php echo $control_id; ?>' /></td>
</tr>
</table>

<table width=100% class='controlTable'>
<tr class='header'>
<!--
<th rowspan=3>Source</th>
-->

<th rowspan=3>Ticket Type</th>
<th colspan=4 <?php if($_POST['type_transact']=="reference"){ echo  "class='highlight'"; } else { echo "class='header'"; } ?> id='allocation_header' name='allocation_header'>Allocation</th>
<th colspan=3  <?php if($_POST['type_transact']=="allocation"){ echo  "class='highlight'"; } else { echo "class='header'"; } ?> id='unsold_header' name='unsold_header'>Unsold/Excess</th>
<th rowspan=3>Sold</th>
<th rowspan=3 <?php if($_POST['type_transact']=="remittance"){ echo  "class='highlight'"; } else { echo "class='header'"; } ?> id='amount_header' name='amount_header'>Amount</th>
</tr>
<tr class='subheader'>

<th colspan=2>Initial</th>
<th colspan=2>Additional</th>
<th class='category' rowspan=2>Sealed</th>
<th colspan=2>Loose (pcs.)</th>
</tr>
<tr class='category'>
<th>Pieces</th>
<th>Loose</th>
<th>Pieces</th>
<th>Loose</th>

<th>Good</th>
<th>Defective</th>


</tr>
<?php

$total_allocation_a=0;
$total_allocation_b=0;
$total_allocation_a_loose=0;
$total_allocation_b_loose=0;


$total_unsold_a=0;
$total_unsold_b=0;
$total_unsold_c=0;


$total_sold=0;
$total_amount=0;


$control_sql="select * from control_slip where id='".$control_id."'";
$control_rs=$db->query($control_sql);
$control_row=$control_rs->fetch_assoc();

$control_log=$control_row['log_id'];
$station=$control_row['station'];

$sql="select * from allocation where control_id='".$control_id."'";

$rs=$db->query($sql);
$nm=$rs->num_rows;

$allocationNM=$nm;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	
	$allocation[$row['source_type']][$row['type']]["initial"]=$row['initial'];
	$total_allocation_a+=$allocation[$row['source_type']][$row['type']]["initial"];
	
	$allocation[$row['source_type']][$row['type']]["initial_loose"]=$row['initial_loose'];
	$total_allocation_a_loose+=$allocation[$row['source_type']][$row['type']]["initial_loose"];

	$allocation[$row['source_type']][$row['type']]["additional"]=$row['additional'];
	$allocation[$row['source_type']][$row['type']]["additional_loose"]=$row['additional_loose'];
	$allocation[$row['source_type']][$row['type']]["total"]=$row['initial']*1+$row['additional']*1+$row['initial_loose']*1+$row['additional_loose']*1;
	$total_allocation_b+=$allocation[$row['source_type']][$row['type']]["additional"];

	$total_allocation_b_loose+=$allocation[$row['source_type']][$row['type']]["additional_loose"];
}

$trackingSQL="select * from control_tracking where control_id='".$control_id."'";
$trackingRS=$db->query($trackingSQL);
$trackingNM=$trackingRS->num_rows;

$allocation['pos']['sjt']['additional']=0;
$allocation['pos']['svt']['additional']=0;

/*
$allocation['tim_a']['sjt']['additional']=0;
$allocation['tim_a']['svt']['additional']=0;
$allocation['tim_b']['sjt']['additional']=0;
$allocation['tim_b']['svt']['additional']=0;
$allocation['tim_c']['sjt']['additional']=0;
$allocation['tim_c']['svt']['additional']=0;
$allocation['tim_d']['sjt']['additional']=0;
$allocation['tim_d']['svt']['additional']=0;
*/


$allocation['pos']['sjt']['additional_loose']=0;
$allocation['pos']['svt']['additional_loose']=0;


/*
$allocation['tim_a']['sjt']['additional_loose']=0;
$allocation['tim_a']['svt']['additional_loose']=0;
$allocation['tim_b']['sjt']['additional_loose']=0;
$allocation['tim_b']['svt']['additional_loose']=0;
$allocation['tim_c']['sjt']['additional_loose']=0;
$allocation['tim_c']['svt']['additional_loose']=0;
$allocation['tim_d']['sjt']['additional_loose']=0;
$allocation['tim_d']['svt']['additional_loose']=0;
*/


for($kl=0;$kl<$trackingNM;$kl++){
$trackingRow=$trackingRS->fetch_assoc();
$sql="select * from ticket_order inner join transaction on ticket_order.transaction_id=transaction.transaction_id where ticket_order.log_id='".$trackingRow['log_id']."' and ticket_order.ticket_seller='".$ticket_seller."' and unit='".$unit."' and log_type='ticket' and station='".$station."' and transaction_type='allocation'";
$rs=$db->query($sql);
$nm=$rs->num_rows;


	$total_allocation_b=0;


	$total_allocation_b_loose=0;


	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		$allocation[$row['source_type']]['sjt']['additional']+=$row['sjt'];
		$allocation[$row['source_type']]['svt']['additional']+=$row['svt'];
		$allocation[$row['source_type']]['sjd']['additional']+=$row['sjd'];
		$allocation[$row['source_type']]['svd']['additional']+=$row['svd'];

		$allocation[$row['source_type']]['sjt']['additional_loose']+=$row['sjt_loose'];
		$allocation[$row['source_type']]['svt']['additional_loose']+=$row['svt_loose'];
		$allocation[$row['source_type']]['sjd']['additional_loose']+=$row['sjd_loose'];
		$allocation[$row['source_type']]['svd']['additional_loose']+=$row['svd_loose'];

		$total_allocation_b+=$allocation[$row['source_type']]['sjt']["additional"];
		$total_allocation_b+=$allocation[$row['source_type']]['sjd']["additional"];
		$total_allocation_b+=$allocation[$row['source_type']]['svt']["additional"];
		$total_allocation_b+=$allocation[$row['source_type']]['svd']["additional"];

		$total_allocation_b_loose+=$allocation[$row['source_type']]['sjd']["additional_loose"];
		$total_allocation_b_loose+=$allocation[$row['source_type']]['sjt']["additional_loose"];
		$total_allocation_b_loose+=$allocation[$row['source_type']]['svd']["additional_loose"];
		$total_allocation_b_loose+=$allocation[$row['source_type']]['svt']["additional_loose"];
	}
}



$sql="update allocation set additional='".$allocation['pos']['sjt']['additional']."',additional_loose='".$allocation['pos']['sjt']['additional_loose']."' where control_id='".$control_id."' and  type='sjt' and source_type='pos'";
$rs=$db->query($sql);

$sql="update allocation set additional='".$allocation['pos']['svt']['additional']."',additional_loose='".$allocation['pos']['svt']['additional_loose']."' where control_id='".$control_id."' and type='svt' and source_type='pos'";
$rs=$db->query($sql);

/*
$sql="update allocation set additional='".$allocation['tim_a']['sjt']['additional']."',additional_loose='".$allocation['tim_a']['sjt']['additional_loose']."' where control_id='".$control_id."' and  type='sjt' and source_type='tim_a'";
$rs=$db->query($sql);

$sql="update allocation set additional='".$allocation['tim_a']['svt']['additional']."',additional_loose='".$allocation['tim_a']['svt']['additional_loose']."' where control_id='".$control_id."' and type='svt' and source_type='tim_a'";
$rs=$db->query($sql);


$sql="update allocation set additional='".$allocation['tim_b']['sjt']['additional']."',additional_loose='".$allocation['tim_b']['sjt']['additional_loose']."' where control_id='".$control_id."' and  type='sjt' and source_type='tim_b'";
$rs=$db->query($sql);

$sql="update allocation set additional='".$allocation['tim_b']['svt']['additional']."',additional_loose='".$allocation['tim_b']['svt']['additional_loose']."' where control_id='".$control_id."' and type='svt' and source_type='tim_b'";
$rs=$db->query($sql);

$sql="update allocation set additional='".$allocation['tim_c']['sjt']['additional']."',additional_loose='".$allocation['tim_c']['sjt']['additional_loose']."' where control_id='".$control_id."' and  type='sjt' and source_type='tim_c'";
$rs=$db->query($sql);

$sql="update allocation set additional='".$allocation['tim_c']['svt']['additional']."',additional_loose='".$allocation['tim_c']['svt']['additional_loose']."' where control_id='".$control_id."' and type='svt' and source_type='tim_c'";
$rs=$db->query($sql);

$sql="update allocation set additional='".$allocation['tim_d']['sjt']['additional']."',additional_loose='".$allocation['tim_d']['sjt']['additional_loose']."' where control_id='".$control_id."' and  type='sjt' and source_type='tim_d'";
$rs=$db->query($sql);

$sql="update allocation set additional='".$allocation['tim_d']['svt']['additional']."',additional_loose='".$allocation['tim_d']['svt']['additional_loose']."' where control_id='".$control_id."' and type='svt' and source_type='tim_d'";
$rs=$db->query($sql);
*/


$sql="select * from control_unsold where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

$unsoldNM=$nm;
for($i=0;$i<$nm;$i++){
	$row=$rs->fetch_assoc();
	
	$unsold[$row['source_type']][$row['type']]["sealed"]=$row['sealed'];
	$unsold[$row['source_type']][$row['type']]["loose_good"]=$row['loose_good'];
	$unsold[$row['source_type']][$row['type']]["loose_defective"]=$row['loose_defective'];

	$total_unsold_a+=$unsold[$row['source_type']][$row['type']]['sealed'];
	$total_unsold_b+=$unsold[$row['source_type']][$row['type']]['loose_good'];
	$total_unsold_c+=$unsold[$row['source_type']][$row['type']]['loose_defective'];

	$unsold[$row['source_type']][$row['type']]['total']=$row['sealed']*1+$row['loose_good']*1+$row['loose_defective']*1;
}




$sql="select * from control_sold where control_id='".$control_id."' and source_type='pos'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
//$row=$rs->fetch_assoc();

if($unsoldNM==0){
//	$sold_tickets["pos"]["sjt"]=$allocation["pos"]["sjt"]["initial"]+$allocation["pos"]["sjt"]["initial_loose"]+$allocation["pos"]['sjt']["additional"]+$allocation["pos"]['sjt']["additional_loose"];
//	$sold_tickets["pos"]["svt"]=$allocation["pos"]["svt"]["initial"]+$allocation["pos"]["svt"]["initial_loose"]+$allocation["pos"]['svt']["additional"]+$allocation["pos"]['svt']["additional_loose"];

/*
	$sold_tickets["tim_a"]["sjt"]=$allocation["tim_a"]["sjt"]["initial"]+$allocation["tim_a"]["sjt"]["initial_loose"]+$allocation["tim_a"]['sjt']["additional"]+$allocation["tim_a"]['sjt']["additional_loose"];
	$sold_tickets["tim_a"]["svt"]=$allocation["tim_a"]["svt"]["initial"]+$allocation["tim_a"]["svt"]["initial_loose"]+$allocation["tim_a"]['svt']["additional"]+$allocation["tim_a"]['svt']["additional_loose"];

	$sold_tickets["tim_b"]["sjt"]=$allocation["tim_b"]["sjt"]["initial"]+$allocation["tim_b"]["sjt"]["initial_loose"]+$allocation["tim_b"]['sjt']["additional"]+$allocation["tim_b"]['sjt']["additional_loose"];
	$sold_tickets["tim_b"]["svt"]=$allocation["tim_b"]["svt"]["initial"]+$allocation["tim_b"]["svt"]["initial_loose"]+$allocation["tim_b"]['svt']["additional"]+$allocation["tim_b"]['svt']["additional_loose"];

	$sold_tickets["tim_c"]["sjt"]=$allocation["tim_c"]["sjt"]["initial"]+$allocation["tim_c"]["sjt"]["initial_loose"]+$allocation["tim_c"]['sjt']["additional"]+$allocation["tim_c"]['sjt']["additional_loose"];
	$sold_tickets["tim_c"]["svt"]=$allocation["tim_c"]["svt"]["initial"]+$allocation["tim_c"]["svt"]["initial_loose"]+$allocation["tim_c"]['svt']["additional"]+$allocation["tim_c"]['svt']["additional_loose"];

	$sold_tickets["tim_d"]["sjt"]=$allocation["tim_d"]["sjt"]["initial"]+$allocation["tim_d"]["sjt"]["initial_loose"]+$allocation["tim_d"]['sjt']["additional"]+$allocation["tim_d"]['sjt']["additional_loose"];
	$sold_tickets["tim_d"]["svt"]=$allocation["tim_d"]["svt"]["initial"]+$allocation["tim_d"]["svt"]["initial_loose"]+$allocation["tim_d"]['svt']["additional"]+$allocation["tim_d"]['svt']["additional_loose"];
*/
	
}
else {
	for($i=0;$i<$nm;$i++){
		//$sold_tickets[$row['source_type']]["sjt"]=$row['sjt']*1;
		
		//$sold_tickets[$row['source_type']]["sjd"]=$row['sjd']*1;


		$row=$rs->fetch_assoc();


		$total_sold+=$row["quantity"];


		//$sold_tickets[$row['source_type']]["svt"]=$row['svt']*1;




	}


}
//$total_sold+=$sold_tickets["sjt"];
//$total_sold+=$sold_tickets["sjd"];


//$total_sold+=$sold_tickets["svt"];

		$sql="select * from control_sales_amount where control_id='".$control_id."' ";
		$rs=$db->query($sql);
		$nm=$rs->num_rows;

		if($nm>0){
			for($k=0;$k<$nm;$k++){
				$row=$rs->fetch_assoc();
//				$sjd_amount[$row['source_type']]=$row['sjd'];

//				$sjt_amount[$row['source_type']]=$row['sjt'];
//				$svt_amount[$row['source_type']]=$row['svt'];
				
//				$cash_revenue_1=$sjt_amount[$row['source_type']]*1+$svt_amount[$row['source_type']]*1;

				$cash_revenue_1+=$row['amount'];

				$total_amount+=$row['amount'];
			}	
		}
		else {
		//	$svt_amount=$sold_tickets["svt"]*100;
		//	$svd_amount=$sold_tickets["svd"]*100;

		//	$total_amount+=$row['svt'];
		//	$total_amount+=$row['svd'];
			
		}


?>

<tr>
<!--
<td rowspan=2>POS</td>
-->



<tr>
<td>SVT</td>
<td><input type=text size=5 name='pos_svt_allocation_a' id='pos_svt_allocation_a' onfocus='focusHeader("allocation")' onkeyup="computeSequence('svt','_allocation_a',event,'tim_a_sjt_allocation_a','pos')" value='<?php echo $allocation["pos"]["svt"]["initial"]; ?>' /></td>
<td><input type=text size=5 name='pos_svt_allocation_a_loose' id='pos_svt_allocation_a_loose' onfocus='focusHeader("allocation")'  onkeyup="computeSequence('svt','_allocation_a_loose',event,'tim_a_sjt_allocation_a_loose','pos')" value='<?php echo $allocation["pos"]["svt"]["initial_loose"]; ?>' /></td>

<td><input type=text size=5 name='pos_svt_allocation_b' id='pos_svt_allocation_b' onkeyup="computeSequence('svt','_allocation_b',event,'tim_a_sjt_allocation_b','pos')" onfocus='focusHeader("allocation")'  value='<?php echo $allocation["pos"]["svt"]["additional"]; ?>' /> <a href='#' onclick='window.open("control_tracking.php?control_track=<?php echo $control_id; ?>","control_track","height=550, width=800");'>Track</a></td>
<td><input type=text size=5 name='pos_svt_allocation_b_loose' id='pos_svt_allocation_b_loose' onkeyup="computeSequence('svt','_allocation_b_loose',event,'tim_a_sjt_allocation_a_loose','pos')" onfocus='focusHeader("allocation")'  value='<?php echo $allocation["pos"]["svt"]["additional_loose"]; ?>' /> <a href='#' onclick='window.open("control_tracking.php?control_track=<?php echo $control_id; ?>","control_track","height=550, width=800");'>Track</a></td>

<td><input type=text size=5 name='pos_svt_unsold_a' id='pos_svt_unsold_a' onkeyup="computeSequence('svt','_unsold_a',event,'tim_a_sjt_unsold_a','pos')" onfocus='focusHeader("remittance")'   value='<?php echo $unsold["pos"]["svt"]["sealed"]; ?>'   /></td>
<td><input type=text size=5 name='pos_svt_unsold_b' id='pos_svt_unsold_b' onkeyup="computeSequence('svt','_unsold_b',event,'tim_a_sjt_unsold_b','pos')" onfocus='focusHeader("remittance")'  value='<?php echo $unsold["pos"]["svt"]["loose_good"]; ?>'   /></td>

<td><input type=text size=5 name='pos_svt_unsold_c' id='pos_svt_unsold_c' onkeyup="computeSequence('svt','_unsold_c',event,'tim_a_sjt_unsold_c','pos')" onfocus='focusHeader("remittance")'   value='<?php echo $unsold["pos"]["svt"]["loose_defective"]; ?>'  /></td>
<td><!--<input type=text name='pos_svt_total' id='pos_svt_total' style='width:100%' />--><input type='button' id="create-user4" value='SV Ticket Sold' /></td>
<td><!--<input type=text name='pos_svt_amount' id='pos_svt_amount' style='width:100%' onkeyup='computeAmount(event,"svt_amount","pos")'  onblur='computeAmount(event,"svd_amount");'  onfocus='focusHeader("ticket_amount")'  />--><input type='button' id="create-user2" value='SV Ticket Amount' /></td>

</tr>

<td>SJT</td>
<td><input type=text size=5 name='pos_sjt_allocation_a' id='pos_sjt_allocation_a' onfocus='focusHeader("allocation")' onkeyup="computeSequence('sjt','_allocation_a',event,'pos_svt_allocation_a','pos')" value='<?php echo $allocation["pos"]["sjt"]["initial"]; ?>' /></td>
<td><input type=text size=5 name='pos_sjt_allocation_a_loose' id='pos_sjt_allocation_a_loose' onfocus='focusHeader("allocation")'  onkeyup="computeSequence('sjt','_allocation_a_loose',event,'pos_svt_allocation_a_loose','pos')" value='<?php echo $allocation["pos"]["sjt"]["initial_loose"]; ?>' /></td>

<td><input type=text size=5 name='pos_sjt_allocation_b' id='pos_sjt_allocation_b' onfocus='focusHeader("allocation")'  onkeyup="computeSequence('sjt','_allocation_b',event,'pos_svt_allocation_b_loose','pos')" value='<?php echo $allocation["pos"]["sjt"]["additional"]; ?>' /> <a href='#' onclick='window.open("control_tracking.php?control_track=<?php echo $control_id; ?>","control_track","height=550, width=800");'>Track</a></td>
<td><input type=text size=5 name='pos_sjt_allocation_b_loose' id='pos_sjt_allocation_b_loose' onfocus='focusHeader("allocation")'  onkeyup="computeSequence('sjt','_allocation_b_loose',event,'pos_svt_allocation_b_loose','pos')" value='<?php echo $allocation["pos"]["sjt"]["additional_loose"]; ?>' /> <a href='#' onclick='window.open("control_tracking.php?control_track=<?php echo $control_id; ?>","control_track","height=550, width=800");'>Track</a></td>

<td><input type=text size=5 name='pos_sjt_unsold_a' id='pos_sjt_unsold_a' onkeyup="computeSequence('sjt','_unsold_a',event,'pos_svt_unsold_a','pos')" onfocus='focusHeader("remittance")'  value='<?php echo $unsold["pos"]["sjt"]["sealed"]; ?>'  /></td>
<td><input type=text size=5 name='pos_sjt_unsold_b' id='pos_sjt_unsold_b' onkeyup="computeSequence('sjt','_unsold_b',event,'pos_svt_unsold_b','pos')" onfocus='focusHeader("remittance")'    value='<?php echo $unsold["pos"]["sjt"]["loose_good"]; ?>' /></td>

<td><input type=text size=5 name='pos_sjt_unsold_c' id='pos_sjt_unsold_c' onkeyup="computeSequence('sjt','_unsold_c',event,'pos_svt_unsold_c','pos')"  onfocus='focusHeader("remittance")'   value='<?php echo $unsold["pos"]["sjt"]["loose_defective"]; ?>' /></td>


<td><!--<input type=text name='pos_sjt_regular_total' id='pos_sjt_regular_total'  />-->
	<input type='button' id="create-user" value='SJ Ticket Sold' />
</td>


<td><!--<input type=text name='pos_sjt_regular_amount' id='pos_sjt_regular_amount' onkeyup='computeAmount(event,"sjt_amount","pos")'  onfocus='focusHeader("ticket_amount")'   onblur='computeAmount(event,"sjd_amount");' />--><input type='button' id="create-user3" value='SJ Ticket Amount' /></td>

</tr>





<tr>
<td>Total</td>
<td><input type=text size=5 name='total_allocation_a' id='total_allocation_a' value='<?php echo $total_allocation_a; ?>' /></td>
<td><input type=text size=5 name='total_allocation_a_loose' id='total_allocation_a_loose'  value='<?php echo $total_allocation_a_loose; ?>' /></td>

<td><input type=text size=5 name='total_allocation_b' id='total_allocation_b'  value='<?php echo $total_allocation_b; ?>' /></td>
<td><input type=text size=5 name='total_allocation_b_loose' id='total_allocation_b_loose'  value='<?php echo $total_allocation_b_loose; ?>' /></td>

<td><input type=text size=5 name='total_unsold_a' id='total_unsold_a'  value='<?php echo $total_unsold_a; ?>' /></td>
<td><input type=text size=5 name='total_unsold_b' id='total_unsold_b'  value='<?php echo $total_unsold_b; ?>' /></td>
<td><input type=text size=5 name='total_unsold_c' id='total_unsold_c'  value='<?php echo $total_unsold_c; ?>' /></td>



<td ><input type=text name='sold_total' id='sold_total' style='width:100%' value='<?php echo $total_sold; ?>' /></td>
<td><input type=text name='total_amount_display' style='width:100%' id='total_amount_display'  value='<?php echo $total_amount; ?>' /></td>
</tr>
</table>
<div align=center><input type=hidden name='ticket_control_id' value='<?php echo $control_id; ?>' />
</div>
<div align=center>
<input type=button onclick='submitForm()' value='Save Ticket Info and Sales' /></div>
</form>
<?php
$sql="select * from fare_adjustment where control_id='".$control_id."'";

$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	//$fare_adjustment=$row['fare_adjustment'];
	$sjt_adjustment=$row['sjt'];
	$sjd_adjustment=$row['sjd'];
	$pwd_adjustment=$row['pwd'];
	$mismatch_adjustment=$row['mismatch'];
	$c_adjustment=$row['c'];
	$ot_adjustment=$row['ot'];
	
	$cash_adjustments=0;
	//$cash_adjustments+=$fare_adjustment*1;
	$cash_adjustments+=$sjt_adjustment*1;
	$cash_adjustments+=$sjd_adjustment*1;
	$cash_adjustments+=$pwd_adjustment*1;
	$cash_adjustments+=$mismatch_adjustment*1;
	$cash_adjustments+=$c_adjustment*1;
	$cash_adjustments+=$ot_adjustment*1;
	
	
	
	
}
$sql="select * from fare_adjustment_tickets where control_id='".$control_id."'";

$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	//$fare_adjustment=$row['fare_adjustment'];
	$sjt_adjustment_t=$row['sjt'];
	$sjd_adjustment_t=$row['sjd'];
	$pwd_adjustment_t=$row['pwd'];
	$mismatch_adjustment_t=$row['mismatch'];
	$c_adjustment_t=$row['c'];
	$ot_adjustment_t=$row['ot'];
	
	$tickets_adjustments=0;
	//$cash_adjustments+=$fare_adjustment*1;
	$tickets_adjustments+=$sjt_adjustment_t*1;
	$tickets_adjustments+=$sjd_adjustment_t*1;
	$tickets_adjustments+=$pwd_adjustment_t*1;
	$tickets_adjustments+=$mismatch_adjustment_t*1;
	$tickets_adjustments+=$c_adjustment_t*1;
	$tickets_adjustments+=$ot_adjustment_t*1;	
	
}

$cash_revenue_2=$cash_revenue_1+$cash_adjustments;

?>
<table width=100%>
<tr>
<td valign=top>
<form action='control_slip.php?control_log=<?php echo $log_id; ?>' method='post' >
<table class='controlTable' align=center>
<tr class='header'><td colspan=2><b>Total Amount</b></td><td><input type=text name='total_amount' id='total_amount' value='<?php echo $cash_revenue_1; ?>' /></td>
<tr class='subheader'><td colspan=3 align=center>Fare Adjustment (Add)</td></tr>
<tr class='category'><td>Type</td><td>Qty.</td><td>Amount</td></tr>
<tr>
<td>Regular SJ</td>
<td><input type=text size=10 name='adjustment_tickets_2' id='adjustment_tickets_2' onkeyup='computeTicketRevenue()'   value='<?php echo $sjt_adjustment_t; ?>' /></td>
<td><input type=text name='adjustment_2' id='adjustment_2' onkeyup='computeCashRevenue()' value='<?php echo $sjt_adjustment; ?>'  /></td>
</tr>
<tr>
<td>Discounted SJ</td>
<td><input type=text size=10 name='adjustment_tickets_3' id='adjustment_tickets_3' onkeyup='computeTicketRevenue()' value='<?php echo $pwd_adjustment_t; ?>'  /></td>
<td><input type=text name='adjustment_3' id='adjustment_3' onkeyup='computeCashRevenue()' value='<?php echo $sjd_adjustment; ?>'  /></td>
</tr>

<tr>
<td>SJ PWD</td>
<td><input type=text size=10 name='adjustment_tickets_4' id='adjustment_tickets_4' onkeyup='computeTicketRevenue()' value='<?php echo $c_adjustment_t; ?>'  /></td>
<td><input type=text name='adjustment_4' id='adjustment_4' onkeyup='computeCashRevenue()' value='<?php echo $pwd_adjustment; ?>'  /></td>
</tr>

<tr>
<td>C</td>
<td><input type=text size=10 name='adjustment_tickets_5' id='adjustment_tickets_5' onkeyup='computeTicketRevenue()' value='<?php echo $ot_adjustment_t; ?>'  /></td>
<td><input type=text name='adjustment_5' id='adjustment_5' onkeyup='computeCashRevenue()' value='<?php echo $c_adjustment; ?>'  /></td>
</tr>
<tr>
<td>ET</td>
<td><input type=text size=10 name='adjustment_tickets_6' id='adjustment_tickets_6' onkeyup='computeTicketRevenue()' value='<?php echo $ot_adjustment_t; ?>' /></td>
<td><input type=text name='adjustment_6' id='adjustment_6' onkeyup='computeCashRevenue()' value='<?php echo $ot_adjustment; ?>'  /></td>
</tr>
<tr>
<td>Mismatch</td>
<td><input type=text size=10 name='adjustment_tickets_7' id='adjustment_tickets_7' onkeyup='computeTicketRevenue()' value='<?php echo $mismatch_adjustment_t; ?>' /></td>
<td><input type=text name='adjustment_7' id='adjustment_7' onkeyup='computeCashRevenue()' value='<?php echo $mismatch_adjustment; ?>'  /></td>
</tr>

<tr>
<td><b>Subtotal</b></td>
<td><input type=text size=10 name='tickets_sub_total' id='tickets_sub_total' value='<?php echo $tickets_adjustments; ?>' /></td>
<td><input type=text name='cash_sub_total' id='cash_sub_total'  value='<?php echo $cash_adjustments; ?>' /></td></tr>
</tr>
<tr>
<td colspan=2><b>Total</b></td>

<td><input type=text name='cash_revenue_1' id='cash_revenue_1' value='<?php echo $cash_revenue_2; ?>' /></td>
</tr>
<tr class='none'>
<td colspan=3 align=center><input type=hidden name='adjustment_control_id_a' value='<?php echo $control_id; ?>' /><input type=submit value='Save Adjustments' /></td>
</tr>
</table>
</form>
<br>
<?php
$sql="select * from discrepancy_ticket where transaction_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
		
		$discrepancy[$row['type']][$row['ticket_type']]=$row['amount'];
		

	}
}


?>
<table class='controlTable2' width=80%>
<tr class='header'><th colspan=3>Ticket Discrepancy</th></tr>
<tr class='subheader'><th>Ticket</th><th>Overage</th><th>Shortage</th></tr>
<tr class='grid'><th>SJT</th><td><?php echo $discrepancy['overage']['sjt']; ?></td><td><?php echo $discrepancy['shortage']['sjt']; ?></td></tr>
<tr class='category'><th>SJD</th><td><?php echo $discrepancy['overage']['sjd']; ?></td><td><?php echo $discrepancy['shortage']['sjd']; ?></td></tr>
<tr class='grid'><th>SVT</th><td><?php echo $discrepancy['overage']['svt']; ?></td><td><?php echo $discrepancy['shortage']['svt']; ?></td></tr>
<tr class='category'><th>SVD</th><td><?php echo $discrepancy['overage']['svd']; ?></td><td><?php echo $discrepancy['shortage']['svd']; ?></td></tr>
<tr><th colspan=3>
<input type=button value='Add Discrepancy' onclick='window.open("discrepancy_ticket.php?tID=<?php echo $control_id; ?>&tsID=<?php echo $ticketSellerName; ?>","discrepancy","height=350, width=400")' /></th></tr>
</table>

<br>
<form action='control_slip.php?control_log=<?php echo $log_id; ?>' method='post' >
<table class='controlTable2' style='border:1px solid red' >
<tr class='header'><th colspan=2>Change User</th></tr>
<tr class='grid'>
	<td>Ticket Seller</td>
	<td>
<?php
	$sql="select * from ticket_seller order by last_name";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	?>
	<div id='cafill' name='cafill'>
	<select name='ticket_seller_change' id='ticket_seller_change'>
	<?php 
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
	?>
		<option value='<?php echo $row['id']; ?>'><?php echo strtoupper($row['last_name']).", ".$row['first_name']; ?></option>
	<?php
	}
	?>
	
	</select>
	</div>
	</td>
</tr>	
<tr  class='category'>
	<td>Search Ticket Seller</td>
	<td><input type=text name='searchTS' id='searchTS' onkeyup='searchTicketSeller(this.value)' /></td>
</tr>

<tr class='grid'>
	<td >Unit</td>
	<td>
		<select name='unit' id='unit'>
		<option>A/D1</option>
		<option>A/D2</option>
		<option>TIM1</option>
		<option>TIM2</option>
		<option>TIM3</option>
		</select>
	</td>
</tr>	
<tr class='category'>
	<td>Station</td>
	<td>
		<select name='station' id='station'>

	<?php
	$logSQL="select * from logbook where id='".$log_id."'";

	$logRS=$db->query($logSQL);
	$logNM=$logRS->num_rows;
	if($logNM>0){
		$logRow=$logRS->fetch_assoc();
		$cash_assistant=$logRow['cash_assistant'];

		$stationSQL="select * from station where id='".$logRow['station']."'";
		$stationRS=$db->query($stationSQL);
		$stationRow=$stationRS->fetch_assoc();
		$station_name=$stationRow['station_name'];
		$station_id=$stationRow['id'];

	}
	?>
	<option value='<?php echo $station_id; ?>'><?php echo $station_name; ?></option>
	<?php
	$extensionSQL="select * from extension inner join station on extension.extension=station.id where extension.station='".$logRow['station']."'";
	$extensionRS=$db->query($extensionSQL);
	$extensionNM=$extensionRS->num_rows;
	if($extensionNM>0){
		$extensionRow=$extensionRS->fetch_assoc();
		$extensionID=$extensionRow['extension'];
		$extensionName=$extensionRow['station_name'];
	?>
	<option value='<?php echo $extensionID; ?>'><?php echo $extensionName; ?></option>
	<?php
	}
	?>

	</select>
	</td>
</tr>	
<tr class='grid'>
<td colspan=2 align=center><input type=hidden name='change_control_id' value='<?php echo $control_id; ?>' /><input type=submit value='Change User' /></td>
</tr>
</table>
</form>

</td>
<td valign=top>

<?php
//$trackingSQL="select * from control_tracking where control_id='".$control_id."'";

$cash_revenue_3=$cash_revenue_2;

$sql="select sum(total) as total from cash_transfer where log_id in (select log_id from control_tracking where control_id='".$control_id."') and ticket_seller='".$ticket_seller."' and unit='".$unit."' and type in ('allocation')";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	$cash_advance=$row['total'];
	$cash_revenue_3+=$cash_advance;
}

$sql="select * from control_cash where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	$overage=$row['overage'];
//	$add_others=$row['add_others'];
//	$refund=$row['refund'];
	$unpaid_storage=$row['unpaid_storage'];
//	$discount=$row['discount'];
//	$less_others=$row['less_storage'];


	$svc_cash_value=$row['svc_value'];

	$cash_revenue_3+=$svc_cash_value;
	$cash_revenue_3+=$overage;
//	$cash_revenue_3+=$add_others;
//	$cash_revenue_3-=$refund;
	$cash_revenue_3-=$unpaid_storage;
//	$cash_revenue_3-=$discount;
//	$cash_revenue_3-=$less_others;
//	$ot_amount=$row['ot'];
//	$cash_revenue_3+=$ot_amount;
}

$sql="select * from discount where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	$sj_discount=$row['sj'];
	$sv_discount=$row['sv'];

	
	$cash_revenue_3-=$sj_discount;
	$cash_revenue_3-=$sv_discount;

}


$sql="select * from refund where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;
if($nm>0){
	$row=$rs->fetch_assoc();
	$sj_refund=$row['sj'];
	$sv_refund=$row['sv'];

	$sj_refund_amount=$row['sj_amount'];
	$sv_refund_amount=$row['sv_amount'];

	$cash_revenue_3-=$sj_refund_amount;
	$cash_revenue_3-=$sv_refund_amount;
	
	
}

$sql="select * from unreg_sale where control_id='".$control_id."'";
$rs=$db->query($sql);
$nm=$rs->num_rows;

if($nm>0){
	$row=$rs->fetch_assoc();
	$sj_unreg=$row['sj'];
	$sv_unreg=$row['sv'];
	$issuance_unreg=$row['issuance_fee'];

	$cash_revenue_3+=$sj_unreg;
	$cash_revenue_3+=$sv_unreg;
	$cash_revenue_3+=$issuance_unreg;

}


?>
<form name='remittance_form' id='remittance_form' action='control_slip.php?control_log=<?php echo $log_id; ?>' method='post'>
<table class='controlTable2'>
<tr class='header'><td><b>Total Cash Revenue</b></td><td  colspan=2><input type=text name='cash_revenue_2' id='cash_revenue_2' value='<?php echo $cash_revenue_2; ?>'   /></td></tr>

<tr class='grid'><th>SVC Cash Value</th><td  colspan=2><input type='text' name='svc_cash_value' /></td></tr>

<tr class='subheader'><td colspan=3 align=center>Adjustments (Add/Less)</td></tr>
<tr class='category'><th colspan=3>Add</th></tr>
<tr class='grid'><th>Cash Advance</th><td  colspan=2><input type=text name='addition_1' id='addition_1' onkeyup='computeRemittance()' value='<?php echo $cash_advance; ?>' /> <a href='#' onclick='window.open("control_tracking.php?control_track=<?php echo $control_id; ?>","control_track","height=550, width=800");'>Track</a></td></tr>
<tr class='grid'><th>Overage</th><td colspan=2><input type=text name='addition_2' id='addition_2' onkeyup='computeRemittance()' value='<?php echo $overage; ?>'  /></td></tr>
<!--
<tr><th>OT Amount</th><td><input type=text name='addition_3' id='addition_3' onkeyup='computeRemittance()'  /></td></tr>
-->
<tr class='header'><th colspan=3>Others (Unreg. Sale)</th></tr>
<tr class='category'>
<th rowspan=2>SJ</th>
<th colspan=2>SV</th>
</tr>
<tr>
	<th>SV Issuance Fee</th>
	<th>Unreg Sale</th>	


</tr>

<tr class='grid'>
<td><input type=text name='unreg_sj' id='unreg_sj' onkeyup='computeRemittance()' value='<?php echo $sj_unreg; ?>'   /></td>
<td><input type=text name='issuance_unreg' id='issuance_unreg' onkeyup='computeRemittance()' value='<?php echo $issuance_unreg; ?>'   /></td>

<td><input type=text name='unreg_sv' id='unreg_sv' onkeyup='computeRemittance()' value='<?php echo $sv_unreg; ?>'   /></td>

</tr>
</table>
<table class='controlTable2'>
<tr class='header'><th colspan=2>Less</th></tr>
<tr class='subheader'><th colspan=2>Refund</th></tr>
<tr class='category'>
<th>Tickets - SJ</th><th>Tickets - SV</th>
</tr>
<tr class='grid'>
<td><input type=text name='refund_sj' id='refund_sj' onkeyup='computeRemittance()' value='<?php echo $sj_refund; ?>'  /></td>
<td><input type=text name='refund_sv' id='refund_sv' onkeyup='computeRemittance()' value='<?php echo $sv_refund; ?>'  /></td>
</tr>

<tr class='category'>
<th>SJ Amount</th><th>SV Amount</th>
</tr>

<tr class='grid'>
<td><input type=text name='refund_sj_amount' id='refund_sj_amount' onkeyup='computeRemittance()' value='<?php echo $sj_refund_amount; ?>'  /></td>
<td><input type=text name='refund_sv_amount' id='refund_sv_amount' onkeyup='computeRemittance()' value='<?php echo $sv_refund_amount; ?>'  /></td>
</tr>
<tr>
<td colspan=2></td>
</tr>
<tr class='grid'><th>Unpaid Shortage</th><td><input type=text name='deduction_2' id='deduction_2' onkeyup='computeRemittance()' value='<?php echo $unpaid_shortage; ?>'  /></td></tr>
<tr>
<td colspan=2></td>
</tr>

<tr class='subheader'><th colspan=2>Discount</th>

<tr class='category'><th>SJ Amount</th><th>SV Amount</th></tr>
<tr class='grid'>
<td><input type=text name='discount_sj' id='discount_sj' onkeyup='computeRemittance()' value='<?php echo $sj_discount; ?>'  /></td>
<td><input type=text name='discount_sv' id='discount_sv' onkeyup='computeRemittance()' value='<?php echo $sv_discount; ?>'  /></td></tr>
<tr>
<td colspan=2 align=center><input type=hidden name='adjustments_2_control_id' value='<?php echo $control_id; ?>' />
<!--<input type=submit value='Save Adjustments' />-->&nbsp;
</td>
</tr>
<!-- others taken out -->
<tr class='header'><td><b>Total Remittance</b></td><td><input type=text name='total_remittance' id='total_remittance' value='<?php echo $cash_revenue_3; ?>' /></td></tr>

<tr>
	<td id='okSubmit' name='okSubmit' colspan=2><a href='#' onclick='enableSubmit()'>OK to Submit</a></td>
</tr>

<tr>
<td colspan=2 align=center style='visibility:hidden' id='remittanceSubmit' name='remittanceSubmit' ><input type=button onclick='remitControlSlip("<?php echo $control_id; ?>")' value='Save Adjustments' /></td>
</tr>



</table>
</form>
</td>
</tr>
<tr>
<td colspan=2 align=center><input type=button value='Generate Printout' onclick='window.open("generate_control_slip.php")' /></td>
</tr>
</table>


<div id="dialog-form" title="Enter Ticket Sold">
  <form action='control_slip.php' method='post'>
    <fieldset>
    	<table id='ticket_sold_table'>
    		<tr><th colspan=2>Ticket Sold</th></tr>

    	</table>
 
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type=hidden name='ticket_sold_type' id='ticket_sold_type' value='sjt' />

      <input type=hidden name='sold_control_id' value='<?php echo $control_id; ?>' />
      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
</div>


<div id="dialog-form2" title="Enter Ticket Amount"> 
  <form action='control_slip.php' method='post'>
    <fieldset>
    	<table id='ticket_amount_table'>
    		<tr><th colspan=2>Ticket Amount</th></tr>

    	</table>
 
      <!-- Allow form submission with keyboard without duplicating the dialog button -->
      <input type=hidden name='ticket_amount_type' id='ticket_amount_type' />
      	

      <input type=hidden name='amount_control_id_1' value='<?php echo $control_id; ?>' />

      <input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
    </fieldset>
  </form>
</div>



<?php
    $mysql_exec_time = (microtime(true) - $start);
	echo "Loaded in ".$mysql_exec_time." seconds";
?>
