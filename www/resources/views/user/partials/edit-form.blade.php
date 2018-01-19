@section('page-styles')
@stop
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-users font-grey-mint"></i>
                    <span class="caption-subject font-grey-mint bold uppercase">{{$pageTitle}}</span>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::model($user,array('url' => $postAction, 'method'=>$actionMethod,'class'=>'form-horizontal user-form')) !!}
                {!! Form::hidden('id', null) !!}
                <div class="form-body">
                  <div class="form-group">
                      <label class="col-md-2 control-label">
                          Login <span class="required" aria-required="true"> * </span>
                      </label>
                      <div class="col-lg-6 col-md-8">
                            @if(!preg_match('/profile/', Route::currentRouteName()))
                            <div class="input-icon right">
                                    <i class="fa"></i>
                                    {!! Form::text('login', null, ['class' => 'form-control', 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}
                                </div>
                            @else
                            <div class="col-sm-10">
                                    <p class="form-control-static">{{$user->login}}</p>
                                  </div>
                            @endif
                      </div>
                  </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            Imię <span class="required" aria-required="true"> * </span>
                        </label>
                        <div class="col-lg-6 col-md-8">
                                @if(!preg_match('/profile/', Route::currentRouteName()))
                            <div class="input-icon right">
                                <i class="fa"></i>
                                {!! Form::text('first_name', null, ['class' => 'form-control', 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}
                            </div>
                            @else
                            <div class="col-sm-10">
                                    <p class="form-control-static">{{$user->first_name}}</p>
                                  </div>
                            @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            Nazwisko <span class="required" aria-required="true"> * </span>
                        </label>
                        <div class="col-lg-6 col-md-8">
                                @if(!preg_match('/profile/', Route::currentRouteName()))
                                <div class="input-icon right">
                                        <i class="fa"></i>
                                        {!! Form::text('surname', null, ['class' => 'form-control', 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}
                                    </div>
                                @else
                                <div class="col-sm-10">
                                        <p class="form-control-static">{{$user->surname}}</p>
                                      </div>
                                @endif
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            Telefon:
                        </label>
                        <div class="col-lg-6 col-md-8">
                            <div class="input-icon right">
                                <i class="fa"></i>
                                {!! Form::text('phoneNumber', null, ['class' => 'form-control', 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            E-mail: <span class="required" aria-required="true"> * </span>
                        </label>
                        <div class="col-lg-6 col-md-8">
                            <div class="input-icon right">
                                <i class="fa"></i>
                                {!! Form::text('email', null, ['class' => 'form-control', 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}
                            </div>
                        </div>
                    </div>
                    @if(preg_match('/create/', Route::currentRouteName()))
                    <div class="form-group">
                        <div class="col-lg-6 col-md-8 col-md-offset-2">
                            {{ Form::checkbox('generatePassword', 1 , true, ['class' => 'form-check-input', 'id' => 'generatePassword']) }}
                            <label class="form-check-labell" for="exampleCheck1">Generuj hasło</label>
                        </div>
                    </div>
                    <div id="assignPassword" class="form-group" style="display:none">
                    @else
                    <div class="form-group">
                    @endif
                        <label class="col-md-2 control-label">
                            Hasło:
                        </label>
                        <div class="col-lg-6 col-md-8">
                            <div class="input-icon right">
                                <i class="fa"></i>
                                {!! Form::password('password', ['class'=>'form-control','id' => 'password_id']) !!}
                            </div>
                        </div>
                    </div>
                    @can('change section')
                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            Dział <span class="required" aria-required="true"> * </span>
                        </label>
                        <div class="col-lg-6 col-md-8">
                            <div class="input-icon right">
                                <i class="fa"></i>
                                  {!! Form::select('section_id', $sections, null, ['class' => 'form-control bs-select','data-none-selected-text' => "Nie wybrano obiektu", 'data-live-search'=>true, 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}
                            </div>
                        </div>
                    </div>
                    @endcan
                    @can('change role')
                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            Rola <span class="required" aria-required="true"> * </span>
                        </label>
                        <div class="col-lg-6 col-md-8">
                            <div class="input-icon right">
                                <i class="fa"></i>
                                  {!! Form::select('role_id', $roles, $user != null ? $user->roles : null, ['class' => 'form-control bs-select','data-none-selected-text' => "Nie wybrano roli", 'data-live-search'=>true, 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}
                            </div>
                        </div>
                    </div>
                    @endcan
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-2 col-lg-5 col-md-7">
                            {!! Form::submit('Zatwierdź', ['class'=>'btn grey-mint grey-mint-stripe btn-outline']) !!}
                            <a class="btn red red-stripe btn-outline" href="{{$back_button_action}}">Powróć</a>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@section('plugin-js')
<script src="{{asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/jquery-validation/js/localization/messages_pl.min.js')}}" type="text/javascript"></script>
@stop
@section('page-js')
@include('user.partials.edit-page-scripts')
@stop
