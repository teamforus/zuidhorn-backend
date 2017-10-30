<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Citizen;

class CitizenToken extends Model
{
    protected $fillable = [
        'citizen_id', 'token', 'revoked', 'used_up', 'expires_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'expires_at',
    ];

    public function citizen() {
        return $this->belongsTo(Citizen::class);
    }
}
