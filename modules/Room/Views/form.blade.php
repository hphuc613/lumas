<form action="" method="post" id="room-form">
    {{ csrf_field() }}
    <div class="form-group row">
        <div class="col-md-4">
            <label for="name">{{ trans('Room name') }}</label>
        </div>
        <div class="col-md-8">
            <input type="text" class="form-control" id="name" name="name" value="{{ $room->name ?? old('name') }}">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-4">
            <label for="number">{{ trans('Room number') }}</label>
        </div>
        <div class="col-md-8">
            <input type="text" class="form-control" id="number" name="number"
                   value="{{ $room->number ?? old('number') }}">
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
                            @if(isset($room) && $room->status === $key) selected @endif>{{ $status }}</option>
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
                      rows="5">{{ $room->description ?? NULL }}</textarea>
        </div>
    </div>
    <div class="input-group mt-5">
        <button type="submit" class="btn btn-main-color mr-2">{{ trans('Save') }}</button>
        <button type="reset" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
    </div>
</form>
{!! JsValidator::formRequest('Modules\Room\Http\Requests\RoomRequest','#room-form') !!}
