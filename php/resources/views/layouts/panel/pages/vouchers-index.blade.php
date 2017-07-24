@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">
    Vouchers
    <div class="btn-group pull-right">
        <a class="btn btn-default" href="{{ url('/panel/vouchers/create') }}">
            <em class="glyphicon glyphicon-plus"> </em> 
            New
        </a>
    </div>
</h4>
<hr>
@include('layouts.panel.blocks.alerts')
<div class="row">
    <div class="col-md-12">
        {{ Form::open(['class' => 'form', 'method' => 'GET']) }}
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('id', 'Id') }}
                    {{ Form::number('id', null, ['placeholder' => 'Id', 'class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('name', 'Name') }}
                    {{ Form::text('name', null, ['placeholder' => 'Name', 'class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('parent_id', 'Parent category') }}
                    {{ Form::select('parent_id', App\Models\Category::hierarchicalSelectOptions(), null, ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::submit('Filter', ['class' => 'btn btn-primary']) }}
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        @include('layouts.panel.blocks.list-vouchers', ['rows' => $rows])
    </div>
</div>
@endsection