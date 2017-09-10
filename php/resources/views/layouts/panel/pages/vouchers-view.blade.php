@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">{{ 'View category: ' . $view->name }}</h4>
<hr>
<div class="row">
    <div class="col-xs-12">
        <h4>Voucher qr-code:</h4>
        <hr>
        @if($view->public_key)
        <p class="text-center">
            <div class="qr-code">
                {!! QrCode::backgroundColor(255,255,255)->margin(1)->size(300)->generate($view->public_key) !!}
            </div>
        </p>
        @endif
        <p>
            Activation code: <strong class="text-primary">{{ $view->code }}</strong><br>
            Public key: 
            <strong class="{{ $view->public_key ? "text-primary" : "" }}">
                {{ $view->public_key ? $view->public_key : "Available after activation" }}
            </strong>
            <br>
            Created at: <strong class="text-primary">{{ $view->created_at->format('M d, Y H:i') }}</strong><br>
            Status: <strong class="text-primary">{{ strtoupper($view->status) }}</strong><br>
            Funds available: <strong>€{{ number_format($view->getAvailableFunds(), 2) }}</strong>
        </p>
        <br>

        <h4>Voucher transactions</h4>
        <hr>
        @if($view->transactions->count() > 0)
        <div class="row">
            <div class="col-md-12">
                @include('layouts.panel.blocks.list-transactions', ['rows' => $view->transactions])
            </div>
        </div>
        @else
        <p>
            <span class="text-center">No transactions.</span>
        </p>
        @endif
    </div>
</div>
@endsection