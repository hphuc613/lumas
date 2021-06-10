<section id="section-all-information">
    <div class="card-group" id="service-information">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round align-self-center bg-info mr-3">
                        <i class="fas fa-cocktail"></i>
                    </div>
                    <div class="align-self-center">
                        <h2 class="mb-0 font-weight-normal">{{ $count_data->service }}</h2>
                        <h5 class="text-muted font-weight-normal mb-0">{{ trans('Service') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" id="course-information">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round align-self-center bg-warning mr-3">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="align-self-center">
                        <h2 class="mb-0 font-weight-normal">{{ $count_data->course }}</h2>
                        <h5 class="text-muted font-weight-normal mb-0">{{ trans('Course') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" id="client-information">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round align-self-center bg-success mr-3">
                        <i class="fas fa-user-friends"></i>
                    </div>
                    <div class="align-self-center">
                        <h2 class="mb-0 font-weight-normal">{{ $count_data->client }}</h2>
                        <h5 class="text-muted font-weight-normal mb-0">{{ trans('Client') }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" id="order-information">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="round align-self-center bg-danger mr-3">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="align-self-center">
                        <h2 class="mb-0 font-weight-normal">0</h2>
                        <h5 class="text-muted font-weight-normal mb-0">{{ trans('Order') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
