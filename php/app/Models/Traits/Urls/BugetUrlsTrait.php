<?php

namespace App\Models\Traits\Urls;

/**
 * summary
 */
trait BugetUrlsTrait
{
    public function urlPanelView()
    {
        return url('panel/bugets/' . $this->id);
    }

    public function urlPanelEdit()
    {
        return url('panel/bugets/' . $this->id . '/edit');
    }

    public function urlPanelDelete()
    {
        return url('panel/bugets/' . $this->id . '/destroy');
    }
}
