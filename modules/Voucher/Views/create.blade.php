@extends("Base::layouts.master")

@section("content")
    <div id="voucher-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('get.voucher.list') }}">{{ trans("Voucher") }}</a>
                    </li>
                    <li class="breadcrumb-item active"><a href="#">{{ trans("Create Voucher") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Create Voucher") }}</h3></div>
        </div>
    </div>
    <!--Search box-->
    <div id="voucher">
        <div class="card">
            <div class="card-body">
                @include('Voucher::_form')
            </div>
        </div>
    </div>
@endsection
