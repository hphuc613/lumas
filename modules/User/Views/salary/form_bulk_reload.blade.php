<form action="" method="post">
    @csrf

    <div class="form-group">
        <label for="month">{{ trans('Month') }}</label>
        <input type="text" id="month" name="month" class="form-control month" value="{{ formatDate(time(), 'm-Y') }}">
    </div>
    <div class="input-group">
        <button type="submit" class="btn btn-main-color">{{ trans('Update') }}</button>
    </div>
</form>
<script>
    $(document).ready(function () {
        $('input.month').datetimepicker({
            format: 'mm-yyyy',
            fontAwesome: true,
            autoclose: true,
            startView: 3,
            minView: 3,
            language: $('html').attr('lang'),
        });
    });

</script>
