@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">
    {{ 'View Shop Keeper: ' . $view->name }}
    <div class="btn-group pull-right">
        <a class="btn btn-primary" href="{{ $view->urlPanelEdit() }}">
            <em class="glyphicon glyphicon-edit"> </em> 
            Edit
        </a>
    </div>
</h4>
<hr>
@include('layouts.panel.blocks.alerts')
<div class="row">
    <div class="col-xs-12">
        <h4>User details</h4>
        <hr>
        <p>
            <strong>First name:</strong> {{ $view->user->first_name }}
            <br>
            <strong>Last name:</strong> {{ $view->user->last_name }}
            <br>
            <strong>E-mail:</strong> {{ $view->user->email }}
        </p>
        <br>
        <h4>Shop details</h4>
        <hr>
        <p>
            <strong>Shop name:</strong> {{ $view->name }}
            <br>
            <strong>IBAN:</strong> {{ $view->iban }}
            <br>
            <strong>KVK number:</strong> {{ $view->iban }}
            <br>
            <strong>Phone number:</strong> {{ $view->phone_number }}
            <br>
            <strong>Bussines address:</strong> {{ $view->bussines_address }}
            <br>
            <strong>Status:</strong>
            <strong class="{{ @["declined" => "text-danger", "approved" => "text-success"][strtolower($view->state)] }}">
                {{ ucfirst(strtolower($view->state)) }}
            </strong>
        </p>
        <br>
    </div>
</div>

<!-- Shop Keeper Categories -->
<div class="row" id="shop_keeper-categories">
    <div class="col-md-12">
        <h4>Shop categories</h4>
        <hr>
        {{ Form::open(['class' => 'form', 'method' => 'POST', 'url' => '/panel/shop-keeper-categories']) }}
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
                    {{ Form::hidden('shop_keeper_id', $view->id) }}
                    {{ Form::submit('Add category', ['class' => 'btn btn-primary']) }}
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        @include('layouts.panel.blocks.list-shop_keeper_categories', ['rows' => $view->shop_keeper_categories])
    </div>
</div>
@if(($view->state != 'approved') && ($view->shop_keeper_categories->count() == 0))
<div class="row">
    <div class="col-md-12">
        <br>
        <div class="alert alert-default">Shop Keeper should have at least one category selected, in order to be approve.</div>
        <br>
    </div>
</div>
@elseif($view->state != 'approved')
<div class="row" id="shop_keeper-categories">
    <div class="col-md-12">
        <h4>Approve this Shop Keeper</h4>
        <hr>
        {{ Form::open(['class' => 'form', 'method' => 'PUT', 'url' => $view->urlPanelStateApprove()]) }}
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::submit('Approve', ['class' => 'btn btn-primary']) }}
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
</div>
@endif
<br>

<!-- Shop Keeper Offices -->
<div class="row" id="shop_keeper-categories">
    <div class="col-md-12">
        <h4>Shop offices</h4>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Html::link($view->urlPanelAddOffice(), 'Add office', ['class' => 'btn btn-primary']) }}
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        @include('layouts.panel.blocks.list-shop_keeper_offices', ['rows' => $view->shop_keeper_offices])
    </div>
</div>
@endsection
