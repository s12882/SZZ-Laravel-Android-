@inject('type', 'App\Enums\DtButtonType')
@if($buttonType == $type::EDIT)
<a class="btn btn-xs blue" href="{{$action}}" data-toggle="tooltip" data-title="Edytuj obiekt"><i class="fa fa-edit"></i></a>
@elseif($buttonType == $type::SHOW)
<a class="btn btn-xs grey-mint" href="{{$action}}" data-toggle="tooltip" data-title="Wyświetl szczegóły"><i class="fa fa-search"></i></a>
@elseif($buttonType == $type::DELETE)
<a class="btn btn-xs red" href="{{$action}}" data-action="delete" data-toggle="tooltip" data-title="Usuń obiekt"><i class="fa fa-close"></i></a>
@elseif($buttonType == $type::ACTIVATE)
<a class="btn btn-xs green" href="{{$action}}" data-toggle="tooltip" data-title="Oznacz jako aktywny"><i class="fa fa-check-square-o"></i></a>
@elseif($buttonType == $type::DEACTIVATE)
<a class="btn btn-xs yellow" href="{{$action}}" data-toggle="tooltip" data-title="Oznacz jako nieaktywny"><i class="fa fa-minus-square-o"></i></a>
@elseif($buttonType == $type::FILES)
<a class="btn btn-xs grey-mint" href="{{$action}}" data-toggle="tooltip" data-title="Pokaż pliki"><i class="fa fa-files-o"></i></a>
@elseif($buttonType == $type::CREATE)
<a class="btn btn-xs green" href="{{$action}}" data-toggle="tooltip" data-title="Pokaż pliki"><i class="fa fa-plus"></i></a>
@endif