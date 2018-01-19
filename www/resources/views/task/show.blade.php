@extends('layouts.layout') @section('page-styles')
<link href="{{asset('assets/pages/css/task.show.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet"> @stop @section('content')
<h3 class="page-title">Szczegóły zadania</h3>
<div class="row">
	<div class='col-md-12 col-lg-6'>
		<div class="portlet light bordered">
			<div class="portlet-title">
				<div class="caption">
					<i class="fa fa-info-circle font-grey-mint"></i>
					<span class="caption-subject font-grey-mint bold uppercase">Zadanie</span>
				</div>
			</div>
			<div class="portlet-body form-horizontal">
				<div class="form-body">
					<div class="form-group">
						<label class="control-label col-sm-2">Nazwa:</label>
						<div class="col-sm-10">
							<p class="form-control-static">{{$task->name}}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">Lokalizacja:</label>
						<div class="col-sm-10">
							<p class="form-control-static">{{$task->location}}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">Opis:</label>
						<div class="col-sm-10">
							<p class="form-control-static">{{$task->description}}</p>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-2">Status:</label>
						<div class="col-sm-10">
							<p id="status"class="form-control-static">{{$task->status_string}}</p>
						</div>
					</div>
					@if($task->status != \App\Enums\TaskStatus::Zakończone && !Auth::user()->hasPermissionTo('update task'))
						@if(!$task->users->contains(\Auth::user()))
						<div class="form-group" id="button_placeholder">
							<button id="reserveButton" class="btn btn-primary btn-block">Rezerwacja</button>
						</div>
						@endif
						@if($task->status == \App\Enums\TaskStatus::W_trakcie_wykonywania || $task->status == \App\Enums\TaskStatus::Do_poprawy)
							<div class="form-group">
								<button data-url="{!!route('task.forwardToCheck', ['id' => $task->id])!!}" id="finishButton" class="btn btn-primary btn-block">Zakończ zadanie</button>
							</div>
						@endif
					@endif
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12 col-lg-6">
		<div class="panel panel-primary">
			<div class="panel-heading" id="accordion">
				<span class="glyphicon glyphicon-comment"></span> Komentarze
			</div>
			<div class="panel" id="collapseOne">
				<div class="panel-body">
					<ul class="chat">
					</ul>
				</div>
				@if($task->status != \App\Enums\TaskStatus::Zakończone)
				<div class="panel-footer">
					{!! Form::open(array('id' => 'comment-form', 'url' => $postAction, 'method'=>$actionMethod,'class'=>'form-horizontal')) !!}
					{!! Form::hidden('task_id',$task->id,['id' => 'task_id'])!!}
					<div class="input-group">
						{!! Form::text('content', null, ['id' => 'content','class' => 'form-control input-sm', 'autocomplete' =>'off', 'spellcheck'=>'true'])
						!!}
						<span class="input-group-btn">
							{!! Form::submit('Wyślij', ['class'=>'btn btn-warning btn-sm', 'id' => 'btn-chat']) !!}
						</span>
					</div>
					{!! Form::close() !!}
				</div>
				@endif
			</div>
		</div>
	</div>
</div>
		@if(isset($task) && count($task->images)> 0)
		<div class="row">
		<div class='col-sm-12'>
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-image font-grey-mint"></i>
						<span class="caption-subject font-grey-mint bold uppercase">Zdjęcia</span>
					</div>
				</div>
				<div class="portlet-body">
					<div class='row'>
						@foreach($task->images as $index=>$image)
						<div class="col-sm-3 col-xs-6">
							<img src='{{asset($image->webPath())}}' class='img-responsive thumbnail'>
							<a class="image-download-button btn red btn-xs" data-toggle="tooltip" data-title="Pobierz plik" href="{{route('task.download_image',['id' => $image->id])}}"
							 type="button" type="button">
								<span class="red">
									<i class="fa fa-download"></i>
								</span>
							</a>
						</div>
						@if(($index + 1) % 4 == 0)
						<div class='clearfix visible-lg visible-md visible-sm'></div>
						@endif @endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif
	<div class="row">
		<div class='col-sm-12'>
			<div class="portlet light bordered">
				<div class="portlet-title">
					<div class="caption">
						<i class="fa fa-image font-grey-mint"></i>
						<span class="caption-subject font-grey-mint bold uppercase">Pobrane przedmioty</span>
					</div>
				</div>
				<div class="portlet-body">
						<div class="table-responsive">
								<table class="table">
										<thead>
											<th>Nazwa</th>
											<th>Typ</th>
											<th>Ilość</th>
										</thead>
										<tbody>
	@if(count($task->getItems()) > 0)
		
							@foreach($task->getItems() as $item)
								<tr>
									<td>{{$item['name']}}</td>
									<td>{{$item['type']}}</td>
									<td>{{$item['amount']}}</td>
								</tr>
							@endforeach
							@endif
						</tbody>
				</table>
			</div>
			<div class="row">
				<div class="col-sm-6 col-sm-offset-3 col-xs-12">
			<a class="btn btn-primary btn-block" href="{!!route('warehouse_document.create_for_task', ['id' => $task->id])!!}">Zarządzaj przedmiotami</a>
				</div>
			</div>
		</div>
	</div>
	</div>
</div>
	@stop @section('page-js')
	<script src="{{ asset('assets/global/scripts/jquery.tmpl.js')}}" type="text/javascript"></script>
	<script src="{{asset('assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js')}}" type="text/javascript"></script>
	<script src="{{ asset('assets/global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
	<script src="{{ asset('assets/global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
	<script src="{{ asset('assets/global/plugins/datatables-responsive/dataTables.responsive.min.js')}}" type="text/javascript"></script>
	@include('task.partials.show-page-scripts') 
	@stop