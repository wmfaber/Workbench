$(document).ready(function(){

$('.typeahead').typeahead({ 
	source: function(query,process){
		return $.ajax({
			url: "Judoka/ajax/1",
			type: "GET",
			data: "action=search&query=" + query,
			dataType: "JSON",
			async: true,
			success: function(data) {
				return process(data);
			}
		});	
	}
});

$(document).on("hidden.bs.modal", function (e) {
    $(e.target).removeData("bs.modal").find(".modal-content").empty();
});
$("a[data-target=#MRweb_Modal]").click(function(ev) {
    ev.preventDefault();
    var target = $(this).attr("href");
    // load the url and show modal on success
    $("#MRweb_Modal .modal-content").load(target, function() { 
         //$("#MRweb_Modal").modal("show"); 
    });
});



/*
$("#MRweb_Modal").on('submit', function(ev) {
    ev.preventDefault();
    this.submit();
	$('#MRweb_Modal').modal('hide')
});
*/
/*
 $('form[data-async]').live('submit', function(event) {
 	console.log('x');
 	console.log(event);
 	event.preventDefault();
        var $form = $(this);
        var $target = $($form.attr('data-target'));
 
        $.ajax({
            type: $form.attr('method'),
            url: $form.attr('action'),
            data: $form.serialize(),
 
            success: function(data, status) {
                $target.html(data);
            }
        });
 
        event.preventDefault();
    });
    */
  });  
  
  