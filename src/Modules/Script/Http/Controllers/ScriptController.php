<?php

namespace Modules\Script\Http\Controllers;

use Foundation\Abstracts\Controller\Controller;
use Foundation\Responses\ApiResponse;
use Modules\Script\Dtos\CreateScriptDto;
use Modules\Script\Http\Requests\CreateScriptRequest;
use Modules\Script\Http\Requests\DeleteScriptRequest;
use Modules\Script\Http\Requests\FindScriptRequest;
use Modules\Script\Http\Requests\IndexScriptRequest;
use Modules\Script\Http\Requests\UpdateScriptRequest;
use Modules\Script\Contracts\ScriptServiceContract;
use Modules\Script\Transformers\ScriptTransformer;

class ScriptController extends Controller
{
    /**
     * @var ScriptServiceContract
     */
    protected $service;

    /**
     * ScriptController constructor.
     *
     * @param $service
     */
    public function __construct(ScriptServiceContract $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexScriptRequest $request)
    {
        return ScriptTransformer::collection($this->service->getByAuthorId(get_authenticated_user_id()));
    }

    /**
     * Store a newly created Script in storage.
     */
    public function store(CreateScriptRequest $request)
    {
        $script = $this->service->create(new CreateScriptDto($request->all()));
        return ScriptTransformer::resource($script);
    }

    /**
     * Update a Script.
     *
     * @param UpdateScriptRequest $request
     * @param $id
     */
    public function update(UpdateScriptRequest $request, $id)
    {
        $script = $this->service->resolve($id);

        $this->exists($script);
        $this->hasAccess($script);
        $script = $this->service->update($id, $request->toArray());

        return ScriptTransformer::resource($script);
    }

    /**
     * Show the specified resource.
     *
     * @param FindScriptRequest $request
     * @param $id
     */
    public function show(FindScriptRequest $request ,$id)
    {
        $script = $this->service->resolve($id);

        $this->exists($script);
        $this->hasAccess($script);

        return ScriptTransformer::resource($script);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteScriptRequest $request, $id)
    {
        $script = $this->service->resolve($id);

        $this->exists($script);
        $this->hasAccess($script);

        $this->service->delete($script);

        return ApiResponse::deleted();
    }
}
