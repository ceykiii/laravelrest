<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequst;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource; 
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $offset = $request->has('offset') ? $request->query('offset') : 0;
        $limit = $request->has('limit') ? $request->query('limit') : 10;

        $qb = User::query();

        if ($request->has('q'))
            $qb->where('name', 'like', '%' . $request->query('q') . '%');

        if ($request->has('sortBy'))
            $qb->orderBy($request->query('sortBy'), $request->query('sort', 'DESC'));

        $data = $qb->offset($offset)->limit($limit)->get();
        return response($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequst $request)
    {
        // 
        $input = $request->all();  
      
        $userObject = new User;
        $userObject->name = $input["name"];
        $userObject->email = $input["email"];
        $userObject->email_verified_at = now();
        $userObject->password = bcrypt($input["password"]);
        $userObject->save();
        return response([
            'data' => $userObject,
            'message' => 'User Created'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // 
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save(); 
        return response([
            'data' => $user,
            'message' => "User Update"
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // 
        $user->delete(); 
        return response([
            'message' => 'User Deleted'
        ],200);
    }

    public function custom(){
        //$user = User::find(2); 
        //return new UserResource($user);

        $users = User::all();

        //return UserResource::collection($users);

        //return new UserCollection($users);

        return UserResource::collection($users)->additional([
            'meta' => [
                'total_users' => $users->count(),
                'custom' => 'value'
            ]
        ]);
    }
}
