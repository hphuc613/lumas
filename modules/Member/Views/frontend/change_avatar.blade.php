<form action="" method="post" id="role-form">
    @csrf

    <div class="input-group mt-5">
        <button type="submit" class="btn btn-success mr-2">{{ trans('Save') }}</button>
        <button type="reset" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
    </div>
</form>
