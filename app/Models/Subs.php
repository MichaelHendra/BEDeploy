<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subs extends Model
{
    use HasFactory;
    protected $table = 'sub_plans';
    protected $primaryKey = 'sub_id';
    protected $fillable = ['sub_day','nama_sub','harga'];
    public $incrementing = true;
}
