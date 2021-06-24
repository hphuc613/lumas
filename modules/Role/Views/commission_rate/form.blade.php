<form action="" method="post" id="commission-rate-form">
    {{ csrf_field() }}
    <div class="form-group row">
        <div class="col-md-4">
            <label>{{ trans('Role') }}</label>
        </div>
        <div class="col-md-8">
            {{ $role->name ?? $rate->role->name }}
            <input type="hidden" name="role_id" value="{{ $role->id ?? $rate->role->id }}">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <label for="target">{{ trans('Target') }}</label>
        </div>
        <div class="col-md-8">
            <input type="number" class="form-control" id="target" name="target"
                   value="{{ $rate->target ?? old('target') }}">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <label for="rate">{{ trans('Rate(%)') }}</label>
        </div>
        <div class="col-md-8">
            <input type="number" class="form-control" id="rate" name="rate" value="{{ $rate->rate ?? old('rate') }}">
        </div>
    </div>
    <div class="input-group mt-5">
        <button type="submit" class="btn btn-main-color mr-2">{{ trans('Save') }}</button>
        <button type="reset" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
    </div>
</form>
{!! JsValidator::formRequest('Modules\Role\Http\Requests\CommissionRateRequest','#commission-rate-form') !!}
