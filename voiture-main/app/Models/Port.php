<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Port extends Model
{
    use HasFactory;

    protected $table = 'ports';

    protected $fillable = [
        'nom','code','pays','ville','type','frais_base','delai_moyen_jours','description','actif'
    ];

    protected $casts = [
        'frais_base' => 'decimal:2',
        'actif' => 'boolean',
    ];

    public function voitures()
    {
        return $this->hasMany(Voiture::class, 'port_recommande_id');
    }
}
