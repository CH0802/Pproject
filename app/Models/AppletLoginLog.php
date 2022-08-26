<?php

namespace App\Models;

class AppletLoginLog extends BaseModel
{
    const CREATED_AT = 'createtime';
    const UPDATED_AT ='updatetime';
    
    protected $connection = 'DkInfo';
    
    protected $table = 'AppletLoginLog';

    public $timestamps = true;

    protected $fillable = [
    	'openid','session_key','nickName','avatarUrl','token'
    ];
    
    protected $casts = [
        'id' => 'string',
        'createtime' => 'string',
        'updatetime' => 'string',
    ];

}
