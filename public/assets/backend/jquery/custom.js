/** Change Language */
function changeLanguage() {
    $('#change-language').change(function () {
        var lang = $(this).val();
        var url = $(this).attr('data-href');
        window.location.href = url + '/' + lang;
    });
}

/** Elfinder Popup */
function openElfinder(btn, url, soundPath, csrf) {
    var modal = '\n' +
        '    <div class="modal fade" style="z-index: 12000" id="elfinder-show">\n' +
        '        <div class="modal-dialog modal-lg" style="max-width: 90%">\n' +
        '            <div class="modal-content bg-transparent border-0">\n' +
        '                <div class="modal-body">\n' +
        '                    <div id="elfinder"></div>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '        </div>\n' +
        '    </div>';

    if ($('body').find('#elfinder-show').length === 0) {
        $('body').append(modal);
    }
    var lang = $('html').attr('lang');
    $('#elfinder-show').modal();
    $('#elfinder').elfinder({
        debug: false,
        lang: lang,
        width: '100%',
        height: '100%',
        customData: {
            _token: csrf
        },
        commandsOptions: {
            getfile: {
                onlyPath: true,
                folders: false,
                multiple: false,
                oncomplete: 'destroy'
            },
            ui: 'uploadbutton'
        },
        mimeDetect: 'internal',
        onlyMimes: [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/gif'
        ],
        soundPath: soundPath,
        url: url,
        getFileCallback: function (file) {
            $(btn).parents('.input-group').find('input').val(file.url)
            console.log($(btn));
            if ($(btn).hasClass('cke_dialog_ui_input_text')) {
                $(btn).val(file.url)
            }
            $('#elfinder-show').modal('hide');
        },
        resizable: false
    }).elfinder('instance');
}

/** Format date */
function formatDateTime(date) {
    let date_ob = new Date(date);
    let day = ("0" + date_ob.getDate()).slice(-2);
    let month = ("0" + (date_ob.getMonth() + 1)).slice(-2);
    let year = date_ob.getFullYear();
    let hours = date_ob.getHours();
    if (hours < 10) {
        hours = "0" + hours;
    }
    let minutes = date_ob.getMinutes();
    if (minutes < 10) {
        minutes = "0" + minutes;
    }
    let seconds = date_ob.getSeconds();
    if (seconds < 10) {
        seconds = "0" + seconds;
    }
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
        var text = (lang === 'zh-TW') ? "還沒有消息..." : "No news yet...";
        notify.html('<div class="text-center p-3"><span class="font-italic">' + text + '</span></div>');
    }
}

/** Get Month List*/
function getMonthToCurrentInYear(current) {
    moment.locale($('html').attr('lang'))
    const list_month = moment.months();
    const current_month = moment().month();
    const the_months = [];

    if (!current) {
        return list_month;
    }

    for (var i = 0; i <= current_month; i++) {
        the_months.push(list_month[i])
    }

    return the_months;
}
