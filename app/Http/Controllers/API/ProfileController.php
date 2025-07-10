<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Traits\ImageHandler;
use App\Models\UserAddress;

class ProfileController extends Controller
{
    use ImageHandler;

    public function updateProfileImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'userId' => 'required|exists:users,id',
        ]);

        $user = User::with('image')->findOrFail($request->userId);

        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $path = $this->uploadImage($image, 'uploads');

            if ($user->image) {
                $user->image()->update(['path' => $path]);
            } else {
                $user->image()->create(['path' => $path]);
            }
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                "id" => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "phone" => $user->phone_number,
                "image" => asset('uploads/' . $user->image->path) ?? null,
            ],
        ]);
    }

    public function store(Request $request)
    {
        if (Auth::guard('provider')->check()) {
            $guard = 'provider';
            $authUser = Auth::guard('provider')->user();
        } elseif (Auth::guard()->check()) {
            $guard = 'user';
            $authUser = Auth::guard()->user();
        } else {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique($guard . 's')->ignore($authUser->id),
            ],
            'phone_number' => [
                'nullable',
                'string',
                Rule::unique($guard . 's')->ignore($authUser->id),
            ],
            'user_type' => ['required', Rule::in(['provider', 'user'])],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        $data = $validator->validated();

        unset($data['user_type']);

        // Update profile
        $authUser->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'user' => $authUser,
            'data' => [
                'email' => $authUser->email,
                'phone_number' => $authUser->phone_number,
                'username' => $authUser->username,
                'name' => $authUser->name,
                'address' => $authUser->address,
                'user_type' => $authUser->user_type,
            ],
        ]);
    }


    public function storeAddress(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'address' => 'required|string|max:255',
        ]);

        $user = $request->user();

        $user->addresses()->create([
            'address' => $request->address,
            'created_at' => now(),
        ]);

        return response()->json([
            'message_en' => 'Address added successfully.',
            'message_ar' => 'تمت إضافة العنوان بنجاح.',
            'status' => true,
        ]);
    }

    public function destroyAddress(Request $request)
    {
        $address = UserAddress::find($request->addressId);

        if (!$address) {
            return response()->json([
                'status' => false,
                'message_en' => 'Address not found.',
                'message_ar' => 'العنوان غير موجود.',
            ], 404);
        }

        $address->delete();

        return response()->json([
            'status' => true,
            'message_en' => 'Address deleted successfully.',
            'message_ar' => 'تم حذف العنوان بنجاح.',
        ]);
    }
}
