$(document).ready(function () {
    /** Modal Ajax */
    $(document).on('click', '[data-toggle=modal]', function () {
        var modal = $(this).attr('data-target');
        if ($(modal).hasClass('modal-ajax')) {
            var url = $(this).attr('href');
            $.ajax({
                url: url,
                type: 'GET',
            }).done(function (response) {
                var html = response + '<script src="/assets/backend/jquery/main.js"></script>';
                $(modal).find('.modal-body').html(html);
                $(modal).find('form').attr('action', url);
            });
        }
    });
});
