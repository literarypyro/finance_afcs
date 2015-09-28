<?php
session_start();
?>
<?php
require("db.php");
?>
<?php
require_once("phpexcel/Classes/PHPExcel.php");
require_once("phpexcel/Classes/PHPExcel/IOFactory.php");
require("excel functions.php");

$log_id=$_SESSION['log_id'];
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
$rowCount=0;
$db=retrieveDb();
$sql="select * from logbook where id='".$log_id."'";

$rs=$db->query($sql);
$row=$rs->fetch_assoc();

$logDate=date("F d, Y",strtotime($row['date']));
$logShift=$row['shift'];
$logUser=$row['cash_assistant'];
$logDayWeek=date("l",strtotime($row['date']));
$logST=$row['station'];




$userSQL="select * from login where username='".$logUser."'";
$userRS=$db->query($userSQL);
$userRow=$userRS->fetch_assoc();
	
$user_fullname=$userRow['lastName'].", ".$userRow['firstName'];


$stationSQL="select * from station where id='".$row['station']."'";
$stationRS=$db->query($stationSQL);
$stationRow=$stationRS->fetch_assoc();

$logStation=$stationRow['station_name'];


$shiftSQL="select * from shift where shift_id='S".$logShift."'";
$shiftRS=$db->query($shiftSQL);
$shiftRow=$shiftRS->fetch_assoc();
$shiftName=$shiftRow['shift_name'];
	



	$dateSlip=date("Y-m-d His");

	$filename="treasury forms/sjt_logbook.xls";

	$newFilename="printout/SJT Logbook_".$log_id." ".$dateSlip.".xls";
	copy($filename,$newFilename);
	$workSheetName="SJT Logbook";	
	$workbookname=$newFilename;
	$excel=loadExistingWorkbook($workbookname);

  	$ExWs=createWorksheet($excel,$workSheetName,"openActive");
	$db=retrieveDb();
		
	$station=$_SESSION['station'];

	$sjt_loose_1=0;
	$sjt_packs_1=0;


	
	
	$sql="select * from beginning_balance_sjt where log_id='".$log_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();

		$sjt_loose_1=$row['sjt_loose'];
		$sjt_packs_1=$row['sjt'];

	}
	else {

		$alternate="SELECT * FROM beginning_balance_sjt inner join logbook on beginning_balance_sjt.log_id=logbook.id and station='".$station."' order by date desc,shift desc";

		$rs2=$db->query($alternate);
		$row=$rs2->fetch_assoc();
		$sjt_loose_1=$row['sjt_loose'];
		$sjt_packs_1=$row['sjt'];


	}		
	$rowCount+=6;
	addContent(setRange("C".$rowCount,"D".$rowCount),$excel,$logStation,"true",$ExWs);
	addContent(setRange("I".$rowCount,"K".$rowCount),$excel,$logDate,"true",$ExWs);	
	$rowCount++;
	addContent(setRange("I".$rowCount,"K".$rowCount),$excel,$shiftName,"true",$ExWs);
	addContent(setRange("C".$rowCount,"E".$rowCount),$excel,$user_fullname,"true",$ExWs);
	
	
	
	$rowCount+=5;
	addContent(setRange("B".$rowCount,"B".$rowCount),$excel,"Beginning Balance","true",$ExWs);

	addContent(setRange("M".$rowCount,"M".$rowCount),$excel,$sjt_packs_1,"true",$ExWs);
	addContent(setRange("N".$rowCount,"O".$rowCount),$excel,$sjt_loose_1,"true",$ExWs);
	
	$rowCount++;
	
	$sql="select * from transaction where log_id='".$log_id."' and log_type in ('ticket','initial','annex','finance') and transaction_type not in ('ticket_amount')  order by id*1";

	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	$db=retrieveDb();
$a=$rowCount;
	$counter=0;
for($m=0;$m<$nm;$m++){

	$row=$rs->fetch_assoc();

	$date=date("h:i a",strtotime($row['date']));
	$transaction_id=$row['transaction_id'];
	$type=$row['transaction_type'];
	$edit_id=$row['id'];

	$log_type=$row['log_type'];
	if($row['reference_id']==""){
		$remarks="&nbsp;";
	}
	else {
		$remarks=$row['reference_id'];
	}		
	$sjt_packs=0;
	$sjt_loose=0;
	

	$sjt_loose_in=0;
	
	$suffix="";
	
	if(($row['log_type']=='ticket')||($row['log_type']=="annex")||($row['log_type']=="finance")){
		$ticketSQL="select * from ticket_order where transaction_id='".$transaction_id."'";
		$ticketRS=$db->query($ticketSQL);
			
		$ticketRow=$ticketRS->fetch_assoc();
		
		if($ticketRow['station']==$logST){
		}
		else {
			$extensionSQL="select * from station where id='".$ticketRow['station']."'";
			$extensionRS=$db->query($extensionSQL);
			$extensionRow=$extensionRS->fetch_assoc();
					
			$suffix=" - ".$extensionRow['station_name'];
				
		}			
		//$sjt_loose=$ticketRow['sjt']%100;
		$sjt_loose=$ticketRow['sjt_loose'];
		$sjt_packs=$ticketRow['sjt'];
		
		//$sjt_packs=($ticketRow['sjt']*1-$sjt_loose);
			
		$unitType=$ticketRow['unit'];	
		$ticketSellerId=$ticketRow['ticket_seller'];
	}		
	else if($row['log_type']=='initial'){
		$sjt_packs=0;

		$sjt_loose=0;

		$sjt_loose_in=0;
		
		$trans_type=$row['transaction_type'];
		if($trans_type=="allocation"){
			$ticketSQL="select * from allocation where transaction_id='".$transaction_id."' and type in ('sjd','sjt')";

			$ticketRS=$db->query($ticketSQL);
			$ticketNM=$ticketRS->num_rows;		
			
			//$ticketSellerId=$ticketsRow['ticket_seller'];
			
			for($i=0;$i<$ticketNM;$i++){			
				$ticketRow=$ticketRS->fetch_assoc();			
				if($i==0){
					$control_id=$ticketRow['control_id'];
					$tsSQL="select * from control_slip where id='".$control_id."'";
					$tsRS=$db->query($tsSQL);
					$tsNM=$tsRS->num_rows;
					$ticketsRow=$tsRS->fetch_assoc();
					
					$ticketSellerId=$ticketsRow['ticket_seller'];
					$unitType=$ticketsRow['unit'];
					if($ticketsRow['station']==$logST){
					}
					else {
						$extensionSQL="select * from station where id='".$ticketRow['station']."'";
						$extensionRS=$db->query($extensionSQL);
						$extensionRow=$extensionRS->fetch_assoc();
								
						$suffix=" - ".$extensionRow['station_name'];
							
					}							
					
				
				}


		
				if($ticketRow['type']=='sjt'){
					$sjt_packs+=$ticketRow['initial']*1;
					$sjt_loose+=$ticketRow['initial_loose']*1;

				
				}
			}
		
		}
		else if($trans_type=="remittance"){
			$sjt_packs=0;
			
			$sjt_loose=0;

			$sjt_loose_in=0;

			
			$looseSQL="select * from control_unsold where transaction_id='".$transaction_id."' and type in ('sjd','sjt')";
			$looseRS=$db->query($looseSQL);
			$looseNM=$looseRS->num_rows;
		
			for($i=0;$i<$looseNM;$i++){
				$looseRow=$looseRS->fetch_assoc();
				if($i==0){
					$control_id=$looseRow['control_id'];
					$tsSQL="select * from control_slip where id='".$control_id."'";
					$tsRS=$db->query($tsSQL);
					$tsNM=$tsRS->num_rows;
					$ticketsRow=$tsRS->fetch_assoc();
					
					$ticketSellerId=$ticketsRow['ticket_seller'];
					$unitType=$ticketsRow['unit'];				
					if($ticketsRow['station']==$logST){
					}
					else {
						$extensionSQL="select * from station where id='".$ticketRow['station']."'";
						$extensionRS=$db->query($extensionSQL);
						$extensionRow=$extensionRS->fetch_assoc();
								
						$suffix=" - ".$extensionRow['station_name'];
							
					}						
				}
			
				if($looseRow['type']=='sjt'){
					$loose_total+=$looseRow['loose_defective']*1;
					$sjt_loose+=$loose_total;
					$sjt_loose_in+=$looseRow['loose_defective']*1+$looseRow['loose_good']*1;
					$sjt_packs+=$looseRow['sealed'];
				}
			
			}
		}

		
	
	}	

	$ticketSellerSQL="select * from ticket_seller where id='".$ticketSellerId."'";		

	$ticketSellerRS=$db->query($ticketSellerSQL);
	$ticketSellerRow=$ticketSellerRS->fetch_assoc();

	$verify=$sjt_packs+$sjt_loose+$sjd_packs+$sjd_loose+$sjt_loose_in+$sjd_loose_in;


	$counter++;
	if($verify==0){
	}
	else {
			

		addContent(setRange("A".$rowCount,"A".$rowCount),$excel,$date,"true",$ExWs);

		if($log_type=="initial"){
			addContent(setRange("B".$rowCount,"C".$rowCount),$excel,strtoupper($ticketSellerRow['last_name']).", ".$ticketSellerRow['first_name'],"true",$ExWs);
		}
		else if($log_type=="annex"){
			addContent(setRange("B".$rowCount,"C".$rowCount),$excel,"FROM ANNEX","true",$ExWs);
		}	
		else if($log_type=="finance"){
			addContent(setRange("B".$rowCount,"C".$rowCount),$excel,"FROM FINANCE TRAIN","true",$ExWs);
		}	
		else {
			$name=strtoupper($ticketSellerRow['last_name']).", ".$ticketSellerRow['first_name'].$suffix;
			if($unitType==""){ } else { $name." - ".$unitType; }
			addContent(setRange("B".$rowCount,"C".$rowCount),$excel,$name,"true",$ExWs);
		}				
		
		if($type=="deposit"){
		}
		else if($type=="remittance"){
			if($log_type=="cash"){
				addContent(setRange("D".$rowCount,"D".$rowCount),$excel,"'".$ticketSellerRow['id'],"true",$ExWs);

			}
			else {
				if(($log_type=='annex')||($log_type=='finance')){
				}
				else {
					addContent(setRange("D".$rowCount,"D".$rowCount),$excel,"'".$ticketSellerRow['id'],"true",$ExWs);
				
				}
				
			}
			
		}
		else { 
			if(($log_type=='annex')||($log_type=='finance')){
			}
			else {
				addContent(setRange("D".$rowCount,"D".$rowCount),$excel,"'".$ticketSellerRow['id'],"true",$ExWs);
			}
		}	
		
	if($log_type=="initial"){
		if($type=="remittance"){
			addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$sjt_packs,"true",$ExWs);
			addContent(setRange("F".$rowCount,"F".$rowCount),$excel,$sjt_loose_in,"true",$ExWs);

			addContent(setRange("J".$rowCount,"J".$rowCount),$excel,$sjt_loose,"true",$ExWs);
		}	
		else if($type=="allocation"){
			addContent(setRange("I".$rowCount,"I".$rowCount),$excel,$sjt_packs,"true",$ExWs);
			addContent(setRange("J".$rowCount,"J".$rowCount),$excel,$sjt_loose,"true",$ExWs);
		}	
	}	
	else if(($log_type=="annex")||($log_type=="finance")){
		addContent(setRange("E".$rowCount,"E".$rowCount),$excel,$sjt_packs,"true",$ExWs);
		addContent(setRange("F".$rowCount,"F".$rowCount),$excel,$sjt_loose,"true",$ExWs);
	}		
	else if($log_type=="ticket"){
		addContent(setRange("I".$rowCount,"I".$rowCount),$excel,$sjt_packs,"true",$ExWs);
		addContent(setRange("J".$rowCount,"J".$rowCount),$excel,$sjt_loose,"true",$ExWs);
	}	
	if(($log_type=="annex")||($log_type=="finance")){
		$sjt_packs_1+=$sjt_packs*1;
		$sjt_loose_1+=$sjt_loose;

		
	}
	else if($log_type=="ticket"){
		if($type=="allocation"){
			$sjt_packs_1-=$sjt_packs*1;
			$sjt_loose_1-=$sjt_loose;
		}
		else if($type=="remittance"){
			$sjt_loose_1+=$sjt_loose_in-$sjt_loose;	
			$sjt_packs_1+=$sjt_packs*1;
		
		
		}
	}
	else if($log_type=="initial"){
		if($type=="allocation"){
			$sjt_packs_1-=$sjt_packs*1;
			$sjt_loose_1-=$sjt_loose*1;
		}
		else if($type=="remittance"){
			$sjt_loose_1+=$sjt_loose_in-$sjt_loose;	
			$sjt_packs_1+=$sjt_packs*1;
		}
	
	}
		addContent(setRange("M".$rowCount,"M".$rowCount),$excel,$sjt_packs_1,"true",$ExWs);
		addContent(setRange("N".$rowCount,"O".$rowCount),$excel,$sjt_loose_1,"true",$ExWs);
		
		if($a==29){
			$rowCount2=$rowCount;

			if($rowCount<=29){
				$pp=1;
				$pageIndex=31;				
			}
			
			if($rowCount>29){
				$pp=2;
				$pageIndex=70;				
			}
			
			if($rowCount>66){
				$pp=3;
				$pageIndex=109;
			}
			addContent(setRange("M".$pageIndex,"M".$pageIndex),$excel,"=M".$rowCount,"true",$ExWs);
			addContent(setRange("N".$pageIndex,"O".$pageIndex),$excel,"=N".$rowCount,"true",$ExWs);


			
			$rowCount+=10;
			$rowCount+=12;
			$a=12;
		}
		else {
			if($rowCount<=29){
				$pp=1;
				$pageIndex=31;				
			}
			
			if($rowCount>29){
				$pp=2;
				$pageIndex=70;				
			}
			
			if($rowCount>66){
				$pp=3;
				$pageIndex=109;
			}
			addContent(setRange("M".$pageIndex,"M".$pageIndex),$excel,"=M".$rowCount,"true",$ExWs);
			addContent(setRange("N".$pageIndex,"O".$pageIndex),$excel,"=N".$rowCount,"true",$ExWs);
	
			$rowCount++;
			$rowCount2=$rowCount-1;
			$a++;	




		}
		
		
		
		
		
		
	}
}	
	$sqlDefective="select * from physically_defective where log_id='".$log_id."'";
	$rsDefective=$db->query($sqlDefective);
	$nmDefective=$rsDefective->num_rows;
	
	if($nmDefective>0){
		$rowDefective=$rsDefective->fetch_assoc();
		$verify=$rowDefective['sjt']+$rowDefective['sjd'];		
		if($verify>0){
			$date=date("h:i a",strtotime($rowDefective['date']));	
			addContent(setRange("A".$rowCount,"A".$rowCount),$excel,$date,"true",$ExWs);
			addContent(setRange("B".$rowCount,"C".$rowCount),$excel,"Physically Defective","true",$ExWs);
			addContent(setRange("J".$rowCount,"J".$rowCount),$excel,$rowDefective['sjt'],"true",$ExWs);

			$sjt_loose_1-=$rowDefective['sjt'];			
			addContent(setRange("M".$rowCount,"M".$rowCount),$excel,$sjt_packs_1,"true",$ExWs);
			addContent(setRange("N".$rowCount,"O".$rowCount),$excel,$sjt_loose_1,"true",$ExWs);
		}
		
		if($rowCount<=29){
			$pp=1;
			$pageIndex=31;				
		}

		if($rowCount>29){
			$pp=2;
			$pageIndex=70;				
		}
				
		if($rowCount>66){
			$pp=3;
			$pageIndex=109;
		}	
		addContent(setRange("M".$pageIndex,"M".$pageIndex),$excel,"=M".$rowCount,"true",$ExWs);
		addContent(setRange("N".$pageIndex,"O".$pageIndex),$excel,"=N".$rowCount,"true",$ExWs);

		
	}
	
	
	
	addContent(setRange("Q7","Q7"),$excel,$pp,"true",$ExWs);
	

	$sql="SELECT * FROM transaction  where log_type='ticket_catransfer' and log_id='".$log_id."'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	if($nm>0){
		$row=$rs->fetch_assoc();
		$cTransferSQL="select * from ticket_order where transaction_id='".$row['transaction_id']."'";
		$cTransferRS=$db->query($cTransferSQL);
		$cTR=$cTransferRS->fetch_assoc();


		
		$turnover_sjt=0;
		$turnover_sjd=0;
		$turnover_svt=0;
		$turnover_svd=0;
		
		
		$turnover_sjt=$cTR['sjt']*1+$cTR['sjt_loose']*1;
		$turnover_svt=$cTR['svt']*1+$cTR['svt_loose']*1;
		
		


		$userSQL="select * from login where username='".$cTR['destination_ca']."'";
		$userRS=$db->query($userSQL);
		$userRow=$userRS->fetch_assoc();

		$destination_ca=$userRow['lastName'].", ".$userRow['firstName'];	
		
		$userSQL="select * from login where username='".$cTR['cash_assistant']."'";
		$userRS=$db->query($userSQL);
		$userRow=$userRS->fetch_assoc();

		$origin_ca=$userRow['lastName'].", ".$userRow['firstName'];	

		addContent(setRange("F33","F33"),$excel,$turnover_sjt,"true",$ExWs);
		addContent(setRange("I33","I33"),$excel,$turnover_sjd,"true",$ExWs);


		addContent(setRange("A34","A34"),$excel,$origin_ca,"true",$ExWs);
		addContent(setRange("F34","I4"),$excel,$destination_ca,"true",$ExWs);
		
		
		$nextShiftTurnover=1;
		if($logShift==3){
			$nextShiftTurnover=1;
		}
		else {
			$nextShiftTurnover=$logShift+1;
		}
		
		
		addContent(setRange("A35","D35"),$excel,"Cash Assistant - Shift ".$logShift,"true",$ExWs);
		addContent(setRange("F35","I35"),$excel,"Cash Assistant - Shift ".$nextShiftTurnover,"true",$ExWs);


		

	}	
	
	
	$userSQL="select * from login where username='".$_SESSION['username']."'";
	$userRS=$db->query($userSQL);
	$userRow=$userRS->fetch_assoc();
	
	$user_fullname=$userRow['lastName'].", ".$userRow['firstName'];
	
	addContent(setRange("K39","N39"),$excel,"Printed by: ".$user_fullname,"true",$ExWs);
	$timePrinted=date("Y-m-d H:i:s");
	$timePrintStamp=date("H:iA",strtotime($timePrinted));
	$datePrintStamp=date("m/d/Y",strtotime($timePrinted));
	
	addContent(setRange("O39","Q39"),$excel,"Time Printed: ".$timePrintStamp,"true",$ExWs);
	
	addContent(setRange("R39","S39"),$excel,"Date Printed: ".$datePrintStamp,"true",$ExWs);	
	
	save($ExWb,$excel,$newFilename); 	
	echo "SJT Logbook has been generated!  Press right click and Save As: <a href='".$newFilename."'>Here</a>";

?>

