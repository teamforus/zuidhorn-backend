<?php

namespace App\Models\Traits\Urls;

/**
 * summary
 */
trait TransactionUrlsTrait
{
    public function urlPanelView()
    {
        return url('panel/voucher-transactions/view/' . $this->id);
    }

    public function urlPanelEdit()
    {
        return url('panel/voucher-transactions/edit/' . $this->id);
    }

    public function urlPanelDelete()
    {
        return url('panel/voucher-transactions/delete/' . $this->id);
    }
}
