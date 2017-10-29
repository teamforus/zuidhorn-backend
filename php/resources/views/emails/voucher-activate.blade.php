<div>
    <h4>Hello, {{ $voucher->user->email }},</h4>
    @if($password)
    <p>Your password is: <strong>{{ $password }}</strong>.</p>
    @endif
    <p>Your public key is: <strong>{{ $voucher->wallet->address }}</strong>.</p>
    <p>
        QR-Code:
        <br>
        <img src="{!!$message->embedData(QrCode::format('png')->margin(1)->size(300)->generate($voucher->wallet->address), 'QrCode.png', 'image/png')!!}">
    </p>
</div>