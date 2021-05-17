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
    </div>

    <div id="user" class="card">
        <div class="card-body">
            @include('Member::backend._form')
        </div>
    </div>
@endsection
