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
