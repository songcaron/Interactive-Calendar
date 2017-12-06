<?php 
	include 'calendar_database.php';
	header("Content-Type: application/json");
	ini_set("session.cookie_httponly", 1);
	session_name ("calendar");
	session_start();
	
	//figure out how to get same request token when initialzing calendar
	if(!hash_equals($_SESSION['token'], $_POST['token'])){
		die($_POST['token']."Request forgery detected  ".$_SESSION['token']);
	}
	
	$previous_ua = @$_SESSION['useragent'];
	$current_ua = $_SERVER['HTTP_USER_AGENT'];
	 
	if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
		die("Session hijack detected");
	}else{
		$_SESSION['useragent'] = $current_ua;
	}
	
	$userId = $_SESSION['user_id'];
	$title = mysql_real_escape_string(htmlentities($_POST['title']));
	$date = mysql_real_escape_string(htmlentities($_POST['date']));
	$time = mysql_real_escape_string(htmlentities($_POST['time']));

	//first check that you own the event, if not exit
	$stmt = $mysqli->prepare("select user_id from events where user_id_share=? and title=? and date=? and time=?");
	$stmt-> bind_param('isss', $userId,$title,$date,$time);
	
	$stmt-> execute();
	
	$stmt->bind_result($eventUserId);
	while($stmt->fetch()) {
		if($eventUserId != $userId) {
			echo json_encode(array(
				"success" => false,
				"message" => "Cannot delete the event of another user"
			));
			exit;
		}
	}
	
	$stmt = $mysqli->prepare("delete from events where user_id=? and title=? and date=? and time=?");
	
	if(!$stmt)
	{
		echo json_encode(array(
			"success" => false,
			"message" => $mysqli->error
		));
		exit;
	}
	else{
		$stmt-> bind_param('isss', $userId,$title,$date,$time);
		$stmt-> execute();
		$stmt-> close();
		
		//update session token
		$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
		echo json_encode(array(
			"success" => true,
			"token" => $_SESSION['token']
		));
		exit;
	}
?>