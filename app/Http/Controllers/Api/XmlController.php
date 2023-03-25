<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Services\DOMValidator;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class XmlController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function xsd(Request $request)
    {
        try {
            $path = $request->file('pokemon');

            if ($path) {
                $validator = new DOMValidator(resource_path('xml/pokemon.xsd'));
                $validated = $validator->validateWithXsd($path);
            }

            if ($validated) {
                return response()->json(['message' => 'XML is valid']);
            } else {
                return response()->json([
                    'message' => 'XML is not valid',
                    'error' => implode($validator->displayErrors())
                ], 404);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
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

            if ($validated) {
                return response()->json(['message' => 'XML is valid']);
            } else {
                return response()->json([
                    'message' => 'XML is not valid',
                    'error' => implode($validator->displayErrors())
                ], 404);
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json([
                'message' => 'Internal server error',
            ], 500);
        }
    }
}
