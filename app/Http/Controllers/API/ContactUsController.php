<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\NewContactMessage;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }
        try {
            $contact_us = ContactUs::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'message' => $request->input('message'),
            ]);

            $email = setting('contact_email', 'info@hpower.ae');

            Mail::to($email)->send(new NewContactMessage());

            return response()->json([
                'status' => 'success',
                'message' => __('lang.saved_successfully', ['operator' => __('lang.contact_us')]),
                'data' => $contact_us,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while saving the contactus.',
                'error' => $e->getMessage(),
                'data' => [],
            ], 500);
        }
    }
}
