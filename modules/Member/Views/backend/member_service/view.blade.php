<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>{{ trans("Service Information") }}</h5>
        @if($member_service->status == \Modules\Member\Model\MemberService::PROGRESSING_STATUS)
            <a href="{{ route('get.member_service.e_sign',$member_service->id) }}"
               class="btn btn-info" data-toggle="modal" data-target="#form-modal"
               data-title="{{ trans('E-sign') }}">
                <i class="fas fa-file-signature"></i>
            </a>
        @endif
    </div>
    <div class="card-body">
        <div id="view" class="row">
            <div class="form-group col-md-6">
                <label for="member">{{ trans("Client") }}</label>
                <input type="hidden" name="member_id" value="{{ $member->id }}">
                <h5 class="text-success">
                    <a href="{{ route('get.member.update',$member->id) }}" target="_blank">
                        {{ $member->name }} | {{ $member->phone }} | {{ $member->email }}
                    </a>
                </h5>
            </div>
            <div class="form-group col-md-6">
                <label>{{ trans("Code") }}</label>
                <h5 class="text-info">
                    {{ $member_service->code }}
                </h5>
            </div>
            <div class="form-group col-md-6">
                <label for="remaining-quantity">{{ trans("Total Quantity") }}</label>
                <h5 class="text-danger">
                    {{ $member_service->quantity }}
                </h5>
            </div>
            <div class="form-group col-md-6">
                <label for="remaining-quantity">{{ trans("Remaining Quantity") }}</label>
                <h5 class="text-danger">
                    {{ $member_service->getRemaining() }}
                </h5>
            </div>
            <div class="form-group col-md-6">
                <label for="status">{{ trans("Price") }}</label>
                <h5 class="text-danger">
                    {{ moneyFormat($member_service->price) }}
                </h5>
            </div>
            <div class="form-group col-md-6">
                <label for="status">{{ trans("Total Price") }}</label>
                <h5 class="text-danger">
                    {{ moneyFormat($member_service->price * $member_service->quantity) }}
                </h5>
            </div>
            <div class="form-group col-md-6">
                <label for="status">{{ trans("Status") }}</label>
                <h5 class="text-danger">
                    {{ $statuses[$member_service->status] }}
                </h5>
            </div>
        </div>
    </div>
</div>
