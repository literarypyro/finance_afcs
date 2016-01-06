<?php
session_start();
?>
<?php
require("db.php");
?>
<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<?php
if(isset($_POST['month'])){
	
	if($_POST['month']<10){
		$mm="0".$_POST['month'];
	
	}
	else {
		$mm=$_POST['month'];
	
	
	}

	if($_POST['day']<10){
		$dd="0".$_POST['day'];
	}
	else {
		$dd=$_POST['day'];
	}
	$enter_action=$_POST['enter_action'];
	
	$dateShift=$_POST['year']."-".$mm."-".$dd;
	
	$_SESSION['log_date']=$dateShift;
	$shift=$_POST['shift'];
	$station=$_POST['station'];
	$_SESSION['station']=$station;
	
	
	if($shift=="3"){
		$revenue=$_POST['revenue'];
	}
	else {
		$revenue="open";
	}
	/*
	if($_POST['enter_action']=="login"){
		$db=retrieveDb();
		$sql="select * from login inner join station on login.station=station.id where username='".$_SESSION['username']."'";
		$rs=$db->query($sql);
		$row=$rs->fetch_assoc();		
		$station=$row['station'];
		$shift=$row['shift'];
	}
	*/
	//$station="1";
	$station=$_POST['station'];
	$time=date("Hi");
	if(($time>=500)&&($time<1300)){
		$shiftMark="1";
	}
	else if(($time>=1300)&&($time<2100)){
		$shiftMark="2";	

	}
	else if(($time>=2100)||($time<500)){
		$shiftMark="3";
	}
	$shift=$shiftMark;	
	
	//$shift=1;
	$shift=$_POST['shift'];	
	$db=retrieveDb();
	$sql="select * from log_history where logout in ('0000-00-00') or logout is null";

	$rs=$db->query($sql);
	$login=$rs->num_rows;
	
	if($login>0){
//		echo "A";
		$sql="select * from logbook where station='".$station."' and shift='".$shift."' and date like '".$dateShift."%%' and revenue='".$revenue."'";
		
		$rs=$db->query($sql);
		$nm=$rs->num_rows;
		if($nm>0){
	//		echo "A";

			$row=$rs->fetch_assoc();
			$log_id=$row['id'];
			$_SESSION['log_id']=$log_id;
		
	
		}
		else {
			$update="insert into logbook(station,shift,date,revenue) values ('".$station."','".$shift."','".$dateShift."','".$revenue."')";

			$updateRS=$db->query($update);
			$_SESSION['log_id']=$db->insert_id;
		
		}
	
		$enter_action="view";
	
	}
	else {

		if($_POST['enter_action']=="login"){
			$sql="select * from logbook where station='".$station."' and shift='".$shift."' and date like '".$dateShift."%%' and revenue='".$revenue."'";

			$rs=$db->query($sql);
			$nm=$rs->num_rows;	
			if($nm>0){

				$row=$rs->fetch_assoc();
				$log_id=$row['id'];
				$_SESSION['log_id']=$log_id;

				if($row['cash_assistant']==$_SESSION['username']){
					if($shift==3){
						$enter_action="login";
						$station=$_POST['station'];
						
						$logDate=date("Y-m-d");
						$logTime=date("Y-m-d H:i:s");					
						$updateSQL="insert into log_history(username,log_id,date,login) values ";
						$updateSQL.="('".$_SESSION['username']."','".$log_id."','".$logDate."','".$logTime."')";

						$updateRS=$db->query($updateSQL);
					
					}
					else {
						$logDate=date("Y-m-d");
						if($dateShift<$logDate){
							$enter_action="view";


						}
						else {
						$enter_action="login";
						$station=$_POST['station'];
						
						$logDate=date("Y-m-d");
						$logTime=date("Y-m-d H:i:s");					
						$updateSQL="insert into log_history(username,log_id,date,login) values ";
						$updateSQL.="('".$_SESSION['username']."','".$log_id."','".$logDate."','".$logTime."')";

						$updateRS=$db->query($updateSQL);
						
						
						}
					
					
					}
					
					
				}
				else {
	
					if($row['cash_assistant']==""){
						
						$updateSQL="update logbook set cash_assistant='".$_SESSION['username']."' where id='".$row['id']."'";
						$updateRS=$db->query($updateSQL);
						$logDate=date("Y-m-d");
						$logTime=date("Y-m-d H:i:s");					

						
						$updateSQL="insert into log_history(username,log_id,date,login) values ";
						$updateSQL.="('".$_SESSION['username']."','".$log_id."','".$logDate."','".$logTime."')";
						$updateRS=$db->query($updateSQL);
						$enter_action="login";
						
						
					}
					else {
						$enter_action="view";
					}
				}
					
					
			}
			else {
				
				$update="insert into logbook(station,shift,date,cash_assistant,revenue) values ('".$station."','".$shift."','".$dateShift."','".$_SESSION['username']."','".$revenue."')";

				$updateRS=$db->query($update);
				$log_id=$db->insert_id;

				$_SESSION['log_id']=$log_id;

				$logDate=date("Y-m-d");
				if($dateShift<$logDate){
					$enter_action="view";


				}
				else {

				
					$logDate=date("Y-m-d");
					$logTime=date("Y-m-d H:i:s");					
					$updateSQL="insert into log_history(username,log_id,date,login) values ";
					$updateSQL.="('".$_SESSION['username']."','".$log_id."','".$logDate."','".$logTime."')";

					$updateRS=$db->query($updateSQL);
					
					$enter_action="login";
				}
			}
		}
		else if($_POST['enter_action']=="view"){
			$enter_action="view";

			$sql="select * from logbook where station='".$station."' and shift='".$shift."' and date like '".$dateShift."%%' and revenue='".$revenue."'";
			$rs=$db->query($sql);
			$nm=$rs->num_rows;
			if($nm>0){
				$row=$rs->fetch_assoc();
				$log_id=$row['id'];
				$_SESSION['log_id']=$log_id;
				

			}
			else {
				$update="insert into logbook(station,shift,date,revenue) values ('".$station."','".$shift."','".$dateShift."','".$revenue."')";
				$updateRS=$db->query($update);
				$_SESSION['log_id']=$db->insert_id;
			
			}

		}
	
	}
	$enter_action="login";

	$nextDate=$dateShift;
	if($shift=="3"){
		if($revenue=="close"){
			$nextDate=date("Y-m-d",strtotime($dateShift."+1 day"));
			$nextShift="3";
			$nextStation=$station;
			$nextRevenue="open";
		}
		else {
			$nextDate=$dateShift;
			$nextShift="1";
			$nextStation=$station;
			$nextRevenue="open";
		}
	}
	else if($shift=="2"){
		$nextDate=$dateShift;	
		$nextShift=$shift*1+1;	
		$nextStation=$station;
		$nextRevenue="close";
	}
	
	else if($shift=="1"){
		
		$nextDate=$dateShift;
		$nextShift=$shift*1+1;
		$nextStation=$station;
		$nextRevenue="open";
	}
	$nextSQL="select * from logbook where date like '".$nextDate."%%' and shift='".$nextShift."' and station='".$nextStation."' and revenue='".$nextRevenue."'";
	$nextRS=$db->query($nextSQL);
	$nextNM=$nextRS->num_rows;
	if($nextNM>0){
		$nextRow=$nextRS->fetch_assoc();
		$_SESSION['next_log_id']=$nextRow['id'];		
	
	}
	else {
		$insert="insert into logbook(station,shift,date,revenue) values ('".$nextStation."','".$nextShift."','".$nextDate."','".$nextRevenue."')";			
		$insertRS=$db->query($insert);

		$_SESSION['next_log_id']=$db->insert_id;		
		
	}

	
	$_SESSION['viewMode']=$enter_action;
	$_SESSION['station']=$station;
	header("Location: cash_logbook.php");	
	
	/*
	
	
	$sql="select * from logbook where station='".$station."' and shift='".$shift."' and date like '".$dateShift."%%'";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	
	if($nm>0){
		$row=$rs->fetch_assoc();
		$log_id=$row['id'];
		$_SESSION['log_id']=$log_id;
		
		$logDate=date("Y-m-d");
		$logTime=date("Y-m-d H:i:s");
		if($_POST['enter_action']=="login"){
			if($row['cash_assistant']==""){
				$insert="update logbook set cash_assistant='".$_SESSION['username']."' where id='".$log_id."'";			
				$insertRS=$db->query($insert);	
			}
			else {
				
				if($row['cash_assistant']==$_SESSION['username']){
					$check="select * from log_history where logout=0000-00-00";
					$checkRS=$db->query($check);
					$checkNM=$checkRS->num_rows;
					if($checkNM>0){
						$enter_action="view";
					}
					else {
						$enter_action="login";
					}
				}
				else {
					
				
				
					$enter_action="view";	
				}
				
			}
			
			$updateLog="insert into log_history(username,log_id,date,login,logout) values ";
			$updateLog.="('".$_SESSION['username']."','".$log_id."','".$logDate."','".$logTime."','')";
			$rs2=$db->query($updateLog);
		
		}
//		header("Location: cash_logbook.php");
	}
	else {
		if($_POST['enter_action']=="view"){	
			$insert="insert into logbook(station,shift,date) values ('".$station."','".$shift."','".$dateShift."')";			
			$insertRS=$db->query($insert);
			$log_id=$db->insert_id;
			$_SESSION['log_id']=$log_id;
		}
		else if($_POST['enter_action']=="login"){
			$insert="insert into logbook(date) values ('".$dateShift."')";			
			$insertRS=$db->query($insert);
			$log_id=$db->insert_id;
			$_SESSION['log_id']=$log_id;
			
			$stationSQL="select * from login where username='".$_SESSION['username']."'";
			$stationRS=$db->query($stationSQL);
			$stationRow=$stationRS->fetch_assoc();
			//$station=$stationRow['station'];

			$station=$_POST['station'];			
		
			$logDate=date("Y-m-d");
			$logTime=date("Y-m-d H:i:s");
			$insert="update logbook set shift='".$shift."',station='".$station."',cash_assistant='".$_SESSION['username']."' where id='".$log_id."'";			
			$insertRS=$db->query($insert);
			$updateLog="insert into log_history(username,log_id,date,login,logout) values ";
			$updateLog.="('".$_SESSION['username']."','".$log_id."','".$logDate."','".$logTime."','')";
			$rs2=$db->query($updateLog);			

		}

	}

	$nextDate=$dateShift;
	if($shift=="3"){
		$nextDate=date("Y-m-d",strtotime($dateShift."+1 day"));
		$nextShift="1";
		$nextStation=$station;
	}
	else {
		$nextDate=$dateShift;
		$nextShift=$shift*1+1;
		$nextStation=$station;
	
	}
	
	
	
	
	
	$nextSQL="select * from logbook where date like '".$nextDate."%%' and shift='".$nextShift."' and station='".$nextStation."'";
	$nextRS=$db->query($nextSQL);
	$nextNM=$nextRS->num_rows;
	if($nextNM>0){
		$nextRow=$nextRS->fetch_assoc();
		$_SESSION['next_log_id']=$nextRow['id'];		
	
	}
	else {
		$insert="insert into logbook(station,shift,date) values ('".$nextStation."','".$nextShift."','".$nextDate."')";			
		$insertRS=$db->query($insert);

		$_SESSION['next_log_id']=$db->insert_id;		
		
	}
	$_SESSION['viewMode']=$enter_action;
	$_SESSION['station']=$station;
		header("Location: cash_logbook.php");
	*/
}	

?>
<script language='javascript'>
function actionBrowse(action){
	if(action=="login"){
	//	document.getElementById('station').disabled=true;
	//	document.getElementById('shift').disabled=true;		
	
	}
	else if(action=="view"){
	//	document.getElementById('station').disabled=false;
	//	document.getElementById('shift').disabled=false;
	}

}
function adminPage(){
	window.open("admin_page.php","_self");


}
function shiftChange(shift,actual){

	if(shift==actual){
		document.getElementById('enter_action').value='login'
	
	}
	else {
		document.getElementById('enter_action').value='view';
	
	}

}

</script>
<link rel="stylesheet" type="text/css" href="layout/login.css">
<br>
<br>
<br>
<?php
$log_id=$_SESSION['log_id'];

$sql="select * from logbook where id='".$log_id."'";

$db=retrieveDb();
$userSQL="select * from login where username='".$_SESSION['username']."'";
$userRS=$db->query($userSQL);
$userRow=$userRS->fetch_assoc();


$user_role=$userRow['role'];
$user_fullname=strtoupper($userRow['lastName']).", ".$userRow['firstName']; 

?>

<form enctype="multipart/form-data" action='select_log_shift.php' method='post'>
<table id='cssTable' align=center  size=50>
<tr><th colspan=2>Enter System</th></tr>
<tr>
	<td>Enter Date:</td>
	<td>
<select name='month'>
<?php
$mm=date("m");
$yy=date("Y");
$dd=date("d");

$hh=date("h");

$min=date("i");
$aa=date("a");

for($i=1;$i<13;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i==$mm){
		echo "selected";
	}
	?>
	>
	<?php
	echo date("F",strtotime(date("Y")."-".$i."-01"));
	?>
	</option>
<?php
}
?>
</select>
<select name='day'>
<?php
for($i=1;$i<=31;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i==$dd){
		echo "selected";
	}
	?>		
	>
	<?php
	
	echo $i;
	?>
	</option>
<?php
}
?>
</select>
<select name='year'>
<?php
$dateRecent=date("Y")*1+16;
for($i=1999;$i<=$dateRecent;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i==$yy){
		echo "selected";
	}
	?>		
	>
	<?php
	echo $i;
	?>
	</option>
<?php
}
?>
</select>
</td>
</tr>
<?php
$time=date("Hi");
if(($time>=600)&&($time<1400)){
	$shiftMark="1";
}
else if(($time>=1400)&&($time<2000)){
	$shiftMark="2";	

}
else if(($time>=2000)||($time<600)){
	$shiftMark="3";
}
?>
<tr>
	<td>Enter Shift:</td>
	<td>
	<select name='shift' id='shift' onchange='shiftChange(this.value,"<?php echo $shiftMark; ?>")'>
	<option <?php if($shiftMark=="1"){ echo "selected"; } ?> value='1'>1 - 5:00am - 1:00pm</option>
	<option <?php if($shiftMark=="2"){ echo "selected"; } ?> value='2'>2 - 1:00pm - 9:00pm</option>
	<option <?php if($shiftMark=="3"){ echo "selected"; } ?> value='3'>3 - 9:00pm - 5:00am</option>
	
	</select>
	</td>
</tr>
<td>Revenue (S3 only):</td>
<td>	
	<select name='revenue'>
		<option value='open'>Open Revenue (New Day)</option>
		<option value='close'>Close Revenue</option>
	</select>
	</td>
</tr>
<tr>
<td>Select Action</td>
<td>
	<select name='enter_action' id='enter_action' onchange='actionBrowse(this.value)'>
		<?php
		if(($user_role=="cash assistant")||($user_role=="administrator")){
		?>
			<option value='login'>Log-In</option>
		<?php
		}
		?>
		<option value='view'>View</option>

	</select>

</td>
</tr>

<tr>
	<td>Enter Station:</td>
	<td>
	<select name='station' id='station'>
	<?php
	$db=retrieveDb();
	$sql="select * from station where id='4' order by id*1";
	$rs=$db->query($sql);
	$nm=$rs->num_rows;
	for($i=0;$i<$nm;$i++){
		$row=$rs->fetch_assoc();
	?>
		<option value='<?php echo $row['id']; ?>'><?php echo $row['station_name']; ?></option>
	
	<?php
	
	}
	?>
	
	</select>
	</td>
</tr>

<tr>
	<td colspan=2 align=center><input type=submit value='Submit' /></td>
</tr>
</table>
</form>
<div align=center>
<a href='logout.php'>Go back to Login</a>
</div>
<br>
<?php
$db=retrieveDb();
$sql="select * from login inner join station on login.station=station.id where username='".$_SESSION['username']."'";
$rs=$db->query($sql);
$row=$rs->fetch_assoc();


?>
<table id='cssTable' align=center  width=300>
<tr><th colspan=2>User Information:</th></tr>
<tr>
<td>Name:</td>
<td><?php echo $user_fullname; ?></td>
</tr>
<tr>
<td>Position:</td>
<td><?php echo strtoupper($user_role); ?></td>
</tr>

<?php
if($user_role=="administrator"){
?>
<tr>
	<td colspan=2 align=center><input type=button value='Go to Admin Page' onclick='adminPage()' /></td>
</tr>	
<?php
}
?>
</table>



