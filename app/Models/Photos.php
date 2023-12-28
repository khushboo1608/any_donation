<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photos extends Model
{
    use HasFactory;
    public $table = "photos";
    protected $primaryKey = 'photo_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'photo_id',
        'ngo_bank_id',
        'photo_name',
        'photo_url',
        'photo_type',
        'photo_status'
           
    ];
}
