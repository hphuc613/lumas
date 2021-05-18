<style>
    .custom-file-input-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 85%;
    }
</style>
<form action="{{ route("frontend.post.member.change_avatar") }}" method="post" id="change-avatar-form"
      enctype="multipart/form-data">
    @csrf
    <div class="form-group custom-file">
        <input type="file" class="form-control custom-file-input" name="avatar">
        <label class="custom-file-label">
            <div class="custom-file-input-name">{{ trans('Choose file') }}</div>
        </label>
    </div>
    <div class="input-group mt-2">
        <button type="submit" class="btn btn-success mr-2">{{ trans('Save') }}</button>
        <button type="reset" class="btn btn-default" data-dismiss="modal">{{ trans('Cancel') }}</button>
    </div>
</form>
{!! JsValidator::formRequest('Modules\Member\Http\Requests\MemberRequest') !!}
<script>
    $(".custom-file-input").on("change", function () {
        var fileName = $(this).val().split("\\").pop();
        $(this).siblings(".custom-file-label").find('.custom-file-input-name').addClass("selected").html(fileName);
    });
</script>

