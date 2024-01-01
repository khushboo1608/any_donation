<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceNeeds extends Model
{
    use HasFactory;
    public $table = "service_needs";
    protected $primaryKey = 'service_needs_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'service_needs_id',
        'service_needs_name',
        'service_needs_status'
    ];
}
