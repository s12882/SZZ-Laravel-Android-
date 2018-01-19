@if (session('success'))
    <div class="alert alert-success alert-success-stripe alert-dismissible margin-top-10" role="alert">
        <button type="button" class="close pull-right" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
<!--        <h4 class="block">@lang('actions.operation_success')</h4>-->
        {{ session('success') }}
    </div>
@endif
@if (session('info'))
    <div class="alert alert-info alert-info-stripe alert-dismissible margin-top-10" role="alert">
        <button type="button" class="close pull-right" data-dismiss="alert" aria-label="Close" style="color:#327ad5"><span aria-hidden="true">&times;</span></button>
        <h4 class="block">Informacja</h4>
        <strong>{{ session('info') }}</strong>
    </div>
@endif
@if(isset($errors) && count($errors) > 0)
    <div class="alert alert-danger alert-danger-stripe alert-dismissible margin-top-10" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="block">@lang('actions.operation_error')</h4>
        @if(is_array($errors))
            @foreach($errors as $e)
                <p>{{$e}}</p>
            @endforeach
        @else
            @foreach($errors->all() as $e)
                <p>{{$e}}</p>
            @endforeach
        @endif
    </div>
@endif
