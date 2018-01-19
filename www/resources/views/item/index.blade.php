@extends('layouts.layout')
@section('title','Przedmioty')
@section('page-styles')
<link href="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet">
<link href="{{ asset('assets/global/plugins/datatables-buttons/css/buttons.dataTables.min.css')}}" rel="stylesheet"/>
<link href="{{asset('assets/global/plugins/datatables-responsive/responsive.dataTables.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
<link rel="stylesheet" type="text/css" href="{{asset('assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css')}}"/>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-grey-mint">
                    <i class="fa fa-list fa-2x font-grey-mint"></i>
                    <span class="caption-subject uppercase bold">Lista stanu magazynowego</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="row no-gutters"> 
                        <div class="col-lg-3 col-md-6 no-gutters">
                                <div class="form-group">
                                        {!! Form::select('category_id', $categories, null, ['class' => 'form-control bs-select selectpicker filter','data-none-selected-text' => "Nie wybrano obiektu", 'placeholder' => 'Dowolna', 'data-live-search'=>'true', 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}   
                                    <span class="help-block"> Kategoria </span>
                                </div> 
                            </div>
                    <div class="col-lg-3 col-md-6 no-gutters">
                        <div class="form-group">
                            <div class="input-group date-picker input-daterange" data-date-format="yyyy-mm-dd">
                                    <input type="text" class="form-control filter" name="amount_from">
                                    <span class="input-group-addon"> do </span>
                                    <input type="text" class="form-control filter" name="amount_to">
                            </div>
                                <span class="help-block"> Ilość przedmiotów </span>
                            </div>
                        </div>
                        </div>
                        @can('create item')
                        <div class="table-toolbar">
                        <div class="row">
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a href="{{route('item.create')}}" class="btn grey-mint btn-outline">
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
                            <th>Nazwa</th>
                            <th>Typ</th>
                            <th>Ilość</th>
                            <th>Kod kreskowy</th>
                            <th>Kategoria</th>
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
<script src="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js')}}" type="text/javascript"></script>
@stop
@section('page-js')
@include('item.partials.index-page-scripts')
@stop
