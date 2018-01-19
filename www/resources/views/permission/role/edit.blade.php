@extends('layouts.layout')
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-lock font-grey-mint"></i>
                    <span class="caption-subject font-grey-mint bold uppercase">{{$pageTitle}}</span>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::model($model,array('url' => $postAction, 'method'=>$actionMethod,'class'=>'form-horizontal', 'id' => "permission-form")) !!}
                {!! Form::hidden('id', null) !!}
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            Nazwa <span class="required" aria-required="true"> * </span>
                        </label>
                        <div class="col-lg-6 col-md-8">
                            <div class="input-icon right">
                                <i class="fa"></i>
                                {!! Form::text('name', null, ['class' => 'form-control', 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                      <label class="col-md-2 control-label" for="checkboxes">
                        Uprawnienia
                      </label>
                      <div class="col-lg-6 col-md-8">
                          @foreach ($permissions as $id => $permission)
                            <div class="input-icon right">
                                <i class="fa"></i>
                              {{ Form::checkbox('permissions[]',  $permission, $model != null ? $model->hasPermissionTo($permission) : null, ['data-rule-oneOrMore' => "true", 'data-msg-oneOrMore' => "Przynajmniej jedno uprawnienie musi być wybrane"]) }}
                              {{ Form::label($permission, trans('permission.'.$permission)) }}
                             </div>
                          @endforeach
                        </div>
                      </div>
                  </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-2 col-lg-4 col-md-6">
                            {!! Form::submit('Zatwierdź', ['class'=>'btn grey-mint grey-mint-stripe btn-outline']) !!}
                            <a class="btn red red-stripe btn-outline" href="{{route('role.index')}}">Powróć do listy</a>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('plugin-js')
<script src="{{asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/jquery-validation/js/localization/messages_pl.min.js')}}" type="text/javascript"></script>
@stop
@section('page-js')
@include('permission.partials.edit-page-scripts')
@stop
