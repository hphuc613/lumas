@extends('Base::layouts.master')

@section('content')
    <div id="member-type-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans('Client Type Listing') }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans('Client Type Listing') }}</h3></div>
            <div class="group-btn">
                <a href="{{ route('get.member_type.create') }}" class="btn btn-main-color" data-toggle="modal"
                   data-target="#form-modal" data-title="{{ trans('Create Client Type') }}">
                    <i class="fa fa-plus"></i> &nbsp; {{ trans('Add new') }}
                </a>
            </div>
        </div>
        <!--Search box-->
        <div class="search-box">
            <div class="card">
                <div class="card-header" data-toggle="collapse" data-target="#form-search-box" aria-expanded="false"
                     aria-controls="form-search-box">
                    <div class="title">{{ trans('Search') }}</div>
                </div>
                <div class="card-body collapse show" id="form-search-box">
                    <form action="" method="get">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="text-input">{{ trans('Client Type name') }}</label>
                                    <input type="text" class="form-control" id="text-input" name="name"
                                           value="{{$filter['name'] ?? null}}">
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <button type="submit" class="btn btn-main-color mr-2">{{ trans('Search') }}</button>
                            <button type="button" class="btn btn-default clear">{{ trans('Cancel') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="listing">
            <div class="card">
                <div class="card-body">
                    <div class="sumary">
                        <span class="listing-information">
                            {{ trans('Showing') }} <b>{{($member_types->currentpage()-1)*$member_types->perpage()+1}} {{ trans('to') }} {{($member_types->currentpage()-1) * $member_types->perpage() + $member_types->count()}}</b> {{ trans('of') }}  <b>{{$member_types->total()}}</b> {{ trans('entries') }}
                        </span>
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
                            @php($key = ($member_types->currentpage()-1)*$member_types->perpage()+1)
                            @foreach($member_types as $member_type)
                                <tr>
                                    <td>{{$key++}}</td>
                                    <td>{{ trans($member_type->name) }}</td>
                                    <td>{{ \Modules\Base\Model\Status::getStatus($member_type->status) ?? null }}</td>
                                    <td>{{ \Carbon\Carbon::parse($member_type->created_at)->format('d/m/Y H:i:s')}}</td>
                                    <td>{{ \Carbon\Carbon::parse($member_type->updated_at)->format('d/m/Y H:i:s')}}</td>
                                    <td class="link-action">
                                        <a href="{{ route('get.member_type.update',$member_type->id) }}"
                                           class="btn btn-main-color mr-2"
                                           data-toggle="modal" data-title="{{ trans('Update Client Type') }}"
                                           data-target="#form-modal">
                                            <i class="fas fa-pencil-alt"></i></a>
                                        <a href="{{ route('get.member_type.delete',$member_type->id) }}"
                                           class="btn btn-danger btn-delete"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-5 pagination-style">
                            {{ $member_types->withQueryString()->render('vendor.pagination.default') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax'])  !!}
@endsection

