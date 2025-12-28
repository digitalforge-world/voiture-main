<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerSupplier extends Model
{
    protected $fillable = [
        'name',
        'type',
        'contact_person',
        'email',
        'phone',
        'country',
        'address',
        'notes'
    ];
}
