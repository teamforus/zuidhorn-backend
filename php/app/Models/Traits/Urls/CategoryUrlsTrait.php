<?php

namespace App\Models\Traits\Urls;

/**
 * summary
 */
trait CategoryUrlsTrait
{
    public function urlPanelView()
    {
        return url('panel/categories/view/' . $this->id);
    }

    public function urlPanelEdit()
    {
        return url('panel/categories/edit/' . $this->id);
    }

    public function urlPanelDelete()
    {
        return url('panel/categories/delete/' . $this->id);
    }
}
