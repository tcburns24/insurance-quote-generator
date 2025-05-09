<?php

namespace App\Http\Controllers;
use App\Models\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class QuoteController extends Controller
{
    public function postQuote(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'dob' => 'required|date',
            'state' => 'required|string|size:2',
            'smoker' => 'required|boolean',
            'gender' => 'required|in:M,F',
            'term' => 'required|in:10,15,20,30',
            'coverage_amount' => 'required|numeric|min:100000|max:1000000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422)->withHeaders([
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            ]);
        }


        // Calculate age from date of birth
        $birthDate = new \DateTime($request->dob);
        $today = new \DateTime();
        $age = $birthDate->diff($today)->y;

        // Prepare data for the internal API
        $quoteData = [
            'age' => $age,
            'state' => $request->state,
            'smoker' => $request->smoker,
            'gender' => $request->gender,
            'term' => (int) $request->term,
            'coverage_amount' => (int) $request->coverage_amount,
        ];

        try {
            // Make the API call to the internal quoting service
            $response = $this->callQuotingApi($request->only([
                'dob', 'state', 'smoker', 'gender', 'term', 'coverage_amount'
            ]));


            return response()->json($response)->withHeaders([
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching quote: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while processing your quote request.',
                'message' => $e->getMessage(),
            ], 500)->withHeaders([
                'Access-Control-Allow-Origin' => '*',
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
            ]);
        }
    }

    public function fetchQuote(Request $request)
    {
        // Pre-process smoker field: convert string 'true'/'false' to real boolean
        $request->merge([
            'smoker' => filter_var($request->query('smoker'), FILTER_VALIDATE_BOOLEAN),
        ]);

        // Validate the incoming query parameters
        $validator = Validator::make($request->all(), [
            'dob' => 'required|date',
            'state' => 'required|string|size:2',
            'smoker' => 'required|boolean',
            'gender' => 'required|in:M,F',
            'term' => 'required|integer',
            'coverageAmount' => 'required|integer',
            'productId' => 'required|integer',
            'riskClass' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        // Prepare data to forward to the external quoting API
        $queryData = [
            'dob' => $request->dob,
            'state' => $request->state,
            'smoker' => $request->smoker,
            'gender' => $request->gender,
            'term' => $request->term,
            'coverageAmount' => $request->coverageAmount,
            'productId' => $request->productId,
            'riskClass' => $request->riskClass,
        ];

        try {
            // Call the external API
            $response = Http::get('https://plumlife-api-stage.azurefd.net/api/quote', $queryData);

            return response()->json($response->json());
        } catch (\Exception $e) {
            \Log::error('Error fetching external quote: ' . $e->getMessage());

            return response()->json([
                'error' => 'An error occurred while retrieving the quote.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }


    private function callQuotingApi($data)
    {
        // Directly create a new quote record
        $quote = Quote::create([
            'dob' => $data['dob'],
            'state' => $data['state'],
            'smoker' => $data['smoker'],
            'gender' => $data['gender'],
            'term' => $data['term'],
            'coverage_amount' => $data['coverage_amount'],
        ]);

        return $quote;
    }
}
