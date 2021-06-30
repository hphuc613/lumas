@extends("Base::layouts.master")

@section("content")
    <div id="documentation-module">
        <div class="breadcrumb-line">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">{{ trans("Home") }}</a></li>
                    <li class="breadcrumb-item"><a href="#">{{ trans("Documentation") }}</a></li>
                </ol>
            </nav>
        </div>
        <div id="head-page" class="d-flex justify-content-between">
            <div class="page-title"><h3>{{ trans("Documentation") }}</h3></div>
            @if(Auth::user()->isAdmin())
                <div class="group-btn">
                    <a href="{{ route("get.documentation.create") }}"
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
                    <div class="col-2">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                             aria-orientation="vertical">
                            @foreach($documents as $key => $document)
                                <a class="nav-link document-title @if($key === 0) active @endif" data-toggle="pill"
                                   href="{{ route("get.documentation.view", $document->id) }}">
                                    {{ trans($document->name) }}
                                </a>
                            @endforeach
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
                                                            <a href="{{ route("get.documentation.delete", $document->id) }}"
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
                                                    <form action="{{ route("post.documentation.update", $document->id) }}"
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
                                                                <textarea name="content" class="ckeditor-document-form"
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
    <script>
        CKEDITOR.replace($('.ckeditor-document-form').attr('id'), {
            height: 800,
            language: $('html').attr('lang')
        });

        $(document).on('click', '.btn-edit', function () {
            var tab_parent = $(this).parents('.document-content');
            tab_parent.find('.view-content').hide();
            tab_parent.find('.edit-content').show();
        })

        $('a').click(function (event) {
            if (!$(document).find('.edit-content').is(':hidden')) {
                event.preventDefault();
                alert("{{ trans('Please submit') }}");
            }
        });

        $('.document-title').on('click', function () {
            if ($(document).find('.edit-content').is(':hidden')) {
                let url = $(this).attr('href');
                $.pjax({url: url, container: '#document-content', push: false})
            }
        })

        $(document).on('submit', '#document-content-form', function (event) {
            $.pjax.submit(event, '#document-content', {push: false})
        })

    </script>
@endpush