//var companyLogin = 'corporacionkimirina'

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

var services = client.getEventList('','','','Proctología');
var doctores = client.getUnitList();

//console.log(doctores);

let serviciosMedicos = [];

let unitMap = [];

let event_id = null;

let doctorId;

let firstWorkingDay;

//let doctores = [];

for (var key in services) {
	const element = services[key];
	serviciosMedicos.push(element.name);
	event_id = element.id;
	//console.log(element.name);
	//serviciosMedicos = serviciosMedicos.push('element.name');
	//unitMap.push(element.unit_map);

	if (element.unit_map) {
		for (var unitId in element.unit_map) {
			unitMap.push(unitId);
		}
	}

}

console.log(event_id);

//console.log(unitMap);

for (let index = 0; index < serviciosMedicos.length; index++) {
	const element = serviciosMedicos[index];

	jQuery('#serviciosMedicos').append(
		jQuery('<option value="' + index + '">' + serviciosMedicos[index] + '</option>')
	);
	
}

console.log(unitMap);

for (var key in doctores) {
	const element = doctores[key];
	
	//console.log(element.id);

	for (let index = 0; index < unitMap.length; index++) {
		//const element = array[index];
		if (unitMap[index] == element.id){
			//console.log(element.name);
			jQuery('#doctores').append(
				jQuery('<option value="' + element.id + '">' + element.name + '</option>')
			);
		}		
	}
}

jQuery('#doctores').change(function () {
	doctorId = jQuery(this).val();
	DoctorFirstWorkingDay(doctorId);
})

function DoctorFirstWorkingDay(id){
	firstWorkingDay = client.getFirstWorkingDay(id);
	console.log(firstWorkingDay);
}
