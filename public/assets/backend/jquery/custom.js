$(document).ready(function () {
    changeLanguage();
});

function changeLanguage() {
    $('#change-language').change(function () {
        var lang = $(this).val();
        var url = $(this).attr('data-href');
        window.location.href = url + '/' + lang;
    });
}

function formatDateTime(date) {
    let date_ob = new Date(date);
// adjust 0 before single digit date
    let day = ("0" + date_ob.getDate()).slice(-2);
// current month
    let month = ("0" + (date_ob.getMonth() + 1)).slice(-2);
// current year
    let year = date_ob.getFullYear();
// current hours
    let hours = date_ob.getHours();
    if (hours < 10) {
        hours = "0" + hours;
    }
// current minutes
    let minutes = date_ob.getMinutes();
    if (minutes < 10) {
        minutes = "0" + minutes;
    }
// current seconds
    let seconds = date_ob.getSeconds();
    if (seconds < 10) {
        seconds = "0" + seconds;
    }
// prints date & time in YYYY-MM-DD HH:MM:SS format
    return day + "-" + month + "-" + year + " " + hours + ":" + minutes;
}

function calendarStyleView() {
    switch ($(".fc-button-active")[0].innerHTML) {
        case 'day':
            window.localStorage.setItem('calendarStyle', 'timeGridDay');
            break;
        case 'week':
            window.localStorage.setItem('calendarStyle', 'timeGridWeek');
            break;
        case 'list':
            window.localStorage.setItem('calendarStyle', 'listMonth');
            break;
        default:
            window.localStorage.setItem('calendarStyle', 'dayGridMonth');
    }
}

function pusherNotification(key) {
    var pusher = new Pusher(key, {
        encrypted: true,
        cluster: "ap1"
    });

    var channel = pusher.subscribe('NotificationEvent');
    channel.bind('send-message', function (data) {
        var html = '<li><a class="dropdown-item" href="#">'
            + '<span class="font-weight-bold">' + data.title + '</span><br>'
            + '<small class="timestamp">About a minute ago</small>'
            + '</a></li>';
        $('#new-notification ul').prepend(html);
    });
}
