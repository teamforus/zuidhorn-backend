@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">
    {{ 'View category: ' . $view->name }}
    <div class="btn-group pull-right">
        <a class="btn btn-primary" href="{{ $view->urlPanelEdit() }}">
            <em class="glyphicon glyphicon-edit"> </em> 
            Edit
        </a>
    </div>
</h4>
</h4>
<hr>
<div class="row">
    <div class="col-xs-12">
        <h4>Base details</h4>
        <hr>
        <p>
            Category: <strong class="text-primary">{{ $view->name }}</strong><br>
            Parent category: 
            @if($view->parent_id)
            <strong>{{ Html::link($view->parent->urlPanelView(), $view->parent->name, ['class' => 'text-primary']) }}</strong>
            @else
            <span class="text-muted">No parent</span>
            @endif
            <br>
            <strong>Original photo:</strong>
            <em class="mdi mdi-open-in-new"></em> 
            {{ Html::link($view->urlOriginal(), 'link', ['class' => 'text-primary', 'target' => '_blank']) }}
            <br>
            <strong>Preview photo:</strong>
            <br> 
            {{ Html::image($view->urlPreview(), '', ['class' => 'thumbnail']) }}
        </p>
        <br>

        <h4>Direct child categories</h4>
        <hr>
        @if($view->childs->count() > 0)
        <div class="row">
            <div class="col-md-12">
                @include('layouts.panel.blocks.list-categories', ['rows' => $view->childs])
            </div>
        </div>
        @else
        <p>
            <span class="text-center">No child categories.</span>
        </p>
        @endif
    </div>
    <div class="col-md-12">
        <h4>Shop Keepers</h4>
        <hr>
        @include('layouts.panel.blocks.list-shop_keepers', ['rows' => $view->shop_keepers, 'no_actions' => true])
    </div>
    <div class="col-md-12">
        <h4>Bugets</h4>
        <hr>
        @include('layouts.panel.blocks.list-bugets', ['rows' => $view->bugets, 'no_actions' => true])
    </div>
</div>
@endsection