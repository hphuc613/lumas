@extends("Base::layouts.master")

@section("content")
    <div id="role-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('get.setting.list') }}">{{ trans('Setting') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ trans('Email Config') }}</li>
                </ol>
            </nav>
        </div>

        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans('Website Config') }}</h3></div>
        </div>
    </div>

    <div id="user" class="card">
        <div class="card-body">
            <form action="" method="post" id="role-form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="name">{{ trans('Logo') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="host"
                                       name="{{ \Modules\Setting\Model\Website::LOGO }}"
                                       value="{{ $setting[\Modules\Setting\Model\Website::LOGO] ?? null}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="name">{{ trans('Background Login') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="host"
                                       name="{{ \Modules\Setting\Model\Website::BG_LOGIN }}"
                                       value="{{ $setting[\Modules\Setting\Model\Website::BG_LOGIN] ?? null}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-group mt-5 d-flex justify-content-between">
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary mr-2">{{ trans('Save') }}</button>
                        <button type="reset" class="btn btn-default">{{ trans('Reset') }}</button>
                    </div>
                    @if(Auth::user()->getRoleAttribute()->id === \Modules\Role\Model\Role::getAdminRole()->id)
                        <div>
                            <a href="{{ route("get.setting.testSendMail") }}"
                               class="btn btn-primary">{{ trans('Test Send Mail') }}</a>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection