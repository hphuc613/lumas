<div class="card">
    <form action="" method="post">
        @csrf
        <div class="row">
            <div class="col-md-8">
                <div class="card-header">
                    <h4>{{ trans("Client information") }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="name">{{ trans('Name') }}</label>
                            <input type="text" id="name" class="form-control" name="name"
                                   value="{{ $member->name ?? old('name') }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="phone">{{ trans('Phone Number') }}</label>
                            <input type="tel" id="phone" class="form-control" name="phone"
                                   value="{{ $member->phone ?? old('phone') }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="email">{{ trans('Email') }}</label>
                            <input type="email" id="email" class="form-control" name="email"
                                   value="{{ $member->email ?? old('email') }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="address">{{ trans('Address') }}</label>
                            <input type="text" id="address" class="form-control" name="address"
                                   value="{{ $member->address ?? old('address') }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="birthday">{{ trans('Birthday') }}</label>
                            <input type="text" class="form-control date" id="birthday" name="birthday"
                                   value="{{ !empty($member) ? \Carbon\Carbon::parse($member->birthday)->format('d-m-Y') : old('birthday') }}"
                                   placeholder="dd-mm-yyyy">
                        </div>
                        <div class="col-md-4 form-group">
                            <label>{{ trans('Sex') }}</label>
                            <div class="row">
                                <div class="col-md-6 radio">
                                    <input type="radio" id="sex-male" class="radio-style" name="sex" value="1"
                                           @if(!empty($member) && $member->sex === 1) checked @endif>
                                    <span class="pl-2"> {{ trans('Male') }}</span>
                                </div>
                                <div class="col-md-6 radio">
                                    <input type="radio" id="sex-female" class="radio-style" name="sex" value="0"
                                           @if(!empty($member) && $member->sex === 0) checked @endif>
                                    <span class="pl-2"> {{ trans('Female') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card-header">
                    <h4>{{ trans("Client Account") }}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="username">{{ trans('Username') }}</label>
                            <input type="text" id="username" class="form-control" name="username"
                                   value="{{ $member->username ?? old('username') }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="type-id">{{ trans('Member Type') }}</label>
                            {{ Form::select('type_id', [null => 'Select'] + $member_types, $member->type_id ?? NULL,
                                             ['id' => 'type-id', 'class' => 'select2 form-control', 'style' => 'width: 100%']) }}
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="status">{{ trans('Status') }}</label>
                            <select name="status" id="status" class="select2 form-control">
                                @foreach($statuses as $key => $status)
                                    <option value="{{ $key }}"
                                            @if(isset($member) && $member->status === $key) selected @endif>{{ $status }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="referrer">{{ trans('Referrer') }}</label>
                            <input type="text" id="referrer" class="form-control" name="referrer"
                                   value="{{ $member->referrer ?? old('referrer') }}">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="password">{{ trans('Password') }}</label>
                            <input type="password" id="password" class="form-control" name="password">
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="password_re_enter">{{ trans('Re-enter Password') }}</label>
                            <input type="password" id="password_re_enter" class="form-control" name="password_re_enter">
                        </div>
                        <div class="col-md-8 form-group">
                            <label for="how_to_know">{{ trans('How to know us') }}</label>
                            <textarea id="how_to_know" class="form-control" rows="5"
                                      name="how_to_know">{{ $member->how_to_know ?? old('how_to_know') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="p-2 mt-5">
                    <button type="submit" id="save" class="btn btn-main-color mr-2">{{ trans('Save') }}</button>
                    <button type="reset" class="btn btn-default">{{ trans('Reset') }}</button>
                </div>
            </div>
        </div>
    </form>
</div>
@push('js')
    {!! JsValidator::formRequest('Modules\Member\Http\Requests\MemberRequest') !!}
@endpush
