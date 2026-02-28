<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Export Tickets</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 20px;
        }

        .header {
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header table {
            width: 100%;
        }

        .header td {
            vertical-align: top;
        }

        .logo {
            width: 80px;
            height: auto;
        }

        .company-info {
            text-align: right;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }

        .company-detail {
            font-size: 10px;
            color: #666;
            line-height: 1.6;
        }

        .report-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a5f;
            margin-bottom: 15px;
            text-align: center;
        }

        .tickets-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .tickets-table th {
            background-color: #2563eb;
            color: #fff;
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
        }

        .tickets-table td {
            padding: 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
        }

        .tickets-table tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }

        .badge-ouvert { background-color: #dbeafe; color: #1d4ed8; }
        .badge-en_cours { background-color: #fef3c7; color: #92400e; }
        .badge-ferme { background-color: #d1fae5; color: #065f46; }
        .badge-cloture { background-color: #f3f4f6; color: #4b5563; }

        .badge-basse { background-color: #f3f4f6; color: #4b5563; }
        .badge-moyenne { background-color: #dbeafe; color: #1d4ed8; }
        .badge-haute { background-color: #fef3c7; color: #92400e; }
        .badge-urgente { background-color: #fee2e2; color: #991b1b; }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 9px;
            color: #9ca3af;
            text-align: center;
        }

        .stats {
            margin-bottom: 15px;
            font-size: 11px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td style="width: 100px;">
                    @if (file_exists(public_path('images/logo.png')))
                        <img src="{{ public_path('images/logo.png') }}" class="logo" alt="Logo">
                    @endif
                </td>
                <td class="company-info">
                    <div class="company-name">{{ config('company.name') }}</div>
                    <div class="company-detail">
                        <strong>Directeur :</strong> {{ config('company.director') }}<br>
                        {{ config('company.address') }}<br>
                        <strong>Tél :</strong> {{ config('company.phone') }} |
                        <strong>Email :</strong> {{ config('company.email') }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="report-title">Rapport des Tickets</div>

    <div class="stats">
        Total : {{ $tickets->count() }} ticket(s)
    </div>

    <table class="tickets-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Titre</th>
                <th>État</th>
                <th>Priorité</th>
                <th>Client</th>
                <th>Technicien</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tickets as $ticket)
                <tr>
                    <td>{{ $ticket->id }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($ticket->titre, 40) }}</td>
                    <td>
                        <span class="badge badge-{{ $ticket->etat?->value }}">
                            {{ $ticket->etat?->getLabel() }}
                        </span>
                    </td>
                    <td>
                        @if ($ticket->priorite)
                            <span class="badge badge-{{ $ticket->priorite->value }}">
                                {{ $ticket->priorite->getLabel() }}
                            </span>
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $ticket->client?->nom }}</td>
                    <td>{{ $ticket->technicien?->nom ?? 'Non assigné' }}</td>
                    <td>{{ $ticket->created_at?->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">Aucun ticket trouvé.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Document généré le {{ now()->format('d/m/Y à H:i') }} — {{ config('company.name') }}
    </div>
</body>
</html>
