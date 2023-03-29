<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use App\Services\SoapService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SoapController extends Controller
{
    public function search(Request $request)
    {
        try {
            $term = $request->get('term');
            $data = SoapService::search($term);

            if (!$data) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'Can not find any entry!'
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Search success',
                'result' => $data
            ]);

        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error',
            ], 500);
        }
    }
}
