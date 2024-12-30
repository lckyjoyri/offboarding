<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function index()
    {
        $profile = User::where('id',auth()->id())->first();

        return view('pages.profile.index', compact('profile'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->back()->with('error', 'User doesn\'t exist');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $isDataChanged = false;

        if ($request->name !== $user->name) {
            $isDataChanged = true;
            $user->name = $request->name;
        }

        if ($request->email !== $user->email) {
            $isDataChanged = true;
            $user->email = $request->email;
        }

        if ($isDataChanged) {
            $user->save();
            return redirect()->back()->with('success', 'User updated successfully');
        }

        return redirect()->back()->with('info', 'No changes were made.');
    }

}
