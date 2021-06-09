$(document).ready(function () {
    changeLanguage();
});


/** Change Language */
function changeLanguage() {
    $('#change-language').change(function () {
        var lang = $(this).val();
        var url = $(this).attr('data-href');
        window.location.href = url + '/' + lang;
    });
}

/** Format date */
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

/** Handle Notification */
function pusherNotification(key, user_id, url) {
    /** Show Notification Popup */
    var pusher = new Pusher(key, {
        encrypted: true,
        cluster: "ap1"
    });
    var channel = pusher.subscribe('NotificationEvent');
    channel.bind('send-message', function (data) {
        var lang = $('html').attr('lang');
        var text = (lang === 'zh-TW') ? "將在幾分鐘後到店." : "will be at the store in a few minutes.";
        if (data.length !== 0 && parseInt(user_id) === parseInt(data.user_id)) {
            swal.fire({
                title: data.title,
                text: data.member + ' ' + text,
                icon: "info",
                confirmButtonText: (lang === 'zh-TW') ? "約會日曆" : "Appointment Schedule",
                allowOutsideClick: false,
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.replace(url + '/' + data.member_id + '/?type=' + data.type);
                }
            });
        }
    });


    /** Handle content Notification box */
    $.each($('.notification-list'), function (i, item) {
        var content = $(item).html();
        if (content.trim() === "") {
            $(item).parent('div').remove();
        }
    })

    var notify = $('.notify');
    if (notify.html().trim() === "") {
        var lang = $('html').attr('lang');
        var text = (lang === 'zh-TW') ? "還沒有消息." : "No news yet.";
        notify.html('<div class="text-center p-3"><span><i>' + text + '</i></span></div>');
    }
}
