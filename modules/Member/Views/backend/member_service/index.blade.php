@extends('Base::layouts.master')
@php
    use App\AppHelpers\Helper;
    $segment = Helper::segment(2);
    $prompt  = [null => trans('Select')]
@endphp
@section('content')
    <div id="role-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('get.member.list') }}">{{ trans('Client') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('Add Service') }}</li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Add Service For Client") }}</h3></div>
            <div class="group-btn">
                <a href="{{ route('get.member_service.add', $member->id) }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> &nbsp; {{ trans('Add new') }}
                </a>
            </div>
        </div>
    </div>

    <div id="member_service" class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @include('Member::backend.member_service._form')
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ trans('Service Listing') }}</h5>
                        </div>
                        <div class="card-body">
                            @include('Member::backend.member_service.service_list')
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-5">
                    @include('Member::backend.member_service.history')
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    {!! JsValidator::formRequest('Modules\Member\Http\Requests\MemberServiceRequest') !!}
    <script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
    <script>
        @if(isset($member_service))
        $(".service-relate").prop("disabled", true);
        $("#btn-add-service").click(function () {
            $(".service-relate").prop("disabled", false);
        })
        @endif
        /** Get service list by service type */
        $(document).on('change', '#service', function () {
            var service = $(this);
            var service_id = service.val();
            $.ajax({
                url: "{{ route('get.voucher.get_list_by_service', '') }}/" + service_id,
                method: "get"
            }).done(function (response) {
                service.parents('form').find('#voucher').html(response);
            });
        });
        $(".tooltip-content").tooltip({
            content: function () {
                return $(this).attr('data-tooltip');
            },
            position: {
                my: "center bottom", // the "anchor point" in the tooltip element
                at: "center top-10", // the position of that anchor point relative to selected element
            }
        });
    </script>
@endpush
