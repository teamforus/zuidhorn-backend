<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetCategory extends Model
{
    use Traits\Urls\BudgetCategoryUrlsTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'budget_id', 'category_id'
    ];

    public function shop_keeper()
    {
        return $this->belongsTo('App\Models\ShopKeeper');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function unlink()
    {
        return $this->delete();
    }
}
