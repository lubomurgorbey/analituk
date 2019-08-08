<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pages extends Model
{
    const STATUS_ON = 'ON';
    const STATUS_OFF = 'OFF';

    public static $labelNames = [
        'status'    =>  [
            self::STATUS_ON=>'ON',
            self::STATUS_OFF=>'OFF',
        ]
    ];
    public static $statuses = [self::STATUS_ON, self::STATUS_OFF];

    protected $table = 'pages';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_id', 'domain',
        'total_time', 'http_code','last_check','size'
    ];

    /**
     * @return mixed
     */
    public function siteID()
    {
        return $this->belongsTo(Sites::class,'id');
    }


}
