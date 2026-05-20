<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoRevision extends Model
{
    use HasFactory;

    protected $table = 'photos_revisions';

    public $timestamps = false;

    protected $fillable = ['revision_id', 'url', 'ordre', 'principale', 'type', 'date_ajout'];

    protected $casts = [
        'principale' => 'boolean',
        'date_ajout' => 'datetime',
    ];

    public function revision()
    {
        return $this->belongsTo(Revision::class, 'revision_id');
    }
}
