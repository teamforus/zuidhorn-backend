@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">
    Bugets
    <div class="btn-group pull-right">
        <a class="btn btn-default" href="{{ url('/panel/bugets/create') }}">
            <em class="glyphicon glyphicon-plus"> </em> 
            New
        </a>
    </div>
</h4>
<hr>
@include('layouts.panel.blocks.alerts')
<div class="row">
    <div class="col-md-12">
        @include('layouts.panel.blocks.list-bugets', ['rows' => $rows])
    </div>
</div>
@endsection