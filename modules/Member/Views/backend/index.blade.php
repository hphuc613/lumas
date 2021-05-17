@extends("Base::layouts.master")

@section("content")
    <div id="member-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Client") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Client Listing") }}</h3></div>
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
                                <label for="text-input">{{ trans("Client name") }}</label>
                                <input type="text" class="form-control" id="text-input" name="name"
                                       value="{{ $filter['name'] ?? null }}">
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn btn-primary mr-2">{{ trans("Search") }}</button>
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
                    <span class="listing-information">
                        <!-- Quantity item -->
                        </span>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>{{ trans('Name') }}</th>
                            <th>{{ trans('Email') }}</th>
                            @can('update-user-role')
                                <th width="200px">{{ trans('Status') }}</th>
                            @endcan
                            <th width="200px">{{ trans('Created At') }}</th>
                            <th width="200px">{{ trans('Updated At') }}</th>
                            <th width="200px" class="action">{{ trans('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php($key = ($members->currentpage()-1)*$members->perpage()+1)
                        @foreach($members as $member)
                            <tr>
                                <td>{{ $key++ }}</td>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                @can('update-user-role')
                                    <td>
                                        <input type="checkbox" class="checkbox-style checkbox-item member-status"
                                               data-id="{{ $member->id }}"
                                               @if($member->status == \Modules\Base\Model\Status::STATUS_ACTIVE) checked
                                               @endif value="1">
                                    </td>
                                @endcan
                                <td>{{ \Carbon\Carbon::parse($member->created_at)->format('d/m/Y H:i:s')}}</td>
                                <td>{{ \Carbon\Carbon::parse($member->updated_at)->format('d/m/Y H:i:s')}}</td>
                                <td class="link-action">
                                    <a href="{{ route('get.member.update',$member->id) }}" class="btn btn-primary mr-2">
                                        <i class="fas fa-pencil-alt"></i></a>
                                    <a href="{{ route('get.member.delete',$member->id) }}"
                                       class="btn btn-danger btn-delete"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-5 pagination-style">
                        {{ $members->render('vendor.pagination.default') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script !src="">
        $(document).on('click', '.member-status', function () {
            var status = $(this).val();
            if (!$(this).is(":checked")) {
                status = -1;
            }
            var data_import = {
                'id': $(this).attr('data-id'),
                'status': status
            };
            $.ajax({
                url: "{{ route('post.member.update_status') }}",
                type: "post",
                data: data_import
            }).done(function (data) {
                location.reload();
            });
        })
    </script>
@endpush
