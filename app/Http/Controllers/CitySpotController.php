<?php

namespace App\Http\Controllers;
use App\Models\Spot;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CitySpotController extends Controller {
    public function getSpotsByCountry(Request $request) {
        $country = $request->query('country');

        if (!$country) {
            return response()->json([
                'error' => 'Missing country param!'
            ], 400);
        }

        $spots = Spot::whereHas('city', function ($query) use ($country) {
            $query->whereRaw('LOWER(country) = ?', [strtolower($country)]);
        })->with('city')->get();


        return response()->json($spots);
    }
}
