$(document).ready(function () {
    changeLanguage();
});

function changeLanguage() {
    $('#change-language').change(function () {
        var lang = $(this).val();
        window.location.href = '/change-locale/'+lang;
    });
}
