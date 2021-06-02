<div class="card">
    <div class="card-header d-flex justify-content-between">
        <h5>{{ !isset($member_service) ? trans("Add Service") : trans("Update Service") }}</h5>
        <div class="group-btn">
            @if(isset($member_service))
                @if($member_service->getRemaining() > 0 && $member_service->status === \Modules\Member\Model\MemberService::PROGRESSING_STATUS)
                    <a href="{{ route('get.member_service.e_sign',$member_service->id) }}"
                       class="btn btn-info" data-toggle="modal" data-target="#form-modal"
                       data-title="{{ trans('E-sign') }}">
                        <i class="fas fa-file-signature"></i>
                    </a>
                @endif
            @endif
            <a href="{{ route('get.voucher.create_popup') }}" class="btn btn-primary" data-toggle="modal"
               data-target="#form-modal" data-title="{{ trans('Create Voucher') }}">
                <i class="fa fa-plus"></i> &nbsp; {{ trans('Add Voucher') }}
            </a>
        </div>
    </div>
    @php($route_form = !isset($member_service) ? route('post.member_service.add', $member->id) : route('post.member_service.edit', $member_service->id))
    <div class="card-body">
        <form action="{{$route_form}}" method="post">
            @csrf
            <div class="row">
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
                    @if(isset($member_service))
                        <label>{{ trans("Code") }}</label>
                        <h5 class="text-info">
                            {{ $member_service->code }}
                        </h5>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="service-form">{{ trans("Service") }}</label>
                    {!! Form::select('service_id', $prompt + $services, $member_service->service_id ?? null, [
                    'id' => 'service-form',
                    'class' => 'select2 form-control service service-relate',
                    'style' => 'width: 100%']) !!}
                </div>
                <div class="form-group col-md-6">
                    <label for="voucher">{{ trans("Voucher") }}</label>
                    @if(!isset($member_service))
                        <select name="voucher_id" id="voucher" class="select2 form-control w-100">
                            <option value="">{{ trans("Please Select Service") }}</option>
                        </select>
                    @else
                        {!! Form::select('voucher_id', $prompt + $vouchers, $member_service->voucher_id ?? null, [
                        'id' => 'voucher',
                        'class' => 'select2 form-control service-relate',
                        'style' => 'width: 100%']) !!}
                    @endif
                </div>
                @if(isset($member_service))
                    <div class="form-group col-md-6">
                        <label for="remaining-quantity">{{ trans("Total Quantity") }}</label>
                        <h5 class="text-danger">
                            {{ $member_service->quantity }}
                        </h5>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="remaining-quantity">{{ trans("Remaning Quantity") }}</label>
                        <h5 class="text-danger">
                            {{ $member_service->getRemaining() }}
                        </h5>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="status">{{ trans("Status") }}</label>
                        {!! Form::select('status', $statuses, $member_service->status ?? null, [
                        'id' => 'status',
                        'class' => 'select2 form-control',
                        'style' => 'width: 100%']) !!}
                    </div>
                @endif
                <div class="form-group col-md-6">
                    @if(isset($member_service))
                        <label for="add-more-quantity">{{ trans("Add More Quantity") }}</label>
                        <input type="number" name="add_more_quantity" id="add-more-quantity" class="form-control"
                               @if(isset($member_service) && $member_service->getRemaining() == 0) readonly @endif>
                    @else
                        <label for="quantity">{{ trans("Quantity") }} </label>
                        <input type="number" name="quantity" id="quantity" class="form-control">
                    @endif

                </div>
                <div class="form-group col-md-12">
                    <label for="remarks">{{ trans("Remarks") }}</label>
                    <textarea class="form-control" name="remarks" id="description"
                              @if(isset($member_service) && $member_service->getRemaining() == 0) readonly @endif
                              rows="5">{{ $member_service->remarks ?? old('remarks') }}</textarea>
                </div>
            </div>
            @if(!isset($member_service) || $member_service->getRemaining() > 0)
                <div class="input-group">
                    <button type="submit" class="btn btn-primary" id="btn-add-service">
                        @if(isset($member_service)) {{ trans("Update") }} @else {{ trans("Add") }} @endif
                    </button>
                </div>
            @endif
        </form>
    </div>
</div>
