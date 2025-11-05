<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        $contactRequest = ContactRequest::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Contact request submitted successfully',
            'data' => $contactRequest,
        ], 201);
    }
}
