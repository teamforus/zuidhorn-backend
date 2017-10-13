@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">
    {{ $edit ? 'Edit budget: ' . $edit->name : 'Add budget' }}
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
{{ Form::open(['method' => $edit ? 'PATCH' : 'POST', 'class' => 'form', 'url' => $edit ? '/panel/budgets/' . $edit->id : '/panel/budgets']) }}
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {{ Form::label('name', 'Category name') }}
            {{ Form::text('name', $edit ? $edit->name : '', ['class' => 'form-control', 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            {{ Form::label('amount_per_child', 'Amount per child') }}
            {{ Form::number('amount_per_child', $edit ? $edit->amount_per_child : '', 
            ['class' => 'form-control', 'placeholder' => 'Amount per child', 'min' => 0]) }}
            {!! $errors->first('amount_per_child', '<p class="text-danger">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-12">
        <hr>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            {{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
        </div>
    </div>
</div>
{{ Form::close() }}
@endsection