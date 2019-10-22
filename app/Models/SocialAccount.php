<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    protected $primaryKey = "id";
    protected $table      = "tbl_social_accounts";
    public $incrementing  = false;
    public $timestamps    = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'provider_user_id',
        'provider',
        'url',
        'expires_time',
        'token',
        'create_date',
        'alter_date',
    ];

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer','id', 'id');
    }
}
