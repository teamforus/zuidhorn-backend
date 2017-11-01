<div>
    <p>
        Geachte heer/mevrouw,
        <br>
        <br>


        
        U heeft uw activatiecode ingevoerd.
        <br>
        Door te klikken op de onderstaande knop zal uw account actief worden en krijgt u uw vouchercode per e-mail toegestuurd.
        <br>
        <br>
        {!! Html::link(env('ZUIDHORN_URL_CITIZEN') . '/activate-voucher/' . $voucher->activation_token, 'Activeer uw account.', ['target' => '_blank']) !!}        <br>
        <br>
        De informatie verzonden in dit e-mailbericht is uitsluitend bestemd voor de geadresseerde. Gebruik van deze informatie door anderen dan de geadresseerde is verboden. Indien u dit bericht ten onrechte ontvangt, wordt u verzocht de inhoud niet te gebruiken maar de afzender direct te informeren via onderstaande contact gegevens en het bericht daarna te verwijderen. Onrechtmatige openbaarmaking, besteding, vermenigvuldiging, verspreiding en/of verstrekking van de in de e-mail ontvangen informatie is niet toegestaan. 



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