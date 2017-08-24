<?php

namespace App\Models\Traits\Urls;

/**
 * summary
 */
trait ShopKeeperUrlsTrait
{
    public function urlPanelView()
    {
        return url('panel/shop-keepers/' . $this->id);
    }

    public function urlPanelEdit()
    {
        return url('panel/shop-keepers/' . $this->id . '/edit');
    }

    public function urlPanelDelete()
    {
        return url('panel/shop-keepers/' . $this->id . '/destroy');
    }

    public function urlPanelValidateStatus()
    {
        return $this->urlPanelView() . "#shop_keeper-categories";
    }

    public function urlPanelStateApprove()
    {
        return url('panel/shop-keepers/' . $this->id . '/state/approve');
    }

    public function urlPanelAddOffice()
    {
        return url('panel/shop-keepers/' . $this->id . '/offices/create');
    }
}
