@extends('Base::layouts.master')
@section('content')
    <div id="role-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('get.member.list') }}">{{ trans('Client') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('Update Client') }}</li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Update Client") }}</h3></div>
            <div>
                <a href="{{ route("get.order.list",['member_id' => $member->id]) }}"
                   class="btn btn-outline-primary"><i class="fas fa-file-invoice"></i></a>
                <a href="{{ route('get.member.appointment',$member->id) }}"
                   class="btn btn-info"><i class="fas fa-calendar-check"></i></a>
            </div>
        </div>
    </div>

    <div id="user">
        @include('Member::backend.member._form')
    </div>
@endsection
