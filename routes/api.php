<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\CitySpotController;

// Old insurance routes
Route::post('/quote', [QuoteController::class, 'postQuote']);
Route::get('/quotes', [QuoteController::class, 'fetchQuote']);
Route::get('/posted-quotes', [QuoteController::class, 'listPostedQuotes']);
Route::delete('/quotes/{id}', [QuoteController::class, 'deleteQuote']);


// New City Spots Routes:
Route::get('/spots/by-country', [CitySpotController::class, 'getSpotsByCountry']);
