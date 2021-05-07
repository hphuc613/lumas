// Should work for most cases
function uniqueId() {
    return Math.round(new Date().getTime()+ 1000 + (Math.random() * 100)) + 250;
}

function slideAlert(selector) {
    if (selector !== undefined) {
        selector.show().animate({
            right: "10px"
        }, 500);

        setTimeout(function () {
            selector.animate({
                right: "-310px"
            }, 3000);
        }, 7000);
    }
}

$(document).ready(function () {
    //Select2
    $('select.select2').select2();
    /***** Action delete *****/
    $(document).on('click', '.btn-delete', function () {
        return confirm('Are you sure you want to delete this item?');
    });
    /***** Action Clear Search *****/
    $(document).on('click', 'button.clear', function (event) {
        event.preventDefault();
        var form = $(this).parents('form');
        form.find('input[type="text"], input[type="checkbox"]').attr('disabled', 'disabled');
        form.find('select').attr('disabled', 'disabled');
        form.trigger('submit');
    });
    /***** Check all item in table *****/
    $(document).on('click', '.select-all', function () {
        var class_child = $(this).attr('id');
        if (class_child !== '') {
            var child = $('input.' + class_child);
            if (child.length > 0) {
                console.log('cl');
                child.not(this).prop('checked', this.checked);
            }
            else {
                if (!$(this).hasClass('select-all-with-other-child')) {
                    $('input.checkbox-item').not(this).prop('checked', this.checked);
                }
            }
        }
        else {
            console.log('ccl');
            $('input.checkbox-item').not(this).prop('checked', this.checked);
        }
    });
    /*********** Datetime Picker *************/
    //VIETNAM CALENDAR
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
        minView: 2
    });
    $('input.time').datetimepicker({
        format: 'hh:ii',
        fontAwesome: true,
        autoclose: true,
        startView: 1,
    });
    $('input.month').datetimepicker({
        format: 'mm-yyyy',
        fontAwesome: true,
        autoclose: true,
        startView: 3,
        minView: 3
    });
    $('input.year').datetimepicker({
        format: 'yyyy',
        fontAwesome: true,
        autoclose: true,
        startView: 4,
        minView: 4
    });
    /***********************************************************************/

    /** Checkbox Style**/
    $.each($('input[type=checkbox]'), function (i, item) {
        var checkbox_id = $(item).attr('id');
        var parent = $(item).parent();
        if (checkbox_id === null || checkbox_id === undefined) {
            $(item).attr('id', uniqueId());
            checkbox_id = $(item).attr('id');
        }
        if (parent.find('.checkmark').html() === undefined) {
            var checkbox_group;
            if (typeof $(item).attr('disabled') !== typeof undefined && $(item).attr('disabled') !== false) {
                checkbox_group = parent.html() + '<span class="checkmark checkmark-disabled"></span>';
            }
            else {
                checkbox_group = parent.html() + '<span class="checkmark"></span>';
            }
            parent.html('');
            var check_mark = '<label class="selection-style-label" for="' + checkbox_id + '">' + checkbox_group + '</label>';
            parent.html(check_mark);
        }
    });

    /** Radio Style**/
    $.each($('input[type=radio]'), function (i, item) {
        var radio_id = $(item).attr('id');
        var parent = $(item).parent();
        if (radio_id === null || radio_id === undefined) {
            $(item).attr('id', uniqueId());
            radio_id = $(item).attr('id');
        }

        if (parent.find('.radiomark').html() === undefined) {
            var radio_group = parent.html() + '<span class="radiomark"></span>';
            parent.html('');
            var radio_mark = '<label class="selection-style-label" for="' + radio_id + '">' + radio_group + '</label>';
            parent.html(radio_mark);
        }
    });

    $('input[type="file"]').change(function (e) {
        var file_name = e.target.files[0].name;
        $(this).siblings('label#upload-display').html('<i class="fas fa-upload"></i> ' + file_name);
    });


});
