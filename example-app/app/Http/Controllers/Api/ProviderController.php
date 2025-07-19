<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProviderLocation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function updateLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'is_online' => 'required|boolean',
        ]);

        $location = ProviderLocation::updateOrCreate(
            ['user_id' => Auth::id()],
            ['lat' => $request->lat, 'lng' => $request->lng, 'is_online' => $request->is_online]
        );

        return response()->json(['status' => 'updated']);
    }

    public function getNearbyProviders(Request $request)
    {
        $lat = $request->query('lat');
        $lng = $request->query('lng');

        $providers = DB::table('provider_locations')
            ->join('users', 'users.id', '=', 'provider_locations.user_id')
            ->where('is_online', true)
            ->select('users.id', 'users.name', 'provider_locations.lat', 'provider_locations.lng')
            ->get();

        $nearby = $providers->filter(function ($provider) use ($lat, $lng) {
            $distance = sqrt(pow($provider->lat - $lat, 2) + pow($provider->lng - $lng, 2));
            return $distance <= 0.01; // فاصله تقریبی
        })->values();

        return response()->json([
            'status' => 'success',
            'providers' => $nearby
        ]);
    }
}