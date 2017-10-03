@if($rows->count() > 0)
<table class="table table-striped table-align-middle">
    <tHead>
        <tr>
            <th>Id</th>
            <th>Shop Keeper</th>
            <th>Address</th>
            <th>Longitude</th>
            <th>Latitude</th>
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
                    <em class="mdi mdi-link"></em>
                    {{ Html::link($row->urlPanelView(), '#' . str_pad($row->id, 5, 0, STR_PAD_LEFT), ['class' => 'text-primary']) }}
                </strong>
            </td>
            <td>
                <strong>
                    <em class="mdi mdi-link"></em>
                    {{ Html::link($row->shop_keeper->urlPanelView(), $row->shop_keeper->user->full_name, ['class' => 'text-primary']) }}
                </strong>
            </td>
            <td>
                <strong>
                    <em class="mdi mdi-open-in-new"></em>
                    {{ Html::link($row->urlGoogleMap(), $row->address, ['class' => 'text-primary', 'target' => '__blank']) }}
                </strong>
            </td>
            <td>{{ $row->lon }}</td>
            <td>{{ $row->lat }}</td>
            <td class="text-right">
                <div class="btn-group">
                    <em class="mdi mdi-open-in-new"></em>
                    {{ Html::link($row->urlGoogleMap(), 'View on map', [
                        'target' => "_blank"]) 
                    }}

                    &nbsp;
                    &nbsp;

                    @can('view', $row)
                    <em class="mdi mdi-link"></em>
                    {{ Html::link($row->urlPanelView(), 'View') }} &nbsp; 
                    @endcan

                    @can('update', $row)
                    <em class="mdi mdi-link"></em>
                    {{ Html::link($row->urlPanelEdit(), 'Edit') }} &nbsp; 
                    @endcan

                    @can('delete', $row)
                    <em class="mdi mdi-link"></em>
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