@extends("Base::layouts.master")

@section("content")
    <div id="store-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('get.store.list') }}">{{ trans("Store") }}</a></li>
                    <li class="breadcrumb-item active"><a href="#">{{ trans("Create Store") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Create Store") }}</h3></div>
        </div>
    </div>
    <div id="store">
        @include('Store::_form')
    </div>
@endsection
