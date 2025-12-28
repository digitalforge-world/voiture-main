<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\LogsActivity;

class ParametreSysteme extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'parametres_systeme';

    protected $fillable = ['cle', 'valeur', 'type', 'description'];

    public const CREATED_AT = null;
    public const UPDATED_AT = 'date_modification';

    protected $casts = [
        'date_modification' => 'datetime',
    ];
}
