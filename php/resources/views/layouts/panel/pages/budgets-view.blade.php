@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">
    {{ 'View budget: ' . $view->name }}
    <div class="btn-group pull-right">
        <a class="btn btn-primary" href="{{ $view->urlPanelEdit() }}">
            <em class="glyphicon glyphicon-edit"> </em> 
            Edit
        </a>
    </div>
</h4>
<hr>
<div class="row">
    <div class="col-xs-12">
        <h4>Base details</h4>
        <hr>
        <p>
            Budget: <strong class="text-primary">{{ $view->name }}</strong><br>
            Amount per child: <strong class="text-primary">€{{ number_format($view->amount_per_child, 2) }}</strong><br>
        </p>
        <br>
</div>
<div class="row" id="budget-categories">
    <div class="col-md-12">
        <h4>Budget categories</h4>
        <hr>
        {{ Form::open(['class' => 'form', 'method' => 'POST', 'url' => '/panel/budget-categories']) }}
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <?php 
                    $categoryOptions = collect(App\Models\Category::hierarchicalSelectOptions())->filter(function($val, $key) use ($view) {
                        return $view->categories->pluck('id')->search($key) === FALSE;
                    });
                    ?>
                    {{ Form::label('category_id', 'Category') }}
                    {{ Form::select('category_id', $categoryOptions, null, ['class' => 'form-control']) }}
                    {!! $errors->first('category_id', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::hidden('budget_id', $view->id) }}
                    {{ Form::submit('Add category', ['class' => 'btn btn-primary']) }}
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        @include('layouts.panel.blocks.list-budget_categories', ['rows' => $view->budget_categories])
    </div>
</div>
@endsection