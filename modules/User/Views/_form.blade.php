@php
    use App\AppHelpers\Helper;
    use Modules\Role\Model\Role;

    $segment = Helper::segment(2);
    $role_admin_id = Role::getAdminRole()->id;
@endphp
<div class="col-md-6">
    <form action="" method="post">
        @csrf
        <div class="form-group row">
            <div class="col-md-4">
                <label for="name">{{ trans('Name') }}</label>
            </div>
            <div class="col-md-8">
                <input type="text" id="name" class="form-control" name="name" value="{{ $user->name ?? old('name') }}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label for="email">{{ trans('Email') }}</label>
            </div>
            <div class="col-md-8">
                <input type="email" id="email" class="form-control" name="email"
                       @if($segment === "update" && Auth::user()->getRoleAttribute()->id !== $role_admin_id)
                       readonly=""
                       @endif
                       value="{{ $user->email ?? old('email') }}">
            </div>
        </div>
        @can('update-user-role')
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="role">{{ trans('Role') }}</label>
                </div>
                <div class="col-md-8">
                    {!! Form::select('role_id',$roles,$user->role->id ?? null,['id' => 'role', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4">
                    <label for="status">{{ trans('Status') }}</label>
                </div>
                <div class="col-md-8">
                    <select name="status" id="status" class="select2 form-control">
                        @foreach($statuses as $key => $status)
                            <option value="{{ $key }}"
                                    @if(isset($user) && $user->status === $key) selected @endif>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endcan
        <div class="form-group row">
            <div class="col-md-4">
                <label for="password">{{ trans('Password') }}</label>
            </div>
            <div class="col-md-8">
                <input type="password" id="password" class="form-control" name="password">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-md-4">
                <label for="password_re_enter">{{ trans('Re-enter Password') }}</label>
            </div>
            <div class="col-md-8">
                <input type="password" id="password_re_enter" class="form-control" name="password_re_enter">
            </div>
        </div>
        <div class="input-group mt-5">
            <button type="submit" id="save" class="btn btn-primary mr-2">{{ trans('Save') }}</button>
            <button type="reset" class="btn btn-default">{{ trans('Reset') }}</button>
        </div>
    </form>
</div>
@push('js')
    {!! JsValidator::formRequest('Modules\User\Http\Requests\UserValidation') !!}
@endpush
