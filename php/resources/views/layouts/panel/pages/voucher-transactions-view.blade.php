@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">{{ 'View category: ' . $view->name }}</h4>
<hr>
<div class="row">
    <div class="col-xs-12">
        <h4>Transaction details:</h4>
        <hr>
        <pre>{!! $view->transactionDetails() !!}</pre>
        <br>
    </div>
</div>
@endsection