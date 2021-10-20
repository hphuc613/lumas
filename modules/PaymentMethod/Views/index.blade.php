@extends("Base::layouts.master")

@section("content")
    <div id="payment-method-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Payment Method") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Payment Method Listing") }}</h3></div>
            <div class="group-btn">
                <a href="{{ route('get.payment_method.create') }}" class="btn btn-main-color" data-toggle="modal"
                   data-target="#form-modal" data-title="{{ trans('Create Payment Method') }}">
                    <i class="fa fa-plus"></i> &nbsp; {{ trans('Add new') }}
                </a>
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
                                    <label for="text-input">{{ trans("Payment Method name") }}</label>
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
                        {!! summaryListing($payment_methods) !!}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th width="50px">#</th>
                                <th>{{ trans('Name') }}</th>
                                <th>{{ trans('Status') }}</th>
                                <th width="200px">{{ trans('Created At') }}</th>
                                <th width="200px">{{ trans('Updated At') }}</th>
                                <th width="200px" class="action">{{ trans('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($key = ($payment_methods->currentpage()-1)*$payment_methods->perpage()+1)
                            @foreach($payment_methods as $payment_method)
                                <tr>
                                    <td>{{$key++}}</td>
                                    <td>{{ trans($payment_method->name) }}</td>
                                    <td>{{ $statuses[$payment_method->status] ?? null }}</td>
                                    <td>{{ \Carbon\Carbon::parse($payment_method->created_at)->format('d/m/Y H:i:s')}}</td>
                                    <td>{{ \Carbon\Carbon::parse($payment_method->updated_at)->format('d/m/Y H:i:s')}}</td>
                                    <td class="link-action">
                                        <a href="{{ route('get.payment_method.update',$payment_method->id) }}"
                                           data-toggle="modal"
                                           data-target="#form-modal" data-title="{{ trans('Create Room') }}"
                                           class="btn btn-main-color mr-2">
                                            <i class="fas fa-pencil-alt"></i></a>
                                        <a href="{{ route('get.payment_method.delete',$payment_method->id) }}"
                                           class="btn btn-danger btn-delete"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-5 pagination-style">
                            {{ $payment_methods->withQueryString()->render('vendor.pagination.default') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax'])  !!}
@endsection
