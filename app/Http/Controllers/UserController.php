<?php

namespace App\Http\Controllers;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Create a new User.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response('User created');
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
        return response('User removed:' . $id);
    }
}
