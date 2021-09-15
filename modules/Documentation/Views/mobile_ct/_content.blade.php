<div class="document-content" id="{{ $document->key }}" role="tabpanel" aria-labelledby="{{ $document->key }}-tab">
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
                    <form action="{{ route("post.documentation_mobile_ct.update", $document->id) }}"
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
{!! JsValidator::formRequest('Modules\Documentation\Http\Requests\DocumentationRequest') !!}
