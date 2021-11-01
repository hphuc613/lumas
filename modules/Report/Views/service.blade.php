@extends("Base::layouts.master")

@section("content")
    <div id="service-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Report") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Service Information") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Service Information") }}</h3></div>
            <div class="group-btn">
                <a href="{{ route('get.report.service', array_merge(request()->query(), ['export' => true])) }}"
                   class="btn btn-info">{{ trans('Export') }}</a>
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
                                <label for="text-input">{{ trans("Service name") }}</label>
                                {!! Form::select('service_id', ['' => trans('All')] + $services, $filter['service_id'] ?? null,
                            ['id' => 'service-id', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
                            </div>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="user-id">{{ trans('Staff') }}</label>
                            {!! Form::select('user_id', ['' => trans('All')] + $users, $filter['user_id'] ?? null,
                            ['id' => 'user-id', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="from">{{ trans('Date') }}</label>
                            <input type="text" class="form-control date" id="from" name="from"
                                   value="{{ $filter['from'] ?? NULL}}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="to">{{ trans('To') }}</label>
                            <input type="text" class="form-control date" id="to" name="to"
                                   value="{{ $filter['to'] ?? NULL }}">
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
                <div class="row">
                    <div class="col-md-4">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>{{ trans('Staff') }}</th>
                                    <th>{{ trans('Times') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($staffs as $key => $staff)
                                    <tr>
                                        <td>{{ $key }}</td>
                                        <td>{{ count($staff) . ' '.trans('Times') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="sumary">
                            {!! summaryListing($data) !!}
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th width="50px">#</th>
                                    <th>
                                        {{ trans('Date') }}
                                        @if(isset($filter['sort']) && $filter['sort'] == 'asc')
                                            <a href="{{ route('get.report.service', array_merge(request()->query(), ['sort' => 'desc'])) }}"><i
                                                    class="fas fa-sort-amount-up"></i></a>
                                        @else
                                            <a href="{{ route('get.report.service', array_merge(request()->query(), ['sort' => 'asc'])) }}"><i
                                                    class="fas fa-sort-amount-down"></i></a>
                                        @endif
                                    </th>

                                    <th>{{ trans('Code') }}</th>
                                    <th>{{ trans('Service') }}</th>
                                    <th>{{ trans('Staff') }}</th>
                                    <th>{{ trans('Times') }}</th>
                                    <th>{{ trans('Amount') }}</th>
                                    <th>{{ trans('Signature') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php($key = ($data->currentpage()-1)*$data->perpage()+1)
                                @foreach($data as $val)
                                    @php($val = (object)$val)
                                    <tr>
                                        <td>{{$key++}}</td>
                                        <td>{{ formatDate(strtotime($val->date), 'd/m/Y')}}</td>
                                        <td>{{ $val->code }}</td>
                                        <td>{{ $val->service_name }}</td>
                                        <td>{{ $val->user_name }}</td>
                                        <td>{{ $val->quantity . ' '.trans('Times') }}</td>
                                        <td>{{ moneyFormat($val->amount) }}</td>
                                        <td>
                                            @if(file_exists(public_path('storage/'.$val->signature)))
                                                <a href="javascript:" class="tooltip-content"
                                                   data-tooltip="<img src='{{ asset('storage/'.$val->signature) }}'
                                   alt='{{ asset('storage/'.$val->signature) }}' width='280'>"
                                                   title="">
                                                    {{ trans('Signature Image') }}
                                                </a>
                                            @else
                                                {{ $val->signature }}
                                            @endif
                                        </td>
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
        </div>
    </div>
@endsection
@push('js')
    <script src="https://code.jquery.com/jquery-migrate-3.0.0.min.js"></script>
    <script>
        $(".tooltip-content").tooltip({
            content: function () {
                return $(this).attr('data-tooltip');
            },
            position: {
                my: "center bottom",
                at: "center top-10",
            },
        });
    </script>
@endpush
