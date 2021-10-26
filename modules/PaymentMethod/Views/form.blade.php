<form action="" method="post" id="payment-method-form">
    {{ csrf_field() }}
    <div class="form-group row">
        <div class="col-md-4">
            <label for="name">{{ trans('Payment Method name') }}</label>
        </div>
        <div class="col-md-8">
            <input type="text" class="form-control" id="name" name="name"
                   value="{{ $payment_method->name ?? old('name') }}">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <label for="status">{{ trans('Status') }}</label>
        </div>
        <div class="col-md-8">
            <select name="status" id="status" class="select2 form-control w-100">
                <option value="">{{ trans('Select') }}</option>
                @foreach($statuses as $key => $status)
                    <option value="{{ $key }}"
                            @if(isset($payment_method) && $payment_method->status === $key) selected @endif>{{ $status }}</option>
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
                      rows="5">{{ $payment_method->description ?? NULL }}</textarea>
        </div>
    </div>
    <div class="input-group mt-5">
        <button type="submit" class="btn btn-main-color mr-2">{{ trans('Save') }}</button>
        <button type="reset" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
    </div>
</form>
{!! JsValidator::formRequest('Modules\PaymentMethod\Http\Requests\PaymentMethodRequest','#payment-method-form') !!}
