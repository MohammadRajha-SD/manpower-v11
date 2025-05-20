<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProviderRequestDataTable;
use App\Http\Controllers\Controller;
use App\Models\ProviderRequest;
use Barryvdh\DomPDF\Facade\Pdf as PDF;



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
}
