<form action="" method="post" id="import-form" enctype="multipart/form-data">
    @csrf
    <div class="input-group">
        <input name="file" type="file" id="file" class="upload-style w-100" accept=".xlsx, .xls, .csv, .ods">
        <label id="upload-display" class="d-block bg-info  w-100" for="file">
            <i class="fas fa-upload"></i>
            <span>{{ trans("Choose File...") }}</span>
        </label>
    </div>
    <div class="input-group mt-5">
        <button type="submit" class="btn btn-main-color mr-2">{{ trans('Save') }}</button>
        <button type="reset" class="btn btn-default"
                onclick='window.location.reload(true);'>{{ trans('Cancel') }}</button>
    </div>
</form>
{!! JsValidator::formRequest('Modules\Member\Http\Requests\MemberImportRequest', "#import-form")->ignore('file') !!}
