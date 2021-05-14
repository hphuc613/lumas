@extends('Base::frontend.master')

@section('content')
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h4 class="text-themecolor">{{ trans('Profile') }}</h4>
        </div>
        <div class="col-md-7 align-self-center text-right">
            <div class="d-flex justify-content-end align-items-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans('Home') }}</a></li>
                    <li class="breadcrumb-item active">{{ trans('Profile') }}</li>
                </ol>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Column -->
        <div class="col-lg-4 col-xlg-3 col-md-5">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        <div class="el-element-overlay">
                            <div class="el-card-item p-0">
                                <div class="el-card-avatar el-overlay-1">
                                    <img src="{{ $member->getAvatar() }}"
                                         class="img-circle profile-image"/>
                                    <div class="el-overlay">
                                        <ul class="el-info">
                                            <li>
                                                <a class="btn default btn-outline image-popup-vertical-fit"
                                                   href="{{ $member->getAvatar() }}">
                                                    <i class="icon-magnifier"></i>
                                                </a>
                                                <a class="btn default btn-outline"
                                                   href="{{ route("frontend.get.member.change_avatar") }}"
                                                   data-toggle="modal" data-target="#form-modal"
                                                   data-title="{{ trans('Update Avatar')}}">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    <h4 class="card-title m-t-10">{{ $member->name }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <hr>
                </div>
                <div class="card-body">
                    <small class="text-muted">{{ trans('Email address') }}</small>
                    <h6>{{ $member->email }}</h6>
                    <small class="text-muted p-t-30 db">{{ trans('Phone') }}</small>
                    <h6>{{ $member->contact_info->phone ?? null }}</h6>
                    <small class="text-muted p-t-30 db">{{ trans('Address') }}</small>
                    <h6>{{ $member->contact_info->address  ?? null }}</h6>
                </div>
            </div>
        </div>
        <!-- Column -->
        <div class="col-lg-8 col-xlg-9 col-md-7">
            <div class="card">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs profile-tab" role="tablist">
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#home" role="tab">Timeline</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#profile" role="tab">Profile</a>
                    </li>
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#settings" role="tab">Settings</a>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane" id="home" role="tabpanel">
                        <div class="card-body">
                            <div class="profiletimeline">
                                <div class="sl-item">
                                    <div class="sl-left"><img src="../assets/images/users/1.jpg" alt="user"
                                                              class="img-circle"/></div>
                                    <div class="sl-right">
                                        <div><a href="javascript:void(0)" class="link">John Doe</a> <span
                                                class="sl-date">5 minutes ago</span>
                                            <p>assign a new task <a href="javascript:void(0)"> Design weblayout</a></p>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-6 m-b-20"><img
                                                        src="../assets/images/big/img1.jpg"
                                                        class="img-responsive radius"/></div>
                                                <div class="col-lg-3 col-md-6 m-b-20"><img
                                                        src="../assets/images/big/img2.jpg"
                                                        class="img-responsive radius"/></div>
                                                <div class="col-lg-3 col-md-6 m-b-20"><img
                                                        src="../assets/images/big/img3.jpg"
                                                        class="img-responsive radius"/></div>
                                                <div class="col-lg-3 col-md-6 m-b-20"><img
                                                        src="../assets/images/big/img4.jpg"
                                                        class="img-responsive radius"/></div>
                                            </div>
                                            <div class="like-comm"><a href="javascript:void(0)" class="link m-r-10">2
                                                    comment</a> <a href="javascript:void(0)" class="link m-r-10"><i
                                                        class="fa fa-heart text-danger"></i> 5 Love</a></div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="sl-item">
                                    <div class="sl-left"><img src="../assets/images/users/2.jpg" alt="user"
                                                              class="img-circle"/></div>
                                    <div class="sl-right">
                                        <div><a href="javascript:void(0)" class="link">John Doe</a> <span
                                                class="sl-date">5 minutes ago</span>
                                            <div class="m-t-20 row">
                                                <div class="col-md-3 col-xs-12"><img src="../assets/images/big/img1.jpg"
                                                                                     alt="user"
                                                                                     class="img-responsive radius"/>
                                                </div>
                                                <div class="col-md-9 col-xs-12">
                                                    <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer
                                                        nec odio. Praesent libero. Sed cursus ante dapibus diam. </p> <a
                                                        href="javascript:void(0)" class="btn btn-success"> Design
                                                        weblayout</a></div>
                                            </div>
                                            <div class="like-comm m-t-20"><a href="javascript:void(0)"
                                                                             class="link m-r-10">2 comment</a> <a
                                                    href="javascript:void(0)" class="link m-r-10"><i
                                                        class="fa fa-heart text-danger"></i> 5 Love</a></div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="sl-item">
                                    <div class="sl-left"><img src="../assets/images/users/3.jpg" alt="user"
                                                              class="img-circle"/></div>
                                    <div class="sl-right">
                                        <div><a href="javascript:void(0)" class="link">John Doe</a> <span
                                                class="sl-date">5 minutes ago</span>
                                            <p class="m-t-10"> Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed
                                                nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum.
                                                Praesent mauris. Fusce nec tellus sed augue semper </p>
                                        </div>
                                        <div class="like-comm m-t-20"><a href="javascript:void(0)" class="link m-r-10">2
                                                comment</a> <a href="javascript:void(0)" class="link m-r-10"><i
                                                    class="fa fa-heart text-danger"></i> 5 Love</a></div>
                                    </div>
                                </div>
                                <hr>
                                <div class="sl-item">
                                    <div class="sl-left"><img src="../assets/images/users/4.jpg" alt="user"
                                                              class="img-circle"/></div>
                                    <div class="sl-right">
                                        <div><a href="javascript:void(0)" class="link">John Doe</a> <span
                                                class="sl-date">5 minutes ago</span>
                                            <blockquote class="m-t-10">
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt
                                            </blockquote>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--second tab-->
                    <div class="tab-pane" id="profile" role="tabpanel">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-xs-6 b-r"><strong>Full Name</strong>
                                    <br>
                                    <p class="text-muted">Johnathan Deo</p>
                                </div>
                                <div class="col-md-3 col-xs-6 b-r"><strong>Mobile</strong>
                                    <br>
                                    <p class="text-muted">(123) 456 7890</p>
                                </div>
                                <div class="col-md-3 col-xs-6 b-r"><strong>Email</strong>
                                    <br>
                                    <p class="text-muted">johnathan@admin.com</p>
                                </div>
                                <div class="col-md-3 col-xs-6"><strong>Location</strong>
                                    <br>
                                    <p class="text-muted">London</p>
                                </div>
                            </div>
                            <hr>
                            <p class="m-t-30">Donec pede justo, fringilla vel, aliquet nec, vulputate eget, arcu. In
                                enim justo, rhoncus ut, imperdiet a, venenatis vitae, justo. Nullam dictum felis eu pede
                                mollis pretium. Integer tincidunt.Cras dapibus. Vivamus elementum semper nisi. Aenean
                                vulputate eleifend tellus. Aenean leo ligula, porttitor eu, consequat vitae, eleifend
                                ac, enim.</p>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum
                                has been the industry's standard dummy text ever since the 1500s, when an unknown
                                printer took a galley of type and scrambled it to make a type specimen book. It has
                                survived not only five centuries </p>
                            <p>It was popularised in the 1960s with the release of Letraset sheets containing Lorem
                                Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker
                                including versions of Lorem Ipsum.</p>
                            <h4 class="font-medium m-t-30">Skill Set</h4>
                            <hr>
                            <h5 class="m-t-30">Wordpress <span class="pull-right">80%</span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="80"
                                     aria-valuemin="0" aria-valuemax="100" style="width:80%; height:6px;"><span
                                        class="sr-only">50% Complete</span></div>
                            </div>
                            <h5 class="m-t-30">HTML 5 <span class="pull-right">90%</span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-info" role="progressbar" aria-valuenow="90"
                                     aria-valuemin="0" aria-valuemax="100" style="width:90%; height:6px;"><span
                                        class="sr-only">50% Complete</span></div>
                            </div>
                            <h5 class="m-t-30">jQuery <span class="pull-right">50%</span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" aria-valuenow="50"
                                     aria-valuemin="0" aria-valuemax="100" style="width:50%; height:6px;"><span
                                        class="sr-only">50% Complete</span></div>
                            </div>
                            <h5 class="m-t-30">Photoshop <span class="pull-right">70%</span></h5>
                            <div class="progress">
                                <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="70"
                                     aria-valuemin="0" aria-valuemax="100" style="width:70%; height:6px;"><span
                                        class="sr-only">50% Complete</span></div>
                            </div>
                        </div>
                    </div>
                    <!--Setting -->
                    <div class="tab-pane active" id="settings" role="tabpanel">
                        <div class="card-body">
                            <form action="" method="post" class="form-horizontal form-material">
                                @csrf
                                <div class="form-group">
                                    <label>{{ trans('Full Name') }}</label>
                                    <input type="text" class="form-control form-control-line" name="name"
                                           value="{{ $member->name }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('Email') }}</label>
                                    <input type="email" class="form-control form-control-line" name="email"
                                           value="{{ $member->email }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('Phone') }}</label>
                                    <input type="tel" class="form-control form-control-line"
                                           name="contact_info[phone]"
                                           value="{{ $member->contact_info->phone ?? null }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('Address') }}</label>
                                    <input type="tel" class="form-control form-control-line"
                                           name="contact_info[address]"
                                           value="{{ $member->contact_info->address ?? null }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('Username') }}</label>
                                    <input type="text" class="form-control form-control-line" name="username"
                                           value="{{ $member->username }}">
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('Password') }}</label>
                                    <input type="password" class="form-control form-control-line"
                                           name="password">
                                </div>
                                <div class="form-group">
                                    <label>{{ trans('Re-enter Password') }}</label>
                                    <input type="password" class="form-control form-control-line"
                                           name="password_re_enter">
                                </div>
                                <div class="form-group">
                                    <button type="submit"
                                            class="btn btn-success">{{ trans('Update Profile') }}</button>
                                    <button type="reset" class="btn btn-inverse waves-effect waves-light">
                                        {{ trans('Reset') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax'])  !!}
@endsection
@push('js')
    {!! JsValidator::formRequest('Modules\Member\Http\Requests\MemberRequest') !!}
    <script src="{{ asset('assets/backend/jquery/modal.js') }}"></script>
@endpush


