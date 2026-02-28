<?php

namespace App\Models;

use App\Enums\EtatTicket;
use App\Enums\PrioriteTicket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
        'etat',
        'priorite',
        'date_resolution',
        'technicien_id',
        'client_id',
    ];

    protected function casts(): array
    {
        return [
            'etat' => EtatTicket::class,
            'priorite' => PrioriteTicket::class,
            'date_resolution' => 'datetime',
        ];
    }

    public function technicien(): BelongsTo
    {
        return $this->belongsTo(Technicien::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function transitionTo(EtatTicket $newState): void
    {
        if (! $this->etat->canTransitionTo($newState)) {
            throw new \InvalidArgumentException(
                "Cannot transition from {$this->etat->value} to {$newState->value}."
            );
        }

        $this->etat = $newState;

        if (in_array($newState, [EtatTicket::Ferme, EtatTicket::Cloture], true)) {
            $this->date_resolution = now();
        }

        $this->save();
    }
}
