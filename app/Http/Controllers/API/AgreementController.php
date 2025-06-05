<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProviderRequest;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Mail\AgreementSignedMail;
use App\Mail\AgreementSignedMailAR;
use App\Models\Agreement;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AgreementController extends Controller
{
    use ImageHandler;

    public function index($uid)
    {
        // $prequest = ProviderRequest::with('agreement')->where('uid', $uid)->first();
        $agreement = Agreement::where('uid', $uid)->first();

        // $prequest = ProviderRequest::with('agreement')
        //     ->whereHas('agreement', function ($query) use ($uid) {
        //         $query->where('uid', $uid);
        //     })
        //     ->first();

        if (!$agreement) {
            return response()->json(['error' => 'Agreement not found'], 404);
        }

        return response()->json([
            'redirect' => $agreement?->signed == 1,
            'id' => $agreement?->id,
            'uid' => $uid,
            'legal_business_name' => $agreement?->name ?? $agreement->prequest?->company_name,
            'trade_license_number' => $agreement?->license_number ?? 'N/A',
            'company_address' => $agreement?->address ?? 'N/A',
            'contact_email' => $agreement?->email ?? $agreement->prequest?->contact_email,
            'contact_phone_number' => $agreement?->phone ?? $agreement->prequest?->phone_number,
            'commission_agreed' => $agreement?->commission . '%' ?? '0%',
            'signature' => $agreement?->signature ? asset($agreement?->signature) : null,
        ]);
    }



    public function store(Request $request, $uid)
    {
        // $prequest = ProviderRequest::with('agreement')->where('uid', $uid)->first();

        // $prequest = ProviderRequest::with('agreement')
        //     ->whereHas('agreement', function ($query) use ($uid) {
        //         $query->where('uid', $uid);
        //     })
        //     ->first();

        $agreement = Agreement::where('uid', $uid)->first();

        if ($agreement) {
            $imageData = $request->input('eSignture');
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageName = 'signature_' . time() . '.png';

            $agreement->signed = 1;
            $agreement->terms = $request->terms == 'true' ? true : false;
            $agreement->signature = 'storage/uploads/' . $imageName;

            $agreement->save();

            Storage::disk('public')->put('uploads/' . $imageName, base64_decode($imageData));

            $name = $agreement?->name ?? $agreement->prequest?->contact_person;
            $email = $agreement?->email ?? $agreement->prequest?->contact_email;
            
            $attachmentPath = 'https://hpower.ae/' . $request->lang . '/agreement/' . $agreement->uid . '/details';

            if ($request->lang == 'ar') {
                Mail::to($email)->send(new AgreementSignedMailAR($name, $attachmentPath));
            } else {
                Mail::to($email)->send(new AgreementSignedMail($name, $attachmentPath));
            }

            return response()->json([
                'status' => 'success',
                'data' => $agreement,
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
            ], 404);
        }
    }
}
