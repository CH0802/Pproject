<?php

namespace App\Models;
use DateTimeInterface;

class TemplateMaster extends BaseModel
{
    const CREATED_AT = 'createtime';
    const UPDATED_AT ='updatetime';
    const STATE_QY ='1';//启用
    
    protected $connection = 'DkInfo';
    
    protected $table = 'TemplateMaster';

    public $timestamps = true;

    protected $fillable = [
    	'tempname'
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
