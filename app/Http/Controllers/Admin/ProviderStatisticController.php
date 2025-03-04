<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\ProviderStatisticDataTable;
use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProviderStatisticController extends Controller
{
    public function index(ProviderStatisticDataTable $dataTable)
    {

        $providers = Provider::withCount([
            'earnings as total_bookings_count' => function ($query) {
                $query->select(DB::raw('count(*)'));
            },
            'earnings as total_earning_sum' => function ($query) {
                $query->select(DB::raw('sum(total_earning)'));
            },
            'earnings as provider_earning_sum' => function ($query) {
                $query->select(DB::raw('sum(provider_earning)'));
            },
            'services as total_services' => function ($query) {
                $query->select(DB::raw('count(*)'));
            }
        ])->orderBy('id', 'desc')
            ->paginate(20);


        // Transform paginated results
        $statistics = $providers->mapWithKeys(function ($provider) {
            return [
                $provider->id => [
                    'id' => $provider->id,
                    'name' => $provider->name,
                    'total_earning' => $provider->total_earning_sum,
                    'total_bookings' => $provider->total_bookings_count,
                    'total_providers' => $provider->e_provider_earning_sum,
                    'total_services' => $provider->total_e_services,
                ]
            ];
        })->toArray();
        
        return $dataTable->with('statistics', $statistics)->render('admins.providers.provider-statistics.index');
    }
}
