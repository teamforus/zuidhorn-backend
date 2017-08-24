@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">
    {{ 'View Office: ' . $view->address }}
    <div class="btn-group pull-right">
        <a class="btn btn-primary" href="{{ $view->urlPanelEdit() }}">
            <em class="glyphicon glyphicon-edit"> </em> 
            Edit
        </a>
    </div>
</h4>
<hr>
@include('layouts.panel.blocks.alerts')
<div class="row">
    <div class="col-xs-12">
        <h4>Office details</h4>
        <hr>
        <p>
            <strong>ShopKeeper:</strong> 
            <em class="mdi mdi-link"></em> 
            {{ Html::link($view->shop_keeper->urlPanelView(), $view->shop_keeper->user->full_name, ['class' => 'text-primary']) }}.
            <br>
            <strong>Address:</strong> 
            <em class="mdi mdi-link"></em> 
            {{ Html::link($view->urlGoogleMap(), $view->address, ['target' => '__blank', 'class' => 'text-primary']) }}.
            <br>
            <strong>Longitude:</strong> 
            {{ $view->lon }}
            <br>
            <strong>Latitude:</strong> 
            {{ $view->lat }}
            <br>
            <strong>Original photo:</strong>
            <em class="mdi mdi-open-in-new"></em> 
            {{ Html::link($view->urlOriginal(), 'link', ['class' => 'text-primary', 'target' => '_blank']) }}
            <br>
            <strong>Preview photo:</strong>
            <br> 
            {{ Html::image($view->urlPreview(), '', ['class' => 'thumbnail']) }}
        </p>
        <br>
    </div>
</div>
@endsection