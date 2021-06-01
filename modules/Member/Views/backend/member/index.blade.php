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
            <div class="group-btn">
                <a href="{{route('get.member.import')}}" class="btn btn-info" data-toggle="modal"
                   data-title="{{ trans("Import Client") }}" data-target="#form-modal">
                    <i class="fa fa-plus"></i>
                    {{ trans("Import Client") }}
                </a>
                <a href="{{ route("get.member.create") }}" class="btn btn-primary"><i class="fa fa-plus"></i>
                    {{ trans("Add New") }}
                </a>
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
                        <div class="col-md-3 form-group">
                            <label for="name">{{ trans("Client name") }}</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ $filter['name'] ?? null }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="phone">{{ trans("Phone Number") }}</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                   value="{{ $filter['phone'] ?? null }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="email">{{ trans("Email") }}</label>
                            <input type="text" class="form-control" id="text-input" name="email"
                                   value="{{ $filter['email'] ?? null }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="type-id">Member Type</label>
                            {{ Form::select('type_id', [null => 'Select'] + $member_types, $filter['type_id'] ?? null,
                                             ['id' => 'type-id', 'class' => 'select2 form-control', 'style' => 'width: 100%']) }}
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="status">{{ trans('Status') }}</label>
                            <select name="status" id="status" class="select2 form-control">
                                @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}"
                                            @if(isset($filter['status']) && $filter['status'] == $key) selected @endif>{{ $status }}</option>
                                @endforeach
                            </select>
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
                    {!! summaryListing($members) !!}
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th width="50px">#</th>
                            <th>{{ trans('Name') }}</th>
                            <th>{{ trans('Email') }}</th>
                            <th>{{ trans('Phone Number') }}</th>
                            <th>{{ trans('Client Type') }}</th>
                            <th width="200px">{{ trans('Status') }}</th>
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
                                <td>{{ $member->phone }}</td>
                                <td>{{ $member->type->name }}</td>
                                <td>
                                    <input type="checkbox" class="checkbox-style checkbox-item member-status"
                                           data-id="{{ $member->id }}"
                                           id="member-{{ $member->id }}"
                                           @if($member->status == \Modules\Base\Model\Status::STATUS_ACTIVE) checked
                                           @endif value="1">
                                </td>
                                <td>{{ \Carbon\Carbon::parse($member->created_at)->format('d/m/Y H:i:s')}}</td>
                                <td>{{ \Carbon\Carbon::parse($member->updated_at)->format('d/m/Y H:i:s')}}</td>
                                <td class="link-action">
                                    <a href="{{ route('get.member_service.add',$member->id) }}"
                                       class="btn btn-info"><i class="fas fa-plus"></i></a>
                                    <a href="{{ route('get.member.update',$member->id) }}" class="btn btn-primary">
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
    {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax']) !!}
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
