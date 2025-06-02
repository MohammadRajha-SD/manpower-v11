<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProviderRequest;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Mail\AgreementSignedMail;
use App\Mail\AgreementSignedMailAR;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AgreementController extends Controller
{
    use ImageHandler;

    public function index($uid)
    {
        $prequest = ProviderRequest::with('agreement')->where('uid', $uid)->first();

        if (!$prequest) {
            return response()->json(['error' => 'Request not found'], 404);
        }

        return response()->json([
            'redirect' => $prequest->signed == 1 || ($prequest->agreement && $prequest->agreement?->signed == 1),
            'uid' => $uid,
            'legal_business_name' => $prequest->agreement?->name ?? $prequest->company_name,
            'trade_license_number' => $prequest->agreement?->license_number ?? 'N/A',
            'company_address' => $prequest->agreement?->address ?? 'N/A',
            'contact_email' => $prequest->agreement?->email ?? $prequest->contact_email,
            'contact_phone_number' => $prequest->agreement?->phone ?? $prequest->phone_number,
            'commission_agreed' => $prequest->agreement?->commission . '%' ?? '0%',
            'signature' => $prequest->agreement?->signature ? asset($prequest->agreement?->signature) : null,
        ]);
    }



    public function store(Request $request, $uid)
    {
        $prequest = ProviderRequest::with('agreement')->where('uid', $uid)->first();

        if ($prequest) {
            $imageData = $request->input('eSignture');
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = 'signature_' . time() . '.png';

            $prequest->agreement->signed = 1;
            $prequest->agreement->terms = $request->terms == 'true' ? true : false;
            $prequest->agreement->signature = 'storage/uploads/' . $imageName;

            $prequest->agreement->save();

            Storage::disk('public')->put('uploads/' . $imageName, base64_decode($imageData));

            $name = $prequest->agreement?->name ?? $prequest->contact_person;
            $email = $prequest->agreement?->email ?? $prequest->contact_email;
            $id = $prequest->agreement?->id;

            if ($request->lang == 'ar') {
                Mail::to($email)->send(new AgreementSignedMailAR($name, $id));
            } else {
                Mail::to($email)->send(new AgreementSignedMail($name, $id));
            }

            return response()->json([
                'status' => 'success',
                'data' => $prequest,
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
            ], 404);
        }
    }
}
