@extends('layouts.layout')
@section('title','Strona główna')
@section('page_styles')	
@stop
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-puzzle"></i>Dostępne strony </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse" data-original-title="Zwiń/rozwiń" title=""> </a>
                </div>
            </div>
            <div class="portlet-body">
                <div class='tiles'>

                    <a href='{{route('task.index')}}'>
                        <div class="tile bg-green-meadow">
                            <div class="tile-body">
                                <i class="fa fa-list-alt"></i>
                            </div>
                            <div class="tile-object">
                                <div class="name"> Zadania </div>
                            </div>
                        </div>                    
                    </a>
                    @can('list users')
                    <a href='{{route('user.index')}}'>
                        <div class="tile bg-yellow">
                            <div class="tile-body">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="tile-object">
                                <div class="name"> Pracownicy </div>
                            </div>
                        </div>                   
                    </a>
                    @endcan
                    @can('list sections')
                    <a href='{{route('section.index')}}'>
                        <div class="tile bg-blue">
                            <div class="tile-body">
                                <i class="fa fa-sitemap"></i>
                            </div>
                            <div class="tile-object">
                                <div class="name"> Działy </div>
                            </div>
                        </div>                     
                    </a>
                    @endcan
                    @can('list roles')
                    <a href='{{route('role.index')}}'>
                        <div class="tile bg-blue-hoki">
                            <div class="tile-body">
                                <i class="fa fa-lock"></i>
                            </div>
                            <div class="tile-object">
                                <div class="name"> Uprawnienia </div>
                            </div>
                        </div>
                    </a>
                    @endcan
                    <a href='{{route('item.index')}}'>
                        <div class="tile bg-red-sunglo">
                            <div class="tile-body">
                                <i class="glyphicon glyphicon-briefcase"></i>
                            </div>
                            <div class="tile-object">
                                <div class="name"> Przedmioty </div>
                            </div>
                        </div>                    
                    </a>                                               
                    <a href='{{route('item_category.index')}}'>
                            <div class="tile bg-grey-mint">
                                <div class="tile-body">
                                    <i class="fa fa-book"></i>
                                </div>
                                <div class="tile-object">
                                    <div class="name"> Kategorie przedmiotów </div>
                                </div>
                            </div>                    
                        </a>   
                </div>
            </div>
        </div>
    </div>
</div>
@stop
@section('plugin_js')
@stop
@section('page_js')
@stop 
