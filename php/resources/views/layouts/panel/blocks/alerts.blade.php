@if (session()->has('alert_default'))
<div class="alert alert-md alert-success">{{ session()->get('alert_default') }}</div>
@endif

@if (session()->has('alert_danger'))
<div class="alert alert-md alert-default">{{ session()->get('alert_danger') }}</div>
@endif

@if (session()->has('alert_warning'))
<div class="alert alert-md alert-default">{{ session()->get('alert_warning') }}</div>
@endif