 <?php
	ini_set("session.cookie_httponly", 1);
	session_name ("calendar");
	session_start();
	include 'calendar_database.php';
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Calendar</title>
		<link rel = "stylesheet" href = "calendar.css">
		
		<!-- JQuery scripts--> 
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		 
		 <!--Preset Calendar scripts--> 
		 <script type="text/javascript" src="calendar.min.js"></script>
		<script type="text/javascript" src="calendar.js"></script>
		
		 <!--Personal scripts-->
		<script>var token="";</script>
		<script type="text/javascript" src="authentication.js"></script>
		<script type="text/javascript" src="cal-GraceEdit.js"></script>
	</head>
	<body>
		<h1>Calendar</h1>
		
		<div id="authentication">
			Username: <input type="text" id="username">
			Password: <input type="password" id="password">
			<input type="button" id="loginButton" value="Login"> 
			<input type="button" id="registerButton" value="Register">
		</div>
		
		<div id="messages"></div>
		
		<div id="category" class="calendar">
			Category:
			<select id="category_select">
				<option value="all">All</option>
				<option value="school">School</option>
				<option value="work">Work</option>
				<option value="social">Social</option>
				<option value="misc">Misc</option>
			</select>
		</div>
		
		<div id="changeCalendar" class="calendar">
			<button id="previous" type="button">Previous Month</button>
			<button id="next" type="button"> Next Month</button>
		</div>
		
		<div id="showCal" class="calendar">
		</div>
		
		<div id="eventModifications" title="Events" class="calendar">
			<input type="button" id="addEventButton" value="Add Event">
			<input type="button" id="deleteEventButton" value="Delete Event">
			<input type="button" id="editEventButton" value="Edit Event">
			<input type="button" id="shareEvent" value="Share Event">
			<input type="button" id="shareCalendar" value="Share Calendar">
		</div>
		
		<div id="eventListDialog">
			<div id="eventsList"></div>
		</div>
		
		<div id="createEventDialog" title="Create Event">
		
			Title: <input type="text" id="eventNameCreate">
			Date: <input id="eventDateCreate" type="date">
			Time: 
			<select id="selectTimeCreate">
				<option value="0:00:00">0:00</option>
				<option value="1:00:00">1:00</option>
				<option value="2:00:00">2:00</option>
				<option value="3:00:00">3:00</option>
				<option value="4:00:00">4:00</option>
				<option value="5:00:00">5:00</option>
				<option value="6:00:00">6:00</option>
				<option value="7:00:00">7:00</option>
				<option value="8:00:00">8:00</option>
				<option value="9:00:00">9:00</option>
				<option value="10:00:00">10:00</option>
				<option value="11:00:00">11:00</option>
				<option value="12:00:00">12:00</option>
				<option value="13:00:00">13:00</option>
				<option value="14:00:00">14:00</option>
				<option value="15:00:00">15:00</option>
				<option value="16:00:00">16:00</option>
				<option value="17:00:00">17:00</option>
				<option value="18:00:00">18:00</option>
				<option value="19:00:00">19:00</option>
				<option value="20:00:00">20:00</option>
				<option value="21:00:00">21:00</option>
				<option value="22:00:00">22:00</option>
				<option value="23:00:00">23:00</option>
			</select>
			Category:
			<select id="selectCategoryCreate">
				<option value="school">School</option>
				<option value="work">Work</option>
				<option value="social">Social</option>
				<option value="misc">Misc</option>
			</select>
			<br>
			<input id="createEventSubmit" type="button" value="Go">
		</div>
		
		<div id="editEventDialog" title="Edit Event">
			Old Title: <input type="text" id="eventNameOld">
			Old Date: <input id="eventDateOld" type="date">
			Old Time: 
			<select id="selectTimeOld">
				<option value="0:00:00">0:00</option>
				<option value="1:00:00">1:00</option>
				<option value="2:00:00">2:00</option>
				<option value="3:00:00">3:00</option>
				<option value="4:00:00">4:00</option>
				<option value="5:00:00">5:00</option>
				<option value="6:00:00">6:00</option>
				<option value="7:00:00">7:00</option>
				<option value="8:00:00">8:00</option>
				<option value="9:00:00">9:00</option>
				<option value="10:00:00">10:00</option>
				<option value="11:00:00">11:00</option>
				<option value="12:00:00">12:00</option>
				<option value="13:00:00">13:00</option>
				<option value="14:00:00">14:00</option>
				<option value="15:00:00">15:00</option>
				<option value="16:00:00">16:00</option>
				<option value="17:00:00">17:00</option>
				<option value="18:00:00">18:00</option>
				<option value="19:00:00">19:00</option>
				<option value="20:00:00">20:00</option>
				<option value="21:00:00">21:00</option>
				<option value="22:00:00">22:00</option>
				<option value="23:00:00">23:00</option>
			</select>
			Old Category:
			<select id="selectCategoryOld">
				<option value="school">School</option>
				<option value="work">Work</option>
				<option value="social">Social</option>
				<option value="misc">Misc</option>
			</select><br>
			
			New Title: <input type="text" id="eventNameNew">
			New Date: <input id="eventDateNew" type="date">
			New Time: 
			<select id="selectTimeNew">
				<option value="0:00:00">0:00</option>
				<option value="1:00:00">1:00</option>
				<option value="2:00:00">2:00</option>
				<option value="3:00:00">3:00</option>
				<option value="4:00:00">4:00</option>
				<option value="5:00:00">5:00</option>
				<option value="6:00:00">6:00</option>
				<option value="7:00:00">7:00</option>
				<option value="8:00:00">8:00</option>
				<option value="9:00:00">9:00</option>
				<option value="10:00:00">10:00</option>
				<option value="11:00:00">11:00</option>
				<option value="12:00:00">12:00</option>
				<option value="13:00:00">13:00</option>
				<option value="14:00:00">14:00</option>
				<option value="15:00:00">15:00</option>
				<option value="16:00:00">16:00</option>
				<option value="17:00:00">17:00</option>
				<option value="18:00:00">18:00</option>
				<option value="19:00:00">19:00</option>
				<option value="20:00:00">20:00</option>
				<option value="21:00:00">21:00</option>
				<option value="22:00:00">22:00</option>
				<option value="23:00:00">23:00</option>
			</select>
			New Category:
			<select id="selectCategoryNew">
				<option value="school">School</option>
				<option value="work">Work</option>
				<option value="social">Social</option>
				<option value="misc">Misc</option>
			</select><br>
			<input id="editEventSubmit" type="button" value="Go">
		</div>
		
		<div id="deleteEventDialog" title="Delete Event">
			Title: <input type="text" id="eventNameDelete"><br>
			Date: <input id="eventDateDelete" type="date"><br>
			Time: 
			<select id="selectTimeDelete">
				<option value="0:00:00">0:00</option>
				<option value="1:00:00">1:00</option>
				<option value="2:00:00">2:00</option>
				<option value="3:00:00">3:00</option>
				<option value="4:00:00">4:00</option>
				<option value="5:00:00">5:00</option>
				<option value="6:00:00">6:00</option>
				<option value="7:00:00">7:00</option>
				<option value="8:00:00">8:00</option>
				<option value="9:00:00">9:00</option>
				<option value="10:00:00">10:00</option>
				<option value="11:00:00">11:00</option>
				<option value="12:00:00">12:00</option>
				<option value="13:00:00">13:00</option>
				<option value="14:00:00">14:00</option>
				<option value="15:00:00">15:00</option>
				<option value="16:00:00">16:00</option>
				<option value="17:00:00">17:00</option>
				<option value="18:00:00">18:00</option>
				<option value="19:00:00">19:00</option>
				<option value="20:00:00">20:00</option>
				<option value="21:00:00">21:00</option>
				<option value="22:00:00">22:00</option>
				<option value="23:00:00">23:00</option>
			</select><br>
			<input id="deleteEventSubmit" type="button" value="Go">
		</div>
		
		<div id="shareEventDialog" title="Share Event">
			Who do you want to share your event with?
			<input id="shareEventName" type="text"><br><br>
			
			Title: <input type="text" id="eventNameShare"><br>
			Date: <input id="eventDateShare" type="date">
			Time: 
			<select id="selectTimeShare">
				<option value="0:00:00">0:00</option>
				<option value="1:00:00">1:00</option>
				<option value="2:00:00">2:00</option>
				<option value="3:00:00">3:00</option>
				<option value="4:00:00">4:00</option>
				<option value="5:00:00">5:00</option>
				<option value="6:00:00">6:00</option>
				<option value="7:00:00">7:00</option>
				<option value="8:00:00">8:00</option>
				<option value="9:00:00">9:00</option>
				<option value="10:00:00">10:00</option>
				<option value="11:00:00">11:00</option>
				<option value="12:00:00">12:00</option>
				<option value="13:00:00">13:00</option>
				<option value="14:00:00">14:00</option>
				<option value="15:00:00">15:00</option>
				<option value="16:00:00">16:00</option>
				<option value="17:00:00">17:00</option>
				<option value="18:00:00">18:00</option>
				<option value="19:00:00">19:00</option>
				<option value="20:00:00">20:00</option>
				<option value="21:00:00">21:00</option>
				<option value="22:00:00">22:00</option>
				<option value="23:00:00">23:00</option>
			</select><br>
			Category:
			<select id="selectCategoryShare">
				<option value="school">School</option>
				<option value="work">Work</option>
				<option value="social">Social</option>
				<option value="misc">Misc</option>
			</select>
			<br>
			
			<input id="shareEventSubmit" type="button" value="Share">
		</div>
		
		<div id="shareCalendarDialog" title="Share Calendar">
			Who do you want to share your calendar with?
			<input id="shareCalendarName" type="text">
			<input id="shareCalendarSubmit" type="button" value="Go">
		</div>
		
		<script>
			$(".calendar").hide(); <!-- hide calendar until successfully authenticated  -->
			$("#createEventDialog").hide();
			$("#editEventDialog").hide();
			$("#deleteEventDialog").hide();
		</script>
	
	</body>
</html>
















