<form action="" method="post">
    @csrf
    <div class="row">
        <div class="form-group col-md-6">
            <label for="">{{ trans("Client") }}</label>
            <h5 class="text-success">
                <a href="{{ route('get.member.update',$member_course->member->id) }}" target="_blank">
                    {{ $member_course->member->name }} | {{ $member_course->member->phone }}
                    | {{ $member_course->member->email }}
                </a>
            </h5>
        </div>
        <div class="form-group col-md-6">
            <label for="">{{ trans("Course") }}</label>
            <h5 class="text-success">
                <a href="{{ route('get.course.update',$member_course->course_id) }}">{{ $member_course->course->name }}</a>
            </h5>
        </div>
    </div>
    <div class="form-group">
        <label for="signature">{{ trans("Signature") }}</label>
        <input type="text" name="signature" id="signature" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">{{ trans("Submit") }}</button>
</form>
