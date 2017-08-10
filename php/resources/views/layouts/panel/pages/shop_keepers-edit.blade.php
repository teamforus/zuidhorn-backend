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
{{ Form::open(['method' => $edit ? 'PATCH' : 'POST', 'class' => 'form', 'url' => $edit ? '/panel/shop-keepers/' . $edit->id : '/panel/shop-keepers']) }}
<div class="row">
    <div class="col-md-4 col-xs-12">
        <div class="form-group">
            {{ Form::label('first_name', 'First name') }}
            {{ Form::text('first_name', $edit ? $edit->user->first_name : '', ['class' => 'form-control', 'placeholder' => 'First name']) }}
            {!! $errors->first('first_name', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('last_name', 'Last name') }}
            {{ Form::text('last_name', $edit ? $edit->user->last_name : '', ['class' => 'form-control', 'placeholder' => 'Last name']) }}
            {!! $errors->first('last_name', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('email', 'E-mail') }}
            {{ Form::email('email', $edit ? $edit->user->email : '', ['class' => 'form-control', 'placeholder' => 'E-mail']) }}
            {!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-xs-12">
        <h4>Shop Keeper details</h4>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-xs-12">
        <div class="form-group">
            {{ Form::label('name', 'Name') }}
            {{ Form::text('name', $edit ? $edit->name : '', ['class' => 'form-control', 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('iban', 'IBAN') }}
            {{ Form::text('iban', $edit ? $edit->iban : '', ['class' => 'form-control', 'placeholder' => 'Name']) }}
            {!! $errors->first('iban', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('kvk_number', 'KVK number') }}
            {{ Form::text('kvk_number', $edit ? $edit->kvk_number : '', ['class' => 'form-control', 'placeholder' => 'KVK number']) }}
            {!! $errors->first('kvk_number', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('phone_number', 'Phone number') }}
            {{ Form::text('phone_number', $edit ? $edit->phone_number : '', ['class' => 'form-control', 'placeholder' => 'Phone number']) }}
            {!! $errors->first('phone_number', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('bussines_address', 'Bussines address') }}
            {{ Form::text('bussines_address', $edit ? $edit->bussines_address : '', ['class' => 'form-control', 'placeholder' => 'Bussines address']) }}
            {!! $errors->first('bussines_address', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('state', 'Business validation') }}
            {{ Form::select('state', \App\Models\ShopKeeper::availableStates(), $edit ? $edit->state : '', 
            ['class' => 'form-control', 'placeholder' => 'Please select state']) }}
            {!! $errors->first('state', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-xs-12">
        <h4>Reset password</h4>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-xs-12">
        <div class="form-group">
            {{ Form::label('password', 'Password') }}
            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) }}
            {!! $errors->first('password', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('password_confirmation', 'Password confirmation') }}
            {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Password confirmation']) }}
            {!! $errors->first('password_confirmation', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>
</div>
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