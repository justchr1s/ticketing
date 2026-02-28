<?php

use App\Http\Controllers\ExportTicketPdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/technicien/export-tickets-pdf', ExportTicketPdfController::class)
    ->middleware(['web', 'auth:technicien'])
    ->name('tickets.export-pdf');
