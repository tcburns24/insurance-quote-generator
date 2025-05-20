<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;

Route::post('/quote', [QuoteController::class, 'postQuote']);
Route::get('/quotes', [QuoteController::class, 'fetchQuote']);
Route::get('/posted-quotes', [QuoteController::class, 'listPostedQuotes']);
Route::delete('/quotes/{id}', [QuoteController::class, 'deleteQuote']);
