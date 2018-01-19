@extends('layouts.layout')
@section('page-styles')
<link rel="stylesheet" type="text/css" href="{{asset('assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css')}}">
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-list font-grey-mint"></i>
                    <span class="caption-subject font-grey-mint bold uppercase">{{$pageTitle}}</span>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::model($model,array('url' => $postAction, 'method'=>$actionMethod,'class'=>'form-horizontal', 'id' => "section-form")) !!}
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
                    @if($parent === null)
                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            Rodzic <span class="required" aria-required="true"> * </span>
                        </label>
                        <div class="col-lg-6 col-md-8">
                            <div class="input-icon right">
                                <i class="fa"></i>
                                {!! Form::select('parent_id', $sections, $parent, ['class' => 'form-control selectpicker','data-none-selected-text' => "Nie wybrano obiektu", 'data-live-search'=>'true', 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}
                            </div>
                        </div>
                    </div>
                    @else
                        {!! Form::hidden('parent_id', $parent) !!}
                    @endif
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-2 col-lg-4 col-md-6">
                            {!! Form::submit('Zatwierdź', ['class'=>'btn grey-mint grey-mint-stripe btn-outline']) !!}
                            <a class="btn red red-stripe btn-outline" href="{{route('section.index')}}">Powróć do listy</a>
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
<script src="{{asset('assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js')}}" type="text/javascript"></script>
@stop
@section('page-js')
@include('section.partials.edit-page-scripts')
@stop
