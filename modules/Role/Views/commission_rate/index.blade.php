<div class="listing">
    <div class="sumary">
        {!! summaryListing($rates) !!}
    </div>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th width="50px">#</th>
                <th>{{ trans('Target') }}</th>
                <th>{{ trans('Rate') }}</th>
                <th width="200px" class="action">{{ trans('Action') }}</th>
            </tr>
            </thead>
            <tbody>
            @php($key = ($rates->currentpage()-1)*$rates->perpage()+1)
            @foreach($rates as $rate)
                <tr>
                    <td>{{$key++}}</td>
                    <td>{{ moneyFormat($rate->target) }}</td>
                    <td>{{ $rate->rate }}%</td>
                    <td class="link-action">
                        <a href="{{ route('get.commission_rate.update', $rate->id) }}"
                           class="btn btn-main-color" data-toggle="modal"
                           data-target="#form-modal" data-title="{{ trans('Update Commission Rate') }}">
                            <i class="fas fa-pencil-alt"></i></a>
                        <a href="{{ route('get.commission_rate.delete', $rate->id) }}"
                           class="btn btn-danger btn-delete"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-5 pagination-style">
            {{ $rates->render('vendor.pagination.default') }}
        </div>
    </div>

</div>
