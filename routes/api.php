<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;

Route::post('/quote', [QuoteController::class, 'postQuote']);
Route::get('/quotes', [QuoteController::class, 'fetchQuote']);

