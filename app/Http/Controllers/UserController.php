<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
     * @return \Illuminate\Http\Response
     */
    public function verify(User $user)
    {
        $this->authorize('verify', $user);

        $user->is_verified = true;
        $user->status = 'active';

        $user->save();

        return back()->with('success', 'User Verified');
    }

    public function toggleStatus(User $user)
    {
        $this->authorize('toggleStatus', $user);

        if ($user->status == 'active') {
            $user->status = 'inactive';
            $action = 'deactivated';
        } else {
            $user->status = 'active';
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
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
