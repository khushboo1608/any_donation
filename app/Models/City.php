<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    public $table = "cities";
    protected $primaryKey = 'city_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'city_id',
        'city_name',
        'state_id'
           
    ];
    public function stateData() {
        return $this->hasOne('App\Models\State', 'state_id', 'state_id');
    }
}

