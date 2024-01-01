<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificNeeds extends Model
{
    use HasFactory;
    public $table = "specific_needs";
    protected $primaryKey = 'specific_needs_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'specific_needs_id',
        'specific_needs_name',
        'specific_needs_status'
    ];
}
