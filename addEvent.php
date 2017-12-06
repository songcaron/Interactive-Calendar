<?php 
	include 'calendar_database.php';
	header("Content-Type: application/json");
	ini_set("session.cookie_httponly", 1);
	session_name ("calendar");
	session_start();
	
	$userId = $_SESSION['user_id'];
	$title = mysql_real_escape_string(htmlentities($_POST['title']));
	$date = mysql_real_escape_string(htmlentities($_POST['date']));
	$time = mysql_real_escape_string(htmlentities($_POST['time']));
	$category = mysql_real_escape_string(htmlentities($_POST['category']));
	
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
		$stmt-> bind_param('iissss', $userId,$userId,$title,$date,$time,$category);
		$stmt-> execute();
		$stmt-> close();
		
		echo json_encode(array(
			"success" => true,
		));
		exit;
	}
?>