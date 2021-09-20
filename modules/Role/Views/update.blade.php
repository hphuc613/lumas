@extends("Base::layouts.master")

@section("content")
    <div id="role-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('get.role.list') }}">{{ trans("Roles") }}</a></li>
                    <li class="breadcrumb-item active"><a href="#">{{ trans("Update Role") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Update Role") }}</h3></div>
            <div>
                <a href="{{ url()->previous() }}" class="btn btn-info">{{ trans('Go Back') }}</a>
            </div>
        </div>
    </div>
    <div id="role">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ trans('Update') }}</h5>
                            </div>
                            <div class="card-body">
                                <form action="" method="post" id="role-form">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label for="name">{{ trans('Name') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" id="name" name="name"
                                                   value="{{ $role->name ?? old('name') }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label for="status">{{ trans('Status') }}</label>
                                        </div>
                                        <div class="col-md-8">
                                            <select name="status" id="status" class="select2 form-control">
                                                <option value="">{{ trans('Select') }}</option>
                                                @foreach($statuses as $key => $status)
                                                    <option value="{{ $key }}"
                                                            @if(isset($role) && $role->status === $key) selected @endif>{{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label for="description">{{ trans('Description') }}</label>
                                        </div>
                                        <div class="col-md-8">
            <textarea name="description" id="description" class="form-control"
                      rows="5">{{ $role->description ?? NULL }}</textarea>
                                        </div>
                                    </div>
                                    <div class="input-group mt-5">
                                        <button type="submit"
                                                class="btn btn-main-color mr-2">{{ trans('Save') }}</button>
                                        <button type="reset" class="btn btn-default"
                                                data-dismiss="modal">{{ trans('Cancel') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5>{{ trans('Commission Rates') }}</h5>
                                <a href="{{ route('get.commission_rate.create', $role->id) }}"
                                   class="btn btn-main-color" data-toggle="modal"
                                   data-target="#form-modal" data-title="{{ trans('Create Commission Rate') }}">
                                    <i class="fa fa-plus"></i> &nbsp; {{ trans('Add new') }}
                                </a>
                            </div>
                            <div class="card-body">
                                @include('Role::commission_rate.index')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax'])  !!}
@endsection
@push('js')
    {!! JsValidator::formRequest('Modules\Role\Http\Requests\RoleValidation','#role-form') !!}
@endpush
