<?php

namespace App\Models;

class AppletLoginLog extends BaseModel
{
    const CREATED_AT = 'createtime';
    
    protected $connection = 'DkInfo';
    
    protected $table = 'AppletLoginLog';

    public $timestamps = false;

    protected $fillable = [
    	'openid','session_key','nickName','avatarUrl','token'
    ];
    
    protected $casts = [
        'id' => 'string',
        'createtime' => 'string',
    ];

}
