@extends('Base::layouts.master')
<?php
$prompt = ['' => 'All'];
?>
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
            <div class="page-title"><h3>{{ trans('Salary Listing') }}</h3></div>
            <div class="group-btn">
                <a href="{{ route('get.salary.bulk_reload') }}" class="btn btn-primary">
                    <i class="fas fa-sync-alt"></i>
                    {{ trans('Bulk Calculate Salary') }}
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
                                    <label for="month">{{ trans('Month') }}</label>
                                    <input type="text" class="form-control month" id="month" name="month"
                                           value="{{$filter['month'] ?? null}}">
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
                        {!! summaryListing($salaries) !!}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th width="50px">#</th>
                                <th>{{ trans('Month') }}</th>
                                <th>{{ trans('Name') }}</th>
                                <th>{{ trans('Email') }}</th>
                                <th>{{ trans('Role') }}</th>
                                <th>{{ trans('Basic Salary') }}</th>
                                <th>{{ trans('Total Commission') }}</th>
                                <th>{{ trans('Total Salary') }}</th>
                                <th class="action text-center">{{ trans('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php($key = ($salaries->currentpage()-1)*$salaries->perpage()+1)
                            @foreach($salaries as $salary)
                                <tr>
                                    <td>{{ $key++ }}</td>
                                    <td>{{ $salary->month }}</td>
                                    <td>{{ $salary->user->name }}</td>
                                    <td>{{ $salary->user->email }}</td>
                                    <td>{{ $salary->user->getRoleAttribute()->name ?? 'N/A' }}</td>
                                    <td>{{ moneyFormat($salary->basic_salary) }}</td>
                                    <td>{{ moneyFormat($salary->total_commission) }}</td>
                                    <td>
                                        <h6>{{ moneyFormat($salary->total_salary) }}</h6>
                                    </td>
                                    @php($month = formatDate(strtotime(Carbon\Carbon::createFromFormat('m/Y', $salary->month)), 'm-Y'))
                                    <td class="link-action text-center">
                                        <a href="{{ route('get.user.salary', [$salary->user->id, 'month' => $month]) }}"
                                           class="btn btn-info">
                                            <i class="fas fa-file-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="mt-5 pagination-style">
                            {{ $salaries->withQueryString()->render('vendor.pagination.default') }}
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
