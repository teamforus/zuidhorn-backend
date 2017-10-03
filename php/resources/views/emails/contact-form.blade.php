<h2>Contact form</h2>
<hr>
@foreach($form as $form_key => $form_control)
<p>
    <strong>{{ ucfirst(str_replace('_', ' ', $form_key)) }}:</strong>
    {{ $form_control }}
</p>
@endforeach