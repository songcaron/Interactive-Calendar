<?php
ini_set("session.cookie_httponly", 1);	
require 'calendar_database.php';

header("Content-Type: application/json");

//***following code used from news_login.php	
$username = trim($_POST['username']);//strip whitespace from beginning and end of a string
$username = htmlspecialchars($username);//convert special char to html entities
$username = mysqli_real_escape_string($mysqli, $username);

$password = trim($_POST['password']);
$password = htmlspecialchars($password);
$password = mysqli_real_escape_string($mysqli, $password);

//password hashed/salted
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
if(password_verify($password, $hashed_password))
{
	$stmt = $mysqli->prepare("insert into users (username, password) values (?, ?)");
	if(!$stmt)
	{
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt-> bind_param('ss', $username, $hashed_password);
	$stmt-> execute();
	$stmt-> close();
	
	session_name ("calendar");
	session_start();
	
	$current_ua = $_SERVER['HTTP_USER_AGENT'];
	$_SESSION['useragent'] = $current_ua;
	
	
	$_SESSION['username'] = $username;
	$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));	
	
	$stmt = $mysqli->prepare("select id from users where username=?");
	if(!$stmt)
	{
		printf("Query Prep Failed: %s\n", $mysqli->error);
		exit;
	}
	$stmt-> bind_param('s', $username);
	$stmt-> execute();
	
	$stmt->bind_result($id);
	
	while($stmt->fetch()){
		$_SESSION['user_id'] = $id; 
	}	
	
	$stmt-> close();
	
	
	echo json_encode(array(
		"success" => true,
		"username" => $username,
		"token" => $_SESSION['token']
	));
	exit;
}
else {
	echo json_encode(array(
		"success" => false,
		"message" => "Unable to successfuly register"
	));
	exit;
}
//***end citation
?>