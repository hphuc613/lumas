@extends("Base::layouts.master")

@section("content")
    <div id="course-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('get.course.list') }}">{{ trans("Course") }}</a>
                    </li>
                    <li class="breadcrumb-item active"><a href="#">{{ trans("Create Course") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Create Course") }}</h3></div>
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-info">{{ trans('Go Back') }}</a>
            </div>
        </div>
    </div>
    <div id="course" class="card">
        <div class="card-body">
            @include('Course::course._form')
        </div>
    </div>
@endsection
