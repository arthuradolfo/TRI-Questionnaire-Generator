<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Uuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'username',
        'email',
        'firstname',
        'lastname',
        'idnumber',
        'institution',
        'department',
        'phone1',
        'phone2',
        'city',
        'url',
        'icq',
        'skype',
        'aim',
        'yahoo',
        'msn',
        'country',
        'password',
    ];

    /**
     * The attributes that should have deafault values.
     *
     * @var array
     */
    protected $attributes = [
        'idnumber' => NULL,
        'institution' => '',
        'department' => '',
        'phone1' => '',
        'phone2' => '',
        'city' => '',
        'url' => '',
        'icq' => '',
        'skype' => '',
        'aim' => '',
        'yahoo' => '',
        'msn' => '',
        'country' => '',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'email_verified_at' => 'datetime',
    ];
}
