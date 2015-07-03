$(document).ready(function(){

	$('.mrcheckbox').each(function( i ) {
		var checkbox_id 	= '#'+this.id;
		var hidden_id   	= checkbox_id.replace('_checkbox','');
		var hidden_value 	= $(hidden_id).attr('value');
		if(hidden_value == 'Y'){
			$(checkbox_id).prop('checked', true);
		}else{
			$(checkbox_id).prop('checked', false);
		}
	});

	$('#require_login_checkbox, #exported_checkbox, .mrcheckbox').mousedown(function(){
			console.log('xz');
		var check_id = this.id;
		var check_id =  check_id.replace('_checkbox','');
		var check_id = '#' + check_id;
		console.log(check_id );
		if (!$(this).is(':checked')) {
			$(check_id).attr('value','Y');
		}else{
			$(check_id).attr('value','N');
		}
	});

/*
	$('#loaded-entrys-switch').change(function(){
		  var url = location.href;
    	url = url.split('?')[0]+"?loadedentrys="+$(this).val();
    	window.location = url;
	});
	*/
		$('.mr_sort, .mr_limit').change(function(ev){
			var field_id = ev.target.id;
			var field_name = field_id.replace('sort_','');
			var sort_value = $(this).val();
			var current_url = window.location.href;
			var params = getUrlVars();
			var querystring = [];
			var has_been_added_changed = false;
			$.each(params, function(key,value) {
				if(value == 'sort')
				{
					return true;
				}
				if(value == field_name){
					querystring.push(value+"="+sort_value);
					has_been_added_changed = true;
				}else{  	
					querystring.push(value+"="+getUrlVars()[value]);			
  			}
			}); 
			if(has_been_added_changed == false){
				querystring.push(field_name+"="+sort_value );
			}
			querystring = querystring.join('&');
			console.log(querystring);
		  var url = location.href;
    	url = url.split('?')[0];
    	url  = url + "?"+querystring;
    	window.location = url;
	});
	
	$('.datepicker').datepicker({
    format: 'dd-mm-yyyy'
	});


	$('.money').money_field();
	
	tinymce.init({
    selector: 'textarea',
    theme: "modern",
    plugins: [
         "autolink link lists charmap preview hr anchor pagebreak spellchecker"]
	}); 
	
});


function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function getUrlVars()
{
    var vars = [], hash;
    var url = window.location.href;
    if(url.indexOf('?') <=0){
    	var url = url+'?sort=true'; //placeholder
    }
    var hashes = url.slice(url.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}

function Close_Modal(id){
	$('#'+id).modal('hide')
}

