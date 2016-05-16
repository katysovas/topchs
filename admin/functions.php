<?php
function urlExistsInDb($url) {
   global $connect;
	$que = mysql_query("SELECT long_url AS url FROM short_urls WHERE long_url = '$url'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		return true;
	}
 }
 function getShorturl($url) {
   global $connect;
	$que = mysql_query("SELECT short_code AS url FROM short_urls WHERE long_url = '$url'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['url'];
		}
		return $nei;
	}
 }
function getLongurl($url) {
   global $connect;
	$que = mysql_query("SELECT long_url AS url FROM short_urls WHERE short_code = '$url'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['url'];
		}
		return $nei;
	}
 }
function insertUrlInDb($domain,$url) {
   global $connect;
    $date = date("Y-m-d H:i:s",time());
	$que = mysql_query("INSERT INTO short_urls (domain,long_url,date_created) VALUES ('$domain','$url','$date')") or die(mysql_error());
    $id = mysql_insert_id();
	return $id;
 }
function insertShortCodeInDb($id,$url) {
   global $connect;
	$que = mysql_query("UPDATE short_urls SET short_code = '$url' WHERE id = '$id'") or die(mysql_error());
 }
function list_files(){
	global $connect;
	$que = mysql_query("SELECT * FROM uploadcontent ORDER BY id ASC") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}

function change_page($url,$page){
    $page = "&page=".$page;
	$new_url = str_replace(strrchr($url,"&"),$page,$url);
    return $new_url;    
}
function verifyUrlExists($url) {
       $ch = curl_init();
       curl_setopt($ch, CURLOPT_URL, $url);                                                                                                                                                                                                                                                                                                                                                   
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                                                                                                                                                                                                                                                             
       $response = curl_exec($ch);
       curl_close($ch);
       return (!empty($response) && $response != 404);
    }
function validateUrlFormat($url) {
        return filter_var($url, FILTER_VALIDATE_URL,
            FILTER_FLAG_HOST_REQUIRED);
}
function random_string($length){
    $key = '';
    $keys = array_merge(range(0, 9), range('a','z'));
    for ($i = 0; $i < $length; $i++) {
        $key .= $keys[array_rand($keys)];
    }
    return $key;
}
function list_user($id){
	global $connect;
	$que = mysql_query("SELECT * FROM users WHERE id = '$id'",$connect) or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function listTemplate($id){
	global $connect;
	$que = mysql_query("SELECT * FROM templates WHERE id = '$id'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}

function show_user($id,$pass){
	global $connect;
	$que = mysql_query("SELECT * FROM users WHERE username= '$id' AND password='$pass' ORDER by id",$connect) or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function protect($val){
    $val = trim($val);
    $val = mysql_real_escape_string($val);
    $val = strip_tags($val);
    $val = htmlspecialchars($val);
	return $val;
}

function check_db_email($email){
	global $connect;
	$que = mysql_query("SELECT email FROM users where email='$email'",$connect) or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}else{
		return true;
	}
}
function check_user($username){
	global $connect;
	$que = mysql_query("SELECT * FROM users WHERE username='$username'",$connect) or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
function check_db_username($username){
   global $connect;
	$que = mysql_query("SELECT username FROM users WHERE username='$username'",$connect) or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}else{
		return true;
	}
}
function list_db_password($email){
   global $connect;
	$que = mysql_query("SELECT password FROM users WHERE email='$email'") or die(mysql_error());
	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei = $rs['password'];
		}
		return $nei;
	}
}
function createDateRange($startDate, $endDate, $format = "Y-m-d H:i:s"){
    $begin = new DateTime($startDate);
    $end = new DateTime($endDate);
    $range = array();
    $interval = new DateInterval('P1D'); // 1 Day
    $dateRange = new DatePeriod($begin, $interval, $end);
    foreach ($dateRange as $date) {
        array_push($range,$date->format($format));
    }
    return $range;
}
function selectResponse($keyword,$date,$ignoreDates){
    global $connect;
   	if ($ignoreDates)
		$que = mysql_query("SELECT * FROM templates WHERE keyword = '$keyword' AND ignoredates = 1 ORDER BY RAND() LIMIT 1") or die(mysql_error());
	else
		$que = mysql_query("SELECT * FROM templates WHERE keyword = '$keyword' AND DATE(date_) = '$date' AND ignoredates = 0 ORDER BY RAND() LIMIT 1") or die(mysql_error());

	if(mysql_num_rows($que) == 0){
		return false;
	}
    else{
		while($rs = mysql_fetch_assoc($que)){
			$nei[] = $rs;
		}
		return $nei;
	}
}
?>
