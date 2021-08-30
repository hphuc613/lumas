@extends("Base::layouts.master")

@section("content")
    <div id="store-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Store") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Store Listing") }}</h3></div>
            <div class="group-btn">
                <a href="{{ route("get.store.create") }}" class="btn btn-main-color"><i class="fa fa-plus"></i>
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
                                <label for="text-input">{{ trans("Store name") }}</label>
                                <input type="text" class="form-control" id="text-input" name="name" value="">
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
                    {!! summaryListing($stores) !!}
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>{{ trans("Name") }}</th>
                            <th>{{ trans("Address") }}</th>
                            <th>{{ trans("Open/Close Time") }}</th>
                            <th>{{ trans("Status") }}</th>
                            <th width="200px" class="action">{{ trans("Action") }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($key = ($stores->currentpage()-1)*$stores->perpage()+1)
                        @foreach($stores as $store)
                            <tr>
                                <td>{{ $key++ }}</td>
                                <td>{{ $store->name }}</td>
                                <td>{{ $store->address }}</td>
                                <td>{{ $store->open_close_time }}</td>
                                <td>{{ \Modules\Base\Model\Status::getStatus($store->status)}}</td>
                                <td class="link-action">
                                    <a href="{{ route('get.store.update', $store->id) }}"
                                       class="btn btn-main-color mr-2">
                                        <i class="fas fa-pencil-alt"></i></a>
                                    <a href="{{ route('get.user.delete',$store->id) }}"
                                       class="btn btn-danger btn-delete"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-5 pagination-style">
                        {{ $stores->withQueryString()->render('vendor.pagination.default') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
