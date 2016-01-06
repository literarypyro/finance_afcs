<?php
session_start();
?>
<?php
require("db.php");
?>

<?php
$start = microtime(true);
$dsrDate=$_SESSION['log_date'];
$station=$_SESSION['station'];
if(isset($_GET['ext'])){
	$clause="?ext=Y";
}
?>
<?php
$db=retrieveDb();
?>
<meta http-equiv="refresh" content="60;url=dsr_cash.php<?php echo $clause; ?>" />
<link href="layout/dsr.css" rel="stylesheet" type="text/css" />
<script language='javascript' src='ajax.js'></script>
<style type='text/css'>
.red {
	color:red;

}

</style>

<script language='javascript'>

var extension="";

function deleteRow(remitId,ext){

	var check=confirm("This will delete this Remittance Record, but other data will remain.  Continue?");
	if(check){
		if(ext=="Y"){
			extension="?ext=Y";
		}

		makeajax("processing.php?deleteRemittance="+remitId,"reloadPage");		
	}	
}

function reloadPage(ajaxHTML){
	self.location="dsr_cash.php"+extension;


}

</script>
<?php 
$stationStamp=$station;
if(isset($_GET['ext'])){
	$extSQL="select * from extension where station='".$station."'";
	$extRS=$db->query($extSQL);
	$extNM=$extRS->num_rows;

	if($extNM>0){
		$extRow=$extRS->fetch_assoc();
		$stationStamp=$extRow['extension'];
		
	}
}
$sql="select * from station where id='".$stationStamp."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();

$logStationId=$row['id'];
$logStation=$row['station_name'];
$clause="";

if(isset($_GET['ext'])){
	$clause="?ext=Y";
}

$extAv="select * from extension where station='".$station."'";
$extAvRS=$db->query($extAv);
$extAvNM=$extAvRS->num_rows;





?>
<body>
<h3><?php echo strtoupper($logStation); 
if(isset($_GET['ext'])){
	echo " - Extension";
}

?></h3>
<br>

<div class='menuHeader'>

Part 1 | <a href='dsr_tickets_a.php<?php echo $clause; ?>'>Part 2</a> | <a href='dsr_tickets_b.php<?php echo $clause; ?>'>Part 3</a> | <a href='dsr_summary_1.php<?php echo $clause; ?>'>Summary</a>

<?php 
if($extAvNM>0){ 
	if(isset($_GET['ext'])){ 
		echo "| <a href='dsr_cash.php'>Satellite</a> "; 
	}
	else {
		echo "| <a href='dsr_cash.php?ext=Y'>Extension</a> "; 
	
	}
} ?>
| <a href='#' onclick='window.open("generate_dsr.php<?php echo $clause; ?>","_blank")'>Printout</a>
</div>

<table class='dsrTable' border=1>
<tr class='header'>
<th rowspan=2>Name of CA</th>
<th rowspan=2>Name of Ticket Seller</th>
<th rowspan=2>Id No.</th>

<th colspan=2>Single Journey (SJ)</th>
<th colspan=2>Discounted Single Journey (DSJT)</th>

<th colspan=3>Stored Value (SV)</th>

<th rowspan=2>Mismatch Entry/Exit</th>
<th rowspan=2>Excess Time</th>
<th rowspan=2>Total Amount</th>
<th rowspan=2>&nbsp;</th>
</tr>
<tr class='subheader'>
<th>Tickets Sold</th>

<th>Amount (P)</th>
<th>Tickets Sold</th>

<th>Amount (P)</th>


<th>Tickets Sold</th>
<th>Issuance Fee</th>
<th>Add Value</th>

</tr>

<?php
$previousDate=date("Y-m-d",strtotime($dsrDate."-1 day"));

$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by field(revenue,'open','close'),field(shift,3,1,2)";
//$sql="select * from logbook where date='".$dsrDate."' and station='".$station."' order by shift";

$rs=$db->query($sql);
$nm=$rs->num_rows;

/*
$sqlAlt="select * from logbook where date='".$previousDate."' and station='".$station."' and shift=3 and revenue='open'";
$rsAlt=$db->query($sqlAlt);
$nmAlt=$rsAlt->num_rows;
*/
/*
if($nmAlt>0){
	$nm++;

}
*/
	$grandtotal["sjtSold"]=0;
	
	$grandtotal['sjtAmount']=0; 
		
	$grandtotal['sjdSold']=0;
	$grandtotal['sjdAmount']=0;
		
	$grandtotal['svdSold']=0;
	$grandtotal['svdAmount']=0;
	
	$grandtotal['svtSold']=0;
	$grandtotal['svtAmount']=0;
	$grandtotal['fare_adjustment']=0;
	$grandtotal['ot_amount']=0;
	$grandtotal['totalAmount']=0;



for($i=0;$i<$nm;$i++){

	$subtotal["sjtSold"]=0;
	
	$subtotal['sjtAmount']=0; 
		
	$subtotal['sjdSold']=0;
	$subtotal['sjdAmount']=0;
		
	$subtotal['svdSold']=0;
	$subtotal['svdAmount']=0;
	
	$subtotal['svtSold']=0;
	$subtotal['svtAmount']=0;
	$subtotal['fare_adjustment']=0;
	$subtotal['ot_amount']=0;
	$subtotal['totalAmount']=0;
	$subtotal['svtIssuance']=0;
	

	$row=$rs->fetch_assoc();
	$log_id=$row['id'];

	$sql2="select * from control_remittance where remit_log='".$log_id."' and station='".$stationStamp."' group by remit_ticket_seller,unit";

	$rs2=$db->query($sql2);
	$nm2=$rs2->num_rows;
	
	$cash_assistSQL="select * from login where username='".$row['cash_assistant']."'";

	$cash_assistRS=$db->query($cash_assistSQL);
	$cash_assistRow=$cash_assistRS->fetch_assoc();
	
	$cash_assistant=$cash_assistRow['lastName'].", ".$cash_assistRow['firstName'];
if($nm2>0){	
?>
<tr class='grid'>
<td rowspan='<?php echo $nm2; ?>'><?php echo $cash_assistant; ?></td>
<?php
for($k=0;$k<$nm2;$k++){
	$row2=$rs2->fetch_assoc();
	
	$totalAmount=0;
	$unit=$row2['unit'];
	
	$remit_id=$row2['remit_id'];
	
	$ticketsellerSQL="select * from ticket_seller where id='".$row2['remit_ticket_seller']."'";	
	$ticketsellerRS=$db->query($ticketsellerSQL);
	$ticketsellerRow=$ticketsellerRS->fetch_assoc();
	$ticket_seller=$ticketsellerRow['last_name'].", ".$ticketsellerRow['first_name'];
	$ticket_id=$ticketsellerRow['id'];
	$unsold_ticket['sjt']=0;
	$unsold_ticket['svt']=0;

	$allocation['sjt']=0;
	$allocation['svt']=0;
	
	
	$allocationSQL="select * from control_unsold inner join control_remittance on control_unsold.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."' and unit='".$unit."'";
	$allocationRS=$db->query($allocationSQL);
	$allocationNM=$allocationRS->num_rows;
	for($m=0;$m<$allocationNM;$m++){
		$allocationRow=$allocationRS->fetch_assoc();
		$unsold_ticket[$allocationRow['type']]+=$allocationRow['sealed']+$allocationRow['loose_good']+$allocationRow['loose_defective'];
		
	}	

	$allocationSQL="select * from allocation inner join control_remittance on allocation.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."' and unit='".$unit."'";
	$allocationRS=$db->query($allocationSQL);
	$allocationNM=$allocationRS->num_rows;
	for($m=0;$m<$allocationNM;$m++){
		$allocationRow=$allocationRS->fetch_assoc();
		$allocation[$allocationRow['type']]+=$allocationRow['initial']+$allocationRow['initial_loose']+$allocationRow['additional']+$allocationRow['additional_loose'];
		
	}	
	
	
	
	$allocationSQL="select * from control_sold inner join control_remittance on control_sold.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."' and unit='".$unit."'";
	$allocationRS=$db->query($allocationSQL);
	$allocationNM=$allocationRS->num_rows;
	
	$sjtSold="&nbsp;";
	$sjdSold="&nbsp;";
	$svtSold="&nbsp;";
	$svdSold="&nbsp;";
	
	$sold_ticket['sjt']['reg']=0;
	$sold_ticket['sjt']['disc']=0;
	$sold_ticket['svc']['reg']=0;
	$sold_ticket['svc']['add_value']=0;
	$sold_ticket['svc']['issuance_fee']=0;
	



	for($m=0;$m<$allocationNM;$m++){
		$allocationRow=$allocationRS->fetch_assoc();
		$sold_ticket[$allocationRow['ticket_type']][$allocationRow['value_type']]+=$allocationRow['quantity'];
	



	}
	


		$sjtSold=$sold_ticket['sjt']['reg'];
		$sjdSold=$sold_ticket['sjt']['pwd']+$sold_ticket['sjt']['disc'];
		$svtSold=$sold_ticket['svc']['reg'];

	$class1="";	
	$class2="";	

	if(($sjtSold+$sjdSold)!==($allocation['sjt']-$unsold_ticket['sjt'])){
		$class1="red";
	
	}
		
	if(($svtSold)!==($allocation['svt']-$unsold_ticket['svt'])){
		$class2="red";
	}

	$adjustmentSQL="select * from control_sales_amount inner join control_remittance on control_sales_amount.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."' and unit='".$unit."'";
	$adjustmentRS=$db->query($adjustmentSQL);
	$adjustmentNM=$adjustmentRS->num_rows;
	$sjtAmount=0;
	$svtIssuance=0;


	$svtAmount=0;
	$sjdAmount=0;
	$svdAmount=0;
	

	$ticket_amount['sjt']['reg']=0;
	$ticket_amount['sjt']['disc']=0;
	$ticket_amount['svc']['reg']=0;
	$ticket_amount['svc']['add_value']=0;
	$ticket_amount['svc']['issuance_fee']=0;
	


	
	for($n=0;$n<$adjustmentNM;$n++){
		$allocationRow=$adjustmentRS->fetch_assoc();
		
		$ticket_amount[$allocationRow['ticket_type']][$allocationRow['value_type']]+=$allocationRow['amount'];


//		$sjtAmount+=$ticket_amount['sjt']['reg']*1;
//		$sjdAmount+=$ticket_amount['sjt']['pwd']*1+$ticket_amount['sjt']['disc']*1;
//		$svtAmount+=$ticket_amount['svc']['bpi']*1+$ticket_amount['svt']['smart']*1+$ticket_amount['svt']['globe']*1+$ticket_amount['svt']['add_value']*1+$ticket_amount['svt']['concessionary']*1;
//		$svtIssuance+=$ticket_amount['svc']['issuance_fee']*1;



	}

	$sjtAmount=$ticket_amount['sjt']['reg']*1;
	$sjdAmount=$ticket_amount['sjt']['pwd']*1+$ticket_amount['sjt']['disc']*1;
	$svtAmount=$ticket_amount['svc']['bpi']*1+$ticket_amount['svc']['smart']*1+$ticket_amount['svc']['globe']*1+$ticket_amount['svc']['add_value']*1+$ticket_amount['svc']['concessionary']*1;
	$svtIssuance=$ticket_amount['svc']['issuance_fee']*1;






	$totalAmount+=$sjtAmount;
	$totalAmount+=$sjdAmount;
	$totalAmount+=$svtAmount;
	$totalAmount+=$svtIssuance;
	
	$ot_amount=0;
	$fare_adjustment=0;
	
	$fareSQL="select * from fare_adjustment inner join control_remittance on fare_adjustment.control_id=control_remittance.control_id where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."' and unit='".$unit."'";

	$fareRS=$db->query($fareSQL);
	$fareNM=$fareRS->num_rows;
	$ot_amount=0;	
	for($n=0;$n<$fareNM;$n++){
		$fareRow=$fareRS->fetch_assoc();
		$fare_adjustment+=$fareRow['sjt']+$fareRow['sjd']+$fareRow['svt']+$fareRow['svd']+$fareRow['c']+$fareRow['pwd']+$fareRow['mismatch'];
		$ot_amount+=$fareRow['ot'];
	}
	$totalAmount+=$fare_adjustment;
/*
	$otSQL="select * from control_cash where control_id in (SELECT control_id FROM control_remittance where remit_log='".$log_id."' and station='".$stationStamp."' and remit_ticket_seller='".$row2['remit_ticket_seller']."')";
	$otRS=$db->query($otSQL);
	$otNM=$otRS->num_rows;
	

	for($n=0;$n<$otNM;$n++){
		$otRow=$otRS->fetch_assoc();
		$ot_amount+=$otRow['ot']*1;
	
	}
*/	
	$totalAmount+=$ot_amount;


	$subtotal["sjtSold"]+=$sjtSold;
	
	$subtotal['sjtAmount']+=$sjtAmount; 
		
	$subtotal['sjdSold']+=$sjdSold;
	$subtotal['sjdAmount']+=$sjdAmount;
		
//	$subtotal['svdSold']+=$svdSold;
//	$subtotal['svdAmount']+=$svdAmount;
	
	$subtotal['svtSold']+=$svtSold;
	$subtotal['svtAmount']+=$svtAmount;
	$subtotal['svtIssuance']+=$svtIssuance*1;

	$subtotal['fare_adjustment']+=$fare_adjustment;
	$subtotal['ot_amount']+=$ot_amount;
	$subtotal['totalAmount']+=$totalAmount;


	
	if($k==0){
?>	
		<td><?php echo $ticket_seller; if($unit=="A/D"){ } else { echo " - ".$unit; }  ?></td>
		<td><?php echo $ticket_id; ?></td>

		<td class="<?php echo $class1; ?>"  align=right><?php echo number_format($sjtSold*1,0); ?></td>



		<td align=right><?php echo number_format($sjtAmount*1,2); ?></td>
		
		<td class="<?php echo $class1; ?>" align=right><?php echo number_format($sjdSold*1,0); ?></td>

		<td align=right><?php echo number_format($sjdAmount*1,2); ?></td>
		
		<td class="<?php echo $class2; ?>" align=right><?php echo number_format($svtSold*1,0); ?></td>
		<td align=right><?php echo number_format($svtIssuance*1,2); ?></td>
		
		<td align=right><?php echo number_format($svtAmount*1,2); ?></td>

		<td align=right><?php echo number_format($fare_adjustment*1,2); ?></td>
		<td align=right><?php echo number_format($ot_amount*1,2); ?></td>

		<td align=right><?php echo number_format($totalAmount*1,2); ?></td>
		<td><a href='#' onclick="deleteRow('<?php echo $remit_id; ?>','<?php echo $_GET['ext']; ?>')">X</a></td>
	</tr>
<?php	
	}
	else {
?>	
	<tr class='grid'>
		<td><?php echo $ticket_seller; if($unit=="A/D"){ } else { echo " - ".$unit; }  ?></td>
		<td><?php echo $ticket_id; ?></td>		
		
		<td align=right><?php echo number_format($sjtSold*1,0); ?></td>


		<td align=right><?php echo number_format($sjtAmount*1,2); ?></td>
		<td align=right><?php echo number_format($sjdSold*1,0); ?></td>
		<td align=right><?php echo number_format($sjdAmount*1,2); ?></td>
		
		<td align=right><?php echo number_format($svtSold*1,0); ?></td>
		<td align=right><?php echo number_format($svtIssuance*1,2); ?></td>

		<td align=right><?php echo number_format($svtAmount*1,2); ?></td>

		<td align=right><?php echo number_format($fare_adjustment*1,2); ?></td>
		<td align=right><?php echo number_format($ot_amount*1,2); ?></td>

		<td align=right><?php echo number_format($totalAmount*1,2); ?></td>

		
		
		<td><a href='#' onclick="deleteRow('<?php echo $remit_id; ?>','<?php echo $_GET['ext']; ?>')">X</a></td>
		
	</tr>
		
	
<?php	
	}

}
}
if($nm2>0){	
	$grandtotal["sjtSold"]+=$subtotal["sjtSold"];
	
	$grandtotal['sjtAmount']+=$subtotal['sjtAmount']; 
		
	$grandtotal['sjdSold']+=$subtotal['sjdSold'];
	$grandtotal['sjdAmount']+=$subtotal['sjdAmount'];
		
	$grandtotal['svdSold']+=$subtotal['svdSold'];
	$grandtotal['svdAmount']+=$subtotal['svdAmount'];
	
	$grandtotal['svtSold']+=$subtotal['svtSold'];
	$grandtotal['svtIssuance']+=$subtotal['svtIssuance'];

	$grandtotal['svtAmount']+=$subtotal['svtAmount'];
	$grandtotal['fare_adjustment']+=$subtotal['fare_adjustment'];
	$grandtotal['ot_amount']+=$subtotal['ot_amount'];
	$grandtotal['totalAmount']+=$subtotal['totalAmount'];

?>
	<tr class='subheader'>
		<th colspan=3>Subtotal</th>
		<td align=right><font><?php echo number_format($subtotal["sjtSold"]*1,0); ?></font></td>
	
		<td align=right><font><?php echo number_format($subtotal['sjtAmount']*1,2); ?></font></td> 
		<td align=right><font><?php echo number_format($subtotal["sjdSold"]*1,0); ?></font></td>
		
		<td align=right><font><?php echo	number_format($subtotal['sjdAmount']*1,2); ?></font></td> 
	
		<td align=right><font><?php echo	number_format($subtotal['svtSold']*1,0); ?></font></td>



		<td align=right><font><?php echo number_format($subtotal['svtIssuance']*1,2); ?></font></td>

		<td align=right><font><?php echo	number_format($subtotal['svtAmount']*1,2); ?></font></td>
		<td align=right><font><?php echo	number_format($subtotal['fare_adjustment']*1,2); ?></font></td>
		<td align=right><font><?php echo	number_format($subtotal['ot_amount']*1,2); ?></font></td>
		<td align=right><font><?php echo	number_format($subtotal['totalAmount']*1,2); ?></font></td>		
		<td>&nbsp;</td>
		
	</tr>	
<?php
}
}
?>
	<tr  class='header'>
		<th colspan=3>Grand Total</th>
		<td align=right><font><?php echo number_format($grandtotal["sjtSold"]*1,0); ?></font></td>
		<td align=right><font><?php echo number_format($grandtotal['sjtAmount']*1,2); ?></font></td> 
		<td align=right><font><?php echo number_format($grandtotal["sjdSold"]*1,0); ?></font></td>		
		<td align=right><font><?php echo number_format($grandtotal['sjdAmount']*1,2); ?></font></td> 
		<td align=right><font><?php echo number_format($grandtotal['svtSold']*1,0); ?></font></td>

		<td align=right><font><?php echo number_format($grandtotal['svtIssuance']*1,2); ?></font></td>	
		<td align=right><font><?php echo	number_format($grandtotal['svtAmount']*1,2); ?></font></td>
		<td align=right><font><?php echo	number_format($grandtotal['fare_adjustment']*1,2); ?></font></td>
		<td align=right><font><?php echo	number_format($grandtotal['ot_amount']*1,2); ?></font></td>
		<td align=right><font><?php echo	number_format($grandtotal['totalAmount']*1,2); ?></font></td>		
		

		<td>&nbsp;</td>
		
	</tr>
</table>
</body>
<?php
    $mysql_exec_time = (microtime(true) - $start);
	echo "Loaded in ".$mysql_exec_time." seconds";
?>