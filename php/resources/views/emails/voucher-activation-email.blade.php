<div>
    <h4>Hello, {{ $voucher->activation_email }},</h4>
    <p>
        Please follow the link down below to activate the voucher:
        <br>
        {!! Html::link(env('ZUIDHORN_URL_CITIZEN') . '/activate-voucher/' . $voucher->activation_token, 'Click here to activate your voucher.', ['target' => '_blank']) !!}
    </p>
</div>