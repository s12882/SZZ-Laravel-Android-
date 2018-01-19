@extends('layouts.layout')
@section('title','Pracownicy')
@section('page-styles')
<link href="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet">
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet">
<link href="{{asset('assets/global/plugins/datatables-responsive/responsive.dataTables.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{asset('assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css')}}"/>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-grey-mint">
                    <i class="fa fa-users fa-2x font-grey-mint"></i>
                    <span class="caption-subject uppercase bold">Lista pracowników</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row no-gutters">                    
                    <div class="col-lg-3 col-md-6 no-gutters">
                        <div class="form-group">
                                {!! Form::select('section_id', $sections, null, ['class' => 'form-control bs-select selectpicker filter','data-none-selected-text' => "Nie wybrano obiektu", 'placeholder' => 'Dowolny', 'data-live-search'=>'true', 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}   
                            <span class="help-block"> Dział </span>
                        </div> 
                    </div>
                </div>
              @can('create user')
                <div class="table-toolbar">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a href="{{route('user.create')}}" class="btn grey-mint btn-outline">
                                    Dodaj
                                    <i class="fa fa-plus-circle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
              @endcan
                <table class="table table-bordered table-striped table-responsive" id="table">
                    <thead>
                        <tr>
                            <th>Imie</th>
                            <th>Nazwisko</th>
                            <th>Dział</th>
                            <th>Numer telefonu</th>
                            <th>E-mail</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@stop
@section('plugin-js')
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables-responsive/dataTables.responsive.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js')}}" type="text/javascript"></script>
@stop
@section('page-js')
@include('user.partials.index-page-scripts')
@stop
