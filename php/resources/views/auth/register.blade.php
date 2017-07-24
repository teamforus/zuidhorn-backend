@extends('layouts.auth.auth_layout')

@section('content')
<div class="login-body">
    <article class="container-login center-block">
        <section>
            <ul class="nav nav-tabs nav-justified">
                <li>{{ Html::link(route('login'), 'Login') }}</li>
                <li class="active">{{ Html::link(route('register'), 'Shoper registration') }}</li>
            </ul>
            <div class="tab-content tabs-login col-lg-12 col-md-12 col-sm-12 cols-xs-12">
                <div class="tab-pane fade active in">
                    {!! Form::open(['url' => route('register'), 'class' => 'form-horizontal clearfix']) !!}

                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                            {!! Form::label('first_name', 'First Name', ['class' => 'sr-only']) !!}
                            {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First Name']) !!}
                            {!! $errors->first('first_name', '<div class="text-danger">:message</div>') !!}
                        </div>
                        <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                            {!! Form::label('last_name', 'Last Name', ['class' => 'sr-only']) !!}
                            {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last Name']) !!}
                            {!! $errors->first('last_name', '<div class="text-danger">:message</div>') !!}
                        </div>
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            {!! Form::label('email', 'E-mail', ['class' => 'sr-only']) !!}
                            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'E-mail']) !!}
                            {!! $errors->first('email', '<div class="text-danger">:message</div>') !!}
                        </div>
                        <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                            {!! Form::label('password', 'Password', ['class' => 'sr-only']) !!}
                            {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) !!}
                            {!! $errors->first('password', '<div class="text-danger">:message</div>') !!}
                        </div>
                        <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            {!! Form::label('password_confirmation', 'Confirm Password', ['class' => 'sr-only']) !!}
                            {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm Password']) !!}
                            {!! $errors->first('password_confirmation', '<div class="text-danger">:message</div>') !!}
                        </div>
                        <div class="row">
                            {!! Form::submit('Register', ['class' => 'btn btn-lg btn-primary']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </section>
    </article>
</div>