@extends("Base::layouts.master")

@section("content")
    <div id="voucher-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Service Voucher") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Service Voucher Listing") }}</h3></div>
            <div class="group-btn">
                <a href="{{ route("get.service_voucher.create") }}" class="btn btn-main-color"><i
                            class="fa fa-plus"></i>
                    &nbsp; {{ trans("Add New") }}</a>
            </div>
        </div>
    </div>
    <!--Search box-->
    <div class="search-box">
        <div class="card">
            <div class="card-header" data-toggle="collapse" data-target="#form-search-box" aria-expanded="false"
                 aria-controls="form-search-box">
                <div class="title">{{ trans("Search") }}</div>
            </div>
            <div class="card-body collapse show" id="form-search-box">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="code">{{ trans("Code") }}</label>
                                <input type="text" class="form-control" id="code" name="code"
                                       value="{{ $filter['code'] ?? NULL }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="service_id">{{ trans("Service") }}</label>
                                {!! Form::select('service_id', ["" => trans("Select")] + $services, $filter['service_id'] ?? NULL, [
                                    'id' => 'service_id',
                                    'class' => 'select2 form-control',
                                    'style' => 'width: 100%']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn btn-main-color mr-2">{{ trans("Search") }}</button>
                        <button type="button" class="btn btn-default clear">{{ trans("Cancel") }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="listing">
        <div class="card">
            <div class="card-body">
                <div class="sumary">
                    {!! summaryListing($vouchers) !!}
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>{{ trans('Code') }}</th>
                            <th>{{ trans('Price') }}</th>
                            <th>{{ trans('Start day') }}</th>
                            <th>{{ trans('Service') }}</th>
                            <th width="200px">{{ trans('Created At') }}</th>
                            <th width="200px">{{ trans('Updated At') }}</th>
                            <th width="200px" class="action">{{ trans('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($key = ($vouchers->currentpage()-1)*$vouchers->perpage()+1)
                        @foreach($vouchers as $voucher)
                            <tr>
                                <td>{{ $key++ }}</td>
                                <td>{{ $voucher->code }}</td>
                                <td>{{ moneyFormat($voucher->price, 0) }}</td>
                                <td>{{ formatDate(strtotime($voucher->start_at)) }}</td>
                                <td>{{ $voucher->service->name }}</td>
                                <td>{{ \Carbon\Carbon::parse($voucher->created_at)->format('d/m/Y H:i:s')}}</td>
                                <td>{{ \Carbon\Carbon::parse($voucher->updated_at)->format('d/m/Y H:i:s')}}</td>
                                <td class="link-action">
                                    <a href="{{ route('get.service_voucher.update',$voucher->id) }}"
                                       class="btn btn-main-color mr-2">
                                        <i class="fas fa-pencil-alt"></i></a>
                                    <a href="{{ route('get.service_voucher.delete',$voucher->id) }}"
                                       class="btn btn-danger btn-delete"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-5 pagination-style">
                        {{ $vouchers->withQueryString()->render('vendor.pagination.default') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
