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
	$date = mysql_real_escape_string(htmlentities($_POST['date']));
	$category = mysql_real_escape_string(htmlentities($_POST['category']));
	
	$eventsList = array(); 
	
	if($category == "all") {
		$stmt = $mysqli->prepare("select * from events where user_id_share=? and date=? order by time asc");
		$stmt-> bind_param('is', $userId,$date);
	}
	else {
		$stmt = $mysqli->prepare("select * from events where user_id_share=? and date=? and category=? order by time asc");
		$stmt-> bind_param('iss', $userId,$date,$category);
	}
	
	if(!$stmt)
	{
		echo json_encode(array(
			"success" => false,
			"message" => $mysqli->error
		));
		exit;
	}
	else{
		$stmt-> execute();
		$result = $stmt->get_result();

		//get results from query and add to array
		while($row=$result->fetch_assoc()) {
			$eventsList[] = $row;
		}
		
		echo json_encode(array(
			"success" => true,
			"events" => $eventsList
		));
		
		$stmt-> close();
		exit;
	}
?>