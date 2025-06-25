<?php

namespace App\Http\Controllers\AAPI;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = User::with('bookings')->where('device_token', $request->api_token)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'data' => [],
            ], 404);
        }

        $bookings = $user->bookings->map(function ($booking) use ($user) {
            return [
                'user_id' => $user->id,
                'id' => $booking->id,
                'quantity' => $booking->quantity,
                'status' => $booking->booking_status_id,
                'booking_at' => $booking->booking_at,
            ];
        });

        return response()->json([
            'success' => true,
            'message' => '',
            'data' => $bookings,
        ]);
    }

}
