@extends('layouts.layout')
@section('title','Mój profil')
@section('page-styles')
<link href="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet">
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet">
<link href="{{asset('assets/global/plugins/datatables-responsive/responsive.dataTables.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
@stop
@section('content')
<div class="row profile">
<div class="col-md-3">
  <div class="profile-sidebar">
    <!-- SIDEBAR USERPIC -->
    <div class="profile-userpic">
      <img src="" class="img-responsive" alt="">
    </div>
    <!-- END SIDEBAR USERPIC -->
    <!-- SIDEBAR USER TITLE -->
    <div class="profile-usertitle">
      <div class="profile-usertitle-name">
        {{$user->fullName()}}
      </div>
      <div class="profile-usertitle-job">
        @if( !empty($user->section))
          {{$user->section->name}}
        @else
          Brak działu
        @endif
      </div>
    </div>
    <!-- END SIDEBAR USER TITLE -->
    <!-- SIDEBAR MENU -->
    <div class="profile-usermenu">
      <ul class="nav">
          <li class="active"><a data-toggle="tab" href="#home"><i class="glyphicon glyphicon-home"></i>Informacje ogólne</a></li>
          <li><a data-toggle="tab" href="#edit"><i class="glyphicon glyphicon-user"></i>Edycja profilu</a></li>
      </ul>
    </div>
    <!-- END MENU -->
  </div>
</div>
<div class="col-md-9">
        <div class="profile-content">
          <div class="tab-content">
            <div id="home" class="tab-pane fade in active">
                  <table class="table table-user-information">
                    <tbody>
                      <tr>
                        <td>Imie:</td>
                        <td>{{$user->first_name}}</td>
                      </tr>
                      <tr>
                        <td>Nazwisko:</td>
                        <td>{{$user->surname}}</td>
                      </tr>
                      <tr>
                        <td>Dział</td>
                        <td>
                          @if( !empty($user->section))
                            {{$user->section->name}}
                          @else
                            Brak działu
                          @endif
                        </td>
                      </tr>
                      <tr>
                        <td>Numer telefonu</td>
                        <td>{{$user->phoneNumber}}</td>
                      </tr>
                      <tr>
                        <td>Email</td>
                        <td><a href="mailto:{{$user->email}}">{{$user->email}}</a></td>
                    </tbody>
                  </table>
            </div>
            <div id="edit" class="tab-pane fade">
                @include('user.partials.edit-form')
            </div>
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
@include('user.partials.edit-page-scripts')
@stop
