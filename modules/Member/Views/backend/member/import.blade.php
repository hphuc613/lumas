<form action="" method="post" id="role-form" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <input name="file" type="file" id="upload-file" class="upload-style w-100" accept=".xlsx, .xls, .csv, .ods">
        <label id="upload-display" class="d-block bg-info  w-100" for="upload-file">
            <i class="fas fa-upload"></i>
            <span>Choose File...</span>
        </label>
    </div>
    <div class="input-group mt-5">
        <button type="submit" class="btn btn-primary mr-2">{{ trans('Save') }}</button>
        <button type="reset" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
    </div>
</form>
