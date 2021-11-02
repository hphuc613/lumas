<?php

use Modules\Setting\Model\CommissionRateSetting;

$target_company = json_decode(CommissionRateSetting::getValueByKey(CommissionRateSetting::COMPANY_INCOME), 1);
$role = $role ?? $rate->role;
?>
<form action="" method="post" id="commission-rate-form">
    {{ csrf_field() }}
    <div class="form-group row">
        <div class="col-md-4">
            <label>{{ trans('Role') }}</label>
        </div>
        <div class="col-md-8">
            {{ $role->name }}
            <input type="hidden" name="role_id" value="{{ $role->id }}">
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
    @if(in_array($role->id, $target_company))
        <div class="form-group row">
            <div class="col-md-4">
                <label for="bonus">{{ trans('Bonus') }}</label>
            </div>
            <div class="col-md-8">
                <input type="number" class="form-control" id="bonus" name="bonus"
                       value="{{ $rate->bonus ?? old('bonus') }}">
            </div>
        </div>
    @else
        <div class="form-group row">
            <div class="col-md-4">
                <label for="rate">{{ trans('Rate(%)') }}</label>
            </div>
            <div class="col-md-8">
                <input type="number" class="form-control" id="rate" name="rate"
                       value="{{ $rate->rate ?? old('rate') }}">
            </div>
        </div>
    @endif
    <div class="input-group mt-5">
        <button type="submit" class="btn btn-main-color mr-2">{{ trans('Save') }}</button>
        <button type="reset" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
    </div>
</form>
{!! JsValidator::formRequest('Modules\Role\Http\Requests\CommissionRateRequest','#commission-rate-form') !!}
