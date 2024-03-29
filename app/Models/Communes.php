<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Communes extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id_com';

    protected $fillable=[
        'id_reg',
        'description'
    ];

    public function customer(){
        return $this->hasMany(Customer::class, 'id_com', 'id_com');
    }

    public function region(){
        return $this->belongsTo(Region::class, 'id_reg', 'id_reg');
    }
}
