<div>
    <p>
        Geachte heer/mevrouw,
        <br>
        <br>



        U heeft gebruik gemaakt van uw kindpakket budget. 
        <br>
        U heeft een bedrag van €{{ number_format($transaction->amount, 2) }}. van uw budget besteed bij {{ $transaction->shop_keeper->name }}.
        <br>
        Uw kindpakket budget bedraagt nu nog €{{ number_format($transaction->voucher->getAvailableFunds(), 2) }}.
        <br>
        Bekijk uw account op: www.zuidhorn.nl/kindpakket



        <br>
        <br>
        Met vriendelijke groet,
        <br>
        <br>
        M.E. Bolwijn, coördinerend juridisch medewerker gemeente Zuidhorn
        <br>
        Telefoonnummer: (0594) 508818
        <br>
        E-mailadres: {!! Html::mailto('kindpakket@zuidhorn.nl', 'kindpakket@zuidhorn.nl') !!}
    </p>
</div>