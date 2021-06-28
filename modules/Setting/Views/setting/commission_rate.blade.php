@extends("Base::layouts.master")
@section("content")
    <div id="role-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('get.setting.list') }}">{{ trans('Setting') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ trans('Commission Rate Config') }}</li>
                </ol>
            </nav>
        </div>

        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans('Commission Rate Config') }}</h3></div>
        </div>
    </div>

    <div id="user" class="card">
        <div class="card-body">
            <form action="" method="post" id="role-form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="logo">{{ trans('Company Income') }}</label>
                            {!! Form::select( \Modules\Setting\Model\CommissionRateSetting::COMPANY_INCOME.'[]', $roles, $company_income_setting,
                                    ['class'    => 'form-control select2',
                                     'multiple' => 'multiple',
                                     'id'       => 'company-income']) !!}
                        </div>
                        <div class="form-group">
                            <label for="logo">{{ trans('Person Income') }}</label>
                            <ul>
                                @foreach($person_income_roles as $role)
                                    <li class="text-primary">{{ $role->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="logo">{{ trans('Service Rate') }}</label>
                            <input type="number" name="{{ \Modules\Setting\Model\CommissionRateSetting::SERVICE_RATE }}"
                                   class="form-control" value="{{ $service_rate }}">
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
