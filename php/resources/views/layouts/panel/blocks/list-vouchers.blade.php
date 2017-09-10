@if($rows->count() > 0)
<table class="table table-striped table-align-middle">
    <tHead>
        <tr>
            <th>Id</th>
            <th>Code</th>
            <th>Citizen</th>
            <th>Buget</th>
            <th>Funds available</th>
            <th class="text-right">Actions</th>
        </tr>
    </tHead>
    <tBody>
        @foreach($rows as $row)
        <tr>
            <td>
                <strong>
                    {{ Html::link($row->urlPanelView(), '#' . str_pad($row->id, 5, 0, STR_PAD_LEFT), 
                    ['class' => 'text-primary']) }}
                </strong>
            </td>
            <td>{{ Html::link($row->urlPanelView(), $row->code, ['class' => 'text-primary']) }}</td>
            <td>
                @if(is_null($row->user_id))
                <span class='text-muted'>Not activated</span>
                @else
                #{{ $row->user->id }}
                @endif
            </td>
            <td>{{ Html::link($row->buget->urlPanelView(), $row->buget->name, ['class' => 'text-primary']) }}</td>
            <td>€{{ number_format($row->getAvailableFunds(), 2) }}</td>
            <td class="text-right">
                <div class="btn-group">
                    @can('view', $row)
                    {{ Html::link($row->urlPanelView(), 'View') }} &nbsp; 
                    @endcan

                    @can('update', $row)
                    {{ Html::link($row->urlPanelEdit(), 'Edit') }} &nbsp; 
                    @endcan

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