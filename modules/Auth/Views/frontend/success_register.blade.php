@extends('Auth::frontend.master')

@section('content')
    <div id="success-regist"></div>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $("#success-regist").css('height', $(window).height());
            Swal.fire({
                icon: 'success',
                title: "{{ trans('Registered Successfully!')}}",
                text: "{{ trans('Now, you can login on app.') }}",
                showConfirmButton: false,
                allowOutsideClick: false,
            })
        });
    </script>
@endpush
