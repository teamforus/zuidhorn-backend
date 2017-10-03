<?php

namespace App\Models\Traits\Urls;

/**
 * summary
 */
trait OfficeUrlsTrait
{
    public function urlPanelView()
    {
        return url('panel/shop-keepers/' . $this->shop_keeper_id . '/offices/' . $this->id);
    }

    public function urlPanelEdit()
    {
        return url('panel/shop-keepers/' . $this->shop_keeper_id . '/offices/' . $this->id . '/edit');
    }

    public function urlPanelDelete()
    {
        return url('panel/shop-keepers/' . $this->shop_keeper_id . '/offices/' . $this->id . '/destroy');
    }

    public function urlGoogleMap()
    {
        return url("//www.google.com/maps/place/{$this->lat},{$this->lon}");
    }
}
