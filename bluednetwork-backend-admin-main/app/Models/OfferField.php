<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferField extends Model
{
    use HasFactory;

    protected $fillable = [
        'offer_id',
        'field_name',
        'description',
        'type',
        'required_field',
        'enabled',
        'has_values',
        'answers',
        'default_value',
        'validation_rules',
        'file_types',
        'max_file_size'
        
    ];
}
