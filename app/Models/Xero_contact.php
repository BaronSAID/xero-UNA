<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class Xero_contact extends Authenticatable
{
    use HasFactory;

    protected $connection = 'oracle';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'contactid',
        'contactnumber',
        'accountnumber',
        'contactstatus',
        'name',
        'firstname',
        'lastname',
        'emailaddress',
        'skypeusername',
        'bankaccountdetails',
        'companynumber',
        'taxnumber',
        'accountsreceivabletaxtype',
        'addresses',
        'phones',
        'issupplier',
        'iscustomer',
        'defaultcurrency',
        'updateddateutc',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'rn', 'id',
    ];

}
