<form action="" method="post" id="role-form">
    {{ csrf_field() }}
    <div class="form-group row">
        <div class="col-md-4">
            <label for="name">{{ trans('Name') }}</label>
        </div>
        <div class="col-md-8">
            <input type="text" class="form-control" id="name" name="name" value="{{ $role->name ?? old('name') }}">
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
                    <option value="{{ $key }}" @if(isset($role) && $role->status === $key) selected @endif>{{ $status }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <label for="description">{{ trans('Description') }}</label>
        </div>
        <div class="col-md-8">
            <textarea name="description" id="description" class="form-control" rows="5">{{ $role->description ?? NULL }}</textarea>
        </div>
    </div>
    <div class="input-group mt-5">
        <button type="submit" class="btn btn-primary mr-2">{{ trans('Save') }}</button>
        <button type="reset" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
    </div>
</form>
{!! JsValidator::formRequest('Modules\Role\Http\Requests\RoleValidation','#role-form') !!}
