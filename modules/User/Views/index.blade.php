@extends('Base::layouts.master')
@php
    $prompt = ['' => 'All']
@endphp
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
                <a href="{{ route('get.salary.bulk_reload') }}" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i>
                    {{ 'Bulk Reload Salary' }}
                </a>
                <a href="{{ route('get.user.create') }}" class="btn btn-main-color"><i class="fa fa-plus"></i>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="text-input">{{ trans('Role') }}</label>
                                    {!! Form::select('role_id', $prompt + $roles, $filter['role_id'] ?? NULL, ['class' => 'select2 form-control w-100']) !!}
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="text-input">{{ trans('Status') }}</label>
                                    {!! Form::select('status', $prompt + $statuses, $filter['status'] ?? NULL, ['class' => 'select2 form-control w-100']) !!}
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
                        {!! summaryListing($users) !!}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th width="50px">#</th>
                                <th>{{ trans('Name') }}</th>
                                <th>{{ trans('Email') }}</th>
                                <th>{{ trans('Role') }}</th>
                                <th>{{ trans('Total Salary') }}</th>
                                @can('update-user-role')
                                    <th width="200px">{{ trans('Status') }}</th>
                                @endcan
                                <th width="200px">{{ trans('Created At') }}</th>
                                <th width="200px">{{ trans('Updated At') }}</th>
                                <th width="200px" class="action text-center">{{ trans('Action') }}</th>
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
                                    <td>
                                        <a class="nav-link" id="salary-tab"
                                           href="{{ route('get.profile.salary', $user->id) }}">
                                            {{ moneyFormat(optional($user->getSalaryCurrentMonth())->total_salary ?? 0) }}
                                        </a>
                                    </td>
                                    @can('update-user-role')
                                        <td>
                                            <input type="checkbox" class="checkbox-style checkbox-item user-status"
                                                   data-id="{{ $user->id }}"
                                                   id="user-{{ $user->id }}"
                                                   @if($user->status == \Modules\Base\Model\Status::STATUS_ACTIVE) checked
                                                   @endif value="1">
                                        </td>
                                    @endcan
                                    <td>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i:s')}}</td>
                                    <td>{{ \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i:s')}}</td>
                                    <td class="link-action">
                                        <a href="{{ route('get.user.appointment',$user->id) }}"
                                           class="btn btn-info"><i class="fas fa-calendar-check"></i></a>
                                        <a href="{{ route('get.user.update',$user->id) }}"
                                           class="btn btn-main-color mr-2">
                                            <i class="fas fa-pencil-alt"></i></a>
                                        @if(Auth::user()->id !== $user->id && ($user->getRoleAttribute()->id ?? null)!== \Modules\Role\Model\Role::getAdminRole()->id)
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
                url: "{{ route('post.user.update_status') }}",
                type: "post",
                data: data_import
            }).done(function (data) {
                location.reload();
            });
        })
    </script>
@endpush
