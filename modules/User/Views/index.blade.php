@extends('Base::layouts.master')

@section('content')
    <div id="user-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans('User Listing') }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans('User Listing') }}</h3></div>
            <div class="group-btn">
                <a href="{{ route('get.user.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i>
                    &nbsp; {{ trans('Add New') }}</a>
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
                                    <label for="text-input">{{ trans('User name') }}</label>
                                    <input type="text" class="form-control" id="text-input" name="name"
                                           value="{{$filter['name'] ?? null}}">
                                </div>
                            </div>
                        </div>
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary mr-2">{{ trans('Search') }}</button>
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
                            {{ trans('Showing') }} <b>{{($users->currentpage()-1)*$users->perpage()+1}}
                            {{ trans('to') }} {{($users->currentpage()-1) * $users->perpage() + $users->count()}}</b>
                            {{ trans('of') }}  <b>{{$users->total()}}</b> {{ trans('entries') }}
                        </span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th width="50px">#</th>
                                <th>{{ trans('Name') }}</th>
                                <th>{{ trans('Email') }}</th>
                                <th>{{ trans('Role') }}</th>
                                @can('update-user-role')
                                    <th width="200px">{{ trans('Status') }}</th>
                                @endcan
                                <th width="200px">{{ trans('Created At') }}</th>
                                <th width="200px">{{ trans('Updated At') }}</th>
                                <th width="200px" class="action">{{ trans('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($key = ($users->currentpage()-1)*$users->perpage()+1)
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $key++ }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->getRoleAttribute()->name ?? 'N/A' }}</td>
                                    @can('update-user-role')
                                        <td>
                                            <input type="checkbox" class="checkbox-style checkbox-item user-status"
                                                   data-id="{{ $user->id }}"
                                                   @if($user->status == \Modules\Base\Model\Status::STATUS_ACTIVE) checked
                                                   @endif value="1">
                                        </td>
                                    @endcan
                                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i:s')}}</td>
                                    <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i:s')}}</td>
                                    <td class="link-action">
                                        <a href="{{ route('get.user.update',$user->id) }}" class="btn btn-primary mr-2">
                                            <i class="fas fa-pencil-alt"></i></a>
                                        @if(Auth::user()->id !== $user->id && ($user->getRoleAttribute()->id ?? NULL)!== \Modules\Role\Model\Role::getAdminRole()->id)
                                            <a href="{{ route('get.user.delete',$user->id) }}"
                                               class="btn btn-danger btn-delete"><i class="fas fa-trash-alt"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-5 pagination-style">
                            {{ $users->render('vendor.pagination.default') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script !src="">
        $(document).on('click', '.user-status', function () {
            var status = $(this).val();
            if (!$(this).is(":checked")) {
                status = -1;
            }
            var data_import = {
                'id': $(this).attr('data-id'),
                'status': status
            };
            $.ajax({
                url: "/admin/user/update-status",
                type: "post",
                data: data_import
            }).done(function (data) {
                location.reload();
            });
        })
    </script>
@endpush
