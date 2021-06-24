@extends("Base::layouts.master")

@section("content")
    <div id="setting-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans('Settings') }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans('Settings') }}</h3></div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-2">
                    <a href="{{ route("get.setting.emailConfig") }}" class="btn btn-light btn-setting">
                        <span>{{ trans('Email Setting') }} </span>
                        <div>{{ trans('To configuration the site email and SMTP') }}</div>
                    </a>
                </div>
                <div class="col-md-6 mb-2">
                    <a href="{{ route("get.setting.websiteConfig") }}" class="btn btn-light btn-setting">
                        <span>{{ trans('Website Setting') }} </span>
                        <div>{{ trans('To configuration the website') }}</div>
                    </a>
                </div>
                <div class="col-md-6 mb-2">
                    <a href="{{ route("get.setting.appointmentConfig") }}" class="btn btn-light btn-setting">
                        <span>{{ trans('Appointment Setting') }} </span>
                        <div>{{ trans('To configuration appointments') }}</div>
                    </a>
                </div>
                <div class="col-md-6 mb-2">
                    <a href="{{ route("get.setting.commissionRateConfig") }}" class="btn btn-light btn-setting">
                        <span>{{ trans('Commission Rate Setting') }} </span>
                        <div>{{ trans('To configuration calculate commission') }}</div>
                    </a>
                </div>
                @env('local')
                    <div class="col-md-6 mb-2">
                        <a href="{{ route("get.setting.langManagement") }}" class="btn btn-light btn-setting">
                        <span>{{ trans('Language Setting') }}
                            <i class="fas fa-exclamation-circle text-danger" data-toggle="tooltip" data-placement="top"
                               title="Not release yet"></i>
                        </span>
                            <div>{{ trans('To manage system language') }}</div>
                        </a>
                    </div>
                @endenv
            </div>
        </div>
    </div>
@endsection
