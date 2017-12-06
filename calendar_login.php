<?php
header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
require 'calendar_database.php';
ini_set("session.cookie_httponly", 1);

// Check to see if the username and password are valid.

//***Following code reused from news_login.php***
$username = trim($_POST['username']);//strip whitespace from beginning and end of a string
$username = htmlspecialchars($username);//convert special char to html entities
$username = mysqli_real_escape_string($mysqli, $username);

$password = trim($_POST['password']);
$password = htmlspecialchars($password);
$password = mysqli_real_escape_string($mysqli, $password);
	
$stmt = $mysqli->prepare("SELECT * FROM users WHERE username=?");

// Bind the parameter
$stmt->bind_param('s', $username);
$stmt->execute();

// Bind the results
$stmt->bind_result($user_id,$user_name, $pwd_hash);
$stmt->fetch();
//***end citation of code***
 
if(password_verify($password, $pwd_hash)){
	session_name ("calendar");
	session_start();
	
	$current_ua = $_SERVER['HTTP_USER_AGENT'];
	$_SESSION['useragent'] = $current_ua;
	
	
	$stmt-> close();
	
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
}else{
	echo json_encode(array(
		"success" => false,
		"message" => "Incorrect Username or Password"
	));
	exit;
}
?>