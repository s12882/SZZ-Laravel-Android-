@extends('layouts.layout')
@section('page-styles')
<link href="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css')}}"/>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-book font-grey-mint"></i>
                    <span class="caption-subject font-grey-mint bold uppercase">{{$pageTitle}}</span>
                </div>
            </div>
            <div class="portlet-body form">
                {!! Form::model($model,array('url' => $postAction, 'method'=>$actionMethod,'class'=>'form-horizontal','files' => true, 'id' => 'warehouse_document')) !!}
                {!! Form::hidden('id', null) !!}
                <div class="form-body">
                    <div class="form-group">
                        <label class="col-md-2 control-label">
                            Rodzaj dokumentu <span class="required" aria-required="true"> * </span>
                        </label>
                        <div class="col-lg-6 col-md-8">
                            <div class="input-icon right">
                                <i class="fa"></i>
                                  {!! Form::select('warehouse_document_category', $categories, isset($task_id) ? \App\Enums\WarehouseDocumentCategory::Pobranie : null, ['class' => 'form-control bs-select','data-none-selected-text' => "Nie wybrano obiektu", 'data-live-search'=>true, 'autocomplete' =>'off', 'spellcheck'=>'false', 'id' => 'document_type']) !!}
                            </div>
                        </div>
                    </div>
                    @if(isset($task_id))
                    {!! Form::hidden('task_id', $task_id)!!}
                    @else
                    <div class="form-group" id="task_assign">
                            <label class="col-md-2 control-label">
                                Zadania <span class="required" aria-required="true"> * </span>
                            </label>
                            <div class="col-lg-6 col-md-8">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    {!! Form::select('task_id', $tasks, null, ['class' => 'form-control bs-select selectpicker filter','data-none-selected-text' => "Nie wybrano obiektu", 'placeholder' => 'Dowolna', 'data-live-search'=>'true', 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}   
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row">
                        <div class="col-lg-6 border-right-2">
                            <p class="text-center">
                                <strong>Dostępne przedmioty</strong>
                            </p>
                            <table class="table table-bordered table-striped table-responsive" id="availableItems">
                                <thead>
                                    <tr>
                                        <th>Nazwa</th>
                                        <th>Typ</th>
                                        <th>Kod kresowy</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <p class="text-center ">
                                <strong>Przypisane przedmioty</strong>
                            </p>
                            <table class="table table-bordered table-striped table-responsive" id="assignedItems">
                                <thead>
                                    <tr>
                                        <th>Nazwa</th>
                                        <th>Typ</th>
                                        <th>Ilość</th>
                                        <th>Kod kresowy</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                </div>
            </div>
                <div class="form-actions">
                    <div class="row">
                        <div class="col-md-offset-2 col-lg-4 col-md-6">
                            {!! Form::submit('Zatwierdź', ['class'=>'btn grey-mint grey-mint-stripe btn-outline']) !!}
                            <a class="btn red red-stripe btn-outline" href="{{route('warehouse_document.index')}}">Powróć do listy</a>
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
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/jquery-validation/js/jquery.validate.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/jquery-validation/js/additional-methods.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/jquery-validation/js/localization/messages_pl.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js')}}" type="text/javascript"></script>
@stop
@section('page-js')
@include('warehouse_document.partials.edit-page-scripts')
@stop 