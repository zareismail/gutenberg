<!DOCTYPE HTML>
<html lang="{{ app()->getLocale() }}">
<head> 
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <!-- Bootstrap CSS --> 
  {!! 
    // renders avaialble plugins that should renders in the header
    $plugins->filterForHead()->toHtml() 
  !!}  
</head>
<body 
  data-component="{{ data_get($component, 'uriKey') }}"  
  data-fragment="@isset($fragment){{ data_get($fragment, 'uriKey') }}@endisset" 
> 
  {!! 
    // renders avaialble widgets
    $widgets->toHtml()
  !!}  
  {!! 
    // renders avaialble plugins that should render in the footer
    $plugins->filterForBody()->toHtml() 
  !!} 
</body>
</html>