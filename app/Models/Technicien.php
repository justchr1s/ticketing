<?php

namespace App\Models;

use App\Enums\RoleTechnicien;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Technicien extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory, Notifiable;

    protected $table = 'techniciens';

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'specialite',
        'mot_de_passe',
        'role',
    ];

    protected $hidden = [
        'mot_de_passe',
        'remember_token',
    ];

    /**
     * Override the default password column name.
     */
    protected $authPasswordName = 'mot_de_passe';

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mot_de_passe' => 'hashed',
            'role' => RoleTechnicien::class,
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'technicien';
    }

    public function isAdministrateur(): bool
    {
        return $this->role === RoleTechnicien::Administrateur;
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function getFilamentName(): string
    {
        return $this->nom;
    }
}
