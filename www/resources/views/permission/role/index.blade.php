@extends('layouts.layout')
@section('title','Role')
@section('page-styles')
<link href="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet">
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet">
<link href="{{asset('assets/global/plugins/datatables-responsive/responsive.dataTables.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-grey-mint">
                    <i class="fa fa-lock fa-2x font-grey-mint"></i>
                    <span class="caption-subject uppercase bold">Lista ról</span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="table-toolbar">
                    <div class="row">
                      @can('create role')
                        <div class="col-md-6">
                            <div class="btn-group">
                                <a href="{{route('role.create')}}" class="btn grey-mint btn-outline">
                                    Dodaj
                                    <i class="fa fa-plus-circle"></i>
                                </a>
                            </div>
                        </div>
                      @endcan
                    </div>
                </div>
                <table class="table table-bordered table-striped table-responsive" id="table">
                    <thead>
                        <tr>
                            <th>Nazwa</th>
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
<script src="{{ asset('assets/global/scripts/datatable.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables-responsive/dataTables.responsive.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
@stop
@section('page-js')
@include('permission.partials.index-page-scripts')
@stop
