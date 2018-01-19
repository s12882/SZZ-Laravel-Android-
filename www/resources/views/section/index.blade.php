@extends('layouts.layout')
@section('title','Działy')
@section('page-styles')
<link href="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet">
<link href="{{ asset('assets/global/css/jquery.orgchart.min.css')}}" rel="stylesheet"/>
<style>
          #chart-container {
            position: relative;
            display: inline-block;
            top: 10px;
            left: 10px;
            height: 420px;
            width: calc(100% - 24px);
            border: 2px dashed #aaa;
            border-radius: 5px;
            overflow: auto;
            text-align: center;
          }
</style>
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption font-grey-mint">
                    <i class="fa fa-list fa-2x font-grey-mint"></i>
                    <span class="caption-subject uppercase bold">Graf działów</span>
                </div>
            </div>
            <div class="portlet-body">
                  <div class="pull-right">
                        <button class="btnRecenter">Wyśrodkuj</button>
                </div>
                <div id="chart-container"></div>
           </div>
        </div>
    </div>
</div>
@stop
@section('plugin-js')
<script src="{{ asset('assets/global/scripts/jquery.orgchart.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
@stop
@section('page-js')
@include('section.partials.index-page-scripts')
@stop
