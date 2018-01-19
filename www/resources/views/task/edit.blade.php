@extends('layouts.layout') @section('page-styles')
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet">
<link href="{{ asset('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('assets/global/plugins/bootstrap-select/css/bootstrap-select.min.css')}}">
<link href="{{ asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet"> @stop @section('content')
<div class="row">
	<div class="col-md-12">
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-tasks font-grey-mint"></i>
					<span class="caption-subject font-grey-mint bold uppercase">{{$pageTitle}}</span>
				</div>
			</div>
			<div class="portlet-body form">
				{!! Form::model($task,array('url' => $postAction, 'method'=>$actionMethod,'class'=>'form-horizontal', 'id' => 'task_form',
				'files' => true)) !!} {!! Form::hidden('id',null) !!} {!! Form::hidden('assignedUsers',null, array('id' => 'assignedUsers'))
				!!}
				<div class="form-body">
					<div class="form-group">
						<label class="col-md-2 control-label">
							Nazwa
							<span class="required" aria-required="true"> * </span>
						</label>
						<div class="col-lg-9 col-md-10">
							{!! Form::text('name', null, ['class' => 'form-control', 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">
							Miejsce:
							<span class="required" aria-required="true"> * </span>
						</label>
						<div class="col-lg-9 col-md-10">
							{!! Form::text('location', null, ['class' => 'form-control', 'autocomplete' =>'off', 'spellcheck'=>'false',]) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">
							Opis:
							<span class="required" aria-required="true"> * </span>
						</label>
						<div class="col-lg-9 col-md-10">
							{!! Form::textarea('description', null, ['id' => 'description', 'rows' => '5', 'class' => 'form-control', 'autocomplete'=>'off',
							'spellcheck'=>'false']) !!}
						</div>
					</div>
					@if(!preg_match( '/create/', Route::currentRouteName()))
					<div class="form-group">
						<label class="col-md-2 control-label">
							Status:
							<span class="required" aria-required="true"> * </span>
						</label>
						<div class="col-lg-9 col-md-10">
							{!! Form::select('status', $status, null, ['class' => 'form-control selectpicker','data-none-selected-text' => "Nie wybrano
							obiektu", 'data-live-search'=>'true', 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}
						</div>
					</div>
					@endif
					<div class="form-group">
						<label class="col-md-2 control-label">
							Zaplanuj:
							<span class="required" aria-required="true"> * </span>
						</label>
						<div class="col-lg-9 col-md-10">
							{!! Form::text('scheduled_for', null, ['class' => 'form-control', 'autocomplete' =>'off', 'spellcheck'=>'false', 'id' =>
							'datepicker']) !!}
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">
							Dział:
							<span class="required" aria-required="true"> * </span>
						</label>
						<div class="col-lg-9 col-md-10">
							{!! Form::select('section_id', $sections, null, ['class' => 'form-control selectpicker filter','data-none-selected-text'
							=> "Nie wybrano obiektu", 'data-live-search'=>'true', 'autocomplete' =>'off', 'spellcheck'=>'false']) !!}
						</div>
					</div>
					@if(preg_match( '/create/', Route::currentRouteName()))
					<div class="form-group">
						<div class="col-md-offset-2 col-md-8">
							<button id="showAssignForm">Przypisz pracowników</button>
						</div>
					</div>
					<div id="userAssign" class="row" style="display:none">
						@else
						<div id="userAssign" class="row">
							@endif
							<div class='col-md-offset-2 col-md-9'>
								<div class="col-md-6 border-right-2">
									<p class="text-center">
										<strong>Dostępni pracownicy</strong>
									</p>
									<table class="table table-bordered table-striped table-responsive" id="available">
										<thead>
											<tr>
												<th>Imie</th>
												<th>Nazwisko</th>
												<th></th>
											</tr>
										</thead>
									</table>
								</div>
								<div class="col-md-6">
									<p class="text-center ">
										<strong>Przypisani pracownicy</strong>
									</p>
									<table class="table table-bordered table-striped table-responsive" id="assigned">
										<thead>
											<tr>
												<th>Imie</th>
												<th>Nazwisko</th>
												<th></th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-md-2 control-label">
								Zdjęcia
							</label>
							<div class="col-lg-9 col-md-10">
								{!! Form::file('images[]', ['accept'=> 'image/*','class'=>'form-control','multiple'=> 'multiple']) !!}
								<span class="help-block">
									Preferowany format pliku - PNG [max. 2MB].
								</span>
							</div>
						</div>
						@if(isset($task) && count($task->images)> 0)
						<div class='form-group'>
							<label class="col-md-2 control-label">
								Dodane zdjęcia
							</label>
							<div class='col-lg-9 col-md-10'>
								@foreach($task->images as $index=>$image)
								<div class="col-lg-3 no-gutters">
									<img src='{{asset($image->webPath())}}' class='img-responsive thumbnail'>
									<div class='image-remove-button remove-image' data-url="{{route('task.destroy_image',['id' => $image->id])}}">
										<img src="{{asset('assets/global/img/remove-icon-small.png')}}" class="thumbnail" />
									</div>
									<a class="image-download-button_edit_page btn red btn-xs" data-toggle="tooltip" data-title="Pobierz plik" href="{{route('task.download_image',['id' => $image->id])}}" type="button" type="button">
										<span class="red">
											<i class="fa fa-download"></i>
										</span>
									</a>
								</div>
								@if(($index +1) % 4 == 0)
								<div class="clearfix visible-lg visible-md"></div>
								@endif @endforeach
							</div>
						</div>
						@endif
						<div class="form-actions">
							<div class="row">
								<div class="col-md-offset-3 col-lg-5 col-md-7">
									{!! Form::submit('Zatwierdź', ['class'=>'btn grey-mint grey-mint-stripe btn-outline']) !!}
									<a class="btn red red-stripe btn-outline" href="{{route('task.index')}}">Powróć</a>
									{!! Form::close() !!}
								</div>
							</div>
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
<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.pl.min.js"
 charset="UTF-8"></script>
<script src="{{ asset('assets/global/plugins/datatables-responsive/dataTables.responsive.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js')}}" type="text/javascript"></script> @stop 
@section('page-js') 
@include('task.partials.edit-page-scripts') 
{{--  @include('task.partials.items-section-scripts')   --}}
@stop