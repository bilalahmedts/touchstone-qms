<?php

namespace App\Http\Controllers\Users;

use App\Models\Role;
use App\Models\User;

use App\Models\Campaign;
use App\Traits\UserTrait;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    use UserTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $users = User::when($request, function ($query, $request) {
            $query->search($request);
        })->with('roles', 'campaign', 'supervisor')->sortable()->orderBy('id', 'asc')->paginate(15);

        $campaigns = Campaign::where('status', 1)->orderBy('name', 'asc')->get();

        return view('user.index')->with(compact('users', 'campaigns'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::where('id', '>', 1)->orderBy('name', 'asc')->get();
        $campaigns = Campaign::orderBy('name', 'asc')->get();
        return view('user.edit')->with(compact('user', 'roles', 'campaigns'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {

        $roles = explode(',', $request->role);

        $user->syncRoles($roles);

        $user->update($request->except("role"));

        return redirect()->route('users.index')->with('success', 'User role is changed successfully!');
    }


}
