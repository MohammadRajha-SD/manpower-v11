<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\UserDataTable;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('admins.settings.users.index');
    }

    public function create()
    {
        return view('admins.settings.users.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'username' => 'required|unique:users,username',
            'phone_number' => 'required',
            'password' => 'required|min:6',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'is_admin' => $request->is_admin,
            'password' => bcrypt($request->password),
        ]);

        // Upload image and associate with user
        if ($request->hasFile('image')) {
            $path = $user->uploadImage($request->image, 'uploads');
            $user->image()->create(['path' => $path]);
        }

        return redirect()->route('admin.users.index')->with('success', __('lang.saved_successfully', ['operator' => __('lang.user')]));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admins.settings.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'username' => 'required|unique:users,username,' . $user->id,
            'phone_number' => 'required',
            'password' => 'nullable|min:6',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'is_admin' => $request->is_admin,
            'password' => $request->password != null ? bcrypt($request->password) : $user->password,
        ]);

        // Upload image and associate with user
        if ($request->hasFile('image')) {
            $path = $user->uploadImage($request->image, 'uploads');
            $user->image()->delete();
            $user->image()->create(['path' => $path]);
        }

        return redirect()->route('admin.users.index')->with('success', __('lang.updated_successfully', ['operator' => __('lang.user')]));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->image()->exists()) {
            $user->deleteImage('uploads/' . $user->image->path);
            $user->image()->delete();
        }
        
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => trans('lang.deleted_successfully', ['operator' => trans('lang.user')])
        ], 200);
    }
}
