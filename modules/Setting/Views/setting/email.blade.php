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
                        <div class="form-group">
                            <label for="MAIL_HOST">{{ trans('Host') }}</label>
                            <input type="text" class="form-control" id="MAIL_HOST"
                                   name="{{ \Modules\Setting\Model\MailConfig::MAIL_HOST }}"
                                   value="{{ $mail_config[\Modules\Setting\Model\MailConfig::MAIL_HOST] ?? NULL}}">
                        </div>
                        <div class="form-group">
                            <label for="MAIL_PORT">{{ trans('Port') }}</label>
                            <input type="text" class="form-control" id="MAIL_PORT"
                                   name="{{ \Modules\Setting\Model\MailConfig::MAIL_PORT }}"
                                   value="{{ $mail_config[\Modules\Setting\Model\MailConfig::MAIL_PORT]  ?? NULL }}">
                        </div>
                        <div class="form-group">
                            <label for="PROTOCOL">{{ trans('Protocol') }}</label>
                            <input type="text" class="form-control" id="PROTOCOL"
                                   name="{{ \Modules\Setting\Model\MailConfig::PROTOCOL }}"
                                   value="{{ $mail_config[\Modules\Setting\Model\MailConfig::PROTOCOL]  ?? NULL }}">
                        </div>
                        <div class="form-group">
                            <label for="MAIL_USERNAME">{{ trans('Username') }}</label>
                            <input type="text" class="form-control" id="MAIL_USERNAME"
                                   name="{{ \Modules\Setting\Model\MailConfig::MAIL_USERNAME }}"
                                   value="{{ $mail_config[\Modules\Setting\Model\MailConfig::MAIL_USERNAME]  ?? NULL }}">
                        </div>
                        <div class="form-group">
                            <label for="MAIL_PASSWORD">{{ trans('Password') }}</label>
                            <input type="text" class="form-control" id="MAIL_PASSWORD"
                                   name="{{ \Modules\Setting\Model\MailConfig::MAIL_PASSWORD }}"
                                   value="{{ $mail_config[\Modules\Setting\Model\MailConfig::MAIL_PASSWORD]  ?? NULL }}">
                        </div>
                        <div class="form-group">
                            <label for="MAIL_DRIVER">{{ trans('SMTP Server') }}</label>
                            <input type="text" class="form-control" id="MAIL_DRIVER"
                                   name="{{ \Modules\Setting\Model\MailConfig::MAIL_DRIVER }}"
                                   value="{{ $mail_config[\Modules\Setting\Model\MailConfig::MAIL_DRIVER]  ?? NULL }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="MAIL_ADDRESS">{{ trans('Email from address') }}</label>
                            <input type="text" class="form-control" id="MAIL_ADDRESS"
                                   name="{{ \Modules\Setting\Model\MailConfig::MAIL_ADDRESS }}"
                                   value="{{ $mail_config[\Modules\Setting\Model\MailConfig::MAIL_ADDRESS]  ?? NULL }}">
                        </div>
                        <div class="form-group">
                            <label for="MAIL_NAME">{{ trans('Email from name') }}</label>
                            <input type="text" class="form-control" id="MAIL_NAME"
                                   name="{{ \Modules\Setting\Model\MailConfig::MAIL_NAME }}"
                                   value="{{ $mail_config[\Modules\Setting\Model\MailConfig::MAIL_NAME]  ?? NULL }}">
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
