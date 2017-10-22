@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">{{ 'View category: ' . $view->name }}</h4>
<hr>
<div class="row">
    <div class="col-xs-12">
        <h4>Voucher qr-code:</h4>
        <hr>
        @if($view->wallet->address)
        <p class="text-center">
            <div class="qr-code">
                {!! QrCode::backgroundColor(255,255,255)->margin(1)->size(300)->generate($view->wallet->address) !!}
            </div>
        </p>
        @endif
        <p>
            Activation code: <strong class="text-primary">{{ $view->code }}</strong><br>
            Address: 
            <strong class="{{ $view->wallet->address ? "text-primary" : "" }}">
                {{ $view->wallet->address ? $view->wallet->address : "Available after activation" }}
            </strong>
            <br>
            Created at: <strong class="text-primary">{{ $view->created_at->format('M d, Y H:i') }}</strong><br>
            Status: <strong class="text-primary">{{ $view->wallet ? 'Active' : 'Pending' }}</strong><br>
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