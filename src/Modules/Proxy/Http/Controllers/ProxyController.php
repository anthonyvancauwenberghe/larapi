<?php

namespace Modules\Proxy\Http\Controllers;

use Foundation\Abstracts\Controller\Controller;
use Foundation\Responses\ApiResponse;
use Illuminate\Http\Request;
use Modules\Proxy\Contracts\ProxyServiceContract;
use Modules\Proxy\Transformers\ProxyTransformer;

class ProxyController extends Controller
{
    /**
     * @var ProxyServiceContract
     */
    protected $service;

    /**
     * ProxyController constructor.
     *
     * @param $service
     */
    public function __construct(ProxyServiceContract $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ProxyTransformer::collection($this->service->getByUserId(get_authenticated_user_id()));
    }

    /**
     * Store a newly created Proxy in storage.
     */
    public function store(Request $request)
    {
        $proxy = $this->service->create($this->injectUserId($request));

        return ProxyTransformer::resource($proxy);
    }

    /**
     * Update a Proxy.
     *
     * @param Request $request
     */
    public function update(Request $request, $id)
    {
        $proxy = $this->service->find($id);

        $this->exists($proxy);
        $this->hasAccess($proxy);
        $proxy = $this->service->update($id, $request->toArray());

        return ProxyTransformer::resource($proxy);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $proxy = $this->service->find($id);

        $this->exists($proxy);
        $this->hasAccess($proxy);

        return ProxyTransformer::resource($proxy);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $proxy = $this->service->find($id);

        $this->exists($proxy);
        $this->hasAccess($proxy);

        $this->service->delete($proxy);

        return ApiResponse::deleted();
    }
}
