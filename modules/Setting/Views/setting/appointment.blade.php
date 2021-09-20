@extends("Base::layouts.master")

@section("content")
    <div id="role-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('get.setting.list') }}">{{ trans('Setting') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ trans('Appointment Config') }}</li>
                </ol>
            </nav>
        </div>

        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans('Appointment Config') }}</h3></div>
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-info">{{ trans('Go Back') }}</a>
            </div>
        </div>
    </div>

    <div id="user" class="card">
        <div class="card-body">
            <form action="" method="post" id="role-form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="logo">{{ trans('Timer (Minutes)') }}</label>
                            <input type="number" class="form-control" id="logo"
                                   name="{{ \Modules\Setting\Model\AppointmentSetting::TIMER }}"
                                   value="{{ $setting->getValueByKey(\Modules\Setting\Model\AppointmentSetting::TIMER) }}">
                        </div>
                    </div>
                </div>
                <div class="input-group mt-5 d-flex justify-content-between">
                    <div class="mb-3">
                        <button type="submit" class="btn btn-main-color mr-2">{{ trans('Save') }}</button>
                        <button type="reset" class="btn btn-default">{{ trans('Reset') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
