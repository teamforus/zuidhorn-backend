@extends("layouts.{$view_layout}.{$view_layout}_layout")

@section('content')
<h4 class="heading-title">{{ 'View category: ' . $view->name }}</h4>
<hr>
{{ Form::open(['method' => 'POST', 'class' => 'form']) }}
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
</div>
{{ Form::close() }}
@endsection