<?php

namespace Modules\Application\Http\Controllers;

use Foundation\Abstracts\Controller\Controller;
use Foundation\Responses\ApiResponse;
use Illuminate\Http\Request;
use Modules\Application\Contracts\ApplicationServiceContract;
use Modules\Application\Transformers\ApplicationTransformer;

class ApplicationController extends Controller
{
    /**
     * @var ApplicationServiceContract
     */
    protected $service;

    /**
     * ApplicationController constructor.
     *
     * @param $service
     */
    public function __construct(ApplicationServiceContract $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ApplicationTransformer::collection($this->service->getByUserId(get_authenticated_user_id()));
    }

    /**
     * Store a newly created Application in storage.
     */
    public function store(Request $request)
    {
        $Application = $this->service->create($this->injectUserId($request));

        return ApplicationTransformer::resource($Application);
    }

    /**
     * Update a Application.
     *
     * @param Request $request
     */
    public function update(Request $request, $id)
    {
        $Application = $this->service->find($id);

        $this->exists($Application);
        $this->isOwner($Application);
        $Application = $this->service->update($id, $request->toArray());

        return ApplicationTransformer::resource($Application);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $Application = $this->service->find($id);

        $this->exists($Application);
        $this->isOwner($Application);

        return ApplicationTransformer::resource($Application);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $Application = $this->service->find($id);

        $this->exists($Application);
        $this->isOwner($Application);

        $this->service->delete($Application);

        return ApiResponse::deleted();
    }
}
