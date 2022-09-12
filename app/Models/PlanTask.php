<?php

namespace App\Models;
use DateTimeInterface;

class PlanTask extends BaseModel
{
    const CREATED_AT = 'createtime';
    const UPDATED_AT ='updatetime';
    
    protected $connection = 'DkInfo';
    
    protected $table = 'PlanTask';

    public $timestamps = true;

    protected $fillable = [
    	'uid','planitle','plancycletype','plancyclevalue','plannum','planclassify','planjournalstate'
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
