<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable implements JWTSubject
{

    protected $primaryKey = "id";
    protected $table      = "tbl_users";
    protected $keyType    = 'string';
    public $incrementing  = false;
    public $timestamps    = false;
    
    const ACTIVE_CUSTOMER = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'password',
        'last_name',
        'first_name',
        'address',
        'email',
        'phone',
        'photo_id',
        'status',
        'create_date',
        'alter_date',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [
            'user' => [
                'id'              => $this->id,
                'first_name'      => $this->first_name,
                'last_name'       => $this->last_name,
                'email'           => $this->email,
                'roles'           => $this->roles,
                'address'         => $this->address,
                'phone'           => $this->phone,
                'photo_id'        => $this->photo_id,
                'status'          => $this->status,
                'create_date'     => $this->create_date,
                'alter_date'      => $this->alter_date,
            ] ,
        ];
    }

    public function scopeGetId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function scopeActive($query)
    {
        return $query->where('status', self::ACTIVE_CUSTOMER);
    }

    public function scopeFindEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    public function scopeSortUpdate($query)
    {
        return $query->orderBy('alter_date', 'DESC');
    }

    public function scopeupdatePassword($query, $data)
    {
        return $query->update($data);
    }
}
