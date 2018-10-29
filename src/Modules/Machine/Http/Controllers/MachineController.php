<?php

namespace Modules\Machine\Http\Controllers;

use Foundation\Abstracts\Controller\Controller;
use Foundation\Responses\ApiResponse;
use Illuminate\Http\Request;
use Modules\Machine\Contracts\MachineServiceContract;
use Modules\Machine\Transformer\MachineTransformer;

class MachineController extends Controller
{
    /**
     * @var MachineServiceContract
     */
    protected $service;

    /**
     * MachineController constructor.
     *
     * @param $service
     */
    public function __construct(MachineServiceContract $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MachineTransformer::collection($this->service->getByUserId(get_authenticated_user_id()));
    }

    /**
     * Store a newly created machine in storage.
     */
    public function store(Request $request)
    {
        $machine = $this->service->create($this->injectUserId($request));

        return MachineTransformer::resource($machine);
    }

    /**
     * Update a machine.
     *
     * @param Request $request
     */
    public function update(Request $request, $id)
    {
        $machine = $this->service->find($id);

        $this->isOwner($machine);

        $machine = $this->service->update($id, $request->toArray());

        return MachineTransformer::resource($machine);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $machine = $this->service->find($id);

        $this->isOwner($machine);

        return MachineTransformer::resource($machine);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $machine = $this->service->find($id);

        $this->isOwner($machine);

        $this->service->delete($machine);

        return ApiResponse::deleted();
    }
}
