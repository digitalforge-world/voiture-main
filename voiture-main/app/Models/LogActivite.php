<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogActivite extends Model
{
    use HasFactory;

    protected $table = 'logs_activites';

    protected $fillable = ['user_id','action','table_concernee','enregistrement_id','details','ip_address','user_agent','date_action'];

    public const CREATED_AT = 'date_action';
    public const UPDATED_AT = null;

    protected $casts = ['date_action' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
