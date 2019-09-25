<?php

namespace App\Http\Controllers;

use App\Jobs\UrlWasHit;
use App\Repositories\UrlRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Psr\Log\LoggerInterface;

class UrlController extends Controller
{
    /**
     * @var UrlRepository
     */
    private $repository;

    /**
     * Create a new controller instance.
     *
     * @param UrlRepository $repository
     */
    public function __construct(UrlRepository $repository)
    {
        $this->repository = $repository;
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
        if (is_null($url = $this->repository->findBySlug($id))){
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }

        $this->dispatch(new UrlWasHit($url));

        return redirect($url->url, Response::HTTP_MOVED_PERMANENTLY);
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
        if (is_null($url = $this->repository->findBySlug($id))){
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }

        return response()->json($this->repository->presentUrlStats($url));
    }

    /**
     * Given a specific user, create a new short url.
     *
     * @param $userId
     * @param Request $request
     *
     * @return Response
     */
    public function create(Request $request, $userId)
    {
        $url = $request->json('url');
        if (false === $model = $this->repository->createUrl($url, $userId)) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }

        return response()
            ->json($this->repository->presentUrlStats($model), Response::HTTP_CREATED);
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
        if (! $this->repository->removeBySlug($id)) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }

        return response(null);
    }

    /**
     * Show statistics from the url service.
     *
     * @return \Illuminate\Http\Response
     */
    public function statistics(LoggerInterface $logger)
    {
        $logger->info('test');
        return response()->json($this->repository->reportStatistics());
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
        if (false === $report = $this->repository->reportStatisticsByUser($userId)) {
            return response()->json(null, Response::HTTP_NOT_FOUND);
        }

        return response()->json($report);
    }
}
