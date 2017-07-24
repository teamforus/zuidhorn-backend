@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">{{ 'View category: ' . $view->name }}</h4>
<hr>
<div class="row">
    <div class="col-xs-12">
        <h4>Base details</h4>
        <hr>
        <p>
            Full name: <strong class="text-primary">{{ $view->full_name }}</strong><br>
            E-mail: <strong class="text-primary">{{ $view->email }}</strong><br>
        </p>
        <br>

        <h4>User vouchers</h4>
        <hr>
        @if($view->vouchers->count() > 0)
        <div class="row">
            <div class="col-md-12">
                @include('layouts.panel.blocks.list-vouchers', ['rows' => $view->vouchers])
            </div>
        </div>
        @else
        <p>
            <span class="text-center">No vouchers.</span>
        </p>
        @endif
    </div>
</div>
@endsection