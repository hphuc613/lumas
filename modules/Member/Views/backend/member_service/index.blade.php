@extends('Base::layouts.master')
@php
    use App\AppHelpers\Helper;
    $segment = Helper::segment(2);
    $prompt  = [null => trans('Select')]
@endphp
@section('content')
    <div id="role-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('get.member.list') }}">{{ trans('Client') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('Add Service') }}</li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Add Service For Client") }}</h3></div>
            <div class="group-btn">
                <a href="{{ route('get.member_service.add', $member->id) }}" class="btn btn-primary">
                    <i class="fa fa-plus"></i> &nbsp; {{ trans('Add new') }}
                </a>
            </div>
        </div>
    </div>

    <div id="member_service" class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    @include('Member::backend.member_service._form')
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ trans('Service Listing') }}</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('get.member_service.add', $member->id) }}" method="get" class="mb-3">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="search-service" name="code"
                                           placeholder="{{ trans("Search Service") }}">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-primary"
                                                type="submit">{{ trans("Search") }}</button>
                                    </div>
                                </div>
                            </form>
                            <div class="sumary">
                                <span class="listing-information">
                                    {{ trans('Showing') }}
                                    <b>
                                        {{($member_services->currentpage()-1)*$member_services->perpage()+1}}
                                        {{ trans('to') }}
                                        {{($member_services->currentpage()-1) * $member_services->perpage() + $member_services->count()}}
                                    </b>
                                    {{ trans('of') }}
                                    <b>{{$member_services->total()}}</b> {{ trans('entries') }}
                                </span>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th width="50px">#</th>
                                        <th>{{ trans('Code') }}</th>
                                        <th>{{ trans('Service') }}</th>
                                        <th>{{ trans('Voucher') }}</th>
                                        <th class="text-center">{{ trans('Remaining') }}
                                        <th class="text-center">{{ trans('Quantity') }}
                                        <th>{{ trans('Price') }}</th>
                                        <th>{{ trans('Total Price') }}</th>
                                        <th>{{ trans('Created At') }}</th>
                                        <th width="200px" class="action text-center">{{ trans('Action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($key = ($member_services->currentpage()-1)*$member_services->perpage()+1)
                                    @foreach($member_services as $value)
                                        <?php
                                        $total_price = !empty($value->voucher)
                                            ? $value->voucher->price * $value->quantity
                                            : $value->service->price * $value->quantity;
                                        ?>
                                        <tr>
                                            <td>{{$key++}}</td>
                                            <td>
                                                <a href="javascript:" class="tooltip-content"
                                                   data-tooltip="{{ generateQRCode($value->code)}}" title="">
                                                    {{$value->code}}
                                                </a>
                                            </td>
                                            <td>
                                                <a target="_blank"
                                                   href="{{ route('get.service.update', $value->service_id) }}">
                                                    {{ $value->service->name }}
                                                </a>
                                            </td>
                                            <td>
                                                @if(!empty($value->voucher))
                                                    <a target="_blank"
                                                       href="{{ route('get.voucher.update', $value->voucher_id) }}">
                                                        {{ $value->voucher->code }}
                                                    </a>
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $value->quantity - $value->deduct_quantity }}</td>
                                            <td>{{ $value->quantity }}</td>
                                            <td>{{ $value->voucher->price ?? $value->service->price }}</td>
                                            <td>{{ $total_price }}</td>
                                            <td>{{ \Carbon\Carbon::parse($value->created_at)->format('d/m/Y H:i:s')}}</td>
                                            <td class="link-action">
                                                <a href="{{ route('get.member_service.edit',$value->id) }}"
                                                   class="btn btn-primary">
                                                    <i class="fas fa-pencil-alt"></i></a>
                                                <a href="{{ route('get.member_service.delete',$value->id) }}"
                                                   class="btn btn-danger btn-delete"><i
                                                        class="fas fa-trash-alt"></i></a>
                                                <a href="{{ route('get.member_service.e_sign',$value->id) }}"
                                                   class="btn btn-info" data-toggle="modal" data-target="#form-modal"
                                                   data-title="{{ trans('E-sign') }}">
                                                    <i class="fas fa-file-signature"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="mt-5 pagination-style">
                                    {{ $member_services->render('vendor.pagination.default') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-5">
                    @include('Member::backend.member_service.history')
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    {!! JsValidator::formRequest('Modules\Member\Http\Requests\MemberServiceRequest') !!}
    <script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
    <script>
        @if(isset($member_service))
        $(".select2").prop("disabled", true);
        $("#btn-add-service").click(function () {
            $(".select2").prop("disabled", false);
        })
        @endif
        /** Get service list by service type */
        $(document).on('change', '#service', function () {
            var service = $(this);
            var service_id = service.val();
            $.ajax({
                url: "{{ route('get.voucher.get_list_by_service', '') }}/" + service_id,
                method: "get"
            }).done(function (response) {
                service.parents('form').find('#voucher').html(response);
            });
        });
        $(".tooltip-content").tooltip({
            content: function () {
                return $(this).attr('data-tooltip');
            },
            position: {
                my: "center bottom", // the "anchor point" in the tooltip element
                at: "center top-10", // the position of that anchor point relative to selected element
            }
        });
    </script>
@endpush
