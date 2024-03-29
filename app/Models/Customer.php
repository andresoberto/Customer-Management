<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'dni';
    public $incrementing = false;

    protected $fillable=[
        'dni',
        'dni',
        'id_reg',
        'id_com',
        'email',
        'name',
        'last_name',
        'address',
        'date_reg',
        'status',
    ];

    public function communes(){
        return $this->belongsTo(Communes::class, 'id_com', 'id_com');
    }

    public function regions(){
        return $this->belongsTo(Region::class, 'id_reg', 'id_reg');
    }
}
