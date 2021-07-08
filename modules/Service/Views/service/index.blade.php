@extends("Base::layouts.master")

@section("content")
    <div id="service-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Service") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Service Listing") }}</h3></div>
            <div class="group-btn">
                <a href="{{ route("get.service.create") }}" class="btn btn-main-color"><i class="fa fa-plus"></i>
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
                                <label for="text-input">{{ trans("Service name") }}</label>
                                <input type="text" class="form-control" id="text-input" name="name"
                                       value="{{ $filter['name'] ?? NULL }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="type-id">{{ trans("Service Type") }}</label>
                                {!! Form::select('type_id',
                                    [NULL => trans("Select")] + $service_types, $filter['type_id'] ?? NULL,
                                    ['id' => 'type-id', 'class' => 'select2 form-control', 'style' => 'width: 100%']) !!}
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
                    {!! summaryListing($services) !!}
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>{{ trans("Service name") }}</th>
                            <th>{{ trans("Price") }}</th>
                            <th>{{ trans("Service Type") }}</th>
                            <th>{{ trans("Status") }}</th>
                            <th width="200px">{{ trans('Created At') }}</th>
                            <th width="200px">{{ trans('Updated At') }}</th>
                            <th width="200px" class="action">{{ trans("Action") }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($key = ($services->currentpage()-1)*$services->perpage()+1)
                        @foreach($services as $service)
                            <tr>
                                <td>{{ $key++ }}</td>
                                <td>{{ $service->name }}</td>
                                <td>{{ moneyFormat($service->price, 0) }}</td>
                                <td>{{ $service->type->name }}</td>
                                <td>{{ \Modules\Base\Model\Status::getStatus($service->status) ?? NULL }}</td>
                                <td>{{ \Carbon\Carbon::parse($service->created_at)->format('d/m/Y H:i:s')}}</td>
                                <td>{{ \Carbon\Carbon::parse($service->updated_at)->format('d/m/Y H:i:s')}}</td>
                                <td class="link-action">
                                    <a href="{{ route('get.service.update',$service->id) }}"
                                       class="btn btn-main-color mr-2">
                                        <i class="fas fa-pencil-alt"></i></a>
                                    <a href="{{ route('get.service.delete',$service->id) }}"
                                       class="btn btn-danger btn-delete"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-5 pagination-style">
                        {{ $services->render('vendor.pagination.default') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
