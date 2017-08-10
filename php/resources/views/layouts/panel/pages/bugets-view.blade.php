@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">
    {{ 'View buget: ' . $view->name }}
    <div class="btn-group pull-right">
        <a class="btn btn-primary" href="{{ $view->urlPanelEdit() }}">
            <em class="glyphicon glyphicon-edit"> </em> 
            Edit
        </a>
    </div>
</h4>
<hr>
<div class="row">
    <div class="col-xs-12">
        <h4>Base details</h4>
        <hr>
        <p>
            Buget: <strong class="text-primary">{{ $view->name }}</strong><br>
            Amount per child: <strong class="text-primary">€{{ number_format($view->amount_per_child, 2) }}</strong><br>
        </p>
        <br>
</div>
@endsection