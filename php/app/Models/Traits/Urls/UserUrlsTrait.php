<?php

namespace App\Models\Traits\Urls;

/**
 * summary
 */
trait UserUrlsTrait
{
    public function urlPanelView()
    {
        $role_key = $this->roles()->first()->key;

        return url('panel/' . $role_key . 's/view/' . $this->id);
    }

    public function urlPanelEdit()
    {
        $role_key = $this->roles()->first()->key;

        return url('panel/' . $role_key . 's/edit/' . $this->id);
    }

    public function urlPanelDelete()
    {
        $role_key = $this->roles()->first()->key;

        return url('panel/' . $role_key . 's/delete/' . $this->id);
    }
}
