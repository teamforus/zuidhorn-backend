@if($rows->count() > 0)
<table class="table table-striped table-align-middle">
    <tHead>
        <tr>
            <th>Id</th>
            <th>Voucher</th>
            <th>Amount</th>
            <th>Extra amount</th>
            <th>Date</th>
            <th>Status</th>
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
            <td>{{ Html::link($row->voucher->urlPanelView(), $row->voucher->code, ['class' => 'text-primary']) }}</td>
            <td>
                <strong>€{{ number_format($row->amount, 2) }}</strong>
            </td>
            <td>
                <strong>€{{ number_format($row->extra_amount, 2) }}</strong>
            </td>
            <td>
                <strong>{{ $row->created_at->format('M d, Y H:i') }}</strong>
            </td>
            <td>
                <strong>{{ ucfirst($row->status) }}</strong>
            </td>
            <td class="text-right">
                <div class="btn-group">
                    @can('view', $row)
                    {{ Html::link($row->urlPanelView(), 'View') }} &nbsp; 
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