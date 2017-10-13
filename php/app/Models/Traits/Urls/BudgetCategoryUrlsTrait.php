<?php

namespace App\Models\Traits\Urls;

/**
 * summary
 */
trait BudgetCategoryUrlsTrait
{
    public function urlPanelView()
    {
        return url('panel/budget-categories/' . $this->id);
    }

    public function urlPanelEdit()
    {
        return url('panel/budget-categories/' . $this->id . '/edit');
    }

    public function urlPanelDelete()
    {
        return url('panel/budget-categories/' . $this->id . '/destroy');
    }
}
