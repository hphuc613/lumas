<form action="" method="post">
    @csrf
    <div class="form-group">
        <label for="create-name">{{ trans('Name') }}</label>
        <input type="text" name="name" id="create-name" class="form-control">
    </div>
    <div class="input-group">
        <button type="submit" class="btn btn-main-color mr-2">{{ trans('Submit') }}</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
    </div>
</form>
{!! JsValidator::formRequest('Modules\Documentation\Http\Requests\DocumentationRequest') !!}