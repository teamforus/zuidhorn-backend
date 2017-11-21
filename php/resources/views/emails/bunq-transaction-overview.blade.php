<h2>Bunq: transaction overview.</h2>
<hr>
@if($transactions->count() == 0)
<p>No transactions today.</p>
@else
<table style="border-collapse: collapse; border-spacing: 0; color: #666;">
    <thead>
        <tr>
            @foreach(collect($transactions->first()->makeVisible(['payment_id', 'voucher_id', 'shop_keeper_id', 'last_attempt_at', 'attempts']))->keys() as $key)
            <th style="padding: 15px 15px; border-bottom: 1px solid #eff0f1;">{{ $key }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
        <tr>
            @foreach(collect($transaction->makeVisible(['payment_id', 'voucher_id', 'shop_keeper_id', 'last_attempt_at', 'attempts'])) as $value)
            <td style="padding: 15px 15px; border-bottom: 1px solid #eff0f1;">{{ $value }}</td>
            @endforeach
        </tr>
        @endforeach
    </tbody>
</table>
@endif