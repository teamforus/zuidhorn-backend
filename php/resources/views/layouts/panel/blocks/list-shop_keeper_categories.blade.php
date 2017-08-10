@if($rows->count() > 0)
<table class="table table-striped">
    <tHead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Parent category</th>
            @if(!isset($no_actions) || !$no_actions)
            <th class="text-right">Actions</th>
            @endif
        </tr>
    </tHead>
    <tBody>
        @foreach($rows as $row)
        <tr>
            <td>
                <strong>
                    {{ Html::link($row->category->urlPanelView(), '#' . str_pad($row->category->id, 5, 0, STR_PAD_LEFT), 
                    ['class' => 'text-primary']) }}
                </strong>
            </td>
            <td>{{ Html::link($row->category->urlPanelView(), $row->category->name, ['class' => 'text-primary']) }}</td>
            <td>
                @if(!$row->category->parent_id)
                <span class="text-muted">No parent</span>
                @else 
                {{ Html::link($row->category->parent->urlPanelView(), $row->category->parent->name, ['class' => 'text-primary']) }}
                @endif
            </td>
            <td class="text-right">
                <div class="btn-group">
                    @can('delete', $row)
                    {{ Html::link($row->urlPanelDelete(), 'Delete', [
                        'confirm-box data-box-title'    => "Are you sure?", 
                        'data-box-text'                 => "This action cannot be undone, double check before continue."]) 
                    }}
                    @endcan
                </div>
            </td>
        </tr>
        @endforeach
    </tBody>
</table>

@if(method_exists($rows, 'render'))
@include('layouts.panel.blocks.pagination', ['paginator' => $rows])
@endif

@else
<hr>
<h6 class="text-center">List is empty.</h6>
@endif