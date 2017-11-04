<div>
    <p>
        Geachte heer/mevrouw,
        <br>
        <br>



        U heeft aangegeven te willen inloggen op uw Kindpakket account. Dit kan door te klikken op de onderstaande knop.
        <br>
        <br>
        {!! Html::link(env('ZUIDHORN_URL_CITIZEN') . '/sign-in/' . $citizenToken->token, 'Log in op uw account', ['target' => '_blank']) !!}
        <br>
        <br>
        Deze inlog knop is 15 minuten geldig.
        <br>
        Mocht u niet hebben geprobeerd in te loggen dan kunt u deze mail veilig negeren.



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