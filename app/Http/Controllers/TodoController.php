<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function create(Request $request): JsonResponse
    {
        $this->authorize("create-contact", Todo::class);

        return response()->json([
            "message" => "Success"
        ]);
    }
}
