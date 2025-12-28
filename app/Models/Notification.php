<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'utilisateur_id','type','titre','message','lien','lu','date_creation','date_lecture'
    ];

    public const CREATED_AT = 'date_creation';
    public const UPDATED_AT = null;

    protected $casts = [
        'lu' => 'boolean',
        'date_creation' => 'datetime',
        'date_lecture' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
