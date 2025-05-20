<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use App\Models\ProviderRequest;

class ProviderRequestController extends Controller
{
    use ImageHandler;

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|max:255',
            'company_website' => 'nullable',
            'contact_person' => 'required|max:255',
            'contact_email' => 'required|max:255',
            'phone_number' => 'required|max:20',
            'number_employees' => 'nullable|integer',
            'cities' => 'nullable',
            'services' => 'nullable',
            'plans' => 'nullable',
            'notes' => 'nullable',
            'licence' => 'nullable|file|mimes:pdf|max:4096',
        ]);

        if ($request->hasFile('licence')) {
            $path = $this->uploadImage($request->file('licence'), 'uploads');
            $validated['licence'] = 'uploads/'.$path;
        }

        $validated['cities'] = json_encode((array) ($validated['cities'] ?? []));
        $validated['services'] = json_encode((array) ($validated['services'] ?? []));
        $validated['plans'] = json_encode((array) ($validated['plans'] ?? []));

        $providerRequest = ProviderRequest::create($validated);

        return response()->json([
            'message' => 'Provider request submitted successfully.',
            'data' => $providerRequest,
        ], 201);
    }
}
