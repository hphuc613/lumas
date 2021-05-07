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
            <div class="page-title"><h3>{{ trans('Email Config') }}</h3></div>
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
                                <label for="name">{{ trans('Host') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="host" name="{{ \Modules\Setting\Model\MailConfig::MAIL_HOST }}"
                                       value="{{ $mail_config[\Modules\Setting\Model\MailConfig::MAIL_HOST] ?? NULL}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="name">{{ trans('Port') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="host" name="{{ \Modules\Setting\Model\MailConfig::MAIL_PORT }}"
                                       value="{{ $mail_config[\Modules\Setting\Model\MailConfig::MAIL_PORT]  ?? NULL }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="name">{{ trans('Protocol') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="host" name="{{ \Modules\Setting\Model\MailConfig::PROTOCOL }}"
                                       value="{{ $mail_config[\Modules\Setting\Model\MailConfig::PROTOCOL]  ?? NULL }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="name">{{ trans('Username') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="host" name="{{ \Modules\Setting\Model\MailConfig::MAIL_USERNAME }}"
                                       value="{{ $mail_config[\Modules\Setting\Model\MailConfig::MAIL_USERNAME]  ?? NULL }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="name">{{ trans('Password') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="host" name="{{ \Modules\Setting\Model\MailConfig::MAIL_PASSWORD }}"
                                       value="{{ $mail_config[\Modules\Setting\Model\MailConfig::MAIL_PASSWORD]  ?? NULL }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="name">{{ trans('SMTP Server') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="host" name="{{ \Modules\Setting\Model\MailConfig::MAIL_DRIVER }}"
                                       value="{{ $mail_config[\Modules\Setting\Model\MailConfig::MAIL_DRIVER]  ?? NULL }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="name">{{ trans('Email from address') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="host" name="{{ \Modules\Setting\Model\MailConfig::MAIL_ADDRESS }}"
                                       value="{{ $mail_config[\Modules\Setting\Model\MailConfig::MAIL_ADDRESS]  ?? NULL }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="name">{{ trans('Email from name') }}</label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="host" name="{{ \Modules\Setting\Model\MailConfig::MAIL_NAME }}"
                                       value="{{ $mail_config[\Modules\Setting\Model\MailConfig::MAIL_NAME]  ?? NULL }}">
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
