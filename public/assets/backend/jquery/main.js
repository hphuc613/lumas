/*function loadTheme() {
    setTheme('Dark');
}

function setTheme(theme) {
    if (theme == 'Dark') {
        $('#current-theme').text(theme);
        $(':root').css('--main-color', '#000000');
        console.log($(':root'));
    }
}*/

$(document).ready(function () {
    //Select2
    $(document).find('.select2').select2();
    $('input.datetime, input.date, input.time, input.month, input.year').attr("autocomplete", "off")

    /***** Action delete *****/
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        var action = $(this).attr('href');
        var lang = $('html').attr('lang');
        var title = (lang === 'zh-TW') ? "你確定嗎?" : "Are you sure?";
        var text = (lang === 'zh-TW') ? "您将无法还原此内容!" : "You won't be able to revert this!";

        swal.fire({
            title: title,
            text: text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: (lang === 'zh-TW') ? '刪除' : 'Delete',
            confirmButtonColor: "#d33",
            cancelButtonText: (lang === 'zh-TW') ? '取消' : 'Cancel',
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                window.location.replace(action);
            }
        });
    });
    /***** Action Clear Search *****/
    $(document).on('click', 'button.clear', function (event) {
        event.preventDefault();
        var form = $(this).parents('form');
        form.find('input[type="text"], input[type="checkbox"]').attr('disabled', 'disabled');
        form.find('select').attr('disabled', 'disabled');
        form.trigger('submit');
    });
    /***** Action Check all item in table *****/
    $(document).on('click', '.select-all', function () {
        var class_child = $(this).attr('id');
        if (class_child !== '') {
            var child = $('input.' + class_child);
            if (child.length > 0) {
                console.log('cl');
                child.not(this).prop('checked', this.checked);
            } else {
                if (!$(this).hasClass('select-all-with-other-child')) {
                    $('input.checkbox-item').not(this).prop('checked', this.checked);
                }
            }
        } else {
            console.log('ccl');
            $('input.checkbox-item').not(this).prop('checked', this.checked);
        }
    });

    /*********** Datetime Picker *************/
//VIETNAM CALENDAR
    var lang = $('html').attr('lang');
    $.fn.datetimepicker.dates['vn'] = {
        days: ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy", "Chủ nhật"],
        daysShort: ["CNhật", "Hai", "Ba", "Tư", "Năm", "Sáu", "Bảy", "CNhật"],
        daysMin: ["CN", "T2", "T3", "T4", "T5", "T6", "T7", "CN"],
        months: ["Tháng một", "Tháng hai", "Tháng ba", "Tháng tư", "Tháng năm", "Tháng sáu", "Tháng bảy", "Tháng tám", "Tháng chín", "Tháng mười", "Tháng mười một", "Tháng mười hai"],
        monthsShort: ["Th. 1", "Th. 2", "Th. 3", "Th. 4", "Th. 5", "Th. 6", "Th. 7", "Th. 8", "Th. 9", "Th. 10", "Th. 11", "Th. 12"],
        today: "Hôm nay",
        meridiem: ['SA', 'CH']
    };
    $('input.datetime').datetimepicker({
        format: 'dd-mm-yyyy hh:ii',
        fontAwesome: true,
        autoclose: true,
        todayHighlight: true,
        todayBtn: true,
        language: lang,
        container: '.datetime-modal'
        //VN Calendar
        /*format: 'dd-mm-yyyy HH:ii P',
        language: 'vn',
        showMeridian: true,*/
    });
    $('input.date').datetimepicker({
        format: 'dd-mm-yyyy',
        fontAwesome: true,
        autoclose: true,
        startView: 2, // 0: hour current, 1: time in date current, 2: date
                      // in month current, 3: month in year current, 4 year
                      // in decade current
        minView: 2,
        todayBtn: true,
        language: lang,
        container: '.datetime-modal'
    });
    $('input.time').datetimepicker({
        format: 'hh:ii',
        fontAwesome: true,
        autoclose: true,
        startView: 1,
        language: lang,
        container: '.datetime-modal'
    });
    $('input.month').datetimepicker({
        format: 'mm-yyyy',
        fontAwesome: true,
        autoclose: true,
        startView: 3,
        minView: 3,
        language: lang,
        container: '.datetime-modal'
    });
    $('input.year').datetimepicker({
        format: 'yyyy',
        fontAwesome: true,
        autoclose: true,
        startView: 4,
        minView: 4,
        language: lang,
        container: '.datetime-modal'
    });
    /***********************************************************************/
});
