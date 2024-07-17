<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksi';
    protected $primaryKey = 'order_id';
    protected $fillable = ['order_id','user_id','plan_id','status'];
    public $incrementing = false;
    public $timestamps = false;
}
