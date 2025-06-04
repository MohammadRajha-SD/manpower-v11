<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\AgreementDataTable;
use App\Http\Controllers\Controller;
use App\Models\Agreement;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProviderWelcomeMailAR;
use App\Mail\ProviderWelcomeMail;

class AgreementController extends Controller
{
    public function index(AgreementDataTable $dataTable)
    {
        return $dataTable->render('admins.providers.agreements.index');
    }

    public function send($id, $lang)
    {
        $agreement = Agreement::findOrFail($id);

        if (empty($agreement->email)) {
            return back()->with('error', 'Provider email is missing.');
        }

        $attachmentPath = 'https://hpower.ae/agreement/' . $agreement->uid;

        if ($lang === 'ar') {
            Mail::to($agreement->email)->send(new ProviderWelcomeMailAR($agreement, $attachmentPath));
        } else {
            Mail::to($agreement->email)->send(new ProviderWelcomeMail($agreement, $attachmentPath));
        }

        return back()->with('success', 'Email sent successfully.');
    }
    
    public function destroy($id)
    {
        $agreement = Agreement::findOrFail($id);
        $agreement->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.agreement')])
        ], 200);
    }
}
