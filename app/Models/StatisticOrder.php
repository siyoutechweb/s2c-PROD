<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class StatisticOrder extends Model
{

    protected $table='statistic_order';

    protected $fillable=[
        'days','chain_id','total_order','income','paid','confirmed','pended','card','check','cash','store_id'
    ];
}
