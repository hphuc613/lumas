(function ($) {
    $(document).ready(function () {
        /** Active menu **/
        var url = window.location.pathname;
        var path_arr = url.split('/');
        var menu_link = $('.left-sidebar').find('.menu-link');
        $.each(menu_link, function (i, item) {
            if ($(item).parents('li.li-has-child').length > 0) { /*Has children*/
                var child = $(item).parents('li.li-has-child').find('a.menu-link-child');
                if (child.length > 0) {
                    $.each(child, function (i, child_item) {
                        var child_id = $(child_item).attr('id');
                        if (child_id === $(path_arr).get(2)) {
                            $(item).addClass('active');
                            $(child_item).addClass('active');
                            $(child_item).parents('ul').addClass('show');
                        } else {
                            $(child_item).removeClass('active');
                        }
                    });
                } else {
                    $(item).parents('li.li-has-child').remove()
                }
            } else {
                var active_id = $(path_arr).get(2) === undefined ? 'dashboard' : $(path_arr).get(2);
                if ($(item).attr('id') === active_id) {
                    $(item).addClass('active');
                }
            }
        });

        /** Show/Hide Left Sidebar **/
        function menuAction(btn, menu, status) {
            var item_link = menu.find('a');
            var item_title = menu.find('.title-link');
            var item_has_child = menu.find('.has-child');

            if (item_has_child.length > 0) {
                menuActionHandle(btn, status, menu, item_link, item_title, item_has_child);
            } else {
                menuActionHandle(btn, status, menu, item_link, item_title, null);
            }
        }

        function menuActionHandle(btn, status, menu, item_link, item_title, item_has_child) {
            if (status === 'show') {
                /*Show full menu*/
                menu.animate({width: '220px'});
                menu.removeClass('hide-menu');
                btn.parent('#logo').animate({width: '220px'});

                /* Has Child */
                if (item_has_child !== null) {
                    $.each(item_has_child, function (i, item) {
                        var list_child = $(item).parents('li').find('.child');
                        list_child.parents('li').addClass('position-relative');
                        setTimeout(function () {
                            $(item).find('span').show();
                        }, 150);
                        list_child.removeClass('list-child-hide');
                        list_child.removeAttr('style');
                    });
                }

                /* No child */
                item_link.removeClass('hide-title-link');
                setTimeout(function () {
                    item_title.removeClass('hide-title');
                }, 200);
                item_title.show();
            } else {
                /*Hide menu only show icon*/
                menu.animate({width: '60px'}, 'slow');
                menu.addClass('hide-menu');
                btn.parent('#logo').animate({width: '80px'});

                /* Has Child */
                if (item_has_child !== null) {
                    /* Has Child */
                    $.each(item_has_child, function (i, item) {
                        var list_child = $(item).parents('li').find('.child');
                        /* Has Child */
                        list_child.parents('li').addClass('position-relative');
                        $(item).find('span').hide();
                        list_child.hide();
                        list_child.addClass('list-child-hide hide-title-link');
                    });
                }

                /* No child */
                item_link.addClass('hide-title-link');
                item_title.addClass('hide-title');
                item_title.hide();
            }
        }

        $(document).on('click', '#menu-button', function () {
            var left_sidebar = $('.left-sidebar');
            if (left_sidebar.is(":hidden")) {
                left_sidebar.addClass('mobile-view');
                left_sidebar.animate({left: '0px'});
                left_sidebar.show();
            } else {
                if (!left_sidebar.hasClass('mobile-view')) {
                    if (left_sidebar.hasClass('hide-menu')) {
                        menuAction($(this), left_sidebar, 'show');
                        $('.page-wrapper').animate({marginLeft: '220px'});
                    } else {
                        menuAction($(this), left_sidebar, 'hide');
                        $('.page-wrapper').animate({marginLeft: '60px'});
                    }
                } else {
                    left_sidebar.animate({left: '-220px'});
                    setTimeout(function () {
                        left_sidebar.hide();
                    }, 500);
                }
            }
        });

        $(document).on({
            mouseenter: function () {
                $(this).find('.hide-title').show();
                $(this).parent('li').find('.list-child-hide').show();
            },
            mouseleave: function () {
                $(this).find('.hide-title').hide();
                $(this).parent('li').find('.list-child-hide').hide();
            }
        }, ".hide-title-link");

        $(document).on({
            mouseleave: function () {
                $(this).next(".list-child-hide").hide();
            }
        }, "#has-child");
    })
})(jQuery);
