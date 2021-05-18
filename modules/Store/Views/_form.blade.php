<div class="card">
    <div class="card-body">
        <form action="" method="post">
            @csrf
            <div class="row">
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name"
                                   value="{{ $store->name ?? old('name') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="location">Location</label>
                            <input type="text" name="location" class="form-control" id="location"
                                   value="{{ $store->location ?? old('location') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="open-close-time">Open/Close Time</label>
                            <input type="text" name="open_close_time" class="form-control" id="open-close-time"
                                   value="{{ $store->open_close_time ?? old('open_close_time') }}">
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="select2 form-control">
                                @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}"
                                            @if(isset($store) && $store->status == $key) selected @endif>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="address">Address</label>
                            <input type="text" name="address" class="form-control"
                                   value="{{ $store->address ?? old('address') }}">
                        </div>
                        <div class="col-md-12 input-group">
                            <button type="submit" id="save" class="btn btn-primary mr-2">{{ trans('Save') }}</button>
                            <button type="reset" class="btn btn-default">{{ trans('Reset') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@push('js')
    {!! JsValidator::formRequest('Modules\Store\Http\Requests\StoreRequest') !!}
@endpush
