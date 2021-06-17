$(document).ready(function () {
    if ($(document).find(".modal.modal-ajax .modal-body").html() !== "") {
        var select2_class = $(document).find('.select2');
        select2_class.select2();
        if (select2_class.hasClass('w-100')) {
            select2_class.parent().find('.select2').css('width', '100%');
        }
        $('input.datetime, input.date, input.time, input.month, input.year').attr("autocomplete", "off");
        /*********** Datetime Picker *************/
        var lang = $('html').attr('lang');
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
                } else {
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

        /** Upload Style**/
        $('input[type="file"]').change(function (e) {
            var file_name = e.target.files[0].name;
            $(this).siblings('label#upload-display').html('<i class="fas fa-upload"></i> ' + file_name);
        });
    }
});
// GenarateID
function uniqueId() {
    return Math.round(new Date().getTime() + 1000 + (Math.random() * 100)) + (Math.random() * 100);
}
