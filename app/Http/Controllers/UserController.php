<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Create a new User.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($user = User::find($request->json('id'))) {
            return response()->json($user, Response::HTTP_CONFLICT);
        }

        $user = User::create($request->all());

        return response()->json($user, Response::HTTP_CREATED);
    }

    /**
     * Remove an User.
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        if (! User::destroy($id)) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }

        return response()->json();
    }
}
