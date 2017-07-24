@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">Import CSV file</h4>
<hr>
<div ng-controller="CSVParserCtrl">
    <div csv-parser></div>
</div>
@endsection