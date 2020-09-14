let username = true;
let email = true;

$(function() {
	$('#registration_form_username').on('keyup', function() {
		userAvailable($('#registration_form_username').val(), 'username');
	});
	$('#registration_form_email').on('keyup', function() {
		userAvailable($('#registration_form_email').val(), 'email');
	});
});

function userAvailable(data, column) {
	var dataAjax = {'data':data,'column':column};
	$.ajax({
		type:'POST',
		url:'/registro/disponible',
		data:dataAjax,
		success: function(response){
			validateData(response.data, column);
		}		
	});
}

function validateData(data, column) {
	if (column == 'username') {
		username = data;
	}
	if (column == 'email') {
		email = data;
	}
	if (data) {
		$('#message-'+column).css('display','none');
	}
	else {
		$('#message-'+column).css('display','block');	
	}
	if (username && email) {
		$('#buttonSubmit').css('display','block');
	}
	else {
		console.log('entre');		
		$('#buttonSubmit').css('display','none');	
	}
}