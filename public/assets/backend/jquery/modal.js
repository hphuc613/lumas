$(document).ready(function () {
    $('.modal-ajax').on('hidden.bs.modal', function () {
        $(document).find('.datetime-modal').html('');
        loadScriptTag();
    })
    /** Modal Ajax */
    $(document).on('click', '[data-toggle=modal]', function () {
        var modal = $(this).attr('data-target');
        var title = $(this).attr('data-title');
        var url = $(this).attr('href');
        getFormView(modal, title, url);
    });

});

function getFormView(modal, title, url) {
    if ($(modal).hasClass('modal-ajax')) {
        $.ajax({
            async: true,
            url: url,
            type: 'GET',
        }).done(function (response) {
            var html = response;
            $(modal).find('.modal-header h5').html(title);
            $(modal).find('.modal-body').html(html);
            $(modal).find('form').attr('action', url);
            loadScriptTag();
        });
    }
}

function loadScriptTag() {
    const body = $('body');
    const script_current = $('script.master-script');
    const url_current = location.pathname;
    script_current.remove();
    if (url_current.indexOf('admin') !== -1) {
        script_current.each(function (i, script) {
            body.append(script);
        })
    }
}
