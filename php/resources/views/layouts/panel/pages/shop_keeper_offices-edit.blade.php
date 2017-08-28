@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">
    {{ $edit ? 'Edit shop-keeper: ' . $edit->name : 'Add shop-keeper' }}
    @if($edit)
    <div class="btn-group pull-right">
        <a class="btn btn-primary" href="{{ $edit->urlPanelView() }}">
            <em class="glyphicon glyphicon-eye-open"> </em> 
            View
        </a>
    </div>
    @endif
</h4>
<hr>
@include('layouts.panel.blocks.alerts')
{{ Form::open(['method' => $edit ? 'PATCH' : 'POST', 'class' => 'form', 'url' => ($edit ? "/panel/shop-keepers/{$shopKeeper->id}/offices/{$edit->id}" : "/panel/shop-keepers/{$shopKeeper->id}/offices"), 'files' => true]) }}
<div class="row">
    <div class="col-md-4 col-xs-12">
        <div class="form-group">
            {{ Form::label('address', 'Office address') }}
            {{ Form::text('address', $edit ? $edit->address : '', ['class' => 'form-control', 'placeholder' => 'Office address']) }}
            {!! $errors->first('address', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 col-xs-12">
        <div class="form-group">
            {{ Form::label('image', 'Office photo') }}
            {{ Form::file('image', ['class' => 'form-control', 'accept' => 'image/*']) }}
            {!! $errors->first('image', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>
</div>

@if($edit)
<div class="row">
    <div class="col-md-4 col-xs-12">
        <div class="form-group">
            {{ Form::label('lon', 'Longitude') }}
            {{ Form::text('lon', $edit ? $edit->lon : '', ['class' => 'form-control', 'placeholder' => 'Office longitude']) }}
            {!! $errors->first('lon', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-xs-12">
        <div class="form-group">
            {{ Form::label('lat', 'Latitude') }}
            {{ Form::text('lat', $edit ? $edit->lat : '', ['class' => 'form-control', 'placeholder' => 'Office latitude']) }}
            {!! $errors->first('lat', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-xs-12"><hr></div>
    <div class="col-md-4 col-xs-12">
        <div class="form-group">
            {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
        </div>
    </div>
</div>
{{ Form::close() }}
@endsection