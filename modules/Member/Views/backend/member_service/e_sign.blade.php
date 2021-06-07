<form action="" method="post">
    @csrf
    <div class="row">
        <div class="form-group col-md-6">
            <label for="">{{ trans("Client") }}</label>
            <h5 class="text-success">
                <a href="{{ route('get.member.update',$member_service->member->id) }}" target="_blank">
                    {{ $member_service->member->name }} | {{ $member_service->member->phone }}
                    | {{ $member_service->member->email }}
                </a>
            </h5>
        </div>
        <div class="form-group col-md-6">
            <label for="">{{ trans("Service") }}</label>
            <h5 class="text-success">
                <a href="{{ route('get.service.update',$member_service->service_id) }}">{{ $member_service->service->name }}</a>
            </h5>
        </div>
    </div>
    <div class="form-group">
        <label for="signature">{{ trans("Signature") }}</label>
        <input type="text" name="signature" id="signature" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">{{ trans("Submit") }}</button>
</form>
