<?php

namespace App\Models\Traits\Urls;

/**
 * summary
 */
trait BudgetUrlsTrait
{
    public function urlPanelView()
    {
        return url('panel/budgets/' . $this->id);
    }

    public function urlPanelEdit()
    {
        return url('panel/budgets/' . $this->id . '/edit');
    }

    public function urlPanelDelete()
    {
        return url('panel/budgets/' . $this->id . '/destroy');
    }
}
