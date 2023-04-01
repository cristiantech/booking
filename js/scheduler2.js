var loginClient = new JSONRpcClient({
	'url': 'https://user-api.simplybook.me/login',
	'onerror': function (error) {
		alert(error);
	}
});
var token = loginClient.getToken('corporacionkimirina', 'ac68cd96a5c843f97c7ef60117f1648847924c7307cf5a73f68ead7a85c80dde');

var client = new JSONRpcClient({
	'url': 'https://user-api.simplybook.me',
	'headers': {
		'X-Company-Login': 'corporacionkimirina',
		'X-Token': token
	},
	'onerror': function (error) {
		alert(error);
	}
});

let services = client.getEventList('','','','Ginecologia');
let performers = client.getUnitList();
let unitMap = [];
let firstWorkingDay;
const newArray = Object.entries(services);
newArray.forEach(gn => {
	console.log(gn[1].unit_map);
})



// fetch service and performers selects here
var serviceId;
var performerId;

jQuery('#serviciosMedicos').empty();
jQuery('#doctores').empty();

//jQuery('#serviciosMedicos').append('<option value=""></option>');
jQuery('#doctores').append('<option value=""></option>');

for (var id in services) {
	const element = services[id];
    jQuery('#serviciosMedicos').append('<option value="' + id + '">' + services[id].name + '</option>');

	if (element.unit_map) {
		for (var unitId in element.unit_map) {
			unitMap.push(unitId);
		}
	}
}



for (var id in performers) {
	const element = performers[id];	
	for (let index = 0; index < unitMap.length; index++) {
		if (unitMap[index] == element.id){
			jQuery('#doctores').append('<option value="' + element.id + '">' + element.name + '</option>');
		}		
	}
}

jQuery('#doctores').change(function () {
	performerId = jQuery(this).val();
	firstWorkingDay = client.getFirstWorkingDay(performerId);
	$('#mytextbox').html(firstWorkingDay);
	//calendarUpdate(firstWorkingDay);
	
	jQuery('#date').datepicker({
		'startDate': new Date(),
		//'todayHighlight': true,
		//'setDate': '04/03/2023',
		'format': 'yyyy-mm-dd',
	}).datepicker("setDate", firstWorkingDay);

	jQuery('#date').datepicker('refresh');

	slots(performerId, firstWorkingDay);

});

var slots = (performerId, firstWorkingDay) => {

	var workCalendar = {};

	jQuery('#date').datepicker({
		'onChangeMonthYear': function (year, month, inst) {
			workCalendar = client.getWorkCalendar(year, month, performerId);
			jQuery('#date').datepicker('refresh');
		},
		'beforeShowDay': function (date) {
			var year = date.getFullYear();
			var month = ("0" + (date.getMonth() + 1)).slice(-2);
			var day = ("0" + date.getDate()).slice(-2);
			var date = year + '-' + month + '-' + day;
			if (typeof(workCalendar[date]) != 'undefined') {
				if (parseInt(workCalendar[date].is_day_off) == 1) {
					return [false, "", ""];
				}
			}
		return [true, "", ""];
		}	
	});

	var firstWorkingDateArr = firstWorkingDay.split('-');
	workCalendar = client.getWorkCalendar(firstWorkingDateArr[0], firstWorkingDateArr[1], performerId);

	console.log(workCalendar);

	jQuery('#date').datepicker('refresh');

	// Handle date selection
var count = 1; // How many slots book
function formatDate(date) {
	var year = date.getFullYear();
	var month = ("0" + (date.getMonth() + 1)).slice(-2);
	var day = ("0" + date.getDate()).slice(-2);
	
	return year + '-' + month + '-' + day;
}

function drawMatrix(matrix) {
	jQuery('#starttime').empty();
	
	for (var i = 0; i < matrix.length; i++) {
		jQuery('#starttime').append('<span data-time="' + matrix[i] + '">' + matrix[i] + '</span>');
	}
	jQuery('#starttime span').click(function () {
		startTime = jQuery(this).data('time');
		
		jQuery('#starttime span').removeClass('selected');
		jQuery(this).addClass('selected');
	});
}

jQuery('#date').datepicker('option', 'onSelect', function () {
	var startDate = formatDate(jQuery(this).datepicker('getDate'));
	jQuery('#dateFrom, #dateTo').val(startDate);
	
	var startMatrix = client.getStartTimeMatrix(startDate, startDate, serviceId, performerId, count);
	
	drawMatrix(startMatrix[startDate]);
});
var startMatrix = client.getStartTimeMatrix(firstWorkingDay, firstWorkingDay, serviceId, performerId, count);
drawMatrix(startMatrix[firstWorkingDay]);

}


//var HorarioTrabajador = client.getWorkCalendar(2023, 4, {event_id: 2})
