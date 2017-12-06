<?php 
	include 'calendar_database.php';
	header("Content-Type: application/json");
	ini_set("session.cookie_httponly", 1);
	session_name ("calendar");
	session_start();
	
	if(!hash_equals($_SESSION['token'], $_POST['token'])){
		die("Request forgery detected");
	}
	
	$previous_ua = @$_SESSION['useragent'];
	$current_ua = $_SERVER['HTTP_USER_AGENT'];
	 
	if(isset($_SESSION['useragent']) && $previous_ua !== $current_ua){
		die("Session hijack detected");
	}else{
		$_SESSION['useragent'] = $current_ua;
	}
	
	$userId = $_SESSION['user_id'];
	
	$oldTitle = mysql_real_escape_string(htmlentities($_POST['oldTitle']));
	$oldTime = mysql_real_escape_string(htmlentities($_POST['oldTime']));
	$oldDate = mysql_real_escape_string(htmlentities($_POST['oldDate']));
	$oldCategory = mysql_real_escape_string(htmlentities($_POST['oldCategory']));
	
	$newTitle = mysql_real_escape_string(htmlentities($_POST['newTitle']));
	$newTime = mysql_real_escape_string(htmlentities($_POST['newTime']));
	$newDate = mysql_real_escape_string(htmlentities($_POST['newDate']));
	$newCategory = mysql_real_escape_string(htmlentities($_POST['newCategory']));
	
	//first check that you own the event, if not exit
	$stmt = $mysqli->prepare("select user_id from events where user_id_share=? and title=? and date=? and time=?");
	$stmt-> bind_param('isss', $userId,$oldTitle,$oldDate,$oldTime);
	
	$stmt-> execute();
	
	$stmt->bind_result($eventUserId);
	while($stmt->fetch()) {
		if($eventUserId != $userId) {
			echo json_encode(array(
				"success" => false,
				"message" => "Cannot edit the event of another user"
			));
			$stmt->close();
			exit;
		}
	}
	
	$stmt = $mysqli->prepare("update events set title=?,date=?,time=?,category=? where user_id=? and title=? and date=? and time=? and category=?");
	
	if(!$stmt)
	{
		echo json_encode(array(
			"success" => false,
			"message" => $mysqli->error
		));
		exit;
	}
	else{
		$stmt-> bind_param('ssssissss', $newTitle,$newDate,$newTime,$newCategory,$userId,$oldTitle,$oldDate,$oldTime,$oldCategory);
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