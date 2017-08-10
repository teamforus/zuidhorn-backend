<?php

namespace App\Models\Traits\Urls;

/**
 * summary
 */
trait ShopKeeperCategoryUrlsTrait
{
    public function urlPanelView()
    {
        return url('panel/shop-keeper-categories/' . $this->id);
    }

    public function urlPanelEdit()
    {
        return url('panel/shop-keeper-categories/' . $this->id . '/edit');
    }

    public function urlPanelDelete()
    {
        return url('panel/shop-keeper-categories/' . $this->id . '/destroy');
    }
}
