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
