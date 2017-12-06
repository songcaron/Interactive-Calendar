var today = new Date(); //initialize to current day and month

var currMonth = today.getMonth();
var currYear = today.getFullYear();
var currDay = 1;
		
$(document).ready(function() {
	 //start and display the calendar
	var calendar = new Calendar("showCal");
	calendar.showCurrent();

	//previous and next button
	$('#next').click(function() {
		calendar.nextMonth();
	});
	
	$('#previous').click(function() {
		calendar.previousMonth();
	});
	
	$("#showCal").on("click",".hover",getEvents);
			
	 
	$("#createEventSubmit").click(createEvent);
	$("#editEventSubmit").click(editEvent);
	$("#deleteEventSubmit").click(deleteEvent);
	$("#getEventsButton").click(getEvents);
	$("#shareEventSubmit").click(shareEvent);
	$("#shareCalendarSubmit").click(shareCalendar);
	
	$("#createEventDialog").dialog({
           title: 'Create Event',
           autoOpen: false,
           draggable: true,
           resizable: true,
		   width: 600
    });
	$("#addEventButton").click(function() {
		$("#createEventDialog").dialog('open');
	});
	
	$("#editEventDialog").dialog({
           title: 'Edit Event',
           autoOpen: false,
           draggable: true,
           resizable: true,
		   width: 1100
    });
	
	$("#editEventButton").click(function() {
		$("#editEventDialog").dialog('open');
	});
	
	$("#deleteEventDialog").dialog({
	   title: 'Delete Event',
	   autoOpen: false,
	   draggable: true,
	   resizable: true,
	   width: 400
    });
	
	$("#deleteEventButton").click(function() {
		$("#deleteEventDialog").dialog('open');
	});
	
	$("#eventListDialog").dialog({
	   title: 'Events',
	   autoOpen: false,
	   draggable: true,
	   resizable: true,
	   width: 500
    });
	
	$("#shareEventDialog").dialog({
	   title: 'Share Event',
	   autoOpen: false,
	   draggable: true,
	   resizable: true,
	   width: 450
    });
	
	$("#shareEvent").click(function() {
		$("#shareEventDialog").dialog('open');
	});
	
	$("#shareCalendarDialog").dialog({
	   title: 'Share Calendar',
	   autoOpen: false,
	   draggable: true,
	   resizable: true,
	   width: 450
    });
	
	$("#shareCalendar").click(function() {
		$("#shareCalendarDialog").dialog('open');
	});
});

var Calendar = function(calID) {
	this.calID = calID;
	this.dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
	this.monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	//set the date to current day and year
	this.currentMonth = currMonth;
	this.currentYear = currYear;
};

//next month
Calendar.prototype.nextMonth = function() {
	if (this.currentMonth == 11) //if current month is December
	{
		this.currentMonth = 0; //next month is January
		currMonth = this.currentMonth;
		
		this.currentYear = this.currentYear + 1; //year would increment as well
		currYear = this.currentYear;
	} else {
		this.currentMonth = this.currentMonth + 1;
		currMonth = this.currentMonth;
	}
	this.showCurrent();
};

//previous month
Calendar.prototype.previousMonth = function() {
	if (this.currentMonth === 0) {
		this.currentMonth = 11;
		currMonth = this.currentMonth;
		this.currentYear = this.currentYear - 1;
		currYear = this.currentYear; 
	} else {
		this.currentMonth = this.currentMonth - 1;
		currMonth = this.currentMonth;
	}
	this.showCurrent();
};

//show current month
Calendar.prototype.showCurrent = function() {
	this.showMonth(this.currentYear, this.currentMonth);
};

Calendar.prototype.showMonth = function(year, month) {
	//var currentDay = new Date();
	//first day of the week in selected month
	var firstDayofMonth = new Date(year, month, 1).getDay();
	//last day of selected month
	var lastDayofMonth = new Date(year, month + 1, 0).getDate();
		//last day of previous month
	var lastDayofPreviousMonth = month === 0 ? new Date(year - 1, 11, 0).getDate() : new Date(year, month, 0).getDate();
	var html = '<table>';
	
	//write selected month and year
	html += '<tr class = "headercolor"><td class = "weekendtable" colspan = "10">' + this.monthNames[month] + " " + year + '</td></tr>';
	//write header of the days of week
	html += '<tr class = "weekdaycolor">';
	for(var i = 0; i < this.dayNames.length; i++)
	{
		html += '<td class = "weekdaytable">' + this.dayNames[i] + '</td>';
	}
	html += '</tr>';

	//write the days 
	var num = 1;
	do {

		var writeDays = new Date(year, month, num).getDay();
		//if it's Sunday, start a new row
		if (writeDays === 0) {
			html += '<tr>';
		}
		//if it's not Sunday but the first day of the month, write the last days from the previous month
		else if (num == 1) {
			html += '<tr>';
			var count = lastDayofPreviousMonth - firstDayofMonth + 1;
			for (var j = 0; j < firstDayofMonth; j++) {
				html += '<td class = "not-current">' + count + '</td>';
				count++;
			}
		}

		html += '<td id = "'+num+'" class = "hover">' + num + '</td>';

		//if it's Saturday, ends the row
		if (writeDays == 6) {
			html += '</tr>';
		}
		//if it isn't Saturday, but last day of the current month, write the next few days from the next month
		else if (num == lastDayofMonth) {
			var count1 = 1;
			for (writeDays; writeDays < 6; writeDays++) {
				html += '<td class = "not-current">' + count1 + '</td>';
				count1++;
			}
		}
		num++;
	}
	while (num <= lastDayofMonth);

	//close the table
	html += '</table>';

	document.getElementById(this.calID).innerHTML = html;
};
		
		
function getEvents(e) {
	var category = document.getElementById("category_select").value;
	currDay = e.target.id; 
	
	var date = currYear+"-"+(currMonth+1)+"-"+currDay;
		
	$.ajax({
		url: 'getEvents.php',
		type: 'POST',
		data: {
			'date': date,
			'category': category
		},
		success: getEventsCallback,
		error: getEventsCallback
	});
}

function getEventsCallback(data) {	
	var eventsString = ""; 
	$("#eventListDialog").dialog('option', 'title', "Events for "+(currMonth+1)+"-"+currDay+"-"+ currYear);
	if(data.success) {
		var eventsArray = data.events; 
		
		if(eventsArray.length > 0) {
			for(var inside = 0; inside<eventsArray.length;inside++) {
				eventsString +=eventsArray[inside].time+": "+eventsArray[inside].title+" ("+eventsArray[inside].category+")<br>";
				$("#eventsList").html(eventsString);
			}
		}
		else {
			eventsString = "No events to display"; 
			$("#eventsList").html(eventsString);
		}
		$("#eventListDialog").dialog('open');
	}
	else {
		console.log(data);
	}
}

function createEvent() {
	var eventName = document.getElementById("eventNameCreate");
	var eventTime = document.getElementById("selectTimeCreate");	
	var eventDate = document.getElementById("eventDateCreate");
	console.log(eventDate.value);
	var eventCategory = document.getElementById("selectCategoryCreate");
	
	$.ajax({
		url: 'addEvent.php',
		type: 'POST',
		data: {
			'title': eventName.value,
			'date': eventDate.value,
			'time': eventTime.value,
			'category': eventCategory.value
		},
		success: createEventCallback,
		error: createEventCallback
	});
}

function createEventCallback(data) {
	if(data.success) {
		console.log("Successfully created event");
		$("#createEventDialog").dialog('close');
	}
	else {
		console.log(data.message);
	}
}

function editEvent() {
	var oldEventName = document.getElementById("eventNameOld");
	var newEventName = document.getElementById("eventNameNew");
	var oldEventDate = document.getElementById("eventDateOld");
	var newEventDate = document.getElementById("eventDateNew");
	var oldEventTime = document.getElementById("selectTimeOld");
	var newEventTime = document.getElementById("selectTimeNew");
	var oldEventCategory = document.getElementById("selectCategoryOld");
	var newEventCategory = document.getElementById("selectCategoryNew");
	
	$.ajax({
		url: 'editEvent.php',
		type: 'POST',
		data: {
			'oldTitle': oldEventName.value,
			'oldDate': oldEventDate.value,
			'oldTime': oldEventTime.value,
			'oldCategory': oldEventCategory.value,
			'newTitle': newEventName.value,
			'newDate': newEventDate.value,
			'newTime': newEventTime.value,
			'newCategory': newEventCategory.value,
			'token': token
		},
		success: editEventCallback,
		error: editEventCallback
	});
}

function editEventCallback(data) {	
	console.log(data);
	
	if(data.success) {
		console.log("Succssfully edited event");
		$("#editEventDialog").dialog('close');
		token = data.token;
	}
	else {
		console.log(data.message);
	}
}

function deleteEvent() {
	var eventName = document.getElementById("eventNameDelete");
	var eventTime = document.getElementById("selectTimeDelete");	
	var eventDate = document.getElementById("eventDateDelete");
	
	$.ajax({
		url: 'deleteEvent.php',
		type: 'POST',
		data: {
			'title': eventName.value,
			'date': eventDate.value,
			'time': eventTime.value,
			'token': token
		},
		success: deleteEventCallback,
		error: deleteEventCallback
	});
}

function deleteEventCallback(data) {
	console.log(data);
	
	if(data.success) {
		console.log("succssfully deleted event");
		$("#deleteEventDialog").dialog('close');
		token = data.token;
	}
	else {
		console.log(data.message);
	}
}

function shareEvent(e) {
	var eventName = document.getElementById("eventNameShare");
	var eventTime = document.getElementById("selectTimeShare");	
	var eventDate = document.getElementById("eventDateShare");
	var eventCategory = document.getElementById("selectCategoryShare");	
	var shareName = document.getElementById("shareEventName");
	
	$.ajax({
		url: 'shareEvent.php',
		type: 'POST',
		data: {
			'title': eventName.value,
			'date': eventDate.value,
			'time': eventTime.value,
			'category': eventCategory.value,
			'name': shareName.value
		},
		success: shareEventCallback,
		error: shareEventCallback
	});
	
}

function shareEventCallback(data) {
	if(data.success) {
		console.log("Event has been succesfully shared");
		$("#shareEventDialog").dialog('close');
	}
}

function shareCalendar() {
	var shareName = document.getElementById("shareCalendarName");
	
	$.ajax({
		url: 'shareCalendar.php',
		type: 'POST',
		data: {
			'name': shareName.value
		},
		success: shareCalendarCallback,
		error: shareCalendarCallback
	});
}

function shareCalendarCallback(data) {
	console.log(data);
	
	if(data.success) {
		console.log("Successfully shared calendar");
		$("#shareCalendarDialog").dialog('close');
	}
	else {
		console.log(data.message);
	}
}