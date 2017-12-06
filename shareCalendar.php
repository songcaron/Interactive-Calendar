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
	$shareUsername = mysql_real_escape_string(htmlentities($_POST['name']));
	$shareUsernameId = -1; 
	$events = array(); 
	
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
	
	$stmt = $mysqli->prepare("select * from events where user_id_share=? order by time asc");
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	
	$stmt-> bind_param('i', $userId);
	
	$stmt-> execute();
	$result = $stmt->get_result();
	
	//get results from query and add to array
	while($row=$result->fetch_assoc()) {
		$events[] = "(".$userId.",".$shareUsernameId.",'".mysql_real_escape_string($row['title'])."','".mysql_real_escape_string($row['date'])."','".mysql_real_escape_string($row['time'])."','".mysql_real_escape_string($row['category'])."')";
	}
	
	$query_statement = 'replace into events (user_id,user_id_share,title,date,time,category) values '.implode(',',$events);
	
	$stmt = $mysqli->prepare($query_statement);
	if(!$stmt){
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	
	$stmt-> execute();
	$stmt-> close();
	
	echo json_encode(array(
		"success" => true,
		"events" => $query_statement
	));
	
	exit;
	
?>