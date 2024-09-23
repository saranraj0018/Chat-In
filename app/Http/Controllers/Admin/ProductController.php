<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function list(Request $request)
    {
        $products = Product::all();
        return view('admin.product.list',compact('products'));
    }

    public function dummy()
    {
        try {
            $response = Http::get('https://calendarific.com/api/v2/holidays', [
                'api_key' => 'AIzaSyCgkUiA7zkxsdc8BwvCqVeSTDuJVncMmAY', // Replace with your correct API key
                'country' => 'IN',
                'year' => 2024,
            ]);

            // Check the response status
            if ($response->successful()) {
                $holidays = $response->json()['response']['holidays'];
                dd($holidays); // Display the holidays list
            } else {
                // Display error details
                dd([
                    'status' => $response->status(),
                    'message' => $response->json()['error']['message'] ?? 'An error occurred'
                ]);
            }
        } catch (\Exception $e) {
            // Catch and display any exceptions
            dd([
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);
        }
    }


}
