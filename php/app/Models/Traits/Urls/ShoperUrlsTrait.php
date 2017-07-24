<?php

namespace App\Models\Traits\Urls;

/**
 * summary
 */
trait ShoperUrlsTrait
{
    public function urlPanelView()
    {
        return url('panel/shopers/view/' . $this->id);
    }

    public function urlPanelEdit()
    {
        return url('panel/shopers/edit/' . $this->id);
    }

    public function urlPanelDelete()
    {
        return url('panel/shopers/delete/' . $this->id);
    }
}
