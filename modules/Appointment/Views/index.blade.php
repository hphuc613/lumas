@extends("Base::layouts.master")

@section("content")
    <div id="appointment-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Appointment") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Appointment Listing") }}</h3></div>
            <div class="group-btn">
                <a href="#" class="btn btn-primary"><i class="fa fa-plus"></i> &nbsp; {{ trans("Add New") }}</a>
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
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="text-input">{{ trans("Appointment name") }}</label>
                                <input type="text" class="form-control" id="text-input" name="name" value="">
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
                            <th>{{ trans("Search") }}</th>
                            <th width="200px" class="action">{{ trans("Search") }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- listing -->
                        </tbody>
                    </table>
                    <div class="mt-5 pagination-style">
                        <!-- Pagination -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
