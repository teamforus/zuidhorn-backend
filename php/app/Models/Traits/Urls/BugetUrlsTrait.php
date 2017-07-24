<?php

namespace App\Models\Traits\Urls;

/**
 * summary
 */
trait BugetUrlsTrait
{
    public function urlPanelView()
    {
        return url('panel/bugets/view/' . $this->id);
    }

    public function urlPanelEdit()
    {
        return url('panel/bugets/edit/' . $this->id);
    }

    public function urlPanelDelete()
    {
        return url('panel/bugets/delete/' . $this->id);
    }
}
