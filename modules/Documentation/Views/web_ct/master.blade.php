<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Lumas Guide</title>
    <link href="{{ asset('assets/frontend/assets/node_modules/prism/prism.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/documentation/dist/css/style.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <link href="{{ asset('assets/frontend/assets/node_modules/Magnific-Popup-master/dist/magnific-popup.css') }}"
          rel="stylesheet">
    <link href="{{ asset('assets/frontend/dist/css/user-card.css') }}" rel="stylesheet">
</head>
<style>
    .left-sidebar {
        width: 300px;
        padding-top: 0;
    }

    @media (min-width: 1024px) {
        .page-wrapper {
            margin-left: 300px;
        }
    }
</style>
<body class="skin-blue fixed-layout">
<div id="main-wrapper p-0">
    <aside class="left-sidebar">
        <div class="scroll-sidebar">
            <nav class="sidebar-nav">
                <ul id="sidebarnav">
                    <li>
                        <a class="waves-effect waves-dark" href="#intro">
                            <i class="fa fa-circle-o"></i>
                            <span class="hide-menu">Introduction</span>
                        </a>
                    </li>
                    @foreach($documents as $document)
                        <li>
                            <a class="waves-effect waves-dark" href="#{{ $document->key }}">
                                <i class="fa fa-circle-o"></i>
                                <span class="hide-menu">{{ $document->name }}</span>
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a class="waves-effect waves-dark" href="#thanks">
                            <i class="fa fa-circle-o"></i>
                            <span class="hide-menu">Thanks</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row p-t-30">
                <div class="col-12">
                    <div class="card" id="intro">
                        <div class="card-body">
                            <div class="display-5 text-center text-success">
                                用戶指南網站
                            </div>
                        </div>
                    </div>
                    @foreach($documents as $document)
                        <div class="card" id="{{$document->key}}">
                            <div class="card-body">
                                <h3>{{$document->name}}</h3>
                                <hr>
                                {!! $document->content !!}
                            </div>
                        </div>
                    @endforeach
                    <div class="card" id="thanks">
                        <div class="card-body">
                            <div class="display-5 text-center text-success">
                                Thank you
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('assets/frontend/assets/node_modules/jquery/jquery-3.2.1.min.js') }}"></script>
<!-- Bootstrap tether Core JavaScript -->
<script src="{{ asset('assets/frontend/assets/node_modules/popper/popper.min.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- slimscrollbar scrollbar JavaScript -->
<script src="{{ asset('assets/frontend/documentation/dist/js/perfect-scrollbar.jquery.min.js') }}"></script>
<!--Wave Effects -->
<script src="{{ asset('assets/frontend/documentation/dist/js/waves.js') }}"></script>
<!--Menu sidebar -->
<script src="{{ asset('assets/frontend/documentation/dist/js/sidebarmenu.js') }}"></script>
<!--Custom JavaScript -->
<script src="{{ asset('assets/frontend/assets/node_modules/prism/prism.js') }}"></script>
<script src="{{ asset('assets/frontend/documentation/dist/js/custom.min.js') }}"></script>
<script
    src="{{ asset('assets/frontend/assets/node_modules/Magnific-Popup-master/dist/jquery.magnific-popup.min.js') }}"></script>
<script
    src="{{ asset('assets/frontend/assets/node_modules/Magnific-Popup-master/dist/jquery.magnific-popup-init.js') }}"></script>
<script>
    $('#sidebarnav a').click(function () {
        $('html, body').animate({
            scrollTop: $($(this).attr('href')).offset().top - 85
        }, 500);
        return false;
    });

    var lastId,
        topMenu = $("#sidebarnav"),
        topMenuHeight = topMenu.outerHeight(),
        menuItems = topMenu.find("a"),
        scrollItems = menuItems.map(function () {
            var item = $($(this).attr("href"));
            if (item.length) {
                return item;
            }
        });

    $(window).scroll(function () {

        var fromTop = $(this).scrollTop() + topMenuHeight - 85;
        var cur = scrollItems.map(function () {
            if ($(this).offset().top < fromTop)
                return this;
        });

        cur = cur[cur.length - 1];
        var id = cur && cur.length ? cur[0].id : "";

        if (lastId !== id) {
            lastId = id;

            menuItems
                .removeClass("active")
                .filter("[href='#" + id + "']").addClass("active");
        }
    });
</script>
</body>

</html>
