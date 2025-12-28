<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, \App\Traits\LogsActivity;

    protected $table = 'users';

    /**
     * Use custom timestamp column names from the SQL schema.
     */
    public const CREATED_AT = 'date_creation';
    public const UPDATED_AT = 'date_modification';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'mot_de_passe',
        'role',
        'adresse',
        'ville',
        'pays',
        'photo_profil',
        'actif',
        'email_verified_at',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int,string>
     */
    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'actif' => 'boolean',
    ];

    /**
     * Return the password used for authentication.
     * Laravel expects getAuthPassword() to return the password field.
     */
    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }

    /**
     * Mutator to set the French password column `mot_de_passe`.
     */
    public function setPasswordAttribute($value): void
    {
        if ($value === null) {
            $this->attributes['mot_de_passe'] = null;
            return;
        }

        $this->attributes['mot_de_passe'] = Hash::needsRehash($value) ? Hash::make($value) : $value;
    }

    /**
     * Mutator to set `mot_de_passe`.
     */
    public function setMotDePasseAttribute($value): void
    {
        $this->setPasswordAttribute($value);
    }

    /**
     * Convenience accessor for full name when `nom`/`prenom` are used.
     */
    public function getFullNameAttribute(): string
    {
        return trim(($this->prenom ?? '') . ' ' . ($this->nom ?? '')) ?: ($this->name ?? '');
    }

    /**
     * Vérifier si l'utilisateur est administrateur.
     */
    public function getIsAdminAttribute(): bool
    {
        return ($this->role ?? '') === 'admin';
    }
}
