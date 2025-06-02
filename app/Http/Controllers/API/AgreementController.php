<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AgreementController extends Controller
{
    public function index($uid)
    {
        return collect([
            'uid' => $uid,
            'legal_business_name' => 'TechNova LLC',
            'trade_license_number' => 'TLN-56789-DXB',
            'company_address' => 'Office 210, Silicon Oasis, Dubai, UAE',
            'contact_email' => 'info@technova.com',
            'contact_phone_number' => '971 50 123 4567',
            'commission_agreed' => '7%',
        ]);
    }

    public function store(Request $request, $uid)
    {
        Log::info($request->all());

        $provider = $uid === '123456';

        if ($provider) {
            return response()->json([
                'status' => 'success',
            ], 201);
        } else {
            return response()->json([
                'status' => 'error',
            ], 404);
        }
    }
}
