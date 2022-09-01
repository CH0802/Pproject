<?php

namespace App\Models;
use DateTimeInterface;

class AppletUser extends BaseModel
{
    const CREATED_AT = 'createtime';
    const UPDATED_AT ='updatetime';
    
    protected $connection = 'DkInfo';
    
    protected $table = 'AppletUser';

    public $timestamps = true;

    protected $fillable = [
    	'openid','session_key','nickName','avatarUrl','token','usercode'
    ];
    
    protected $casts = [
        'id' => 'string',
        'createtime' => 'string',
        'updatetime' => 'string',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
