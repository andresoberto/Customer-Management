<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'id_reg';

    protected $fillable=[
        'description'
    ];

    public function communes(){
        return $this->hasMany(Communes::class, 'id_reg', 'id_reg');
    }

    public function customers() {
        return $this->hasMany(Customer::class, 'id_reg', 'id_reg');
    }
}
