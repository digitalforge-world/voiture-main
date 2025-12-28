<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';

    protected $fillable = [
        'reference','tracking_number','user_id','type_transaction','transaction_id','montant','methode','operateur','numero_transaction_externe','statut','date_paiement','date_confirmation','notes'
    ];

    public const CREATED_AT = 'date_paiement';
    public const UPDATED_AT = null;

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'datetime',
        'date_confirmation' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
