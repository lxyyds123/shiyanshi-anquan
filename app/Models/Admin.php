<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject
{
    use Notifiable ;

    //关联表
    protected $table = 'tb_admin';
    //设置守卫
    protected $guarded = [];
    //允许查询的字段
    protected $fillable = ['account','password'];
    //隐藏密码
    protected $hidden = ['password'];
    protected $remeberTokenName = NULL;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role'=>'admin'];
    }
}
