<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

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
        return UserResource::collection($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' =>  'required',
            'email' => 'required|email',
            'role_id' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);
        $userCreated = User::create(
            $request->only('firstname', 'lastname', 'email', 'role_id', 'phone', 'address') +
                ['password' => Hash::make(1234)]
        );
        $userCreated->image()->create(['image_url' => 'avatar.jpg']);
        return response($userCreated, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $userUpdated = User::findorfail($id);
        $request->validate([
            'email' => 'required|email',
        ]);
        $userUpdated->update(
            $request->only('firstname', 'lastname', 'email', 'role_id', 'phone', 'address')
        );
        return response(new UserResource($userUpdated), Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }


    public function currentUser()
    {
        return new UserResource(Auth::user());
    }

    public function updateInfo(Request $request)
    {
        $userUpdated = Auth::user();
        $request->validate([
            'email' => 'required|email',
        ]);
        $userUpdated->update(
            $request->only('firstname', 'lastname', 'email', 'phone', 'address')
        );
        return response(new UserResource($userUpdated), Response::HTTP_ACCEPTED);
    }


    public function updatePassword(Request $request)
    {
        $userUpdated = Auth::user();
        $userUpdated->update(
            $request->Hash::make($request->password)
        );
        return response(new UserResource($userUpdated), Response::HTTP_ACCEPTED);
    }
}
