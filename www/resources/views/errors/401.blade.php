@extends('layouts.layout')
@section('page-styles')
<link href="{{asset('assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/pages/css/error.min.css')}}" rel="stylesheet" type="text/css" />
@stop
@section('content')
  <body class="page-404-full-page">
      <div class="row">
          <div class="col-md-12 page-404">
            <div class="number font-red"> 401 </div>
            <div class="details">
                <p> Nie masz odpowiednich uprawnień do wyświetlenia tej strony.
                    <br/>
                    <a href="{{route('home')}}"> Powróć do strony domowej. </a>
                </p>
            </div>
          </div>
      </div>
  </body>
@stop
