<!DOCTYPE html>
<html>
<head>
<title>404</title>
<link href="{{asset('assets/global/css/plugins.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/pages/css/error.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('assets/global/css/components.min.css')}}" rel="stylesheet" id="style_components" type="text/css" />
</head>
<body class="page-404-full-page">
    <div class="row">
        <div class="col-md-12 page-404">
          <div class="number font-red"> 404 </div>
          <div class="details">
              <p> Nie znaleziono strony.
              </br><a href="{{URL::previous()}}"> Powróć do poprzedniej strony</a>
          </p>
          </div>
        </div>
    </div>
</body>
</html>