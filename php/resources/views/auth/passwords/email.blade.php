@extends('layouts.auth.auth_layout')

@section('content')
<div class="login-body">
    <article class="container-login center-block">
        <section>
            <ul class="nav nav-tabs nav-justified">
                <li class="active">{{ Html::link(route('login'), 'Login') }}</li>
                <li>{{ Html::link(route('register'), 'Shoper registration') }}</li>
            </ul>
            <div class="tab-content tabs-login col-lg-12 col-md-12 col-sm-12 cols-xs-12">
                <div class="tab-pane fade active in">
                    {!! Form::open(['url' => route('password.email'), 'class' => 'form-horizontal clearfix']) !!}
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="col-xs-12">
                        <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                            {!! Form::label('email', 'E-mail', ['class' => 'sr-only']) !!}
                            {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'E-mail']) !!}
                            {!! $errors->first('email', '<div class="text-danger">:message</div>') !!}
                        </div>
                        <div class="row">
                            {!! Form::submit('Send Password Reset Link', ['class' => 'btn btn-lg btn-primary']) !!}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </section>
    </article>
</div>