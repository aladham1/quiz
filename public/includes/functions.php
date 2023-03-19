<?php
date_default_timezone_set("Asia/Kolkata");
//include_once('includes/system_connection.php');
ini_set('memory_limit', '-1');
function getSelected($select, $name){
	if($name == $select){
		return 'class="selected"';
	}
	else{
	return '';	
	}
}
function arrdiff($a1, $a2) {
  $res = array();
  foreach($a2 as $a) if (array_search($a, $a1) === false) $res[] = $a;
  return $res;
}
function insertData($con,$table_name, $post){
$sql_fetch=mysqli_query($con,"SHOW COLUMNS FROM $table_name"); 
$columns = array();
while($row=mysqli_fetch_assoc($sql_fetch)){
	array_push($columns, $row['Field']);
}
$values = array();
foreach($post as $key=>$val)
{

	if(in_array($key, $columns)){
	$temp = '`'.$key.'`="'.mysqli_real_escape_string($con,trim($val)).'"';
	array_push($values, $temp);
			}
	
}
try{
mysqli_query($con,'INSERT INTO `'.$table_name.'` set '.implode(', ', $values));

return true;
}
catch(Exception $e){
return false;	
}
}
function updateInfo($con,$table_name, $post, $field, $match){
$sql_fetch=mysqli_query($con,"SHOW COLUMNS FROM $table_name");
$columns = array();
while($row=mysqli_fetch_assoc($sql_fetch)){
	array_push($columns, $row['Field']);
}
$values = array();
foreach($post as $key=>$val)
{

	if(in_array($key, $columns)){
	$temp = $key.'="'.mysqli_real_escape_string($con,trim($val)).'"';
	array_push($values, $temp);
			}
	
}
try{
mysqli_query($con,'UPDATE '.$table_name.' SET '.implode(', ', $values).' WHERE '.$field.'="'.$match.'"');
return true;
}
catch(Exception $e){
return false;	
}
}
function uploadErrors($err_code) {
	switch ($err_code) { 
        case UPLOAD_ERR_INI_SIZE: 
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini'; 
        case UPLOAD_ERR_FORM_SIZE: 
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form'; 
        case UPLOAD_ERR_PARTIAL: 
            return 'The uploaded file was only partially uploaded'; 
        case UPLOAD_ERR_NO_FILE: 
            return 'No file was uploaded'; 
        case UPLOAD_ERR_NO_TMP_DIR: 
            return 'Missing a temporary folder'; 
        case UPLOAD_ERR_CANT_WRITE: 
            return 'Failed to write file to disk'; 
        case UPLOAD_ERR_EXTENSION: 
            return 'File upload stopped by extension'; 
        default: 
            return 'Unknown upload error'; 
} 
}
function getZeroAddedNumber($string, $length){
$return = '';
$len = (int)$length;
$str_length = strlen((string)$string);
$zeros = $len - $str_length;
if($zeros > 0){
	$return = str_repeat('0', $zeros).$string;
}
else{
	$return = $string;
}
return $return;
}
function getRandomBinaryNumber($length){
	$len = (int)$length;
	$return = '';
	for($i=1; $i<=$len; $i++){
		$return .= rand(0, 1);
	}
	return (string)$return;
}
function trimElemnt($el){
	return str_replace(' ', '_', trim($el));
}
function checkMembershipCompletion(){
	$id = $_SESSION['uid'];
	$check_sql = mysql_query('SELECT membership_complete FROM members WHERE id='.mysql_real_escape_string($id).'');
	$row_mem = mysql_fetch_assoc($check_sql);
	if($row_mem['membership_complete'] != '1'){
		echo '<script>
		alert("Please complete your profile to access this");
		window.location="?q=members/comepleteYourProfileForm/";
		</script>';
		die();
	}
}
function resizeImage($ob, $width, $height, $image, $savename){
	$ob->load($image);
   	$ob->resize($width, $height);
    $ob->save($savename);
}
function get_url ( $part='' ) {
$script = $_SERVER['SCRIPT_NAME'];

$dir = strstr($script, 'index.php', true);
if($dir == ''){
	$dir = strstr($script, 'index.php', true);
}
$server = $_SERVER['SERVER_NAME'];
if( $part == '' ){
return 'http://'.$server.$dir;
}
else {

return 'http://'.$server.$dir.$part;
}
}
/// =========================STARTING FROM THIS SECTION==========

function time_spend($ptime)
{
    $etime = time() - $ptime;

    if ($etime < 1)
    {
        return '0 seconds';
    }

    $a = array( 12 * 30 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60       =>  'month',
                24 * 60 * 60            =>  'day',
                60 * 60                 =>  'hour',
                60                      =>  'minute',
                1                       =>  'second'
                );

    foreach ($a as $secs => $str)
    {
        $d = $etime / $secs;
        if ($d >= 1)
        {
            $r = round($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '') . ' ago';
        }
    }
}


function checkUserStarRating($user_id){
	$query_check=mysql_query("select user_id from rating where user_id=$user_id and rating_from='".$_SESSION['user_id']."'");
	$count=mysql_num_rows($query_check);
	if($count>0)
	{
	//return 'Rated';
	}
	else
	{
	return  '<div id="star" name="'.$user_id.'"></div>';	
	}
}

function checkClubStarRating($club_id){
	$query_check_club_rating=mysql_query("select id from club_ratting where club_id=$club_id and rating_from='".$_SESSION['user_id']."'");
	$count_club_rating=mysql_num_rows($query_check_club_rating);
	if($count_club_rating>0)
	{
	//return 'Rated';
	}
	else
	{
	return  '<div id="clubstar" name="'.$club_id.'"></div>';	
	}
}

function checkConnectionStatus($user_id){
	$query_check=mysql_query("select * from friend_request where (request_from='".$_SESSION['user_id']."' and request_to='$user_id') or (request_to='".$_SESSION['user_id']."' and request_from='$user_id')");
	$count=mysql_num_rows($query_check);
	if($count>0)
	{
		$status_row=mysql_fetch_array($query_check);
		$status=$status_row['status'];
		
		$request_from=$status_row['request_from'];
		$request_to=$status_row['request_to'];
		if($request_from==$user_id && $request_to==$_SESSION['user_id'] && $status==0)
		{
			return  '<div class="dropdown" id="dropdown"><a class="dropdown_button dropdown_open">Confirm Connection </a><ul class="dropdown_content"><li><a name="'.$user_id.'" id="1" onclick="acceptRequest(this.name, this.id);">Accept</a></li><li><a name="'.$user_id.'" id="0" onclick="acceptRequest(this.name, this.id);">Reject</a></li></ul></div>';
		}
		
		if($request_from==$_SESSION['user_id'] && $request_to==$user_id && $status==0)
		{
			return  '<div class="dropdown" id="dropdown"><a class="dropdown_button dropdown_open" href="#dropdown">Connection Send </a><ul class="dropdown_content"><li><a href="#">OK</a></li><li><a id="connection_cancel" name="'.$user_id.'" onclick="cancelRequest(this.name);">Cancel</a></li></ul></div>';
		}
		if($status==1)
		{
			return  '<div class="dropdown" id="dropdown"><a class="dropdown_button dropdown_open" href="#dropdown">Connected</a><ul class="dropdown_content"><li><a href="#">OK</a></li><li><a name="'.$user_id.'" id="0" onclick="disconnectConnection(this.name, this.id);">Disconnect</a></li></ul></div>';
		}
	
	//return 'Rated';
	}
	else
	{
	return  '<div id="connection_btn" style="cursor:pointer;" ><div class="dropdown" id="dropdown"><a class="dropdown_button " name="'.$user_id.'" onclick="sendRequest(this.name);">Connect Now</a></div></div>';	
	}
}

function pushNotification($notification_to, $notification_type){//$notification_type=1 for connection request
	if($notification_type==1)
	{
		$notification_message='Send You A Connection.';
		$insert_notification=mysql_query("insert into notification (`notification_to`, `notification_from`, `notification_message`, `type`) values ('$notification_to', '".$_SESSION['user_id']."', '$notification_message', '$notification_type')");
	}
	if($notification_type==2)
	{
		$score=$_SESSION['score'];
		$notification_message='Give You '.$score.' Star as Rating.';
		$insert_notification=mysql_query("insert into notification (`notification_to`, `notification_from`, `notification_message`, `type`) values ('$notification_to', '".$_SESSION['user_id']."', '$notification_message', '$notification_type')");
		unset($_SESSION['score']);
	}
}


function countNotification($notification_type){//$notification_type=1 for connection request
	if($notification_type==1)
	{
		
		$query_notification=mysql_query("select id from notification where notification_to='".$_SESSION['user_id']."' and view=0");
		$count_notification=mysql_num_rows($query_notification);
		if($count_notification>0)
		{
		return '<font color="red">('.$count_notification.')</a>';	
		}
	}
	if($notification_type==2)
	{
		
		$query_connection_notification=mysql_query("select id from friend_request where request_to='".$_SESSION['user_id']."' and status=0");
		$count_connection_notification=mysql_num_rows($query_connection_notification);
		if($count_connection_notification>0)
		{
		return '<font color="red">('.$count_connection_notification.')</a>';	
		}
	}
}


function countConnectionRequest(){
	
	$query_connection_notification=mysql_query("select id from friend_request where request_to='".$_SESSION['user_id']."' and status=0 and view=0");
		$count_connection_notification=mysql_num_rows($query_connection_notification);
		if($count_connection_notification>0)
		{
		return '<div class="noti_bubble">'.$count_connection_notification.'</div>';	
		}
	
}
function countContactUnlock($userid){
	$query_check=mysql_query("select id from contact_unlock_request where request_to='".$_SESSION['user_id']."' and view=0");
	$count_no=mysql_num_rows($query_check);
	if($count_no>0)
	{
	return 	'<div id="count_contact">&nbsp;<b>'.$count_no.'&nbsp;</b></div>';
	}
}

function countProfileVisitor($userid){
	$query_check_visitor=mysql_query("select id from profile_visitors where visit_to='".$_SESSION['user_id']."' and view=0");
	$count_visitor=mysql_num_rows($query_check_visitor);
	if($count_visitor>0)
	{
	return 	'<div id="count_contact">&nbsp;<b>'.$count_visitor.'&nbsp;</b></div>';
	}
}

function contactSettings($userid, $mode){
		
		
		$query_check=mysql_query("select $mode from contact_unlock_request where request_to='$userid' and request_from='".$_SESSION['user_id']."'");
		$count_check=mysql_num_rows($query_check);
		if($count_check>0)
		{
			$contact_info=mysql_fetch_assoc($query_check);
			$mode_status=$contact_info[$mode];
			if($mode_status=='0')
			{
			return '<div id="samep"><img src="'.get_url("images/lock.png").'" /><div id="design_'.$mode.'"><a href="javascript:;" title="Edit User" class="edituser" id="'.$userid.'" name="'.$mode.'" onclick="unlockPhone(this.id, this.name);"> Ask to Unlock</div></div>';	
			}
			elseif($mode_status=='1')
			{
				return '<img src="'.get_url("images/lock.png").'" />Request Send.';
			}
			elseif($mode_status=='2')
			{
				$query_mode=mysql_query("select $mode from user_registration where user_id='$userid'");
				$contact_info=mysql_fetch_assoc($query_mode);
				$mode_no=$contact_info[$mode];
				return $mode_no;
				
			}
		
		}
		else
		{
		$query_settings=mysql_query("select ".$mode."_settings from user_settings where user_id='$userid'");
		$count_settings=mysql_num_rows($query_settings);
		if($count_settings>0)
		{
		$contact_info=mysql_fetch_assoc($query_settings);
		$mode_settings=$contact_info[$mode.'_settings'];
			if($mode_settings==0)
			{
				$query_mode_type=mysql_query("select $mode from user_registration where user_id='$userid'");
				$contact_info=mysql_fetch_assoc($query_mode_type);
				$mode_no=$contact_info[$mode];
				return $mode_no;
			}
			else
			{
				return '<div id="samep"><img src="'.get_url("images/lock.png").'" /><div id="design_'.$mode.'"><a href="javascript:;" title="Edit User" class="edituser" id="'.$userid.'" name="'.$mode.'" onclick="unlockPhone(this.id, this.name);"> Ask to Unlock</div></div>';
			}
		
		}
		}
		
}



function calculateFullRating($userid){
	$query_count = mysql_query("SELECT id FROM rating where user_id='$userid'"); 
	$count_rating_users=mysql_num_rows($query_count);
	//$query_rating=mysql_query("select SUM (rate) AS totalrating from rating where user_id='$userid'");
	$result = mysql_query("SELECT SUM(rate) AS value_sum FROM rating where user_id='$userid'"); 
$row = mysql_fetch_assoc($result); 
$sum = $row['value_sum'];
	//$count_user=mysql_num_rows($query_rating);
	
	if($sum!="")
	{
		$total_rating=floatval ((intval($sum))/(intval($count_rating_users)));
	return 	$total_rating;
	}
	else
	{
		return 	"No Rating";
	}
	
}
function profileVisitor($userid){
	$query_visitor = mysql_query("SELECT id FROM profile_visitors where visit_from='".$_SESSION['user_id']."' and visit_to='$userid'"); 
	$count_visitor=mysql_num_rows($query_visitor);
	//$query_rating=mysql_query("select SUM (rate) AS totalrating from rating where user_id='$userid'");
	if($count_visitor>0)
	{
		$time=time();
		$query_update = mysql_query("update profile_visitors set time='$time', total_visit=(total_visit+1) where visit_from='".$_SESSION['user_id']."' and visit_to='$userid'");
	}
	else
	{
	$time=time();
		$query_update = mysql_query("insert into  profile_visitors (`visit_from`, `visit_to`, `time`, `total_visit`) values ('".$_SESSION['user_id']."', '$userid', '$time', '1')");
	}
	
}

function siteNotice($userid){
	
	$query_setting=mysql_query("select * from site_settings");
	$settings_row=mysql_fetch_assoc($query_setting);
	$account_verify_notice=$settings_row['account_verify_notice'];
	$club_verify_notice=$settings_row['club_verify_notice'];
	
	$query_user_setting=mysql_query("select verify_mobile, verify_email from user_registration where user_id='10'");
	$user_settings_row=mysql_fetch_assoc($query_user_setting);
	$verify_mobile=$user_settings_row['verify_mobile'];
	$verify_email=$user_settings_row['verify_email'];
	if($verify_mobile=='0' && $account_verify_notice=='1')
	{
	return '<div class="page_notice">Please Verify Your Mobile Number to be a Verified Member.<a href="">Click Here </a> To Verify Your Mobile Number.</div>';	
	}
	if($verify_mobile=='0' && $club_verify_notice=='1')
	{
	return '<div class="page_notice">Please Verify Your Club Mobile Number to be a Verified Club.<a href="">Click Here </a> To Verify Your Mobile Number.</div>';	
	}
	
}

function clubVerifiedCheck($clubid){
	
	$query_club_verify=mysql_query("select verified from club_settings where club_id='$clubid'");
	$verify_info=mysql_fetch_assoc($query_club_verify);
	$verified=$verify_info['verified'];
	if($verified=='1')
	{
		return '<img src="'.get_url("images/574964-Tick-16.png").'" height="12" width="12" /> VERIFIED';
	}
	else
	{
		return '<img src="'.get_url("images/unable-16.png").'" height="12" width="12" /> NOT VERIFIED';
	}
	
	
}

function clubMemberCheck($clubid){
	
	$query_club_member=mysql_query("select joining_approved from club_member where club_id='$clubid' and joining_request_id='".$_SESSION['user_id']."'");
	$count_member=mysql_num_rows($query_club_member);
	if($count_member>0)
	{
	$member_info=mysql_fetch_assoc($query_club_member);
	$joining_approved=$member_info['joining_approved'];
			if($joining_approved=='1')
			{
				return '<div class="clubjoing_btn" ><a class="dropdown_button " >Member</a><ul class="dropdown_content"><li><a id="connection_cancel" name="'.$clubid.'" onclick="leaveClub(this.name);">Leave Club</li></ul></div>';
			}
			elseif($joining_approved=='0')
			{
				return '<div class="clubjoing_btn" ><a class="dropdown_button " >Request Send</a><ul class="dropdown_content"><li><a id="connection_cancel" name="'.$clubid.'" onclick="cancelClubJoinRequest(this.name);">Cancel Request</li></ul></div>';
			}
	}
	else
	{
		return '<div class="clubjoing_btn" ><a class="dropdown_button " name="'.$clubid.'" onclick="clubJoinRequest(this.name);">Ask to Join</a></div>';
	}
	
}
function checkEventAttempt($eventid){
	
	$query_event_attempt=mysql_query("select id from event_attempt where event_id='$eventid' and user_id=".$_SESSION['user_id']);
	$count_event_attempt=mysql_num_rows($query_event_attempt);
	if($count_event_attempt>0)
	{
		
	}
	else
	{
		return ''.'<input type="submit" id="styled-button-semiwhite"  value="Attempt" class="event_attempt" name="1" eventid="'.$eventid.'" />&nbsp;<input type="submit" id="styled-button-semiwhite"  value="Maybe" class="event_attempt" name="2" eventid="'.$eventid.'" />&nbsp;<input type="submit" id="styled-button-semiwhite"  value="Not Interested" class="event_attempt" name="0" eventid="'.$eventid.'" />'.'';
		
	}
	
}
function clubVisitors($clubid){
	
	$query_user_exist_check=mysql_query("select id from club_visitors where user_id='".$_SESSION['user_id']."' and club_id='$clubid'");
	$count_user_check=mysql_num_rows($query_user_exist_check);
	if($count_user_check>0)
	{
		
	}
	else
	{
		$insert_user=mysql_query("insert into club_visitors (`user_id`, `club_id`) values ('".$_SESSION['user_id']."', '$clubid')");
	}
	$query_user_exist=mysql_query("select id from club_visitors where club_id='$clubid'");
	$count_user=mysql_num_rows($query_user_exist);
	return 'Visitors : '.$count_user;
	
}



function userPoint($star_rate){
	
	if($star_rate=='1')
	{
		return -10;
	}
	elseif($star_rate=='2')
	{
		return 10;
	}
	elseif($star_rate=='3')
	{
		return 50;
	}
	elseif($star_rate=='4')
	{
		return 100;
	}
	elseif($star_rate=='5')
	{
		return 150;
	}
	
}

function clubPoint($star_rate){
	
	if($star_rate=='1')
	{
		return -20;
	}
	elseif($star_rate=='2')
	{
		return 40;
	}
	elseif($star_rate=='3')
	{
		return 100;
	}
	elseif($star_rate=='4')
	{
		return 200;
	}
	elseif($star_rate=='5')
	{
		return 350;
	}
	
}

function selected($value , $match){
	if($value == $match){
		return 'selected="selected"';
	}
	else{
		return '';	
	}
}

function checked($value , $match){
	if($value == $match){
		return 'checked="checked"';
	}
	else{
		return '';	
	}
}

function encode_url($string){
	 $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $string)); // Removes special chars.
}



function send_mail($to,$subject,$message){
		$email = 'noreply@cainfracon.org';
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		// More headers
		$headers .= 'From: <'.$email. ">\r\n";

		$headers .= "Reply-To: ".$email."\r\n";
		$headers .= "Return-Path: ".$email."\r\n";

		$headers.= "X-Priority: 1\r\n"; 


		$mail_send=mail($to,$subject,$message,$headers);
	if($mail_send){
		return 1;
	}else{
		return 0;
	}
}

function convertYoutube($string,$width='') {
	
    return preg_replace(
        "/\s*[a-zA-Z\/\/:\.]*youtu(be.com\/watch\?v=|.be\/)([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i",
        "<iframe src=\"//www.youtube.com/embed/$2?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0\" allow='accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture' width='".$width."'  allowfullscreen></iframe>",
        $string
    );
}


// Compress image
function compressImage($source, $destination, $quality) {

	$info = getimagesize($source);
	
	if ($info['mime'] == 'image/jpeg'){
		$image = imagecreatefromjpeg($source);
		$exif = exif_read_data($source); //echo 'or'.$exif['Orientation'];
			if(!empty($exif['Orientation'])) {
				switch($exif['Orientation']) {
				case 8:
					$image = imagerotate($image,90,0);
					break;
				case 3:
					$image = imagerotate($image,180,0);
					break;
				case 6:
					$image = imagerotate($image,-90,0);
					break;
				default:
						
					break;
			}
		}
	}
	elseif ($info['mime'] == 'image/gif'){
	  $image = imagecreatefromgif($source);
	}
	elseif ($info['mime'] == 'image/png'){
	  $image = imagecreatefrompng($source);
	}
	
  
	  imagejpeg($image, $destination, $quality);
  
  }

function uniord($u) {
    // i just copied this function fron the php.net comments, but it should work fine!
    $k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
    $k1 = ord(substr($k, 0, 1));
    $k2 = ord(substr($k, 1, 1));
    return $k2 * 256 + $k1;
}
function is_arabic($str) {
    if(mb_detect_encoding($str) !== 'UTF-8') {
        $str = mb_convert_encoding($str,mb_detect_encoding($str),'UTF-8');
    }

    /*
    $str = str_split($str); <- this function is not mb safe, it splits by bytes, not characters. we cannot use it
    $str = preg_split('//u',$str); <- this function woulrd probably work fine but there was a bug reported in some php version so it pslits by bytes and not chars as well
    */
    preg_match_all('/.|\n/u', $str, $matches);
    $chars = $matches[0];
    $arabic_count = 0;
    $latin_count = 0;
    $total_count = 0;
    foreach($chars as $char) {
        //$pos = ord($char); we cant use that, its not binary safe 
        $pos = uniord($char);
        //echo $char ." --> ".$pos.PHP_EOL;

        if($pos >= 1536 && $pos <= 1791) {
            $arabic_count++;
        } else if($pos > 123 && $pos < 123) {
            $latin_count++;
        }
        $total_count++;
    }
    if(($arabic_count/$total_count) > 0.6) {
        // 60% arabic chars, its probably arabic
        return true;
    }
    return false;
}

function is_turkish($str) {
    if(mb_detect_encoding($str) !== 'UTF-8') {
        $str = mb_convert_encoding($str,mb_detect_encoding($str),'UTF-8');
    }

    /*
    $str = str_split($str); <- this function is not mb safe, it splits by bytes, not characters. we cannot use it
    $str = preg_split('//u',$str); <- this function woulrd probably work fine but there was a bug reported in some php version so it pslits by bytes and not chars as well
    */
    preg_match_all('/.|\n/u', $str, $matches);
    $chars = $matches[0];
    $arabic_count = 0;
    $latin_count = 0;
    $total_count = 0;
    foreach($chars as $char) {
        //$pos = ord($char); we cant use that, its not binary safe 
        $pos = uniord($char);
        //echo $char ." --> ".$pos.PHP_EOL;

        if($pos == 199 || $pos == 286 || $pos == 304 || $pos == 214 || $pos == 350 || $pos == 220) {
            $arabic_count++;
        } else if($pos > 123 && $pos < 123) {
            $latin_count++;
        }
        $total_count++;
    }
    if(($arabic_count/$total_count) > 0.6) {
        // 60% arabic chars, its probably arabic
        return true;
    }
    return false;
}

?>
