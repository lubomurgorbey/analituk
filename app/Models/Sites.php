<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Sites extends Model
{
    const STATUS_ON = 'on';
    const STATUS_OFF = 'off';

    public static $labelNames = [
        'status'    =>  [
            self::STATUS_ON=>'on',
            self::STATUS_OFF=>'off',
        ]
    ];
    public static $statuses = [self::STATUS_ON, self::STATUS_OFF];

    protected $table = 'sites';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_name', 'domain',
        'avg_time', 'status','pages','last_check'
    ];


       public function pages()
      {
        return $this->hasMany(Pages::class,'event_id');
       }
    /**
     * @return mixed
     */
}
