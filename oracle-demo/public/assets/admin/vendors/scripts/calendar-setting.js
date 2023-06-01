jQuery(document).ready(function () {
    jQuery("#add-event").submit(function () {
        alert("Submitted");
        var values = {};
        $.each($('#add-event').serializeArray(), function (i, field) {
            values[field.name] = field.value;
        });
        console.log(
            values
        );
    });
});

(function () {
    'use strict';
    // ------------------------------------------------------- //
    // Calendar
    // ------------------------------------------------------ //
    jQuery(function () {
        // page is ready
        jQuery('#calendar').fullCalendar({
            themeSystem: 'bootstrap4',
            initialDate: new Date(),
            selectOverlap: false, //evitar ocupar horas no disponibles
            // emphasizes business hours
            initialView: 'dayGridMonth',
            businessHours: false,
            defaultView: 'month',
            selectable: true,

         
            selectAllow: function (select) {
                return moment().diff(select.start, 'days') <= 0
            },
			select: function(arg) {
				alert('selected ' + arg);
			  },
            // header
            header: {
                left: '',
                center: 'title',
                right: 'today prev,next'
            },
            events: [{
                    title: 'Barber',
                    description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eu pellentesque nibh. In nisl nulla, convallis ac nulla eget, pellentesque pellentesque magna.',
                    start: '2022-10-20',
                    end: '2022-10-21',
                    className: 'fc-bg-default',
                    icon: "circle"
                },

            ],
           
        })
    });

})(jQuery);
