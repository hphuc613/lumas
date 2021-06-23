<section id="chart-section">
    <div class="card-group">
        <div class="card" id="appointment-chart">
            <div class="card-body">
                <canvas id="order-earning-chart" height="400"></canvas>
            </div>
        </div>
        <div class="card" id="appointment-chart2">
            <div class="card-body">
                <canvas id="appointment-statistical-chart" height="400"></canvas>
            </div>
        </div>
    </div>
</section>
<input type="hidden" id="chart_appointment_data" value="{{ json_encode($chart_data['appointment']) }}">
<input type="hidden" id="chart_order_data" value="{{ json_encode($chart_data['order']) }}">
@push('js')
    <script>
        const labels = getMonthToCurrentInYear(true);
        /** Order Chart */
        var order_data = JSON.parse($('#chart_order_data').val());
        const data_order = {
            labels: labels,
            datasets: [{
                type: 'line',
                label: "{{ trans('Revenue/Month') }}",
                backgroundColor: 'rgb(255, 99, 132)',
                borderColor: 'rgb(255, 99, 132)',
                data: order_data.paid,
            }]
        };

        const order_earning = new Chart(document.getElementById('order-earning-chart'), {
            type: 'line',
            data: data_order,
            options: {
                interaction: {
                    intersect: false,
                },
                plugins: {
                    title: {
                        display: true,
                        text: "{{ trans('Order Earning Chart') }}"
                    },
                    tooltip: {
                        callbacks: {
                            label: function (context) {
                                if (context.parsed.y !== null) {
                                    var html = new Intl.NumberFormat('en-HK', {
                                        style: 'currency',
                                        currency: 'HKD'
                                    }).format(context.parsed.y);
                                }
                                return html;
                            }
                        }
                    },
                },
                maintainAspectRatio: false
            },
        });

        /** Appointment Chart */
        var appointment_data = JSON.parse($('#chart_appointment_data').val());
        const data_appointment = {
            labels: labels,
            datasets: [{
                label: "{{ trans('Total') }}",
                backgroundColor: 'rgb(0,55,255)',
                borderColor: 'rgb(0,55,255)',
                data: appointment_data.all,
            }, {
                label: "{{ trans('Completed') }}",
                backgroundColor: 'rgb(5,219,0)',
                borderColor: 'rgb(5,219,0)',
                data: appointment_data.completed,
            }, {
                label: "{{ trans('Abort') }}",
                backgroundColor: 'rgb(255,0,0)',
                borderColor: 'rgb(255,0,51)',
                data: appointment_data.abort
            }]
        };

        const appointment = new Chart(document.getElementById('appointment-statistical-chart'), {
            type: 'bar',
            data: data_appointment,
            options: {
                interaction: {
                    intersect: false,
                },
                plugins: {
                    title: {
                        display: true,
                        text: "{{ trans('Appointment Statistical Chart') }}"
                        }
                    },
                    maintainAspectRatio: false,
                },
            }
        );
    </script>
@endpush
