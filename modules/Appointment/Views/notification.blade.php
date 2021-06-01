@extends("Base::layouts.master")

@section('content')
    <div class="container card">
        <div class="card-header">

        </div>
        <form action="" method="post" class="card-body">
            @csrf
            <div class="form-group">
                <label>Title</label>
                <input name="title" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label>Content</label>
                <input name="content" type="text" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
@push('js')
    <script>
        $('form').submit(function (e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('notification.postNotification') }}",
                method: "POST",
                data: $(this).serialize()
            }).done(function (data) {

            })
        });
    </script>
@endpush
