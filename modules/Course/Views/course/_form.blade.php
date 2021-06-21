<form action="" method="post" id="course-form">
    @csrf
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="name">{{ trans('Name') }}</label>
                    <input type="text" id="name" class="form-control" name="name"
                           value="{{ $course->name ?? old('name') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label for="price">{{ trans('Price') }}</label>
                    <input type="text" id="price" class="form-control" name="price"
                           value="{{ $course->price ?? old('price') }}">
                </div>
                <div class="col-md-6 form-group">
                    <label for="category-id">{{ trans('Course Category') }}</label>
                    {!! Form::select('category_id',[null => trans('Select')] + $course_categories, $course->category_id ?? null,
                        ['id' => 'category-id', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
                </div>
                <div class="col-md-6 form-group">
                    <label for="status">{{ trans('Status') }}</label>
                    <select name="status" id="status" class="select2 form-control">
                        @foreach($statuses as $key => $status)
                            <option value="{{ $key }}"
                                    @if(isset($course) && $course->status === $key) selected @endif>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="form-group">
                <label for="description">{{ trans('Description') }}</label>
                <textarea name="description" class="form-control" id="description"
                          rows="6">{{ $course->description ?? old('description') }}</textarea>
            </div>
        </div>
        <div class="col-md-12 input-group mt-5">
            <button type="submit" id="save" class="btn btn-main-color mr-2">{{ trans('Save') }}</button>
            <button type="reset" class="btn btn-default">{{ trans('Reset') }}</button>
        </div>
    </div>
</form>
@push('js')
    {!! JsValidator::formRequest('Modules\Course\Http\Requests\CourseRequest') !!}
@endpush
