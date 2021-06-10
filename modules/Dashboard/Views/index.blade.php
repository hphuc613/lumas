@extends('Base::layouts.master')

@section('content')
    <div id="dashboard-module" class="pt-3">
{{--        @include('Dashboard::clock')--}}
        @include('Dashboard::all_information')
        @include('Dashboard::chart')
        @include('Dashboard::appointment')
    </div>
@endsection
@push('js')
    <script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
    <script>
        $(".tooltip-content").tooltip({
            content: function () {
                return $(this).attr('data-tooltip');
            },
            position: {
                my: "center bottom",
                at: "center top-10",
            }
        });
    </script>
@endpush
