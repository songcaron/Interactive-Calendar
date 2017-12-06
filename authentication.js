//check if the page has been reloaded. If so, then logout 
if (window.performance.navigation.type == 1) {
  console.info( "This page is reloaded" );
  logoutAjax();
}

$(document).ready(function() {
	
	$("#loginButton").click(loginAjax);
	$("#registerButton").click(registerAjax);
	$("#messages").on("click","#logoutButton",logoutAjax);
});

function loginAjax(e) {
	var username = document.getElementById("username").value; 
	var password = document.getElementById("password").value; 
		
	$.ajax({
		url: 'calendar_login.php',
		type: 'POST',
		data: {
			'username': username,
			'password': password
		},
		success: loginCallback,
		error: loginCallback
	});
}

function loginCallback(data) {
	console.log("Login was called");
	console.log(data);
	
	if(data.success) {
		//get remaining data from json
		$("#authentication").hide(500);
		$(".calendar").show(500);
		$("#messages").html("Welcome, "+data.username+" <input id='logoutButton' type=button value='Logout'>");
		$("#messages").show(500);
		
		token = data.token;
	}
	else {	
		console.log("You have not logged in");
		console.log(data.message);
	}
}

function registerAjax(e) {
	var username = document.getElementById("username").value; 
	var password = document.getElementById("password").value; 	
	
	$.ajax({
		url: 'calendar_register.php',
		type: 'POST',
		data: {
			'username': username,
			'password': password
		},
		success: registerCallback, 
		error: registerCallback
	});
}

function registerCallback(data) {
	console.log("Register was called");
		
	if(data.success) {
		// get remaining data from json and unhide appropiate divs
		$("#authentication").hide(500);
		$(".calendar").show(500);
		$("#messages").html("Welcome, "+data.username+" <input id='logoutButton' type=button value='Logout'>");
		$("#messages").show(500);
		
		token = data.token;
	}
	else {
		alert("You have not registered");
		console.log(data.message);
	}
}

function logoutAjax() {
	$.ajax({
		url: 'calendar_logout.php',
		type: 'POST',
		success: logoutCallback,
		error: logoutCallback
	});
}

function logoutCallback(data) {
	console.log("Logout was called");
	
	if(data.success) {
		//get remaining data from json and unhide/hide appropiate divs
		$("#authentication").show(500);
		$(".calendar").hide(500);
		$("#messages").hide(500);
	}
	else {
		console.log("You have not logout");
		console.log(data.message);
	}
}