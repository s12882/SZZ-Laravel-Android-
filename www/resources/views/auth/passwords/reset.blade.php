@extends('layouts.auth') @section('content')
<div class="row">
	<div class="logo">
		<a href="index.html">
			<!-- LOGO -->
		</a>
	</div>
	<div class='content'>
		<form class="password-reset-form" method="POST" action="{{ route('password.request') }}">
			{{ csrf_field() }}
			<h3 class="form-title font-green">Resetowanie hasła</h3>
			@if (session('status'))
			<div class="alert alert-success">
				{{ session('status') }}
			</div>
			@endif @if (session('message'))
			<div class="alert alert-danger">
				<button class="close" data-close="alert"></button>
				<span> {!! session('message') !!} </span>
			</div>
			@endif
			<input type="hidden" name="token" value="{{ $token }}">
			<div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
				<div class="input-group">
					<span class="input-group-addon">
						<i class="glyphicon glyphicon-envelope"></i>
					</span>
					<input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required="required"
					 placeholder="E-mail" autofocus>
				</div>
				@if ($errors->has('email'))
				<span class="help-block">
					<strong>{{ $errors->first('email') }}</strong>
				</span>
				@endif
			</div>
			<div class="form-group { $errors->has('password') ? ' has-error' : '' }}">
				<div class="input-group">
					<span class="input-group-addon">
						<i class="glyphicon glyphicon-lock"></i>
					</span>
					<label class="control-label visible-ie8 visible-ie9">Hasło</label>
					<input id="password" type="password" class="form-control" name="password" required="required" placeholder="Hasło">
				</div>
				@if ($errors->has('password'))
				<span class="help-block">
					<strong>{{ $errors->first('password') }}</strong>
				</span>
				@endif
			</div>
			<div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
				<div class="input-group">
					<span class="input-group-addon">
						<i class="glyphicon glyphicon-lock"></i>
					</span>
					<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required="required" placeholder="Potwierdzenie hasła">
				</div>
				@if ($errors->has('password_confirmation'))
				<span class="help-block">
					<strong>{{ $errors->first('password_confirmation') }}</strong>
				</span>
				@endif
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