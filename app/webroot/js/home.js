function changeType(roundTrip) {
	if(roundTrip) {
		$("#scheduleReturn").removeAttr("disabled");
		fillSchedule();
	} else {
		$("#scheduleReturn").attr("disabled", true);
	}
}

function enableConfirm(id) {
	var fields = ["Carrier", "Date", "Airports", "Time", "Price"];
	
	$("#modalFlightsDetails p").html("");
	
	for(var i in fields) {
		$("#modalFlightsDetails #outbound" + fields[i]).html($("#outboundFlight" + id + " #outbound" + fields[i]).html());
		$("#modalFlightsDetails #return" + fields[i]).html($("#returnFlight" + id + " #return" + fields[i]).html());
	}
	
	if($("[value='round-trip']:checked").length) {
		if($("[name='outboundFlight']:checked").length && $("[name='returnFlight']:checked").length) {
			$("#confirmButton").removeAttr("disabled").removeClass("disabled");
		}
	} else {
		if($("[name='outboundFlight']:checked").length) {
			$("#confirmButton").removeAttr("disabled").removeClass("disabled");
		}
	}
}

function fillArrivals() {
	var departure = $("#departure").selectpicker("val");
	
	if(departure != "") {
		$.get("/TuiParser/getArrivals/" + departure, function(data) {
			if(data) {
				var routes = JSON.parse(data);

				$("#arrival").html("").append("<option value=''>Select arrival airport</option>");

				for(var i in routes) {
					$("#arrival").append("<option value='" + i + "'>" + routes[i] + "</option>");
				}
				
				$("#arrival").selectpicker("refresh");
			}
		});
	}
}

function fillSchedule() {
	var departure = $("#departure").selectpicker("val");
	var arrival = $("#arrival").selectpicker("val");
	
	if(arrival == "" || departure == "") {
		return false;
	}
	
	$.get("/TuiParser/getSchedule/" + departure + "/" + arrival + "/" + ($("[name='flightType']:checked").val() == "round-trip" ? "1" : "0"), function(data) {
		if(data) {
			var dates = JSON.parse(data);
			var enabledDates = [];
			var currentDate;
			var dateFound = false;
			
			for(var i in dates["OUT"]) {
				enabledDates[enabledDates.length] = moment(dates["OUT"][i], "DD/MM/YYYY");
			}

			$("#scheduleDeparture").data("DateTimePicker").enabledDates(enabledDates);
			
			if($("#scheduleDeparture").val() != "") {
				currentDate = moment($("#scheduleDeparture").val(), "DD/MM/YYYY");
				
				for(var i in enabledDates) {
					dateFound = enabledDates[i].format("x") == currentDate.format("x");
					
					if(dateFound) {
						break;
					}
				}
				
				if(!dateFound) {
					$("#scheduleDeparture").data("DateTimePicker").clear();
				}
			}

			enabledDates = [];
			dateFound = false;
			
			for(var i in dates["RET"]) {
				enabledDates[enabledDates.length] = moment(dates["RET"][i], "DD/MM/YYYY");
			}
			
			$("#scheduleReturn").data("DateTimePicker").enabledDates(enabledDates);
			
			if($("#scheduleReturn").val() != "") {
				currentDate = moment($("#scheduleReturn").val(), "DD/MM/YYYY");
				
				for(var i in enabledDates) {
					dateFound = enabledDates[i].format("x") == currentDate.format("x");
					
					if(dateFound) {
						break;
					}
				}
				
				if(!dateFound) {
					$("#scheduleReturn").data("DateTimePicker").clear();
				}
			}
		}
	});
}

function prepareModal() {
	if($("[value='round-trip']:checked").length) {
		$("#modalFlightsDetails #outboundDetails").removeClass("col-sm-12").addClass("col-sm-6");
		$("#modalFlightsDetails #returnDetails").removeClass("hidden");
	} else {
		$("#modalFlightsDetails #outboundDetails").removeClass("col-sm-6").addClass("col-sm-12");
		$("#modalFlightsDetails #returnDetails").addClass("hidden");
	}
}

function searchAvailability() {
	var form = $("#homeForm");
	
	$("label.error").remove();
	
	$.post("/TuiParser/getAvailability", form.serialize(), function(data) {
		if(data) {
			try {
				var errors = JSON.parse(data);
				var target;

				for(var i in errors) {
					for(var j in errors[i]) {
						if($("#" + i).next(".input-group-addon").length) {
							target = $("#" + i).parent();
						} else {
							target = $("#" + i);
						}
						
						target.after("<label class='error'>" + errors[i][j] + "</label>");
					}
				}
			} catch(e) {
				$("#availability").addClass("bg-success").html(data);
			}
		}
	});
}

function validateDates() {
	if($("#scheduleDeparture").val() != "" && $("#scheduleReturn").val() != "") {
		if($("#scheduleReturn").data("DateTimePicker").date().diff($("#scheduleDeparture").data("DateTimePicker").date()) < 0) {
			$("#scheduleReturn").data("DateTimePicker").clear();
		}
	}
}

$(window).load(function() {
	$("#scheduleDeparture").data("DateTimePicker").minDate(moment().format("DD/MM/YYYY"));
	$("#scheduleReturn").data("DateTimePicker").minDate(moment().format("DD/MM/YYYY"));
	$("#scheduleDeparture, #scheduleReturn").on("dp.change", function(e) {
		if($("#scheduleDeparture").val() == "" && $("#scheduleReturn").val() == "") {
			$("#scheduleDeparture").data("DateTimePicker").minDate(moment().format("DD/MM/YYYY"));
			$("#scheduleReturn").data("DateTimePicker").minDate(moment().format("DD/MM/YYYY"));
		}
		else if(e.date != "") {
			$("#scheduleReturn").data("DateTimePicker").minDate(e.date.format("DD/MM/YYYY"));
			
			validateDates();
		}
	});
});