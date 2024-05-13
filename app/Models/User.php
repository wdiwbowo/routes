<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\SoftDeletes;

use Tymon\JWTAuth\contracts\JWTsubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTsubject
{
    use Authenticatable, Authorizable, HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'username', 'email', 'password', 'role'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    // protected $hidden = [
    //     'password',
    // ];


   public function getJWTIdentifier()
    {
        return $this->getkey();
    }

    public function getJWTCustomclaims()
    {
        return[];
    } 

    public function lendings()
    {
        return $this->hasMany(Lending::class);
    }

    public function restorations()
    {
        return $this->hasMany(Restoration::class);
    }
}
