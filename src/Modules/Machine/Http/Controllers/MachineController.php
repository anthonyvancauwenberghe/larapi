<?php

namespace Modules\Machine\Http\Controllers;

use Foundation\Abstracts\Controller\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Machine\Contracts\MachineServiceContract;
use Modules\Machine\Entities\Machine;
use Modules\Machine\Resources\MachineResource;
use Modules\Machine\Services\MachineService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MachineController extends Controller
{

    /**
     * @var MachineService
     */
    protected $service;

    /**
     * MachineController constructor.
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
        $machines = get_authenticated_user()->machines;
        return MachineResource::collection($machines);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $this->authorize('create');
        return \response()->json($this->service->create($request->toArray()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $machine = $this->service->find($id);
        $this->authorize('update', $machine);
        $machine = $this->service->update($id,$request->toArray());
        return \response()->json($machine);
    }

    /**
     * Show the specified resource.
     *
     * @return MachineResource
     */
    public function show($id)
    {
        $machine = Machine::find($id);

        if ($machine === null) {
            throw new NotFoundHttpException();
        }

        $this->authorize('access', $machine);

        return new MachineResource($machine);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $machine = Machine::find($id);

        if ($machine === null) {
            throw new NotFoundHttpException();
        }

        $this->authorize('delete', $machine);
        return \response()->json(['deleted']);
    }
}
