<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Budget
 * @property mixed $id
 * @property string $name
 * @property float $amount_per_child
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection $users
 * @property Collection $budget_categories
 * @property Collection $categories
 * @package App\Models
 */
class Budget extends Model
{
    use Traits\Urls\BudgetUrlsTrait;

    protected $fillable = ['name', 'amount_per_child'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(
            'App\Models\User', 
            'budget_users');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function budget_categories()
    {
        return $this->hasMany('App\Models\BudgetCategory');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(
            'App\Models\Category', 
            'budget_categories');
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function unlink()
    {
        return $this->delete();
    }
}
