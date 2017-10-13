<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use Traits\Urls\BudgetUrlsTrait;

    protected $fillable = ['name', 'amount_per_child'];

    public function users()
    {
        return $this->belongsToMany(
            'App\Models\User', 
            'budget_users');
    }

    public function budget_categories()
    {
        return $this->hasMany('App\Models\BudgetCategory');
    }

    public function categories()
    {
        return $this->belongsToMany(
            'App\Models\Category', 
            'budget_categories');
    }

    public function unlink()
    {
        return $this->delete();
    }
}
