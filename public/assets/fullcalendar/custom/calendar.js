/** localStorage view calendar appointment */
function calendarStyleView() {
    switch ($(".fc-button-active")[0].innerHTML) {
        case 'day':
        case '天':
            window.localStorage.setItem('calendarStyle', 'timeGridDay');
            break;
        case 'week':
        case '週':
            window.localStorage.setItem('calendarStyle', 'timeGridWeek');
            break;
        case 'list':
        case '活動列表':
            window.localStorage.setItem('calendarStyle', 'listMonth');
            break;
        default:
            window.localStorage.setItem('calendarStyle', 'dayGridMonth');
    }
}

/** Generate Calendar Appointment */
function generateCalendarAppointment(url_update_time, url_product_list, member_id) {
    var initialView;
    var calendarEl = document.getElementById('fullcalendar');
    var initialLocaleCode = $('html').attr('lang');
    var events = JSON.parse($('#event').val());

    if (window.localStorage.getItem('calendarStyle')) {
        initialView = window.localStorage.getItem('calendarStyle');
        window.localStorage.removeItem('calendarStyle');
    } else {
        initialView = "timeGridWeek";
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
        },
        initialView: initialView,
        selectable: true,
        locale: initialLocaleCode,
        weekNumbers: true,
        navLinks: true, // can click day/week names to navigate views
        editable: true,
        views: {
            week:   {
                dayHeaderContent: (args) => {
                    return moment(args.date).format('ddd D/M')
                },
            }
        },
        eventDidMount: function (info) {
            $(info.el).attr("id", "event_id_" + info.event.id);
            $(info.el).addClass("tooltip-content");
            $(info.el).attr("title", "");
            $(info.el).attr("data-tooltip", info.event.extendedProps.product_list);
        },
        eventDrop: function (info) {
            var eventObj = info.event;
            var time = info.event.start.toISOString();

            eventDropUpdateTime(url_update_time, initialLocaleCode, eventObj, time)
        },
        eventDurationEditable: false,
        dayMaxEvents: true,
        events: events,
        dateClick: function (info) {
            $('#create-booking').click();
            $('input#get-date').val(formatDateTime(info.dateStr));
            calendarStyleView();
        },
        eventClick: function (info) {
            var eventObj = info.event;
            var update_boooking_url = $("#update-booking").attr('data-url');
            $("#update-booking").attr("href", update_boooking_url + "/" + eventObj.id + "&member_id=" + member_id);
            $("#update-booking").click();
            calendarStyleView();
        }
    });
    calendar.render();

    return calendar;
}

/** Update time appointment when drop event */
function eventDropUpdateTime(url, initialLocaleCode, eventObj, time) {
    $.ajax({
        url: url + "/" + eventObj.id,
        method: "post",
        data: {'time': formatDateTime(time)}
    }).done(function (response) {
        if (response.status !== 200) {
            info.revert();
        } else {
            calendarStyleView();
            var title = (initialLocaleCode === 'zh-TW') ? '改變成功' : 'Change success';
            swal.fire({
                title: title,
                icon: "success",
                allowOutsideClick: false
            }).then((done) => {
                if (done) {
                    location.reload()
                }
            });
        }
    });
}

/** Form Handle */
function getProductByType(service_type) {
    $(document).on('change', '#appointment-form #type', function () {
        if ($(this).val() === service_type) {
            $("#appointment-form .select-service").show();
            $("#appointment-form .select-course").hide();
        } else {
            $("#appointment-form .select-service").hide();
            $("#appointment-form .select-course").show();
        }
    });

    $(document).on('change', '.select-product', function () {
        var product = $(this);
        var html =
            '<tr id="' + product.val() + '">' +
            '<td>' +
            '<input type="hidden" name="product_ids[]" value="' + product.val() + '">' +
            '<span class="text-option">' + product.children(':selected').text() + '</span>' +
            '</td>' +
            '<td class="text-center"><button type="button" class="btn btn-danger delete-product"><i class="fas fa-trash"></i></button></td>' +
            '</tr>';
        $("#product-list tbody").append(html);
        product.children(':selected').remove();
    });

    /** Delete product*/
    $(document).on('click', '.delete-product', function () {
        var tr_parent = $(this).parents('tr');
        var value = tr_parent.attr('id');
        var option = tr_parent.find('.text-option').html();
        var html = '<option value="' + value + '">' + option + '</option>';
        var form = $(document).find('#appointment-form');
        if (form.find('#type').val() === service_type) {
            $(document).find('#service-select').append(html);
        } else {
            $(document).find('#course-select').append(html);
        }
        $(this).parents('tr').remove();
    });
}
