<div>
    <h4>Hello, {{ $citizenToken->citizen->user->email }},</h4>
    <p>
        Please follow the link down below to sign in:
        <br>
        {!! Html::link(env('ZUIDHORN_URL_CITIZEN') . '/sign-in/' . $citizenToken->token, 'Click here to sign in.', ['target' => '_blank']) !!}
    </p>
</div>