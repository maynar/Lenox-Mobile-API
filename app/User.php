<?php

namespace App;
use Illuminate\Database\Eloquent\Model;


class User extends Model
{

    protected $table = 'tb_users';
    protected $primaryKey = 'idUser';
    public $timestamps = true;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idUser','first_name','last_name', 'email', 'password','token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at'
    ];


}
