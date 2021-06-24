<form action="" method="post">
    @csrf
    <div class="form-group">
        <label for="basic-salary">{{ trans('Basic Salary') }}</label>
        <input type="number" name="basic_salary" class="form-control" value="{{ $user->basic_salary }}">
    </div>
    <div class="input-group">
        <button type="submit" class="btn btn-main-color">{{ trans('Update') }}</button>
    </div>
</form>