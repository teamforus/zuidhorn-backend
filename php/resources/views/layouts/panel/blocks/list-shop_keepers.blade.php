@if($rows->count() > 0)
<table class="table table-striped">
    <tHead>
        <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Kvk-number</th>
            <th>IBAN</th>
            <th>E-mail</th>
            <th>Phone</th>
            <th>Status</th>
            <th>Categories</th>
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
                    {{ Html::link($row->urlPanelView(), '#' . str_pad($row->id, 5, 0, STR_PAD_LEFT), ['class' => 'text-primary']) }}
                </strong>
            </td>
            <td>
                @can('view', $row)
                <strong>
                    {{ Html::link($row->urlPanelView(), $row->name, ['class' => 'text-primary']) }}
                </strong>
                @else
                {{ $row->name }}
                @endcan
            </td>
            <td>{{ $row->kvk_number }}</td>
            <td>{{ $row->iban }}</td>
            <td>{{ $row->user->email }}</td>
            <td>{{ $row->phone_number }}</td>
            <td><strong>{{ ucfirst(strtolower($row->state)) }}</strong></td>
            <td>
                @if($row->categories->count())
                {{ str_limit($row->categories->pluck('name')->implode(','), 32) }}
                @else
                <strong class="text-muted">N/A</strong>
                @endif
            </td>

            @if(!isset($no_actions) || !$no_actions)
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
            @endif
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