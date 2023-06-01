<script>
    /*
     * jQuery-Calendar Plugin v1.1.1
     *
     * 2018 (c) Sebastian Knopf
     * This software is licensed under the MIT license!
     * View LICENSE.md for more information
     */

    (function($) {


        //$('.cargar').click();

        $.fn.calendar = function(opts) {

            let options = $.extend({
                color: '#000',
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                days: ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'],
            }, $.fn.calendar.defaults, opts);

            return this.each(function() {
                let currentYear, currentMonth, currentDay, currentCalendar;

                initCalendar($(this), options);
            });
        };

        function initCalendar(wrapper, options) {
            let color = options.color;

            wrapper.addClass('calendar').empty();

            let header = $('<header>').appendTo(wrapper);
            header.addClass('calendar-header');
            header.css({
                background: color,
                color: createContrast(color)
            });

            let buttonLeft = $('<span>').appendTo(header);
            buttonLeft.addClass('button').addClass('left');

            buttonLeft.html(' &lang; ');
            buttonLeft.bind('click', function() {
                currentCalendar = $(this).parents('.calendar');
                selectMonth(false, options);
            });
            buttonLeft.bind('mouseover', function() {
                $(this).css('background', createAccent('#f6750d', -20));
            });
            buttonLeft.css('background', '#f6750d');
            buttonLeft.bind('mouseout', function() {
                $(this).css('background', '#f6750d');
            });

            let headerLabel = $('<span>').appendTo(header);
            headerLabel.addClass('header-label')
            headerLabel.html(' Month Year ');
            headerLabel.bind('click', function() {
                currentCalendar = $(this).parents('.calendar');
                selectMonth(null, options, new Date().getMonth(), new Date().getFullYear());

                currentDay = new Date().getDate();
                triggerSelectEvent(options.onSelect);
            });

            let buttonRight = $('<span>').appendTo(header);
            buttonRight.addClass('button').addClass('right');
            buttonRight.html(' &rang; ');
            buttonRight.bind('click', function() {
                currentCalendar = $(this).parents('.calendar');
                selectMonth(true, options);
            });
            buttonRight.css('background', '#f6750d');
            buttonRight.bind('mouseover', function() {
                $(this).css('background', createAccent('#f6750d', -20));
            });
            buttonRight.bind('mouseout', function() {
                $(this).css('background', '#f6750d');
            });

            let dayNames = $('<table>').appendTo(wrapper);
            dayNames.append('<thead><th>' + options.days.join('</th><th>') + '</th></thead>');
            dayNames.css({
                width: '100%'
            });

            let calendarFrame = $('<div>').appendTo(wrapper);
            calendarFrame.addClass('calendar-frame');

            headerLabel.click();
        }

        function selectMonth(next, options, month, year) {
            let tmp = currentCalendar.find('.header-label').text().trim().split(' '),
                tmpYear = parseInt(tmp[1], 10);

            if (month === 0) {
                currentMonth = month;
            } else {
                currentMonth = month || ((next) ? ((tmp[0] === options.months[options.months.length - 1]) ? 0 : options.months.indexOf(tmp[0]) + 1) : ((tmp[0] === options.months[0]) ? 11 : options.months.indexOf(tmp[0]) - 1));
            }

            currentYear = year || ((next && currentMonth === 0) ? tmpYear + 1 : (!next && currentMonth === 11) ? tmpYear - 1 : tmpYear);

            let calendar = createCalendar(currentMonth, currentYear, options),
                frame = calendar.frame();

            currentCalendar.find('.calendar-frame').empty().append(frame);
            currentCalendar.find('.header-label').text(calendar.label);


        }

        function createCalendar(month, year, options) {
            let currentDay = 1,
                daysLeft = true,

                startDay = new Date(year, month, currentDay).getDay() - 1,
                lastDays = [31, (((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0)) ? 29 : 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
                calendar = [];
            let dia_actual = new Date().getDate().toString();
            let mes_actual = new Date().getMonth();
            let anio_actual = new Date().getFullYear();
            let i = 0;
            while (daysLeft) {
                calendar[i] = [];

                for (let d = 0; d < 7; d++) {
                    if (i == 0) {
                        if (d == startDay) {
                            calendar[i][d] = currentDay++;
                            startDay++;
                        } else if (startDay === -1) {
                            calendar[i][6] = currentDay++;
                            startDay++;
                        }
                    } else if (currentDay <= lastDays[month]) {
                        calendar[i][d] = currentDay++;
                    } else {
                        calendar[i][d] = '';
                        daysLeft = false;
                    }

                    if (currentDay > lastDays[month]) {
                        daysLeft = false;
                    }


                }

                i++;
            }

            let frame = $('<table>').addClass('current');
            let frameBody = $('<tbody>').appendTo(frame);

            for (let j = 0; j < calendar.length; j++) {
                let frameRow = $('<tr>').appendTo(frameBody);

                $.each(calendar[j], function(index, item) {
                    let frameItem = $('<td>').appendTo(frameRow);
                    frameItem.text(item);
                });
            }
            console.log(currentMonth)
            let dias = [21, 31, 30];
            let maxdate = new Date();
            maxdate.setDate(maxdate.getDate() + 31);
            dia_maximo = maxdate.getDate().toString();
            mes_maximo = maxdate.getMonth();
            anio_maximo = maxdate.getFullYear();
            console.log(`${dia_maximo} - ${mes_maximo} - ${anio_maximo}`)


            $('td:empty', frame).addClass('disabled-table');
            if (currentMonth === mes_actual && (parseInt(year) == parseInt(anio_actual))) {
                $('td', frame).filter(function() {
                    return $(this).text() === dia_actual;
                }).addClass('today');
                $('td', frame).filter(function() {
                    return $(this).text() === dia_actual;
                }).attr('id', 'today');

                $('td', frame).filter(function() {
                    if (((parseInt($(this).text()) < parseInt(dia_actual)) && (parseInt(year) <= parseInt(anio_actual)))) {
                        $($(this), frame).addClass('disabled-table');
                    } else {
                        let fechas_php = <?= $fechas['fecha_texto']?>;
                        console.log(fechas_php);
                            if ((parseInt($(this).text()) >= parseInt(dia_actual)) && parseInt(currentMonth) === parseInt(mes_actual) && (parseInt(year) == parseInt(anio_actual))) {

                                console.log(`${anio_actual}-${mes_actual}-${$(this).text()}`)

                                if ($.inArray(parseInt($(this).text()), dias) >= 0) {
                                    $($(this), frame).css('text-decoration', 'underline');
                                    $($(this), frame).css('text-decoration-color', 'red');
                                    $($(this), frame).css('text-decoration-thickness', '5px');
                                    $($(this), frame).addClass('disabled-table');
                                    // $($(this), frame).html('<span><i class="fa fa-minus" style="color:red;"></i></span>');

                                } else {
                                    // $($(this), frame).css('background', '#d4edda');
                                    // $($(this), frame).css('color', '#155724');
                                    $($(this), frame).css('text-decoration', 'underline');
                                    $($(this), frame).css('text-decoration-color', '#39bd3c');
                                    $($(this), frame).css('text-decoration-thickness', '5px');
                                }
                            }
                    }
                })
            } else {
                let i = 0;
                $('td', frame).filter(function() {
                    console.log(frame)
                    resultado = (parseInt($(this).text()) < parseInt(dia_maximo) && parseInt(currentMonth) == parseInt(mes_maximo) && (parseInt(year) == parseInt(anio_maximo))) ? true : false;
                    if (resultado == false || resultado == 'false') {
                        $($(this), frame).addClass('disabled-table');
                    } else {
                        // $($(this), frame).css('background', '#d4edda');
                        // $($(this), frame).css('color', '#155724');
                        $($(this), frame).css('text-decoration', 'underline');
                        $($(this), frame).css('text-decoration-color', '#39bd3c');
                        $($(this), frame).css('text-decoration-thickness', '5px');

                    }
                    console.log(`Día Actual : ${parseInt($(this).text())} < Día Máx: ${parseInt(dia_maximo)}`)
                    console.log(resultado);


                })
            }



            if (parseInt(currentMonth) < parseInt(mes_actual) && (parseInt(year) <= parseInt(anio_actual))) {
                $('td', frame).addClass('disabled-table');
            }
            if ((parseInt(year) < parseInt(anio_actual))) {
                $('td', frame).addClass('disabled-table');
            }


            return {
                frame: function() {
                    return frame.clone()
                },
                label: options.months[month] + ' ' + year
            };
        }

        function triggerSelectEvent(event) {
            let date = new Date(currentYear, currentMonth, currentDay);

            let label = []; // se define texto a mostrar
            label[2] = (date.getDate() < 10) ? '0' + date.getDate() : date.getDate();
            label[1] = ((date.getMonth() + 1) < 10) ? '0' + (date.getMonth() + 1) : date.getMonth() + 1;
            label[0] = (date.getFullYear());

            if (event != undefined) {
                event({
                    date: date,
                    label: label.join('-')
                });
                //alert(label.join('-'))
            }
        }

        function createContrast(color) {
            if (color.length < 5) {
                color += color.slice(1);
            }

            return (color.replace('#', '0x')) > (0xffffff) ? '#222' : '#fff';
        }

        function createAccent(color, percent) {
            let num = parseInt(color.slice(1), 16),
                amt = Math.round(2.55 * percent),
                R = (num >> 16) + amt,
                G = (num >> 8 & 0x00FF) + amt,
                B = (num & 0x0000FF) + amt;
            return '#' + (0x1000000 + (R < 255 ? R < 1 ? 0 : R : 255) * 0x10000 + (G < 255 ? G < 1 ? 0 : G : 255) * 0x100 + (B < 255 ? B < 1 ? 0 : B : 255)).toString(16).slice(1);
        }
        let dia_actual = new Date().getDate().toString();




    }(jQuery));
</script>