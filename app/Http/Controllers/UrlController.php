<?php

namespace App\Http\Controllers;

class UrlController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Try to find a short url and redirect if found.
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectFromId($id)
    {
        return response('Redirect to : ' . $id);
    }

    /**
     * Display details from a specific url id.
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        return response('Show info : ' . $id);
    }

    /**
     * Given a specific user, create a new short url.
     *
     * @param $userId
     *
     * @return \Illuminate\Http\Response
     */
    public function create($userId)
    {
        return response('Create new Url for user : ' . $userId);
    }

    /**
     * Remove a short url.
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function remove($id)
    {
        return response('Remove : ' . $id);
    }

    /**
     * Show statistics from the url service.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics()
    {
        return response('Statistics');
    }

    /**
     * Given a user, show statistics from their urls.
     *
     * @param $userId
     *
     * @return \Illuminate\Http\Response
     */
    public function statisticsByUser($userId)
    {
        return response('Statistics for user: ' . $userId);
    }
}
