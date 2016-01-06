<?php
require("db.php");
?>

<?php
ini_set("date.timezone","Asia/Kuala_Lumpur");
?>
<form action='transaction_history.php' method='post'>
<table>
<tr>
<th>From:</th>
<td>
<select name='first_month'>
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
<select name='first_day'>
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
<select name='first_year'>
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
<select name='first_hour'>
<?php
for($i=1;$i<=12;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i*1==$hh*1){
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
<select name='first_minute'>
<?php
for($i=0;$i<=59;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i*1==$min*1){
		echo "selected";
	}
	?>		
	>
	<?php
	if($i<10){
	echo "0".$i;
	}
	else {
	echo $i;
	}
	?>	
	</option>
<?php
}
?>
</select>
<select name='first_amorpm'>
<option value='am' <?php if($aa=="am"){ echo "selected"; } ?>>AM</option>
<option value='pm' <?php if($aa=="pm"){ echo "selected"; } ?>>PM</option>
</select>
</td>
</tr>
<tr>
<th>To:</th>
<td>
<select name='last_month'>
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
<select name='last_day'>
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
<select name='last_year'>
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
<select name='last_hour'>
<?php
for($i=1;$i<=12;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i*1==$hh*1){
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
<select name='last_minute'>
<?php
for($i=0;$i<=59;$i++){
?>
	<option value='<?php echo $i; ?>' 
	<?php
	if($i*1==$min*1){
		echo "selected";
	}
	?>		
	>
	<?php
	if($i<10){
	echo "0".$i;
	}
	else {
	echo $i;
	}
	?>	
	</option>
<?php
}
?>
</select>
<select name='last_amorpm'>
<option value='am' <?php if($aa=="am"){ echo "selected"; } ?>>AM</option>
<option value='pm' <?php if($aa=="pm"){ echo "selected"; } ?>>PM</option>
</select>
</td>
</tr>
<tr>
<td colspan=2><input type=submit value='Retrieve Records' /></td>
</tr>

</table>
</form>
<br>
<br>
<table border=1 style='border-collapse:collapse;' width=100%>
<tr>
<th>Action</th>
<th>Form Element</th>
<th>Action Time</th>
<th>Reference Id (C.Slip ID, CTF No., etc.)</th>
<th>Log ID</th>
</tr>
<?php
if(isset($_POST['first_month'])){
	$first_year=$_POST['first_year'];
	$first_month=$_POST['first_month'];
	$first_day=$_POST['first_day'];
	
	$first_hour=$_POST['first_hour'];
	$first_minute=$_POST['first_minute'];
	$first_amorpm=$_POST['first_amorpm'];

	if($first_amorpm=="pm"){
		if($first_hour<12){
			$first_hour+=12;
			
		}
		else {
		}
	}
	else {
		if($first_hour=="12"){
			$first_hour=0;
			
		}
	
	}
	
	$first_date=date("Y-m-d H:i",strtotime($first_year."-".$first_month."-".$first_day." ".$first_hour.":".$first_minute));

	$last_year=$_POST['last_year'];
	$last_month=$_POST['last_month'];
	$last_day=$_POST['last_day'];
	
	$last_hour=$_POST['last_hour'];
	$last_minute=$_POST['last_minute'];
	$last_amorpm=$_POST['last_amorpm'];

	if($last_amorpm=="pm"){
		if($last_hour<12){
			$last_hour+=12;
			
		}
		else {
		}
	}
	else {
		if($last_hour=="12"){
			$last_hour=0;
			
		}
	
	}
	
	$last_date=date("Y-m-d H:i",strtotime($last_year."-".$last_month."-".$last_day." ".$last_hour.":".$last_minute));


	$record_db=new mysqli("localhost","root","","transaction_history");
	
	$record_sql="select * from history where transaction_time between '".$first_date."' and '".$last_date."'";
	$record_rs=$record_db->query($record_sql);	

	$record_nm=$record_rs->num_rows;	

	for($i=0;$i<$record_nm;$i++){
		$record_row=$record_rs->fetch_assoc();
		
		$action=$record_row['action'];
		$element=$record_row['element'];
		$transaction_time=date("Y-m-d H:i:s", strtotime($record_row['transaction_time']));
		$reference_id=$record_row['reference_id'];
		$log_id=$record_row['log_id'];
?>		
		<tr>
			<td><?php echo $action; ?></td>
			<td><?php echo $element; ?></td>
			<td><?php echo $transaction_time; ?></td>
			<td><?php echo $reference_id; ?></td>
			<td><?php echo $log_id; ?></td>
		</tr>
<?php		
	}
}
?>
</table>


