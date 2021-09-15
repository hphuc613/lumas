@extends("Base::layouts.master")
@push('css')
    <link href="{{ asset('assets/frontend/assets/node_modules/Magnific-Popup-master/dist/magnific-popup.css') }}"
          rel="stylesheet">
    <link href="{{ asset('assets/frontend/dist/css/user-card.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/nestable/nestable.css') }}" rel="stylesheet">
@endpush
@section("content")
    <div id="documentation-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("移動應用程序 - 用戶指南") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("移動應用程序 - 用戶指南") }}</h3></div>
            @if(Auth::user()->isAdmin())
                <div class="group-btn">
                    <a href="{{ route("get.documentation_mobile_ct.create") }}"
                       class="btn btn-main-color"
                       data-toggle="modal"
                       data-title="{{ trans('Documentation') }}"
                       data-target="#form-modal">
                        <i class="fa fa-plus"></i> &nbsp; {{ trans("Add New") }}
                    </a>
                </div>
            @endif
        </div>
    </div>
    <div class="listing">
        <div class="card">
            <div class="card-body" style="min-height: 800px">
                <div class="row">
                    <div class="col-2" id="list-title-tab">
                        <div class="nav flex-column nav-pills" id="list-title" role="tablist"
                             aria-orientation="vertical">
                            @foreach($documents as $key => $document)
                                <a class="nav-link document-title @if($key === 0) active @endif" data-toggle="pill"
                                   href="{{ route("get.documentation_mobile_ct.view", $document->id) }}">
                                    {{ ($document->sort+1).'. '.$document->name }}
                                </a>
                            @endforeach
                            <div class="pt-5">
                                <button type="button" class="btn btn-info w-100 btn-sort">Sort</button>
                            </div>
                        </div>
                        <div id="nestable-tab" style="display: none">
                            <div class="dd w-100 mb-5" id="nestable">
                                <ol class="dd-list">
                                    @foreach($documents as $key => $document)
                                        <li class="dd-item" data-id="{{ $document->id }}">
                                            <div class="dd-handle">
                                                {{ ($document->sort+1).'. '.$document->name }}
                                            </div>
                                        </li>
                                    @endforeach
                                </ol>
                            </div>
                            <form action="{{route("post.documentation_mobile_ct.sort")}}" method="post"
                                  id="sort-list-title-form">
                                @csrf
                                <textarea id="nestable-output" name="sort" class="d-none"></textarea>
                                <button type="submit" class="btn btn-info w-100">Sort</button>
                            </form>
                        </div>
                    </div>
                    <div class="col-10">
                        <div class="tab-content" id="document-content">
                            @if($documents->isNotEmpty())
                                @php($document = $documents->first())
                                <div class="document-content" id="{{ $document->key }}" role="tabpanel"
                                     aria-labelledby="{{ $document->key }}-tab">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between">
                                            <h5>{{ trans($document->name) }}</h5>
                                        </div>
                                        <div class="card-body" style="min-height: 800px">
                                            <div class="content">
                                                <div class="view-content">
                                                    @if(Auth::user()->isAdmin())
                                                        <div class="btn-group">
                                                            <button class="btn btn-info mr-2 btn-edit">
                                                                <i class="fas fa-pencil-alt"></i>
                                                            </button>
                                                            <a href="{{ route("get.documentation_mobile_ct.delete", $document->id) }}"
                                                               class="btn btn-danger btn-delete">
                                                                <i class="fas fa-trash"></i>
                                                            </a>
                                                        </div>
                                                    @endif
                                                    <div class="pt-3">
                                                        {!! $document->content !!}
                                                    </div>
                                                </div>
                                                <div class="edit-content" style="display: none">
                                                    <form
                                                        action="{{ route("post.documentation_mobile_ct.update", $document->id) }}"
                                                        method="post" id="document-content-form">
                                                        <div class="btn-group">
                                                            <button type="submit"
                                                                    class="btn btn-info mr-2 btn-edit-success">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </div>
                                                        @csrf
                                                        <div class="pt-3">
                                                            <div class="form-group">
                                                                <label for="name"> {{ trans('Name') }}</label>
                                                                <input type="text" name="name" class="form-control"
                                                                       value="{{ $document->name }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="name"> {{ trans('Content') }}</label>
                                                                <textarea name="content" class="form-control" rows="100"
                                                                          id="content-{{ $document->key }}">{{ $document->content }}</textarea>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! \App\AppHelpers\Helper::getModal(['class' => 'modal-ajax', 'size' => 'modal-lg'])  !!}
@endsection
@push('js')
    {!! JsValidator::formRequest('Modules\Documentation\Http\Requests\DocumentationRequest') !!}

    <script
        src="{{ asset('assets/frontend/assets/node_modules/Magnific-Popup-master/dist/jquery.magnific-popup.min.js') }}"></script>
    <script
        src="{{ asset('assets/frontend/assets/node_modules/Magnific-Popup-master/dist/jquery.magnific-popup-init.js') }}"></script>

    <script src="{{ asset('assets/nestable/jquery.nestable.js') }}"></script>
    <script>

        $(document).on('click', '.btn-edit', function () {
            var tab_parent = $(this).parents('.document-content');
            tab_parent.find('.view-content').hide();
            tab_parent.find('.edit-content').show();
        })

        $('.document-title').on('click', function () {
            if ($(document).find('.edit-content').is(':hidden')) {
                let url = $(this).attr('href');
                $.pjax({url: url, container: '#document-content', push: false})
            }
        })

        $(document).on('submit', '#document-content-form', function (event) {
            $.pjax.submit(event, '#document-content', {push: false})
        })

        $(document).ready(function () {

            $('.btn-sort').click(function () {
                $('#list-title').hide();
                $('#nestable-tab').show();
                $(this).hide()
            });

            var updateOutput = function (e) {
                var list = e.length ? e : $(e.target),
                    output = list.data('output');
                if (window.JSON) {
                    output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));
                } else {
                    output.val('JSON browser support required for this demo.');
                }
            };

            // activate Nestable for list 1
            $('#nestable').nestable({
                group: 1
            }).on('change', updateOutput);

            updateOutput($('#nestable').data('output', $('#nestable-output')));

            $('#nestable-menu').on('click', function (e) {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });
        });
    </script>
@endpush
