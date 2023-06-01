$(function () {
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
    // Get the form.
    let form = $('#contact-form');

    // Get the messages div.
    let formMessages = $('.form-messege');

    // Set up an event listener for the contact form.
    $(form).submit(function (e) {
        // Stop the browser from submitting the form.
        e.preventDefault();
        // Serialize the form data.
        let formData = $(form).serialize();
		// Submit the form using AJAX.
        $.ajax({
            url: $(form).attr('action'),
            type: "POST",
            data: formData,

            success: function (response) {
				resp = JSON.parse(response);
				
                if (resp.type != 'error') {
                    // Make sure that the formMessages div has the 'success' class.
                    $(formMessages).removeClass('error');
                    $(formMessages).addClass('success');
                    // Set the message text.
                    $(formMessages).text(resp.msg);
                    // Clear the form.
                    $('#contact-form input,#contact-form textarea').val('');
                } else {
                    $(formMessages).removeClass('success');
                    $(formMessages).addClass('error');
					$(formMessages).text(resp.msg);
                }
            },
            error: function (error) {
                console.log(JSON.stringify(error))
                $(formMessages).removeClass('success');
                $(formMessages).addClass('error');
                $(formMessages).text('Oops! Se ha producido un error y su mensaje no ha podido ser enviado.');
            }
        });
        
    });

});
