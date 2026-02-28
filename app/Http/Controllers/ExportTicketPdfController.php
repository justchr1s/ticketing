<?php

namespace App\Http\Controllers;

use App\Models\Technicien;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ExportTicketPdfController extends Controller
{
    public function __invoke(): Response
    {
        /** @var Technicien $user */
        $user = auth('technicien')->user();

        $query = Ticket::with(['client', 'technicien'])
            ->orderByDesc('created_at');

        if (! $user->isAdministrateur()) {
            $query->where('technicien_id', $user->id);
        }

        $tickets = $query->get();

        $pdf = Pdf::loadView('exports.tickets-pdf', compact('tickets'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('tickets-'.now()->format('Y-m-d').'.pdf');
    }
}
