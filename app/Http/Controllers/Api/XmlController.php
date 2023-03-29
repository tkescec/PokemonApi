<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Http\Controllers\Controller;
use App\Services\DOMValidator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XmlController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function xsd(Request $request)
    {
        try {
            $path = $request->file('pokemon');

            if ($path) {
                $validator = new DOMValidator(resource_path('xml/pokemon.xsd'));
                $validated = $validator->validateWithXsd($path);
            }

            if (!$validated) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'XML is not valid',
                    'error' => implode($validator->displayErrors())
                ], 422);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'XML is valid'
            ]);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error',
            ], 500);
        }
    }

    public function rng(Request $request)
    {
        try {
            $path = $request->file('pokemon');

            if ($path) {
                $validator = new DOMValidator(resource_path('xml/pokemon.rng'));
                $validated = $validator->validateWithRng($path);
            }

            if (!$validated) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'XML is not valid',
                    'error' => implode($validator->displayErrors())
                ], 422);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'XML is valid'
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
