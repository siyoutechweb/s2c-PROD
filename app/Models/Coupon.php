<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table='coupon';
    protected $fillable=['store_id','chain_id','barcode','start_date','expire_date','amount','used'];
}