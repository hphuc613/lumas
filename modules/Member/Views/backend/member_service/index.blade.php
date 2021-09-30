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
                @if(!empty($appointment_in_progressing = $member->getAppointmentInProgressing(\Modules\Appointment\Model\Appointment::SERVICE_TYPE)))
                    <a href="{{ route('get.appointment.update', $appointment_in_progressing->id) }}"
                       class="btn btn-warning text-light"
                       id="update-booking" data-toggle="modal"
                       data-target="#form-modal"
                       data-title="{{ trans('View Appointment') }}">
                        &nbsp; {{ trans('Appointment Progressing') }}
                    </a>
                @else
                    <a href="{{ route('get.member.appointment', $member->id) }}" class="btn btn-danger text-light">
                        &nbsp; {{ trans('Check In Appointment Here') }}
                    </a>
                @endif
                <a href="{{ route('get.member_service.add', $member->id) }}" class="btn btn-main-color">
                    <i class="fa fa-plus"></i> &nbsp; {{ trans('Add new') }}
                </a>
                <a href="{{ route('get.member.appointment',$member->id) }}"
                   class="btn btn-info">{{ trans('Go to Appointment Listing') }}</a>
            </div>
        </div>
    </div>

    <div id="member_service" class="card">
        <div class="card-body">
            @include('Member::backend.member_service.listing.progressing')
            <div class="row">
                <div class="col-md-4">
                    @if(!isset($member_service))
                        @include('Member::backend.member_service._form')
                    @else
                        @include('Member::backend.member_service.view')
                    @endif
                </div>
                <div class="col-md-8">
                    @include('Member::backend.member_service.listing.list')
                </div>
                <div class="col-md-12 mt-5">
                    @include('Member::backend.member_service.listing.history')
                </div>
            </div>
        </div>
    </div>
    {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax', 'size' => 'modal-lg'])  !!}
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
