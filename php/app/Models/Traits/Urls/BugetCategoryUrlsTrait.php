<?php

namespace App\Models\Traits\Urls;

/**
 * summary
 */
trait BugetCategoryUrlsTrait
{
    public function urlPanelView()
    {
        return url('panel/buget-categories/' . $this->id);
    }

    public function urlPanelEdit()
    {
        return url('panel/buget-categories/' . $this->id . '/edit');
    }

    public function urlPanelDelete()
    {
        return url('panel/buget-categories/' . $this->id . '/destroy');
    }
}
