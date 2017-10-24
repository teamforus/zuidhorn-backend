@if($state == "approved")
<p>Dag,</p>
<p>
    U bent gevalideerd en kunt nu scannen.
    <br>
    Update uw profiel via: www.winkelier.forus.io 
    <br>
</p>
<p>
    Groet,
    <br>
    Zuidhorn
</p>
@elseif($state == "pending")
<p>Hi, your shopkeeper state have been changed to "pending".</p>
@elseif($state == "declined")
<p>Hi, your shopkeeper sign up request was declined.</p>
@endif