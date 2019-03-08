<?php

namespace Modules\Schedule\Http\Controllers;

use Foundation\Abstracts\Controller\Controller;
use Foundation\Responses\ApiResponse;
use Illuminate\Http\Request;
use Modules\Schedule\Contracts\ScheduleServiceContract;
use Modules\Schedule\Transformers\ScheduleTransformer;

class ScheduleController extends Controller
{
    /**
     * @var ScheduleServiceContract
     */
    protected $service;

    /**
     * ScheduleController constructor.
     *
     * @param $service
     */
    public function __construct(ScheduleServiceContract $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ScheduleTransformer::collection(
            $this->service->getByUserId(get_authenticated_user_id())
        );
    }

    /**
     * Store a newly created Schedule in storage.
     */
    public function store(Request $request)
    {
        $schedule = $this->service->create($this->injectUserId($request));

        return ScheduleTransformer::resource($schedule);
    }

    /**
     * Update a Schedule.
     *
     * @param Request $request
     */
    public function update(Request $request, $id)
    {
        $schedule = $this->service->find($id);

        $this->exists($schedule);
        $this->hasAccess($schedule);
        $schedule = $this->service->update($id, $request->toArray());

        return ScheduleTransformer::resource($schedule);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $schedule = $this->service->find($id);

        $this->exists($schedule);
        $this->hasAccess($schedule);

        return ScheduleTransformer::resource($schedule);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedule = $this->service->find($id);

        $this->exists($schedule);
        $this->hasAccess($schedule);

        $this->service->delete($schedule);

        return ApiResponse::deleted();
    }
}
