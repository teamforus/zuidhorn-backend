<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use App\Services\UIDGeneratorService\Facades\UIDGenerator;
use App\Models\CitizenToken;
use App\Models\User;

class Citizen extends Model
{
    protected $fillable = ['user_id'];

    // token live duration in minutes
    protected $tokenExpiresIn = 15;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function citizenTokens() {
        return $this->hasMany(CitizenToken::class);
    }

    public function generateAuthToken() {
        return $this->citizenTokens()->create([
            'token'         => UIDGenerator::generate(32, 4),
            'expires_at'    => Carbon::now()->addMinutes(60),
        ]);
    }

    public function generateAccessToken() {
        $token = $this->user->createToken('Token');

        $token->token->expires_at = Carbon::now()->addMinutes(
            $this->tokenExpiresIn);
        $token->token->save();
        
        return $token->accessToken;
    }
}
