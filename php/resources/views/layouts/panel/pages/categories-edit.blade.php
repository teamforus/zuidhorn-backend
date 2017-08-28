@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">
    {{ $edit ? 'Edit category: ' . $edit->name : 'Add category' }}
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
{{ Form::open(['method' => $edit ? 'PATCH' : 'POST', 'class' => 'form', 'url' => ($edit ? "/panel/categories/{$edit->id}" : '/panel/categories/'), 'files' => true]) }}
<div class="row">
    <div class="col-md-4 col-xs-12">
        <div class="form-group">
            {{ Form::label('name', 'Category name') }}
            {{ Form::text('name', $edit ? $edit->name : '', ['class' => 'form-control', 'placeholder' => 'Name']) }}
            {!! $errors->first('name', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            {{ Form::label('image', 'Category photo') }}
            {{ Form::file('image', ['class' => 'form-control', 'accept' => 'image/*']) }}
            {!! $errors->first('image', '<p class="text-danger">:message</p>') !!}
        </div>
        <div class="form-group">
            <label class="form-label">Parent category:</label>
            {{ Form::select('parent_id', App\Models\Category::hierarchicalSelectOptions(true, $edit ? $edit->id : false), $edit ? $edit->parent_id : '', ['class' => 'form-control']) }}
            {!! $errors->first('parent_id', '<p class="text-danger">:message</p>') !!}
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