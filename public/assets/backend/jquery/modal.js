$(document).ready(function () {
    /** Modal Ajax */
    $(document).on('click', '[data-toggle=modal]', function () {
        var modal = $(this).attr('data-target');
        var title = $(this).attr('data-title');
        if ($(modal).hasClass('modal-ajax')) {
            var url = $(this).attr('href');
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
    });
});
