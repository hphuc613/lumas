@extends("Base::layouts.master")
<?php
use Carbon\Carbon;
$month = (isset($filter['month'])) ? strtotime(Carbon::createFromFormat('m-Y', $filter['month'])) : time();
?>
@section("content")
    <div id="service-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Report") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Service Expendable Report") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Service Expendable Report") }}</h3></div>
            <div class="group-btn">
                <a href="{{ route('get.report.service', array_merge(request()->query(), ['export' => true])) }}"
                   class="btn btn-info">{{ trans('Export') }}</a>
            </div>
        </div>
    </div>
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
                                <label for="text-input">{{ trans("Invoice code") }}</label>
                                <input type="number" class="form-control" id="text-input" name="code"
                                       value="{{ $filter['code'] ?? NULL }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Client") }}</label>
                                {!! Form::select('member_id', ["" => trans("All")] + $members, $filter['member_id'] ?? NULL, ['class' => 'form-control select2 w-100']) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Month") }}</label>
                                <input type="text" name="month" class="form-control month"
                                       value=" {{ formatDate($month, 'm-Y') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Creator") }}</label>
                                {!! Form::select('creator', ["" => trans("All")] + $creators, $filter['creator'] ?? NULL, ['class' => 'form-control select2 w-100']) !!}
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
                <div class="sumary d-flex justify-content-between">
                    <span class="listing-information">
                        {!! summaryListing($data) !!}
                    </span>
                    <span class="total-price">
                         <h4>
                             {{ trans('Month') }}:
                             <span class="text-danger font-size-clearfix">
                                 {{ formatDate($month, 'm-Y') }}
                             </span>
                         </h4>
                         <h4>
                             {{ trans('Total:') }}
                             <span class="text-danger font-size-clearfix">
                                 {{ moneyFormat($total_amount) }}
                             </span>
                         </h4>
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>{{ trans("Date") }}</th>
                            <th>{{ trans("Staff") }}</th>
                            <th>{{ trans("Order Code") }}</th>
                            <th>{{ trans("Location") }}</th>
                            <th>{{ trans("Client ID") }}</th>
                            <th>{{ trans("Client Name") }}</th>
                            <th>{{ trans("Service Name") }}</th>
                            <th>{{ trans("Times") }}</th>
                            <th>{{ trans("Amount") }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $key = ($data->currentpage() - 1) * $data->perpage() + 1 ?>
                        @foreach($data as $val)
                            <tr>
                                <td>{{ $key++ }}</td>
                                <td>{{ $val->date }}</td>
                                <td>{{ $val->created_by }}</td>
                                <td>
                                    <h5>{{ (is_numeric($val->order_code)) ? 'CWB'.$val->order_code : $val->order_code }}</h5>
                                </td>
                                <td>{{ $val->location }}</td>
                                <td>
                                    <h5>{{ (is_numeric($val->id_number)) ? 'CWB'.$val->id_number : $val->id_number }}</h5>
                                </td>
                                <td>{{ $val->member_name }}</td>
                                <td>{{ $val->service_name }}</td>
                                <td>{{ $val->times." ".trans('Times') }}</td>
                                <td>{{ moneyFormat($val->amount) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-5 pagination-style">
                        {{ $data->withQueryString()->render('vendor.pagination.default') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax', 'size' => 'modal-lg'])  !!}
@endsection
