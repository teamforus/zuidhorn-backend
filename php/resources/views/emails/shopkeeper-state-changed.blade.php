<p>
    Geachte heer/mevrouw,
    <br>
    <br>



    
    @if($state == "approved")


    De belangrijke wijziging die u heeft aangebracht aan uw winkeliers profiel is succesvol gevalideerd.
    <br>
    Update uw profiel via: www.winkelier.forus.io 
    <br>
    

    @elseif($state == "pending")
    

    Uw winkeliers profiel wordt momenteel gevalideerd. Zodra deze status is gewijzigd ontvangt u een bericht.
    <br>
    Mocht u een nadere toelichting willen ontvangen dan kan u ons contacteren via 
    {!! Html::mailto("kindpakket@zuidhorn.nl", "kindpakket@zuidhorn.nl") !!}
    

    @elseif($state == "declined")
    

    Uw bent helaas niet geaccepteerd om deel te nemen als winkelier aan de kindpakket regeling.
    <br>
    Mocht u een nadere toelichting willen ontvangen dan kan u ons contacteren via 
    {!! Html::mailto("kindpakket@zuidhorn.nl", "kindpakket@zuidhorn.nl") !!}
    

    @endif




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