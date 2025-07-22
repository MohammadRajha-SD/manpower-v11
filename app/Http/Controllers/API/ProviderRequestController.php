<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use App\Models\ProviderRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ProviderRequestController extends Controller
{
    use ImageHandler;

    public function store(Request $request)
    {
        $messages = [
            'company_name.required' => $request->lang === 'ar' ? 'اسم الشركة مطلوب.' : 'Company name is required.',
            'contact_person.required' => $request->lang === 'ar' ? 'اسم الشخص المسؤول مطلوب.' : 'Contact person is required.',
            'contact_email.required' => $request->lang === 'ar' ? 'البريد الإلكتروني مطلوب.' : 'Email is required.',
            'contact_email.email' => $request->lang === 'ar' ? 'صيغة البريد الإلكتروني غير صحيحة.' : 'Invalid email format.',
            'contact_email.unique' => $request->lang === 'ar'
                ? 'لقد قمت بالفعل بإرسال طلب من قبل باستخدام هذا البريد الإلكتروني.'
                : 'You have already submitted a request using this email address.',
            'phone.required' => $request->lang === 'ar' ? 'رقم الهاتف مطلوب.' : 'Phone number is required.',
            'licence.required' => $request->lang === 'ar' ? 'ملف الترخيص مطلوب.' : 'Licence file is required.',
        ];

        try {
            Log::info('Incoming Request:', $request->all());

            $validator = Validator::make($request->all(), [
                'company_name' => 'required',
                'company_website' => 'nullable',
                'contact_person' => 'required',
                'contact_email' => 'required|email|unique:provider_requests,contact_email',
                'phone' => 'required',
                'number_employees' => 'nullable',
                'cities' => 'nullable',
                'services' => 'nullable',
                'plans' => 'nullable',
                'notes' => 'nullable',
                'licence' => 'required',
            ], $messages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $validated = $validator->validated();



            // Upload licence file if exists
            if ($request->hasFile('licence')) {
                try {
                    $path = $this->uploadImage($request->file('licence'), 'uploads');
                    $validated['licence'] = 'uploads/' . $path;
                    Log::info('Licence uploaded to: ' . 'uploads/' . $validated['licence']);
                } catch (\Exception $e) {
                    return response()->json(['error' => 'Failed to upload licence file.'], 500);
                }
            }

            // Handle comma-separated string to JSON array
            $validated['cities'] = isset($validated['cities']) ? json_encode(explode(',', $validated['cities'])) : json_encode([]);
            $validated['services'] = isset($validated['services']) ? json_encode(explode(',', $validated['services'])) : json_encode([]);
            $validated['plans'] = isset($validated['plans']) ? json_encode(explode(',', $validated['plans'])) : json_encode([]);
            $validated['phone_number'] = $request->phone;
            unset($validated['phone']);


            Log::info('Final Payload to DB: ', $validated);

            $providerRequest = ProviderRequest::create($validated);

            if (!empty($validated['contact_email'])) {
                try {
                    if ($request->lang == 'ar') {
                        Mail::to($validated['contact_email'])->send(
                            new \App\Mail\ProviderThankYouMailAR($validated['contact_person'])
                        );
                    } else {
                        Mail::to($validated['contact_email'])->send(
                            new \App\Mail\ProviderThankYouMail($validated['contact_person'])
                        );
                    }
                } catch (\Throwable $e) {
                    Log::warning('Email sending failed: ' . $e->getMessage());
                }
            }


            return response()->json([
                'message' => 'Provider request submitted successfully.',
                'data' => $providerRequest,
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $ve) {
            Log::error('Validation Error:', $ve->errors());
            return response()->json(['errors' => $ve->errors()], 422);

        } catch (\Exception $e) {
            Log::error('General Error: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong. Please try again.'], 500);
        }
    }
}
