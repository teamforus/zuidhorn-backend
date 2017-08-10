<?php

namespace App\Models\Traits\Urls;

/**
 * summary
 */
trait CategoryUrlsTrait
{
    public function urlPanelView()
    {
        return url('panel/categories/' . $this->id);
    }

    public function urlPanelEdit()
    {
        return url('panel/categories/' . $this->id . '/edit');
    }

    public function urlPanelDelete()
    {
        return url('panel/categories/' . $this->id . '/destroy');
    }
}
