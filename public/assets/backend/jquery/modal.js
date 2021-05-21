$(document).ready(function () {
    $('#form-modal').on('hidden.bs.modal', function () {
        if ($(document).find(".datetimepicker").length > 1)
            $(document).find(".datetimepicker").not(':first').remove();
    })
    /** Modal Ajax */
    $(document).on('click', '[data-toggle=modal]', function () {
        var modal = $(this).attr('data-target');
        var title = $(this).attr('data-title');
        var url = $(this).attr('href');
        getFormView(modal, title, url);
    });

    function getFormView(modal, title, url) {
        if ($(modal).hasClass('modal-ajax')) {
            $.ajax({
                url: url,
                type: 'GET',
            }).done(function (response) {
                html = response;
                var url_current = location.pathname;
                if (url_current.indexOf('admin') != -1) {
                    html += '<script src="/assets/backend/jquery/main.js"></script>';
                }
                $(modal).find('.modal-header h5').html(title);
                $(modal).find('.modal-body').html(html);
                $(modal).find('form').attr('action', url);
            });
        }
    }
});
