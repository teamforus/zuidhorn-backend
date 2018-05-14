<div>
    <p>
        Geachte heer/mevrouw,
        <br>
        <br>



        Zojuist heeft u uw Kindpakket account succesvol geactiveerd.
        <br>
        Onderstaand treft u een QR-code aan.
        <br>
        <img src="{!!$message->embedData(QrCode::format('png')->margin(1)->size(300)->generate($voucher->wallet->address), 'QrCode.png', 'image/png')!!}">
        <br>
        Uw huidige budget is: €{{ number_format($voucher->getAvailableFunds(), 2) }}
        <br>
        Met deze QR-code kunt u uw kindpakket budget bij diverse winkels besteden.
        <br>
        Print hem uit of laat hem zien op uw telefoon.
        <br>
        De QR-code zal gescand worden door de winkelier en het te betalen bedrag wordt afgeschreven van uw budget.
        <br>
        Een overzicht van alle deelnemende winkels vindt u op: 
        {{ Html::link(env('ZUIDHORN_URL_CITIZEN'), env('ZUIDHORN_URL_CITIZEN')) }} 
        <br>
        Wij wensen u veel plezier toe!



        <br>
        <br>
        Met vriendelijke groet,
        <br>
        namens burgemeester en wethouders van de gemeente Zuidhorn,
        <br>
        <br>
        M.E. Bolwijn, coördinerend juridisch medewerker gemeente Zuidhorn
        <br>
        Telefoonnummer: (0594) 508818
        <br>
        E-mailadres: {!! Html::mailto('kindpakket@zuidhorn.nl', 'kindpakket@zuidhorn.nl') !!}
    </p>
</div>