<?php
?>
<form action="" method="post">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="form-group col-md-4">
                    <label for="code">{{ trans("Code") }}</label>
                    <input type="text" name="code" class="form-control" value="{{ $voucher->code ?? old('code') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="price">{{ trans("Price") }}</label>
                    <input type="text" name="price" class="form-control" value="{{ $voucher->price ?? old('price') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="status">{{ trans("Status") }}</label>
                    {!! Form::select('status', $statuses, $voucher->status ?? null, ["id" => "status", "class" => "form-control select2 w-100"]) !!}
                </div>
                <div class="form-group col-md-4">
                    <label for="start-at">{{ trans("Start day") }}</label>
                    <input type="text" name="start_at" id="start-at" class="form-control date"
                           value="{{ isset($voucher) ? formatDate($voucher->start_at) : old('start_at') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="end-at">{{ trans("End day") }}</label>
                    <input type="text" name="end_at" id="end-at" class="form-control date"
                           value="{{ isset($voucher) ? formatDate($voucher->end_at) : old('end_at') }}">
                </div>
                <div class="form-group col-md-4">
                    <label for="image">{{ trans("Image") }}</label>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="image" id="image"
                               value="{{ $voucher->image ?? old('image') }}">
                        <div class="input-group-prepend">
                            <button class="btn btn-primary btn-elfinder"
                                    type="button">{{ trans("Open File Manager") }}</button>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-4">
                    <label for="description">{{ trans("Description") }}</label>
                    <textarea name="description" id="description" class="form-control"
                              rows="10">{{ $voucher->description ?? old('description') }}</textarea>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            @if(isset($voucher))
                <img src="{{ asset($voucher->image) }}" class="w-100" alt="{{ asset($voucher->image) }}">
            @endif
        </div>
    </div>

    <div class="input-group">
        <button type="submit" class="btn btn-primary mr-2">{{ trans("Save") }}</button>
        <button type="reset" class="btn btn-default">{{ trans("Cancel") }}</button>
    </div>
</form>
@push('js')
    {!! JsValidator::formRequest('Modules\Voucher\Http\Requests\VoucherRequest') !!}
@endpush
