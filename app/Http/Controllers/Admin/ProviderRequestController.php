<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProviderRequestDataTable;
use App\Http\Controllers\Controller;
use App\Mail\ProviderWelcomeMailAR;
use App\Models\ProviderRequest;
use App\Mail\ProviderWelcomeMail;
use Illuminate\Support\Facades\Mail;

class ProviderRequestController extends Controller
{
    public function index(ProviderRequestDataTable $dataTable)
    {
        return $dataTable->render('admins.providers.provider-requests.index');
    }

    public function toggleAccepted($id)
    {
        $provider = ProviderRequest::findOrFail($id);
        $provider->accepted = !$provider->accepted;
        $provider->save();

        return back();
    }
    public function toggleSigned($id)
    {
        $provider = ProviderRequest::findOrFail($id);
        $provider->signed = !$provider->signed;
        $provider->save();

        return back();
    }
    public function toggleSubscribed($id)
    {
        $provider = ProviderRequest::findOrFail($id);
        $provider->subscribed = !$provider->subscribed;
        $provider->save();

        return back();
    }

    public function destroy($id)
    {
        $provider = ProviderRequest::findOrFail($id);
        $provider->delete();

        return redirect()->back()->with('success', 'Provider request deleted successfully.');
    }

    public function streamLicence($id)
    {
        $provider = ProviderRequest::findOrFail($id);

        if (!$provider->licence || !file_exists(public_path($provider->licence))) {
            abort(404, 'Licence file not found.');
        }

        $filePath = public_path($provider->licence);

        return response()->file($filePath);
    }


    public function send($id)
    {
        $provider = ProviderRequest::findOrFail($id);

        if (empty($provider->contact_email)) {
            return back()->with('error', 'Provider email is missing.');
        }

        $attachmentPath = 'https://hpower.ae/agreement/' . $provider->uid;

        Mail::to($provider->contact_email)->send(new ProviderWelcomeMail($provider, $attachmentPath));
        Mail::to($provider->contact_email)->send(new ProviderWelcomeMailAR($provider, $attachmentPath));

        return back()->with('success', 'Email sent successfully.');
    }


    public function agreement($id)
    {
        return view('admins.providers.provider-requests.agreement');
    }
}
