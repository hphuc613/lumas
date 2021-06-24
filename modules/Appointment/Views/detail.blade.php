<?php

use App\AppHelpers\Helper;

$prompt                  = [null => trans('Select')];
$route_previous          = Helper::getRoutePrevious();
$route_is_member_product = in_array($route_previous, ["get.member_service.add",
                                                      "get.member_service.view",
                                                      "get.member_course.add",
                                                      "get.member_course.view"]);
$member_display_id       = (int)request()->get('member_id');
$type                    = request()->get('type');
$segment                 = Helper::segment(2);
?>
{{-- Appointment Form --}}
<div class="appointment-form">
    @include('Appointment::form')
</div>
{{-- View Appointment --}}
@if(isset($appointment))
    <div id="appointment-info">
        @include('Appointment::view')
    </div>
@endif
{!! JsValidator::formRequest('Modules\Appointment\Http\Requests\AppointmentRequest', '#appointment-form') !!}
<script>
    $(document).ready(function () {
        $('#appointment-form #type').prop('disabled', true);
        /** Show View/Edit form appointment */
        @if(isset($appointment))
        $('#appointment-form #member').prop('disabled', true);
        editAble(true) //View
        $(document).on("click", "#edit-btn", function () {
            editAble(false) //Edit
        });
        @else

        /** Add booking time is current if new record*/
        $('input#booking-time').val($('input#get-date').val());
        @endif

        /** Member display */
        @if (!empty($member_display_id))
        $('#appointment-form #member').prop('disabled', true);
        @endif

        /** Show Service/Course drop down */
        if ($("#appointment-form #type").val() === "{{ \Modules\Appointment\Model\Appointment::SERVICE_TYPE }}") {
            $('.select-course').hide();
            $('.select-service').show();
        } else {
            $('.select-course').show();
            $('.select-service').hide();
        }

        /** Datetimepicker */
        $('input.datetime').datetimepicker({
            format: 'dd-mm-yyyy hh:ii',
            language: $('html').attr('lang'),
            todayHighlight: true,
            todayBtn: true,
            autoclose: true,
            fontAwesome: true,
            container: '.datetime-modal'
        });
    });

    /** Edit able */
    function editAble(status) {
        if (status === true) {
            $("#appointment-form").hide();
            $("#appointment-info").show();
        } else {
            $("#appointment-form").show();
            $("#appointment-info").hide();
        }
    }
</script>
