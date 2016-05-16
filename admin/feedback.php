<?php
require_once "config.php";
require_once "functions.php";
$now = date("Y-m-d H:i:s",time()); 
$next = date("Y-m-d H:i:s",time()+86400); //add one day for next billing
$phone = $_REQUEST['to'];
$id = $_REQUEST['clientref'];
$synref = $_REQUEST['synref'];
$status = $_REQUEST['status'];
$from = $_REQUEST['from']; //mira id
$reason = $_REQUEST['reason'];
$service = get_service($id);
$que = mysql_query("UPDATE billing SET synref = '$synref',status = '$status',reason='$reason' WHERE id = '$id'");
if(strtolower($status) == 'billing_complete'){
     $quer = mysql_query("UPDATE billing SET next_billing = '$next',sending_order = sending_order+1 WHERE id = '$id'");
     $query = mysql_query("INSERT INTO payments (msisdn,service,date_) VALUES ('$phone','$service','$now')") or die(mysql_error());
}
?>
