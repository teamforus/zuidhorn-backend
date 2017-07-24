<?php

namespace App\Models\Traits\Urls;

/**
 * summary
 */
trait VoucherUrlsTrait
{
    public function urlPanelView()
    {
        return url('panel/vouchers/view/' . $this->id);
    }

    public function urlPanelEdit()
    {
        return url('panel/vouchers/edit/' . $this->id);
    }

    public function urlPanelDelete()
    {
        return url('panel/vouchers/delete/' . $this->id);
    }
}
