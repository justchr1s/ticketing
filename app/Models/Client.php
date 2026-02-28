<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Client extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory, Notifiable;

    protected $table = 'clients';

    protected $fillable = [
        'nom',
        'email',
        'telephone',
        'adresse',
        'mot_de_passe',
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
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'client';
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
