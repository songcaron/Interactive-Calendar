<?php
	include 'calendar_database.php';
	header("Content-Type: application/json");
	ini_set("session.cookie_httponly", 1);
	session_name ("calendar");
	session_start();
	
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
	$category = mysql_real_escape_string(htmlentities($_POST['category']));
	$shareUsername = mysql_real_escape_string(htmlentities($_POST['name']));
	$shareUsernameId = -1; 
	
	//get the correct id of the username that I want to share with
	$stmt = $mysqli->prepare("select id from users where username=? limit 1");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}

	
	$stmt-> bind_param('s', $shareUsername);
	
	$stmt->execute(); 
	$stmt->bind_result($id);
	
	while($stmt->fetch()) {
		$shareUsernameId = htmlentities($id);
	}
	
	$stmt->close(); 
	
	
	$stmt = $mysqli->prepare("insert into events (user_id,user_id_share,title,date,time,category) values (?,?,?,?,?,?)");
	if(!$stmt)
	{
		echo json_encode(array(
			"success" => false,
			"message" => $mysqli->error
		));
		exit;
	}
	else{
		$stmt-> bind_param('iissss', $userId,$shareUsernameId,$title,$date,$time,$category);
		$stmt-> execute();
		$stmt-> close();
		
		echo json_encode(array(
			"success" => true,
		));
		exit;
	}
?>