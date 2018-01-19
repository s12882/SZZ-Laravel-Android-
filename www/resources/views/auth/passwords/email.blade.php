@extends('layouts.auth') @section('content')
<div class="row">
	<div class="logo">
		<a href="index.html">
			<!-- LOGO -->
		</a>
	</div>
	<div class='content'>

		<form class="password-reset-form" method="POST" action="{{ route('password.email') }}">
			{{ csrf_field() }}
			<h3 class="form-title font-green">Resetowanie hasła</h3>
			@if (session('status'))
			<div class="alert alert-success">
				{{ session('status') }}
			</div>
			@endif @if ($errors->has('email'))
			<span class="help-block">
				<div class="alert alert-danger">
					<button class="close" data-close="alert"></button>
					<span> {{ $errors->first('email') }} </span>
				</div>
			</span>
			@endif
			<div class="form-group">
				<div class="input-group">
					<span class="input-group-addon">
						<i class="glyphicon glyphicon-envelope"></i>
					</span>
					<input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required="required" placeholder="E-mail">
				</div>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-lg btn-block blue-dark uppercase">Resetuj Hasło</button>
			</div>

		</form>
	</div>
	<div class="copyright">
	</div>
</div>
@endsection