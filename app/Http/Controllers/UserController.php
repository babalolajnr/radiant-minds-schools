<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('users', compact('users'));
    }

    /**
     * Verify a new user.
     *
     * @return Redirect
     */
    public function verify(User $user)
    {
        $this->authorize('verify', $user);

        $user->is_verified = true;
        $user->status = 'active';

        $user->save();

        return back()->with('success', 'User Verified');
    }

    /**
     * Toggle the user status between active and inactive states.
     *
     * @param  \App\Models\User  $user
     * @return Redirect
     */
    public function toggleStatus(User $user)
    {
        $this->authorize('toggleStatus', $user);

        if ($user->isActive()) {
            $user->is_active = false;

            //set action to deactivated
            $action = 'deactivated';
        } else {
            $user->is_active = true;

            //set action to activated
            $action = 'activated';
        }

        $user->save();

        return back()->with('success', 'User ' . $action . '!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);
        return view('showUser', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user)]
        ]);

        $user->update($data);

        return redirect()->back()->with('success', 'User updated!');
    }

    /**
     * Update password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request, User $user)
    {
        $this->authorize('view', $user);

        $data = $request->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'confirmed', 'min:8']
        ]);

        //if password does not match the current password
        if (!Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages(['current_password' => ['Password does not match current password']]);
        }

        $user->password = bcrypt($data['new_password']);
        $user->save();

        return redirect()->back()->with('success', 'Password updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted!');
    }
}
