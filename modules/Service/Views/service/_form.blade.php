<form action="" method="post" id="service-form">
    @csrf
    <div class="row">
        <div class="col-md-4 form-group">
            <label for="name">{{ trans('Name') }}</label>
            <input type="text" id="name" class="form-control" name="name" value="{{ $service->name ?? old('name') }}">
        </div>
        <div class="col-md-4 form-group">
            <label for="name">{{ trans('Service Types') }}</label>
            {!! Form::select('type_id',[null => 'Please Select'] + $service_types, $service->type_id ?? null,
                ['id' => 'type-id', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
        </div>
        <div class="col-md-4 form-group">
            <label for="intend_time">{{ trans('Intend Time (Minute)') }}</label>
            <input type="number" id="intend_time" class="form-control" name="intend_time"
                   value="{{ $service->intend_time ?? old('intend_time') }}">
        </div>
        <div class="col-md-4 form-group">
            <label for="status">{{ trans('Status') }}</label>
            <select name="status" id="status" class="select2 form-control">
                @foreach($statuses as $key => $status)
                    <option value="{{ $key }}"
                            @if(isset($service) && $service->status === $key) selected @endif>{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 form-group">
            <label for="price">{{ trans('Price') }}</label>
            <input type="number" min="0.00" max="10000.00" step="0.01" id="price" class="form-control" name="price"
                   value="{{ $service->price ?? old('price') }}">
        </div>
        <div class="col-md-4 form-group">
            <label for="additional_price">{{ trans('Additional Price') }}</label>
            <input type="text" id="additional_price" class="form-control" name="additional_price"
                   value="{{ $service->additional_price ?? old('additional_price') }}">
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="additional_service">{{ trans('Additional Service') }}</label>
                    <textarea name="additional_service" class="form-control" id="additional_service"
                              rows="6">{{ $service->additional_service ?? old('additional_service') }}</textarea>
                </div>
                <div class="col-md-6 form-group">
                    <label for="description">{{ trans('Description') }}</label>
                    <textarea name="description" class="form-control" id="description"
                              rows="6">{{ $service->description ?? old('description') }}</textarea>
                </div>
            </div>
        </div>
        <div class="col-md-12 input-group mt-5">
            <button type="submit" id="save" class="btn btn-main-color mr-2">{{ trans('Save') }}</button>
            <button type="reset" class="btn btn-default">{{ trans('Reset') }}</button>
        </div>
    </div>
</form>
@push('js')
    {!! JsValidator::formRequest('Modules\Service\Http\Requests\ServiceRequest') !!}
@endpush
